<?php
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/punto_ventaM.php');
require_once(dirname(__DIR__, 2) .'/modelo/transaccionesM.php');
require_once(dirname(__DIR__, 2) .'/lib/Reporte_pdf.php');

/**
 * 
 */
$controlador = new transaccionesC();
if(isset($_GET['transacciones']))
{
	$query = $_POST['parametros'];
	$datos = $controlador->transacciones($query);
	echo json_encode($datos);
}
if(isset($_GET['transacciones_inactivo']))
{
	$query = $_POST['query'];
	$datos = $controlador->transacciones_inactivos($query);
	echo json_encode($datos);
}
if(isset($_GET['proveedor']))
{
	$query = $_POST['query'];
	$datos = $controlador->proveedor($query);
	echo json_encode($datos);
}
if(isset($_GET['new_usuario']))
{
	$parametros = $_POST;
	echo json_encode($controlador->add_usuario($parametros));
}
if(isset($_GET['ficha_usuario']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->ficha_usuario($id));
}
if(isset($_GET['delete_usuario']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->delete_usuario($id));
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

if(isset($_GET['transacciones_']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->tablas_transacciones($id));
}

if(isset($_GET['ddl_tipo_transaccion']))
{
	$id = isset($_GET['q']);
	echo json_encode($controlador->tipo_transaccion($id));
}

if(isset($_GET['ver_transacciones']))
{
	$parametros = array('id'=>$_GET['id'],'tran'=>$_GET['tran']);
	echo json_encode($controlador->ver_transacciones($parametros));
}
class transaccionesC
{
	private $modelo;
	private $pagina;
	private $punto_venta;
	private $pdf;
	function __construct()
	{
		$this->modelo = new transaccionesM();
		$this->pagina = new codigos_globales();
		$this->punto_venta = new punto_ventaM();
		$this->pagina->registrar_pagina_creada('../vista/transacciones.php','Transacciones','2','estado');
		$this->pdf = new Reporte_pdf();

	}

	function transacciones($parametros)
	{

		$datos = $this->modelo->transacciones($parametros);
		// print_r($datos);die();
		$cabecera = array('Documento','Nun documento','Tipo de transaccion','Fecha','Bodega salida','bodega Entrada','Usuario');
		$ocultar = array('tipo_transaccion');		
		 $botones[0] = array('boton'=>'Ver','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'primary','id'=>'num_documento,tipo_transaccion');
		// $botones[1] = array('boton'=>'Eliminar','icono'=>'<i class="fas fa-trash nav-icon"></i>','tipo'=>'danger','id'=>'id');
		// $botones[2] = array('boton'=>'Transacciones','icono'=>'<i class="fas fa-hand-holding-usd nav-icon"></i>','tipo'=>'warning','id'=>'id');
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
	function  add_usuario($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo']='nombre';
		$datos[0]['dato']=$parametros['txt_nombre_new'];
		$datos[1]['campo']='ci_ruc';
		$datos[1]['dato']=$parametros['txt_ci_new'];
		$datos[2]['campo']='direccion';
		$datos[2]['dato']=$parametros['txt_dir'];
		$datos[3]['campo']='telefono';
		$datos[3]['dato']=$parametros['txt_telefono'];
		$datos[4]['campo']='email';
		$datos[4]['dato']=$parametros['txt_emial'];
		$datos[5]['campo']='tipo';
		$datos[5]['dato']='C';
		$datos[6]['campo']='credito';
		$datos[6]['dato']=$parametros['txt_credito'];
		if($parametros['txt_id']=='')
		{
			$rep =  $this->punto_venta->guardar($datos,'cliente_proveedor');
		}else
		{
			$where[0]['campo']='id_cliente_prove';
			$where[0]['dato']=$parametros['txt_id'];
			$rep = $this->modelo->update('cliente_proveedor',$datos,$where);
		}
		if($rep!=1)
		{
			return -1;
		}
			return 1;

	}

	function ficha_usuario($id)
	{
		$datos = $this->modelo->ficha_transacciones($id);
		return $datos[0];
		// print_r($datos);die();
	}
	function delete_usuario($id)
	{
		$datos = $this->modelo->delete_transacciones($id);
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

	function ver_transacciones($parametros)
	{
		$datos = $this->modelo->datos_transacciones($parametros);
		$this->pdf->detalle_de_transaccion($datos);
	}

	function tipo_transaccion($query)
	{
		$datos = $this->modelo->tipo_transaccion($query);
		$resp[0] = array('id'=>'T','text'=>'TODOS');
		foreach ($datos as $key => $value) {
			$resp[] = array('id'=>$value['id'],'text'=>$value['nombre']);
		}
		return $resp;

	}


}

?>