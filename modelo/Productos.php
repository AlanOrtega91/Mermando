<?php
require_once dirname ( __FILE__ ) . "/../base-de-datos/DataBase.php";
require_once dirname ( __FILE__ ) . "/Pagos.php";
require_once dirname ( __FILE__ ) . "/../recursos/FDPDF/MultiCellBlt2.php";
require_once dirname ( __FILE__ ) . "/../recursos/PHPMailer/PHPMailerAutoload.php";


class Productos {
	private $dataBase;
	const POLIZA = 'HZ0123';
	const MEDICA365 = 1;
	const MEDICA365_PRECIO = 365.00;
	
	public function __construct() {
		$this->dataBase = new DataBase();
	}

	public function comprarMedica365($token, $nombre, $primerApellido, $segundoApellido, $ocupacion, $telefono, $celular, $email, $rfc, $beneficiario, $asociado) {
		$idUsuarioConekta = Pago::crearUsuario($token, $nombre." ".$primerApellido." ".$segundoApellido, $celular, $email);
		$idTrasaccion = Pago::realizarPago($idUsuarioConekta, Productos::MEDICA365_PRECIO, 'Tarjeta Medica365', 'Tarjeta de Seguros de Gastos Medicos');
		date_default_timezone_set ( 'America/Mexico_City' );
		$vigencia = date ( "Y-m-d", strtotime('+1 year') );
		$certificado = $this->dataBase->guardarBeneficiario($nombre, $primerApellido, $segundoApellido, $ocupacion, 
				$telefono, $celular, $email, $rfc, $beneficiario, Productos::POLIZA, $vigencia);
		$this->dataBase->guardarOrden(Productos::MEDICA365, $asociado, $certificado, $idTrasaccion, Productos::MEDICA365_PRECIO);
		
		$destino = $email;
		$asunto = "Bienvenido a Medica365";
		//TODO: Configurar el mensaje
		$pdf = $this->buildMedicaPDF($certificado, $nombre." ".$primerApellido." ".$segundoApellido,$vigencia);
		$mensaje = $nombre." ".$primerApellido." ".$segundoApellido." te damos la bienvenida al grupo, adjunto encontraras el certificado que contiene tu tarjeta medica365.
				A partir de este momento podrás acceder a los descuentos de prestadores de servicios y establecimientos con los que contamos.
				La cobertura del seguro de ACCIDENTES PERSONALES así como la ASISTENCIA MEDICA TELEFÓNICA, entraran en vigor en un lapso de diez días hábiles.
				Tu numero de Poliza es: ". Productos::POLIZA." y tu numero de certificado es: ".$certificado."
						¡GRACIAS!";
		$this->sendMail($asunto, $mensaje, $destino, $pdf);
		
	}
	
	function sendMail($asunto,$mensaje,$destino, $attach){
		$mail = new PHPMailer(false);
		
		//$mail->SMTPDebug = 3;                                 // Enable verbose debug output
		
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'as1r2052.servwingu.mx';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'topbidmx';                 // SMTP username
		$mail->Password = 'HV!wE!km8$E';                           // SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 465;                                    // TCP port to connect to
		
		
		
		$mail->setFrom('no-reply@topbid.mx', 'Medica365');
		$mail->addAddress($destino);               			  // Name is optional

		$mail->addAttachment($attach, '', $encoding = 'base64', $type = 'application/pdf');							  // Add attachments
		$mail->isHTML(true);                                  // Set email format to HTML
		
		$mail->Subject = $asunto;
		$mail->Body    = $mensaje;

		
		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} 
	}
	
	function buildMedicaPDF($certificado, $nombre, $vigencia) {
		$tableWidth1 = 40;
		$tableWidth2 = 45;
		$tableHeight = 8;
		$pdf = new PDF('P','mm');
		$pdf->AddPage();
		$pdf->Image('http://localhost/Mermando/recursos/imagenes/logo_mail.png',50,10,110,0,'PNG');
		$pdf->Cell(100,50,'',0,2);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(100,8,'',1,0);
		$pdf->Cell($tableWidth1,$tableHeight,'POLIZA',1,0,'C');
 		$pdf->Cell($tableWidth2,$tableHeight,Productos::POLIZA,1,1,'C');
 		$pdf->Cell(100,8,'',1,0);
 		$pdf->Cell($tableWidth1,$tableHeight,'CERTIFICADO',1,0,'C');
 		$pdf->Cell($tableWidth2,$tableHeight,$certificado,1,1,'C');
 		$pdf->Cell(100,8,'',1,0);
 		$pdf->Cell($tableWidth1,$tableHeight,'VIGENCIA',1,0,'C');
 		$pdf->Cell($tableWidth2,$tableHeight,$vigencia,1,1,'C');
		
		$pdf->SetFont('Arial','BU',16);
		$pdf->Cell(0,8,'¡'.$nombre.'!',1,1,'C');
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
		$pdf->Image('http://localhost/Mermando/recursos/imagenes/tarjeta_corte.png',50,$pdf->getPageHeight() - 60 ,110,0,'PNG');
		$pdf->Cell(0,30,'',1,1);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(40.5,3,"",1,0);
		$pdf->Cell(35,3.2,$nombre,1,1,'C');
		$pdf->Cell(40.5,3.2,"",1,0);
		$pdf->Cell(35,3.2,Productos::POLIZA,1,1,'C');
		$pdf->Cell(44,3.2,"",1,0);
		$pdf->Cell(35,3.2,$certificado,1,1,'C');
		$pdf->Cell(37,3.2,"",1,0);
		$pdf->Cell(34,3.2,$vigencia,1,1,'C');
		$pdf->Output("F",dirname ( __FILE__ ) . "/../recursos/pdf/".Productos::POLIZA."-".$certificado.".pdf",true);
		return dirname ( __FILE__ ) . "/../recursos/pdf/".Productos::POLIZA."-".$certificado.".pdf";
	}
}
class errorSendingMailException extends Exception{
}
?>