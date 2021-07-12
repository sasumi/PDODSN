<?php
namespace LFPhp\PDODSN\tests;

use LFPhp\PDODSN\Database\MySQL;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase {
	public function testMySQL(){
		$mysql_dsn = new MySQL();
		$mysql_dsn->host = 'localhost';
		$mysql_dsn->database = 'user';
		echo $mysql_dsn->__toString();
		$this->assertTrue(!!strlen($mysql_dsn->__toString()));
	}
}