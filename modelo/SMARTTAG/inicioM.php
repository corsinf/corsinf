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
class inicioM
{
	private $db;
	function __construct()
	{		
		$this->db = new db();
	}

	// function menu_lateral()
	// {
	// 	$sql = "SELECT id_menu as 'id', nombre_menu as 'nombre',icono_menu as 'icono',link_menu as 'link',padre_menu as 'padre' FROM menu WHERE Estado = 'A' AND padre_menu = '.'";
	// 	$menu = $this->db->datos($sql);
	// 	return $menu;
	// }
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