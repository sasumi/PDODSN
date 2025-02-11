<?php
namespace LFPhp\PDODSN\Exception;

use Exception;

class DSNException extends Exception {
	public $dsn = null;

	public function __construct($message = "", $data = []){
		if($data){
			foreach($data as $k=>$item){
				$this->{$k} = $item;
			}
		}
		parent::__construct($message);
	}
}
