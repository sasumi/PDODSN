<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;
use PDO;

/**
 * Class PostgreSQL
 * @package LFPhp\PDODSN\Database
 * @property string $host
 * @property string $port
 * @property string $database
 * @property string $user
 * @property string $password
 * @example $dsn_str = 'pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass';
 */
class PostgreSQL extends DSN {
	public static function getDSNPrefix(){
		return 'pgsql';
	}

	public static function getAttrDSNSegMap(){
		return [
			'host'     => 'host',
			'port'     => 'port',
			'database' => 'dbname',
			'user'     => 'user',
			'password' => 'password',
		];
	}

	public function pdoConnect(array $ext_option = []){
		return new PDO($this->__toString(), $this->user, $this->password, $this->getPdoOption($ext_option));
	}
}