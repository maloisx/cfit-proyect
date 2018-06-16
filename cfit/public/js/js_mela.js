function buscar_asistencias_x_dia(){
	
	var fecha = $('#txt_fecha').val();
//	console.log(fecha);
			
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/mela/buscarasistenciaxdia/", 
		 	data: 	"fecha="+fecha,	 	 
			beforeSend: function(data){ 	 	
				$('#div_tbl_rpta').html("Cargando...");
			},
			success: function(requestData){
				$('#div_tbl_rpta').html(requestData);
			},		
			error: function(requestData, strError, strTipoError){											
				$('#div_tbl_rpta').html("Error " + strTipoError +": " + strError);
			},
			complete: function(requestData, exito){ 
			
			}
		});
		
}

function buscar_asistencias_oga(){
	
	var fecha = $('#txt_fecha').val();
//	console.log(fecha);
			
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/mela/buscarasistenciaoga/", 
		 	data: 	"fecha="+fecha,	 	 
			beforeSend: function(data){ 	 	
				$('#div_tbl_rpta').html("Cargando...");
			},
			success: function(requestData){
				$('#div_tbl_rpta').html(requestData);
			},		
			error: function(requestData, strError, strTipoError){											
				$('#div_tbl_rpta').html("Error " + strTipoError +": " + strError);
			},
			complete: function(requestData, exito){ 
			
			}
		});
		
}

function rangosfechas(){
	$(document).ready(function() {
		var d = new Date();
		var dt =  LPad(d.getDate().toString(),2,'0')+ '-' + LPad((d.getMonth()+1).toString(),2,'0') +'-'+ d.getFullYear();
		
		$( "#fecha_ini" ).val( dt );
		$( "#fecha_fin" ).val( dt );
		
		$( "#fecha_ini" ).datepicker({
            //changeMonth: true,
			maxDate: '0',
            dateFormat : 'dd-mm-yy',
            defaultDate: new Date(),
            onClose: function( selectedDate ) {
                $( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#fecha_fin" ).datepicker({
            //changeMonth: true,
        	//maxDate: '0',
            dateFormat : 'dd-mm-yy',
            onClose: function( selectedDate ) {
                $( "#fecha_ini" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
}


function buscarempleados(){
	var cod_ddrr = $('#cbddrr').val();
		if(cod_ddrr == '99999') cod_ddrr = '';
	var cod_depen = $('#cbdependencia').val();
		if(cod_depen == '99999') cod_depen = '';
	var cod_unid = $('#cbunidad').val();
		if(cod_unid == '99999') cod_unid = '';
		
//	console.log("cod_ddrr="+cod_ddrr+"&cod_depen="+cod_depen+"&cod_unid="+cod_unid);	
		
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/mela/buscarempleados/", 
	 	data: 	"cod_ddrr="+cod_ddrr+"&cod_depen="+cod_depen+"&cod_unid="+cod_unid,	 	 
		beforeSend: function(data){ 	 	
			//$('#cbdependencia').html("Cargando...");
		},
		success: function(requestData){
			$('#cbempleado').html(requestData);
		},		
		error: function(requestData, strError, strTipoError){											
			console.log("Error " + strTipoError +": " + strError);
		},
		complete: function(requestData, exito){ 
		
		}
	});
	
}

function reportediasnolaboradosdata(){
	
	var cod_emp = $('#cbempleado').val();
		if(cod_emp == '99999') cod_emp = '';
	var fecha_ini = $('#fecha_ini').val();
	var fecha_fin = $('#fecha_fin').val();
			
	var cod_ddrr = $('#cbddrr').val();
		if(cod_ddrr == '99999') cod_ddrr = '';
	var cod_depen = $('#cbdependencia').val();
		if(cod_depen == '99999') cod_depen = '';
	var cod_unid = $('#cbunidad').val();
		if(cod_unid == '99999') cod_unid = '';
		
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/mela/reportediasnolaboradosdata/", 
		 	data: 	"cod_emp="+cod_emp+"&fecha_ini="+fecha_ini+"&fecha_fin="+fecha_fin+"&cod_ddrr="+cod_ddrr+"&cod_depen="+cod_depen+"&cod_unid="+cod_unid,	 	 
			beforeSend: function(data){ 	 	
				$('#div_reporte').html("Cargando...");
			},
			success: function(requestData){
				$('#div_reporte').html(requestData);
			},		
			error: function(requestData, strError, strTipoError){											
				$('#div_reporte').html("Error " + strTipoError +": " + strError);
			},
			complete: function(requestData, exito){ 
			
			}
		});
		
}


function buscardependencias(){
	var cod_ddrr = $('#cbddrr').val();
	
	if(cod_ddrr == '99999'){
		$('#cbdependencia').html('<option value="99999">Seleccionar...</option>');	
		$('#cbunidad').html('<option value="99999">Seleccionar...</option>');	
	}else{
		$('#cbunidad').html('<option value="99999">Seleccionar...</option>');
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/mela/buscardependencias/", 
		 	data: 	"cod_ddrr="+cod_ddrr,	 	 
			beforeSend: function(data){ 	 	
				//$('#cbdependencia').html("Cargando...");
			},
			success: function(requestData){
				$('#cbdependencia').html(requestData);
			},		
			error: function(requestData, strError, strTipoError){											
				console.log("Error " + strTipoError +": " + strError);
			},
			complete: function(requestData, exito){ 
			
			}
		});
	}
	
	
}

function buscarunidad(){
	var cod_ddrr = $('#cbddrr').val();
	var cod_depen = $('#cbdependencia').val();
	if(cod_depen == '99999'){
		$('#cbunidad').html('<option value="99999">Seleccionar...</option>');	
	}else{
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/mela/buscarunidad/", 
		 	data: 	"cod_ddrr="+cod_ddrr+"&cod_depen="+cod_depen,	 	 
			beforeSend: function(data){ 	 	
				//$('#cbdependencia').html("Cargando...");
			},
			success: function(requestData){
				$('#cbunidad').html(requestData);
			},		
			error: function(requestData, strError, strTipoError){											
				console.log("Error " + strTipoError +": " + strError);
			},
			complete: function(requestData, exito){ 
			
			}
		});
	}
}

