<?php

namespace Core\Controller;

use Core\Controller;
use Core\Model\Models\Albums;
use Core\Model\Models\Photos;
use Core\Model\Models\Users;
use Core\Model\Models\Users_has_Albums;
use Core\V;

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
        $albums = $albums->setConnection($this->connection)->selectAll(array('id', 'name', 'description', 'date', 'available', 'owner'), array('owner', $user->id));

        $photos = new Photos();
        $photos->setConnection($this->connection);
        $user_albums = new Users_has_Albums();
        $user_albums->setConnection($this->connection);

        $arr_photos = [];

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

        $users = new Users();
        $arr_users = $users->setConnection($this->connection)->selectAll(array('id', 'nick'));

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
        $album = $album->setConnection($this->connection)->selectObj(array('id', $data[0]));

        $album->setConnection($this->connection);
        $album->name = $this->request->get('new_name');
        $album->date = $this->request->get('new_date');
        $album->description = $this->request->get('new_description');
        //(null !== $album->comments)?:$album->comments = '';
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
        $photo = $photo->setConnection($this->connection)->selectObj(array('id', $data), \PDO::FETCH_OBJ);

        $photo->setConnection($this->connection);
        $photo->name = $this->request->get('data');
        $photo->update('id', $data);
    }

    public function photo_deleteAction()
    {
        $this->redirectPost('albums');

        $data = explode('.', $this->request->get('data'));

        $photo = new Photos();
        $photo->setConnection($this->connection)->delete($data[0]);

        $this->session->set('collapse', $data[1]);
        $this->session->setFlash('Photo has been deleted', 'warning');
    }

    public function createAction()
    {
        $this->redirectPost('albums');

        $user = $this->auth->getUser();

        $album = new Albums();
        $album->setConnection($this->connection);
        $album->name = htmlspecialchars($this->request->get('create_album_name'));
        $album->date = $this->request->get('create_album_date');
        $description = $this->request->get('create_album_description_1');
        $description = isset($description)? $this->request->get('create_album_description_1'):$this->request->get('create_album_description_2');
        $album->description = (isset($description))?$description:'';
        $album->owner = $user->id;
        $album->comments = '';
        $album->buhlikes = '';

        $album->insert();

        $this->session->setFlash('Album has been added successfully', 'success');
        $this->response->redirect('/albums');
    }

    public function drop_albumAction($data)
    {
        $this->redirectPost('albums');
        $album = new Albums();
        $sql = "SET FOREIGN_KEY_CHECKS=0; ".
        "delete from `albums` where `id` = ".$data."; ".
        "SET FOREIGN_KEY_CHECKS=1;";

        $album->setConnection($this->connection)->makeQuery($sql);
        $this->session->setFlash('Album has been deleted', 'warning');
        $this->response->redirect('/albums');
    }

    public function addPhotoAction($data)
    {
        $this->redirectPost('albums');

        $data = explode('.', $data);
        $error = '';

        $links = $this->request->get('link_add_photo');
        $names = $this->request->get('name_add_photo');

        for($i = 0; $i<count($links); $i++){
            $date = new \DateTime(null, new \DateTimeZone('Europe/Kiev'));
            $filetype = strrchr($links[$i] ,'.');
            $filename = md5($date->format('Y-m-d H:i:s')).$filetype;
            $upload_path = DOC_ROOT.'/uploads/'.$filename;
            if(!$this->image->setImageSize($links[$i], 1920, 1080, true, $upload_path)){
                $error .= 'Can\'t upload photo: '.$links[$i].'<br/>';
                continue;
            }
            $photo = new Photos();
            $photo->setConnection($this->connection);
            $photo->link = '../uploads/'.$filename;
            $photo->name = $names[$i]?$names[$i]:'';
            $photo->albums_id = $data[0];
            $photo->insert();
        }

        $this->session->set('collapse', $data[1]);

        if(empty($error)){
            $this->session->setFlash('Photos have uploaded successfully', 'success');
        }else {
            $this->session->setFlash($error, 'danger');
        }
        $this->response->redirect('/albums');
    }

    public function buhlikeAction($data)
    {
        $this->redirectPost('albums');

        $user = $this->auth->getUser();

        $album = new Albums();
        $album = $album->setConnection($this->connection)->selectObj(array('id', $data), \PDO::FETCH_OBJ);

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
        $album->setConnection($this->connection)->update('id', $data);

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
        $album = $album->setConnection($this->connection)->selectObj(array('id', $data), \PDO::FETCH_OBJ);
        $album->available = json_encode(trim($json, ','));
        $album->setConnection($this->connection)->update('id', $data);
        $this->session->setFlash($album->name.' permissions has been updated', 'success');
        $this->response->redirect('/albums');
    }

    public function shareAction($data)
    {
        $this->redirectPost('albums');

        $curr_user = $this->auth->getUser();

        $user_has_albums = new Users_has_Albums();
        $user_has_albums->setConnection($this->connection);

        $user = new Users();
        $users = $user->setConnection($this->connection)->selectAll(array('id'));

        $id = $this->request->get('js_data');

        foreach($users as $item){
            if($id === $curr_user->id) continue;
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
        $users_h_albums->setConnection($this->connection)->makeQuery($sql);
    }

    public function uploadAction($data)
    {
        $this->redirectPost('albums');

        $data = explode('.', $data);
        $error = '';

        for($i = 0; $i < sizeof($_FILES['photos']['name']); $i++){
            $time = microtime(true);
            $micro = sprintf("%06d",($time - floor($time)) * 1000000);
            $date = new \DateTime(date('Y-m-d H:i:s.'.$micro,$time), new \DateTimeZone('Europe/Kiev'));
            $currname = $_FILES['photos']['name'][$i];
            $filetype = strrchr($currname,'.');
            $filename = md5($date->format('Y-m-d H:i:s.u')).$filetype;
            $upload_path = DOC_ROOT.'/uploads/'.$filename;
            if (move_uploaded_file($_FILES['photos']['tmp_name'][$i], $upload_path)) {
                $this->image->SetImageSize($upload_path, 1920, 1080, true);
                $photo = new Photos();
                $photo->name = substr($currname, 0, strrpos($currname, $filetype));
                $photo->link = '../uploads/'.$filename;
                $photo->albums_id = $data[0];
                $photo->setConnection($this->connection)->insert();
            }else{
                $error .= 'Photo #'.($i+1).' not uploaded with error '.$_FILES['photos']['error'][$i].'<br/> ';
            }
        }

        $this->session->set('collapse', $data[1]);
        if(empty($error)){
            $this->session->setFlash('Photos have uploaded successfully', 'success');
        }else {
            $this->session->setFlash($error, 'danger');
        }

        $this->response->redirect('/albums');
    }
}