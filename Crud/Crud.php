<?php namespace Crud;

class Crud {

	private static $config = array(
		'db' => 'mysql:host=localhost;dbname=test',
		'user' => '',
		'pass' => ''
	);

	private static $mapper;

	protected static function init () {
		if (self::$mapper === null) {
			self::$mapper = new Mapper(self::$config);
		}
	}

	public static function config ($data) {
		self::$config = array_merge(self::$config, $data);
	}

	public static function create ($model, $data = array()) {
		self::init();
		return new Model($model, $data);
	}

	public static function delete (Model $model) {
		self::init();
		return self::$mapper->delete($model);
	}

	public static function read ($model, $where = array()) {
		$result = self::read_all($model, $where, 1);
		return count($result) ? $result[0] : null;
	}

	public static function read_all ($model, $where = array(), $limit = 0, $order = '') {
		self::init();

		if (!is_array($where)) {
			$where = array('id' => $where);
		}

		return self::$mapper->select($model, $where, $limit, $order);
	}

	public static function save (Model $model) {
		self::init();
		return $model->id ? self::$mapper->update($model) : self::$mapper->insert($model);
	}

}
