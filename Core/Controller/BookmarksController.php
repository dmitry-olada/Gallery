<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 02.02.16
 * Time: 0:33
 */

namespace Core\Controller;


use Core\Controller;
use Core\Model\Models\Users;

class BookmarksController extends Controller
{
    public function __construct($di)
    {
        parent::__construct($di);
    }

    public function defaultAction()
    {
        $user = $this->auth->getUser();
        $layout = $this->makeLayout($user->id);

        $users = new Users();
        $bookmarks = $users->select('bookmarks', array('id', $user->id));

        $bookmarks_id = json_decode($bookmarks['bookmarks'], true);
        $bookmarks_id = explode(',', $bookmarks_id['id']);

        $myBookmarks = array();
        foreach ($bookmarks_id as $item){
            $myBookmarks = array_merge($myBookmarks, $users->selectAll(array('id', 'nick', 'avatar'), array('id', $item)));
        }

        return $this->view->set('bookmarks', $myBookmarks)->render('views::bookmarks.html', $layout);
    }
}