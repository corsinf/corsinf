<?php 
include ("../modelo/reportesM.php");
include ("../funciones/funciones.php");
/**
 * 
 */
$controlador =  new reportes();
if(isset($_GET['tipo_reporte']))
{
	echo json_encode($controlador->tipo_reporte());
}
if(isset($_GET['crear_reporte']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->crear_reporte($parametros));
}
if(isset($_GET['datos_reporte']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->datos_reporte($parametros));
}
if(isset($_GET['guardar_campos']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_campos($parametros));
}
if(isset($_GET['detalle_reporte']))
{
	$parametros = $_POST;
	// print_r($parametros);die();
	echo json_encode($controlador->detalle_reporte($parametros));
}
if(isset($_GET['filtro_reporte']))
{
	$parametros = $_POST;
	// print_r($parametros);die();
	echo json_encode($controlador->filtro_reporte($parametros));
}
if(isset($_GET['lista_reportes']))
{
	// print_r($parametros);die();
	echo json_encode($controlador->lista_reportes());
}


class reportes
{
	private $modelo;
	
	function __construct()
	{
		$this->modelo = new reportesM();
		$this->funciones = new funciones();
	}

	function tipo_reporte()
	{
		return $this->modelo->tipo_reporte();
	}
	function crear_reporte($parametros)
	{
		$reg = $this->modelo->buscar_reporte($parametros['tipo'],$parametros['nombre']);
		if(count($reg)==0)
		{
			$datos[0]['campo'] = 'NOMBRE_REPORTE'; 
			$datos[0]['dato'] = $parametros['nombre'];
			$datos[1]['campo'] = 'TIPO_REPORTE';
			$datos[1]['dato'] = $parametros['tipo']; 
			$datos[1]['tipo'] = 'string'; 
			$datos[2]['campo'] = 'DETALLE';
			$datos[2]['dato'] = $parametros['detalle']; 

			$ing = $this->modelo->add('REPORTE',$datos);		
			$reg = $this->modelo->buscar_reporte($parametros['tipo'],$parametros['nombre']);
			return array('respuesta'=>$ing,'id'=>$reg[0]['ID_REPORTE']);
		}else
		{
			return array('respuesta'=>-2,'id'=>$reg[0]['ID_REPORTE']);;
		}

	}
	function datos_reporte($parametros)
	{
		$id = $parametros['id'];
		$datos = $this->modelo->datos_reporte($id);

		$tablas = $datos[0]['TABLAS_ASOCIADAS'];		
		$principal = $datos[0]['TABLA_PRINCIPAL'];
		$titulo =  $datos[0]['NOMBRE_REPORTE'];
		$descripcion =  $datos[0]['DETALLE'];
		$tablas = explode(',',$tablas);
		$div = '';

		if($principal!='') {
			$div.='<div class="card border-top border-0 border-4 border-danger">
					<div class="card-body p-3">
						<div class="card-title d-flex align-items-center">
							<div><i class="bx bxs-table me-1 font-22 text-danger"></i>
							</div>
							<h6 class="mb-0 text-danger">'.$this->funciones->equivalente($principal).'</h6>
						</div>
						<div class="row">';
			$campo = $this->modelo->campos_tabla($principal);
			// print_r($campo);die();
			// imprime los campos en un check
			foreach ($campo as $key1 => $value1) {
				//la mayoria de lso campos que son primary y foreing key son de tipo int asi que no los colocamos
				if($value1['tipo']!='int')
				{
					$div.='<div class="col-sm-3">
				 		 <label title="'.$value1['campo'].'"><input type="checkbox" id="'.$principal.'-'.$value1['campo'].'" name="'.$principal.'-'.$value1['campo'].'"> '.$this->funciones->equivalente($value1['campo']).'</label>
					</div>';
				}
			}
			$div.="</div></div></div>";			
		}

		//crea la seccion segun la tabla ASOCIADAS

		// print_r($tablas);die();
		foreach ($tablas as $key => $value) {
			if($value!='')
			{
				$div.='<div class="card border-top border-0 border-4 border-danger">
						<div class="card-body p-3">
							<div class="card-title d-flex align-items-center">
								<div><i class="bx bxs-table me-1 font-22 text-danger"></i>
								</div>
								<h6 class="mb-0 text-danger">'.$this->funciones->equivalente($value).'</h6>
							</div>
							<div class="row">';
				$campo = $this->modelo->campos_tabla($value);
				// print_r($campo);die();
				// imprime los campos en un check
				foreach ($campo as $key1 => $value1) {
					//la mayoria de lso campos que son primary y foreing key son de tipo int asi que no los colocamos
					if($value1['tipo']!='int')
					{
						$div.='<div class="col-sm-3">
					 		 <label title="'.$value1['campo'].'"><input type="checkbox" id="'.$value.'-'.$value1['campo'].'" name="'.$value.'-'.$value1['campo'].'"> '.$this->funciones->equivalente($value1['campo']).'</label>
						</div>';
					}
				}
				$div.="</div></div></div>";		
			}	
		}

		return array('div'=>$div,'campos'=>$datos[0]['CAMPOS'],'titulo'=>$titulo,'detalle'=>$descripcion);
	}
	function guardar_campos($parametros)
	{
		if(isset($parametros['id']) && $parametros['id']!='')
		{
			$campos = '';
			$temTabla = '';
			$arraygrupos =array();
			$selected = '';
			
			$datos = $this->modelo->datos_reporte($parametros['id']);
			$principal = $datos[0]['TABLA_PRINCIPAL'];
			$detalle = $parametros['txt_detalle'];
			$titulo_rep = $parametros['txt_titulo'];

			unset($parametros['txt_detalle']);	
			unset($parametros['txt_titulo']);		
			$campos_todos = array();
			foreach ($parametros as $key => $value) {
				if($key!='id')
				{
					$selected.= $key.',';
					$datos = explode('-', $key);
					if($temTabla!=$datos[0])
					{
						$temTabla = $datos[0];
						$arraygrupos[$temTabla] = array($datos[1]);
 					}else
					{						
						array_push($arraygrupos[$temTabla], $datos[1]);
					}
					array_push($campos_todos,$datos[1]);
				}
			}

			//analisa cual de los datos esta repetido en nombre
			$campos2 = array_unique($campos_todos);
			$v_comunes1 = array_diff_assoc($campos_todos, $campos2); 
			$v_comunes2 = array_unique($v_comunes1); 
			$repetidos = implode(',',$v_comunes2);  
			$repetidos = explode(',',$repetidos);  
			// fin de analizar datos repetidos

			//GENERA EL HTML DE LOS FILTROS
			$fil = array();
			$filtros_html = '';
			foreach ($arraygrupos as $key => $value) {
				$tabla = $key;
				foreach ($value as $key2 => $value2) {
					$campo = $this->modelo->campos_tabla($tabla,$value2);
					// print_r($campo[0]['tipo']);
					$titulo = '';
					foreach ($repetidos as $key3 => $value3) {
						if($value3== $value2)
						{
							$titulo = $tabla;
						}							
					}

					if($campo[0]['tipo']=='date' || $campo[0]['tipo']=='datetime')
					{
						$filtros_html.='<div class="col-sm-3">
						<b style="font-size: 12px;">'.$this->funciones->equivalente($value2).'</b>
							<div class="row">
								<div class="col-sm-6">
									<input type="date" class="form-control form-control-sm" id="txt_'.$value2.'-'.$titulo.'" name="txt_'.$value2.'-'.$titulo.'[]">
								</div>
								<div class="col-sm-6">
									<input type="date" class="form-control form-control-sm" id="txt_'.$value2.'-'.$titulo.'" name="txt_'.$value2.'-'.$titulo.'[]">
								</div>
							</div>
						</div>';
					}else if($campo[0]['tipo']=='bit'){
						$filtros_html.='<div class="col-sm-2">
						<br>
						<label>
							<input type ="checkbox" id="txt_'.$value2.'-'.$titulo.'" name="txt_'.$value2.'-'.$titulo.'">
							<b style="font-size: 12px;">'.$value2.'</b></label>
						</div>';
					}else{						
						$filtros_html.='<div class="col-sm-3">
						<b style="font-size: 12px;">'.$value2.' '.$titulo.'</b>
							<input class="form-control form-control-sm" id="txt_'.$value2.'-'.$titulo.'" name="txt_'.$value2.'-'.$titulo.'">
						</div>';
					}				
				}
			}

			$filtros_html.='<div class="col-sm-12 text-end"><button type="button" class="btn btn-danger btn-sm" onclick="detalle_reporte('.$parametros['id'].')">Buscar</button></div>';



			//fin generacion de html


			// print_r('ddd');die();
			

			//generamos el sql para guardar
			$from = ' FROM '.$principal.' A ';
			$join = '';
			$campos = 'SELECT ';
			$iden = 'A';
			$cam = '';
			foreach ($arraygrupos as $key => $value) {
				if($cam!=$key)
				{
					$cam = $key;

					$iden = substr($cam,0,4);
					// $iden++;
				}
				$join.= $this->funciones->join_tabla($principal,'A',$cam,$iden);
				foreach ($value as $key2 => $value2) {		
					if($cam!=$principal)
					{
						$titulo = '';
						foreach ($repetidos as $key3 => $value3) {
							if($value3==$value2)
							{
								$titulo = $cam;
							}							
						}			
						$campos.=$iden.'.'.$value2.' as "'.$value2.' '.$titulo.'" ,';
					}else
					{
						$campos.='A.'.$value2.',';			
					}
				}

			}

			$pk =  $this->modelo->PK($principal);

			$campos = substr($campos,0,-1);

			$sql = $campos.$from.' '.$join.' ORDER BY '.$pk[0]['PRIMARYKEYCOLUMN'].' DESC';

			//guardamos los campos;
			$datosR[0]['campo'] = 'CAMPOS';
			$datosR[0]['dato'] = substr($selected,0,-1);
			$datosR[1]['campo'] = 'SQL';
			$datosR[1]['dato'] = $sql;
			$datosR[2]['campo'] = 'FILTROS_HTML';
			$datosR[2]['dato'] = $filtros_html;
			$datosR[3]['campo'] = 'DETALLE';
			$datosR[3]['dato'] = $detalle;
			$datosR[4]['campo'] = 'NOMBRE_REPORTE';
			$datosR[4]['dato'] = $titulo_rep;


			$where[0]['campo'] = 'ID_REPORTE';
			$where[0]['dato'] = $parametros['id'];

			return $this->modelo->update('REPORTE',$datosR,$where);

		}
	}
	function detalle_reporte($parametros)
	{
		$filtro_para = false;
		// print_r($parametros);die();
		if(isset($parametros['id']) && $parametros['id']!='')
		{
			$datos = $this->modelo->datos_reporte($parametros['id']);
			$sql = $datos[0]['SQL'];
			$detalle = $datos[0]['DETALLE'];
			$nombre =  $datos[0]['NOMBRE_REPORTE'];

			$sql = $this->funciones->generar_sql($parametros,$sql,$para_vista=true);
			// print_r($sql);die();
			$campos = $datos[0]['CAMPOS'];
			$campos = explode(',', $campos);
			$cabe = array();
			// $tbl = ' <thead><tr>';                    
                  
			foreach ($campos as $key => $value) {
				$cam = explode('-',$value);
				$cabe[] = $cam[1];
				$c[] = array('data'=>$cam[1]);
				// $tbl.='<th>'.$cam[1].'</th>';
			}
			$tbl='';
			// print_r($sql);die();
			$data = $this->modelo->realizar_consulta($sql['sql_normal']);
			$total = $this->modelo->realizar_consulta($sql['sql_total']);
			$total = $total[0]['total'];

			// print_r($data);
			// print_r($total);die();

			//-----------------generar paginacion--------------

			$pag2 = explode('-',$parametros['pag']);
			$pag = explode('-',$parametros['pag2']);         
        	// var pag2 = $('#txt_pag').val().split('-');

          $pagi = '';
        if($total > $pag[1])
        {
     	   $pagi.= '<li class="paginate_button page-item" onclick="guias_pag(\'-\')"><a class="page-link" href="#"> << </a></li>';
     
           $num = $total / $pag[1];
           if($num > 10)
           {
	            if($pag2[0]/$pag[1] < 9)
	            {
		            for ($i = 1; $i < 11 ; $i++) 
		            {
		               	$pos = $pag[1]; //pag[1]*i;
		              	$ini =$pag[0]+($pag[1]*$i)-$pag[1];  
		              	$pa = $ini.'-'.$pos;
			            if($parametros['pag']==$pa)
			            {
			               $pagi.='<li class="paginate_button page-item active" onclick="paginacion(\''.$pa.'\')"><a class="page-link" href="#">'.$i.'</a></li>';
			            }else
			            { 
			                $pagi.='<li class="paginate_button page-item" onclick="paginacion(\''.$pa.'\')"><a class="page-link" href="#">'.$i.'</a></li>';
			            }
		            }
	           	}else
	           	{
		            $pagi.='<li class="paginate_button page-item" onclick="paginacion(\'0-25\')"><a class="page-link" href="#">1</a></li>';
		            for ($i = $pag2[0]/25+1; $i < ($pag2[0]/25)+10 ; $i++) 
		            {
			              $pos =$pag[1]; //pag[1]*i;
			              $ini =$pag[0]+($pag[1]*$i)-$pag[1];  
			              $pa  = $ini.'-'.$pos;
			              if($parametros['pag']==$pa){
			               $pagi.='<li class="paginate_button page-item active" onclick="paginacion(\''.$pa.'\')"><a class="page-link" href="#">'.$i.'</a></li>';
			              }else
			              { 
			                $pagi.='<li class="paginate_button page-item" onclick="paginacion(\''.$pa.'\')"><a class="page-link" href="#">'.$i.'</a></li>';
			              }
		            }
	           	}
	            $pagi.='<li class="paginate_button page-item" onclick="guias_pag(\'+\')"><a class="page-link" href="#"> >> </a></li>';
           }else
           { 
             
            for ($i = 1; $i < $num+1 ; $i++) {
              $pos = $pag[1]; //pag[1]*i;
              $ini = $pag[0]+($pag[1]*$i)-$pag[1];  
              $pa = $ini.'-'.$pos;
              if($parametros['pag'] == $pa)
              {
               $pagi.='<li class="paginate_button page-item active"  onclick="paginacion(\''.$pa.'\')"><a class="page-link" href="#">'.$i.'</a></li>';
              }else
              {  
                $pagi.='<li class="paginate_button page-item"  onclick="paginacion(\''.$pa.'\')"><a class="page-link" href="#">'.$i.'</a></li>';
              }
            }
           }
       }
			//-------------fin generar paginacion--------------

           // print_r($pagi);die();


			//--------------lineas de detalle--------------------
			foreach ($data as $key => $value) {

				$arraysIndividuales = array_map(function($elemento) {
					return array($elemento);
				}, $value);

				// print_r($arraysIndividuales);die();

				$tbl.='<tr>';
				foreach ($arraysIndividuales as $key2 => $value2) {
					$value2 = $value2[0];
					// print_r($value2);die();
					if(!is_object($value2))
					{
						$tbl.='<td>'.$value2.'</td>';
					}else
					{
						// print_r($value2);die();
						$tbl.='<td>'.$value2->format('Y-m-d').'</td>';
					}	
				}
				$tbl.= '</tr>';
			}
			//---------------fin linea de detalles--------------------

			return array('body'=>$tbl,'head'=>$c,'paginacion'=>$pagi,'detalle'=>$detalle,'nombre'=>$nombre);
			// print_r($tbl);die();
		}
	}
	function filtro_reporte($parametros)
	{
		$filtro_para = false;
		// print_r($parametros);die();
		if(isset($parametros['id']) && $parametros['id']!='')
		{
			$datos = $this->modelo->datos_reporte($parametros['id']);
			$sql = $datos[0]['SQL'];
			$filtros = $datos[0]['FILTROS_HTML'];

			return $filtros;
		}
	}

