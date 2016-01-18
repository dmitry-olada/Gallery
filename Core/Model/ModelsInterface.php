<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 09.01.16
 * Time: 18:54
 */

namespace Core\Model;

interface ModelsInterface
{
    public function getId();
    public function getTable();
    public function getColumns();
}