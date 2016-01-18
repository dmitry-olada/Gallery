<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 09.01.16
 * Time: 18:20
 */

namespace Core\Model;

use ArrayAccess;
use Core\Model\QueryBuilder\Builder;
use Core\Model\QueryBuilder\Grammar\Mysql;

class Model implements ModelsInterface
{
    protected $builder;

    protected $pdo;

    /**
     * @return string
     */
    public function getId()
    {
    }

    /**
     * @return string
     */
    public function getTable()
    {
    }

    /**
     * @return array
     */
    public function getColumns()
    {
    }

    public function __construct($pdo = null)
    {
        $opt = array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
        );
        $this->pdo = $pdo ? $pdo : new \PDO('mysql:dbname=gallery;host=127.0.0.1', 'root', '123', $opt);
        $this->builder = new Builder($this->pdo, new Mysql());
    }

    public function selectAll($key, $value)
    {
        return $this->builder->table($this->getTable())->where($key, '=', $value)->get()->fetch();
    }

    public function select($fields, $key, $value, $opt = null)
    {
        return $this->builder->table($this->getTable())->fields($fields)->where($key, '=', $value)->get()->fetch($opt);
    }

    public function insert(){
        foreach ($this->getColumns() as $key) {
            $values[] = $this->$key;
        }
        $data = array_combine($this->getColumns(), $values);
        $this->builder->table($this->getTable())->insert($data);
    }

    public function update($field, $value){
        foreach ($this->getColumns() as $key) {
            $values[] = $this->$key;
        }
        $data = array_combine($this->getColumns(), $values);
        //var_dump($data);
        $this->builder->table($this->getTable())->where($field, '=', $value)->update($data);
    }

    public function delete($id){
        $this->builder->table($this->getTable())->where($this->getId(), '=', $id)->delete();
    }

}