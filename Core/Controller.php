<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 07.01.16
 * Time: 21:15
 */

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
        if(!empty($param)){
            if($main_id !== $param) {
                $profile_owner = false;
                $user = new Users();
                $user = $user->selectObj(array('id', $param), \PDO::FETCH_OBJ);
            }
        }

        $keys =
            [
            'nick', 'id', 'email', 'avatar', 'date', 'bookmarks', 'my_albums',
            'albums', 'photos', 'profile_owner', 'main_id', 'site_title', 'alert'
            ];

        $album = new Users_has_Albums();
        $user_albums = $album->selectAll('albums_id', array('users_id', $user->id));

        $my_albums = new Albums();
        $count_my_albums = $my_albums->select('count(id)', array('owner', $user->id), \PDO::FETCH_NUM);

        $user_photos = new Photos();

        $user_bookmarks = 0;
        $count_albums = 0;
        $count_photos = 0;

        foreach($user_albums as $item){
            $tmp = $user_photos->select('count(id)', array('albums_id', $item['albums_id']), \PDO::FETCH_NUM);
            $count_photos += $tmp[0];
            $count_albums++;
        }


        $alert = $this->session->getFlash();

        $values =
            [
                ucfirst($user->nick), $user->id, $user->email, $user->avatar, $user->reg_date, $user_bookmarks, $count_my_albums[0],
                $count_albums, $count_photos, $profile_owner, $main_id, Controller::SITE_TITLE, $alert
            ];

        return array_combine($keys, $values);
    }

}