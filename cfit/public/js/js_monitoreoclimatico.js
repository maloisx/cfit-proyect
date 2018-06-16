	
var map, manager;
var centerLatitude = -9.49282, centerLongitude = -75.3689, startZoom = 6;
var markersArray = [];
var arrayestaciones;
var popup = new google.maps.InfoWindow();

var optionsmaps = {
        zoom: startZoom
        , center: new google.maps.LatLng(centerLatitude, centerLongitude)
//        , mapTypeId: google.maps.MapTypeId.SATELLITE
        , mapTypeId: google.maps.MapTypeId.HYBRID
 
        , backgroundColor: '#ffffff'
        , noClear: true
        , disableDefaultUI: true
        , keyboardShortcuts: false
        , disableDoubleClickZoom: true
        , draggable: true
//        , scrollwheel: true
        , draggableCursor: 'move'
        , draggingCursor: 'move'
 
        , mapTypeControl: true
        , mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
            , position: google.maps.ControlPosition.TOP_RIGHT
            , mapTypeIds: [
                google.maps.MapTypeId.ROADMAP
                ,google.maps.MapTypeId.SATELLITE
                ,google.maps.MapTypeId.HYBRID
                ,google.maps.MapTypeId.TERRAIN
            ]
        }
//        , navigationControl: true
        , streetViewControl: false
        , navigationControlOptions: {
            position: google.maps.ControlPosition.TOP_LEFT
//            , style: google.maps.NavigationControlStyle.ANDROID
            , style: google.maps.NavigationControlStyle.ZOOM_PAN
        }	       
//        , scaleControl: true
        , scaleControlOptions: {
            position: google.maps.ControlPosition.BOTTOM_LEFT
            , style: google.maps.ScaleControlStyle.DEFAULT
        }
    };


