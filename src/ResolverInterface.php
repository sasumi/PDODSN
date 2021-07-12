<?php

namespace LFPhp\PDODSN;

/**
 * DNS resolve interface
 */
interface ResolverInterface {
	public static function resolveSegment($segment);

	public static function getDSNPrefix();
}
