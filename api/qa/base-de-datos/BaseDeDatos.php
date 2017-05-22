<?php
abstract class BaseDeDatos{
	 	
 	const DB_LINK = '127.0.0.1';
 	const DB_LOGIN = 'vagmx_VagDB';
 	const DB_PASSWORD ='v5gUserDB!';
 	const DB_NAME = 'vagmx_QA';
 	
 	var $mysqli;
 	
 	function __construct()
 	{
 		$this->mysqli = new mysqli(self::DB_LINK, self::DB_LOGIN, self::DB_PASSWORD, self::DB_NAME);
 		if ($this->mysqli->connect_errno) {
 			throw new errorConBaseDeDatos("Error connecting with database");
 		}
 		$this->mysqli->set_charset("utf8");
 	}
 	
 	function ejecutarQuery($query)
 	{
 		if(! ($resultado = $this->mysqli->query($query))) {
 			throw new errorConBaseDeDatos($query.' ---- Query failed '.$this->mysqli->error);
 		}
 		return $resultado;
 	}
 	
 	function resultadoTieneValores($resultado) {
 		if ($resultado->num_rows > 0)
 		{
 			return true;
 		} else {
 			return false;
 		}
 	}
 	
}

class errorConBaseDeDatos extends Exception{
}
?>