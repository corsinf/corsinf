<?php 
require_once(dirname(__DIR__,2).'/db/db.php');

$controlado = new activeDirC();
if(isset($_GET['grupos']))
{
	echo json_encode($controlado->cargar_grupo());
}

/**
 * 
 */
class activeDirM
{
	private $db;
	function __construct()
	{
		$this->db = new db();
	}

	function datos_empresa($id_empresa)
	{
		$sql="SELECT * FROM EMPRESAS WHERE Id_empresa = '".$id_empresa."'";
		return $this->db->datos($sql,$id_empresa);
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
}

?>