function rangosfechas_monitoreo(){
	$(document).ready(function() {
		var d = new Date();
		var dt = d.getFullYear() + '-' + LPad((d.getMonth()+1).toString(),2,'0')+ '-'+ LPad(d.getDate().toString(),2,'0');
		
		$( "#fecha_ini" ).val( dt );
		$( "#fecha_fin" ).val( dt );
		
		$( "#fecha_ini" ).datepicker({
            //changeMonth: true,
			maxDate: '0',
            dateFormat : 'yy-mm-dd',
            defaultDate: new Date(),
            onClose: function( selectedDate ) {
                $( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
                pintar_mapas_criterio_fechas();
            }
        });
        $( "#fecha_fin" ).datepicker({
            //changeMonth: true,
        	maxDate: '0',
            dateFormat : 'yy-mm-dd',
            onClose: function( selectedDate ) {
                $( "#fecha_ini" ).datepicker( "option", "maxDate", selectedDate );
                pintar_mapas_criterio_fechas();
            }
        });
    });
}

function rangosfechas(){
	$(document).ready(function() {
		var d = new Date();
		var dt = d.getFullYear() + '-' + LPad((d.getMonth()+1).toString(),2,'0')+ '-'+ LPad(d.getDate().toString(),2,'0');
		
		$( "#fecha_ini" ).val( dt );
		$( "#fecha_fin" ).val( dt );
		
		$( "#fecha_ini" ).datepicker({
            //changeMonth: true,
			maxDate: '0',
            dateFormat : 'yy-mm-dd',
            defaultDate: new Date(),
            onClose: function( selectedDate ) {
                $( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#fecha_fin" ).datepicker({
            //changeMonth: true,
        	maxDate: '0',
            dateFormat : 'yy-mm-dd',
            onClose: function( selectedDate ) {
                $( "#fecha_ini" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
}

function pintarmapa(divmapa){
	 
	    map = new google.maps.Map(document.getElementById(divmapa), optionsmaps);
	 
//	    map.setZoom(9);
//	    var zoomLevel = map.getZoom();
//	 
//	    map.setCenter(new google.maps.LatLng(18.17, -66.3));
//	    var centerOfMap = map.getCenter();
//	 
//	    map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
//	    var mapTypeIdOfMap = map.getMapTypeId();
//	 
//	    alert(zoomLevel + ' -- ' + centerOfMap + ' -- ' + mapTypeIdOfMap);
};

function pintararbolmonitoreo(jsonchecked,accion){
	
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/monitoreoclimatico/pintararbolmonitoreo/", 
	 	data: 	"jsonchecked="+jsonchecked+"&accion="+accion,	 	 
		beforeSend: function(data){ 	 	
			$('#tree-div').html("Cargando...");
		},
		success: function(requestData){
			$('#tree-div').html(requestData);
		},		
		error: function(requestData, strError, strTipoError){											
			$('#tree-div').html("Error " + strTipoError +": " + strError);
		},
		complete: function(requestData, exito){ 
		
		}
	});
	
}

function pintarestaciones_monitoreo(jsonestaciones,recepciondata){
		
	removermarkers(markersArray);
	
	arrayestaciones = jQuery.parseJSON(jsonestaciones);	
	
	for(var i=0;i<arrayestaciones.length;i++){
		if(arrayestaciones[i]['tipo'] == 'E'){
//			console.log(arrayestaciones[i]['estacion'] + ' => ' + arrayestaciones[i]['lat'] + ' / ' + arrayestaciones[i]['lng'] );	
			aniadirmarker(arrayestaciones[i]['codestacion'] ,arrayestaciones[i]['estacion'] , arrayestaciones[i]['lat'] , arrayestaciones[i]['lng'], arrayestaciones[i]['alerta'],recepciondata,'');			 
		}
	}
	
	centrarmapa();
}

function pintartablaestaciones_monitoreo(jsonestaciones){
	
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/monitoreoclimatico/monitoreodatostabla/", 
	 	data: 	"json="+jsonestaciones,	 	 
		beforeSend: function(data){ 	 	
			$('#div_datostotalesmonitoreo').html("Cargando...");
		},
		success: function(requestData){
			$('#div_datostotalesmonitoreo').html(requestData);
		},		
		error: function(requestData, strError, strTipoError){											
			$('#div_datostotalesmonitoreo').html("Error " + strTipoError +": " + strError);
		},
		complete: function(requestData, exito){ 
		
		}
	});
	
}

function aniadirmarker(codestacion,estacion,lat,lng,alerta,accion,pathicon,css){

	if ( pathicon == '' || pathicon == undefined ){
		pathicon = path +'img/localizacion_' + ((trim(alerta) == '')?'verde':'rojo') + '.png';
	}if ( css == '' || css == undefined ){
		css = 'labelsgooglemaps';
	}
	
//	var marker = new google.maps.Marker({
//	        position: new google.maps.LatLng(lat, lng)
//	        , map: map
//	        , title: estacion
//	        , icon: 'http://gmaps-samples.googlecode.com/svn/trunk/markers/red/marker10.png'
////	        , icon: path +'img/localizacion_' + ((trim(alerta) == '')?'verde':'rojo') + '.png'
//	    });		
	
	
	var marker = new MarkerWithLabel({
	       position: new google.maps.LatLng(lat, lng),
	       draggable: false,
	       map: map,
	       icon: pathicon,
//	       icon: path +'css/s.gif',
	       labelContent: estacion,
//	       labelAnchor: new google.maps.Point(100, 0),
	       labelClass: css,//"labelsgooglemaps", // the CSS class for the label
	       labelStyle: {opacity: 0.75},
	       labelInBackground: false
	     });
	
	
	if(accion == '1'){
		    google.maps.event.addListener(marker, 'click', function(){
			        var note = '<div id="contentInfoWindow' + codestacion + '" class="ui-corner-all" style="width: 550px; height: 310px; border: 1px solid #000;"></div>';
			    	popup.setContent(note);
			        popup.open(map, this);
			    	
			    	var note = '';
			    	$.ajax({
					 	dataType: "html",
					 	type: "GET",
					 	url: path + "index.php/monitoreoclimatico/monitoreodatosestacion/", 
					 	data: 	"codestacion="+codestacion,	 	 
						beforeSend: function(data){ 	 	
							$('#contentInfoWindow' + codestacion).html("Cargando...");
						},
						success: function(requestData){
							$('#contentInfoWindow' + codestacion).html(requestData);
						},		
						error: function(requestData, strError, strTipoError){											
							$('#contentInfoWindow' + codestacion).html("Error " + strTipoError +": " + strError);
						},
						complete: function(requestData, exito){ 
						
						}
					});    	
		       
			    });
	}
    
	markersArray.push(marker);
}

function removermarkers(markersArray){
	 for (var i = 0; i < markersArray.length; i++) {
		  markersArray[i].setMap(null);
     }
	  markersArray.length=0;
}

function centrarmapa(){
			
	if(markersArray.length>0){
		var limits = new google.maps.LatLngBounds();
		for(var marker=0;marker<markersArray.length;marker++){
			limits.extend(markersArray[marker].getPosition());
		}
		map.fitBounds(limits);	
	}else{
		map.setOptions({zoom: startZoom, center: new google.maps.LatLng(centerLatitude, centerLongitude)});
	}
	
}

function obtdatosgraficomonitoreo(jsonestaciones){
	
	var fechaini = $( "#fecha_ini" ).val();
	var fechafin = $( "#fecha_fin" ).val();

	var jsoncriterio = '[';
	
	$('.checkbox_criteriosmonitoreo').each(function(index) {			
		if($(this).attr('checked'))
			jsoncriterio += '"' + $(this).attr("codcriterio") + '",';	    
	});
	if(jsoncriterio.length > 1)
		jsoncriterio = jsoncriterio.substring(0,jsoncriterio.length -1);
	
	jsoncriterio += ']';
	
	arrayestaciones = jQuery.parseJSON(jsonestaciones);	
	
	var jsonestacion = '[';	
	for(var i=0;i<arrayestaciones.length;i++){
		if(arrayestaciones[i]['tipo'] == 'E'){	
			jsonestacion += '"' + arrayestaciones[i]['codestacion'] + '",';				 
		}
	}	
	if(jsonestacion.length > 1)
		jsonestacion = jsonestacion.substring(0,jsonestacion.length -1);
	jsonestacion += ']';
	
	
	console.log('1=>'+fechaini + '/' + fechafin);
	console.log('2=>'+jsoncriterio);
	console.log('3=>'+jsonestacion);
	
}

function mantenimientoestacion(codestacion){
	$.lightbox(path + "index.php/monitoreoclimatico/mantenimientoestacion/?codestacion="+codestacion, {
        'width'       : 400, 
        'height'      : 370, 
        'autoresize'  : true,
        'modal': false
      });
}

function guardarestacion(){
	alert('click en guardar!!');
}

function monitoreodatosestacion_detalle(codestacion,estacion,dirreg,fecha,horaprevista,horallegada){
	$.lightbox(path + "index.php/monitoreoclimatico/monitoreodatosestaciondetalle/?codestacion="+codestacion+"&estacion="+estacion+"&dirreg="+dirreg+"&fecha="+fecha+"&horaprevista="+horaprevista+"&horallegada="+horallegada, {
        'width'       : 600, 
        'height'      : 450, 
        'autoresize'  : true,
        'modal': false
      });
}

var array_colorestemperatura = null;
function csscolorestemperatura(){	
	var jsoncolor = obttemperaturacolor();
	array_colorestemperatura = jQuery.parseJSON(jsoncolor);
	console.log(array_colorestemperatura);	
	cad_stylecolores = '';
	if(array_colorestemperatura != null){
		
		cad_stylecolores += '.labelsgooglemaps_rangoTM_0_0{ ';
		cad_stylecolores += 'color: black; ';
		cad_stylecolores += 'background-color: white; ';
		cad_stylecolores += 'font-family: "Lucida Grande", "Arial", sans-serif; ';
		cad_stylecolores += 'font-size: 12px; ';
		cad_stylecolores += 'font-weight: bold; ';
		cad_stylecolores += 'text-align: center; ';
		cad_stylecolores += 'padding: 3px; ';
		cad_stylecolores += 'border: 2px solid black; ';
		cad_stylecolores += 'white-space: nowrap;	 ';
		cad_stylecolores += 'width: 25px; height: 25px; ';
		cad_stylecolores += 'background: #ffffff;  ';
		cad_stylecolores += '-moz-border-radius: 70px; -webkit-border-radius: 70px; border-radius: 70px;';
		cad_stylecolores += '} ';
		
		cad_stylecolores += "\n\n";
		
	
		for(var i=0;i<array_colorestemperatura.length;i++){
			
			var min = array_colorestemperatura[i][0].toString();
			var max = array_colorestemperatura[i][1].toString();
			
			min = replaceAll( min, '-', '_' );
			min = replaceAll( min, '.', '' );
			max = replaceAll( max, '-', '_' );
			max = replaceAll( max, '.', '' );
			
			cad_stylecolores += '.labelsgooglemaps_rangoTM_'+min+'_'+max+'{ ';
			cad_stylecolores += 'color: black; ';
			cad_stylecolores += 'background-color: white; ';
			cad_stylecolores += 'font-family: "Lucida Grande", "Arial", sans-serif; ';
			cad_stylecolores += 'font-size: 12px; ';
			cad_stylecolores += 'font-weight: bold; ';
			cad_stylecolores += 'text-align: center; ';
			cad_stylecolores += 'padding: 3px; ';
			cad_stylecolores += 'border: 2px solid black; ';
			cad_stylecolores += 'white-space: nowrap;	 ';
			cad_stylecolores += 'width: 25px; height: 25px; ';
			cad_stylecolores += 'background: #'+array_colorestemperatura[i][2]+';  ';
			cad_stylecolores += '-moz-border-radius: 70px; -webkit-border-radius: 70px; border-radius: 70px;';
			cad_stylecolores += '} ';
			
			cad_stylecolores += "\n\n";
		}
		console.log("<style type='text/css'>"+cad_stylecolores+"</style>");
		$("head").append("<style type='text/css'>"+cad_stylecolores+"</style>");
	}
}

function mapas_criterio_fechas_pintarmapa(fecha){
	
	if(fecha == undefined){
		fecha = trim($("#div_titulofecha").html());
	}

	var mostrarnombres = $('#nom_estacion').is(':checked');
	var mostrarvalor = $('#valor_estacion').is(':checked');		
			
	var predatos = 0;
	if(markersArray.length > 0){
		predatos = 1;
		var zoomLevel = map.getZoom();
	    var centerOfMap = map.getCenter();	    
	}
	
	$("#div_titulofecha").html(fecha);		
	var json = $('#datafechamap_'+fecha).html();
	var array_temp = jQuery.parseJSON(json);

	if(markersArray.length > 0){
		removermarkers(markersArray);
	}
	
	var codcriterio  = $('#cbcriterio').val();
	var caract_crit = obtcaracparam('','',codcriterio);
	var array_crit = jQuery.parseJSON(caract_crit);
	console.log('------------->>>>>'+array_crit[0][0]);
		
	for(var i=0;i<array_temp.length;i++){
		var label = '';
		var icon = '';
		var css = '';			
			if(mostrarnombres == true){
				label += array_temp[i][1]+'<br>';
				css = 'labelsgooglemaps';
			}if( mostrarvalor == true){
				label += ((array_temp[i][5]=='')?'S/D':array_temp[i][5]);
				css = 'labelsgooglemaps_circulo';
			}if(mostrarnombres == false || mostrarvalor == false){
				icon = path +'css/images/s.gif';
			}if(mostrarnombres == false && mostrarvalor == false){
				icon = path +'css/images/s.gif';
				css = 'labelsgooglemaps_circulo';
			}if(mostrarnombres == true && mostrarvalor == true){
				label = array_temp[i][1]+'<br>('+((array_temp[i][5]=='')?'S/D':array_temp[i][5])+')';
				icon = '';
				css = 'labelsgooglemaps';
			}
			
			if(mostrarnombres == false && array_crit[0][0] == 'TM'){				
				css = 'labelsgooglemaps_circulo';
				var temp_max = '';
				var temp_min = '';
				for(var j=0;j<array_colorestemperatura.length;j++){					
					var valor = parseFloat((array_temp[i][5]=='')?'-999':array_temp[i][5]);					
					if( valor >= array_colorestemperatura[j][0] && valor <= array_colorestemperatura[j][1]){
						 temp_min = array_colorestemperatura[j][0];
						 temp_max = array_colorestemperatura[j][1];
						 break;
					}
				}
				temp_min = replaceAll( temp_min, '-', '_' );
				temp_min = replaceAll( temp_min, '.', '' );
			    temp_max = replaceAll( temp_max, '-', '_' );
			    temp_max = replaceAll( temp_max, '.', '' );
				
				if(temp_max != '' && temp_min != ''){
					css = 'labelsgooglemaps_rangoTM_'+temp_min+'_'+temp_max;
				}else{
					css = 'labelsgooglemaps_rangoTM_0_0';
				}
				console.log("->"+css+"\n");
			}
			
		
//			if( solovalor == 0){
//				label = array_temp[i][1]+'<br>('+((array_temp[i][5]=='')?'S/D':array_temp[i][5])+')';
//				icon = '';
//			}
//			else{				
//				label = ((array_temp[i][5]=='')?'S/D':array_temp[i][5]);
//				icon = path +'css/images/s.gif';
//			}
			
			
		aniadirmarker('',label,array_temp[i][2],array_temp[i][3],'','',icon,css);
	}
		if(predatos == 0){
			var limits = new google.maps.LatLngBounds();
			for(var marker=0;marker<markersArray.length;marker++){
				limits.extend(markersArray[marker].getPosition());
			}
			map.fitBounds(limits);
		}
		else{
			map.setZoom(zoomLevel);		 
		    map.setCenter(centerOfMap);
		}
	
}

function obttemperaturacolor(){
	var v_rpta = null;
	$.ajaxSetup({async: false});
	$.post(path + "index.php/monitoreoclimatico/obttemperaturacolor/"
          ,function(data){v_rpta = data;}
		  ,'data'
		  );
	$.ajaxSetup({async: true});
    return v_rpta;
}

function obtcaracparam(v_codgrupo , v_codparam , v_codcriterio){
	var v_rpta = null;
	$.ajaxSetup({async: false});
	$.post(path + "index.php/monitoreoclimatico/obtcaracparam/"
          ,{codgrupo:v_codgrupo ,codparam :v_codparam, codcriterio :v_codcriterio } 
		  ,function(data){v_rpta = data;}
		  ,'data'
		  );
	$.ajaxSetup({async: true});
    return v_rpta;
}

function mapas_criterio_fechas(jsonestaciones){
	
	array = jQuery.parseJSON(jsonestaciones);	
	var n = 0;
	var xmlestaciones = '<d>';
	for(var i=0;i<array.length;i++){
		if(array[i]['tipo'] == 'E'){
			//xmlestaciones += '<r codestacion="'+array[i]['codestacion']+'"></r>';
			xmlestaciones += '<r><codestacion>'+array[i]['codestacion']+'</codestacion></r>';
		}
	}	
	xmlestaciones += '</d>';
	console.log(xmlestaciones);	
	var codcriterio  = $('#cbcriterio').val();
	//	console.log(codcriterio);	
	var f1  = $('#fecha_ini').val();
	var f2  = $('#fecha_fin').val();
	var diffdias = diferencias_dias(f1,f2);
//	console.log(f1 + '-->' + f2);
	
	if(codcriterio != '99999' && xmlestaciones != '<d></d>'){

		$('#c_mapa').html('<table border="0" cellpadding="0" cellspacing="0"><tr><td style="width: 110px;height: 585px"><div class="ui-corner-all" align="center" style="overflow-y:scroll;border:1px solid;width: 110px;height: 585px;float:left" id="fechas_mapa"></div></td><td style="width: 910px;height: 585px;"><div align="center" class="ui-corner-all ui-state-default" id="div_titulofecha">titulo fecha</div><div id="div_graf_mapa" style="border:1px solid;width: 910px;height: 565px" >para dibujar el mapa</div></td></tr></table>');
				
		//---------------------------------------------------------------------------------
//		//puntos en el mapa!
		

		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/monitoreoclimatico/estacionescriteriovalor/", 
		 	data: 	"xmlestaciones="+xmlestaciones+"&codcriterio="+codcriterio+"&fechaini="+f1+"&fechafin="+f2,	 	 
			beforeSend: function(data){ 	 					
			},
			success: function(requestData){
				var data = jQuery.parseJSON(requestData);	
//				var json = JSON.stringify(data);	
				
				var array_datafechas = new Array();
				for(var i=0;i<data.length;i++){
					array_datafechas[data[i][4]] = new Array();
				}
				for(var i=0;i<data.length;i++){
					array_datafechas[data[i][4]].push(data[i]);
				}
				
				$('#fechas_mapa').html('');
				for(var i=0;i<=diffdias;i++){
					var d = sumar_dias(f1,i);
					$('#fechas_mapa').append('<button id="btnmap_'+d+'" fecha="'+d+'" onclick="mapas_criterio_fechas_pintarmapa(\''+d+'\')">'+d+'</button><div class="datafechas" style="display: none;" id="datafechamap_'+d+'">'+JSON.stringify(array_datafechas[d])+'</div><br>');
				}		
				$("button").button();
				pintarmapa('div_graf_mapa');
				markersArray = [];
				mapas_criterio_fechas_pintarmapa(f1);				
			},		
			error: function(requestData, strError, strTipoError){					
				$('#c_mapa').html("Error " + strTipoError +": " + strError);
			},
			complete: function(requestData, exito){ 
			
			}
		}); 	
		
		
		//---------------------------------------------------------------------------------
					
		//---------------------------------------------------------------------------------
//		//puntos en el mapa!
//		console.log("xmlestaciones="+xmlestaciones+"&codcriterio="+codcriterio+"&fechaini="+f1+"&fechafin="+f2);
//		$.ajax({
//		 	dataType: "html",
//		 	type: "POST",
//		 	url: path + "index.php/monitoreoclimatico/estacionescriteriovalor/", 
//		 	data: 	"xmlestaciones="+xmlestaciones+"&codcriterio="+codcriterio+"&fechaini="+f1+"&fechafin="+f2,	 	 
//			beforeSend: function(data){ 	 	
//				
//			},
//			success: function(requestData){
//				var data = jQuery.parseJSON(requestData);	
////				var array_markers = new Array();
//			
//				for(var i=0;i<data.length;i++){
//					var marker = new MarkerWithLabel({
//					       position: new google.maps.LatLng(data[i][2], data[i][3]),
//					       draggable: false,
//					       map: array_maps['map_'+data[i][4]],
//					       icon: path +'img/localizacion_verde.png',
//					       labelContent: data[i][1]+'<br>('+((data[i][5]=='')?'S/D':data[i][5])+')',
////					       labelAnchor: new google.maps.Point(100, 0),
//					       labelClass: "labelsgooglemaps", 
//					       labelStyle: {opacity: 0.75},
//					       labelInBackground: false
//					     });
////					array_markers.push(marker);
//				}
//
////				var limits = new google.maps.LatLngBounds();
////				for(var marker=0;marker<array_markers.length;marker++){
////					limits.extend(array_markers[marker].getPosition());
////				}
////
////				for(var j=0;j<=diffdias;j++){
////					var d = sumar_dias(f1,j);		
////					console.log(array_maps['map_'+d]);
////					array_maps['map_'+d].fitBounds(limits);
////				}
////				console.log('---------------------------------------');
//			},		
//			error: function(requestData, strError, strTipoError){					
//				$('#c_mapa').html("Error " + strTipoError +": " + strError);
//			},
//			complete: function(requestData, exito){ 
//			
//			}
//		}); 	
//		//---------------------------------------------------------------------------------
//		//grafico
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/monitoreoclimatico/estacionescriteriografico/", 
		 	data: 	"xmlestaciones="+xmlestaciones+"&codcriterio="+codcriterio+"&fechaini="+f1+"&fechafin="+f2,	 	 
			beforeSend: function(data){ 	 	
				
			},
			success: function(requestData){
				$('#div_grafico').html(requestData);
			},		
			error: function(requestData, strError, strTipoError){					
				$('#div_grafico').html("Error " + strTipoError +": " + strError);
			},
			complete: function(requestData, exito){ 
			
			}
		}); 	
//		//---------------------------------------------------------------------------------
//		//tabladatos
//		$.ajax({
//		 	dataType: "html",
//		 	type: "POST",
//		 	url: path + "index.php/monitoreoclimatico/estacionescriteriotabladatos/", 
//		 	data: 	"xmlestaciones="+xmlestaciones+"&codcriterio="+codcriterio+"&fechaini="+f1+"&fechafin="+f2,	 	 
//			beforeSend: function(data){ 	 	
//				
//			},
//			success: function(requestData){
//				$('#div_tabladatosestaciones').html('<table width="1100px" border="1" cellpadding="0" cellspacing="0" id="c_tabladatosestaciones"></table>');
//				$('#div_tabladatosestaciones').append(requestData);
//			},		
//			error: function(requestData, strError, strTipoError){					
//				$('#div_tabladatosestaciones').append("Error " + strTipoError +": " + strError);
//			},
//			complete: function(requestData, exito){ 
//			
//			}
//		}); 	
		//---------------------------------------------------------------------------------
	}
}

function estacionescriteriotabladatoseditor_(codestacion,estacion,fecha,codcriterio){
	
	var valoractual = $('#val_'+fecha+'_'+codcriterio+'_'+codestacion).html();
    var criterio_text = $("#cbcriterio option[value='"+codcriterio+"']").text();
	$.lightbox(path + "index.php/monitoreoclimatico/estacionescriteriotabladatoseditor1/?codestacion="+codestacion+"&estacion="+estacion+"&fecha="+fecha+"&criterio_text="+criterio_text+"&codcriterio="+codcriterio+"&valoractual="+valoractual, {
        'width'       : 260, 
        'height'      : 270, 
        'autoresize'  : true,
        'modal': false
      });
}

function estacionescriteriotabladatoseditor(tipoventana,codestacion,fecha,codgrupo,codparam,valoract, celda){
	
	var nrofila =  $(celda).attr('nrofila');
	var nrocol = $(celda).attr('nrocol');
			
	$.lightbox2(path + "index.php/monitoreoclimatico/estacionescriteriotabladatoseditor/?codestacion="+codestacion+"&fecha="+fecha+"&codgrupo="+codgrupo+"&codparam="+codparam+"&valoract="+valoract+"&tipoventana="+tipoventana+"&nrofila="+nrofila+"&nrocol="+nrocol, {
        'width'       : 350, 
        'height'      : 300, 
        'autoresize'  : true,
        'modal': false
      });
}

//function guardarestacioncriteriovalor(codestacion,fecha,codcriterio){
//	var nuevovalor = $("#txtnuevovalor").val();
//	var actualvalor = $("#txtvaloractual").html();
//	var comentario = $("#txacomentario").val();
//	//alert(codestacion+'->'+fecha+'->'+criterio+'->'+actualvalor+'->'+nuevovalor+'->'+comentario);	
//	var error = '';
//	
//	if(nuevovalor == ''){
//		error += 'Ingresar nuevo valor para el criterio seleccionado.<br><br>';
//	}if(comentario == ''){
//		error += 'Ingresar comentario por el motivo de actualizaci&oacute;n..<br>';
//	}
//	
//	if(error != ''){
//		$("#div_mensaje").addClass("ui-state-error");
//		$('#div_mensaje').html(error);
//	}else{
//		$('#div_mensaje').html('');
//		$("#div_mensaje").removeClass("ui-state-error");
//		$.ajax({
//		 	dataType: "html",
//		 	type: "POST",
//		 	url: path + "index.php/monitoreoclimatico/guardarestacioncriteriovalor/", 
//		 	data: 	"codestacion="+codestacion+"&fecha="+fecha+"&codcriterio="+codcriterio+"&nuevovalor="+nuevovalor+"&actualvalor="+actualvalor+"&comentario="+comentario,	 	 
//			beforeSend: function(data){ 	 	
//				$('#div_mensaje').html('Cargando...');
//			},
//			success: function(requestData){
//				var obj = jQuery.parseJSON(requestData);					
//				$('#div_mensaje').html(obj[1]);
//				if(obj[0] == '1'){
//					$("#btn_guardar").button( "disable" );
//					$("#div_mensaje").addClass("ui-state-highlight");
//					pintar_mapas_criterio_fechas();
//				}
//			},		
//			error: function(requestData, strError, strTipoError){					
//				$('#div_mensaje').append("Error " + strTipoError +": " + strError);
//			},
//			complete: function(requestData, exito){ 
//			
//			}
//		});	
//	}
//	
//}

function guardarestacioncriteriovalor(nrofila, nrocol , tipoventana){
	
	/*
	 * tipoventana
	 * val : 1 -> ventana listaestaciones - modo modficacion
	 * val : 2 -> ventana modiciacion de monitorio de datos , graficos
	 * */
	
	var codestacion = $("#codestacion").val();
	var fecha  = $("#txtfecha").html();
	var codgrupo = $("#hd_codgrupo").val();
	var codparam = $("#hd_codparam").val();
	var nuevovalor = $("#v_nuevovalor").val();
	var actualvalor = $("#hd_codvaloractual").val();
	var comentario = $("#txacomentario").val();
	
	var error = '';
	
	if(nuevovalor == '' || nuevovalor == '99999'){
		error += 'Ingresar nuevo valor para el criterio seleccionado.<br>';
	}if(trim(comentario) == ''){
		error += 'Ingresar comentario por el motivo de actualizaci&oacute;n..<br>';
	}
	
	if(error != ''){
		$("#div_mensaje_editor").addClass("ui-state-error");
		$('#div_mensaje_editor').html(error);
	}else{
		$('#div_mensaje_editor').html('');
		$("#div_mensaje_editor").removeClass("ui-state-error");
		
//		$('#div_mensaje').html(codestacion+'->'+fecha+'->'+codgrupo+'->'+codparam+'->'+actualvalor+'->'+nuevovalor+'->'+comentario);	
		
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/monitoreoclimatico/guardarestacioncriteriovalor/", 
		 	data: 	"codestacion="+codestacion+"&fecha="+fecha+"&codgrupo="+codgrupo+"&codparam="+codparam+"&nuevovalor="+nuevovalor+"&actualvalor="+actualvalor+"&comentario="+comentario,	 	 
			beforeSend: function(data){ 	 	
				//$('#div_mensaje').html('Cargando...');
			},
			success: function(requestData){
				var obj = jQuery.parseJSON(requestData);					
				$('#div_mensaje_editor').html(obj[1]);
				if(obj[0] == '1'){
					$("#btn_guardar").button( "disable" );
					$("#div_mensaje_editor").addClass("ui-state-highlight");
					
					if(tipoventana == '1'){
//						tablaestacioncriteriovalor_pintar();
						var oTable = $('#c_tbl_est_crit_val').dataTable();
						var node = oTable.fnGetNodes(nrofila);
						oTable.fnUpdate(nuevovalor,node,nrocol);
						
					}if(tipoventana == '2'){
						pintar_mapas_criterio_fechas();
					}					
				}
			},		
			error: function(requestData, strError, strTipoError){					
				$('#div_mensaje').append("Error " + strTipoError +": " + strError);
			},
			complete: function(requestData, exito){ 
			
			}
		});			
	}	
}

