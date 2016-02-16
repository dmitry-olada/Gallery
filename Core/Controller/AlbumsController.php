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
use Core\Model\Models\Users;
use Core\Model\Models\Users_has_Albums;

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
        $albums = $albums->selectAll(array('id', 'name', 'description', 'date', 'available', 'owner'), array('owner', $user->id));



        $photos = new Photos();
        $arr_photos = array();
        $user_albums = new Users_has_Albums();
        $users = new Users();

        foreach($albums as $key => $value){
            $arr_photos[] = $photos->selectAll($photos->getColumns(), array('albums_id', $albums[$key]['id']));
            $share = $user_albums->selectAll(array('users_id'), array('albums_id', $albums[$key]['id']), \PDO::FETCH_NUM);

            if(!empty($share)){
                $arr_share = array();
                foreach($share as $item){
                    foreach($item as $new_item){
                        $arr_share[$new_item] = $new_item;
                    }
                }
                $albums[$key]['share'] = $arr_share;
            }

            if(empty($albums[$key]['available'])){
                $albums[$key]['available'] = null;
            }else{
                $arr_users = array();
                $aval = explode(',', json_decode($albums[$key]['available']));
                foreach($aval as $item){
                    $arr_users[$item] = $item;
                }
                $albums[$key]['available'] = $arr_users;
            }
        }

        $arr_users = $users->selectAll(array('id', 'nick'));

        if($this->session->has('collapse')){
            $collapse = $this->session->get('collapse');
            $this->session->remove('collapse');
            $this->view->set('collapse', $collapse);
        }else{
            $this->view->set('collapse', null);
        }

        return $this->view->set('all_albums', $albums)->set('all_photos', $arr_photos)->set('all_users', $arr_users)->render('views::albums.html', $layout);
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

    public function addPhotoAction($data)
    {
        $this->redirectPost('albums');

        $data = explode('.', $data);

        $links = $this->request->get('link_add_photo');
        $names = $this->request->get('name_add_photo');

        for($i = 0; $i<count($links); $i++){
            $photo = new Photos();
            $photo->link = $links[$i];
            $photo->name = $names[$i]?$names[$i]:'';
            $photo->albums_id = $data[0];
            $photo->insert();
        }

        $this->session->set('collapse', $data[1]);
        $this->session->setFlash('Photo has been added successfully', 'success');
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
                break;
            }
        }

        $delete?:$bm[] = $user->id;

        $album->buhlikes = json_encode(trim(implode($bm, ','), ','));
        $album->update('id', $data);

        $count = count($bm);
        if(isset($bm[0])){
            if($bm[0] === ""){
                $count--;
            }
        }

        return json_encode(array($delete?0:1, $count, $data));
    }

    public function availableAction($data)
    {
        $this->redirectPost('albums');

        $selects = $this->request->get('selects');

        $json ='';
        foreach($selects as $item){
            $json .= $item.',';
        }

        $album = new Albums();
        $album = $album->selectObj(array('id', $data), \PDO::FETCH_OBJ);
        $album->available = json_encode(trim($json, ','));
        $album->update('id', $data);
        $this->session->setFlash($album->name.' permissions has been updated', 'success');
        $this->response->redirect('/albums');
    }

    public function shareAction($data)
    {
        $this->redirectPost('albums');

        $user_has_albums = new Users_has_Albums();

        $user = new Users();
        $users = $user->selectAll(array('id'));

        $id = $this->request->get('js_data');

        foreach($users as $item){
            $values = array_search($id, $item);
            if($values){
                $user_has_albums->albums_id = $data;
                $user_has_albums->users_id = $id;
                $nick = $user->select(array('nick'), array('id', $id));
                $user_has_albums->insert();
                $string = "<div><a href='/profile/".$id."'>".$nick['nick']."</a><a href='/albums/deleteShare/".$id.".".$data."' class='share_deleter'>×&nbsp</a><div class='albums_ph_delimiter'></div></div>";
                return json_encode($string);
            }
        }
    }

    public function deleteShareAction($data)
    {
        $this->redirectPost('albums');

        $data = explode('.', $data);
        $users_h_albums = new Users_has_Albums();
        $sql = "DELETE FROM `users_has_albums` WHERE `users_id` = ".$data[0]." && `albums_id` = ".$data[1].";";
        $users_h_albums->makeQuery($sql);
    }

}