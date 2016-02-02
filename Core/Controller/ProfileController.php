<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 07.01.16
 * Time: 21:12
 */

namespace Core\Controller;

use Core\Controller;
use Core\Model\Models\Albums;
use Core\Model\Models\Users_has_Albums;

class ProfileController extends Controller
{
    public function __construct($di)
    {
        parent::__construct($di);
    }

    public function defaultAction($data = null)
    {
        $layout = $this->makeLayout($data);
        $album = new Albums();
        $user_albums = new Users_has_Albums();
        $user_albums = $user_albums->selectAll('albums_id', array('users_id', $layout['id']), \PDO::FETCH_NUM);
        $albums = array();
        foreach ($user_albums as $item){
            $albums = array_merge($albums, $album->selectAll($album->getColumns(), array('id', $item[0])));
            // albums[] создает в 2 вложенных массива, почему и нахуя - хз.
        }

        $albums = array('user_albums' => $albums);
        $data = array_merge_recursive($layout, $albums);

        return $this->view->render('views::profile.html', $data);
    }

}