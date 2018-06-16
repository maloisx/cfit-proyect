function ValidarLogeo() {
//	var user = $('#txtuser').val();
//	var pass = $('#txtpass').val();
		
	var usuario=$('#txtuser'), password =$('#txtpass'), captcha=$('#captcha');
    var usuario_mens=$("#usuario-mens"), password_mens=$("#password-mens"), captcha_mens=$("#captcha-mens");
    var all_inputs = $( [] ).add( usuario ).add( password ).add( captcha );
    var all_texts  = $( [] ).add( usuario_mens ).add( password_mens ).add( captcha_mens );

    all_inputs.removeClass( "input-error" );
    all_texts.text("");

		if(usuario.val()==""){
		        usuario.addClass("input-error");
		        usuario_mens.text("Introduce tu Usuario");
		    }
		if(password.val()==""){
		        password.addClass("input-error");
		        password_mens.text("Introduce tu Clave para el Sistema");
		    }
//		if(captcha.val()==""){
//		        captcha.addClass("input-error");
//		        captcha_mens.text("Introduce los caracteres de la imagen");
//		    }
		
//		if(usuario.val()!="" && password.val()!="" && captcha.val()!=""){
		if(usuario.val()!="" && password.val()!=""){

				$.ajax( 
			 	{
			 	dataType: "html",
			 	type: "POST",
			 	url: path + "index.php/logeo/validarlogeo/", 
			 	data: 	"user="+escape(usuario.val())+"&pass="+escape(password.val())+"&captcha="+captcha.val(),	 	 
				success: function(requestData){ 
			 		captcha_mens.html(requestData);
				},		
				error: function(requestData, strError, strTipoError){											
					all_inputs.addClass("input-error");
                    captcha_mens.html(respuesta);
				}
				});
				
		    }
	
	
}

