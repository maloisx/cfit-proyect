function mant_tipconv(c_tipconv){
	//console.log(c_tipconv);
	
	$.lightbox(path + "index.php/sisconv/tipconvmant/?c_tipconv="+c_tipconv, {
        'width'       : 300, 
        'height'      : 200, 
        'autoresize'  : true,
        'modal': true
      });
	
}

function guardar_tipconv(){
	
	var c_tipconv = $('#c_tipconv').html();
	var d_tipconv = trim($('#d_tipconv').val());
	
	var error = '';
	
	if(d_tipconv == ''){
		error += 'Ingrese la descripci&oacute;n.<br>';		
	}
	
	//console.log(c_tipconv + '->' + d_tipconv);
	
	if(error != ''){
		$("#div_mensaje").addClass("ui-state-error");
		$('#div_mensaje').html(error);
	}else{
		$('#div_mensaje').html('');
		$("#div_mensaje").removeClass("ui-state-error");
		
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/sisconv/guardartipconv/", 
		 	data: 	"c_tipconv="+c_tipconv+"&d_tipconv="+d_tipconv,	 	 
			success: function(requestData){ 
				$('#c_tipconv').html(requestData);//se escribe la respuesta en el componente indicado
				cerrarsubvent();//para cerrar la ventana 
				
				$.ajax({
				 	dataType: "html",
				 	type: "POST",
				 	url: path + "index.php/sisconv/tipconv/", 
				 	data: 	"listardatos=1",	 	 
					success: function(requestData){ 					
						$("#div_reloaddata").html(requestData);
					},		
					error: function(requestData, strError, strTipoError){
						console.log("Error " + strTipoError +": " + strError);
					}
			 	});
				
			},		
			error: function(requestData, strError, strTipoError){
				console.log("Error " + strTipoError +": " + strError);
			}
	 	});
	}
}

function mant_sector(c_sector){
	//console.log(c_tipconv);
	
	$.lightbox(path + "index.php/sisconv/sectormant/?c_sector="+c_sector, {
        'width'       : 300, 
        'height'      : 200, 
        'autoresize'  : true,
        'modal': true
      });
	
}

function guardar_sector(){
	
	var c_sector = $('#c_sector').html();
	var d_sector = trim($('#d_sector').val());
	
	var error = '';
	
	if(d_sector == ''){
		error += 'Ingrese la descripci&oacute;n.<br>';		
	}
	
	//console.log(c_tipconv + '->' + d_tipconv);
	
	if(error != ''){
		$("#div_mensaje").addClass("ui-state-error");
		$('#div_mensaje').html(error);
	}else{
		$('#div_mensaje').html('');
		$("#div_mensaje").removeClass("ui-state-error");
		
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/sisconv/guardarsector/", 
		 	data: 	"c_sector="+c_sector+"&d_sector="+d_sector,	 	 
			success: function(requestData){ 
				$('#c_sector').html(requestData);//se escribe la respuesta en el componente indicado
				cerrarsubvent();//para cerrar la ventana 
				
				$.ajax({
				 	dataType: "html",
				 	type: "POST",
				 	url: path + "index.php/sisconv/sector/", 
				 	data: 	"listardatos=1",	 	 
					success: function(requestData){ 					
						$("#div_reloaddata").html(requestData);
					},		
					error: function(requestData, strError, strTipoError){
						console.log("Error " + strTipoError +": " + strError);
					}
			 	});
				
			},		
			error: function(requestData, strError, strTipoError){
				console.log("Error " + strTipoError +": " + strError);
			}
	 	});
	}
}

function mant_perconv(c_perconv){
	//console.log(c_perconv);
	
	$.lightbox(path + "index.php/sisconv/perconvmant/?c_perconv="+c_perconv, {
        'width'       : 300, 
        'height'      : 200, 
        'autoresize'  : true,
        'modal': true
      });
	
}



function guardar_perconv(){
	
	var c_perconv = $('#c_perconv').html();
	var d_perconv = trim($('#d_perconv').val());
	var n_perconv = trim ($('#n_perconv').val());
	
	var error = '';
	
	if(d_perconv == ''){
		error += 'Ingrese la descripci&oacute;n.<br>';		
	}
	
	if(n_perconv == ''){
		error += 'Ingrese el  n&uacute;mero de d&iacute;as<br>';		
	}
	
	//console.log(c_tipconv + '->' + d_tipconv);
	
	if(error != ''){
		$("#div_mensaje").addClass("ui-state-error");
		$('#div_mensaje').html(error);
	}else{
		$('#div_mensaje').html('');
		$("#div_mensaje").removeClass("ui-state-error");
		
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/sisconv/guardarperconv/", 
		 	data: 	"c_perconv="+c_perconv+"&d_perconv="+d_perconv+"&n_perconv="+n_perconv,	 	 
			success: function(requestData){ 
				$('#c_perconv').html(requestData);//se escribe la respuesta en el componente indicado
				cerrarsubvent();//para cerrar la ventana 
				
				$.ajax({
				 	dataType: "html",
				 	type: "POST",
				 	url: path + "index.php/sisconv/perconv/", 
				 	data: 	"listardatos=1",	 	 
					success: function(requestData){ 					
						$("#div_reloaddata").html(requestData);
					},		
					error: function(requestData, strError, strTipoError){
						console.log(respuesta);
					}
			 	});
				
			},		
			error: function(requestData, strError, strTipoError){
				console.log(respuesta);
			}
	 	});
	}
}
	
function mant_respooblig(c_respooblig){
	//console.log(c_perconv);
	
	$.lightbox(path + "index.php/sisconv/respoobligmant/?c_respooblig="+c_respooblig, {
        'width'       : 300, 
        'height'      : 200, 
        'autoresize'  : true,
        'modal': true
      });
	
}	
	

function guardar_respooblig(){
	
	var c_respooblig = $('#c_respooblig').html();
	var d_respooblig = trim($('#d_respooblig').val());
	console.log(c_respooblig+'-'+d_respooblig);
	var error = '';
	
	if(d_respooblig == ''){
		error += 'Ingrese la descripci&oacute;n.<br>';		
	}
	
	//console.log(c_tipconv + '->' + d_tipconv);
	
	if(error != ''){
		$("#div_mensaje").addClass("ui-state-error");
		$('#div_mensaje').html(error);
	}else{
		$('#div_mensaje').html('');
		$("#div_mensaje").removeClass("ui-state-error");
		
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/sisconv/guardarrespooblig/", 
		 	data: 	"c_respooblig="+c_respooblig+"&d_respooblig="+d_respooblig,	 	 
			success: function(requestData){ 
				$('#c_respooblig').html(requestData);//se escribe la respuesta en el componente indicado
				cerrarsubvent();//para cerrar la ventana 
				
				$.ajax({
				 	dataType: "html",
				 	type: "POST",
				 	url: path + "index.php/sisconv/respooblig/", 
				 	data: 	"listardatos=1",	 	 
					success: function(requestData){ 					
						$("#div_reloaddata").html(requestData);
					},		
					error: function(requestData, strError, strTipoError){
						console.log(respuesta);
					}
			 	});
				
			},		
			error: function(requestData, strError, strTipoError){
				console.log(strError);
			}
	 	});
	}
}


