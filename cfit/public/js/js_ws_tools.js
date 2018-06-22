
function ws_contenido_combo(cb_id_html, data, id_seleccionado) {
    /*llenado de combo dando por hecho q la data solo tiene dos columnas 1 = id , 2 = desc*/
    //cb_id_html = "cb_prueba";
    //id_seleccionado = "";
    cont_combo = "";
    for (var i = 0; i < data.length; i++) {
        var item = data[i];
        ind = 0;
        id = null;
        val = null;
        if(typeof item === 'object'){
            $.each(item, function (index_item, value_item) {
                if (ind == 0) {
                    id = value_item;
                    ind++;
                } else if (ind == 1) {
                    val = value_item;
                }
            });
        }else{
            id = item;
            val = item;
        }
        //console.log(id + " // " + val);
        if (id_seleccionado == id) {
            cont_combo += "<option value='" + id + "' selected='selected' >" + val + "</option>";
        } else {
            cont_combo += "<option value='" + id + "'>" + val + "</option>";
        }
    }
    //console.log(cont_combo);
    $('#' + cb_id_html).html(cont_combo);
//    $('#' + cb_id_html).material_select();
    $('#' + cb_id_html).selectpicker('refresh');
}




function ws_datatable(id_div_tbl, data, tbl_cab, opciones) {

    if(opciones == undefined) opciones = {};

    var opciones_default = {
        responsive: true
        , bFilter: true
        , bLengthChange: false
        , bInfo: false
        , bPaginate: false
        //, dom: "Blfrtip"
        , dom: '<"row"<"col-xs-6"B><"col-xs-6"f>><"row"<"col-xs-12 "p>>rt<"bottom"><"clear">'
        , buttons: [{extend: 'excel', text: 'Exportar a Excel', className: 'btn btn-info btn-sm'}]
    };

    var tbl_data = [];
    for (var i = 0; i < data.length; i++) {
        var item = data[i];
        var tbl_row = [];
        $.each(item, function (index_item, value_item) {
            tbl_row.push(value_item);
        });
        tbl_data.push(tbl_row);
    }

    var tbl_n = parseInt(Math.random() * 99999 + 1);
//    var html_tbl = "<table border='1' class='table table-striped table-bordered dt-responsive' id='tbl_dt_" + tbl_n + "'></table>";
    
    var tbl_responsive = "";
    var b_responsibe = true;
        if(opciones.responsive != undefined){
            b_responsibe = opciones.responsive;
        }else{
            b_responsibe = opciones_default.responsive;
        }
        
        if(b_responsibe == true){
            tbl_responsive = "dt-responsive";
        }
             

    var html_tbl = "<table border='1' class='table table-striped table-bordered "+ tbl_responsive + "' style='width:100%;' id='tbl_dt_" + id_div_tbl + "'></table>";

    $('#' + id_div_tbl).html(html_tbl);
        
       var tbl = $('#tbl_dt_' + id_div_tbl).dataTable({
        "bFilter": (opciones.bFilter != undefined)?opciones.bFilter : opciones_default.bFilter,
        "bLengthChange": (opciones.bLengthChange != undefined)?opciones.bLengthChange : opciones_default.bLengthChange,
        "bInfo": (opciones.bInfo != undefined)?opciones.bInfo : opciones_default.bInfo,
        "bPaginate": (opciones.bPaginate != undefined)?opciones.bPaginate : opciones_default.bPaginate,
        "bScrollCollapse": true,
        //"sScrollY": '93%', 
        "aoColumns": tbl_cab,
        "aaData": tbl_data,
        "fixedColumns": true,
        "dom": (opciones.dom != undefined)?opciones.dom : opciones_default.dom,
        "buttons": (opciones.buttons != undefined)?opciones.buttons : opciones_default.buttons,
        "language": {'url': '/cfit/public/datatables/Spanish.json'}
    });
    
    tbl.$('tr').hover(function () {
        $(this).addClass('highlighted');
    }, function () {
        tbl.$('tr.highlighted').removeClass('highlighted');
    });

    return tbl;
}