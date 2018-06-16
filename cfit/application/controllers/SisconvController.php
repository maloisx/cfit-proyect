<?php

/**
 * SisconvController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class SisconvController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	//hacemos la llamada al JS: js_sisconv
	public function init() {
		$this->view->util()->registerScriptJSController($this->getRequest());		
		
		$url = $this->view->util()->getPath();
		$ddatosuserlog = new Zend_Session_Namespace('datosuserlog');
		$codemp = $ddatosuserlog->codemp;
		$nompers = $ddatosuserlog->nombre . ' '. $ddatosuserlog->apepat . ' ' . $ddatosuserlog->apemat;

		if($codemp == '' || $codemp == null){
			$this->_redirect($url.'index.php/logeo/');
		}
		
	}	
	
	public function indexAction() {
		
	}
	
	public function tipconvAction() {
		
		echo $this->view->util()->SetTitlePag('MANTENIMIENTO TIPO DE CONVENIO');
		
		$func = new Libreria_Pintar();
		
		$listardatos = trim($this->_request->getPost('listardatos'));
		if($listardatos == '1'){
			$this->_helper->layout->disableLayout();//quitamos el layout
			$this->_helper->viewRenderer->setNoRender (); //deshabilitamos la vista
			
			//al deshabilitar la vista, debemos de volver a escribir la tabla
			echo '<table id="tbl_tipconv" border="0" cellpadding="0" cellspacing="0" width="500px"></table>';
			
		}else{
			$evt[] = array('btn_nuevo','click',"mant_tipconv('');");
			$func->PintarEvento($evt);
		}
		
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.sp_obt_tipconv';
	    $arraydatos[] = array(':p_c_tipconv','');
		
	    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    $datos_finales = null;
	    for($i=0;$i<count($datos);$i++){
	    	$datos_finales[] = array(
	    								$datos[$i][0],
	    								$datos[$i][1],
	    								'<ul><li class="ui-state-default ui-corner-all ui-state-hover" onclick="mant_tipconv(\''.$datos[$i][0].'\')" title=".ui-icon-search"><span class="ui-icon ui-icon-search"></span></li></ul>'
	    							 );
	    }	
	    
		$datatable_opciones[] = array('bFilter','false');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode($datos_finales));
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Cod','sWidth'=>'50px'),
										array('sTitle'=>'Tipo Convenio','sWidth'=>'830px'),
										array('sTitle'=>'-','sWidth'=>'20px')
										)));			
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_tipconv",$datatable_opciones);	
	}
	
	public function tipconvmantAction() {
		$this->_helper->layout->disableLayout();
		$c_tipconv = trim($this->_request->getParam('c_tipconv'));
		
		$func = new Libreria_Pintar();
		
		if($c_tipconv != ''){
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_tipconv';
	   	 	$arraydatos[] = array(':p_c_tipconv',$c_tipconv);
		
	    	$datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    	$val[] = array('c_tipconv',$datos[0][0],'html');
	    	$val[] = array('d_tipconv',$datos[0][1],'val');
	    	$func->PintarValor($val);
		}
		
		
		
		$exec[] = array("$('button').button();");		
		$evt[] = array('btn_cancelar','click','cerrarsubvent();');
		$evt[] = array('btn_guardar','click','guardar_tipconv();');
		
		$func->EjecutarFuncion($exec);
		$func->PintarEvento($evt);
		
	}
	
	public function guardartipconvAction(){
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) 
		{			
			//obtenemos parametros
			$c_tipconv = trim($this->_request->getPost('c_tipconv'));
			$d_tipconv = trim($this->_request->getPost('d_tipconv'));
			
			//llamamos a la coneccion de la bd
			$cn = new Model_DataAdapter();
			//colocamos el nombre del procedure
			$ns = 'pkg_senamhi.sp_ins_act_tipconv';
			//parametros del procedure
		    $arraydatos[] = array(':p_c_tipconv',$c_tipconv);
		    $arraydatos[] = array(':p_d_tipconv',$d_tipconv);
		    //ejecutamos el procedure!!!
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
		    //obtenemos la respuesta :) 
		    echo $datos[0][0]; // esto va al javascript    
		}
	}
	
	public function sectorAction() {
		
		echo $this->view->util()->SetTitlePag('MANTENIMIENTO SECTOR');
		
		$func = new Libreria_Pintar();
		
		$listardatos = trim($this->_request->getPost('listardatos'));
		if($listardatos == '1'){
			$this->_helper->layout->disableLayout();//quitamos el layout
			$this->_helper->viewRenderer->setNoRender (); //deshabilitamos la vista
			
			//al deshabilitar la vista, debemos de volver a escribir la tabla
			echo '<table id="tbl_sector" border="0" cellpadding="0" cellspacing="0" width="500px"></table>';
			
		}else{
			$evt[] = array('btn_nuevo','click',"mant_sector('');");
			$func->PintarEvento($evt);
		}
		
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.sp_obt_sector';
	    $arraydatos[] = array(':p_c_sector','');
		
	    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    $datos_finales = array();
	    for($i=0;$i<count($datos);$i++){
	    	$datos_finales[] = array(
	    								$datos[$i][0],
	    								$datos[$i][1],
	    								'<ul><li class="ui-state-default ui-corner-all ui-state-hover" onclick="mant_sector(\''.$datos[$i][0].'\')" title=".ui-icon-search"><span class="ui-icon ui-icon-search"></span></li></ul>'
	    							 );
	    }	
	    
		$datatable_opciones[] = array('bFilter','false');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode($datos_finales));
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Cod','sWidth'=>'50px'),
										array('sTitle'=>'Sector','sWidth'=>'830px'),
										array('sTitle'=>'-','sWidth'=>'20px')
										)));			
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_sector",$datatable_opciones);	
	}
	
	public function sectormantAction() {
		$this->_helper->layout->disableLayout();
		$c_sector = trim($this->_request->getParam('c_sector'));
		
		$func = new Libreria_Pintar();
		
		if($c_sector != ''){
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_sector';
	   	 	$arraydatos[] = array(':p_c_sector',$c_sector);
		
	    	$datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos); 
	    	$val[] = array('c_sector',$datos[0][0],'html');
	    	$val[] = array('d_sector',$datos[0][1],'val');
	    	$func->PintarValor($val);
		}
		
		$exec[] = array("$('button').button();");		
		$evt[] = array('btn_cancelar','click','cerrarsubvent();');
		$evt[] = array('btn_guardar','click','guardar_sector();');
		
		$func->EjecutarFuncion($exec);
		$func->PintarEvento($evt);
		
	}
	
	public function guardarsectorAction(){
	
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
		
			//obtenemos parametros
			$c_sector = trim($this->_request->getPost('c_sector'));
			$d_sector = trim($this->_request->getPost('d_sector'));
			
			//llamamos a la coneccion de la bd
			$cn = new Model_DataAdapter();
			//colocamos el nombre del procedure
			$ns = 'pkg_senamhi.sp_ins_act_sector';
			//parametros del procedure
		    $arraydatos[] = array(':p_c_sector',$c_sector);
		    $arraydatos[] = array(':p_d_sector',$d_sector);
		    //ejecutamos el procedure!!!
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
		    //obtenemos la respuesta :) 
		    echo $datos[0][0]; // esto va al javascript  
		
		}  
	}

public function entidadAction() {
		
	echo $this->view->util()->SetTitlePag('MANTENIMIENTO ENTIDAD');
	
		$func = new Libreria_Pintar();
		
		$listardatos = trim($this->_request->getPost('listardatos'));
		$buscar = $this->_request->getParam('buscar','');
		
		if($listardatos == '1'){
			$this->_helper->layout->disableLayout();//quitamos el layout
			$this->_helper->viewRenderer->setNoRender (); //deshabilitamos la vista
			
			//al deshabilitar la vista, debemos de volver a escribir la tabla
			echo '<table id="tbl_entidad" border="0" cellpadding="0" cellspacing="0" width="800px"></table>';
			
		}else if($buscar == '1'){
			$this->_helper->layout->disableLayout();
			$evt[] = array('btn_nuevo_entidad','hide',"");
			$func->PintarEvento($evt);
		}else{
			$evt[] = array('btn_nuevo_entidad','click',"mant_entidad('');");
			$func->PintarEvento($evt);
		}
		
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.sp_obt_entidad';
	    $arraydatos[] = array(':p_c_entidad','');
		
	    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    $datos_finales = array();
	    for($i=0;$i<count($datos);$i++){
	    	
	    	if($buscar == '1')
	    		$btn = '<ul><li class="ui-state-default ui-corner-all ui-state-hover" onclick="aniadir_entidad(\''.$datos[$i][0].'\',\''.$datos[$i][1].'\')" title=".ui-icon-search"><span class="ui-icon ui-icon-check"></span></li></ul>';
	    	else
	    		$btn = '<ul><li class="ui-state-default ui-corner-all ui-state-hover" onclick="mant_entidad(\''.$datos[$i][0].'\')" title=".ui-icon-search"><span class="ui-icon ui-icon-search"></span></li></ul>';
	    	$datos_finales[] = array(
	    								$datos[$i][0],
	    								$datos[$i][1],
	    								$datos[$i][2],
	    								$datos[$i][4],
	    								$btn
	    							 );
	    }	
	    
		$datatable_opciones[] = array('bFilter','true');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode($datos_finales));
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Cod','sWidth'=>'50px'),
										array('sTitle'=>'Entidad','sWidth'=>'450px'),
										array('sTitle'=>'Ruc','sWidth'=>'100px'),
										array('sTitle'=>'Sector','sWidth'=>'80px'),
										array('sTitle'=>'-','sWidth'=>'20px')
										)));			
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_entidad",$datatable_opciones);	
	}
	
	public function entidadmantAction() {
		$this->_helper->layout->disableLayout();
		$c_entidad = trim($this->_request->getParam('c_entidad'));
		
		$cn = new Model_DataAdapter();
		$func = new Libreria_Pintar();
		
		$ns = 'pkg_senamhi.sp_obt_sector';
    	$arraydatos_sector[] = array(':p_c_sector','');
    	$datos_sector = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_sector);
		
		if($c_entidad != ''){
			
			$ns = 'pkg_senamhi.sp_obt_entidad';
	   	 	$arraydatos[] = array(':p_c_entidad',$c_entidad);
			
	   	 	$datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    	$val[] = array('c_entidad',$datos[0][0],'html');
	    	$val[] = array('d_entidad',$datos[0][1],'val');
	    	$val[] = array('d_ruc',$datos[0][2],'val');
	    	$val[] = array('cbsector',$func->ContenidoCombo($datos_sector,$datos[0][3]),'html');   		
	    	
		}else{
			$val[] = array('cbsector',$func->ContenidoCombo($datos_sector,null),'html');
		}
		
		
		
		$exec[] = array("$('button').button();");		
		$evt[] = array('btn_cancelar','click','cerrarsubvent();');
		$evt[] = array('btn_guardar','click','guardar_entidad();');
		
		$func->EjecutarFuncion($exec);
		$func->PintarEvento($evt);
		$func->PintarValor($val);
		
	}
	
public function guardarentidadAction(){
	
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
		
			$c_entidad = trim($this->_request->getPost('c_entidad'));
			$d_entidad = trim($this->_request->getPost('d_entidad'));
			$d_ruc = trim($this->_request->getPost('d_ruc'));
			$c_sector = trim($this->_request->getPost('c_sector'));
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_ins_act_entidad';
		    $arraydatos[] = array(':p_c_entidad',$c_entidad);
		    $arraydatos[] = array(':p_d_entidad',$d_entidad);
		    $arraydatos[] = array(':p_c_ruc',$d_ruc);
		    $arraydatos[] = array(':p_c_sector',$c_sector);
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);

		    echo $datos[0][0]; 
		
		}  
	}
	
public function convenioAction() {
		
		echo $this->view->util()->SetTitlePag('MANTENIMIENTO CONVENIO');
	
		$func = new Libreria_Pintar();
		
		$listardatos = trim($this->_request->getPost('listardatos'));
		if($listardatos == '1'){
			$this->_helper->layout->disableLayout();//quitamos el layout
			$this->_helper->viewRenderer->setNoRender (); //deshabilitamos la vista
			
			//al deshabilitar la vista, debemos de volver a escribir la tabla
			echo '<table id="tbl_convenio" border="0" cellpadding="0" cellspacing="0" width="1200px"></table>';
			
		}else{
			$evt[] = array('btn_nuevo','click',"mant_convenio('');");
			$func->PintarEvento($evt);
		}
		
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.sp_obt_conv_cab';
	    $arraydatos[] = array(':p_c_conv','');
	    $arraydatos[] = array(':p_c_emp','');
	    $arraydatos[] = array(':p_c_tipconv','');
//		
	    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    $datos_finales = array();
	    for($i=0;$i<count($datos);$i++){
	    	$datos_finales[] = array(
	    								$datos[$i][0],
	    								$datos[$i][3],
	    								$datos[$i][1],
	    								$datos[$i][14],
	    								$datos[$i][7],
	    								'<ul><li class="ui-state-default ui-corner-all ui-state-hover" onclick="mant_convenio(\''.$datos[$i][0].'\')" title=".ui-icon-search"><span class="ui-icon ui-icon-search"></span></li></ul>'
	    							 );
	    }	
	    $datatable_opciones[] = array('bFilter','true');
		$datatable_opciones[] = array('bLengthChange','true');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','true');	
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('sScrollY','"500px"');
		$datatable_opciones[] = array('aaData',json_encode($datos_finales));
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Cod','sWidth'=>'50px'),
										array('sTitle'=>'Tipo Conv.','sWidth'=>'150px'),
										array('sTitle'=>'Nombre','sWidth'=>'300px'),
										array('sTitle'=>'Entidad','sWidth'=>'250px'),
										array('sTitle'=>'Ofic. Resp.','sWidth'=>'250px'),
										array('sTitle'=>'-','sWidth'=>'20px')
										)));			
//		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		
		echo $this->view->util()->datatable("tbl_convenio",$datatable_opciones);	
	}
	
	public function conveniomantAction() {
		$this->_helper->layout->disableLayout();
		
//		$this->view->util()->console('xxx');
		
		$c_convenio = trim($this->_request->getParam('c_convenio'));		
			if($c_convenio == ''){
				$c_convenio = '000000';	
			}
		$func = new Libreria_Pintar();
		
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.sp_obt_tipconv';
	    $arraydatos_tipconv[] = array(':p_c_tipconv','');		
	    $datos_tipconv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_tipconv);
	    
	    $ns = 'pkg_senamhi.sp_obt_entidad';
	    $arraydatos_entidad[] = array(':p_c_entidad','');		
	    $datos_entidad = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_entidad);
	    
	    $ns = 'pkg_senamhi.sp_obt_unidad';
	    $arraydatos_unidad[] = array(':p_c_unid','');		
	    $datos_unidad = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_unidad);
	    
	    $ns = 'pkg_senamhi.sp_obt_perconv';
	    $arraydatos_perconv[] = array(':p_c_perconv','');		
	    $datos_perconv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_perconv);
		
	    $ns = 'pkg_senamhi.sp_obt_conv_cab';
	    $arraydatos_conv[] = array(':p_c_conv',$c_convenio);
	    $arraydatos_conv[] = array(':p_c_emp','');		
	    $arraydatos_conv[] = array(':p_c_tipconv','');
	    $datos_conv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_conv);
	    	    
	    $exec[] = array("$('button').button();");	
		$exec[] = array("$('#tabs').tabs();");	
		$exec[] = array("rangosfechas_convenio();");	
	    
		$datos_tbl_oblig = array();
		$datos_entconv_final = array();
		
	    if(count($datos_conv) > 0 ){
	    	$val[] = array('c_convenio',$datos_conv[0][0],'html');	    	
	    	$val[] = array('d_convenio',$datos_conv[0][1],'val');
	    	$val[] = array('cbtipoconvenio',$func->contenidocombo($datos_tipconv,$datos_conv[0][2]),'html');
   
		    $val[] = array('d_resolucion',$datos_conv[0][4],'val');
		    $val[] = array('d_objetivo',$datos_conv[0][5],'val');
		     
		    $val[] = array('cb_perioeval',$func->contenidocombo($datos_perconv,$datos_conv[0][12]),'html');
		    $val[] = array('cbunidad',$func->contenidocombo($datos_unidad,$datos_conv[0][6]),'html');
		    
		    $ns = 'pkg_senamhi.sp_obt_emp_unid';
		    $arraydatos_emp[] = array(':p_c_unid',$datos_conv[0][6]);
		    $arraydatos_emp[] = array(':p_c_emp','');
		    $datos_emp = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_emp);
		    
		    $val[] = array('cb_responsable_senamhi',$func->contenidocombo($datos_emp,$datos_conv[0][8]),'html');
		    
		    $val[] = array('fecha_ini',$datos_conv[0][10],'val');
		    $val[] = array('fecha_fin',$datos_conv[0][11],'val');
		    
		    $ns = 'pkg_senamhi.sp_obt_entconv';
		    $arraydatos_entconv[] = array(':p_c_convenio',$datos_conv[0][0]);
		    $datos_entconv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_entconv);
		    $datos_entconv_final = array();
		    for($i=0;$i<count($datos_entconv);$i++){
		    	$datos_entconv_final[] = array(  '<input class="c_entconv" type="hidden" value="'.$datos_entconv[$i][0].'">'.
		    	                                 $datos_entconv[$i][1],
		    	                                 '<input type="text" id="resp_entconv_'.$datos_entconv[$i][0].'" value="'.$datos_entconv[$i][2].'" style="width:230px">' ,
		    	                                 '<ul><li class="ui-state-default ui-corner-all ui-state-hover" ><span class="ui-icon ui-icon-close btn_borraentconv" nro_entconv="" onclick="eliminar_entconv(this);"></span></li></ul>'
		    								   );
		    }
		    
		    $exec[] = array("actualizar_index_entconv();"); 
		    
		     $ns = 'pkg_senamhi.sp_obt_conv_oblig';
		     $arraydatos_oblig[] = array(':p_c_conv',$datos_conv[0][0]);		
		     $datos_oblig= $cn->ejec_store_oracle_sisconv($ns,$arraydatos_oblig);
		     
		     for($i=0;$i<count($datos_oblig);$i++){
		     	
		     	$exec[] = array("aniadir_obligacion('','".$datos_oblig[$i][0]."', '".$datos_oblig[$i][1]."' , '".$datos_oblig[$i][2]."' , '".$datos_oblig[$i][3]."' );");
		     }
		    
		     $ns = 'pkg_senamhi.sp_obt_conv_perioeval';
		     $arraydatos_eval[] = array(':p_c_conv',$c_convenio);		
			 $arraydatos_eval[] = array(':p_c_eval','');
		     $datos_eval= $cn->ejec_store_oracle_sisconv($ns,$arraydatos_eval);
		     $exec[] = array("cargar_perio_eval('".json_encode($datos_eval)."');"); 
		     
	    	 $ns = 'pkg_senamhi.sp_obt_conv_estacion';
		     $arraydatos_estacion[] = array(':p_c_conv',$c_convenio);		
		     $datos_estacion= $cn->ejec_store_oracle_sisconv($ns,$arraydatos_estacion);
		     for($i=0;$i<count($datos_estacion);$i++){
		     	$exec[] = array("aniadirestacion('".$datos_estacion[$i][0]."', '".$datos_estacion[$i][1]."' , '".$datos_estacion[$i][3]."');");
		     }
		     
		     /*documentos referentes*/
		     $exec[] = array("cargarlista_docref('".$datos_conv[0][0]."');"); 
		     
		     
		     //tipo de convenio y tabs
		     if($datos_conv[0][2] == '1'){
		     	$exec[] = array("$('#tabs').tabs('option','disabled', [1,2,3] );");
		     }
		    
	    }else{
	    	
	    	$val[] = array('cbtipoconvenio',$func->contenidocombo($datos_tipconv,null),'html');
		    $val[] = array('cbentidad',$func->contenidocombo($datos_entidad,null),'html');
		    $val[] = array('cb_perioeval',$func->contenidocombo($datos_perconv,null),'html');
		    $val[] = array('cbunidad',$func->contenidocombo($datos_unidad,null),'html');
		    $val[] = array('cb_responsable_senamhi',$func->contenidocombo(array(),null),'html');
	    	
	    	$exec[] = array("$('#tabs').tabs('option','disabled', [1,2,3,4] );");
	    }

	    $evt[] = array('d_objetivo','keyup','Textarea_Sin_Enter(event.keyCode, this.id);');
	    
		$evt[] = array('cbtipoconvenio','change','tipconv_tabs();');
		$evt[] = array('btn_aniadir_docref','click','aniadir_docref();');
		$evt[] = array('cbunidad','change','cargar_emp_unid();');
		$evt[] = array('cb_perioeval','change','cargar_perio_eval();');
		$evt[] = array('btn_cancelar','click','cerrarsubvent();');
		$evt[] = array('btn_guardar','click','guardar_convenio();');

		$evt[] = array('btn_aniadirentidad','click'," listar_entidades();");
		
		$evt[] = array('btn_aniadir_obligacion','click',"mant_obligacion('','');");
		$evt[] = array('btn_aniadir_estacion','click',"estacionseleccion();");
				
		$datatable_opciones = array();
		$datatable_opciones[] = array('bFilter','false');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode($datos_entconv_final));
		$datatable_opciones[] = array('sScrollY','"100px"');
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Nombre entidad','sWidth'=>'250px'),
										array('sTitle'=>'Encargado','sWidth'=>'250px'),
										array('sTitle'=>'-','sWidth'=>'20px')
										)));			
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_entconv",$datatable_opciones);
		
		
		$datatable_opciones = array();
		$datatable_opciones[] = array('bFilter','false');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');
