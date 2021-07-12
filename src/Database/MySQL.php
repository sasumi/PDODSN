<?php
namespace LFPhp\PDODSN\Database;

use Exception;
use LFPhp\PDODSN\DSN;

class MySQL extends DSN {
	public $unix_socket;
	public $host;
	public $port;
	public $database;
	public $user;
	public $password;
	public $charset;

	/**
	 * @param $segment
	 * @return static
	 * @throws \Exception
	 */
	public static function resolveSegment($segment){
		$dsn_obj = new static();
		$segments = explode(';', $segment);
		foreach($segments as $seg){
			list($k, $v) = explode('=', $seg);
			switch($k){
				case 'dbname':
					$dsn_obj->database = $v;
					break;
				case 'host':
					$dsn_obj->host = $v;
					break;
				case 'charset':
					$dsn_obj->charset = $v;
					break;
				case 'port':
					$dsn_obj->port = $v;
					break;
				case 'unix_socket':
					$dsn_obj->unix_socket = $v;
					break;

				//扩展支持 user & password
				case 'user':
					$dsn_obj->user = $v;
					break;
				case 'password':
					$dsn_obj->password = $v;
					break;
				default:
					throw new Exception("DSN key no supported: $seg");
			}
		}
		return $dsn_obj;
	}

	public static function getDSNPrefix(){
		return 'mysql';
	}

	public function __toString(){
		$dsn_prefix = self::getDSNPrefix();
		$segments = [];
		$this->unix_socket && $segments[] = "unix_socket=".$this->database;
		$this->host && $segments[] = "host=".$this->host;
		$this->port && $segments[] = "port=".$this->port;
		$this->charset && $segments[] = "charset=".$this->charset;
		$this->database && $segments[] = "dbname=".$this->database;
		return $dsn_prefix.':'.join(';', $segments);
	}
}