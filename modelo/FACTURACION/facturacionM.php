<?php
require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class facturacionM extends BaseModel
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
				'estado_pago'

    ];

	
  function lista_facturas($query=false,$empresa='1')
	{
		$sql= "SELECT id_factura as 'id',num_factura as 'num',fecha,C.nombre,estado_factura,total  
		FROM facturas F 
		INNER JOIN cliente C ON F.id_cliente = C.id_cliente 
		WHERE F.id_empresa = '".$empresa."' ";
		if($query)
		{
			if(is_numeric($query))
			{
				$sql.=" AND num_factura LIKE '%".$query."%'";
			}else
			{
				$sql.=" AND C.nombre LIKE '%".$query."%'";
			}
		}		
		$sql.=" ORDER BY F.fecha DESC";
		$result = $this->db->datos($sql,$empresa);
	    return $result;
	}

	function linea_facturas($id,$id_empresa)
	{
		$sql= "SELECT id_lineas,producto,cantidad,LF.precio_uni,LF.iva,foto,total,subtotal,descuento FROM lineas_factura LF 
		INNER JOIN productos P ON LF.referencia = P.referencia
		WHERE id_factura = '".$id."' ORDER BY id_lineas DESC";
		$result = $this->db->datos($sql,$id_empresa);
	    return $result;
	}
	function linea_facturas_all($id,$id_empresa)
	{
		$sql= "SELECT * FROM lineas_factura LF 
		WHERE id_factura = '".$id."' 
		ORDER BY id_lineas DESC";
		$result = $this->db->datos($sql,$id_empresa);
	    return $result;
	}

	function linea_detalle($id,$id_empresa)
	{
		$sql= "SELECT id_lineas as 'id',producto, precio_uni as 'precio',cantidad,iva,descuento,porc_descuento,total,subtotal FROM lineas_factura 
		WHERE id_lineas ='".$id."' 
		AND id_empresa = '".$id_empresa."'
		ORDER BY id_lineas DESC";
		$result = $this->db->datos($sql,$id_empresa);
	    return $result;
	}

	function articulos($query,$empresa,$category=false)
	{
		$sql= "SELECT id_productos as 'id',nombre,precio_uni,foto,iva FROM productos WHERE id_empresa = '".$empresa."' and nombre LIKE '%".$query."%' ";
		if($category){
			$sql.=" AND categoria = '".$category."'";
		}
		$sql.= '  ORDER BY id_productos  LIMIT 10;';
		// print_r($sql);die();
		$result = $this->db->datos($sql,$empresa);
	    return $result;
	}

	function articulos2($query,$empresa,$category=false)
	{
		$sql= "SELECT id_productos as 'id',nombre,precio_uni,foto,iva,stock,referencia 
		FROM productos 
		WHERE id_empresa = '".$empresa."' AND materia_prima = 0 AND producto_terminado = 1 ";
		if($query)
		{
		  $sql.=" AND  CONCAT(referencia,nombre) LIKE '%".$query."%' ";
		}
		if($category){
			$sql.=" AND categoria = '".$category."'";
		}
		$sql.= ' ORDER BY id_productos LIMIT 10';
		// print_r($sql);die();
		$result = $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
	    return $result;
	}

	function articulos_id($query,$empresa,$category=false)
	{
		$sql= "SELECT id_productos as 'id',nombre,precio_uni,foto,iva,referencia FROM productos WHERE id_empresa = '".$empresa."' and id_productos = '".$query."' ";
		if($category){
			$sql.=" AND categoria = '".$category."'";
		}
		$sql.= ' ORDER BY id_productos LIMIT 10;';
		// print_r($sql);die();
		$result = $this->db->datos($sql,$empresa);
	    return $result;
	}

	function cliente_factura($id,$id_empresa)
	{
		$sql="SELECT C.id_cliente,nombre,mail,C.telefono,C.direccion,ci_ruc,num_factura,serie,valor_iva,Autorizacion,fecha,estado_factura,Tipo_pago,datos_adicionales FROM facturas 
			INNER JOIN cliente C ON facturas.id_cliente = C.id_cliente
			INNER JOIN empresa ON facturas.id_empresa = empresa.id_empresa
			WHERE facturas.id_factura = '".$id."'";
			$result = $this->db->datos($sql,$id_empresa);
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

	      $result = $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
	         // print_r($result);die();
	        $datos =  array();
	        foreach ($result as $key => $value) {	        		
			$datos[] =array('Codigo'=>$value['Codigo'],'CTipoPago'=>$value['CTipoPago']);	
			 // $datos[] = $row;
		   }
	      return $datos;
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

	 function buscar_facturas($empresa,$numero=false)
	{
		$sql= "SELECT id_factura as 'id',num_factura as 'num',fecha,C.nombre,estado_factura  
		FROM facturas F 
		INNER JOIN cliente C ON F.id_cliente = C.id_cliente 
		WHERE F.id_empresa = '".$empresa."' ";
		if($numero)
		{			
			$sql.=" AND num_factura = '".$numero."'";			
		}
		
		$sql.=" ORDER BY F.fecha DESC";

		// print_r($sql);die();
		$result = $this->db->datos($sql);
	    return $result;
	}

	function categorias($empresa,$query=false)
	{
		$sql= "SELECT id_categoria as 'id',nombre,imagen 
		FROM categoria 
		WHERE estado ='A' AND empresa = '".$empresa."' ";
		if($query)
		{
			$sql.=" AND nombre LIKE '%".$query."%'";
		}
		$result = $this->db->datos($sql,$empresa);
	    return $result;
	}

	function datos_empresa_sucursal_usuario($idUsuario,$id_empresa)
	{
		$sql = "SELECT * FROM usuario U
		INNER JOIN empresa E ON U.id_empresa = E.id_empresa
		LEFT JOIN sucursales S ON U.sucursal = S.id_sucursal 
		WHERE id_usuario = '".$idUsuario."'";
		// print_r($sql);die();
		$result = $this->db->datos($sql,$id_empresa);
	    return $result;

	}
	function datos_rimpe($ruc)
    {
    	$sql = "SELECT * FROM lista_tipo_contribuyente WHERE RUC ='".$ruc."'";
    	// print_r($sql);die();
    	return $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
    }

    function DCCiudad($query=false)
  	{  
    	$sql = "SELECT Descripcion_Rubro 
         FROM tabla_naciones 
         WHERE TR = 'C'";
         if($query)
          {
            $sql.=" and Descripcion_Rubro like '%".$query."%'";
          } 
         $sql.="ORDER BY Descripcion_Rubro ";
    	 $respuest  = $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
     	 return $respuest;
     
  	}
  	function AdoPersonas($query)
	{             
	    $sql = "SELECT *
	        FROM cliente 
	        WHERE tipo IN ('C','R') AND id_empresa='".$_SESSION['INICIO']['ID_EMPRESA']."'";
	        if($query)
	         {
	            $sql.=" and nombre like '%".$query."%'";
	         } 
	     $sql.=" ORDER BY nombre";

	     // print_r($sql);die();
	     $respuest  = $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
	     return $respuest;
	     
	}

	function guia_remision($serie,$factura)
	{
		$sql = "SELECT * FROM guia_remision 
		WHERE id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."' 
		AND serie = '".$serie."' 
		AND factura = '".$factura."'";
		$respuest  = $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
	    return $respuest;
	}
	
}


?>