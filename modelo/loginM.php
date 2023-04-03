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

}
?>