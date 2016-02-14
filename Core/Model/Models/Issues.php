<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 14.02.16
 * Time: 1:27
 */

namespace Core\Model\Models;


use Core\Model\Model;
use Core\Model\ModelsInterface;

class Issues extends Model implements ModelsInterface
{
    public $id;
    public $users_id;
    public $nick;
    public $text;
    public $type;

    public function getId()
    {
        return 'id';
    }

    public function getTable()
    {
        return 'issues';
    }

    public function getColumns()
    {
        return array('id','users_id', 'nick', 'text', 'type');
    }
}