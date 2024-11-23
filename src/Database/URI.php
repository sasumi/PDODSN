<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;
use LFPhp\PDODSN\Exception\DsnException;

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
				throw new DsnException("DSN resolve fail, file no exists:$segment");
			}
			$str = trim(file_get_contents($file));
			return static::resolveString($str);
		}
		throw new DsnException("File no detected in uri:$segment");
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
	 * @throws \LFPhp\PDODSN\Exception\DsnException
	 */
	public static function getAttrDSNSegMap(){
		throw new DsnException('no support yet');
	}
}
