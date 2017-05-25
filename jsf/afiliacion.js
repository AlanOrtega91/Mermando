(function ($){
  jQuery("document").ready(function(){
	  var baseAPI = "http://vag.mx/api/interfaz/";
	  
	  $('#forma').submit(function afiliarse(event){

		  $('#forma-boton').prop('value', 'Enviando...');
		  
		  var nombre = $('#nombre').val();
		  var email = $('#email').val();
		  var contraseña = $('#contrasenia').val();
		  var contraseña2 = $('#contrasenia2').val();
		  var numeroOrden = $('#orden-compra').val();
		  
		  if (contraseña != contraseña2) {
			  mostrarError("Contraseñas no coinciden");
			  return;
		  }
		  
		  var parametros = {nombre: nombre, email: email, contrasenia: contraseña, numeroOrden: numeroOrden};
		  var direccion = baseAPI + "afiliarse/";
		  $.post(direccion, parametros, afiliacionRespondio, "json").fail(afiliacionError);
	  });
	  
	  var afiliacionRespondio = function (datos){
		  console.log(datos);
	        if(datos.status == "ok"){
	        	mostrarError();
	        } else{
	        	if(datos.clave == "email") {	
	        		mostrarError("El email ya esta siendo usado en otra cuenta");
	        	} else if(datos.clave == "orden") {	
	        		mostrarError("El numero de orden es invalido");
	        	} else {
	        		mostrarError("Error al registrar la cuenta. Intentalo mas tarde");
	        	}
	        }
	  }
	  
	  var afiliacionError = function (datos) {
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
		  $('#forma-boton').prop('value', 'Enviar');
	  }
	  
  });
})(jQuery);