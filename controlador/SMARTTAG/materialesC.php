<?php
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/materialesM.php');
/**
 * 
 */
$controlador = new materialesC();
if(isset($_GET['lista']))
{
	$query = '';
	$datos = $controlador->lista_categoria($query);
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
	$datos = $controlador->materiales_inactivas($query);
	echo json_encode($datos);
}
if(isset($_GET['add']))
{
	// print_r($_POST);die();
	$query = $_POST['nombre'];
	$datos = $controlador->materiales_add($query);
	echo json_encode($datos);
}
if(isset($_GET['material']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->ddl_materiales($query));
}

class materialesC
{
	private $modelo;
	private $pagina;
	private $punto_venta;
	function __construct()
	{
		$this->modelo = new materialesM();
		$this->pagina = new codigos_globales();
		$this->pagina->registrar_pagina_creada('../vista/materiales.php','Editar materiales','2','estado');

	}
	function materiales_inactivas($query)
	{
		$datos = $this->modelo->lista_categoria_ina($query);
		// print_r($datos);die();
		$cabecera = array('Nombre de materiales');
		$ocultar = array('id');		
		$botones[0] = array('boton'=>'Activar','icono'=>'<i class="fas fa-check nav-icon"></i>','tipo'=>'success','id'=>'id');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;

	}

	function lista_categoria($query)
	{
		$datos = $this->modelo->lista_categoria($query);
		$tabla = '<tr><td colspan="2">sin registros...</td></tr>';
		if(count($datos)>0)
		{
			$tabla = '';
			foreach ($datos as $key => $value) {
				$tabla.='<tr>
				            <td>
				              <input type="text" name="txt_nombre_'.$value['id'].'" id="txt_nombre_'.$value['id'].'" class="form-control-sm form-control" value="'.$value['nombre'].'">
				            </td>
				            <td>				             
      			 		       <button class="btn btn-primary btn-sm" type="button" onclick="editar(\''.$value['id'].'\')"><i class="fa fa-save"></i></button>
      			 			   <button class="btn btn-danger btn-sm" type="button" onclick="eliminar(\''.$value['id'].'\')"><i class="fa fa-trash"></i></button>
				            </td>
				        </tr>';
			}
		}
		// print_r($tabla);die();
		return $tabla;

	}

	function eliminar($id)
	{		
	return	$resp = $this->modelo->delete($id);
	}
	function editar($parametros)
	{
		$datos[0]['campo']='detalle_material';
		$datos[0]['dato']=$parametros['nom'];
		$where[0]['campo']='id_material';
		$where[0]['dato']=$parametros['id'];
		$resp = $this->modelo->update('material',$datos,$where);
		return $resp;

	}
	function estado_usuario($id)
	{
		$where[0]['campo']='id_material';
		$where[0]['dato']=$id;
		$datos[0]['campo']='estado_material';
		$datos[0]['dato']='I';
		$rep = $this->modelo->update('material',$datos,$where);
		return $rep;
	}
	function activar_usuario($id)
	{
		$where[0]['campo']='id_material';
		$where[0]['dato']=$id;
		$datos[0]['campo']='estado_material';
		$datos[0]['dato']='A';
		$rep = $this->modelo->update('material',$datos,$where);
		return $rep;
	}
	function materiales_add($nombre)
	{
		if($this->modelo->existente($nombre)==false)
		{
		 $datos[0]['campo']='detalle_material';
		 $datos[0]['dato']=$nombre;
		 $datos[1]['campo']='estado_material';
		 $datos[1]['dato']='A';
		 $rep = $this->modelo->guardar($datos,'material');
		 return $rep;
		}else
		{
			return -2;
		}
	}

	function ddl_materiales($query)
	{
		$datos = $this->modelo->lista_categoria($query);
		$cta = array();
		foreach ($datos as $key => $value) {
			$cta[] = array('id'=>$value['id'],'text'=>utf8_encode($value['nombre']));			
		}
		return $cta;

	}



}

?>