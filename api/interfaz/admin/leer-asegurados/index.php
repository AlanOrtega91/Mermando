<?php
require_once dirname(__FILE__)."/../../../modelo/SafeString.php";
require_once dirname(__FILE__)."/../../../modelo/Administrador.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token'])) {
			die(json_encode(array("Status"=>"ERROR missing values")));
		}

	try{
		$token = SafeString::safe($_POST['token']);
		
		$administrador = new Administrador();
		$administrador->checarToken($token);
		$aseguradosActivos = $administrador->leerAseguradosActivos();
		$aseguradosActivosPorAgregar = $administrador->leerAseguradosActivosPorAgregar();
		$aseguradosVencidos = $administrador->leerAseguradosVencidos();
		$aseguradosVencidosPorDarDeBaja = $administrador->leerAseguradosVencidosPorDarDeBaja();
		
		echo json_encode(array("status"=>"ok","aseguradosActivos" => $aseguradosActivos,
				"aseguradosActivosPorAgregar" => $aseguradosActivosPorAgregar, "aseguradosVencidos" => $aseguradosVencidos,
				"aseguradosVencidosPorDarDeBaja" => $aseguradosVencidosPorDarDeBaja
		));
		
	} catch(errorConBaseDeDatos $e) {
		echo json_encode(array("status"=>"error","clave"=>"db","explicacion"=>$e->getMessage()));
	} catch (Exception $e) {
		echo json_encode(array("status"=>"error","clave"=>"desconocido","explicacion"=>$e->getMessage()));
	}
?>