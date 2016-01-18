<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 07.01.16
 * Time: 21:12
 */

namespace Core\Controller;

use Core\Controller;
use Core\Interfaces\ControllerInterface;
use Core\Model\Models\Friends;
use Core\Model\Models\Photos;
use Core\Model\Models\Users;

class ProfileController extends Controller implements ControllerInterface
{
    public function __construct($di)
    {
        parent::__construct($di);
    }

    public function defaultAction($param = null)
    {
        $owner = true;
        $user = $this->di->get('auth')->getUser();
        !($user->user_id == $param[1]) ?: $param = null;
        if($param != null) {
            $owner = false;
            $user = new Users();
            $user = $user->selectAll('user_id', $param);
        }
        $keys = ['nick', 'id', 'email', 'avatar', 'date', 'friends', 'photos', 'videos', 'owner'];
        $photo = new Photos();
        $friend = new Friends();
        $user_photos = $photo->select('count(photo_id)', 'owner', $user->user_id, \PDO::FETCH_ASSOC);
        $user_friends = $friend->select('count(friend_iden)', 'user_iden', $user->user_id, \PDO::FETCH_ASSOC);
        $values = [ucfirst($user->nick), $user->user_id, $user->email, $user->avatar, $user->reg_date,
            $user_friends['count(friend_iden)'], $user_photos['count(photo_id)'], 0, $owner];
        return $this->di->get('view')->setData($keys, $values)->render(ROOT.'Web/views/profile.html.php');
    }

    //TODO: СДЕЛАТЬ ОШИБКУ ЕСЛИ НЕПРАВИЛЬНЫЙ ПАРОЛЬ + РЕГИСТРАЦИЯ

    public function showUsers()
    {

    }
}