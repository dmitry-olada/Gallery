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
use Core\Model\Models\Photos;

class PhotosController extends Controller implements _ControllerInterface
{
    public function __construct($di)
    {
        parent::__construct($di);
    }

    public function DefaultAction($data = null)
    {
        //TODO: Хозяин альбома, взять с бд. Сделать юзера.
        $data = explode('.', $data);
        $layout = $this->makeLayout($data[0]);
        $photos = new Photos();
        $photos = $photos->selectAll($photos->getColumns(), array('albums_id', $data[1]));
        $photos = array('photo' => $photos);
        $albums = new Albums();
        $albums = $albums->selectAll($albums->getColumns(), array('id', $data[1]));
        $albums = array('album' => $albums);
        $data = array_merge_recursive($layout, $albums, $photos);

        return $this->view->render('views::gallery.html', $data);
    }

    public function Edit($id, $number)
    {

    }
}