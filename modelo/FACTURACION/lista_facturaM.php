<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');


class lista_facturaM  extends BaseModel
{
	protected $tabla = 'facturas';
    protected $primaryKey = 'id_factura AS _id';

    protected $camposPermitidos = [  
		'num_factura',
		'subtotal',
		'descuento',
		'iva',
		'total',
		'id_empresa',
		'id_usuario',
		'fecha',
		'id_cliente',
		'serie',
		'Autorizacion',
		'Sin_Iva',
		'Porc_IVA',
		'Con_IVA',
		'Tipo_pago',
		'Propina',
		'Clave_Acceso',
		'estado_factura',
		'id_pedido',
		'datos_adicionales',
		'estado_pago',
    ];


	function add($tabla,$datos,$id_empresa)
	{
		return $this->db->inserts($tabla,$datos,$id_empresa);
	}
	function update($tabla,$datos,$where,$id_empresa)
	{
		return $this->db->update($tabla,$datos,$where,$id_empresa);
	}
	function delete($tabla,$datos,$id_empresa)
	{
		return $this->db->delete($tabla,$datos,$id_empresa);
	}

	// function delete_lineas_factura($empresa,$id,$factura)
	// {
	// 	$sql = "DELETE FROM lineas_factura WHERE 1=1";
	// 	if($id)
	// 	{
	// 		$sql.=" AND id_lineas = '".$id."'";
	// 	}
	// 	if($factura)
	// 	{
	// 		$sql.=" AND id_factura= '".$factura."'";
	// 	}
	// 	return $this->db->sql_string($sql,1);
	// }

	// function delete_factura($empresa,$id)
	// {
	// 	$sql = "DELETE FROM facturas WHERE id_empresa = '".$empresa."' ";
	// 	if($id)
	// 	{
	// 		$sql.=" AND id_factura = '".$id."'";
	// 	}

	// 	return $this->db->sql_string($sql);

	// }

	
	
    function lista_facturas($query=false,$numfac=false,$desde=false,$hasta=false,$serie=false)
	{
		$sql= "SELECT id_factura as 'id',num_factura as 'num',fecha,C.nombre,total,serie,estado_factura as 'estado',Autorizacion,estado_pago as 'pago'
		FROM facturas F 
		INNER JOIN cliente C ON F.id_cliente = C.id_cliente 
		WHERE F.id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."' ";
		if($query)
		{			
			$sql.=" AND C.nombre LIKE '%".$query."%'";
		}
		if($numfac)
		{
			if(is_numeric($numfac))
			{
				$sql.=" AND num_factura LIKE '%".$numfac."%'";
			}
		}
		if($desde!='' &&  $hasta!='')
		{			
			$sql.=" AND fecha BETWEEN '".$desde."' AND '".$hasta."'";
		}

		if($serie)
		{			
			$sql.=" AND serie = '".$serie."'";
		}		
		$sql.=" ORDER BY F.id_factura DESC";
		$result = $this->db->datos($sql);
	    return $result;
	}

	function lista_series()
	{
		$sql= "SELECT DISTINCT serie 
		FROM facturas WHERE serie <> '' ";
		$result = $this->db->datos($sql);
	    return $result;
	}
	function linea_facturas_all($id,$id_empresa)
	{
		$sql= "SELECT * FROM lineas_factura LF 
		WHERE id_factura = '".$id."' ORDER BY id_lineas DESC";
		$result = $this->db->datos($sql);
	    return $result;
	}

	function linea_detalle($id,$id_empresa)
	{
		$sql= "SELECT id_lineas as 'id',producto, precio_uni as 'precio',cantidad,iva,descuento,porc_descuento,total,subtotal FROM lineas_factura 
		WHERE id_lineas ='".$id."' ORDER BY id_lineas DESC";
		$result = $this->db->datos($sql,$id_empresa);
	    return $result;
	}

	function articulos_id($query,$empresa,$category=false)
	{
		$sql= "SELECT id_productos as 'id',nombre,precio_uni,foto,iva,referencia 
		FROM productos 
		WHERE id_productos = '".$query."' ";
		if($category){
			$sql.=" AND categoria = '".$category."'";
		}
		$sql.= ' ORDER BY id_productos';
		// print_r($sql);die();
		$result = $this->db->datos($sql);
	    return $result;
	}

