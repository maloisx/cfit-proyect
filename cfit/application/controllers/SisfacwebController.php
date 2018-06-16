<?php

/**
 * SisfacwebController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class SisfacwebController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$ns = 'pkg_senamhi.sp_prueba';
			    $arraydatos[] = array(':p_param','NI101');
			    $arraydatos[] = array(':p_codesta','201111');
			    $arraydatos[] = array(':p_ano_ini','2000');
			    $arraydatos[] = array(':p_ano_fin','2014');
	
				$cn = new Model_DataAdapter();
				$datos = $cn->ejec_store_oracle_sisfacweb($ns,$arraydatos);
				
				print_r($datos);
	}

}

