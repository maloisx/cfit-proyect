<?php

class LogeoController extends Zend_Controller_Action implements Zend_Auth_Adapter_Interface {
	public $flag = false;
	
	public function init() {
		$this->view->util()->registerScriptJSController($this->getRequest());
		$this->view->util()->registerLeaveControllerAction($this->getRequest());
	}
	
    public function indexAction(){
    	
    	$func = new Libreria_Pintar();
		$func->IniciaScript();
		
		$url = $this->view->util()->getPath();
		
		$val[] = array("txtuser","admin","val");
		$val[] = array("txtpass","admin","val");
		$func->PintarValor($val);
		
		
		$evt[0] = array("txtuser","keypress","if(event.keyCode == 13){ValidarLogeo();}");
		$evt[1] = array("txtpass","keypress","if(event.keyCode == 13){ValidarLogeo();}");
		$evt[2] = array("btningreso","click","ValidarLogeo();");
		
		$att[0] = array('logo','src',$url.'img/logomuni.png');
		
		
		
		$func->PintarEvento($evt);
		$func->AtributoComponente($att);
		$func->FinScript();
    }
       
    public function redirectAction() {
    }
    
	public function validarlogeoAction() {   
		$this->_helper->viewRenderer->setNoRender(); 
		$this->_helper->layout->disableLayout();
		$this->_helper->getHelper('ajaxContext')->initContext();
		
		if ($this->getRequest()->isXmlHttpRequest()) {
			
			$url = $this->view->util()->getPath();

			$user = trim($this->_request->getPost('user'));
			$pass = trim($this->_request->getPost('pass'));
								
			//echo '->'.$user."\n".'->'.$pass."\n".'->'.$captcha.'('.$dcaptcha->data.')'."\n";
			

				$ns = 'sp_login';
			    $arraydatos[] = $user;
			    $arraydatos[] = $pass;
	
				$cn = new Model_DataAdapter();
				$datos = $cn->ejec_store_procedura_mysql($ns,$arraydatos);

				$caddatos = "";
				
				if($datos == '' || $datos == null || count($datos)<=0){
					echo 'Datos Incorrectos...';
					$this->flag = false;
				}
				else{
//					echo $datos[0]->nom;
					//print_r($datos);
					
					if ($datos[0]->estado == '1'){	
											
						$ddatosuserlog = new Zend_Session_Namespace('datosuserlog');				
						$ddatosuserlog->codemp = $datos[0]->cod_personal;
						$ddatosuserlog->nombre = $datos[0]->nom;
						$ddatosuserlog->apepat = $datos[0]->appat;
						$ddatosuserlog->apemat = $datos[0]->apmat;
						$ddatosuserlog->dni = $datos[0]->dni;					
						$ddatosuserlog->usuario = $user; 
					
						echo '<script language=\"JavaScript\">window.open(\''.$url.'index.php\', \'_self\')</script>';
						
					}else{
						echo '<font color="#FF0000">Usuario Inactivo...</font>';
					}
					
					$this->flag = true;
				}
				
				$auth = Zend_Auth::getInstance();
				$auth->authenticate($this);

		}
	}

	public function logoutAction() {
		$url = $this->view->util()->getPath();
		Zend_Session::destroy();
		$this->_redirect($url.'index.php/');
	}
	
	public function authenticate()  {
		if ($this->flag) {
			$user = new Zend_Session_Namespace('datosuserlog');
			$result = new Zend_Auth_Result ( Zend_Auth_Result::SUCCESS, explode('|', $user->data), array ("Ok" ) );
		} else {
			$result = new Zend_Auth_Result ( Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, null, array ("Error" ) );
		}
		
		return $result;
	}
}

