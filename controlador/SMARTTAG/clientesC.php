<?php
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/punto_ventaM.php');
require_once(dirname(__DIR__, 2) .'/modelo/clientesM.php');
/**
 * 
 */
$controlador = new clientesC();
if(isset($_GET['clientes']))
{
	$parametros = $_POST['parametros'];
	$datos = $controlador->clientes($parametros);
	echo json_encode($datos);
}

if(isset($_GET['clientes_ddl']))
{
	$parametros = array('tipo'=>'','query'=>'');
	if(isset($_GET['q']))
	{
		$parametros['query'] = $_GET['q'];
		// $parametros['tipo'] = 'C'; 
	}
	$datos = $controlador->clientes_ddl($parametros);
	echo json_encode($datos);
}

if(isset($_GET['clientes_inactivo']))
{
	$query = $_POST['query'];
	$datos = $controlador->clientes_inactivos($query);
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

if(isset($_GET['transacciones']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->tablas_transacciones($id));
}

if(isset($_GET['derecha']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->derecha($parametros));
}
if(isset($_GET['izquierda']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->izquierda($parametros));
}
class clientesC
{
	private $modelo;
	private $pagina;
	private $punto_venta;
	function __construct()
	{
		$this->modelo = new clientesM();
		$this->pagina = new codigos_globales();
		$this->punto_venta = new punto_ventaM();
		$this->pagina->registrar_pagina_creada('../vista/cliente.php','Clientes','','estado');

	}

	function clientes($parametros)
	{

		$datos = $this->modelo->clientes($parametros);
		// print_r($datos);die();
		$cabecera = array('Cliente','CI','Email','Telefono','Ciudad','Tipo cliente');
		$ocultar = array('id','direccion');		
		$botones[0] = array('boton'=>'Editar','icono'=>'<i class="fas fa-pen nav-icon"></i>','tipo'=>'primary','id'=>'id');
		$botones[1] = array('boton'=>'Eliminar','icono'=>'<i class="fas fa-trash nav-icon"></i>','tipo'=>'danger','id'=>'id');
		$botones[2] = array('boton'=>'Transacciones','icono'=>'<i class="fas fa-hand-holding-usd nav-icon"></i>','tipo'=>'warning','id'=>'id');
		$enlace[0] = array('posicion'=>1,'link'=>'detalle_cliente.php','get'=>array('nombre'=>'id','valor'=>'id'));
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones=false,false,$ocultar,$foto=false,$posicion=false,$enlace);
		return $tabla;

	}
	function clientes_ddl($parametros)
	{

		$datos = $this->modelo->clientes($parametros);
		$ddl = array();
		foreach ($datos as $key => $value) {
			$ddl[] = array('id'=>$value['id'],'text'=>$value['nombre']);
		}
		
		return $ddl;

	}

	function clientes_inactivos($query)
	{

		$datos = $this->modelo->clientes_inactivos($query);
		// print_r($datos);die();
		$cabecera = array('Cliente','CI','Email','Telefono','Direccion');
		$ocultar = array('id');		
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
		$datos[7]['campo']='id_tipo_cliente';
		$datos[7]['dato']=$parametros['ddl_tipo_cliente'];
		$datos[8]['campo']='ciudad';
		$datos[8]['dato']=$parametros['txt_ciudad'];
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
		$datos = $this->modelo->ficha_clientes($id);
		return $datos[0];
		// print_r($datos);die();
	}
	function delete_usuario($id)
	{
		$datos = $this->modelo->delete_clientes($id);
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

	function derecha($parametros)
	{
		return $this->pagina->derecha($parametros);
	}

	function izquierda($parametros)
	{
		return $this->pagina->izquierda($parametros);
	}


}

?>