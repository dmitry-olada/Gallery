<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 09.01.16
 * Time: 18:16
 */

namespace Core\Model\Grammar;


use Core\Model\Grammar;

class Mysql extends Grammar
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
    ];
    protected static $delete = [
        'tables',
        'joins',
        'wheres',
        'orders',
        'limit'
    ];
    protected static $update = [
        'sets',
        'wheres',
        'orders',
        'limit'
    ];
    public function buildSelect(array $parts)
    {
        $query = parent::buildSelect($parts);
        if (!empty($parts['unions'])) {
            $query = '('.$query.') '.$this->buildUnions($parts['unions']);
        }
        return $query;
    }
    public function buildUpdate(array $parts)
    {
        $query = 'UPDATE '.implode(',', array_map([$this, 'buildTable'], $parts['tables']));
        return $query.$this->buildParts(static::$update, $parts);
    }
    protected function buildUnion($union){
        if (is_object($union['union'])) {
            $string = 'UNION ';
            if ($union['type'] === 'ALL') {
                $string .= 'ALL ';
            }
            return $string.'('.$this->buildSelect($union['union']->getParts()).')';
        } else {
            throw new \InvalidArgumentException('Union should be object.');
        }
    }
    protected function quoteField($string)
    {
        if(false !== strpos($string, '.')){
            return implode('.',array_map([$this, 'quoteField'], explode('.', $string)));
        } else {
            return ($string === '*' || is_int($string))?$string:'`'.$string.'`';
        }
    }
    protected function quoteTable($string)
    {
        return '`'.$string.'`';
    }
    protected function buildSets($values)
    {
        $sets = [];
        foreach ($values as $key => $value){
            if(strripos($key, ',') !== false) {
                $keys = array_fill_keys(explode(',', $key), $value);
                foreach ($keys as $k => $v){
                    $sets[] = $this->buildSet($k, $value);
                }
            }else{
                $sets[] = $this->buildSet($key, $value);
            }
        }
        return ' SET '.implode(', ', $sets);
    }
}