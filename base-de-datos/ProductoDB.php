<?php
require_once dirname ( __FILE__ ) . "/BaseDeDatos.php";
require_once dirname ( __FILE__ ) . "/AsociadoDB.php";

class ProductoDB extends BaseDeDatos {

 	const AGREGAR_ORDEN = "INSERT INTO Orden (idProducto, idAsociado, certificado, idTransaccion,
 			costo, fecha )
 			VALUES (%d, %s, %d, '%s', %s, '%s')";
 	
 	function guardarOrden($idProducto, $asociado, $certificado, $idTrasaccion, $precio){
 		date_default_timezone_set ( 'America/Mexico_City' );
 		$fecha = date ( "Y-m-d H:i:s" );
 		if (!$asociado) {
 			$asociado = "null";
 		} else {
 			$query = sprintf(AsociadoDB::BUSCAR_ASOCIADO, $asociado);
 			$result = $this->mysqli->query($query);
 			if($result != TRUE || $result->num_rows == 0) {
 				$asociado = "null";
 			}
 		}
 		$query = sprintf(self::AGREGAR_ORDEN, $idProducto, $asociado, $certificado, $idTrasaccion, $precio, $fecha);
 		if($this->mysqli->query($query) != TRUE) {
 			throw new errorWithDatabaseException('Query failed'.$query);
 		}
 	}
}
?>