function estacionescomentarios(codestacion){
	
	$.lightbox2(path + "index.php/monitoreoclimatico/estacionescomentarios/?codestacion="+codestacion, {
        'width'       : 750, 
        'height'      : 450, 
        'autoresize'  : true,
        'modal': false
      });
}


function tablaestacioncriteriovalor_ventana0(codestacion){
	
	var width = $(window).width();
    var height = $(window).height();
	
	console.log( width +' x ' + height );
	$.lightbox(path + "index.php/monitoreoclimatico/tablaestacioncriteriovalorventana/?codestacion="+codestacion, {
        'width'       : width - 100, //1500, 
        'height'      : height - 100 , //800, 
        'autoresize'  : true,
        'modal': false
      });
}

function tablaestacioncriteriovalor_pintar0(codestacion){
	
//	estacion = '<d><r codestacion="'+codestacion+'"></r></d>';
	fechaini = $('#fecha_ini').val();
	fechafin = $('#fecha_fin').val();
	
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/monitoreoclimatico/tablaestacioncriteriovalor/", 
	 	data: 	"codestacion="+codestacion+"&codcriterio=&fechaini="+fechaini+"&fechafin="+fechafin,	 	 
		beforeSend: function(data){ 	 	
			$('#c_tablarpta').html('Cargando...');
		},
		success: function(requestData){					
			$('#c_tablarpta').html(requestData);
		},		
		error: function(requestData, strError, strTipoError){					
			$('#c_tablarpta').append("Error " + strTipoError +": " + strError);
		},
		complete: function(requestData, exito){ 
		
		}
	});	
}

