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

class AlbumsController extends Controller implements _ControllerInterface
{
    public function __construct($di)
    {
        parent::__construct($di);
    }

    private function redirectGet($id){
        if($this->request->isGet()){
            $this->response->redirect('/albums/'.$id);
        }
    }

    //TODO: СДЕЛАТЬ СКРЫТИЕ ВСЕХ ЭЛЕМЕНТОВ ПР ОТКРЫТИИ ЛЮБОЙ ВКЛАДКИ НА ФОТКАХ. Album/changeAction().
    //TODO: Удалить audacious если не загрузиться.

    public function defaultAction($data = null)
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

        return $this->view->set('all_albums', $albums)->set('all_photos', $arr_photos)->render('views::albums.html', $layout);
    }

    public function changeAction($data)
    {
        $user = $this->auth->getUser();
        $this->redirectGet($user->id);

        //Писать ли isPost() или и так нормально?

        //Возможно ли подделать POST запрос, чтоб изменить не свои фотки?

        //TODO: Сделать редактирование инфы без перезагрузки страницы.

        $album = new Albums();
        $album = $album->selectObj(array('id', $data));

        $album->name = $this->request->get('new_name');
        $album->date = $this->request->get('new_date');
        $album->description = $this->request->get('new_description');
        (null !== $album->comments)?:$album->comments = '';
        (null !== $album->buhlikes)?:$album->buhlikes = '';

        $album->update('id', $data);
        $this->session->setFlash('Album data has been updated successfully', 'success');

        $this->response->redirect('/albums/'.$user->id);
    }

    public function photo_changeAction($data)
    {
        $user = $this->auth->getUser();
        $this->redirectGet($user->id);

        $photo = new Photos();
        $photo = $photo->selectObj(array('id', $data));

        $photo->name = $this->request->get('new_photo_name');
        $photo->update('id', $data);

        $this->session->setFlash('Photo name has been updated successfully', 'success');
        $this->response->redirect('/albums/'.$user->id);
    }

    public function photo_deleteAction($data)
    {
        $user = $this->auth->getUser();
        $this->redirectGet($user->id);

        $photo = new Photos();
        $photo->delete($data);

        $this->session->setFlash('Photo has been deleted successfully', 'success');
        $this->response->redirect('/albums/'.$user->id);
    }

    public function createAction($data = null){
        $user = $this->auth->getUser();
        $this->redirectGet($user->id);


        $album = new Albums();
        $album->name = $this->request->get('create_album_name');
        $album->date = $this->request->get('create_album_date');
        $album->description = (null === $this->request->get('create_album_description'))?'':$this->request->get('create_album_description');
        $album->owner = $user->id;
        $album->comments = '';
        $album->buhlikes = '';

        $album->insert();

        $this->session->setFlash('Album has been added successfully', 'success');
        $this->response->redirect('/albums/'.$user->id);

    }

}