function pdf(){
	
	var cod_emp = $('#cbempleado').val();
		if(cod_emp == '99999') cod_emp = '';
	var fecha_ini = $('#fecha_ini').val();
	var fecha_fin = $('#fecha_fin').val();
			
	var cod_ddrr = $('#cbddrr').val();
		if(cod_ddrr == '99999') cod_ddrr = '';
	var cod_depen = $('#cbdependencia').val();
		if(cod_depen == '99999') cod_depen = '';
	var cod_unid = $('#cbunidad').val();
		if(cod_unid == '99999') cod_unid = '';
	
	window.open(path+"index.php/mela/pdf"+"?"+"cod_emp="+cod_emp+"&fecha_ini="+fecha_ini+"&fecha_fin="+fecha_fin+"&cod_ddrr="+cod_ddrr+"&cod_depen="+cod_depen+"&cod_unid="+cod_unid,"_blank");
	
}

function asistenciaempleadodata(){
	
	var esquema = "SISPER";
	var report = "asistenciaempleado";
	var param = "";
	
	var cod_emp = $('input[name=cod_emp]:checked').val();	
	var fecha_ini = $('#fecha_ini').val();
	var fecha_fin = $('#fecha_fin').val();
	param = "p_cod_emp$"+cod_emp+"|p_fecha_ini$"+fecha_ini+"|p_fecha_fin$"+fecha_fin;
	
	//var url = "http://172.25.0.13:8080/senamhi_report/?s="+esquema+"&r="+report+"&p="+param;
	var url = "http://sgd.senamhi.gob.pe/sis/sisper/reporteasistenciapdf?s="+esquema+"&r="+report+"&p="+param;	
	window.open(url,'_blank');
	
}

