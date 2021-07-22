<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;

/**
 * Class MySQL
 * @package LFPhp\PDODSN\Database
 * @property string $unix_socket
 * @property string $host
 * @property string $port
 * @property string $database
 * @property string $user
 * @property string $password
 * @property string $charset
 */
class MySQL extends DSN {
	public static function getDSNPrefix(){
		return 'mysql';
	}

	/**
	 * fix charset dashes
	 * @param $name
	 * @param $value
	 */
	public function __set($name, $value){
		if(strcasecmp($name, 'charset') === 0){
			$value = str_replace('-', '', $value);
		}
		parent::__set($name, $value);
	}

	public static function getFieldMap(){
		return [
			'unix_socket' => 'unix_socket',
			'host'        => 'host',
			'port'        => 'port',
			'charset'     => 'charset',
			'user'        => 'user',
			'password'    => 'password',
		];
	}
}