//		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('bAutoWidth','true');
		
		$datatable_opciones[] = array('sScrollY','"330px"');
//		$datatable_opciones[] = array('aaData',json_encode($datos_tbl_oblig));
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Nro.','sWidth'=>'30px'),
										array('sTitle'=>'Descripci&oacute;n de obligaci&oacute;n','sWidth'=>'270px'),
										array('sTitle'=>'Responsabilidad','sWidth'=>'250px'),
										array('sTitle'=>'-','sWidth'=>'50px')
										)));			
//		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(3)", nRow).attr( "align","center" );}');
		echo $this->view->util()->datatable("tbl_obligaciones",$datatable_opciones);

		$datatable_opciones = array();
		$datatable_opciones[] = array('bFilter','false');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode(array()));
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'C&oacute;digo','sWidth'=>'100px'),
										array('sTitle'=>'Nombre estaci&oacute;n','sWidth'=>'250px'),
										array('sTitle'=>'Direcci&oacute;n Regional','sWidth'=>'250px'),
										array('sTitle'=>'-','sWidth'=>'20px')
										)));			
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_estaciones",$datatable_opciones);

		
		$exec[] = array('$("#tabs").tabs( {
							"show": function(event, ui) {
								var oTable1 = $("div.dataTables_scrollBody>table.cl_tbl_obligaciones", ui.panel).dataTable();
								if ( oTable1.length > 0 ) {
									oTable1.fnAdjustColumnSizing();
								}
								var oTable2 = $("div.dataTables_scrollBody>table.cl_tbl_periodoeval", ui.panel).dataTable();
								if ( oTable2.length > 0 ) {
									oTable2.fnAdjustColumnSizing();
								}								
								var oTable3 = $("div.dataTables_scrollBody>table.cl_tbl_estaciones", ui.panel).dataTable();
								if ( oTable3.length > 0 ) {
									oTable3.fnAdjustColumnSizing();
								}
								var oTable4 = $("div.dataTables_scrollBody>table.cl_tbl_entconv", ui.panel).dataTable();
								if ( oTable3.length > 0 ) {
									oTable3.fnAdjustColumnSizing();
								}
							}
						} );');
		
		$func->PintarValor($val);
		$func->PintarEvento($evt);
		$func->EjecutarFuncion($exec);
		
		
	}
	
	public function guardarconvenioAction(){
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) 
		{			
			//obtenemos parametros
			$xml = trim($this->_request->getPost('xml'));
			$c_convenio = trim($this->_request->getPost('codconvenio'));
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_ins_act_convenio';			
		    $arraydatos[] = array(':p_xml',$xml,'OCI_B_CLOB');
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
		    echo trim($datos[0][0]); 
		    
		     $ns = 'pkg_senamhi.sp_obt_conv_perioeval';
		     $arraydatos_eval[] = array(':p_c_conv',$c_convenio);		
			 $arraydatos_eval[] = array(':p_c_eval','');
		     $datos_eval= $cn->ejec_store_oracle_sisconv($ns,$arraydatos_eval);
		     $exec[] = array("cargar_perio_eval('".json_encode($datos_eval)."');"); 
		    
		    $func = new Libreria_Pintar();
		    $func->EjecutarFuncion($exec);
		     
		    
		}
	}
	
	
	public function obtconvenioobligacionAction() {
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
		
			$codconv = trim($this->_request->getPost('codconv'));
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_conv_oblig';
		    $arraydatos_oblig[] = array(':p_c_conv',$codconv);		
		    $datos_oblig= $cn->ejec_store_oracle_sisconv($ns,$arraydatos_oblig);
		    echo json_encode($datos_oblig);			
		}  
	}
	
	
	public function obligacionmantAction() {
		$this->_helper->layout->disableLayout();
		$cod_obligacion = trim($this->_request->getParam('cod_obligacion'));
		$desc_obligacion = trim($this->_request->getParam('desc_obligacion'));
		$cod_responsable = trim($this->_request->getParam('cod_responsable'));
		$index = trim($this->_request->getParam('index'));
		
		$c_convenio = trim($this->_request->getParam('c_convenio'));

		$func = new Libreria_Pintar();		
		$cn = new Model_DataAdapter();
		
		$ns = 'pkg_senamhi.sp_obt_entconv';
	    $arraydatos[] = array(':p_c_convenio',$c_convenio);		
	    $datos_respoblig = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
		
	    if(trim($desc_obligacion) != ''){
	    	$val[] = array('d_obligacion_obligmant',$desc_obligacion,'val');
	    	$val[] = array('cbresponsable_obligmant',$func->ContenidoCombo($datos_respoblig,$cod_responsable),'html');
	    }else{
	    	$val[] = array('cbresponsable_obligmant',$func->ContenidoCombo($datos_respoblig,null),'html');
	    }
		$exec[] = array("$('button').button();");
				
		$evt[] = array('btn_cancelar_obligmant','click','cerrarsubvent2();');
		$evt[] = array('btn_guardar_obligmant','click',"aniadir_obligacion('".$index."','".$cod_obligacion."');");
		$evt[] = array('d_obligacion_obligmant','keypress','Textarea_Sin_Enter(event.keyCode, this.id);');
		$evt[] = array('d_obligacion_obligmant','keyup','Textarea_Sin_Enter(event.keyCode, this.id);');
		
		
		$func->EjecutarFuncion($exec);
		$func->PintarValor($val);
		$func->PintarEvento($evt);
		
	}
	
	public function guardarobligacionesAction(){
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
		
			$cod_conv = trim($this->_request->getPost('cod_conv'));
			$cod_oblig = trim($this->_request->getPost('cod_oblig'));
			$desc_oblig = trim($this->_request->getPost('desc_oblig'));
			$cod_resp = trim($this->_request->getPost('cod_resp'));
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_ins_act_obligacion';
		    $arraydatos[] = array(':p_cod_convenio',$cod_conv);
		    $arraydatos[] = array(':p_oblig_cod',$cod_oblig);
		    $arraydatos[] = array(':p_oblig_desc',$desc_oblig);
		    $arraydatos[] = array(':p_oblig_cod_resp',$cod_resp);
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
		
		    echo $datos[0][0];
		
		}  
	}
	
	public function borrarobligacionesAction(){
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
		
			$cod_oblig = trim($this->_request->getPost('cod_oblig'));
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_del_oblig';
		    $arraydatos[] = array(':p_c_oblig',$cod_oblig);
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
		
		    echo $datos[0][0];
		
		}  
	}
	
	public function cargarempunidAction(){
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
		
			$cod_unid = trim($this->_request->getPost('cod_unid'));
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_emp_unid';
		    $arraydatos[] = array(':p_c_unid',$cod_unid);
		    $arraydatos[] = array(':p_c_emp','');
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
					    	    
		    $func = new Libreria_Pintar();		
		    echo $func->ContenidoCombo($datos,null);
		
		}  
	}
	
	public function cargarperioevalAction(){
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
		
			$cod_perioeval = trim($this->_request->getPost('cod_perioeval'));
			$dia_diff = trim($this->_request->getPost('dia_diff'));
			
			$datos = trim($this->_request->getPost('datos'));
			$datos = str_replace('\"', '"', $datos);
			
			//print_r($cod_perioeval);
			
			if($cod_perioeval != '99999'){
			
					$cn = new Model_DataAdapter();
					$func = new Libreria_Pintar();	
					
					$ns = 'pkg_senamhi.sp_obt_perconv';
				    $arraydatos_perconv[] = array(':p_c_perconv',$cod_perioeval);		
				    $datos_perconv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_perconv);
				    
				    
					$diasperiodo = $datos_perconv[0][2];
					 
					$nro_eval = round($dia_diff/$diasperiodo);
					//print_r($dia_diff);
					//print_r($diasperiodo);
					
					
					
				    if( $diasperiodo >= $dia_diff){
						echo '<div class="ui-state-error" align="center" >Se necesita un minimo de '.$diasperiodo . ' d&iacute;as <br>para generar una fecha de evalucaci&oacute;n</div>';		    
				    }else{
				    	echo ' <table id="tbl_periodoseval" cellpadding="0" cellspacing="0" border="0" class="cl_tbl_periodoeval"></table>';
				    			    	
				    	$array_tbl = array();
				    	for($i=1;$i<=$nro_eval;$i++){
				    		$array_tbl[] = array('<span class="nro_eval" id="nro_eval_'.$i.'" nro="'.$i.'" codeval="">'.$i.'</span>'
				    							,'<input type="text" value="" id="txt_inieval_'.$i.'" class="txtfecha" style="width: 100px">'
				    							,'<input type="text" value="" id="txt_fineval_'.$i.'" class="txtfecha" style="width: 100px">'
				    							,''
				    							//,'<ul><li class="ui-state-default ui-corner-all ui-state-error" style="width:20px;" onclick="alert(\''.$i.'\')"><span class="ui-icon ui-icon-circle-close"></span></li></ul>'
				    							);
				    							
		//		    		$evt[] = array('txt_inieval_'.$i,'datepicker','');
		//		    		$evt[] = array('txt_fineval_'.$i,'datepicker','');
							$exec[] = array("rangosfechas('txt_inieval_".$i."','txt_fineval_".$i."',false)");
				    	}
				    	
				    	$exec[] = array('$( ".txtfecha" ).datepicker();');
				    	
				    	$datatable_opciones = array();
						$datatable_opciones[] = array('bFilter','false');
						$datatable_opciones[] = array('bLengthChange','false');
						$datatable_opciones[] = array('bInfo','false');
						$datatable_opciones[] = array('bPaginate','false');	
						$datatable_opciones[] = array('bSort','false');	
						$datatable_opciones[] = array('bJQueryUI','true');
		//				$datatable_opciones[] = array('bScrollCollapse','true');
						$datatable_opciones[] = array('bScrollCollapse','false');
						$datatable_opciones[] = array('sScrollY','"320px"');
						$datatable_opciones[] = array('aaData',json_encode($array_tbl));
						$datatable_opciones[] = array('aoColumns',json_encode(array(
														array('sTitle'=>'Nro. Periodo','sWidth'=>'80px'),
														array('sTitle'=>'Inicio Evaluaci&oacute;n','sWidth'=>'150px'),
														array('sTitle'=>'Fin Evaluaci&oacute;n','sWidth'=>'150px'),
														array('sTitle'=>'-','sWidth'=>'20px')
														)));			
						$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
						
						$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(0),td:eq(1),td:eq(2)", nRow).attr( "align","center" );}');
						echo $this->view->util()->datatable("tbl_periodoseval",$datatable_opciones);
				    
				    	
				    	
				    	$datos = json_decode($datos);				    	
				    	if($datos != '' || count($datos) <= 0){ 				    		
				    		$val = array();
				    		for($i=0;$i<count($datos);$i++){					    				    			
				    			$exec[] = array("$('#nro_eval_".$datos[$i][1]."').attr('codeval','".$datos[$i][0]."');");
				    			$val[] = array('txt_inieval_'.$datos[$i][1],$datos[$i][2],'val');
				    			$val[] = array('txt_fineval_'.$datos[$i][1],$datos[$i][3],'val');
				    		}				    		
				    		$func->PintarValor($val);
				    	}
				    	
				    	$func->EjecutarFuncion($exec);
						
				    }
			}
		
		}  
	}
	
	public function estacionseleccionAction() {
		
		$this->_helper->layout->disableLayout();
		
		$cn = new Model_DataAdapter();		
			
		
		$ns = 'pkg_senamhi.sp_obt_estaciones';
	    $arraydatos[] = array(':p_codest','');
	    $arraydatos[] = array(':p_coddreg','');			
	    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
		
	    $arraydatos = array();
	    for($i=0;$i<count($datos);$i++){
	    	$arraydatos[] = array( $datos[$i][0]
	    						  ,trim($datos[$i][1])
	    						  ,$datos[$i][3]
	    						  ,$datos[$i][18]
	    						  ,'<ul><li class="ui-state-default ui-corner-all" style="width:20px" onclick="aniadirestacion(\''.$datos[$i][0].'\',\''.$datos[$i][1].'\',\''.$datos[$i][3].'\')"><span class="ui-icon ui-icon-plusthick"></span></li></ul>'
	    							);
	    }
	    
	   
	    
		$datatable_opciones = array();
		$datatable_opciones[] = array('bFilter','true');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode($arraydatos));
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(2,'asc'))));
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'C&oacute;digo','sWidth'=>'60px'),
										array('sTitle'=>'Nombre estaci&oacute;n','sWidth'=>'200px'),
										array('sTitle'=>'Direcci&oacute;n Regional','sWidth'=>'240px'),
										array('sTitle'=>'Categoria','sWidth'=>'200px'),
										array('sTitle'=>'-','sWidth'=>'20px')             
										)));			
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(3)", nRow).attr( "align","center" );}');
		echo $this->view->util()->datatable("tbl_estacionseleccion",$datatable_opciones);
		
	}


