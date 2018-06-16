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
		
		$val[] = array("txtuser","mvillanueva@senamhi.gob.pe","val");
		$val[] = array("txtpass","123456","val");
		$func->PintarValor($val);
		
		
		$evt[0] = array("txtuser","keypress","if(event.keyCode == 13){ValidarLogeo();}");
		$evt[1] = array("txtpass","keypress","if(event.keyCode == 13){ValidarLogeo();}");
		$evt[2] = array("btningreso","click","ValidarLogeo();");
		
		$att[0] = array('logo','src',$url.'img/logomuni.png');
		
		
		
		$func->PintarEvento($evt);
		$func->AtributoComponente($att);
		$func->FinScript();
    }
    
	public function captchaAction() {
    	$this->_helper->viewRenderer->setNoRender(); 
		$this->_helper->layout->disableLayout();
		
		$url = $this->view->util()->getPath();
		
		function genera_codigo_aleatorio($cantidad_char) {
		    $patron = "123456789";
		    $i = 0;
		    $codigo_aleatorio = '';
		    while ($i < $cantidad_char) {
		        $codigo_aleatorio .= substr($patron, mt_rand(0, strlen($patron) - 1), 1);
		        $i++;
		    }
		    return $codigo_aleatorio;
		}
		$size=$_REQUEST['size'];
//		//$size="little";
		if($size=="little"){
		    $imagen=$url."img/bg_captcha_mediano.png";
		    $cant_carac=3;
		    $gdf=PATH."fonts/segoe.gdf";
		    $w=70;
		    $h=35;
		    $padding_top=0;
		    $padding_left=20;
		}  
//		elseif ($size=="big") {
//		    $cant_carac=6;
//		    $imagen="images/bg_captcha.png";
//		    $gdf="fonts/anonymous.gdf";
//		    $w=120;
//		    $h=40;
//		    $padding_top=2;
//		    $padding_left=10;
//		}else{
//		    $cant_carac=9;
//		    $imagen="images/bg_captcha_chico.png";
//		    $gdf="../include/fonts/arial.gdf";
//		    $w=70;
//		    $h=27;
//		    $padding_top=0;
//		    $padding_left=2;
//		}
		
		
		$cadena = genera_codigo_aleatorio($cant_carac);
		$dcaptcha = new Zend_Session_Namespace('captcha');
		$dcaptcha->data = $cadena;
		//Session::setAttribute("codigo_aleatorio",$cadena );
		//$cadena = Session::getAttribute("codigo_aleatorio");
		
		$imagen_inicial_captcha = imagecreatefrompng($imagen);
		$color_cadena = imagecolorallocate($imagen_inicial_captcha, 0, 0, 0); //color20,40,100azulino//255,255,255blanco//0,0,0negro
		$noise_color = imagecolorallocate($imagen_inicial_captcha, 150, 160, 180);//azulfuerte100,120,180
		
		//$font = imageloadfont('fonts/anonymous.gdf');
		$font = imageloadfont($gdf);
		for ($i = 0; $i < 270; $i++) {
		    imagefilledellipse($imagen_inicial_captcha, mt_rand(0, $w), mt_rand(0, $h), 1, 1, $noise_color);
		}
		for ($i = 0; $i < 7; $i++) {
		    imageline($imagen_inicial_captcha, mt_rand(0, $w), mt_rand(0, $h), mt_rand(0, $w), mt_rand(0, $h), $noise_color);
		}
		imagestring($imagen_inicial_captcha, $font, $padding_left, $padding_top, $cadena, $color_cadena);
		imagepng($imagen_inicial_captcha);
		imagedestroy($imagen_inicial_captcha);
		
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
			$captcha = $this->_request->getPost('captcha'); // aun no se usa!! pero igual lo pongo xD!
		
			$dcaptcha = new Zend_Session_Namespace('captcha');
			
			//echo '->'.$user."\n".'->'.$pass."\n".'->'.$captcha.'('.$dcaptcha->data.')'."\n";
			
//			if($captcha != $dcaptcha->data){
//				echo 'ERROR EN EL CAPTCHA.';
//			}else{
				$ns = 'pkg_senamhi.sp_login';
			    $arraydatos[] = array(':p_usuario',$user);
			    $arraydatos[] = array(':p_clave',$pass);
//				
				$cn = new Model_DataAdapter();
				$datos = $cn->ejec_store_oracle_siscar($ns,$arraydatos);
//
				$caddatos = "";
				
				if($datos == '' || $datos == null || count($datos)<=0){
					echo '<font color="#FF0000">Datos Incorrectos...</font>';
					$this->flag = false;
				}
				else{
//					if($datos[0][7] >= $dias[0][2]){
//						echo '<font color="#FF0000">Usuario bloqueado por superar el limite de inactividad <br>... Comuniquese con la S/G de Informatica</font>';
//					}else
					if ($datos[0][3] == '1'){						
						$ddatosuserlog = new Zend_Session_Namespace('datosuserlog');				
						$ddatosuserlog->codindet = $datos[0][0];	
						$ddatosuserlog->nombre = $datos[0][5];
						$ddatosuserlog->apepat = $datos[0][6];
						$ddatosuserlog->apemat = $datos[0][7];
						$ddatosuserlog->codemp = $datos[0][4];	
						$ddatosuserlog->usuario = $datos[0][1]; 
					
						echo '<script language=\"JavaScript\">window.open(\''.$url.'index.php\', \'_self\')</script>';
						
					}else{
						echo '<font color="#FF0000">Usuario Inactivo...</font>';
					}
					$this->flag = true;
				}
//				
				$auth = Zend_Auth::getInstance();
				$auth->authenticate($this);
//			}
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

