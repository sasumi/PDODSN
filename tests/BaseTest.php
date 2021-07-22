<?php
namespace LFPhp\PDODSN\tests;

use LFPhp\PDODSN\Database\MySQL;
use LFPhp\PDODSN\DSN;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase {
	public function testMySQL(){
		$mysql_dsn = new MySQL();
		$mysql_dsn->host = 'localhost';
		$mysql_dsn->database = 'user';
		echo $mysql_dsn->__toString();
		$this->assertTrue(!!strlen($mysql_dsn->__toString()));
	}

	public function testSqlSer(){
		$dsn_str = 'sqlsrv:Server=12345abcde.database.windows.net;Database=testdb';
		$dsn = DSN::resolveString($dsn_str);
		var_dump($dsn);
		$this->assertIsObject($dsn);

		$s = $dsn->__toString();
		var_dump($s);
		$this->assertIsString($s);
	}

	public function testSQLite(){
		$dsn = DSN::resolveString('sqlite:c:/htdocs/i.php');
		var_dump($dsn);
		$this->assertIsObject($dsn);
		$this->assertTrue(!!strlen($dsn->__toString()));
	}

	public function testPostgreSQL(){
		$dsn = DSN::resolveString('pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass');
		var_dump($dsn);
		$this->assertIsObject($dsn);
	}
}