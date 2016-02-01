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

class Albums extends Model implements ModelsInterface
{
    public $id;
    public $name;
    public $description;
    public $date;
    public $buhlikes;
    public $comments;
    public $owner;

    public function getId()
    {
        return 'id';
    }

    public function getTable()
    {
        return 'albums';
    }

    public function getColumns()
    {
        return array('id', 'name', 'description', 'comments', 'date', 'buhlikes', 'owner');
    }


}