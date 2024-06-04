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
 * @property bool $strict_mode 是否以严格模式处理SQL
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
			//这里不能使用 isset，isset不会触发 __get 方法
			if($this->strict_mode !== null){
				self::toggleStrictMode($conn, !!$this->strict_mode);
			}
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
	 * 普通模式、严格模式切换
	 * @param bool $to_strict 是否切换到严格模式
	 */
	public static function toggleStrictMode($conn, $to_strict = false){
		if($to_strict){
			$sql = "SET session sql_mode='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'";
		}else{
			$sql = "SET sql_mode ='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'";
		}
		$conn->query($sql);
	}

	/**
	 * protect password
	 * @return string|null
	 */
	public function __toString(){
		$field_map = static::getAttrDSNSegMap();
		if($field_map){
			$p = static::getDSNPrefix().':';
			$comma = '';
			foreach($field_map as $attr => $dsn_seg){
				if($this->{$attr}){
					$v = $dsn_seg === 'password' ? '*******' : $this->{$attr};
					$p .= $comma."$dsn_seg=".$v;
					$comma = ';';
				}
			}
			return $p;
		}
		return null;
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
		parent::__set($name, $value);
	}
}
