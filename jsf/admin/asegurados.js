(function ($){
  jQuery("document").ready(function(){
	  var baseAPI = "../api/interfaz/admin/";
	  var token = leerToken();
	  var aseguradosActivos = [];
	  var aseguradosActivosPorAgregar = [];
	  var aseguradosVencidos = [];
	  var aseguradosVencidosPorDarDeBaja = [];
	  
	  
	  var direccionAsegurados = baseAPI + "leer-asegurados/";
	  var parametrosAsegurados = {token: token};
	  
	  var leerAseguradosRespondio = function (datos){
		  console.log(datos);
	        if(datos.status == "ok"){
	        	aseguradosActivos = datos.aseguradosActivos;
	        	aseguradosActivosPorAgregar = datos.aseguradosActivosPorAgregar;
	        	aseguradosVencidos = datos.aseguradosVencidos;
	        	aseguradosVencidosPorDarDeBaja = datos.aseguradosVencidosPorDarDeBaja;
	        } else{

	        }
	  }
	  
	  var leerAseguradosError = function (datos) {
		  console.log(datos);
	  }
	  
	  $.post(direccionAsegurados, parametrosAsegurados, leerAseguradosRespondio,"json").fail(leerAseguradosError);
	  
	  
	  
	  
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
	  
	  $('#asegurados').click(function(){
		  $('#asegurados-titulo').text("Asegurados Activos");
		  var listaHTML = "<style>" +
		  		"table, th, td {" +
		  		"border: 1px solid black;" +
		  		"border-collapse: collapse;" +
		  		"text-align: center;" +
		  		"}" +
		  		"</style>" +
		  		"<table style='width:100%'>" +
		  				"<tr>" +
		  				"<th>Certificado</th>" +
		  				"<th>Nombre</th> " +
		  				"<th>RFC</th>" +
		  				"</tr>";
		  $.each(aseguradosActivos, function(index,asegurado){
			  listaHTML += "<tr>" +
			  		"<td>" + asegurado.certificado + "</td>" +
			  		"<td>" + asegurado.nombre + "</td> " +
			  		"<td>" + asegurado.rfc + "</td>" +
			  		"</tr>";
		  });
		  listaHTML += "</table>";
		  $('#tabla-asegurados').html(listaHTML);
		  $('#boton-asegurados').hide();
	  });
	  
	  $('#aseguradosEspera').click(function(){
		  $('#asegurados-titulo').text("Asegurados En Espera");
		  var listaHTML = "<style>" +
		  		"table, th, td {" +
		  		"border: 1px solid black;" +
		  		"border-collapse: collapse;" +
		  		"text-align: center;" +
		  		"}" +
		  		"</style>" +
		  		"<table style='width:100%'>" +
		  				"<tr>" +
		  				"<th>Certificado</th>" +
		  				"<th>Nombre</th> " +
		  				"<th>RFC</th>" +
		  				"<th>Agregar</th>" +
		  				"</tr>";
		  $.each(aseguradosActivosPorAgregar, function(index,asegurado){
			  listaHTML += "<tr>" +
			  		"<td>" + asegurado.certificado + "</td>" +
			  		"<td>" + asegurado.nombre + "</td> " +
			  		"<td>" + asegurado.rfc + "</td>" +
			  		"<td><input id='" + asegurado.id + "' type='checkbox' name='agregar'></td>" +
			  		"</tr>";
		  });
		  listaHTML += "</table>";
		  $('#tabla-asegurados').html(listaHTML);
		  $('#boton-asegurados').show();
		  $('#boton-asegurados').prop('value', 'Agregar');
	  });
	  
	  $('#vencidosBaja').click(function(){
		  $('#asegurados-titulo').text("Asegurados Vencidos");
		  var listaHTML = "<style>" +
		  		"table, th, td {" +
		  		"border: 1px solid black;" +
		  		"border-collapse: collapse;" +
		  		"text-align: center;" +
		  		"}" +
		  		"</style>" +
		  		"<table style='width:100%'>" +
		  				"<tr>" +
		  				"<th>Certificado</th>" +
		  				"<th>Nombre</th> " +
		  				"<th>RFC</th>" +
		  				"<th>Dar de Baja</th>" +
		  				"</tr>";
		  $.each(aseguradosVencidosPorDarDeBaja, function(index,asegurado){
			  listaHTML += "<tr>" +
			  		"<td>" + asegurado.certificado + "</td>" +
			  		"<td>" + asegurado.nombre + "</td> " +
			  		"<td>" + asegurado.rfc + "</td>" +
			  		"<td><input id='" + asegurado.id + "' type='checkbox' name='agregar'></td>" +
			  		"</tr>";
		  });
		  listaHTML += "</table>";
		  $('#tabla-asegurados').html(listaHTML);
		  $('#boton-asegurados').show();
		  $('#boton-asegurados').prop('value', 'Dar de Baja');
	  });
	  
	  $('#vencidos').click(function(){
		  $('#asegurados-titulo').text("Asegurados Dados De Baja");
		  var listaHTML = "<style>" +
		  		"table, th, td {" +
		  		"border: 1px solid black;" +
		  		"border-collapse: collapse;" +
		  		"text-align: center;" +
		  		"}" +
		  		"</style>" +
		  		"<table style='width:100%'>" +
		  				"<tr>" +
		  				"<th>Certificado</th>" +
		  				"<th>Nombre</th> " +
		  				"<th>RFC</th>" +
		  				"</tr>";
		  $.each(aseguradosVencidos, function(index,asegurado){
			  listaHTML += "<tr>" +
			  		"<td>" + asegurado.certificado + "</td>" +
			  		"<td>" + asegurado.nombre + "</td> " +
			  		"<td>" + asegurado.rfc + "</td>" +
			  		"</tr>";
		  });
		  listaHTML += "</table>";
		  $('#tabla-asegurados').html(listaHTML);
		  $('#boton-asegurados').hide();
	  });
	  
  });
})(jQuery);