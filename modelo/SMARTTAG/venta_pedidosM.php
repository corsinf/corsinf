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
class venta_pedidosM
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

	
	function eliminar_permisos($id)
	{
		$sql = "DELETE FROM accesos WHERE id_tipo_usuario='".$id."'";
		// print_r($sql);die();
		$datos = $this->db->sql_string($sql);
		return $datos;
	}

	function cargar_todas_pedidos($estado=false)
	{

		$sql="SELECT id_factura as 'id',tipo_factura as 'tipo',C.nombre as 'nombre',numero_factura as 'num',fecha_factura as 'fecha' ,subtotal_factura as 'sub',iva_factura as 'iva',total_factura as 'total',estado_factura as 'estado',punto_venta
		FROM facturas F
		LEFT JOIN cliente_proveedor C ON F.cliente = C.id_cliente_prove
		WHERE tipo_factura  = 'PR'";
		if($estado =='P')
		{
			$sql.=" and estado_factura ='".$estado."' ";
		}else if($estado =='F')
		{
			$sql.=" and estado_factura ='".$estado."' ";
		}

		$sql.=" ORDER BY fecha_factura DESC;";

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

}
?>