<?php
require_once dirname ( __FILE__ ) . "/../base-de-datos/BeneficiarioDB.php";
require_once dirname ( __FILE__ ) . "/../base-de-datos/ProductoDB.php";
require_once dirname ( __FILE__ ) . "/Pagos.php";
require_once dirname ( __FILE__ ) . "/../recursos/FDPDF/MultiCellBlt2.php";
require_once dirname ( __FILE__ ) . "/../recursos/PHPMailer/PHPMailerAutoload.php";


class Producto {
	
	const POLIZA = 'HZ0123';
	const MEDICA365 = 1;
	const MEDICA365_PRECIO = 365.00;
	

	public function comprarMedica365($token, $nombre, $primerApellido, $segundoApellido, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado) {
		date_default_timezone_set ( 'America/Mexico_City' );
		$vigencia = date ( "Y-m-d", strtotime('+1 year') );
		$destino = $email;
		$asunto = "Bienvenido a Medica365";
		$emailAdmin = "vagmx2017@gmail.com";
		
		$idTransaccion = $this->realizarPago($token, $nombre, $primerApellido, $segundoApellido, $celular, $email);

		$certificado = $this->guardarDatos($token, $nombre, $primerApellido, $segundoApellido, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado, $idTransaccion, $vigencia);
		
		$pdf = $this->construirPDFMedica($certificado, $nombre." ".$primerApellido." ".$segundoApellido,$vigencia);
		
		
		$mensaje = $nombre." ".$primerApellido." ".$segundoApellido." te damos la bienvenida al grupo, adjunto encontraras el certificado que contiene tu tarjeta medica365.<br>
				A partir de este momento podrás acceder a los descuentos de prestadores de servicios y establecimientos con los que contamos.<br>
				La cobertura del seguro de ACCIDENTES PERSONALES así como la ASISTENCIA MEDICA TELEFÓNICA, entraran en vigor en un lapso de diez días hábiles.<br>
				Tu numero de Poliza es: ". self::POLIZA." y tu numero de certificado es: ".$certificado."<br>
						¡GRACIAS!";
		//TODO: Esto en un thread diferente
		$this->enviarEmail($asunto, $mensaje, $destino, $pdf);
		$mensajeAdmin = "Se ha realizado la siguiente transaccion";
		$this->enviarEmail("Venta Realizada", $mensajeAdmin, $emailAdmin, $pdf);
		
	}
	
	function realizarPago($token, $nombre, $primerApellido, $segundoApellido, $celular, $email)
	{
		$idUsuarioConekta = Pago::crearUsuario($token, $nombre." ".$primerApellido." ".$segundoApellido, $celular, $email);
		$idTransaccion = Pago::realizarPago($idUsuarioConekta, Producto::MEDICA365_PRECIO, 'Tarjeta Medica365', 'Tarjeta de Seguros de Gastos Medicos');
		return $idTransaccion;
	}
	