//-----------------------------------------------------------------

function tablaestacioncriteriovalor_ventana(coddirreg,codestacion,mod){
	
	var width = $(window).width();
    var height = $(window).height();
	$.lightbox(path + "index.php/monitoreoclimatico/tablaestacioncriteriovalorventana/?coddirreg="+coddirreg+"&codestacion="+codestacion+"&height="+height+"&mod="+mod, {
        'width'       : width - 100, //1500, 
        'height'      : height - 100 , //800, 
        'autoresize'  : true,
        'modal': false
      });
	
}

function cb_seleccionarestacion(){	
	var coddirreg = $('#c_dirreg').val();
	console.log(coddirreg);
	$.ajax({
	 	dataType: "html",
	 	type: "POST",
	 	url: path + "index.php/monitoreoclimatico/cbseleccionarestacion/", 
	 	data: 	"coddirreg="+coddirreg,	 	 
		beforeSend: function(data){ 	 	
			$('#c_estacion').html('<option>Cargando...</option>');
		},
		success: function(requestData){					
			$('#c_estacion').html(requestData);
		},		
		error: function(requestData, strError, strTipoError){					
			$('#c_estacion').append("Error " + strTipoError +": " + strError);
		},
		complete: function(requestData, exito){ 
		
		}
	});	
}

