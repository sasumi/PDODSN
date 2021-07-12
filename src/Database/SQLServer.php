<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;

class SQLServer extends DSN {
	public $app;
	public $connection_pooling;
	public $database;
	public $encrypt;
	public $failover_partner;
	public $login_timeout;
	public $multiple_active_result_sets;
	public $quoted_id;
	public $server;
	public $trace_file;
	public $trace_on;
	public $transaction_isolation;
	public $trust_server_certificate;
	public $wsid;

	public static function getDSNPrefix(){
		return 'sqlsrv';
	}

	public static function resolveSegment($segment){
	}

	public function __toString(){
		// TODO: Implement __toString() method.
	}
}