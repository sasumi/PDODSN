<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;
use LFPhp\PDODSN\Exception\DSNException;

/**
 * URI
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
				throw new DSNException("DSN resolve fail, file no exists:$segment");
			}
			$str = trim(file_get_contents($file));
			return static::resolveString($str);
		}
		throw new DSNException("File no detected in uri:$segment");
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

	/**
	 * @return array|void
	 * @throws \LFPhp\PDODSN\Exception\DSNException
	 */
	public static function getAttrDSNSegMap(){
		throw new DSNException('no support yet');
	}
}
