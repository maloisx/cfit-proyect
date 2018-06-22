<?php

class Zend_View_Helper_Util extends Zend_View_Helper_Abstract {
	
	private $widthLayout = 1600;

    public function util() {
        return $this;
    }
    
	public function console($msj , $tipo = 'out') {
		
		$tipomensaje =  null;
			
		if($tipo == 'out' || $tipo == '')
			$tipomensaje = Zend_Log::INFO;
		if($tipo == 'err')
			$tipomensaje = Zend_Log::ERR;
		
		$logger = Zend_Registry::get('logger');
		$logger->log($msj, $tipomensaje);
    }
	
	public function multi_array_unique($arr) { 
		foreach ($arr as &$elm) {
        $elm = serialize($elm);
	    }
	
	    $arr = array_unique($arr);
	
	    foreach ($arr as &$elm) {
	        $elm = unserialize($elm);
	    }
	
	    return $arr;
    }

	public function getConfigmail() {
    
    	$config = array('ssl' => 'tls', 
		                 'port' => 587, 
		                 'auth' => 'login', 
		                 'username' => 'pruebas.software.malois@gmail.com', 
		                 'password' => '*123456789*');
    	
		return $smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
    	
    }
    
    public function getTitle() {
        return "SENAMHI";
    }
    
    public function getEntidad() {
    	return "";
    }
    
    public function getRUC() {
    	return "";
    }
    
    public function getAreaCobranza() {
    	return "";
    }
    
    public function getProtocol() {
        return "http://";
    }
    
    public function getPath() {
        $path = explode("/index.php", $_SERVER["PHP_SELF"]);
        return $this->getProtocol() . $_SERVER["HTTP_HOST"] . $path[0] . "/" . PATH;
    }

    public function getPath2() {
        $path = explode("/index.php", $_SERVER["PHP_SELF"]);
        return $this->getProtocol() . $_SERVER["HTTP_HOST"] . $path[0] . "/";
    }
    
	public function getPathReport() {
		return $this->getProtocol() . $_SERVER["HTTP_HOST"] . ':8084/titania/index.jsp?';
	}
    
    public function getLink($url) {
        return $this->getPath2() . "index.php/" . $url;
    }
    
    public function getImage($image) {
        return $this->getPath() . "img/$image";
    }
    
public function SetTitlePag($title) {
		if(trim($title) != ''){
        	$script =  "\t\t<script type='text/javascript'>$('#titulopag').html('".strtoupper($title)."').addClass('demo-section').height('30px');</script>\n";
		}else{
			$script =  "\t\t<script type='text/javascript'>$('#titulopag').html('').height('0px');</script>\n";
		}
		return $script;
    }
    
    public function getScript($pathJS) {
        $script = "\n";
        $files = $this->readFile($pathJS, $this->getPath());
        foreach ($files as $value) {
            if (!is_array($value)) {
                $script .= "\t\t<script type='text/javascript' src='$value'></script>\n";
            }
        }
        return $script;
    }    
    
    public function registerScriptJSController(Zend_Controller_Request_Abstract $request) {
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $script = "\t\t<script type='text/javascript' src='".$this->getPath()."js/js_".$controller.".js'/></script>\n";
        $session = new Zend_Session_Namespace("scriptController");
        $session->data = $script;
        return $script;
    }
    
    public function registerScriptJSControllerAction(Zend_Controller_Request_Abstract $request) {
    	$controller = $request->getControllerName();
    	$action = $request->getActionName();
    	$script = "\t\t<script type='text/javascript' src='".$this->getPath()."js/js_".$controller."_".$action.".js'/></script>\n";
    	$session = new Zend_Session_Namespace("scriptControllerAction");
    	$session->data = $script;
    	return $script;
    }

    public function registerLeaveControllerAction(Zend_Controller_Request_Abstract $request) {
    	$controller = $request->getControllerName();
    	$action = $request->getActionName();
    	$script  = "\t\t<script type='text/javascript'/>var __rw = ".'false'.";</script>\n";
    	$session = new Zend_Session_Namespace("leave");
    	$session->data = $script;
    	return $script;
    }    
    
