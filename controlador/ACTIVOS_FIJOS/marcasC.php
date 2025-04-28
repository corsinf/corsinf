<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/marcasM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

/**
 * 
 **/

$controlador = new marcasC();

if (isset($_GET['lista'])) {

	if (isset($_POST['id'])) {
		$parametro['id'] = $_POST['id'];
		$parametro['pag'] = false;
	} else {
		$parametro = $_POST['parametros'] ?? [];
	}
	// print_r($parametro);die();
	echo json_encode($controlador->lista_marcas($parametro['id'] ?? '', $parametro['pag'] ?? ''));
}

if (isset($_GET['buscar'])) {
	echo json_encode($controlador->buscar_marcas($_POST['buscare']));
}

if (isset($_GET['insertar'])) {
	echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
	echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['paginacion'])) {
	echo json_encode($controlador->lista_marcas_pag());
}



class marcasC
{
	private $modelo;
	private $cod_global;

	function __construct()
	{
		$this->modelo = new marcasM();
		$this->cod_global = new codigos_globales();
	}

	function lista_marcas($id, $pag)
	{
		/* $reg = count($this->modelo->lista_marcas_pag());
		if ($reg > 25) {

			// print_r($id.'-'.$pag);die();
			$datos = $this->modelo->lista_marcas($id, $pag);
		} else {
			$datos = $this->modelo->lista_marcas();
		}

		// print_r($datos);die();
		$resultado =  array('datos' => $datos, 'cant' => $reg);
		return $resultado; */

		$datos = $this->modelo->lista_marcas($id);
		return $datos;
	}

	function lista_marcas_pag()
	{
		$datos = $this->modelo->lista_marcas_pag();
		$datos = count($datos);
		// print_r($datos);
		return $datos;
	}

	function buscar_marcas($buscar)
	{
		$datos = $this->modelo->buscar_marcas($buscar);
		return $datos;
	}

	function insertar_editar($parametros)
	{
		$datos[0]['campo'] = 'CODIGO';
		$datos[0]['dato'] = $parametros['cod'];
		$datos[1]['campo'] = 'DESCRIPCION';
		$datos[1]['dato'] = $parametros['des'];

		if ($parametros['id'] == '') {
			if (count($this->modelo->buscar_marcas_codigo($datos[0]['dato'])) == 0) {
				$datos = $this->modelo->insertar($datos);
				$movimiento = 'Insertado nuevo registro en MARCAS (' . $parametros['des'] . ')';
			} else {
				return -2;
			}
		} else {
			$where[0]['campo'] = 'ID_MARCA';
			$where[0]['dato'] = $parametros['id'];
			$movimiento = $this->compara_datos($parametros);
			$datos = $this->modelo->editar($datos, $where);
		}

		if ($movimiento != '' && $datos == 1) {
			// Funcion para FTP relacioado con SAP para futura version
			// $texto = $parametros['cod'] . ';' . $parametros['des'];
			// $this->cod_global->para_ftp('marcas', $texto);
			$this->cod_global->ingresar_movimientos(false, $movimiento, 'ac_marcas');
		}

		return $datos;
	}

	function compara_datos($parametros)
	{
		$text = '';
		$marca = $this->modelo->lista_marcas($parametros['id']);
		if ($marca[0]['CODIGO'] != $parametros['cod']) {
			$text .= ' Se modifico CODIGO en MARCAS de ' . $marca[0]['CODIGO'] . ' a ' . $parametros['cod'];
		}
		if ($marca[0]['DESCRIPCION'] != $parametros['des']) {
			$text .= ' Se modifico DESCRIPCION en MARCAS DE ' . $marca[0]['DESCRIPCION'] . ' a ' . $parametros['des'];
		}

		return $text;
	}

	function eliminar($id)
	{
		$datos[0]['campo'] = 'ID_MARCA';
		$datos[0]['dato'] = $id;
		$datos = $this->modelo->eliminar($datos);
		return $datos;
	}
}
