<?php
require_once dirname(__FILE__)."/../../modelo/SafeString.php";
require_once dirname(__FILE__)."/../../modelo/Asociado.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']) || !isset($_POST['contrasenia']) || !isset($_POST['contraseniaNueva'])) {
	die(json_encode(array("Status"=>"ERROR missing values")));
}

try{
	$token = SafeString::safe($_POST['token']);
	$contraseña = SafeString::safe($_POST['contrasenia']);
	$contraseñaNueva = SafeString::safe($_POST['contraseniaNueva']);
	
	(new Asociado())->cambiarDatosContrasena($token, $contraseña, $contraseñaNueva);
	
	echo json_encode(array("status"=>"ok"));
	
} catch(errorConBaseDeDatos $e) {
	echo json_encode(array("status"=>"error","clave"=>"db","explicacion"=>$e->getMessage()));
} catch(errorClavesNoCoinciden $e) {
	echo json_encode(array("status"=>"error","clave"=>"claves","explicacion"=>"Las claves no coinciden"));
} catch (Exception $e) {
	echo json_encode(array("status"=>"error","clave"=>"desconocido","explicacion"=>$e->getMessage()));
}
?>