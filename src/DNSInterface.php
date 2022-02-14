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
	 * field map to DSN fragments
	 * @return array
	 */
	public static function getAttrDSNSegMap();

	/**
	 * 获取DSN模块前缀
	 * @return string
	 */
	public static function getDSNPrefix();
}
