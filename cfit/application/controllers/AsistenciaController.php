<?php

/**
 * AsistenciaController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class AsistenciaController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function init() {
		$this->view->util()->registerScriptJSController($this->getRequest());
	}
	public function indexAction() {
		// TODO Auto-generated AsistenciaController::indexAction() default action
	}
	
	public function reporteasistenciaemploginAction() {
		$evt[] = array("btningreso","click","generar_reporteasistenciaemp();");		
		$func = new Libreria_Pintar();
		$func->PintarEvento($evt);
	}
	
	public function validarreporteasistenciaempAction() {   
		$this->_helper->viewRenderer->setNoRender(); 
		$this->_helper->layout->disableLayout();
		$this->_helper->getHelper('ajaxContext')->initContext();
		$url = $this->view->util()->getPath();
		if ($this->getRequest()->isXmlHttpRequest()) {
			$dni = trim($this->_request->getPost('dni'));
			$anionac = trim($this->_request->getPost('anio'));
			
			
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_asistencias.sp_obt_datos_emp';
			$psparam[] = array(':p_cod',$dni);
			$psparam[] = array(':p_cod_ddrr','');
			$psparam[] = array(':p_cod_depen','');
			$psparam[] = array(':p_cod_unid','');
			$datos = $cn->ejec_store_oracle_sisper($ns,$psparam);
			
			if(count($datos) == 0){
				echo 'DNI no existe en la base de datos.';
			}else{
//				echo $datos[0][1] . '---->' . substr($datos[0][9],0,4) ;
				if($anionac == substr($datos[0][9],0,4) ){
					$dd = new Zend_Session_Namespace('dat_asis_emp');				
					$dd->dni  = $datos[0][1];	
					$dd->fechanac = $datos[0][9];
					$dd->nom = $datos[0][2];
					$dd->codemp = $datos[0][0];					
					$dd->depen = $datos[0][4];
					$dd->unid = $datos[0][5];
					
					$dd->codddrr = $datos[0][10];
					$dd->coddepen = $datos[0][11];
					$dd->codunid = $datos[0][12];
					
					echo '<script language=\"JavaScript\">';
					echo 'window.open(\''.$url.'index.php/asistencia/reporteasistenciaemp\', \'_self\');';
					echo '</script>';
					
				}else{
					echo 'La fecha de Nacimiento no coincide con el DNI';
				}
			}
				
		}
	}
	
	public function logoutAction() {
		$url = $this->view->util()->getPath();
		Zend_Session::destroy();
		$this->_redirect($url.'index.php/asistencia/reporteasistenciaemplogin');
	}
	
	public function reporteasistenciaempAction() {   
		
		$this->view->inlineScript()->appendScript('$("button").button();');
		echo $this->view->inlineScript();
		
		$cn = new Model_DataAdapter();		
		$func = new Libreria_Pintar();
		
			$dd = new Zend_Session_Namespace('dat_asis_emp');				
			$dni = $dd->dni ;	
			$fecnac = $dd->fechanac; 
			$nom = $dd->nom;
			$codemp = $dd->codemp;
			$unid = $dd->unid;
			$codddrr = $dd->codddrr ;
			$coddepen = $dd->coddepen ;
			$codunid = $dd->codunid ;
												
			$psparam = array();
			$ns = 'pkg_asistencias.sp_obt_emp_report_no_asist';
			$psparam[] = array(':p_cod_emp',$codemp);
			$psparam[] = array(':p_fec_ini','01-01-'.date("Y"));
			$psparam[] = array(':p_fec_fin','31-12-'.date("Y"));
			$psparam[] = array(':p_cod_ddrr',$codddrr);
			$psparam[] = array(':p_cod_depen',$coddepen);
			$psparam[] = array(':p_cod_unid',$codunid);
			$datos = $cn->ejec_store_oracle_sisper($ns,$psparam);

			$date = date('d-m-Y');
			$olddate = strtotime ( '-1 month' , strtotime ( $date ) ) ;
			$olddate = date ( '01-'.'m-Y', $olddate );
						
			$val[] = array('txt_emp',$nom.'<br><br>('.$unid.')','html');
			$val[] = array('div_reporte_asistencia_pdf','<iframe height="100%" width="100%" src="http://172.25.0.13:8080/senamhi_report/?s=SISPER&r=asistenciaempleado&p=p_cod_emp$'.$codemp.'|p_fecha_ini$'.$olddate.'|p_fecha_fin$'.$date.'"></iframe>','html');
			$func->PintarValor($val);
//			
			$evt[] = array('btn_salir','click','salir_reporte_emp();');
			$func->PintarEvento($evt);
				
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
											for($i=0;$i<count($datos);$i++) echo $datos[$i][5] . ',';
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
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][3].' / '.$datos[$i][4].'"'. ',';
				 ECHO                 	  '],
			                    line: {visible: false}
			                },
			                tooltip: {visible: true,template: "#= series.name #: #= value #"}
			            });	        
			    </script>';
				 
				 /*********************************************************************************************/
				 
				 echo '<script>	        
			            $("#chart_faltos").kendoChart({
			                title: { text: "DIAS FALTOS" },
			                legend: { position: "top" },
			                series: [
				                {
				                	type: "column",
				                	labels: {visible: true,border: {width: 2,color: "black"}},
				                    name: "DIAS FALTOS POR MES",
				                    data: [';
											for($i=0;$i<count($datos);$i++) echo $datos[$i][13] . ',';
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 315},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][3].' / '.$datos[$i][4].'"'. ',';
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
											for($i=0;$i<count($datos);$i++) echo $datos[$i][6] . ',';
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 315},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][3].' / '.$datos[$i][4].'"'. ',';				                    		
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
												$acum = $acum + $datos[$i][6];
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
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][3].' / '.$datos[$i][4].'"'. ',';				                    		
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
											for($i=0;$i<count($datos);$i++) echo $datos[$i][7] . ',';
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][3].' / '.$datos[$i][4].'"'. ',';
				                    		
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
											for($i=0;$i<count($datos);$i++) echo $datos[$i][8] . ',';			                    		
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][3].' / '.$datos[$i][4].'"'. ',';
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
											for($i=0;$i<count($datos);$i++) echo $datos[$i][9] . ',';
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][3].' / '.$datos[$i][4].'"'. ',';				                    		
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
											for($i=0;$i<count($datos);$i++) echo $datos[$i][10] . ',';				                    		
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][3].' / '.$datos[$i][4].'"'. ',';				                    		
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
											for($i=0;$i<count($datos);$i++) echo $datos[$i][11] . ',';
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][3].' / '.$datos[$i][4].'"'. ',';				                    		
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
											for($i=0;$i<count($datos);$i++) echo $datos[$i][12] . ',';				                    		
				 ECHO                 	  ']
				                }
			                ],
			                valueAxis: {line: {visible: false},axisCrossingValue: 0},
			                categoryAxis: {
			                	labels: {rotation: 270},
			                    categories: [';
											for($i=0;$i<count($datos);$i++) echo '"'.$datos[$i][3].' / '.$datos[$i][4].'"'. ',';				                    		
				 ECHO                 	  '],
			                    line: {visible: false}
			                },
			                tooltip: {visible: true,template: "#= series.name #: #= value #"}
			            });	        
			    </script>';
			
	}

}

