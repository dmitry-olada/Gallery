<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 16.01.16
 * Time: 20:18
 */

namespace Core\Controller;


use Core\Controller;

class NotFoundController extends Controller
{
    public function defaultAction($param){
        $param = (null === $param) ? 'Unknown error' : $param;
        return $this->view->render('views::404.html', array('subject' => $param));
    }
}