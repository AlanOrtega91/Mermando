<?php
require_once dirname(__FILE__)."/../../modelo/SafeString.php";
require_once dirname(__FILE__)."/../../modelo/Asociado.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['nombre']) || !isset($_POST['email']) || !isset($_POST['contrasenia']) || !isset($_POST['numeroOrden']) ) 
{
	die(json_encode(array("Status"=>"ERROR missing values")));
}

	try{
		$nombre = SafeString::safe($_POST['nombre']);
		$email = SafeString::safe($_POST['email']);
		$contraseña = SafeString::safe($_POST['contrasenia']);
		$orden = SafeString::safe($_POST['numeroOrden']);
		
		$asociado = new Asociado();
		$asociado->nuevoAsociado($nombre, $email, $contraseña, $orden);
		
		echo json_encode(array("status"=>"ok"));
		
	} catch(errorConBaseDeDatos $e) {
		echo json_encode(array("status"=>"error","clave"=>"db"));
	} catch(errorEmailUsado $e) {
		echo json_encode(array("status"=>"error","clave"=>"email"));
	} catch(errorOrdenUsada $e) {
		echo json_encode(array("status"=>"error","clave"=>"orden"));
	} catch (Exception $e) {
		echo json_encode(array("status"=>"error","clave"=>"desconocido"));
 	}
?>