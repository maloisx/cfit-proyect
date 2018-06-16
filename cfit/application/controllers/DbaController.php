<?php

/**
 * DbaController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class DbaController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated DbaController::indexAction() default action
	}
	
	public function bloqueosAction() {
		$cn = new Model_DataAdapter();
		$ns = 'pkg_senamhi.SP_OBT_BLOQUEOS';
		$datos = $cn->ejec_store_oracle_sys($ns,null);
		print_r($datos);
	}

}

