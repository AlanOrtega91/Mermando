<?php
require_once dirname ( __FILE__ ) . "/../recursos/conekta-php/lib/Conekta.php";

class Pago {
	
	public static function crearUsuario($token, $nombre, $celular, $email) {
		\Conekta\Conekta::setApiKey("key_6e2z4xgxxasifz655rzFPA");
		\Conekta\Conekta::setApiVersion("2.0.0");		
		$customer = \Conekta\Customer::create(
				array(
						'name'  => $nombre,
						'email' => $email,
						'phone' => $celular,
						'payment_sources' => array(
								array(
										'type' => 'card',
										'token_id' => $token
								)
						)//payment_methods
				)//customer
			);
		if (!$customer) {
			throw new errorCreatingUserPaymentException ();
		}
		return $customer->id;
	}

	public static function realizarPago($idUsuarioConekta, $precio, $nombreProducto, $descripcionProducto) {
		\Conekta\Conekta::setApiKey("key_6e2z4xgxxasifz655rzFPA");
		\Conekta\Conekta::setApiVersion("2.0.0");
		$precioConekta =  $precio*100;
		$charge = \Conekta\Order::create(array(
				'line_items' => array(
						array(
								'name' => $nombreProducto,
								'description' => $descripcionProducto,
								'unit_price' => $precioConekta,
								'quantity' => 1
						)
				),//line_items
				'charges' => array(
						array(
								'payment_method' => array(
										'type' => 'default'
								)
						)
				),//charges
				'currency' => 'mxn',
				'customer_info' => array(
						'customer_id' => $idUsuarioConekta
				)//customer_info
				));
		if (! $charge)
			throw new errorMakingPaymentException ();
		return $charge->id;
	}
}

class errorMakingPaymentException extends Exception {
}
class errorCreatingUserPaymentException extends Exception {
}
?>