<?php
@session_start();
if($_SESSION['INICIO']['TIPO_BASE'] == 'MYSQL')
   {
   	if(!class_exists('db'))
   	{
   		include('../db/db_mysql.php');
   	}
   }else
   {
   	if(!class_exists('db'))
   	{
   		include('../db/db_sql.php'); 
   	}
   }
   if(!class_exists('codigos_globales'))
   {
   	include('../db/codigos_globales.php');
   }
/**
 * 
 */
class punto_ventaM
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

	function lista_de_productos($query=false)
	{
		$sql = "SELECT id_producto as 'id',referencia_producto as 'ref',detalle_producto as 'detalle',c.detalle_categoria as 'cate',c.id_categoria as 'idcat',precio_producto as 'precio',stock_producto as 'stock',foto_producto as 'foto',fecha_creacion,p.bodega,b.detalle_bodega 
		FROM productos p
		INNER JOIN categorias c on p.id_categoria = c.id_categoria 
		INNER JOIN bodegas b ON p.bodega = b.id_bodegas  AND bodega in (".$_SESSION['INICIO']['BODEGAS'].") WHERE 1=1 ";
		if($query)
		{
			$sql.=" and referencia_producto+' '+detalle_producto LIKE '%".$query."%'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function lista_de_productos_all($query=false,$bodega=false)
	{
		$sql = "SELECT id_producto as 'id',referencia_producto as 'ref',detalle_producto as 'detalle',c.detalle_categoria as 'cate',c.id_categoria as 'idcat',precio_producto as 'precio',stock_producto as 'stock',foto_producto as 'foto',fecha_creacion,p.bodega,b.detalle_bodega 
		FROM productos p
		INNER JOIN categorias c on p.id_categoria = c.id_categoria 
		INNER JOIN bodegas b ON p.bodega = b.id_bodegas  WHERE 1=1 ";
		if($query)
		{
			$sql.=" and referencia_producto+' '+detalle_producto LIKE '%".$query."%'";
		}
		if($bodega)
		{
			$sql.=" and bodega= '".$bodega."'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}


	function lista_de_productos_all_detalle($query=false)
	{
		$sql = "SELECT id_producto as 'id',referencia_producto as 'ref',detalle_producto as 'detalle',c.detalle_categoria as 'cate',c.id_categoria as 'idcat',precio_producto as 'precio',stock_producto as 'stock',foto_producto as 'foto',fecha_creacion,p.bodega,b.detalle_bodega,peso,material,detalle_material  
		FROM productos p
		INNER JOIN categorias c on p.id_categoria = c.id_categoria 
      LEFT JOIN material M ON p.material = M.id_material
		INNER JOIN bodegas b ON p.bodega = b.id_bodegas  AND bodega in (".$_SESSION['INICIO']['BODEGAS'].") WHERE 1=1 ";
		if($query)
		{
			$sql.=" and referencia_producto+' '+detalle_producto LIKE '%".$query."%'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function lista_de_productos_all_detalle_ord($query=false)
	{
		$sql = "SELECT id_producto as 'id',referencia_producto as 'ref',detalle_producto as 'detalle',c.detalle_categoria as 'cate',c.id_categoria as 'idcat',precio_producto as 'precio',stock_producto as 'stock',foto_producto as 'foto',fecha_creacion,p.bodega,b.detalle_bodega,peso,material,detalle_material  
		FROM productos p
		INNER JOIN categorias c on p.id_categoria = c.id_categoria 
      LEFT JOIN material M ON p.material = M.id_material
		INNER JOIN bodegas b ON p.bodega = b.id_bodegas WHERE 1=1 AND trabajo=0";
		if($query)
		{
			$sql.=" and referencia_producto+' '+detalle_producto LIKE '%".$query."%'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}


	function lista_de_productos_all_detalle_id($query=false)
	{
		$sql = "SELECT id_producto as 'id',referencia_producto as 'ref',detalle_producto as 'detalle',c.detalle_categoria as 'cate',c.id_categoria as 'idcat',precio_producto as 'precio',stock_producto as 'stock',foto_producto as 'foto',fecha_creacion,p.bodega,b.detalle_bodega,peso,material,detalle_material,id_estado_joya,detalle_estado,id_cliente_prove,nombre,descripcion_trabajo  
		FROM productos p
		INNER JOIN categorias c on p.id_categoria = c.id_categoria 
      LEFT JOIN material M ON p.material = M.id_material
      LEFT JOIN estado_joya E ON p.estado_ingreso = E.id_estado_joya
      LEFT JOIN cliente_proveedor CP ON p.id_proveedor = CP.id_cliente_prove
		INNER JOIN bodegas b ON p.bodega = b.id_bodegas  WHERE 1=1 ";
		if($query)
		{
			$sql.=" AND id_producto = '".$query."'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}


	function lista_de_productos_pedido($idfactura=false)
	{
		//$sql = "SELECT * FROM facturas WHERE id_factura ='".$idfactura."'";
		//$cabecera = $this->db->datos($sql);
		$sql2 = "SELECT id_linea as 'id',producto,cantidad,precio_uni,descuento,subtotal,iva,total FROM lineas_factura WHERE id_factura='".$idfactura."'";
		$lineas = $this->db->datos($sql2);
		
		//$result = array('cabecera'=>$cabecera,'lineas'=>$lineas);
		// print_r($sql);die();
		return $lineas;

	}
	function lista_de_productos_pedido_($idfactura=false)
	{
		//$sql = "SELECT * FROM facturas WHERE id_factura ='".$idfactura."'";
		//$cabecera = $this->db->datos($sql);
		$sql2 = "SELECT id_linea as 'id',producto,cantidad,precio_uni,descuento,subtotal,iva,total,id_bodega FROM lineas_factura WHERE id_factura='".$idfactura."'";
		$lineas = $this->db->datos($sql2);
		
		//$result = array('cabecera'=>$cabecera,'lineas'=>$lineas);
		// print_r($sql);die();
		return $lineas;

	}

	function lista_de_productos_pedido_all_description($idfactura=false)
	{
		$sql = "SELECT * FROM facturas F
		LEFT JOIN cliente_proveedor CP ON F.cliente = CP.id_cliente_prove WHERE id_factura ='".$idfactura."'";
		$cabecera = $this->db->datos($sql);
		$sql2 = "SELECT id_linea as 'id',codigo_ref as 'codigo',producto,cantidad,precio_uni,descuento,subtotal,iva,total FROM lineas_factura WHERE id_factura='".$idfactura."'";
		$lineas = $this->db->datos($sql2);
		
		$result = array('cabecera'=>$cabecera,'lineas'=>$lineas);
		// print_r($sql);die();
		return $result;

	}


	function lista_de_clientes($query=false,$ci=false,$id=false)
	{
		$sql = "SELECT id_cliente_prove as 'id', nombre,ci_ruc as 'ci', email ,direccion ,telefono,credito FROM cliente_proveedor WHERE 1=1";
		"tipo = 'C' ";
		if($query)
		{
			$sql.=" and nombre LIKE '%".$query."%'";
		}
		if($ci)
		{
			$sql.=" and ci_ruc LIKE '%".$ci."%'";
		}
		if($id)
		{
			$sql.=" and id_cliente_prove= '".$ci."'";
		}

		$sql.= " AND estado='A'";

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function lista_de_clientes_panio($query=false)
	{
		$sql = "SELECT id_tipo_usuario as 'id',nombre_usuario as 'nombre',ci_ruc_usuario as 'ci',email_usuario as 'email' FROM usuarios WHERE estado_usuario = 'A' ";
		if($query)
		{
			$sql.=" and nombre_usuario +' '+ci_ruc_usuario LIKE '%".$query."%'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function datos_cliente($idfactura,$num=false)
	{
		
		    $sql = "SELECT C.id_cliente_prove as 'id',C.nombre as nombre,C.ci_ruc as 'ci',C.telefono as 'tel',C.email as 'email',F.numero_factura as 'fac',F.fecha_factura as 'fecha',C.direccion as 'dir',F.fecha_exp as 'fecha_ven',credito FROM facturas F
		    LEFT JOIN cliente_proveedor C ON F.cliente = C.id_cliente_prove
		    WHERE id_factura = '".$idfactura."' ";
	    // print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function numero_de_factura($numero = false)
	{
			$numero = '1';		
			$numero = $this->numero_fac();
			if(!empty($numero))
			{
				$numero = $numero[0]['num']+1;
				$existe = true;
				while ($existe == true ) {
				 	$nu = $this->numero_fac($numero);
				 	if(empty($num))
				 	{
				 		// print_r('expression');die();
				 		$existe = false;
				 		break;
				 	}else
				 	{
				 		// print_r('ssexpression');die();
				 		$numero +=1;
				 	}
				 } 
			}

			// print_r($numero);die();
		return $numero;

	}

	function numero_de_coti($numero = false)
	{
			$numero = '1';		
			$numero = $this->numero_coti();
			if(!empty($numero))
			{
				$numero = $numero[0]['num']+1;
				$existe = true;
				while ($existe == true ) {
				 	$nu = $this->numero_coti($numero);
				 	if(empty($num))
				 	{
				 		// print_r('expression');die();
				 		$existe = false;
				 		break;
				 	}else
				 	{
				 		// print_r('ssexpression');die();
				 		$numero +=1;
				 	}
				 } 
			}

			// print_r($numero);die();
		return $numero;

	}

	function eliminar_permisos($id)
	{
		$sql = "DELETE FROM accesos WHERE id_tipo_usuario='".$id."'";
		// print_r($sql);die();
		$datos = $this->db->sql_string($sql);
		return $datos;
	}
	function eliminar_linea($id)
	{
		$sql = "DELETE FROM lineas_factura WHERE id_linea='".$id."'";
		// print_r($sql);die();
		$datos = $this->db->sql_string($sql);
		return $datos;
	}

	function bodegas_punto($id=false)
	{
		$sql ="SELECT id_punto_venta as 'id',bodega,all_bodegas as 'all',nombre_punto as 'nombre' FROM punto_venta WHERE 1 = 1 AND estado = 'A' AND id_punto_venta";
		if($id)
		{
			$sql.='= '.$id;

		}else
		{
			$sql.=" in (".$_SESSION['INICIO']['PUNTO_VENTA'].")";

		} 

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function pasar_a_factura_coti($parametro)
	{
		$numero = '1';
		if($parametro['tip']=='FA')
		{
			$numero = $this->numero_fac();
			if(!empty($numero))
			{
				$numero = $numero[0]['num']+1;
				$existe = true;
				while ($existe == true ) {
				 	$nu = $this->numero_fac($numero);
				 	if(empty($num))
				 	{
				 		$existe = false;
				 		break;
				 	}else
				 	{
				 		$numero +=1;
				 	}
				 } 
			}
		}

		$sql= "UPDATE facturas SET tipo_factura = '".$parametro['tip']."',numero_factura='".$numero."' WHERE  id_factura = '".$parametro['fac']."'";
		// print_r($sql);die();
		$datos = $this->db->sql_string($sql);
		return $datos;
	}

	function numero_fac($num=false,$tipo ='V')
	{
		if($num)
		{
			$reg =' * ';
			$nume = " AND numero_factura = '".$num."'";
		}else
		{
			 $reg = "MAX(numero_factura) as 'num'";
			 $nume = '';
		}
		$sql = "SELECT ".$reg." FROM facturas WHERE tipo_factura = 'FA' ".$nume." AND tipo_documento = '".$tipo."'";

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function numero_coti($num=false)
	{
		if($num)
		{
			$reg =' * ';
			$nume = " AND numero_factura = '".$num."'";
		}else
		{
			 $reg = "MAX(numero_factura) as 'num'";
			 $nume = '';
		}
		$sql = "SELECT ".$reg." FROM facturas WHERE tipo_factura = 'PR' ".$nume;

		// print_r($sql);
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function cheques_posfechados($factura)
	{
		$sql="SELECT cheques_id,cheques_num,cheques_banco,cheques_fecha,cheque_monto FROM cheques_pos WHERE factura = '".$factura."'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}
	function delete($tabla,$datos)
	{
		return $this->db->delete($tabla,$datos);
	}

	function cheques_pos_fecha_cru($factura)
	{
		$sql = "SELECT * FROM cheques_pos WHERE factura = '".$factura."' AND cheques_fecha <= GETDATE()";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function facturas_x_cliente($cliente)
	{
		$sql = "SELECT id_factura as 'id',numero_factura,subtotal_factura,iva_factura,total_factura,fecha_factura,estado_factura,punto_venta,tipo_factura  FROM facturas WHERE  cliente = ".$cliente." AND tipo_factura = 'FA'";

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function presupuestos_x_cliente($cliente)
	{
		$sql = "SELECT id_factura as 'id',numero_factura,subtotal_factura,iva_factura,total_factura,fecha_factura,estado_factura,punto_venta,tipo_factura  FROM facturas WHERE  cliente = ".$cliente." AND tipo_factura = 'PR'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

}
?>