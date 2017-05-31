<?php
require_once dirname ( __FILE__ ) . "/BaseDeDatos.php";

class ProductoDB extends BaseDeDatos {

 	const AGREGAR_ORDEN = "INSERT INTO Orden (idProducto, idAsociado, certificado, idTransaccion,
 			costo, fecha , pagada)
 			VALUES (%d, %s, %s, '%s', %d, '%s', '%s')";
 	
 	const ACTUALIZAR_CERTIFICADO = "UPDATE Orden SET certificado = '%s', pagada = '1' WHERE idTransaccion = '%s'";
 	
 	function guardarOrden($idProducto, $asociado, $certificado, $idTrasaccion, $precio, $pagada){
 		date_default_timezone_set ( 'America/Mexico_City' );
 		$fecha = date ( "Y-m-d H:i:s" );
 		$query = sprintf(self::AGREGAR_ORDEN, $idProducto, $asociado, $certificado, $idTrasaccion, $precio, $fecha, $pagada);
 		$this->ejecutarQuery($query);
 	}
 	
 	function actualizarCertificadoYPagar($certificado, $idTransaccion) {
 		$query = sprintf(self::ACTUALIZAR_CERTIFICADO, $certificado, $idTransaccion);
 		$resultado = $this->ejecutarQuerySinContraints($query);
 	}
}
?>