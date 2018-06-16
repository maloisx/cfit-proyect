<?php
//require_once dirname(__FILE__).'/../../library/Log4PHP/Logger.php';

class Model_DataAdapter {

	
	public function coneccion_sig(){
		return  array(
					'usuario'=>'SIG',
					'clave'=>'Y06PSIG',
					'tipo'=>OCI_DEFAULT,
					'bd'=>'172.32.0.4:1521/ogeip.senamhi.gob.pe');
	}
	
	public function coneccion_sismethaweb(){
		return array(
					'usuario'=>'SISMETHAWEB',
					'clave'=>'SISMETHAWEB',
					'tipo'=>OCI_DEFAULT,
					'bd'=>'172.32.0.4:1521/ogeip.senamhi.gob.pe');
	}
	public function coneccion_sismetha(){
		return array(
					'usuario'=>'SISMETHA',
					'clave'=>'SISMETHA03P',
					'tipo'=>OCI_DEFAULT,
					'bd'=>'172.32.0.4:1521/ogeip.senamhi.gob.pe');
	}	
	public function coneccion_sisper(){
		return array(
					'usuario'=>'SISPER',
					'clave'=>'SISOPE2',
					'tipo'=>OCI_DEFAULT,
					'bd'=>'172.32.0.4:1521/ogeip.senamhi.gob.pe');
	}
	
	public function coneccion_siscar(){
		return  array(
					'usuario'=>'SISCAR',
					'clave'=>'AUR012011',
					'tipo'=>OCI_DEFAULT,
					'bd'=>'172.32.0.4:1521/ogeip.senamhi.gob.pe');
	}	
	public function coneccion_sisconv(){
		return  array(
					'usuario'=>'SISCONV',
					'clave'=>'SISCONV',
					'tipo'=>OCI_DEFAULT,
					'bd'=>'172.32.0.4:1521/ogeip.senamhi.gob.pe');
	}
	public function coneccion_sisaba(){
		return  array(
					'usuario'=>'SISABA',
					'clave'=>'ABASISP',
					'tipo'=>OCI_DEFAULT,
					'bd'=>'172.32.0.4:1521/ogeip.senamhi.gob.pe');
	}
	
	public function coneccion_sisfacweb(){
			return array(
						'usuario'=>'SISFACWEB',
						'clave'=>'SISFAC',
						'tipo'=>OCI_DEFAULT,
						'bd'=>'172.32.0.4:1521/ogeip.senamhi.gob.pe');
		}
	
	public function coneccion_sys(){
		return  array(
					'usuario'=>'sys',
					'clave'=>'D1sn3yM1k1',
					'tipo'=>OCI_SYSDBA,
					'bd'=>'172.32.0.4:1521/ogeip.senamhi.gob.pe');
	}
	
	public function ejec_store_oracle_sismethaweb($nombrestore,$arraydatos=null){
		return $this->ejec_store_procedura_oracle($nombrestore,$arraydatos,$this->coneccion_sismethaweb());
	}
	
	public function ejec_store_oracle_siscar($nombrestore,$arraydatos=null){
		return $this->ejec_store_procedura_oracle($nombrestore,$arraydatos,$this->coneccion_siscar());
	}
	
	public function ejec_store_oracle_sisconv($nombrestore,$arraydatos=null){
		return $this->ejec_store_procedura_oracle($nombrestore,$arraydatos,$this->coneccion_sisconv());
	}
	
	public function ejec_store_oracle_sisaba($nombrestore,$arraydatos=null){
		return $this->ejec_store_procedura_oracle($nombrestore,$arraydatos,$this->coneccion_sisaba());
	}
	
public function ejec_store_oracle_sisfacweb($nombrestore,$arraydatos=null){
		return $this->ejec_store_procedura_oracle($nombrestore,$arraydatos,$this->coneccion_sisfacweb());
	}

public function ejec_store_oracle_sisper($nombrestore,$arraydatos=null){
		return $this->ejec_store_procedura_oracle($nombrestore,$arraydatos,$this->coneccion_sisper());
	}
	
public function ejec_store_oracle_sys($nombrestore,$arraydatos=null){
		return $this->ejec_store_procedura_oracle($nombrestore,$arraydatos,$this->coneccion_sys());
	}
	
