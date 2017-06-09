(function ($){
  jQuery("document").ready(function(){
	  var baseAPI = "../../api/interfaz/";
	  var token = leerToken();
	  var direccionCambiarContrasena = baseAPI + "cambiar-contrasena/";
	  
	  
	  $('#forma').submit(function afiliarse(event){

		  $('#forma-boton').prop('value', 'Guardando...');
		  
		  var contrasenia = $('#contrasenia').val();
		  var contraseniaNueva = $('#contraseniaNueva').val();
		  var contraseniaNueva2 = $('#contraseniaNueva2').val();
		  
		  if(contraseniaNueva != contraseniaNueva2) {
			  mostrarError("Las contraseñas no coinciden");
			  return;
		  }
		  
		  var parametrosCambiarContrasena = {token: token, contrasenia: contrasenia, contraseniaNueva: contraseniaNueva};
		  $.post(direccionCambiarContrasena,parametrosCambiarContrasena, leerCambiarContrasenaRespondio,"json").fail(leerCambiarContrasenaError);
	  });
	  
	  
	  
	  
	  
	  var leerCambiarContrasenaRespondio = function (datos){
		  console.log(datos);
		  if(datos.status == "ok"){
			  mostrarExito();
			  } else{
				  if(datos.clave == "claves") {
					  mostrarError("La contraseña no coincide");
				  } else {
					  mostrarError("Error al cambair la contraseña");
				  }
			  }
		  }
	  
	  var leerCambiarContrasenaError = function (datos) {
		  console.log(datos);
		  mostrarError("Error con el servidor intentalo mas tarde");
	  }
	  
	  

	  function leerToken(){
		  if (typeof(Storage) !== "undefined") {
			  //HTML5 Web Storage
			  return sessionStorage.getItem('token');
			} else {
				// Save as Cookie
				return leerCookie("token");
			}
	  }
	  
	  function leerCookie(cname) {
		    var name = cname + "=";
		    var decodedCookie = decodeURIComponent(document.cookie);
		    var ca = decodedCookie.split(';');
		    for(var i = 0; i <ca.length; i++) {
		        var c = ca[i];
		        while (c.charAt(0) == ' ') {
		            c = c.substring(1);
		        }
		        if (c.indexOf(name) == 0) {
		            return c.substring(name.length, c.length);
		        }
		    }
		    return "";
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
		  $('#forma-boton').prop('value', 'Guardar');
	  }
	  
  });
})(jQuery);