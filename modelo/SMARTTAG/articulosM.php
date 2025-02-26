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
class articulosM
{
	private $db;
	private $global;
	function __construct()
	{		
		$this->db = new db();
		$this->global = new codigos_globales();
	}

	function cargar_articulos($parametros=false)
	{
		$sql = "SELECT id_producto as 'id',referencia_producto as 'ref',detalle_producto as 'detalle',c.detalle_categoria as 'cate',c.id_categoria as 'idcat',precio_producto as 'precio',stock_producto as 'stock',foto_producto as 'foto',detalle_bodega,peso,gramo,costo,p.produccion,talla,modificacion,p.bodega,detalle_bodega,sarta,forma,color FROM productos p
		LEFT JOIN categorias c on p.id_categoria = c.id_categoria 
		LEFT JOIN bodegas b on p.bodega = b.id_bodegas WHERE 1=1 AND trabajo = 0 and materia_prima = 0 ";
		if(isset($parametros['id']))
		{
			$sql.=" AND id_producto = '".$parametros['id']."'";
		}
		if(isset($parametros['referencia']))
		{
			$sql.=" AND referencia_producto = '".$parametros['referencia']."'";
		}
		if(isset($parametros['detalle']))
		{
			$sql.=" AND detalle_producto = '".$parametros['detalle']."'";
		}
		if(isset($parametros['detalle_like']))
		{
			$sql.=" AND referencia_producto+' '+detalle_producto LIKE '%".$parametros['detalle_like']."%'";
		}
		if(isset($parametros['bodega']) && $parametros['bodega']!='')
		{
			$sql.=" AND p.bodega='".$parametros['bodega']."'";
		}
		if(isset($parametros['categoria']) && $parametros['categoria']!='')
		{
			$sql.=" AND p.id_categoria='".$parametros['categoria']."'";
		}

		$sql.=" ORDER BY id_producto Desc";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function cargar_materia($parametros=false)
	{
		$sql = "SELECT id_producto as 'id',referencia_producto as 'ref',detalle_producto as 'detalle',precio_producto as 'precio',stock_producto as 'stock',unidad_medida,foto_producto as 'foto',detalle_bodega,paquetes,uni_paquetes,p.bodega as 'idbo',color,forma,sarta,puntos FROM productos p
		LEFT JOIN categorias c on p.id_categoria = c.id_categoria 
		LEFT JOIN bodegas b on p.bodega = b.id_bodegas WHERE 1=1 AND trabajo = 0 and materia_prima = 1 ";
		if(isset($parametros['id']))
		{
			$sql.=" AND id_producto = '".$parametros['id']."'";
		}
		if(isset($parametros['referencia']))
		{
			$sql.=" AND referencia_producto = '".$parametros['referencia']."'";
		}
		if(isset($parametros['detalle']))
		{
			$sql.=" AND detalle_producto = '".$parametros['detalle']."'";
		}
		if(isset($parametros['detalle_like']))
		{
			$sql.=" AND referencia_producto+' '+detalle_producto LIKE '%".$parametros['detalle_like']."%'";
		}
		if(isset($parametros['bodega']) && $parametros['bodega']!='')
		{
			$sql.=" AND p.bodega='".$parametros['bodega']."'";
		}
		if(isset($parametros['categoria']) && $parametros['categoria']!='')
		{
			$sql.=" AND p.id_categoria='".$parametros['categoria']."'";
		}

		$sql.=" ORDER BY id_producto Desc";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function cargar_materia_produccion($parametros=false)
	{
		$sql = "SELECT id_datos_produccion as 'id',cantidad,P2.detalle_producto as 'material' FROM datos_produccion DP
		INNER JOIN productos P2 ON DP.id_materia_prima = P2.id_producto
		 WHERE 1 = 1 and DP.id_producto = ".$parametros['id'];
		 if(isset($parametros['detalle_like']) && $parametros['detalle_like']!='')
		 {
		 	$sql.=" AND P2.detalle_producto LIKE '%".$parametros['detalle_like']."%' ";

		 }

		$sql.=" ORDER BY id_datos_produccion Desc";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}


	function productos($id=false,$ref=false,$bodega=false)
	{
		$sql="SELECT * FROM productos WHERE 1=1 ";
		if($id)
		{
			$sql.= "AND id_producto = '".$id."' ";
		}
		if($ref)
		{
			$sql.=" AND referencia_producto = '".$ref."'";
		}
		if($bodega)
		{
			$sql.=" AND bodega = '".$bodega."'";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function cargar_articulos_stock($parametros=false)
	{
		$sql = "SELECT id_asiento_k as 'id',P.referencia_producto as 'referencia',P.detalle_producto as 'producto',AK.cantidad as 'cantidad',AK.precio as 'precio',AK.iva as 'iva',AK.total as 'total'
		    FROM ASIENTO_K AK
		    LEFT JOIN productos P ON AK.id_producto = P.id_producto
		    WHERE id_usuario = '".$_SESSION['INICIO']['ID']."'";
		
		$sql.=" ORDER BY id_asiento_k DESC";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function cargar_articulos_stock_mat($parametros=false)
	{
		$sql = "SELECT id_asiento_k as 'id',P.referencia_producto as 'referencia',P.detalle_producto as 'producto',AK.cantidad as 'cantidad',AK.precio as 'precio',AK.iva as 'iva',AK.total as 'total'
		    FROM ASIENTO_K AK
		    LEFT JOIN productos P ON AK.id_producto = P.id_producto
		    WHERE id_usuario = '".$_SESSION['INICIO']['ID']."' and AK.materia_prima=1 ";
		
		$sql.=" ORDER BY id_asiento_k DESC";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function img_guardar($name,$codigo,$referencia=false,$tipo=false)
	{
		$tabla = 'productos';
		$datos[0]['campo']='foto_producto';
		$datos[0]['dato']=$name;
		

		$where[0]['campo']='id_producto';
		$where[0]['dato'] = $codigo;
		if($codigo!='')
		{
			$datos = $this->db->update($tabla,$datos,$where);
		}else
		{
			$datos[1]['campo']='referencia_producto';
		    $datos[1]['dato']=$referencia;
		    if($tipo)
		{
			$datos[2]['campo']='materia_prima';
		   $datos[2]['dato']=1;
		}
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

	function guardar_editar($datos,$where=false)
	{

		// print_r($datos);
		// print_r($where);
		// die();
		$tabla = 'productos';
		if($where)
		{

		// print_r('ddsssssdd');
		// die();
			$datos = $this->db->update($tabla,$datos,$where);
		}else
		{

		// print_r('dddd');
		// die();
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

	function existe_materia_produccion($material,$productos)
	{
		$sql= "SELECT * FROM datos_produccion WHERE id_producto = '".$productos."' AND id_materia_prima = '".$material."'";
		// print_r($sql);die();
		return $this->db->existente($sql);
	}


	function existe_bodega_arti($bodega,$referencia)
	{
		$sql= "SELECT stock_producto as 'stock' FROM productos WHERE bodega = '".$bodega."' AND referencia_producto = '".$referencia."'";
		return $this->db->existente($sql);
	}

	function existe_trans_datos($bodega,$bodega2,$referencia)
	{
		$sql= "SELECT id_transferencia as 'id',cantidad_transferencia as 'cant' FROM transferencias_bodegas_temp WHERE id_producto = '".$referencia."' AND id_bodega_salida='".$bodega."' AND id_bodega_entrada='".$bodega2."'";
		return $this->db->datos($sql);
	}

	function tabla_transferencias()
	{
		$sql="SELECT id_transferencia as 'id',detalle_producto as 'producto',cantidad_transferencia as 'cant',B1.detalle_bodega as 'salida',B.detalle_bodega as 'entrada'  FROM transferencias_bodegas_temp  TB
		INNER JOIN bodegas B ON TB.id_bodega_entrada = B.id_bodegas
		INNER JOIN bodegas B1 ON TB.id_bodega_salida = B1.id_bodegas
		INNER JOIN productos P ON TB.id_producto = P.id_producto WHERE id_usuario = '".$_SESSION['INICIO']['ID']."' ORDER BY id_transferencia";

		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function tabla_transferencias_()
	{
		$sql="SELECT * FROM transferencias_bodegas_temp WHERE id_usuario  = '".$_SESSION['INICIO']['ID']."' ORDER BY id_transferencia";
		// print_r($sql);die();
		return $this->db->datos($sql);
	}


	function categorias($query=false)
	{
		$sql = "SELECT id_categoria as 'id', detalle_categoria as 'nombre' FROM categorias WHERE 1=1 and estado='A' ";
		if($query)
		{
			$sql.=" AND detalle_categoria LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function proveedor($query=false)
	{
		$sql = "SELECT id_cliente_prove as 'id',nombre FROM cliente_proveedor WHERE tipo = 'P' AND estado='A' ";
		if($query)
		{
			$sql.=" AND nombre LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}



	function bodegas($query=false)
	{
		$sql = "SELECT id_bodegas as 'id',detalle_bodega as 'bodega',numero_bodega as 'num_bodega' FROM bodegas WHERE 1=1 AND estado='A' ";
		if($query)
		{
			$sql.=" AND detalle_bodega LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function bodegas_materia($query=false)
	{
		$sql = "SELECT id_bodegas as 'id',detalle_bodega as 'bodega',numero_bodega as 'num_bodega' FROM bodegas WHERE 1=1 
		AND estado='A' AND produccion=1";
		if($query)
		{
			$sql.=" AND detalle_bodega LIKE '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function eliminar_linea($id)
	{
		// print_r($id);die();
		$sql = "DELETE FROM ASIENTO_K WHERE id_asiento_k='".$id."' AND id_usuario='".$_SESSION['INICIO']['ID']."' ";
		// print_r($sql);die();
		$datos = $this->db->sql_string($sql);
		return $datos;
	}

	function eliminar_trans($id)
	{
		$sql = "DELETE FROM transferencias_bodegas_temp WHERE id_transferencia='".$id."' AND id_usuario='".$_SESSION['INICIO']['ID']."' ";
		$datos = $this->db->sql_string($sql);
		return $datos;
	}

	function eliminar_articulo($id)
	{
		$sql = "DELETE FROM productos WHERE id_producto='".$id."' ";
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

	}
	function eliminar_prima($id)
	{
		$sql = "DELETE FROM datos_produccion WHERE id_datos_produccion='".$id."' ";
		$datos = $this->db->sql_string($sql);
		return $datos;
	}

	function delete_all_transferencias()
	{
		$sql = "DELETE FROM transferencias_bodegas_temp WHERE id_usuario='".$_SESSION['INICIO']['ID']."' ";
		$datos = $this->db->sql_string($sql);
		return $datos;
	}

	function datos_asiento_k()
	{
		$sql = "SELECT * FROM ASIENTO_K WHERE id_usuario='".$_SESSION['INICIO']['ID']."' ";
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function datos_asiento_k_mat()
	{
		$sql = "SELECT * FROM ASIENTO_K WHERE id_usuario='".$_SESSION['INICIO']['ID']."' and materia_prima=1 ";
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function eliminar_asiento_K($orden)
	{
		$sql = "DELETE FROM ASIENTO_K WHERE num_orden='".$orden."' AND id_usuario='".$_SESSION['INICIO']['ID']."' ";
		$datos = $this->db->sql_string($sql);
		return $datos;
	}
	function existente_cate($nombre)
	{
		$sql = "SELECT id_categoria,detalle_categoria as 'nombre' FROM categorias WHERE 1 = 1 AND detalle_categoria = '".$nombre."'";
		return $this->db->existente($sql);
	}
	function productos_x_bodega($query,$bodega)
	{
		$sql="SELECT id_producto as 'id',detalle_producto as 'nombre',stock_producto  as 'stock' FROM productos WHERE 1=1 AND bodega = '".$bodega."'";
		if($query)
		{
			$sql.=" AND detalle_producto like '%".$query."%'";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function tipo_transaccion($query)
	{
		$sql = "SELECT * FROM tipo_transacciones WHERE detalle_transaccion = '".$query."'";
		$datos = $this->db->datos($sql);
		if(count($datos)>0)
		{
			return $datos[0]['id_tipo_transaccion'];
		}else
		{
			$datos1[0]['campo'] = 'detalle_transaccion';
			$datos1[0]['dato'] = $query;
			if($this->guardar($datos1,'tipo_transacciones')==1)
			{
				return $this->tipo_transaccion($query);
			}

		}
	}

	function auto_material($query=false)
	{
		$sql = "SELECT id_producto as 'id',detalle_producto as 'nombre' FROM productos WHERE materia_prima = 1";
		if($query)
		{
			$sql.= " AND detalle_producto LIKE '%".$query."%'";
		}

		$datos = $this->db->datos($sql);
		return $datos;


	}
	function referencia_codigo_producto()
	{
		$sql="SELECT referencia_producto FROM productos WHERE referencia_producto LIKE 'EF0%' ORDER BY referencia_producto DESC";
		$datos = $this->db->datos($sql);
		return $datos;		
	}

	function referencia_codigo_material()
	{
		$sql="SELECT referencia_producto FROM productos WHERE referencia_producto LIKE 'MA0%' ORDER BY referencia_producto DESC";
		$datos = $this->db->datos($sql);
		return $datos;		
	}

}
?>