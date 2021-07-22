<?php

namespace LFPhp\PDODSN;

use Exception;
use LFPhp\PDODSN\Database\Firebird;
use LFPhp\PDODSN\Database\Informix;
use LFPhp\PDODSN\Database\MySQL;
use LFPhp\PDODSN\Database\ODBC;
use LFPhp\PDODSN\Database\PostgreSQL;
use LFPhp\PDODSN\Database\SQLite;
use LFPhp\PDODSN\Database\SQLServer;
use LFPhp\PDODSN\Database\URI;
use function LFPhp\Func\explode_by;

/**
 * 数据库配置对象
 */
abstract class DSN implements DNSInterface {
	protected $values = [];

	/** @var DSN[] */
	const DRIVER_LIST = [
		Firebird::class,
		Informix::class,
		MySQL::class,
		ODBC::class,
		PostgreSQL::class,
		SQLite::class,
		SQLServer::class,
		URI::class,
	];

	/**
	 * auto set values
	 * @param $name
	 * @param $value
	 */
	public function __set($name, $value){
		$this->values[$name] = $value;
	}

	/**
	 * get values
	 * @param $name
	 * @return mixed
	 */
	public function __get($name){
		return $this->values[$name];
	}

	/**
	 * @param $segment
	 * @return \LFPhp\PDODSN\DSN
	 * @throws \Exception
	 */
	public static function resolveSegment($segment){
		$field_map = static::getFieldMap();
		if(!$field_map){
			throw new Exception("No field map return");
		}
		$dsn_obj = new static();
		$segments = explode_by(';', $segment);
		foreach($segments as $seg){
			list($k, $v) = explode_by('=', $seg);
			$found = false;
			foreach($field_map as $field => $m){
				if(strcasecmp($m, $k) === 0){
					$dsn_obj->{$field} = $v;
					$found = true;
				}
			}
			if(!$found){
				throw new Exception("DSN key no supported: $seg");
			}
		}
		return $dsn_obj;
	}

	/**
	 * 生成DSN字符串
	 * @return string
	 */
	public function __toString(){
		$field_map = static::getFieldMap();
		if($field_map){
			$p = static::getDSNPrefix().':';
			$comma = '';
			foreach($field_map as $k => $field){
				if(isset($this->{$k})){
					$p .= $comma."$field=".$this->{$k};
					$comma = ';';
				}
			}
			return $p;
		}
		return null;
	}

	/**
	 * 解析DSN字符串
	 * @param $dsn_str
	 * @return static
	 * @throws \Exception
	 */
	public static function resolveString($dsn_str){
		if(preg_match('/^(\w+):(.+)$/i', $dsn_str, $matches)){
			foreach(self::DRIVER_LIST as $driver){
				if(strcasecmp($driver::getDSNPrefix(), $matches[1]) === 0){
					return $driver::resolveSegment($matches[2]);
				}
			}
		}
		throw new Exception("No driver found:$dsn_str");
	}
}