	function cliente_factura($id,$id_empresa)
	{
		$sql="SELECT C.id_cliente,nombre,mail,C.telefono,C.direccion,ci_ruc,num_factura,serie,valor_iva,Autorizacion,fecha,estado_factura,Tipo_pago,datos_adicionales,estado_pago 
		FROM facturas F
		INNER JOIN cliente C ON F.id_cliente = C.id_cliente
		INNER JOIN EMPRESAS E ON F.id_empresa = E.id_empresa
		WHERE F.id_factura = '".$id."'";
		$result = $this->db->datos($sql);
	    return $result;
				
	}

	function buscar_cliente($query,$idempresa)
	{
		$sql = "SELECT id_cliente,nombre,ci_ruc,telefono,mail,direccion FROM cliente WHERE nombre LIKE '%".$query."%' AND id_empresa = '".$idempresa."'";
		$result = $this->db->datos($sql);
	    return $result;

	}

	function datos_empresa($idempresa)
	{
		$sql = "SELECT * FROM empresa WHERE  id_empresa = '".$idempresa."'";
		$result = $this->db->datos($sql,$idempresa);
	    return $result;

	}

	function numero_factura($empresa)
	{
		$sql="SELECT max(o.num) as 'num' FROM
		(
			SELECT CAST(num_factura as INT) as 'num' FROM facturas WHERE id_empresa ='".$empresa."'
		) as o";
		$numero = $this->db->datos($sql,$empresa);
		return $numero[0]['num'];
	}

	 function buscar_facturas($empresa,$numero=false,$cliente=false)
	{
		$sql= "SELECT id_factura as 'id',num_factura as 'num',fecha,C.nombre,estado_factura  
		FROM facturas F 
		INNER JOIN cliente C ON F.id_cliente = C.id_cliente 
		WHERE F.id_empresa = '".$empresa."' 
		AND id_usuario = '".$_SESSION['INICIO']['ID_USUARIO']."'";
		if($numero)
		{			
			$sql.=" AND num_factura = '".$numero."'";			
		}
		if($cliente)
		{			
			$sql.=" AND id_cliente = '".$cliente."'";			
		}
		
		$sql.=" ORDER BY F.fecha DESC";
		$result = $this->db->datos($sql,$empresa);
	    return $result;
	}

	

	function datos_empresa_sucursal_usuario($idUsuario,$id_empresa)
	{
		$sql = "SELECT * 
		FROM USUARIOS U 
		LEFT JOIN sucursales S ON U.serie = S.serie_s 
		INNER JOIN EMPRESAS E ON S.empresa = E.id_empresa 
		WHERE id_usuarios = '".$idUsuario."'
		AND S.empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."'";
		// print_r($sql);die();
		$result = $this->db->datos($sql);
	    return $result;

	}

	function articulos($query=false,$ref=false,$categoria=false,$tipo=false,$id_empresa)
	{
		$sql= "SELECT id_productos,referencia,P.nombre,stock,precio_uni,peso,uni_medida,marca,modelo,C.nombre as 'categoria',inventario,iva,codigo_aux,nombre_sucursal,foto
		FROM productos P
		INNER JOIN categoria C ON P.categoria = C.id_categoria
 		LEFT JOIN sucursales Su ON P.sucursal = Su.id_sucursal
		WHERE id_empresa = '".$id_empresa."'";
		if($query)
		{
			$sql.=" AND P.nombre LIKE '%".$query."%'";
		}
		if($ref)
		{
			$sql.=" AND referencia LIKE '%".$ref."%'";
		}
		if($tipo=='P')
		{
			$sql.=" AND inventario ='1' ";
		}else
		{
			$sql.=" AND inventario ='0' ";
		}
		if($categoria)
		{
			$sql.=" AND P.categoria = '".$categoria."'";
		}
		if(isset($_SESSION['INICIO']['SUCURSAL']) && $_SESSION['INICIO']['SUCURSAL']!='')
		{
			$sql.=" AND sucursal='".$_SESSION['INICIO']['SUCURSAL']."' ";
		}
		$sql.= '  ORDER BY id_productos DESC LIMIT 50;';

		print_r($sql);die();
		$result = $this->db->datos($sql,$id_empresa);
	    return $result;
	}

