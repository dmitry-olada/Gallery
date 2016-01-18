<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 07.01.16
 * Time: 21:15
 */

namespace Core;

use Lebran\Container;
use Lebran\Container\InjectableInterface;
use Lebran\Container\InjectableTrait;

class Controller implements InjectableInterface
{
    use InjectableTrait;

    public function __construct($di)
    {
        $this->di = $di;
    }

}