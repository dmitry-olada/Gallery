<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 09.01.16
 * Time: 18:48
 */

namespace Core\Model\Models;

use Core\Model\Model;
use Core\Model\ModelsInterface;

class Users extends Model implements ModelsInterface
{
    public $user_id;
    public $nick;
    public $avatar;
    public $reg_date;
    public $email;
    public $password;


    public function getId()
    {
        return "user_id";
    }

    public function getTable()
    {
        return "Users";
    }

    public function getColumns()
    {
        return array('user_id', 'nick', 'avatar', 'reg_date', 'email', 'password');
    }
}