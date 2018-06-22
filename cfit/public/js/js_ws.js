function ws(p_sp,p_params) {         
    var respuesta = '';    
    $.ajax({
    	async: false,
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/ws/", 
	 	data: { sp : p_sp , params : JSON.stringify(p_params) } ,	 	 
		beforeSend: function(data){ 	 	
			//console.log("Cargando...");
		},
		success: function(requestData){
			//console.log(requestData);
			respuesta = requestData;
		},		
		error: function(requestData, strError, strTipoError){											
			console.log("Error ws: " + strTipoError +": " + strError);
		}
	});    
    return JSON.parse(respuesta);
    
}

