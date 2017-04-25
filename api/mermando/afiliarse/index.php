<?php
require_once dirname(__FILE__)."/../../../modelo/SafeString.php";
require_once dirname(__FILE__)."/../../../modelo/Vendedor.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['nombre']) || !isset($_POST['apellido']) || !isset($_POST['email']) || !isset($_POST['contraseña']) || !isset($_POST['certificado']) ) 
{
	die(json_encode(array("Status"=>"ERROR missing values")));
}

	try{
		$nombre = SafeString::safe($_POST['nombre']);
		$apellido = SafeString::safe($_POST['apellido']);
		$email = SafeString::safe($_POST['email']);
		$contraseña = SafeString::safe($_POST['contraseña']);
		$certificado = SafeString::safe($_POST['certificado']);
		
		echo json_encode(array("Status"=>"OK"));
		
	} catch(errorWithDatabaseException $e) {
		echo json_encode(array("Status"=>"ERROR DB"));
	} catch(errorMakingPaymentException $e) {
		echo json_encode(array("Status"=>"ERROR PAGO"));
 	} catch(Conekta\ErrorList $e) {
 		echo json_encode(array("Status"=>"ERROR Datos".$e->getMessage()));
 	} catch (Exception $e) {
 		echo json_encode(array("Status"=>"ERROR Desconocido"));
 	}
?>