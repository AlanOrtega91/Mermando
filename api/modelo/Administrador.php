<?php
require_once dirname ( __FILE__ ) . "/../base-de-datos/AdministradorDB.php";
require_once dirname ( __FILE__ ) . "/../../recursos/PHPMailer/PHPMailerAutoload.php";
require_once dirname ( __FILE__ ) . "/Asociado.php";

class Administrador {
	
	private $dataBase;
	
	public function __construct() {
		$this->dataBase = new AdministradorDB();
	}
	
	
	function iniciarSesion($email, $contraseña) {
		
		if (!$this->dataBase->existeEmail($email)) {
			throw new errorEmailNoExiste();
		}
		if (!$this->dataBase->clavesCoinciden($email, $contraseña)){
			throw new errorClavesNoCoinciden();
		}
		date_default_timezone_set ( 'America/Mexico_City' );
		$fecha = date ( "Y-m-d H:i:s");
		return $this->dataBase->crearSesion($email, $fecha);
	}
	
	function checarToken($token) {
		return $this->dataBase->leerCuenta($token);
	}
	
	function leerComisiones()
	{
		$asociados = $this->leerAsociados();
		$asociadoClass = new Asociado();
		for ($i=0; $i < count($asociados); $i++)
		{
			$comision = $asociadoClass->leerComisionActual($asociados[$i]['id']);
			$asociados[$i]['comision'] = $comision;
		}
		return $asociados;
	}
	
	function leerAsociados() 
	{
		$asociados = $this->dataBase->leerAsociados();
		for ($asociadosLista = array(); $fila = $asociados->fetch_assoc(); $asociadosLista[] = $fila);
		return $asociadosLista;
	}
	
	function leerAseguradosActivos()
	{
		$asegurados = $this->dataBase->leerAseguradosActivos();
		for ($aseguradosLista= array(); $fila = $asegurados->fetch_assoc(); $aseguradosLista[] = $fila);
		return $aseguradosLista;
	}
	function leerAseguradosActivosPorAgregar()
	{
		$asegurados = $this->dataBase->leerAseguradosActivosPorAgregar();
		for ($aseguradosLista= array(); $fila = $asegurados->fetch_assoc(); $aseguradosLista[] = $fila);
		return $aseguradosLista;
	}
	function leerAseguradosVencidos()
	{
		$asegurados = $this->dataBase->leerAseguradosVencidos();
		for ($aseguradosLista= array(); $fila = $asegurados->fetch_assoc(); $aseguradosLista[] = $fila);
		return $aseguradosLista;
	}
	function leerAseguradosVencidosPorDarDeBaja()
	{
		$asegurados = $this->dataBase->leerAseguradosVencidosPorDarDeBaja();
		for ($aseguradosLista= array(); $fila = $asegurados->fetch_assoc(); $aseguradosLista[] = $fila);
		return $aseguradosLista;
	}
	
}
?>