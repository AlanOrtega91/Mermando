<?php
header ( 'Content-Type: text/html; charset=utf8' );

if (!isset($_POST['asunto']) || !isset($_POST['mensaje']) || !isset($_POST ['email'])) {
	die (json_encode(array("Status" => "ERROR missing values")));
}
//TODO: Cambiar email al correo correcto
$destino = "contacto@topbid.mx";
$asunto = $_POST ['asunto'];
$mensaje = $_POST ['mensaje'];
$fuente = $_POST['email'];



$mail = new PHPMailer(false);

//$mail->SMTPDebug = 3;                                 // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'as1r2052.servwingu.mx';  				// Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'topbidmx';                			 // SMTP username
$mail->Password = 'HV!wE!km8$E';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to



$mail->setFrom($fuente);
$mail->addAddress($destino);               			  // Name is optional

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $asunto;
$mail->Body    = $mensaje;


if($mail->send()) {
	echo json_encode(array("Status"=>"OK"));
} else {
	echo json_encode(array("Status"=>"ERROR"));
}
?>