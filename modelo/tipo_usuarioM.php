<?php
@session_start();
if(!class_exists('db'))
{
 include('../db/db.php');
}
   if(!class_exists('codigos_globales'))
   {
   	include('../db/codigos_globales.php');
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
		$datos = $this->db->inserts($tabla,$datos,1);
		if($datos==1)
		{
			return 1;
		}else
		{
			return -1;
		}

	}

	function guardarLocal($datos,$tabla)
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
		$datos = $this->db->update($tabla,$datos,$where,1);
		if($datos==1)
		{
			return 1;
		}else
		{
			return -1;
		}

	}
	function updateLocal($tabla,$datos,$where)
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
		$sql = "SELECT ID_TIPO as 'id', DESCRIPCION as 'nombre' FROM TIPO_USUARIO WHERE 1=1 ";
		if($query)
		{
			$sql.=" AND DESCRIPCION LIKE '%".$query."%'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_tipo_usuario_all($query=false,$exacto=false)
	{
		$sql = "SELECT ID_TIPO as 'id', DESCRIPCION as 'nombre' FROM TIPO_USUARIO WHERE 1=1 ";
		if($query)
		{
			if($exacto)
			{
				$sql.=" AND DESCRIPCION = '".$query."'";

			}else{
				$sql.=" AND DESCRIPCION LIKE '%".$query."%'";
			}
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql,1);
		return $datos;
	}


	function lista_tipo_usuario_all_empresa($query=false)
	{
		$sql = "SELECT ID_TIPO,TU.DESCRIPCION 
					FROM TIPO_USUARIO_EMPRESA TUE
					INNER JOIN TIPO_USUARIO TU ON TUE.id_tipo_usuario = TU.ID_TIPO
					WHERE TU.DESCRIPCION  = '".$query."'";
		
		// print_r($sql);die();
		$datos = $this->db->datos($sql,1);
		return $datos;
	}


	function lista_modulos($query=false,$id=false,$modulo_sis=false)
	{
		$sql = "SELECT id_modulo as 'id',nombre_modulo as 'modulo',icono_modulo as 'icono',descripcion_modulo as 'detalle',modulos_sistema FROM modulos WHERE 1=1";
		if($query)
		{
			$sql.=" AND nombre_modulo LIKE '%".$query."%'";
		}
		if($id)
		{
			$sql.=" AND id_modulo = '".$id."'";
		}
		if($modulo_sis)
		{
			$sql.=" AND modulos_sistema = '".$modulo_sis."'";
		}
		$sql.=" ORDER BY nombre_modulo ASC";
		// print_r($sql);die();
		$datos = $this->db->datos($sql,1);
		return $datos ;
	}

	function modulos_sis()
	{
		switch ($_SESSION['INICIO']['TIPO']) {
			case 'DBA':
				$sql = "SELECT  * FROM MODULOS_SISTEMA";
				$datos = $this->db->datos($sql,1);
				break;
			case 'ADMINISTRADOR':
			case 'ADMIN':
			   $sql ="SELECT MS.*
						FROM LICENCIAS L
						INNER JOIN MODULOS_SISTEMA MS ON L.Id_Modulo = MS.id_modulos
						WHERE Id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."' AND L.registrado = 1 AND DATEDIFF(DAY, GETDATE(), Fecha_exp) >= 0 ";
				$datos = $this->db->datos($sql,1);
				break;			

			default:
				$sql = "SELECT  * FROM MODULOS_SISTEMA";
				$datos = $this->db->datos($sql);

				break;
		}
		
		return $datos;
	}

	function modulos_sistema_actual($id)
	{
		$sql = "SELECT  * FROM MODULOS_SISTEMA WHERE id_modulos = '".$id."'";
		return $this->db->datos($sql);
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

	function lista_usuarios_en_tipo($id=false,$usuario=false)
	{
		$sql="SELECT ID,nombres+' '+apellidos as 'nombre' FROM  USUARIO_TIPO_USUARIO UT
		INNER JOIN USUARIOS U ON U.id_usuarios = UT.ID_USUARIO
		WHERE 1=1"; 
		if($id)
		{
		 	$sql.="AND ID_TIPO_USUARIO=  '".$id."'";
		}
		if($usuario)
		{			
			$sql.=" AND ID_USUARIO = '".$usuario."'";
		}

		print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function eliminar_tipo($id)
	{
		$sql = "DELETE FROM TIPO_USUARIO WHERE ID_TIPO='".$id."'";
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

	function paginas($query=false,$modulo=false,$menu=false,$para_dba=false)
	{
		$sql = "SELECT id_paginas,nombre_pagina,detalle_pagina,estado_pagina,M.nombre_modulo,P.default_pag 
		FROM PAGINAS P
		LEFT JOIN MODULOS M ON P.id_modulo = M.id_modulo 
		INNER JOIN MODULOS_SISTEMA MS ON M.modulos_sistema = MS.id_modulos WHERE 1 = 1";
		if($query)
		{
			$sql.=" AND nombre_pagina like '%".$query."%'";
		}
		if($modulo)
		{
			$sql.=" AND M.modulos_sistema ='".$modulo."'";
		}
		if($menu)
		{
			$sql.=" AND M.id_modulo = '".$menu."'";
		}
		switch ($para_dba) {
			case '1':
					$sql.=" AND P.para_dba = ".$para_dba." ";
				break;
			case '2':
					$sql.=" AND P.para_dba = 0 ";
				break;			
		}
	

		// print_r($sql);die();
		$datos = $this->db->datos($sql,1);
		return $datos;

	}

	function existe_acceso($pag,$per,$empresa)
	{
		$sql = "SELECT * 
		FROM ACCESOS A
		WHERE A. id_paginas = '".$pag."' 
		AND id_tipo_usu = '".$per."'
		AND id_empresa = '".$empresa."'"; 

		// print_r($sql);die();
		$datos = $this->db->datos($sql,1);
		return $datos;
	}

	function lista_accesos_asignados($perfil,$modulo_sis=false,$modulo=false,$query=false)
	{
		$sql = "SELECT Ver,editar,eliminar,A.id_paginas as 'pag' 
				FROM ACCESOS A
				INNER JOIN PAGINAS P ON A.id_paginas = P.id_paginas 
				WHERE id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."' AND id_tipo_usu = '".$perfil."'";
				if($modulo_sis)
				{
					$sql.=" AND P.id_modulo = '".$modulo_sis."'";
				}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;


	}


	function lista_accesos_por_perfil($perfil)
	{
		$sql = "SELECT Ver,editar,eliminar,id_paginas as 'pag' FROM ACCESOS A
					INNER JOIN USUARIO_TIPO_USUARIO UT ON A.id_tipo_usu = UT.ID
					WHERE ID_TIPO_USUARIO = '".$perfil."' AND ID_EMPRESA = '".$_SESSION['INICIO']['ID_EMPRESA']."'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;




	}

	function eliminar_usuario_tipo($id)
	{
		$sql = "DELETE FROM  USUARIO_TIPO_USUARIO WHERE ID='".$id."'";
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