function tablaestacioncriteriovalor_pintar(){
	
	var error = '';
	
	var codestacion = $('#c_estacion').val();
		if(codestacion == '99999'){codestacion = '';} 
	var coddirreg = $('#c_dirreg').val();
		if(coddirreg == '99999'){error += 'Seleccionar Direccion Regional.';} 
	var codhora = $('#c_hora').val();
		if(codhora == '99999'){codhora = '';} 
	
	var fechaini = $('#fecha_ini').val();
	var fechafin = $('#fecha_fin').val();
	var height = $('#hd_height').val();
	var mod = $('#hd_mod').val();
	
	if(error != ''){
		$("#div_mensaje").addClass("ui-state-error");
		$('#div_mensaje').html(error);
	}else{
		$('#div_mensaje').html('');
		$("#div_mensaje").removeClass("ui-state-error");
		console.log("codestacion="+codestacion+"&coddirreg="+coddirreg+"&fechaini="+fechaini+"&fechafin="+fechafin+"&height="+height+"&mod="+mod+"&codhora="+codhora);
		$.ajax({
		 	dataType: "html",
		 	type: "POST",
		 	url: path + "index.php/monitoreoclimatico/tablaestacioncriteriovalor/", 
		 	data: 	"codestacion="+codestacion+"&coddirreg="+coddirreg+"&fechaini="+fechaini+"&fechafin="+fechafin+"&height="+height+"&mod="+mod+"&codhora="+codhora,	 	 
			beforeSend: function(data){ 	 	
				//$('#div_mensaje').html('Cargando...');
			},
			success: function(requestData){					
				$('#div_mensaje').html(requestData);
			},		
			error: function(requestData, strError, strTipoError){					
				$('#div_mensaje').html("Error " + strTipoError +": " + strError);
			},
			complete: function(requestData, exito){ 
			
			}
		});	
		
	}
}

