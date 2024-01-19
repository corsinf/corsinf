<?php 
@session_start();
include('../modelo/ingreso_stockM.php');
include('../modelo/insumosM.php');
include('../modelo/medicamentosM.php');
include('../db/codigos_globales.php');
/**
 * 
 */
$controlador  = new ingreso_stockC();

if(isset($_GET['lista_articulos']))
{
	$query = '';
	$tipo = '';
	if(isset($_GET['q'])){$query=$_GET['q'];}
	if(isset($_GET['tipo'])){$tipo=$_GET['tipo'];}
	$parametros = array(
		'query'=>$query,
		'tabla'=>$tipo,
	);
	echo json_encode($controlador->lista_articulos($parametros));
}

class ingreso_stockC 
{
	private $modelo;
	private $insumos;
	private $medicamentos;
	private $cod_global;
	function __construct()
	{
		$this->modelo = new ingreso_stockM();	
		$this->insumos = new insumosM();	
		$this->medicamentos = new medicamentosM();	
		$this->cod_global = new codigos_globales();
	}

	function lista_articulos($parametros)
	{
		switch ($parametros['tabla']) {
			case 'Insumos':
			$datos = $this->insumos->buscar_insumos($parametros['query']);
				break;
			
			default:
				$datos = $this->medicamentos->buscar_medicamentos($parametros['query']);
				break;
		}

		$lista = array();
		foreach ($datos as $key => $value) {
			$lista[] = array('id'=>$value['sa_cmed_id'] ,'text'=>$value['sa_cmed_concentracion'])
		}
	}


}
?>