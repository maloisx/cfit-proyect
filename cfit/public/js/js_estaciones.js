var map_siap,map_oracle, manager;
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

function retraso_pintarmapas(){	 
	map_siap = new google.maps.Map(document.getElementById('div_map_siap'), optionsmaps);
	map_oracle = new google.maps.Map(document.getElementById('div_map_oracle'), optionsmaps);
};


var map ;
function pintarestaciones_retraso(tip_map,jsonestaciones){
//	console.log(jsonestaciones);
	
	
	if(tip_map == 'siap'){
		map = map_siap;
	}if(tip_map == 'oracle'){
		map = map_oracle;
	}
	
//	removermarkers(markersArray);	
	arrayestaciones = jQuery.parseJSON(jsonestaciones);	
	console.log(arrayestaciones);
	for(var i=0;i<arrayestaciones.length;i++){
		var ret_min = arrayestaciones[i]['ret_min'];
		var icon = path +'img/localizacion_verde.png';
		if(ret_min<=120){
			icon = path +'img/localizacion_verde.png';			
		}else if(ret_min>120 && ret_min<=180){
			icon = path +'img/localizacion_amarillo.png';			
		}else if(ret_min>180 && ret_min<=2880){
			icon = path +'img/localizacion_naranja.png';			
		}else if(ret_min>2880){
			icon = path +'img/localizacion_rojo.png';			
		}
		
		aniadirmarker(map,arrayestaciones[i]['codestacion'] ,arrayestaciones[i]['estacion'] + '( retraso de '+ arrayestaciones[i]['ret'] +')' , arrayestaciones[i]['lat'] , arrayestaciones[i]['lng'], icon);		 
	}	
	centrarmapa(map);
}

function aniadirmarker(map,codestacion,estacion,lat,lng,pathicon,css){

	if ( pathicon == '' || pathicon == undefined ){
		pathicon = path +'img/localizacion_verde.png';
	}if ( css == '' || css == undefined ){
		css = 'labelsgooglemaps';
	}
	
	var marker = new MarkerWithLabel({
	       position: new google.maps.LatLng(lat, lng),
	       draggable: false,
	       map: map,
	       icon: pathicon,
	       //labelContent: estacion,
	       //labelClass: css,
	       title: estacion ,
	       labelStyle: {opacity: 0.75},
	       labelInBackground: false
	     });	
	

	    google.maps.event.addListener(marker, 'click', function(){
	        var iframe = '<div class="ui-corner-all" style="width: 550px; height: 310px; border: 1px solid #000;">';
	            iframe += '<iframe height="100%" width="100%" src="http://www.sutronwin.com/dcpmon/dcpout.jsp?select_option=dcp_text&dcp_text='+codestacion+'&date_range=1"></iframe>';
	            iframe += '</div>';
	    	popup.setContent(iframe);
	        popup.open(map, this);
	    });
    
	markersArray.push(marker);
}

function removermarkers(markersArray){
	 for (var i = 0; i < markersArray.length; i++) {
		  markersArray[i].setMap(null);
     }
	  markersArray.length=0;
}

function centrarmapa(map){
			
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