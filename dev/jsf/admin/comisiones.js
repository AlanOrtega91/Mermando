(function ($){
  jQuery("document").ready(function(){
	  var baseAPI = "../api/interfaz/admin/";
	  var token = leerToken();

	  
	  
	  var direccionComision = baseAPI + "comisiones/";
	  var parametrosComision = {token: token};
	  
	  var leerComisionRespondio = function (datos){
		  console.log(datos);
	        if(datos.status == "ok"){
	        	construirLista(datos.asociados);
	        } else{

	        }
	  }
	  
	  var leerComisionError = function (datos) {
		  console.log(datos);
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
	  
	  function construirLista(asociados) {
		  var listaHTML = "";
		  $.each(asociados, function(index,asociado){
			  listaHTML += "<li class='list-item'> " +
			  		"<div class='w-row'> " +
			  			"<div class='w-col w-col-6'>" +
			  				"<h1>" + asociado.nombre + " (" + asociado.id + ")</h1>" +
			  				"<div>" + asociado.metodoPago + "</div>" +
		  				"</div>" +
			  		"<div class='w-col w-col-4'>" +
			  			"<h1>Comision</h1>" +
			  			"<div>$" + asociado.comision +"</div>" +
			  		"</div>" +
			  		"<div class='w-col w-col-2'><a class='form-button w-button' href='#' id='" + asociado.id + "'>Pagar Comisión</a>" +
			  		"</div>" +
			  		"</div>" +
			  		"</li>";
		  });
		  $('#lista-comisiones').html(listaHTML);
	  }
	  
  });
})(jQuery);