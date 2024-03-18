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

	function buscar_empresas($email,$pass,$id=false)
	{
		$sql = "SELECT * 
				FROM ACCESOS_EMPRESA AC
				INNER JOIN USUARIOS US ON AC.Id_usuario = US.id_usuarios
				INNER JOIN EMPRESAS EM ON AC.Id_Empresa = EM.Id_empresa
				WHERE US.email = '".$email."' AND US.password = '".$pass."' AND EM.Estado = 'A'";
				if($id)
				{
					$sql.=" AND id_empresa='".$id."'";
				}
				// print_r($sql);die();
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function empresa_tabla_noconcurente($id_empresa=false)
	{
		$sql = "SELECT Tabla,Id_Empresa,Campo_usuario,Campo_pass,tipo_perfil,TU.DESCRIPCION as 'tipo',campo_img
			FROM TABLAS_NOCONCURENTE T
			INNER JOIN TIPO_USUARIO TU ON T.tipo_perfil = TU.ID_TIPO 
				WHERE 1=1 ";
				if($id_empresa)
				{
					$sql.=" AND Id_Empresa='".$id_empresa."'";
				}
				$sql.="GROUP BY Tabla,Id_Empresa,Campo_usuario,Campo_pass,tipo_perfil,TU.DESCRIPCION,campo_img";

				// print_r($sql);die();
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function buscar_db_terceros($database,$usuario,$password,$servidor,$puerto,$parametros)
	{
		$item = array();
		$sql = "SELECT * FROM ".$parametros['tabla']." WHERE ".$parametros['Campo_Usuario']." = '".$parametros['email']."' AND ".$parametros['Campo_Pass']."='".$parametros['pass']."'";
		if($this->db->conexion_db_terceros($database,$usuario,$password,$servidor,$puerto)!='-1')
		{
		 $item = $this->db->datos_db_terceros($database,$usuario,$password,$servidor,$puerto,$sql);
		}
		return $item;
	}

	function buscar_empresas_no_concurentes($email,$pass,$id=false)
	{
		$sql = "SELECT * 
				FROM ACCESOS_EMPRESA AC
				INNER JOIN USUARIOS US ON AC.Id_usuario = US.id_usuarios
				INNER JOIN EMPRESAS EM ON AC.Id_Empresa = EM.Id_empresa
				WHERE US.email = '".$email."' AND US.password = '".$pass."'";
				if($id)
				{
					$sql.=" AND id_empresa='".$id."'";
				}
				// print_r($sql);die();
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function datos_login($email=false,$pass=false,$id=false)
	{		
		$sql ="SELECT id_usuarios as 'id',U.*,TU.DESCRIPCION as tipo,A.*  FROM ACCESOS A
		 INNER JOIN TIPO_USUARIO TU ON A.id_tipo_usu = TU.ID_TIPO
		 INNER JOIN USUARIOS U ON TU.ID_TIPO = U.perfil
		WHERE 1=1 ";		
		if($email){
			$sql.=" AND email = '".$email."' ";
		}
		if($pass)
		{
			$sql.=" AND password = '".$pass."' ";
		}
		if($id)
		{
			$sql.=" AND U.id_usuarios = ".$id;
		}

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function accesos_dba($email,$pass)
	{

		$sql = "SELECT id_usuarios as 'id',perfil as tipo,U.*,TU.DESCRIPCION  FROM  USUARIOS U
		INNER JOIN TIPO_USUARIO TU ON U.perfil = TU.ID_TIPO 
		WHERE email = '".$email."'  AND password = '".$pass."'";
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
			WHERE id_tipo_usu ='".$_SESSION['INICIO']['PERFIL']."' 
			AND subpagina<> 1 
			AND Ver <> 0 
			AND editar <> 0 
			AND eliminar <> 0
			AND MS.estado = 'A' ";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function modulos_empresa()
	{
		$sql = "SELECT * FROM MODULOS_SISTEMA WHERE estado = 'A'";
		$datos = $this->db->datos($sql,1);
		return $datos;

	}


	function modulos_empresa_search($id)
	{
		$sql = "SELECT * FROM MODULOS_SISTEMA WHERE 1=1";
		if($id)
		{
			$sql.=" AND id_modulos = '".$id."'";
		}
		$datos = $this->db->datos($sql,1);
		return $datos;

	}

	function add($tabla,$datos,$master=false)
	{
		return $this->db->inserts($tabla,$datos,$master);
	}

	function update($tabla,$datos,$where)
	{
		return $this->db->update($tabla,$datos,$where);
	}
	function paginas($pagina)
	{
		$sql = "SELECT * FROM PAGINAS WHERE link_pagina = '".$pagina."' ";
		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function empresa_licencias($id)
	{
		$sql = "SELECT * FROM LICENCIAS WHERE Id_empresa = '".$id."' AND registrado = 1";
		return $this->db->datos($sql,1);
	}

	function empresa_licencias_regitrado($id,$licencia,$modulo)
	{
		$sql = "SELECT * 
		FROM LICENCIAS 
		WHERE Id_empresa = '".$id."' 
		AND registrado = 0
		AND Codigo_licencia = '".$licencia."'
		AND Id_Modulo = '".$modulo."'";
		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function lista_empresa($id,$master=false)
	{
		$sql = "SELECT E.*,Id_empresa as 'Id_Empresa' FROM EMPRESAS E WHERE Id_empresa = '".$id."'";
		if($master)
		{
			return $this->db->datos($sql);
		}else
		{
			return $this->db->datos($sql,1);
		}
	}
	function datos_no_concurente($tabla,$campoid,$id)
	{
		$sql= "SELECT * FROM ".$tabla." WHERE ".$campoid.' = '.$id;
		$datos = $this->db->datos($sql);
		return $datos;
		// print_r($sql);die();
	}
	function permisos_db_terceros($database, $usuario, $password, $servidor, $puerto)	{
		// print_r($database);die();
		
		$sql ="SELECT id_usuarios as 'id',U.*,TU.DESCRIPCION as tipo,A.*  FROM ACCESOS A
		INNER JOIN TIPO_USUARIO TU ON A.id_tipo_usu = TU.ID_TIPO
		INNER JOIN USUARIOS U ON TU.ID_TIPO = U.perfil
		WHERE 1=1   AND U.id_usuarios = '".$_SESSION['INICIO']['ID_USUARIO']."'";

		// print_r($sql);die();
		return $this->db->datos_db_terceros($database, $usuario, $password, $servidor,$puerto,$sql);

	}

	

}
?>