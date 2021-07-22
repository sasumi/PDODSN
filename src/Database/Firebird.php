<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;

/**
 * Class Firebird
 * @package LFPhp\PDODSN\Database
 * @property string $database
 * @property string $role
 * @property string $charset
 * @property string $dialect
 */
class Firebird extends DSN {
	public static function getDSNPrefix(){
		return 'firebird';
	}

	public static function getFieldMap(){
		return [
			'database' => 'dbname',
			'charset'  => 'charset',
			'role'     => 'role',
			'dialect'  => 'dialect',
		];
	}
}