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
use Core\Model\Models\Comments;
use Core\Model\Models\Photos;

class PhotosController extends Controller
{
    public function __construct($di)
    {
        parent::__construct($di);
    }

    public function defaultAction($data = null)
    {
        $data = explode('.', $data);
        $layout = $this->makeLayout($data[0]);

        $photos = new Photos();
        $photos = $photos->selectAll($photos->getColumns(), array('albums_id', $data[1]));
        $photos = array('photo' => $photos);

        $albums = new Albums();
        $albums = $albums->selectAll($albums->getColumns(), array('id', $data[1]));

        $albums[0]['isliked'] = false;
        $buhlikes = json_decode($albums[0]['buhlikes']);
        $buhlikes = explode(',', $buhlikes);
        $albums[0]['buhlikes'] = count($buhlikes);
        if(array_search($layout['main_id'], $buhlikes)){
            $albums[0]['isliked'] = true;
        }

        $albums = array('album' => $albums);

        $data = array_merge_recursive($layout, $albums, $photos);

        return $this->view->render('views::gallery.html', $data);
    }

    public function addCommentAction($data)
    {
        $this->redirectPost();

        if($this->request->get('data') === ''){
            return;
        }

        $user = $this->auth->getUser();

        $comment = new Comments();

        $comment->users_id = $user->id;
        $comment->albums_id = $data;
        $comment->comment = $this->request->get('data');
        $date = new \DateTime(null, new \DateTimeZone('Europe/Kiev'));
        $comment->date = $date->format('Y-m-d H:i:s');
        $comment->insert();
    }

    public function commentAction($data)
    {
        $comments = new Comments();
        $sql = "SELECT `comm`.`users_id`, `comm`.`comment`, `comm`.`date`, `usr`.`nick` FROM `comments` AS `comm` join `users` AS `usr` ON `comm`.`users_id` = `usr`.`id` WHERE `comm`.`albums_id` = ".$data." ORDER BY `comm`.`date` DESC;";
        $comments = $comments->makeQuery($sql)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($comments);
    }

}