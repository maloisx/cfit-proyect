<?php

/**
 * EstacionesController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class EstacionesController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	
public function init() {
		$this->view->util()->registerScriptJSController($this->getRequest());
	}
	
	public function indexAction() {
		// TODO Auto-generated EstacionesController::indexAction() default action
	}
	
	public function estacionesautomaticasretrasoAction() {
		$cn =  new Model_DataAdapter();
		$datos_oracle = $cn->ejec_store_oracle_sismethaweb('pkg_senamhi.sp_obt_esta_auto_retraso',null);

		$url_content = file_get_contents('http://172.25.0.48:8080/siapmicros/public/index.php/index/retrasoestaciones');
		$datos_sql = json_decode($url_content);
		
		
		
//        $datos = array();        
//        for($i = 0 ; $i <count($datos_sql) ; $i++){
//        	$codsenamhi = $datos_sql[$i]->IDSenamhi;
//        	$nombre_esta = $datos_sql[$i]->StzNome;
//        	$fecha = $datos_sql[$i]->fecha;
//        	$hora_max = $datos_sql[$i]->hora_max;
//        	$fecha_server_oracle = '';
//        	$hora_max_oracle = '';
//        	
//        	for($j=0;$j<count($datos_oracle);$j++){
//        		if($codsenamhi == $datos_oracle[$j][0]){
//        			$fecha_server_oracle = $datos_oracle[$j][2];
//        			$hora_max_oracle = substr($datos_oracle[$j][3],0,5);
//        			break;
//        		}
//        	}
//        	$datos[] = array($codsenamhi , $nombre_esta,$fecha,$hora_max,$fecha_server_oracle,$hora_max_oracle);
//        }

		$datos = array();
        for($i = 0 ; $i <count($datos_sql) ; $i++){
        	$codsenamhi = $datos_sql[$i]->IDSenamhi;
//        		try {
//        			$nombre_esta = utf8_decode ($datos_sql[$i]->StzNome); 
//        		} catch (Exception $e) {
//        			$nombre_esta = "";
//				    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
//				}
        	$fecha = $datos_sql[$i]->fecha;
        	$hora_max = $datos_sql[$i]->hora_max;
        	$fecha_server_oracle = '';
        	$hora_max_oracle = '';
        	$hora_llegada = $datos_sql[$i]->offset;
        	$tipo = $datos_sql[$i]->tipo;
        	$estado = '';
			$nombre_esta = '';
        	for($j=0;$j<count($datos_oracle);$j++){
        		if($codsenamhi == $datos_oracle[$j][0]){
        			$nombre_esta = $datos_oracle[$j][1];
        			$fecha_server_oracle = $datos_oracle[$j][2];
        			$hora_max_oracle = (($datos_oracle[$j][3]==false)?'':substr($datos_oracle[$j][3],0,5)) ;
        			$estado = $datos_oracle[$j][4];
        			break;
        		}
        	}
        	
        	$datos[] = array($codsenamhi , $nombre_esta,$fecha,$hora_max,$fecha_server_oracle,$hora_max_oracle,$hora_llegada,$tipo/*,$estado*/);
        }
        
		echo '<div align="center"><div style="width:700px"> <table id="tbl" border="1" cellpadding="0" cellspacing="0"></table></div></div>';

			$datatable_opciones[] = array('bFilter','true');
			$datatable_opciones[] = array('bLengthChange','false');
			$datatable_opciones[] = array('bInfo','true');
			$datatable_opciones[] = array('bPaginate','true');		
			$datatable_opciones[] = array('sPaginationType','"full_numbers"');
			$datatable_opciones[] = array('iDisplayLength',count($datos));
			$datatable_opciones[] = array('bJQueryUI','true');
			$datatable_opciones[] = array('bScrollCollapse','true');
			$datatable_opciones[] = array('aaData',json_encode($datos));
			$datatable_opciones[] = array('aaSorting',json_encode(array(array(2,'desc'),array(3,'desc'))));
			$datatable_opciones[] = array('aoColumns',json_encode(array(array('sTitle'=>'cod estacion','sWidth'=>'70px')			                                                                            
			                                                                            ,array('sTitle'=>'Estacion','sWidth'=>'250px')
			                                                                            ,array('sTitle'=>'SIAP<br>Fecha','sWidth'=>'80px')
			                                                                            ,array('sTitle'=>'SIAP<br>Ultima Hora','sWidth'=>'60px')
			                                                                            ,array('sTitle'=>'ORACLE<br>Fecha','sWidth'=>'80px')
			                                                                            ,array('sTitle'=>'ORACLE<br>Ultima Hora','sWidth'=>'60px')
			                                                                            ,array('sTitle'=>'Horas de<br>Transmision','sWidth'=>'50px')
																						,array('sTitle'=>'TIPO','sWidth'=>'50px')
																						//,array('sTitle'=>'Estado','sWidth'=>'50px')
			                                                                            )
			                                                                        ));
			$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){ 
															 if( $("td:eq(4)", nRow).html() + $("td:eq(5)", nRow).html() != ""  ){
																	if($("td:eq(2)", nRow).html() + $("td:eq(3)", nRow).html() != $("td:eq(4)", nRow).html() + $("td:eq(5)", nRow).html()  )
																	{$("td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5)", nRow).addClass( "ui-state-highlight" );} 
															}
															}');
				                                                                        
			echo $this->view->util()->datatable("tbl",$datatable_opciones);
	}
	
