<?php

/**
 * WsController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class WsController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
public function init() {
		$this->view->util()->registerScriptJSController($this->getRequest());
	}
	
	public function indexAction() {
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$sp = trim($this->_request->getPost('sp'));
			$params = trim($this->_request->getPost('params'));
						
			$cn = new Model_DataAdapter();
			$datos = $cn->ejec_store_procedura_mysql($sp,json_decode($params));
			echo json_encode($datos);
		}
		
	}

}

