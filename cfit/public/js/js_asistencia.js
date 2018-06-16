function generar_reporteasistenciaemp(){
	var dni = $('#txtdni').val();
	var anio = $('#txtanionac').val();
	
	console.log(dni);
	console.log(anio);
	
	$.ajax( 
 	{
 	dataType: "html",
 	type: "POST",
 	url: path + "index.php/asistencia/validarreporteasistenciaemp/", 
 	data: 	"dni="+dni+"&anio="+anio,	 	 
	success: function(requestData){ 
 		$('#div_msjerror').html(requestData);
	},		
	error: function(requestData, strError, strTipoError){											
		$('#div_msjerror').html(respuesta);
	}
	});
}

function salir_reporte_emp(){
	window.open(path + "index.php/asistencia/logout/", "_self" );	
}