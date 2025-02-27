<?php
@session_start();
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/salida_stockM.php');
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/insumosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/medicamentosM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

/**
 * 
 */

$controlador  = new salida_stockC();

if (isset($_GET['lista_articulos'])) {
	$query = '';
	$tipo = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	if (isset($_GET['tipo'])) {
		$tipo = $_GET['tipo'];
	}
	$parametros = array(
		'query' => $query,
		'tabla' => $tipo,
	);
	echo json_encode($controlador->lista_articulos($parametros));
}

if (isset($_GET['lista_kardex'])) {
	echo json_encode($controlador->lista_kardex());
}

if (isset($_GET['lista_kardex_entrada'])) {
	echo json_encode($controlador->lista_kardex_entrada());
}

if (isset($_GET['lista_kardex_all'])) {
	echo json_encode($controlador->lista_kardex_all());
}

class salida_stockC
{
	private $modelo;
	private $insumos;
	private $medicamentos;
	private $cod_global;
	function __construct()
	{
		$this->modelo = new salida_stockM();
		$this->insumos = new insumosM();
		$this->medicamentos = new medicamentosM();
	}

	function lista_articulos($parametros)
	{
		
		$lista = array();
		switch ($parametros['tabla']) {
			case 'Insumos':
				$datos = $this->insumos->buscar_insumos($parametros['query']);
				foreach ($datos as $key => $value) {
					$lista[] = array('id' => $value['sa_cins_id'], 'text' => ($value['sa_cins_nombre_comercial'] . ' (' . $value['sa_cins_presentacion'] . ')'), 'data' => $value);
				}
				break;

			default:
				$datos = $this->medicamentos->buscar_medicamentos($parametros['query']);
				foreach ($datos as $key => $value) {
					$lista[] = array('id' => $value['sa_cmed_id'], 'text' => ($value['sa_cmed_nombre_comercial'] . ' (' . $value['sa_cmed_presentacion'] . ')'), 'data' => $value);
				}
				break;
		}
		return $lista;
	}

	function lista_kardex()
	{
		$datos = $this->modelo->lista_kardex(false, 1);
		// print_r($datos);die();
		return $datos;
	}

	function lista_kardex_entrada()
	{
		$datos = $this->modelo->lista_kardex(1, false);
		// print_r($datos);die();
		return $datos;
	}

	function lista_kardex_all()
	{
		$datos = $this->modelo->lista_kardex();
		// print_r($datos);die();
		return $datos;
	}
}