function calcular_cindex_borrardatos(){
	
	var tbl_index = 0; 
	$(".btn_borrardatos").each(function(){
		$(this).attr('cindex',tbl_index);
		tbl_index++;
	});	
	
}

function borrardatosestacionhorasinop(codestacion,estacion,fecha,hora,btn){
		
	console.log(codestacion+'->'+estacion+'->'+fecha+'->'+hora);
	var r=confirm("Desea eliminar los datos de la estaci&oacute;n "+estacion + ' del dia '+fecha+' ' + hora + '?');
	  if (r==true)
	  {
		  $.ajax({
			 	dataType: "html",
			 	type: "POST",
			 	url: path + "index.php/monitoreoclimatico/borrardatosestacionhorasinop/", 
			 	data: 	"codestacion="+codestacion+"&fecha="+fecha+"&hora="+hora,	 	 
				beforeSend: function(data){ 	 	
					////////$('#div_mensaje').html('Cargando...');
				},
				success: function(requestData){					
					if(requestData == '1'){
						//tablaestacioncriteriovalor_pintar();
						var cindex = $(btn).attr('cindex');
						var oTable = $('#c_tbl_est_crit_val').dataTable();	   
					    oTable.fnDeleteRow( index );
					    calcular_nrofilas_tbl();
					    calcular_cindex_combinardatos();
					    calcular_cindex_borrardatos();
					}else{
						alert('ERROR EN ELIMINACION!')
					}
				},		
				error: function(requestData, strError, strTipoError){					
					console.log("Error " + strTipoError +": " + strError);
				},
				complete: function(requestData, exito){ 
				
				}
			});	
	  }
	
}