/*codificacion para adjuntar documento de referencia*/	
	public function aniadirdocrefAction(){
		$this->_helper->layout->disableLayout();
		$url = $this->view->util()->getPath();
		
		$cod_convenio = trim($this->_request->getParam('cod_convenio'));
		
		$func = new Libreria_Pintar();	
		$exec[] = array("$('#iframe_form_docref').attr('src','".$url."index.php/sisconv/aniadirdocrefform/?cod_convenio='+".$cod_convenio.");");	
		$func->EjecutarFuncion($exec);
		
	} 
	
	public function aniadirdocrefformAction(){
		$this->_helper->layout->disableLayout();
		$url = $this->view->util()->getPath();
		
		echo '<link href="'.$url.'theme/blue_explora/jquery-ui.css" rel="stylesheet" type="text/css" id="jquery_theme_link" />';
		echo $this->view->util()->getScript("js/common");
		echo $this->view->util()->getStyle();
		
		$cod_convenio = trim($this->_request->getParam('cod_convenio'));
		
		$func = new Libreria_Pintar();	

		$val[] = array('cod_convenio_docref',$cod_convenio,'val');
		
		$exec[] = array("$('#form_docref').attr('action','".$url."index.php/sisconv/subirdocref');");	
		$exec[] = array("$('button , #btn_submit_docref').button();");			
		$evt[] = array('btn_cancelar_docref','click','cerrarsubvent2();');
		
		$func->PintarValor($val);
		$func->EjecutarFuncion($exec);
		$func->PintarEvento($evt);
		
	} 
	
	public function subirdocrefAction () {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		
		$url = $this->view->util()->getPath();
		echo '<link href="'.$url.'theme/blue_explora/jquery-ui.css" rel="stylesheet" type="text/css" id="jquery_theme_link" />';
		echo $this->view->util()->getScript("js/common");
		echo $this->view->util()->getStyle();
		echo '<script type="text/javascript" src="'.$url.'js/js_sisconv.js"></script>';
		
			$lim_tam = "5242880"; 
			$cod_convenio = $_POST['cod_convenio_docref']; 
			$lob_upload_name = $_FILES['lob_upload']['name']; 
			$lob_upload_size = $_FILES['lob_upload']['size']; 
			$lob_upload_type = $_FILES['lob_upload']['type']; 
			$lob_upload = $_FILES['lob_upload']['tmp_name']; 
			
			if($_FILES['lob_upload']['error']==1){ 
			print " <script> alert('El Archivo supera el límite de tamaño, por favor seleccione un archivo diferente.');</script> "; 
			}else if($lob_upload_size>$lim_tam){ 
			print " <script> alert('El Archivo supera el límite de tamaño, por favor seleccione un archivo diferente.') ;</script> "; 
			} else if($_FILES['lob_upload']['error']!=0){ 
			print "<script> alert('Error de Archivo, el archivo no se puede subir.') ;</script> "; 
			} 
			else { 
			$user = 'SISCONV'; 
			$pass = 'SISCONV'; 
			$tsnames = "  (DESCRIPTION = 
							    (ADDRESS_LIST = 
							      (ADDRESS = 
							        (PROTOCOL = TCP)
							        (HOST = 172.32.0.4)
							        (PORT = 1521)
							      )
							    )
							    (CONNECT_DATA = 
							      (SID = ogeip)
							    )
							  )"; 

			$conn = OCILogon($user,$pass,$tsnames); 
			$lob = OCINewDescriptor($conn, OCI_D_LOB); 
			$stmt = OCIParse($conn,"INSERT INTO CONTBP_DOCREF(C_COD_CONV,C_CORR_IMDOC,F_FOT_DOC,V_TIP_DOC) VALUES ('".$cod_convenio."',( select lpad(nvl(max(c_corr_imdoc),0)+1,4,'0') from CONTBP_DOCREF where c_cod_conv = '".$cod_convenio."' ),EMPTY_BLOB(),'".$lob_upload_type."') returning F_FOT_DOC into :the_blob"); 
			OCIBindByName($stmt, ':the_blob',$lob, -1, OCI_B_BLOB); 
			OCIExecute($stmt, OCI_DEFAULT); 
			$mensaje = '';
			if($lob->savefile($lob_upload)) 
			{ 
			OCICommit($conn); 
				$mensaje = 'DOCUMENTO CARGADO CON EXITO.';	
				echo "<script> cargar_docref();</script> ";		
			} 
			else 
			{ 
				$mensaje = 'ERROR EN SUBIDA.';			
			} 
			
			echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"><tr valign="middle"><td align="center">';
			echo '<div align="center" class="demo-section" style="font-weight: bold">'.$mensaje.'</div>';
			echo '</td></tr></table>';
			
			OCIFreeStatement($stmt); 
			OCILogoff($conn); 
			} 
		}
	
		
		public function cargarlistadocrefAction () {
			
			$this->_helper->viewRenderer->setNoRender ();
			$this->_helper->layout->disableLayout ();
		
			$url = $this->view->util()->getPath();
			
			$cod_convenio = trim($this->_request->getParam('cod_convenio'));
			
//			echo 'xxx--->'.$cod_convenio;
			
			$user = 'SISCONV'; 
			$pass = 'SISCONV'; 
			$tsnames = "  (DESCRIPTION = 
							    (ADDRESS_LIST = 
							      (ADDRESS = 
							        (PROTOCOL = TCP)
							        (HOST = 172.32.0.4)
							        (PORT = 1521)
							      )
							    )
							    (CONNECT_DATA = 
							      (SID = ogeip)
							    )
							  )";  
			$Conn = OciLogon($user, $pass, $tsnames); 
			
			$query = "select C_CORR_IMDOC , C_COD_CONV , F_FOT_DOC , V_TIP_DOC from CONTBP_DOCREF where C_COD_CONV='$cod_convenio' order by c_corr_imdoc"; 
			$stmt = OCIParse($Conn, $query); 
			
			OCIExecute($stmt); 
			
			 echo '<div style="width:650px;" align="center">';
				while (($row = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_LOBS)) != false) {
					//echo '<div style="float:left;width:100px;height:120px;padding:3px">';
					echo '<div style="width:570px;height:120px;padding:3px">';
					echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"><tr valign="middle">';
					echo '<td width="24px" valign="top">';
						echo '<ul ><li class="ui-state-default ui-corner-all" onclick="eliminar_docref(\''.$row['C_COD_CONV'].'\',\''.$row['C_CORR_IMDOC'].'\');"><span class="ui-icon ui-icon-circle-close"></span></li></ul>';
					echo '</td>';
					echo '<td align="center" width="120px" >';					
					    	if($row['V_TIP_DOC'] == 'image/jpeg')
								echo '<img src="data:image/jpeg;base64,'.base64_encode($row['F_FOT_DOC']).'" width="100px"  height="100px" onclick="mostrar_docref(\''.$row['C_COD_CONV'].'\',\''.$row['C_CORR_IMDOC'].'\');" />'; 
							else
								echo '<img src="'.$url.'img/mini-pdf.png" width="100px"  height="100px" onclick="mostrar_docref(\''.$row['C_COD_CONV'].'\',\''.$row['C_CORR_IMDOC'].'\');" />';
					echo '</td>';
				    echo '<td width="450px"> 
				    			<ul><li style="width: 80px" class="ui-state-default ui-corner-all" onclick="editar_docref(\''.$row['C_COD_CONV'].'\',\''.$row['C_CORR_IMDOC'].'\');"><span class="ui-icon ui-icon-circle-close" style="float:left;"></span>  EDITAR</li></ul>
				    			<textarea readonly class="txt_docref" cod_conv="'.$row['C_COD_CONV'].'" corr_img="'.$row['C_CORR_IMDOC'].'"  rows="5" style="width: 98%"></textarea> 
				    	  </td>';
				    echo '</tr></table>';
				    echo '<hr>';
				    echo '</div>';
				    echo '<br>';
				}
			echo '</div>';				
			
			OCIFreeStatement($stmt); 
				 
		}
		
		public function mostrardocrefAction(){
			$this->_helper->layout->disableLayout();
			
			$cod_conv = trim($this->_request->getParam('cod_conv'));
			$corr = trim($this->_request->getParam('corr'));
			$width = trim($this->_request->getParam('width'));
			$height = trim($this->_request->getParam('height'));
			
			$user = 'SISCONV'; 
			$pass = 'SISCONV'; 
			$tsnames = "  (DESCRIPTION = 
							    (ADDRESS_LIST = 
							      (ADDRESS = 
							        (PROTOCOL = TCP)
							        (HOST = 172.32.0.4)
							        (PORT = 1521)
							      )
							    )
							    (CONNECT_DATA = 
							      (SID = ogeip)
							    )
							  )";  
			$Conn = OciLogon($user, $pass, $tsnames); 
			
			$query = "select F_FOT_DOC , V_TIP_DOC from CONTBP_DOCREF where C_COD_CONV='$cod_conv' and C_CORR_IMDOC = '$corr'"; 
			 
			$stmt = OCIParse($Conn, $query); 
			
			OCIExecute($stmt); 
			
				while (($row = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_LOBS)) != false) {
					echo '<table cellpadding="0" cellspacing="0" border="0" height="'.$height.'"><tr valign="middle"><td align="center">';
				    //echo '<img src="data:image/jpeg;base64,'.base64_encode($row['F_FOT_DOC']).'" width="'.($width - 20).'" />';
			        //echo '<iframe src="data:image/jpeg;base64,'.base64_encode($row['F_FOT_DOC']).'" width="'.$width.'" height="'.$height.'"></iframe>';
					echo '<iframe scrolling="no" src="data:'.$row['V_TIP_DOC'].';base64,'.base64_encode($row['F_FOT_DOC']).'" width="'.$width.'" height="'.$height.'"></iframe>';
					
					echo '</td></tr></table>';
				}			
			
			OCIFreeStatement($stmt);
			
			
			
			
			
		}

