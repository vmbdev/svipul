<?php

/*  params:
		fk: table
		null
		min: value or length
		max: value or length
		url, email, ...
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
	const TYPE_FOREIGNKEY = 5;

	protected $__model, $__db;
	private $__id;
	static private $__fklist;

	function __construct($db = null) {
		if (!$db)
			$this->__db = ResManager::getDatabase();

		else
			$this->__db = $db;

		Self::$__fklist[get_called_class()] = array();
		$this->createModel();
	}

	public function createModel() {
		foreach($this->__model as $property => $attrib_list) {
			if (current($attrib_list) == Model::TYPE_FOREIGNKEY) {
				$foreignclass = next($attrib_list);

				if (class_exists($foreignclass)) {
					$this->$property = new $foreignclass($this->__db);
					Self::$__fklist[get_called_class()][$property] = $foreignclass;
				}
				else
					throw new Exception("Foreign key points to a non existing class: " . $foreignclass);
			}

			else
				$this->$property = null;
		}
	}

	public function getId() {
		return $this->__id;
	}

	public function getAttrib($prop) {
		if (isset($this->$__model[$prop]))
			return $this->$__model[$prop];

		else
			throw new Exception("Property does not exists under model", 20);
	}

	public function getProp($prop) {
		if (property_exists($this, $prop))
			return $this->$prop;

		else
			throw new Exception("Property does not exists under model: " . $prop, 20);
	}

	public function setProp($prop, $value) {
		if (property_exists($this, $prop)) {
			$attrib_list = $this->__model[$prop];

			if ($value == null) {
				if (!in_array('null', $attrib_list))
					throw new Exception('Type is not coherent: cannot be NULL', 21);
				else
					$this->$prop = null;
			}

			else {
				$type = array_shift($attrib_list);

				if ($type == Model::TYPE_INT) {
					$options = array();

					foreach ($attrib_list as $attrib) {
						// keys in the format of param: value
						$pv = explode(":", $attrib);
						$p = current($pv); // the param
						$v = next($pv); // the value

						if (($p == 'min') && is_numeric($v))
							$options['options']['min_range'] = $v;

						else if (($p == 'max') && is_numeric($v))
							$options['options']['max_range'] = $v;
					}

					// if $value = 0, filter_var will return false, but not 0 as type
					if ((filter_var($value, FILTER_VALIDATE_INT, $options) === false) && (filter_var($value, FILTER_VALIDATE_INT, $options) === 0))
						throw new Exception("Type is not coherent: INT", 22);
				}

				else if (($type == Model::TYPE_FLOAT) && !filter_var($value, FILTER_VALIDATE_FLOAT))
					throw new Exception("Type is not coherent : FLOAT", 23);

				else if ($type == Model::TYPE_STRING) {
					$options = array();

					foreach ($attrib_list as $attrib) {
						$pv = explode(":", $attrib);
						$p = current($pv);
						$v = next($pv);

						if (($p == 'min') && is_numeric($v)) {
							if (strlen($value) < $v)
								throw new Exception("Type is not coherent: STRING minimum lenght is " . $v, 24);
						}

						else if (($p == 'max') && is_numeric($v)) {
							if (strlen($value) > $v)
								$value = substr($value, 0, $v);
						}

					}
				}

				else if ($type == Model::TYPE_DATE) {
					$date = DateTime::createFromFormat('d/m/Y', $value);
					$value = $date->format('Y-m-d H:i:s');
				}

				else if ($type == Model::TYPE_FOREIGNKEY) {
					$foreignclass = current($attrib_list);

					if (!class_exists($foreignclass))
						throw new Exception("Foreign key points to a non existing class: " . $foreignclass);
				}

				$this->$prop = $value;
			}
		}
	}

	public static function row2obj($obj, $row) {
		foreach ($row as $property => $value) {
			if (array_key_exists($property, $obj->__model) && (reset($obj->__model[$property]) != Model::TYPE_FOREIGNKEY))
				$obj->$property = $value;
		}

		// fetch the tables referenced as foreign key
		foreach (Self::$__fklist[get_called_class()] as $reference => $fk) {
			if ($row[$reference] != null) {
				$obj->$reference = new $fk($obj->__db);
				$obj->$reference->findById($row[$reference]);
			}
		}

		$obj->__id = $row['id'];
	}

	public function find($cond = null) {
		$r = $this->__db->select(get_class($this), '*', $cond, 1);

		if (empty($r))
			throw new Exception('No data found', 25);

		else
			Self::row2obj($this, $r);
	}

	public function findByParams(array $data) {
		if (is_array($data)) {
			$cond = implode('=? AND ', array_keys($data)) . '=?';
			$r = $this->__db->select(get_class($this), '*', $cond, 1, null, null, array_values($data));

			if (empty($r))
				throw new Exception('No data found', 205);

			else
				Self::row2obj($this, $r);
		}
	}

	public function findById($id) {
		if (is_numeric($id)) {
			try {
				$r = $this->find('id = ' . $id);
				$this->__id = $id;
			} catch (Exception $e) {
				throw new Exception('No data found by id ' . $id, 211);
			}

			return $r;
		}

		else
			throw new Exception('ID must be numeric', 26);
	}

	public static function findAll($cond = null, $limit = null, $offset = null, $db = null) {
		if ($db == null)
			$db = ResManager::getDatabase();

		$r = $db->select(get_called_class(), '*', $cond, $limit, null, $offset);

		if (empty($r))
			throw new Exception('No data found', 25);

		else {
			$results = array();
			foreach ($r as $row) {
				$mod = new static($db);
				Self::row2obj($mod, $row);
				$results[] = $mod;
			}

			return $results;
		}
	}

	public static function findAllByParams(array $data, $limit = null, $offset = null, $db = null) {
		if ($db == null)
			$db = ResManager::getDatabase();

		if (is_array($data)) {
			$cond = implode('=? AND ', array_keys($data)) . '=?';
			$r = $db->select(get_called_class(), '*', $cond, $limit, null, $offset, array_values($data));

			if (empty($r))
				throw new Exception('No data found', 25);

			else {
				$results = array();
				foreach ($r as $row) {
					$mod = new static($db);
					Self::row2obj($mod, $row);
					$results[] = $mod;
				}

				return $results;
			}
		}
	}

	public function exists($id) {
		$class = get_class($this);
		$n = new $class($this->__db);

		try {
			$n->findById($id);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function insert() {
		$data = array();
		$fkinserted = array();

		// fetch the tables referenced as foreign key
		// we insert this objects before to get their id
		foreach (Self::$__fklist[get_called_class()] as $reference => $fk) {
			if ($this->$reference != null) {
				if (!$this->$reference->exists($this->$reference->getId())) {
					$this->$reference->insert();
					$fkinserted[$reference] = $this->__db->getLastId();
				}
				else {
					$this->$reference->merge();
					$fkinserted[$reference] = $this->$reference->getId();
				}

				$data[$reference] = $this->$reference->getId();
			}
		}

		// data must be set through setProp, so we trust it's coherent
		// unless no data was inserted, in which case...

		foreach ($this->__model as $property => $attrib_list) {
			if (!in_array("null", $attrib_list) && empty($this->$property))
				throw new Exception('Property cannot be null: ' . $property, 27);

			if (reset($this->__model[$property]) != Model::TYPE_FOREIGNKEY)
				$data[$property] = $this->$property;

			// if it's a foreign key, we have the id from the previous loop
			else {
				if (array_key_exists($property, $data)) {
					$fkinserted[$reference];
					$data[$property] = $fkinserted[$reference];
				}
				else
					$data[$property] = null;
			}
		}

		if (is_numeric($this->__id) && ($this->__id >= 0))
			$data['id'] = $this->__id;

		$r = $this->__db->insert(get_class($this), $data);

		if (!$r)
			throw new Exception('Error inserting the new item', 28);
	}

	public function merge($cond = null) {
		if (!$cond)
			$cond['id'] = $this->__id;

		$data = array();
		$fkinserted = array();

		// fetch the tables referenced as foreign key
		// we insert this objects before to get their id

		foreach (Self::$__fklist[get_called_class()] as $reference => $fk) {
			if ($this->$reference != null) {
				if (!$this->$reference->exists($this->$reference->getId())) {
					$this->$reference->insert();
					$fkinserted[$reference] = $this->__db->getLastId();
				}
				else {
					$this->$reference->merge();
					$fkinserted[$reference] = $this->$reference->getId();
				}

				$data[$reference] = $this->$reference->getId();
			}
		}

		// data must be set through setProp, so we trust it's coherent
		// unless no data was inserted, in which case...

		foreach ($this->__model as $property => $attrib_list) {
			if (!in_array("null", $attrib_list) && empty($this->$property))
				throw new Exception('Property cannot be null: ' . $property, 27);

			if (reset($this->__model[$property]) != Model::TYPE_FOREIGNKEY)
				$data[$property] = $this->$property;


			// if it's a foreign key, we have the id from the previous loop
			else {
				if (array_key_exists($property, $data)) {
					$fkinserted[$reference];
					$data[$property] = $fkinserted[$reference];
				}
				else
					$data[$property] = null;
			}
		}

		if (is_numeric($this->__id) && ($this->__id > 0))
			$data['id'] = $this->__id;

		$r = $this->__db->update(get_class($this), $data, $cond);

		if (!$r)
			throw new Exception('Error updating this item', 29);
	}

	public function delete($id = null) {
		// can delete only if id is provided
		// or if findById was called successfully
		if (!$id && $this->__id)
			$id = $this->__id;

		if ($id)
			$this->__db->delete(get_class($this), $id);

		else
			throw new Exception('No id specified', 210);
	}
}
