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

class Photos extends Model implements ModelsInterface
{
    public $id;
    public $link;
    public $albums_id;
    public $name;

    public function getId()
    {
        return 'id';
    }

    public function getTable()
    {
        return 'photos';
    }

    public function getColumns()
    {
        return array('id', 'link', 'albums_id', 'name');
    }

}