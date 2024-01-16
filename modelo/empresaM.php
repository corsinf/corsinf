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

	function datos_empresa($id_empresa)
	{
		$sql="SELECT * FROM EMPRESAS WHERE Id_empresa = '".$id_empresa."'";
		return $this->db->datos($sql,$id_empresa);
	}
	function editar($tabla,$datos,$where)
	{
		 $rest = $this->db->update($tabla,$datos,$where,$_SESSION['INICIO']['ID_EMPRESA'],1);
		return $rest;
		
	}

	function tipo_usuario()
	{
		$sql="SELECT id_tipo_usuario as 'id',tipo_usuario as 'detalle' FROM tipo_usuario WHERE empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."'";
		return $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
	}

}
?>