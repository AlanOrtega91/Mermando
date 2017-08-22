<?php
require_once dirname ( __FILE__ ) . "/BaseDeDatos.php";

class AdministradorDB extends BaseDeDatos {
	
 	const BUSCAR_EMAIL = "SELECT * FROM Administrador WHERE email = '%s'";
 	
 	const CHECAR_CLAVES= "SELECT * FROM Administrador WHERE email = '%s' AND contrasenia = SHA2(MD5(('%s')),512)";
 	const CREAR_SESION= "INSERT INTO Sesion_Admin (email, token ,fecha)
 			VALUES ('%s', '%s','%s')";
 	const LEER_CUENTA = "SELECT Administrador.id AS id FROM Sesion_Admin
							LEFT JOIN Administrador ON Sesion_Admin.email = Administrador.email WHERE token = '%s'";
 	const LEER_ASOCIADOS = "SELECT id, nombre, metodoPago FROM Asociado";
 	
 	const LEER_ASEGURADOS_ACTIVOS = "SELECT certificado, nombre, rfc
			FROM BeneficiarioMedica365
			WHERE agregadoApoliza = 1 AND Vigencia > NOW()";
 	const LEER_ASEGURADOS_ACTIVOS_POR_AGREGAR = "SELECT certificado, nombre, rfc
			FROM BeneficiarioMedica365
			WHERE agregadoApoliza = 0 AND Vigencia > NOW()";
 	const LEER_ASEGURADOS_VENCIDOS = "SELECT certificado, nombre, rfc
			FROM BeneficiarioMedica365
			WHERE agregadoApoliza = 0 AND Vigencia < NOW()";
 	const LEER_ASEGURADOS_VENCIDOS_POR_BAJA = "SELECT certificado, nombre, rfc
			FROM BeneficiarioMedica365
			WHERE agregadoApoliza = 0 AND Vigencia < NOW()";
 	
 	const AGREGAR_ASEGURADOS = "UPDATE BeneficiarioMedica365
			SET agregadoAPoliza = 1
			WHERE vigencia > NOW()
			AND certificado IN (%s)";
 	
 	const BAJA_ASEGURADOS= "UPDATE BeneficiarioMedica365
			SET agregadoAPoliza = 0
			WHERE vigencia < NOW()
			AND certificado IN (%s)";
 	
 	function existeEmail($email){
 		$query = sprintf(self::BUSCAR_EMAIL, $email);
 		$resultado = $this->ejecutarQuery($query);
 		return $this->resultadoTieneValores($resultado);
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
 	
 	function leerAsociados()
 	{
 		$query = sprintf(self::LEER_ASOCIADOS);
 		$resultado = $this->ejecutarQuery($query);
 		return $resultado;
 	}
 	
 	function leerAseguradosActivos()
 	{
 		$query = sprintf(self::LEER_ASEGURADOS_ACTIVOS);
 		$resultado = $this->ejecutarQuery($query);
 		return $resultado;
 	}
 	
 	function leerAseguradosActivosPorAgregar()
 	{
 		$query = sprintf(self::LEER_ASEGURADOS_ACTIVOS_POR_AGREGAR);
 		$resultado = $this->ejecutarQuery($query);
 		return $resultado;
 	}
 	
 	function leerAseguradosVencidos()
 	{
 		$query = sprintf(self::LEER_ASEGURADOS_VENCIDOS);
 		$resultado = $this->ejecutarQuery($query);
 		return $resultado;
 	}
 	
 	function leerAseguradosVencidosPorDarDeBaja()
 	{
 		$query = sprintf(self::LEER_ASEGURADOS_VENCIDOS_POR_BAJA);
 		$resultado = $this->ejecutarQuery($query);
 		return $resultado;
 	}
 	
 	function agregarAsegurados($certificados)
 	{
 		$query = sprintf(self::AGREGAR_ASEGURADOS, $certificados);
 		$this->ejecutarQuery($query);
 	}
 	
 	function bajaAsegurados($certificados)
 	{
 		$query = sprintf(self::BAJA_ASEGURADOS, $certificados);
 		$this->ejecutarQuery($query);
 	}
}
?>