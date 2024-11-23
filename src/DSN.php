<?php

namespace LFPhp\PDODSN;

use ArrayAccess;
use LFPhp\PDODSN\Database\Firebird;
use LFPhp\PDODSN\Database\Informix;
use LFPhp\PDODSN\Database\MySQL;
use LFPhp\PDODSN\Database\ODBC;
use LFPhp\PDODSN\Database\PostgreSQL;
use LFPhp\PDODSN\Database\SQLite;
use LFPhp\PDODSN\Database\SQLServer;
use LFPhp\PDODSN\Database\URI;
use LFPhp\PDODSN\Exception\DsnException;
use PDO;
use function LFPhp\Func\explode_by;
use function LFPhp\Func\get_max_socket_timeout;

/**
 * DSN Class
 */
abstract class DSN implements DNSInterface, ArrayAccess {
	public $persist = false; //persist connect
	public $connect_timeout = null; //connect timeout
	public $max_reconnect_count = 0; //max reconnect count
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
	 * get PDO option
	 * @param array $ext_option Priority configuration
	 * @return array PDO configuration array
	 */
	protected function getPdoOption(array $ext_option = []){
		$max_connect_timeout = isset($this->connect_timeout) ? $this->connect_timeout : get_max_socket_timeout(2);
		$pdo_option = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		];
		if($max_connect_timeout){
			$pdo_option[PDO::ATTR_TIMEOUT] = $max_connect_timeout;
		}
		if($this->persist){
			$pdo_option[PDO::ATTR_PERSISTENT] = true;
		}
		foreach($ext_option as $f=>$v){
			$pdo_option[$f] = $v;
		}
		return $pdo_option;
	}

	/**
	 * @param string $segment
	 * @return \LFPhp\PDODSN\DSN
	 * @throws \Exception
	 */
	public static function resolveSegment($segment){
		$field_map = static::getAttrDSNSegMap();
		if(!$field_map){
			throw new DsnException("No attribute-dsn seg map define.");
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
				throw new DsnException("DSN key no supported: $seg");
			}
		}
		return $dsn_obj;
	}

	/**
	 * Generate a DSN string and only take the supported attributes in the DSN SEG MAP
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
	 * @param array $config
	 * @throws \Exception
	 */
	public function __construct(array $config = []){
		$class = get_called_class();
		if($class == self::class){
			throw new DsnException('Method no callable via DSN.');
		}
		if(!$config){
			return;
		}
		// No need to set according to DSN definition, additionally supports more attribute control
		// $attrs = array_keys(static::getAttrDSNSegMap());
		foreach($config as $attr=>$val){
			$this->{$attr} = $val;
		}
	}

	/**
	 * Parse the DSN object from the array. This method needs to be called in the subclass.
	 * @param array $config
	 * @return static
	 * @throws \Exception
	 */
	public static function resolveArray(array $config){
		return new static($config);
	}

	/**
	 * resolve DSN string to DSN Object
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
		throw new DsnException("No driver found:$dsn_str");
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
