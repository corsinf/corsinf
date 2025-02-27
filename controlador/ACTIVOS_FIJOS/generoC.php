<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/generoM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

/**
 * 
 **/

$controlador = new generoC();

if (isset($_GET['lista'])) {
	echo json_encode($controlador->lista_genero($_POST['id']));
}

if (isset($_GET['buscar'])) {
	echo json_encode($controlador->buscar_genero($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
	echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
	echo json_encode($controlador->eliminar($_POST['id']));
}



class generoC
{
	private $modelo;
	private $cod_global;

	function __construct()
	{
		$this->modelo = new generoM();
		$this->cod_global = new codigos_globales();
	}

	function lista_genero($id)
	{
		$datos = $this->modelo->lista_genero($id);
		return $datos;
	}

	function buscar_genero($buscar)
	{
		$datos = $this->modelo->buscar_genero($buscar);
		return $datos;
	}

	function insertar_editar($parametros)
	{
		$datos[0]['campo'] = 'CODIGO';
		$datos[0]['dato'] = strval($parametros['cod']);
		$datos[1]['campo'] = 'DESCRIPCION';
		$datos[1]['dato'] = $parametros['des'];
		if ($parametros['id'] == '') {
			if (count($this->modelo->buscar_genero_CODIGO($datos[0]['dato'])) == 0) {
				$datos = $this->modelo->insertar($datos);
				$movimiento = 'Insertado nuevo registro en GENERO (' . $parametros['des'] . ')';
			} else {
				return -2;
			}
		} else {
			$movimiento = $this->compara_datos($parametros);
			$where[0]['campo'] = 'ID_GENERO';
			$where[0]['dato'] = $parametros['id'];
			$datos = $this->modelo->editar($datos, $where);
		}
		if ($movimiento != '' && $datos == 1) {
			$texto = $parametros['cod'] . ';' . $parametros['des'];
			$this->cod_global->para_ftp('genero', $texto);
			$this->cod_global->ingresar_movimientos(false, $movimiento, 'GENERO');
		}


		return $datos;
	}

	function compara_datos($parametros)
	{
		$text = '';
		$marca = $this->modelo->lista_genero($parametros['id']);
		if ($marca[0]['CODIGO'] != $parametros['cod']) {
			$text .= ' Se modifico CODIGO en GENERO de ' . $marca[0]['CODIGO'] . ' a ' . $parametros['cod'];
		}
		if ($marca[0]['DESCRIPCION'] != $parametros['des']) {
			$text .= ' Se modifico DESCRIPCION en GENERO DE ' . $marca[0]['DESCRIPCION'] . ' a ' . $parametros['des'];
		}

		return $text;
	}

	function eliminar($id)
	{
		$datos[0]['campo'] = 'ID_GENERO';
		$datos[0]['dato'] = $id;
		$datos = $this->modelo->eliminar($datos);
		return $datos;
	}
}
