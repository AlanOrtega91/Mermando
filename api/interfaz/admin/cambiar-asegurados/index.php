<?php
require_once dirname(__FILE__)."/../../../modelo/SafeString.php";
require_once dirname(__FILE__)."/../../../modelo/Administrador.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']) || !isset($_POST['certificados']) || !isset($_POST['tipo'])) {
			die(json_encode(array("Status"=>"ERROR missing values")));
		}

	try{
		$token = SafeString::safe($_POST['token']);
		$certificados = SafeString::safe($_POST['certificados']);
		$tipo = SafeString::safe($_POST['tipo']);
		
		$administrador = new Administrador();
		$administrador->checarToken($token);
		if (strlen($certificados) == 0)
		{
			echo json_encode(array("status"=>"error", "clave" => "certificados", "explicacion" => "Error con los certificados"));
			die();
		}
		if ($tipo === "0") {
			$administrador->agregarAsegurados($certificados);
		} else if ($tipo === "1") {
			$administrador->bajaAsegurados($certificados);
		} else {
			echo json_encode(array("status"=>"error", "clave" => "tipo", "explicacion" => "tipo invalido"));
		}
		
		echo json_encode(array("status"=>"ok"));
		
	} catch(errorConBaseDeDatos $e) {
		echo json_encode(array("status"=>"error","clave"=>"db","explicacion"=>$e->getMessage()));
	} catch (Exception $e) {
		echo json_encode(array("status"=>"error","clave"=>"desconocido","explicacion"=>$e->getMessage()));
	}
?>