function buscartabladatoshidro(codestacion, fecha, hora){
	console.log(codestacion +'->'+ fecha +'->'+ hora);
	$( "#tabs_hidro" ).tabs( { active: 0 });
}

function calcular_nrofilas_tbl(){
	
	var array_tipreg = new Array();
	
	$(".tbl_reg_fila").each(function(){
		var c = $(this).attr('tip_reg');
		console.log(c);
		array_tipreg.push(c);
		array_tipreg = array_unique(array_tipreg);		
//		tbl_index++;
	});	
	
	for(var i = 0 ; i<array_tipreg.length;i++){
		var tbl_index = 0; 
		$("."+array_tipreg[i]).each(function(){
			$(this).attr('nrofila',tbl_index);
			tbl_index++;
		});	
	}
	
	
}

function calcular_cindex_combinardatos(){
	
	var tbl_index = 0; 
	$(".check_combinardatos").each(function(){
		$(this).attr('cindex',tbl_index);
		tbl_index++;
	});	
	
}

function calcular_ids_combinardatos(){
	var tbl_index = 0; 
	$(".check_combinardatos").each(function(){
		$(this).attr('id','ch_conv_'+tbl_index);
		tbl_index++;
	});	
}

function activarbotoncombinacion(){
	var ch = 0;
	var cnt = 0;
	$('.check_combinardatos').each(function(index) {			
		if($(this).attr('checked')){
			ch = 1;
			cnt++;
		}
	});
	
	if(ch==1 && cnt > 1){
		$('#btn_combinardatos').show();
	}else{
		$('#btn_combinardatos').hide();
	}
}

