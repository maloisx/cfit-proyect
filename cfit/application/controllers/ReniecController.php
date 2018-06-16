<?php

/**
 * ReniecController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class ReniecController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function init() {
		$this->view->util()->registerScriptJSController($this->getRequest());
	}
	
	public function indexAction() {
		// TODO Auto-generated ReniecController::indexAction() default action
	}
	
	public function buscarAction() {
		$fn = new Libreria_Pintar();
		$evt[] = array('btn_buscar','click','buscar();');
		$fn->PintarEvento($evt);
	}

	public function buscarreniecAction() {
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$dni = $this->_request->getPost( 'dni');
			$pat = $this->_request->getPost( 'pat');
			$mat = $this->_request->getPost( 'mat');
			$nom = $this->_request->getPost( 'nom');
			
			$cn = new Model_DataAdapter();
			$datos = $cn->ejec_store_procedura_mysql('buscar',array($dni,$nom,$pat,$mat));
//			print_r($datos);
			echo '<table width="800px" border="0" cellpadding="0" cellspacing="0" id="tbl_datos"></table>';
			
			$datatable_opciones[] = array('bFilter','true');
			$datatable_opciones[] = array('bLengthChange','true');
			$datatable_opciones[] = array('bInfo','true');
			$datatable_opciones[] = array('bPaginate','false');	
			$datatable_opciones[] = array('bSort','false');	
			$datatable_opciones[] = array('bJQueryUI','true');
			$datatable_opciones[] = array('bScrollCollapse','false');
			$datatable_opciones[] = array('sScrollY','"500px"');
			$datatable_opciones[] = array('aaData',json_encode($datos));
			$datatable_opciones[] = array('aoColumns',json_encode(array(
											array('sTitle'=>'DNI','sWidth'=>'100px'),
											array('sTitle'=>'Fech. Nac.','sWidth'=>'100px'),
											array('sTitle'=>'Apellido Paterno','sWidth'=>'200px'),
											array('sTitle'=>'Apellido Materno','sWidth'=>'200px'),
											array('sTitle'=>'Nombres','sWidth'=>'200px')
											)));			
			$datatable_opciones[] = array('aaSorting',json_encode(array(array(2,'asc'),array(3,'asc'),array(4,'asc'))));
			$datatable_opciones[] = array('aoColumnDefs',json_encode(array(array('sClass'=>'center' ,'aTargets'=>array(1)))));
			echo $this->view->util()->datatable("tbl_datos",$datatable_opciones);	
			
			
		}
	}		
	
}