	function lista_reportes()
	{
		$datos = $this->modelo-> buscar_reporte($tipo=false,$nombre=false);
		$html = '';
		foreach ($datos as $key => $value) {
		$html.= '<div class="col">
					<div class="card">
						<div class="card-body">
							<div>
								<h5 class="card-title">'.strtoupper($value['NOMBRE_REPORTE']).'</h5>
							</div>
							<p class="card-text">Informe del total de activos registrados.</p>
							<div class="alert border-0 border-start border-5 border-warning alert-dismissible fade show py-2">
								<div class="d-flex align-items-center">
									<div class="font-35 text-warning"><i class="bx bx-info-circle"></i>
									</div>
									<div class="ms-3">
										<h6 class="mb-0 text-warning">Advertencia</h6>
										<div>Este proceso podria durar varios minutos</div>
									</div>
								</div>
							</div>
							<div class="col text-end">
										<div class="btn-group" role="group" aria-label="Basic example">
											<a href="reporte_detalle.php?id='.$value['ID_REPORTE'].'" id="" class="btn btn-outline-dark btn-sm"><i class="bx bx-show-alt"></i></a>
										<a href="nuevo_reporte.php?id='.$value['ID_REPORTE'].'" id="" class="btn btn-primary btn-sm" id=""><i class="bx bx-pencil"></i></a>
											<a href="nuevo_reporte.php?id='.$value['ID_REPORTE'].'" id="btn_eliminar" class="btn btn-danger btn-sm" id=""><i class="bx bx-trash"></i></a>
										</div>
							</div>
						</div>
					</div>
				</div>';
			// print_r($datos);die();
		}

		return $html;		
	}
}

?>