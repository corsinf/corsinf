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
class admin_punto_ventaM
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

	function existente($nombre,$num)
	{
		$resp = false;

		$sql = "SELECT * FROM punto_venta WHERE 1 = 1 AND estado = 'A' AND nombre_punto = '".$nombre."'";
		$nom= $this->db->existente($sql);

		$sql = "SELECT * FROM punto_venta WHERE 1 = 1 AND estado = 'A' AND num_punto_venta = '".$num."'";
		$nu= $this->db->existente($sql);

		if($nom == true || $nu == true)
		{
			return true;
		}else
		{
			return $resp;
		}
	}

	function existente_acceso($usuario,$punto)
	{

		$sql = "SELECT * FROM acceso_punto_venta WHERE 1 = 1 AND id_usuario='".$usuario."' AND id_punto_venta ='".$punto."'";
		$nu= $this->db->existente($sql);
		return $nu;
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
		$sql = "DELETE FROM  bodegas WHERE id_bodegas='".$id."'";
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
	function lista_puntos($query)
	{
		$sql = "SELECT id_punto_venta as 'id',nombre_punto as 'nombre',num_punto_venta as 'num' FROM punto_venta WHERE 1 = 1 AND estado = 'A'";
		if($query)
		{
			$sql.=" AND nombre_punto LIKE '%".$query."%'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}
	function lista_usuario($query)
	{
		$sql = "SELECT id_usuario as 'id',nombre_usuario as 'nombre',punto_venta as 'punto' FROM usuarios WHERE estado_usuario = 'A'";
		if($query)
		{
			$sql.=" AND nombre_usuario LIKE '%".$query."%'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}
	function lista_bodega_ina($query)
	{
		$sql = "SELECT id_bodegas as 'id',detalle_bodega as 'nombre' FROM bodegas WHERE 1 = 1 AND estado = 'I'";
		if($query)
		{
			$sql.=" AND detalle_categoria LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}
	function lista_bodega($query=false,$id=false)
	{
		$sql = "SELECT id_bodegas as 'id',detalle_bodega as 'nombre' FROM bodegas WHERE 1 = 1 AND estado = 'A'";
		if($query)
		{
			$sql.=" AND detalle_categoria LIKE '%".$query."%'";
		}
		if($id)
		{
			$sql.=" AND id_bodegas = '".$id."'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}
	function bodegas_asignadas($query)
	{
		$sql = "SELECT id_punto_venta as 'id',nombre_punto as 'nombre',bodega,all_bodegas FROM punto_venta WHERE estado = 'A' ORDER BY id_punto_venta Desc;";
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function delete_punto($id)
	{
		$sql = "DELETE FROM  punto_venta WHERE id_punto_venta='".$id."'";
		// print_r($sql);die();
		$datos = $this->db->sql_string_cod_error($sql);
		if($datos==1)
		{
			return $datos;
		}else if($datos=='547')
		{
			return -2;
		}else
		{
			return -1;
		}
	}

	function delete_usu_punto($id)
	{
		$sql = "DELETE FROM  acceso_punto_venta WHERE id_acceso_punto='".$id."'";
		// print_r($sql);die();
		$datos = $this->db->sql_string_cod_error($sql);
		if($datos==1)
		{
			return $datos;
		}else if($datos=='547')
		{
			return -2;
		}else
		{
			return -1;
		}
	}

	function update_punto($tabla,$datos,$where)
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
	function acceso_punto()
	{
		$sql="SELECT DISTINCT A.id_punto_venta as 'id', nombre_punto as 'nombre'
             FROM acceso_punto_venta A
             INNER JOIN punto_venta P ON A.id_punto_venta = P.id_punto_venta";
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function usuarios_acceso_punto($punto)
	{
		$sql="SELECT id_acceso_punto as 'id',nombre_usuario as 'nombre', nombre_punto as 'punto'
              FROM acceso_punto_venta AP 
              INNER JOIN usuarios U ON AP.id_usuario = U.id_usuario
              INNER JOIN punto_venta P ON AP.id_punto_venta = P.id_punto_venta
              WHERE 1=1 AND AP.id_punto_venta =".$punto;
              $datos = $this->db->datos($sql);
		return $datos;
	}
}
?>