//fin de codigo de documento de referencia
	//--------------------------------------------------------------------------------------------------------------------------------------------
	//----------CARLITOS <3 -----------
	//--------------------------------------------------------------------------------------------------------------------------------------------

//pruebas para cargar fotografias
//---------------------------------------------------------------------------------------------------
/*codificacion para adjuntar documento de referencia*/	
	public function aniadirimgrefAction(){
		$this->_helper->layout->disableLayout();
		$url = $this->view->util()->getPath();
		
		$cod_evalu = trim($this->_request->getParam('cod_evalu'));
		
		
		
		//echo "$('#iframe_form_imgref').attr('src','".$url."index.php/sisconv/aniadirimgrefform/?cod_evalu='+'".$cod_evalu."');";
		
		$func = new Libreria_Pintar();	
		$exec[] = array("$('#iframe_form_imgref').attr('src','".$url."index.php/sisconv/aniadirimgrefform/?cod_evalu='+'".$cod_evalu."');");	
		$func->EjecutarFuncion($exec);
		
	} 
	
	public function aniadirimgrefformAction(){
		$this->_helper->layout->disableLayout();
		$url = $this->view->util()->getPath();
		
		echo '<link href="'.$url.'theme/blue_explora/jquery-ui.css" rel="stylesheet" type="text/css" id="jquery_theme_link" />';
		echo $this->view->util()->getScript("js/common");
		echo $this->view->util()->getStyle();
		
		$cod_evalu = trim($this->_request->getParam('cod_evalu'));
		
		//echo 'xxx--->'.$cod_evalu;
		
		$func = new Libreria_Pintar();	

		$val[] = array('cod_convenio_imgref',$cod_evalu,'val');   
		
		$exec[] = array("$('#form_imgref').attr('action','".$url."index.php/sisconv/subirimgref');");	
		$exec[] = array("$('button , #btn_submit_imgref').button();");			
		$evt[] = array('btn_cancelar_imgref','click','cerrarsubvent2();');
		
		$func->PintarValor($val);
		$func->EjecutarFuncion($exec);
		$func->PintarEvento($evt);
		
	} 
	
	public function subirimgrefAction () {
		
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		
		
		
		$url = $this->view->util()->getPath();
		echo '<link href="'.$url.'theme/blue_explora/jquery-ui.css" rel="stylesheet" type="text/css" id="jquery_theme_link" />';
		echo $this->view->util()->getScript("js/common");
		echo $this->view->util()->getStyle();
		echo '<script type="text/javascript" src="'.$url.'js/js_sisconv.js"></script>';
		
			$lim_tam = "5242880"; 
			$cod_evalu = $_POST['cod_convenio_imgref'];

				
			
			$lob_upload_name = $_FILES['lob_upload']['name']; 
			$lob_upload_size = $_FILES['lob_upload']['size']; 
			$lob_upload_type = $_FILES['lob_upload']['type']; 
			$lob_upload = $_FILES['lob_upload']['tmp_name']; 
			
			if($_FILES['lob_upload']['error']==1){ 
			print " <script> alert('El Archivo supera el límite de tamaño, por favor seleccione un archivo diferente.');</script> "; 
			}else if($lob_upload_size>$lim_tam){ 
			print " <script> alert('El Archivo supera el límite de tamaño, por favor seleccione un archivo diferente.') ;</script> "; 
			} else if($_FILES['lob_upload']['error']!=0){ 
			print "<script> alert('Error de Archivo, el archivo no se puede subir.') ;</script> "; 
			} 
			else { 
			$user = 'SISCONV'; 
			$pass = 'SISCONV'; 
			$tsnames = "  (DESCRIPTION = 
							    (ADDRESS_LIST = 
							      (ADDRESS = 
							        (PROTOCOL = TCP)
							        (HOST = 172.32.0.4)
							        (PORT = 1521)
							      )
							    )
							    (CONNECT_DATA = 
							      (SID = ogeip)
							    )
							  )"; 

			$conn = OCILogon($user,$pass,$tsnames); 
			$lob = OCINewDescriptor($conn, OCI_D_LOB); 
			//$stmt = OCIParse($conn,"INSERT INTO CONTBP_IMAGENESP(C_COD_EVALU,C_COD_CORR,F_FOT_IMAG,V_TIP_DOC) VALUES ('".$cod_evalu."',( select lpad(nvl(max(c_cod_corr),0)+1,4,'0') from CONTBP_IMAGENESP where c_cod_evalu = '".$cod_evalu."' ),EMPTY_BLOB(),'".$lob_upload_type."') returning F_FOT_IMAG into :the_blob"); 
			$stmt = OCIParse($conn,"INSERT INTO CONTBP_IMAGENES(C_COD_EVALU,C_COD_CORR,F_FOT_IMAG,V_TIP_DOC) VALUES (lpad('".$cod_evalu."',10,'0'),( select lpad(nvl(max(c_cod_corr),0)+1,4,'0') from CONTBP_IMAGENES where c_cod_evalu = (lpad('".$cod_evalu."',10,'0'))),EMPTY_BLOB(),'".$lob_upload_type."') returning F_FOT_IMAG into :the_blob");
			//echo 'xxx--->'.$cod_evalu;
			
			//print_r($stmt);
			
			OCIBindByName($stmt, ':the_blob',$lob, -1, OCI_B_BLOB); 
			OCIExecute($stmt, OCI_DEFAULT); 
			$mensaje = '';
			
			
			
			if($lob->savefile($lob_upload)) 
			{ 
			OCICommit($conn); 
				$mensaje = 'DOCUMENTO CARGADO CON EXITO.';	
				echo "<script> cargar_docref();</script> ";		
			} 
			else 
			{ 
				$mensaje = 'ERROR EN SUBIDA.';			
			} 
			
			echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"><tr valign="middle"><td align="center">';
			echo '<div align="center" class="demo-section" style="font-weight: bold">'.$mensaje.'</div>';
			echo '</td></tr></table>';
			
			OCIFreeStatement($stmt); 
			OCILogoff($conn); 
			} 
		}
	
		
		public function cargarlistaimgrefAction () {
			
			$this->_helper->viewRenderer->setNoRender ();
			$this->_helper->layout->disableLayout ();
		
			$url = $this->view->util()->getPath();
			
			$cod_evalu = trim($this->_request->getParam('cod_evalu'));
			
			//$exec[] = array("cargarlista_imgref('".$codeval[0][0]."');"); 
			//$exec[] = array("cargarlista_imgref(lpad(".$cod_evalu.",10,'0'));");
			
			//echo 'xxx--->'.$cod_evalu;
			
			$user = 'SISCONV'; 
			$pass = 'SISCONV'; 
			$tsnames = "  (DESCRIPTION = 
							    (ADDRESS_LIST = 
							      (ADDRESS = 
							        (PROTOCOL = TCP)
							        (HOST = 172.32.0.4)
							        (PORT = 1521)
							      )
							    )
							    (CONNECT_DATA = 
							      (SID = ogeip)
							    )
							  )";  
			$Conn = OciLogon($user, $pass, $tsnames); 
			
			//$query = "select C_COD_CORR , C_COD_EVALU , F_FOT_IMAG , V_TIP_DOC from CONTBP_IMAGENES where C_COD_EVALU='$cod_evalu' order by c_cod_corr";
			$query = "select C_COD_CORR , C_COD_EVALU , F_FOT_IMAG , V_TIP_DOC from CONTBP_IMAGENES where C_COD_EVALU=lpad('".$cod_evalu."',10,'0') order by c_cod_corr";  
			$stmt = OCIParse($Conn, $query); 
			
			//echo 'xxx--->'.$query;
			
			
			OCIExecute($stmt); 
			
			 echo '<div style="width:650px;" align="center">';
				while (($row = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_LOBS)) != false) {
					echo '<div style="float:left;width:100px;height:120px;padding:3px">';
					echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"><tr valign="middle"><td align="center">';
					    	if($row['V_TIP_DOC'] == 'image/jpeg')
								echo '<img src="data:image/jpeg;base64,'.base64_encode($row['F_FOT_IMAG']).'" width="100px"  height="100px" onclick="mostrar_imgref(\''.$row['C_COD_EVALU'].'\',\''.$row['C_COD_CORR'].'\');" />';
					    	else
								echo '<img src="'.$url.'img/mini-pdf.png" width="100px"  height="100px" onclick="mostrar_imgref(\''.$row['C_COD_EVALU'].'\',\''.$row['C_COD_CORR'].'\');" />';
								
					    	echo '<ul ><li class="ui-state-default ui-corner-all" onclick="eliminar_imgref(\''.$row['C_COD_EVALU'].'\',\''.$row['C_COD_CORR'].'\');"><span class="ui-icon ui-icon-circle-close"></span></li></ul>';
				    echo '</td></tr></table>';
				    echo '</div>';
				}
			echo '</div>';				
			
			OCIFreeStatement($stmt); 
				 
		}
		
		public function mostrarimgrefAction(){
			$this->_helper->layout->disableLayout();
			
			$cod_evalu = trim($this->_request->getParam('cod_evalu'));
			$corr = trim($this->_request->getParam('corr'));
			$width = trim($this->_request->getParam('width'));
			$height = trim($this->_request->getParam('height'));
			
					
			$user = 'SISCONV'; 
			$pass = 'SISCONV'; 
			$tsnames = "  (DESCRIPTION = 
							    (ADDRESS_LIST = 
							      (ADDRESS = 
							        (PROTOCOL = TCP)
							        (HOST = 172.32.0.4)
							        (PORT = 1521)
							      )
							    )
							    (CONNECT_DATA = 
							      (SID = ogeip)
							    )
							  )";  
			$Conn = OciLogon($user, $pass, $tsnames); 
			
			//$query = "select F_FOT_IMAG , V_TIP_DOC from CONTBP_IMAGENES where C_COD_EVALU='$cod_evalu' and C_COD_CORR = '$corr'";
			$query = "select F_FOT_IMAG , V_TIP_DOC from CONTBP_IMAGENES where C_COD_EVALU=lpad('".$cod_evalu."',10,'0') and C_COD_CORR = lpad('".$corr."',4,'0')"; 
			 
			$stmt = OCIParse($Conn, $query); 
			
			OCIExecute($stmt); 
			
				while (($row = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_LOBS)) != false) {
					echo '<table cellpadding="0" cellspacing="0" border="0" height="'.$height.'"><tr valign="middle"><td align="center">';
				    //echo '<img src="data:image/jpeg;base64,'.base64_encode($row['F_FOT_DOC']).'" width="'.($width - 20).'" />';
			        //echo '<iframe src="data:image/jpeg;base64,'.base64_encode($row['F_FOT_DOC']).'" width="'.$width.'" height="'.$height.'"></iframe>';
					echo '<iframe scrolling="no" src="data:'.$row['V_TIP_DOC'].';base64,'.base64_encode($row['F_FOT_IMAG']).'" width="'.$width.'" height="'.$height.'"></iframe>';
					
					echo '</td></tr></table>';
				}			
			
			OCIFreeStatement($stmt);
			
			
			
			
			
		}

