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
class transaccionesM
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

	function lista_transacciones($query=false,$cali=false,$proc=false,$WHERE=false)
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
	function transacciones($parametros)
	{
		// print_r($parametros);die();
		$sql="SELECT DISTINCT documento,num_documento,tipo_transaccion,detalle_transaccion,fecha,B2.detalle_bodega as 'Bodega salida',B.detalle_bodega as 'Bodega entrada',nombre_usuario 
		FROM transacciones T
		INNER JOIN tipo_transacciones TT ON T.tipo_transaccion = TT.id_tipo_transaccion 
		LEFT JOIN bodegas B ON T.id_bodega_entrada = B.id_bodegas
		LEFT JOIN bodegas B2 ON T.id_bodega_salida = B2.id_bodegas
		INNER JOIN usuarios U ON T.id_usuario = U.id_usuario WHERE 1 = 1";

		if($parametros['desde']!='' && $parametros['hasta']=='')
		{
			$sql.=" AND fecha BETWEEN '".$parametros['desde']."' AND '".date('Y')."1231'";
		}else	if($parametros['desde']=='' && $parametros['hasta']!='')
		{
			$sql.=" AND fecha BETWEEN '".date('Y').".0101' AND '". $parametros['hasta']."'";
		}else if($parametros['desde']!='' && $parametros['hasta']!='')
		{			
			$sql.=" AND fecha BETWEEN '".$parametros['desde']."' AND '". $parametros['hasta']."'";
		}

		if($parametros['usu'] =='T')
		  {
		  	$parametros['usu'] = '';
		  }
      if($parametros['tipo'] =='T')
		  {
		  	$parametros['tipo'] = '';
		  }
      if($parametros['salida'] =='T')
		  {
		  	$parametros['salida'] = '';
		  }
      if($parametros['entrada'] =='T')
		  {
		  	$parametros['entrada'] = '';
		  }


		if($parametros['usu'])
		{

			$sql.=" AND T.id_usuario ='".$parametros['usu']."'";
		}

		if($parametros['tipo'])
		{

			$sql.=" AND T.tipo_transaccion ='".$parametros['tipo']."'";
		}
		if($parametros['salida'])
		{

			$sql.=" AND T.id_bodega_salida ='".$parametros['salida']."'";
		}
		if($parametros['entrada'])
		{

			$sql.=" AND T.id_bodega_entrada ='".$parametros['entrada']."'";
		}

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function transacciones_reporte($id=false)
	{
		$sql="SELECT DISTINCT documento,num_documento,tipo_transaccion,detalle_transaccion,fecha as 'fecha_factura',B.detalle_bodega as 'entrada',B2.detalle_bodega as 'salida',nombre_usuario as 'nombre' 
		FROM transacciones T
		INNER JOIN tipo_transacciones TT ON T.tipo_transaccion = TT.id_tipo_transaccion 
		LEFT JOIN bodegas B ON T.id_bodega_entrada = B.id_bodegas
		LEFT JOIN bodegas B2 ON T.id_bodega_salida = B2.id_bodegas
		INNER JOIN usuarios U ON T.id_usuario = U.id_usuario ";
		if($id)
		{
			$sql.=" AND num_documento =  '".$id."'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function proveedores($query=false)
	{
		$sql="SELECT id_cliente_prove as 'id',nombre,ci_ruc as 'ci',email,telefono,direccion FROM cliente_proveedor WHERE  tipo = 'P' AND estado='A'";
		if($query)
		{
			$sql.=" AND nombre LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;


	}

	function proveedores_inactivos($query=false)
	{
		$sql="SELECT id_cliente_prove as 'id',nombre,ci_ruc as 'ci',email,telefono,direccion FROM cliente_proveedor WHERE  tipo = 'P' AND estado='I'";
		if($query)
		{
			$sql.=" AND nombre LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}



	function ficha_transacciones($id)
	{
		$sql="SELECT id_cliente_prove as 'id',nombre,ci_ruc as 'ci',email,telefono,direccion,credito FROM cliente_proveedor WHERE  tipo = 'C'
		 AND id_cliente_prove = '".$id."'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function ficha_proveedor($id)
	{
		$sql="SELECT id_cliente_prove as 'id',nombre,ci_ruc as 'ci',email,telefono,direccion FROM cliente_proveedor WHERE  tipo = 'P'
		 AND id_cliente_prove = '".$id."'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function delete_transacciones($id)
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
	function tipo_transaccion($query)
	{
		$sql = "SELECT id_tipo_transaccion as 'id',detalle_transaccion as 'nombre' FROM tipo_transacciones WHERE estado_transaccion = 'A'";
		if($query)
		{
			$sql.=" AND detalle_transaccion 	like '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;


	}

	function datos_transacciones($parametros)
	{
		if($parametros['tran']==2)
		{
			$sql = "SELECT id_factura,documento,num_documento,tipo_transaccion,detalle_transaccion,fecha_factura,detalle_bodega as 'salida',null as 'entrada',nombre FROM facturas F 
			 LEFT JOIN cliente_proveedor C ON F.cliente = C.id_cliente_prove 
			LEFT JOIN transacciones T ON F.numero_factura = T.num_documento 
			LEFT JOIN tipo_transacciones TT ON T.tipo_transaccion = TT.id_tipo_transaccion
			LEFT JOIN bodegas B ON T.id_bodega_salida = B.id_bodegas 
			WHERE numero_factura = '".$parametros['id']."'";
			$datos = $this->db->datos($sql);
			$sql2 = "SELECT DISTINCT L.producto,L.cantidad,L.precio_uni,L.codigo_ref,peso,foto_producto as 'foto' FROM lineas_factura L LEFT JOIN productos P ON L.codigo_ref = P.referencia_producto  WHERE id_factura = '".$datos[0]['id_factura']."'";
			$datos_l = $this->db->datos($sql2);
		}else 
		{
			$datos = $this->transacciones_reporte($parametros['id']);

			$sql = "SELECT  P.detalle_producto as producto,T.cantidad_transferencia as 'cantidad',P.precio_producto as 'precio_uni',P.referencia_producto as 'codigo_ref',P.peso,p.foto_producto as 'foto'  FROM transacciones T
			LEFT JOIN productos P ON T.id_producto = P.id_producto
			WHERE num_documento = '".$parametros['id']."'";

			// print_r($sql);die();
			$datos_l = $this->db->datos($sql);
		}
		$respuesta = array('documento_datos'=>$datos,'lines_documentos'=>$datos_l);

		// print_r($respuesta);die();
		return $respuesta;
	}
}
?>