	function guardarDatos($token, $nombre, $primerApellido, $segundoApellido, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado, $idTransaccion, $vigencia)
	{
		$beneficiarioDB = new BeneficiarioDB();
		$productoDB = new ProductoDB();
		
		$certificado = $beneficiarioDB->guardarBeneficiario($nombre, $primerApellido, $segundoApellido, $ocupacion,
				$telefono, $celular, $email, $rfc, $beneficiario, Producto::POLIZA, $vigencia);

		$productoDB->guardarOrden(Producto::MEDICA365, $asociado, $certificado, $idTransaccion, Producto::MEDICA365_PRECIO);
		return $certificado;
	}

	
	function construirPDFMedica($certificado, $nombre, $vigencia) {
		//TODO: cambiar a imagen y cambiar ciertas cosas solamente
		$border = 0;
		$tableWidth1 = 40;
		$tableWidth2 = 45;
		$tableHeight = 8;
		$pdf = new PDF('P','mm');
		$pdf->AddPage();
		$pdf->Image(dirname ( __FILE__ ) . "/../recursos/imagenes/logo_mail.png",50,10,110,0,'PNG');
		$pdf->Cell(100,50,'',$border,2);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(100,8,'',$border,0);
		$pdf->Cell($tableWidth1,$tableHeight,'POLIZA',$border,0,'C');
		$pdf->Cell($tableWidth2,$tableHeight,self::POLIZA,$border,1,'C');
		$pdf->Cell(100,8,'',$border,0);
		$pdf->Cell($tableWidth1,$tableHeight,'CERTIFICADO',$border,0,'C');
		$pdf->Cell($tableWidth2,$tableHeight,$certificado,$border,1,'C');
		$pdf->Cell(100,8,'',$border,0);
		$pdf->Cell($tableWidth1,$tableHeight,'VIGENCIA',$border,0,'C');
		$pdf->Cell($tableWidth2,$tableHeight,$vigencia,$border,1,'C');
		
		$pdf->SetFont('Arial','BU',16);
		$pdf->Cell(0,8,'¡'.$nombre.'!',$border,1,'C');
		$pdf->SetFont('Arial','',12);
		$pdf->MultiCell(0,8,'Felicitaciones, te has afiliado con éxito al grupo de beneficios “MEMBRESIA MEDICA365”, por lo que ahora cuentas con un SEGURO DE ACCIDENTES PERSONALES, el cual te brinda protección y tranquilidad cuando más la necesites. Así mismo, tienes acceso a descuentos con diversos prestadores de servicios y establecimientos, los cuales podrás consultar en nuestra página web diariamente. Tus nuevos beneficios son:',1,'J');
		

		$column_width = $pdf->getPageWidth() - 30;
		$lista = array();
		$lista['bullet'] = '>';
		$lista['margin'] = ' ';
		$lista['indent'] = 5;
		$lista['spacer'] = 5;
		$lista['text'] = array(
				'Asesoría médica telefónica 24/7',
				'Consultas médicas a domicilio',
				'Servicio de ambulancia sin costo',
				'Reembolso de gastos médicos por accidentes personales hasta $15,000 pesos al año',
				'Seguro por muerte accidental de $100,000 pesos',
				'Seguro de pérdidas orgánicas de $100,000 pesos',
				'Descuentos en análisis clínicos, estudios radiológicos, farmacias, tiendas, restaurantes');
		$pdf->SetX(20);
		$pdf->MultiCellBltArray($column_width-$pdf->getX(),6,$lista);
		$pdf->Ln(10);
		$pdf->WriteHTML('*Consulta términos y condiciones en: <a href="www.tarjetamedica365.com">www.tarjetamedica365.com</a>');
		$pdf->Ln();
		$pdf->Image(dirname ( __FILE__ ) . "/../recursos/imagenes/tarjeta_corte.png",50,$pdf->getPageHeight() - 60 ,110,0,'PNG');
		$pdf->Cell(0,30,'',$border,1);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(40.5,3,"",$border,0);
		$pdf->Cell(35,3.2,$nombre,$border,1,'C');
		$pdf->Cell(40.5,3.2,"",$border,0);
		$pdf->Cell(35,3.2,self::POLIZA,$border,1,'C');
		$pdf->Cell(44,3.2,"",$border,0);
		$pdf->Cell(35,3.2,$certificado,$border,1,'C');
		$pdf->Cell(37,3.2,"",$border,0);
		$pdf->Cell(34,3.2,$vigencia,$border,1,'C');
		$pdf->Output("F",dirname ( __FILE__ ) . "/../recursos/medica365-afiliados/".self::POLIZA."-".$certificado.".pdf",true);
		return dirname ( __FILE__ ) . "/../recursos/medica365-afiliados/".self::POLIZA."-".$certificado.".pdf";
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
		$mail->Host = "as1r2052.servwingu.mx";
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = 26;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication
		$mail->Username = "topbidmx";
		//Password to use for SMTP authentication
		$mail->Password = 'HV!wE!km8$E';
	
		$mail->Subject = $asunto;
		$mail->Body    = $mensaje;
		
		$mail->setFrom('ventas@topbid.mx', 'Medica365');
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