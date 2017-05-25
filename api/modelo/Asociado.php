<?php
require_once dirname ( __FILE__ ) . "/../base-de-datos/AsociadoDB.php";
require_once dirname ( __FILE__ ) . "/../../recursos/PHPMailer/PHPMailerAutoload.php";

class Asociado {
	
	private $dataBase;
	
	public function __construct() {
		$this->dataBase = new AsociadoDB();
	}
	
	public function nuevoAsociado($nombre, $email, $contraseña, $orden)
	{
		if ($this->dataBase->existeEmail($email)) {
			throw new errorEmailUsado();
		}
		if (!$this->dataBase->ordenDeCompraLibre($orden)) {
			throw new errorOrdenUsada();		
		}
		
		$this->dataBase->nuevoAsociado($nombre, $email, $contraseña);
		$this->dataBase->usarOrdenEnRegistro($orden);
		
		$asunto = "¡Bienvenido a Mermando!";
		$mensaje = "";
		$this->enviarCorreo($asunto, $mensaje, $mail);
	}
	
	function enviarEmail($asunto,$mensaje,$destino, $attach){
		
		//SMTP needs accurate times, and the PHP time zone MUST be set
		//This should be done in your php.ini, but this is how to do it if you don't have access to that
		date_default_timezone_set('Etc/UTC');
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
		//Set the hostname of the mail server
		$mail->Host = "as1r2064.servwingu.mx";
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = 26;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication
		$mail->Username = "vagmx";
		//Password to use for SMTP authentication
		$mail->Password = 'NnTt2$mhH*d';
		
		$mail->Subject = $asunto;
		$mail->Body    = $mensaje;
		
		$mail->setFrom('ventas@vag.mx', 'Medica365');
		$mail->addAddress($destino);						// Name is optional
		
		$mail->addAttachment($attach, 'Certificado Medica365', $encoding = 'base64', $type = 'application/pdf');	  // Add attachments
		$mail->isHTML(true);                                // Set email format to HTML
		
		
		
		if(!$mail->send()) {
			//echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			//echo "enviado";
		}
	}
	
	
	function iniciarSesion($email, $contraseña) {
		
		if (!$this->dataBase->existeEmail($email)) {
			throw new errorEmailNoExiste();
		}
		if (!$this->dataBase->clavesCoinciden($email, $contraseña)){
			throw new errorClavesNoCoinciden();
		}
		date_default_timezone_set ( 'America/Mexico_City' );
		$fecha = date ( "Y-m-d H:i:s");
		return $this->dataBase->crearSesion($email, $fecha);
	}
	
	function leerCuenta($token) {
		return $this->dataBase->leerCuenta($token);
	}
	
	function leerComisionActual($token) {
		$comisionesTotales = $this->dataBase->comisionesTotales($token);
		$comisionesPagadas = $this->dataBase->comisionesPagadasTotales($token);
		return $comisionesTotales[0] - $comisionesPagadas[0];
	}
}

class errorSendingMailException extends Exception{
}
class errorEmailUsado extends Exception{
}
class errorOrdenUsada extends Exception{
}
class errorEmailNoExiste extends Exception{
}
class errorClavesNoCoinciden extends Exception{
}
?>