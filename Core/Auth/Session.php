<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 10.01.16
 * Time: 19:32
 */

namespace Core\Auth;


class Session
{
    public function __construct()
    {
        session_start();
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function get($name)
    {
        return $_SESSION[$name];
    }

    public function has($name)
    {
        return !empty($_SESSION[$name])?true:false;
    }

    public function remove($name)
    {
        unset($_SESSION[$name]);
    }

    public function setFlash($value, $type){
        $_SESSION['flash'][$type] = $value;
    }

    public function getFlash(){
        if (array_key_exists('flash', $_SESSION)) {
            $tmp = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $tmp;
        } else {
            return null;
        }
    }
}