function mant_entidad(c_entidad){
	//console.log(c_tipconv);
	
	$.lightbox(path + "index.php/sisconv/entidadmant/?c_entidad="+c_entidad, {
        'width'       : 300, 
        'height'      : 300, 
        'autoresize'  : true,
        'modal': true
      });
	
}

function guardar_entidad(){
	
	var c_entidad = $('#c_entidad').html();
	var d_entidad = trim($('#d_entidad').val());
	var d_ruc = trim($('#d_ruc').val());
	var c_sector = trim($('#cbsector').val());
	
	var error = '';
	
	if(d_entidad == ''){
		error += 'Ingrese nombre de la Entidad.<br>';		
	}if(d_ruc == ''){
		error += 'Ingrese el Ruc.<br>';		
	}if(c_sector == '99999'){
		error += 'Seleccionar Sector.<br>';		
	}
	
	//console.log(c_tipconv + '->' + d_tipconv);
	
	if(error != ''){
		$("#div_mensaje").addClass("ui-state-error");
		$('#div_mensaje').html(error);
	}else{
		$('#div_mensaje').html('');
		$("#div_mensaje").removeClass("ui-state-error");
		
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/sisconv/guardarentidad/", 
		 	data: 	"c_entidad="+c_entidad+"&d_entidad="+d_entidad+"&d_ruc="+d_ruc+"&c_sector="+c_sector,	 	 
			success: function(requestData){ 
			   
			   if(requestData.substring(0, 5).toUpperCase() != 'ERROR'){
				 $('#c_entidad').html(requestData);
				 cerrarsubvent();//para cerrar la ventana 
				
					$.ajax({
					 	dataType: "html",
					 	type: "POST",
					 	url: path + "index.php/sisconv/entidad/", 
					 	data: 	"listardatos=1",	 	 
						success: function(requestData){ 					
							$("#div_reloaddata").html(requestData);
						},		
						error: function(requestData, strError, strTipoError){
							console.log("Error " + strTipoError +": " + strError);
						}
				 	});
				
				}else{
					$('#div_mensaje').html(requestData);				
				}
				
			},		
			error: function(requestData, strError, strTipoError){
				console.log("Error " + strTipoError +": " + strError);
			}
	 	});
	}
}

function mant_convenio(c_convenio){
	//console.log(c_tipconv);
	
	$.lightbox(path + "index.php/sisconv/conveniomant/?c_convenio="+c_convenio, {
        'width'       : 750, 
        'height'      : 650, 
        'autoresize'  : true,
        'modal': true
      });
	
}

function mant_obligacion(cod_obligacion, desc_obligacion , cod_responsable , boton ){

	c_convenio = $('#c_convenio').html();
	
	if(c_convenio != ''){
	
		var index = $(boton).attr('cindex');
		if(index == undefined)
			index = '';
		
		$.lightbox2(path + "index.php/sisconv/obligacionmant/?cod_obligacion="+cod_obligacion+"&desc_obligacion="+desc_obligacion+"&cod_responsable="+cod_responsable+"&index="+index+"&c_convenio="+c_convenio, {
	        'width'       : 500, 
	        'height'      : 300, 
	        'autoresize'  : true,
	        'modal': true
	      });
	}else{
		alert('REGISTRAR PRIMERO EL CONVENIO.');
	}
}

function aniadir_obligacion(tbl_index,c_obligacion, d_obligacion , c_responsable , d_responsable ){
	
	if(c_obligacion == undefined) c_obligacion = '';
	if(d_obligacion == undefined) d_obligacion = trim($('#d_obligacion_obligmant').val());
	if(c_responsable == undefined) c_responsable = $('#cbresponsable_obligmant').val();
	if(d_responsable == undefined) d_responsable = $('#cbresponsable_obligmant option:selected').text();
	
var error = '';
	
	if(d_obligacion == ''){
		error += 'Ingrese Obligaci&oacute;n.<br>';		
	}if(c_responsable == '99999'){
		error += 'Seleccionar Responsable.<br>';		
	}
	
	//console.log(c_tipconv + '->' + d_tipconv);
	
	if(error != ''){
		$("#div_mensaje_obligmant").addClass("ui-state-error");
		$('#div_mensaje_obligmant').html(error);
	}else{
		$('#div_mensaje_obligmant').html('');
		$("#div_mensaje_obligmant").removeClass("ui-state-error");
	
		var oTable = $('#tbl_obligaciones').dataTable();
				
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/sisconv/guardarobligaciones/", 
		 	data: 	"cod_conv="+$('#c_convenio').html()+"&cod_oblig="+c_obligacion+"&desc_oblig="+d_obligacion+"&cod_resp="+c_responsable,	 	 
			success: function(requestData){ 	
			
				c_obligacion = requestData;
				
				if(tbl_index != ''){
					var node = oTable.fnGetNodes(tbl_index);
					oTable.fnUpdate(['<span class="nro_oblig" cod_oblig="'+c_obligacion+'" c_responsable="'+c_responsable+'" nro="" d_obligacion="'+d_obligacion+'"><span>'
		                             ,d_obligacion
		                             ,d_responsable
		                             ,'<ul><li class="ui-state-default ui-corner-all editar_obligacion" title="Editar" style="width:20px;float:left" cindex=""  cod_oblig="'+c_obligacion+'" onclick="mant_obligacion(\''+c_obligacion+'\',\'\',\''+c_responsable+'\',this)"><span class="ui-icon ui-icon-pencil"></span></li>'+
		                             '<li class="ui-state-default ui-corner-all ui-state-error borrar_obligacion" title="Eliminar Obligaci&oacute;n" style="width:20px;float:left" cindex="" onclick="borrar_obligaciones(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>']
		                            ,node);
					
				}else{
					oTable.fnAddData(['<span class="nro_oblig" cod_oblig="'+c_obligacion+'" c_responsable="'+c_responsable+'" nro="" d_obligacion="'+d_obligacion+'"><span>'
					                             ,d_obligacion
					                             ,d_responsable
					                             ,'<ul><li class="ui-state-default ui-corner-all editar_obligacion" title="Editar" style="width:20px;float:left" cindex=""  onclick="mant_obligacion(\''+c_obligacion+'\',\''+d_obligacion+'\',\''+c_responsable+'\',this)"><span class="ui-icon ui-icon-pencil"></span></li>'+
					                             '<li class="ui-state-default ui-corner-all ui-state-error borrar_obligacion" title="Eliminar Obligaci&oacute;n" style="width:20px;float:left" cindex=""  cod_oblig="'+c_obligacion+'" onclick="borrar_obligaciones(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>']);
				}
				
				actualizar_nro_obligaciones();
				
				
			},		
			error: function(requestData, strError, strTipoError){
				console.log("Erroren guardar obligacion " );
			}
	 	});
		
		
		cerrarsubvent2();
	
		
		
	}
	
}

