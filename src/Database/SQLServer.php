<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;
use PDO;

/**
 * Class SQLServer
 * @package LFPhp\PDODSN\Database
 * @property string $app
 * @property string $connection_pooling
 * @property string $database
 * @property string $encrypt
 * @property string $failover_partner
 * @property string $login_timeout
 * @property string $multiple_active_result_sets
 * @property string $quoted_id
 * @property string $server
 * @property string $trace_file
 * @property string $trace_on
 * @property string $transaction_isolation
 * @property string $trust_server_certificate
 * @property string $wsid
 * @property int $attr_query_timeout 查询超时时间
 */
class SQLServer extends DSN {
	public static function getDSNPrefix(){
		return 'sqlsrv';
	}

	public static function getAttrDSNSegMap(){
		return [
			'app'                         => 'APP',
			'server'                      => 'Server',
			'connection_pooling'          => 'ConnectionPooling',
			'database'                    => 'Database',
			'encrypt'                     => 'Encrypt',
			'failover_partner'            => 'FailoverPartner',
			'login_timeout'               => 'LoginTimeout',
			'multiple_active_result_sets' => 'MultipleActiveResultSets',
			'quoted_id'                   => 'QuotedId',
			'trace_file'                  => 'TraceFile',
			'trace_on'                    => 'TraceOn',
			'transaction_isolation'       => 'TransactionIsolation',
			'trust_server_certificate'    => 'TrustServerCertificate',
			'wsid'                        => 'WSID',
		];
	}

	public function pdoConnect(array $ext_option = []){
		if($this->attr_query_timeout){
			$ext_option[PDO::SQLSRV_ATTR_QUERY_TIMEOUT] = $this->attr_query_timeout;
		}
		return new PDO($this->__toString(), '', '', $this->getPdoOption($ext_option));
	}
}