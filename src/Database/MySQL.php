<?php
namespace LFPhp\PDODSN\Database;

use LFPhp\PDODSN\DSN;
use LFPhp\PDODSN\Exception\ConnectException;
use PDO;
use PDOException;
use function LFPhp\Func\server_in_windows;

/**
 * Class MySQL
 * @package LFPhp\PDODSN\Database
 * @property string $unix_socket
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
	 * @throws \LFPhp\PDODSN\Exception\ConnectException
	 */
	public function pdoConnect(array $ext_option = []){
		try{
			$conn = new PDO($this->__toString(), $this->user, $this->password, $this->getPdoOption($ext_option));
		}catch(PDOException $e){
			if(server_in_windows()){
				//convert gbk message to utf8
				throw new ConnectException(mb_convert_encoding($e->getMessage(), 'utf-8', 'gbk'), ['dsn' => $this]);
			}
			throw new ConnectException($e->getMessage(), ['dsn' => $this]);
		}
		return $conn;
	}

	/**
	 * 兼容MySQL charset表达式
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value){
		if($name === 'charset'){
			$value = str_replace('-', '', $value);
		}
		return parent::__set($name, $value);
	}
}