# PDODSN
> PDODSN基于PHP5.6+PDO以上环境开发测试

PDODSN 库用于解析 PDO-DSN 字符串。框架库通过识别 DSN 标志，解析出响应数据库类型、驱动、端口等信息（注意各数据库类型拥有字段不尽相同）。使用过程请严格区分不同数据库类型、使用驱动、使用模式之间的差异。

## 1. 安装

```shell
composer require lfphp/pdodsn
```

## 2. 使用

解析 DSN 字符串

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

生成DSN字符串

```php
<?php    
use LFPhp\PDODSN\Database\MySQL;

$mysql_dsn = new MySQL();
$mysql_dsn->host = 'localhost';
$mysql_dsn->database = 'user';
echo $mysql_dsn->__toString();
```

