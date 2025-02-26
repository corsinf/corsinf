<?php
@session_start();
if($_SESSION['INICIO']['TIPO_BASE'] == 'MYSQL')
   {
   	if(!class_exists('db'))
   	{
   		require_once(dirname(__DIR__, 2) .'/db/db_mysql.php');
   	}
   }else
   {
   	if(!class_exists('db'))
   	{
   		require_once(dirname(__DIR__, 2) .'/db/db_sql.php'); 
   	}
   }
   if(!class_exists('codigos_globales'))
   {
   	require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
   }
/**
 * 
 */
class headerM
{
	private $db;
	function __construct()
	{		
		$this->db = new db();
	}

	function menu_lateral()
	{
		$sql = "SELECT id_modulo as 'id',nombre_modulo as 'nombre',icono_modulo as 'icono' FROM modulos  WHERE estado = 'A' ORDER BY nombre_modulo ASC";
		$menu = $this->db->datos($sql);
		return $menu;
	}
	function paginas($item)
	{

		$sql = "SELECT id_paginas as 'id',nombre_pagina as 'nombre',link_pagina as 'link',icono_paginas as 'icono' FROM paginas WHERE";
		if($item=='')
		{
			if($_SESSION['INICIO']['TIPO_BASE']=='MYSQL')
			{
				$sql.=" ISNULL(id_modulo) ";
			}
			else
			{
				$sql.=" id_modulo IS NULL";
			}

		}else
		{
			$sql.= " id_modulo = '".$item."'";
		} 
		// print_r($sql);
		$submenu = $this->db->datos($sql);
		return $submenu;

	}
	function datos_empresa($empresa)
	{
		$sql= "SELECT id_empresa as 'id',nombre_empresa as 'nombre',icono_empresa as 'icono',ruc_empresa as 'ruc',direccion_empresa as 'direccion',nombre_comercial as 'comercial',telefono_empresa as 'telefono' FROM empresa";
		$empresa = $this->db->datos($sql);
		return $empresa;
	}
	function accesos($pag,$tipo=false)
	{
		$sql="SELECT * FROM accesos INNER JOIN paginas ON	paginas.id_paginas = accesos.id_paginas
		WHERE 1=1";
		if($tipo)
		{
		  $sql.="AND id_tipo_usuario = '".$tipo."' ";
		}else
		{
			 $sql.="AND id_tipo_usuario = '".$_SESSION['INICIO']['TIPO']."' ";
		}
		$sql.="AND accesos.id_paginas = '".$pag."' AND paginas.estado_pagina = 'A'";
		$empresa = $this->db->existente($sql);
		return $empresa;
	}

	function ci_existente($ci,$tipo)
	{
		if($tipo=='U')
		{
		  $sql="SELECT * FROM usuarios WHERE 1 = 1 AND ci_ruc_usuario = '".$ci."' ";
		}else
		{
			 $sql="SELECT * FROM cliente_proveedor WHERE 1=1 AND ci_ruc =  '".$ci."' ";
		}
		$empresa = $this->db->existente($sql);
		return $empresa;

	}
}
?>