function actualizar_nro_obligaciones(){

	var responsables = new Array();
	$(".nro_oblig").each(function(){
		var c_responsable = $(this).attr('c_responsable');	
		responsables.push(c_responsable);
	});	
	responsables = responsables.unique();
		
	
	for(var i = 0; i<responsables.length ; i ++){
		var index = 1;
		$(".nro_oblig").each(function(){
			var c_responsable = $(this).attr('c_responsable');	
			if(responsables[i] == c_responsable){
				$(this).html(LPad(index.toString(),3,'0'));
				$(this).attr('nro',LPad(index.toString(),3,'0'));
				index++;
			}
		});			
	}	
	
	//index tabla
		
		var tbl_index = 0; 
		$(".borrar_obligacion").each(function(){
			$(this).attr('cindex',tbl_index);	
			tbl_index++;
		});	
		tbl_index = 0; 
		$(".editar_obligacion").each(function(){
			$(this).attr('cindex',tbl_index);	
			tbl_index++;
		});	
	
}

function borrar_obligaciones(boton){
	var index = $(boton).attr('cindex');
	var cod_oblig = $(boton).attr('cod_oblig');
	var oTable = $('#tbl_obligaciones').dataTable();	   
    
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/sisconv/borrarobligaciones/", 
	 	data: 	"cod_oblig="+cod_oblig,	 	 
		success: function(requestData){ 					
			if(requestData == '1'){
				oTable.fnDeleteRow( index );
			    actualizar_nro_obligaciones();
		    }else{
		    	console.log("Error " + strTipoError +": " + strError);
		    }
		},		
		error: function(requestData, strError, strTipoError){
			console.log("Error " + strTipoError +": " + strError);
		}
 	});
	
	
}

function estacionseleccion(){
		
	$.lightbox2(path + "index.php/sisconv/estacionseleccion/", {
        'width'       : 900, 
        'height'      : 300, 
        'autoresize'  : true,
        'modal': false
      });
	
}

function aniadirestacion(codestacion,nomestacion,nomddrr){
	var oTable = $('#tbl_estaciones').dataTable();
	oTable.dataTable().fnAddData([codestacion
		                             ,nomestacion
		                             ,nomddrr
		                             ,'<ul><li class="ui-state-default ui-corner-all ui-state-error borrar_estacion estacion" codestacion="'+codestacion+'" title="Eliminar Estaci&oacute;n" style="width:20px;float:left" cindex="" onclick="borrar_estacion(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>']);
	
	cerrarsubvent2();
	actualizar_index_estacion();
}

function actualizar_index_estacion(){
	var tbl_index = 0; 
	$(".borrar_estacion").each(function(){
		$(this).attr('cindex',tbl_index);	
		tbl_index++;
	});	
}

function borrar_estacion(boton){
	var index = $(boton).attr('cindex');
	var oTable = $('#tbl_estaciones').dataTable();	   
    oTable.fnDeleteRow( index );
    actualizar_index_estacion();
}


