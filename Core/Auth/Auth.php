<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 10.01.16
 * Time: 19:29
 */

namespace Core\Auth;

use Lebran\Container\InjectableInterface;
use Lebran\Container\InjectableTrait;

class Auth implements InjectableInterface
{
    use InjectableTrait;

    const SESSION_USER_NAME = 'user_name';

    public function setUser($user)
    {
        var_dump($user);
        $this->di->get('session')->set(self::SESSION_USER_NAME, serialize($user));
    }

    public function getUser()
    {
        return $this->isAuthenticated()?unserialize($this->di->get('session')->get(self::SESSION_USER_NAME)):null;
    }

    public function isAuthenticated()
    {
        return $this->di->get('session')->has(self::SESSION_USER_NAME);
    }

    public function clear()
    {
        $this->di->get('session')->remove(self::SESSION_USER_NAME);
    }

    public function hash($password){
        return hash('sha256', $password);
    }
}