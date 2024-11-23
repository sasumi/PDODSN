<?php

namespace LFPhp\PDODSN;

/**
 * DNS resolve interface
 */
interface DNSInterface {
	/**
	 * resolve dsn string segment
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
	 * get DSN prefix
	 * @return string
	 */
	public static function getDSNPrefix();
}