    public function getScriptJSController() {
        $session = new Zend_Session_Namespace("scriptController");
        $script = $session->data;
        $session->data = "";
        return $script;
    }
    
    public function getScriptJSControllerAction() {
    	$session = new Zend_Session_Namespace("scriptControllerAction");
    	$script = $session->data;
    	$session->data = "";
    	return $script;
    }
    
    public function getScriptLeave() {
    	$session = new Zend_Session_Namespace("leave");
    	$script = $session->data;
    	$session->data = "";
    	return $script;
    }
    
    public function getStyle() {
        $files = $this->readFile("css", $this->getPath());
        $script = $this->style($files);
        return $script;
    }

    public function getWidthLayout() {
    	return $this->widthLayout . "px";
    }
    
    public function getSubstractWidthLayout($size) {
    	return ($this->widthLayout - $size) . "px";
    }    
    
    private function style($files) {
        $script = "\n";
        foreach ($files as $value) {
            if (!is_array($value)) {
                $ext = explode(".", $value);
                if (strtolower($ext[count($ext) - 1]) == 'css') {
                    $script .= "\t<link href='$value' rel=\"stylesheet\" type=\"text/css\"/>\n";
                }
            } else {
                $script .= $this->style($value);
            }
        }
        return $script;
    }
    
    private function readFile($carpeta, $path) {
        $script = array();
        if (is_dir(PATH . $carpeta)) {
            if (($_carpeta = opendir(PATH . $carpeta))) {
                while (($archivo = readdir($_carpeta)) !== false) {
                    if (is_dir(PATH . $carpeta . "/" . $archivo) && $archivo != "." && $archivo != "..") {
                        $script[] = $this->readFile($carpeta . "/" . $archivo, $path);
                    } else {
                        if ($archivo != "." && $archivo != "..") {
                            $script[] = $path . $carpeta . "/" . $archivo;
                        }
                    }
                }
                closedir($_carpeta);
            }
        }
        
        sort($script);
        return $script;
    }
    
	public function subval_sort($a,$subkey) {
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$subkey]);
		}
		asort($b);
		foreach($b as $key=>$val) {
			$c[] = $a[$key];
		}
		return $c;
	}
    
	//------------------------------
	/*
	 * ordena el array bidimensional segun el campo especificado , ejemplo $array_ordenadito2 = $this->ordenar_array($array_a_ordenar, 'campo3', SORT_DESC, 'campo2', SORT_DESC, 'campo1', SORT_ASC ) or die('<br>ERROR!<br>'); print_r($array_ordenadito2);
	 * @param array $array_a_ordenar
	 * return  
	 * */
	public function ordenar_array() { 
		  $n_parametros = func_num_args(); // Obenemos el nmero de parmetros 
		  if ($n_parametros<3 || $n_parametros%2!=1) { // Si tenemos el n de parametro mal... 
		    return false; 
		  } else { // Hasta aqu todo correcto...veamos si los parmetros tienen lo que debe ser... 
		    $arg_list = func_get_args(); 
		
		    if (!(is_array($arg_list[0]) && is_array(current($arg_list[0])))) { 
		      return false; // Si el primero no es un array...MALO! 
		    } 
		    for ($i = 1; $i<$n_parametros; $i++) { // Miramos que el resto de parmetros tb estn bien... 
		      if ($i%2!=0) {// Parmetro impar...tiene que ser un campo del array... 
		        if (!array_key_exists($arg_list[$i], current($arg_list[0]))) { 
		          return false; 
		        } 
		      } else { // Par, no falla...si no es SORT_ASC o SORT_DESC...a la calle! 
		        if ($arg_list[$i]!=SORT_ASC && $arg_list[$i]!=SORT_DESC) { 
		          return false; 
		        } 
		      } 
		    } 
		    $array_salida = $arg_list[0]; 
		
		    // Una vez los parmetros se que estn bien, proceder a ordenar... 
		    $a_evaluar = "foreach (\$array_salida as \$fila){\n"; 
		    for ($i=1; $i<$n_parametros; $i+=2) { // Ahora por cada columna... 
		      $a_evaluar .= "  \$campo{$i}[] = \$fila['$arg_list[$i]'];\n"; 
		    } 
		    $a_evaluar .= "}\n"; 
		    $a_evaluar .= "array_multisort(\n"; 
		    for ($i=1; $i<$n_parametros; $i+=2) { // Ahora por cada elemento... 
		      $a_evaluar .= "  \$campo{$i}, SORT_REGULAR, \$arg_list[".($i+1)."],\n"; 
		    } 
		    $a_evaluar .= "  \$array_salida);"; 
		    // La verdad es que es ms complicado de lo que crea en principio... :) 
		
		    eval($a_evaluar); 
		    return $array_salida; 
		  } 
		} 
	
	//------------------------------
    	   
	public function datatable($id,$opciones,$estaticolumn = false,$estaticolumn_opc = '') { 
		$n = rand() ;
        $datatable =  '<script>';
        $datatable .= 'var oTable'.$n.' = $("#'.$id.'").dataTable( {';	
        	for($i=0;$i<count($opciones);$i++){
        		$datatable .= '"'.$opciones[$i][0].'":'.$opciones[$i][1].',';        	
        	}	
		$datatable .= '});';
		$datatable .= 'oTable'.$n.'.$("tr").hover( function() {					      
					       $(this).addClass("highlighted");
					    }, function() {
					        oTable'.$n.'.$("tr.highlighted").removeClass("highlighted");
					    } );';
		if($estaticolumn){
		$datatable .= 'new FixedColumns( oTable'.$n. (($estaticolumn_opc!='')?','.$estaticolumn_opc:'') .');';
		}
