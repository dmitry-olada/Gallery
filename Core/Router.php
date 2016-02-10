<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 02.01.16
 * Time: 18:55
 */

namespace Core;

use Lebran\Container;
use Lebran\Container\InjectableInterface;

class Router implements InjectableInterface
{
    use Container\InjectableTrait;

    protected $uri;

    public function execute($uri = null){
        $this->uri = $uri ? $uri : $this->di->get('request')->getUri();
        return explode('/', $this->uri);
    }

    public function getUserUri(){
        return $_SERVER['REQUEST_URI'];
    }

}