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
class orden_trabajoM
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
	function eliminar_imagen($tabla,$datos,$where)
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
		$sql = "DELETE FROM  lineas_orden WHERE id_linea='".$id."'";
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
		$sql = "SELECT id_material as 'id',detalle_material as 'nombre' FROM material WHERE 1 = 1 AND estado_material = 'A'";
		if($query)
		{
			$sql.=" AND detalle_material LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}
	function lineas_orden_trabajo($idorden)
	{
		//$sql = "SELECT * FROM facturas WHERE id_factura ='".$idfactura."'";
		//$cabecera = $this->db->datos($sql);
		$sql2 = "SELECT id_linea as 'id',codigo_ref,producto,cantidad,linea_detalle,numero_orden,Encargado FROM  lineas_orden l
		LEFT JOIN orden_trabajo O ON l.id_factura = O.id_orden
		WHERE id_factura='".$idorden."'";

		// print_r($sql2);die();
		$lineas = $this->db->datos($sql2);
		
		//$result = array('cabecera'=>$cabecera,'lineas'=>$lineas);
		// print_r($sql);die();
		return $lineas;

	}
	function lineas_orden_trabajo_detalle($idorden)
	{
		//$sql = "SELECT * FROM facturas WHERE id_factura ='".$idfactura."'";
		//$cabecera = $this->db->datos($sql);
		$sql2 = "SELECT id_linea as 'id',codigo_ref,producto,cantidad,linea_detalle,numero_orden,Encargado,foto_producto as 'foto'  FROM  lineas_orden l
		LEFT JOIN orden_trabajo O ON l.id_factura = O.id_orden
		LEFT JOIN productos P ON l.codigo_ref = P.referencia_producto 
		WHERE id_factura='".$idorden."'";

		// print_r($sql2);die();
		$lineas = $this->db->datos($sql2);
		
		//$result = array('cabecera'=>$cabecera,'lineas'=>$lineas);
		// print_r($sql);die();
		return $lineas;

	}

	function ordenes($numero = false,$id=false)
	{
		$sql = "   SELECT id_orden as 'id',Encargado,numero_orden,fecha_orden,fecha_exp,nombre_punto,estado_orden,U.nombre_usuario,detalle_bodega,bodega_destino,O.maestro as 'idma',US.nombre_usuario as 'maestro',boceto,codigo FROM orden_trabajo O
		LEFT JOIN punto_venta P ON O.punto_venta = P.id_punto_venta
    LEFT JOIN bodegas B ON O.bodega_destino = B.id_bodegas 
		LEFT JOIN usuarios U ON O.id_usuario = U.id_usuario  
		LEFT JOIN usuarios US ON O.maestro = US.id_usuario where 1 = 1";

		if($numero)
		{
			$sql.= " AND O.numero_orden='".$numero."'";
		}
		if($id)
		{
			$sql.= " AND id_orden='".$id."'";
		}
		// print_r($sql);die();
		$lineas = $this->db->datos($sql);
		return $lineas;
	}
	function datos_produccion($ref,$bodega)
	{
		$sql="SELECT cantidad,id_materia_prima FROM datos_produccion D INNER JOIN productos P ON D.id_producto = P.id_producto WHERE P.referencia_producto = '".$ref."' and bodega=".$bodega;
		$materia = $this->db->datos($sql);
		return $materia;
	}
	function stock_material($material)
	{
		$sql = "SELECT id_producto,stock_producto FROM productos WHERE id_producto = '".$material."'";
		$stock = $this->db->datos($sql);
		return $stock;
	}

	function producto_select($reference,$bodega)
	{
		$sql = "SELECT * FROM productos WHERE referencia_producto = '".$reference."' AND bodega='".$bodega."'";
		$stock = $this->db->datos($sql);
		return $stock;
	}


	function num_orden()
	{
		$sql= "SELECT MAX (numero_orden) as num FROM orden_trabajo";
		$stock = $this->db->datos($sql);
		return $stock;
	}
	function lista_de_maestros($query=false)
	{
		$sql = "SELECT id_usuario as 'id',nombre_usuario as 'nombre' FROM usuarios WHERE maestro = 1";
		if($query)
		{
			$sql.=" and nombre_usuario like '%".$query."%'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function img_guardar($name,$codigo,$posicion,$orden)
	{
		// print_r($codigo);die();
		$tabla = 'detalle_trabajo';
		$datos[0]['campo']='foto'.($posicion+1);
		$datos[0]['dato']=$name;
		$datos[1]['campo']='id_trabajo';
		$datos[1]['dato']=$orden;
		

		$where[0]['campo']='id_detalle_trabajo';
		$where[0]['dato'] = $codigo;

		$datos1[0]['campo']='boceto';
		$datos1[0]['dato']=1;
		$where1[0]['campo']='id_orden';
		$where1[0]['dato'] = $orden;
		$this->db->update('orden_trabajo',$datos1,$where1);
		if($codigo!='')
		{
			$datos = $this->db->update($tabla,$datos,$where);
		}else{
			$datos = $this->db->inserts($tabla,$datos);
		}
		if($datos==1)
		{
			return 1;
		}else
		{
			return -1;
		}

	}

	function detalle_boceto($cod)
	{
		$sql = "SELECT * FROM detalle_trabajo WHERE id_trabajo = '".$cod."'";
		$datos = $this->db->datos($sql);
		return $datos;
	}
	
	function eliminar_de_producto($id)
	{
		$sql = "DELETE FROM  lineas_orden WHERE id_factura='".$id."'";
		return $this->db->sql_string($sql);


	}
	function eliminar_de_diseño($id)
	{
		$sql = "DELETE FROM  detalle_trabajo WHERE id_trabajo='".$id."'";
		return $this->db->sql_string($sql);
	}


}
?>