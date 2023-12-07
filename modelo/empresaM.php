<?php 
if(!class_exists('db'))
{
	include('../db/db.php');
}
/**
 * 
 */
class empresaM
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
	
	function lista_empresas($query)
	{
		$sql = "SELECT * FROM EMPRESAS
		WHERE 1=1 ";
		if($query)
		{
			$sql.=" AND Razon_social+' '+Ruc like '%".$query."%'"; 
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