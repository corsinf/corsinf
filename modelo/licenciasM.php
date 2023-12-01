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
	
}
?>