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
class estado_trabajoM
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


	function lista_trabajos($query=false,$id=false)
	{
		$sql="SELECT id_producto as 'id',detalle_producto as 'nombre',referencia_producto as 'cod',fecha_ingreso as 'fecha',peso as 'peso',precio_producto as 'precio',detalle_categoria as 'tipo',detalle_material as 'material',nombre as 'cliente',ET.detalle_estado as 'est', descripcion_trabajo as 'trabajo',aprobado 
		FROM productos  A
		LEFT JOIN material M ON A.material = M.id_material
		LEFT JOIN estado_joya EJ  ON A.estado_ingreso = EJ.id_estado_joya
		LEFT JOIN categorias C ON A.id_categoria = C.id_categoria
		LEFT JOIN estado_trabajo ET ON A.estado_trabajo = ET.id_estado_trabajo
		LEFT JOIN cliente_proveedor CP ON A.id_proveedor = CP.id_cliente_prove 
		WHERE trabajo = 1 AND stock_producto <>0";		
		if($_SESSION['INICIO']['TIPO']!=1)
		{
			$sql.=" AND id_maestro=".$_SESSION['INICIO']['ID'];
		}	
		if($query)
		{
			$sql.=" AND  detalle_producto  LIKE '%".$query."%'";
		}
		if($id)
		{
			$sql.=" AND  id_producto  =  '".$id."'";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function ordenes_trabajo($query=false,$id=false)
	{
		$sql = "SELECT id_orden as 'id',fecha_orden as 'fecha' ,Encargado,codigo,DT.observacion,detalle_estado as 'estado',boceto,aprobado,tipo = 
		CASE
		WHEN boceto = 1
      THEN 'Diseño'
		WHEN boceto IS NULL OR boceto = 0
		THEN 'Producto'
		END               
		FROM orden_trabajo  OT
		LEFT JOIN detalle_trabajo DT ON OT.id_orden = DT.id_trabajo 
		LEFT JOIN estado_trabajo E ON OT.estado_trabajo = E.id_estado_trabajo
		WHERE 1 = 1";
		if($_SESSION['INICIO']['TIPO']!=1)
		{
			$sql.=" AND maestro=".$_SESSION['INICIO']['ID'];
		}

		if($id!='')
		{
			$sql.=" AND id_orden=".$id;
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}
		

	function estado_trabajo($query=false)
	{
		$sql="SELECT id_estado_trabajo as 'id',detalle_estado as 'nombre' FROM estado_trabajo";
		if($query)
		{
			$sql.=" AND detalle_estado LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function observaciones($id)
	{
		$sql="SELECT fecha,E.detalle_estado as 'estado',observacion FROM observaciones_trabajo 
		LEFT JOIN estado_trabajo E ON observaciones_trabajo.id_estado = E.id_estado_trabajo
		WHERE id_articulos = '".$id."'
		ORDER BY fecha";
		$datos = $this->db->datos($sql);
		return $datos;

	}	

	function observaciones_ord($id)
	{
		$sql="SELECT fecha,E.detalle_estado as 'estado',observacion FROM observaciones_trabajo 
		LEFT JOIN estado_trabajo E ON observaciones_trabajo.id_estado = E.id_estado_trabajo
		WHERE id_orden = '".$id."'
		ORDER BY fecha";
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function lista_material_all($id=false,$orden=false,$id_prima=false)
	{
		$sql = "SELECT id_datos_produccion as 'id',cantidad,P2.detalle_producto as 'material',id_materia_prima as 'id_prima' FROM datos_produccion DP
		INNER JOIN productos P2 ON DP.id_materia_prima = P2.id_producto
		 WHERE 1 = 1";
		 if($id)
		 {
		   $sql.= " AND DP.id_producto = ".$id;
		 }
		 if($orden)
		 {
		   $sql.= " AND DP.id_orden = ".$orden;
		 }
		 if($id_prima)
		 {
		   $sql.= " AND DP.id_materia_prima = ".$id_prima;
		 }

		 
		$sql.=" ORDER BY id_datos_produccion Desc";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}


	function lista_material($id=false,$orden=false)
	{
		$sql = "SELECT id_datos_produccion as 'id',cantidad,P2.detalle_producto as 'material',id_materia_prima as 'id_prima' FROM datos_produccion DP
		INNER JOIN productos P2 ON DP.id_materia_prima = P2.id_producto
		 WHERE 1 = 1 and default_pro = 0";
		 if($id)
		 {
		   $sql.= " AND DP.id_producto = ".$id;
		 }
		 if($orden)
		 {
		   $sql.= " AND DP.id_orden = ".$orden;
		 }

		 
		$sql.=" ORDER BY id_datos_produccion Desc";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function lista_material_default($id=false,$orden=false)
	{
		$sql = "SELECT id_datos_produccion as 'id',cantidad,P2.detalle_producto as 'material',id_materia_prima as 'id_prima' FROM datos_produccion DP
		INNER JOIN productos P2 ON DP.id_materia_prima = P2.id_producto
		 WHERE 1 = 1 and default_pro = 1";
		 if($id)
		 {
		   $sql.= " AND DP.id_producto = ".$id;
		 }
		 if($orden)
		 {
		   $sql.= " AND DP.id_orden = ".$orden;
		 }

		 
		$sql.=" ORDER BY id_datos_produccion Desc";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}


		function lista_material_fal($id=false,$orden=false)
	{
		$sql = "SELECT id_datos_produccion as 'id',cantidad,P2.detalle_producto as 'material',id_materia_prima as 'id_prima',tipo= CASE
		WHEN tipo=0
		THEN 'Faltante'
		WHEN tipo = 1
		THEN 'Devolucion'
		END,tipo as 'id_tipo' 
		FROM datos_produccion_fal DP
		INNER JOIN productos P2 ON DP.id_materia_prima = P2.id_producto
		 WHERE 1 = 1";
		 if($id)
		 {
		   $sql.= " AND DP.id_producto = ".$id;
		 }
		 if($orden)
		 {
		   $sql.= " AND DP.id_orden = ".$orden;
		 }

		 
		$sql.=" ORDER BY id_datos_produccion Desc";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}


   function lista_material_orden($id)
	{
		$sql = "SELECT id_datos_produccion as 'id',cantidad,P2.detalle_producto as 'material',P2.id_producto as 'id_prima' FROM datos_produccion DP
		INNER JOIN productos P2 ON DP.id_materia_prima = P2.id_producto
		 WHERE 1 = 1 and DP.id_producto = ".$id;
		 
		$sql.=" ORDER BY id_datos_produccion Desc";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}	

	

	function delete_clientes($id)
	{
		$sql = "DELETE FROM  cliente_proveedor WHERE id_cliente_prove='".$id."'";
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

	function lineas_orden($id)
	{
		$sql = "SELECT producto,cantidad,id_producto,linea_detalle FROM lineas_orden L 
		WHERE 1 = 1 and id_factura = ".$id;
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function producto($ref)
	{
		$sql="SELECT id_producto,detalle_producto FROM productos WHERE referencia_producto = '".$ref."'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function eliminar_linea_fal($id)
	{
		// print_r($id);die();
		$sql = "DELETE FROM datos_produccion_fal WHERE id_datos_produccion='".$id."' ";
		// print_r($sql);die();
		$datos = $this->db->sql_string($sql);
		return $datos;
	}
	function eliminar_linea($id)
	{
		// print_r($id);die();
		$sql = "DELETE FROM datos_produccion WHERE id_datos_produccion='".$id."' ";
		// print_r($sql);die();
		$datos = $this->db->sql_string($sql);
		return $datos;
	}

}
?>