//fin de codigo de documento de referencia
//-----------------------------fin de pruebas--------------------------------------------------------//		
public function perconvAction () {
		
	echo $this->view->util()->SetTitlePag('MANTENIMIENTO PERIODO CONVENIO');
	
		$func = new Libreria_Pintar();
		
		$listardatos = trim($this->_request->getPost('listardatos'));
		if($listardatos == '1'){
			$this->_helper->layout->disableLayout();//quitamos el layout
			$this->_helper->viewRenderer->setNoRender (); //deshabilitamos la vista
			
			//al deshabilitar la vista, debemos de volver a escribir la tabla
			echo '<table id="tbl_perconv" border="0" cellpadding="0" cellspacing="0" width="500px"></table>';
			
		}else{
			$evt[] = array('btn_nuevo','click',"mant_perconv('');");
			$func->PintarEvento($evt);
		}
		
		
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.sp_obt_perconv';
		$arraydatos[] = array(':p_c_perconv','');
		
		
		$datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    $datos_finales = null;
	    for($i=0;$i<count($datos);$i++){
	    	$datos_finales[] = array(
	    								$datos[$i][0],
	    								$datos[$i][1],
	    								$datos[$i][2],
	    								'<ul><li class="ui-state-default ui-corner-all ui-state-hover" onclick="mant_perconv(\''.$datos[$i][0].'\')" title=".ui-icon-search"><span class="ui-icon ui-icon-search"></span></li></ul>'
	    							 );
	    }	
		
	
		$datatable_opciones[] = array('bFilter','false');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode($datos_finales));
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Cod','sWidth'=>'50px'),
										array('sTitle'=>'Periodo Convenio','sWidth'=>'830px'),
										array('sTitle'=>'Nro de dias','sWidth'=>'50px'),
										array('sTitle'=>'-','sWidth'=>'20px')
										)));			
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		$datatable_opciones[] = array('fnRowCallback','function( nRow, aData, iDisplayIndex ){$("td:eq(2)", nRow).attr( "align","right" );}');
		echo $this->view->util()->datatable("tbl_perconv",$datatable_opciones);	
	
	}
	
	public function perconvmantAction () {
	
	$this->_helper->layout->disableLayout();
		$c_perconv = trim($this->_request->getParam('c_perconv'));
		
		$func = new Libreria_Pintar();
		
		if($c_perconv != ''){
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_perconv';
	   	 	$arraydatos[] = array(':p_c_perconv',$c_perconv);
		
	    	$datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    	$val[] = array('c_perconv',$datos[0][0],'html');
	    	$val[] = array('d_perconv',$datos[0][1],'val');
	    	$val[] = array('n_perconv',$datos[0][2],'val');
	    	$func->PintarValor($val);
		}
		
		$exec[] = array("$('button').button();");		
		$evt[] = array('btn_cancelar','click','cerrarsubvent();');
		$evt[] = array('btn_guardar','click','guardar_perconv();');
		
		$func->EjecutarFuncion($exec);
		$func->PintarEvento($evt);
	
	}

	public function guardarperconvAction(){
		$this->_helper->layout->disableLayout();
		
		//obtenemos parametros
		$c_perconv = trim($this->_request->getPost('c_perconv'));
		$d_perconv = trim($this->_request->getPost('d_perconv'));
		$n_perconv = trim($this->_request->getPost('n_perconv'));
		
		//llamamos a la coneccion de la bd
		$cn = new Model_DataAdapter();
		//colocamos el nombre del procedure
		$ns = 'pkg_senamhi.sp_ins_act_perconv';
		//parametros del procedure
	    $arraydatos[] = array(':p_c_perconv',$c_perconv);
	    $arraydatos[] = array(':p_d_perconv',$d_perconv);
	    $arraydatos[] = array(':p_n_perconv',$n_perconv);
	    //ejecutamos el procedure!!!
	    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    //obtenemos la respuesta :) 
	    echo $datos[0][0]; // esto va al javascript    
	}
	
	public function respoobligAction() {
		
		echo $this->view->util()->SetTitlePag('MANTENIMIENTO RESPONSABLE DE LA OBLIGACION');
		
		$func = new Libreria_Pintar();
		
		$listardatos = trim($this->_request->getPost('listardatos'));
		if($listardatos == '1'){
			$this->_helper->layout->disableLayout();//quitamos el layout
			$this->_helper->viewRenderer->setNoRender (); //deshabilitamos la vista
			
			//al deshabilitar la vista, debemos de volver a escribir la tabla
			echo '<table id="tbl_respooblig" border="0" cellpadding="0" cellspacing="0" width="500px"></table>';
			
		}else{
			$evt[] = array('btn_nuevo','click',"mant_respooblig('');");
			$func->PintarEvento($evt); 
		}
		
		$cn = new Model_DataAdapter(); //conecta a la bd
		$ns = 'pkg_senamhi.sp_obt_respooblig';
	    $arraydatos[] = array(':p_c_respooblig','');
		
	    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    $datos_finales = null;
	    for($i=0;$i<count($datos);$i++){
	    	$datos_finales[] = array(
	    								$datos[$i][0],
	    								$datos[$i][1],
	    								'<ul><li class="ui-state-default ui-corner-all ui-state-hover" onclick="mant_respooblig(\''.$datos[$i][0].'\')" title=".ui-icon-search"><span class="ui-icon ui-icon-search"></span></li></ul>'
	    							 );
	    }	
	    
		$datatable_opciones[] = array('bFilter','false');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('aaData',json_encode($datos_finales));
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Cod','sWidth'=>'50px'),
										array('sTitle'=>'Responsable Obligacion','sWidth'=>'830px'),
										array('sTitle'=>'-','sWidth'=>'20px')
										)));			
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_respooblig",$datatable_opciones);	
	}
	
	public function respoobligmantAction () {
	
	$this->_helper->layout->disableLayout();
		$c_respooblig = trim($this->_request->getParam('c_respooblig'));
		
		$func = new Libreria_Pintar();
		
		if($c_respooblig != ''){
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_respooblig';
	   	 	$arraydatos[] = array(':p_c_respooblig',$c_respooblig);
		
	    	$datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    	$val[] = array('c_respooblig',$datos[0][0],'html');
	    	$val[] = array('d_respooblig',$datos[0][1],'val');
	    	$func->PintarValor($val);
		}
		
		
		
		$exec[] = array("$('button').button();");		
		$evt[] = array('btn_cancelar','click','cerrarsubvent();');
		$evt[] = array('btn_guardar','click','guardar_respooblig();');
		
		$func->EjecutarFuncion($exec);
		$func->PintarEvento($evt);
	
	}
	
	public function guardarrespoobligAction(){
		$this->_helper->layout->disableLayout();
		
		$this->_helper->viewRenderer->setNoRender();
		
		//obtenemos parametros
		$c_respooblig = trim($this->_request->getPost('c_respooblig'));
		$d_respooblig = trim($this->_request->getPost('d_respooblig'));
		
		//llamamos a la coneccion de la bd
		$cn = new Model_DataAdapter();
		//colocamos el nombre del procedure
		$ns = 'pkg_senamhi.sp_ins_act_respooblig';
		//parametros del procedure
	    $arraydatos[] = array(':p_c_respooblig',$c_respooblig);
	    $arraydatos[] = array(':p_d_respooblig',$d_respooblig);
	    //ejecutamos el procedure!!!
	    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    //obtenemos la respuesta :) 
	    echo $datos[0][0]; // esto va al javascript    
	}
	
	public function evalconvAction(){

		$ddatosuserlog = new Zend_Session_Namespace('datosuserlog');				
		$codemp =   $ddatosuserlog->codemp;
		
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.sp_obt_conv_cab';
	    $arraydatos[] = array(':p_c_conv','');
	    $arraydatos[] = array(':p_c_emp',$codemp);
	    $arraydatos[] = array(':p_c_tipconv','2');
	    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    
	    //print_r($datos);
	    
	    $tbl = '';
	    
	    $tbl .='<table cellpadding="0" cellspacing="0" border="1">';
	    /*cabecera*/	
			$tbl .='<thead>';
				$tbl .='<tr>';
					$tbl .='<th class="ui-state-default" width="100">C&oacute;digo</th>';
					$tbl .='<th class="ui-state-default" width="450">Convenio</th>';
					$tbl .='<th class="ui-state-default" width="300">Entidades</th>';
					$tbl .='<th class="ui-state-default" width="30">-</th>';
				$tbl .='</tr>';
			$tbl .='</thead>';
	    /*cabecera*/
		/*cuerpo*/
			$tbl .='<tbody>';	
			for($i=0;$i<count($datos);$i++){
				$tbl .='<tr>';
					$tbl .='<td align="center">'.$datos[$i][0].'</td>'; 
					$tbl .='<td>'.$datos[$i][1].'</td>';
					$tbl .='<td>'.$datos[$i][14].'</td>';
					$tbl .='<td align="center"><ul><li class="ui-state-default ui-corner-all ui-state-hover" onclick="mostrar_perioeval(\''.$datos[$i][0].'\');" style="width:16px"><span class="ui-icon ui-icon-circle-triangle-s"></span></li></ul></td>';
					$tbl .='</tr>';
					$tbl .='<tr id="tr_'.$datos[$i][0].'" style="display: none;"><td colspan="4" align="center">';
//					$tbl .='escondito '.$datos[$i][0];
			
					$ns = 'pkg_senamhi.sp_obt_conv_perioeval';
					$arraydatos_pereval = null;
				    $arraydatos_pereval[] = array(':p_c_conv',$datos[$i][0]);
				    $arraydatos_pereval[] = array(':p_c_eval','');
				    $datos_pereval = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_pereval);
				    	$tbl .='<table cellpadding="0" cellspacing="0" border="1" style="padding: 10px">';
					    	$tbl .='<thead>';
								$tbl .='<tr>';
									$tbl .='<th class="ui-state-default" width="80">Nro. Eval</th>';
									$tbl .='<th class="ui-state-default" width="80">Inicio</th>';
									$tbl .='<th class="ui-state-default" width="80">Fin</th>';
									$tbl .='<th class="ui-state-default" width="50">Evaluar</th>';
								$tbl .='</tr>';
							$tbl .='</thead>';
							$tbl .='<tbody>';
				    for($j=0;$j<count($datos_pereval);$j++){
				    	$tbl .='<tr>';
							$tbl .='<td align="center">'.$datos_pereval[$j][1].'</td>';
							$tbl .='<td align="center">'.$datos_pereval[$j][2].'</td>';
							$tbl .='<td align="center">'.$datos_pereval[$j][3].'</td>';
							if($datos_pereval[$j][4] == '1')
								$tbl .='<td align="center"><ul><li class="ui-state-default ui-corner-all ui-state-hover" onclick="evaluar_periodoconvenio(\''.$datos_pereval[$j][0].'\');" style="width:16px"><span class="ui-icon ui-icon-folder-open"></span></li></ul></td>';
							else
								$tbl .= '<td></td>';
						$tbl .='</tr>';
				    }
				    	$tbl .='</tbody>';
				    	$tbl .='</table>';
				
				$tbl .='</td></tr>';
			}	
			$tbl .='</tbody>';		
	    /*cuerpo*/
	    $tbl .='</table>';
		
	    echo $tbl;
	}
	
	public function evaluarperiodoconvenioAction(){
		$this->_helper->layout->disableLayout();
		$codeval = trim($this->_request->getParam('codeval'));
//		echo $codeval;
	
		$opcion_combo_avance[]=array('0','NO');
		$opcion_combo_avance[]=array('1','SI');
		
		$func = new Libreria_Pintar();
		$cn = new Model_DataAdapter();

		$val[] = array('codeval',$codeval,'val');
		
		$evt[] = array('btn_aniadir_eval_logro','click','eval_logro_mant();');
		$evt[] = array('btn_aniadir_eval_opinion','click','eval_opinion_mant();');
		
		//$evt[] = array('btn_aniadir_eval_img','click','eval_observacion_mant();'); imagenesssssssss
		$evt[] = array('btn_aniadir_eval_img','click','aniadir_imgref();');
		
		$evt[] = array('btn_aniadir_eval_observacion','click','eval_observacion_mant();');
		$evt[] = array('btn_guardarevaluacion','click','guardarevalconv();');
		$fn[] = array('$("button").button();');	

		$ns = 'pkg_senamhi.sp_obt_conv_perioeval';
	    $arraydatos_pereval[] = array(':p_c_conv','');
	    $arraydatos_pereval[] = array(':p_c_eval',$codeval);
	    $datos_pereval = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_pereval);

	    $val[] = array('nroeval',$datos_pereval[0][1],'html');
	    $val[] = array('tipperioeval',$datos_pereval[0][7],'html');
	    
	    $ns = 'pkg_senamhi.sp_obt_conv_cab';
	    //$arraydatos_conv[] = array(':p_c_conv',$datos_pereval[0][5]);
	    $arraydatos_conv[] = array(':p_c_conv',$datos_pereval[0][5]);
	    $arraydatos_conv[] = array(':p_c_emp','');		
	    $arraydatos_conv[] = array(':p_c_tipconv','');
	    $datos_conv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_conv);
	    	    
	    $val[] = array('c_conv',$datos_conv[0][0],'html');
	    $val[] = array('d_conv',$datos_conv[0][1],'html');
	    //$val[] = array('f_conv',$datos_conv[0][13] .' / ' . $datos_conv[0][14],'html'); herr
	    $val[] = array('f_conv',$datos_conv[0][10] .' / ' . $datos_conv[0][11],'html');
	    //$val[] = array('d_resol',$datos_conv[0][7],'html');
	    $val[] = array('d_resol',$datos_conv[0][4],'html');
	    
	    $tbls_oblig = '';
	    //$ns = 'pkg_senamhi.sp_obt_respoblig';
	    $ns = 'pkg_senamhi.sp_obt_entconv';
	    $arraydatos_respoblig[] = array(':p_c_convenio',$datos_pereval[0][5]);
	    $datos_respoblig = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_respoblig); 
	    
	    $ns = 'pkg_senamhi.sp_obt_logros';
	    $arraydatos_logros[] = array(':p_c_eval',$codeval);
	    $datos_logros = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_logros);
	    $array_logros_temp = array();
	    //for( $i = 0 ; $i<count($datos_logros);$i++ ){
	    	//$array_logros_temp[] = array($datos_logros[$i][0],$datos_logros[$i][1],'<ul><li class="ui-state-default ui-corner-all editar_logro" title="Editar" style="width:20px;float:left" cindex="" onclick="eval_logro_mant(this)"><span class="ui-icon ui-icon-pencil"></span></li>'.
              //               '<li class="ui-state-default ui-corner-all ui-state-error borrar_logro" title="Eliminar" style="width:20px;float:left" cindex="" onclick="borrar_logro(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>');
	    
	    	//<span class="nro_logro" id="c_logro_0" cindex="0">1</span>
	    
	    //}
		for( $i = 0 ; $i<count($datos_logros);$i++ ){

                    $array_logros_temp[] = array(
                                                '<span class="nro_logro" id="" cindex="">'.$datos_logros[$i][0].'</span>',
                                                //$datos_logros[$i][1], comentado x herr
                                                '<span class="d_logro" id="" cindex="">'.$datos_logros[$i][1].'</span>', //prueba herr
                                                '<ul><li class="ui-state-default ui-corner-all editar_logro" title="Editar" style="width:20px;float:left" cindex="" onclick="eval_logro_mant(this)"><span class="ui-icon ui-icon-pencil"></span></li>'.
                             					'<li class="ui-state-default ui-corner-all ui-state-error borrar_logro" title="Eliminar" style="width:20px;float:left" cindex="" onclick="borrar_logro(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>');
            
                    
         }
	    
		$ns = 'pkg_senamhi.sp_obt_opinion';
	    $arraydatos_opinion[] = array(':p_c_eval',$codeval);
	    $datos_opinion = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_opinion);
	    $array_opinion_temp = array();
	    for( $i = 0 ; $i<count($datos_opinion);$i++ ){
	    	//$array_opinion_temp[] = array($datos_opinion[$i][0],$datos_opinion[$i][1],'<ul><li class="ui-state-default ui-corner-all editar_logro" title="Editar" style="width:20px;float:left" cindex="" onclick="eval_logro_mant(this)"><span class="ui-icon ui-icon-pencil"></span></li>'.
                             //'<li class="ui-state-default ui-corner-all ui-state-error borrar_logro" title="Eliminar" style="width:20px;float:left" cindex="" onclick="borrar_logro(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>');
	    			$array_opinion_temp[] = array(
                                                '<span class="nro_opinion" id="" cindex="">'.$datos_opinion[$i][0].'</span>',
                                                //$datos_logros[$i][1], comentado x herr
                                                '<span class="d_opinion" id="" cindex="">'.$datos_opinion[$i][1].'</span>', //prueba herr
                                                '<ul><li class="ui-state-default ui-corner-all editar_opinion" title="Editar" style="width:20px;float:left" cindex="" onclick="eval_opinion_mant(this)"><span class="ui-icon ui-icon-pencil"></span></li>'.
                             					'<li class="ui-state-default ui-corner-all ui-state-error borrar_opinion" title="Eliminar" style="width:20px;float:left" cindex="" onclick="borrar_opinion(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>');
            
                    
         }
         

        

	    $ns = 'pkg_senamhi.sp_obt_observaciones';
	    $arraydatos_observacion[] = array(':p_c_eval',$codeval);
	    $datos_observacion = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_observacion);
	    $array_observacion_temp = array();
	    for( $i = 0 ; $i<count($datos_observacion);$i++ ){
	    	//$array_observacion_temp[] = array($datos_observacion[$i][0],$datos_observacion[$i][1],'<ul><li class="ui-state-default ui-corner-all editar_logro" title="Editar" style="width:20px;float:left" cindex="" onclick="eval_logro_mant(this)"><span class="ui-icon ui-icon-pencil"></span></li>'.
                             //'<li class="ui-state-default ui-corner-all ui-state-error borrar_logro" title="Eliminar" style="width:20px;float:left" cindex="" onclick="borrar_logro(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>');
              	  $array_observacion_temp[] = array(
                                                '<span class="nro_observacion" id="" cindex="">'.$datos_observacion[$i][0].'</span>',
                                                //$datos_logros[$i][1], comentado x herr
                                                '<span class="d_observacion" id="" cindex="">'.$datos_observacion[$i][1].'</span>', //prueba herr
                                                '<ul><li class="ui-state-default ui-corner-all editar_observacion" title="Editar" style="width:20px;float:left" cindex="" onclick="eval_observacion_mant(this)"><span class="ui-icon ui-icon-pencil"></span></li>'.
                             					'<li class="ui-state-default ui-corner-all ui-state-error borrar_observacion" title="Eliminar" style="width:20px;float:left" cindex="" onclick="borrar_observacion(this)"><span class="ui-icon ui-icon-circle-close"></span></li></ul>');
             	
	    }
	    
	    for($i = 0 ; $i < count($datos_respoblig) ; $i++){
	    	$tbls_oblig .= '<table cellpadding="0" cellspacing="0" border="1">';
	    	$tbls_oblig .= '<thead>';
	    	$tbls_oblig .= '<tr>';
	    	$tbls_oblig .= '	<th class="ui-state-default" colspan="5">'.$datos_respoblig[$i][1].'</th>';
			$tbls_oblig .= '</tr>';
			$tbls_oblig .= '<tr>';
	    	$tbls_oblig .= '	<th class="ui-state-default" width="30">Nro.</th>';
	    	$tbls_oblig .= '	<th class="ui-state-default" width="410">Detalle</th>';
	    	$tbls_oblig .= '	<th class="ui-state-default" width="60">Avance</th>';
	    	$tbls_oblig .= '	<th class="ui-state-default" width="215">Causa de Frenaje</th>';
	    	$tbls_oblig .= '	<th class="ui-state-default" width="215">Propuesta de Soluci&oacute;n</th>';
			$tbls_oblig .= '</tr>';
			$tbls_oblig .= '</thead>';
			$tbls_oblig .= '<tbody>';

			$ns = 'pkg_senamhi.sp_obt_oblig_eval';
			$arraydatos_obligeval = null;
		    $arraydatos_obligeval[] = array(':p_c_eval',$codeval);
		    $arraydatos_obligeval[] = array(':p_c_respoblog',$datos_respoblig[$i][0]);
		    //echo 'xxxxxxxx-->'.$codeval.'/'.$datos_respoblig[$i][0].'<br>';
		    $datos_obligeval = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_obligeval);
			    for($j = 0 ; $j < count($datos_obligeval) ; $j++){
			    	$tbls_oblig .= '<tr>';
			    		$tbls_oblig .= '<td align="center" class="nro_oblig_eval" codeval="'.$datos_obligeval[$j][0].'" codoblig="'.$datos_obligeval[$j][1].'">'.$datos_obligeval[$j][2].'</td>';
			    		$tbls_oblig .= '<td>'.$datos_obligeval[$j][3].'</td>';
//			    		$tbls_oblig .= '<td align="center"><input id="oblig_'.$datos_obligeval[$j][1].'" type="text" value="" style="width:50px"></td>';
			    		$tbls_oblig .= '<td align="center"><select id="oblig_'.$datos_obligeval[$j][1].'" style="width:60px">'.$func->ContenidoCombo($opcion_combo_avance,$datos_obligeval[$j][4]).'</select></td>';
			    		$tbls_oblig .= '<td><textarea rows="3" style="width: 96%" id="frenaje_oblig_'.$datos_obligeval[$j][1].'">'.$datos_obligeval[$j][7].'</textarea></td>';
			    		$tbls_oblig .= '<td><textarea rows="3" style="width: 96%" id="solucion_oblig_'.$datos_obligeval[$j][1].'">'.$datos_obligeval[$j][8].'</textarea></td>';
			    	$tbls_oblig .= '</tr>';
			    	
			    	//$fn[] = array('$("#oblig_'.$datos_obligeval[$j][1].'").kendoNumericTextBox({format: "#",step: 1,decimals: 0 , min : '.$datos_obligeval[$j][5].' , max: '.$datos_obligeval[$j][6].' , value: '.$datos_obligeval[$j][4].' });');
			    	//$val[] = array('oblig_'.$datos_obligeval[$j][1],,'html');
//			    	
			    }	    	
	    	$tbls_oblig .= '</tbody>';
	    	$tbls_oblig .= '</table><br>';
	    	
	    }
	    
	    $val[] = array('det_obligaciones',$tbls_oblig,'html');
	    
	    $datatable_opciones_logros[] = array('bFilter','false');
		$datatable_opciones_logros[] = array('bLengthChange','false');
		$datatable_opciones_logros[] = array('bInfo','false');
		$datatable_opciones_logros[] = array('bPaginate','false');	
		$datatable_opciones_logros[] = array('bSort','false');	
		$datatable_opciones_logros[] = array('bJQueryUI','true');
		$datatable_opciones_logros[] = array('bScrollCollapse','true');
		//$datatable_opciones_logros[] = array('aaData',json_encode(array()));
		$datatable_opciones_logros[] = array('aaData',json_encode($array_logros_temp));
		$datatable_opciones_logros[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Nro','sWidth'=>'50px'),
										array('sTitle'=>'Logro','sWidth'=>'830px'),
										array('sTitle'=>'-','sWidth'=>'50px')
										)));			
