<?php

namespace Core;

use Core\Model\Models\Albums;
use Core\Model\Models\Photos;
use Core\Model\Models\Users;
use Core\Model\Models\Users_has_Albums;
use Lebran\Container;
use Lebran\Container\InjectableInterface;
use Lebran\Container\InjectableTrait;

class Controller implements InjectableInterface
{
    use InjectableTrait;

    const SITE_TITLE = 'Gallery';

    public function __construct($di)
    {
        $this->di = $di;
    }

    public function __get($service)
    {
        return $this->di->get($service);
    }

    public function makeLayout($param = '')
    {
        $profile_owner = true;
        $user = $this->auth->getUser();
        $main_id = $user->id;
        if(!empty($param) && $main_id !== $param){
            $profile_owner = false;
        }else{
            $param = $main_id;
        }

        $user = new Users();
        $user = $user->setConnection($this->connection)->selectObj(array('id', $param), \PDO::FETCH_OBJ);
        $user->setConnection($this->connection);

        $keys =
            [
            'nick', 'id', 'email', 'avatar', 'date', 'my_albums',
            'albums', 'photos', 'profile_owner', 'main_id', 'site_title', 'alert', 'bm_status'
            ];

        $album = new Users_has_Albums();
        $user_albums = $album->setConnection($this->connection)->selectAll('albums_id', array('users_id', $user->id));

        $my_albums = new Albums();
        $count_my_albums = $my_albums->setConnection($this->connection)->select('count(id)', array('owner', $user->id), \PDO::FETCH_NUM)[0];

        $bm_status = false;
        if(!$profile_owner) {
            $bookmarks = $user->select('bookmarks', array('id', $main_id));
            $bookmarks = json_decode($bookmarks['bookmarks'], true);
            $bookmarks = explode(',', $bookmarks);
            foreach($bookmarks as $key => $value){
                if($user->id === $value) $bm_status = true;
            }
        }

        $count_albums = 0;
        $count_photos = 0;

        $user_photos = new Photos();
        $user_photos->setConnection($this->connection);
        foreach($user_albums as $item){
            $tmp = $user_photos->select('count(id)', array('albums_id', $item['albums_id']), \PDO::FETCH_NUM);
            $count_photos += $tmp[0];
            $count_albums++;
        }

        $alert = $this->session->getFlash();

        $values =
            [
                ucfirst($user->nick), $user->id, $user->email, $user->avatar, $user->reg_date, $count_my_albums,
                $count_albums, $count_photos, $profile_owner, $main_id, Controller::SITE_TITLE, $alert, $bm_status
            ];

        return array_combine($keys, $values);
    }

    protected function redirectPost($where = ''){
        if(!$this->request->isPost()){
            $this->session->setFlash('Unknown error', 'danger');
            $this->response->redirect('/'.$where);
        }
    }

}