<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;

/**
 * Class Informix
 * @package LFPhp\PDODSN\Database
 * @property string $host
 * @property string $server
 * @property string $service
 * @property string $database
 * @property string $user
 * @property string $password
 * @property string $protocol
 * @property string $enable_scrollable_cursors
 * @example $dsn = "informix:host=host.domain.com; service=9800;
database=common_db; server=ids_server; protocol=onsoctcp;
EnableScrollableCursors=1";
 */
class Informix extends DSN {
	public static function getDSNPrefix(){
		return 'informix';
	}

	public static function getFieldMap(){
		return [
			'host'                      => 'host',
			'server'                    => 'server',
			'service'                   => 'service',
			'database'                  => 'database',
			'user'                      => 'user',
			'password'                  => 'password',
			'protocol'                  => 'protocol',
			'enable_scrollable_cursors' => 'EnableScrollableCursors',
		];
	}
}