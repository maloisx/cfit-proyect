function ValidarLogeo() {
		
	var usuario=$('#txtuser'), password =$('#txtpass');
		if(usuario.val()!="" && password.val()!=""){

				$.ajax( 
			 	{
			 	dataType: "html",
			 	type: "POST",
			 	url: path + "index.php/logeo/validarlogeo/", 
			 	data: 	"user="+escape(usuario.val())+"&pass="+escape(password.val()),	 	 
				success: function(requestData){ 
			 		$('#reauth-user').html(requestData);
				},		
				error: function(requestData, strError, strTipoError){											
					$('#reauth-user').html(requestData);
				}
				});
				
		    }
	
	
}