	public function ejec_store_procedura_oracle($nombrestore,$arraydatos=null,$arrayconeccion=null)
	{	
		
		if($arrayconeccion == null){
			$arrayconeccion = $this->coneccion_sismethaweb();
		}
		
		$usuario = $arrayconeccion['usuario'];
		$clave = $arrayconeccion['clave'];
		$bd = $arrayconeccion['bd'];
		$tipo = $arrayconeccion['tipo'];	
		
		
		if (function_exists('oci_connect')) {
			$conec =  oci_connect($usuario, $clave, $bd,'UTF8',$tipo);
		} else {
			echo "Las funciones de oci_connect no están disponibles.<br />\n";
		}
		
			if (!$conec) {
			    echo 'Error en conecci&oacute;n a la BASE DE DATOS oracle...<br>';
			}	else{
				$stid = oci_parse ($conec, "alter session set nls_date_format = 'dd/mm/yyyy'");
				oci_execute ($stid);
			}
		
		$curs = oci_new_cursor($conec);
			$variables = "";
				for($i=0;$i<count($arraydatos);$i++){
				$variables .= $arraydatos[$i][0].",";
				}		 
		$ins =  "begin ".$nombrestore."(".$variables.":io_cursor);end;"; 
				
		$stmt = oci_parse($conec, $ins);		
		//oci_bind_by_name($stmt, ":p_cip", $cip);
		
		$v_clob = '';
		
				for($i=0;$i<count($arraydatos);$i++){
					//oci_bind_by_name($stmt, $datos[$i][0], $datos[$i][1]);
					if(count($arraydatos[$i])==2){
						oci_bind_by_name($stmt, $arraydatos[$i][0], $arraydatos[$i][1]);
					}else{
						if($arraydatos[$i][2] == 'OCI_B_CLOB'){
							
							$clob = oci_new_descriptor($conec, OCI_D_LOB);							
							$v_clob = $arraydatos[$i][1];							
							oci_bind_by_name($stmt, $arraydatos[$i][0], $clob , -1 , OCI_B_CLOB );
							$clob->WriteTemporary($v_clob);
						}
						
					}	
				}
		oci_bind_by_name($stmt, ":io_cursor", $curs, -1, OCI_B_CURSOR); 		
		oci_execute($stmt, OCI_DEFAULT); 
		
		oci_commit($conec);
		
  		oci_execute($curs);
  		$contador=0;
  		$dato = null;
	 	while ($data = oci_fetch_row($curs)) {
	 		for($i = 0 ; $i<count($data);$i++){
				$arraydata[$i] = $data[$i];
 			}   
		$dato[$contador] = $arraydata;
      	$contador++;
  		}
   		oci_free_statement($stmt);
  		oci_free_statement($curs);	
  		
  			if($v_clob != '')
  				$clob->free();
  				
  		oci_close($conec);	
		return $dato;		
	}
	
    public function ejec_store_procedura_postgres($func, $parameters = null) {
    	    	
        try {

        $driver = "host=172.25.60.241 port=5432 dbname=postgres user=postgres password=123456";
        $connection = pg_connect($driver);
        	
        	$caddatos = '';
				if(count($parameters)>0){
					for($i=0;$i<count($parameters);$i++){
						$caddatos.= "'".$parameters[$i]."',";
					}
					//$caddatos = substr($caddatos,0,strlen($caddatos)-1);
				}

	     $qry = "BEGIN; select ".$func."(".$caddatos."'ref_cursor'); FETCH ALL IN ref_cursor; ";
			//echo '<textarea>'.$qry.'</textarea><br>';
	     $result = pg_query ($connection, $qry) or die(pg_last_error());
	     
        	if (!$result) { 
			    pg_query($connection, "ROLLBACK"); 
			} else { 
			    pg_query($connection, "COMMIT"); 
			}
				     
	     $num = pg_num_rows($result);
	     $array = array();
	     for ($i=0; $i < $num; $i++) {
	       $r = pg_fetch_row($result, $i);
	       		for ($j=0; $j <count($r); $j++){
	       			$array[$i][$j] = utf8_decode($r[$j]);
	       		}
	    }
	    return $array;	
        
            
        } catch (Exception $e) {
//            $this->logger->error($e->getMessage());
            return array($e->getMessage());
        }
    }
    
