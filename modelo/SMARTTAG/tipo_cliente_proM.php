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
class tipo_cliente_proM
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

	
	function ddl_tipos($query)
	{
		$sql="SELECT id_tipo_cliente_prove as 'id',detalle_tipo_cliente_prove as 'nombre' FROM tipo_cliente_proveedor WHERE tipo_para = 'C' AND estado_tipo_cliente_prove = 'A' ";
		if($query)
		{
			$sql.= " and detalle_tipo_cliente_prove LIKE '%".$query."%'";
		}
		return $this->db->datos($sql);

	}

	function ddl_tipos_P($query)
	{
		$sql="SELECT id_tipo_cliente_prove as 'id',detalle_tipo_cliente_prove as 'nombre' FROM tipo_cliente_proveedor WHERE tipo_para = 'P' AND estado_tipo_cliente_prove = 'A' ";
		if($query)
		{
			$sql.= " and detalle_tipo_cliente_prove LIKE '%".$query."%'";
		}
		return $this->db->datos($sql);

	}


}
?>