public function index2Action() {
		
		$this->_helper->viewRenderer->setNoRender(); 
		$this->_helper->layout->disableLayout();
		
		//$fec = '201501'
		$ch = curl_init ('http://172.25.0.48:8080/siapmicros/public/index.php/index/retrasoestaciones') ;
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
        $res = curl_exec ($ch) ;
        curl_close ($ch) ;
        $array = json_decode($res);
		print_r($array);	
//		for($i=0;$i<count($array);$i++){			
//			echo $array[$i]->fecha 
//		    . ' -> ' . $array[$i]->h00
//			. ' -> ' . $array[$i]->h01
//			. ' -> ' . $array[$i]->h02 . '<br>';
//		}
	}
	
	//------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------------------------

	public function estacionesautomaticasretraso3Action() {
		$cn =  new Model_DataAdapter();
		$datos_oracle = $cn->ejec_store_oracle_sismethaweb('pkg_senamhi.sp_obt_esta_auto_retraso',null);

		$func = new Libreria_Pintar();
		
		
		$datos_sql = $cn->ejec_store_procedura_sql('obt_retraso_estaciones',null);	
//		print_r($datos_sql);
//		$url_content = file_get_contents('http://172.25.0.48:8080/siapmicros/public/index.php/index/retrasoestaciones');
//		$datos_sql = json_decode($datos_sql);
		
		
		
		$cad_exec = '';
		
		$datos = array();
        for($i = 0 ; $i <count($datos_sql) ; $i++){
//        	print_r($datos_sql[$i][0]);
        	$codsenamhi = $datos_sql[$i][0];
        		try {
        			$nombre_esta = utf8_decode ($datos_sql[$i][1]);
        		} catch (Exception $e) {
        			$nombre_esta = "";
				    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
				}
        	$fecha = $datos_sql[$i][2];
        	$hora_max = $datos_sql[$i][3];
        	$fecha_server_oracle = '';
        	$hora_max_oracle = '';
        	$hora_llegada = $datos_sql[$i][4];
        	$tipo = $datos_sql[$i][5];
        	$estado = '';
//        	echo $codsenamhi . '->' . $nombre_esta . '<br>';

//        	$cad_exec .= "ultima_hora_oracle('".$codsenamhi."');";

//        	$psest[0] = array(':p_codesta',$codsenamhi);
//        	$datos_oracle = $cn->ejec_store_oracle_sismethaweb('pkg_senamhi.sp_obt_esta_auto_retraso_test',$psest);
//        	if(count($datos_oracle)>0){
//	        	$fecha_server_oracle = $datos_oracle[0][2];
//	        	$hora_max_oracle = substr($datos_oracle[0][3],0,5);
//        	}

        	for($j=0;$j<count($datos_oracle);$j++){
        		if($codsenamhi == $datos_oracle[$j][0]){
        			$fecha_server_oracle = $datos_oracle[$j][2];
        			$hora_max_oracle = substr($datos_oracle[$j][3],0,5);
        			$estado = $datos_oracle[$j][4];
        			break;
        		}
        	}
        	
        	$datos[] = array($codsenamhi , $nombre_esta,$fecha,$hora_max,$fecha_server_oracle,$hora_max_oracle,$hora_llegada,$tipo,$estado);
        }
        
        
		echo '<div align="center"><div style="width:700px"> <table id="tbl" border="1" cellpadding="0" cellspacing="0"></table></div></div>';

			$datatable_opciones[] = array('bFilter','true');
			$datatable_opciones[] = array('bLengthChange','false');
			$datatable_opciones[] = array('bInfo','true');
			$datatable_opciones[] = array('bPaginate','true');		
			$datatable_opciones[] = array('sPaginationType','"full_numbers"');
			$datatable_opciones[] = array('iDisplayLength',count($datos));
			$datatable_opciones[] = array('bJQueryUI','true');
			$datatable_opciones[] = array('bScrollCollapse','true');
			$datatable_opciones[] = array('aaData',json_encode($datos));
			$datatable_opciones[] = array('aaSorting',json_encode(array(array(2,'desc'),array(3,'desc'))));
			$datatable_opciones[] = array('aoColumns',json_encode(array(array('sTitle'=>'cod estacion','sWidth'=>'70px')			                                                                            
			                                                                            ,array('sTitle'=>'Estacion','sWidth'=>'250px')
			                                                                            ,array('sTitle'=>'SIAP<br>Fecha','sWidth'=>'80px')
			                                                                            ,array('sTitle'=>'SIAP<br>Ultima Hora','sWidth'=>'60px')
			                                                                            ,array('sTitle'=>'ORACLE<br>Fecha','sWidth'=>'80px')
			                                                                            ,array('sTitle'=>'ORACLE<br>Ultima Hora','sWidth'=>'60px')
			                                                                            ,array('sTitle'=>'Horas de<br>Transmision','sWidth'=>'50px')
																						,array('sTitle'=>'TIPO','sWidth'=>'50px')
																						,array('sTitle'=>'Estado','sWidth'=>'50px')
			                                                                            )
			                                                                        ));
			$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){ 
															 if( $("td:eq(4)", nRow).html() + $("td:eq(5)", nRow).html() != ""  ){
																	if($("td:eq(2)", nRow).html() + $("td:eq(3)", nRow).html() != $("td:eq(4)", nRow).html() + $("td:eq(5)", nRow).html()  )
																	{$("td:eq(0),td:eq(1),td:eq(2),td:eq(3),td:eq(4),td:eq(5)", nRow).addClass( "ui-state-highlight" );} 
															}
															}');
				                                                                        
			echo $this->view->util()->datatable("tbl",$datatable_opciones);
