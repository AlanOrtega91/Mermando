<?php

$ch = curl_init();
$fieldsMessage = array(
		"currency"=> "MXN",
		"customer_info"=>array(
				"customer_id"=> "cus_zzmjKsnM9oacyCwV3"
				
		),
		"line_items"=>array(
				array(
						"name"=> "Box of Cohiba S1s",
						"unit_price"=>35000,
						"quantity"=>1
						
				),
				"charges"=>array(
						array(
								"payment_method"=>array(
										"type"=> "default"
										
								)
								
						)
						
				)
		)
		
);
curl_setopt($ch, CURLOPT_URL, "https://api.conekta.io/orders");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fieldsMessage));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, "key_D6HkDtvghhq8PdAxJ8uLAg" . ":" . "");

$headers = array("Accept: application/vnd.conekta-v2.0.0+json","Content-type: application/json");

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
	echo 'Error:' . curl_error($ch);
}
curl_close ($ch);
echo 'resultado='.var_dump($result);
?>