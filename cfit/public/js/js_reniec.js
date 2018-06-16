function buscar(){
	
	var dni = $('#txtdni').val();
	var pat = $('#txtpat').val();
	var mat = $('#txtmat').val();
	var nom = $('#txtnom').val();
	
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/reniec/buscarreniec/", 
	 	data: 	"dni="+dni+"&pat="+pat+"&mat="+mat+"&nom="+nom,	 	 
		beforeSend: function(data){ 	 	
			$('#div_buscar').html("Cargando...");
		},
		success: function(requestData){
			$('#div_buscar').html(requestData);
		},		
		error: function(requestData, strError, strTipoError){											
			$('#div_buscar').html("Error " + strTipoError +": " + strError);
		},
		complete: function(requestData, exito){ 
		
		}
	});
	
}