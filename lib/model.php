<?php

/*  params:
        pk
        fk: table
        null
        min: value or length
        max: value or length
        url, email
 */

// $model = array(
//  var => [ type, params, ... ]
// )

class Model {
    const TYPE_INT = 0;
    const TYPE_FLOAT = 1;
    const TYPE_STRING = 2;
    const TYPE_TEXT = 3;
    const TYPE_DATE = 4;

    protected $__model, $__db, $__name;
    private $__id;

    function __construct($model, $db) {
        $this->__model = $model;
        $this->__db = $db;
        $this->createModel($model);
    }

    public function createModel($model) {
        foreach($model as $var=>$val)
            $this->$var = null;
    }

    public function getProp($var) {
        if (isset($this->$var))
            return $this->$var;

        else
            throw new Exception("Property does not exists under model");
    }

    public function setProp($var, $value) {
        if (property_exists($this, $var)) {
            $attrib_list = $this->__model[$var];
            if (!in_array('null', $attrib_list) && $value == null)
                throw new Exception('Type is not coherent: cannot be NULL');

            $type = array_shift($attrib_list);

            if ($type == Model::TYPE_INT) {
                $options = array();

                foreach ($attrib_list as $attrib) {
                    $pv = explode(":", $attrib);
                    $p = current($pv);
                    $v = next($pv);

                    if (($p == 'min') && is_numeric($v))
                        $options['options']['min_range'] = $v;

                    else if (($p == 'max') && is_numeric($v))
                        $options['options']['max_range'] = $v;
                }

                if (!filter_var($value, FILTER_VALIDATE_INT, $options))
                    throw new Exception("Type is not coherent: INT");
            }

            else if (($type == Model::TYPE_FLOAT) && !filter_var($value, FILTER_VALIDATE_FLOAT))
                throw new Exception("Type is not coherent : FLOAT");

            else if ($type == Model::TYPE_STRING) {
                $options = array();

                foreach ($attrib_list as $attrib) {
                    $pv = explode(":", $attrib);
                    $p = current($pv);
                    $v = next($pv);

                    if (($p == 'min') && is_numeric($v)) {
                        if (strlen($value) < $v)
                            throw new Exception("Type is not coherent: STRING minimum lenght is " . $v);
                    }

                    else if (($p == 'max') && is_numeric($v)) {
                        if (strlen($value) > $v)
                            $value = substr($value, 0, $v);
                    }

                }
            }

            else if ($type == Model::TYPE_DATE) {
                $date = DateTime::createFromFormat('d/m/Y H:i:s', $value);
                $value = $date->format('Y-m-d H:i:s');
            }

            $this->$var = $value;
        }
    }

    public function findById($id) {
//public function select($table, $fields = '*', $where = null, $limit = null, $order = null, $offset = null)
        if (is_numeric($id)) {
            $r = $this->__db->select($this->__name, '*', 'id = ' . $id, 1);

            if (empty($r))
                throw new Exception('No data found by id ' . $id);

            else
                return $r;
        }

        else
            throw new Exception('ID must be numeric');
    }

    public function insert($obj = null) {
        if (!$obj)
            $obj = $this;
    }

    public function delete($id = null) {

    }

    public function merge() {

    }

    public function setModelName($name) {
        $this->__name = $name;
    }
}
