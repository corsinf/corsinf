<?php
@session_start();
if(!class_exists('db'))
{
 include('../db/db.php');
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

	function lista_usuarios($id=false,$query=false,$tipo=false,$ci=false,$email=false)
	{
		$sql="SELECT id_usuarios as 'id',ci_ruc as 'ci',nombres,apellidos as 'ape',nombres +' '+apellidos as 'nom', direccion as 'dir',telefono as 'tel',password as 'pass',email as 'email', T.ID_TIPO as 'idt',DESCRIPCION as 'tipo',foto FROM USUARIO_TIPO_USUARIO UT
			RIGHT JOIN USUARIOS U ON UT.ID_USUARIO = U.id_usuarios 
			LEFT JOIN TIPO_USUARIO T ON UT.ID_TIPO_USUARIO = T.ID_TIPO
			WHERE 1 = 1 ";
		if($id)
		{
			$sql.=" AND id_usuarios = '".$id."'";
		}
		if($query)
		{
			$sql.=" AND  nombres +' '+apellidos+' '+ci_ruc LIKE '%".$query."%'";
		}
		if($ci)
		{
			$sql.=" AND ci_ruc = '".$ci."'";
		}
		if($email)
		{
			$sql.=" AND email = '".$email."'";
		}
		if($tipo)
		{
			$sql.=" AND U.id_tipo='".$tipo."'";
		}

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}



	function lista_usuarios_simple($id=false,$query=false,$ci=false,$email=false)
	{
		$sql="SELECT id_usuarios as 'id',ci_ruc as 'ci',nombres,apellidos as 'ape',nombres +' '+apellidos as 'nom', direccion as 'dir',telefono as 'tel',password as 'pass',email as 'email',foto FROM USUARIOS
			WHERE 1 = 1 ";
		if($id)
		{
			$sql.=" AND id_usuarios = '".$id."'";
		}
		if($query)
		{
			$sql.=" AND  nombres +' '+apellidos+' '+ci_ruc LIKE '%".$query."%'";
		}
		if($ci)
		{
			$sql.=" AND ci_ruc = '".$ci."'";
		}
		if($email)
		{
			$sql.=" AND email = '".$email."'";
		}
		

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function perfiles_asignados($id=false,$query=false,$tipo=false)
	{
		$sql = "SELECT  ID,U.nombres+' '+U.apellidos AS 'nom' FROM USUARIO_TIPO_USUARIO UTU
		INNER JOIN USUARIOS U ON UTU.ID_USUARIO = U.id_usuarios
		INNER JOIN TIPO_USUARIO TU ON UTU.ID_TIPO_USUARIO = TU.ID_TIPO
		WHERE ID_TIPO_USUARIO  ='".$tipo."'";
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
		$sql = " DELETE FROM USUARIO_TIPO_USUARIO WHERE ID_USUARIO = '".$id."'; DELETE FROM USUARIOS WHERE id_usuarios='".$id."'";
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

	function existe_usuario_perfil($tipo=false,$usuario=false)
	{
		$sql = "SELECT * FROM USUARIO_TIPO_USUARIO WHERE 1=1 "; 
		if($tipo)
		{
			$sql.=" AND	ID_TIPO_USUARIO  ='".$tipo."' ";
		}
		if($usuario)
		{
			$sql.=" AND ID_USUARIO = '".$usuario."'";
		}
		return $this->db->existente($sql);
	}

	function existe_usuario_perfil_datos($tipo=false,$usuario=false)
	{
		$sql = "SELECT * FROM USUARIO_TIPO_USUARIO WHERE 1=1 "; 
		if($tipo)
		{
			$sql.=" AND	ID_TIPO_USUARIO  ='".$tipo."' ";
		}
		if($usuario)
		{
			$sql.=" AND ID_USUARIO = '".$usuario."'";
		}
		return $this->db->datos($sql);
	}

}
?>