<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 29.01.16
 * Time: 13:35
 */

namespace Core\Controller;


use Core\Controller;
use Core\Model\Models\Albums;
use Core\Model\Models\Photos;

class AlbumsController extends Controller
{
    public function __construct($di)
    {
        parent::__construct($di);
    }

    private function redirectPost(){
        if(!$this->request->isPost()){
            $this->response->redirect('/albums');
        }
    }

    //TODO: СДЕЛАТЬ СКРЫТИЕ ВСЕХ ЭЛЕМЕНТОВ ПР ОТКРЫТИИ ЛЮБОЙ ВКЛАДКИ НА ФОТКАХ. Album/changeAction().
    //TODO: Удалить audacious если не загрузиться.

    public function defaultAction()
    {
        $user = $this->auth->getUser();
        $layout = $this->makeLayout($user->id);

        $albums = new Albums();
        $albums = $albums->selectAll(array('id', 'name', 'description', 'date'), array('owner', $user->id));

        $photos = new Photos();
        $arr_photos = array();

        foreach ($albums as $item){
            $arr_photos[] = $photos->selectAll($photos->getColumns(), array('albums_id', $item['id']));
        }

        if($this->session->has('collapse')){
            $collapse = $this->session->get('collapse');
            $this->session->remove('collapse');
            $this->view->set('collapse', $collapse);
        }else{
            $this->view->set('collapse', null);
        }

        return $this->view->set('all_albums', $albums)->set('all_photos', $arr_photos)->render('views::albums.html', $layout);
    }

    public function changeAction($data)
    {
        $this->redirectPost();

        $data = explode('.', $data);

        //Возможно ли подделать POST запрос, чтоб изменить не свои фотки?

        //TODO: Сделать редактирование инфы без перезагрузки страницы.

        $album = new Albums();
        $album = $album->selectObj(array('id', $data[0]));

        $album->name = $this->request->get('new_name');
        $album->date = $this->request->get('new_date');
        $album->description = $this->request->get('new_description');
        (null !== $album->comments)?:$album->comments = '';
        (null !== $album->buhlikes)?:$album->buhlikes = '';

        $album->update('id', $data[0]);

        $this->session->set('collapse', $data[1]);
        $this->session->setFlash('Album data has been updated successfully', 'success');

        $this->response->redirect('/albums');
    }

    public function photo_changeAction($data)
    {
        $this->redirectPost();

        $data = explode('.', $data);

        $photo = new Photos();
        $photo = $photo->selectObj(array('id', $data[0]));
        $photo->name = $this->request->get('new_photo_name');

        $photo->update('id', $data[0]);

        $this->session->set('collapse', $data[1]);
        $this->session->setFlash('Photo name has been updated successfully', 'success');

        $this->response->redirect('/albums');
    }

    public function photo_deleteAction($data)
    {
        $this->redirectPost();

        $data = explode('.', $data);

        $photo = new Photos();
        $photo->delete($data[0]);

        $this->session->set('collapse', $data[1]);
        $this->session->setFlash('Photo has been deleted successfully', 'success');
        $this->response->redirect('/albums');
    }

    public function createAction(){

        $user = $this->auth->getUser();
        $this->redirectPost();

        $album = new Albums();
        $album->name = $this->request->get('create_album_name');
        $album->date = $this->request->get('create_album_date');
        $album->description = (null === $this->request->get('create_album_description'))?'':$this->request->get('create_album_description');
        $album->owner = $user->id;
        $album->comments = '';
        $album->buhlikes = '';

        $album->insert();

        $this->session->setFlash('Album has been added successfully', 'success');
        $this->response->redirect('/albums');

    }

}