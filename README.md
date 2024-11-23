#PDODSN
> PDODSN is developed and tested based on PHP5.6+PDO or above environment

The PDODSN library is used to parse PDO-DSN strings. The framework library recognizes the DSN mark and parses out the response database type, driver, port and other information (note that each database type has different fields). Please strictly distinguish the differences between different database types, usage drivers, and usage modes during use.

## 1. Installation

```shell
composer require lfphp/pdodsn
```

## 2. Use

Parse DSN string

```php
<?php
use LFPhp\PDODSN\Database\MySQL;
use LFPhp\PDODSN\DSN;

$dsn_string = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$dsn = DSN::resolveString($dsn_string);
if($dsn instanceof MySQL){
var_dump($dsn->port);
die;
}
```

Generate DSN string

```php
<?php
use LFPhp\PDODSN\Database\MySQL;

$mysql_dsn = new MySQL();
$mysql_dsn->host = 'localhost';
$mysql_dsn->database = 'user';
echo $mysql_dsn->__toString();
```
