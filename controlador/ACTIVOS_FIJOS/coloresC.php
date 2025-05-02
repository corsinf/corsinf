<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/coloresM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

/**
 * 
 **/

$controlador = new coloresC();
if (isset($_GET['lista'])) {
	echo json_encode($controlador->lista_colores($_POST['id'] ?? ''));
}

if (isset($_GET['buscar'])) {
	echo json_encode($controlador->buscar_colores($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
	echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
	echo json_encode($controlador->eliminar($_POST['id']));
}



class coloresC
{
	private $modelo;
	private $cod_global;

	function __construct()
	{
		$this->modelo = new coloresM();
		$this->cod_global = new codigos_globales();
	}

	function lista_colores($id)
	{
		$datos = $this->modelo->lista_colores($id);
		return $datos;
	}

	function buscar_colores($buscar)
	{
		$datos = $this->modelo->buscar_colores($buscar);
		return $datos;
	}

	function insertar_editar($parametros)
	{
		$datos[0]['campo'] = 'CODIGO';
		$datos[0]['dato'] = $parametros['cod'];
		$datos[1]['campo'] = 'DESCRIPCION';
		$datos[1]['dato'] = $parametros['des'];
		if ($parametros['id'] == '') {
			if (count($this->modelo->buscar_colores_codigo($datos[0]['dato'])) == 0) {
				$datos = $this->modelo->insertar($datos);
				$movimiento = 'Insertado nuevo registro en COLORES (' . $parametros['des'] . ')';
			} else {
				return -2;
			}
		} else {
			$where[0]['campo'] = 'ID_COLORES';
			$where[0]['dato'] = $parametros['id'];
			$movimiento = $this->compara_datos($parametros);
			$datos = $this->modelo->editar($datos, $where);
		}

		if ($movimiento != '' && $datos == 1) {
			// Funcion para FTP relacioado con SAP para futura version
			// $texto = $parametros['cod'] . ';' . $parametros['des'];
			// $this->cod_global->para_ftp('colores', $texto);
			$this->cod_global->ingresar_movimientos(false, $movimiento, 'COLORES');
		}

		return $datos;
	}

	function compara_datos($parametros)
	{
		$text = '';
		$marca = $this->modelo->lista_colores($parametros['id']);
		if ($marca[0]['CODIGO'] != $parametros['cod']) {
			$text .= ' Se modifico CODIGO en COLORES de ' . $marca[0]['CODIGO'] . ' a ' . $parametros['cod'];
		}

		if ($marca[0]['DESCRIPCION'] != $parametros['des']) {
			$text .= ' Se modifico DESCRIPCION en COLORES DE ' . $marca[0]['DESCRIPCION'] . ' a ' . $parametros['des'];
		}

		return $text;
	}

	function eliminar($id)
	{
		$datos[0]['campo'] = 'ID_COLORES';
		$datos[0]['dato'] = $id;
		$datos = $this->modelo->eliminar($datos);
		return $datos;
	}
}
