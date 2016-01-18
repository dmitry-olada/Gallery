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

class Application
{
    use InjectableTrait;

    public function __construct($di)
    {
        $this->di = $di;
        $this->di->set('router', new Router());
        $this->di->set('request', new Request());
        $this->di->set('response', new Response());
        $this->di->set('view', new View());
        $this->di->set('auth', new Auth());
        $this->di->set('session', new Session());
        $this->di->set('cookie', new Cookie());
    }

    public function handle()
    {
        var_dump($_SERVER['REQUEST_METHOD']);
        echo BR;
        var_dump($_POST);
        echo BR;
        //die;
        //var_dump($_SESSION);
        $components = $this->di->get('router')->execute();
        $controller = 'Core\\Controller\\'.ucfirst(strtolower($components[0])).'Controller';
        //$controller = 'Web\\Controller\\'.ucfirst(strtolower($components[0])).'Controller';
        //var_dump($controller);
            if(class_exists($controller)){
                $controller = new $controller($this->di);
                if(preg_match("/[a-z,A-z]+/", $components[1])){
                    $action = (strtolower($components[1])).'Action';
                    if(method_exists($controller, $action)) {
                        unset($components[0], $components[1]);
                        $components = implode(',', $components);
                        if($this->di->get('auth')->isAuthenticated()) {
                            $response = $controller->{$action}($components);
                            return $this->di->get('response')->setContent($response)->send();
                        }else{
                            $auth = new AuthController($this->di);
                            return $this->di->get('response')->setContent($auth->authAction())->send();
                        }
                    }else{
                        $this->notFound('Action');
                    }
                }elseif(preg_match("/\\d+/", $components[1])){
                    $action = 'DefaultAction';
                    unset($components[0]);
                    implode(',', $components);
                    if($this->di->get('auth')->isAuthenticated()) {
                        $response = $controller->{$action}($components);
                        return $this->di->get('response')->setContent($response)->send();
                    }else{
                        $auth = new AuthController($this->di);
                        return $this->di->get('response')->setContent($auth->authAction())->send();
                    }

                }else{
                    $this->notFound('Action');
                }

            }else if($this->di->get('router')->getUserUri() == '/'){
                if($this->di->get('auth')->isAuthenticated()) {
                    $controller = new ProfileController($this->di);
                    $action = 'DefaultAction';
                    $response = $controller->{$action}();
                    return $this->di->get('response')->setContent($response)->send();
                }else{
                    $auth = new AuthController($this->di);
                    return $this->di->get('response')->setContent($auth->authAction())->send();
                }
            }else{
                $this->notFound('Controller');
            }
    }

    private function notFound($subject){
        $controller = new NotFoundController($this->di);
        $action = 'DefaultAction';
        $response = $controller->{$action}($subject);
        return $this->di->get('response')->setStatus(404)->setContent($response)->send();
    }

    //TODO: сделать с файлом routes





}