<?php

/**
 * xMonitoreoclimaticoController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class xMonitoreoclimaticoController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	
	public function init() {
		$this->view->util()->registerScriptJSController($this->getRequest());
	}
	
	public function indexAction() {

	}
	
	public function monitoreodatosAction() {
		
		$this->view->inlineScript()->appendScript('$("button").button();');
		$this->view->inlineScript()->appendScript("pintarmapa('c_mapa');");
		$this->view->inlineScript()->appendScript("pintararbolmonitoreo('','1');");		
		echo $this->view->inlineScript();		
	}
	
	public function pintararbolmonitoreoAction() {
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			$cn = new Model_DataAdapter();
			$jsonchecked = $this->_request->getPost( 'jsonchecked');
			$accion = $this->_request->getPost( 'accion');
			$nodos_checked = json_decode($jsonchecked);
			
			/**
			 * $accion
			 * 		1 = rececpion data
			 * 		2 = mantenimiento de data
			 */
			
			$ns = 'pkg_senamhi.sp_obt_dirreg';
			$psdirreg[] = array(':p_coddreg','');
			$datosregiones = $cn->ejec_store_procedura_oracle($ns,$psdirreg);
				
			$datosregiones_temp = array();		
			for($i = 0; $i<count($datosregiones);$i++){	
				$datosregiones_temp[$i]['tipo'] = 'D';
				$datosregiones_temp[$i]['codregion'] = $datosregiones[$i][0];
				$datosregiones_temp[$i]['region'] = '('.$datosregiones[$i][3].') ' .$datosregiones[$i][1];
			}
			$datosregiones = $datosregiones_temp;
			
			//estaciones
			$ns = 'pkg_senamhi.sp_obt_estaciones';
			$psest[] = array(':p_codest','');
			$psest[] = array(':p_coddreg','');
			$psest[] = array(':p_codenti','');
			$psest[] = array(':p_tipo','M');		
			$datosestaciones = $cn->ejec_store_procedura_oracle($ns,$psest);
			
			$datosestaciones_temp = array();		
			for($i = 0; $i<count($datosestaciones);$i++){	
				$datosestaciones_temp[$i]['tipo'] = 'E';
				$datosestaciones_temp[$i]['codestacion'] = $datosestaciones[$i][0];
				$datosestaciones_temp[$i]['estacion'] = $datosestaciones[$i][1];
				$datosestaciones_temp[$i]['codregion'] = $datosestaciones[$i][2];
				$datosestaciones_temp[$i]['region'] = $datosestaciones[$i][3];
				$datosestaciones_temp[$i]['lat'] = $datosestaciones[$i][7];
				$datosestaciones_temp[$i]['lng'] = $datosestaciones[$i][11];
				$datosestaciones_temp[$i]['alerta'] = '';
			}
			$datosestaciones = $datosestaciones_temp;
					
			//armado de arbolito
		
			$checked_root = false;
			$checked_dirreg = false;
			$checked_estacion = false;
			
			for($i = 0; $i<count($nodos_checked);$i++){
				if($nodos_checked[$i]->tipo == 'R')
					$checked_root = true;		
			}
			
			$arrayroot[0] = array ('text' => 'Regiones', 'id' => json_encode(array('tipo'=>'R')), 'depth' => '0', 'leaf' => false, 'checked' => $checked_root, 'children' => '' );
				
				//llenado de regiones
				for($i = 0; $i<count($datosregiones);$i++){	

					for($z = 0; $z<count($nodos_checked);$z++){
						if($nodos_checked[$z]->tipo == 'D'){
							if($nodos_checked[$z]->codregion == $datosregiones[$i]['codregion']){
							$checked_dirreg = true;		
							}
						}	
					}	
					$arrayroot[0]['children'][$i] = array ('text' => $datosregiones[$i]['region'], 'id' => json_encode($datosregiones[$i]), "expanded"=> $checked_dirreg , 'depth' => '1', 'leaf' => false, 'checked' => $checked_dirreg, 'children' => '' );
					
					$checked_dirreg = false;
					
					//llenado de estaciones por region
					for($j = 0; $j<count($datosestaciones);$j++){
						//si los codigos de las regiones son el mismo se añade como hijo
						if( $datosestaciones[$j]['codregion'] == $datosregiones[$i]['codregion'] ){			

							for($y = 0; $y<count($nodos_checked);$y++){
								if($nodos_checked[$y]->tipo == 'E'){
									if($nodos_checked[$y]->codestacion == $datosestaciones[$j]['codestacion']){
									$checked_estacion = true;		
									}
								}	
							}
							
							$arrayroot[0]['children'][$i]['children'][] = array ('text' => $datosestaciones[$j]['estacion'] . '(' .$datosestaciones[$j]['codestacion'].')', 'id' => json_encode($datosestaciones[$j]), 'depth' => '2', 'leaf' => true, 'checked' => $checked_estacion , 'children' => '' );
	//						$this->view->inlineScript()->appendScript("aniadirmarker('".$datosestaciones[$j]['codestacion']."','".$datosestaciones[$j]['estacion']."','".$datosestaciones[$j]['lat']."','".$datosestaciones[$j]['lng']."','".$datosestaciones[$j]['alerta']."')");
							$checked_estacion = false;
						}
					}
					
					if(count($arrayroot[0]['children'][$i]['children']) <= 1){
						$arrayroot[0]['children'][$i]['children'][] = array ('text' => 'Sin Estaciones Moviles.', 'id' => json_encode(array('tipo'=>'-')), 'depth' => '2', 'leaf' => true, 'checked' => false  );
					}
					
					
				}
		 		
			echo '<script type="text/javascript"><!--
					Ext.BLANK_IMAGE_URL = "' . $this->view->util()->getPath() . 'tree/images/s.gif";
					var tree;
					Ext.EventManager.onDocumentReady(function() {
						tree = new Ext.tree.TreePanel(\'tree-div\',{
							animate:true,
							loader: new Ext.tree.CustomUITreeLoader({baseAttr:{uiProvider: Ext.tree.CheckboxNodeUI}}),
							enableDD:false,
							collapsible : true,
					        animCollapse: true,
							containerScroll: true,
							rootUIProvider: Ext.tree.CheckboxNodeUI,
							selModel:new Ext.tree.CheckNodeMultiSelectionModel(),
							rootVisible:false
						});
						
						tree.on(\'check\', function() {
							var estaciones = this.getChecked().join(\',\');							
							'.(($accion == '1')?'pintarestaciones_monitoreo(\'[\'+estaciones+\']\',\''.$accion.'\'); pintartablaestaciones_monitoreo(\'[\'+estaciones+\']\');':'').'							
							'.(($accion == '2')?'mapas_criterio_fechas(\'[\'+tree.getChecked().join(\',\')+\']\');':'').'
						}, tree);
					
						// set the root node
						root = new Ext.tree.AsyncTreeNode({
									text: \'root\',
									draggable:false,
									id:\'source\',
									uiProvider: Ext.tree.CheckboxNodeUI,
									children: ' . json_encode ( $arrayroot ) . '	
							    });
												    
						tree.setRootNode(root);
					
						// render the tree
						tree.render();
						root.expand(false, false, function() {
							root.firstChild.expand(false);
						});
						
						Ext.get(\'cbcriterio\').on(\'change\', function() {mapas_criterio_fechas(\'[\'+tree.getChecked().join(\',\')+\']\');});
						
						repintartree_monitoreo = function() {
													pintararbolmonitoreo(\'[\'+tree.getChecked().join(\',\')+\']\',\''.$accion.'\');
												 };
												 
						pintar_mapas_criterio_fechas = function() {mapas_criterio_fechas(\'[\'+tree.getChecked().join(\',\')+\']\');};				 
						
					});				
					
					</script>										
					';
		}
	}
	
	public function monitoreodatostablaAction() {
	
	    $this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			$cn = new Model_DataAdapter();
			$func = new Libreria_Pintar();
			$json = $this->_request->getPost( 'json');
			
			$imgalerta = $this->view->util()->getPath().'img/alerta_amarilla.png';
			$imgok = $this->view->util()->getPath().'img/ok.png';
			$imgsindata = $this->view->util()->getPath().'img/minus.png';
			
			$array = json_decode($json);
			$arrayestaciones = array();
//			print_r($array);
			$xmlestaciones = '<d>';
			for($i=0;$i<count($array);$i++){
				if($array[$i]->tipo == 'E'){
					$arrayestaciones[] = array($array[$i]->codestacion,$array[$i]->estacion);
					$xmlestaciones .= '<r codestacion="'.$array[$i]->codestacion.'" ></r>';
				}
			}
			$xmlestaciones .= '</d>';
			
			if(count($arrayestaciones) > 0){
			

					//tabla datos
					////cabecera fechas y matriz principal
					$ns = 'pkg_senamhi.sp_obt_tablamonitoreo_fechas';
					$datosfechas = $cn->ejec_store_procedura_oracle($ns,null);
					$ns = 'pkg_senamhi.sp_obt_tablamonitoreo_HORAS';
					$datoshoras = $cn->ejec_store_procedura_oracle($ns,null);
					$tabladatosfechas = '<th class="ui-state-default" rowspan="2"  width="200px">Estaciones</th>';
					$tabladatoshoras = '';
					$tabladatosiconos = '';
					for($i=0;$i<count($datosfechas);$i++){
						$tabladatosfechas .= '<th class="ui-state-default" colspan="3" width="150px">&nbsp;&nbsp;'.$datosfechas[$i][0].'&nbsp;&nbsp;</th>';	
						for($j=0;$j<count($datoshoras);$j++){
							$tabladatoshoras .= '<th class="ui-state-default">&nbsp;'.$datoshoras[$j][0].'&nbsp;</th>';	
						}
					}
					for($h=0;$h<count($arrayestaciones);$h++){
						$tabladatosiconos .= '<tr height = "25px" valing="middle"><td  height = "25px">'.$arrayestaciones[$h][1].'</td>';
						for($i=0;$i<count($datosfechas);$i++){
							for($j=0;$j<count($datoshoras);$j++){
								$tabladatosiconos .= '<td  id="td_'.$arrayestaciones[$h][0].'_'.str_replace('/','',$datosfechas[$i][0]).'_'.$datoshoras[$j][0].'" align="center"><img src="'.$imgsindata.'" width="20px" height="20px"  onclick="monitoreodatosestacion_detalle(\''.$arrayestaciones[$h][0].'\',\'\',\'\',\''.$datosfechas[$i][0].'\',\''.$datoshoras[$j][0].':00:00'.'\',\'\')"></img></td>';	
							}
						}
						$tabladatosiconos .= '</tr>';
					}			
					$tabladatos = '<table border="1" cellpadding="0" cellspacing="0"  id="c_table_monitoriodatos_datostabla">';
					$tabladatos .= '<thead><tr>'.$tabladatosfechas.'</tr>';	
					$tabladatos .= '<tr>'.$tabladatoshoras.'</tr></thead>';	
					$tabladatos .= '<tbody>'.$tabladatosiconos.'</tbody>';		
					$tabladatos .= '</table>'; 
		
					//pintar iconos
					echo '-------->'.$xmlestaciones;
					$ns = 'pkg_senamhi.sp_obt_tablamonitoreo_datos';
					$psest[] = array(':p_xmlestaciones',$xmlestaciones);
					$datosmonitorio = $cn->ejec_store_procedura_oracle($ns,$psest);
					$val = array();
					for($i=0;$i<count($datosmonitorio);$i++){
						if( ($datosmonitorio[$i][3] - $datosmonitorio[$i][4] ) == 0 ){
							$val[] = array('td_'.$datosmonitorio[$i][0].'_'.str_replace('/','',$datosmonitorio[$i][1]).'_'.$datosmonitorio[$i][2],'<img src="'.$imgok.'" width="20px" height="20px" onclick="monitoreodatosestacion_detalle(\''.$datosmonitorio[$i][0].'\',\'\',\'\',\''.$datosmonitorio[$i][1].'\',\''.$datosmonitorio[$i][2].':00:00'.'\',\'\')"></img>','html');
						}if( ($datosmonitorio[$i][3] - $datosmonitorio[$i][4] ) > 0 ){
							$val[] = array('td_'.$datosmonitorio[$i][0].'_'.str_replace('/','',$datosmonitorio[$i][1]).'_'.$datosmonitorio[$i][2],'<img src="'.$imgalerta.'" width="20px" height="20px" onclick="monitoreodatosestacion_detalle(\''.$datosmonitorio[$i][0].'\',\'\',\'\',\''.$datosmonitorio[$i][1].'\',\''.$datosmonitorio[$i][2].':00:00'.'\',\'\')"></img>','html');
						}if(  $datosmonitorio[$i][4] == 0 ){
							$val[] = array('td_'.$datosmonitorio[$i][0].'_'.str_replace('/','',$datosmonitorio[$i][1]).'_'.$datosmonitorio[$i][2],'<img src="'.$imgsindata.'" width="20px" height="20px" onclick="monitoreodatosestacion_detalle(\''.$datosmonitorio[$i][0].'\',\'\',\'\',\''.$datosmonitorio[$i][1].'\',\''.$datosmonitorio[$i][2].':00:00'.'\',\'\')"></img>','html');
						}
					}
					
					echo $tabladatos;
					$func->PintarValor($val);
					
					$datatable_opciones[] = array('bFilter','false');
					$datatable_opciones[] = array('bLengthChange','false');
					$datatable_opciones[] = array('bInfo','false');
					$datatable_opciones[] = array('bPaginate','false');		
		//			$datatable_opciones[] = array('iDisplayLength',count($datoscriterios_temp));
					$datatable_opciones[] = array('bJQueryUI','true');
					$datatable_opciones[] = array('bScrollCollapse','true');
					$datatable_opciones[] = array('sScrollX','"100%"');
					$datatable_opciones[] = array('sScrollXInner','"150%"');
		//			$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(1)", nRow).attr( "align","center" );}');
					$datatable_opciones[] = array('bSorting','false');
		//			$datatable_opciones[] = array('aoColumnDefs',json_encode(array(array('bVisible'=>false ,'aTargets'=>array(0)))));
					echo $this->view->util()->datatable("c_table_monitoriodatos_datostabla",$datatable_opciones,true);
			}
			
		}
	}
	
	public function monitoreodatosestacionAction() {
	
//	    $this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			$func = new Libreria_Pintar();
			$cn = new Model_DataAdapter();
			$codestacion = $this->_request->getParam( 'codestacion','');
			
			$ns = 'pkg_senamhi.sp_obt_estaciones';
			$psest[] = array(':p_codest',$codestacion);
			$psest[] = array(':p_coddreg','');
			$psest[] = array(':p_codenti','');
			$psest[] = array(':p_tipo','M');		
			$datosestaciones = $cn->ejec_store_procedura_oracle($ns,$psest);
			
			$val[] = array('wi_googlemaps_estacion',$datosestaciones[0][1],'html');
			$val[] = array('wi_googlemaps_dirreg',$datosestaciones[0][3],'html');
			$val[] = array('wi_googlemaps_tipoestacion',$datosestaciones[0][16].'<br>('.$datosestaciones[0][18].')','html');			
			$val[] = array('wi_googlemaps_lat',$datosestaciones[0][4].'C&deg; '.$datosestaciones[0][5].'\' '.$datosestaciones[0][6].'\'\'','html');
			$val[] = array('wi_googlemaps_lng',$datosestaciones[0][8].'C&deg; '.$datosestaciones[0][9].'\' '.$datosestaciones[0][10].'\'\'','html');
			$val[] = array('wi_googlemaps_msnm',$datosestaciones[0][12],'html');
			$func->PintarValor($val);
			
			$ns = 'pkg_senamhi.sp_obt_cab_monitoriodatos';
			$psmonitoreoest[] = array(':p_codestacion',$codestacion);	
			$psmonitoreoest[] = array(':p_fecha','');
			$psmonitoreoest[] = array(':p_hora','');		
			$datomonitoreoest = $cn->ejec_store_procedura_oracle($ns,$psmonitoreoest);
			
			$array = array();
			for($i=0;$i<count($datomonitoreoest);$i++){
				$array[] = array($datomonitoreoest[$i][1],
				                 $datomonitoreoest[$i][2],
				                 $datomonitoreoest[$i][3],
				                 $datomonitoreoest[$i][4],
				                 $datomonitoreoest[$i][5],
				                 $datomonitoreoest[$i][6],
				                 $datomonitoreoest[$i][7],
				                 $datomonitoreoest[$i][8],
				                 '<ul><li disabled="disabled" class="ui-state-default ui-corner-all"  id="" onclick="monitoreodatosestacion_detalle(\''.$datomonitoreoest[$i][0].'\',\''.$datosestaciones[0][1].'\',\''.$datosestaciones[0][3].'\',\''.$datomonitoreoest[$i][1].'\',\''.$datomonitoreoest[$i][2].'\',\''.$datomonitoreoest[$i][3].'\')" style="width:20px;cursor: pointer; cursor: hand;"><span class="ui-icon ui-icon-folder-open"></span></li></ul>'
				                 );
			}
			
			$datatable_opciones[] = array('bFilter','false');
			$datatable_opciones[] = array('bLengthChange','false');
			$datatable_opciones[] = array('bInfo','false');
			$datatable_opciones[] = array('bPaginate','false');		
//			$datatable_opciones[] = array('iDisplayLength',count($datoscriterios_temp));
			$datatable_opciones[] = array('bJQueryUI','true');
			$datatable_opciones[] = array('bScrollCollapse','false');
			$datatable_opciones[] = array('sScrollY','"155px"');
			$datatable_opciones[] = array('aaData',json_encode($array));
			$datatable_opciones[] = array('aoColumns',json_encode(array(
																		array('sTitle'=>'Fecha')
																		,array('sTitle'=>'Hora<br>Prevista')
																		,array('sTitle'=>'Hora de<br>llegada')
																		,array('sTitle'=>'Diferencia<br>Horas')
																		,array('sTitle'=>'Datos<br>Esperados')
																		,array('sTitle'=>'Datos<br>Reales')
																		,array('sTitle'=>'Diferencia<br>Datos')
																		,array('sTitle'=>'%')
																		,array('sTitle'=>'-','sWidth'=>'20px'))));
//			$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(8)", nRow).attr( "align","center" );}');
			$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'desc'))));
			$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(7)", nRow).attr( "align","center" ); if (aData[4] == aData[6]){$("td", nRow).addClass("ui-state-error" );}else if (aData[6]>0){$("td", nRow).addClass("ui-state-highlight" );}}');
			$datatable_opciones[] = array('aoColumnDefs',json_encode(array(array('bVisible'=>false ,'aTargets'=>array(0)))));
			$datatable_opciones[] = array('fnDrawCallback','function(oSettings){if( oSettings.aiDisplay.length == 0 ){return;}var nTrs = $("#c_table_monitoreodataestacion_googlemaps tbody tr");var iColspan = nTrs[0].getElementsByTagName("td").length;var sLastGroup = "";for ( var i=0 ; i<nTrs.length ; i++ ){var iDisplayIndex = oSettings._iDisplayStart + i;var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];if ( sGroup != sLastGroup ){var nGroup = document.createElement( "tr" );var nCell = document.createElement( "td" );nCell.colSpan = iColspan;nCell.className = "ui-state-default";nCell.innerHTML = sGroup;nGroup.appendChild( nCell );nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );sLastGroup = sGroup;}}}');
			echo $this->view->util()->datatable("c_table_monitoreodataestacion_googlemaps",$datatable_opciones);
		}
		
	}
	
	public function monitoreodatosestaciondetalleAction() {
		$this->_helper->layout->disableLayout ();
		$func = new Libreria_Pintar();
		$cn = new Model_DataAdapter();
		
		$codestacion = $this->_request->getParam( 'codestacion','');
		$estacion = $this->_request->getParam( 'estacion','');
		$dirreg = $this->_request->getParam( 'dirreg','');
		$fecha = $this->_request->getParam( 'fecha','');
		$horaprevista = $this->_request->getParam( 'horaprevista','');
		$horallegada = $this->_request->getParam( 'horallegada','');
		
		if($dirreg == '' || $estacion == ''){
			$ns = 'pkg_senamhi.sp_obt_estaciones';
			$psest[] = array(':p_codest',$codestacion);
			$psest[] = array(':p_coddreg','');
			$psest[] = array(':p_codenti','');
			$psest[] = array(':p_tipo','M');		
			$datosestaciones = $cn->ejec_store_procedura_oracle($ns,$psest);
			$estacion = $datosestaciones[0][1];
			$dirreg = $datosestaciones[0][3];
		}
		
		if($horallegada == ''){
			$ns = 'pkg_senamhi.sp_obt_cab_monitoriodatos';
			$psmonitoreoest_cab[] = array(':p_codestacion',$codestacion);	
			$psmonitoreoest_cab[] = array(':p_fecha',$fecha);
			$psmonitoreoest_cab[] = array(':p_hora',$horaprevista);		
			$datomonitoreoest_cab = $cn->ejec_store_procedura_oracle($ns,$psmonitoreoest_cab);
			$horallegada = $datomonitoreoest_cab[0][3];
		}
		
		$val[] = array('wi_googlemaps_estacion_det',$estacion,'html');
		$val[] = array('wi_googlemaps_dirreg_det',$dirreg,'html');
		$val[] = array('wi_googlemaps_fecha_det',$fecha,'html');			
		$val[] = array('wi_googlemaps_horaprevista_det',$horaprevista,'html');
		$val[] = array('wi_googlemaps_horallegada_det',$horallegada,'html');
		$func->PintarValor($val);
		
		$evt[] = array('btn_cerrar','click','cerrarsubvent()');
		$evt[] = array('btn_xxx','click','repintartree_monitoreo()');		
		
		$func->PintarEvento($evt);
		$fn[] = array('$("button").button();');		
		$func->EjecutarFuncion($fn);
		
			$ns = 'pkg_senamhi.sp_obt_cab_monitoriodatos_det';
			$psmonitoreoest[] = array(':p_codestacion',$codestacion);
			$psmonitoreoest[] = array(':p_fecha',$fecha);
			$psmonitoreoest[] = array(':p_hora',$horaprevista);		
			$datomonitoreoest_det = $cn->ejec_store_procedura_oracle($ns,$psmonitoreoest);
			
			$array = array();
			for($i=0;$i<count($datomonitoreoest_det);$i++){
				$array[] = array($datomonitoreoest_det[$i][1] . ' - ' . $datomonitoreoest_det[$i][2],
				                 $datomonitoreoest_det[$i][3],
				                 $datomonitoreoest_det[$i][4],
				                 trim($datomonitoreoest_det[$i][5])
				                 );
			}
		
			$datatable_opciones[] = array('bFilter','false');
			$datatable_opciones[] = array('bLengthChange','false');
			$datatable_opciones[] = array('bInfo','false');
			$datatable_opciones[] = array('bPaginate','false');		
//			$datatable_opciones[] = array('iDisplayLength',count($datoscriterios_temp));
			$datatable_opciones[] = array('bJQueryUI','true');
			$datatable_opciones[] = array('bScrollCollapse','true');
			$datatable_opciones[] = array('aaData',json_encode($array));
			$datatable_opciones[] = array('aoColumns',json_encode(array(
																		array('sTitle'=>'Fecha','sClass'=>'center')
																		,array('sTitle'=>'Grupo')
																		,array('sTitle'=>'Parametro')
																		,array('sTitle'=>'Valor','sWidth'=>'60px','sClass'=>'right'))
																		)
																  );
//			$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(1)", nRow).attr( "align","center" );}');
			$datatable_opciones[] = array('aaSorting',json_encode(array(array(1,'asc'),array(2,'asc'))));
			$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){if (rtrim(aData[3]) == ""){$("td", nRow).addClass("ui-state-error" );}}');
			$datatable_opciones[] = array('aoColumnDefs',json_encode(array(array('bVisible'=>false ,'aTargets'=>array(0)))));
			$datatable_opciones[] = array('fnDrawCallback','function(oSettings){if( oSettings.aiDisplay.length == 0 ){return;}var nTrs = $("#c_table_monitoreodatosestaciondetalle_googlemaps tbody tr");var iColspan = nTrs[0].getElementsByTagName("td").length;var sLastGroup = "";for ( var i=0 ; i<nTrs.length ; i++ ){var iDisplayIndex = oSettings._iDisplayStart + i;var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];if ( sGroup != sLastGroup ){var nGroup = document.createElement( "tr" );var nCell = document.createElement( "td" );nCell.colSpan = iColspan;nCell.className = "ui-state-default";nCell.innerHTML = sGroup;nGroup.appendChild( nCell );nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );sLastGroup = sGroup;}}}');
			echo $this->view->util()->datatable("c_table_monitoreodatosestaciondetalle_googlemaps",$datatable_opciones);
		
	}
	
	public function listaestaciones0Action() {
		
		$cn = new Model_DataAdapter();		
		$ns = 'pkg_senamhi.sp_obt_estaciones';
		$psest[] = array(':p_codest','');
		$psest[] = array(':p_coddreg','');
		$psest[] = array(':p_codenti','');
		$psest[] = array(':p_tipo','M');
		$datos = $cn->ejec_store_procedura_oracle($ns,$psest);
//		print_r($datos);
		$array = array();
		for($i=0;$i<count($datos);$i++){
			$array[] = array($datos[$i][0],
			                 $datos[$i][1],
			                 $datos[$i][3],
			                 $datos[$i][4],
			                 $datos[$i][5],
			                 $datos[$i][6],
			                 $datos[$i][8],
			                 $datos[$i][9],
			                 $datos[$i][10],
			                 $datos[$i][16].'<br>('.$datos[$i][18].')',
			                 '',//'<ul><li disabled="disabled" class="ui-state-default ui-corner-all"  id="" onclick="mantenimientoestacion(\''.$datos[$i][0].'\')" style="width:20px;cursor: pointer; cursor: hand;"><span class="ui-icon ui-icon-gear"></span></li></ul>',
			                 (($datos[$i][19]=='1')?'<ul><li disabled="disabled" class="ui-state-default ui-corner-all"  id="" onclick="tablaestacioncriteriovalor_ventana(\''.$datos[$i][0].'\')" style="width:20px;cursor: pointer; cursor: hand;"><span class="ui-icon ui-icon-document""></span></li></ul>':'')
			                 );
		}
		
		
		$func = new Libreria_Pintar();
		$fn[] = array('$("button").button();');		
		$func->EjecutarFuncion($fn);
		$evt[] = array('btn_enviarmail','click',"enviarmailmatrizvariables('');");					
		$func->PintarEvento($evt);

		//tablas
		$datatable_opciones[] = array('bFilter','true');
		$datatable_opciones[] = array('bLengthChange','true');
		$datatable_opciones[] = array('bInfo','true');
		$datatable_opciones[] = array('bPaginate','true');		
		$datatable_opciones[] = array('sPaginationType','"full_numbers"');
//		$datatable_opciones[] = array('iDisplayLength',count($array));
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode($array));
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(1,'asc'))));
		$datatable_opciones[] = array('aoColumnDefs',json_encode(array(array('sClass'=>'center' ,'aTargets'=>array(0,3,4,5,6,7,8)))));
		$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(10),td:eq(11)", nRow).attr( "align","center" );}');
		echo $this->view->util()->datatable("c_tabla_estaciones",$datatable_opciones);
	}
	
	public function mantenimientoestacionAction() {
		
		$this->_helper->layout->disableLayout();
		$codestacion = $this->_request->getParam('codestacion','');
		$sololectura = $this->_request->getParam('sololectura','');
		
		$func = new Libreria_Pintar();
		$fn[] = array('$("button").button();');		
		
		$cn = new Model_DataAdapter();
			
		$ns = 'pkg_senamhi.sp_obt_dirreg';
		$psdirreg[] = array(':p_coddreg','');
		$datosregiones = $cn->ejec_store_procedura_oracle($ns,$psdirreg);
		
		if($codestacion != ''){			
			$ns = 'pkg_senamhi.sp_obt_estaciones';
			$psest[] = array(':p_codest',$codestacion);
			$psest[] = array(':p_coddreg','');
			$psest[] = array(':p_codenti','');
			$psest[] = array(':p_tipo','');
			$datosestacion = $cn->ejec_store_procedura_oracle($ns,$psest);			
				print_r($datosestacion);
			$val[] = array('txtcodestacion',$datosestacion[0][0],'html');	
			$val[] = array('txtestacion',$datosestacion[0][1],'val');
			$val[] = array('cbdirreg',$func->ContenidoCombo($datosregiones,$datosestacion[0][2]),'html');
			$val[] = array('txtlathora',$datosestacion[0][4],'val');
			$val[] = array('txtlatmin',$datosestacion[0][5],'val');
			$val[] = array('txtlatseg',$datosestacion[0][6],'val');
			$val[] = array('txtlnghora',$datosestacion[0][8],'val');
			$val[] = array('txtlngmin',$datosestacion[0][9],'val');
			$val[] = array('txtlngseg',$datosestacion[0][10],'val');
			$val[] = array('txtmsnm',$datosestacion[0][12],'val');
			
			$fn[] = array('$(".number").kendoNumericTextBox({format: "#.##",decimals: 2});');
			
		}else{
			$val[] = array('cbdirreg',$func->ContenidoCombo($datosregiones,null),'html');
		}
				
		$func->PintarValor($val);
		$func->EjecutarFuncion($fn);
//		$evt[] = array('btn_guardar','click','guardarestacion()');
		$evt[] = array('btn_cerrar','click','cerrarsubvent()');	
		
		if($sololectura != ''){
//			$evt[] = array('btn_guardar','hide','');
//		    $evt[] = array('btn_cerrar','hide','');				
//			$sl[] = array('txtcodproyecto',true);
//			$sl[] = array('txtproyecto',true);
//			$sl[] = array('txtdescripcion',true);
//			$sl[] = array('txttema',true);					
//			$func->ComponenteSoloLectura($sl);
//			$hab[] = array('cbmetodologia',false);
//			$hab[] = array('cbestado',false);
//			$func->HabilitarComponente($hab);
		}
		$func->PintarEvento($evt);
		
	}
	
	
	public function alertasAction() {
		
		$cn = new Model_DataAdapter();
		
		$this->view->inlineScript()->appendScript('$("button").button();');
		$this->view->inlineScript()->appendScript('pintarmapa();');
//		$this->view->inlineScript()->appendScript('rangosfechas();');		
		
		//datos regiones //bd
//		$datosregiones[] = array('codregion01','Reg_01');
//		$datosregiones[] = array('codregion02','Reg_02');
//		$datosregiones[] = array('codregion03','Reg_03');
//		$datosregiones[] = array('codregion04','Reg_04');		
		$ns = 'pkg_senamhi.sp_obt_dirreg';
		$ps[] = array(':p_coddreg','');
		$datosregiones = $cn->ejec_store_procedura_oracle($ns,$ps);
			
		$datosregiones_temp = array();		
		for($i = 0; $i<count($datosregiones);$i++){	
			$datosregiones_temp[$i]['tipo'] = 'R';
			$datosregiones_temp[$i]['codregion'] = $datosregiones[$i][0];
			$datosregiones_temp[$i]['region'] = '('.$datosregiones[$i][3].') ' .$datosregiones[$i][1];
		}
		$datosregiones = $datosregiones_temp;
		
		//estaciones //bd
		/*
		 * codigo estacion
		 * nombre estacion
		 * codregion
		 * region
		 * latitud
		 * longitud
		 * */
		$datosestaciones[] = array('codest00001','Estacion_00001','codregion01','Reg_01','-9.03389','-75.50833','');
		$datosestaciones[] = array('codest00002','Estacion_00002','codregion01','Reg_01','-8.92972','-76.11167','');
		$datosestaciones[] = array('codest00003','Estacion_00003','codregion01','Reg_01','-14.86694','-70.58361','');
		$datosestaciones[] = array('codest00004','Estacion_00004','codregion02','Reg_02','-7.05083','-76.55917','');
		$datosestaciones[] = array('codest00005','Estacion_00005','codregion02','Reg_02','-3.91167','-70.51222','');
		$datosestaciones[] = array('codest00006','Estacion_00006','codregion03','Reg_03','-10.46694','-76.98361','');
		$datosestaciones[] = array('codest00007','Estacion_00007','codregion03','Reg_03','-12.35028','-75.86694','');
		$datosestaciones[] = array('codest00008','Estacion_00008','codregion03','Reg_03','-10.69444','-76.25417','');
		$datosestaciones[] = array('codest00009','Estacion_00009','codregion04','Reg_04','-16.81306','-70.69444','');
		$datosestaciones[] = array('codest00010','Estacion_00010','codregion04','Reg_04','-15.20333','-69.7625','');
		
		$datosestaciones_temp = array();		
		for($i = 0; $i<count($datosestaciones);$i++){	
			$datosestaciones_temp[$i]['tipo'] = 'E';
			$datosestaciones_temp[$i]['codestacion'] = $datosestaciones[$i][0];
			$datosestaciones_temp[$i]['estacion'] = $datosestaciones[$i][1];
			$datosestaciones_temp[$i]['codregion'] = $datosestaciones[$i][2];
			$datosestaciones_temp[$i]['region'] = $datosestaciones[$i][3];
			$datosestaciones_temp[$i]['lat'] = $datosestaciones[$i][4];
			$datosestaciones_temp[$i]['lng'] = $datosestaciones[$i][5];
			$datosestaciones_temp[$i]['alerta'] = trim($datosestaciones[$i][6]);
		}
		$datosestaciones = $datosestaciones_temp;
		
//		//criterios
//		$datoscriterios[] = array('codcriterio001','TEMPERATURA M&Aacute;XIMA','-31.2','45.0','codunid01','Grados Celsius','C&deg;');
//		$datoscriterios[] = array('codcriterio002','TEMPERATURA M&Iacute;NIMA','-31.2','45.0','codunid02','Grados Celsius','C&deg;');
//		$datoscriterios[] = array('codcriterio003','TEMPERATURA BULBO SECO','-31.2','45.0','codunid03','Grados Celsius','C&deg;');
//		$datoscriterios[] = array('codcriterio004','TEMPERATURA BULBO H&Uacute;MEDO','-31.2','45.0','codunid04','Grados Celsius','C&deg;');
//		$datoscriterios[] = array('codcriterio005','PRECIPITACI&Oacute;N','0.0','500.0','codunid05','milimetros','mm');
//		$datoscriterios[] = array('codcriterio006','VISIBILIDAD','0.0','60.0','codunid06','Kilometros','km');
//		$datoscriterios[] = array('codcriterio007','VELOCIDAD DE VIENTO','0.0','36.0','codunid07','Metros sobre segundos','m/s');
//		
//		$datoscriterios_temp = array();		
//		for($i = 0; $i<count($datoscriterios);$i++){	
//			$datoscriterios_temp[] = array('<input type="checkbox" checked="checked" class="checkbox_criteriosmonitoreo" id="check_'.$datoscriterios[$i][0].'" codcriterio="'.$datoscriterios[$i][0].'" />'
//											 ,$datoscriterios[$i][1]
//											 ,$datoscriterios[$i][6]		                 
//							                 );
//		}

		
		//armado de arbolito
		$arrayroot[0] = array ('text' => 'Regiones', 'id' => json_encode(array('tipo'=>'-')), 'depth' => '0', 'leaf' => false, 'checked' => true, 'children' => '' );
			
			//llenado de regiones
			for($i = 0; $i<count($datosregiones);$i++){				
				$arrayroot[0]['children'][$i] = array ('text' => $datosregiones[$i]['region'], 'id' => json_encode($datosregiones[$i]), 'depth' => '1', 'leaf' => false, 'checked' => true, 'children' => '' );
				
				//llenado de estaciones por region
				for($j = 0; $j<count($datosestaciones);$j++){
					//si los codigos de las regiones son el mismo se añade como hijo
					if( $datosestaciones[$j]['codregion'] == $datosregiones[$i]['codregion'] ){		
						
						$icon = '';
						if($datosestaciones[$j]['alerta'] != '')
							$icon = $this->view->util()->getPath()."img/alerta_roja.png";						
							$arrayroot[0]['children'][$i]['children'][] = array ('text' => $datosestaciones[$j]['estacion'] . '(' .$datosestaciones[$j]['codestacion'].')', 'id' => json_encode($datosestaciones[$j]), 'depth' => '2', 'leaf' => true, 'checked' => true , 'icon'=> $icon  , 'children' => '' );
							$this->view->inlineScript()->appendScript("aniadirmarker('".$datosestaciones[$j]['codestacion']."','".$datosestaciones[$j]['estacion']."','".$datosestaciones[$j]['lat']."','".$datosestaciones[$j]['lng']."','".$datosestaciones[$j]['alerta']."')");
					}
				}
				
				if(count($arrayroot[0]['children'][$i]['children']) <= 1){
					$arrayroot[0]['children'][$i]['children'][] = array ('text' => 'Sin Estaciones Moviles.', 'id' => '[]', 'depth' => '2', 'leaf' => true, 'checked' => true  );
				}
			}

		echo $this->view->inlineScript();
 		
		echo '<script type="text/javascript"><!--
				Ext.BLANK_IMAGE_URL = "' . $this->view->util()->getPath() . 'tree/images/s.gif";
				var tree;
				Ext.EventManager.onDocumentReady(function() {
					tree = new Ext.tree.TreePanel(\'tree-div\',{
						animate:true,
						loader: new Ext.tree.CustomUITreeLoader({baseAttr:{uiProvider: Ext.tree.CheckboxNodeUI}}),
						enableDD:false,
						collapsible : true,
				        animCollapse: true,
						containerScroll: true,
						rootUIProvider: Ext.tree.CheckboxNodeUI,
						selModel:new Ext.tree.CheckNodeMultiSelectionModel(),
						rootVisible:false
					});
					
					tree.on(\'check\', function() {
						var estaciones = this.getChecked().join(\',\');
						pintarestaciones(\'[\'+estaciones+\']\');
						
					}, tree);
				
					// set the root node
					root = new Ext.tree.AsyncTreeNode({
								text: \'root\',
								draggable:false,
								id:\'source\',
								uiProvider: Ext.tree.CheckboxNodeUI,
								children: ' . json_encode ( $arrayroot ) . '	
						    });
											    
					tree.setRootNode(root);
				
					// render the tree
					tree.render();
					root.expand(false, false, function() {
						root.firstChild.expand(true);
					});
					
//					Ext.get(\'btn_generargrafico\').on(\'click\', function() {	
//																				obtdatosgraficomonitoreo(\'[\'+tree.getChecked().join(\',\')+\']\');
//																			 });
																		
					Ext.get(\'buscar_tree\').on(\'keyup\', function() {	
																		var txt = $(\'#buscar_tree\').val();
																		var removeArray = [];
	                    												var i=0;
	                    												$(".l-tcb").each(function(index) {$(this).attr("checked",true);});
																		tree.getRootNode().cascade(function() { // descends into child nodes
																			if(this.isLeaf()){
																			   this.getUI().show();	
																			   this.getUI().toggleCheck();
																			   this.getUI().toggleCheck();																		   
																			   if(!(this.text.indexOf(txt)>-1)) {//Now do the actual filtering
																					removeArray[i] = this;//cannot call this.remove() here as cascadeBy() tens to traverse all the nodes and if node is removed it gives error
																					i++;
																			   }
																			}																			   
																		});
																																				
																		for(var j=0;j<i;j++) {				
																		    removeArray[j].getUI().toggleCheck();
																			removeArray[j].getUI().hide();
																		}																			
																	  });																			
					
				});				
				
				</script>										
				';
		
		
//		//tablas de criterios
//		$datatable_opciones[] = array('bFilter','false');
//		$datatable_opciones[] = array('bLengthChange','false');
//		$datatable_opciones[] = array('bInfo','false');
//		$datatable_opciones[] = array('bPaginate','false');		
//		$datatable_opciones[] = array('iDisplayLength',count($datoscriterios_temp));
//		$datatable_opciones[] = array('bJQueryUI','true');
//		$datatable_opciones[] = array('bScrollCollapse','true');
//		$datatable_opciones[] = array('aaData',json_encode($datoscriterios_temp));
//		$datatable_opciones[] = array('aaSorting',json_encode(array(array(1,'asc'))));
//		$datatable_opciones[] = array('aoColumnDefs',json_encode(array(array('sClass'=>'center' ,'aTargets'=>array(0,2)))));
//		echo $this->view->util()->datatable("c_table_criteriosmonitoreo",$datatable_opciones);
		
				
		
		//graficas estadisticas		
		$g1_normal = array(
						   array('2012-12-01',15.04030230586814)
		                  ,array('2012-12-02',14.083853163452858)
		                  ,array('2012-12-03',13.510007503399555)
		                  ,array('2012-12-04',13.846356379136388)
		                  ,array('2012-12-05',14.783662185463227)
		                  ,array('2012-12-06',15.460170286650365)
		                  ,array('2012-12-07',15.253902254343304)
		                  ,array('2012-12-08',14.354499966191387)
		                  ,array('2012-12-09',13.588869738115323)
		                  ,array('2012-12-10',13.660928470923547)
		                  ,array('2012-12-11',14.50442569798805)
		                  ,array('2012-12-12',15.343853958732492),array('2012-12-13',15.407446781450195)
		                  ,array('2012-12-14',14.636737218207834),array('2012-12-15',13.740312087141179)
		                  ,array('2012-12-16',13.542340519676616),array('2012-12-17',14.224836661948403)
		                  ,array('2012-12-18',15.16031670824408),array('2012-12-19',15.48870461818667)
		                  ,array('2012-12-20',14.908082061813392),array('2012-12-21',13.95227073977573)
		                  ,array('2012-12-22',13.500039173605362),array('2012-12-23',13.967166979666603)
		                  ,array('2012-12-24',14.924179007336997),array('2012-12-25',15.491202811863474)
		                  ,array('2012-12-26',15.14691932232864)
		                  ,array('2012-12-27',14.207861191266163)
		                  ,array('2012-12-28',13.537394133686433)
		                  ,array('2012-12-29',13.751942470311)
		                  ,array('2012-12-30',14.654251449887584)
		                  ,array('2012-12-31',15.414742357804531)
		                  );
		$graf[] = $g1_normal;
		$intervalos_espectro = 3.5;
//		$espectro_normal_superior = array();
//		$espectro_normal_inferior = array();
//			for($j=1;$j<count($g1_normal);$j++){
//				$espectro_normal_superior[$j][0] = $espectro_normal_inferior[$j][0] = $g1_normal[$j][0];
//				$espectro_normal_superior[$j][1] = $g1_normal[$j][1] + $intervalos_espectro;
//				$espectro_normal_inferior[$j][1] = $g1_normal[$j][1] - $intervalos_espectro;
//			}
		$s1_normal = array('label'=>'Normal','showLine'=>true,'markerOptions'=>array('size'=>0,'showMarker'=>false),'rendererOptions'=>array('bands'=>array('interval'=>$intervalos_espectro),'shadowAlpha'=>0.02,'smooth'=>true));
//		$s1_normal = array('label'=>'Normal','showLine'=>true,'markerOptions'=>array('size'=>0,'showMarker'=>false),'rendererOptions'=>array('bandData'=>array($espectro_normal_superior,$espectro_normal_inferior),'shadowAlpha'=>0.02,'smooth'=>true));
		$serie[] = $s1_normal;
		
		$g1_l1 = array(
					   array('2012-12-01',15.54030230586814)
					  ,array('2012-12-02 07:00',14.583853163452858)
					  ,array('2012-12-02 13:00',14.983853163452858)
					  ,array('2012-12-02 19:00',14.183853163452858)
					  ,array('2012-12-03 07:00',14.010007503399555)
					  ,array('2012-12-03 13:00',14.810007503399555)
					  ,array('2012-12-03 19:00',13.810007503399555)
					  ,array('2012-12-04',14.346356379136388)
					  ,array('2012-12-05',15.283662185463227)
					  ,array('2012-12-06','')
					  ,array('2012-12-07','')
					  ,array('2012-12-08',14.854499966191387)
					  ,array('2012-12-09',14.088869738115323)
					  ,array('2012-12-10',14.160928470923547)
					  ,array('2012-12-11',15.00442569798805)
					  ,array('2012-12-12',15.843853958732492)
					  ,array('2012-12-13',15.907446781450195)
					  ,array('2012-12-14',20)
					  ,array('2012-12-15',14.240312087141179)
					  ,array('2012-12-16',14.042340519676616)
					  ,array('2012-12-17',14.724836661948403)
					  ,array('2012-12-18',15.66031670824408)
					  ,array('2012-12-19','')//,array('2012-12-19',15.98870461818667)
					  ,array('2012-12-20','')//,array('2012-12-20',15.408082061813392)
					  ,array('2012-12-21',14.45227073977573)
					  ,array('2012-12-22',14.000039173605362)
					  ,array('2012-12-23',8.467166979666603)
					  ,array('2012-12-24',15.424179007336997)
					  ,array('2012-12-25',15.991202811863474)
					  ,array('2012-12-26','')
					  ,array('2012-12-27','')
					  ,array('2012-12-28',14.037394133686433)
					  ,array('2012-12-29',14.251942470311)
					  ,array('2012-12-30',15.154251449887584)
					  ,array('2012-12-31',15.914742357804531)
					  );
		$graf[] = $g1_l1;
		$s1_l1 = array('label'=>'Estacion_00006','markerOptions'=>array('size'=>7));
		$serie[] = $s1_l1;
		
//		$g1_l2 = array(array('2012-12-01',15.397338661590123),array('2012-12-02',16.86407817193445),array('2012-12-03',16.61699280763918),array('2012-12-04',14.88325171314484),array('2012-12-05',13.256848455172824),array('2012-12-06',13.233090688559694),array('2012-12-07',14.833821194365008),array('2012-12-08',16.587335727698306),array('2012-12-09',16.881461113359546),array('2012-12-10',15.445779828200495),array('2012-12-11',13.600250624812915),array('2012-12-12',13.041644541697366),array('2012-12-13',14.283541435526342),array('2012-12-14',16.184147029414447),array('2012-12-15',16.996053305432724),array('2012-12-16',15.9727973777076),array('2012-12-17',14.055156027203068),array('2012-12-18',13.006199867916807),array('2012-12-19',13.790334355187431),array('2012-12-20',15.68662985763979),array('2012-12-21',16.95164103553395),array('2012-12-22',16.422322445811965),array('2012-12-23',14.585327158786482),array('2012-12-24',13.129580169610922),array('2012-12-25',13.39348854661209),array('2012-12-26',15.13441614505095),array('2012-12-27',16.75176215962178),array('2012-12-28',16.75854612330145),array('2012-12-29',15.148530891168722),array('2012-12-30',13.401957042680772),array('2012-12-31',13.124616519399439));
//		$graf[] = $g1_l2;
//		$s1_l2 = array('label'=>'Estacion_00007','markerOptions'=>array('size'=>7));
//		$serie[] = $s1_l2;
////		
//		$g1_l3 = array(array('2012-12-01',14.167706326905716)
//		              ,array('2012-12-02',13.692712758272776)
//		              ,array('2012-12-03',16.92034057330073)
//		              ,array('2012-12-04',14.708999932382772)
//		              ,array('2012-12-05',13.321856941847095)
//		              ,array('2012-12-06',10.687707917464984)
//		              ,array('2012-12-07',15.273474436415666)
//		              ,array('2012-12-08',13.084681039353232)
//		              ,array('2012-12-09',16.32063341648816)
//		              ,array('2012-12-10',15.816164123626784)
//		              ,array('2012-12-11',13.000078347210726)
//		              ,array('2012-12-12',15.848358014673995)
//		              ,array('2012-12-13',16.29383864465728)
//		              ,array('2012-12-14',13.074788267372867)
//		              ,array('2012-12-15','')
//		              ,array('2012-12-16','')
//		              ,array('2012-12-17',13.30285945043079)
//		              ,array('2012-12-18',14.744072620745191)
//		              ,array('2012-12-19',16.91014728809459)
//		              ,array('2012-12-20',13.666123876695476)
//		              ,array('2012-12-21',14.200029370023298)
//		              ,array('2012-12-22','')
//		              ,array('2012-12-23','')
//		              ,array('2012-12-24',13.7197113210616)
//		              ,array('2012-12-25','')
//		              ,array('2012-12-26','')
//		              ,array('2012-12-27','')
//		              ,array('2012-12-28',16.70644021544517)
//		              ,array('2012-12-29',15.238360270897639)
//		              ,array('2012-12-30',13.095174039169688)
//		              ,array('2012-12-31',16.347014324647173));
//		$graf[] = $g1_l3;
//		$s1_l3 = array('label'=>'Estacion_00008','markerOptions'=>array('size'=>7));
//		$serie[] = $s1_l3;
				
		$idplot = 'div_grafico';
		$titulo = 'TEMPERATURA M&Aacute;XIMA';
		$x_label = 'Fechas';
		$x_formarstring = '%Y-%m-%d %H:%M';
		$y_label = 'Grados Centigrados';
		$y_formarstring = 'C&deg;';
		
		echo $this->view->util()->jqplot_graf_lineal($idplot,$graf,$serie,$titulo,$x_label,$x_formarstring,$y_label,$y_formarstring);
		
	}
	
	public function  modificarmonitoreodatosAction() {

		$this->view->inlineScript()->appendScript('rangosfechas_monitoreo();');
		$this->view->inlineScript()->appendScript("pintararbolmonitoreo('','2');");	
		$this->view->inlineScript()->appendScript("csscolorestemperatura();");			
		echo $this->view->inlineScript();		
		
		$func = new Libreria_Pintar();
		$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_parametros';
			$psparam[] = array(':p_cod_parag','');
			$psparam[] = array(':p_cod_corrp','');
			$psparam[] = array(':p_cod_corparahm','');
			$psparam[] = array(':p_graf','1');
			$datosparam = $cn->ejec_store_procedura_oracle($ns,$psparam);
				
			$datosparam_temp = array();		
			for($i = 0; $i<count($datosparam);$i++){	
				$datosparam_temp[] = array($datosparam[$i][3],$datosparam[$i][2]);
			}

			$val[] = array('cbcriterio',$func->ContenidoCombo($datosparam_temp,null),'html');
			$func->PintarValor($val);	
			
			$evt[] = array('nom_estacion','change','mapas_criterio_fechas_pintarmapa();');
			$evt[] = array('valor_estacion','change','mapas_criterio_fechas_pintarmapa();');
			$func->PintarEvento($evt);
				
	}
	
	public function estacionescriteriovalorAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$xmlestaciones = $this->_request->getPost ( 'xmlestaciones' );
			$codcriterio = $this->_request->getPost ( 'codcriterio' );
			$fechaini = $this->_request->getPost ( 'fechaini' );
			$fechafin = $this->_request->getPost ( 'fechafin' );	
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_estacion_criterio_valor';
			$psparam[] = array(':p_xmlestacion',$xmlestaciones);
			$psparam[] = array(':p_cod_criterio',$codcriterio);
			$psparam[] = array(':p_fecha_ini',$fechaini);
			$psparam[] = array(':p_fecha_fin',$fechafin);
			$datos = $cn->ejec_store_procedura_oracle($ns,$psparam);
				
			$tmp = array();
			for($i = 0 ; $i<count($datos) ; $i++){
				$tmp[] = array($datos[$i][0],$datos[$i][1],$datos[$i][2],$datos[$i][3],$datos[$i][4],trim($datos[$i][5]),$datos[$i][6]);				
			}
			$datos = $tmp;
			echo json_encode($datos);

		}
	}
	
	public function estacionescriteriograficoAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$xmlestaciones = $this->_request->getPost ( 'xmlestaciones' );
			$codcriterio = $this->_request->getPost ( 'codcriterio' );
			$criterio = $this->_request->getPost ( 'criterio' );
			$fechaini = $this->_request->getPost ( 'fechaini' );
			$fechafin = $this->_request->getPost ( 'fechafin' );	
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_estacion_criterio_valor';
			$psparam[] = array(':p_xmlestacion',$xmlestaciones);
			$psparam[] = array(':p_cod_criterio',$codcriterio);
			$psparam[] = array(':p_fecha_ini',$fechaini);
			$psparam[] = array(':p_fecha_fin',$fechafin);
			$datos = $cn->ejec_store_procedura_oracle($ns,$psparam);
				
				$tmp = array();
				for($i = 0 ; $i<count($datos) ; $i++){
					$tmp[] = array($datos[$i][0],$datos[$i][1],$datos[$i][2],$datos[$i][3],$datos[$i][4],trim($datos[$i][5]),$datos[$i][6]);				
				}
				$datos = $tmp;
			
			$arrayestaciones_t = array();
			for($i = 0 ; $i<count($datos) ; $i++){
				$arrayestaciones_t[] = array($datos[$i][0],$datos[$i][1]);				
			}
