<?php
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/estado_trabajoM.php');
require_once(dirname(__DIR__, 2) .'/modelo/articulosM.php');
require_once(dirname(__DIR__, 2) .'/lib/Reporte_pdf.php');
/**
 * 
 */
$controlador = new estado_trabajoC();
if(isset($_GET['trabajos']))
{
	echo json_encode($controlador->lista_trabajos());
}
if(isset($_GET['reporte']))
{
	$parametros = $_GET['id'];
	echo json_encode($controlador->reporte_trabajo($parametros));
}
if(isset($_GET['add_ob']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_ob($parametros));
}
if(isset($_GET['add_ob_ord']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_ob_ord($parametros));
}
if(isset($_GET['guardar_materia']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_materia($parametros));
}
if(isset($_GET['guardar_materia_orden']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_materia_orden($parametros));
}

if(isset($_GET['guardar_materia_fal']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_materia_fal($parametros));
}
if(isset($_GET['guardar_materia_orden_fal']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_materia_orden_fal($parametros));
}


if(isset($_GET['estado_trabajo']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->estado_trabajo($query));
}
if(isset($_GET['lista_trabajos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_observaciones($parametros['id']));
	
}
if(isset($_GET['lista_trabajos_ord']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_observaciones_ord($parametros['id']));
	
}
if(isset($_GET['lista_material']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_material($parametros));
	
}

if(isset($_GET['lista_material_fa']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_material_fa($parametros));
	
}

if(isset($_GET['lista_material_orden']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_material_orden($parametros));
	
}
if(isset($_GET['finalizar_orden']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->finalizar_orden($parametros));
}

if(isset($_GET['estado_orden']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->estado_orden($parametros));
}

if(isset($_GET['ddl_materia_orden']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	$ti = '';
	if(isset($_GET['ti']))
	{
		$ti = $_GET['ti']; 
	}
	$id= $_GET['cod']; 
	
	// print_r($_GET);die();
	echo json_encode($controlador->ddl_materia_orden($query,$ti,$id));
}
if(isset($_GET['ddl_material_fa']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	$parametros = array('detalle'=>$query);
	echo json_encode($controlador->ddl_materia_fa($query));
}

if(isset($_GET['eliminar_linea_fal']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_linea_fal($parametros));
}

if(isset($_GET['aprobar_faltante']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->aprobar_faltante($parametros));
}

class estado_trabajoC
{
	private $modelo;
	private $pagina;
	private $punto_venta;
	private $pdf;
	private $articulos;
	function __construct()
	{
		$this->modelo = new estado_trabajoM();
		$this->pagina = new codigos_globales();
		$this->pdf = new Reporte_pdf();
		$this->articulos = new articulosM();
		$this->pagina->registrar_pagina_creada('../vista/estado_trabajo.php','Lista de trabajos','6','estado');

	}
	function lista_trabajos()
	{
		$datos = $this->modelo->lista_trabajos($query=false);
		$datos1 = $this->modelo->ordenes_trabajo($query=false);
		$num = count($datos);
		$num2 = count($datos1);
		$tr ='';
		$tr2='';
		foreach ($datos as $key => $value) {
			$apro = '<span class="float-right badge bg-primary">Ingresado</span>';
			if($value['aprobado']==1)
			{
				$apro = '<span class="float-right badge bg-success">aprobado</span>';
			}else if($value['aprobado']==2)
			{
				$apro = '<span class="float-right badge bg-danger">Rechazado</span>';
			}
			else if($value['aprobado']==3)
			{
				$apro = '<span class="float-right badge bg-warning">Enviado a revision</span>';
			}
			// print_r($value);die();
			$tr.='<tr>
			<td>
			    <!-- <button class="btn btn-default btn-sm" onclick="observaciones(\''.$value['id'].'\')"><i class="fas fa-file nav-icon"></i></button> -->
			    <a href="estado_trabajo_detalle.php?joya='.$value['id'].'" title="Detalle de trabajo" class="btn btn-default btn-sm"><i class="fas fas fa-list-alt  nav-icon"></i></a>
			   
			</td>
			<td>'.$value['fecha']->format('Y-m-d').'</td>
			<td>'.$value['cliente'].'</td>
			<td>'.$value['nombre'].'</td>
			<td>'.$value['trabajo'].'</td>
			<td>'.$value['est'].'</td>		
			<td>'.$apro.'</td>				

			</tr>';		

		}
		foreach ($datos1 as $key => $value) {
			$apro = '<span class="float-right badge bg-primary">Ingresado</span>';
			if($value['aprobado']==1)
			{
				$apro = '<span class="float-right badge bg-success">aprobado</span>';
			}else if($value['aprobado']==2)
			{
				$apro = '<span class="float-right badge bg-danger">Rechazado</span>';
			}else if($value['aprobado']==3)
			{
				$apro = '<span class="float-right badge bg-warning">Enviado a revision</span>';
			}
			$tipo ='producto';
			if($value['boceto']==1)
			{
				$tipo='boceto';
			}
			// print_r($value);die();
			$tr2.='<tr>
			<td>
			  <!--  <button class="btn btn-default btn-sm" onclick="reporte_trabajo(\''.$value['id'].'\')"><i class="fas fa-file nav-icon"></i></button> -->
			    <a href="estado_trabajo_detalle.php?'.$tipo.'='.$value['id'].'" title="Detalle de trabajo" class="btn btn-default btn-sm"><i class="fas fas fa-list-alt nav-icon"></i></a>
			   
			</td>
			<td>'.$value['fecha']->format('Y-m-d').'</td>
			<td>'.$value['Encargado'].'</td>
			<td>'.$value['codigo'].'</td>
			<td>'.$value['observacion'].'</td>
			<td>'.$value['estado'].'</td>		
			<td>'.$value['tipo'].'</td>		
			<td>'.$apro.'</span></td>	
			</tr>';		

		}
		$datos = array('joyas'=>$tr,'lineas'=>$num,'ordenes'=>$tr2,'lineas2'=>$num2);
		return $datos;

	}

	function reporte_trabajo($parametros)
	{
		$datos = $this->modelo->lista_trabajos($query=false,$parametros);
		$estado = $this->modelo->observaciones($parametros);
		$this->pdf->reporte_trabajo($datos,$estado);
	}
	function estado_trabajo($query)
	{
		$datos = $this->modelo->estado_trabajo($query);
		$resp = array();
		foreach ($datos as $key => $value) {
			$resp[] = array('id'=>$value['id'],'text'=>$value['nombre']);
		}
		return $resp;
	}
	function lista_observaciones($id)
	{	
		$estado = $this->modelo->observaciones($id);
		$tr = '';
		foreach ($estado as $key => $value) {
			$tr.='<tr><td>'.$value['fecha']->format('Y-m-d').'</td><td>'.$value['estado'].'</td><td>'.$value['observacion'].'</td></tr>';
		}
		return $tr;
	}
	function lista_observaciones_ord($id)
	{	
		$estado = $this->modelo->observaciones_ord($id);
		$tr = '';
		foreach ($estado as $key => $value) {
			$tr.='<tr><td>'.$value['fecha']->format('Y-m-d').'</td><td>'.$value['estado'].'</td><td>'.$value['observacion'].'</td></tr>';
		}
		return $tr;
	}

	function lista_material($parametros)
	{	
		$parametros['joya'] = $parametros['id'];
		$est = $this->estado_orden($parametros);
		$estado = $this->modelo->lista_material($parametros['joya']);
		// print_r($estado);die();
		$tr = '';
		foreach ($estado as $key => $value) {
			$tr.='<tr><td>'.$value['cantidad'].'</td><td>'.$value['material'].'</td>';
			if($est!=1 && $est!=3)
				{
					$tr.='<td><button class="btn btn-sm btn-danger"><i class="nav-icon fa fas fa-trash"></i></button></td></tr>';
				}
		}
		return $tr;
	}

	function lista_material_fa($parametros)
	{	

		$est = $this->estado_orden($parametros);
		$estado = $this->modelo->lista_material_fal($parametros['joya']);
		if($parametros['joya']=='')
		{
			$estado = $this->modelo->lista_material_fal(false,$parametros['orden']);
		}
		$tr = '';
		foreach ($estado as $key => $value) {
			$tr.='<tr><td></td><td>'.$value['cantidad'].'</td><td>'.$value['material'].'</td><td>'.$value['tipo'].'</td>';
			// if($est!=1 && $est!=3)
				// {
					$tr.='<td><button class="btn btn-sm btn-danger" type="button" onclick="eliminar_fal(\''.$value['id'].'\')"><i class="nav-icon fa fas fa-trash"></i></button></td></tr>';
				// }
		}
		return $tr;
	}

    function lista_material_orden($parametros)
	{
		// print_r($parametros);die();
		$parametros['joya'] = '';

		if($parametros['producto']!= '')
		{
			$parametros['orden'] = $parametros['producto'];
		   	$est = $this->estado_orden($parametros);
			$pro = $this->modelo->lineas_orden($parametros['producto']);
			$tr = '';

			foreach ($pro as $key => $value) {
				$estado = $this->modelo->lista_material_orden($value['id_producto']);
				// print_r($estado);die();
				$tr.='<tr><td><b><u>'.$value['cantidad'].'</u></b></td><td  colspan="2"><b><u>'.$value['producto'].'</u></b></td></tr>';				
			}

			$otros = $this->modelo->lista_material($id=false,$parametros['producto']);
			if(count($otros)>0)
			{
				// $tr.='<tr><td colspan="3"><b>Adicionales</b></td></tr>';
				foreach ($otros as $key => $value3) {
					$tr.='<tr><td>'.$value3['cantidad'].'</td><td>'.$value3['material'].'</td>';
					if($est!=3 && $est!=1)
						{
							$tr.='<td><button class="btn btn-sm btn-danger"><i class="nav-icon fa fas fa-trash"></i></button></td></tr>';
						}
				}
		    }


		    // material por default 
			$default = $this->modelo->lista_material_default($id=false,$parametros['producto']);
			// print_r($default);die();
		    foreach ($default as $key => $value4) {
					$tr.='<tr><td>'.$value4['cantidad'].'</td><td>'.$value4['material'].'</td></tr>';
				}

		}else
		{

			$parametros['orden'] = $parametros['boceto'];
		   	$est = $this->estado_orden($parametros);
			$tr='';
			$otros = $this->modelo->lista_material($id=false,$parametros['boceto']);
			if(count($otros)>0)
			{
				foreach ($otros as $key => $value3) {
					$tr.='<tr><td>'.$value3['cantidad'].'</td><td>'.$value3['material'].'</td>';
					if($est!=3 && $est!=1)
						{
							$tr.='<td><button class="btn btn-sm btn-danger"><i class="nav-icon fa fas fa-trash"></i></button></td></tr>';
						}
				}
		    }

		}
		
		// $estado = $this->modelo->lista_material_orden($id);
		// $tr = '';
		// foreach ($estado as $key => $value) {
		// 	$tr.='<tr><td>'.$value['cantidad'].'</td><td>'.$value['material'].'</td><td><button class="btn btn-sm btn-danger"><i class="nav-icon fa fas fa-trash"></i></button></td></tr>';
		// }
		return $tr;
	}

	function add_ob($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo']='id_estado';
		$datos[0]['dato']=$parametros['estado'];
		$datos[1]['campo']='observacion';
		$datos[1]['dato']=$parametros['obser'];
		$datos[2]['campo']='id_articulos';
		$datos[2]['dato']=$parametros['id'];
		$datos[3]['campo']='fecha';
		$datos[3]['dato']=date('Y-m-d');

		$datos1[0]['campo'] ='estado_trabajo';
		$datos1[0]['dato'] =$parametros['estado'];
		$where[0]['campo']='id_producto';
		$where[0]['dato']=$parametros['id'];
		$this->modelo->update('productos',$datos1,$where);
		return $this->modelo->guardar($datos,'observaciones_trabajo');
	}
	function add_ob_ord($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo']='id_estado';
		$datos[0]['dato']=$parametros['estado'];
		$datos[1]['campo']='observacion';
		$datos[1]['dato']=$parametros['obser'];
		$datos[2]['campo']='id_orden';
		$datos[2]['dato']=$parametros['id'];
		$datos[3]['campo']='fecha';
		$datos[3]['dato']=date('Y-m-d');

		$datos1[0]['campo'] ='estado_trabajo';
		$datos1[0]['dato'] =$parametros['estado'];
		$where[0]['campo']='id_orden';
		$where[0]['dato']=$parametros['id'];
		$this->modelo->update('orden_trabajo',$datos1,$where);
		return $this->modelo->guardar($datos,'observaciones_trabajo');
	}

	function guardar_materia($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo']='id_materia_prima';
		$datos[0]['dato']=$parametros['materia'];
		$datos[1]['campo']='id_producto';
		$datos[1]['dato']=$parametros['id'];
		$datos[2]['campo']='cantidad';
		$datos[2]['dato']=$parametros['cant'];
		return $this->modelo->guardar($datos,'datos_produccion');
	}

	function guardar_materia_orden($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo']='id_materia_prima';
		$datos[0]['dato']=$parametros['materia'];
		$datos[1]['campo']='id_orden';
		$datos[1]['dato']=$parametros['id'];
		$datos[2]['campo']='cantidad';
		$datos[2]['dato']=$parametros['cant'];
		return $this->modelo->guardar($datos,'datos_produccion');
	}
	
	function guardar_materia_fal($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo']='id_materia_prima';
		$datos[0]['dato']=$parametros['materia'];
		$datos[1]['campo']='id_producto';
		$datos[1]['dato']=$parametros['id'];
		$datos[2]['campo']='cantidad';
		$datos[2]['dato']=$parametros['cant'];
		$datos[3]['campo']='observacion';
		$datos[3]['dato']=$parametros['obs'];
		if($parametros['tipo']=='false')
		{
			$datos[4]['campo']='tipo';
			$datos[4]['dato']=1;
		}
		return $this->modelo->guardar($datos,'datos_produccion_fal');
	}

	function guardar_materia_orden_fal($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo']='id_materia_prima';
		$datos[0]['dato']=$parametros['materia'];
		$datos[1]['campo']='id_orden';
		$datos[1]['dato']=$parametros['id'];
		$datos[2]['campo']='cantidad';
		$datos[2]['dato']=$parametros['cant'];
		$datos[3]['campo']='observacion';
		$datos[3]['dato']=$parametros['obs'];
		if($parametros['tipo']=='false')
		{
			$datos[4]['campo']='tipo';
			$datos[4]['dato']=1;
		}
		return $this->modelo->guardar($datos,'datos_produccion_fal');
	}

	function finalizar_orden($parametros)
	{
		if($parametros['joya']!='')
		{
			$datos[0]['campo'] = 'aprobado';
			$datos[0]['dato'] = $parametros['aprobado'];

			$where[0]['campo'] = 'id_producto';
			$where[0]['dato'] = $parametros['joya'];
			if($parametros['aprobado']==1)
			{
				$this->reducir_stock($parametros['joya'],'joya');
			}
			return $this->modelo->update('productos',$datos,$where);
		}else
		{
			$datos[0]['campo'] = 'aprobado';
			$datos[0]['dato'] = $parametros['aprobado'];

			$where[0]['campo'] = 'id_orden';
			$where[0]['dato'] = $parametros['orden'];
			if($parametros['aprobado']==1)
			{
				$this->reducir_stock($parametros['orden'],'orden');
			}
			return $this->modelo->update('orden_trabajo',$datos,$where);

		}

			  

	}

	function estado_orden($parametros)
	{
		// print_r($parametros);die();
		if($parametros['joya']!='')
		{
			$datos = $this->modelo->lista_trabajos($query=false,$parametros['joya']);
		}else{
			$datos = $this->modelo->ordenes_trabajo($query=false,$parametros['orden']);
	    }

	    return $datos[0]['aprobado'];
	}

	function reducir_stock($id,$tipo)
	{
		if($tipo=='joya')
		{
			$joya = $this->modelo->lista_material($id);
			foreach ($joya as $key => $value) {
			    $parametros['id']=$value['id_prima'];
				$prima = $this->articulos->cargar_materia($parametros);
				$existencias = $prima[0]['stock']-$value['cantidad'];

				$datos[0]['campo']='id_producto';
				$datos[0]['dato']=$value['id_prima'];
				$datos[1]['campo']='fecha';
				$datos[1]['dato']=date('Y-m-d');
				$datos[2]['campo']='salida';
				$datos[2]['dato']=$value['cantidad'];
				$datos[3]['campo']='valor_uni';
				$datos[3]['dato']=$prima[0]['precio'];
				$datos[4]['campo']='valor_total';
				$datos[4]['dato']=number_format($prima[0]['precio']*$value['cantidad'],2,'.','');
				$datos[5]['campo']='existencias';
				$datos[5]['dato']=number_format($existencias);
				$datos[6]['campo']='joya';
				$datos[6]['dato']=$id;
				$datos[7]['campo']='materia_prima';
				$datos[7]['dato']='1';

				$this->modelo->guardar($datos,'kardex');

				$datos1[0]['campo']='stock_producto';
				$datos1[0]['dato']=number_format($existencias,2);
				$where[0]['campo']='id_producto';
				$where[0]['dato']=$prima[0]['id'];

				$this->modelo->update('productos',$datos1,$where);
				
			}
			// print_r($joya);die();
			

		 }else
		{
			$orden = $this->modelo->ordenes_trabajo($query=false,$id);
			if($orden[0]['boceto']==1)
			{
				$joya = $this->modelo->lista_material(false,$id);
				foreach ($joya as $key => $value) {
				    $parametros['id']=$value['id_prima'];
					$prima = $this->articulos->cargar_materia($parametros);
					$existencias = $prima[0]['stock']-$value['cantidad'];

					$datos[0]['campo']='id_producto';
					$datos[0]['dato']=$value['id_prima'];
					$datos[1]['campo']='fecha';
					$datos[1]['dato']=date('Y-m-d');
					$datos[2]['campo']='salida';
					$datos[2]['dato']=$value['cantidad'];
					$datos[3]['campo']='valor_uni';
					$datos[3]['dato']=$prima[0]['precio'];
					$datos[4]['campo']='valor_total';
					$datos[4]['dato']=number_format($prima[0]['precio']*$value['cantidad'],2,'.','');
					$datos[5]['campo']='existencias';
					$datos[5]['dato']=number_format($existencias);
					$datos[6]['campo']='orden';
					$datos[6]['dato']=$id;
					$datos[7]['campo']='materia_prima';
					$datos[7]['dato']='1';

					$this->modelo->guardar($datos,'kardex');

					$datos1[0]['campo']='stock_producto';
					$datos1[0]['dato']=number_format($existencias,2);
					$where[0]['campo']='id_producto';
					$where[0]['dato']=$prima[0]['id'];

					$this->modelo->update('productos',$datos1,$where);
					
				}

			}else
			{

				$pro = $this->modelo->lineas_orden($id);
				$adicional = $this->modelo->lista_material(false,$id);
				$default = array();

				if(count($adicional)>0)
				{
					foreach ($adicional as $key => $value3) {
						$default[] = array('cantidad'=>$value3['cantidad'],'material'=>$value3['material'],'id_prima'=>$value3['id_prima']);
					}
			    }
				foreach ($pro as $key => $value) {
					$estado = $this->modelo->lista_material_orden($value['id_producto']);
					foreach ($estado as $key => $value1) {
						$default[] = array('cantidad'=>$value1['cantidad']*$value['cantidad'],'material'=>$value1['material'],'id_prima'=>$value1['id_prima']);
					}
				}

				foreach ($default as $key => $value) {
				    $parametros['id']=$value['id_prima'];
					$prima = $this->articulos->cargar_materia($parametros);
					$existencias = $prima[0]['stock']-$value['cantidad'];

					$datos[0]['campo']='id_producto';
					$datos[0]['dato']=$value['id_prima'];
					$datos[1]['campo']='fecha';
					$datos[1]['dato']=date('Y-m-d');
					$datos[2]['campo']='salida';
					$datos[2]['dato']=$value['cantidad'];
					$datos[3]['campo']='valor_uni';
					$datos[3]['dato']=$prima[0]['precio'];
					$datos[4]['campo']='valor_total';
					$datos[4]['dato']=number_format($prima[0]['precio']*$value['cantidad'],2,'.','');
					$datos[5]['campo']='existencias';
					$datos[5]['dato']=number_format($existencias);
					$datos[6]['campo']='orden';
					$datos[6]['dato']=$id;
					
					$datos[7]['campo']='materia_prima';
					$datos[7]['dato']='1';

					$this->modelo->guardar($datos,'kardex');

					$datos1[0]['campo']='stock_producto';
					$datos1[0]['dato']=number_format($existencias,2);
					$where[0]['campo']='id_producto';
					$where[0]['dato']=$prima[0]['id'];

					$this->modelo->update('productos',$datos1,$where);
					
				}



			}
		}


	}

	function ddl_materia_orden($query,$tipo,$id)
	{
		// print_r($id);die();
		$lista = array();	

		switch ($tipo) {
			case 'p':
				$ma = $this->modelo->lista_material(false,$id);
				foreach ($ma as $key => $value) {
					$lista[] = array('id'=>$value['id_prima'].'_'.$value['cantidad'],'text'=>$value['material']);
				}
				$ma = $this->modelo->lista_material_default(false,$id);
				foreach ($ma as $key => $value) {
					$lista[] = array('id'=>$value['id_prima'].'_'.$value['cantidad'],'text'=>$value['material']);
				}				
				break;
			case 'b':
				$datos = $this->modelo->lista_material($id);
				foreach ($datos as $key => $value) {
					$lista[] = array('id'=>$value['id_prima'].'_'.$value['cantidad'],'text'=>$value['material']);
				}
				break;
			
			default:
				$datos = $this->modelo->lista_material_orden($id);
				foreach ($datos as $key => $value) {
					$lista[] = array('id'=>$value['id_prima'].'_'.$value['cantidad'],'text'=>$value['material']);
				}				
				break;
		}

		// print_r($lista);die();
		return $lista;

	}


	function ddl_materia_fa($parametros){

		$datos = $this->articulos->cargar_materia($parametros);
		$ddl = array();
		foreach ($datos as $key => $value) {
			$ddl[] = array('id'=>$value['id'],'text'=>$value['detalle']);
		}
		
		return $ddl;
	}

	function eliminar_linea_fal($parametros)
	{

		return $this->modelo->eliminar_linea_fal($parametros['id']);
	}

	function existe_fal_dev($parametros)
	{
	   $da = $this->modelo->lista_material_fal($parametros['joya']);
		if($parametros['joya']=='')
		{
			$da = $this->modelo->lista_material_fal(false,$parametros['orden']);
		}
	   if(count($da)>0)
	   {
	   	return 1;
	   }else
	   {
	   	return -1;
	   }
	}

	function aprobar_faltante($parametros)
	{
		$parametros1 = $parametros;
		$regist = $this->modelo->lista_material_fal($parametros['joya'],$orden=false);
		$datos[0]['campo']='joya';
		$datos[0]['dato']=$parametros['joya'];
		if($parametros['joya']=='')
		{
			$regist = $this->modelo->lista_material_fal(false,$parametros['orden']);

			$datos[0]['campo']='orden';
			$datos[0]['dato']=$parametros['orden'];
		}
		foreach ($regist as $key => $value) {			
			    $parametros['id']=$value['id_prima'];
				$prima = $this->articulos->cargar_materia($parametros);
				$existencias = $prima[0]['stock']-$value['cantidad'];

				$datos[1]['campo']='salida';
				$datos[1]['dato']=$value['cantidad'];

		    if($value['id_tipo']==1)
		    {
				$existencias = $prima[0]['stock']+$value['cantidad'];
				$datos[1]['campo']='entrada';
				$datos[1]['dato']=$value['cantidad'];
		    }
				$datos[2]['campo']='id_producto';
				$datos[2]['dato']=$value['id_prima'];
				$datos[3]['campo']='fecha';
				$datos[3]['dato']=date('Y-m-d');
				$datos[4]['campo']='valor_uni';
				$datos[4]['dato']=$prima[0]['precio'];
				$datos[5]['campo']='valor_total';
				$datos[5]['dato']=number_format($prima[0]['precio']*$value['cantidad'],2,'.','');
				$datos[6]['campo']='existencias';
				$datos[6]['dato']=number_format($existencias,2,'.','');
				$datos[7]['campo']='materia_prima';
				$datos[7]['dato']='1';

				//guarda en kardex el movimiento
				$this->modelo->guardar($datos,'kardex');


				//actualizo existencias
			    $datos1[0]['campo']='stock_producto';
				$datos1[0]['dato']=number_format($existencias,2);
				$where[0]['campo']='id_producto';
				$where[0]['dato']=$prima[0]['id'];

				$this->modelo->update('productos',$datos1,$where);

				// die();



                //guarda la materia prima faltante en datos de produccion
				if($parametros1['joya']=='' && $value['id_tipo']==0)
				{
					$parametros = array('materia'=>$value['id_prima'],'id'=>$parametros1['orden'],'cant'=>$value['cantidad'],'aprobado'=>1);
					$this->guardar_materia_orden($parametros);

				}else if ($parametros1['joya']!='' && $value['id_tipo']==0)
				{

					$parametros = array('materia'=>$value['id_prima'],'id'=>$parametros1['joya'],'cant'=>$value['cantidad'],'aprobado'=>1);
					$this->guardar_materia($parametros);
				}

				if ($parametros1['joya']=='' && $value['id_tipo']==1)
				{
					$lin = $this->modelo->lista_material_all(false,$parametros1['orden'],$value['id_prima']);
					$canlis = number_format($lin[0]['cantidad']-$value['cantidad'],2);
					if($canlis>0)
					{
						$datosli[0]['campo']='cantidad';
						$datosli[0]['dato']=$canlis;

						$whereli[0]['campo']='id_orden';
						$whereli[0]['dato']=$parametros1['orden'];
						$whereli[1]['campo']='id_materia_prima';
						$whereli[1]['dato']=$value['id_prima'];

						$this->modelo->update('datos_produccion',$datosli,$whereli);
					}else
					{
						 $this->modelo->eliminar_linea($lin[0]['id']);
					}
				}

				$this->modelo->eliminar_linea_fal($value['id']);
		}
		$this->finalizar_orden_fal($parametros1);

	}

	function finalizar_orden_fal($parametros)
	{
		if($parametros['joya']!='')
		{
			$datos[0]['campo'] = 'aprobado';
			$datos[0]['dato'] = $parametros['aprobado'];

			$where[0]['campo'] = 'id_producto';
			$where[0]['dato'] = $parametros['joya'];
			
			return $this->modelo->update('productos',$datos,$where);
		}else
		{
			$datos[0]['campo'] = 'aprobado';
			$datos[0]['dato'] = $parametros['aprobado'];

			$where[0]['campo'] = 'id_orden';
			$where[0]['dato'] = $parametros['orden'];
			
			return $this->modelo->update('orden_trabajo',$datos,$where);

		}

			  

	}





}

?>