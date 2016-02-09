<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 05.02.16
 * Time: 1:03
 */

namespace Core\Model\Models;


use Core\Model\Model;
use Core\Model\ModelsInterface;

class Comments extends Model implements ModelsInterface
{
    public $id;
    public $albums_id;
    public $users_id;
    public $comment;
    public $date;

    public function getId()
    {
        return 'id';
    }

    public function getTable()
    {
        return 'comments';
    }

    public function getColumns()
    {
        return array('id', 'albums_id', 'users_id', 'comment', 'date');
    }
}