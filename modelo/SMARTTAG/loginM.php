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
class loginM
{
	private $db;
	function __construct()
	{
		$this->db = new db();
	}

	function existe($email,$pass)
	{
		$sql = "SELECT * FROM usuarios WHERE nick_usuario = '".$email."' AND pass_usuario = '".$pass."' AND estado_usuario ='A'";
		$datos = $this->db->existente($sql);
		return $datos;
	}
	function datos_login($email,$pass)
	{
		$sql = "SELECT nombre_usuario as 'nombres',usuarios.id_tipo_usuario as 'tipo',id_usuario as 'ID',email_usuario as 'email',detalle_tipo_usuario as 'tipo_detalle' FROM usuarios LEFT JOIN tipo_usuario T ON usuarios.id_tipo_usuario = T.id_tipo_usuario WHERE nick_usuario = '".$email."' AND pass_usuario = '".$pass."'";
		$datos = $this->db->datos($sql);
		return $datos;

	}
	function puntos_venta($usuario)
	{	
		$sql = "SELECT id_punto_venta as 'id' FROM acceso_punto_venta WHERE 1 = 1 AND id_usuario='".$usuario."'";
		$datos = $this->db->datos($sql);
		return $datos;	
	}

	function bodegas($id)
	{
		$sql = "SELECT * FROM punto_venta WHERE id_punto_venta = '".$id."'";
		$datos = $this->db->datos($sql);
		return $datos;	
	}

	function bodegas_all()
	{
		$sql = "SELECT id_bodegas  as 'id' FROM bodegas WHERE estado = 'A'";
		$datos = $this->db->datos($sql);
		return $datos;	

	}

}
?>