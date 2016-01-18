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
        return $this->di->get('view')->setData('subject', $param)->render(ROOT . 'Web/views/404.html.php');
    }
}