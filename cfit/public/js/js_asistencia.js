var tbl_cab_asistencia_tbl_alumnos_clase = [{'sTitle': 'ID'}, {'sTitle': 'DESC'}, {'sTitle': '-'}];	
var opciones_tbl_asistencia_tbl_alumnos_clase = {
            responsive: false
            , bLengthChange: false
            , bInfo: false
            , bFilter : false
            //, bPaginate: true
            , buttons: []
            //, buttons: [{extend: 'excel', text: 'Exportar a Excel', className: 'btn btn-info btn-sm'},{extend: 'csv', text: 'Exportar a CSV', className: 'btn btn-info btn-sm'}]
        };

function charge_list_boostrap_select_datatable(){
		
    var id_cb_ini = $('#cb_alumnos_reg_clase').val();
    
    //console.log(tbl_datatable); 
    var t_op = '';
    if(id_cb_ini){
    	var datos = [];
	    for(var i = 0 ; i<id_cb_ini.length ; i++){
	        var texto = $('#cb_alumnos_reg_clase option[value="'+id_cb_ini[i]+'"]').text();
	        //console.log( id_cb_ini[i] + '--->' + t );
	        var row = [id_cb_ini[i] , texto ,'' ];
	        datos.push(row);
	    }
	    
	    var tbl = ws_datatable("asistencia_tbl_alumnos_clase", datos , tbl_cab_asistencia_tbl_alumnos_clase , opciones_tbl_asistencia_tbl_alumnos_clase);
    
    }
    else{
    	ws_datatable("asistencia_tbl_alumnos_clase", [] , tbl_cab_asistencia_tbl_alumnos_clase , opciones_tbl_asistencia_tbl_alumnos_clase);
    }         
}

function asistencia_index(){
	
	ws_datos = ws('sp_calendario_clases' , ['1'] );	
	//console.log(ws_datos);
	
	ws_datos_clientes = ws('sp_cliente' , [''] );	
//	console.log(ws_datos_clientes);
	
	var v_eventos = [];
	
	for(var i = 0 ; i < ws_datos.length ; i ++){
		var ev = {
				id: ws_datos[i].cod_clase ,
	            title: ws_datos[i].nom_disciplina ,
	            fecha: ws_datos[i].fecha ,
	            start: ws_datos[i].fecha + 'T' + ws_datos[i].hora_ini,	
	            end: ws_datos[i].fecha + 'T' + ws_datos[i].hora_fin,
	            //url: '',
	            color: ws_datos[i].color,
	            personal: ws_datos[i].nom + ' ' + ws_datos[i].appat + ' ' + ws_datos[i].apmat ,
	            sala: ws_datos[i].nom_sala ,
	            aforo: ws_datos[i].aforo	            
		};
		v_eventos.push(ev);
	}
	
	//console.log(v_eventos);
	
	
    var tbl = ws_datatable("asistencia_tbl_alumnos_clase", [] , tbl_cab_asistencia_tbl_alumnos_clase , opciones_tbl_asistencia_tbl_alumnos_clase);
	
    $('#cb_alumnos_reg_clase').change(function(event){ charge_list_boostrap_select_datatable(); });
    
    
    $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay,listMonth'
        },
        eventClick: function(calEvent, jsEvent, view) {    
        	
        	ws_datatable("asistencia_tbl_alumnos_clase", [] , tbl_cab_asistencia_tbl_alumnos_clase , opciones_tbl_asistencia_tbl_alumnos_clase);
        	
        	$("#hd_id_clase").html(calEvent.id);
        	$("#lb_disciplina").html("Registro de Alumnos a Clases de " + calEvent.title);
        	$("#lb_instructor").html(calEvent.personal);
        	$("#lb_sala").html(calEvent.sala);
        	$("#lb_aforo").html(calEvent.aforo);
        	
        	ws_contenido_combo('cb_alumnos_reg_clase', ws_datos_clientes, '');
        	
        	$("#modal_clientes_clase_registro").modal();
        	
//        	console.log('id: ' + calEvent.id);
//            console.log('Event: ' + calEvent.title);
//            console.log('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
//            console.log('View: ' + view.name);
//            console.log('personal: ' + calEvent.personal);
//            console.log('fecha: ' + calEvent.fecha);
//            console.log('sala: ' + calEvent.sala);
//            console.log('aforo: ' + calEvent.aforo);
            
          },
        //defaultDate: '2018-03-12',
        defaultView : 'agendaWeek',
        contentHeight: 600,
        locale: 'es',
        buttonIcons: false, // show the prev/next text
        weekNumbers: true,
        navLinks: true, // can click day/week names to navigate views
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        events: v_eventos
      });
}
