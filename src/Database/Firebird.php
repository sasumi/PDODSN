<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;
use PDO;

/**
 * Class Firebird
 * @package LFPhp\PDODSN\Database
 * @property string $database
 * @property string $role
 * @property string $charset
 * @property string $dialect
 * @property string $user
 * @property string $password
 */
class Firebird extends DSN {
	public static function getDSNPrefix(){
		return 'firebird';
	}

	public static function getAttrDSNSegMap(){
		return [
			'database' => 'dbname',
			'charset'  => 'charset',
			'role'     => 'role',
			'dialect'  => 'dialect',
		];
	}

	public function pdoConnect(array $ext_option = []){
		return new PDO($this->__toString(), $this->user, $this->password);
	}
}