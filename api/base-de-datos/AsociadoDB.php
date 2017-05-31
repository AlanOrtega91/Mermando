<?php
require_once dirname ( __FILE__ ) . "/BaseDeDatos.php";

class AsociadoDB extends BaseDeDatos {
	
 	const BUSCAR_EMAIL = "SELECT * FROM Asociado WHERE email = '%s'";
 	const BUSCAR_ASOCIADO = "SELECT * FROM Asociado WHERE id = %s";
 	const BUSCAR_ORDEN_LIBRE = "SELECT * FROM Orden WHERE idTransaccion = '%s' AND usadoParaRegistro = 0";
 	const AGREGAR_ASOCIADO = "INSERT INTO Asociado (nombre, email, contrasenia )
 			VALUES ('%s', '%s', SHA2(MD5(('%s')),512))";
 	const CHECAR_CLAVES= "SELECT * FROM Asociado WHERE email = '%s' AND contrasenia = SHA2(MD5(('%s')),512)";
 	const CREAR_SESION= "INSERT INTO Sesion_Asociado (email, token ,fecha)
 			VALUES ('%s', '%s','%s')";
 	const USAR_ORDEN_REGISTRO = "UPDATE Orden SET usadoParaRegistro = 1 WHERE idTransaccion = '%s'";
 	const LEER_CUENTA = "SELECT Asociado.nombre AS nombre, Asociado.email AS email, Asociado.id AS id FROM Sesion_Asociado 
							LEFT JOIN Asociado ON Sesion_Asociado.idAsociado = Asociado.id WHERE token = '%s'";
 	function existeAsociado($asociado){
 		$query = sprintf(self::BUSCAR_ASOCIADO, $asociado);
 		$resultado = $this->ejecutarQuery($query);
 		return $this->resultadoTieneValores($resultado);
 	}
 	
 	function existeEmail($email){
 		$query = sprintf(self::BUSCAR_EMAIL, $email);
 		$resultado = $this->ejecutarQuery($query);
 		return $this->resultadoTieneValores($resultado);
 	}
 	
 	function ordenDeCompraLibre($orden){
 		$query = sprintf(self::BUSCAR_ORDEN_LIBRE, $orden);
 		$resultado = $this->ejecutarQuery($query);
 		return $this->resultadoTieneValores($resultado);
 	}
 	
 	function nuevoAsociado($nombre, $email, $contraseña) {
 		$query = sprintf(self::AGREGAR_ASOCIADO, $nombre, $email, $contraseña);
 		$this->ejecutarQuery($query);
 	}
 	
 	function usarOrdenEnRegistro($orden){
 		$query = sprintf(self::USAR_ORDEN_REGISTRO, $orden);
 		$this->ejecutarQuery($query);
 	}
 	
 	function clavesCoinciden($email, $contraseña){
 		$query = sprintf(self::CHECAR_CLAVES, $email, $contraseña);
 		$resultado = $this->ejecutarQuery($query);
 		return $this->resultadoTieneValores($resultado);
 	}
 	
 	function crearSesion($email, $fecha) {
 		$token = md5 (uniqid(mt_rand(), true));
 		$query = sprintf(self::CREAR_SESION, $email, $token,$fecha);
 		$resultado = $this->ejecutarQuery($query);
 		return $token;
 	}
 	
 	function leerCuenta($token) {
 		$query = sprintf(self::LEER_CUENTA, $token);
 		$resultado = $this->ejecutarQuery($query);
 		return $resultado->fetch_assoc();
 	}
}
?>