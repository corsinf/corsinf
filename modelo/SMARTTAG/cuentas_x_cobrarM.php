<?php
@session_start();
if($_SESSION['INICIO']['TIPO_BASE'] == 'MYSQL')
   {
   	if(!class_exists('db'))
   	{
   		require_once(dirname(__DIR__, 2) .'/db/db_mysql.php');
   	}
   }else
   {
   	if(!class_exists('db'))
   	{
   		require_once(dirname(__DIR__, 2) .'/db/db_sql.php'); 
   	}
   }
   if(!class_exists('codigos_globales'))
   {
   	require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
   }
/**
 * 
 */
class cuentas_x_cobrarM
{
	private $db;
	private $global;
	function __construct()
	{		
		$this->db = new db();
		$this->global = new codigos_globales();
	}

	
	function guardar($datos,$tabla)
	{
		$datos = $this->db->inserts($tabla,$datos);
		if($datos==1)
		{
			return 1;
		}else
		{
			return -1;
		}

	}
	function update($tabla,$datos,$where)
	{
		$datos = $this->db->update($tabla,$datos,$where);
		if($datos==1)
		{
			return 1;
		}else
		{
			return -1;
		}

	}

	function facturas_finalizadas($query=false)
	{
		$sql = "SELECT id_factura as 'id',numero_factura as 'fac',C.nombre as 'nombre',fecha_factura as 'fecha_fa',subtotal_factura as 'sub',iva_factura as 'iva',descuento_factura as 'dcto',total_factura as 'total',tipo_factura as 'tipo' FROM facturas F
		INNER JOIN cliente_proveedor C ON F.cliente = C.id_cliente_prove WHERE estado_factura = 'F' AND estado_pago = 'XP' ";
		if($query)
		{
			$sql.=" AND F.cliente ='".$query."'";
		}
		$sql.=" ORDER BY fecha_factura";

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}
	function facturas_finalizadas_pagadas($query=false)
	{
		$sql = "SELECT id_factura as 'id',numero_factura as 'fac',C.nombre as 'nombre',fecha_factura as 'fecha_fa',subtotal_factura as 'sub',iva_factura as 'iva',descuento_factura as 'dcto',total_factura as 'total',tipo_factura as 'tipo' FROM facturas F
		INNER JOIN cliente_proveedor C ON F.cliente = C.id_cliente_prove WHERE estado_factura = 'F' AND estado_pago = 'PA' ";
		if($query)
		{
			$sql.=" AND F.cliente ='".$query."'";
		}
		$sql.=" ORDER BY fecha_factura";

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function abonos_factura($id)
	{
		$sql="SELECT id_abonos as 'id',tipo_pago as 'tipo',abono,num_cheqDep as 'num',fecha,monto FROM abonos WHERE factura_id = '".$id."' AND cuota is null ORDER by fecha";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function cuotas_factura($id)
	{
		$sql="SELECT id_abonos,fecha_cuota,cuota FROM abonos WHERE factura_id = '".$id."' AND cuota is not null ORDER by fecha_cuota";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function es_cuota($id)
	{
		$sql="SELECT id_abonos,abono,cuota,fecha_cuota,factura_id FROM abonos WHERE id_abonos = '".$id."' AND fecha_cuota is not null";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function cuota_padre($fac,$fecha_cu)
	{
		$sql = "SELECT * FROM abonos WHERE factura_id = '".$fac."' AND fecha_cuota = '".$fecha_cu."' AND cuota is not null";
		$datos = $this->db->datos($sql);
		return $datos;
	}


	
	function eliminar_abono($id)
	{
		$sql = "DELETE FROM abonos WHERE id_abonos='".$id."'";
		// print_r($sql);die();
		$datos = $this->db->sql_string($sql);
		if($datos==1)
		{
			return 1;
		}else
		{
			return -1;
		}
	}

	function eliminar_cheque_pos($id)
	{
		$sql = "DELETE FROM cheques_pos WHERE cheques_id='".$id."'";
		// print_r($sql);die();
		$datos = $this->db->sql_string($sql);
		if($datos==1)
		{
			return 1;
		}else
		{
			return -1;
		}
	}

	function datos_de_factura($id)
	{
		$sql="SELECT total_factura AS 'total' FROM facturas WHERE id_factura = '".$id."'";
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function forma_pago($query=false)
	{
		$sql="SELECT id_forma_pago as 'id',detalle,comprobante,interes FROM tipo_pago WHERE 1=1";
		if($query)
		{
			$sql.=" AND detalle LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function cheques_pos_fecha_cru($factura)
	{
		$sql = "SELECT * FROM cheques_pos WHERE cheques_id = '".$factura."' AND cheques_fecha <= GETDATE()";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}



}
?>