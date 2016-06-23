<?php

namespace Core\Controller;

use Core\Controller;
use Core\Model\Models\Albums;
use Core\Model\Models\Comments;
use Core\Model\Models\Photos;
use Core\Model\Models\Users;
use Core\V;

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
        $photos = $photos->setConnection($this->connection)->selectAll($photos->getColumns(), array('albums_id', $data[1]));
        $photos = array('photo' => $photos);

        $albums = new Albums();
        $albums = $albums->setConnection($this->connection)->selectAll($albums->getColumns(), array('id', $data[1]));

//        $sql = "select a.id, a.name, a.description, a.available, a.date, a.buhlikes, a.owner, u.nick from albums a join users u on (u.id = a.owner) where a.id = ".$data[1].";";
//        $albums = $albums->makeQuery($sql)->fetch();

        $albums[0]['isliked'] = false;
        $buhlikes = json_decode($albums[0]['buhlikes']);
        $buhlikes = explode(',', $buhlikes);
        $albums[0]['buhlikes'] = ($buhlikes[0] !== "")?count($buhlikes):0;
        if(array_search($layout['main_id'], $buhlikes) || $buhlikes[0] === $layout['main_id']){
            $albums[0]['isliked'] = true;
        }

        if($layout['main_id'] === $albums[0]['owner']){
            $albums[0]['nick'] = $layout['nick'];
        }else{
            $user = new Users();
            $albums[0]['nick'] = $user->setConnection($this->connection)->select('nick', array('id', $albums[0]['owner']))['nick'];
        }

        $albums = array('album' => $albums[0]);

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
        $comment->setConnection($this->connection)->insert();
    }

    public function deleteCommentAction($data)
    {
        $data = explode('.', $data);
        $user = $this->auth->getUser();

        $comment = new Comments();
        $sql = "select a.owner from albums a join comments c on (a.id = c.albums_id) where c.id = ". $data[1] .";";
        $owner = $comment->setConnection($this->connection)->makeQuery($sql)->fetch(\PDO::FETCH_NUM);

        if($data[0] === $user->id || $owner[0] === $user->id){
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
        $comments = $comments->setConnection($this->connection)->makeQuery($sql)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($comments);
    }

}