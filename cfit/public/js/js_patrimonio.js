function buscarpatrim(){
	
	$('#div_roleo').hide();
	$('#txtcantidad').val('');
	
	var codpatrim = $('#txtcodpatrim').val();
	
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/patrimonio/buscarpatrim/", 
	 	data: 	"codpatrim="+codpatrim,	 	 
		beforeSend: function(data){ 	 	
			$('#div_buscar').html("Cargando...");
		},
		success: function(requestData){
			var patrim = jQuery.parseJSON(requestData);
						
			if(patrim[0] != '000000000000'){
				$('#div_roleo').show();
				$('#div_buscar').html('<b>BIEN ENCONTRADO:</b> '+patrim[1]);			
			}else{
				$('#div_buscar').html('<b>'+patrim[1]+'</b>');
			}
		},		
		error: function(requestData, strError, strTipoError){											
			$('#div_buscar').html("Error " + strTipoError +": " + strError);
		},
		complete: function(requestData, exito){ 
		
		}
	});
	
}

function rolearpatrim(){
	
	var codpatrim = $('#txtcodpatrim').val();
	var cant = $('#txtcantidad').val();
	
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/patrimonio/rolearpatrim/", 
	 	data: 	"codpatrim="+codpatrim+"&cant="+cant,	 	 
		beforeSend: function(data){ 	 	
			$('#div_buscar').html("Cargando...");
		},
		success: function(requestData){
			$('#div_mensaje_roleo').html('<b>'+requestData+'</b>');			
		},		
		error: function(requestData, strError, strTipoError){											
			$('#div_mensaje_roleo').html("Error " + strTipoError +": " + strError);
		},
		complete: function(requestData, exito){ 
		
		}
	});
	
}
alert('xxx');

function buscarpatrim_pa500(){
		
	var codpatrim = $('#txtcodpatrim').val();
	
	alert(codpatrim);
	
//	$.ajax({
//	 	dataType: "html",
//	 	type: "POST",
//	 	url: path + "index.php/patrimonio/buscarpatrim/", 
//	 	data: 	"codpatrim="+codpatrim,	 	 
//		beforeSend: function(data){ 	 	
//			$('#div_buscar').html("Cargando...");
//		},
//		success: function(requestData){
//			var patrim = jQuery.parseJSON(requestData);
//						
//			if(patrim[0] != '000000000000'){
//				$('#div_roleo').show();
//				$('#div_buscar').html('<b>BIEN ENCONTRADO:</b> '+patrim[1]);			
//			}else{
//				$('#div_buscar').html('<b>'+patrim[1]+'</b>');
//			}
//		},		
//		error: function(requestData, strError, strTipoError){											
//			$('#div_buscar').html("Error " + strTipoError +": " + strError);
//		},
//		complete: function(requestData, exito){ 
//		
//		}
//	});
	
}