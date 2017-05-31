<?php
require_once dirname(__FILE__)."/../../modelo/SafeString.php";
require_once dirname(__FILE__)."/../../modelo/Producto.php";

header('Content-Type: text/html; charset=utf8');
file_put_contents('webhooks.txt','llego algo', FILE_APPEND);

if (!isset($_GET['idTransaccion']) || !isset($_GET['tipo'])) {
	die(json_encode(array("status"=>"error","clave"=>"argumentos")));
		}

	try{
		$tipo = SafeString::safe($_GET['tipo']);
		$idTransaccion = SafeString::safe($_GET['idTransaccion']);
		
		if ($tipo == 'oxxo') {
			$producto = new Producto();
			$producto->ordenOXXOPagada($idTransaccion);
		} else {
			echo json_encode(array("status"=>"nada"));
		}
		
	} catch(errorConBaseDeDatos$e) {
		echo json_encode(array("status"=>"error","clave"=>"db = ".$e->getMessage()));
	} catch(errorEmailNoExiste $e) {
		echo json_encode(array("status"=>"error","clave"=>"email"));
	} catch(errorClavesNoCoinciden $e) {
		echo json_encode(array("status"=>"error","clave"=>"claves"));
	} catch (Exception $e) {
 		echo json_encode(array("status"=>"error","clave"=>"desconocido"));
 	}
?>