<?php

namespace Aspecto\Repository;

class ConnectionFactory {

	private static $conn;

	public static function getConnection() {
		if(static::$conn == null) {
			static::$conn = new \PDO("mysql:host=localhost;dbname=contatos_goaop", "root", "123456");
		}

		return static::$conn;
	}

}