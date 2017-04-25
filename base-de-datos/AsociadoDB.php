<?php
require_once dirname ( __FILE__ ) . "/BaseDeDatos.php";

class AsociadoDB extends BaseDeDatos {
	
 	const BUSCAR_ASOCIADO = "SELECT * FROM Asociado WHERE id = %s";
 	
 	const AGREGAR_ASOCIADO = "INSERT INTO Asociado (nombre, apellido, email, contrase�a )
 			VALUES (%s, %s, %s, SHA2(MD5(('%s')),512))";
 	
 	
 	function nuevoAfiliado($nombre, $apellido, $email, $contrase�a) {
 		$query = sprintf(DataBase::AGREGAR_VENDEDOR, $nombre, $apellido, $email, $contrase�a);
 		if($this->mysqli->query($query) != TRUE) {
 			throw new errorWithDatabaseException('Query failed'.$query);
 		}
 	}
}
?>