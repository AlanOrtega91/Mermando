<?php
require_once dirname(__FILE__)."/../../../modelo/SafeString.php";
require_once dirname(__FILE__)."/../../../modelo/Productos.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']) || !isset($_POST['nombre']) || !isset($_POST['primerApellido'])
		|| !isset($_POST['ocupacion']) || !isset($_POST['telefono']) || !isset($_POST['celular'])
		|| !isset($_POST['email']) || !isset($_POST['rfc']) || !isset($_POST['beneficiario'])) {
			die(json_encode(array("Status"=>"ERROR missing values")));
		}

	try{
		$token = SafeString::safe($_POST['token']);
		$nombre = SafeString::safe($_POST['nombre']);
		$primerApellido = SafeString::safe($_POST['primerApellido']);
		$segundoApellido = null;
		if (isset($_POST['segundoApellido'])) {
			$segundoApellido = SafeString::safe($_POST['segundoApellido']);
		}
		$ocupacion = SafeString::safe($_POST['ocupacion']);
		$telefono = SafeString::safe($_POST['telefono']);
		$celular = SafeString::safe($_POST['celular']);
		$email = SafeString::safe($_POST['email']);
		$rfc = SafeString::safe($_POST['rfc']);
		$beneficiario = SafeString::safe($_POST['beneficiario']);
		$asociado = null;
		if (isset($_POST['asociado'])) {
			$asociado = SafeString::safe($_POST['asociado']);
		}
		
		$productos = new Productos();
		$productos->comprarMedica365($token, $nombre, $primerApellido, $segundoApellido, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado);
		
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