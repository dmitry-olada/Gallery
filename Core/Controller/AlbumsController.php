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
        $this->redirectPost('albums');

        $data = explode('.', $data);

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
        $this->redirectPost('albums');

        $photo = new Photos();
        $photo = $photo->selectObj(array('id', $data), \PDO::FETCH_OBJ);

        $photo->name = $this->request->get('data');
        $photo->update('id', $data);
    }

    public function photo_deleteAction()
    {
        $this->redirectPost('albums');

        $data = explode('.', $this->request->get('data'));

        $photo = new Photos();
        $photo->delete($data[0]);

        $this->session->set('collapse', $data[1]);
        $this->session->setFlash('Photo has been deleted', 'success');
    }

    public function createAction()
    {
        $this->redirectPost('albums');

        $user = $this->auth->getUser();

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

    public function buhlikeAction($data)
    {
        $this->redirectPost('albums');

        $user = $this->auth->getUser();

        $album = new Albums();
        $album = $album->selectObj(array('id', $data), \PDO::FETCH_OBJ);

        $bm = explode(',', json_decode($album->buhlikes));

        $delete = false;
        foreach ($bm as $key => $value){
            if($user->id === $value){
                unset($bm[$key]);
                $delete = true;
            }
        }
        if(!$delete){
            $bm[] = $user->id;
        }

        $album->buhlikes = json_encode(trim(implode($bm, ','), ','));
        $album->update('id', $data);
        return count($bm);
    }

}