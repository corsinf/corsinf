<?php 
include('../db/db.php');
/**
 * 
 */
class licenciasM
{
	private $db;
	function __construct()
	{
		$this->db = new db();
	}


	function add($tabla,$datos,$master=false)
	{
		return $this->db->inserts($tabla,$datos,$master);
	}

	function update($tabla,$datos,$where,$master=false)
	{
		return $this->db->update($tabla,$datos,$where,$master);
	}
	function eliminar_licencia_definitivo($id)
	{
		$sql = "DELETE FROM LICENCIAS WHERE Id_licencias = '".$id."'";
		return $this->db->sql_string($sql,1);
	}
	function lista_licencias($modulo,$registrado=0,$clave=false,$validar_activo=false)
	{
		$sql = "SELECT * 
		FROM LICENCIAS 
		WHERE Id_Modulo = '".$modulo."'
		AND Id_empresa='".$_SESSION['INICIO']['ID_EMPRESA']."'
		AND registrado = ".$registrado;
		if($clave)
		{
			$sql.=" AND Codigo_licencia = '".$clave."'"; 
		}
		if($validar_activo)
		{
			$sql.=" AND GETDATE() BETWEEN Fecha_ini AND Fecha_exp";
		}
		return $this->db->datos($sql,1);
	}

	function lista_modulos()
	{
		$sql = "SELECT * FROM MODULOS_SISTEMA WHERE estado = 'A'";
		return $this->db->datos($sql,1);
	}
	
	function lista_licencias_all($modulo=false,$registrado=0,$clave=false,$validar_activo=false,$empresa=false)
	{
		$sql = "SELECT * 		
		FROM LICENCIAS l
		INNER JOIN EMPRESAS e ON l.Id_empresa = e.Id_empresa
		INNER JOIN MODULOS_SISTEMA m ON l.Id_Modulo = m.id_modulos
		WHERE 1=1 ";
		if($modulo)
		{
			$sql.=" AND Id_Modulo = '".$modulo."'";
		}
		if($empresa)
		{
			$sql.=" AND Id_empresa='".$empresa."'";
		}
		if($registrado)
		{
			$sql.=" AND registrado = ".$registrado;
		}
		if($clave)
		{
			$sql.=" AND Codigo_licencia = '".$clave."'"; 
		}
		if($validar_activo)
		{
			$sql.=" AND GETDATE() BETWEEN Fecha_ini AND Fecha_exp";
		}
		$sql.=" ORDER BY id_licencias DESC ";
		// print_r($sql);die();
		return $this->db->datos($sql,1);
	}
}
?>