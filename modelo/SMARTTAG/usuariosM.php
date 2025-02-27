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
class usuariosM
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

	function lista_usuarios($id=false,$query=false)
	{
		$sql="SELECT id_usuario as 'id',ci_ruc_usuario as 'ci',nombre_usuario as 'nom', direccion_usuario as 'dir',telefono_usuario as 'tel',T.detalle_tipo_usuario as 'tipo',nick_usuario as 'nick',pass_usuario as 'pass',email_usuario as 'email',estado_usuario as 'estado',U.id_tipo_usuario as 'idt',maestro  FROM usuarios U LEFT JOIN tipo_usuario T ON U.id_tipo_usuario = T.id_tipo_usuario WHERE 1=1";
		if($id)
		{
			$sql.=" AND id_usuario = '".$id."'";
		}
		if($query)
		{
			$sql.=" AND  nombre_usuario+''+ci_ruc_usuario  LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function lista_usuarios_ina($id=false,$query=false)
	{
		$sql="SELECT id_usuario as 'id',ci_ruc_usuario as 'ci',nombre_usuario as 'nom', direccion_usuario as 'dir',telefono_usuario as 'tel',T.detalle_tipo_usuario as 'tipo',nick_usuario as 'nick',pass_usuario as 'pass',email_usuario as 'email',estado_usuario as 'estado',U.id_tipo_usuario as 'idt'  FROM usuarios U LEFT JOIN tipo_usuario T ON U.id_tipo_usuario = T.id_tipo_usuario WHERE 1=1 AND estado_usuario='I'";
		if($id)
		{
			$sql.=" AND id_usuario = '".$id."'";
		}
		if($query)
		{
			$sql.=" AND  nombre_usuario+''+ci_ruc_usuario  LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function eliminar_tipo($id)
	{
		$sql = "DELETE FROM usuarios WHERE id_usuario='".$id."'";
		// print_r($sql);die();
		$datos = $this->db->sql_string_cod_error($sql);
		if($datos==1)
		{
			return 1;
		}else if($datos=='547')
		{
			return -2;
		}else
		{
			return -1;
		}
		return $datos;

	}

}
?>