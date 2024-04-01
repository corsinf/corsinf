<?php 
if(!class_exists('db'))
{
 include('../db/db.php');
}
/**
 * 
 */
class modulos_paginasM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

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

	function paginas($query=false,$modulo=false)
	{
		if($_SESSION['INICIO']['TIPO']!='DBA')
		{
			$sql = "SELECT P.id_paginas,nombre_pagina,detalle_pagina,estado_pagina,link_pagina,icono_paginas,P.id_modulo,M.nombre_modulo,P.default_pag,subpagina 
			FROM PAGINAS P
			INNER JOIN ACCESOS AC ON P.id_paginas = AC.id_paginas
			LEFT JOIN MODULOS M ON P.id_modulo = M.id_modulo WHERE estado_pagina = 'A'  AND subpagina = 0 AND AC.id_tipo_usu = '".$_SESSION['INICIO']['PERFIL']."' ";
			if($query)
			{
				$sql.=" AND nombre_pagina like '%".$query."%'";
			}
			if($modulo)
			{
				$sql.=" AND M.id_modulo = '".$modulo."'";
			}
			$sql.='AND AC.Ver = 1';
		}else
		{
			$sql = "SELECT P.id_paginas,nombre_pagina,detalle_pagina,estado_pagina,link_pagina,icono_paginas,P.id_modulo,M.nombre_modulo,P.default_pag,subpagina 
			FROM PAGINAS P 
			LEFT JOIN MODULOS M ON P.id_modulo = M.id_modulo
			WHERE 1 = 1  AND subpagina =0 AND estado_pagina = 'A' AND P.id_modulo = '".$modulo."'";

		}

		// print_r($_SESSION['INICIO']);
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function paginas_all($query=false,$modulo=false,$para_dba=false)
	{
		$sql = "SELECT id_paginas,nombre_pagina,detalle_pagina,estado_pagina,link_pagina,icono_paginas,P.id_modulo,M.nombre_modulo,P.default_pag,subpagina,para_dba 
		FROM PAGINAS P
		LEFT JOIN MODULOS M ON P.id_modulo = M.id_modulo WHERE 1 = 1 ";
		if($query)
		{
			$sql.=" AND nombre_pagina like '%".$query."%'";
		}
		if($modulo)
		{
			$sql.=" AND M.id_modulo = '".$modulo."'";
		}
		if($para_dba)
		{
			$sql.=" AND P.para_dba = 1";
		}

		// print_r($sql);die();
		if($_SESSION['INICIO']['TIPO']!='DBA')
		{
			$datos = $this->db->datos($sql);
		}else{			
			$datos = $this->db->datos($sql,1);
		}
		return $datos;

	}

	function eliminar($id)
	{
		$sql = "DELETE FROM MODULOS WHERE id_modulo = '".$id."'";
		return $this->db->sql_string_cod_error($sql,1);

	}
	function eliminar_pagina($id)
	{
		$sql = "DELETE FROM ACCESOS WHERE id_paginas = '".$id."' ";
		$this->db->sql_string($sql,1);
		$sql2= "DELETE FROM PAGINAS WHERE id_paginas = '".$id."'";
		return $this->db->sql_string($sql2,1);

	}

	function accesos($pagina,$perfil)
	{
		$sql = "SELECT * FROM ACCESOS A
		INNER JOIN PAGINAS P ON A.id_paginas = P.id_paginas
		INNER JOIN MODULOS M ON P.id_modulo = M.id_modulo
		WHERE link_pagina ='".$pagina."' AND id_tipo_usu = '".$perfil."'"; 

		if($pagina=='perfil')
		{
			$sql.=" AND modulos_sistema='".$_SESSION['INICIO']['MODULO_SISTEMA']."' ";
		}

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}


	function modulos_sis()
	{
		$sql = "SELECT  * FROM MODULOS_SISTEMA WHERE 1=1";
		$datos = $this->db->datos($sql,1);
		return $datos;
	}
}

?>