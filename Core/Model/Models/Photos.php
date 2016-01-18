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
    protected $photo_id;
    protected $link;
    protected $description;
    protected $date;
    protected $owner;

    public function getId()
    {
        return "photo_id";
    }

    public function getTable()
    {
        return "Photos";
    }

    public function getColumns()
    {
        return array('photo_id', 'link', 'description', 'date', 'owner');
    }

}