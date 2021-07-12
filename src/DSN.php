<?php

namespace LFPhp\PDODSN;

use Exception;
use LFPhp\PDODSN\Database\MySQL;
use LFPhp\PDODSN\Database\ODBC;
use LFPhp\PDODSN\Database\SQLServer;
use LFPhp\PDODSN\Database\URI;

/**
 * 数据库配置对象
 */
abstract class DSN {
	public $type;

	const DRIVER_LIST = [
		URI::class,
		MySQL::class,
		SQLServer::class,
		ODBC::class,
	];

	public function __construct(){
		$this->type = get_called_class();
	}

	protected abstract static function resolveSegment($segment);
	protected abstract static function getDSNPrefix();

	/**
	 * convert to DSN string
	 * @return string
	 */
	public abstract function __toString();

	/**
	 * 解析DSN字符串
	 * @param $dsn_str
	 * @return static
	 * @throws \Exception
	 */
	public static function resolveString($dsn_str){
		if(preg_match('/^(\w+):(.+)$/i', $dsn_str, $matches)){
			/** @var DSN $driver */
			foreach(self::DRIVER_LIST as $driver){
				if(strcasecmp($driver::getDSNPrefix(), $matches[1]) === 0){
					return $driver::resolveSegment($matches[2]);
				}
			}
		}
		throw new Exception("No driver found:$dsn_str");
	}
}
