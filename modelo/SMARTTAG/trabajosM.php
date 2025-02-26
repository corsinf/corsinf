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
class trabajosM
{
	private $db;
	private $global;
	function __construct()
	{		
		$this->db = new db();
		$this->global = new codigos_globales();
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

	
	function eliminar_permisos($id)
	{
		$sql = "DELETE FROM accesos WHERE id_tipo_usuario='".$id."'";
		// print_r($sql);die();
		$datos = $this->db->sql_string($sql);
		return $datos;
	}

	function lista_material($query=false)
	{
		$sql="SELECT id_material as 'id', detalle_material as 'nombre' FROM material WHERE estado_material = 'A' AND 1=1";
		
		if($query)
		{
			$sql.=" AND  detalle_material  LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_estado_joya($query=false)
	{
		$sql="SELECT id_estado_joya as 'id',detalle_estado as 'nombre' FROM estado_joya WHERE estado = 'A' AND 1=1";
		
		if($query)
		{
			$sql.=" AND  detalle_estado  LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}


	function lista_trabajos($query=false,$id=false)
	{
		$sql="SELECT A.id_producto as 'id',referencia_producto as 'cod',detalle_producto as 'nombre',fecha_ingreso as 'fecha',peso as 'peso',precio_producto as 'precio',detalle_categoria as 'tipo',detalle_material as 'material',nombre as 'cliente',ET.detalle_estado as 'est', descripcion_trabajo as 'trabajo',id_detalle_trabajo  
		FROM productos  A
		LEFT JOIN material M ON A.material = M.id_material
		LEFT JOIN estado_joya EJ  ON A.estado_ingreso = EJ.id_estado_joya
		LEFT JOIN categorias C ON A.id_categoria = C.id_categoria
		LEFT JOIN estado_trabajo ET ON A.estado_trabajo = ET.id_estado_trabajo
		LEFT JOIN cliente_proveedor CP ON A.id_proveedor = CP.id_cliente_prove  
      LEFT JOIN detalle_trabajo D ON A.id_producto = D.id_producto 
		WHERE trabajo = 1 AND stock_producto <>0";	
		if($query)
		{
			$sql.=" AND  detalle_producto  LIKE '%".$query."%'";
		}
		if($id)
		{
			$sql.=" AND  A.id_producto ='".$id."'";
		}

		$sql.=' ORDER BY A.id_producto DESC';
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function producto_trabajo($codigo=false,$id=false)
	{
		$sql ="SELECT p.id_producto,referencia_producto,detalle_producto,precio_producto,P.descripcion_trabajo,foto_producto,foto1,foto2,foto3,foto4,foto5,foto6,id_detalle_trabajo 
		FROM productos P
		LEFT JOIN detalle_trabajo D ON P.id_producto = D.id_producto
		WHERE trabajo = 1";
		if($codigo)
		{
			$sql.=" AND referencia_producto='".$codigo."'";
		}
		if($id)
		{
			$sql.=" AND p.id_producto='".$id."'";
		}
	   $datos = $this->db->datos($sql);
		return $datos;

	}

	function producto_trabajo_detalle($codigo=false,$id=false)
	{
		$sql ="SELECT *
		FROM productos P		
		LEFT JOIN estado_joya EJ  ON P.estado_ingreso = EJ.id_estado_joya
		LEFT JOIN categorias C ON P.id_categoria = C.id_categoria
		LEFT JOIN detalle_trabajo D ON P.id_producto = D.id_producto
		LEFT JOIN material M ON P.material = M.id_material
		LEFT JOIN cliente_proveedor CP ON P.id_proveedor = CP.id_cliente_prove  
		LEFT JOIN bodegas B ON P.bodega = B.id_bodegas
		LEFT JOIN punto_venta PV ON P.id_punto = PV.id_punto_venta 
		WHERE trabajo = 1";
		if($codigo)
		{
			$sql.=" AND referencia_producto='".$codigo."'";
		}
		if($id)
		{
			$sql.=" AND p.id_producto='".$id."'";
		}
		// print_r($sql);die();
	   $datos = $this->db->datos($sql);
		return $datos;

	}

	function nuevo_codigo()
	{
		$new_cod= 1;
		$sql="SELECT MAX(referencia_producto) as cod FROM productos WHERE trabajo = 1";
		$datos = $this->db->datos($sql);
		if(count($datos)>0)
		{
		$new_cod = $datos[0]['cod'];
		$new_cod = substr($new_cod,3);
		$new_cod = intval($new_cod)+1;
	  }
		return $new_cod;

	}


}
?>