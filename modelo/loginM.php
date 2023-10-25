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

	function datos_login_no_concurentes($email,$pass)
	{
		$sql = "SELECT PE.PERSON_NO,PE.PERSON_NOM,nombres,apellidos,email,DESCRIPCION,Ver,editar,eliminar,dba,T.DESCRIPCION as 'tipo',U.id_usuarios as 'id',ID as 'perfil',U.foto 
			FROM PERSON_NO PE
			INNER JOIN USUARIO_TIPO_USUARIO UTU ON PE.PERFIL = UTU.ID
			INNER JOIN USUARIOS U ON UTU.ID_USUARIO = U.id_usuarios
			INNER JOIN TIPO_USUARIO T ON UTU.ID_TIPO_USUARIO  = T.ID_TIPO
			LEFT JOIN ACCESOS AC ON UTU.ID = AC.id_tipo_usu 
			WHERE PERSON_CORREO = '".$email."' AND PASS ='".$pass."'";
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
			$sql = "SELECT id_modulos as 'id',nombre_modulo,link,icono FROM MODULOS_SISTEMA WHERE estado ='A'";
		}else
		{
			$sql = "SELECT DISTINCT(MS.id_modulos) as 'id', MS.nombre_modulo,MS.icono,MS.link FROM ACCESOS A 
			INNER JOIN PAGINAS P ON A.id_paginas = P.id_paginas
			INNER JOIN MODULOS M ON P.id_modulo = M.id_modulo
			INNER JOIN MODULOS_SISTEMA MS ON M.modulos_sistema = MS.id_modulos
			WHERE id_tipo_usu ='".$_SESSION['INICIO']['PERFIL']."' AND subpagina<> 1 AND Ver <> 0 AND editar <> 0 AND eliminar <> 0 ";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function add($tabla,$datos)
	{
		return $this->db->inserts($tabla,$datos);
	}
	function paginas($pagina)
	{
		$sql = "SELECT * FROM PAGINAS WHERE link_pagina = '".$pagina."' ";
		// print_r($sql);die();
		return $this->db->datos($sql);
	}

}
?>