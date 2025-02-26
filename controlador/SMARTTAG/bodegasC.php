<?php
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/bodegasM.php');
/**
 * 
 */
$controlador = new bodegasC();
if(isset($_GET['lista']))
{
	$query = '';
	$datos = $controlador->lista_bodega($query);
	echo json_encode($datos);
}
if(isset($_GET['lista_ddl_bodega']))
{
	$query = isset($_GET['q']);
	$datos = $controlador->ddl_lista_bodegas($query);
	echo json_encode($datos);
}
if(isset($_GET['editar']))
{
	$parametros = $_POST['parametros'];
	$datos = $controlador->editar($parametros);
	echo json_encode($datos);
}
if(isset($_GET['eliminar']))
{
	$query = $_POST['id'];
	$datos = $controlador->eliminar($query);
	echo json_encode($datos);
}
if(isset($_GET['estado']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->estado_usuario($id));
}
if(isset($_GET['activar']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->activar_usuario($id));
}
if(isset($_GET['inactivo']))
{
	$query = $_POST['query'];
	$datos = $controlador->categorias_inactivas($query);
	echo json_encode($datos);
}
if(isset($_GET['produccion']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	$datos = $controlador->lista_bodega_produccion($query);
	echo json_encode($datos);
}
if(isset($_GET['add']))
{
	// print_r($_POST);die();
	$query = $_POST['parametros'];
	$datos = $controlador->categorias_add($query);
	echo json_encode($datos);
}
class bodegasC
{
	private $modelo;
	private $pagina;
	private $punto_venta;
	function __construct()
	{
		$this->modelo = new bodegasM();
		$this->pagina = new codigos_globales();
		$this->pagina->registrar_pagina_creada('../vista/bodegas.php','Editar bodegas','2','estado');

	}
	function categorias_inactivas($query)
	{
		$datos = $this->modelo->lista_bodega_ina($query);
		// print_r($datos);die();
		$cabecera = array('Nombre de categorias');
		$ocultar = array('id');		
		$botones[0] = array('boton'=>'Activar','icono'=>'<i class="fas fa-check nav-icon"></i>','tipo'=>'success','id'=>'id');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;

	}

	function lista_bodega($query)
	{
		$datos = $this->modelo->lista_bodega($query);
		$tabla = '<tr><td colspan="2">sin registros...</td></tr>';
		if(count($datos)>0)
		{
			$tabla = '';
			$no = 'checked=""';
			$si = '';
			foreach ($datos as $key => $value) {
				if($value['produccion']==1)
				{
					$si = 'checked=""';
					$no = '';
				}
				$tabla.='<tr>
				            <td>
				              <input type="text" name="txt_nombre_'.$value['id'].'" id="txt_nombre_'.$value['id'].'" class="form-control-sm form-control" value="'.$value['nombre'].'">
				            </td>
				            <td>
				              <label><input type="radio" name="rbl_produccion_'.$value['id'].'" id="rbl_no" '.$no.' value="0"> NO</label>
				              <label><input type="radio" name="rbl_produccion_'.$value['id'].'" id="rbl_si" '.$si.' value="1" > SI</label>  
				            </td>
				            <td>				             
      			 		       <button class="btn btn-primary btn-sm" type="button" onclick="editar(\''.$value['id'].'\')"><i class="fa fa-save"></i></button>
      			 			   <button class="btn btn-danger btn-sm" type="button" onclick="eliminar(\''.$value['id'].'\')"><i class="fa fa-trash"></i></button>
				            </td>
				        </tr>';
		    $no = 'checked=""';
			$si = '';
			}
		}
		// print_r($tabla);die();
		return $tabla;

	}

	function ddl_lista_bodegas($query)
	{		
		$datos = $this->modelo->lista_bodega($query);
		$rep[0] = array('id'=>'T','text'=>'TODOS');
		foreach ($datos as $key => $value) {
			$rep[] = array('id'=>$value['id'],'text'=>$value['nombre']);
		
		}
		return $rep;

	}

	function eliminar($id)
	{		
	return	$resp = $this->modelo->delete($id);
	}
	function editar($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo']='detalle_bodega';
		$datos[0]['dato']=$parametros['nom'];		
		$datos[1]['campo']='produccion';
		$datos[1]['dato']=$parametros['pro'];

		$where[0]['campo']='id_bodegas';
		$where[0]['dato']=$parametros['id'];
		$resp = $this->modelo->update('bodegas',$datos,$where);
		return $resp;

	}
	function estado_usuario($id)
	{
		$where[0]['campo']='id_bodegas';
		$where[0]['dato']=$id;
		$datos[0]['campo']='estado';
		$datos[0]['dato']='I';
		$rep = $this->modelo->update('bodegas',$datos,$where);
		return $rep;
	}
	function activar_usuario($id)
	{
		$where[0]['campo']='id_bodegas';
		$where[0]['dato']=$id;
		$datos[0]['campo']='estado';
		$datos[0]['dato']='A';
		$rep = $this->modelo->update('bodegas',$datos,$where);
		return $rep;
	}
	function categorias_add($parametros)
	{
		// print_r($parametros);die();
		if($this->modelo->existente($parametros['nombre'])==false)
		{
		 $datos[0]['campo']='detalle_bodega';
		 $datos[0]['dato']=$parametros['nombre'];
		 $datos[1]['campo']='estado';
		 $datos[1]['dato']='A';
		 if(isset($parametros['pro']))
		 {		 
		  $datos[2]['campo']='produccion';
		  $datos[2]['dato']=$parametros['pro'];
		 }
		 $rep = $this->modelo->guardar($datos,'bodegas');
		 return $rep;
		}else
		{
			return -2;
		}
	}

	function lista_bodega_produccion($parametros)
	{
		$datos = $this->modelo->lista_bodega_produccion($parametros);
		// print_r($datos);die();
		$op = array();
		foreach ($datos as $key => $value) {
			$op[] = array('id'=>$value['id'],'text'=>$value['nombre']);
		}
		return $op;

	}


}

?>