//			$arrayestaciones_t = array_values(array_unique($arrayestaciones_t,SORT_REGULAR));
			$arrayestaciones_t = array_values($this->view->util()->multi_array_unique($arrayestaciones_t));

			
			$arrayestaciones_d = array();
			for($i = 0 ; $i<count($arrayestaciones_t) ; $i++){
				for($j = 0 ; $j<count($datos) ; $j++){				
					if($arrayestaciones_t[$i][0] == $datos[$j][0]){
						$arrayestaciones_t[$i][2][] = array($datos[$j][4],$datos[$j][5],$datos[$j][1]);
					}			
				}	
								
			}					
//			print_r($arrayestaciones_t);
//			echo '<br><br>';
			$graf  = array();
			$serie = array();
			for($i = 0 ; $i<count($arrayestaciones_t) ; $i++){
				$graf[] = $arrayestaciones_t[$i][2];		
				$serie[] = array('label'=>$arrayestaciones_t[$i][1],'markerOptions'=>array('size'=>7));	
			}
			
////			print_r($graf);
//			echo '<br><br>************';
//			echo json_encode($graf);
//			echo '<br>->';
////			print_r($serie);
//			echo '<br>->************';
//			echo json_encode($serie);
//			echo '<br><br>';				
			
			$idplot = 'div_grafico';
			$titulo = $criterio;
			$x_label = '';
			$x_formarstring = '%Y-%m-%d';
			$y_label = '';
			$y_formarstring = '%.2f';
			
			echo $this->view->util()->jqplot_graf_lineal($idplot,$graf,$serie,$titulo,$x_label,$x_formarstring,$y_label,$y_formarstring);
			
		}
	}
	
	public function estacionescriteriotabladatosAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$xmlestaciones = $this->_request->getPost ( 'xmlestaciones' );
			$codcriterio = $this->_request->getPost ( 'codcriterio' );			
			$fechaini = $this->_request->getPost ( 'fechaini' );
			$fechafin = $this->_request->getPost ( 'fechafin' );	
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_estacion_criterio_valor';
			$psparam[] = array(':p_xmlestacion',$xmlestaciones);
			$psparam[] = array(':p_cod_criterio',$codcriterio);
			$psparam[] = array(':p_fecha_ini',$fechaini);
			$psparam[] = array(':p_fecha_fin',$fechafin);
			$datos = $cn->ejec_store_procedura_oracle($ns,$psparam);

			$ns = 'pkg_senamhi.sp_obt_parametros';
			$pscriterio[] = array(':p_cod_parag','');
			$pscriterio[] = array(':p_cod_corrp','');
			$pscriterio[] = array(':p_cod_corparahm',$codcriterio);
			$pscriterio[] = array(':p_graf','');
			$datocriterio = $cn->ejec_store_procedura_oracle($ns,$pscriterio);
			
			
				$tmp = array();
				for($i = 0 ; $i<count($datos) ; $i++){
					$tmp[] = array($datos[$i][0],$datos[$i][1],$datos[$i][2],$datos[$i][3],$datos[$i][4],trim($datos[$i][5]),$datos[$i][6]);				
				}
				$datos = $tmp;
			
			$arrayestaciones_t = array();
			for($i = 0 ; $i<count($datos) ; $i++){
				$arrayestaciones_t[] = array($datos[$i][0],$datos[$i][1]);				
			}
			$arrayestaciones_t = array_values(array_unique($arrayestaciones_t,SORT_REGULAR));
			
