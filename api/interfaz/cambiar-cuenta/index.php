<?php
require_once dirname(__FILE__)."/../../modelo/SafeString.php";
require_once dirname(__FILE__)."/../../modelo/Asociado.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']) || !isset($_POST['email']) || !isset($_POST['nombre'])) {
	die(json_encode(array("Status"=>"ERROR missing values")));
}

try{
	$token = SafeString::safe($_POST['token']);
	$email = SafeString::safe($_POST['email']);
	$nombre = SafeString::safe($_POST['nombre']);
	
	(new Asociado())->cambiarDatosCuenta($token, $email, $nombre);
	
	echo json_encode(array("status"=>"ok"));
	
} catch(errorConBaseDeDatos $e) {
	echo json_encode(array("status"=>"error","clave"=>"db","explicacion"=>$e->getMessage()));
} catch (Exception $e) {
	echo json_encode(array("status"=>"error","clave"=>"desconocido","explicacion"=>$e->getMessage()));
}
?>