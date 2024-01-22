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

if(isset($_GET['producto_nuevo']))
{
	$parametros = $_POST;
	echo json_encode($controlador->producto_nuevo($parametros));
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
		$lista = array();
		switch ($parametros['tabla']) {
			case 'Insumos':
			$datos = $this->insumos->buscar_insumos($parametros['query']);
			foreach ($datos as $key => $value) {
					$lista[] = array('id'=>$value['sa_cins_id'] ,'text'=>$value['sa_cins_presentacion'],'data'=>$value);
				}
				break;
			
			default:
				$datos = $this->medicamentos->buscar_medicamentos($parametros['query']);
				foreach ($datos as $key => $value) {
					$lista[] = array('id'=>$value['sa_cmed_id'] ,'text'=>$value['sa_cmed_presentacion'],'data'=>$value);
				}
				break;
		}
		return $lista;
	}

	function producto_nuevo($parametros)
	{
		// print_r($parametros);die();
		$datos = array(array('campo'=>'id_proveedor','dato'=>$parametros['ddl_proveedor']),
					   array('campo'=>'sa_kar_codigo_referencia','dato'=>$parametros['txt_referencia']),
					   array('campo'=>'sa_kar_factura','dato'=>$parametros['txt_factura']),
					   array('campo'=>'sa_kar_fecha','dato'=>$parametros['txt_fecha']),
					   array('campo'=>'sa_kar_id_articulo','dato'=>$parametros['ddl_lista_productos']),
					   array('campo'=>'sa_kar_unidad_medida','dato'=>$parametros['txt_unidad']),
					   array('campo'=>'sa_kar_fecha_elaboracion','dato'=>$parametros['txt_fecha_ela']),		
					   array('campo'=>'sa_kar_fecha_exp','dato'=>$parametros['txt_fecha_exp']),		
					   array('campo'=>'sa_kar_registro_sanitario','dato'=>$parametros['txt_reg_sani']),		
					   array('campo'=>'sa_kar_procedencia','dato'=>$parametros['txt_procedencia']),		
					   array('campo'=>'sa_kar_lote','dato'=>$parametros['txt_lote']),	
					   array('campo'=>'sa_kar_entrada','dato'=>$parametros['txt_canti']),	
					   array('campo'=>'sa_kar_valor_unitario','dato'=>$parametros['txt_precio']),	
					   array('campo'=>'sa_kar_existencias','dato'=>$parametros['txt_existencias']),	
					   array('campo'=>'sa_kar_total_desc','dato'=>$parametros['txt_descto']),	
					   array('campo'=>'sa_kar_subtotal','dato'=>$parametros['txt_subtotal']),	
					   array('campo'=>'sa_kar_total_iva','dato'=>$parametros['txt_iva']),	
					   array('campo'=>'sa_kar_valor_total','dato'=>$parametros['txt_total']),
					   array('campo'=>'sa_kar_serie','dato'=>$parametros['txt_serie']),
					   array('campo'=>'sa_kar_tipo','dato'=>$parametros['ddl_tipo'])					   

					);

		// print_r($datos);die();
		return $this->modelo->guardar('kardex',$datos);
	}


}
?>