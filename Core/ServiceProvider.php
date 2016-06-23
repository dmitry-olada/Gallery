<?php

namespace Core;


use Core\Model\QueryBuilder\Connection;
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
    public function register(Container $di)
    {
        $pdo = new \PDO('mysql:dbname=new_gallery;host=127.0.0.1', 'root', '',
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ]);

        $di->set('view', function(){
            $view = new View(array(new Blocks()));
            $view->addFolder('views', DOC_ROOT.'/views');
            return $view;
        }, true)
            ->set('connection', new Connection($pdo))
            ->set('router', new Router())
            ->set('request', new Request())
            ->set('image', new Image())
            ->set('response', new Response())
            ->set('auth', new Auth(), true)
            ->set('session', new Session())
            ->set('cookie', new Cookie());
    }
}