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
use Core\Model\Models\Users;

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
        $sql = "select a.id, a.name, a.description, a.available, a.date, a.buhlikes, a.owner, u.nick from albums a join users u on (u.id = a.owner) where a.id = ".$data[1].";";
        $albums = $albums->makeQuery($sql)->fetch();

        $albums['isliked'] = false;
        $buhlikes = json_decode($albums['buhlikes']);
        $buhlikes = explode(',', $buhlikes);
        $albums['buhlikes'] = ($buhlikes[0] !== "")?count($buhlikes):0;
        if(array_search($layout['main_id'], $buhlikes) || $buhlikes[0] === $layout['main_id']){
            $albums['isliked'] = true;
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

    public function deleteCommentAction($data)
    {
        $data = explode('.', $data);
        $user = $this->auth->getUser();

        if($data[0] === $user->id){
            $comment = new Comments();
            $comment->delete($data[1]);
        }else{
            $this->session->setFlash('not today =)', 'danger');
        }
        $this->response->redirect('/photos/'.substr($_SERVER['HTTP_REFERER'], strlen($_SERVER['HTTP_REFERER']) - 3));
    }

    public function commentAction($data)
    {
        $comments = new Comments();
        $sql = "select comm.id, comm.users_id, comm.comment, comm.date, usr.nick from comments comm join users usr on comm.users_id = usr.id where comm.albums_id = ".$data." order by comm.date DESC;";
        $comments = $comments->makeQuery($sql)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($comments);
    }

}