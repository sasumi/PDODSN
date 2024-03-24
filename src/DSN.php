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
 * 数据库配置对象
 */
abstract class DSN implements DNSInterface, ArrayAccess {
	public $persist = false; //是否长连接
	public $connect_timeout = null; //超时时间
	public $max_reconnect_count = 0; //最大重新连接次数
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
	 * 获取PDO配置
	 * @param array $ext_option 优先配置
	 * @return array PDO 配置数组
	 */
	protected function getPdoOption($ext_option = []){
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
	 * 生成DSN字符串，只取 DSN SEG MAP中支持属性
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
			throw new DsnException('Method no callable via DSN.');
		}
		if(!$config){
			return;
		}
		// 不需要按照 DSN定义设置，额外支持更多属性控制
		// $attrs = array_keys(static::getAttrDSNSegMap());
		foreach($config as $attr=>$val){
			$this->{$attr} = $val;
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
