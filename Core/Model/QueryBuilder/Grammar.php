<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 09.01.16
 * Time: 18:15
 */

namespace Core\Model;


class Grammar
{
    protected static $select = [
        'distinct',
        'fields',
        'tables',
        'joins',
        'wheres',
        'groups',
        'havings',
        'orders',
        'limit',
        'offset',
        'unions'
    ];
    protected static $insert = [
        'table',
        'values'
    ];
    protected static $delete = [
        'tables',
        'wheres'
    ];
    protected static $update = [
        'table',
        'sets',
        'wheres'
    ];
    protected static $operators = [
        '=',
        '<>',
        '!=',
        '>',
        '<',
        '>=',
        '<=',
        'IN',
        'NOT IN',
        'BETWEEN',
        'LIKE',
        'NOT LIKE',
        'IS NULL',
        'NOT NULL',
        'ANY',
        'ALL',
        'EXISTS',
        'NOT EXISTS',
        'SOME'
    ];
    public function buildSelect(array $parts)
    {
        return 'SELECT '.$this->buildParts(static::$select, $parts);
    }
    public function buildInsert(array $parts)
    {
        $parts['table'] = array_shift($parts['tables']);
        return 'INSERT INTO '.$this->buildParts(static::$insert, $parts);
    }
    public function buildUpdate(array $parts)
    {
        $parts['table'] = array_shift($parts['tables']);
        return 'UPDATE '.$this->buildParts(static::$update, $parts);
    }
    public function buildDelete(array $parts)
    {
        return 'DELETE '.$this->buildParts(static::$delete, $parts);
    }
    public function buildTruncate(array $parts)
    {
        return 'TRUNCATE TABLE '.$this->buildParts(['table'], $parts);
    }
    protected function buildParts($map, array $parts)
    {
        $builtParts = [];
        foreach ($map as $part) {
            if (!empty($parts[$part])) {
                $builtParts[] = $this->{'build'.ucfirst($part)}($parts[$part]);
            }
        }
        return implode(' ', $builtParts);
    }
    protected function buildDistinct($distinct)
    {
        return $distinct?'DISTINCT':'';
    }
    protected function buildFields(array $fields)
    {
        $fields = array_map([$this, 'buildField'], $fields);
        return implode(',', $fields);
    }
    protected function buildField($field)
    {
        switch (gettype($field)) {
            case 'string':
                return $this->buildAggregate($field);
                break;
            case 'array':
                return $this->buildAlias($this->buildField($field[0]), $this->quote($field[1]));
                break;
            case 'object':
                return $this->buildSelect($field->getParts());
                break;
            default:
                throw new \InvalidArgumentException('Field only: string, array or object.');
        }
    }
    protected function buildTables(array $tables)
    {
        $tables = array_map([$this, 'buildTable'], $tables);
        return 'FROM '.implode(',', $tables);
    }
    protected function buildTable($table)
    {
        switch (gettype($table)) {
            case 'string':
                return $this->quoteTable($table);
                break;
            case 'array':
                return $this->buildAlias($this->buildTable($table[0]), $this->quote($table[1]));
                break;
            case 'object':
                return '('.$this->buildSelect($table->getParts()).')';
                break;
            default:
                throw new \InvalidArgumentException('Table only: string, array or object.');
        }
    }
    protected function buildJoins(array $joins)
    {
        $joins = array_map([$this, 'buildJoin'], $joins);
        return implode(' ', $joins);
    }
    protected function buildJoin($join)
    {
        $query = $join['type'].'JOIN ';
        if (!$join['one'] && !$join['two']) {
            $query = 'NATURAL'.$query;
            $query .= $this->quoteTable($join['table']);
        } else {
            $query .= $this->quoteTable($join['table']);
            if (!$join['two']) {
                $query .= ' USING('.$this->quoteField($join['one']).')';
            } else {
                $query .= ' ON('.$this->quoteField($join['one'])
                    .' '.$join['operator'].' '
                    .$this->quoteField($join['two']).')';
            }
        }
        return $query;
    }
    protected function buildWheres(array $wheres)
    {
        return 'WHERE '.$this->buildWheresNested($wheres);
    }
    protected function buildWheresNested(array $wheres)
    {
        $wheres[0]['boolean'] = null;
        return implode(' ', array_map([$this, 'buildWhere'], $wheres));
    }
    protected function buildWhere($where)
    {
        $query = '';
        if (is_string($where['boolean'])) {
            $where['boolean'] = strtoupper($where['boolean']);
            if (in_array($where['boolean'], ['OR', 'AND'], true)) {
                $query .= $where['boolean'].' ';
            }
        }
        if (is_string($where['field'])) {
            $query .= $this->buildAggregate($where['field']).' ';
            if (is_string($where['operator'])) {
                $where['operator'] = strtoupper($where['operator']);
                if (in_array($where['operator'], static::$operators, true)) {
                    $query .= $where['operator'].' ';
                } else {
                    throw new \InvalidArgumentException('Supports only '.explode(',', static::$operators).'.');
                }
            } else {
                throw new \InvalidArgumentException('Operator should be string.');
            }
            switch (gettype($where['data'])) {
                case 'string':
                case 'integer':
                case 'double':
                    $query .= $this->quote($where['data']);
                    break;
                case 'null':
                    break;
                case 'array':
                    if (strtoupper($where['operator']) === 'BETWEEN') {
                        $query .= $where['data'][0].' AND '.$where['data'][1];
                    } else {
                        $query .= '('.implode(',', $where['data']).')';
                    }
                    break;
                case 'object':
                    $query .= '('.$this->buildSelect($where['data']->getParts()).')';
                    break;
                default:
                    throw new \InvalidArgumentException();
            }
        } else if (is_object($where['field'])) {
            $query .= '('.$this->buildWheresNested($where['field']->getParts()['wheres']).')';
        } else {
            throw new \InvalidArgumentException('Where field support only string or object.');
        }
        return $query;
    }
    protected function buildAggregate($string)
    {
        $pattern = "#(\w+)\(([\(\)\w\*\s,]+)\)$#i";
        $match   = [];
        if (preg_match($pattern, $string, $match)) {
            $last_pos = strripos($match[2], ')');
            if ($last_pos === false) {
                return $string;
            } else {
                $function = substr($match[2], 0, $last_pos + 1);
                $args     = trim(substr($match[2], $last_pos + 1), ',');
                if ($match[2]) {
                    $args = explode(',', $args);
                    foreach ($args as $key => $value) {
                        $args[$key] = (count($args) === 0)?"`".$value."`":null;
                    }
                    $args = (null === $args[0])?'':','.implode(',', $args);
                }
                $new_function = $this->buildAggregate($function);
                return strtoupper($match[1])."(".$new_function.$args.")";
            }
        } else {
            return $this->quoteField($string);
        }
    }
    protected function buildGroups(array $groups)
    {
        $groups = array_map([$this, 'buildField'], $groups);
        return 'GROUP BY '.implode(',', $groups);
    }
    protected function buildHavings(array $havings)
    {
        return 'HAVING '.$this->buildWheresNested($havings);
    }
    protected function buildOrders(array $orders)
    {
        $orders = array_map([$this, 'buildOrder'], $orders);
        return 'ORDER BY '.implode(',', $orders);
    }
    protected function buildOrder($order)
    {
        switch (gettype($order)) {
            case 'string':
                return $this->quoteField($order);
                break;
            case 'array':
                $condition = '';
                if (is_string($order[1])) {
                    $order[1] = strtoupper($order[1]);
                    if (in_array($order[1], ['ASC', 'DESC'], true)) {
                        $condition = ' '.$order[1];
                    }
                }
                return $this->quoteField($order[0]).$condition;
            default:
                throw new \InvalidArgumentException('OrderBy should be string or array.');
        }
    }
    protected function buildLimit($limit)
    {
        return 'LIMIT '.$limit;
    }
    protected function buildOffset($offset)
    {
        return 'OFFSET '.$offset;
    }
    protected function buildUnions(array $unions)
    {
        return implode(' ', array_map([$this, 'buildUnion'], $unions));
    }
    protected function buildUnion($union)
    {
        if (is_object($union['union'])) {
            $string = 'UNION ';
            if ($union['type'] === 'ALL') {
                $string .= 'ALL ';
            }
            return $string.$this->buildSelect($union['union']->getParts());
        } else {
            throw new \InvalidArgumentException('Union should be object.');
        }
    }
    protected function buildValues($values)
    {
        switch (gettype($values)) {
            case 'array':
                if (is_array(reset($values))) {
                    array_walk(
                        $values,
                        function (&$item) {
                            ksort($item);
                        }
                    );
                } else {
                    $values = [$values];
                }
                $parameters = [];
                $query      = '('.$this->buildFields(array_keys(reset($values))).')';
                foreach ($values as $value) {
                    $parameters[] = '('.implode(',', array_map([$this, 'quote'], $value)).')';
                }
                return $query.' VALUES '.implode(',', $parameters);
                break;
            case 'object':
                $query  = '';
                $fields = $values->getParts()['fields'];
                if (reset($fields) !== '*') {
                    $query = '('.$this->buildFields($fields).') ';
                }
                return $query.$this->buildSelect($values->getParts());
                break;
            default:
                throw new \InvalidArgumentException('Values should be array or object.');
        }
    }
    protected function buildSets($values)
    {
        $sets = [];
        foreach ($values as $key => $value){
            $sets[] = $this->buildSet($key, $value);
        }
        return 'SET '.implode(', ', $sets);
    }
    protected function buildSet($key, $value)
    {
        if(strripos($key, ',') !== false){
            $query = '('.implode(',',array_map([$this, 'quoteField'], explode(',', $key))).')';
        }else{
            $query = $this->quoteField($key);
        }
        $query .= ' = ';
        switch (gettype($value)) {
            case 'string':
                return $query.$this->quote($value);
                break;
            case 'object':
                return $query.'('.$this->buildSelect($value->getParts()).')';
                break;
            default:
                throw new \InvalidArgumentException('Value only: string or object.');
        }
    }
    protected function buildAlias($string, $alias)
    {
        return $this->quoteTable($string).' as '.$this->quoteTable($alias);
    }
    protected function quote($string)
    {
        return is_int($string)?$string:"'".$string."'";
    }
    protected function quoteField($string)
    {
        if (0 !== strpos($string, '.')) {
            return implode('.', array_map([$this, 'quoteField'], explode('.', $string)));
        } else {
            return ($string === '*')?$string:$this->quote($string);
        }
    }
    protected function quoteTable($string)
    {
        return $this->quote($string);
    }
}