//		$datatable .= 'setTimeout(function () {oTable'.$n.'.fnAdjustColumnSizing();}, 10 );';
		$datatable .= '</script>';
		
		return $datatable;
    }
    
	public function jqplot_graf_lineal($idplot,$datos = array(),$series = array(),$titulo = '',$x_label = '',$x_formarstring = '',$y_label = '',$y_formarstring = '') { 

//		$colores= array("#4bb2c5", "#c5b47f", "#EAA228", "#579575", "#839557", "#958c12","#953579", "#4b5de4", "#d8b83f", "#ff5800", "#0085cc");
		$colores = array();
		for($i=0;$i<count($series);$i++){
			 $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    		 $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    		 $exist_color = 0;	
			 for($j=0;$j<count($colores);$j++){
				if($colores[$j] == $color){
					$exist_color = 1;
				}
			}
			if($exist_color == 0){
				$colores[] = $color;
			}				
		}
		
		
		//obteniendo el min y max valor del x
		$datos_minmax_temp_x = array();
		for($i=0;$i<count($datos);$i++){
			for($j=0;$j<count($datos[$i]);$j++){
				$array_valorx = explode(" ",$datos[$i][$j][0]);
				$datos_minmax_temp_x[] = $array_valorx[0] ;
			}			
		}
		sort($datos_minmax_temp_x);
//		$min_x =  $datos_minmax_temp[0];		
//		$max_x = $datos_minmax_temp[count($datos_minmax_temp)-1];
		$min_x = date('Y-m-d', strtotime($datos_minmax_temp_x[0] . ' -2 days'));
		$max_x = date('Y-m-d', strtotime($datos_minmax_temp_x[count($datos_minmax_temp_x)-1] . ' +2 days'));
		
//		echo $min_x . '->' . $max_x.'<br><br>';
		
	    //obteniendo el min valor del y
		$datos_minmax_temp_y = array();
		for($i=0;$i<count($datos);$i++){
			for($j=0;$j<count($datos[$i]);$j++){
				if($datos[$i][$j][1] != '')
				$datos_minmax_temp_y[] = $datos[$i][$j][1] ;
			}			
		}
		if(count($datos_minmax_temp_y)>0){
			sort($datos_minmax_temp_y);		
			$min_y = $datos_minmax_temp_y[0];
		}else{
			$min_y = 0;
		}
//		echo '------>>>>'.$min_y;
		
		//si una serie no tuviera ningun parametro, la llenamos con el minimo valor de y encontrado anteriormente // bug arreglado 
		for($i=0;$i<count($datos);$i++){
			$var_t = '';
			for($j=0;$j<count($datos[$i]);$j++){
				if($datos[$i][$j][1] != '')
					$var_t = $datos[$i][$j][1] ;
			}	
			if($var_t == ''){
				for($j=0;$j<count($datos[$i]);$j++){
					$datos[$i][$j][1] = $min_y;
				}
				$series[$i]['show']	= false ;				
//				$series[$i]['markerOptions']['size'] = 0;
//				$series[$i]['markerOptions']['show'] = false;
//				$series[$i]['showLine']	= false ;
//				$series[$i]['showMarker']	= false ;
//				$series[$i]['lineWidth']	= 0 ;
			}		
		}
		//------------------------------
		
//		print_r($datos);
//		echo '<br><br>';echo '<br><br>';
		$datos_temp = array();		
		for($i=0;$i<count($datos);$i++){
//			echo $i.':'.count($datos[$i]).'<br>';
			for($j=0;$j<count($datos[$i]);$j++){
//				echo '->'.$j.'<br>';
					if(trim($datos[$i][$j][1]) == ''){
//						echo '-->error:'.$datos[$i][$j][0].' ||  '.$datos[$i][$j][1].'<br>';
					}else{	
//						echo '-->entro:'.$datos[$i][$j][0].' ||  '.$datos[$i][$j][1].'<br>';
						$datos_temp[$i][$j][0] = $datos[$i][$j][0];
						$datos_temp[$i][$j][1] = $datos[$i][$j][1];
						$datos_temp[$i][$j][2] = $i;
						$datos_temp[$i][$j][3] = $datos[$i][$j][2];
					}
			}
//				echo '<br><br>';
//				print_r($datos_temp);
				$datos_temp = array_values($datos_temp);
				$datos_temp[$i] = array_values($datos_temp[$i]);
//				print_r($datos_temp);
		}
		$datos_temp = array_values($datos_temp);

		$n_array = 0;
		for($i=0;$i<count($datos_temp);$i++){
			for($j=0;$j<count($datos_temp[$i]);$j++){				
				$datos_temp[$i][$j][4] = $n_array;
				$array_valorx = explode(" ",$datos_temp[$i][$j][0]);
				$valorx = $array_valorx[0];
				$array_valorx_siguiente = explode(" ",$datos_temp[$i][(($j+1<count($datos_temp[$i]))?$j+1:$j)][0]); 
				$valorx_siguiente = $array_valorx_siguiente[0];				
				if($valorx_siguiente > date('Y-m-d', strtotime($valorx . ' + '.(($j+1<count($datos_temp[$i]))?1:0).' days')) ){
					$n_array++;
				}
			}
			$n_array++;
		}
		
		$array_partidos = array();
		for($i=0;$i<count($datos_temp);$i++){
			for($j=0;$j<count($datos_temp[$i]);$j++){
				$array_partidos[$datos_temp[$i][$j][4]][] = $datos_temp[$i][$j] ;
			}			
		}
		
		$array_series = array();
		for($i=0;$i<count($array_partidos);$i++){
			for($j=0;$j<count($array_partidos[$i]);$j++){				
				    $series[$array_partidos[$i][$j][2]]['color'] = $colores[$array_partidos[$i][$j][2]];
					$array_series[$i] = $series[$array_partidos[$i][$j][2]];
			}
		}
		
		//pasamos los parametros de "Y" a tipo float, //bug arreglado 
		$array_partidos_tmp= array();
		for($i=0;$i<count($array_partidos);$i++){
			for($j=0;$j<count($array_partidos[$i]);$j++){
				$array_partidos_tmp[$i][$j] = array($array_partidos[$i][$j][0],floatval ($array_partidos[$i][$j][1]),$array_partidos[$i][$j][2],$array_partidos[$i][$j][3],$array_partidos[$i][$j][4]) ;
			}			
		}
		
		$array_partidos = $array_partidos_tmp;
		
		$jqplot= '<script>		
				$(document).ready(function(){
					 
					  var jqplot = $.jqplot("'.$idplot.'", '.json_encode($array_partidos).', 
					    { 
					      title:"'.$titulo.'",
					      legend: {
					           show: true,
					           placement: "outside"
					        },
					      highlighter: {
					        show: true,
					        sizeAdjust: 10,
            				tooltipOffset: 9,
					        lineWidthAdjust: 3.5,
					        tooltipAxes: "#title#"
					      },
					      cursor: {show: true,zoom: true},
//				          axesDefaults: {labelRenderer: $.jqplot.CanvasAxisLabelRenderer},
					      axes: {
					          xaxis: {
					            	label: "'.$x_label.'"
					            	,labelRenderer: $.jqplot.CanvasAxisLabelRenderer
					            	,renderer: $.jqplot.DateAxisRenderer
					            	,tickOptions: {
					                    formatString: "'.$x_formarstring.'"
					                    ,angle: -90					                    
					                }
					                ,tickRenderer:$.jqplot.CanvasAxisTickRenderer
					                ,min: "'.$min_x.'"
					                ,max: "'.$max_x.'"
					                ,tickInterval: "1 days"
					          },
					          yaxis: {
					            	label: "'.$y_label.'"
					            	,labelRenderer: $.jqplot.CanvasAxisLabelRenderer
					            	,tickOptions: {
					                    formatString: "'.$y_formarstring.'",					                    
					                    showMark: false
					                }
					          }
				          },
					      series: '.json_encode($array_series).'
					    }
					  );
					    
					});
				</script>
		';
		
		return $jqplot;
    }
    
	public function menu_buscar_hijos($datos_menu,$codmenu){
		
		$url = $this->util()->getPath();
		echo '<ul '.(($codmenu=='0')?' id="menu" ':'').'>';
		
		for($i=0;$i<count($datos_menu);$i++){	
							
			$cod_obj = $datos_menu[$i]->cod_menu;
		    $desc_obj = $datos_menu[$i]->desc_menu;
		    $cod_obj_padre = $datos_menu[$i]->cod_padre;
		    $url_obj = $datos_menu[$i]->url;
		    
		    $orden = $datos_menu[$i]->orden;
		    $cant_hijos = $datos_menu[$i]->hijos;

			if($codmenu == $cod_obj_padre){
								
					echo '<li>';
					echo '<a href="'. (($cant_hijos>0)?'javascript: void(0)':$url.$url_obj) .'">'.$desc_obj.'</a>';
						if($cant_hijos>0){
								$this->util()->menu_buscar_hijos($datos_menu,$cod_obj);					
						}
					echo '</li>';				
			}
			
		}
		echo '</ul>';
	}
	
	public function menu_buscar_hijos_boostrap($datos_menu,$codmenu){
		
		$url = $this->util()->getPath();
		
		
		for($i=0;$i<count($datos_menu);$i++){	
							
			$cod_obj = $datos_menu[$i]->cod_menu;
		    $desc_obj = $datos_menu[$i]->desc_menu;
		    $cod_obj_padre =  $datos_menu[$i]->cod_padre;
		    $url_obj = $datos_menu[$i]->url;
		    
		    $orden = $datos_menu[$i]->orden;
		    $cant_hijos = $datos_menu[$i]->hijos;
		    
			if($codmenu == $cod_obj_padre){				
				$cod_obj_padre = (($codmenu=='0')?'sidebar': 'menu'.$cod_obj_padre) ;				
				if($cant_hijos>0){
					echo '<a href="#menu'.$cod_obj.'" class="list-group-item collapsed" data-toggle="collapse" data-parent="#'.$cod_obj_padre.'" aria-expanded="false"><i class="fa fa-list"></i> <span class="hidden-sm-down">'.$desc_obj.'</span> </a>';
					echo '<div class="collapse" id="menu'.$cod_obj.'">';
						$this->util()->menu_buscar_hijos_boostrap($datos_menu,$cod_obj);	
					echo '</div>';
				}else{
					echo '<a href="'.$url.$url_obj.'" class="list-group-item collapsed" data-parent="#'.$cod_obj_padre.'"><i class=""></i> <span class="hidden-sm-down">'.$desc_obj.'</span></a>';
				}							
			}
			
			
			
			
		}
			if($codmenu=='0'){
				echo '<a href="'.$url.'index.php/logeo/logout" class="list-group-item collapsed" data-parent="#sidebar"><i class="fa fa-sign-out"></i> <span class="hidden-sm-down">Salir</span></a>';
			}
	}
    
    
}
