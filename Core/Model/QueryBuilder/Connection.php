<?php

namespace Core\Model\QueryBuilder;

use Core\Model\QueryBuilder\Grammar\Mysql;

class Connection
{
    protected $pdo;
    protected $type;
    protected $grammar;
    protected $cache = [];

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->type = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
    }
    public function query()
    {
        return new Builder($this->pdo, $this->getQueryGrammar());
    }
    public function table()
    {
        return call_user_func_array([$this->query(), 'table'], func_get_args());
    }
    public function raw($sql)
    {
        return $this->pdo->query($sql);
    }
    /**
     * Gets column names for the specified table.
     *
     * @param string $table Name of the table to get columns from.
     *
     * @return array Array of column names.
     * @throws \RuntimeException
     */
    public function columnList($table)
    {
        if(array_key_exists($table, $this->cache)){
            return $this->cache[$table];
        }
        $columns = [];
        if ($this->type === 'mysql') {
            $list = $this->raw("DESCRIBE `$table`")->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($list as $column) {
                $columns[] = $column['Field'];
            }
        } else if ($this->type === 'pgsql') {
            $list = $this->raw(
                "SELECT column_name FROM information_schema.columns WHERE table_name = '$table' and table_catalog = current_database();"
            )->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($list as $column) {
                $columns[] = $column['column_name'];
            }
        } else if ($this->type === 'sqlite') {
            $list = $this->raw("PRAGMA table_info('$table')")->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($list as $column) {
                $columns[] = $column['name'];
            }
        } else {
            throw new \RuntimeException('Supports only: mysql, sqlite, pgsql');
        }
        $this->cache[$table] = $columns;
        return $columns;
    }
    public function setQueryGrammar(Grammar $grammar){
        $this->grammar = $grammar;
        return $this;
    }
    public function getQueryGrammar(){
        if(null === $this->grammar){
            $grammar = '\\Lebran\\Banana\\Query\\Grammar\\'.strtoupper($this->type);
            $this->grammar = class_exists($grammar)? new $grammar:  new Grammar;
        }
        return $this->grammar;
    }
    public function getPdo()
    {
        return $this->pdo;
    }
}