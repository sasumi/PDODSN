<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;
use PDO;
use PDOException;
use function LFPhp\Func\get_max_socket_timeout;
use function LFPhp\Func\server_in_windows;

/**
 * Class MySQL
 * @package LFPhp\PDODSN\Database
 * @property string $unix_socket
 * @property int $connect_timeout
 * @property bool $persist
 * @property string $host
 * @property string $port
 * @property string $database
 * @property string $user
 * @property string $password
 * @property string $charset
 */
class MySQL extends DSN {
	public static function getDSNPrefix(){
		return 'mysql';
	}

	public static function getAttrDSNSegMap(){
		return [
			'unix_socket' => 'unix_socket',
			'host'        => 'host',
			'database'    => 'dbname',
			'port'        => 'port',
			'charset'     => 'charset',
			'user'        => 'user',
			'password'    => 'password',
		];
	}

	/**
	 * PDO连接
	 * @param array $ext_option
	 * @return \PDO
	 */
	public function pdoConnect(array $ext_option = []){
		$max_connect_timeout = isset($this->connect_timeout) ? $this->connect_timeout : get_max_socket_timeout(2);
		$opt = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		];
		if($max_connect_timeout){
			$opt[PDO::ATTR_TIMEOUT] = $max_connect_timeout;
		}
		if($this->persist){
			$opt[PDO::ATTR_PERSISTENT] = true;
		}
		//connect & process windows encode issue
		try {
			$conn = new PDO($this->__toString(), $this->user, $this->password, array_merge($opt, $ext_option));
		} catch(PDOException $e){
			if(server_in_windows()){
				//convert gbk message to utf8
				throw new PDOException(mb_convert_encoding($e->getMessage(), 'utf-8', 'gbk'), $e->getCode(), $e->getPrevious());
			} else {
				throw $e;
			}
		}
		return $conn;
	}
}