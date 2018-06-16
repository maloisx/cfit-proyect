<?php

/**
 * MelaController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';
//require_once "fpdf/fpdf.php";
require_once "fpdf/class.multicelltag.php";
require_once "fpdf/header_footer.inc";

class MelaController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	
	

	public function init() {
		$this->view->util()->registerScriptJSController($this->getRequest());
	}
	
	public function indexAction() {
		echo 'melaaaaaaaaa';
	}
	
	public function reporteasistenciasAction() {
		$func = new Libreria_Pintar();
		$fn[] = array('$( "#txt_fecha" ).datepicker({dateFormat:"dd-mm-yy",maxDate: "0"});');
		
		$evt[] = array("btn_buscar","click","buscar_asistencias_x_dia();");
		
		echo $func->EjecutarFuncion($fn);
		echo $func->PintarEvento($evt);
	}
	
	public function reporteasistenciasogaAction() {
		/*reproet asistencia solo de OGA*/
		$func = new Libreria_Pintar();
		$fn[] = array('$( "#txt_fecha" ).datepicker({dateFormat:"dd-mm-yy",maxDate: "0"});');
				
		$evt[] = array("btn_buscar","click","buscar_asistencias_oga();");
		
		echo $func->EjecutarFuncion($fn);
		echo $func->PintarEvento($evt);
	}
	
	
	public function buscarasistenciaxdiaAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$fecha = $this->_request->getPost ( 'fecha' );	
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_asistencias.sp_obt_datos_asistenciaxdia';
			$psparam[] = array(':p_fecha',$fecha);
			$datos = $cn->ejec_store_oracle_sisper($ns,$psparam);
			
			echo '<table id="tbl_rpta" border="1" cellpadding="0" cellspacing="0"></table>';

			$datatable_opciones[] = array('bFilter','true');
			$datatable_opciones[] = array('bLengthChange','false');
			$datatable_opciones[] = array('bInfo','true');
			$datatable_opciones[] = array('bPaginate','true');		
			$datatable_opciones[] = array('sPaginationType','"full_numbers"');
			$datatable_opciones[] = array('iDisplayLength',count($datos));
			$datatable_opciones[] = array('bJQueryUI','true');
			$datatable_opciones[] = array('bScrollCollapse','true');
			$datatable_opciones[] = array('aaData',json_encode($datos));
			$datatable_opciones[] = array('aoColumns',json_encode(array(array('sTitle'=>'cod empleado','sWidth'=>'100px')			                                                                            
			                                                                            ,array('sTitle'=>'Empleado','sWidth'=>'3 00px')
			                                                                            //,array('sTitle'=>'DR','sWidth'=>'150px')
			                                                                            //,array('sTitle'=>'DEPEN','sWidth'=>'150px')
			                                                                            //,array('sTitle'=>'UNID','sWidth'=>'150px')
			                                                                            ,array('sTitle'=>'Fecha','sWidth'=>'100px')
			                                                                            ,array('sTitle'=>'Hora','sWidth'=>'50px')
//			                                                                            ,array('sTitle'=>'Tipo','sWidth'=>'100px')
			                                                                            )
			                                                                        ));
//			$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){ if($("td:eq(9)", nRow).html() == "Tipo" ){$("td:eq(9)", nRow).addClass( "ui-state-highlight" );} }');
			$datatable_opciones[] = array('sDom','\'<"H"Tfr>t<"F"ip>\'');
			$datatable_opciones[] = array('oTableTools','{"aButtons": [{"sExtends": "collection","sButtonText": "Guardar","aButtons":[ "xls"]}]}');	                                                                           
			echo $this->view->util()->datatable("tbl_rpta",$datatable_opciones);			
		}
	}
	
	
	public function buscarasistenciaogaAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$fecha = $this->_request->getPost ( 'fecha' );	
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_asistencias.sp_obt_datos_asistencia_oga';
			$psparam[] = array(':p_fecha',$fecha);
			$datos = $cn->ejec_store_oracle_sisper($ns,$psparam);
			
			echo '<table id="tbl_rpta" border="1" cellpadding="0" cellspacing="0"></table>';

			$datatable_opciones[] = array('bFilter','true');
			$datatable_opciones[] = array('bLengthChange','false');
			$datatable_opciones[] = array('bInfo','true');
			$datatable_opciones[] = array('bPaginate','true');		
			$datatable_opciones[] = array('sPaginationType','"full_numbers"');
			$datatable_opciones[] = array('iDisplayLength',count($datos));
			$datatable_opciones[] = array('bJQueryUI','true');
			$datatable_opciones[] = array('bScrollCollapse','true');
			$datatable_opciones[] = array('aaData',json_encode($datos));
			$datatable_opciones[] = array('aoColumns',json_encode(array(array('sTitle'=>'Unidad','sWidth'=>'100px')			                                                                            
			                                                                            ,array('sTitle'=>'Empleado','sWidth'=>'300px')
			                                                                            //,array('sTitle'=>'Fecha','sWidth'=>'100px')
			                                                                            ,array('sTitle'=>'Entrada','sWidth'=>'50px')
			                                                                            ,array('sTitle'=>'Salida','sWidth'=>'50px')
			                                                                            ,array('sTitle'=>'Tardanza','sWidth'=>'50px')
			                                                                            ,array('sTitle'=>'Falta','sWidth'=>'50px')
			                                                                            ,array('sTitle'=>'Min Tarde','sWidth'=>'50px')
			                                                                            )
			                                                                        ));