//		$datatable_opciones_logros[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_eval_logros",$datatable_opciones_logros);	
	    
	    $datatable_opciones_opiniones[] = array('bFilter','false');
		$datatable_opciones_opiniones[] = array('bLengthChange','false');
		$datatable_opciones_opiniones[] = array('bInfo','false');
		$datatable_opciones_opiniones[] = array('bPaginate','false');	
		$datatable_opciones_opiniones[] = array('bSort','false');	
		$datatable_opciones_opiniones[] = array('bJQueryUI','true');
		$datatable_opciones_opiniones[] = array('bScrollCollapse','true');
		//$datatable_opciones_opiniones[] = array('aaData',json_encode(array()));
		$datatable_opciones_opiniones[] = array('aaData',json_encode($array_opinion_temp));
		$datatable_opciones_opiniones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Nro','sWidth'=>'50px'),
										array('sTitle'=>'Opini&oacute;n','sWidth'=>'830px'),
										array('sTitle'=>'-','sWidth'=>'50px')
										)));			
//		$datatable_opciones_opiniones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_eval_opinion",$datatable_opciones_opiniones);	
		
		$datatable_opciones_observaciones[] = array('bFilter','false');
		$datatable_opciones_observaciones[] = array('bLengthChange','false');
		$datatable_opciones_observaciones[] = array('bInfo','false');
		$datatable_opciones_observaciones[] = array('bPaginate','false');	
		$datatable_opciones_observaciones[] = array('bSort','false');	
		$datatable_opciones_observaciones[] = array('bJQueryUI','true');
		$datatable_opciones_observaciones[] = array('bScrollCollapse','true');
		//$datatable_opciones_observaciones[] = array('aaData',json_encode(array()));
		$datatable_opciones_observaciones[] = array('aaData',json_encode($array_observacion_temp));
		$datatable_opciones_observaciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Nro','sWidth'=>'50px'),
										array('sTitle'=>'Observaciones','sWidth'=>'830px'),
										array('sTitle'=>'-','sWidth'=>'50px')
										)));			
