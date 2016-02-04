<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 09.01.16
 * Time: 18:15
 */

namespace Core\Model\QueryBuilder;

use PDO;
use Closure;

class Builder
{
    protected $db;
    protected $grammar;
    protected $distinct = false;
    protected $fields = ['*'];
    protected $tables;
    protected $joins;
    protected $wheres;
    protected $groups;
    protected $havings;
    protected $orders;
    protected $limit;
    protected $offset;
    protected $unions;

    public function __construct(PDO $db, Grammar $grammar)
    {
        $this->grammar = $grammar;
        $this->db      = $db;
    }

    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    public function fields()
    {
        $this->fields = [];
        foreach (func_get_args() as $field) {
            if ($field instanceof Closure) {
                $field = $this->subQuery($field);
            }
            $this->fields[] = $field;
        }
        return $this;
    }

    public function table()
    {
        foreach (func_get_args() as $table) {
            if ($table instanceof Closure) {
                $table = $this->subQuery($table);
            }
            $this->tables[] = $table;
        }
        return $this;
    }
    public function join($table, $one = null, $operator = null, $two = null, $type = '')
    {
        $this->joins[] = compact('table', 'one', 'operator', 'two', 'type');
        return $this;
    }

    public function where($field, $operator = null, $data = null, $boolean = 'AND')
    {
        if ($field instanceof Closure) {
            $field = $this->subQuery($field);
        }
        if ($data instanceof Closure) {
            $data = $this->subQuery($data);
        }
        $this->wheres[] = compact('field', 'operator', 'data', 'boolean');
        return $this;
    }

    public function orWhere($field, $operator = null, $data = null)
    {
        return $this->where($field, $operator, $data, 'OR');
    }

    public function groupBy($groups)
    {
        $this->groups = func_get_args();
        return $this;
    }

    public function having($field, $operator = null, $data = null, $boolean = 'AND')
    {
        if ($field instanceof Closure) {
            $field = $this->subQuery($field);
        }
        if ($data instanceof Closure) {
            $data = $this->subQuery($data);
        }
        $this->havings[] = compact('field', 'operator', 'data', 'boolean');
        return $this;
    }
    public function orderBy($field)
    {
        $this->orders = func_get_args();
        return $this;
    }
    public function limit($limit = 1)
    {
        $this->limit = $limit;
        return $this;
    }
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
    public function union($union, $type = null)
    {
        if ($union instanceof Closure) {
            $union = $this->subQuery($union);
        }
        $this->unions[] = compact('union', 'type');
        return $this;
    }
    public function get()
    {
        $sql = $this->grammar->buildSelect($this->getParts());
        return $this->db->query($sql);
    }
    public function select(){
        return $this->get();
    }
    public function insert($values)
    {
        if ($values instanceof Closure) {
            $values = $this->subQuery($values);
        }
        $sql = $this->grammar->buildInsert(['tables' => $this->tables, 'values' => $values]);
        return $this->db->query($sql);
    }
    public function delete()
    {
        $sql = $this->grammar->buildDelete($this->getParts());
        return $this->db->query($sql);
    }
    public function truncate($table)
    {
        $sql = $this->grammar->buildTruncate(['table' => $table]);
        return $this->db->query($sql);
    }
    public function update(array $values)
    {
        $values = array_map(function($value){
            return ($value instanceof Closure)?$this->subQuery($value):$value;
        }, $values);
        $sql = $this->grammar->buildUpdate(array_merge($this->getParts(), ['sets' => $values]));
        var_dump($sql);
        return $this->db->query($sql);
    }
    protected function subQuery(Closure $closure)
    {
        $object = new static($this->db, $this->grammar);
        call_user_func(($closure->bindTo($object, $this)));
        return $object;
    }
    public function getParts()
    {
        $parts = [
            'distinct' => $this->distinct,
            'fields'   => $this->fields,
            'tables'   => $this->tables,
            'joins'    => $this->joins,
            'wheres'   => $this->wheres,
            'groups'   => $this->groups,
            'havings'  => $this->havings,
            'orders'   => $this->orders,
            'limit'    => $this->limit,
            'offset'   => $this->offset,
            'unions'   => $this->unions
        ];
        array_walk_recursive(
            $parts,
            function (&$item) {
                if (is_string($item)) {
                    $item = trim($item);
                }
            }
        );
        return $parts;
    }
}