<?php
$pathAPI = dirname(__FILE__)."/../../../../api/";
require_once $pathAPI. "modelo/SafeString.php";
require_once $pathAPI. "modelo/Producto.php";

header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['nombre']) || !isset($_POST['ocupacion']) 
		|| !isset($_POST['telefono']) || !isset($_POST['celular']) || !isset($_POST['email']) 
		|| !isset($_POST['rfc']) || !isset($_POST['beneficiario'])) {
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
		$asociado = null;
		if (isset($_POST['asociado'])) {
			$asociado = SafeString::safe($_POST['asociado']);
		}
		
		$producto = new Producto();
		$datos = $producto->generarOrdenOXXOMedica365($nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado);
		
		echo json_encode(array("status"=>"ok","datos"=>$datos));
	} catch(errorConBaseDeDatos $e) {
		echo json_encode(array("status"=>"error","clave"=>"db","explicacion"=>$e->getMessage()));
	} catch(errorMakingPaymentException $e) {
		echo json_encode(array("status"=>"error","clave"=>"pago","explicacion"=>$e->getMessage()));
	} catch(Conekta\Handler $error) {
		//Aqui es donde muestra el error de que no se pudo conectar a la api
		var_dump($error);
		echo('Mensaje ='.$error->getMessage().' -------');
		echo('Code ='.$error->getCode().' -------');
		echo('Line ='.$error->getLine().' -------');
		echo('Trace ='.$error->getTraceAsString().' -------');
		

		//Conekta object
		echo "Mensaje dump = ";
		var_dump($error->getConektaMessage());
		echo " ----------";
		//Conekta object props
		$conektaError = $error->getConektaMessage();
		echo "Type dump = ";
		var_dump($conektaError->type);
		echo " ----------";
		echo "Detail dump = ";
		var_dump($conektaError->details);
		echo " ----------";

		//Object iteration
		$conektaError = $error->getConektaMessage();
		foreach ($conektaError->details as $key) {
			echo "Iteracion = ";
			echo($key->debug_message);
			echo " ----------";
		}
		echo json_encode(array("status"=>"error","clave"=>"datos","explicacion"=>$error->getMessage().'---'.var_dump($error->getConektaMessage())));
	} catch (Exception $e) {
		echo json_encode(array("status"=>"error","clave"=>"desconocido","explicacion"=>$e->getMessage()));
	}
?>