<?php

/**
 * PruebasController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class PruebasController extends Zend_Controller_Action {
	
	public function init() {
		$this->view->util()->registerScriptJSController($this->getRequest());
		//$this->view->util()->registerLeaveControllerAction($this->getRequest());
	}
	public function indexAction() {
		// TODO Auto-generated PruebasController::indexAction() default action
	}
	
	public function formAction(){
		echo $this->view->util()->SetTitlePag('TItUlO dE pAGiNa dE PrUEbA');
		
		$func=new Libreria_Pintar();
		$val[] = array('txt','alverjita','val');
		$val[] = array('div','alverjita','html');
		$func->PintarValor($val);
		
		
	}
	
public function pruebaAction () {
	
	}
	
	public function pruebasubirAction () {
		
		//$this->_helper->layout->disableLayout();
		//$this->_helper->viewRenderer()->setNoRender();
		
			$lim_tam = "1024000"; 
			$lob_description = $_POST['lob_description']; 
			$lob_upload_name = $_FILES['lob_upload']['name']; 
			$lob_upload_size = $_FILES['lob_upload']['size']; 
			$lob_upload_type = $_FILES['lob_upload']['type']; 
			$lob_upload = $_FILES['lob_upload']['tmp_name']; 
			
			if($_FILES['lob_upload']['error']==1){ 
			print " 
			<script> 
			alert('El Archivo supera el límite de tamaño, por favor seleccione un archivo diferente.') 
			document.location.href='lobosolitario.php' 
			</script> 
			"; 
			}else if($lob_upload_size>$lim_tam){ 
			print " 
			<script> 
			alert('El Archivo supera el límite de tamaño, por favor seleccione un archivo diferente.') 
			document.location.href='lobosolitario.php' 
			</script> 
			"; 
			} else if($_FILES['lob_upload']['error']!=0){ 
			print " 
			<script> 
			alert('Error de Archivo, el archivo no se puede subir.') 
			document.location.href='lobosolitario.php' 
			</script> 
			"; 
			} 
			else { 
			$user = 'SISCONV'; 
			$pass = 'SISCONV'; 
			$tsnames = "  (DESCRIPTION = 
							    (ADDRESS_LIST = 
							      (ADDRESS = 
							        (PROTOCOL = TCP)
							        (HOST = 172.32.0.3)
							        (PORT = 1521)
							      )
							    )
							    (CONNECT_DATA = 
							      (SERVICE_NAME = ogeip.senamhi.gob.pe)
							    )
							  )"; 
			//Aqui se establece la conexion con una base de datos oracle. 
			$conn = OCILogon($user,$pass,$tsnames); 
			
			/*Inicializa un nuevo descriptor vacío LOB/FILE (LOB por defecto) 
			Reserva espai per mantenir descriptors o localitzadors LOB. Els valors valids pel tipus type son 
			OCI_D_FILE, OCI_D_LOB, OCI_D_ROWID. Per descriptors LOB, els metodes LOAD, SAVE, i SAVEFILE estan associats 
			amb el descriptor, per BFILE només existeix el mètode LOAD*/ 
			$lob = OCINewDescriptor($conn, OCI_D_LOB); 
			
			//Preparem la consulta SQL(INSERT) capaç d'introduir els valors a la base de dades. 
			$stmt = OCIParse($conn,"INSERT INTO CONTBP_DOCREF_2(C_COD_CONV,C_CORR_IMDOC,F_FOT_DOC) VALUES ('1','1',EMPTY_BLOB()) returning F_FOT_DOC into :the_blob"); 
			
			/* Enlaza una variable PHP a un Placeholder de Oracle 
			Enllaça la variable PHP variable a un placeholder d'ORACLE ph_name. Si aquesta serà usada per entrada o 
			o sortida es determinarà en temps d'execució, i serà reservat l'espai necessari d'emmagatzemament. El 
			parametre lenght estableix el tamany màxim de l'enllaç. Si s'estableix length a -1 OCIBindByName utilitzarà 
			el tamany de la variable per establir el tamany màxim. 
			EN LA ULTIMA PRUEBA QUE SE REALIZÓ EL 07/01/2011 ESTE VALOR $LOB VENÍA PASADO POR REFERENCIA &$LOB NO OBSTANTE ESTO ES UN ERROR EN ESTE CASO 
			*/ 
			OCIBindByName($stmt, ':the_blob',$lob, -1, OCI_B_BLOB); 
			
			//Ejecucion de la sentencia. 
			OCIExecute($stmt, OCI_DEFAULT); 
			if($lob->savefile($lob_upload)) 
			{ 
			OCICommit($conn); 
			echo "Blob successfully uploaded\n<br>".$lob_upload_type; 
			echo "<a href=show.phtml>SHOW FILES</a>"; 
			
					
			} 
			else 
			{ 
			echo "Couldn't upload Blob\n"; 
			} 
			OCIFreeStatement($stmt); 
			OCILogoff($conn); 
			} 
		}
	
		public function showAction () {
				/* 
				file.php 
				Archivo que nos muestra el archivo pedido a la base de datos 
				*/ 
				//$id=$_GET['id']; 
			
				$user = 'SISCONV'; 
				$pass = 'SISCONV'; 
				$tsnames = "  (DESCRIPTION = 
								    (ADDRESS_LIST = 
								      (ADDRESS = 
								        (PROTOCOL = TCP)
								        (HOST = 172.32.0.3)
								        (PORT = 1521)
								      )
								    )
								    (CONNECT_DATA = 
								      (SERVICE_NAME = ogeip.senamhi.gob.pe)
								    )
								  )";  
				$Conn = OciLogon($user, $pass, $tsnames); 
				
				$query = "select F_FOT_DOC from CONTBP_DOCREF where C_COD_CONV='130002' order by c_corr_imdoc"; 
				$stmt = OCIParse($Conn, $query); 
				
//				$NewData = array(); 
//				OCIDefineByName($stmt,"F_FOT_DOC",$NewData["F_FOT_DOC"]); 
				
				OCIExecute($stmt); 
//				OCIFetch($stmt); 
				echo '<div style="width:650px;" align="center">';
				while (($row = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_LOBS)) != false) {
//				    echo $row[0] . " " . $row[1] . "<br>\n";
//					print_r($row);
					echo '<div style="float:left;width:100px;height:100px;padding:2px">';
					echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"><tr valign="middle"><td align="center">';
				    echo '<img src="data:image/jpeg;base64,'.base64_encode($row['F_FOT_DOC']).'" width="100px" onclick="mostrar_docref(this);" />'; 
					echo '</td></tr></table>';
				    echo '</div>';
				}
				echo '</div>';
//				if (is_object($NewData["F_FOT_DOC"])) 
//				{ 
//				$NewData["F_FOT_DOC"] = $NewData["F_FOT_DOC"]->load(); 
//				} 
//				echo '<img src="data:image/jpeg;base64,'.base64_encode($NewData["F_FOT_DOC"]).'" />'; 
				
				
				
				OCIFreeStatement($stmt); 
				 
		}

}

