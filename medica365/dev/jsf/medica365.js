(function ($){
  jQuery("document").ready(function(){
	  
	  var baseAPI = "https://medica365.vag.mx/api/dev/interfaz/";
	  var sending = false;
	  var TARJETA = 1;
	  var OXXO = 2;
	  var metodo = TARJETA;
	  Conekta.setPublishableKey('key_MUou9QgjrL1DrcsYkQeBznA');
	  
	  $('#metodo-pago-tarjeta').click(function (event) {
		  $('#tarjeta-info').show();
		  $('#forma-boton').prop('value', 'Comprar');
		  metodo = TARJETA;
	  });
	  $('#metodo-pago-oxxo').click(function (event) {
		  $('#tarjeta-info').hide();
		  $('#forma-boton').prop('value', 'Generar Orden');
		  metodo = OXXO;
	  });
	  
	  
	  $('#forma').submit(function tokenizar(event){
		  if (sending) {
			  console.log("regresa");
			  return;
		  }
		  sending = true;
		  
		  
		  if (metodo == TARJETA) {
			  $('#forma-boton').prop('value', 'Comprando...');
			  var tokenParams = {
					  "card": {
					    "number": $('#numero-tarjeta').val(),
					    "name": $('#nombre-tarjeta').val(),
					    "exp_year": $('#anio-tarjeta').val(),
					    "exp_month": $('#mes-tarjeta').val(),
					    "cvc": $('#cvv-tarjeta').val(),
					  }
			  };
			  Conekta.token.create(tokenParams, successResponseHandler, errorResponseHandler);
		  } else if (metodo == OXXO) {
			  $('#forma-boton').prop('value', 'Generando...');
			  generarOrden();
		  } else {
			  mostrarError("Metodo de pago no aceptado");
		  }
		  
	  });
	  
	  function generarOrden() {
		  var nombre = $('#nombre').val();
		  var ocupacion = $('#ocupacion').val();
		  var telefono = $('#telefono-fijo').val();
		  var celular = $('#celular').val();
		  var email = $('#email').val();
		  var rfc = $('#rfc').val();
		  var beneficiario = $('#beneficiario').val();
		  var asociado = $('#clave-asociado').val();
		  
		  var parametros = {nombre: nombre, ocupacion: ocupacion, telefono: telefono, celular: celular, email: email, rfc: rfc, beneficiario: beneficiario, asociado: asociado};
		  var direccion = baseAPI + "generar-orden/";
		  $.post(direccion, parametros, ordenContesto, "json").fail(ordenError);
	  }
	  
	  var ordenContesto = function (datos){
		  console.log(datos);
	        if(datos.status == "ok"){
	        	mostrarRecibo(datos.datos.precio, datos.datos.moneda, datos.datos.referencia);
	        } else{
	        	if(datos.clave == "datos") {	
	        		mostrarError(datos.explicacion);
	        	} else if(datos.clave == "pago") {	
	        		mostrarError(datos.explicacion);
	        	} else {
	        		mostrarError("Existe un error revisa tu informacion o intenta mas tarde");
	        	}
	        }
	  }
	  
	  var ordenError = function (datos){
		  console.log(datos);
		  mostrarError('Error de Servidor intentalo mas tarde');
	  }
	  
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
	        	mostrarExito();
	        } else{
	        	if(datos.clave == "datos") {	
	        		mostrarError(datos.explicacion);
	        	} else if(datos.clave == "pago") {	
	        		mostrarError(datos.explicacion);
	        	} else {
	        		mostrarError("Existe un error revisa tu informacion o intenta mas tarde");
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
	  
	  function mostrarRecibo(precio, moneda, referencia){
		  $('#forma').hide();
		  $('#mensaje-exito').hide();
		  $('#mensaje-error').hide();
		  $('#recibo-oxxo').show();
		  $('#recibo-oxxo-precio').html("$ " + precio + ".00 <sup>" + moneda + "</sup>");
		  $('#recibo-oxxo-referencia').html(referencia);
	  }
	  
	  function mostrarError(error){
		  $('#forma').show();
		  $('#mensaje-exito').hide();
		  $('#mensaje-error').show("slow");
		  $('#mensaje-error-texto').html(error);
		  $('#forma-boton').prop('value', 'Comprar');
		  sending = false;
	  }
  });
})(jQuery);