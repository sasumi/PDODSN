<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;
use LFPhp\PDODSN\Exception\DsnException;
use PDO;

/**
 * Class SQLite
 * @package LFPhp\PDODSN\Database
 * @example $dsn = "sqlite:/opt/databases/mydb.sq3";
 * @example $dsn = "sqlite::memory:";
 * @property string $database SQLite数据库不需要database
 */
class SQLite extends DSN {
	private $file = '';
	private $memory = false;

	public static function getDSNPrefix(){
		return 'sqlite';
	}

	public static function resolveSegment($segment){
		$dsn_obj = new static();
		if(strcasecmp($segment, 'memory:') === 0){
			$dsn_obj->memory = true;
			return $dsn_obj;
		}
		if(!is_file($segment)){
			throw new DsnException("Database file no exists:$segment");
		}
		$dsn_obj->file = $segment;
		return $dsn_obj;
	}

	public function __toString(){
		$dsn_str = self::getDSNPrefix();
		if($this->memory){
			return $dsn_str.':memory:';
		}
		if($this->file){
			return $dsn_str.':'.$this->file;
		}
		return $dsn_str.':';
	}

	public static function getAttrDSNSegMap(){
		return [];
	}

	public function pdoConnect(array $ext_option = []){
		return new PDO($this->__toString(), null, null, $this->getPdoOption($ext_option));
	}
}