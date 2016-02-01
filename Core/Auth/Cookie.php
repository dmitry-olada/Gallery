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
        setcookie($name, $value, time()+60*60*24*30, '/', 'gallery.hz');
    }

    public function getCookie($name){
        return $_COOKIE[$name];
    }

    public function hasCookie($name){
        return !empty($_COOKIE[$name])?true:false;
    }

    public function deleteCookie($name = null){
        if($name == null){
           foreach ($_COOKIE as $key => $value){
               setCookie($key, '', time(), '/', 'gallery.hz');
           }
        }else{
            setCookie($name, '', time(), '/', 'gallery.hz');
        }
    }


}