//		$datatable_opciones_opiniones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_eval_observacion",$datatable_opciones_observaciones);
		
		//realizado por HERR 04/08/2014
		$fn[] = array('actualizar_nro_logros();');	
		$fn[] = array('actualizar_nro_opinion();');
		$fn[] = array('actualizar_nro_observacion();');
		
		/*documentos referentes*/
		$fn[] = array("cargarlista_imgref('".$codeval."');"); 
		
	    $func->PintarValor($val);
	    $func->PintarEvento($evt);
	    $func->EjecutarFuncion($fn);
	}
	
	public function evallogromantAction(){
		$this->_helper->layout->disableLayout();
		
		$index = trim($this->_request->getParam('index'));
		$d_logro = trim($this->_request->getParam('d_logro'));		
		
		$func = new Libreria_Pintar();
		
		$fn[] = array('$("button").button();');		
		
		$val[] = array('txa_logroeval',$d_logro,'html');
		
		$evt[] = array('btn_guardar_evallogro','click','eval_logro_aniadir(\''.$index.'\');');
		$evt[] = array('btn_cancelar_evallogro','click','cerrarsubvent2();');
		
		$func->EjecutarFuncion($fn);  
		$func->PintarValor($val);
		$func->PintarEvento($evt);
	}
	
	public function evalopinionmantAction(){
		$this->_helper->layout->disableLayout();
		
		$index = trim($this->_request->getParam('index'));
		$d_opinion = trim($this->_request->getParam('d_opinion'));		
		
		$func = new Libreria_Pintar();
		
		$fn[] = array('$("button").button();');		
		
		$val[] = array('txa_opinioneval',$d_opinion,'html');
		
		$evt[] = array('btn_guardar_evalopinion','click','eval_opinion_aniadir(\''.$index.'\');');
		$evt[] = array('btn_cancelar_evalopinion','click','cerrarsubvent2();');
		
		$func->EjecutarFuncion($fn);  
		$func->PintarValor($val);
		$func->PintarEvento($evt);
	}
	
	public function evalobservacionmantAction(){
		$this->_helper->layout->disableLayout();
		
		$index = trim($this->_request->getParam('index'));
		$d_observacion = trim($this->_request->getParam('d_observacion'));		
		
		$func = new Libreria_Pintar();
		
		$fn[] = array('$("button").button();');		
		
		$val[] = array('txa_observacioneval',$d_observacion,'html');
		
		$evt[] = array('btn_guardar_evalobservacion','click','eval_observacion_aniadir(\''.$index.'\');');
		$evt[] = array('btn_cancelar_evalobservacion','click','cerrarsubvent2();');
		
		$func->EjecutarFuncion($fn);  
		$func->PintarValor($val);
		$func->PintarEvento($evt);
	}
	
	public function guardarevalconvAction(){
		$this->_helper->layout->disableLayout();
		
		$this->_helper->viewRenderer->setNoRender();
				
		$codeval = trim($this->_request->getPost('codeval'));
		$xml_oblig_eval = trim($this->_request->getPost('xml_oblig_eval'));
		$xml_logros = trim($this->_request->getPost('xml_logros'));
		$xml_opiniones = trim($this->_request->getPost('xml_opiniones'));
		$xml_observaciones = trim($this->_request->getPost('xml_observaciones'));
		
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.sp_ins_act_eval';
	    $arraydatos[] = array(':p_c_eval',$codeval);
	    $arraydatos[] = array(':p_xml_avanceoblig',$xml_oblig_eval);
	    $arraydatos[] = array(':p_xml_logros',$xml_logros);
	    $arraydatos[] = array(':p_xml_opiniones',$xml_opiniones);
	    $arraydatos[] = array(':p_xml_observaciones',$xml_observaciones);
	    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    
	    echo json_encode(array($datos[0][0],$datos[0][1]));   
	}
	
	public function eliminardocrefAction(){
	
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
		
			$cod_convenio = trim($this->_request->getPost('cod_convenio'));
			$corr = trim($this->_request->getPost('corr'));
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_del_docref';
		    $arraydatos[] = array(':p_c_convenio',$cod_convenio);
		    $arraydatos[] = array(':p_c_corr',$corr);
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
		    echo $datos[0][0]; 
		
		}  
	}
	
	public function eliminarimgrefAction(){
	
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
		
			$cod_evalu = trim($this->_request->getPost('cod_evalu'));
			$corr = trim($this->_request->getPost('corr'));
			
			$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_del_imgref';
		    $arraydatos[] = array(':p_c_evalu',$cod_evalu);
		    $arraydatos[] = array(':p_c_corr',$corr);
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
		    echo $datos[0][0]; 
		
		}  
	}
	
	public function cumplimientoconvenioAction() {
		
		echo $this->view->util()->SetTitlePag('CUMPLIMIENTO CONVENIO');
	
		$func = new Libreria_Pintar();
		
		$listardatos = trim($this->_request->getPost('listardatos'));
		if($listardatos == '1'){
			$this->_helper->layout->disableLayout();//quitamos el layout
			$this->_helper->viewRenderer->setNoRender (); //deshabilitamos la vista
			
			//al deshabilitar la vista, debemos de volver a escribir la tabla
			echo '<table id="tbl_convenio" border="0" cellpadding="0" cellspacing="0" width="1200px"></table>';
			
		}
		
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.sp_obt_conv_cab';
	    $arraydatos[] = array(':p_c_conv','');
	    $arraydatos[] = array(':p_c_emp','');
	    $arraydatos[] = array(':p_c_tipconv','2');
//		
	    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    $datos_finales = array();
	    for($i=0;$i<count($datos);$i++){
	    	$datos_finales[] = array(
	    								$datos[$i][0],
	    								$datos[$i][3],
	    								$datos[$i][1],
	    								$datos[$i][14],
	    								$datos[$i][7],
	    								
	    								($datos[$i][12]=='')?'':'<ul><li class="ui-state-default ui-corner-all ui-state-hover" onclick="cumplimiento_convenio(\''.$datos[$i][0].'\')" title=".ui-icon-search"><span class="ui-icon ui-icon-search"></span></li></ul>'
	    							 );
	    }	
	    $datatable_opciones[] = array('bFilter','true');
		$datatable_opciones[] = array('bLengthChange','true');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','true');	
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('sScrollY','"500px"');
		$datatable_opciones[] = array('aaData',json_encode($datos_finales));
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Cod','sWidth'=>'50px'),
										array('sTitle'=>'Tipo Conv.','sWidth'=>'150px'),
										array('sTitle'=>'Nombre','sWidth'=>'300px'),
										array('sTitle'=>'Entidad','sWidth'=>'250px'),
										array('sTitle'=>'Ofic. Resp.','sWidth'=>'250px'),
										array('sTitle'=>'-','sWidth'=>'20px')
										)));			
//		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		
		echo $this->view->util()->datatable("tbl_convenio",$datatable_opciones);	
	}
	
	public function convenioreportcumplimientoAction() {
		$this->_helper->layout->disableLayout();
		
//		$this->view->util()->console('xxx');
		$url = $this->view->util()->getPath();
		$c_convenio = trim($this->_request->getParam('c_convenio'));
		$periodo = trim($this->_request->getParam('periodo'));
		$print = trim($this->_request->getParam('print'));	
		
			if($c_convenio == ''){
				$c_convenio = '000000';	
			}
			
		if($print=='1'){	
			echo $this->view->util()->getScript("js/common");
			echo $this->view->util()->getStyle();
			echo '<link href="'.$url.'theme/blue_explora/jquery-ui.css" rel="stylesheet" type="text/css" id="jquery_theme_link" />';
			echo '<link href="'.$url.'datatables/css/demo_page.css" rel="stylesheet" />
				  <link href="'.$url.'datatables/css/demo_table.css" rel="stylesheet" />
				  <link href="'.$url.'datatables/css/TableTools.css" rel="stylesheet" />
				  <script type="text/javascript" language="javascript" src="'.$url.'datatables/js/jquery.dataTables.js"></script>
				  <script type="text/javascript" language="javascript" src="'.$url.'datatables/js/ZeroClipboard.js"></script>
				  <script type="text/javascript" language="javascript" src="'.$url.'datatables/js/TableTools.js"></script>
				  <script type="text/javascript" language="javascript" src="'.$url.'datatables/js/FixedColumns.js"></script>
				  <script type="text/javascript" language="javascript" src="'.$url.'datatables/js/FixedHeader.js"></script>
				  
				  <script>var path="'.$url.'";</script>
				  <script type="text/javascript" language="javascript" src="'.$url.'js/js_sisconv.js"></script>
				  ';
			$evt[] = array('btn_cancelar','hide','');
			$evt[] = array('btn_print','hide','');
			$sl[] = array('cbperiodo',true);	
		}
			
		$func = new Libreria_Pintar();
		
		$val[] = array('div_banner','<img alt="" src="'.$url.'img/bannersenamhi.jpg" width="500px">','html');
		
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.sp_obt_tipconv';
	    $arraydatos_tipconv[] = array(':p_c_tipconv','');		
	    $datos_tipconv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_tipconv);
	    
	    $ns = 'pkg_senamhi.sp_obt_entidad';
	    $arraydatos_entidad[] = array(':p_c_entidad','');		
	    $datos_entidad = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_entidad);
	    
	    $ns = 'pkg_senamhi.sp_obt_unidad';
	    $arraydatos_unidad[] = array(':p_c_unid','');		
	    $datos_unidad = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_unidad);
	    
	    $ns = 'pkg_senamhi.sp_obt_perconv';
	    $arraydatos_perconv[] = array(':p_c_perconv','');		
	    $datos_perconv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_perconv);
		
	    $ns = 'pkg_senamhi.sp_obt_conv_cab';
	    $arraydatos_conv[] = array(':p_c_conv',$c_convenio);
	    $arraydatos_conv[] = array(':p_c_emp','');		
	    $arraydatos_conv[] = array(':p_c_tipconv','');
	    $datos_conv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_conv);
	    	    
	    $exec[] = array("$('button').button();");	
//		$exec[] = array("$('#tabs').tabs();");	
//		$exec[] = array("rangosfechas_convenio();");	
	    
		$datos_tbl_oblig = array();
		$datos_entconv_final = array();
		
	    if(count($datos_conv) > 0 ){
	    	$val[] = array('c_convenio',$datos_conv[0][0],'html');	    	
	    	$val[] = array('d_convenio',$datos_conv[0][1],'html');
	    	$val[] = array('txt_tipoconvenio',$datos_conv[0][3],'html');   
		    $val[] = array('d_resolucion',$datos_conv[0][4],'html');
			$val[] = array('d_unidad',$datos_conv[0][7],'html');	
		    $val[] = array('d_responsable_senamhi',$datos_conv[0][9],'html');		    
		    $val[] = array('fecha_ini',$datos_conv[0][10],'val');
		    $val[] = array('fecha_fin',$datos_conv[0][11],'val');
		    
		    $cant_periodo = $datos_conv[0][15];
		    $datos_cb_periodo = array();
		    for($i=1;$i<=$cant_periodo;$i++){
		    	$datos_cb_periodo[] = array($i,$datos_conv[0][13]  . ' - ' . $i);
		    }
		    $val[] = array('cbperiodo',$func->contenidocombo($datos_cb_periodo,$periodo),'html');
//		    $ns = 'pkg_senamhi.sp_obt_entconv';
//		    $arraydatos_entconv[] = array(':p_c_convenio',$datos_conv[0][0]);
//		    $datos_entconv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_entconv);
		    
		    $exec[] = array("cargar_tblobligacioncumplimiento('".$datos_conv[0][0]."','".$periodo."');");   
		    $exec[] = array("cargar_tbllogrocumplimiento('".$datos_conv[0][0]."','".$periodo."');"); 
		    $exec[] = array("cargar_tblopinioncumplimiento('".$datos_conv[0][0]."','".$periodo."');"); 
		    
	    }else{
	    	
	    	$val[] = array('cbtipoconvenio',$func->contenidocombo($datos_tipconv,null),'html');
		    $val[] = array('cbentidad',$func->contenidocombo($datos_entidad,null),'html');
		    $val[] = array('cb_perioeval',$func->contenidocombo($datos_perconv,null),'html');
		    $val[] = array('cbunidad',$func->contenidocombo($datos_unidad,null),'html');
		    $val[] = array('cb_responsable_senamhi',$func->contenidocombo(array(),null),'html');
	    	
	    }

	    $evt[] = array('d_objetivo','keyup','Textarea_Sin_Enter(event.keyCode, this.id);');
	    
		$evt[] = array('cbperiodo','change','cargar_tblobligacioncumplimiento();cargar_tbllogrocumplimiento();cargar_tblopinioncumplimiento();');

//		$evt[] = array('btn_aniadir_docref','click','aniadir_docref();');
		$evt[] = array('cbunidad','change','cargar_emp_unid();');
//		$evt[] = array('cb_perioeval','change','cargar_perio_eval();');
		$evt[] = array('btn_cancelar','click','cerrarsubvent();');
		$evt[] = array('btn_print','click','print_cump_oblig();');

		
	    $sl[] = array('d_convenio',true);
	    $sl[] = array('txt_tipoconvenio',true);
		$sl[] = array('d_resolucion',true);
	    $sl[] = array('d_unidad',true);
	    $sl[] = array('d_responsable_senamhi',true);
	    $sl[] = array('fecha_ini',true);
	    $sl[] = array('fecha_fin',true);
	    		
		
		$func->PintarValor($val);
		$func->PintarEvento($evt);
		$func->EjecutarFuncion($exec);
		$func->ComponenteSoloLectura($sl);
		
		
	}
	
	public function tblobligacioncumplimientoAction(){
	
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
//		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
//		if ($this->getRequest ()->isXmlHttpRequest ()) {
//		
//			$cod_evalu = trim($this->_request->getPost('cod_evalu'));
//			$corr = trim($this->_request->getPost('corr'));
//			
//			$cn = new Model_DataAdapter();
//			$ns = 'pkg_senamhi.sp_del_imgref';
//		    $arraydatos[] = array(':p_c_evalu',$cod_evalu);
//		    $arraydatos[] = array(':p_c_corr',$corr);
//		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
//		    echo $datos[0][0]; 
//		
//		}  
 
		$codconv = trim($this->_request->getPost('codconv'));
		$periodo = trim($this->_request->getPost('periodo'));
		//echo $codconv . '-->'. $periodo;
		
		$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_cump_oblig';
		    $arraydatos[] = array(':p_c_conv',$codconv);
		    $arraydatos[] = array(':p_nroperiodo',$periodo);
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);		
		
		echo '<table id="tbl_obligaciones" border="1" cellpadding="0" cellspacing="0" width="100%"></table>';		
		$datatable_opciones = array();
		$datatable_opciones[] = array('bFilter','false');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');		
		$datatable_opciones[] = array('aaData',json_encode($datos));
		//$datatable_opciones[] = array('bScrollCollapse','true');
		//$datatable_opciones[] = array('sScrollY','"100px"');
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Entidad','sWidth'=>'200px'),
										array('sTitle'=>'Obligaci&oacute;n','sWidth'=>'200px'),
										array('sTitle'=>'Cumple','sWidth'=>'50px'),
										array('sTitle'=>'Frenaje','sWidth'=>'100px'),
										array('sTitle'=>'Propuesta','sWidth'=>'100px')
										
										)));			
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_obligaciones",$datatable_opciones);
		
	}
	
	public function tbllogrocumplimientoAction(){
	
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();

		$codconv = trim($this->_request->getPost('codconv'));
		$periodo = trim($this->_request->getPost('periodo'));
		//echo $codconv . '-->'. $periodo;
//		$datos = array();
		$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_cump_logro';
		    $arraydatos[] = array(':p_c_conv',$codconv);
		    $arraydatos[] = array(':p_nroperiodo',$periodo);
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);		
		
		echo '<table id="tbl_logros" border="1" cellpadding="0" cellspacing="0" width="100%"></table>';		
		$datatable_opciones = array();
		$datatable_opciones[] = array('bFilter','false');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');		
		$datatable_opciones[] = array('aaData',json_encode($datos));
		//$datatable_opciones[] = array('bScrollCollapse','true');
		//$datatable_opciones[] = array('sScrollY','"100px"');
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Nro','sWidth'=>'30px'),
										array('sTitle'=>'Logro')										
										)));			
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_logros",$datatable_opciones);
		
	}
	
