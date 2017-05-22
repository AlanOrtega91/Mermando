<?php
require_once dirname ( __FILE__ ) . "/BaseDeDatos.php";

class ProductoDB extends BaseDeDatos {

 	const AGREGAR_ORDEN = "INSERT INTO Orden (idProducto, idAsociado, certificado, idTransaccion,
 			costo, fecha )
 			VALUES (%d, %s, %s, '%s', %d, '%s')";
 	
 	function guardarOrden($idProducto, $asociado, $certificado, $idTrasaccion, $precio){
 		date_default_timezone_set ( 'America/Mexico_City' );
 		$fecha = date ( "Y-m-d H:i:s" );
 		$query = sprintf(self::AGREGAR_ORDEN, $idProducto, $asociado, $certificado, $idTrasaccion, $precio, $fecha);
 		$this->ejecutarQuery($query);
 	}
}
?>