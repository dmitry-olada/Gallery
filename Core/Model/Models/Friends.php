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

class Friends extends Model implements ModelsInterface
{
    protected $id;
    protected $user_iden;
    protected $friend_iden;

    public function getId()
    {
        return "id";
    }

    public function getTable()
    {
        return "Friends";
    }

    public function getColumns()
    {
        return array('id', 'user_iden', 'friend_iden');
    }


}