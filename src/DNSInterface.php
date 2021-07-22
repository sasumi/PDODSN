<?php

namespace LFPhp\PDODSN;

/**
 * DNS resolve interface
 */
interface DNSInterface {
	/**
	 * 解析子块
	 * @param string $segment
	 * @return static
	 */
	public static function resolveSegment($segment);

	/**
	 * @return array
	 */
	public static function getFieldMap();

	/**
	 * 获取DSN模块前缀
	 * @return string
	 */
	public static function getDSNPrefix();
}
