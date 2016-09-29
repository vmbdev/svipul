<?php

class MySQLDriver extends Database {

	public function connect($host, $db, $user, $password) {
		if (!isset($conn)) {
			$this->conn = new PDO("mysql:host=$host;dbname=$db", $user, $password,
								array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}
	}

	public function disconnect() {
		$this->conn = null;
	}

	public function query($query, $params = null, $fetchAll = false) {
		$q = $this->conn->prepare($query);
		$q->execute($params);
		if ($fetchAll)
			return $q->fetchAll(PDO::FETCH_ASSOC);

		else
			return $q->fetch(PDO::FETCH_ASSOC);
	}

	public function queryAll($query, $params = null) {
		return $this->query($query, $params, true);
	}

	public function modify($query, $params = null) {
		$q = $this->conn->prepare($query);
		return $q->execute($params);
	}

    public function select($table, $fields = '*', $where = null, $limit = null, $order = null, $offset = null, $params = null) {
        $q = 'SELECT ' . $fields . ' FROM ' . $table
            . ($where ? ' WHERE ' . $where : '')
            . ($limit ? ' LIMIT ' . $limit : '')
            . (($offset && $limit) ? ' OFFSET ' . $offset: '')
            . ($order ? ' ORDER BY ' . $order : '');
        $r = $this->queryAll($q, $params);

        // more than 1 row => array of arrays
        return ((($limit != 1) && (!empty($r))) ? $r : current($r));
    }

    public function insert($table, array $data) {
        // data must be in the format column => value
        $columns = implode(',', array_keys($data));
        $values = array_values($data);
        $q = 'INSERT INTO ' . $table . ' ( ' . $columns . ' ) VALUES (?' . str_repeat(',?', count($data) - 1) . ')';

        return $this->modify($q, $values);
    }

    public function update($table, array $data, $cond = null, $where = null) {
        if ($cond) {
            $cond_values = array_values($cond);
            $cond = implode('=? AND ', array_keys($cond)) . '=?';
        }

        $set = array();

        foreach (array_keys($data) as $column)
            $set[] = $column . '=?';

        $set = implode(',', $set);

        $q = 'UPDATE ' . $table . ' SET ' . $set .
             (($where || $cond) ? ' WHERE ' : '') . ($cond ? $cond : '') .
             (($where && $cond) ? ' AND ' : '') . ($where ? $where : '');

        $params = array_values($data);

        return $this->modify($q, ($cond ? array_merge($params, $cond_values) : $params));
    }

    public function delete($table, $id = null, $where = null) {
        if ($id) {
            $cond = ' WHERE id = ' . $id;
            if ($where)
                $cond .= ' AND ' . $where;
        }
        else if ($where)
            $cond = ' WHERE ' . $where;

        $q = 'DELETE FROM ' . $table . ($cond ? $cond : '');

        return $this->modify($q);
    }

    public function getLastId() {
        return $this->conn->lastInsertId();
    }

    public function quote($string) {
        return $this->conn->quote($string);
    }

    public function now() {
        return date('Y-m-d H:i:s');
    }

}
