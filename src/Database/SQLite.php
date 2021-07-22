<?php
namespace LFPhp\PDODSN\Database;

use Exception;
use LFPhp\PDODSN\DSN;

/**
 * Class SQLite
 * @package LFPhp\PDODSN\Database
 * @example $dsn = "sqlite:/opt/databases/mydb.sq3";
 * @example $dsn = "isqlite::memory:";
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
			throw new Exception("Database file no exists:$segment");
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

	public static function getFieldMap(){
		return [];
	}
}