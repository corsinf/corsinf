<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/familiasM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

/**
 * 
 **/

$controlador = new familiasC();

if (isset($_GET['lista'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_familias($parametros));
}

if (isset($_GET['lista_drop'])) {
	$q = '';
	if (isset($_GET['q'])) {
		$q = $_GET['q'];
	}
	echo json_encode($controlador->lista_familias_drop($q));
}

if (isset($_GET['subfamilia'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_subfamilias($parametros));
}

if (isset($_GET['buscar'])) {
	echo json_encode($controlador->buscar_familias($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
	echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['insertar_sub'])) {
	echo json_encode($controlador->insertar_editar_sub($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
	echo json_encode($controlador->eliminar($_POST['id']));
}



class familiasC
{
	private $modelo;
	private $cod_global;

	function __construct()
	{
		$this->modelo = new familiasM();
		$this->cod_global = new codigos_globales();
	}

	function lista_familias($parametros)
	{
		$datos = $this->modelo->lista_familias(false, $parametros['query']);
		return $datos;
	}

	function lista_familias_drop($q)
	{
		$datos = $this->modelo->lista_familias(false, $q);
		$datos2 = array();
		foreach ($datos as $key => $value) {
			$datos2[] = array('id' => $value['id_familia'], 'text' => $value['detalle_familia']);
		}
		return $datos2;
	}

	function familias($parametros)
	{
		$datos = $this->modelo->lista_familias(false, $parametros['query']);
		return $datos;
	}

	function lista_subfamilias($parametros)
	{
		$datos = $this->modelo->lista_subfamilias($parametros['id'], $parametros['query']);
		return $datos;
	}

	function buscar_familias($buscar)
	{
		$datos = $this->modelo->buscar_familias($buscar);
		return $datos;
	}

	function insertar_editar($parametros)
	{
		$datos[0]['campo'] = 'detalle_familia';
		$datos[0]['dato'] = $parametros['des'];
		// $datos[1]['campo'] = 'DESCRIPCION';
		// $datos[1]['dato']= $parametros['des'];
		if ($parametros['id'] == '') {
			if (count($this->modelo->buscar_familias_codigo($datos[0]['dato'])) == 0) {
				$datos = $this->modelo->insertar($datos);
				$movimiento = 'Insertado nuevo registro en familias (' . $parametros['des'] . ')';
			} else {
				return -2;
			}
		} else {
			$where[0]['campo'] = 'id_familia';
			$where[0]['dato'] = $parametros['id'];
			$movimiento = $this->compara_datos($parametros);
			$datos = $this->modelo->editar($datos, $where);
		}
		if ($movimiento != '' && $datos == 1) {
			$texto = $parametros['id'] . ';' . $parametros['des'];
			$this->cod_global->para_ftp('familias', $texto);
			$this->cod_global->ingresar_movimientos(false, $movimiento, 'familias');
		}
		return $datos;
	}

	function insertar_editar_sub($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo'] = 'detalle_familia';
		$datos[0]['dato'] = $parametros['des'];
		$datos[1]['campo'] = 'familia';
		$datos[1]['dato'] = $parametros['fam'];
		if ($parametros['id'] == '') {
			// if (count($this->modelo->buscar_familias_codigo($datos[0]['dato']))==0) {				
			$datos = $this->modelo->insertar($datos);
			$movimiento = 'Insertado nuevo registro en familias (' . $parametros['des'] . ')';
			// }else
			// {
			// return -2;
			// }
		} else {
			$where[0]['campo'] = 'id_familia';
			$where[0]['dato'] = $parametros['id'];
			$movimiento = $this->compara_datos_sub($parametros);
			$datos = $this->modelo->editar($datos, $where);
		}
		if ($movimiento != '' && $datos == 1) {
			$texto = $parametros['des'];
			$this->cod_global->para_ftp('familias', $texto);
			$this->cod_global->ingresar_movimientos(false, $movimiento, 'familias');
		}
		return $datos;
	}

	function compara_datos_sub($parametros)
	{
		// print_r($parametros);die();
		$text = '';
		$marca = $this->modelo->lista_subfamilias($parametros['id']);
		// print_r($marca);die();
		if ($marca[0]['id_familia'] != $parametros['des']) {
			$text .= ' Se modifico FAMILIA de ' . $marca[0]['id_familia'] . ' a ' . $parametros['des'];
		}
		if ($marca[0]['detalle_familia'] != $parametros['des']) {
			$text .= ' Se modifico DESCRIPCION en GENERO DE ' . $marca[0]['detalle_familia'] . ' a ' . $parametros['des'];
		}

		return $text;
	}

	function compara_datos($parametros)
	{
		// print_r($parametros);die();
		$text = '';
		$marca = $this->modelo->lista_familias($parametros['id']);
		// print_r($marca);die();
		if ($marca[0]['id_familia'] != $parametros['des']) {
			$text .= ' Se modifico FAMILIA de ' . $marca[0]['id_familia'] . ' a ' . $parametros['des'];
		}
		if ($marca[0]['detalle_familia'] != $parametros['des']) {
			$text .= ' Se modifico DESCRIPCION en GENERO DE ' . $marca[0]['detalle_familia'] . ' a ' . $parametros['des'];
		}

		return $text;
	}

	function eliminar($id)
	{
		$datos[0]['campo'] = 'id_familia';
		$datos[0]['dato'] = $id;
		$datos = $this->modelo->eliminar_($datos);
		return $datos;
	}
}