public function tblopinioncumplimientoAction(){
	
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();

		$codconv = trim($this->_request->getPost('codconv'));
		$periodo = trim($this->_request->getPost('periodo'));
		//echo $codconv . '-->'. $periodo;
//		$datos = array();
		$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_cump_opinion';
		    $arraydatos[] = array(':p_c_conv',$codconv);
		    $arraydatos[] = array(':p_nroperiodo',$periodo);
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);		
		
		echo '<table id="tbl_opinion" border="1" cellpadding="0" cellspacing="0" width="100%"></table>';		
		$datatable_opciones = array();
		$datatable_opciones[] = array('bFilter','false');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');		
		$datatable_opciones[] = array('aaData',json_encode($datos));
		//$datatable_opciones[] = array('bScrollCollapse','true');
		//$datatable_opciones[] = array('sScrollY','"100px"');
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Nro','sWidth'=>'30px'),
										array('sTitle'=>'Opinion')										
										)));			
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_opinion",$datatable_opciones);
		
	}
	
	public function nocumplimientoconvenioAction() {
		
		echo $this->view->util()->SetTitlePag('OBLIGACIONES INCUMPLIDAS DEL CONVENIO');
	
		$func = new Libreria_Pintar();
		
		$listardatos = trim($this->_request->getPost('listardatos'));
		if($listardatos == '1'){
			$this->_helper->layout->disableLayout();//quitamos el layout
			$this->_helper->viewRenderer->setNoRender (); //deshabilitamos la vista
			
			//al deshabilitar la vista, debemos de volver a escribir la tabla
			echo '<table id="tbl_convenio" border="0" cellpadding="0" cellspacing="0" width="1200px"></table>';
			
		}
		
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.sp_obt_conv_cab';
	    $arraydatos[] = array(':p_c_conv','');
	    $arraydatos[] = array(':p_c_emp','');
	    $arraydatos[] = array(':p_c_tipconv','2');
//		
	    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
	    $datos_finales = array();
	    for($i=0;$i<count($datos);$i++){
	    	$datos_finales[] = array(
	    								$datos[$i][0],
	    								$datos[$i][3],
	    								$datos[$i][1],
	    								$datos[$i][14],
	    								$datos[$i][7],
	    								
	    								($datos[$i][12]=='')?'':'<ul><li class="ui-state-default ui-corner-all ui-state-hover" onclick="nocumplimiento_convenio(\''.$datos[$i][0].'\')" title=".ui-icon-search"><span class="ui-icon ui-icon-search"></span></li></ul>'
	    							 );
	    }	
	    $datatable_opciones[] = array('bFilter','true');
		$datatable_opciones[] = array('bLengthChange','true');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','true');	
		$datatable_opciones[] = array('bJQueryUI','true');
		$datatable_opciones[] = array('bScrollCollapse','true');
		$datatable_opciones[] = array('sScrollY','"500px"');
		$datatable_opciones[] = array('aaData',json_encode($datos_finales));
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Cod','sWidth'=>'50px'),
										array('sTitle'=>'Tipo Conv.','sWidth'=>'150px'),
										array('sTitle'=>'Nombre','sWidth'=>'300px'),
										array('sTitle'=>'Entidad','sWidth'=>'250px'),
										array('sTitle'=>'Ofic. Resp.','sWidth'=>'250px'),
										array('sTitle'=>'-','sWidth'=>'20px')
										)));			
//		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		
		echo $this->view->util()->datatable("tbl_convenio",$datatable_opciones);	
	}

	public function convenioreportnocumplimientoAction() {
		$this->_helper->layout->disableLayout();
		
//		$this->view->util()->console('xxx');
		$url = $this->view->util()->getPath();
		$c_convenio = trim($this->_request->getParam('c_convenio'));
		$periodo = trim($this->_request->getParam('periodo'));
		$print = trim($this->_request->getParam('print'));	
		
			if($c_convenio == ''){
				$c_convenio = '000000';	
			}
			
		if($print=='1'){	
			echo $this->view->util()->getScript("js/common");
			echo $this->view->util()->getStyle();
			echo '<link href="'.$url.'theme/blue_explora/jquery-ui.css" rel="stylesheet" type="text/css" id="jquery_theme_link" />';
			echo '<link href="'.$url.'datatables/css/demo_page.css" rel="stylesheet" />
				  <link href="'.$url.'datatables/css/demo_table.css" rel="stylesheet" />
				  <link href="'.$url.'datatables/css/TableTools.css" rel="stylesheet" />
				  <script type="text/javascript" language="javascript" src="'.$url.'datatables/js/jquery.dataTables.js"></script>
				  <script type="text/javascript" language="javascript" src="'.$url.'datatables/js/ZeroClipboard.js"></script>
				  <script type="text/javascript" language="javascript" src="'.$url.'datatables/js/TableTools.js"></script>
				  <script type="text/javascript" language="javascript" src="'.$url.'datatables/js/FixedColumns.js"></script>
				  <script type="text/javascript" language="javascript" src="'.$url.'datatables/js/FixedHeader.js"></script>
				  
				  <script>var path="'.$url.'";</script>
				  <script type="text/javascript" language="javascript" src="'.$url.'js/js_sisconv.js"></script>
				  ';
			$evt[] = array('btn_cancelar','hide','');
			$evt[] = array('btn_printt','hide','');
			$sl[] = array('cbperiodo',true);	
		}
			
		$func = new Libreria_Pintar();
		
		$val[] = array('div_banner','<img alt="" src="'.$url.'img/bannersenamhi.jpg" width="500px">','html');
		
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.sp_obt_tipconv';
	    $arraydatos_tipconv[] = array(':p_c_tipconv','');		
	    $datos_tipconv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_tipconv);
	    
	    $ns = 'pkg_senamhi.sp_obt_entidad';
	    $arraydatos_entidad[] = array(':p_c_entidad','');		
	    $datos_entidad = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_entidad);
	    
	    $ns = 'pkg_senamhi.sp_obt_unidad';
	    $arraydatos_unidad[] = array(':p_c_unid','');		
	    $datos_unidad = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_unidad);
	    
	    $ns = 'pkg_senamhi.sp_obt_perconv';
	    $arraydatos_perconv[] = array(':p_c_perconv','');		
	    $datos_perconv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_perconv);
		
	    $ns = 'pkg_senamhi.sp_obt_conv_cab';
	    $arraydatos_conv[] = array(':p_c_conv',$c_convenio);
	    $arraydatos_conv[] = array(':p_c_emp','');		
	    $arraydatos_conv[] = array(':p_c_tipconv','');
	    $datos_conv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_conv);
	    	    
	    $exec[] = array("$('button').button();");	
//		$exec[] = array("$('#tabs').tabs();");	
//		$exec[] = array("rangosfechas_convenio();");	
	    
		$datos_tbl_oblig = array();
		$datos_entconv_final = array();
		
	    if(count($datos_conv) > 0 ){
	    	$val[] = array('c_convenio',$datos_conv[0][0],'html');	    	
	    	$val[] = array('d_convenio',$datos_conv[0][1],'html');
	    	$val[] = array('txt_tipoconvenio',$datos_conv[0][3],'html');   
		    $val[] = array('d_resolucion',$datos_conv[0][4],'html');
			$val[] = array('d_unidad',$datos_conv[0][7],'html');	
		    $val[] = array('d_responsable_senamhi',$datos_conv[0][9],'html');		    
		    $val[] = array('fecha_ini',$datos_conv[0][10],'val');
		    $val[] = array('fecha_fin',$datos_conv[0][11],'val');
		    
		    $cant_periodo = $datos_conv[0][15];
		    $datos_cb_periodo = array();
		    for($i=1;$i<=$cant_periodo;$i++){
		    	$datos_cb_periodo[] = array($i,$datos_conv[0][13]  . ' - ' . $i);
		    }
		    $val[] = array('cbperiodo',$func->contenidocombo($datos_cb_periodo,$periodo),'html');
//		    $ns = 'pkg_senamhi.sp_obt_entconv';
//		    $arraydatos_entconv[] = array(':p_c_convenio',$datos_conv[0][0]);
//		    $datos_entconv = $cn->ejec_store_oracle_sisconv($ns,$arraydatos_entconv);
		    
		    $exec[] = array("cargar_tblobligacionnocumplimiento('".$datos_conv[0][0]."','".$periodo."');");   
		     
		    
	    }else{
	    	
	    	$val[] = array('cbtipoconvenio',$func->contenidocombo($datos_tipconv,null),'html');
		    $val[] = array('cbentidad',$func->contenidocombo($datos_entidad,null),'html');
		    $val[] = array('cb_perioeval',$func->contenidocombo($datos_perconv,null),'html');
		    $val[] = array('cbunidad',$func->contenidocombo($datos_unidad,null),'html');
		    $val[] = array('cb_responsable_senamhi',$func->contenidocombo(array(),null),'html');
	    	
	    }

	    $evt[] = array('d_objetivo','keyup','Textarea_Sin_Enter(event.keyCode, this.id);');
	    
		$evt[] = array('cbperiodo','change','cargar_tblobligacionnocumplimiento();');

//		$evt[] = array('btn_aniadir_docref','click','aniadir_docref();');
		$evt[] = array('cbunidad','change','cargar_emp_unid();');
//		$evt[] = array('cb_perioeval','change','cargar_perio_eval();');
		$evt[] = array('btn_cancelar','click','cerrarsubvent();');
		$evt[] = array('btn_printt','click','print_nocump_oblig();');

		
	    $sl[] = array('d_convenio',true);
	    $sl[] = array('txt_tipoconvenio',true);
		$sl[] = array('d_resolucion',true);
	    $sl[] = array('d_unidad',true);
	    $sl[] = array('d_responsable_senamhi',true);
	    $sl[] = array('fecha_ini',true);
	    $sl[] = array('fecha_fin',true);
	    		
		
		$func->PintarValor($val);
		$func->PintarEvento($evt);
		$func->EjecutarFuncion($exec);
		$func->ComponenteSoloLectura($sl);
	}
	
	public function tblobligacionnocumplimientoAction(){
	
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
//		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
//		if ($this->getRequest ()->isXmlHttpRequest ()) {
//		
//			$cod_evalu = trim($this->_request->getPost('cod_evalu'));
//			$corr = trim($this->_request->getPost('corr'));
//			
//			$cn = new Model_DataAdapter();
//			$ns = 'pkg_senamhi.sp_del_imgref';
//		    $arraydatos[] = array(':p_c_evalu',$cod_evalu);
//		    $arraydatos[] = array(':p_c_corr',$corr);
//		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);
//		    echo $datos[0][0]; 
//		
//		}  
 
		$codconv = trim($this->_request->getPost('codconv'));
		$periodo = trim($this->_request->getPost('periodo'));
		//echo $codconv . '-->'. $periodo;
		
		$cn = new Model_DataAdapter();
			$ns = 'pkg_senamhi.sp_obt_nocump_oblig';
		    $arraydatos[] = array(':p_c_conv',$codconv);
		    $arraydatos[] = array(':p_nroperiodo',$periodo);
		    $datos = $cn->ejec_store_oracle_sisconv($ns,$arraydatos);		
		
		echo '<table id="tbl_obligaciones" border="1" cellpadding="0" cellspacing="0" width="100%"></table>';		
		$datatable_opciones = array();
		$datatable_opciones[] = array('bFilter','false');
		$datatable_opciones[] = array('bLengthChange','false');
		$datatable_opciones[] = array('bInfo','false');
		$datatable_opciones[] = array('bPaginate','false');	
		$datatable_opciones[] = array('bSort','false');	
		$datatable_opciones[] = array('bJQueryUI','true');		
		$datatable_opciones[] = array('aaData',json_encode($datos));
		//$datatable_opciones[] = array('bScrollCollapse','true');
		//$datatable_opciones[] = array('sScrollY','"100px"');
		$datatable_opciones[] = array('aoColumns',json_encode(array(
										array('sTitle'=>'Entidad','sWidth'=>'200px'),
										array('sTitle'=>'Obligaci&oacute;n','sWidth'=>'200px'),
										array('sTitle'=>'Cumple','sWidth'=>'50px'),
										array('sTitle'=>'Frenaje','sWidth'=>'100px'),
										array('sTitle'=>'Propuesti','sWidth'=>'100px')
										
										)));			
		$datatable_opciones[] = array('aaSorting',json_encode(array(array(0,'asc'))));
		echo $this->view->util()->datatable("tbl_obligaciones",$datatable_opciones);
		
	}
	
	
}


