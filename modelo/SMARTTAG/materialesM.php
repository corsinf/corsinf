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
class materialesM
{
	private $db;
	function __construct()
	{		
		$this->db = new db();
	}

	function guardar($datos,$tabla)
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

	function existente($nombre)
	{
		$sql = "SELECT id_material,detalle_material as 'nombre' FROM material WHERE 1 = 1 AND detalle_material = '".$nombre."'";
		return $this->db->existente($sql);
	}

	function update($tabla,$datos,$where)
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
	function delete($id)
	{
		$sql = "DELETE FROM  material WHERE id_material='".$id."'";
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
	function lista_categoria($query,$id=false)
	{
		$sql = "SELECT id_material as 'id',detalle_material as 'nombre' FROM material WHERE 1 = 1 AND estado_material = 'A'";
		if($query)
		{
			$sql.=" AND detalle_material LIKE '%".$query."%'";
		}
		if($id)
		{
			$sql.=" AND id_material = '".$id."'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}
	function lista_categoria_ina($query)
	{
		$sql = "SELECT id_categoria as 'id',detalle_categoria as 'nombre' FROM material WHERE 1 = 1 AND estado_material = 'I'";
		if($query)
		{
			$sql.=" AND detalle_categoria LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}

}
?>