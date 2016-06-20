<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 26.02.16
 * Time: 0:38
 */

namespace Core\Auth;


class Validator
{
    public function htmlValidation()
    {
        foreach ($_POST as $key => $value){
            $_POST[$key] = htmlspecialchars($_POST[$key]);
        }
    }
}