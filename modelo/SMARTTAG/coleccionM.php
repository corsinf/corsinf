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
class coleccionM
{
	private $db;
	function __construct()
	{		
		$this->db = new db();
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

	function lista_coleccion($query=false,$cali=false,$proc=false,$WHERE=false)
	{
		$sql = "SELECT id_cliente as 'id',nombre_cliente as 'nombre',direccion_cliente as 'dir',nivel_cliente as 'nivel',detalle_proceso as 'proceso', color_proceso as 'color',id_usuario as 'usu',contactado_cliente as 'contactado' FROM cliente C
		   LEFT JOIN proceso P ON c.id_proceso = P.id_proceso WHERE 1=1 ";
		   if($query)
		   {
		   	$sql.=" AND  nombre_cliente LIKE '%".$query."%'";
		   }
		   if($cali)
		   {
		   	if($cali==-1)
		   	{
		   		$sql.=" AND nivel_cliente = 0";
		   	}else
		   	{
		   		$sql.=" AND nivel_cliente = ".$cali;
		   	}
		   }
		   if($proc)
		   {
		   	$sql.=" AND C.id_proceso = ".$proc;
		   }
		   $sql.=" ORDER BY contactado_cliente ASC";
		   // print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}
	function coleccion($query=false)
	{
		$sql="SELECT id_coleccion as 'id',detalle_coleccion as 'coleccion',detalle_material as 'material',detalle_categoria as 'tipo joya',fecha_coleccion as 'fecha',C.id_material,C.id_categoria 
		FROM coleccion C 
		INNER JOIN material M ON c.id_material = M.id_material
		INNER JOIN categorias T ON C.id_categoria = T.id_categoria WHERE 1=1 and estado_coleccion = 'A'";
		if($query)
		{
			$sql.=" AND detalle_coleccion LIKE '%".$query."%'";
		}
		$sql.="ORDER BY id_coleccion DESC";
		$datos = $this->db->datos($sql);
		return $datos;


	}

	function coleccion_inactivos($query=false)
	{
		$sql="SELECT id_coleccion as 'id',detalle_coleccion as 'coleccion',detalle_material as 'material',detalle_categoria as 'tipo joya',fecha_coleccion as 'fecha',C.id_material,C.id_categoria 
		FROM coleccion C 
		INNER JOIN material M ON c.id_material = M.id_material
		INNER JOIN categorias T ON C.id_categoria = T.id_categoria WHERE 1=1 and estado_coleccion = 'I'";
		if($query)
		{
			$sql.=" AND detalle_coleccion LIKE '%".$query."%'";
		}
		$sql.="ORDER BY id_coleccion DESC";
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function ficha_coleccion($id)
	{
		$sql=$sql="SELECT id_coleccion as 'id',detalle_coleccion as 'coleccion',detalle_material as 'material',detalle_categoria as 'tipo_joya',fecha_coleccion as 'fecha',C.id_material,C.id_categoria 
		FROM coleccion C 
		INNER JOIN material M ON c.id_material = M.id_material
		INNER JOIN categorias T ON C.id_categoria = T.id_categoria WHERE 1=1 AND id_coleccion = '".$id."'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function delete_coleccion($id)
	{
		$sql = "DELETE FROM  coleccion WHERE id_coleccion='".$id."'";
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