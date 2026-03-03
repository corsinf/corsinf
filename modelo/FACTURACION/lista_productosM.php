<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class lista_productosM  extends BaseModel
{
	
	protected $tabla = 'productos';
    protected $primaryKey = 'id_productos AS _id';

    protected $camposPermitidos = [  
		'referencia',
		'nombre',
		'precio_uni',
		'iva',
		'stock',
		'servicio',
		'inventario',
		'producto_terminado',
		'peso',
		'categoria',
		'codigo_aux',
		'codigo_bar',
		'id_empresa',
		'foto',
		'marca',
		'modelo',
		'uni_medida',
		'color',
		'genero',
		'estado',
		'sucursal',
		'RFID',
		'fecha_creacion',
		'descripcion2',
		'max',
		'min',
		'serie_pro',
		'promocion',
		'materia_prima',
		'paquetes',
		'xpaquetes',
		'sueltos',
		'sueltos',
    ];


	function insertar__($datos,$tabla)
	{
		 $rest = $this->db->inserts('productos',$datos,$_SESSION['INICIO']['ID_EMPRESA']);	   
		return $rest;
	}

	function insertar_($tabla,$datos)
	{
		 $rest = $this->db->inserts($tabla,$datos,$_SESSION['INICIO']['ID_EMPRESA']);	   
		return $rest;
	}

	function update($datos,$tabla,$where)
	{
		 $rest = $this->db->update('productos',$datos,$where,$_SESSION['INICIO']['ID_EMPRESA']);	   
		return $rest;
	}
	function update_($datos,$tabla,$where)
	{
		 $rest = $this->db->update($tabla,$datos,$where,$_SESSION['INICIO']['ID_EMPRESA']);	   
		return $rest;
	}
	function delete($datos,$tabla)
	{
		 $rest = $this->db->delete($tabla,$datos,$_SESSION['INICIO']['ID_EMPRESA']);	   
		return $rest;
		
	}

	function delete_cod($tabla,$datos)
	{
		 $rest = $this->db->sql_string_cod_error($tabla,$datos,$_SESSION['INICIO']['ID_EMPRESA']);
		return $rest;
		
	}

	function detalle_articulos_all($id=false,$id_empresa)
	{
		$sql= "SELECT TOP 10 id_productos as 'id',referencia,codigo_aux,P.nombre,precio_uni as 'precio',stock,iva,inventario,peso,P.categoria as 'idCa',
		CA.nombre as 'categoria',codigo_bar,C.DESCRIPCION as 'color',P.color as 'idCo',M.DESCRIPCION as 'marca',P.marca as 'idMa',
		E.DESCRIPCION as 'estado',P.estado as 'idEs',G.DESCRIPCION as 'genero',P.genero as 'idGe',P.uni_medida,P.modelo,P.fecha_creacion,P.RFID,P.foto,P.sucursal as 'idSu',SU.nombre_sucursal as 'sucursal',max,min,descripcion2 as 'des2',serie_pro,paquetes,xpaquetes,sueltos,servicio,producto_terminado  FROM productos P 
		LEFT JOIN ac_colores C ON P.color = C.ID_COLORES
		LEFT JOIN ac_marcas M ON P.marca = M.ID_MARCA
		LEFT JOIN ac_estado E ON P.estado = E.ID_ESTADO
		LEFT JOIN ac_genero G ON P.genero = G.ID_GENERO
		LEFT JOIN categoria CA ON P.categoria = CA.id_categoria
		LEFT JOIN sucursales SU ON P.sucursal = SU.id_sucursal
		WHERE 1=1 ";
		if($id)
		{
			$sql.=" AND id_productos = '".$id."'";
		}
		
		$sql.= '  ORDER BY id_productos ';
		// print_r($sql);die();
		$result = $this->db->datos($sql,false,false,false);
	    return $result;
	}	

	function detalle_articulos_all_busqueda($id_empresa,$id=false,$query=false,$categoria=false,$limit=true)
	{
		$sql= "SELECT id_productos as 'id',referencia,codigo_aux,P.nombre,precio_uni as 'precio',stock,iva,inventario,peso,P.categoria as 'idCa',
		CA.nombre as 'categoria',codigo_bar,C.DESCRIPCION as 'color',P.color as 'idCo',M.DESCRIPCION as 'marca',P.marca as 'idMa',
		E.DESCRIPCION as 'estado',P.estado as 'idEs',G.DESCRIPCION as 'genero',P.genero as 'idGe',P.uni_medida,P.modelo,P.fecha_creacion,P.RFID,P.foto,P.sucursal as 'idSu',SU.nombre_sucursal as 'sucursal',max,min,descripcion2 as 'des2',serie_pro,paquetes,xpaquetes,sueltos  FROM productos P 
		LEFT JOIN colores C ON P.color = C.ID_COLORES
		LEFT JOIN marcas M ON P.marca = M.ID_MARCA
		LEFT JOIN estado E ON P.estado = E.ID_ESTADO
		LEFT JOIN genero G ON P.genero = G.ID_GENERO
		LEFT JOIN categoria CA ON P.categoria = CA.id_categoria
		LEFT JOIN sucursales SU ON P.sucursal = SU.id_sucursal
		WHERE 1=1 ";
		if($id)
		{
			$sql.=" AND id_productos = '".$id."'";
		}
		if($query)
		{
			$sql.=" AND nombre like '".$query."'";
		}
		if($categoria)
		{
			$sql.=" AND id_categoria = '".$categoria."'";
		}
		$sql.=" AND id_empresa = '".$id_empresa."'";
		
		if($limit)
		{
			$sql.= '  ORDER BY id_productos  LIMIT 10;';
		}
		// print_r($sql);die();
		$result = $this->db->datos($sql,$id_empresa);
	    return $result;
	}	

	function detalle_articulos_ref($ref=false,$id_empresa)
	{
		$sql= "SELECT * FROM productos P 		
		WHERE referencia = '".$ref."'
		AND id_empresa = '".$id_empresa."'";
		
		$sql.= '  ORDER BY id_productos  LIMIT 10;';
		// print_r($sql);die();
		$result = $this->db->datos($sql,$id_empresa);
		// print_r($result);die();
	    return $result;
	}

	function lista_trasnferencia()
	{
		$sql = "SELECT id_transferencias as 'id',id_producto,referencia,P.nombre,T.cantidad,c.nombre as 'categoria' 
		FROM transferencias T 
		INNER JOIN productos P ON T.id_producto = P.id_productos 
		INNER JOIN categoria C ON P.categoria = C.id_categoria
		WHERE id_empresa ='".$_SESSION['INICIO']['ID_EMPRESA']."' AND id_usuario = '".$_SESSION['INICIO']['ID_USUARIO']."'";
		return $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
	}	

	function eliminar_transferencia($id)
	{
		// $sql = "DELETE FROM transferencias WHERE id_transferencias ='".$id."'";
		$datos[0]['campo']='id_transferencias';
		$datos[0]['dato']=$id;
		return $this->db->delete('transferencias',$datos,$_SESSION['INICIO']['ID_EMPRESA']);
		// return $this->db->sql_string($sql,$_SESSION['INICIO']['ID_EMPRESA']);
	}

	function buscar_en_localizacion($destino,$referencia)
	{
		$sql = "SELECT * FROM productos WHERE id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."' AND sucursal = '".$destino."' AND referencia = '".$referencia."'";
		return $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
	}
	function kit_tamanio($id)
    {
    	$sql = "SELECT id_tamanio as 'id', nombre,precio FROM tamanio WHERE id_producto = '".$id."'";
    	return $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
    }

    function kit_tamanio_datos($id)
    {
    	$sql = "SELECT id_tamanio as 'id', nombre,precio,id_producto FROM tamanio WHERE id_tamanio = '".$id."'";
    	return $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
    }

    function kit_tamanio_delete($id)
    {
    	// $sql = "DELETE FROM tamanio WHERE id_tamanio = '".$id."'";
    	$datos[0]['campo']='id_tamanio';
		$datos[0]['dato']=$id;
    	return $this->db->delete('tamanio',$datos,$_SESSION['INICIO']['ID_EMPRESA']);
    	// return $this->db->sql_string($sql,$_SESSION['INICIO']['ID_EMPRESA']);
    }

    function kit_materia_prima($id)
    {
    	$sql = "SELECT id_recetas as 'id',R.id_producto,id_materia_prima,nombre,R.cantidad,R.peso FROM recetas R
    	INNER JOIN productos MP ON R.id_materia_prima = MP.id_productos
    	WHERE id_producto = '".$id."' ";
    	return $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);

    }

   	function adicionales($id)
    {
    	$sql = "SELECT id_combo as 'id',P.nombre,id_producto_add as 'id_p_add' FROM combo C
    	INNER JOIN productos P ON C.id_producto_add = P.id_productos
    	WHERE id_producto = '".$id."'";
    	return $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
    }

    function adicionales_datos($id)
    {
    	$sql = "SELECT id_tamanio as 'id', nombre,precio,id_producto FROM tamanio WHERE id_tamanio = '".$id."'";
    	return $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
    }

    function adicionales_delete($id)
    {
    	// $sql = "DELETE FROM combo WHERE id_combo = '".$id."'";
    	$datos[0]['campo']='id_combo';
		$datos[0]['dato']=$id;		
    	return $this->db->delete('combo',$datos,$_SESSION['INICIO']['ID_EMPRESA']);
    	// return $this->db->sql_string($sql,$_SESSION['INICIO']['ID_EMPRESA']);
    }

    function materia_delete($id)
    {
    	// $sql = "DELETE FROM recetas WHERE id_recetas = '".$id."'";
    	$datos[0]['campo']='id_recetas';
		$datos[0]['dato']=$id;		
    	return $this->db->delete('recetas',$datos,$_SESSION['INICIO']['ID_EMPRESA']);

    	// return $this->db->sql_string($sql,$_SESSION['INICIO']['ID_EMPRESA']);
    }

    function categorias($query=false)
    {
    	$sql = "SELECT * FROM categoria WHERE empresa='".$_SESSION['INICIO']['ID_EMPRESA']."'";
    	if($query)
    	{
    		$sql.=" AND nombre = '".$query."'";
    	}
    	return $this->db->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);

    }


	
}


?>