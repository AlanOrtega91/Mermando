(function ($){
  jQuery("document").ready(function(){
	  var baseAPI = "../api/interfaz/";
	  var direccionCuenta = baseAPI + "cuenta/";
	  var token = leerToken();
	  var parametrosCuenta = {token: token};
	  
	  var leerCuentaRespondio = function (datos){
		  console.log(datos);
	        if(datos.status == "ok"){
	        	$('#cuenta-nombre').text(datos.info.nombre + ' (' + datos.info.id + ')');
	        	$('#cuenta-email').html(datos.info.email);
	        } else{
	        	alert("Error leyendo la cuenta");
	        }
	  }
	  
	  var leerCuentaError = function (datos) {
		  console.log(datos);
		  alert("Error con el servidor al leer la cuenta");
	  }
	  
	  $.post(direccionCuenta,parametrosCuenta, leerCuentaRespondio,"json").fail(leerCuentaError);

	  
	  
	  var direccionComision = baseAPI + "comision-actual/";
	  var parametrosComision = {token: token};
	  
	  var leerComisionRespondio = function (datos){
		  console.log(datos);
	        if(datos.status == "ok"){
	        	$('#cuenta-comisiones').html("$ " + datos.comision);
	        } else{
	        	alert("Error leyendo la comision");
	        }
	  }
	  
	  var leerComisionError = function (datos) {
		  console.log(datos);
		  alert("Error con el servidor al leer comision");
	  }
	  
	  $.post(direccionComision,parametrosComision, leerComisionRespondio,"json").fail(leerComisionError);
	  
	  
	  
	  
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
	  
  });
})(jQuery);