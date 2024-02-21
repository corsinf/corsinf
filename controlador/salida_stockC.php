<?php
@session_start();
include('../modelo/salida_stockM.php');
include('../modelo/insumosM.php');
include('../modelo/medicamentosM.php');
include('../db/codigos_globales.php');
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

if (isset($_GET['producto_nuevo'])) {
	$parametros = $_POST;
	echo json_encode($controlador->producto_nuevo_entrada($parametros));
}
if(isset($_GET['lista_kardex']))
{
	echo json_encode($controlador->lista_kardex());
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
		// print_r($parametros);die();
		$lista = array();
		switch ($parametros['tabla']) {
			case 'Insumos':
				$datos = $this->insumos->buscar_insumos($parametros['query']);
				foreach ($datos as $key => $value) {
					$lista[] = array('id' => $value['sa_cins_id'], 'text' => $value['sa_cins_presentacion'], 'data' => $value);
				}
				break;

			default:
				$datos = $this->medicamentos->buscar_medicamentos($parametros['query']);
				foreach ($datos as $key => $value) {
					$lista[] = array('id' => $value['sa_cmed_id'], 'text' => $value['sa_cmed_presentacion'], 'data' => $value);
				}
				break;
		}
		return $lista;
	}
}