//			echo $cad_exec;
//			$func->EjecutarFuncion(array(array($cad_exec)));
	}
	//------------------------------------------------------------------------------------------------------------------------------------
	
	public function estacionesautomaticasretrasomapAction() {
		
		header("Refresh: 300;");
		
		$url = $this->view->util()->getPath();				
		$this->view->inlineScript()->appendScript("retraso_pintarmapas();");		
		
		$func =  new Libreria_Pintar();
		$leyanda = "LEYENDA: "
		          .'Retraso menor a 2 horas <img src="'.$url.'/img/localizacion_verde.png" > '
		          .'Retraso entre 2 a 3 horas <img src="'.$url.'/img/localizacion_amarillo.png" > '
		          .'Retraso entre 3 a 48 horas <img src="'.$url.'/img/localizacion_naranja.png" > '
		          .'Retraso mayor a 48 horas <img src="'.$url.'/img/localizacion_rojo.png" > ';
		$val[] = array('div_leyenda',$leyanda,'html');
		$func->PintarValor($val);
		
		$cn =  new Model_DataAdapter();
		$datos_oracle = $cn->ejec_store_oracle_sismethaweb('pkg_senamhi.sp_obt_esta_auto_retraso',null);

		$url_content = file_get_contents('http://172.25.0.48:8080/siapmicros/public/index.php/index/retrasoestaciones');
		$datos_sql = json_decode($url_content);
		
		$datos_map_oracle = array();
		$datos_map_siap = array();
		
		
	    for($i = 0 ; $i <count($datos_sql) ; $i++){
        	$codsenamhi = $datos_sql[$i]->IDSenamhi;
        	$fecha_sql = $datos_sql[$i]->fecha .' '. $datos_sql[$i]->hora_max;
        	$fecha_ora = '';
        	
        	$estado = '';
			$nombre_esta = '';
			$lat = '';
			$lng = '';
			
        	for($j=0;$j<count($datos_oracle);$j++){
        		if($codsenamhi == $datos_oracle[$j][0]){
        			$nombre_esta = $datos_oracle[$j][1];
        			$fecha_ora = trim($datos_oracle[$j][2] .' '. (($datos_oracle[$j][3]==false)?'':substr($datos_oracle[$j][3],0,5)) );
        			//$estado = $datos_oracle[$j][4];
        			$lat = $datos_oracle[$i][6];
					$lng = $datos_oracle[$i][5];
        			break;
        		}
        	}
        	
        	$e_map_s = array();
        	$e_map_o = array();
        	
        	  

			/*oracle*/
			if($fecha_ora != ''){
				$e_map_o['codestacion'] = $codsenamhi;
				$e_map_o['estacion'] = $nombre_esta;	
				$e_map_o['lat'] = $lat;
				$e_map_o['lng'] = $lng; 
				$datetime = strtotime($fecha_ora);	
				$datetime_hoy  = strtotime(date("Y-m-d H:i:s"));
				$diff= $datetime_hoy- $datetime;
				$min= intval($diff/60);
				$e_map_o['ret_min'] = $min ;				
				$e_map_o['ret'] = str_pad(floor($min/60), 2, "0", STR_PAD_LEFT) . ' horas con ' . str_pad(($min - floor($min/60)*60), 2, "0", STR_PAD_LEFT) .' min';
			}
			/*sql*/
				$e_map_s['codestacion'] =$codsenamhi;
				$e_map_s['estacion'] = $nombre_esta;	
				$e_map_s['lat'] = $lat;
				$e_map_s['lng'] = $lng;  
	        	$datetime = strtotime($fecha_sql);	
				$datetime_hoy  = strtotime(date("Y-m-d H:i:s"));
				$diff= $datetime_hoy- $datetime;
				$min= intval($diff/60);
				$e_map_s['ret_min'] = $min ;				
				$e_map_s['ret'] = str_pad(floor($min/60), 2, "0", STR_PAD_LEFT) . ' horas con ' . str_pad(($min - floor($min/60)*60), 2, "0", STR_PAD_LEFT) .' min';

				$datos_map_oracle[] = $e_map_o;
				$datos_map_siap[] = $e_map_s;
        }

		$this->view->inlineScript()->appendScript("pintarestaciones_retraso('oracle','".json_encode($datos_map_oracle)."');");
		$this->view->inlineScript()->appendScript("pintarestaciones_retraso('siap','".json_encode($datos_map_siap)."');");		

		echo $this->view->inlineScript();
		
	}
	
	
	//------------------------------------------------------------------------------------------------------------------------------------
	
	
	
}

