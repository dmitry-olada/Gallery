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
        $bookmarks_id = explode(',', $bookmarks_id);

        $myBookmarks = array();
        foreach ($bookmarks_id as $item){
            $myBookmarks = array_merge($myBookmarks, $users->selectAll(array('id', 'nick', 'avatar'), array('id', $item)));
        }

        return $this->view->set('bookmarks', $myBookmarks)->render('views::bookmarks.html', $layout);
    }

    public function addAction()
    {
        $this->redirectPost('bookmarks');

        $id = $this->request->get('user_id');

        $user = $this->auth->getUser();

        $users = new Users();
        $curr_user = $users->selectObj(array('id', $user->id), \PDO::FETCH_OBJ);

        $bookmarks_id = json_decode($curr_user->bookmarks, true);
        $bookmarks_id = explode(',', $bookmarks_id);

        $delete = false;

        foreach ($bookmarks_id as $key => $value){
            if((string)$id === $value){
                unset($bookmarks_id[$key]);
                $delete = true;
                break;
            }
        }
        if(!$delete){
            $bookmarks_id[] = $id;
        }
        $curr_user->bookmarks = json_encode(trim(implode($bookmarks_id, ','), ','));
        $curr_user->update('id', $user->id);
        return $delete?1:0; //без этого никак
    }
}