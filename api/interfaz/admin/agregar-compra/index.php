<?php
$pathAPI = dirname(__FILE__)."/../../../";
require_once $pathAPI. "modelo/SafeString.php";
require_once $pathAPI. "modelo/Producto.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['nombre']) || !isset($_POST['ocupacion']) 
		|| !isset($_POST['telefono']) || !isset($_POST['celular']) || !isset($_POST['email']) 
		|| !isset($_POST['rfc']) || !isset($_POST['beneficiario']) || !isset($_POST['referencia'])) {
			die(json_encode(array("Status"=>"ERROR missing values")));
		}
		
	try{
		$nombre = SafeString::safe($_POST['nombre']);
		$ocupacion = SafeString::safe($_POST['ocupacion']);
		$telefono = SafeString::safe($_POST['telefono']);
		$celular = SafeString::safe($_POST['celular']);
		$email = SafeString::safe($_POST['email']);
		$rfc = SafeString::safe($_POST['rfc']);
		$beneficiario = SafeString::safe($_POST['beneficiario']);
		$referencia = $_POST['referencia'];
		if ($nombre == "" || $ocupacion == "" || $telefono == "" || $celular == "" || $email == "" || $rfc == "" || $beneficiario == "" || $referencia == "") {
			die(json_encode(array("Status"=>"ERROR missing values")));
		}
		$asociado = null;
		if (isset($_POST['asociado'])) {
			$asociado = SafeString::safe($_POST['asociado']);
		}
		
		$producto = new Producto();
		$producto->agregarCompraMedica365($token, $nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado, $referencia);

		echo json_encode(array("status"=>"ok"));
		
	} catch(errorConBaseDeDatos $e) {
		echo json_encode(array("status"=>"error","clave"=>"db","explicacion"=>$e->getMessage()));
	} catch(errorMakingPaymentException $e) {
		echo json_encode(array("status"=>"error","clave"=>"pago","explicacion"=>$e->getMessage()));
	} catch(Conekta\ErrorList $e) {
		echo json_encode(array("status"=>"error","clave"=>"datos","explicacion"=>$e->getMessage().'---'.$e->getConektaMessage()));
	} catch (Exception $e) {
		echo json_encode(array("status"=>"error","clave"=>"desconocido","explicacion"=>$e->getMessage()));
	}
?>