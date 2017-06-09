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
		$asociados = $administrador->leerComisiones();
		
		echo json_encode(array("status"=>"ok","asociados"=>$asociados));
		
	} catch(errorConBaseDeDatos $e) {
		echo json_encode(array("status"=>"error","clave"=>"db","explicacion"=>$e->getMessage()));
	} catch (Exception $e) {
		echo json_encode(array("status"=>"error","clave"=>"desconocido","explicacion"=>$e->getMessage()));
	}
?>