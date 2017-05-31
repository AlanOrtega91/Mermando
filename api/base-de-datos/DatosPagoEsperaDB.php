<?php
require_once dirname ( __FILE__ ) . "/BaseDeDatos.php";

class DatosPagoEsperaDB extends BaseDeDatos {

 	
	const AGREGAR_DATOS= "INSERT INTO DatosPagoEspera (nombre, ocupacion, 
 			telefono, celular, email, RFC, beneficiario, poliza, idOrden )
 			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')";
 	
 	
 	function guardarDatosEspera($nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $poliza, $idOrden) {
 		$query = sprintf(self::AGREGAR_DATOS, $nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $poliza, $idOrden);
 		$this->ejecutarQuery($query);
 	}
}
?>