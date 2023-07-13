<?php 
include('../db/db.php');
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
		$sql = "SELECT * FROM USUARIOS WHERE email = '".$email."' AND password = '".$pass."'";
		$datos = $this->db->existente($sql);
		return $datos;
	}
	function datos_login($email,$pass)
	{
		$sql = "SELECT nombres,apellidos,email,DESCRIPCION,Ver,editar,eliminar,dba,T.DESCRIPCION as 'tipo',U.id_usuarios as 'id',ID as 'perfil',U.foto FROM USUARIO_TIPO_USUARIO A 
		INNER JOIN USUARIOS U ON A.ID_USUARIO = U.id_usuarios
		INNER JOIN TIPO_USUARIO T ON A.ID_TIPO_USUARIO  = T.ID_TIPO
		LEFT JOIN ACCESOS AC ON A.ID = AC.id_tipo_usu 
		WHERE email = '".$email."' AND password = '".$pass."'
		GROUP BY  nombres,apellidos,email,DESCRIPCION,Ver,editar,eliminar,dba,id_usuarios,ID,foto";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function datos_usuario($id=false,$email=false,$pass=false)
	{
		$sql = "SELECT * FROM USUARIOS WHERE 1=1 ";
		if($id)
		{
			$sql.=" AND id_usuarios = '".$id."'";
		}
		if($email)
		{
		$sql.=" AND email = '".$email."' ";
		}
		if($pass)
		{
			$sql.=" AND password = '".$pass."'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function modulos_sistema()
	{
		if($_SESSION['INICIO']['TIPO']=="DBA")
		{
			$sql = "SELECT id_modulos as 'id',nombre_modulo,link,icono FROM MODULOS_SISTEMA";
		}else
		{
			$sql = "SELECT DISTINCT(modulo_sistema) as 'id',MS.nombre_modulo,MS.icono,MS.link  FROM ACCESOS A
			INNER JOIN PAGINAS P ON A.id_paginas = P.id_paginas
			INNER JOIN MODULOS_SISTEMA MS ON P.modulo_sistema = MS.id_modulos 
			WHERE id_tipo_usu = '".$_SESSION['INICIO']['PERFIL']."'";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}

}
?>