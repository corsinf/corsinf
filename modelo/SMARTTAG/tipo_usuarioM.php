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
class tipo_usuarioM
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

	function lista_tipo_usuario($query=false)
	{
		$sql = "SELECT id_tipo_usuario as 'id', detalle_tipo_usuario as 'nombre' FROM tipo_usuario WHERE 1=1 ";
		if($query)
		{
			$sql.=" detalle_tipo_usuario LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}


	function lista_modulos($query=false)
	{
		$sql = "SELECT id_modulo as 'id',nombre_modulo as 'modulo',icono_modulo as 'icono' FROM modulos WHERE 1=1 ";
		if($query)
		{
			$sql.=" AND nombre_modulo LIKE '%".$query."%'";
		}
		$sql.=" ORDER BY nombre_modulo ASC";
		$datos = $this->db->datos($sql);
		return $datos ;

	}

	function lista_paginas($query =false,$modulo=false,$idpag=false)
	{
		$sql = "SELECT id_paginas as 'id',nombre_pagina as 'pagina',detalle_pagina as 'detalle',icono_paginas as 'icono' FROM paginas WHERE estado_pagina = 'A' ";
		if($query)
		{
			$sql.=" AND nombre_pagina LIKE '%".$query."%'";
		}
		if($modulo)
		{
			$sql.=" AND id_modulo ='".$modulo."'";
		}
		if($idpag)
		{
			$sql.=" AND id_paginas ='".$idpag."'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_paginas_default($query =false,$modulo=false,$idpag=false)
	{
		$sql = "SELECT id_paginas as 'id',nombre_pagina as 'pagina',detalle_pagina as 'detalle',icono_paginas as 'icono' FROM paginas WHERE estado_pagina = 'A' AND paginas.[default] = '1' ";
		if($query)
		{
			$sql.=" AND nombre_pagina LIKE '%".$query."%'";
		}
		if($modulo)
		{
			$sql.=" AND id_modulo ='".$modulo."'";
		}
		if($idpag)
		{
			$sql.=" AND id_paginas ='".$idpag."'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_paginas_sin_modulo($query =false,$modulo=false,$idpag=false)
	{
		$sql = "SELECT id_paginas as 'id',nombre_pagina as 'pagina',detalle_pagina as 'detalle',icono_paginas as 'icono' FROM paginas WHERE estado_pagina = 'A' AND paginas.[default] <> '1' AND id_modulo is NULL ";
		if($query)
		{
			$sql.=" AND nombre_pagina LIKE '%".$query."%'";
		}
		if($modulo)
		{
			$sql.=" AND id_modulo ='".$modulo."'";
		}
		if($idpag)
		{
			$sql.=" AND id_paginas ='".$idpag."'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function eliminar_permisos($id)
	{
		$sql = "DELETE FROM accesos WHERE id_tipo_usuario ='".$id."' AND id_modulos IS NULL";
		// print_r($sql);die();
		$datos = $this->db->sql_string($sql);
		return $datos;
	}

	function datos_asiento_k()
	{
		$sql = "SELECT * FROM ASIENTO_K WHERE id_usuario='".$_SESSION['INICIO']['ID']."' ";
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function lista_usuarios_en_tipo($id)
	{
		$sql="SELECT nombre_usuario as 'nombre',email_usuario as 'email', nick_usuario as 'usuario',pass_usuario as 'pass' FROM tipo_usuario LEFT JOIN usuarios ON tipo_usuario.id_tipo_usuario = usuarios.id_tipo_usuario WHERE tipo_usuario.id_tipo_usuario = '".$id."'";
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function eliminar_tipo($id)
	{
		$sql = "DELETE FROM tipo_usuario WHERE id_tipo_usuario='".$id."'";
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

	function modulos_habilitados($tipo)
	{
		$sql ="SELECT id_modulos FROM accesos WHERE id_tipo_usuario = '".$tipo."' AND id_modulos IS NOT NULL";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function eliminar_all_modulos($tipo)
	{
		$sql = "DELETE FROM accesos WHERE id_tipo_usuario='".$tipo."' AND id_modulos IS NOT NULL AND id_paginas IS NULL";
		$datos = $this->db->sql_string($sql);
		return $datos;
	}


}
?>