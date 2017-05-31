<?php
require_once dirname ( __FILE__ ) . "/../base-de-datos/BeneficiarioDB.php";
require_once dirname ( __FILE__ ) . "/../base-de-datos/ProductoDB.php";
require_once dirname ( __FILE__ ) . "/../base-de-datos/AsociadoDB.php";
require_once dirname ( __FILE__ ) . "/../base-de-datos/DatosPagoEsperaDB.php";
require_once dirname ( __FILE__ ) . "/Pagos.php";
require_once dirname ( __FILE__ ) . "/OrdenOxxo.php";
require_once dirname ( __FILE__ ) . "/../../recursos/FPDF/fpdf.php";
require_once dirname ( __FILE__ ) . "/../../recursos/FPDI/fpdi.php";
require_once dirname ( __FILE__ ) . "/../../recursos/PHPMailer/PHPMailerAutoload.php";


class Producto {
	
	const POLIZA = 'EH07195A';
	const MEDICA365 = 1;
	const MEDICA365_PRECIO = 399.00;
	const MEDICA365_NOMBRE = 'Tarjeta Medica365';
	

	public function comprarMedica365($token, $nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado) {
		date_default_timezone_set ( 'America/Mexico_City' );
		$vigencia = date ( "Y-m-d", strtotime('+1 year') );
		$destino = $email;
		
		
		$idTransaccion = $this->realizarPago($token, $nombre, $celular, $email);

		$certificado = $this->guardarDatos($nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado, $idTransaccion, $vigencia);
		
		$nombreCurado = iconv('UTF-8','windows-1252',$nombre);
		$pdf = $this->construirPDFMedica($certificado, $nombreCurado, $vigencia);
		
		$this->generarEmailUsuarioBienvenidaMedica365($nombreCurado, $certificado, $idTransaccion, $email, $pdf);
		$this->generarEmailAdminVenta(self::MEDICA365_NOMBRE, self::MEDICA365_PRECIO.".00",$idTransaccion, self::POLIZA, 'Poliza', $certificado, 'Certificado', $pdf);
	}
	
	function generarEmailUsuarioBienvenidaMedica365($nombre, $certificado, $idTransaccion, $email, $pdf) {
		$asunto = "Bienvenido a Medica365 DEV";
		

		$mensaje = $nombre." te damos la bienvenida al grupo, adjunto encontrarás el certificado que contiene tu tarjeta medica365.<br>
				A partir de este momento podrás acceder a los descuentos de prestadores de servicios y establecimientos con los que contamos.<br>
				La cobertura del seguro de ACCIDENTES PERSONALES así como la ASISTENCIA MÉDICA TELEFÓNICA, entrarán en vigor en un lapso de diez días hábiles.<br>
				Tu número de Póliza es: ". self::POLIZA." y tu número de certificado es: ".$certificado."<br>
				Tu número de orden es: ". $idTransaccion."<br>
						¡GRACIAS!<br>
				Correo de modo de desarrollo";
		$this->enviarEmail($asunto, $mensaje, $email, $pdf);
	}
	
	function generarEmailAdminVenta($producto, $precio,$idTransaccion, $info1, $info1Descripcion, $info2, $info2Descripcion, $pdf) {
		
		
		$mensajeAdmin = "Se ha realizado la siguiente transaccion:<br>
							<table style='width:500px;border:1px solid black'>
								<tr style='border:1px solid black'>
									<th style='border:1px solid black'>Producto</th>
									<th style='border:1px solid black'>Precio</th>
									<th style='border:1px solid black'>Numero de Orden</th>
									<th style='border:1px solid black'>".$info1Descripcion."</th>
									<th style='border:1px solid black'>".$info2Descripcion."</th>
								</tr>
								<tr>
									<th style='border:1px solid black'>".$producto."</th>
									<th style='border:1px solid black'>$".$precio."</th>
									<th style='border:1px solid black'>".$idTransaccion."</th>
									<th style='border:1px solid black'>".$info1."</th>
									<th style='border:1px solid black'>".$info2."</th>
								</tr>
							</table>
";
		$this->enviarEmail("Venta Realizada Dev", $mensajeAdmin, "vagmx2017@gmail.com", $pdf);
	}
		
	
	function generarOrdenOXXOMedica365($nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado) {
		$destino = $email;
		$asunto = "Orden de Pago Medica365 DEV";
		$emailAdmin = "vagmx2017@gmail.com";
		
		$orden = Pago::generarOrdenOXXO($nombre, $email, $celular, self::MEDICA365_PRECIO, 'Tarjeta Medica365', 'Tarjeta de Seguros de Gastos Medicos');
		
		$certificado = $this->guardarDatosOXXO($nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado, $orden->id, $vigencia);
		
		$nombreCurado = iconv('UTF-8','windows-1252', $nombre);
		
		$mensaje = OrdenOxxo::generarReciboHTML($orden->charges[0]->payment_method->reference, $orden->amount/100, $orden->currency);
		$this->enviarEmail($asunto, $mensaje, $destino, $pdf);
		return array("referencia"=>$orden->charges[0]->payment_method->reference,"precio"=> $orden->amount/100,"moneda"=> $orden->currency);
	}
	
