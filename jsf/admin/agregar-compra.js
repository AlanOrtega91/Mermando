(function ($){
  jQuery("document").ready(function(){
	  
	  var direccion = "../api/interfaz/admin/agregar-compra/";
	  var sending = false;
	  
	  $('#forma').submit(function tokenizar(event){
		  $('#forma-boton').prop('value', 'Comprando...');
		  if (sending) {
			  console.log("regresa");
			  return;
		  }
		  sending = true;

		  var nombre = $('#nombre').val();
		  var ocupacion = $('#ocupacion').val();
		  var telefono = $('#telefono-fijo').val();
		  var celular = $('#celular').val();
		  var email = $('#email').val();
		  var rfc = $('#rfc').val();
		  var beneficiario = $('#beneficiario').val();
		  var asociado = $('#clave-asociado').val();
		  var referencia = $('#referencia').val();
		  if (nombre == "" || ocupacion == "" || telefono == "" || celular == "" || email == "" || rfc == "" || beneficiario == "" || referencia == "") {
			  mostrarError("Faltan valores");
			  return;
		  }
		  
		  var parametros = {nombre: nombre, ocupacion: ocupacion, telefono: telefono, celular: celular, email: email, rfc: rfc, beneficiario: beneficiario, asociado: asociado, referencia: referencia};
		  $.post(direccion, parametros, contesto, "json").fail(error);
	  });
	  

	  
	  var contesto = function (datos){
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
	  
	  var error = function (datos){
		  console.log(datos);
		  mostrarError('Error de Servidor intentalo mas tarde');
	  }
	  
	  function mostrarExito(){
		  $('#forma').hide("slow");
		  $('#mensaje-exito').show("slow");
		  $('#mensaje-error').hide();
		  sending = false;
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