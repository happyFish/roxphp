<?php
/**
 * Model
 *
 * This Software is released under the MIT License.
 * See license.txt for more details.
 *
 * @package	rox
 * @author Ramon Torres
 * @copyright Copyright (c) 2008 Ramon Torres
 * @license http://roxphp.com/static/license.html
 * @link http://roxphp.com 
 * @access public
 */
class Model extends Object {
	var $name = null;
	var $table = null;
	var $primaryKey = 'id';
	var $data = null;
	var $id = null;

	private $datasource = null;

  /**
   * Class constructor
   *
   * @return
   */
	function __construct() {
		$this->datasource = Registry::getObject('DataSource');
	}

  /**
   * Resets the model data
   *
   * @param mixed $data
   * @return
   */
	function create($data) {
		//reset the id
		$this->id = null;
		$this->data = $data;
	}

  /**
   * Model::save()
   *
   * @param mixed $data
   * @return boolean
   */
	function save($data = null) {
		if (!empty($data)) {
			$this->data = $data;
		}

		if (empty($this->id) || !$this->exists($this->id)) {
			$values = array_values($this->data[$this->name]);
	
			foreach($values as &$v) {
				$v = "'" . $this->datasource->escape($v) . "'";
			}

			$fields = implode(', ', array_keys($this->data[$this->name]));
			$values = implode(', ', $values);

			$this->datasource->execute("INSERT INTO `{$this->table}` ({$fields}) VALUES ({$values})");
		} else {
			$updateData = '';
			foreach($this->data[$this->name] as $f => $v) {
				$updateData .= $f . " = '" . $this->datasource->escape($v) . "'";
			}

			$this->datasource->execute("UPDATE `{$this->table}` SET {$updateData} WHERE {$this->primaryKey} = {$this->id}");
		}

		$saved = $this->datasource->affectedRows() == 1;
		if (!$saved) {
			return false;	
		}

		$this->id = $this->datasource->lastInsertedID();
		return $saved;
	}

  /**
   * Returns true if a record exists
   *
   * @param mixed $id
   * @return boolean
   */
	function exists($id) {
		$sql = "SELECT COUNT(*) AS `count` FROM `{$this->table}` WHERE `{$this->primaryKey}` = {$id} LIMIT 1";
		$result = $this->datasource->query($sql);
		return (boolean)$result[0]['count'];
	}

  /**
   * Model::read()
   *
   * @param mixed $fields
   * @param integer $id
   * @return array
   */
	function read($fields = null, $id = null) {
		if (empty($fields)) {
			$fields = '*';
		} else if (is_array($fields)) {
			$fields = implode(', ', $fields);
		}

		$sql = "SELECT {$fields} FROM `{$this->table}` WHERE `{$this->primaryKey}` = {$id}";
		$result = $this->datasource->query($sql);
		return array($this->name => $result[0]);
	}

  /**
   * Deletes a record
   *
   * @param integer $id
   * @return boolean
   */
	function delete($id = null) {
		if (empty($id)) {
			$id = $this->id;
		}

		if (empty($id)) {
			return false;
		}

		$this->datasource->execute("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = {$id}");
		return $this->datasource->affectedRows() > 0;
	}

  /**
   * Performs a SQL query
   *
   * @param string $sql
   * @return mixed
   */
	function query($sql) {
		return $this->datasource->query($sql); 
	}
}
?>