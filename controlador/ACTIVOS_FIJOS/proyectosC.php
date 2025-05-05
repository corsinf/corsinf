<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/proyectosM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');


/**
 * 
 **/

$controlador = new proyectosC();

if (isset($_GET['listar'])) {
	echo json_encode($controlador->lista_proyectos($_POST['id'] ?? ''));
}

if (isset($_GET['buscar'])) {
	echo json_encode($controlador->buscar_proyectos($_POST['buscar']));
}

if (isset($_GET['buscar_contenido'])) {
	echo json_encode($controlador->buscar_proyectos_conte($_POST['id']));
}

if (isset($_GET['insertar'])) {
	echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['insertar_conte'])) {
	echo json_encode($controlador->insertar_editar_conte($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
	echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['eliminar_conte'])) {
	echo json_encode($controlador->eliminar_conte($_POST['id']));
}



class proyectosC
{
	private $modelo;
	private $cod_global;

	function __construct()
	{
		$this->modelo = new proyectosM();
		$this->cod_global = new codigos_globales();
	}

	function lista_proyectos($id)
	{
		$datos = $this->modelo->lista_proyectos($id);
		// print_r($datos);die();
		return $datos;
	}

	function buscar_proyectos($buscar)
	{
		$datos = $this->modelo->buscar_proyecto($buscar);
		return $datos;
	}

	function buscar_proyectos_conte($buscar)
	{
		$datos = $this->modelo->buscar_proyectos_conte($buscar);
		return $datos;
	}

	function insertar_editar($parametros)
	{

		$datos[0]['campo'] = 'programa_financiacion';
		$datos[0]['dato'] = $parametros['fin'];
		$datos[1]['campo'] = 'entidad_cp';
		$datos[1]['dato'] = $parametros['ent'];
		$datos[2]['campo'] = 'denominacion';
		$datos[2]['dato'] = $parametros['den'];
		$datos[3]['campo'] = 'descripcion';
		$datos[3]['dato'] = $parametros['des'];
		$datos[4]['campo'] = 'validez_de';
		$datos[4]['dato'] = $parametros['val'];
		$datos[5]['campo'] = 'validez_a';
		$datos[5]['dato'] = $parametros['vla'];
		$datos[6]['campo'] = 'expiracion';
		$datos[6]['dato'] = $parametros['exp'];

		if ($parametros['id'] == "") {
			if (count($this->modelo->buscar_proyecto_programa($datos[0]['dato'])) == 0) {
				$res = $this->modelo->insertar($datos);
				$movimiento = 'Insertado nuevo registro en PROYECTO (' . $parametros['den'] . ')';
			} else {
				return -2;
			}
		} else {
			$where[0]['campo'] = 'ID_PROYECTO';
			$where[0]['dato'] = $parametros['id'];
			$movimiento = $this->compara_datos($parametros);
			$res = $this->modelo->editar($datos, $where);
		}
		if ($movimiento != '' && $res == 1) {
			// Funcion para FTP relacioado con SAP para futura version
			// $texto = $parametros['fin'] . ';' . $parametros['ent'] . ';' . $parametros['den'] . ';' . $parametros['des'] . ';' . $parametros['val'] . ';' . $parametros['vla'] . ';' . $parametros['exp'];
			// $this->cod_global->para_ftp('proyecto', $texto);

			$this->cod_global->ingresar_movimientos(false, $movimiento, 'PROYECTO');
		}
		return $res;
	}

	function compara_datos($parametros)
	{
		$text = '';
		$marca = $this->modelo->lista_proyectos($parametros['id']);

		$valde = new DateTime($marca[0]['valde']);
		$vala = new DateTime($marca[0]['vala']);
		$exp = new DateTime($marca[0]['exp']);

		if ($marca[0]['pro'] != $parametros['fin']) {
			$text .= ' Se modifico PROGRAMA FINANCIACION en PROYECTO de ' . $marca[0]['pro'] . ' a ' . $parametros['fin'];
		}
		if ($marca[0]['enti'] != $parametros['ent']) {
			$text .= ' Se modifico ENTIDAD  en PROYECTO DE ' . $marca[0]['enti'] . ' a ' . $parametros['ent'];
		}
		if ($marca[0]['deno'] != $parametros['den']) {
			$text .= ' Se modifico DENOMINACION en PROYECTO DE ' . $marca[0]['deno'] . ' a ' . $parametros['den'];
		}
		if ($marca[0]['desc'] != $parametros['des']) {
			$text .= ' Se modifico DESCRIPCION en PROYECTO DE ' . $marca[0]['desc'] . ' a ' . $parametros['des'];
		}
		if ($valde->format('Y-m-d') != $parametros['val']) {
			$text .= ' Se modifico FECHA VALIDEZ DE en PROYECTO DE ' . $valde . ' a ' . $parametros['val'];
		}
		if ($vala->format('Y-m-d') != $parametros['vla']) {
			$text .= ' Se modifico FECHA VALIDEZ A en PROYECTO DE ' .$vala . ' a ' . $parametros['vla'];
		}
		if ($exp->format('Y-m-d') != $parametros['exp']) {
			$text .= ' Se modifico FECHA EXPIRACION en PROYECTO DE ' .$exp . ' a ' . $parametros['exp'];
		}

		return $text;
	}

	function insertar_editar_conte($parametros)
	{
		$datos[0]['campo'] = 'ID_ARTICULO';
		$datos[0]['dato'] = $parametros['pro'];
		$datos[1]['campo'] = 'ID_PROYECTO';
		$datos[1]['dato'] = $parametros['id'];
		$datos = $this->modelo->insertar_conteNIDO($datos);

		return $datos;
	}

	function eliminar($id)
	{
		$datos[0]['campo'] = 'ID_PROYECTO';
		$datos[0]['dato'] = $id;
		$datos = $this->modelo->eliminar($datos);
		return $datos;
	}

	function eliminar_conte($id)
	{
		$datos[0]['campo'] = 'ID_CONTENIDO';
		$datos[0]['dato'] = $id;
		$datos = $this->modelo->eliminar_conte($datos);
		return $datos;
	}
}
