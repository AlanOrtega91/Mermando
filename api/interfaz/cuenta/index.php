<?php
require_once dirname(__FILE__)."/../../modelo/SafeString.php";
require_once dirname(__FILE__)."/../../modelo/Asociado.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token'])) {
			die(json_encode(array("Status"=>"ERROR missing values")));
		}

	try{
		$token = SafeString::safe($_POST['token']);
		
		$asociado = new Asociado();
		$informacion = $asociado->leerCuenta($token);
		
		echo json_encode(array("status"=>"ok","info"=>$informacion));
		
	} catch(errorConBaseDeDatos $e) {
		echo json_encode(array("status"=>"error","clave"=>"db","explicacion"=>$e->getMessage()));
	} catch (Exception $e) {
		echo json_encode(array("status"=>"error","clave"=>"desconocido","explicacion"=>$e->getMessage()));
	}
?>