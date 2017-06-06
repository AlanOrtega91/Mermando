(function ($){
  jQuery("document").ready(function(){
	  var baseAPI = "http://vag.mx/dev/api/interfaz/";
	  var direccionCuenta = baseAPI + "cuenta/";
	  var token = leerToken();
	  var parametrosCuenta = {token: token};
	  
	  var leerCuentaRespondio = function (datos){
		  console.log(datos);
	        if(datos.status == "ok"){
	        	$('#cuenta-nombre').val(datos.info.nombre);
	        	$('#cuenta-email').val(datos.info.email);
	        } else{

	        }
	  }
	  
	  var leerCuentaError = function (datos) {
		  console.log(datos);
		  mostrarError("Error con el servidor");
	  }
	  
	  $.post(direccionCuenta,parametrosCuenta, leerCuentaRespondio,"json").fail(leerCuentaError);
	  
	  var direccionCambiarCuenta = baseAPI + "cambiar-cuenta/";
	  
	  $('#forma').submit(function afiliarse(event){

		  $('#forma-boton').prop('value', 'Guardando...');
		  
		  var email = $('#cuenta-email').val();
		  var nombre = $('#cuenta-nombre').val();
		  var parametrosCambiarCuenta = {token: token, email: email, nombre: nombre};
		  
		  $.post(direccionCambiarCuenta,parametrosCambiarCuenta, leerCambiarCuentaRespondio,"json").fail(leerCambiarCuentaError);
	  });
	  
	  
	  
	  
	  
	  var leerCambiarCuentaRespondio = function (datos){
		  console.log(datos);
		  if(datos.status == "ok"){
			  mostrarExito();
			  } else{
				  
			  }
		  }
	  
	  var leerCambiarCuentaError = function (datos) {
		  console.log(datos);
		  mostrarError("Error con el servidor");
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