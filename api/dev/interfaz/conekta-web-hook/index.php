<?php
$body = @file_get_contents('php://input');
$data = json_decode($body);
http_response_code(200); // Return 200 OK

if ($data->type == 'charge.paid'){
	$msg = "Tu pago ha sido comprobado.";
}
?>