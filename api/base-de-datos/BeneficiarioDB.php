<?php
require_once dirname ( __FILE__ ) . "/BaseDeDatos.php";

class BeneficiarioDB extends BaseDeDatos {

 	
 	const AGREGAR_BENEFICIARIO = "INSERT INTO BeneficiarioMedica365 (nombre, ocupacion, 
 			telefono, celular, email, RFC, beneficiario, vigencia, poliza )
 			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')";
 	
 	
 	function guardarBeneficiario($nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $poliza, $vigencia){
 		$query = sprintf(self::AGREGAR_BENEFICIARIO, $nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $vigencia, $poliza);
 		$this->ejecutarQuery($query);
 		return $this->mysqli->insert_id;
 	}
}
?>