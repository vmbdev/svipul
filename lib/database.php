<?php

abstract class Database {
	protected $conn;
    const ORDER_ASC = 'ASC';
    const ORDER_DESC = 'DESC';

	abstract public function disconnect();

    // queries that return data
	abstract public function query($query, $params = null, $fetchAll = false);
	abstract public function queryAll($query, $params = null);

    // queries that return the number of rows affected
	abstract public function modify($query, $params = null);

    // CRUD operations
    abstract public function select($table, $fields = '*', $where = null, $order = null, $limit = null, $offset = null);
    abstract public function insert($table, array $data);
    abstract public function update($table, array $data, $cond = null, $where = null);
    abstract public function delete($table, $where = null);

    // others
    abstract public function getLastId();
    abstract public function quote($string);
}
