<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 26.01.16
 * Time: 17:31
 */

namespace Core\Model\Models;

use Core\Model\Model;
use Core\Model\ModelsInterface;

class Users_has_Albums extends Model implements ModelsInterface
{
    public $users_id;
    public $albums_id;

    public function getId()
    {
        return 'users_id';
    }

    public function getTable()
    {
        return 'users_has_albums';
    }

    public function getColumns()
    {
        return array('users_id', 'albums_id');
    }

}