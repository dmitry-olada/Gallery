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
    public $id;
    public $email;
    public $password;
    public $nick;
    public $avatar;
    public $reg_date;
    public $bookmarks;

    public function getId()
    {
        return 'id';
    }

    public function getTable()
    {
        return 'users';
    }

    public function getColumns()
    {
        return array('id', 'email', 'password', 'nick', 'avatar', 'reg_date', 'bookmarks');
    }
}