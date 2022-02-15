<?php

namespace LFPhp\PDODSN;

use ArrayAccess;
use Exception;
use LFPhp\PDODSN\Database\Firebird;
use LFPhp\PDODSN\Database\Informix;
use LFPhp\PDODSN\Database\MySQL;
use LFPhp\PDODSN\Database\ODBC;
use LFPhp\PDODSN\Database\PostgreSQL;
use LFPhp\PDODSN\Database\SQLite;
use LFPhp\PDODSN\Database\SQLServer;
use LFPhp\PDODSN\Database\URI;
use PDO;
use function LFPhp\Func\explode_by;

/**
 * 数据库配置对象
 */
abstract class DSN implements DNSInterface, ArrayAccess {
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
	 * PDO连接
	 * @param array $ext_option
	 * @return PDO
	 */
	abstract public function pdoConnect(array $ext_option = []);

	/**
	 * @param string $segment
	 * @return \LFPhp\PDODSN\DSN
	 * @throws \Exception
	 */
	public static function resolveSegment($segment){
		$field_map = static::getAttrDSNSegMap();
		if(!$field_map){
			throw new Exception("No attribute-dsn seg map define.");
		}
		$dsn_obj = new static();
		$segments = explode_by(';', $segment);
		foreach($segments as $seg){
			list($k, $v) = explode_by('=', $seg);
			$found = false;
			foreach($field_map as $attr => $dsn_seg){
				if(strcasecmp($dsn_seg, $k) === 0){
					$dsn_obj->{$attr} = $v;
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
		$field_map = static::getAttrDSNSegMap();
		if($field_map){
			$p = static::getDSNPrefix().':';
			$comma = '';
			foreach($field_map as $attr => $dsn_seg){
				if($this->{$attr}){
					$p .= $comma."$dsn_seg=".$this->{$attr};
					$comma = ';';
				}
			}
			return $p;
		}
		return null;
	}

	/**
	 * DSN constructor.
	 * @param $config
	 * @throws \Exception
	 */
	public function __construct($config = []){
		$class = get_called_class();
		if($class == self::class){
			throw new Exception('Method no callable via DSN.');
		}
		if(!$config){
			return;
		}
		$attrs = array_keys(static::getAttrDSNSegMap());
		foreach($attrs as $attr){
			if(isset($config[$attr])){
				$this->{$attr} = $config[$attr];
			}
		}
	}

	/**
	 * 从数组中解析出DSN对象，该方法需要在子类中调用。
	 * @param array $config
	 * @return static
	 * @throws \Exception
	 */
	public static function resolveArray($config){
		return new static($config);
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

	public function offsetExists($offset){
		return isset($this->{$offset});
	}

	public function offsetGet($offset){
		return $this->{$offset};
	}

	public function offsetSet($offset, $value){
		$this->__set($offset, $value);
	}

	public function offsetUnset($offset){
		$this->{$offset} = null;
	}
}
