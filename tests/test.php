<?php

use LFPhp\PDODSN\Database\MySQL;
use LFPhp\PDODSN\DSN;

$dsn_string = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$dsn = DSN::resolveString($dsn_string);
if($dsn instanceof MySQL){
	var_dump($dsn->port);
	die;
}