function combinardatoshidrometeorologicos(){
//	var xml = '<d>';
//	$('.check_combinardatos').each(function(index) {			
//		if($(this).attr('checked'))
//			xml += '<r><codestacion>'+$(this).attr("codestacion")+'</codestacion><fecha>'+$(this).attr("fecha")+'</fecha><hora>'+$(this).attr("hora")+'</hora></r> ';    
//	});
//	xml += '</d>';
	
	var json = '[';
	$('.check_combinardatos').each(function(index) {			
		if($(this).attr('checked')){
//			json += '{"codestacion":"'+$(this).attr("codestacion")+'", "fecha":  "'+$(this).attr("fecha")+'", "hora":"'+$(this).attr("hora")+'"},';
			json += '["'+$(this).attr("codestacion")+'","'+$(this).attr("estacion")+'","'+$(this).attr("fecha")+'","'+$(this).attr("hora")+'","'+$(this).attr("horasinoptica")+'","'+$(this).attr("id")+'"],';
		}
	});
	json = json.substring(0,json.length -1);
	json += ']';
	
	console.log(json);
	
//	var width = $(window).width();
//    var height = $(window).height();
	
	console.log( width +' x ' + height );
	$.lightbox2(path + "index.php/monitoreoclimatico/ventanacombinardatoshidrometeorologicos/?json="+json, {
        'width'       : 500, //1500, 
        'height'      : 650 , //800, 
        'autoresize'  : true,
        'modal': false
      });
}

function guardarcombinardatoshidrometeorologicos(json_datosconv){
	
	console.log(json_datosconv);
	var datosconv = jQuery.parseJSON(json_datosconv);
	console.log(datosconv);
	
	var codestacion = $("#editor_codestacion").html();
	var fecha = $("#editor_fecha").html();
	var horacorrecta = $("#cb_editor_horareg").val();
	
	var a_del_reg = new Array();
	
	var xmlhoras = '<horas>';
		xmlhoras += '<hcorrec>'+horacorrecta+'</hcorrec>'; //hora correcta
		xmlhoras += '<hcambio>';
			for(var i=0;i<datosconv.length;i++){				
				if(datosconv[i][3] != horacorrecta){
					xmlhoras += '<h>'+datosconv[i][3]+'</h>'; //hora a eliminar
					a_del_reg.push(datosconv[i][5]);
				}
			}
		xmlhoras += '</hcambio>';
	xmlhoras += '</horas>';
	
	var xmldatos = '<d>';
	$('.cb_editor_datos').each(function(index) {
		
		var id_cb=$(this).attr('id');
				
		var grupo = $('#'+id_cb+' option:selected').attr('grupo');
		var corr = $('#'+id_cb+' option:selected').attr('corr');
		var valor = $('#'+id_cb+' option:selected').html();
		
		xmldatos += '<r>';
			xmldatos += '<grupo>'+grupo+'</grupo>';
			xmldatos += '<corr>'+corr+'</corr>';
			xmldatos += '<valor>'+valor+'</valor>';
		xmldatos += '</r>';
		
	});
	xmldatos += '</d>';
	
	console.log(codestacion);
	console.log(fecha);
	console.log(xmlhoras);
	console.log(a_del_reg);
	console.log(xmldatos);
	
	$.ajax({
 	dataType: "html",
 	type: "POST",
 	url: path + "index.php/monitoreoclimatico/guardarcombinardatoshidrometeorologicos/", 
 	data: 	"codestacion="+codestacion+"&fecha="+fecha+"&xmlhoras="+xmlhoras+"&xmldatos="+xmldatos,	 	 
	beforeSend: function(data){ 	 	
//		$('#div_mensaje_editor').html('Cargando...');
	},
	success: function(requestData){
		var obj = jQuery.parseJSON(requestData);					
		$('#div_mensaje_editor_int').html(obj[1]);
		if(obj[0] == '1'){
//			$("#btn_guardar").button( "disable" );
			$("#div_mensaje_editor_int").addClass("ui-state-highlight");
				
				$('.cb_editor_datos').each(function(index) {
					
					id_cb = $(this).attr('id');
					
					console.log(id_cb);					
					
					grupo = $('#'+id_cb+' option:selected').attr('grupo');
					corr = $('#'+id_cb+' option:selected').attr('corr');
					var tipo = $('#'+id_cb+' option:selected').attr('tipo');
					var param = $('#'+id_cb+' option:selected').attr('param');
					var codestacion = $('#'+id_cb+' option:selected').attr('codestacion');
					var estacion = $('#'+id_cb+' option:selected').attr('estacion');
					var ddrr = $('#'+id_cb+' option:selected').attr('ddrr');
					fecha = $('#'+id_cb+' option:selected').attr('fecha');
					var codhora = $('#'+id_cb+' option:selected').attr('codhora');
					valor = $('#'+id_cb+' option:selected').html();
					
					console.log("\t" +grupo + "\n\t" + corr + "\n\t" + tipo + "\n\t" + param + "\n\t" + codestacion  + "\n\t" + estacion + "\n\t" + ddrr + "\n\t" + fecha + "\n\t" + codhora + "\n\t" + valor );
					
					var id=fecha+"_"+codhora+"_"+horacorrecta.replace(":","")+"_"+tipo+"_"+replaceAll(param,' ','_')+"_"+replaceAll(estacion,' ','_')+"_"+ replaceAll(ddrr,' ','_');
					console.log(id);	
					$('#'+id).html(valor);			
					
				});
				
				var oTable = $('#c_tbl_est_crit_val').dataTable();	 
				for(var i=0;i<a_del_reg.length;i++){
					 var ch_cindex = $( '#' + a_del_reg[i] ).attr('cindex');
					 oTable.fnDeleteRow( ch_cindex );
					 calcular_nrofilas_tbl();
					 calcular_cindex_combinardatos();
					 calcular_cindex_borrardatos();
				}
				
				$('.check_combinardatos').each(function(index) {			
					$(this).attr('checked','');
				});
				
				$('#btn_combinardatos').hide();
				
	
		}else{
			$("#div_mensaje_editor_int").addClass("ui-state-error");
		}
	},		
	error: function(requestData, strError, strTipoError){					
		$('#div_mensaje_editor_int').html("Error " + strTipoError +": " + strError);
		$("#div_mensaje_editor_int").addClass("ui-state-error");
	},
	complete: function(requestData, exito){ 
	
	}
});
	
	
	
}
