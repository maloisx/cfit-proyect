<?php

/**
 * MigraddrrController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class MigraddrrController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated MigraddrrController::indexAction() default action
	}
	
	public function archpendientesAction() {
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		header("Content-Type:text/plain");
		
		$codddrr = $this->_request->getParam('codddrr','');
		$coddesc = $this->_request->getParam('coddesc','');
		
		$cn = new Model_DataAdapter();			
		$ns = 'pkg_migra_bddiff.sp_obt_arch_pend';
		$ps[] = array(':p_c_md5',$coddesc);
		$ps[] = array(':p_cod_dreg',$codddrr);		
		$datos = $cn->ejec_store_procedura_oracle($ns,$ps);
		
		for($i=0;$i<count($datos);$i++){
			echo $datos[$i][0] . ',';
		}
				
	}
	
	public function actualizararchivoAction() {
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		header("Content-Type:text/plain");
		
		$md5 = $this->_request->getParam('md5','');
		$arch = $this->_request->getParam('arch','');
		$proc = $this->_request->getParam('proc','');
		$err = $this->_request->getParam('err','');
				
		$cn = new Model_DataAdapter();			
		$ns = 'pkg_migra_bddiff.sp_act_archivoftp';
		$ps[] = array(':p_nomarch',$arch);
		$ps[] = array(':p_proc',$proc);
		$ps[] = array(':p_err',$err);
		$ps[] = array(':p_md5',$md5);
		$datos = $cn->ejec_store_procedura_oracle($ns,$ps);
		
		echo $datos[0][0] ;
				
	}

}

