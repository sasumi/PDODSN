<?php

use LFPhp\PDODSN\Database\MySQL;

$mysql_dsn = new MySQL();
$mysql_dsn->host = 'localhost';
$mysql_dsn->database = 'user';
echo $mysql_dsn->__toString();