	function articulos_all($id_empresa,$query=false,$ref=false,$categoria=false,$inventario=false,$materia_p=false,$servicios=false,$producto_ter = false)
	{
		$sql= "SELECT id_productos,referencia,P.nombre,stock,precio_uni,peso,uni_medida,marca,modelo,inventario,iva,codigo_aux,nombre_sucursal,foto,max,min
		FROM productos P
 		LEFT JOIN sucursales Su ON P.sucursal = Su.id_sucursal
		WHERE id_empresa = '".$id_empresa."'";
		if($query)
		{
			$sql.=" AND P.nombre LIKE '%".$query."%'";
		}
		if($ref)
		{
			$sql.=" AND referencia LIKE '%".$ref."%'";
		}
		if($inventario)
		{
			$sql.=" AND inventario ='".$inventario."' ";
		}
		if($servicios)
		{
			$sql.=" AND servicio ='".$servicios."' ";
		}
		if($materia_p)
		{
			$sql.=" AND materia_prima =1 ";
		}
		if($producto_ter)
		{
			$sql.=" AND producto_terminado ='".$producto_ter."' ";
		}
		if($categoria)
		{
			$sql.=" AND P.categoria = '".$categoria."'";
		}
		if(isset($_SESSION['INICIO']['SUCURSAL']) && $_SESSION['INICIO']['SUCURSAL']!='')
		{
			$sql.=" AND sucursal='".$_SESSION['INICIO']['SUCURSAL']."' ";
		}
		$sql.= '  ORDER BY id_productos DESC LIMIT 50;';

		// print_r($sql);die();
		$result = $this->db->datos($sql,$id_empresa);
	    return $result;
	}

	function articulos_all2($id_empresa,$query=false,$categoria=false,$inventario=false,$materia_p=false,$servicios=false,$producto_ter = false)
	{
		$sql= "SELECT TOP 100 id_productos,referencia,P.nombre,stock,precio_uni,peso,uni_medida,marca,modelo,C.nombre as 'categoria',inventario,iva,codigo_aux,nombre_sucursal,foto
		FROM productos P
		INNER JOIN categoria C ON P.categoria = C.id_categoria
 		LEFT JOIN sucursales Su ON P.sucursal = Su.id_sucursal
		WHERE 1=1";
		if($query)
		{
			$sql.=" AND (P.nombre LIKE '%".$query."%' OR referencia LIKE '%".$query."%')";
		}
		if($inventario)
		{
			$sql.=" AND inventario ='".$inventario."' ";
		}
		if($servicios)
		{
			$sql.=" AND servicio ='".$servicios."' ";
		}
		if($materia_p)
		{
			$sql.=" AND materia_prima =1 ";
		}
		if($producto_ter)
		{
			$sql.=" AND producto_terminado ='".$producto_ter."' ";
		}
		if($categoria)
		{
			$sql.=" AND P.categoria = '".$categoria."'";
		}
		if(isset($_SESSION['INICIO']['SUCURSAL']) && $_SESSION['INICIO']['SUCURSAL']!='')
		{
			$sql.=" AND sucursal='".$_SESSION['INICIO']['SUCURSAL']."' ";
		}
		$sql.= '  ORDER BY id_productos DESC';

		// print_r($sql);die();
		$result = $this->db->datos($sql);
	    return $result;
	}

	function DCTipoPago($id=false,$codigo=false,$descripcion=false)
	{
   	  $sql = "SELECT Codigo,CONCAT(Codigo,' ',Descripcion) As CTipoPago
         FROM tabla_referenciales_sri
         WHERE Tipo_Referencia = 'FORMA DE PAGO'";
         if($codigo)
         {
         	$sql.=" AND Codigo = '".$codigo."'";
         }
         $sql.=" ORDER BY Codigo ";

	      $result = $this->db->datos($sql);
	         // print_r($result);die();
	        $datos =  array();
	        foreach ($result as $key => $value) {	        		
			$datos[] =array('Codigo'=>$value['Codigo'],'CTipoPago'=>$value['CTipoPago']);	
			 // $datos[] = $row;
		   }
	      return $datos;
	}

	
}


?>