<?php
require_once dirname ( __FILE__ ) . "/BaseDeDatos.php";

class BeneficiarioDB extends BaseDeDatos {

 	
 	const AGREGAR_BENEFICIARIO = "INSERT INTO BeneficiarioMedica365 (nombre, primerApellido, segundoApellido, ocupacion, 
 			telefono, celular, email, RFC, beneficiario, vigencia, poliza )
 			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')";
 	
 	
 	function guardarBeneficiario($nombre, $primerApellido, $segundoApellido, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $poliza, $vigencia){
 		$query = sprintf(self::AGREGAR_BENEFICIARIO, $nombre, $primerApellido, $segundoApellido, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $vigencia, $poliza);
 		if($this->mysqli->query($query) != TRUE) {
 			throw new errorWithDatabaseException('Query failed'.$query);
 		}
 		return $this->mysqli->insert_id;
 	}
}
?>