function rangosfechas_convenio(){
	$(document).ready(function() {
		var d = new Date();
		var dt = d.getFullYear() + '-' + LPad((d.getMonth()+1).toString(),2,'0')+ '-'+ LPad(d.getDate().toString(),2,'0');
		
//		$( "#fecha_ini" ).val( dt );
//		$( "#fecha_fin" ).val( dt );
		
		$( "#fecha_ini" ).datepicker({
            //changeMonth: true,
			//maxDate: (($( "#fecha_fin" ).val() == '')?'':'0'),
            dateFormat : 'yy-mm-dd',
            defaultDate: new Date(),
            onClose: function( selectedDate ) {
                $( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#fecha_fin" ).datepicker({
            //changeMonth: true,
        	//maxDate: '0',
            dateFormat : 'yy-mm-dd',
            onClose: function( selectedDate ) {
                $( "#fecha_ini" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
}

function rangosfechas(fechaini,fechafin,diaactual){
	$(document).ready(function() {
		
		var dt = '';
		if(diaactual == undefined || diaactual == false || diaactual == '')
			dt = '';
		else{
			var d = new Date();
			dt = d.getFullYear() + '-' + LPad((d.getMonth()+1).toString(),2,'0')+ '-'+ LPad(d.getDate().toString(),2,'0');
		}
				
		if($( "#"+fechaini ).val() == '' ) $( "#"+fechaini ).val( dt );
		if($( "#"+fechafin ).val() == '' ) $( "#"+fechafin ).val( dt );
		
		$( "#"+fechaini ).datepicker({
            //changeMonth: true,
			maxDate: (($( "#"+fechafin ).val() == '')?'':'0'),
            dateFormat : 'yy-mm-dd',
            defaultDate: new Date(),
            onClose: function( selectedDate ) {
                $( "#"+fechafin ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#"+fechafin ).datepicker({
            //changeMonth: true,
        	//maxDate: '0',
            dateFormat : 'yy-mm-dd',
            onClose: function( selectedDate ) {
                $( "#"+fechaini ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
}

function tipconv_tabs(){
	
	
	var cod_convenio = $( "#c_convenio" ).html();
	var cod_tipconv = $( "#cbtipoconvenio" ).val();
	
		if(cod_tipconv=="1"){
			if(cod_convenio == '')
				$('#tabs').tabs('option','disabled', [1,2,3,4] );
			else
				$('#tabs').tabs('option','disabled', [1,2,3] );	
		}if(cod_tipconv=="2"){
			if(cod_convenio == ''){
				$( "#tabs" ).tabs( "enable", 1 );
				$( "#tabs" ).tabs( "enable", 2 );
				$( "#tabs" ).tabs( "enable", 3 );
				$('#tabs').tabs('option','disabled', [4] );
			}else{
				$( "#tabs" ).tabs( "enable", 1 );
				$( "#tabs" ).tabs( "enable", 2 );
				$( "#tabs" ).tabs( "enable", 3 );
				$( "#tabs" ).tabs( "enable", 4 );
			}
		}
	
}

function cargar_emp_unid(){
	var cod_unid = $( "#cbunidad" ).val();
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/sisconv/cargarempunid/", 
	 	data: 	"cod_unid="+cod_unid,	 	 
		success: function(requestData){ 					
			$("#cb_responsable_senamhi").html(requestData);
		},		
		error: function(requestData, strError, strTipoError){
			console.log("Error " + strTipoError +": " + strError);
		}
 	});
}


function cargar_perio_eval(datos){
	
	if(datos == undefined ) datos = '';
	
	console.log(datos);
	
	var cod_perioeval = $( "#cb_perioeval" ).val();
	
	var fecha_ini = $( "#fecha_ini" ).val();
	var fecha_fin = $( "#fecha_fin" ).val();
	var dia_diff = diferencias_dias(fecha_ini,fecha_fin);
	
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/sisconv/cargarperioeval/", 
	 	data: 	"cod_perioeval="+cod_perioeval+"&dia_diff="+dia_diff+"&datos="+datos,	 	 
		success: function(requestData){ 					
			$("#div_tbl_periodoseval").html(requestData);
		},		
		error: function(requestData, strError, strTipoError){
			console.log("Error " + strTipoError +": " + strError);
		}
 	});
	
}

function guardar_convenio(){
	var cod_convenio = $( "#c_convenio" ).html();
	var nom_convenio = $( "#d_convenio" ).val();
	var cod_tipconv = $( "#cbtipoconvenio" ).val();
//	var cod_entidad = $( "#cbentidad" ).val();
//	var resp_entidad = $( "#d_responsable_entidad" ).val();
	var resol_aprob = $( "#d_resolucion" ).val();
	var obj_espc = $( "#d_objetivo" ).val();
	var cod_unid = $( "#cbunidad" ).val();
	var resp_senamhi = $( "#cb_responsable_senamhi" ).val();
	var fecha_ini = $( "#fecha_ini" ).val();
	var fecha_fin = $( "#fecha_fin" ).val();
	
	var error = '';
	
	var xml='';
	
//	xml +="<>"++"</>";
	
	xml += '<d>'+"\n";
		xml +="<cod_convenio>"+cod_convenio+"</cod_convenio>"+"\n";
		xml +="<nom_convenio>"+nom_convenio+"</nom_convenio>"+"\n";
		xml +="<cod_tipconv>"+cod_tipconv+"</cod_tipconv>"+"\n";
//		xml +="<cod_entidad>"+cod_entidad+"</cod_entidad>"+"\n";
//		xml +="<resp_entidad>"+resp_entidad+"</resp_entidad>"+"\n";
		xml +="<resol_aprob>"+resol_aprob+"</resol_aprob>"+"\n";
		xml +="<obj_espc>"+obj_espc+"</obj_espc>"+"\n";
		xml +="<cod_unid>"+cod_unid+"</cod_unid>"+"\n";
		xml +="<resp_senamhi>"+resp_senamhi+"</resp_senamhi>"+"\n";
		xml +="<fecha_ini>"+fecha_ini+"</fecha_ini>"+"\n";
		xml +="<fecha_fin>"+fecha_fin+"</fecha_fin>"+"\n";
			
	/*entidades*/	
		xml +="<ents>"+"\n";
		$('.c_entconv').each(function(index) {		
			var cod_ent = $(this).val();		
			var resp_ent = $('#resp_entconv_'+cod_ent).val();
				xml +="<ent>"+"\n";
					xml +="<c>"+cod_ent+"</c>"+"\n";
					xml +="<r>"+resp_ent+"</r>"+"\n";
				xml +="</ent>"+"\n";						
		});
		xml +="</ents>"+"\n";	
    /*---------*/
		
	/*OBLIGACIONES*/
	xml +="<os>"+"\n";
	xml +="</os>"+"\n";
	/*	
	xml +="<os>"+"\n";
	$('.nro_oblig').each(function(index) {		
		var obligacion = $(this).attr('d_obligacion');
		var cod_responsable = $(this).attr('c_responsable');
		var cod_oblig = $(this).attr('cod_oblig');
		xml +="<o>"+"\n"; 
			xml +="<oc>"+cod_oblig+"</oc>"+"\n"; //codigo
			xml +="<od>"+obligacion+"</od>"+"\n"; //descripcion
			xml +="<r>"+cod_responsable+"</r>"+"\n"; //responsable
		xml +="</o>"+"\n";
	});
	xml +="</os>"+"\n";
	*/
	
	/*EVALUACIONES*/
	xml +="<evs>"+"\n";
	var cod_perioeval = $( "#cb_perioeval" ).val();
	if(cod_perioeval == '99999') cod_perioeval = '';
	xml +="<cper>"+cod_perioeval+"</cper>";
	xml +="<fs>";
	$('.nro_eval').each(function(index) {		
		var nro = $(this).attr('nro');
		var fecha_ini_eval = trim($( "#txt_inieval_"+nro ).val());
		var fecha_fin_eval = trim($( "#txt_fineval_"+nro ).val());
		var codeval = $(this).attr('codeval');
		if(fecha_ini_eval != '' || fecha_fin_eval != '' ){
			xml +="<f>"+"\n";
				xml +="<ec>"+codeval+"</ec>"+"\n";
				xml +="<en>"+nro+"</en>"+"\n";
				xml +="<fi>"+fecha_ini_eval+"</fi>"+"\n";
				xml +="<ff>"+fecha_fin_eval+"</ff>"+"\n";
			xml +="</f>"+"\n";
		}
			
	});
	xml +="</fs>"+"\n";
	xml +="</evs>"+"\n";
	
	/*Estaciones*/
	xml +="<ess>"+"\n";
	$('.estacion').each(function(index) {		
		var codestacion = $(this).attr('codestacion');
		xml +="<ec>"+codestacion+"</ec>"+"\n";
	});
	xml +="</ess>"+"\n";
	
	xml += '</d>'+"\n";
	
	console.log(xml);
	
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/sisconv/guardarconvenio/", 
	 	data: 	"codconvenio="+cod_convenio+"&xml="+xml,	 	 
		success: function(requestData){
		
		    var codx = requestData.split("<script", 1);
//		    codx = codx.replace("\n","");
		    console.log(codx);
			var f = IsNumeric(codx);
			
			console.log(f);
			if(f){				
				$('#c_convenio').html(requestData);//se escribe la respuesta en el componente indicado
				$('#div_mensaje').html('Guardado exitoso.');
				$( "#tabs" ).tabs( "enable", 4 );
				$.ajax({
				 	dataType: "html",
				 	type: "POST",
				 	url: path + "index.php/sisconv/convenio/", 
				 	data: 	"listardatos=1",	 	 
					success: function(requestData){ 					
						$("#div_reloaddata").html(requestData);
					},		
					error: function(requestData, strError, strTipoError){
						console.log("Error " + strTipoError +": " + strError);
					}
			 	});
				
				//recargar tabla obligaciones
				var oTable_oblig = $('#tbl_obligaciones').dataTable();
				oTable_oblig.fnClearTable();
				cargar_tbl_oblig(requestData);
				
				
			}
			else{
				$('#div_mensaje').html(requestData);
			}	
			//cerrarsubvent();//para cerrar la ventana 
			
			
			
		},		
		error: function(requestData, strError, strTipoError){
			console.log("Error " + strTipoError +": " + strError);
		}
 	});
	
	
}

function cargar_tbl_oblig(codconv){
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/sisconv/obtconvenioobligacion/", 
	 	data: 	"codconv="+codconv,	 	 
		success: function(requestData){ 
		
		var oblig = jQuery.parseJSON(requestData);
		console.log(oblig);
			if(oblig != null || oblig != undefined) {
				for(var i = 0; i<oblig.length ; i ++){
					aniadir_obligacion('',oblig[i][0], oblig[i][1] , oblig[i][2] , oblig[i][3] );
				}
			}
		},		
		error: function(requestData, strError, strTipoError){
			console.log("Error al recargar tabla de obligaciones!");
		}
 	});
}

/*referente documentos de referencia*/
function aniadir_docref(){
	
	var cod_convenio = $( "#c_convenio" ).html();
	
	$.lightbox2(path + "index.php/sisconv/aniadirdocref/?cod_convenio="+cod_convenio, {
        'width'       : 370, 
        'height'      : 100, 
        'autoresize'  : true,
        'modal': false,
        'onClose' : function(){
							   cargarlista_docref(cod_convenio);
							  }
      });
	
}

function cargarlista_docref(cod_convenio){

	
	$.ajax({
	 	dataType: "html",
	 	type: "GET",
	 	url: path + "index.php/sisconv/cargarlistadocref/", 
	 	data: 	"cod_convenio="+cod_convenio,	 	 
		success: function(requestData){ 					
			$('#div_tbl_docref').html(requestData);
		},		
		error: function(requestData, strError, strTipoError){
			$('#div_tbl_docref').html("Error " + strTipoError +": " + strError);
		}
 	});
	
	
}

function mostrar_docref(cod_conv,corr){
	
	var width = $(window).width() - 200;
    var height = $(window).height() - 200;
    
	$.lightbox2(path + "index.php/sisconv/mostrardocref/?cod_conv="+cod_conv+"&corr="+corr+"&width="+width+"&height="+height, {
        'width'       : width, 
        'height'      : height, 
        'autoresize'  : true,
        'modal': false
      });
	
}

function eliminar_docref(cod_conv,corr){ 
	console.log(cod_conv + '->' + corr);
	
	var r = confirm("SEGURO DE ELIMINAR ESTE DOCUMENTO?");
	if (r == true) {
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/sisconv/eliminardocref/", 
		 	data: 	"cod_convenio="+cod_conv+"&corr="+corr,	 	 
			success: function(requestData){ 					
				cargarlista_docref(cod_conv);
			},		
			error: function(requestData, strError, strTipoError){
				console.log("Error " + strTipoError +": " + strError);
			}
	 	});
	}
}

/* fin de documento de referencia */

/*referente imagen de referencia*/
function aniadir_imgref(){
	
	var cod_evalu = $( "#codeval" ).val();
	
	$.lightbox2(path + "index.php/sisconv/aniadirimgref/?cod_evalu="+cod_evalu, {
        'width'       : 370, 
        'height'      : 100, 
        'autoresize'  : true,
        'modal': false,
        'onClose' : function(){
							   cargarlista_imgref(cod_evalu);
							  }
      });
	
}

function cargarlista_imgref(cod_evalu){ 

	
	$.ajax({
	 	dataType: "html",
	 	type: "GET",
	 	url: path + "index.php/sisconv/cargarlistaimgref/", 
	 	data: 	"cod_evalu="+cod_evalu,	 	 
		success: function(requestData){ 					
			$('#div_tbl_imgref').html(requestData);
		},		
		error: function(requestData, strError, strTipoError){
			$('#div_tbl_imgref').html("Error " + strTipoError +": " + strError);
		}
 	});
	
	
}

function mostrar_imgref(cod_evalu,corr){
	
	var width = $(window).width() - 200;
    var height = $(window).height() - 200;
    
	$.lightbox2(path + "index.php/sisconv/mostrarimgref/?cod_evalu="+cod_evalu+"&corr="+corr+"&width="+width+"&height="+height, {
        'width'       : width, 
        'height'      : height, 
        'autoresize'  : true,
        'modal': false
      });
	
}

function eliminar_imgref(cod_evalu,corr){ 
	console.log(cod_evalu + '->' + corr);
	
	var r = confirm("SEGURO DE ELIMINAR ESTE DOCUMENTO?");
	if (r == true) {
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/sisconv/eliminarimgref/", 
		 	data: 	"cod_evalu="+cod_evalu+"&corr="+corr,	 	 
			success: function(requestData){ 					
				cargarlista_imgref(cod_evalu);
			},		
			error: function(requestData, strError, strTipoError){
				console.log("Error " + strTipoError +": " + strError);
			}
	 	});
	}
}

/* fin de imagen de referencia */

function mostrar_perioeval(cod_conv){
	$('#tr_'+cod_conv).toggle();
}

function evaluar_periodoconvenio(codeval){
	$.lightbox(path + "index.php/sisconv/evaluarperiodoconvenio/?codeval="+codeval, {
        'width'       : 950, 
        'height'      : 600, 
        'autoresize'  : true,
        'modal': false
      });
}

function eval_logro_mant(btn){
	
	var index = '';
	var d_logro = '';
	if(btn != undefined){
		index = $(btn).attr('cindex');
		d_logro = $('#d_logro_'+index).html();
	}
			
	$.lightbox2(path + "index.php/sisconv/evallogromant/?index="+index+"&d_logro="+d_logro, {
        'width'       : 400, 
        'height'      : 300, 
        'autoresize'  : true,
        'modal': false
      });
}

function eval_logro_aniadir(tbl_index, d_logro ){
	
	if(tbl_index == undefined) tbl_index = '';
//	if(c_logro == undefined) c_logro = '';
	if(d_logro == undefined) d_logro = trim($('#txa_logroeval').val());
	
var error = '';
	
	if(d_logro == ''){
		error += 'Ingrese Logro.<br>';		
	}
	
	if(error != ''){
		$("#div_mensaje_eval_logro").addClass("ui-state-error");
		$('#div_mensaje_eval_logro').html(error);
	}else{
		$('#div_mensaje_eval_logro').html('');
		$("#div_mensaje_eval_logro").removeClass("ui-state-error");
	
		var oTable = $('#tbl_eval_logros').dataTable();
		
		if(tbl_index != ''){
			var node = oTable.fnGetNodes(tbl_index);
			oTable.fnUpdate(['<span class="nro_logro" cindex="" id=""></span>'
                             ,'<span class="d_logro" id="">'+d_logro+'</span>'
                             ,'<ul><li class="ui-state-default ui-corner-all editar_logro" title="Editar" style="width:20px;float:left" cindex="" onclick="eval_logro_mant(this)"><span class="ui-icon ui-icon-pencil"></span></li>'+
                             '<li class="ui-state-default ui-corner-all ui-state-error borrar_logro" title="Eliminar" style="width:20px;float:left" cindex="" onclick="borrar_logro(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>']
                            ,node);
			
		}else{
			oTable.dataTable().fnAddData(['<span class="nro_logro" id=""></span>'
			                              ,'<span class="d_logro" id="">'+d_logro+'</span>'
			                              ,'<ul><li class="ui-state-default ui-corner-all editar_logro" title="Editar" style="width:20px;float:left" cindex="" onclick="eval_logro_mant(this)"><span class="ui-icon ui-icon-pencil"></span></li>'+
			                              '<li class="ui-state-default ui-corner-all ui-state-error borrar_logro" title="Eliminar" style="width:20px;float:left" cindex="" onclick="borrar_logro(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>']
			                              );
		}
	
		cerrarsubvent2();
	
		actualizar_nro_logros();
	}
	
}


function actualizar_nro_logros(){
		
	var tbl_index = 0; 
	$(".nro_logro").each(function(){
		$(this).attr('id','c_logro_'+tbl_index);	
		$(this).attr('cindex',tbl_index);
		$(this).html(tbl_index + 1);
		tbl_index++;
	});	
	
	tbl_index = 0; 
	$(".d_logro").each(function(){
		$(this).attr('id','d_logro_'+tbl_index);	
		tbl_index++;
	});
	
	tbl_index = 0; 
	$(".editar_logro").each(function(){
		$(this).attr('cindex',tbl_index);	
		tbl_index++;
	});	
		
	tbl_index = 0; 
	$(".borrar_logro").each(function(){
		$(this).attr('cindex',tbl_index);	
		tbl_index++;
	});	
	
}

function borrar_logro(boton){
	var index = $(boton).attr('cindex');
	var oTable = $('#tbl_eval_logros').dataTable();	   
    oTable.fnDeleteRow( index );
    actualizar_nro_logros();
}

//-----

function eval_opinion_mant(btn){
	
	var index = '';
	var d_opinion = '';
	if(btn != undefined){
		index = $(btn).attr('cindex');
		d_opinion = $('#d_opinion_'+index).html();
	}
			
	$.lightbox2(path + "index.php/sisconv/evalopinionmant/?index="+index+"&d_opinion="+d_opinion, {
        'width'       : 400, 
        'height'      : 300, 
        'autoresize'  : true,
        'modal': false
      });
}

function eval_opinion_aniadir(tbl_index, d_opinion ){
	
	if(tbl_index == undefined) tbl_index = '';
//	if(c_logro == undefined) c_logro = '';
	if(d_opinion == undefined) d_opinion = trim($('#txa_opinioneval').val());
	
var error = '';
	
	if(d_opinion == ''){
		error += 'Ingrese Opinion.<br>';		
	}
	
	if(error != ''){
		$("#div_mensaje_eval_opinion").addClass("ui-state-error");
		$('#div_mensaje_eval_opinion').html(error);
	}else{
		$('#div_mensaje_eval_opinion').html('');
		$("#div_mensaje_eval_opinion").removeClass("ui-state-error");
	
		var oTable = $('#tbl_eval_opinion').dataTable();
		
		if(tbl_index != ''){
			var node = oTable.fnGetNodes(tbl_index);
			oTable.fnUpdate(['<span class="nro_opinion" cindex="" id=""></span>'
                             ,'<span class="d_opinion" id="">'+d_opinion+'</span>'
                             ,'<ul><li class="ui-state-default ui-corner-all editar_opinion" title="Editar" style="width:20px;float:left" cindex="" onclick="eval_opinion_mant(this)"><span class="ui-icon ui-icon-pencil"></span></li>'+
                             '<li class="ui-state-default ui-corner-all ui-state-error borrar_opinion" title="Eliminar" style="width:20px;float:left" cindex="" onclick="borrar_opinion(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>']
                            ,node);
			
		}else{
			oTable.dataTable().fnAddData(['<span class="nro_opinion" cindex="" id=""></span>'
			                              ,'<span class="d_opinion" id="">'+d_opinion+'</span>'
			                              ,'<ul><li class="ui-state-default ui-corner-all editar_opinion" title="Editar" style="width:20px;float:left" cindex="" onclick="eval_opinion_mant(this)"><span class="ui-icon ui-icon-pencil"></span></li>'+
			                              '<li class="ui-state-default ui-corner-all ui-state-error borrar_opinion" title="Eliminar" style="width:20px;float:left" cindex="" onclick="borrar_opinion(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>']
			                             );
		}
	
		cerrarsubvent2();
	
		actualizar_nro_opinion();
	}
	
}


function actualizar_nro_opinion(){
		
	var tbl_index = 0; 
	$(".nro_opinion").each(function(){
		$(this).attr('id','c_opinion_'+tbl_index);	
		$(this).attr('cindex',tbl_index);
		$(this).html(tbl_index + 1);
		tbl_index++;
	});	
	
	tbl_index = 0; 
	$(".d_opinion").each(function(){
		$(this).attr('id','d_opinion_'+tbl_index);	
		tbl_index++;
	});
	
	tbl_index = 0; 
	$(".editar_opinion").each(function(){
		$(this).attr('cindex',tbl_index);	
		tbl_index++;
	});	
		
	tbl_index = 0; 
	$(".borrar_opinion").each(function(){
		$(this).attr('cindex',tbl_index);	
		tbl_index++;
	});	
	
}

function borrar_opinion(boton){
	var index = $(boton).attr('cindex');
	var oTable = $('#tbl_eval_opinion').dataTable();	   
    oTable.fnDeleteRow( index );
    actualizar_nro_opinion();
}
//-------

function eval_observacion_mant(btn){
	
	var index = '';
	var d_observacion = '';
	if(btn != undefined){
		index = $(btn).attr('cindex');
		d_observacion = $('#d_observacion_'+index).html();
	}
			
	$.lightbox2(path + "index.php/sisconv/evalobservacionmant/?index="+index+"&d_observacion="+d_observacion, {
        'width'       : 400, 
        'height'      : 300, 
        'autoresize'  : true,
        'modal': false
      });
}

function eval_observacion_aniadir(tbl_index, d_observacion ){
	
	if(tbl_index == undefined) tbl_index = '';
//	if(c_logro == undefined) c_logro = '';
	if(d_observacion == undefined) d_observacion = trim($('#txa_observacioneval').val());
	
var error = '';
	
	if(d_observacion == ''){
		error += 'Ingrese observacion.<br>';		
	}
	
	if(error != ''){
		$("#div_mensaje_eval_observacion").addClass("ui-state-error");
		$('#div_mensaje_eval_observacion').html(error);
	}else{
		$('#div_mensaje_eval_observacion').html('');
		$("#div_mensaje_eval_observacion").removeClass("ui-state-error");
	
		var oTable = $('#tbl_eval_observacion').dataTable();
		
		if(tbl_index != ''){
			var node = oTable.fnGetNodes(tbl_index);
			oTable.fnUpdate(['<span class="nro_observacion" cindex="" id=""></span>'
                             ,'<span class="d_observacion" id="">'+d_observacion+'</span>'
                             ,'<ul><li class="ui-state-default ui-corner-all editar_observacion" title="Editar" style="width:20px;float:left" cindex="" onclick="eval_observacion_mant(this)"><span class="ui-icon ui-icon-pencil"></span></li>'+
                             '<li class="ui-state-default ui-corner-all ui-state-error borrar_observacion" title="Eliminar" style="width:20px;float:left" cindex="" onclick="borrar_observacion(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>']
                            ,node);
			
		}else{
			oTable.dataTable().fnAddData(['<span class="nro_observacion" cindex="" id=""></span>'
			                              ,'<span class="d_observacion" id="">'+d_observacion+'</span>'
			                              ,'<ul><li class="ui-state-default ui-corner-all editar_observacion" title="Editar" style="width:20px;float:left" cindex="" onclick="eval_observacion_mant(this)"><span class="ui-icon ui-icon-pencil"></span></li>'+
			                              '<li class="ui-state-default ui-corner-all ui-state-error borrar_observacion" title="Eliminar" style="width:20px;float:left" cindex="" onclick="borrar_observacion(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>']
			                             );
		}
	
		cerrarsubvent2();
	
		actualizar_nro_observacion();
	}
	
}


function actualizar_nro_observacion(){
		
	var tbl_index = 0; 
	$(".nro_observacion").each(function(){
		$(this).attr('id','c_observacion_'+tbl_index);	
		$(this).attr('cindex',tbl_index);
		$(this).html(tbl_index + 1);
		tbl_index++;
	});	
	
	tbl_index = 0; 
	$(".d_observacion").each(function(){
		$(this).attr('id','d_observacion_'+tbl_index);	
		tbl_index++;
	});
	
	tbl_index = 0; 
	$(".editar_observacion").each(function(){
		$(this).attr('cindex',tbl_index);	
		tbl_index++;
	});	
		
	tbl_index = 0; 
	$(".borrar_observacion").each(function(){
		$(this).attr('cindex',tbl_index);	
		tbl_index++;
	});	
	
}

function borrar_observacion(boton){
	var index = $(boton).attr('cindex');
	var oTable = $('#tbl_eval_observacion').dataTable();	   
    oTable.fnDeleteRow( index );
    actualizar_nro_observacion();
}

//-----

function guardarevalconv(){
	
	var codeval = trim($('#codeval').val());
		
	var xml_oblig_eval ='<oblig_eval>'+"\n";
	$(".nro_oblig_eval").each(function(){
		var codoblig = $(this).attr('codoblig');
		var oblig_avance = $('#oblig_'+codoblig).val();
		var oblig_frenaje = $('#frenaje_oblig_'+codoblig).val();
		var oblig_solucion = $('#solucion_oblig_'+codoblig).val();
		xml_oblig_eval +='<oblig>'+"\n";
		xml_oblig_eval +='<codoblig>'+codoblig+'</codoblig>'+"\n";
		xml_oblig_eval +='<oblig_avance>'+oblig_avance+'</oblig_avance>'+"\n";
		xml_oblig_eval +='<oblig_frenaje>'+oblig_frenaje+'</oblig_frenaje>'+"\n";
		xml_oblig_eval +='<oblig_solucion>'+oblig_solucion+'</oblig_solucion>'+"\n";
		xml_oblig_eval +='</oblig>'+"\n";
	});
	xml_oblig_eval +='</oblig_eval>'+"\n";
	
	var xml_logros ='<logros>'+"\n";
	$(".nro_logro").each(function(){
		var cindex = $(this).attr('cindex'); 
		var c_logro = $(this).html();
		var d_logro = $('#d_logro_'+cindex).html();
		xml_logros +='<logro>'+"\n";
		xml_logros +='<c_logro>'+c_logro+'</c_logro>'+"\n";
		xml_logros +='<d_logro>'+d_logro+'</d_logro>'+"\n";
		xml_logros +='</logro>'+"\n";
	});
	xml_logros +='</logros>'+"\n";
	
	
	var xml_opiniones ='<opiniones>'+"\n";
	$(".nro_opinion").each(function(){
		var cindex = $(this).attr('cindex'); 
		var c_opinion = $(this).html();
		var d_opinion = $('#d_opinion_'+cindex).html();
		xml_opiniones +='<opinion>'+"\n";
		xml_opiniones +='<c_opinion>'+c_opinion+'</c_opinion>'+"\n";
		xml_opiniones +='<d_opinion>'+d_opinion+'</d_opinion>'+"\n";
		xml_opiniones +='</opinion>'+"\n";
	});
	xml_opiniones +='</opiniones>'+"\n";
	
	var xml_observaciones ='<observaciones>'+"\n";
	$(".nro_observacion").each(function(){
		var cindex = $(this).attr('cindex'); 
		var c_observacion = $(this).html();
		var d_observacion = $('#d_observacion_'+cindex).html();
		xml_observaciones +='<observacion>'+"\n";
		xml_observaciones +='<c_observacion>'+c_observacion+'</c_observacion>'+"\n";
		xml_observaciones +='<d_observacion>'+d_observacion+'</d_observacion>'+"\n";
		xml_observaciones +='</observacion>'+"\n";
	});
	xml_observaciones +='</observaciones>'+"\n";
	
	
	console.log(codeval);
	console.log('');
	console.log(xml_oblig_eval);
	console.log('');
	console.log(xml_logros);
	console.log('');
	console.log(xml_opiniones);
	console.log('');
	console.log(xml_observaciones);
	
	console.log("codeval="+codeval+"&xml_oblig_eval="+xml_oblig_eval+"&xml_logros="+xml_logros+"&xml_opiniones="+xml_opiniones+"&xml_observaciones="+xml_observaciones);
	
	$.ajax({
	 	dataType: "html",
	 	type: "post",
	 	url: path + "index.php/sisconv/guardarevalconv/", 
	 	data: 	"codeval="+codeval+"&xml_oblig_eval="+xml_oblig_eval+"&xml_logros="+xml_logros+"&xml_opiniones="+xml_opiniones+"&xml_observaciones="+xml_observaciones,	 	 
		success: function(requestData){ 	
		    var obj = jQuery.parseJSON(requestData);
		    if(obj[0] == '1'){
		    	$('#div_mensaje_eval').addClass('ui-title ui-state-default');
		    }if(obj[0] == '0'){
		    	$('#div_mensaje_eval').addClass('ui-title ui-state-error');
		    }
			$('#div_mensaje_eval').html(obj[1]);
		},		
		error: function(requestData, strError, strTipoError){
			$('#div_mensaje_eval').html("Error " + strTipoError +": " + strError);
		}
 	});
	
}

function listar_entidades(){
		
	$.lightbox2(path + "index.php/sisconv/entidad/?buscar=1", {
        'width'       : 800, 
        'height'      : 300, 
        'autoresize'  : true,
        'modal': false
      });
	
}

function aniadir_entidad(codentidad,entidad){
	var oTable = $('#tbl_entconv').dataTable();
	oTable.dataTable().fnAddData([ '<input class="c_entconv" type="hidden" value="'+codentidad+'">'+entidad
		                             ,'<input type="text" id="resp_entconv_'+codentidad+'" value="" style="width:230px">' 
		                             ,'<ul><li class="ui-state-default ui-corner-all ui-state-hover" ><span class="ui-icon ui-icon-close btn_borraentconv" nro_entconv="" onclick="eliminar_entconv(this);"></span></li></ul>']);
	
	cerrarsubvent2();
	actualizar_index_entconv();
}

function actualizar_index_entconv(){
	tbl_index = 0; 
	$(".btn_borraentconv").each(function(){
		$(this).attr('nro_entconv',tbl_index);	
		tbl_index++;
	});
	
}

function eliminar_entconv(btn){
	var index = $(btn).attr('nro_entconv');
	console.log(index);
	var oTable = $('#tbl_entconv').dataTable();	   
    oTable.fnDeleteRow( index );
    actualizar_index_entconv();
}

function cumplimiento_convenio(c_convenio){
	//console.log(c_tipconv);
	
	$.lightbox(path + "index.php/sisconv/convenioreportcumplimiento/?c_convenio="+c_convenio, {
        'width'       : 950, 
        'height'      : 650, 
        'autoresize'  : true,
        'modal': true
      });
	
}

function nocumplimiento_convenio(c_convenio){
	//console.log(c_tipconv);
	
	$.lightbox(path + "index.php/sisconv/convenioreportnocumplimiento/?c_convenio="+c_convenio, {
        'width'       : 950, 
        'height'      : 650, 
        'autoresize'  : true,
        'modal': true
      });
	
}

function cargar_tblobligacioncumplimiento(codconv,periodo){
	if(codconv == undefined) codconv = $('#c_convenio').html();	
	if(periodo == undefined) periodo = $('#cbperiodo').val();	
	console.log(codconv + '  / ' + periodo);
	$.ajax({
	 	dataType: "html",
	 	type: "post",
	 	url: path + "index.php/sisconv/tblobligacioncumplimiento/", 
	 	data: 	"codconv="+codconv+"&periodo="+periodo,	 	 
		success: function(requestData){
			$('#div_tbl_obligaciones').html(requestData);
		},		
		error: function(requestData, strError, strTipoError){
			$('#div_tbl_obligaciones').html("Error " + strTipoError +": " + strError);
		}
 	});
}

function cargar_tbllogrocumplimiento(codconv,periodo){
	if(codconv == undefined) codconv = $('#c_convenio').html();	
	if(periodo == undefined) periodo = $('#cbperiodo').val();	
	console.log(codconv + '  / ' + periodo);
	$.ajax({
	 	dataType: "html",
	 	type: "post",
	 	url: path + "index.php/sisconv/tbllogrocumplimiento/", 
	 	data: 	"codconv="+codconv+"&periodo="+periodo,	 	 
		success: function(requestData){
			$('#div_tbl_logros').html(requestData);
		},		
		error: function(requestData, strError, strTipoError){
			$('#div_tbl_logros').html("Error " + strTipoError +": " + strError);
		}
 	});
}

function cargar_tblopinioncumplimiento(codconv,periodo){
	if(codconv == undefined) codconv = $('#c_convenio').html();	
	if(periodo == undefined) periodo = $('#cbperiodo').val();	
	console.log(codconv + '  / ' + periodo);
	$.ajax({
	 	dataType: "html",
	 	type: "post",
	 	url: path + "index.php/sisconv/tblopinioncumplimiento/", 
	 	data: 	"codconv="+codconv+"&periodo="+periodo,	 	 
		success: function(requestData){
			$('#div_tbl_opiniones').html(requestData);
		},		
		error: function(requestData, strError, strTipoError){
			$('#div_tbl_opiniones').html("Error " + strTipoError +": " + strError);
		}
 	});
}

function cargar_tblobligacionnocumplimiento(codconv,periodo){
	if(codconv == undefined) codconv = $('#c_convenio').html();	
	if(periodo == undefined) periodo = $('#cbperiodo').val();	
	console.log(codconv + '  / ' + periodo);
	$.ajax({
	 	dataType: "html",
	 	type: "post",
	 	url: path + "index.php/sisconv/tblobligacionnocumplimiento/", 
	 	data: 	"codconv="+codconv+"&periodo="+periodo,	 	 
		success: function(requestData){
			$('#div_tbl_obligaciones').html(requestData);
		},		
		error: function(requestData, strError, strTipoError){
			$('#div_tbl_obligaciones').html("Error " + strTipoError +": " + strError);
		}
 	});
}

function print_cump_oblig(){
	 codconv = $('#c_convenio').html();	
	 periodo = $('#cbperiodo').val();
	window.open(path + "index.php/sisconv/convenioreportcumplimiento?print=1&c_convenio="+codconv+"&periodo="+periodo,'_blank')
}

function print_nocump_oblig(){
	 codconv = $('#c_convenio').html();	
	 periodo = $('#cbperiodo').val();
	window.open(path + "index.php/sisconv/convenioreportnocumplimiento?print=1&c_convenio="+codconv+"&periodo="+periodo,'_blank')
}

