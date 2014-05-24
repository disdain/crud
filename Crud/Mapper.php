<?php namespace Crud;

class Mapper {

	private static $config;

	private static $pdo;

	protected function connect () {
		if (self::$pdo === null) {
			$db = self::$config['db'];
			$user = self::$config['user'];
			$pass = self::$config['pass'];
			self::$pdo = new \PDO($db, $user, $pass);
			self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
	}

	public function __construct ($config) {
		self::$config = $config;
	}

	public function delete (Model $model) {
		$this->connect();

		$name = $model->name();

		$query = "DELETE FROM $name WHERE id = :id";

		$st = self::$pdo->prepare($query);
		$st->execute(array('id' => $model->id));

		return $st->rowCount() > 0;
	}

	public function insert (Model $model) {
		$this->connect();

		$name = $model->name();
		$data = $model->data();

		$keys = array_keys($data);

		$query = "INSERT INTO $name (" . implode(', ', $keys) . ") VALUES (:" . implode(', :', $keys) . ")";

		$st = self::$pdo->prepare($query);
		$st->execute($data);

		return $st->rowCount() ?  $model->id = self::$pdo->lastInsertId() : false;
	}

	public function select ($model, $where = array(), $limit = 0, $order = '') {
		$this->connect();

		$query = "SELECT * FROM $model";

		if (count($where)) {
			$keys = array();
			foreach ($where as $key => $value) array_push($keys, "$key = :$key");
			$query .= " WHERE " . implode(" AND ", $keys);
		}

		if ($limit) {
			$query .= " LIMIT $limit";
		}

		if ($order) {
			$query .= " ORDER BY $order";
		}

		$st = self::$pdo->prepare($query);
		$st->execute($where);

		$result = array();

		if ($st->rowCount()) {
			while (($row = $st->fetch(\PDO::FETCH_ASSOC))) {
				array_push($result, new Model($model, $row));
			}
		}

		return $result;
	}

	public function update (Model $model) {
		$this->connect();

		$name = $model->name();
		$data = $model->data();

		$query = "UPDATE $name SET ";

		$keys = array();
		foreach ($data as $key => $value) {
			if ($key !== 'id') {
				array_push($keys, "$key = :$key");
			}
		}

		$query .= implode(', ', $keys) . " WHERE id = :id";

		var_dump($query);

		$st = self::$pdo->prepare($query);
		$st->execute($data);

		return $st->rowCount() > 0;
	}

}
