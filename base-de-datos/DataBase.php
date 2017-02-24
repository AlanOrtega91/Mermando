<?php
class DataBase {
	 	
 	const DB_LINK = '127.0.0.1';
 	const DB_LOGIN = 'topbidmx_root';
 	const DB_PASSWORD ='DBmermando!';
 	const DB_NAME = 'topbidmx_mermando';
//  	const DB_LINK = '127.0.0.1';
//  	const DB_LOGIN = 'root';
//  	const DB_PASSWORD ='';
//  	const DB_NAME = 'mermando';
 	
 	const AGREGAR_BENEFICIARIO = "INSERT INTO BeneficiarioMedica365 (nombre, primerApellido, segundoApellido, ocupacion, 
 			telefono, celular, email, RFC, beneficiario, vigencia, poliza )
 			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')";
 	const AGREGAR_ORDEN = "INSERT INTO Orden (idProducto, idAsociado, certificado, idTransaccion,
 			costo, fecha )
 			VALUES (%d, %s, %d, '%s', %s, '%s')";
 	const BUSCAR_ASOCIADO = "SELECT * FROM Asociado WHERE id = %s";
 	
 	var $mysqli;
 	
 	function __construct()
 	{
 		$this->mysqli = new mysqli(DataBase::DB_LINK, DataBase::DB_LOGIN, DataBase::DB_PASSWORD, DataBase::DB_NAME);
 		if ($this->mysqli->connect_errno) {
 			throw new errorWithDatabaseException("Error connecting with database");
 		}
 		$this->mysqli->set_charset("utf8");
 	}
 	
 	function guardarBeneficiario($nombre, $primerApellido, $segundoApellido, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $poliza, $vigencia){
 		$query = sprintf(DataBase::AGREGAR_BENEFICIARIO, $nombre, $primerApellido, $segundoApellido, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $vigencia, $poliza);
 		if($this->mysqli->query($query) != TRUE) {
 			throw new errorWithDatabaseException('Query failed'.$query);
 		}
 		return $this->mysqli->insert_id;
 	}
 	
 	function guardarOrden($idProducto, $asociado, $certificado, $idTrasaccion, $precio){
 		date_default_timezone_set ( 'America/Mexico_City' );
 		$fecha = date ( "Y-m-d H:i:s" );
 		if (!$asociado) {
 			$asociado = "null";
 		} else {
 			$query = sprintf(DataBase::BUSCAR_ASOCIADO, $asociado);
 			$result = $this->mysqli->query($query);
 			if($result != TRUE || $result->num_rows == 0) {
 				$asociado = "null";
 			}
 		}
 		$query = sprintf(DataBase::AGREGAR_ORDEN, $idProducto, $asociado, $certificado, $idTrasaccion, $precio, $fecha);
 		if($this->mysqli->query($query) != TRUE) {
 			throw new errorWithDatabaseException('Query failed'.$query);
 		}
 	}
}

class errorWithDatabaseException extends Exception{
}
?>