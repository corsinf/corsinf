<?php
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo//SMARTTAG/categoriasM.php');
/**
 * 
 */
$controlador = new categoriasC();
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
	$datos = $controlador->categorias_inactivas($query);
	echo json_encode($datos);
}
if(isset($_GET['add']))
{
	// print_r($_POST);die();
	$query = $_POST['nombre'];
	$datos = $controlador->categorias_add($query);
	echo json_encode($datos);
}
class categoriasC
{
	private $modelo;
	private $pagina;
	private $punto_venta;
	function __construct()
	{
		$this->modelo = new categoriasM();
		$this->pagina = new codigos_globales();
		$this->pagina->registrar_pagina_creada('../vista/categorias.php','Editar tipo joya','2','estado');

	}
	function categorias_inactivas($query)
	{
		$datos = $this->modelo->lista_categoria_ina($query);
		// print_r($datos);die();
		$cabecera = array('Nombre de categorias');
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
		$datos[0]['campo']='detalle_categoria';
		$datos[0]['dato']=$parametros['nom'];
		$where[0]['campo']='id_categoria';
		$where[0]['dato']=$parametros['id'];
		$resp = $this->modelo->update('categorias',$datos,$where);
		return $resp;

	}
	function estado_usuario($id)
	{
		$where[0]['campo']='id_categoria';
		$where[0]['dato']=$id;
		$datos[0]['campo']='estado';
		$datos[0]['dato']='I';
		$rep = $this->modelo->update('categorias',$datos,$where);
		return $rep;
	}
	function activar_usuario($id)
	{
		$where[0]['campo']='id_categoria';
		$where[0]['dato']=$id;
		$datos[0]['campo']='estado';
		$datos[0]['dato']='A';
		$rep = $this->modelo->update('categorias',$datos,$where);
		return $rep;
	}
	function categorias_add($nombre)
	{
		if($this->modelo->existente($nombre)==false)
		{
		 $datos[0]['campo']='detalle_categoria';
		 $datos[0]['dato']=$nombre;
		 $datos[1]['campo']='estado';
		 $datos[1]['dato']='A';
		 $rep = $this->modelo->guardar($datos,'categorias');
		 return $rep;
		}else
		{
			return -2;
		}
	}


}

?>