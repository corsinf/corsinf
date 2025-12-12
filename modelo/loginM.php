<?php 
require_once('../db/db.php');
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

	function buscar_empresas($email,$pass=false,$id=false,$ambiente_empresa=false)
	{
		$sql = "SELECT DISTINCT  E.*,U.password,A.Id_Empresa FROM USUARIOS U
				INNER JOIN ACCESOS_EMPRESA A ON U.id_usuarios = A.Id_usuario
				INNER JOIN EMPRESAS E ON A.Id_Empresa = E.Id_empresa
				WHERE U.email = '".$email."' ";
				if($pass)
				{
					$sql.=" AND U.password = '".$pass."' ";
				}
				if($id)
				{
					$sql.=" AND A.Id_empresa='".$id."'";
				}
				if($ambiente_empresa)
				{
					$sql.="ANd E.ambiente_empresa = '".$ambiente_empresa."'";
				}
				// print_r($sql);die();
		$datos = $this->db->datos($sql,1);
		// print_r($datos);die();
		return $datos;
	}

	function empresa_tabla_noconcurente($id_empresa=false,$tabla=false,$ambiente_empresa = false,$perfil=false)
	{
		$sql = "SELECT Tabla,T.Id_Empresa,Campo_usuario,Campo_pass,tipo_perfil,TU.DESCRIPCION as 'tipo',campo_img
			FROM TABLAS_NOCONCURENTE T
			INNER JOIN TIPO_USUARIO TU ON T.tipo_perfil = TU.ID_TIPO 
			INNER JOIN EMPRESAS EM ON T.Id_Empresa = EM.Id_empresa 
				WHERE 1=1 ";
				if($id_empresa)
				{
					$sql.=" AND T.Id_Empresa='".$id_empresa."'";
				}
				if($tabla)
				{
					$sql.= " AND Tabla = '".$tabla."' ";
				}
				if($perfil)
				{
					$sql.=" AND tipo_perfil = '".$perfil."'";
				}
				if($ambiente_empresa)
				{
					$sql.= " AND  ambiente_empresa = '".$ambiente_empresa."' ";
				}
				$sql.=" GROUP BY Tabla,T.Id_Empresa,Campo_usuario,Campo_pass,tipo_perfil,TU.DESCRIPCION,campo_img";

				// print_r($sql);die();
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function buscar_db_terceros($database,$usuario,$password,$servidor,$puerto,$parametros)
	{
		$item = array();
		$sql = "SELECT * FROM ".$parametros['tabla']." WHERE ".$parametros['Campo_Usuario']." = '".$parametros['email']."' ";
		if(isset($parametros['Campo_Pass']))
		{
		    $sql.=" AND ".$parametros['Campo_Pass']."='".$parametros['pass']."'";
		    // print_r($parametros);
		    // print_r($sql);die();
		}
		// print_r($parametros);
		// print_r($sql);
		$dbconnection = $this->db->conexion_db_terceros($database,$usuario,$password,$servidor,$puerto);
		// print_r($dbconnection);die();
		if($dbconnection!=-1)
		{
		 	$item = $this->db->datos_db_terceros($database,$usuario,$password,$servidor,$puerto,$sql);
		 	// print_r($item);
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

	function datos_login($email=false,$pass=false,$id=false,$tipo=false)
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
		if($tipo)
		{
			$sql.=" AND perfil='".$tipo."'";
		}

		// print_r($_SESSION['INICIO']);
		// print_r($sql);die();

		$datos = $this->db->datos($sql);
		// print_r($datos);die();
		return $datos;
	}

	function datos_login_pass_requiered($email,$pass,$id=false,$tipo=false,$master=false)
	{		
		// print_r($pass);die();
		$sql ="SELECT id_usuarios as 'id',U.*,TU.DESCRIPCION as tipo,A.*  FROM ACCESOS A
		 INNER JOIN TIPO_USUARIO TU ON A.id_tipo_usu = TU.ID_TIPO
		 INNER JOIN ACCESOS_EMPRESA AE ON A.id_tipo_usu= AE.Id_Tipo_usuario
		 INNER JOIN USUARIOS U ON U.id_usuarios = AE.Id_usuario
		WHERE 1=1 AND email = '".$email."'  AND password = '".$pass."' ";
		if($id)
		{
			$sql.=" AND U.id_usuarios = ".$id;
		}
		if($tipo)
		{
			$sql.=" AND id_tipo_usu='".$tipo."'";
		}

		if($master)
		{
			$datos = $this->db->datos($sql,1);
		}else{
			$datos = $this->db->datos($sql);
		}

		// print_r($_SESSION['INICIO']);
		// print_r($sql);die();

		// print_r($datos);die();
		return $datos;
	}


	// function datos_login_pass_requiered_acceso($email,$pass,$empresa,$id=false,$tipo=false)
	// {		
	// 	// print_r($pass);die();
	// 	$sql ="SELECT * FROM ACCESOS_EMPRESA A
	// 	INNER JOIN TIPO_USUARIO TU ON A.Id_Tipo_usuario = TU.ID_TIPO
	// 	INNER JOIN USUARIOS U ON A.Id_usuario = U.id_usuarios
	// 	INNER JOIN ACCESOS AC ON A.Id_Tipo_usuario = AC.id_tipo_usu
	// 	WHERE Id_Empresa = '".$empresa."' AND email = '".$email."'  AND password = '".$pass."' ";
	// 	if($id)
	// 	{
	// 		$sql.=" AND U.id_usuarios = ".$id;
	// 	}
	// 	if($tipo)
	// 	{
	// 		$sql.=" AND Id_Tipo_usuario='".$tipo."'";
	// 	}

	// 	// print_r($_SESSION['INICIO']);
	// 	print_r($sql);die();

	// 	$datos = $this->db->datos($sql);
	// 	// print_r($datos);die();
	// 	return $datos;
	// }


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
			FROM th_personas PE
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
			$sql = "SELECT DISTINCT id_modulos as 'id',nombre_modulo,link,icono,esquema   
			FROM MODULOS_SISTEMA MS
			INNER JOIN LICENCIAS L ON MS.id_modulos = L.Id_Modulo
			WHERE estado ='A'";

			// AND DATEDIFF(DAY, GETDATE(), Fecha_exp) >= 0
		}else
		{
			$sql = "SELECT DISTINCT(MS.id_modulos) as 'id', MS.nombre_modulo,MS.icono,MS.link,MS.esquema
			FROM ACCESOS A 
			INNER JOIN PAGINAS P ON A.id_paginas = P.id_paginas
			INNER JOIN MODULOS M ON P.id_modulo = M.id_modulo
			INNER JOIN MODULOS_SISTEMA MS ON M.modulos_sistema = MS.id_modulos
			INNER JOIN LICENCIAS L ON MS.id_modulos = L.Id_Modulo
			WHERE id_tipo_usu ='".$_SESSION['INICIO']['PERFIL']."' 
			AND L.Id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."'
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

	function modulos_sistema_licencia_activa($modulo)
	{
		if($_SESSION['INICIO']['TIPO']=="DBA")
		{
			$sql = "SELECT id_modulos as 'id',nombre_modulo,link,icono,L.Fecha_ini,L.Fecha_exp   
			FROM MODULOS_SISTEMA MS
			INNER JOIN LICENCIAS L ON MS.id_modulos = L.Id_Modulo
			WHERE estado ='A' 
			AND L.Id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."'
			AND MS.id_modulos = '".$modulo."'
			AND L.registrado = 1
			AND DATEDIFF(DAY, GETDATE(), Fecha_exp) >= 0";
			
		}else
		{
			$sql = "SELECT DISTINCT(MS.id_modulos) as 'id', MS.nombre_modulo,MS.icono,MS.link,L.Fecha_ini,L.Fecha_exp  
			FROM ACCESOS A 
			INNER JOIN PAGINAS P ON A.id_paginas = P.id_paginas
			INNER JOIN MODULOS M ON P.id_modulo = M.id_modulo
			INNER JOIN MODULOS_SISTEMA MS ON M.modulos_sistema = MS.id_modulos
			INNER JOIN LICENCIAS L ON MS.id_modulos = L.Id_Modulo
			WHERE id_tipo_usu ='".$_SESSION['INICIO']['PERFIL']."' 
			AND MS.id_modulos = '".$modulo."'
			AND L.registrado = 1
			AND DATEDIFF(DAY, GETDATE(), Fecha_exp) >= 0
			AND L.Id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."'
			AND subpagina<> 1 
			AND Ver <> 0 
			AND editar <> 0 
			AND eliminar <> 0
			AND MS.estado = 'A' ";
		}
		// print_r($sql);
		// die();
		$datos = $this->db->datos($sql,1);
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

	function update($tabla,$datos,$where,$master=false)
	{
		return $this->db->update($tabla,$datos,$where,$master);
	}
	function paginas($pagina)
	{
		$sql = "SELECT * FROM PAGINAS WHERE link_pagina = '".$pagina."' ";
		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function empresa_licencias($id,$modulo=false)
	{
		$sql = "SELECT * FROM LICENCIAS WHERE Id_empresa = '".$id."' AND registrado = 1 ";
		if($modulo)
		{
			$sql.=" AND Id_Modulo = '".$modulo."'";
		}
		$sql.=" ORDER by Id_Licencias DESC";

		// print_r($sql);die();
		return $this->db->datos($sql,1);
	}

	function empresa_licencias_activas($id,$modulo=false)
	{
		$sql = "SELECT * 
		FROM LICENCIAS 
		WHERE Id_empresa = '".$id."' 
		AND registrado = 1 AND DATEDIFF(DAY, GETDATE(), Fecha_exp) >= 0";
		if($modulo)
		{
			$sql.=" AND Id_Modulo = '".$modulo."'";
		}

		// print_r($sql);die();
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

	function empresa_licencias_regitrado_x_master($id,$licencia,$modulo)
	{
		$sql = "SELECT * 
		FROM LICENCIAS 
		WHERE Id_empresa = '".$id."' 
		AND registrado = 0
		AND Codigo_licencia = '".$licencia."'
		AND Id_Modulo = '".$modulo."'";
		// print_r($sql);die();
		return $this->db->datos($sql,1);
	}

	function lista_empresa($id,$master=false)
	{
		$sql = "SELECT E.*,Id_empresa as 'Id_Empresa' FROM EMPRESAS E WHERE Id_empresa = '".$id."'";
		// print_r($sql);die();
		if($master)
		{
			return $this->db->datos($sql);
		}else
		{
			return $this->db->datos($sql,1);
		}
	}
	function datos_no_concurente($empresa,$tabla,$campoid,$id)
	{
		$database = $empresa[0]['Base_datos'];
		$usuario = $empresa[0]['Usuario_db'];
		$password = $empresa[0]['Password_db'];
		$servidor = $empresa[0]['Ip_host'];
		$puerto = $empresa[0]['Puerto_db'];
		
		$sql= "SELECT * FROM ".$tabla." WHERE ".$campoid.' = '.$id;
		$datos = $this->db->datos_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);
		return $datos;

		// print_r($sql);die();
	}
	function permisos_db_terceros($database, $usuario, $password, $servidor, $puerto)	{
		// print_r($database);die();
		
		$sql ="SELECT id_usuarios as 'id',U.*,TU.DESCRIPCION as tipo,A.*  FROM ACCESOS A
		INNER JOIN TIPO_USUARIO TU ON A.id_tipo_usu = TU.ID_TIPO
		INNER JOIN USUARIOS U ON TU.ID_TIPO = U.perfil
		WHERE 1=1   AND U.id_usuarios = '".$_SESSION['INICIO']['ID_USUARIO']."'";

		// print_r($sql);
		// print_r($database);die();
		return $this->db->datos_db_terceros($database, $usuario, $password, $servidor,$puerto,$sql);

	}

	function tabla_noconcurente($idempresa=false,$tabla=false)
	{
		$sql= "SELECT DISTINCT Tabla,Id_Empresa,Campo_usuario,Campo_pass FROM  TABLAS_NOCONCURENTE
		WHERE 1 = 1";
		if($idempresa)
		{
			$sql.=" AND Id_Empresa='".$idempresa."'";
		}
		if($tabla)
		{
			$sql.=" AND Tabla = '".$tabla."'";
		}

		// print_r($sql);die();
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function buscar_en_tablas_noconcurente_empresaTerceros($empresa,$tabla,$correo,$campoValidar,$validar =false,$campoPass=false)
	{
		$database = $empresa[0]['Base_datos'];
		$usuario = $empresa[0]['Usuario_db'];
		$password = $empresa[0]['Password_db'];
		$servidor = $empresa[0]['Ip_host'];
		$puerto = $empresa[0]['Puerto_db'];


		$sql = "SELECT *  FROM ".$tabla." 
				WHERE ".$campoValidar." = '".$correo."' ";
				if($validar)
				{
					$sql.=" AND ".$campoPass." IS NOT NULL ";
				}
				// print_r($sql);
		$datos = $this->db->datos_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);
		return $datos;
	}

	
	function id_tabla_terceros($tabla,$empresa)
	{
		// print_r($empresa);die();
		$database = $empresa[0]['Base_datos'];
		$usuario = $empresa[0]['Usuario_db'];
		$password = $empresa[0]['Password_db'];
		$servidor = $empresa[0]['Ip_host'];
		$puerto = $empresa[0]['Puerto_db'];

		$parts = explode('.', $tabla);
		if (count($parts) === 2) {
			$schema = $parts[0];
			$table  = $parts[1];
		} else {
			$schema = null; // sin esquema explícito
			$table  = $tabla;
		}

		$table_esc = addslashes($table);
		$where_schema = $schema ? " AND kcu.TABLE_SCHEMA = '" . addslashes($schema) . "'" : "";

		$sql = "SELECT kcu.COLUMN_NAME AS ID
				FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
				JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
				ON kcu.CONSTRAINT_SCHEMA = tc.CONSTRAINT_SCHEMA
				AND kcu.CONSTRAINT_NAME   = tc.CONSTRAINT_NAME
				WHERE tc.CONSTRAINT_TYPE = 'PRIMARY KEY'
				AND kcu.TABLE_NAME = '".$table_esc."'
				".$where_schema." ORDER BY kcu.ORDINAL_POSITION;";

		// $sql="SELECT COLUMN_NAME as 'ID'
		// 		FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
		// 		WHERE OBJECTPROPERTY(OBJECT_ID(CONSTRAINT_NAME), 'IsPrimaryKey') = 1
		// 		AND TABLE_NAME = '".$tabla."'";
		// print_r($sql);die();
		$datos2 = $this->db->datos_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);
		return $datos2;
	}

	function update_no_concurente($empresa,$sql)
	{
		// print_r($empresa);die();
		$database = $empresa[0]['Base_datos'];
		$usuario = $empresa[0]['Usuario_db'];
		$password = $empresa[0]['Password_db'];
		$servidor = $empresa[0]['Ip_host'];
		$puerto = $empresa[0]['Puerto_db'];
		$datos2 = $this->db->sql_string_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);
		return $datos2;
	}

	function roles_x_empresa($id,$usuario)
	{
		$sql = "SELECT * 
				FROM ACCESOS_EMPRESA  A
				INNER JOIN TIPO_USUARIO T ON A.Id_Tipo_usuario = T.ID_TIPO
				INNER JOIN USUARIOS U ON A.Id_usuario = U.id_usuarios
				WHERE Id_Empresa = '".$id."' AND U.email = '".$usuario."' ";
				// print_r($sql);die();
		return  $this->db->datos($sql,1);
	}	

	function ejecutarAuditoria($Base_datos,$Usuario_db,$Password_db,$Ip_host,$Puerto_db)
	{
		$sql5 = "EXEC CrearTriggerAuditoria;";
		$this->db->ejecutar_sp_db_terceros($Base_datos,'sa','Tango456',$Ip_host,$Puerto_db,$sql5,false,false);
	}	

}
?>