<?php
namespace LFPhp\PDODSN\Database;

use Exception;
use LFPhp\PDODSN\DSN;

class ODBC extends DSN {
	public $driver;
	public $user;
	public $password;
	public $host;
	public $port;
	public $database;
	public $database_file;
	public $protocol;

	public static function getDSNPrefix(){
		return 'odbc';
	}

	/**
	 * @param $segment
	 * @return \LFPhp\PDODSN\Database\ODBC
	 * @throws \Exception
	 */
	public static function resolveSegment($segment){
		if(preg_match('/^\w+$/', $segment, $matches)){
			$str = ini_get("pdo.dsn.{$matches[1]}");
			return static::resolveString($str);
		}
		$dsn_obj = new static();
		$segments = explode(';', $segment);
		foreach($segments as $seg){
			list($k, $v) = explode('=', $seg);
			switch(strtolower($k)){
				case 'driver':
					$dsn_obj->driver = $v;
					break;
				case 'hostname':
					$dsn_obj->host = $v;
					break;
				case 'port':
					$dsn_obj->port = $v;
					break;
				case 'database':
					$dsn_obj->database = $v;
					break;
				case 'dbq':
					$dsn_obj->database_file = $v;
					break;
				case 'protocol':
					$dsn_obj->protocol = $v;
					break;

				//扩展支持 user & password
				case 'uid':
					$dsn_obj->user = $v;
					break;
				case 'pwd':
					$dsn_obj->password = $v;
					break;
				default:
					throw new Exception("DSN key no supported: $seg");
			}
		}
		throw new Exception('ODBC segment resolve fail');
	}

	public function __toString(){
		$dsn_prefix = self::getDSNPrefix();
		$segments = [];
		$this->driver && $segments[] = "DRIVER=".$this->database;
		$this->host && $segments[] = "HOSTNAME=".$this->host;
		$this->port && $segments[] = "PORT=".$this->port;
		$this->protocol && $segments[] = "PROTOCOL=".$this->protocol;
		$this->database && $segments[] = "DATABASE=".$this->database;
		$this->user && $segments[] = "UID=".$this->user;
		$this->password && $segments[] = "PWD=".$this->password;
		$this->database_file && $segments[] = "Dbq=".$this->database_file;
		return $dsn_prefix.':'.join(';', $segments);
	}
}