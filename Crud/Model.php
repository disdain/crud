<?php namespace Crud;

class Model {

	private $name;

	private $data;

	public function __construct ($name, $data = array()) {
		$this->name = $name;
		$this->data = $data;
	}

	public function __get ($key) {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}

	public function __set ($key, $value) {
		$this->data[$key] = $value;
	}

	public function name ($name = null) {
		return $name ? ($this->name = $name) : $this->name;
	}

	public function data ($data = null, $value = null) {
		switch (func_num_args()) {
		case 1:
			return is_array($data) ? ($this->data = array_merge($this->data, $data)) : $this->__get($data);

		case 2:
			return $this->__set($data, $value);

		default:
			return $this->data;
		}
	}

}
