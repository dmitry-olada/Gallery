<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 09.01.16
 * Time: 18:20
 */

namespace Core\Model;

use ArrayAccess;
use Core\Controller;
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
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        );
        $this->pdo = $pdo ? $pdo : new \PDO('mysql:dbname=new_gallery;host=127.0.0.1', 'root', '', $opt);
        $this->builder = new Builder($this->pdo, new Mysql());
    }

    public function selectAll($fields, $where = array(), $opt = null){
        if(empty($where)){
            return $this->builder->table($this->getTable())->fields($fields)->get()->fetchAll($opt);
        }else{
            return $this->builder->table($this->getTable())->fields($fields)->where($where[0], '=', $where[1])->get()->fetchAll($opt);
        }
    }

    public function select($fields, $where = array(), $opt = null)
    {
        return $this->builder->table($this->getTable())->fields($fields)->where($where[0], '=', $where[1])->get()->fetch($opt);
    }

    public function selectObj($where = array(), $opt = null, $auth = false)
    {
        $sql = $this->builder->table($this->getTable())->where($where[0], '=', $where[1])->get();
        return $auth ? $sql->fetch($opt) : $sql->fetchObject(static::class);
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

        $this->builder->table($this->getTable())->where($field, '=', $value)->update($data);
    }

    public function delete($id){
        $this->builder->table($this->getTable())->where($this->getId(), '=', $id)->delete();
    }

}