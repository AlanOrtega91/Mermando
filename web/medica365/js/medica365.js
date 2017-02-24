(function ($){
  jQuery("document").ready(function(){
	  Conekta.setPublishableKey('key_MY3JCSc73ZxNonTsreczf2g');
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
		  
		  var nombre = $('#nombre').val();
		  var primerApellido = $('#primer-apellido').val();
		  var segundoApellido = $('#segundo-apellido').val();
		  var ocupacion = $('#ocupacion').val();
		  var telefono = $('#telefono-fijo').val();
		  var celular = $('#celular').val();
		  var email = $('#email').val();
		  var rfc = $('#rfc').val();
		  var beneficiario = $('#beneficiario').val();
		  var asociado = $('#clave-asociado').val();
		  
		  var parametros = {token: token.id, nombre: nombre, primerApellido: primerApellido, segundoApellido: segundoApellido, ocupacion: ocupacion, telefono: telefono, celular: celular, email: email, rfc: rfc, beneficiario: beneficiario, asociado: asociado};
		  var direccion = "http://topbid.mx/Mermando/api/medica365/comprar/";
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
		        }
		      }, "json");
	  };
	  
	  var errorResponseHandler = function(error){
		  console.log(error);
		  $('#comprar').show();
		  $('#exito-compra').hide();
		  $('#fracaso-compra').show("slow");
	  };
	  
	  function enviarCompra(){
		  
	  }
  });
})(jQuery);