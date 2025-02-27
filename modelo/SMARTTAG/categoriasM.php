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
class categoriasM
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
		$sql = "SELECT id_categoria,detalle_categoria as 'nombre' FROM categorias WHERE 1 = 1 AND detalle_categoria = '".$nombre."'";
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
		$sql = "DELETE FROM  categorias WHERE id_categoria='".$id."'";
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
	function lista_categoria($query)
	{
		$sql = "SELECT id_categoria as 'id',detalle_categoria as 'nombre' FROM categorias WHERE 1 = 1 AND estado = 'A'";
		if($query)
		{
			$sql.=" AND detalle_categoria LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}
	function lista_categoria_ina($query)
	{
		$sql = "SELECT id_categoria as 'id',detalle_categoria as 'nombre' FROM categorias WHERE 1 = 1 AND estado = 'I'";
		if($query)
		{
			$sql.=" AND detalle_categoria LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}

}
?>