function asistenciapracticantes(){
	
	var esquema = "SISPER";
	var report = "asistenciapracticantes";
	var param = "";
	
	var cod_emp = $('input[name=cod_emp]:checked').val();	
	var fecha_ini = $('#fecha_ini').val();
	var fecha_fin = $('#fecha_fin').val();
	param = "p_cod_emp$"+cod_emp+"|p_fecha_ini$"+fecha_ini+"|p_fecha_fin$"+fecha_fin;
	
	//var url = "http://172.25.0.13:8080/senamhi_report/?s="+esquema+"&r="+report+"&p="+param;
	var url = "http://sgd.senamhi.gob.pe/sis/sisper/reporteasistenciapdf?s="+esquema+"&r="+report+"&p="+param;
	window.open(url,'_blank');
	
}
function marcarhorasfueradelabor(){
	
	var cod_emp = $('input[name=cod_emp]:checked').val();	
	var fecha_ini = $('#fecha_ini').val();
	var fecha_fin = $('#fecha_fin').val();
	
	if(cod_emp == undefined){
		alert("SELECCIONAR UN EMPLEADO!");		
	}else{	
		$.lightbox(path + "index.php/mela/marcacionesfueradehorario/?cod_emp="+cod_emp+"&fecha_ini="+fecha_ini+"&fecha_fin="+fecha_fin, {
	        'width'       : 500, 
	        'height'      : 480, 
	        'autoresize'  : true,
	        'modal': true
	      });
	}	
}

function sumarhorasextras(){
	var sum = 0;
	$("input[name=chb_horasextras]:checked").each(function (){
    	sum = sum + parseInt($(this).val());    	
	});
	
	var hora_extras = LPad(String(Math.floor(sum/60)),2,'0') + ":" + LPad(String((sum - (Math.floor(sum/60)*60) )),2,'0') ;
	
	$('#c_sumhorasextras').html(hora_extras);
}

function guardarhorasextrastomadas(){
	var cod_emp = $('#txt_cod_emp').val();
	var nro_papeleta = $('#txt_nropapeleta').val();
	var fecha = "";
	var hora_ent = "";
	var hora_sal = "";
	var min_extras = "";
	
	if(nro_papeleta != ''){	
		$("input[name=chb_horasextras]:checked").each(function (){
			fecha = $(this).attr("fecha");
			hora_ent = $(this).attr("hora_ent");
			hora_sal = $(this).attr("hora_sal");
			min_extras = $(this).val(); 
			
	//		console.log(cod_emp + "\t" + nro_papeleta  + "\t" + fecha   + "\t" + hora_ent  + "\t" + hora_sal  + "\t" + min_extras);
			$.ajax({
			 	dataType: "html",
			 	type: "POST",
			 	url: path + "index.php/mela/guardarhorasextrastomadas/", 
			 	data: 	"codemp="+cod_emp+"&nropapeleta="+nro_papeleta+"&fecha="+fecha+"&hora_ent="+hora_ent+"&hora_sal="+hora_sal+"&min_ext="+min_extras,	 	 
				success: function(requestData){ 
					console.log(requestData);
					//$('#c_sector').html(requestData);//se escribe la respuesta en el componente indicado				
				},		
				error: function(requestData, strError, strTipoError){
					console.log("Error " + strTipoError +": " + strError);
				}
		 	});
		});
		cerrarsubvent();//para cerrar la ventana 
	}else{
		$('#div_error_horasextrastomadas').html("INGRESAR NRO DE PAPELETA");
	}
}

function eliminarmarcacionesfueradehorario(codemp,papeleta){
	
	papeleta = papeleta.substring(7, papeleta.length -1).trim();
//	console.log(codemp + "--->" +papeleta);
	
	var rpta = confirm("SEGURO DE ELIMINAR LA PAPELETA NRO. "+papeleta+"?");
	if(rpta){
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/mela/eliminarmarcacionesfueradehorario/", 
		 	data: 	"codemp="+codemp+"&papeleta="+papeleta,	 	 
			success: function(requestData){ 
				alert(requestData);
				cerrarsubvent();
				//$('#c_sector').html(requestData);//se escribe la respuesta en el componente indicado				
			},		
			error: function(requestData, strError, strTipoError){
				console.log("Error " + strTipoError +": " + strError);
			}
	 	});
	}
	
}

