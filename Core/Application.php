<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 02.01.16
 * Time: 20:28
 */

namespace Core;

use Core\Auth\Auth;
use Core\Auth\Cookie;
use Core\Auth\Session;
use Core\Controller\AuthController;
use Core\Controller\NotFoundController;
use Core\Controller\ProfileController;
use Core\Http\Request;
use Core\Http\Response;
use Lebran\Container\InjectableTrait;
use Lebran\Mvc\View;

class Application
{
    use InjectableTrait;

    public function __construct($di)
    {
        $this->di = $di;
        $this->di->set('view', new View(array(new View\Extension\Blocks())), true);
        $this->di->set('router', new Router());
        $this->di->set('request', new Request());
        $this->di->set('response', new Response());
        $this->di->set('auth', new Auth());
        $this->di->set('session', new Session());
        $this->di->set('cookie', new Cookie());
    }

    public function handle()
    {
        // Ромка. В бд нельзя добавлять значение null через queryBuilder!

        //TODO: Если после числа еще / и числа, то выкидывать notFound.

        //TODO: Переформатировать дату в бд.

        $response = $this->di->get('response');

        $components = $this->di->get('router')->execute();
        $components[0] = ($components[0] === '')?'Profile':$components[0];
        $controller = 'Core\\Controller\\' . ucfirst(strtolower($components[0])) . 'Controller';
        if (class_exists($controller)) {
            $controller = new $controller($this->di);
            unset($components[0]);
            if (empty($components[1])){
                $action = 'DefaultAction';
                $components = !empty($components[1]) ? $components[1] : '';
            }elseif(preg_match("/\\d+/", $components[1])) {                   //Пушо пхп ругается, на $components[1];
                $action = 'DefaultAction';
                $components = !empty($components[1]) ? $components[1] : '';
            }elseif(preg_match("/[a-z,A-z]+/", $components[1])) {
                $action = (strtolower($components[1])) . 'Action';
                unset($components[1]);
                $components = !empty($components[2]) ? $components[2] : '';
            }else {
                $this->notFound('Action');
            }
            if (method_exists($controller, $action)) {
                if ($this->di->get('auth')->isAuthenticated()) {
                    $content = $controller->{$action}($components);
                    return $response->setContent($content)->send();
                } else {
                    $auth = new AuthController($this->di);
                    return $response->setContent($auth->authAction())->send();
                }
            } else {
                $this->notFound('Action');
            }
        } else {
            $this->notFound('Controller');
        }
    }

    private function notFound($subject){
        $controller = new NotFoundController($this->di);
        $action = 'DefaultAction';
        $response = $controller->{$action}($subject);
        return $this->di->get('response')->setStatus(404)->setContent($response)->send();
    }


}