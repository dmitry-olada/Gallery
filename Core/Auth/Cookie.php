<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 10.01.16
 * Time: 19:32
 */

namespace Core\Auth;


class Cookie
{

    public function setCookie($name, $value){
        setcookie($name, $value, time()+36000);
    }

    public function getCookie($name){
        echo BR;
        var_dump($name);
        echo BR;
        var_dump($_COOKIE);
        return $_COOKIE[$name];
    }


}