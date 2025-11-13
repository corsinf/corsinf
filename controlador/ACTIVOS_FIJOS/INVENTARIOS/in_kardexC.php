<?php 
date_default_timezone_set('America/Guayaquil');
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/INVENTARIO/in_kardexM.php');


$controlador = new in_kardexC();

if (isset($_GET['Listatabla'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Listatabla($parametros));
}

if (isset($_GET['Insert'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->insert($parametros));
}


/**
 * 
 */
class in_kardexC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new in_kardexM();
	}

	function Listatabla($parametros)
	{
		$lista = $this->modelo->listarJoin($parametros['desde'],$parametros['hasta']);
		return $lista;
	}

	function insert($parametros)
	{
		// print_r($parametros);die();
		$entrada = 0;
		$salida = 0;
		if(isset($parametros['entrada'])){$entrada = $parametros['entrada'];}
		if(isset($parametros['salida'])){$entrada = $parametros['salida'];}
		$data = array(
				array('campo'=>'in_kar_codigo_referencia', 'datos'=>$parametros['referencia']),
		        array('campo'=>'in_kar_fecha', 'datos'=>$parametros['fecha']),
		        array('campo'=>'in_kar_entrada', 'datos'=>$entrada),
		        array('campo'=>'in_kar_salida', 'datos'=>$salida),
		        array('campo'=>'in_kar_valor_unitario', 'datos'=>$parametros['pvp']),
		        array('campo'=>'in_kar_valor_total', 'datos'=>$parametros['total']),
		        array('campo'=>'in_kar_existencias', 'datos'=>$parametros['existencias']),
		        array('campo'=>'id_proveedor', 'datos'=>$parametros['proveedor']),
		        array('campo'=>'in_kar_orden_no', 'datos'=>$parametros['orden']),
		        array('campo'=>'in_kar_total_iva', 'datos'=>$parametros['iva']),
		        array('campo'=>'in_kar_tipo', 'datos'=>$parametros['tipo']),
		        array('campo'=>'in_kar_factura', 'datos'=>$parametros['factura']),
		        array('campo'=>'in_kar_serie', 'datos'=>$parametros['serie']),
		        array('campo'=>'in_kar_fecha_exp', 'datos'=>$parametros['fecha_expiracion']),
		        array('campo'=>'in_kar_procedencia', 'datos'=>$parametros['procedencia']),
		        array('campo'=>'in_kar_lote', 'datos'=>$parametros['lote']),
		        array('campo'=>'in_kar_fecha_elaboracion', 'datos'=>$parametros['fecha_ela']),
		        array('campo'=>'in_kar_registro_sanitario', 'datos'=>$parametros['registro_sa']),
		        array('campo'=>'in_kar_unidad_medida', 'datos'=>$parametros['unidad']),
		        array('campo'=>'in_kar_total_desc', 'datos'=>$parametros['descuento']),
		        array('campo'=>'in_kar_subtotal', 'datos'=>$parametros['subtotal']),
		        array('campo'=>'in_kar_id_articulo', 'datos'=>$parametros['articulo']),
		        array('campo'=>'id_usuarios','datos'=>$parametros['usuario'])
	    	);

			return $this->insert($data);
		}

}


?>