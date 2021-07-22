<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;

/**
 * Class ODBC
 * @package LFPhp\PDODSN\Database
 * @property string $driver
 * @property string $user
 * @property string $password
 * @property string $host
 * @property string $port
 * @property string $database
 * @property string $database_file
 * @property string $protocol
 */
class ODBC extends DSN {
	public static function getDSNPrefix(){
		return 'odbc';
	}

	public static function getFieldMap(){
		return [
			'driver'        => 'DRIVER',
			'host'          => 'HOSTNAME',
			'port'          => 'PORT',
			'protocol'      => 'PROTOCOL',
			'database'      => 'DATABASE',
			'user'          => 'UID',
			'password'      => 'PWD',
			'database_file' => 'Dbq',
		];
	}
}