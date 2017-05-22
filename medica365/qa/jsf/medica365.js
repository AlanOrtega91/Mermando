(function ($){
  jQuery("document").ready(function(){
	  
	  var baseAPI = "https://medica365.vag.mx/api/qa/interfaz/";
	  
	  Conekta.setPublishableKey('key_eosVxYfvc7g6Hxo9j6Mgrsg');
	  $('#forma').submit(function tokenizar(event){
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
	  
	  var errorResponseHandler = function(error){
		  console.log(error);
		  mostrarError('Existe un error en los datos de la tarjeta. Favor de revisarlo');
	  };
	  
	  var successResponseHandler = function(token){
		  
		  var nombre = $('#nombre').val();
		  var ocupacion = $('#ocupacion').val();
		  var telefono = $('#telefono-fijo').val();
		  var celular = $('#celular').val();
		  var email = $('#email').val();
		  var rfc = $('#rfc').val();
		  var beneficiario = $('#beneficiario').val();
		  var asociado = $('#clave-asociado').val();
		  
		  var parametros = {token: token.id, nombre: nombre, ocupacion: ocupacion, telefono: telefono, celular: celular, email: email, rfc: rfc, beneficiario: beneficiario, asociado: asociado};
		  var direccion = baseAPI + "comprar/";
		  $.post(direccion, parametros, compraContesto, "json").fail(compraError);
	  };
	  
	  
	  var compraContesto = function (datos){
		  console.log(datos);
	        if(datos.status == "ok"){
	        	mosrarExito();
	        } else{
	        	if(datos.clave == "") {
	        		mostrarError('Error');
	        	}
	        }
	  }
	  
	  var compraError = function (datos){
		  console.log(datos);
		  mostrarError('Error de Servidor intentalo mas tarde');
	  }
	  
	  function mostrarExito(){
		  $('#forma').hide("slow");
		  $('#mensaje-exito').show("slow");
		  $('#mensaje-error').hide();
	  }
	  
	  function mostrarError(error){
		  $('#forma').show();
		  $('#mensaje-exito').hide();
		  $('#mensaje-error').show("slow");
		  $('#mensaje-error-texto').html(error);
		  $('#forma-boton').prop('value', 'Comprar');
	  }
  });
})(jQuery);