<?php
require_once dirname ( __FILE__ ) . "/../base-de-datos/DataBase.php";
require_once dirname ( __FILE__ ) . "/../recursos/PHPMailer/PHPMailerAutoload.php";

class Afiliado {
	
	private $dataBase;
	
	public function __construct() {
		$this->dataBase = new DataBase();
	}
	
	public function guardarNuevoAfiliado($nombre, $apellido, $email, $contraseña)
	{
		$this->dataBase->nuevoAfiliado($nombre, $apellido, $email, $contraseña);
		
		$asunto = "¡Bienvenido a Mermando!";
		$mensaje = "";
		$this->enviarCorreo($asunto, $mensaje, $mail);
	}
	
	function enviarCorreo($asunto,  $mensaje,$destino)
	{
		//TODO: cambiar a imagen y cambiar ciertas cosas solamente
		$mail = new PHPMailer(false);
	
		//$mail->SMTPDebug = 3;                                 // Enable verbose debug output
	
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'as1r2052.servwingu.mx';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'topbidmx';                 // SMTP username
		$mail->Password = 'HV!wE!km8$E';                           // SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 465;                                    // TCP port to connect to
	
	
	
		$mail->setFrom('no-reply@topbid.mx', 'Mermando');
		$mail->addAddress($destino);               			  // Name is optional
	
		$mail->isHTML(true);                                  // Set email format to HTML
	
		$mail->Subject = $asunto;
		$mail->Body    = $mensaje;
	
	
		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
	}
}

class errorSendingMailException extends Exception{
}
?>