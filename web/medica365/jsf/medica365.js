(function ($){
  jQuery("document").ready(function(){
	  Conekta.setPublishableKey('key_MUou9QgjrL1DrcsYkQeBznA');
	  $('#comprar').submit(function tokenizar(event){
		  var numero = $('#numero-tarjeta').val();
		  var nombre = $('#nombre-tarjeta').val();
		  var mes = $('#mes-tarjeta').val();
		  var anio = $('#anio-tarjeta').val();
		  var cvv = $('#cvv-tarjeta').val();
		  
		  var tokenParams = {
				  "card": {
				    "number": numero,
				    "name": nombre,
				    "exp_year": anio,
				    "exp_month": mes,
				    "cvc": cvv,
				  }
		  };
		  Conekta.token.create(tokenParams, successResponseHandler, errorResponseHandler);
	  });
	  
	  
	  
	  var successResponseHandler = function(token){
		  $('#comprar').show();
		  $('#exito-compra').hide();
		  $('#fracaso-compra').hide();
		  $('#boton-comprar').prop('value', 'Realizando Transacción...');
		  
		  var nombre = $('#nombre').val();
		  var ocupacion = $('#ocupacion').val();
		  var telefono = $('#telefono-fijo').val();
		  var celular = $('#celular').val();
		  var email = $('#email').val();
		  var rfc = $('#rfc').val();
		  var beneficiario = $('#beneficiario').val();
		  var asociado = $('#clave-asociado').val();
		  
		  var parametros = {token: token.id, nombre: nombre, ocupacion: ocupacion, telefono: telefono, celular: celular, email: email, rfc: rfc, beneficiario: beneficiario, asociado: asociado};
		  var direccion = "http://medica365.vag.mx/api/comprar/";
		  $.post(direccion, parametros, function(data){
		      	console.log(data);
		        if(data.Status == "OK"){
		        	$('#comprar').hide("slow");
		        	$('#exito-compra').show("slow");
		        	$('#fracaso-compra').hide();
		        } else{
		        	$('#comprar').show();
		        	$('#exito-compra').hide();
		        	$('#fracaso-compra').show("slow");
		        	$('#boton-comprar').prop('value', 'Comprar');
		        }
		      }, "json");
	  };
	  
	  var errorResponseHandler = function(error){
		  console.log(error);
		  $('#comprar').show();
		  $('#exito-compra').hide();
		  $('#fracaso-compra').show("slow");
		  $('#fracaso-compra').prop('value', 'Existe un error en los datos de la tarjeta. Favor de revisarlo');
		  $('#boton-comprar').prop('value', 'Comprar');
	  };
	  
	  function enviarCompra(){
		  
	  }
  });
})(jQuery);