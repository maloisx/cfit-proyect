<?php

/**
 * PatrimonioController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class PatrimonioController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	
	public function init() {
		$this->view->util()->registerScriptJSController($this->getRequest());
	}
	
	public function indexAction() {
		// TODO Auto-generated PatrimonioController::indexAction() default action
	}
	
	public function roleoAction() {
		$func = new Libreria_Pintar();
		$evt[] = array('div_roleo','hide','');
		$evt[] = array('btn_buscar','click','buscarpatrim();');
		$evt[] = array('btn_rolear','click','rolearpatrim();');
		$func->PintarEvento($evt);
	}
	
	public function buscarpatrimAction() {
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$codpatrim = $this->_request->getPost( 'codpatrim');
			
			$cn = new Model_DataAdapter();
			$arraydatos[] = array(':codpatrim',$codpatrim);
			$datos = $cn->ejec_store_oracle_sisaba('pkg_senamhi.sp_obt_patrim',$arraydatos);	
			
			if(count($datos)==0)
				$datos[] = array('000000000000','NO SE ENCONTRO BIEN CON EL CODIGO: '.$codpatrim);
			
			echo json_encode(array($datos[0][0],$datos[0][1]));
		}
	}
	
	public function rolearpatrimAction() {
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$codpatrim = $this->_request->getPost( 'codpatrim');
			$cant = $this->_request->getPost( 'cant');
			
			$cn = new Model_DataAdapter();
			$arraydatos[] = array(':codpatrim',$codpatrim);
			$arraydatos[] = array(':cant',$cant);
			$datos = $cn->ejec_store_oracle_sisaba('pkg_senamhi.sp_rolear_patrim',$arraydatos);	
						
			echo $datos[0][0];
		}
	}
	
	public function pa500Action() {
		//$this->_helper->viewRenderer->setNoRender ();		
		$this->_helper->layout->disableLayout ();
		$url = $this->view->util()->getPath();	

		$codpatrim = $this->_request->getPost( 'txtcodpatrim');
		if($codpatrim != ''){
			echo "<script>document.getElementById('txtcodpatrim').focus();</script>";
			$cn = new Model_DataAdapter();
			$arraydatos[] = array(':codpatrim',$codpatrim);
			$datos = $cn->ejec_store_oracle_sisaba('pkg_senamhi.sp_obt_patrim',$arraydatos);	
			
			if(count($datos)==0)
				echo  'NO SE ENCONTRO BIEN CON EL CODIGO: '.$codpatrim;
			else{
				//echo $datos[0][1];
				$tbl = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
					$tbl .= '<tr>
								<td style="font-weight: bold">Cod. Patrim.</td>
								<td>'.$datos[0][0].'</td>
							</tr>';
					$tbl .= '<tr>
								<td>Desc. Patrim.</td>
								<td>'.$datos[0][1].'</td>
							</tr>';
					$tbl .= '<tr>
								<td>Estado</td>
								<td>'.$datos[0][17].'</td>
							</tr>';
					$tbl .= '<tr>
								<td>Serie</td>
								<td>'.$datos[0][15].'</td>
							</tr>';
					$tbl .= '<tr>
								<td>Modelo</td>
								<td>'.$datos[0][16].'</td>
							</tr>';					
					$tbl .= '<tr>
								<td>Alta</td>
								<td>Doc:'.$datos[0][2]."<br>Fecha:".$datos[0][3].'</td>
							</tr>';
					$tbl .= '<tr>
								<td>DDRR.</td>
								<td>'.$datos[0][6].'</td>
							</tr>';
					$tbl .= '<tr>
								<td>Dependencia.</td>
								<td>'.$datos[0][7].'</td>
							</tr>';
					$tbl .= '<tr>
								<td>Unidad</td>
								<td>'.$datos[0][11].'</td>
							</tr>';
					$tbl .= '<tr>
								<td>Empleado</td>
								<td>'.$datos[0][14].'</td>
							</tr>';
					$tbl .= '<tr>
								<td>Baja</td>
								<td>Doc:'.''."<br>Fecha:".$datos[0][18].'</td>
							</tr>';
				$tbl .= '</table>';
				echo $tbl;
			}
		}
	}
	
	public function syncpocket2oracleAction() {
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		header("Content-Type:text/plain");
		
		$cad = $this->_request->getParam('cad','');
		$datos = explode("|", $cad);
		
		$codpatrim = $datos[0];
		$modelo =  $datos[2];
		$serie = $datos[3];
		$cod_ddrr = $datos[4];
		$cod_depen = $datos[5];
		$cod_unid = $datos[6];
		$cod_usu = $datos[8];
		
		echo $codpatrim . ' ->   :)';
		
//		$cn = new Model_DataAdapter();			
//		$ns = 'pkg_migra_bddiff.sp_obt_arch_pend';
//		$ps[] = array(':p_cod_dreg',$codddrr);
//		$datos = $cn->ejec_store_procedura_oracle($ns,$ps);
//		
//		for($i=0;$i<count($datos);$i++){
//			echo $datos[$i][0] . ',';
//		}
				
	}
	
}