	public function ejec_store_procedura_mysql($nombrestore,$arraydatos = null)
	{		
		$conexion = mysql_connect("localhost", "root", "");
		mysql_select_db("reniec", $conexion);
		mysql_query("SET NAMES 'utf8'");
		
		$caddatos = ' ( ';
			if($arraydatos != '' || $arraydatos != null){
				if(count($arraydatos) > 0){
					for($i=0;$i<count($arraydatos);$i++){
						$valvar = $arraydatos[$i];
						$valvar = mysql_real_escape_string ($valvar,$conexion);
						$caddatos.= "'".$valvar."',";
					}
					$caddatos = substr($caddatos,0,strlen($caddatos)-1);
				}
			}
    		$caddatos .= ' )';
    	
		$que = "call ".$nombrestore.$caddatos;
		//echo '<textarea>'.$que.'</textarea>';
		$res = mysql_query($que, $conexion) or die(mysql_error());
		$tot = mysql_num_rows($res);			
//	    if ($totEmp > 0) {
	       $conta = 0;
	       $array = null;
		   while($row = mysql_fetch_row($res)) {
//		      $array[$conta] = $rowEmp;
//		      $conta++;
		      for($i = 0 ; $i<count($row);$i++){
//		      		$temparray[$i] = utf8_decode($row[$i]);
//					$temparray[$i] = htmlentities($row[$i]);
					$temparray[$i] = $row[$i];
	 			}   
			$array[$conta] = $temparray;
	      	$conta++;
		   }
		mysql_close($conexion);
		return $array;
	}
	
	public function ejec_store_procedura_sql($nombrestore,$arraydatos = null)
	{		
		ini_set('mssql.charset', 'UTF-8');
		$cn = mssql_connect("172.25.0.48","senamhi","senamhi"); 

		if(!$cn){
			echo 'error en base de datos';
		}else{			
			mssql_select_db("SENAMHI_Dati",$cn);			
			$caddatos = '';
			if($arraydatos != '' || $arraydatos != null){
				if(count($arraydatos) > 0){
					for($i=0;$i<count($arraydatos);$i++){
							//$nomvar = $arraydatos[$i][0];
							$valvar = $arraydatos[$i];
						$caddatos.= "'".$valvar."',";
					}
					$caddatos = substr($caddatos,0,strlen($caddatos)-1);
				}
			}
			$cadins = 'exec '.$nombrestore.' '.$caddatos;
//			echo '<textarea>'.$cadins.'</textarea>';
			$result=mssql_query($cadins,$cn) or die("Error al ejecutar: <textarea>".$cadins."</textarea>"); 
			$contador=0;
			$datos = null;
			while ($row = mssql_fetch_row($result)) {
//				$c = count($row);
//						for($i = 0 ; $i<$c;$i++){
//								//$cadreplace = $row[$i];
//								$cadreplace = htmlentities($row[$i]);
//								$cadreplace = str_replace('&amp;','&',$cadreplace);
//								$cadreplace = str_replace("'",' ',$cadreplace);
//								$cadreplace = str_replace('"',' ',$cadreplace);
//								$cadreplace = str_replace('|','',$cadreplace);
//								$cadreplace = str_replace('°','',$cadreplace);
//								$cadreplace = str_replace('\\','',$cadreplace);	
//								//$cadreplace = str_replace('&Ntilde;',htmlentities('Ñ'),$cadreplace);
//								$arraydata[$i] = $cadreplace; 
//				 			}      	
//				 		$datos[$contador] = $arraydata;
//			   $contador++;
			   $datos[] = $row;
			} 
			
			mssql_close($cn);
			return $datos;
		}
    		
    		
    		
    		
	}
	
}