	function ordenOXXOPagada($idTransaccion) {
		date_default_timezone_set ( 'America/Mexico_City' );
		$vigencia = date ( "Y-m-d", strtotime('+1 year') );
		
		$productoDB = new ProductoDB();
		$beneficiarioDB = new BeneficiarioDB();
		$comprador = $beneficiarioDB->leerDatosEnEspera($idTransaccion);
		
 		$certificado = $beneficiarioDB->guardarBeneficiario($comprador['nombre'], $comprador['ocupacion'],
 				$comprador['telefono'], $comprador['celular'], $comprador['email'], $comprador['RFC'], $comprador['beneficiario'], Producto::POLIZA, $vigencia);
		
		
		$productoDB->actualizarCertificadoYPagar($certificado, $idTransaccion);
		
		$beneficiarioDB->borrarDatosEnEspera($idTransaccion);
		
		$nombreCurado = iconv('UTF-8','windows-1252',$comprador['nombre']);
		$pdf = $this->construirPDFMedica($certificado, $nombreCurado, $vigencia);
		
		$this->generarEmailUsuarioBienvenidaMedica365($nombreCurado, $certificado, $idTransaccion, $comprador['email'], $pdf);
		$this->generarEmailAdminVenta(self::MEDICA365_NOMBRE, self::MEDICA365_PRECIO.".00",$idTransaccion, self::POLIZA, 'Poliza', $certificado, 'Certificado', $pdf);
	}
	
	function realizarPago($token, $nombre, $celular, $email)
	{
		$idUsuarioConekta = Pago::crearUsuario($token, $nombre, $celular, $email);
		$idTransaccion = Pago::realizarPago($idUsuarioConekta, Producto::MEDICA365_PRECIO, 'Tarjeta Medica365', 'Tarjeta de Seguros de Gastos Medicos');
		return $idTransaccion;
	}
	
	function guardarDatos($nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado, $idTransaccion, $vigencia)
	{
		$beneficiarioDB = new BeneficiarioDB();
		$productoDB = new ProductoDB();
		$asociadoDB = new AsociadoDB();
		
		$certificado = $beneficiarioDB->guardarBeneficiario($nombre, $ocupacion,
				$telefono, $celular, $email, $rfc, $beneficiario, Producto::POLIZA, $vigencia);

		if ($asociado) {
			if (!$asociadoDB->existeAsociado($asociado)) {
				$asociado = "null";
			}
		} else {
			$asociado = "null";
		}
		$productoDB->guardarOrden(Producto::MEDICA365, $asociado, $certificado, $idTransaccion, Producto::MEDICA365_PRECIO, 1);
		return $certificado;
	}

	function guardarDatosOXXO($nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado, $idOrden, $vigencia)
	{
		$datosPagoEsperaDB = new DatosPagoEsperaDB();
		$productoDB = new ProductoDB();
		$asociadoDB = new AsociadoDB();
		
		if ($asociado) {
			if (!$asociadoDB->existeAsociado($asociado)) {
				$asociado = "null";
			}
		} else {
			$asociado = "null";
		}
		$productoDB->guardarOrden(Producto::MEDICA365, $asociado, "null", $idOrden, Producto::MEDICA365_PRECIO, 0);
		$datosPagoEsperaDB->guardarDatosEspera($nombre, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, Producto::POLIZA, $idOrden);
	}
	
	function construirPDFMedica($certificado, $nombre, $vigencia) {
		try {
			$border = 1;
			$pdf = new FPDI();
			$pdf->AddPage();
			$pdf->setSourceFile(dirname ( __FILE__ ) . "/../../recursos/documentos/bienvenida-medica365.pdf");
			$page = $pdf->importPage(1);
			$pdf->useTemplate($page,0,0,null,null,false);
			$pdf->SetFont('Times','B',12);
			//Offset a la tabla
			$pdf->SetXY(168,64);
			//Dato de la tabla
			$pdf->Cell(29,6,self::POLIZA,$border,2,'C');
			$pdf->Cell(29,6,$certificado,$border,2,'C');
			$pdf->Cell(29,6,$vigencia,$border,2,'C');
			//Nombre
			$pdf->SetXY(0,95);
			$pdf->SetFont('Arial','BU',16);
			
			$pdf->Cell(0,8,'¡'.$nombre.'!',$border,1,'C');
			
			$pdf->SetFont('Times','B',10);
			//Tarjeta Nombre
			$pdf->SetXY(25,246);
			$pdf->Cell(60,4,$nombre,$border,2,'L');
			//Tarjeta poliza
			$pdf->SetXY(45,252);
			$pdf->Cell(60,4,self::POLIZA,$border,2,'L');
			//Tarjeta certificado
			$pdf->SetXY(52,257);
			$pdf->Cell(60,4,$certificado,$border,2,'L');
			//tarjeta vigencia
			$pdf->SetXY(25,262);
			$pdf->Cell(60,4,$vigencia,$border,2,'L');
			
			$path = dirname ( __FILE__ ) . "/../../recursos/medica365-afiliados/".self::POLIZA."-".$certificado.".pdf";
			$pdf->Output("F",$path,true);
			return $path;
		} catch (Exception $e) {
			return null;
		}
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
}
class errorSendingMailException extends Exception{
}
?>