//			$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){ if($("td:eq(9)", nRow).html() == "Tipo" ){$("td:eq(9)", nRow).addClass( "ui-state-highlight" );} }');
			$datatable_opciones[] = array('sDom','\'<"H"Tfr>t<"F"ip>\'');
			$datatable_opciones[] = array('oTableTools','{"aButtons": [{"sExtends": "collection","sButtonText": "Guardar","aButtons":[ "xls"]}]}');	                                                                           
			echo $this->view->util()->datatable("tbl_rpta",$datatable_opciones);			
		}
	}
	
	public function buscarempleadoAction() {
		//$this->_helper->viewRenderer->setNoRender ();		
		$this->_helper->layout->disableLayout ();
		$url = $this->view->util()->getPath();	

		$dni = $this->_request->getPost( 'txtdni');
		if($dni != ''){
			echo "<script>document.getElementById('txtdni').focus();</script>";
			$cn = new Model_DataAdapter();
			$arraydatos[] = array(':dni',$dni);
			$datos = $cn->ejec_store_oracle_sisper(' pkg_asistencias.sp_obt_datos_emp',$arraydatos);	
			
			if(count($datos)==0)
				echo  'NO SE ENCONTRO EMPLEADO CON EL DNI: '.$dni;
			else{
				//echo $datos[0][1];
				$tbl = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
					$tbl .= '<tr>
								<td style="font-weight: bold">Cod. Emp.</td>
								<td>'.$datos[0][0].'</td>
							</tr>';
					$tbl .= '<tr>
								<td style="font-weight: bold">DNI.</td>
								<td>'.$datos[0][1].'</td>
							</tr>';
					$tbl .= '<tr>
								<td style="font-weight: bold">Nombre</td>
								<td>'.$datos[0][2].'</td>
							</tr>';
					$tbl .= '<tr>
								<td style="font-weight: bold">DDRR.</td>
								<td>'.$datos[0][3].'</td>
							</tr>';
					$tbl .= '<tr>
								<td style="font-weight: bold">DEPEN.</td>
								<td>'.$datos[0][4].'</td>
							</tr>';					
					$tbl .= '<tr>
								<td style="font-weight: bold">UNID.</td>
								<td>'.$datos[0][5].'</td>
							</tr>';
					$tbl .= '<tr>
								<td style="font-weight: bold">ENTRADA</td>
								<td>'.$datos[0][6].'</td>
							</tr>';
					$tbl .= '<tr>
								<td style="font-weight: bold">SALIDA</td>
								<td>'.$datos[0][7].'</td>
							</tr>';
				$tbl .= '</table>';
				echo $tbl;
			}
		}
	}
	
	public function reportediasnolaboradosAction() {
		$this->view->inlineScript()->appendScript("rangosfechas();");
		$this->view->inlineScript()->appendScript("buscarempleados();");
		
		
		$cn = new Model_DataAdapter();
//		$arraydatos[] = array(':p_cod','');
//		$datos_emp = $cn->ejec_store_oracle_sisper(' pkg_asistencias.sp_obt_datos_emp',$arraydatos);	
		
		$arraydatos = array();
		$arraydatos[] = array(':p_cod','');
		$datos_ddrr = $cn->ejec_store_oracle_sisper(' pkg_asistencias.sp_obt_ddrr',$arraydatos);
		
//		$a_emp = array();
//		for($i=0;$i<count($datos_emp);$i++)
//			$a_emp[] = array( $datos_emp[$i][0],$datos_emp[$i][2] );
		
		$fn = new Libreria_Pintar();
//		$val[] = array('cbempleado',$fn->ContenidoCombo($a_emp,''),'html');
		$val[] = array('cbddrr',$fn->ContenidoCombo($datos_ddrr,''),'html');
		$val[] = array('cbdependencia','<option value="99999">Seleccionar...</option>','html');
		$val[] = array('cbunidad','<option value="99999">Seleccionar...</option>','html');
		$evt[] = array('btn_buscar','click','reportediasnolaboradosdata();');
		$evt[] = array('btn_pdf_dirreg','click','pdf();');
		$evt[] = array('cbddrr','change','buscardependencias();buscarempleados();');
		$evt[] = array('cbdependencia','change','buscarunidad();buscarempleados();');
		$evt[] = array('cbunidad','change','buscarempleados();');
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		
		echo $this->view->inlineScript();	
		
	}
	
		public function buscarempleadosAction() {
		  		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
			if ($this->getRequest ()->isXmlHttpRequest ()) {
				$cod_ddrr = $this->_request->getPost( 'cod_ddrr');
				$cod_depen = $this->_request->getPost( 'cod_depen');
				$cod_unid = $this->_request->getPost( 'cod_unid');
				$cn = new Model_DataAdapter();
				$arraydatos[] = array(':p_cod','');
				$arraydatos[] = array(':p_cod_ddrr',$cod_ddrr);
				$arraydatos[] = array(':p_cod_depen',$cod_depen);
				$arraydatos[] = array(':p_cod_unid',$cod_unid);
				$datos_emp = $cn->ejec_store_oracle_sisper('pkg_asistencias.sp_obt_datos_emp',$arraydatos);
				
				$a_emp = array();
				for($i=0;$i<count($datos_emp);$i++)
					$a_emp[] = array( $datos_emp[$i][0],$datos_emp[$i][2] );
				
				$fn = new Libreria_Pintar();
				echo $fn->ContenidoCombo($a_emp,'');
			}
		}
		
		public function buscardependenciasAction() {
		  		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
			if ($this->getRequest ()->isXmlHttpRequest ()) {
				$cod_ddrr = $this->_request->getPost( 'cod_ddrr');
				$cn = new Model_DataAdapter();
				$arraydatos[] = array(':p_cod',$cod_ddrr);
				$datos = $cn->ejec_store_oracle_sisper('pkg_asistencias.sp_obt_dependencias',$arraydatos);
				
				$fn = new Libreria_Pintar();
				echo $fn->ContenidoCombo($datos,'');
			}
		}

		public function buscarunidadAction() {
		  		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
			if ($this->getRequest ()->isXmlHttpRequest ()) {
				$cod_ddrr = $this->_request->getPost( 'cod_ddrr');
				$cod_depen = $this->_request->getPost( 'cod_depen');
				$cn = new Model_DataAdapter();
				$arraydatos[] = array(':p_cod_ddrr',$cod_ddrr);
				$arraydatos[] = array(':p_cod_depen',$cod_depen);
				$datos = $cn->ejec_store_oracle_sisper('pkg_asistencias.sp_obt_unidad',$arraydatos);
				
				$fn = new Libreria_Pintar();
				echo $fn->ContenidoCombo($datos,'');
			}
		}
	
	
	public function reportediasnolaboradosdataAction() {
		  
		
//		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
		
		
//		$codemp = '00010654';
//		$fec_ini = '01-01-2014';
//		$fec_fin = '31-12-2014';
		
		
		$codemp = $this->_request->getPost( 'cod_emp');
		$fec_ini = $this->_request->getPost( 'fecha_ini');
		$fec_fin = $this->_request->getPost( 'fecha_fin');
		
		$cod_ddrr = $this->_request->getPost( 'cod_ddrr');
		$cod_depen = $this->_request->getPost( 'cod_depen');
		$cod_unid = $this->_request->getPost( 'cod_unid');
		
		$cn = new Model_DataAdapter();
		
		$psparam = array();
		$ns = 'pkg_asistencias.sp_obt_emp_report_no_asist';
		$psparam[] = array(':p_cod_emp',$codemp);
		$psparam[] = array(':p_fec_ini',$fec_ini);
		$psparam[] = array(':p_fec_fin',$fec_fin);
		$psparam[] = array(':p_cod_ddrr',$cod_ddrr);
		$psparam[] = array(':p_cod_depen',$cod_depen);
		$psparam[] = array(':p_cod_unid',$cod_unid);
		$datos = $cn->ejec_store_oracle_sisper($ns,$psparam);
		
		if($codemp == ''){				
				
				echo '<div class="demo-section k-content"  style="width: 1200px">
				        <table id="tbl_datos_report" border="1"  cellpadding="0" cellspacing="0"></table>
				    </div> ';
				
				$datatable_opciones[] = array('bFilter','true');
				$datatable_opciones[] = array('bLengthChange','false');
				$datatable_opciones[] = array('bInfo','true');
				$datatable_opciones[] = array('bPaginate','true');		
				$datatable_opciones[] = array('sPaginationType','"full_numbers"');
				$datatable_opciones[] = array('iDisplayLength',count($datos));
				$datatable_opciones[] = array('bJQueryUI','true');
				$datatable_opciones[] = array('bScrollCollapse','true');
				$datatable_opciones[] = array('aaData',json_encode($datos));
				$datatable_opciones[] = array('aoColumns',json_encode(array( array('sTitle'=>'cod empleado','sWidth'=>'100px')			                                                                            
                                                                            ,array('sTitle'=>'Empleado','sWidth'=>'500px')
                                                                            ,array('sTitle'=>'Unidad','sWidth'=>'120px')
                                                                            ,array('sTitle'=>'Mes','sWidth'=>'50px')
                                                                            ,array('sTitle'=>'A&ntilde;o','sWidth'=>'50px')
                                                                            ,array('sTitle'=>'Permisos<br>Personales<br>(MIN)','sWidth'=>'100px')
                                                                            ,array('sTitle'=>'Dias Descanso<br>Medico<br>','sWidth'=>'100px')
                                                                            ,array('sTitle'=>'Citas<br>Medicas<br>(CANT)','sWidth'=>'100px')
                                                                            ,array('sTitle'=>'Citas<br>Medicas<br>(MIN)','sWidth'=>'100px')
                                                                            ,array('sTitle'=>'Comision de<br>Servicios<br>(CANT)','sWidth'=>'100px')
                                                                            ,array('sTitle'=>'Comision de<br>Servicios<br>(MIN)','sWidth'=>'100px')
                                                                            ,array('sTitle'=>'Tardanzas<br>(CANT)','sWidth'=>'100px')
                                                                            ,array('sTitle'=>'Tardanzas<br>(MIN)','sWidth'=>'100px')
                                                                             ,array('sTitle'=>'Faltas<br>(CANT)','sWidth'=>'100px')
				                                                                            )
				                                                                        ));
                $datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){
                																					$("td:eq(0),td:eq(5),td:eq(6),td:eq(7),td:eq(8),td:eq(9),td:eq(10),td:eq(11),td:eq(12),td:eq(13)", nRow).attr( "align","center" );
																										/*Permisos personales*/
                																					    if(parseInt($("td:eq(5)", nRow).html()) >= 232 ){$("td:eq(5)", nRow).css({"background-color":"yellow"});}
																									    if(parseInt($("td:eq(5)", nRow).html()) >= 465 ){$("td:eq(5)", nRow).css({"background-color":"red"});}
																									    /*Cantidad de Faltas*/
																									    if(parseInt($("td:eq(11)", nRow).html()) >= 4 ){$("td:eq(11)", nRow).css({"background-color":"red"});}
																									    if(parseInt($("td:eq(12)", nRow).html()) >= 40 ){$("td:eq(12)", nRow).css({"background-color":"yellow"});}
																									    if(parseInt($("td:eq(12)", nRow).html()) >= 60 ){$("td:eq(12)", nRow).css({"background-color":"red"});}
																									}');				                                                                        
	//			$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){ if($("td:eq(9)", nRow).html() == "Tipo" ){$("td:eq(9)", nRow).addClass( "ui-state-highlight" );} }');
				$datatable_opciones[] = array('sDom','\'<"H"Tfr>t<"F"ip>\'');
				$datatable_opciones[] = array('oTableTools','{"aButtons": [{"sExtends": "collection","sButtonText": "Guardar","aButtons":[ "xls"]}]}');	                                                                        
				echo $this->view->util()->datatable("tbl_datos_report",$datatable_opciones);
				
		}else{
			
			
			echo '
				<div class="demo-section k-content"  style="width: 800px">
					<table>
						<tr>
							<td width="90px">Nombres</td>
							<td width="30px">:</td>
							<td id="txt_nombre">XXXX</td>
						</tr>
						<tr>
							<td>C&oacute;digo</td>
							<td>:</td>
							<td id="txt_codemp">XXXX</td>
						</tr>
						<tr>
							<td>Dependencia</td>
							<td>:</td>
							<td id="txt_depen">XXXX</td>
						</tr>
					</table>
				</div>
				
					<div class="demo-section k-content"  style="width: 1000px">
				        <div id="chart_permisos_personales"></div>
				    </div> 
			        
			        <br>
			        
			        <div style="width: 1200px">
			        	<div id="chart_descanso_medico"  class="demo-section k-content" style="width: 70%;float: left"></div>
			        	<div id="chart_descanso_medico_acum"  class="demo-section k-content" style="width: 25%;float: right;" ></div>
			        </div>
			        
			        <br> 
			            
			        <div style="width: 1200px">
			        	<div id="chart_citas_medicas_frecuencia"  class="demo-section k-content" style="width: 47%;float: left"></div>
			        	<div id="chart_citas_medicas_acum_min"  class="demo-section k-content" style="width: 47%;float: right;" ></div>
			        </div>
			        
			        <br>
			        
			        <div style="width: 1200px">
			        	<div id="chart_comision_servico_frecuencia"  class="demo-section k-content" style="width: 47%;float: left"></div>
			        	<div id="chart_comision_servico_acum_min"  class="demo-section k-content" style="width: 47%;float: right;"></div>
			        </div>
			        
			        <br>
			        
			        <div style="width: 1200px">
			        	<div id="chart_tardanzas_frecuencia"  class="demo-section k-content" style="width: 47%;float: left"></div>
			        	<div id="chart_tardanzas_acum_min"  class="demo-section k-content" style="width: 47%;float: right;"></div>
			        </div>';
			
			
				$datosemp = $cn->ejec_store_oracle_sisper('pkg_asistencias.sp_obt_datos_emp',array(array(':p_cod',$codemp),array(':p_cod_ddrr',''),array(':p_cod_depen',''),array(':p_cod_unid','')));	
		//		print_r($datosemp);
				$fn = new Libreria_Pintar();
				$val[] = array('txt_nombre',$datosemp[0][2],'html');
				$val[] = array('txt_codemp',$datosemp[0][0],'html');
				$val[] = array('txt_depen',$datosemp[0][4],'html');
				echo $fn->PintarValor($val);
		
//				$ns = 'pkg_asistencias.sp_obt_emp_perm_pers_x_mes';
//				$psparam[] = array(':p_cod_emp',$codemp);
//				$psparam[] = array(':p_fec_ini',$fec_ini);
//				$psparam[] = array(':p_fec_fin',$fec_fin);
//				$datos = $cn->ejec_store_oracle_sisper($ns,$psparam);
								
				echo '<script>	        
			            $("#chart_permisos_personales").kendoChart({
			                title: { text: "PERMISOS PERSONALES" },
			                legend: { position: "top" },
			                series: [
				                {
				                	type: "column",
				                	labels: {visible: true,border: {width: 2,color: "black"}},
				                    name: "MINUTOS POR MES",
				                    data: [';
											for($i=0;$i<count($datos);$i++) echo $datos[$i][4] . ',';
				 ECHO                 	  ']
				                }, {
				                    type: "line",
				                    data: [';
											for($i=0;$i<count($datos);$i++) echo '465' . ',';
				 ECHO                 	  '],
				                    name: "Limite de minutos: 465 min",
				                    color: "#FF0000"
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 315},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][2].' / '.$datos[$i][3].'"'. ',';
				 ECHO                 	  '],
			                    line: {visible: false}
			                },
			                tooltip: {visible: true,template: "#= series.name #: #= value #"}
			            });	        
			    </script>';
				 
				 /*********************************************************************************************/
				 
//				$ns = ' pkg_asistencias.sp_obt_emp_descanso_med_x_mes';
//				$datos = $cn->ejec_store_oracle_sisper($ns,$psparam);
								
				echo '<script>	        
			            $("#chart_descanso_medico").kendoChart({
			                title: { text: "DESCANSO MEDICOS" },
			                legend: { position: "top" },
			                seriesDefaults: {  type: "column" },
			                series: [
				                {
				                	labels: {visible: true,border: {width: 2,color: "black"}},
				                    name: "DIAS POR MES",
				                    data: [';
											for($i=0;$i<count($datos);$i++) echo $datos[$i][5] . ',';
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 315},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][2].' / '.$datos[$i][3].'"'. ',';				                    		
				 ECHO                 	  '],
			                    line: {visible: false}
			                },
			                tooltip: {visible: true,template: "#= series.name #: #= value #"}
			            });	        
			    </script>';
				 
				 echo '<script>	        
			            $("#chart_descanso_medico_acum").kendoChart({
			                title: { text: "DESCANSO MEDICOS" },
			                legend: { position: "top" },
			                seriesDefaults: {  type: "line" },
			                series: [
				                {
				                	labels: {visible: true,border: {width: 2,color: "black"}},
				                    name: "ACUM DE DIAS POR MES",
				                    data: [';
				 							$acum = 0;
											for($i=0;$i<count($datos);$i++){ 
												$acum = $acum + $datos[$i][5];
												echo $acum . ',';
											}
				 ECHO                 	  ']
				                },{
				                	//labels: {visible: true,border: {width: 2,color: "black"}},
				                    name: "Limite 20 dias",
				                    color: "#FF0000",
				                    data: [';
											for($i=0;$i<count($datos);$i++){ 
												echo '20,';
											}
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][2].' / '.$datos[$i][3].'"'. ',';				                    		
				 ECHO                 	  '],
			                    line: {visible: false}
			                },
			                tooltip: {visible: true,template: "#= series.name #: #= value #"}
			            });	        
			    </script>';
				 /*********************************************************************************************/
				 
//				$ns = ' pkg_asistencias.sp_obt_emp_citas_medic_x_mes';
//				$datos = $cn->ejec_store_oracle_sisper($ns,$psparam);
								
				echo '<script>	        
			            $("#chart_citas_medicas_frecuencia").kendoChart({
			                title: { text: "CITAS MEDICAS" },
			                legend: { position: "top" },
			                seriesDefaults: {  type: "column" },
			                series: [
				                {
				                	labels: {visible: true,border: {width: 2,color: "black"}},
				                    name: "FRECUENCIA POR MES",
				                    data: [';
											for($i=0;$i<count($datos);$i++) echo $datos[$i][6] . ',';
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][2].' / '.$datos[$i][3].'"'. ',';
				                    		
				 ECHO                 	  '],
			                    line: {visible: false}
			                },
			                tooltip: {visible: true,template: "#= series.name #: #= value #"}
			            });	        
			    </script>';
				 
				 echo '<script>	        
			            $("#chart_citas_medicas_acum_min").kendoChart({
			                title: { text: "CITAS MEDICAS" },
			                legend: { position: "top" },
			                seriesDefaults: {  type: "column" },
			                series: [
				                {
				                	labels: {visible: true,border: {width: 0,color: "black"}},
				                    name: "MINUTOS CUMULADAS POR MES",
				                    data: [';
											for($i=0;$i<count($datos);$i++) echo $datos[$i][7] . ',';			                    		
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][2].' / '.$datos[$i][3].'"'. ',';
				 ECHO                 	  '],
			                    line: {visible: false}
			                },
			                tooltip: {visible: true,template: "#= series.name #: #= value #"}
			            });	        
			    </script>';
				 
				 /*********************************************************************************************/
				 
//				$ns = ' pkg_asistencias.sp_obt_emp_comision_serv_x_mes';
//				$datos = $cn->ejec_store_oracle_sisper($ns,$psparam);
								
				echo '<script>	        
			            $("#chart_comision_servico_frecuencia").kendoChart({
			                title: { text: "COMISIONES DE SERVICIO" },
			                legend: { position: "top" },
			                seriesDefaults: {  type: "column" },
			                series: [
				                {
				                	labels: {visible: true,border: {width: 2,color: "black"}},
				                    name: "FRECUENCIA POR MES",
				                    data: [';
											for($i=0;$i<count($datos);$i++) echo $datos[$i][8] . ',';
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][2].' / '.$datos[$i][3].'"'. ',';				                    		
				 ECHO                 	  '],
			                    line: {visible: false}
			                },
			                tooltip: {visible: true,template: "#= series.name #: #= value #"}
			            });	        
			    </script>';
				 
				 echo '<script>	        
			            $("#chart_comision_servico_acum_min").kendoChart({
			                title: { text: "COMISIONES DE SERVICIO" },
			                legend: { position: "top" },seriesDefaults: {  type: "column" },
			                series: [
				                {
				                	labels: {visible: true,border: {width: 0,color: "black"}},
				                    name: "MINUTOS CUMULADAS POR MES",
				                    data: [';
											for($i=0;$i<count($datos);$i++) echo $datos[$i][9] . ',';				                    		
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][2].' / '.$datos[$i][3].'"'. ',';				                    		
				 ECHO                 	  '],
			                    line: {visible: false}
			                },
			                tooltip: {visible: true,template: "#= series.name #: #= value #"}
			            });	        
			    </script>';
				 
				 				 /*********************************************************************************************/
				 
//				$ns = ' pkg_asistencias.sp_obt_emp_comision_serv_x_mes';
//				$datos = $cn->ejec_store_oracle_sisper($ns,$psparam);
								
				echo '<script>	        
			            $("#chart_tardanzas_frecuencia").kendoChart({
			                title: { text: "TARDANZAS" },
			                legend: { position: "top" },
			                seriesDefaults: {  type: "column" },
			                series: [
				                {
				                	labels: {visible: true,border: {width: 2,color: "black"}},
				                    name: "FRECUENCIA POR MES",
				                    data: [';
											for($i=0;$i<count($datos);$i++) echo $datos[$i][10] . ',';
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][2].' / '.$datos[$i][3].'"'. ',';				                    		
				 ECHO                 	  '],
			                    line: {visible: false}
			                },
			                tooltip: {visible: true,template: "#= series.name #: #= value #"}
			            });	        
			    </script>';
				 
				 echo '<script>	        
			            $("#chart_tardanzas_acum_min").kendoChart({
			                title: { text: "TARDANZAS" },
			                legend: { position: "top" },seriesDefaults: {  type: "column" },
			                series: [
				                {
				                	labels: {visible: true,border: {width: 0,color: "black"}},
				                    name: "MINUTOS CUMULADAS POR MES",
				                    data: [';
											for($i=0;$i<count($datos);$i++) echo $datos[$i][11] . ',';				                    		
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][2].' / '.$datos[$i][3].'"'. ',';				                    		
				 ECHO                 	  '],
			                    line: {visible: false}
			                },
			                tooltip: {visible: true,template: "#= series.name #: #= value #"}
			            });	        
			    </script>';
		}
			    
		}
	}

	
	public function pdfAction() {
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		
		$codemp = $this->_request->getParam('cod_emp');
		$fec_ini = $this->_request->getParam( 'fecha_ini');
		$fec_fin = $this->_request->getParam( 'fecha_fin');
		
		$cod_ddrr = $this->_request->getParam( 'cod_ddrr');
		$cod_depen = $this->_request->getParam( 'cod_depen');
		$cod_unid = $this->_request->getParam( 'cod_unid');
		
		$pdf = new pdf_usage();
		
		$ancho_col = array(17, 70,20,15,15,19,19,15,15,15,15,17,17,15);		
		$header = array( 'cod'
		                ,'Empleado'
		                ,'Unidad'
		                ,'Mes'
		                ,'Año'
		                ,'Permisos Personales (MIN)'
		                ,'Dias Descanso Medico'
		                ,'Citas Medicas (CANT)'
		                ,'Citas Medicas (MIN)'
		                ,'Comision de Servicios (CANT)'
		                ,'Comision de Servicios (MIN)'
		                ,'Tardanzas (CANT)'
		                ,'Tardanzas (MIN)'
		                ,'Faltas (CANT)'
		                );
		                
		$cn = new Model_DataAdapter();

		/*************************************************************************/
//		if($cod_ddrr != "" && $cod_depen == "" && $cod_unid == "" ){  //--------------------------
			
			// Datos				   
		    $psparam = array();
			$ns = 'pkg_asistencias.sp_obt_emp_report_no_asist';
			$psparam[] = array(':p_cod_emp','');
			$psparam[] = array(':p_fec_ini',$fec_ini);
			$psparam[] = array(':p_fec_fin',$fec_fin);
			$psparam[] = array(':p_cod_ddrr',$cod_ddrr);
			$psparam[] = array(':p_cod_depen',$cod_depen);
			$psparam[] = array(':p_cod_unid',"");
			$data = $cn->ejec_store_oracle_sisper($ns,$psparam);

			$titulo = "";
			$resumen = 0;
			for($i=0;$i<count($data);$i++){
//			for($i=0;$i<7;$i++){
				if($titulo != $data[$i][14].' - '.$data[$i][15]){
					/*dibujar la ulktima linea de la tablaanterior*/
					
					$permisos_personales_min = 0;
					$Dias_descanso_medico = 0;
					$citas_medicas_cant = 0;
					$citas_medicas_min = 0;
					$comisiones_cant = 0;			
					$comisiones_min = 0;
					$tardanzas_cant = 0;
					$tardanzas_min = 0;
					$faltas = 0;
					
					$titulo = $data[$i][14].' - '.$data[$i][15];
					$pdf->AddPage('L');
					$pdf->SetFont('Arial','',10);
					$pdf->Cell(40,10,$titulo . '             ( Reporte del '.$fec_ini.' al '.$fec_fin.' )');
					$pdf->Ln();
					// Colores, ancho de línea y fuente en negrita
				    $pdf->SetFillColor(255,0,0);
				    $pdf->SetTextColor(255);
				    $pdf->SetDrawColor(128,0,0);
				    $pdf->SetLineWidth(.3);
				    $pdf->SetFont('','B',8);
				    // Cabecera   
				    $y = 0;
				    $x = 0;
				    $anch = 21;
				    for($j=0;$j<count($header);$j++){	
				    		if($j == 0){ $y = $pdf->GetY();$x = $pdf->GetX();}
				    		else{ $pdf->SetY($y);$x = $x + $ancho_col[$j-1];$pdf->SetX($x);} 	 
				    		$cant = count(explode(" ",$header[$j])); 					    			
				    		$pdf->MultiCell($ancho_col[$j],$anch/$cant,$header[$j],1,'C',true);	
				    }
				    
				   $fill = false;
				   // Restauración de colores y fuentes
				   $pdf->SetFillColor(224,235,255);$pdf->SetTextColor(0);$pdf->SetFont('','',8); 
				}
				
//					for($l=0;$l<count($ancho_col);$l++){
//			        	$pdf->Cell($ancho_col[$l],6,utf8_decode($data[$i][$l]),'1',0,'L',$fill);	
//			    	}

					$pdf->Cell($ancho_col[0],6,utf8_decode($data[$i][0]),'1',0,'L',$fill);	
					$pdf->Cell($ancho_col[1],6,utf8_decode($data[$i][1]),'1',0,'L',$fill);
			    	$pdf->Cell($ancho_col[2],6,utf8_decode($data[$i][2]),'1',0,'L',$fill);
			    	$pdf->Cell($ancho_col[3],6,utf8_decode($data[$i][3]),'1',0,'L',$fill);
			    	$pdf->Cell($ancho_col[4],6,utf8_decode($data[$i][4]),'1',0,'L',$fill);
			    	
			    	$fill_temp = $fill;
			    	if($data[$i][5] > 232) {$pdf->SetFillColor(255,255,0);$fill=true;}
			    	if($data[$i][5] > 465) {$pdf->SetFillColor(255,0,0);	$fill=true;}
			    	$pdf->Cell($ancho_col[5],6,utf8_decode($data[$i][5]),'1',0,'L',$fill);
			    		$permisos_personales_min = $permisos_personales_min+$data[$i][5];
			    	$pdf->SetFillColor(224,235,255);$fill = $fill_temp;	
			    		
			    		
			    	$pdf->Cell($ancho_col[6],6,utf8_decode($data[$i][6]),'1',0,'L',$fill);
			    		$Dias_descanso_medico = $Dias_descanso_medico + $data[$i][6];
			    		
			    	$pdf->Cell($ancho_col[7],6,utf8_decode($data[$i][7]),'1',0,'L',$fill);
			    		$citas_medicas_cant = $citas_medicas_cant  + $data[$i][7];
			    		
			    	$pdf->Cell($ancho_col[8],6,utf8_decode($data[$i][8]),'1',0,'L',$fill);
			    		$citas_medicas_min = $citas_medicas_min + $data[$i][8];
			    		
			    	$pdf->Cell($ancho_col[9],6,utf8_decode($data[$i][9]),'1',0,'L',$fill);
			    		$comisiones_cant = $comisiones_cant + $data[$i][9];
			    		
			    	$pdf->Cell($ancho_col[10],6,utf8_decode($data[$i][10]),'1',0,'L',$fill);
			    		$comisiones_min = $comisiones_min + $data[$i][10];

			    	$fill_temp = $fill;	
			    	if($data[$i][11] > 2 && $data[$i][11] <= 4){$pdf->SetFillColor(255,255,0);$fill=true;}
			    	if($data[$i][11] >= 5){$pdf->SetFillColor(255,0,0);	$fill=true;}	
			    	$pdf->Cell($ancho_col[11],6,utf8_decode($data[$i][11]),'1',0,'L',$fill);
			    		$tardanzas_cant = $tardanzas_cant + $data[$i][11];
			    	$pdf->SetFillColor(224,235,255);$fill = $fill_temp;
			    		
			    	$pdf->Cell($ancho_col[12],6,utf8_decode($data[$i][12]),'1',0,'L',$fill);
			    		$tardanzas_min = $tardanzas_min + $data[$i][12];
			    		
			    	$pdf->Cell($ancho_col[13],6,utf8_decode($data[$i][13]),'1',0,'L',$fill);
			    		$faltas = $faltas + $data[$i][13];
			    		
			    	$pdf->Ln();
			        $fill = !$fill;	
			        if(count($data) > $i+1 ){
				        if($titulo != $data[$i+1][14].' - '.$data[$i+1][15]){
				        	$resumen = 1;
				        }			        
			        }
					if(count($data)-1 == $i){
			        	$resumen = 1;
			        }
			        
			        if($resumen == 1){
			        	$resumen = 0;
			        	$pdf->Cell(40,10,'RESUMEN:');$pdf->Ln();
						$pdf->Cell(50,5,'Total de Permisos Personales (MIN):',0,0);$pdf->Cell(40,5,$permisos_personales_min,0,0);$pdf->Ln();						
						$pdf->Cell(50,5,'Total de Dias de descanzo medico:',0,0);$pdf->Cell(40,5,$Dias_descanso_medico,0,0);$pdf->Ln();
						
						$pdf->Cell(50,5,'Total de Citas Medicas:',0,0);
						$pdf->Cell(20,5,'Cantidad:   '.$citas_medicas_cant,0,0);
						$pdf->Cell(37,5,'/   Total en Minutos:   '.$citas_medicas_min,0,0);
						$tiempo_en_segundos = $citas_medicas_min*60;
						$dias = floor($tiempo_en_segundos / (3600*7.75));
						$horas = floor(($tiempo_en_segundos - ( $dias * 3600 * 7.75 ) )  / 3600);
						$minutos = floor(($tiempo_en_segundos- ( $dias * 3600 * 7.75 )  - ($horas * 3600)) / 60);
						//$segundos = $tiempo_en_segundos- ( $dias * 3600 * 24 )  - ($horas * 3600) - ($minutos * 60);						
						$pdf->Cell(40,5,'/   Total en tiempo:   '. $dias .' dias, '.$horas . ' horas, '.$minutos.' minutos',0,0);$pdf->Ln();
						
						
						$pdf->Cell(50,5,'Total de Comisiones de servicio:',0,0);
						$pdf->Cell(20,5,'Cantidad:   '.$comisiones_cant,0,0);
						$pdf->Cell(37,5,'/   Total en Minutos:   '.$comisiones_min,0,0);
						$tiempo_en_segundos = $comisiones_min*60;
						$dias = floor($tiempo_en_segundos / (3600*7.75));
						$horas = floor(($tiempo_en_segundos - ( $dias * 3600 * 7.75 ) )  / 3600);
						$minutos = floor(($tiempo_en_segundos- ( $dias * 3600 * 7.75 )  - ($horas * 3600)) / 60);
						//$segundos = $tiempo_en_segundos- ( $dias * 3600 * 24 )  - ($horas * 3600) - ($minutos * 60);						
						$pdf->Cell(40,5,'/   Total en tiempo:   '. $dias .' dias, '.$horas . ' horas, '.$minutos.' minutos',0,0);$pdf->Ln();
						
						$pdf->Cell(50,5,'Total de Tardanzas:',0,0);
						$pdf->Cell(20,5,'Cantidad:   '.$tardanzas_cant,0,0);
						$pdf->Cell(40,5,'/   Total en Minutos:   '.$tardanzas_min,0,0);$pdf->Ln();
						
						$pdf->Cell(50,5,'Total de Faltas:',0,0);$pdf->Cell(40,5,$faltas,0,0);$pdf->Ln();
			        }
			        
			        
			}
				
//		} //--------		
		/*************************************************************************/
		$pdf->Output();
		
	}
	
	public function asistenciaempleadoAction() {
		$this->view->inlineScript()->appendScript("rangosfechas();");
//		$this->view->inlineScript()->appendScript("buscarempleados();");
		
		
		$cn = new Model_DataAdapter();
		$arraydatos[] = array(':p_cod','');
		$arraydatos[] = array(':p_cod_ddrr','');
		$arraydatos[] = array(':p_cod_depen','');
		$arraydatos[] = array(':p_cod_unid','');
		$datos_emp = $cn->ejec_store_oracle_sisper('pkg_asistencias.sp_obt_datos_emp',$arraydatos);	
		
		$datos_emp_tmp = array();
		for($i=0;$i<count($datos_emp);$i++){
			$datos_emp_tmp[] = array(
									$datos_emp[$i][0]
									,$datos_emp[$i][1]
									,$datos_emp[$i][2]
									,$datos_emp[$i][5]
									,'<input type="radio" name="cod_emp" value="'.$datos_emp[$i][0].'">'	
									);
		}
		
		$datatable_opciones[] = array('bFilter','true');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','true');
		$datatable_opciones[] = array('bPaginate','true');		
		$datatable_opciones[] = array('sPaginationType','"full_numbers"');
		$datatable_opciones[] = array('sScrollY','"200px"');
		$datatable_opciones[] = array('iDisplayLength',count($datos_emp_tmp));
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode($datos_emp_tmp));
		$datatable_opciones[] = array('aoColumns',json_encode(array( array('sTitle'=>'cod empleado','sWidth'=>'80px')		
												                    ,array('sTitle'=>'DNI','sWidth'=>'80px')                                                        
                                                                    ,array('sTitle'=>'Empleado','sWidth'=>'400px')
                                                                    ,array('sTitle'=>'Unidad','sWidth'=>'250px')     
                                                                    ,array('sTitle'=>'-','sWidth'=>'20px')                                                                        
			                                                        )
			                                                                ));
        echo $this->view->util()->datatable("tbl_empleados",$datatable_opciones);
		
//		$arraydatos = array();
//		$arraydatos[] = array(':p_cod','');
//		$datos_ddrr = $cn->ejec_store_oracle_sisper(' pkg_asistencias.sp_obt_ddrr',$arraydatos);
		
//		$a_emp = array();
//		for($i=0;$i<count($datos_emp);$i++)
//			$a_emp[] = array( $datos_emp[$i][0],$datos_emp[$i][2] );
		
		$fn = new Libreria_Pintar();
//		$val[] = array('cbempleado',$fn->ContenidoCombo($a_emp,''),'html');
		$evt[] = array('btn_buscar','click','asistenciaempleadodata();');
		$evt[] = array('btn_marcarhorasfueradelabor','click','marcarhorasfueradelabor();');
//		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		
		echo $this->view->inlineScript();	
		
	}
	
	public function asistenciapracticantesAction() {
		$this->view->inlineScript()->appendScript("rangosfechas();");
//		$this->view->inlineScript()->appendScript("buscarempleados();");
		
		
		$cn = new Model_DataAdapter();
		
		$datos_emp = $cn->ejec_store_oracle_sisper('pkg_asistencias.sp_obt_practicantes',null);	
		
		$datos_emp_tmp = array();
		for($i=0;$i<count($datos_emp);$i++){
			$datos_emp_tmp[] = array(
									$datos_emp[$i][0]
									,$datos_emp[$i][2]
									,$datos_emp[$i][5]
									,'<input type="radio" name="cod_emp" value="'.$datos_emp[$i][0].'">'	
									);
		}
		
		$datatable_opciones[] = array('bFilter','true');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','true');
		$datatable_opciones[] = array('bPaginate','true');		
		$datatable_opciones[] = array('sPaginationType','"full_numbers"');
		$datatable_opciones[] = array('sScrollY','"200px"');
		$datatable_opciones[] = array('iDisplayLength',count($datos_emp_tmp));
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode($datos_emp_tmp));
		$datatable_opciones[] = array('aoColumns',json_encode(array( array('sTitle'=>'cod empleado','sWidth'=>'80px')			                                                                            
                                                                    ,array('sTitle'=>'Empleado','sWidth'=>'400px')
                                                                    ,array('sTitle'=>'Unidad','sWidth'=>'250px')     
                                                                    ,array('sTitle'=>'-','sWidth'=>'20px')                                                                        
			                                                        )
			                                                                ));
        echo $this->view->util()->datatable("tbl_empleados",$datatable_opciones);

		$fn = new Libreria_Pintar();
//		$val[] = array('cbempleado',$fn->ContenidoCombo($a_emp,''),'html');
		$evt[] = array('btn_buscar','click','asistenciapracticantes();');
//		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		
		echo $this->view->inlineScript();	
		
	}
	
	
	public function marcacionesfueradehorarioAction() {
		date_default_timezone_set('America/Lima');
		$dia_esp = array("LUN", "MAR", "MIE" ,"JUE","VIE","SAB","DOM");
		$day_eng = array("MON", "TUE", "WED" ,"THU","FRI","SAT","SUN");
			
		$this->_helper->layout->disableLayout ();
		
		$this->view->inlineScript()->appendScript('$("button").button();');
		echo $this->view->inlineScript();
		
		$codemp = $this->_request->getParam('cod_emp');
		$fecha_ini = $this->_request->getParam('fecha_ini');
		$fecha_fin = $this->_request->getParam('fecha_fin');	

//		echo $codemp . '->' . $fecha_ini . '->' .$fecha_fin;
		
		$cn = new Model_DataAdapter();
		$arraydatos[] = array(':p_cod_emp',$codemp);
		$arraydatos[] = array(':p_fecha_ini',$fecha_ini);
		$arraydatos[] = array(':p_fecha_fin',$fecha_fin);
		$datos = $cn->ejec_store_oracle_sisper('pkg_asistencias.SP_OBT_ASIST_EMP',$arraydatos);

		$fn = new Libreria_Pintar();
		
		$val[] = array('txt_emp',$datos[0][1],'html');
		$val[] = array('txt_cod_emp',$datos[0][0],'val');
		$fn->PintarValor($val);
		
		$evt[] = array('btn_guardarhorasextrastomadas','click','guardarhorasextrastomadas();');
		$evt[] = array('btn_cancelarhorasextrastomadas','click','cerrarsubvent();');
		$fn->PintarEvento($evt);
		
		$datos_temp = array();
		for($i=0;$i<count($datos);$i++){
			
			$datos[$i][3] = str_replace($day_eng, $dia_esp, $datos[$i][3]);
			
			if($datos[$i][11] != '' || ( $datos[$i][7] == 'VACACIONES' && $datos[$i][8] != 0 ) || ( strpos($datos[$i][7], 'COMISION') >= 0 && ($datos[$i][3] == 'DOM' || $datos[$i][3] == 'SAB' )) ){

				if($datos[$i][13]!=''){
					$datos_temp[] = array(
									 '<div class="ui-state-error" align="left" >'.$datos[$i][2].' ('.$datos[$i][3].')</div>'	
									,'<div class="ui-state-error" align="center" >'.$datos[$i][5].'</div>'	
									,'<div class="ui-state-error" align="center" >'.$datos[$i][6].'</div>'	
									,'<div  class="ui-state-error" align="center" >'.$datos[$i][11].'</div>'	
									, '<div  class="ui-state-error" align="center">'
									  .'<ul><li class="ui-state-error ui-corner-all"  title=".ui-icon-circle-minus">'.$datos[$i][13].'<span class="ui-icon ui-icon-circle-minus" onclick="eliminarmarcacionesfueradehorario(\''.$codemp.'\',\''.$datos[$i][13].'\')" style="float:right;"></span></li></ul>'
									  .'</div>'	
									  //.'<input type="checkbox" id="chb_horasextras_'.$datos[$i][2].'" name="chb_horasextras" fecha="'.$datos[$i][2].'" hora_ent="'.$datos[$i][5].'"  hora_sal="'.$datos[$i][6].'" value="'.$datos[$i][10].'" onclick="sumarhorasextras()">'
									  
									  
									);
				}else{
					$datos_temp[] = array(
									$datos[$i][2] .' ('.$datos[$i][3].')'	
									,'<div align="center" >'.$datos[$i][5].'</div>'	
									,'<div align="center" >'.$datos[$i][6].'</div>'	
									,'<div align="center" >'.$datos[$i][11].'</div>'	
									,'<div align="center"><input type="checkbox" id="chb_horasextras" name="chb_horasextras" fecha="'.$datos[$i][2].'" hora_ent="'.$datos[$i][5].'"  hora_sal="'.$datos[$i][6].'" value="'.$datos[$i][10].'" onclick="sumarhorasextras()"></div>'	
									);
				}				
			
			}
		}
		
//		print_r($datos);
		
		$datatable_opciones[] = array('bFilter','true');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');		
//		$datatable_opciones[] = array('sPaginationType','"full_numbers"');
		$datatable_opciones[] = array('sScrollY','"200px"');
		$datatable_opciones[] = array('iDisplayLength',count($datos_temp));
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode($datos_temp));
		$datatable_opciones[] = array('aaSorting',"[]");
		$datatable_opciones[] = array('aoColumns',json_encode(array( array('sTitle'=>'Fecha','sWidth'=>'70px')			                                                                            
                                                                    ,array('sTitle'=>'Hora Ent.','sWidth'=>'70px')
                                                                    ,array('sTitle'=>'Hora Sal.','sWidth'=>'70px')     
                                                                    ,array('sTitle'=>'Fuera de<br> Horario','sWidth'=>'70px') 
                                                                    ,array('sTitle'=>'-','sWidth'=>'120px')                                                                        
			                                                        )
			                                                                ));
        echo $this->view->util()->datatable("tbl_marcacionesfueradehora",$datatable_opciones);
	}
	
	public function guardarhorasextrastomadasAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$codemp= $this->_request->getPost ( 'codemp' );
			$nropapeleta= $this->_request->getPost ( 'nropapeleta' );
			$fecha= $this->_request->getPost ( 'fecha' );	
			$hora_ent= $this->_request->getPost ( 'hora_ent' );
			$hora_sal= $this->_request->getPost ( 'hora_sal' );
			$min_ext= $this->_request->getPost ( 'min_ext' );			
			
			$func = new Libreria_Pintar();
			$cn = new Model_DataAdapter();
			$ns = 'pkg_asistencias.sp_tomar_horas_ext';
			$ps[] = array(':p_codemp',$codemp);
			$ps[] = array(':p_nropapeleta',$nropapeleta);
			$ps[] = array(':p_fecha',$fecha);
			$ps[] = array(':p_hora_ent',$hora_ent);	
			$ps[] = array(':p_hora_sal',$hora_sal);
			$ps[] = array(':p_min_ext',$min_ext);	
			$datos = $cn->ejec_store_oracle_sisper($ns,$ps);
			echo $datos[0][0];
		}
	}
	
	public function eliminarmarcacionesfueradehorarioAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$codemp= $this->_request->getPost ( 'codemp' );
			$papeleta= $this->_request->getPost ( 'papeleta' );
						
			$func = new Libreria_Pintar();
			$cn = new Model_DataAdapter();
			$ns = 'pkg_asistencias.sp_eliminar_horas_ext';
			$ps[] = array(':p_codemp',$codemp);
			$ps[] = array(':p_papeleta',$papeleta);
			$datos = $cn->ejec_store_oracle_sisper($ns,$ps);
			echo $datos[0][0];
		}
	}
	
	
	
}

