<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 07.01.16
 * Time: 21:15
 */

namespace Core;

use Core\Model\Models\Users;
use Core\Model\Models\Users_has_Albums;
use Lebran\Container;
use Lebran\Container\InjectableInterface;
use Lebran\Container\InjectableTrait;

class Controller implements InjectableInterface
{
    use InjectableTrait;

    const SITE_TITLE = 'Buhlogram';

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
        if(!empty($param)){
            if($main_id !== $param) {
                $profile_owner = false;
                $user = new Users();
                $user = $user->selectObj(array('id', $param), \PDO::FETCH_OBJ);
            }
        }

        $keys =
            [
            'nick', 'id', 'email', 'avatar', 'date', 'my_albums',
            'albums', 'photos', 'profile_owner', 'main_id', 'site_title', 'alert', 'bm_status'
            ];


        $albums = new Users_has_Albums();
        $sql = "select count(u.users_id), (select count(a.id) from albums a where a.owner = ".$user->id.") from users_has_albums u where u.users_id = ".$user->id.";";
        $album = $albums->makeQuery($sql)->fetch(\PDO::FETCH_NUM);

        $bm_status = false;
        if(!$profile_owner) {
            $bookmarks = $user->select('bookmarks', array('id', $main_id));
            $bookmarks = json_decode($bookmarks['bookmarks'], true);
            $bookmarks = explode(',', $bookmarks);
            foreach($bookmarks as $key => $value){
                if($user->id === $value) $bm_status = true;
            }
        }

        $count_photos = "Disabled";

        /*
        $user_photos = new Photos();
        foreach($user_albums as $item){
            $tmp = $user_photos->select('count(id)', array('albums_id', $item['albums_id']), \PDO::FETCH_NUM);
            $count_photos += $tmp[0];
            $count_albums++;
        }
        */

        $alert = $this->session->getFlash();

        $values =
            [
                ucfirst($user->nick), $user->id, $user->email, $user->avatar, $user->reg_date, $album[1],
                $album[0], $count_photos, $profile_owner, $main_id, Controller::SITE_TITLE, $alert, $bm_status
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