<?php
namespace LFPhp\PDODSN\Database;

use Exception;
use LFPhp\PDODSN\DSN;

/**
 * URI模式DSN，暂不支持实例化
 * @package LFPhp\PDODSN\Database
 */
abstract class URI extends DSN {
	/**
	 * @param $segment
	 * @return \LFPhp\PDODSN\Database\URI
	 * @throws \Exception
	 */
	public static function resolveSegment($segment){
		if(preg_match('/^file:\/\/(.*)$/i', $segment, $matches)){
			$file = $matches[1];
			if(!is_file($file)){
				throw new Exception("DSN resolve fail, file no exists:$segment");
			}
			$str = trim(file_get_contents($file));
			return static::resolveString($str);
		}
		throw new Exception("File no detected in uri:$segment");
	}

	public static function getDSNPrefix(){
		return 'uri';
	}

	/**
	 * @return string;
	 * @throws \Exception
	 */
	public function __toString(){
		return '';
	}

	public static function getFieldMap(){
		throw new Exception('no support yet');
	}
}