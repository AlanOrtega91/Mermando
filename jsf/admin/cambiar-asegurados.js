(function ($){
  jQuery("document").ready(function(){
	  var baseAPI = "../api/interfaz/admin/";
	  var token = leerToken();
	  
	  
	  var direccionAsegurados = baseAPI + "cambiar-asegurados/";
	  
	  var leerAseguradosRespondio = function (datos){
		  console.log(datos);
	        if(datos.status == "ok"){
	        	location.reload();
	        } else{
	        	alert("Hubo error al cambiar los datos");
	        }
	  }
	  
	  var leerAseguradosError = function (datos) {
		  console.log(datos);
	  }
	  
	  //$.post(direccionAsegurados, parametrosAsegurados, leerAseguradosRespondio,"json").fail(leerAseguradosError);
	  
	  
	  $('#boton-asegurados').click(function() {
		  if($(this).val() == "agregar"){
			  agregar();
		  } else if ($(this).val() == "baja"){
			  baja();
		  } else {
			  
		  }
      });
	  
	  function agregar(){
		  var first = true;
		  var certificados = "";
          $('table').find('tr').each(function (){
        	  if (first) {
        		  first = false;
        		  return;
        	  }
        	  if ($(this).children().find('input').is(':checked')) {
        		  certificados += $(this).children(":first").text() + ",";
        	  }
          });
          certificados = certificados.slice(0,-1);
          console.log(certificados);
          var parametrosAsegurados = {token: token, certificados: certificados, tipo:"0"};
          $.post(direccionAsegurados, parametrosAsegurados, leerAseguradosRespondio,"json").fail(leerAseguradosError);
	  }
	  
	  function baja(){
		  
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
	  
  });
})(jQuery);