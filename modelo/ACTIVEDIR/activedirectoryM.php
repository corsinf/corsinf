<?php 
require_once(dirname(__DIR__,2).'/db/db.php');

/**
 * 
 */
class activeDirM
{
	private $db;
	function __construct()
	{
		$this->db = new db();
	}

	function datos_empresa($id_empresa)
	{
		$sql="SELECT * FROM EMPRESAS WHERE Id_empresa = '".$id_empresa."'";
		return $this->db->datos($sql,$id_empresa);
	}

	function guardar($datos,$tabla)
	{
		// inserta en base de datos master
		$datos = $this->db->inserts($tabla,$datos,1);
		// mandar a actualizar y ejecutar el proceso almacenado
		if($datos==1)
		{
			return 1;
		}else
		{
			return -1;
		}

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
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function buscar_no_concurente_ligado($tipoperfil=false)
	{
		$sql = "SELECT DISTINCT(Tabla),Campo_usuario,Campo_pass,campo_img 
				FROM TABLAS_NOCONCURENTE
				WHERE Id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."'";
				if($tipoperfil)
				{
				 	$sql.=" AND tipo_perfil = '".$tipoperfil."'";
				 }

		$datos = $this->db->datos($sql,1);
		return $datos;
	}
	function bucar_campos_tabla($tabla,$campo)
	{
		$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
				WHERE TABLE_NAME = '".$tabla."'
				AND COLUMN_NAME LIKE '%".$campo."%';";

				// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}


	function ejecutar_sql($sql,$datos=false)
	{
		if($datos)
		{
			return $this->db->datos($sql);
		}else{
			return $this->db->sql_string($sql);
		}
	}

	function buscar_registr_noconcurente($id=false,$id_usu=false,$tabla=false,$tipo=false)
	{
		$sql = "SELECT * FROM TABLAS_NOCONCURENTE WHERE 1=1";
		if($id)
		{
			$sql.= " AND Id_no_concurente = '".$id."'";
		}
		if($id_usu)
		{
			$sql.= " AND Id_usuario = '".$id_usu."'";
		}
		if($tabla)
		{
			$sql.= " AND Tabla = '".$tabla."'";
		}
		if($tipo)
		{
			$sql.= " AND tipo_perfil = '".$tipo."'";
		}

		// print_r($sql);die();
		return $this->db->datos($sql,1);

	}

	function existe_acceso_usuario_empresa($usuario=false)
	{
		$sql = "SELECT * FROM ACCESOS_EMPRESA WHERE Id_Empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."' ";
		if($usuario)
		{
			$sql.=" AND Id_usuario = '".$usuario."'"; 
		}		
		// print_r($sql);die();
		return $this->db->datos($sql,1);
	}
	
}

?>