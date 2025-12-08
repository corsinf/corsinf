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
	function update($tabla,$datos,$where)
	{
		$datos = $this->db->update($tabla,$datos,$where,1);
		if($datos==1)
		{
			return 1;
		}else
		{
			return -1;
		}

	}

	function updateEmpresa($tabla,$datos,$where)
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
		$datos = $this->db->sql_string($sql,1);
		return $datos;
	}

	function lista_usuarios($id=false,$query=false,$tipo=false,$ci=false,$email=false)
	{
		$sql="SELECT id_usuarios as 'id',ci_ruc as 'ci',nombres as 'nombre',apellidos as 'apellido',nombres +' '+apellidos as 'nom', direccion as 'direccion',telefono as 'telefono',password as 'pass',email as 'email',DESCRIPCION as 'tipo',foto,link_fb,link_gmail,link_ins,link_tw,link_web,usu=null 
			FROM ACCESOS_EMPRESA UT
			RIGHT JOIN USUARIOS U ON UT.ID_USUARIO = U.id_usuarios 
			LEFT JOIN TIPO_USUARIO T ON UT.ID_TIPO_USUARIO = T.ID_TIPO
			WHERE ID_EMPRESA = '".$_SESSION['INICIO']['ID_EMPRESA']."' ";
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
			$sql.=" AND T.id_tipo='".$tipo."'";
		}

		// print_r($sql);die();

		// la lista de usuarios la busca en la base de datos especifica
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function lista_usuarios_sin_dba($id=false,$query=false,$ci=false,$email=false)
	{
		$sql="SELECT id_usuarios as 'id',ci_ruc as 'ci',nombres as 'nombre',apellidos as 'apellido',nombres +' '+apellidos as 'nom', direccion as 'direccion',telefono as 'telefono',password as 'pass',email as 'email',foto,link_fb,link_gmail,link_ins,link_tw,link_web,usu=null 
			FROM ACCESOS_EMPRESA UT
			RIGHT JOIN USUARIOS U ON UT.ID_USUARIO = U.id_usuarios 
			LEFT JOIN TIPO_USUARIO T ON UT.ID_TIPO_USUARIO = T.ID_TIPO
			WHERE ID_EMPRESA = '".$_SESSION['INICIO']['ID_EMPRESA']."'
			 AND T.id_tipo <>1 ";
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

		$sql.=" GROUP BY id_usuarios,ci_ruc,nombres,apellidos,nombres +' '+apellidos,direccion,telefono,password,email,
foto,link_fb,link_gmail,link_ins,link_tw,link_web";
		
		
		// print_r($sql);die();

		// la lista de usuarios la busca en la base de datos especifica
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function lista_usuarios_sin_tipo($id=false,$query=false,$tipo=false,$ci=false,$email=false)
	{
		$sql="SELECT id_usuarios as 'id',ci_ruc as 'ci',nombres as 'nombre',apellidos as 'apellido',nombres +' '+apellidos as 'nom', direccion as 'direccion',telefono as 'telefono',password as 'pass',email as 'email',foto,link_fb,link_gmail,link_ins,link_tw,link_web,usu=null 
			FROM ACCESOS_EMPRESA UT
			RIGHT JOIN USUARIOS U ON UT.ID_USUARIO = U.id_usuarios 
			LEFT JOIN TIPO_USUARIO T ON UT.ID_TIPO_USUARIO = T.ID_TIPO
			WHERE ID_EMPRESA = '".$_SESSION['INICIO']['ID_EMPRESA']."' ";
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

		$sql.=" GROUP BY  id_usuarios,ci_ruc,nombres,apellidos,nombres +' '+apellidos,direccion,telefono,password,email,foto,link_fb,link_gmail,link_ins,link_tw,link_web";

		// print_r($sql);die();

		// la lista de usuarios la busca en la base de datos especifica
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function usuarios_all($id=false,$query=false,$tipo=false,$ci=false,$email=false)
	{
		$sql="SELECT id_usuarios as 'id',ci_ruc as 'ci',nombres,apellidos as 'ape',nombres +' '+apellidos as 'nom', direccion as 'dir',telefono as 'tel',password as 'pass',email as 'email', T.ID_TIPO as 'idt',DESCRIPCION as 'tipo',foto,link_fb,link_gmail,link_ins,link_tw,link_web FROM USUARIO_TIPO_USUARIO UT
			RIGHT JOIN USUARIOS U ON UT.ID_USUARIO = U.id_usuarios 
			LEFT JOIN TIPO_USUARIO T ON UT.ID_TIPO_USUARIO = T.ID_TIPO
			WHERE 1=1 ";
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

		// la lista de usuarios la busca en la base de datos especifica
		$datos = $this->db->datos($sql,1);
		return $datos;
	}


	function usuarios_all_sin_tipo_usuario($id=false,$query=false,$tipo=false,$ci=false,$email=false)
	{
		$sql="SELECT DISTINCT  id_usuarios as 'id',ci_ruc as 'ci',nombres,apellidos as 'ape',nombres +' '+apellidos as 'nom', direccion as 'dir',telefono as 'tel',password as 'pass',email as 'email',foto,link_fb,link_gmail,link_ins,link_tw,link_web FROM USUARIOS
		WHERE 1=1 ";
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

		// la lista de usuarios la busca en la base de datos especifica
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function usuarios_all_empresa_actual($id=false,$query=false,$tipo=false,$ci=false,$email=false)
	{
		$sql="SELECT * FROM USUARIOS
			 WHERE id_usuarios in (SELECT Id_usuario 
			 						    FROM ACCESOS_EMPRESA 
			 						    WHERE Id_Empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."')";
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

		// la lista de usuarios la busca en la base de datos especifica
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function credenciales_no_concurentes_campos()
	{
		$tipo= $_SESSION['INICIO']['PERFIL'];
		$tabla= $_SESSION['INICIO']['NO_CONCURENTE_TABLA'];
		$campo= $_SESSION['INICIO']['NO_CONCURENTE_TABLA_ID'];
		$empresa = $_SESSION['INICIO']['ID_EMPRESA'];


		$sql = "SELECT Campo_Usuario as 'usu',Campo_pass as 'pass',campo_img as 'foto'
		FROM TABLAS_NOCONCURENTE
		WHERE Tabla = '".$tabla."'
		AND Id_Empresa = '".$empresa."'
		AND tipo_perfil = '".$tipo."'";
		$datos = $this->db->datos($sql,1);
		// print_r($datos);die();
		return $datos;
	}
	function credenciales_no_concurentes_datos($campo_usu,$campo_pass)
	{
		$usuario= $_SESSION['INICIO']['NO_CONCURENTE'];
		$tabla= $_SESSION['INICIO']['NO_CONCURENTE_TABLA'];
		$campo= $_SESSION['INICIO']['NO_CONCURENTE_TABLA_ID'];
		$empresa = $_SESSION['INICIO']['ID_EMPRESA'];

		$sql = "SELECT ".$campo_usu." as usuario ,".$campo_pass." as 'pass'
		FROM ".$tabla."
		WHERE ".$_SESSION['INICIO']['NO_CONCURENTE_TABLA_ID']." = '".$usuario."'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function no_concurente_data()
	{
		$usuario= $_SESSION['INICIO']['NO_CONCURENTE'];
		$tabla= $_SESSION['INICIO']['NO_CONCURENTE_TABLA'];
		$campo= $_SESSION['INICIO']['NO_CONCURENTE_TABLA_ID'];

		 $parametros = array(
		    $usuario,
		    $tabla,
		    $campo,
		  );
		 
		  $sql = "EXEC BuscarDatosNoconcurente @id_usuario = ?, @tabla = ?, @campowhere = ?";

		  $datos = $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros, $master = false);
		  $datos[0]['tabla'] = $tabla;

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
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function perfiles_asignados($id=false,$query=false,$tipo=false)
	{
		$sql = "SELECT  ID,U.nombres+' '+U.apellidos AS 'nom' FROM USUARIO_TIPO_USUARIO UTU
		INNER JOIN USUARIOS U ON UTU.ID_USUARIO = U.id_usuarios
		INNER JOIN TIPO_USUARIO TU ON UTU.ID_TIPO_USUARIO = TU.ID_TIPO
		WHERE ID_TIPO_USUARIO  ='".$tipo."'";
		$datos = $this->db->datos($sql,1);
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
		// print_r($sql);die();
		$datos = $this->db->datos($sql,1);
		return $datos;
	}
	function eliminar_tipo($id)
	{
		$sql = "DELETE FROM ACCESOS_EMPRESA WHERE id_usuario='".$id."' AND Id_Empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."'";
		// print_r($sql);die();
		$datos = $this->db->sql_string_cod_error($sql,1);
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
		$sql.= " AND ID_EMPRESA = '".$_SESSION['INICIO']['ID_EMPRESA']."' ";
		return $this->db->existente($sql,1);
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
		// print_r($sql);die();
		return $this->db->datos($sql,1);
	}

	function existe_acceso_usuario_empresa($usuario=false,$tipo=false)
	{
		$sql = "SELECT * FROM ACCESOS_EMPRESA WHERE Id_Empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."'";
		if($tipo)
		{
			$sql.=" AND Id_Tipo_usuario = '".$tipo."'"; 
		}	
		if($usuario)
		{
			$sql.=" AND Id_usuario = '".$usuario."'"; 
		}		
		// print_r($sql);die();
		return $this->db->datos($sql,1);
	}


	function generar_primera_vez($db_destino,$id_empresa)
	{		
		 $db_origen = EMPRESA_MASTER;
		 $parametros = array($db_origen,
		    				$db_destino,
		    				$id_empresa);
		  $sql = "EXEC CopiarEstructuraAccesos @origen_bd = ?,@destino_bd = ?,@id_empresa = ?";
		  $resp =  $this->db->ejecutar_procesos_almacenados($sql,$parametros,false,$basemaster=1);

		  $usuarios = $this->existe_acceso_usuario_empresa();
		   foreach ($usuarios as $key => $value) {
		  	// print_r($value);die();
		  	$datos[0]['campo'] = 'perfil';
		  	$datos[0]['dato'] = $value['Id_Tipo_usuario'];

		  	$where[0]['campo'] = 'id_usuarios';
		  	$where[0]['dato'] = $value['Id_usuario'];
		  	$p =  $this->db->update('USUARIOS',$datos, $where);
		  	// print_r($p);
		  }

		  return $resp;

	}

	function acceso_usuario_empresa_rol($usuario=false,$tipo=false)
	{
		$sql = "SELECT * FROM ACCESOS_EMPRESA  AE 
		INNER JOIN TIPO_USUARIO TU ON AE.Id_Tipo_usuario = TU.ID_TIPO
		WHERE Id_Empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."'";
		if($tipo)
		{
			$sql.=" AND Id_Tipo_usuario = '".$tipo."'"; 
		}	
		if($usuario)
		{
			$sql.=" AND Id_usuario = '".$usuario."'"; 
		}		
		// print_r($sql);die();
		return $this->db->datos($sql,1);
	}

	function empresa_licencias($id,$modulo=false)
	{
		$sql = "SELECT * FROM LICENCIAS WHERE Id_empresa = '".$id."' AND registrado = 1";
		if($modulo)
		{
			$sql.=" AND Id_Modulo = '".$modulo."'";
		}
		return $this->db->datos($sql,1);
	}

	function usuario_x_modulo_empresa($empresa,$modulo,$usuario=false)
	{
		$sql = "SELECT * FROM USUARIO_X_MODULO WHERE Id_empresa = '".$empresa."' AND id_modulo_siste = '".$modulo."'";
		if($usuario)
		{
			$sql.=" AND id_usuarios = '".$usuario."'";
		}
		return $this->db->datos($sql,1);
	}

}
?>