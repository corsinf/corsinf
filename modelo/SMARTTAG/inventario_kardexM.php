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
/**
 * 
 */
class inventario_kardexM
{
	private $db;
	function __construct()
	{		
		$this->db = new db();
	}

	function lista_kardex($query,$tipo)
	{
		$sql = "SELECT K.fecha,detalle_producto,entrada,salida,factura,joya,orden,Documento = 
		CASE
		WHEN factura IS NULL AND joya IS NULL AND orden IS NOT NULL
	    THEN 'Orde de trabajo'
		WHEN factura IS NULL  AND orden IS NULL AND joya IS NOT NULL
		THEN 'Trabajo en joya'
	    WHEN joya is NULL AND orden IS NULL AND factura IS NOT NULL
	    THEN 'Factura'
	    WHEN joya is NULL AND orden IS NULL AND factura IS NULL
	    THEN 'INGRESO INICIAL KARDEX'
		END ,existencias_ant,existencias        
		FROM kardex K
		INNER JOIN productos P ON K.id_producto = P.id_producto
		WHERE 1=1";
		if($query)
			{
				$sql.=" AND detalle_producto like '%".$query."%'";
			}
		if($tipo=='true')
		{
			$sql.=" AND K.materia_prima = '1'";
		}else
		{
			$sql.=" AND K.materia_prima = '0'";
		}
		$sql.=" ORDER BY fecha DESC";
		$menu = $this->db->datos($sql);
		return $menu;
	}
	// function sub_menu($item)
	// {

	// 	$sql = "SELECT id_menu as 'id', nombre_menu as 'nombre',icono_menu as 'icono',link_menu as 'link',padre_menu as 'padre' FROM menu WHERE Estado = 'A' AND padre_menu = '".$item."'";
	// 	$submenu = $this->db->datos($sql);
	// 	return $submenu;

	// }
	// function datos_empresa($empresa)
	// {
	// 	$sql= "SELECT id_empresa as 'id',nombre_empresa as 'nombre',icono_empresa as 'icono',ruc_empresa as 'ruc' FROM empresa";
	// 	$empresa = $this->db->datos($sql);
	// 	return $empresa;
	// }
}
?>