(function ($){
  jQuery("document").ready(function(){
	  var baseAPI = "http://vag.mx/api/interfaz/";
	  
	  $('#forma').submit(function afiliarse(event){

		  $('#forma-boton').prop('value', 'Enviando...');
		  
		  var email = $('#email').val();
		  var contraseña = $('#contrasenia').val();
		  
		  
		  var parametros = {email: email, contrasenia: contraseña};
		  var direccion = baseAPI + "inicio-sesion/";
		  $.post(direccion, parametros, inicioSesionRespondio, "json").fail(inicioSesionError);
	  });
	  
	  var inicioSesionRespondio = function (datos){
		  console.log(datos);
	        if(datos.status == "ok"){
	        	guardarToken(datos.token);
	        	window.location.replace("http://vag.mx/cuenta/cuenta.html");
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
	  
	  var inicioSesionError = function (datos) {
		  console.log(datos);
		  mostrarError('Error de Servidor intentalo mas tarde');
	  }
	  
	  function guardarToken(token){
		  if (typeof(Storage) !== "undefined") {
			  //HTML5 Web Storage
			  sessionStorage.setItem('token',token);
			} else {
				// Save as Cookie
				document.cookie = 'vag=' + token;
			}
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