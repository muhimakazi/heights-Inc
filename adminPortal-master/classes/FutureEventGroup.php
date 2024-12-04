<?php
class FutureEventGroup

{
	private $_db,
	$_data,
	$_count = 0,
	$_sessionName,
	$_cookieName,
	$_isLoggedIn,
	$_errors = array();

	public function __construct($user = null)
	{
		$this->_db = DB::getInstance();

		if ($user) {

		}
	}

	public function insert($fields = array())
	{
		if (!$this->_db->insert('future_group_registration', $fields))
			throw new Exception("There was a problem inserting.");
	}

	public function update($fields = array(), $id = null)
	{
		if (!$this->_db->update('future_group_registration', $id, $fields))
			throw new Exception('There was a problem updating');
	}

	public function insertSlot($fields = array())
	{
		if (!$this->_db->insert('future_group_registration_slots', $fields))
			throw new Exception("There was a problem inserting.");
	}

	public function updateSlot($fields = array(), $id = null)
	{
		if (!$this->_db->update('future_group_registration_slots', $id, $fields))
			throw new Exception('There was a problem updating');
	}

	public function find($user = null, $limit = null)
	{
		if ($user) {
			$hit = false;
			if (is_numeric($user)) {
				$field = 'ID';
				$data = $this->_db->get('future_group_registration', array($field, '=', $user), $limit);
				if ($data->count()) {
					$this->_data = $data->first();
					$hit = true;
				}
			}

			if ($hit == false) {
				if ($this->findUserByPhone($user)) {
					return true;
				}
			}
			else {
				return true;
			}
		}
		return false;
	}

	public function select($sql = null)
	{
		$data = $this->_db->query("SELECT* FROM `future_group_registration` {$sql}");
		if ($data->count()) {
			$this->_count = $data->count();
			$this->_data = $data->results();
		}
	}

	public function selectQuery($sql, $params = array())
	{
		$data = $this->_db->query($sql, $params);
		if ($data->count()) {
			$this->_count = $data->count();
			$this->_data = $data->results();
		}
	}

	public function exists()
	{
		return (!empty($this->_data)) ? true : false;
	}

	public function data()
	{
		return $this->_data;
	}

	public function first()
	{
		$data = $this->data();
		if (isset($data[0])) {
			return $data[0];
		}
		return '';
	}

	public function count()
	{
		return $this->_count;
	}

	private function addError($error)
	{
		$this->_errors[] = $error;
	}

	public function errors()
	{
		return $this->_errors;
	}

}
?> 