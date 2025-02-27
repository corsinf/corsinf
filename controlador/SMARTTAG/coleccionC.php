<?php
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/punto_ventaM.php');
require_once(dirname(__DIR__, 2) .'/modelo/coleccionM.php');
/**
 * 
 */
$controlador = new coleccionC();
if(isset($_GET['coleccion']))
{
	$query = $_POST['query'];
	$datos = $controlador->coleccion($query);
	echo json_encode($datos);
}
if(isset($_GET['coleccion_inactivo']))
{
	$query = $_POST['query'];
	$datos = $controlador->coleccion_inactivos($query);
	echo json_encode($datos);
}
if(isset($_GET['proveedor']))
{
	$query = $_POST['query'];
	$datos = $controlador->proveedor($query);
	echo json_encode($datos);
}
if(isset($_GET['new_coleccion']))
{
	$parametros = $_POST;
	echo json_encode($controlador->add_coleccion($parametros));
}
if(isset($_GET['ficha_coleccion']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->ficha_coleccion($id));
}
if(isset($_GET['delete_coleccion']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->delete_coleccion($id));
}
if(isset($_GET['cliente_estado']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->estado_usuario($id));
}
if(isset($_GET['cliente_activar']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->activar_usuario($id));
}

if(isset($_GET['transacciones']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->tablas_transacciones($id));
}
class coleccionC
{
	private $modelo;
	private $pagina;
	private $punto_venta;
	function __construct()
	{
		$this->modelo = new coleccionM();
		$this->pagina = new codigos_globales();
		$this->punto_venta = new punto_ventaM();
		$this->pagina->registrar_pagina_creada('../vista/coleccion.php','Coleccion','2','estado');

	}

	function coleccion($query)
	{

		$datos = $this->modelo->coleccion($query);
		// print_r($datos);die();
		$cabecera = array('Coleccion','Material','Tipo joya','Fecha');
		$ocultar = array('id_material','id_categoria','id');		
		$botones[0] = array('boton'=>'Editar','icono'=>'<i class="fas fa-pen nav-icon"></i>','tipo'=>'primary','id'=>'id');
		$botones[1] = array('boton'=>'Eliminar','icono'=>'<i class="fas fa-trash nav-icon"></i>','tipo'=>'danger','id'=>'id');
		// $botones[2] = array('boton'=>'Transacciones','icono'=>'<i class="fas fa-hand-holding-usd nav-icon"></i>','tipo'=>'warning','id'=>'id');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;

	}
	function coleccion_inactivos($query)
	{

		$datos = $this->modelo->coleccion_inactivos($query);
		// print_r($datos);die();
		$cabecera = array('Coleccion','Material','Tipo joya','Fecha');
		$ocultar = array('id_material','id_categoria','id');		
		$botones[0] = array('boton'=>'Activar','icono'=>'<i class="fas fa-check nav-icon"></i>','tipo'=>'success','id'=>'id');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;

	}
	function proveedor($query)
	{

		$datos = $this->modelo->proveedores($query);
		// print_r($datos);die();
		$cabecera = array('Proveedor','CI','Email','Telefono','Direccion');
		$ocultar = array('id');		
		$botones[0] = array('boton'=>'Editar','icono'=>'<i class="fas fa-pen nav-icon"></i>','tipo'=>'primary','id'=>'id');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;

	}
	function  add_coleccion($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo']='detalle_coleccion';
		$datos[0]['dato']=$parametros['txt_nombre_new'];
		$datos[1]['campo']='id_categoria';
		$datos[1]['dato']=$parametros['ddl_categoria'];
		$datos[2]['campo']='id_material';
		$datos[2]['dato']=$parametros['ddl_material'];
		$datos[3]['campo']='fecha_coleccion';
		$datos[3]['dato']=$parametros['txt_fecha'];
		if($parametros['txt_id']=='')
		{
			$rep =  $this->punto_venta->guardar($datos,'coleccion');
		}else
		{
			$where[0]['campo']='id_coleccion';
			$where[0]['dato']=$parametros['txt_id'];
			$rep = $this->modelo->update('coleccion',$datos,$where);
		}
		if($rep!=1)
		{
			return -1;
		}
			return 1;

	}

	function ficha_coleccion($id)
	{
		$datos = $this->modelo->ficha_coleccion($id);
		return $datos[0];
		// print_r($datos);die();
	}
	function delete_coleccion($id)
	{
		$datos = $this->modelo->delete_coleccion($id);
		// print_r($datos);die();
		return $datos;
	}

	function estado_usuario($id)
	{
		$where[0]['campo']='id_cliente_prove';
		$where[0]['dato']=$id;
		$datos[0]['campo']='estado';
		$datos[0]['dato']='I';
		$rep = $this->modelo->update('cliente_proveedor',$datos,$where);
		return $rep;
	}
	function activar_usuario($id)
	{
		$where[0]['campo']='id_cliente_prove';
		$where[0]['dato']=$id;
		$datos[0]['campo']='estado';
		$datos[0]['dato']='A';
		$rep = $this->modelo->update('cliente_proveedor',$datos,$where);
		return $rep;
	}

	function tablas_transacciones($cliente)
	{
		$fa = $this->punto_venta->facturas_x_cliente($cliente);
		$pr = $this->punto_venta->presupuestos_x_cliente($cliente);
		$cabecera = array('Numero factura','subtotal','iva','Total','Fecha','Estado');
		$cabecera2 = array('Numero Cotizacion','subtotal','iva','Total','Fecha','Estado');
		$ocultar = array('id','punto_venta','tipo_factura');		
		$botones[0] = array('boton'=>'Visualizar Factura','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'success','id'=>'id,tipo_factura,estado_factura,punto_venta');
		$botones2[0] = array('boton'=>'Visualizar Cotizacion','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'success','id'=>'id,tipo_factura,estado_factura,punto_venta');
		$tabla = $this->pagina->tabla_generica($fa,$cabecera,$botones,false,$ocultar,$foto=false);
		$tablaP = $this->pagina->tabla_generica($pr,$cabecera2,$botones2,false,$ocultar,$foto=false);
		return array('factura'=>$tabla,'pedido'=>$tablaP);
	}


}

?>