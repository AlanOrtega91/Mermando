<?php
abstract class BaseDeDatos{
	 	
//  	const DB_LINK = '127.0.0.1';
//  	const DB_LOGIN = 'topbidmx_root';
//  	const DB_PASSWORD ='DBmermando!';
//  	const DB_NAME = 'topbidmx_mermando';

 	const DB_LINK = '127.0.0.1';
 	const DB_LOGIN = 'root';
 	const DB_PASSWORD ='';
 	const DB_NAME = 'mermando';
 
 	
 	var $mysqli;
 	
 	function __construct()
 	{
 		$this->mysqli = new mysqli(self::DB_LINK, self::DB_LOGIN, self::DB_PASSWORD, self::DB_NAME);
 		if ($this->mysqli->connect_errno) {
 			throw new errorWithDatabaseException("Error connecting with database");
 		}
 		$this->mysqli->set_charset("utf8");
 	}
 	
}

class errorWithDatabaseException extends Exception{
}
?>