//			print_r($arrayestaciones_t);
			
			
			
				//cabecera
				$temp_cabeceratabla[]= array('sTitle'=>'Fecha','sClass'=>'center');
				for($i = 0 ; $i<count($arrayestaciones_t) ; $i++){
					$temp_cabeceratabla[] =array('sTitle'=>'<div>'.$arrayestaciones_t[$i][1].'
					 											  <ul style="float:right">
				 											  		<li class="ui-state-default ui-corner-all" style="cursor:pointer;float:right" onclick="estacionescomentarios(\''.$arrayestaciones_t[$i][0].'\')">
				 											  			<span class="ui-icon ui-icon-comment" style=""></span>
				 											  		</li>
					 											  </ul>
					 										</div>','sWidth'=>'100px');				
				}
				//cuerpo
				
				$datetime1 = new DateTime($fechaini);
				$datetime2 = new DateTime($fechafin);
				$interval = $datetime1->diff($datetime2);
				$datatabla = array();		
				for($i = 0 ; $i<=$interval->format('%a');$i++){
					$fecha = date ( 'Y-m-d' , strtotime ( '+'.$i.' day' , strtotime ( $fechaini ) ) );
					$datatabla[$i][] = $fecha;					
					for($j = 0 ; $j<count($arrayestaciones_t) ; $j++){
						//$datatabla[$i][] = '<div align="right" onclick="estacionescriteriotabladatoseditor(\''.$arrayestaciones_t[$j][0].'\',\''.$arrayestaciones_t[$j][1].'\',\''.$fecha.'\',\''.$codcriterio.'\')" onmouseover="$(\'#btn_editar_'.$fecha.'_'.$codcriterio.'_'.$arrayestaciones_t[$j][0].'\').show();" onmouseout="$(\'#btn_editar_'.$fecha.'_'.$codcriterio.'_'.$arrayestaciones_t[$j][0].'\').hide();" ><div id="val_'.$fecha.'_'.$codcriterio.'_'.$arrayestaciones_t[$j][0].'" align="center" style="float:left;width:80%">-</div><div style="float:left;width:20%" class="btn_editarcriterio" id="btn_editar_'.$fecha.'_'.$codcriterio.'_'.$arrayestaciones_t[$j][0].'"><ul><li class="ui-state-default ui-corner-all" style="width:20px"><span class="ui-icon ui-icon-pencil"></span></li></ul></div></div>';
						$datatabla[$i][] = '<div align="right" onclick="estacionescriteriotabladatoseditor(\'2\',\''.$arrayestaciones_t[$j][0].'\',\''.$fecha.'\',\''.$datocriterio[0][0].'\',\''.$datocriterio[0][1].'\',$(\'#val_'.$fecha.'_'.$codcriterio.'_'.$arrayestaciones_t[$j][0].'\').html())" onmouseover="$(\'#btn_editar_'.$fecha.'_'.$codcriterio.'_'.$arrayestaciones_t[$j][0].'\').show();" onmouseout="$(\'#btn_editar_'.$fecha.'_'.$codcriterio.'_'.$arrayestaciones_t[$j][0].'\').hide();" ><div id="val_'.$fecha.'_'.$codcriterio.'_'.$arrayestaciones_t[$j][0].'" align="center" style="float:left;width:80%">-</div><div style="float:left;width:20%" class="btn_editarcriterio" id="btn_editar_'.$fecha.'_'.$codcriterio.'_'.$arrayestaciones_t[$j][0].'"><ul><li class="ui-state-default ui-corner-all" style="width:20px"><span class="ui-icon ui-icon-pencil"></span></li></ul></div></div>';					
					}		
					
				}
				
			$datatable_opciones[] = array('bFilter','false');
			$datatable_opciones[] = array('bLengthChange','false');
			$datatable_opciones[] = array('bInfo','false');
			$datatable_opciones[] = array('bPaginate','false');
			$datatable_opciones[] = array('bSort','false');					
//			$datatable_opciones[] = array('iDisplayLength',count($datoscriterios_temp));
			$datatable_opciones[] = array('bJQueryUI','true');			
			$datatable_opciones[] = array('sScrollX','"100%"');
			$datatable_opciones[] = array('sScrollXInner','"150%"');
			$datatable_opciones[] = array('bScrollCollapse','true');
			$datatable_opciones[] = array('aaData',json_encode($datatabla));
			$datatable_opciones[] = array('aoColumns',json_encode($temp_cabeceratabla));
//			$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(1)", nRow).attr( "align","center" );}');
//			$datatable_opciones[] = array('aaSorting',json_encode(array(array(1,'asc'),array(2,'asc'))));
//			$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){if (rtrim(aData[3]) == ""){$("td", nRow).addClass("ui-state-error" );}}');
//			$datatable_opciones[] = array('aoColumnDefs',json_encode(array(array('bVisible'=>false ,'aTargets'=>array(0)))));
//			$datatable_opciones[] = array('fnDrawCallback','function(oSettings){if( oSettings.aiDisplay.length == 0 ){return;}var nTrs = $("#c_table_monitoreodatosestaciondetalle_googlemaps tbody tr");var iColspan = nTrs[0].getElementsByTagName("td").length;var sLastGroup = "";for ( var i=0 ; i<nTrs.length ; i++ ){var iDisplayIndex = oSettings._iDisplayStart + i;var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];if ( sGroup != sLastGroup ){var nGroup = document.createElement( "tr" );var nCell = document.createElement( "td" );nCell.colSpan = iColspan;nCell.className = "ui-state-default";nCell.innerHTML = sGroup;nGroup.appendChild( nCell );nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );sLastGroup = sGroup;}}}');
			echo $this->view->util()->datatable("c_tabladatosestaciones",$datatable_opciones,TRUE,'{"iLeftColumns": 1,"iLeftWidth": 100}');
			
			$func = new Libreria_Pintar();
			$val = array();					
			for($i = 0 ; $i<count($datos);$i++){
				$val[] = array('val_'.$datos[$i][4].'_'.$codcriterio.'_'.$datos[$i][0], ((trim($datos[$i][5])=='')?'':$datos[$i][5]) ,'html');											
			}
			$func->PintarValor($val);
			$fn[] = array("$('.btn_editarcriterio').hide();");
			$func->EjecutarFuncion($fn);
		}
	}
	
	public function estacionescriteriotabladatoseditor1Action() {	
		$this->_helper->layout->disableLayout ();
			$codestacion = $this->_request->getParam( 'codestacion','' );
			$estacion = $this->_request->getParam( 'estacion','' );
			$fecha = $this->_request->getParam ( 'fecha','' );			
			$codcriterio = $this->_request->getParam ( 'codcriterio','' );
			$criterio_text = $this->_request->getParam ( 'criterio_text','' );
			$valoractual = $this->_request->getParam ( 'valoractual','' );
//			echo $codestacion.'->'.$estacion.'->'.$fecha.'->'.$criterio.'->'.$criterio_text.'->'.$valoractual;
			$func = new Libreria_Pintar();
			$val[] = array('txtestacion',$estacion,'html');
			$val[] = array('txtcriterio',$criterio_text,'html');
			$val[] = array('txtfecha',$fecha,'html');
			$val[] = array('txtvaloractual',$valoractual,'html');
			$func->PintarValor($val);
			
			$fn[] = array('$("button").button();');		
			$fn[] = array('$("#txtnuevovalor").kendoNumericTextBox({format: "#.##",step: 1,decimals: 2});');
			$func->EjecutarFuncion($fn);
			
			$evt[]= array('btn_guardar','click',"guardarestacioncriteriovalor('".$codestacion."','".$fecha."','".$codcriterio."');");
			$func->PintarEvento($evt);
	}
	
	public function estacionescriteriotabladatoseditorAction() {	
		$this->_helper->layout->disableLayout ();

			$codestacion = $this->_request->getParam( 'codestacion','' );
			$fecha = $this->_request->getParam ( 'fecha','' );			
			$codgrupo = $this->_request->getParam ( 'codgrupo','' );
			$codparam = $this->_request->getParam ( 'codparam','' );
			$valoract = $this->_request->getParam ( 'valoract','' );
			$tipoventana = $this->_request->getParam ( 'tipoventana','' );
			$nrofila = $this->_request->getParam ( 'nrofila','' );
			$nrocol = $this->_request->getParam ( 'nrocol','' );
			
//			echo $codestacion.'->'.$fecha.'->'.$codgrupo.'->'.$codparam.'->'.$valoract;
			
			$cn = new Model_DataAdapter();		
			$ns = 'pkg_senamhi.sp_obt_estaciones';
			$psest[] = array(':p_codest',$codestacion);
			$psest[] = array(':p_coddreg','');
			$psest[] = array(':p_codenti','');
			$psest[] = array(':p_tipo','');
			$datosestacion = $cn->ejec_store_procedura_oracle($ns,$psest);
			
			$ns = 'pkg_senamhi.sp_obt_parametros';
			$pscriterio[] = array(':p_cod_parag',$codgrupo);
			$pscriterio[] = array(':p_cod_corrp',$codparam);
			$pscriterio[] = array(':p_cod_corparahm','');
			$pscriterio[] = array(':p_graf','');
			$datocriterio = $cn->ejec_store_procedura_oracle($ns,$pscriterio);
			
			$func = new Libreria_Pintar();
			$val[] = array('codestacion',$codestacion,'val');
			$val[] = array('hd_codgrupo',$codgrupo,'val');
			$val[] = array('hd_codparam',$codparam,'val');
			$val[] = array('hd_codcriterio',$datocriterio[0][3],'val');
			$val[] = array('txtestacion',$datosestacion[0][1],'html');
			$val[] = array('txtdirreg',$datosestacion[0][3],'html');
			$val[] = array('txtcriterio',$datocriterio[0][2],'html');
			$val[] = array('txtfecha',$fecha,'html');
				
			
				if($datocriterio[0][7] == '1'){					
					
					$ns = 'pkg_senamhi.sp_obt_equivalencia';
					$pscriterio_equival[] = array(':p_cod_parag',$codgrupo);
					$datocriterio_val = $cn->ejec_store_procedura_oracle($ns,$pscriterio_equival);
			
					$valoract_nom = '';
					$valoract_nval = '';
					$arrayequival = array();
					for($i = 0; $i<count($datocriterio_val) ; $i++){
						if($datocriterio_val[$i][3] == $valoract){
							$valoract_nom = $datocriterio_val[$i][2];
							$valoract_nval = $datocriterio_val[$i][4];
						}
						$arrayequival[] = array($datocriterio_val[$i][4],$datocriterio_val[$i][3].'  ('. $datocriterio_val[$i][2] .')' );
					}
					
					$val[] = array('txtvaloractual',$valoract.'  ('.$valoract_nom.')','html');
					$val[] = array('hd_codvaloractual',$valoract_nval,'val');					
					$val[] = array('td_nuevovalor','<select id="v_nuevovalor" style="width:100%"></select>','html');					
						
						$val[] = array('v_nuevovalor',$func->ContenidoCombo($arrayequival,null),'html');
				}else{
					
					$val[] = array('txtvaloractual',$valoract,'html');
					$val[] = array('hd_codvaloractual',$valoract,'val');
					
					$val[] = array('td_nuevovalor','<input type="text" id="v_nuevovalor" style="width:100%" value="">','html');
					$fn[] = array('$("#v_nuevovalor").kendoNumericTextBox({format: "#.##",step: 1,decimals: 2});');					
				}
			$func->PintarValor($val);
//			
			$fn[] = array('$("button").button();');		
//			
			$func->EjecutarFuncion($fn);
//			
			$evt[]= array('btn_guardar','click',"guardarestacioncriteriovalor('".$nrofila."','".$nrocol."','".$tipoventana."');");
			$evt[]= array('btn_cerrar','click',"cerrarsubvent2()");	
			$func->PintarEvento($evt);
	}
	
	public function guardarestacioncriteriovalorAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$codestacion = $this->_request->getPost ( 'codestacion' );
			$fecha = $this->_request->getPost ( 'fecha' );
			$codgrupo = $this->_request->getPost ( 'codgrupo' );
			$codparam = $this->_request->getPost ( 'codparam' );			
			$nuevovalor = $this->_request->getPost ( 'nuevovalor' );
			$actualvalor = $this->_request->getPost ( 'actualvalor' );
			$comentario = $this->_request->getPost ( 'comentario' );	
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_act_estacion_criterio_valor';
			$psparam[] = array(':p_cod_estacion',$codestacion);
			$psparam[] = array(':p_codgrupo',$codgrupo);
			$psparam[] = array(':p_codparam',$codparam);
			$psparam[] = array(':p_fecha_criterio',$fecha);
			$psparam[] = array(':p_valor_nuevo',$nuevovalor);
			$psparam[] = array(':p_valor_antiguo',$actualvalor);
			$psparam[] = array(':p_comentario',$comentario);

			$datos = $cn->ejec_store_procedura_oracle($ns,$psparam);		
			echo json_encode(array($datos[0][0],$datos[0][1]));

		}
	}
	
	public function estacionescomentariosAction() {		

		$this->_helper->layout->disableLayout ();			
		$codestacion = $this->_request->getParam ( 'codestacion','' );	
		$func = new Libreria_Pintar();
		$fn[] = array('$( "#tabs" ).tabs();');
		$func->EjecutarFuncion($fn);
		$fn[] = array('$("button").button();');		
		$func->EjecutarFuncion($fn);
		
		$cn = new Model_DataAdapter();
		
		//estaciones
			$ns = 'pkg_senamhi.sp_obt_estaciones';
			$psest[] = array(':p_codest',$codestacion);
			$psest[] = array(':p_coddreg','');
			$psest[] = array(':p_codenti','');
			$psest[] = array(':p_tipo','M');		
			$datosestaciones = $cn->ejec_store_procedura_oracle($ns,$psest);

			$val[] = array('c_estacion_coment',$datosestaciones[0][1],'html');
			$val[] = array('c_dirreg_coment',$datosestaciones[0][3],'html');		
			
			$func->PintarValor($val);
			
//			//tablas de comentarios
//			$datatable_opciones_comentarios[] = array('bFilter','false');
//			$datatable_opciones_comentarios[] = array('bLengthChange','true');
////			$datatable_opciones_comentarios[] = array('bInfo','false');
//			$datatable_opciones_comentarios[] = array('bPaginate','true');					
//			$datatable_opciones_comentarios[] = array('sPaginationType','"full_numbers"');
//			$datatable_opciones_comentarios[] = array('sScrollY','"200px"');
//			
//	//		$datatable_opciones_comentarios[] = array('iDisplayLength',count($datoscriterios_temp));
//			$datatable_opciones_comentarios[] = array('bJQueryUI','true');
//			$datatable_opciones_comentarios[] = array('bScrollCollapse','false');
//			$datatable_opciones_comentarios[] = array('aoColumns',json_encode(array(array('sTitle'=>'Fecha'),array('sTitle'=>'Comentario'))));
//			$datatable_opciones_comentarios[] = array('aaData','[]');
//			$datatable_opciones_comentarios[] = array('aaSorting',json_encode(array(array(0,'asc'))));
////			$datatable_opciones_comentarios[] = array('aoColumnDefs',json_encode(array(array('sClass'=>'center' ,'aTargets'=>array(1)))));
////			$datatable_opciones_comentarios[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(1)", nRow).attr( "align","center" );}');
//
//			echo $this->view->util()->datatable("c_tabladatosestaciones_comentarios",$datatable_opciones_comentarios);
			
			
			
			$ns = 'pkg_senamhi.sp_obt_ingmovil_hist';
			$pshist[] = array(':p_cod_estacion',$codestacion);		
			$datoshist = $cn->ejec_store_procedura_oracle($ns,$pshist);
			
			//tablas de valores modificados
//			$datatable_opciones_historialvalores[] = array('bFilter','false');
			$datatable_opciones_historialvalores[] = array('bLengthChange','true');
//			$datatable_opciones_historialvalores[] = array('bInfo','false');
			$datatable_opciones_historialvalores[] = array('bPaginate','true');					
			$datatable_opciones_historialvalores[] = array('sPaginationType','"full_numbers"');
			$datatable_opciones_historialvalores[] = array('sScrollY','"200px"');
			
	//		$datatable_opciones_historialvalores[] = array('iDisplayLength',count($datoscriterios_temp));
			$datatable_opciones_historialvalores[] = array('bJQueryUI','true');
			$datatable_opciones_historialvalores[] = array('bScrollCollapse','false');
			$datatable_opciones_historialvalores[] = array('aoColumns',json_encode(array(array('sTitle'=>'Fecha<br>Modificaci&oacute;n')
			                                                                            ,array('sTitle'=>'Parametro')
			                                                                            ,array('sTitle'=>'Fecha<br>Parametro')
			                                                                            ,array('sTitle'=>'Valor<br>Antiguo')
			                                                                            ,array('sTitle'=>'Valor<br>Modificado')
			                                                                            ,array('sTitle'=>'Comentario')
			                                                                            )
			                                                                        ));
			$datatable_opciones_historialvalores[] = array('aaData',json_encode($datoshist));
			$datatable_opciones_historialvalores[] = array('aaSorting',json_encode(array(array(0,'asc'))));
			$datatable_opciones_historialvalores[] = array('aoColumnDefs',json_encode(array(array('sClass'=>'center' ,'aTargets'=>array(3,4)))));
//			$datatable_opciones_historialvalores[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(1)", nRow).attr( "align","center" );}');

			echo $this->view->util()->datatable("c_tabladatosestaciones_historialvalores",$datatable_opciones_historialvalores);
		
	}


	public function listaestacionesAction() {
		
		$tipo = $this->_request->getParam( 'tipo','M' );
		$mod = $this->_request->getParam( 'mod','' );
		
		$cn = new Model_DataAdapter();		
		$ns = 'pkg_senamhi.sp_obt_estaciones';
		$psest[] = array(':p_codest','');
		$psest[] = array(':p_coddreg','');
		$psest[] = array(':p_codenti','');
		$psest[] = array(':p_tipo',$tipo);
		$datos = $cn->ejec_store_procedura_oracle($ns,$psest);
//		print_r($datos);
		$array = array();
		for($i=0;$i<count($datos);$i++){
			$array[] = array($datos[$i][0],
			                 $datos[$i][1],
			                 $datos[$i][3],
			                 $datos[$i][4],
			                 $datos[$i][5],
			                 $datos[$i][6],
			                 $datos[$i][8],
			                 $datos[$i][9],
			                 $datos[$i][10],
			                 $datos[$i][16].'<br>('.$datos[$i][18].')',			                 
			                 (($datos[$i][19]=='1')?'<ul><li disabled="disabled" class="ui-state-default ui-corner-all"  id="" onclick="tablaestacioncriteriovalor_ventana(\''.$datos[$i][2].'\',\''.$datos[$i][0].'\',\''.$mod.'\')" style="width:20px;cursor: pointer; cursor: hand;"><span class="ui-icon ui-icon-document""></span></li></ul>':'')
			                 );
		}
		
		
		$func = new Libreria_Pintar();
		$fn[] = array('$("button").button();');		
		$func->EjecutarFuncion($fn);
//		$evt[] = array('btn_enviarmail','click',"enviarmailmatrizvariables('');");					
//		$func->PintarEvento($evt);

		//tablas
		$datatable_opciones[] = array('bFilter','true');
		$datatable_opciones[] = array('bLengthChange','true');
		$datatable_opciones[] = array('bInfo','true');
		$datatable_opciones[] = array('bPaginate','true');		
		$datatable_opciones[] = array('sPaginationType','"full_numbers"');
//		$datatable_opciones[] = array('iDisplayLength',count($array));
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode($array));
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(1,'asc'))));
		$datatable_opciones[] = array('aoColumnDefs',json_encode(array(array('sClass'=>'center' ,'aTargets'=>array(0,3,4,5,6,7,8)))));
		$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(10),td:eq(11)", nRow).attr( "align","center" );}');
		echo $this->view->util()->datatable("c_tabla_estaciones",$datatable_opciones);
	}
	
	public function tablaestacioncriteriovalorventanaAction() {

//	    $this->_helper->viewRenderer->setNoRender ();

		$this->_helper->layout->disableLayout ();
		
		$codestacion = $this->_request->getParam( 'codestacion','' );
		$codcoddirreg = $this->_request->getParam( 'coddirreg','' );
		$height = $this->_request->getParam( 'height','' );
		$mod = $this->_request->getParam( 'mod','' );
		
		$func = new Libreria_Pintar();
		$cn = new Model_DataAdapter();
		
		$ns = 'pkg_senamhi.sp_obt_estaciones';
		$psest[] = array(':p_codest','');
		$psest[] = array(':p_coddreg',$codcoddirreg);
		$psest[] = array(':p_codenti','');
		$psest[] = array(':p_tipo','M');		
		$datosestaciones = $cn->ejec_store_procedura_oracle($ns,$psest);

		$ns = 'pkg_senamhi.sp_obt_dirreg';
		$psdr[] = array(':p_codreg','');		
		$datosdirreg = $cn->ejec_store_procedura_oracle($ns,$psdr);
		
		$ns = 'pkg_senamhi.sp_obt_horassinopticas';
		$pshora[] = array(':p_cod_hora','');		
		$datoshora = $cn->ejec_store_procedura_oracle($ns,$pshora);
		
//		$val[] = array('c_estacion',$datosestaciones[0][1],'html');
//		$val[] = array('c_dirreg',$datosestaciones[0][3],'html');		
		
		$val[] = array('c_estacion',$func->ContenidoCombo($datosestaciones,$codestacion),'html');
		$val[] = array('c_dirreg',$func->ContenidoCombo($datosdirreg,$codcoddirreg),'html');
		$val[] = array('c_hora',$func->ContenidoCombo($datoshora,''),'html');
		$val[] = array('hd_height',$height,'val');
		$val[] = array('hd_mod',$mod,'val');
		
		$func->PintarValor($val);
		
		$evt[] = array('btn_buscardatos','click',"tablaestacioncriteriovalor_pintar()");
		$evt[] = array('c_dirreg','change',"cb_seleccionarestacion()");
		$func->PintarEvento($evt);
		
		$this->view->inlineScript()->appendScript('rangosfechas();');	
		echo $this->view->inlineScript();
			
		$fn[] = array('$("button").button();');		
		$fn[] = array('$( "#tabs" ).tabs();');
//		$fn[] = array('$( "#tabs_hidro" ).tabs({ collapsible: true });');
		$fn[] = array('$("#tabs").tabs( {
							"show": function(event, ui) {
								var oTable2 = $("div.dataTables_scrollBody>table.display2", ui.panel).dataTable();
								if ( oTable2.length > 0 ) {
									oTable2.fnAdjustColumnSizing();
								}
							}
						} );');
		$fn[] = array('$("#tabs_hidro").tabs( {
							"show": function(event, ui) {
								var oTable1 = $("div.dataTables_scrollBody>table.display1", ui.panel).dataTable();
								if ( oTable1.length > 0 ) {
									oTable1.fnAdjustColumnSizing();
								}								
								var oTable3 = $("div.dataTables_scrollBody>table.display3", ui.panel).dataTable();
								if ( oTable3.length > 0 ) {
									oTable3.fnAdjustColumnSizing();
								}
							}
						} );');
		
		$func->EjecutarFuncion($fn);
	}
	
	public function cbseleccionarestacionAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$coddirreg= $this->_request->getPost ( 'coddirreg' );	
			
			$func = new Libreria_Pintar();
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_estaciones';
			$psest[] = array(':p_codest','');
			$psest[] = array(':p_coddreg',$coddirreg);
			$psest[] = array(':p_codenti','');
			$psest[] = array(':p_tipo','M');		
			$datosestaciones = $cn->ejec_store_procedura_oracle($ns,$psest);
			echo $func->ContenidoCombo($datosestaciones,null);

		}
	}
	
	public function tablaestacioncriteriovalorAction() {
		
		$this->_helper->layout->disableLayout ();
		
		$url = $this->view->util()->getPath();
		
		$codestacion = $this->_request->getPost ( 'codestacion' );
		$coddirreg = $this->_request->getPost ( 'coddirreg' );
		$fechaini = $this->_request->getPost ( 'fechaini' );		
		$fechafin = $this->_request->getPost ( 'fechafin' );
		$height = $this->_request->getPost( 'height','' );
		$mod = $this->_request->getPost( 'mod','' );
		$codhora = $this->_request->getPost ( 'codhora' );
		
		$cn = new Model_DataAdapter();
//		$ns = 'pkg_senamhi.sp_obt_tbl_estac_criterio_val';
//		$psparam[] = array(':p_coddirreg',$coddirreg);
//		$psparam[] = array(':p_codestacion',$codestacion);		
//		$psparam[] = array(':p_fecha_ini',$fechaini);
//		$psparam[] = array(':p_fecha_fin',$fechafin);
//		$psparam[] = array(':p_codhora',$codhora);
//		$datos = $cn->ejec_store_procedura_oracle($ns,$psparam);
		
		//DATOS HIRDROMETEOROLOGICOS
		
		//cabecera
		$ns = 'pkg_senamhi.sp_obt_tbl_estac_crit_cab';
		$cabecera = $cn->ejec_store_procedura_oracle($ns,array());
	
		$cabecera_grupos = array();
		for($i = 0 ; $i<count($cabecera) ; $i++){
			$cabecera_grupos[] = array($cabecera[$i][1],0,$cabecera[$i][3]);
		}
		$cabecera_grupos = array_values($this->view->util()->multi_array_unique($cabecera_grupos));	
		$cabecera_grupos = $this->view->util()->ordenar_array($cabecera_grupos, 2, SORT_ASC );
		for($i = 0 ; $i<count($cabecera_grupos) ; $i++){
			for($j = 0 ; $j<count($cabecera) ; $j++){
				if($cabecera_grupos[$i][0] == $cabecera[$j][1])
					$cabecera_grupos[$i][1] = $cabecera_grupos[$i][1] + 1;			
			}				
		}

		//boton para eliminar
		$edit_tit = '-';
		$edit_b_subtit = 'b';	//borrar	
		$edit_c_subtit = 'c';	//combinar
		if($mod == '1'){
			$cabecera[] = array('',$edit_tit,$edit_b_subtit,'999','1');
			$cabecera[] = array('',$edit_tit,$edit_c_subtit,'999','2');
			$cabecera_grupos[] = array($edit_tit,'1','999');		
		}
		
		//laterales
		$psparam = array();
		$ns = 'pkg_senamhi.sp_obt_tbl_estac_crit_lat';
		$psparam[] = array(':p_coddirreg',$coddirreg);
		$psparam[] = array(':p_codestacion',$codestacion);		
		$psparam[] = array(':p_fecha_ini',$fechaini);
		$psparam[] = array(':p_fecha_fin',$fechafin);
		$psparam[] = array(':p_codhora',$codhora);
		$lateral = $cn->ejec_store_procedura_oracle($ns,$psparam);
//		echo'--->';print_r($lateral);
		$lateral = array_values($this->view->util()->multi_array_unique($lateral));	
		$lateral = $this->view->util()->ordenar_array($lateral, 5, SORT_ASC, 4, SORT_ASC, 0, SORT_ASC, 1, SORT_ASC, 3, SORT_ASC );
		
		
		//datos tabla
		$psparam = array();
		$ns = 'pkg_senamhi.sp_obt_tbl_estac_crit_dat';
		$psparam[] = array(':p_coddirreg',$coddirreg);
		$psparam[] = array(':p_codestacion',$codestacion);		
		$psparam[] = array(':p_fecha_ini',$fechaini);
		$psparam[] = array(':p_fecha_fin',$fechafin);
		$psparam[] = array(':p_codhora',$codhora);
		$datos = $cn->ejec_store_procedura_oracle($ns,$psparam);
		
		$ddatoshidro = new Zend_Session_Namespace('datoshidrometeorologicos');				
		$ddatoshidro->data = $datos;	
		
		$tbl = '';
		$tbl_cab_l1 = '';
		$tbl_cab_l2 = '';
						
			for($i = 0 ; $i<count($cabecera_grupos) ; $i++){
				$tbl_cab_l1 .= '<th class="ui-state-default" colspan="'.$cabecera_grupos[$i][1].'">'.$cabecera_grupos[$i][0].'</th>';	
				for($j = 0 ; $j<count($cabecera) ; $j++){
					if($cabecera_grupos[$i][0] == $cabecera[$j][1]){
						$tbl_cab_l2 .= '<th>'.$cabecera[$j][2].'</th>';
					}	
				}			
			}

		$tbl_cuerpo = '';
			
			$cant_row_group = 0;
			for($i = 0 ; $i<count($lateral) ; $i++){
				$tbl_cuerpo .= '<tr>';
				$tbl_cuerpo .= '<td align="center">'.$lateral[$i][4].' ('.$lateral[$i][5].') <ul style="float:left"><li class="ui-state-default ui-corner-all" style="cursor:pointer;float:right" onclick="estacionescomentarios(\''.$lateral[$i][6].'\')"><span class="ui-icon ui-icon-comment" style=""></span></li></ul> </td>';
				$tbl_cuerpo .= '<td align="center" id="dat_'.$lateral[$i][6].'_'.$lateral[$i][0].'_'.substr($lateral[$i][1],0,2).'">'.$lateral[$i][0].'</td>';
				$tbl_cuerpo .= '<td align="center">'.$lateral[$i][1].'</td>';
				$tbl_cuerpo .= '<td align="center">'.$lateral[$i][3].'</td>';
				$nrocol = 4;			
					for($j = 0 ; $j<count($cabecera) ; $j++){									   	
						$tbl_cuerpo .= '<td nrocol="'.($nrocol + $j).'" nrofila="" class="tbl_reg_fila '.$cabecera[$j][1].'_'.str_replace(' ','_',$cabecera[$j][2]).'_'.str_replace(' ','_',$lateral[$i][4]).'_'.str_replace(' ','_',$lateral[$i][5]).'"  tip_reg="'.$cabecera[$j][1].'_'.str_replace(' ','_',$cabecera[$j][2]).'_'.str_replace(' ','_',$lateral[$i][4]).'_'.str_replace(' ','_',$lateral[$i][5]).'" id="'.$lateral[$i][2].'_'.$cabecera[$j][1].'_'.str_replace(' ','_',$cabecera[$j][2]).'_'.str_replace(' ','_',$lateral[$i][4]).'_'.str_replace(' ','_',$lateral[$i][5]).'"></td>';						
					}
				$tbl_cuerpo .= '</tr>';
			}
			
		$tbl.= '<thead><tr><th rowspan="2">Estaci&oacute;n</th><th rowspan="2" width="70px">FECHA</th><th rowspan="2" width="60px">HORA<BR>PREVISTA</th><th rowspan="2" width="60px">HORA<br>LLEGADA</th>'.$tbl_cab_l1.'</tr><tr>'.$tbl_cab_l2.'</tr></thead><tbody>'.$tbl_cuerpo.'</tbody>';		
		
		$func = new Libreria_Pintar();
		$val[] = array('c_tablarpta_varhidrometeorologicas','<div align="left"><button id="btn_combinardatos" style="display:none" onclick="combinardatoshidrometeorologicos()">Combinar Datos</button><br></div><table id="c_tbl_est_crit_val" class="display1" border="1" cellpadding="0" cellspacing="0"></table>','html');
		$val[] = array('c_tablarpta_meteoros','<table id="c_tbl_est_meteoros" class="display2" border="1" cellpadding="0" cellspacing="0"></table>','html');
		$val[] = array('c_tbl_est_crit_val',$tbl,'html');
		
		//pintar datos
		$fn = array();
		$evt = array();
		$onclick = '';
		
		$fn[] = array('$("button").button();');	
		
		for($i = 0 ; $i<count($datos) ; $i++){			
			$valtxt = $datos[$i][9];
			if($datos[$i][10] == '0'){
				$valtxt = '';
				$fn[] = array('$("#'.$datos[$i][1].'_'.$datos[$i][3].'_'.str_replace(':','',$datos[$i][11]).'_'.$datos[$i][6].'_'.str_replace(' ','_',$datos[$i][8]).'_'.str_replace(' ','_',$datos[$i][14]).'_'.str_replace(' ','_',$datos[$i][16]).'").addClass("ui-state-error" );');
//				$fn[] = array('$("#'.$datos[$i][1].'_'.$datos[$i][3].'_'.str_replace(':','',$datos[$i][11]).'_'.$datos[$i][6].'_'.str_replace(' ','_',$datos[$i][8]).'_'.str_replace(' ','_',$datos[$i][14]).'_'.str_replace(' ','_',$datos[$i][16]).'").html("");');
			}
			$val[] = array($datos[$i][1].'_'.$datos[$i][3].'_'.str_replace(':','',$datos[$i][11]).'_'.$datos[$i][6].'_'.str_replace(' ','_',$datos[$i][8]).'_'.str_replace(' ','_',$datos[$i][14]).'_'.str_replace(' ','_',$datos[$i][16]),$valtxt,'html');
			if($mod == '1'){
				//$fn[] = array('$("#'.$datos[$i][1].'_'.$datos[$i][3].'_'.str_replace(':','',$datos[$i][11]).'_'.$datos[$i][6].'_'.str_replace(' ','_',$datos[$i][8]).'_'.str_replace(' ','_',$datos[$i][14]).'_'.str_replace(' ','_',$datos[$i][16]).'").attr("c_index","x" );');
				$fn[] = array('$("#'.$datos[$i][1].'_'.$datos[$i][3].'_'.str_replace(':','',$datos[$i][11]).'_'.$datos[$i][6].'_'.str_replace(' ','_',$datos[$i][8]).'_'.str_replace(' ','_',$datos[$i][14]).'_'.str_replace(' ','_',$datos[$i][16]).'").attr("tip_reg","'.$datos[$i][6].'_'.str_replace(' ','_',$datos[$i][8]).'_'.str_replace(' ','_',$datos[$i][14]).'_'.str_replace(' ','_',$datos[$i][16]).'" );');
				$fn[] = array('$("#'.$datos[$i][1].'_'.$datos[$i][3].'_'.str_replace(':','',$datos[$i][11]).'_'.$datos[$i][6].'_'.str_replace(' ','_',$datos[$i][8]).'_'.str_replace(' ','_',$datos[$i][14]).'_'.str_replace(' ','_',$datos[$i][16]).'").addClass("'.$datos[$i][6].'_'.str_replace(' ','_',$datos[$i][8]).'_'.str_replace(' ','_',$datos[$i][14]).'_'.str_replace(' ','_',$datos[$i][16]).'" );');
				//$fn[] = array('calcular_cindex_tblestacioncriteriovalor("'.$datos[$i][6].'_'.str_replace(' ','_',$datos[$i][8]).'_'.str_replace(' ','_',$datos[$i][14]).'_'.str_replace(' ','_',$datos[$i][16]).'" );');
				$evt[] = array($datos[$i][1].'_'.$datos[$i][3].'_'.str_replace(':','',$datos[$i][11]).'_'.$datos[$i][6].'_'.str_replace(' ','_',$datos[$i][8]).'_'.str_replace(' ','_',$datos[$i][14]).'_'.str_replace(' ','_',$datos[$i][16]),'click',"estacionescriteriotabladatoseditor('1','".$datos[$i][0]."','".$datos[$i][1]."','".$datos[$i][5]."','".$datos[$i][7]."','".$valtxt."',this)");
				$edi_b_icon = '<ul><li class="ui-state-default ui-state-error ui-corner-all" title=".ui-icon-circle-close"><span class="ui-icon ui-icon-circle-close btn_borrardatos" cindex="" onclick="borrardatosestacionhorasinop(\''.$datos[$i][0].'\',\''.$datos[$i][14].'\',\''.$datos[$i][1].'\',\''.$datos[$i][11].'\',this)"></span></li></ul>';
				$edi_c_check = '<input type="checkbox" class="check_combinardatos" id="" onchange="activarbotoncombinacion()" cindex="" codestacion="'.$datos[$i][0].'" estacion="'.$datos[$i][14].'" fecha="'.$datos[$i][1].'" hora="'.$datos[$i][11].'" horasinoptica="'.$datos[$i][2].'">';
				$val[] = array($datos[$i][1].'_'.$datos[$i][3].'_'.str_replace(':','',$datos[$i][11]).'_'.$edit_tit.'_'.$edit_b_subtit.'_'.str_replace(' ','_',$datos[$i][14]).'_'.str_replace(' ','_',$datos[$i][16]),$edi_b_icon,'html');
				$val[] = array($datos[$i][1].'_'.$datos[$i][3].'_'.str_replace(':','',$datos[$i][11]).'_'.$edit_tit.'_'.$edit_c_subtit.'_'.str_replace(' ','_',$datos[$i][14]).'_'.str_replace(' ','_',$datos[$i][16]),$edi_c_check,'html');
			}
		}
		$fn[] = array('calcular_nrofilas_tbl()');
		$fn[] = array('calcular_ids_combinardatos()');
		$fn[] = array('calcular_cindex_combinardatos()');
		
		
		//LLEGADA DE DATOS ------------
		
		$val[] = array('c_tablarpta_llegadadatos','<div id="div_tbl_llegadadatos_leyenda">LEYENDA</div><table id="c_tbl_llegadadatos" class="display3" border="1" cellpadding="0" cellspacing="0"></table>','html');
		
		$ns = 'pkg_senamhi.sp_obt_tbl_estac_criterio_dat';
		
		$psparam_llegadadatos[] = array(':p_coddirreg',$coddirreg);
		$psparam_llegadadatos[] = array(':p_codestacion',$codestacion);		
		$psparam_llegadadatos[] = array(':p_fecha_ini',$fechaini);
		$psparam_llegadadatos[] = array(':p_fecha_fin',$fechafin);
		$datos_llegadadatos = $cn->ejec_store_procedura_oracle($ns,$psparam_llegadadatos);
			
//		print_r($datos_llegadadatos);
		$fechas = array();
		$horas = array();
		$estaciones = array();
		
		for($i=0;$i<count($datos_llegadadatos);$i++){
			$fechas[] = $datos_llegadadatos[$i][4];
			$horas[] = $datos_llegadadatos[$i][6];
			$estaciones[] = array($datos_llegadadatos[$i][0],$datos_llegadadatos[$i][1],$datos_llegadadatos[$i][2],$datos_llegadadatos[$i][3]);
		}
//		$fechas = array_values(array_unique($fechas,SORT_REGULAR));
//		$horas = array_values(array_unique($horas,SORT_REGULAR));
//		$estaciones = array_values(array_unique($estaciones,SORT_REGULAR));
		
		$fechas = array_values($this->view->util()->multi_array_unique($fechas));	
		$horas = array_values($this->view->util()->multi_array_unique($horas));	
		$estaciones = array_values($this->view->util()->multi_array_unique($estaciones));	
		
		$tbl_cab_l1 = '';
		$tbl_cab_l2 = '';
		$tbl_body = '';
			for($i = 0 ; $i<count($fechas) ; $i++){
				$tbl_cab_l1 .= '<th class="ui-state-default" colspan="'.count($horas).'">'.$fechas[$i].'</th>';	
				for($j = 0 ; $j<count($horas) ; $j++){
						$tbl_cab_l2 .= '<th>'.$horas[$j].'</th>';
				}			
			}
			
			for($i = 0 ; $i<count($estaciones) ; $i++){
				$tbl_body .= '<tr><td>'.$estaciones[$i][1].'</td>';	
				for($j = 0 ; $j<count($fechas) ; $j++){
					for($k = 0 ; $k<count($horas) ; $k++){
						$tbl_body .= '<td id="'.$estaciones[$i][0].'_'.$fechas[$j].'_'.$horas[$k].'">'.'</td>';
					}
				}
				$tbl_body .= '</tr>';			
			}
					
		$tbl = '';
		$tbl.= '<thead><tr><th rowspan="2">Estaci&oacute;n</th>'.$tbl_cab_l1.'</tr><tr>'.$tbl_cab_l2.'</tr></thead><tbody>'.$tbl_body.'</tbody>';
		
		$val[] = array('c_tbl_llegadadatos',$tbl,'html');
				
			$url = $this->view->util()->getPath();
			
			$ok = $url.'img/ok.png';
			$sindatos = $url.'img/minus.png';
			$datosfaltantes = $url.'img/alerta_amarilla.png';
			$error = $url.'img/alerta.gif';
			
			$leyenda  = '<img src="'.$ok.'" height="20px" width="20px" /> Datos Completos.';
			$leyenda .= '/<img src="'.$sindatos.'" height="20px" width="20px" /> Sin Informaci&oacute;n.';
			$leyenda .= '/<img src="'.$datosfaltantes.'" height="20px" width="20px" /> Datos Incompletos.';
			$leyenda .= '/<img src="'.$error.'" height="20px" width="20px" /> Error.';
			
			$val[] = array('div_tbl_llegadadatos_leyenda',$leyenda,'html');
			
			for($i = 0 ; $i<count($datos_llegadadatos) ; $i++){
				$icon = '';
				if($datos_llegadadatos[$i][10] == '0')
					$icon = $sindatos;
				if($datos_llegadadatos[$i][10] == '1')
					$icon = $ok;
				if($datos_llegadadatos[$i][10] == '2')
					$icon = $datosfaltantes;
				if($datos_llegadadatos[$i][10] == '3')
					$icon = $error;

					
				$icon = '<img src="'.$icon.'" height="20px" width="20px" onclick="buscartabladatoshidro(\''.$datos_llegadadatos[$i][0].'\',\''.$datos_llegadadatos[$i][4].'\',\''.$datos_llegadadatos[$i][6].'\')" />';	
				$val[] = array($datos_llegadadatos[$i][0].'_'.$datos_llegadadatos[$i][4].'_'.$datos_llegadadatos[$i][6],$icon,'html');
			}
		
		$func->PintarValor($val);
				
//		//tablas
		$datatable_opciones_datoshidro[] = array('bFilter','true');
		$datatable_opciones_datoshidro[] = array('bLengthChange','false');
		$datatable_opciones_datoshidro[] = array('bInfo','true');
		$datatable_opciones_datoshidro[] = array('bPaginate','true');		
		$datatable_opciones_datoshidro[] = array('sPaginationType','"full_numbers"');
		$datatable_opciones_datoshidro[] = array('iDisplayLength',count($lateral));
		$datatable_opciones_datoshidro[] = array('bJQueryUI','true');
		$datatable_opciones_datoshidro[] = array('bSort','false');
		$datatable_opciones_datoshidro[] = array('bScrollCollapse','false');
			$datatable_opciones_datoshidro[] = array('sScrollY','"'.($height - 400).'px"');
//		$datatable_opciones_datoshidro[] = array('aaData',json_encode($array));
		$datatable_opciones_datoshidro[] = array('aaSorting',json_encode(array(array(1,'asc'),array(2,'asc'))));
		
		if($codhora == ''){
			$datatable_opciones_datoshidro[] = array('aoColumnDefs',json_encode(array(
																		array('sClass'=>'center' ,'aTargets'=>array(4,5,6,7,8,9,10,11,12,13,14,15,16,17,18))
																	    ,array('sWidth'=>'70px' ,'aTargets'=>array(1))
																	    ,array('bVisible'=>false ,'aTargets'=>array(0))
																	   )));

     		$datatable_opciones_datoshidro[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){
     																							if (rtrim(aData[4]) == ""){$("td:eq(3)", nRow).addClass("ui-state-highlight" );}
     																							if (rtrim(aData[5]) == ""){$("td:eq(4)", nRow).addClass("ui-state-highlight" );}
     																							if (rtrim(aData[10]) == ""){$("td:eq(9)", nRow).addClass("ui-state-highlight" );}     																							
     																							if( parseInt($("td:eq(11)", nRow).html()) ){$("td:eq(11)", nRow).html(parseInt($("td:eq(11)", nRow).html()));} 
     																							if( parseInt($("td:eq(14)", nRow).html()) ){$("td:eq(14)", nRow).html(parseInt($("td:eq(14)", nRow).html()));}
     																							if( parseInt($("td:eq(16)", nRow).html()) ){$("td:eq(16)", nRow).html(parseInt($("td:eq(16)", nRow).html()));}
     																							if( parseInt($("td:eq(17)", nRow).html()) ){$("td:eq(17)", nRow).html(parseInt($("td:eq(17)", nRow).html()));}  
     																						  }');
		}else{
			$datatable_opciones_datoshidro[] = array('aoColumnDefs',json_encode(array(
																		array('sWidth'=>'70px' ,'aTargets'=>array(1))
																	    ,array('bVisible'=>false ,'aTargets'=>array(0))
																	   )));
		}

     	
		$datatable_opciones_datoshidro[] = array('fnDrawCallback','function(oSettings){if( oSettings.aiDisplay.length == 0 ){return;}var nTrs = $("#c_tbl_est_crit_val tbody tr");var iColspan = nTrs[0].getElementsByTagName("td").length;var sLastGroup = "";for ( var i=0 ; i<nTrs.length ; i++ ){var iDisplayIndex = oSettings._iDisplayStart + i;var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];if ( sGroup != sLastGroup ){var nGroup = document.createElement( "tr" );var nCell = document.createElement( "td" );nCell.colSpan = iColspan;nCell.className = "ui-state-default";nCell.innerHTML = sGroup;nGroup.appendChild( nCell );nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );sLastGroup = sGroup;}}}');
		$datatable_opciones_datoshidro[] = array('sDom','\'<"H"Tfr>t<"F"ip>\'');
//		$datatable_opciones_datoshidro[] = array('oTableTools','{"aButtons": [{"sExtends": "copy","sButtonText": "Copy to clipboard"},{"sExtends": "csv","sButtonText": "Save to CSV"},{"sExtends": "xls","sButtonText": "Save for Excel"}]}');
		$datatable_opciones_datoshidro[] = array('oTableTools','{"aButtons": [{"sExtends": "collection","sButtonText": "Guardar","aButtons":[ "xls"]}]}');
		echo $this->view->util()->datatable("c_tbl_est_crit_val",$datatable_opciones_datoshidro);
		
		$datatable_opciones_llegadahidro[] = array('bFilter','true');
		$datatable_opciones_llegadahidro[] = array('bLengthChange','false');
		$datatable_opciones_llegadahidro[] = array('bInfo','true');
		$datatable_opciones_llegadahidro[] = array('bPaginate','true');		
		$datatable_opciones_llegadahidro[] = array('sPaginationType','"full_numbers"');
		$datatable_opciones_llegadahidro[] = array('iDisplayLength','99999');
		$datatable_opciones_llegadahidro[] = array('bJQueryUI','true');
		$datatable_opciones_llegadahidro[] = array('bSort','false');
		$datatable_opciones_llegadahidro[] = array('bScrollCollapse','false');
		$datatable_opciones_llegadahidro[] = array('sScrollY','"'.($height - 400).'px"');
//		$datatable_opciones_datoshidro[] = array('aaData',json_encode($array));
		$datatable_opciones_llegadahidro[] = array('aaSorting',json_encode(array(array(1,'asc'),array(2,'asc'))));
    	
		//$datatable_opciones_llegadahidro[] = array('fnDrawCallback','function(oSettings){if( oSettings.aiDisplay.length == 0 ){return;}var nTrs = $("#c_tbl_est_crit_val tbody tr");var iColspan = nTrs[0].getElementsByTagName("td").length;var sLastGroup = "";for ( var i=0 ; i<nTrs.length ; i++ ){var iDisplayIndex = oSettings._iDisplayStart + i;var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];if ( sGroup != sLastGroup ){var nGroup = document.createElement( "tr" );var nCell = document.createElement( "td" );nCell.colSpan = iColspan;nCell.className = "ui-state-default";nCell.innerHTML = sGroup;nGroup.appendChild( nCell );nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );sLastGroup = sGroup;}}}');
		$datatable_opciones_llegadahidro[] = array('sDom','\'<"H"Tfr>t<"F"ip>\'');
		$datatable_opciones_llegadahidro[] = array('oTableTools','{"aButtons": [{"sExtends": "collection","sButtonText": "Guardar","aButtons":[ "xls"]}]}');
		echo $this->view->util()->datatable("c_tbl_llegadadatos",$datatable_opciones_llegadahidro);
		
			
		//METEOROS------------
		
		$ns = 'pkg_senamhi.sp_obt_tbl_estac_meteoros';
		$psparam_met[] = array(':p_coddirreg',$coddirreg);
		$psparam_met[] = array(':p_codestacion',$codestacion);		
		$psparam_met[] = array(':p_fecha_ini',$fechaini);
		$psparam_met[] = array(':p_fecha_fin',$fechafin);
		$datosmeteoros = $cn->ejec_store_procedura_oracle($ns,$psparam_met);
		
		$arraymeteoros = array();
		
		for($i=0;$i<count($datosmeteoros);$i++){
				$arraymeteoros[] = array($datosmeteoros[$i][1],
						                 $datosmeteoros[$i][2],
						                 $datosmeteoros[$i][3],
						                 $datosmeteoros[$i][4],
						                 $datosmeteoros[$i][7]
						                 );
			}
	
		//tablas
		$datatable_opciones_meteoros[] = array('bFilter','true');
		$datatable_opciones_meteoros[] = array('bLengthChange','false');
		$datatable_opciones_meteoros[] = array('bInfo','true');
		$datatable_opciones_meteoros[] = array('bPaginate','true');		
		$datatable_opciones_meteoros[] = array('sPaginationType','"full_numbers"');
		$datatable_opciones_meteoros[] = array('iDisplayLength',count($lateral));
		$datatable_opciones_meteoros[] = array('bJQueryUI','true');
		$datatable_opciones_meteoros[] = array('bSort','false');
		$datatable_opciones_meteoros[] = array('bScrollCollapse','false');
		$datatable_opciones_meteoros[] = array('sScrollY','"'.($height - 380).'px"');
		$datatable_opciones_meteoros[] = array('aaData',json_encode($arraymeteoros));
		$datatable_opciones_meteoros[] = array('aaSorting',json_encode(array(array(1,'asc'),array(2,'asc'))));
		$datatable_opciones_meteoros[] = array('aoColumns',json_encode(array(       
                                                                            array('sTitle'=>'Estacion')
                                                                            ,array('sTitle'=>'Fecha')
                                                                            ,array('sTitle'=>'Hora de Envio')
                                                                            ,array('sTitle'=>'Hora de Ocurrencia')
                                                                            ,array('sTitle'=>'Meteoro')
                                                                            )
                                                                        ));
		$datatable_opciones_meteoros[] = array('aoColumnDefs',json_encode(array(
																		array('bVisible'=>false ,'aTargets'=>array(0))
																	   )));
$datatable_opciones_meteoros[] = array('fnDrawCallback','function(oSettings){if( oSettings.aiDisplay.length == 0 ){return;}var nTrs = $("#c_tbl_est_meteoros tbody tr");var iColspan = nTrs[0].getElementsByTagName("td").length;var sLastGroup = "";for ( var i=0 ; i<nTrs.length ; i++ ){var iDisplayIndex = oSettings._iDisplayStart + i;var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];if ( sGroup != sLastGroup ){var nGroup = document.createElement( "tr" );var nCell = document.createElement( "td" );nCell.colSpan = iColspan;nCell.className = "ui-state-default";nCell.innerHTML = sGroup;nGroup.appendChild( nCell );nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );sLastGroup = sGroup;}}}');
		$datatable_opciones_meteoros[] = array('sDom','\'<"H"Tfr>t<"F"ip>\'');
$datatable_opciones_meteoros[] = array('oTableTools','{"aButtons": [{"sExtends": "collection","sButtonText": "Guardar","aButtons":[ "xls"]}]}');
		echo $this->view->util()->datatable("c_tbl_est_meteoros",$datatable_opciones_meteoros);
		
		
		if(count($fn)>0)
			$func->EjecutarFuncion($fn);
		if(count($evt)>0)
			$func->PintarEvento($evt);
			
	}
	
	/*--------------------------------------------------------------------------------------------------------------------------------------------*/
	
	
	public function obtcaracparamAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$codgrupo= $this->_request->getPost ( 'codgrupo' );
			$codparam= $this->_request->getPost ( 'codparam' );	
			$codcriterio= $this->_request->getPost ( 'codcriterio' );	
			
			$func = new Libreria_Pintar();
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_parametros';
			$ps[] = array(':p_cod_parag',$codgrupo);
			$ps[] = array(':p_cod_corrp',$codparam);
			$ps[] = array(':p_cod_corparahm',$codcriterio);
			$ps[] = array(':p_graf','');		
			$datos = $cn->ejec_store_procedura_oracle($ns,$ps);
			echo json_encode($datos);

		}
	}
	
	public function obttemperaturacolorAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$codgrupo= $this->_request->getPost ( 'codgrupo' );
			$codparam= $this->_request->getPost ( 'codparam' );	
			$codcriterio= $this->_request->getPost ( 'codcriterio' );	
			
			$func = new Libreria_Pintar();
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_temperaturacolor';
			$datos = $cn->ejec_store_procedura_oracle($ns,null);
			echo json_encode($datos);

		}
	}
	
	public function borrardatosestacionhorasinopAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$codestacion= $this->_request->getPost ( 'codestacion' );
			$fecha= $this->_request->getPost ( 'fecha' );	
			$hora= $this->_request->getPost ( 'hora' );	
			
			$func = new Libreria_Pintar();
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_borrar_datosestacion';
			$ps[] = array(':p_cod_estacion',$codestacion);
			$ps[] = array(':p_fecha',$fecha);
			$ps[] = array(':p_horaing',$hora);
			$datos = $cn->ejec_store_procedura_oracle($ns,$ps);
			echo $datos[0][0];

		}
	}
	
	public function ventanacombinardatoshidrometeorologicosAction() {
		//ventana combinar datos hidrometeorologicos Action
		//$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
					
		$func = new Libreria_Pintar();
		$fn[] = array('$("button").button();');	
		
		
		$json= $this->_request->getParam('json','');
//		echo $json;
//		echo '<br><br>';
		$datoscombinacion = json_decode($json);
//		print_r($datoscombinacion);
//		echo '<br><br>';
		$codestacion = array();
		$estacion = array();
		$fecha = array();
		$horareg = array();
		$horasinoptica = array();		
		for($i=0;$i<count($datoscombinacion);$i++){
//			echo $datoscombinacion[$i][0] . '->' . $datoscombinacion[$i][1] . '->' . $datoscombinacion[$i][2]. '->' . $datoscombinacion[$i][3]. '->' . $datoscombinacion[$i][4] .'<br>';
			$codestacion[] = array($datoscombinacion[$i][0],$datoscombinacion[$i][0]);
			$estacion[] = array($datoscombinacion[$i][1],$datoscombinacion[$i][1]);
			$fecha[] = array($datoscombinacion[$i][2],$datoscombinacion[$i][2]);
			$horareg[] = array($datoscombinacion[$i][3],$datoscombinacion[$i][3]);
			$horasinoptica[] = array($datoscombinacion[$i][4],$datoscombinacion[$i][4]);
		}
		$codestacion = array_values($this->view->util()->multi_array_unique($codestacion));
		$fecha = array_values($this->view->util()->multi_array_unique($fecha));
		$horareg = array_values($this->view->util()->multi_array_unique($horareg));
		$horasinoptica = array_values($this->view->util()->multi_array_unique($horasinoptica));
		
		$msjerror = "";
		
		if(count($codestacion)>1)
			$msjerror .= 'Seleccione solo una estaci&oacute;n ah editar.<br>';
		if(count($fecha)>1)	
			$msjerror .= 'Seleccione solo una fecha para editar.<br>';
		if(count($horasinoptica)>1)	
			$msjerror .= 'Seleccione solo datos de una hora sinoptica espec&iacute;fica para editar.<br>';

		if(trim($msjerror) != ''){
			$val[] = array('div_mensaje_editor',$msjerror,'html');
			$fn[] = array("$('#div_mensaje_editor').addClass('ui-state-error')");
		}else{
					$val[] = array('editor_codestacion',$codestacion[0][0],'html');
					$val[] = array('editor_estacion',$estacion[0][0],'html');		
					$val[] = array('editor_fecha',$fecha[0][0],'html');	
					$val[] = array('editor_hora_sinop',$horasinoptica[0][0],'html');
					$val[] = array('cb_editor_horareg',$func->ContenidoCombo($horareg,$horareg[0][0]),'html');
						
					$ddatoshidro = new Zend_Session_Namespace('datoshidrometeorologicos');				
					$datossession = $ddatoshidro->data ;
					
					$datos = array();
					for($i=0;$i<count($datossession);$i++){
						for($j=0;$j<count($datoscombinacion);$j++){
							if($datossession[$i][0] == $datoscombinacion[$j][0] && $datossession[$i][1] == $datoscombinacion[$j][2] && $datossession[$i][11] == $datoscombinacion[$j][3])
								$datos[] =  $datossession[$i];
						}	
					}
					
//					print_r($datos);
					
						//cabecera
					$cabecera = array();
					$cabecera_grupos = array();
					for($i = 0 ; $i<count($datos) ; $i++){
						$cabecera[] = array($datos[$i][5],$datos[$i][6],$datos[$i][8],$datos[$i][12],$datos[$i][13],array());
						$cabecera_grupos[] = array($datos[$i][6],0 , $datos[$i][12]);				
					}
					
					$cabecera = array_values($this->view->util()->multi_array_unique($cabecera));
					$cabecera = $this->view->util()->ordenar_array($cabecera, 3, SORT_ASC, 4, SORT_ASC );
					
					$cabecera_grupos = array_values($this->view->util()->multi_array_unique($cabecera_grupos));	
					$cabecera_grupos = $this->view->util()->ordenar_array($cabecera_grupos, 2, SORT_ASC );
					
					for($i = 0 ; $i<count($cabecera_grupos) ; $i++){
						for($j = 0 ; $j<count($cabecera) ; $j++){
							if($cabecera_grupos[$i][0] == $cabecera[$j][1])
								$cabecera_grupos[$i][1] = $cabecera_grupos[$i][1] + 1;			
						}				
					}
					
					
					
					$tbl = '<table border="1" cellpadding="0" cellspacing="0">';
					
					$tbl .= '<thead>';
					$tbl .= '<tr>';
						$tbl .= '<th width="120px">TIPO</th>';
						$tbl .= '<th width="300px">VARIABLE</th>';
						$tbl .= '<th width="120px">VALOR</th>';
					$tbl .= '</tr>';
					$tbl .= '</thead>';
					$tbl .= '<tbody>';
						$cant_row_group = 0;
						for($i = 0 ; $i<count($cabecera) ; $i++){
							$tbl .= '<tr>';
								
								if($cant_row_group == 0){
									for($j = 0 ; $j<count($cabecera_grupos) ; $j++){					
										if($cabecera_grupos[$j][2] == $cabecera[$i][3]){
											$tipo = $cabecera_grupos[$j][0];
											$cant_row_group = $cabecera_grupos[$j][1];
										}						
									}
									$tbl .= '<td rowspan="'.$cant_row_group.'" valign="middle">'.$tipo.'</td>';
								}
								$cant_row_group = $cant_row_group - 1;
										
								
								$tbl .= '<td>'.$cabecera[$i][2].'</td>';
								$tbl .= '<td><select class="cb_editor_datos" id="cb_editor_datos_'.str_replace(' ','_',$cabecera[$i][2]).'" style="width:80%">';
									for($j = 0 ; $j<count($datos) ; $j++){
										if($cabecera[$i][2] == $datos[$j][8]){
//											if(trim($datos[$j][9]) != ''){																							
//											}else{
												$tbl .= '<option grupo="'.$datos[$j][5].'" corr="'.$datos[$j][7].'" tipo="'.$datos[$j][6].'" param="'.$datos[$j][8].'" codestacion="'.$datos[$j][0].'" estacion="'.$datos[$j][14].'" ddrr="'.$datos[$j][16].'" fecha="'.$datos[$j][1].'" codhora="'.$datos[$j][3].'" >'.$datos[$j][9].'</option>';
//											}
										}
									}
								$tbl .= '</select></td>';
							$tbl .= '</tr>';
						}
					$tbl .= '</tbody>';
					$tbl .= '</table>';
					
					$val[] = array('editor_tbl',$tbl,'html');
					
		}
		
		$evt[] = array('btn_guardar_editor','click',"guardarcombinardatoshidrometeorologicos('".json_encode($datoscombinacion)."');");
		$evt[] = array('btn_salir_editor','click','cerrarsubvent2();');
		
		$func->PintarValor($val);
		$func->PintarEvento($evt);
		$func->EjecutarFuncion($fn);
	}
	
	public function guardarcombinardatoshidrometeorologicosAction() {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$codestacion= $this->_request->getPost ( 'codestacion' );
			$fecha= $this->_request->getPost ( 'fecha' );	
			$xmlhoras= $this->_request->getPost ( 'xmlhoras' );
			$xmldatos= $this->_request->getPost ( 'xmldatos' );			
			
			$func = new Libreria_Pintar();
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_act_combinar_ingmov';
			$ps[] = array(':p_codest',$codestacion);
			$ps[] = array(':p_fecha',$fecha);
			$ps[] = array(':p_xmlhoras',$xmlhoras);
			$ps[] = array(':p_xmldatos',$xmldatos);		
			$datos = $cn->ejec_store_procedura_oracle($ns,$ps);
			echo json_encode(array($datos[0][0],$datos[0][1]));

		}
	}
	
}


