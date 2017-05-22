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
			  $('#mensaje-error').text('Contraseñas no coinciden');
			  $('#mensaje-error').show('slow');
			  return;
		  }
		  
		  var parametros = {nombre: nombre, email: email, contrasenia: contraseña, numeroOrden: numeroOrden};
		  var direccion = baseAPI + "afiliarse/";
		  $.post(direccion, parametros, afiliacionRespondio, "json").fail(afiliacionError);
	  });
	  
	  var afiliacionRespondio = function (datos){
		  console.log(datos);
	        if(datos.status == "ok"){
	        	$('#afiliarse').hide("slow");
	        	$('#mensaje-exito').show("slow");
	        	$('#mensaje-fracaso').hide();
	        } else{
	        	if(datos.clave == "") {
	        		mostrarError('Error');
	        	}
	        }
	  }
	  
	  var afiliacionError = function (datos) {
		  console.log(datos);
		  mostrarError("Error");
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