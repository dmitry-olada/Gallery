<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 01.02.16
 * Time: 21:41
 */

namespace Core;


use Lebran\Container;
use Lebran\Container\ServiceProviderInterface;
use Core\Auth\Auth;
use Core\Auth\Cookie;
use Core\Auth\Session;
use Core\Http\Request;
use Core\Http\Response;
use Lebran\Mvc\View;
use Lebran\Mvc\View\Extension\Blocks;

class ServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Container $di
     *
     * @return mixed
     */

    public function register(Container $di)
    {
        $di->set('view', function(){
            $view = new View(array(new Blocks()));
            $view->addFolder('views', DOC_ROOT.'/views');
            return $view;
        }, true)
            ->set('router', new Router())
            ->set('request', new Request())
            ->set('response', new Response())
            ->set('auth', new Auth())
            ->set('session', new Session())
            ->set('cookie', new Cookie());
    }
}