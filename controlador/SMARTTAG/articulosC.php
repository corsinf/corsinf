<?php 
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/articulosM.php');
require_once(dirname(__DIR__, 2) .'/lib/Reporte_pdf.php');
require_once(dirname(__DIR__, 2) .'/lib/Reporte_excel.php');
require_once(dirname(__DIR__, 2) .'/modelo/punto_ventaM.php');

/**
 * 
 */$controlador = new articulosC();

if(isset($_GET['Articulos']))
{
	// print_r($_POST);die();
	$query = $_POST['parametros'];
	echo json_encode($controlador->cargar_articulos($query));
}

if(isset($_GET['Articulos_ddl']))
{
	// print_r($_GET);die();
	$query = array();
	if(isset($_GET['q']))
	{
		$query['detalle_like'] = $_GET['q'];
	}
	
	echo json_encode($controlador->cargar_articulos_ddl($query));
}

if(isset($_GET['materia_ddl']))
{
	// print_r($_GET);die();
	$query = array();
	if(isset($_GET['q']))
	{
		$query['detalle_like'] = $_GET['q'];
	}
	
	echo json_encode($controlador->cargar_materia_ddl($query));
}

if(isset($_GET['add_prima']))
{
	// print_r($_POST);die();
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_prima($parametros));
}
if(isset($_GET['etiqueta']))
{
	// print_r($_GET);die();
	$id = $_GET['id'];
	echo json_encode($controlador->etiqueta($id));
}

if(isset($_GET['etiqueta_m']))
{
	// print_r($_GET);die();
	$id = $_GET['id'];
	echo json_encode($controlador->etiqueta_m($id));
}

if(isset($_GET['editar_prima']))
{
	// print_r($_POST);die();
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->editar_prima($parametros));
}
if(isset($_GET['materia']))
{
	// print_r($_POST);die();
	$query = $_POST['parametros'];
	echo json_encode($controlador->cargar_materia($query));
}
if(isset($_GET['materia_produccion']))
{
	// print_r($_POST);die();
	$query = $_POST['parametros'];
	echo json_encode($controlador->cargar_materia_produccion($query));
}
if(isset($_GET['Articulos_detalle']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->detalle_articulo($parametros));
}
if(isset($_GET['Articulos_detalle_materia']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->detalle_articulo_materia($parametros));
}
if(isset($_GET['guardar_editar']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_editar($parametros));
}
if(isset($_GET['guardar_editar_materia']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_editar_materia($parametros));
}

if(isset($_GET['Articulos_imagen']))
{
	$parametros = $_POST;
	echo json_encode($controlador->agregar_articulo_foto($_FILES,$_POST));
}

if(isset($_GET['Articulos_imagen_m']))
{
	$parametros = $_POST;
	echo json_encode($controlador->agregar_articulo_foto($_FILES,$_POST,'m'));
}

if(isset($_GET['categoria']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->categorias($query));
}

if(isset($_GET['filtro_categoria']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->categorias($query));
} 
if(isset($_GET['proveedor']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->proveedor($query));
} 
if(isset($_GET['bodegas']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->bodegas($query));
}
if(isset($_GET['bodegas_materia']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->bodegas_materia($query));
}
if(isset($_GET['auto_material']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->auto_material($query));
}
if(isset($_GET['filtro_bodega']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->bodegas($query));
}

if(isset($_GET['reporte_all_articulos']))
{
	$parametros = array(
		'detalle_like'=>$_GET['detalle_like'],
		'bodega'=>$_GET['bodega'],
		'categoria'=>$_GET['categoria_f']
	);
	echo json_encode($controlador->reporte($parametros));
}

if(isset($_GET['reporte_inventario']))
{
	$parametros = array(
		'bodega'=>$_GET['bodega'],
		'categoria'=>$_GET['categoria_f']
	);
	echo json_encode($controlador->reporte_inventario($parametros));
}

if(isset($_GET['reporte_inventario_mat']))
{
	echo json_encode($controlador->reporte_inventario_materia());
}

if(isset($_GET['Articulos_ingreso']))
{
	echo json_encode($controlador->cargar_articulos_ingreso());
}
if(isset($_GET['Articulos_ingreso_mat']))
{
	echo json_encode($controlador->cargar_articulos_ingreso_mat());
}
if(isset($_GET['producto_modal']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cargar_productos_modal($query));
}
if(isset($_GET['materia_modal']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cargar_materia_modal($query));
}

if(isset($_GET['producto_transferencia']))
{
	$query = '';
	$bodega = $_GET['bodega'];
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->productos_x_bodega($query,$bodega));
}


if(isset($_GET['ingresar_lista']))
{
	$parametros = $_POST;
	echo json_encode($controlador->cargar_articulos_lista($parametros));
}
if(isset($_GET['ingresar_lista_mat']))
{
	$parametros = $_POST;
	echo json_encode($controlador->cargar_articulos_lista_mat($parametros));
}

if(isset($_GET['eliminar_stock']))
{
	$id = $_POST['num'];
	echo json_encode($controlador->eliminar_stock($id));
}

if(isset($_GET['eliminar_Articulo']))
{
	$id = $_POST['num'];
	echo json_encode($controlador->eliminar_articulo($id));
}
if(isset($_GET['eliminar_prima']))
{
	$id = $_POST['num'];
	echo json_encode($controlador->eliminar_prima($id));
}

if(isset($_GET['ingresar_stock']))
{
	$parametros = $_POST;
	echo json_encode($controlador->ingresar_stock());
}

if(isset($_GET['ingresar_stock_mat']))
{
	$parametros = $_POST;
	echo json_encode($controlador->ingresar_stock_mat());
}


if(isset($_GET['ingresar_categoria']))
{
	$parametros = $_POST['cate'];
	echo json_encode($controlador->ingresar_categoria($parametros));
}

if(isset($_GET['add_transferencia']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_transferencia($parametros));
}
if(isset($_GET['tbl_trans']))
{
	echo json_encode($controlador->cargar_trans());
}
if(isset($_GET['eliminar_trans']))
{
	$parametros = $_POST['id'];
	echo json_encode($controlador->eliminar_trans($parametros));
}
if(isset($_GET['transferir_art']))
{
	// $parametros = $_POST['id'];
	echo json_encode($controlador->transferir_art());
}
if(isset($_GET['generar_cod']))
{
	// $parametros = $_POST['id'];
	echo json_encode($controlador->referencia_codigo_producto());
}
if(isset($_GET['generar_cod_ma']))
{
	// $parametros = $_POST['id'];
	echo json_encode($controlador->referencia_codigo_material());
}

class articulosC
{
	private $modelo;
	private $pagina;
	private $global;
	private $pdf;
	private $facturacion;
	private $excel;

	
	function __construct()
	{
		$this->modelo = new articulosM();
		$this->pagina = new codigos_globales();
		$this->pagina->registrar_pagina_creada('../vista/articulos.php','Articulos','2','estado');
		$this->facturacion = new punto_ventaM();
		$this->pdf = new Reporte_pdf(); 
		$this->excel = new Reporte_excel(); 
	}

  // revuelve tabla de articulos 
	function cargar_articulos($parametros)
	{


		$datos = $this->modelo->cargar_articulos($parametros);
		// print_r($datos);die();
		$cabecera = array('Detalle','Categoria','Precio','Stock','Foto','Bodega','Peso','Talla');
		//opciones seran los botones seran los que se agregaran linea por linea en cada fila
		// $botones[0]['boton']='Editar';
		// $botones[0]['icono']='<i class="fas fa-pen nav-icon"></i>';
		// $botones[0]['tipo']='primary';
		// $botones[0]['id']='id';
		$botones[1]['boton']='Eliminar';
		$botones[1]['icono']='<i class="fas fa-trash nav-icon"></i>';
		$botones[1]['tipo']='danger';	
		$botones[1]['id']='id';

		$foto[0] = array('foto','100px','100px');

		$ocultar = array('idcat','gramo','modificacion','produccion','costo','id','ref','bodega','sarta','forma','color');
		$enlace[0] = array('posicion'=>2,'link'=>'detalle_articulo.php','get'=>array('nombre'=>'articulo','valor'=>'id'));
		

		//el primer id es el nombre de la matriz el segundo es de la consulta sql
		// $chek=array('id'=>'id');

		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones=false,false,$ocultar,$foto,false,$enlace);

		// print_r($tabla);die();

		return $tabla;
	}

	// carga articulos en forma de lista dropdowlist
	function cargar_articulos_ddl($parametros)
	{
		$datos = $this->modelo->cargar_articulos($parametros);
		$ddl = array();
		foreach ($datos as $key => $value) {
			$ddl[] = array('id'=>$value['id'],'text'=>$value['detalle']);
		}
		
		return $ddl;
	}

	// carga articulos de materia prima en forma de tabla
	function cargar_materia($parametros)
	{


		$datos = $this->modelo->cargar_materia($parametros);
		// print_r($datos);die();
		$cabecera = array('Codigo / Referencia','Detalle','Precio','Stock','Foto','Bodega','Paquetes','uni/paquetes');
		//opciones seran los botones seran los que se agregaran linea por linea en cada fila
		// $botones[0]['boton']='Editar materia prima';
		// $botones[0]['icono']='<i class="fas fa-pen nav-icon"></i>';
		// $botones[0]['tipo']='primary';
		// $botones[0]['id']='id';
		$botones[1]['boton']='Eliminar';
		$botones[1]['icono']='<i class="fas fa-trash nav-icon"></i>';
		$botones[1]['tipo']='danger';	
		$botones[1]['id']='id';

		$foto[0] = array('foto','100px','100px');

		$ocultar = array('idcat','gramo','modificacion','produccion','costo','cate','idbo','id','unidad_medida','sarta','forma','color');
		$enlace[0] = array('posicion'=>2,'link'=>'detalle_materia.php','get'=>array('nombre'=>'materia','valor'=>'id'));
		

		//el primer id es el nombre de la matriz el segundo es de la consulta sql
		// $chek=array('id'=>'id');

		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones=false,false,$ocultar,$foto,false,$enlace);
		// $tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones=false,false,$ocultar,$foto=false,$posicion=false,$enlace);

		// print_r($tabla);die();

		return $tabla;
	}

	// carga materia prima en forma de lsita dropdownlist
	function cargar_materia_ddl($parametros)
	{
		$datos = $this->modelo->cargar_materia($parametros);
		$ddl = array();
		foreach ($datos as $key => $value) {
			$ddl[] = array('id'=>$value['id'],'text'=>$value['detalle']);
		}
		
		return $ddl;
		
	}


 // carga materia prima registrada en tabla produccion
	function cargar_materia_produccion($parametros)
	{
		$datos = $this->modelo->cargar_materia_produccion($parametros);
		$html = '';
		foreach ($datos as $key => $value) {
			$html.='<tr><td><input class="form-control form-control-sm" id="cantPrima_'.$value['id'].'" value=\''.$value['cantidad'].'\'></td>
			            <td><input class="form-control form-control-sm" value=\''.$value['material'].'\' readonly=""></td>
			            <td>
			            <button class="btn btn-sm btn-primary" onclick="Editar_pro(\''.$value['id'].'\')" title="Editar"><i class="fas fa-pen nav-icon"></i></button>
			            <button class="btn btn-sm btn-danger" onclick="Eliminar_pro(\''.$value['id'].'\')" title="Eliminar"><i class="fas fa-trash nav-icon"></i></button></td></tr>';
		}
		
		return $html;
	}


	// carga lista de articulos en forma de tabla que se hayan registrado en stock para su ingreso
	function cargar_articulos_ingreso()
	{


		$datos = $this->modelo->cargar_articulos_stock();
		// print_r($datos);die();
		// $botones[0]['boton']='Editar';
		// $botones[0]['icono']='<i class="fas fa-save nav-icon"></i>';
		// $botones[0]['tipo']='primary';
		// $botones[0]['id']='id';
		// $botones[1]['boton']='Eliminar';
		// $botones[1]['icono']='<i class="fas fa-trash nav-icon"></i>';
		// $botones[1]['tipo']='danger';	
		// $botones[1]['id']='id';
		$botones[0] =array('boton'=>'Eliminar linea','icono'=>'<i class="fas fa-trash nav-icon"></i>','tipo'=>'danger','id'=>'id');
		$cabecera = array('#','Referencia','Detalle','Cantidad','Precio','Iva','Total');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar=false,$foto=false);

		return $tabla;
	}


	// carga lista de articulos de materia prima en forma de tabla que se hayan registrado en stock para su ingreso
	function cargar_articulos_ingreso_mat()
	{


		$datos = $this->modelo->cargar_articulos_stock_mat();
		// print_r($datos);die();
		// $botones[0]['boton']='Editar';
		// $botones[0]['icono']='<i class="fas fa-save nav-icon"></i>';
		// $botones[0]['tipo']='primary';
		// $botones[0]['id']='id';
		// $botones[1]['boton']='Eliminar';
		// $botones[1]['icono']='<i class="fas fa-trash nav-icon"></i>';
		// $botones[1]['tipo']='danger';	
		// $botones[1]['id']='id';
		$botones[0] =array('boton'=>'Eliminar linea','icono'=>'<i class="fas fa-trash nav-icon"></i>','tipo'=>'danger','id'=>'id');
		$cabecera = array('#','Referencia','Detalle','Cantidad','Precio','Iva','Total');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar=false,$foto=false);

		return $tabla;
	}

	// carga lista de articulos a trasnferir entre bodegas en forma de tabla 
	function cargar_trans()
	{
		// print_r('expression');die();
		$datos = $this->modelo->tabla_transferencias();
		// print_r($datos);die();
		$ocultar =array('id');
		$botones[0] =array('boton'=>'Eliminar linea stock','icono'=>'<i class="fas fa-trash nav-icon"></i>','tipo'=>'danger','id'=>'id');
		$cabecera = array('Producto','cant','Sale de ','Entra en');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;

	}

	// guarda datos de articulo temporalmente para realizar ingreso de productos
	function cargar_articulos_lista($parametros)
	{
		   $producto = explode('-', $parametros['ddl_producto_modal_ing']);
		    $datos[0]['campo']='id_producto';
		    $datos[0]['dato']=$producto[0];
		    $datos[1]['campo']='cantidad';
		    $datos[1]['dato']=$parametros['txt_canti_modal_ing'];
		    $datos[2]['campo']='precio';
		    $datos[2]['dato']=$parametros['txt_precio_modal_ing'];
		    $datos[3]['campo']='iva';
		    $datos[3]['dato']=$parametros['txt_iva_modal_ing'];
		    $datos[4]['campo']='total';
		    $datos[4]['dato']=$parametros['txt_total_modal_ing'];
		    $datos[5]['campo']='num_orden';
		    $datos[5]['dato']=$parametros['txt_orden_modal_ing'];
		    $datos[6]['campo']='id_usuario';
		    $datos[6]['dato']=$_SESSION['INICIO']['ID'];
		    $datos[7]['campo']='id_bodegas';
		    $datos[7]['dato']=$parametros['ddl_bodega'];
		    $datos[8]['campo']='id_proveedor';
		    $datos[8]['dato']=$parametros['ddl_proveedor_modal_ing'];
		    $datos[9]['campo']='referencia';
		    $datos[9]['dato']=$parametros['txt_referencia_modal_ing'];    
				$datoI[10]['campo']='procedencia';
				$datoI[10]['dato']= $parametros['txt_procedencia'];
		    $rep =  $this->modelo->guardar($datos,'ASIENTO_K');
		   return $rep;

	}

	// guarda productos temporalmente de materia prima para realizar el ingreso
	function cargar_articulos_lista_mat($parametros)
	{
		// print_r($parametros);die();
		   $producto = explode('-', $parametros['ddl_materia_modal_ing']);
		    $datos[0]['campo']='id_producto';
		    $datos[0]['dato']=$producto[0];
		    $datos[1]['campo']='cantidad';
		    $datos[1]['dato']=$parametros['txt_canti_modal_ing_mat'];
		    $datos[2]['campo']='precio';
		    $datos[2]['dato']=$parametros['txt_precio_modal_ing_mat'];
		    $datos[3]['campo']='iva';
		    $datos[3]['dato']=$parametros['txt_iva_modal_ing_mat'];
		    $datos[4]['campo']='total';
		    $datos[4]['dato']=$parametros['txt_total_modal_ing_mat'];
		    $datos[5]['campo']='num_orden';
		    $datos[5]['dato']=$parametros['txt_orden_modal_ing_mat'];
		    $datos[6]['campo']='id_usuario';
		    $datos[6]['dato']=$_SESSION['INICIO']['ID'];
		    $datos[7]['campo']='id_bodegas';
		    $datos[7]['dato']=$parametros['ddl_bodega_materia'];		    
		    $datos[8]['campo']='materia_prima';
		    $datos[8]['dato']=1;    
		    $datos[9]['campo']='id_proveedor';
		    $datos[9]['dato']=$parametros['ddl_proveedor'];
		    $datos[10]['campo']='referencia';
		    $datos[10]['dato']=$parametros['txt_referencia_modal_ing_mat'];
		    $datos[11]['campo']='procedencia';
				$datos[11]['dato']= $parametros['txt_procedencia_mat'];
		    
		    
		    $rep =  $this->modelo->guardar($datos,'ASIENTO_K');
		   return $rep;

	}

	// devuelve todos los detalle de producto buscado
	function detalle_articulo($parametros)
	{
		$datos = $this->modelo->cargar_articulos($parametros);
		if(!file_exists($datos[0]['foto']))
		{
			$datos[0]['foto'] = '../img/de_sistema/sin_imagen.png';
		}

		// print_r($datos[0]);die();
		return $datos;
	}

	// devuelve todos los detalle de materia prima buscada
	function detalle_articulo_materia($parametros)
	{
		$datos = $this->modelo->cargar_materia($parametros);
		// print_r($datos);die();
		if(!file_exists($datos[0]['foto']))
		{
			$datos[0]['foto'] = '../img/de_sistema/sin_imagen.jpg';
		}

		// print_r($datos[0]);die();
		return $datos;
	}

	// guarda ingreso de articulo,guarda registro en kardex,guardad en transacciones de un producto
	function guardar_editar($parametros)
	{
		// print_r($parametros);die();
		if($parametros['txt_id1']=='')
		{
			  $datos[0]['campo']='detalle_producto';
		    $datos[0]['dato']=$parametros['txt_detalle'];
		    $datos[1]['campo']='precio_producto';
		    $datos[1]['dato']=$parametros['txt_precio'];
		    $datos[2]['campo']='stock_producto';
		    $datos[2]['dato']=$parametros['txt_stock'];
		    $datos[3]['campo']='id_categoria';
		    $datos[3]['dato']=$parametros['ddl_categoria'];
		    $datos[4]['campo']='referencia_producto';
		    $datos[4]['dato']=$parametros['txt_referencia'];
		    $datos[5]['campo']='peso';
		    $datos[5]['dato']=$parametros['txt_peso'];
		    $datos[6]['campo']='gramo';
		    $datos[6]['dato']=$parametros['txt_gramo'];
		    $datos[7]['campo']='costo';
		    $datos[7]['dato']=$parametros['txt_costo'];
		    $datos[8]['campo']='produccion';
		    $datos[8]['dato']=$parametros['txt_produccion'];
		    $datos[9]['campo']='talla';
		    $datos[9]['dato']=$parametros['txt_talla'];
		    $datos[10]['campo']='modificacion';
		    $datos[10]['dato']=$parametros['txt_modificado'];

		    $rep =  $this->modelo->guardar_editar($datos);
		     $para= array('referencia'=>$parametros['txt_referencia']);
		    $idp= $this->modelo->cargar_articulos($para);
		      //------------------ingreso en kardex----------------
				$existencias = $parametros['txt_stock'];
				$datosI[0]['campo']='id_producto';
				$datosI[0]['dato']=$idp[0]['id'];
				$datosI[1]['campo']='fecha';
				$datosI[1]['dato']=date('Y-m-d');
				$datosI[2]['campo']='entrada';
				$datosI[2]['dato']=$parametros['txt_stock'];
				$datosI[3]['campo']='valor_uni';
				$datosI[3]['dato']=$parametros['txt_precio'];
				$datosI[4]['campo']='valor_total';
				$datosI[4]['dato']=number_format(($parametros['txt_precio']*$existencias),2,'.','');
				$datosI[5]['campo']='existencias';
				$datosI[5]['dato']=number_format($existencias);
				$datosI[7]['campo']='existencias_ant';
				$datosI[7]['dato']= 0;
				$datosI[8]['campo']='id_bodega';
				$datosI[8]['dato']=$parametros['ddl_bodega'];
				$datosI[9]['campo']='procedencia';
				$datosI[9]['dato']= 'KARDEX INICIAL';

				$this->modelo->guardar($datosI,'kardex');

				//-----------------fin de ingreso en kardex-----------
		    if($rep==1)
		    {
		    	$parametros = array('referencia'=>$parametros['txt_referencia']);
		    	$ficha = $this->modelo->cargar_articulos($parametros);
		    	if(count($ficha))
		    	{
		    		return $ficha[0]['id'];
		    	}else
		    	{
		    		return -1;
		    	}
		    }else
		    {
		    	return -1;
		    }

		}else
		{
		    $datos[0]['campo']='detalle_producto';
		    $datos[0]['dato']=$parametros['txt_detalle'];
		    $datos[1]['campo']='precio_producto';
		    $datos[1]['dato']=$parametros['txt_precio'];
		    $datos[2]['campo']='stock_producto';
		    $datos[2]['dato']=$parametros['txt_stock'];
		    $datos[3]['campo']='id_categoria';
		    $datos[3]['dato']=$parametros['ddl_categoria'];
		    $datos[4]['campo']='referencia_producto';
		    $datos[4]['dato']=$parametros['txt_referencia'];
		    $datos[5]['campo']='peso';
		    $datos[5]['dato']=$parametros['txt_peso'];
		    $datos[6]['campo']='gramo';
		    $datos[6]['dato']=$parametros['txt_gramo'];
		    $datos[7]['campo']='costo';
		    $datos[7]['dato']=$parametros['txt_costo'];
		    $datos[8]['campo']='produccion';
		    $datos[8]['dato']=$parametros['txt_produccion'];
		    $datos[9]['campo']='talla';
		    $datos[9]['dato']=$parametros['txt_talla'];
		    $datos[10]['campo']='modificacion';
		    $datos[10]['dato']=$parametros['txt_modificado'];


		    $where[0]['campo']='id_producto';
		    $where[0]['dato'] = $parametros['txt_id1'];

		    // print_r($datos);die();
		    return $this->modelo->guardar_editar($datos,$where);
		}
	}

	// guarda ingreso de articulo,guarda registro en kardex,guardad en transacciones de una materia prima
	function guardar_editar_materia($parametros)
	{
		// print_r($parametros);die();
		if($parametros['txt_id1_m']=='')
		{
			$datos[0]['campo']='detalle_producto';
		    $datos[0]['dato']=$parametros['txt_detalle_m'];
		    $datos[1]['campo']='precio_producto';
		    $datos[1]['dato']=$parametros['txt_precio_m'];
		    $datos[2]['campo']='stock_producto';
		    $datos[2]['dato']=$parametros['txt_stock_m'];
		    $datos[3]['campo']='referencia_producto';
		    $datos[3]['dato']=$parametros['txt_referencia_m'];
		    // $datos[4]['campo']='peso';
		    // $datos[4]['dato']=$parametros['txt_peso_m'];


		    $datos[4]['campo']='fecha_creacion';
		    $datos[4]['dato']=$parametros['txt_fecha_ing_m'];
		    $datos[5]['campo']='paquetes';
		    $datos[5]['dato']=$parametros['txt_gramo_m'];
		    $datos[6]['campo']='uni_paquetes';
		    $datos[6]['dato']=$parametros['txt_costo_m'];
		    $datos[7]['campo']='materia_prima';
		    $datos[7]['dato']=1;
		    $datos[8]['campo']='unidad_medida';
		    $datos[8]['dato']=$parametros['txt_unidad'];
		    $datos[9]['campo']='bodega';
		    $datos[9]['dato']=$parametros['ddl_bodega'];
		    $datos[10]['campo']='color';
		    $datos[10]['dato']=$parametros['txt_color'];
		    $datos[11]['campo']='forma';
		    $datos[11]['dato']=$parametros['txt_forma'];
		    $datos[12]['campo']='sarta';
		    $datos[12]['dato']=0;
		    if($parametros['cbx_ti']=='S')
		    {
		    	 $datos[12]['campo']='sarta';
		       $datos[12]['dato']=1;
		    }

		    $datos[13]['campo']='puntos';
		    $datos[13]['dato']=$parametros['txt_puntos'];

		    $rep =  $this->modelo->guardar_editar($datos);
		    $para= array('referencia'=>$parametros['txt_referencia_m']);
		    $idp= $this->modelo-> cargar_materia($para);
		      //------------------ingreso en kardex----------------
				$existencias = $parametros['txt_stock_m'];
				$datosI[0]['campo']='id_producto';
				$datosI[0]['dato']=$idp[0]['id'];
				$datosI[1]['campo']='fecha';
				$datosI[1]['dato']=date('Y-m-d');
				$datosI[2]['campo']='entrada';
				$datosI[2]['dato']=$parametros['txt_stock_m'];
				$datosI[3]['campo']='valor_uni';
				$datosI[3]['dato']=$parametros['txt_precio_m'];
				$datosI[4]['campo']='valor_total';
				$datosI[4]['dato']=number_format(($parametros['txt_precio_m']*$existencias),2,'.','');
				$datosI[5]['campo']='existencias';
				$datosI[5]['dato']=number_format($existencias);
				$datosI[7]['campo']='existencias_ant';
				$datosI[7]['dato']= 0;
				$datosI[8]['campo']='id_bodega';
				$datosI[8]['dato']=$parametros['ddl_bodega'];
				$datosI[9]['campo']='procedencia';
				$datosI[9]['dato']= 'KARDEX INICIAL';				
				$datosI[10]['campo']='materia_prima';
				$datosI[10]['dato']= '1';

				$this->modelo->guardar($datosI,'kardex');

				//-----------------fin de ingreso en kardex-----------

		    if($rep==1)
		    {
		    	$parametros = array('referencia'=>$parametros['txt_referencia_m']);
		    	$ficha = $this->modelo->cargar_materia($parametros);
		    	if(count($ficha))
		    	{
		    		return $ficha[0]['id'];
		    	}else
		    	{
		    		return -1;
		    	}
		    }else
		    {
		    	return -1;
		    }

		}else
		{
		    $datos[0]['campo']='detalle_producto';
		    $datos[0]['dato']=$parametros['txt_detalle_m'];
		    $datos[1]['campo']='precio_producto';
		    $datos[1]['dato']=$parametros['txt_precio_m'];
		    $datos[2]['campo']='stock_producto';
		    $datos[2]['dato']=$parametros['txt_stock_m'];
		    $datos[3]['campo']='referencia_producto';
		    $datos[3]['dato']=$parametros['txt_referencia_m'];
		    // $datos[4]['campo']='peso';
		    // $datos[4]['dato']=$parametros['txt_peso_m'];

		    $datos[4]['campo']='unidad_medida';
		    $datos[4]['dato']=$parametros['txt_unidad'];
		    $datos[5]['campo']='paquetes';
		    $datos[5]['dato']=$parametros['txt_gramo_m'];
		    $datos[6]['campo']='uni_paquetes';
		    $datos[6]['dato']=$parametros['txt_costo_m'];
		    $datos[7]['campo']='materia_prima';
		    $datos[7]['dato']=1;
		    $datos[8]['campo']='fecha_creacion';
		    $datos[8]['dato']=$parametros['txt_fecha_ing_m'];
		    $datos[9]['campo']='bodega';
		    $datos[9]['dato']=$parametros['ddl_bodega'];
		    $datos[10]['campo']='color';
		    $datos[10]['dato']=$parametros['txt_color'];
		    $datos[11]['campo']='forma';
		    $datos[11]['dato']=$parametros['txt_forma'];
		    $datos[12]['campo']='sarta';
		    $datos[12]['dato']='0';
		    if($parametros['cbx_ti']=='S')
		    {
		    	 $datos[12]['campo']='sarta';
		       $datos[12]['dato']='1';

		    }
		    $datos[13]['campo']='puntos';
		    $datos[13]['dato']=$parametros['txt_puntos'];


		    // print_r($parametros);die();

		    $where[0]['campo']='id_producto';
		    $where[0]['dato'] = $parametros['txt_id1_m'];

		    // print_r($datos);die();
		    return $this->modelo->guardar_editar($datos,$where);
		}
	}


	// sube fotos al servidor de un producto registrando en base de datos su ruta
   function agregar_articulo_foto($file,$post,$tipo=false)
   {
   	// print_r($file);
   	// print_r($post);die();
   	

   	$ruta='../img/';//ruta carpeta donde queremos copiar las imágenes
   	if (!file_exists($ruta)) {
       mkdir($ruta, 0777, true);
    }
    if($file['file']['type']=="image/jpeg" || $file['file']['type']=="image/pjpeg" || $file['file']['type']=="image/gif" || $file['file']['type']=="image/png")
      {
   	     $uploadfile_temporal=$file['file']['tmp_name'];
   	     $tipo = explode('/', $file['file']['type']);
   	     if(isset($post['txt_nom_img']))
   	     {
         $nombre = $post['txt_nom_img'].'.'.$tipo[1];
         }else
         {
         	$nombre = $post['txt_nom_img_m'].'.'.$tipo[1];
         }
        
   	     $nuevo_nom=$ruta.$nombre;
   	     if (is_uploaded_file($uploadfile_temporal))
   	     {
   		     move_uploaded_file($uploadfile_temporal,$nuevo_nom);
   		     $this->pagina->reducir_img($nuevo_nom,$ruta,$nombre,$calidad=20);
   		     if(isset($post['txt_id']))
   		     {
   		     if($post['txt_id']!='')
   		     	{
   		     		$base = $this->modelo->img_guardar($nuevo_nom,$post['txt_id']);
   		     	} else
   		     	{
   		     		$base = $this->modelo->img_guardar($nuevo_nom,'',$post['txt_nom_img']);
   		     	}  
   		     }else
   		     {
   		     	if($post['txt_id_m']!='')
   		     	{
   		     		$base = $this->modelo->img_guardar($nuevo_nom,$post['txt_id_m'],'','m');
   		     	} else
   		     	{
   		     		$base = $this->modelo->img_guardar($nuevo_nom,'',$post['txt_nom_img_m'],'m');
   		     	}  

   		     }		     
   		     if($base==1)
   		     {
   		     	return 1;
   		     }else
   		     {
   		     	return -1;
   		     }

   	     }
   	     else
   	     {
   		     return -1;
   	     } 
     }else
     {
     	return -2;
     }

  }

  // devuelve lsita de categorias en forma de lsita dropdownlist
  function categorias($query)
	{
		$datos = $this->modelo->categorias($query);
		$cta = array();
		foreach ($datos as $key => $value) {
			$cta[] = array('id'=>$value['id'],'text'=>utf8_decode($value['nombre']));			
		}
		return $cta;
	}

	// devuelve en forma de lista dropdownlis de los proveedores
	function proveedor($query)
	{
		$datos = $this->modelo->proveedor($query);
		$cta = array();
		foreach ($datos as $key => $value) {
			$cta[] = array('id'=>$value['id'],'text'=>utf8_decode($value['nombre']));			
		}
		return $cta;
	}
// devuelve en forma de lsita dropdownlis de las bodegas de producto
	 function bodegas($query)
	{
		$datos = $this->modelo->bodegas($query);
		$cta = array();
		foreach ($datos as $key => $value) {
			$cta[] = array('id'=>$value['id'],'text'=>utf8_decode($value['bodega']));			
		}
		return $cta;
	}

	// devuelve en forma de lista dropdownlist de las bodegas de materia prima
	 function bodegas_materia($query)
	{
		$datos = $this->modelo->bodegas_materia($query);
		$cta = array();
		foreach ($datos as $key => $value) {
			$cta[] = array('id'=>$value['id'],'text'=>utf8_decode($value['bodega']));			
		}
		return $cta;
	}

  // genera reporte en pdf de el invwentario de materia prima
	function reporte_inventario_materia($parametros=false)
	{
		$titulo = 'Reporte de todo los articulos';
		$datos = $this->modelo->cargar_materia();
		$sarta = array();
		$dat = array();
		foreach ($datos as $key => $value) {
			if($value['sarta']==1)
			{
				$sarta[]=$value;
			}else
			{
				$dat[] = $value;
			}
		}
		$this->excel->reporte_existencias($dat,$sarta);
	}
  //genera reporte en pdf de inventario de productos 
	function reporte_inventario($parametros)
	{
		$titulo = 'Reporte de todo los articulos';
		$datos = $this->modelo->cargar_articulos($parametros);
		
		$this->excel-> reporte_existencias2($datos);
	}
  // genera reporte en pdf de inventario por filtro
	function reporte($parametros)
	{
		$titulo = 'REPORTE INVENTARIO '.date('Y-m-d');
		$datos = $this->modelo->cargar_articulos($parametros);
		$this->pdf->REPORTE_ARTICULOS($datos,false,false,$titulo);
	}

	// elimina items en ingreso de stock de productos
	function eliminar_stock($id)
	{
		$datos = $this->modelo->eliminar_linea($id);
		return $datos;
	}
	// elimina items de transferencia de bodega
	function eliminar_trans($id)
	{
		$datos = $this->modelo->eliminar_trans($id);
		return $datos;
	}

	// elimina producto de lista de producto
	function eliminar_articulo($id)
	{
		$datos = $this->modelo->eliminar_articulo($id);
		return $datos;
	}
	// elimina item de ingreso de materia prima
	function eliminar_prima($id)
	{
		$datos = $this->modelo->eliminar_prima($id);
		return $datos;
	}

	function cargar_productos_modal($query)
	{
		$parametros = array('detalle_like'=>$query);
		$datos = $this->modelo->cargar_articulos($parametros);
		$pro = array();
		foreach ($datos as $key => $value) {
			$pro[] = array('id'=>$value['id'].'-'.$value['ref'],'text'=>$value['detalle']);
		}

		return $pro;
	}


	function cargar_materia_modal($query)
	{
		$parametros = array('detalle_like'=>$query);
		$datos = $this->modelo->cargar_materia($parametros);
		$pro = array();
		foreach ($datos as $key => $value) {
			$pro[] = array('id'=>$value['id'].'-'.$value['ref'],'text'=>$value['detalle']);
		}

		return $pro;
	}

	function ingresar_stock()
	{
		$datos = $this->modelo->datos_asiento_k();
		$parametros = array('numero'=>$datos[0]['num_orden'],'cli'=>$datos[0]['id_proveedor']);
		$FA = $this->crear_documento($parametros);

	if(count($datos)>0)
	{
		$existe = true;
				foreach ($datos as $key => $value) 
				{
					$parametros = array('referencia'=>$value['referencia'],'bodega'=>$value['id_bodegas']);
					$producto = $this->modelo->cargar_articulos($parametros);
					// print_r($producto);die();
						if(empty($producto))
						{
							$producto[0]['stock'] = 0;
							$producto[0]['bodega'] = $value['id_bodegas'];
							$existe = false;
						}
						// print_r($producto);die();
				    $datos[0]['campo']='id_producto';
				    $datos[0]['dato']=$value['id_producto'];
				    $datos[1]['campo']='entrada';
				    $datos[1]['dato']=$value['cantidad'];
				    $datos[2]['campo']='precio';
				    $datos[2]['dato']= number_format($value['precio'],2,'.','');
				    $datos[3]['campo']='iva';
				    $datos[3]['dato']= number_format($value['iva'],2,'.','');
				    $datos[4]['campo']='total';
				    $datos[4]['dato']= number_format($value['total'],2,'.','');
				    $datos[5]['campo']='num_comprobante';
				    $datos[5]['dato']=$value['num_orden'];
				    $datos[6]['campo']='id_usuario';
				    $datos[6]['dato']=$_SESSION['INICIO']['ID'];
				    $datos[7]['campo']='id_bodega';
				    $datos[7]['dato']=$value['id_bodegas'];
				    $datos[8]['campo']='fecha';
				    $datos[8]['dato']=date('Y-m-d');
				    $rep =  $this->modelo->guardar($datos,'movimientos');


				    $tra = $this->modelo->tipo_transaccion('INGRESO INVENTARIO');	
				    $tran[0]['campo']='id_producto';
				    $tran[0]['dato']=$value['id_producto'];
				    $tran[1]['campo']='cantidad_transferencia';
				    $tran[1]['dato']=$value['cantidad'];
				    $tran[2]['campo']='id_bodega_entrada';
				    $tran[2]['dato']=$value['id_bodegas'];
				    $tran[3]['campo']='id_usuario';
				    $tran[3]['dato']=$_SESSION['INICIO']['ID'];
				    $tran[4]['campo']='fecha';
				    $tran[4]['dato']=date('Y-m-d');
				    $tran[5]['campo']='tipo_transaccion';
				    $tran[5]['dato']=$tra;
				    $tran[6]['campo']='documento';
				    $tran[6]['dato']='INGRESO DE STOCK';
				    $tran[7]['campo']='num_documento';
				    $tran[7]['dato']=$value['num_orden'];
				    $tran[8]['campo']='total';
				    $tran[8]['dato']=$value['total'];
				    // print_r($tran);die();
				    $this->modelo->guardar($tran,'transacciones');


				    //------------------ingreso en kardex----------------
						$existencias =$producto[0]['stock']+$value['cantidad'];

						$datosI[0]['campo']='id_producto';
						$datosI[0]['dato']=$value['id_producto'];
						$datosI[1]['campo']='fecha';
						$datosI[1]['dato']=date('Y-m-d');
						$datosI[2]['campo']='entrada';
						$datosI[2]['dato']=$value['cantidad'];
						$datosI[3]['campo']='valor_uni';
						$datosI[3]['dato']=number_format($value['precio'],2,'.','');
						$datosI[4]['campo']='valor_total';
						$datosI[4]['dato']=number_format($value['total'],2,'.','');
						$datosI[5]['campo']='existencias';
						$datosI[5]['dato']=number_format($existencias,2,'.','');
						$datosI[6]['campo']='factura';
						$datosI[6]['dato']=$FA['id'] ;
						$datosI[7]['campo']='existencias_ant';
						$datosI[7]['dato']= $producto[0]['stock'];
						$datosI[8]['campo']='id_bodega';
						$datosI[8]['dato']= $producto[0]['bodega'];
						$datosI[9]['campo']='procedencia';
						$datosI[9]['dato']= $value['procedencia'];

						$this->modelo->guardar($datosI,'kardex');
						
						//-----------------fin de ingreso en kardex-----------

				    if($rep==-1)
				    {
				    	return -1;
				    }

				    if($existe == true)
				    {
					    $datos1[0]['campo']='stock_producto';
					    $datos1[0]['dato']=$value['cantidad']+$producto[0]['stock'];

					    $where[0]['campo']='id_producto';
					    $where[0]['dato']=$producto[0]['id'];
					    $rep1 =  $this->modelo->update('productos',$datos1,$where);
					    if($rep==-1)
					    {
					    	return -1;
					    }
					  }else
					  {
					  	$parametros = array('id'=>$value['id_producto']);
						  $parametros = $this->modelo->cargar_articulos($parametros);
						  $datos[0]['campo']='detalle_producto';
					    $datos[0]['dato']=$parametros[0]['detalle'];
					    $datos[1]['campo']='precio_producto';
					    $datos[1]['dato']=$parametros[0]['precio'];
					    $datos[2]['campo']='stock_producto';
					    $datos[2]['dato']=$value['cantidad'];
					    $datos[3]['campo']='id_categoria';
					    $datos[3]['dato']=$parametros[0]['idcat'];
					    $datos[4]['campo']='referencia_producto';
					    $datos[4]['dato']=$parametros[0]['ref'];
					    $datos[5]['campo']='peso';
					    $datos[5]['dato']=$parametros[0]['peso'];
					    $datos[6]['campo']='gramo';
					    $datos[6]['dato']=$parametros[0]['gramo'];
					    $datos[7]['campo']='costo';
					    $datos[7]['dato']=$parametros[0]['costo'];
					    $datos[8]['campo']='produccion';
					    $datos[8]['dato']=$parametros[0]['produccion'];
					    $datos[9]['campo']='talla';
					    $datos[9]['dato']=$parametros[0]['talla'];
					    $datos[10]['campo']='modificacion';
					    $datos[10]['dato']=$parametros[0]['modificacion'];
					    $datos[11]['campo']='bodega';
					    $datos[11]['dato']=$value['id_bodegas'];					    
					    $datos[12]['campo']='foto_producto';
					    $datos[12]['dato']=$parametros[0]['foto'];
					    $this->modelo->guardar($datos,'productos');

					  }
				}
		     $this->modelo->eliminar_asiento_K($value['num_orden']);		   			
		return 1;
	}else
	{
		return -2;
	}

	}

// genera una nueva factura
function crear_documento($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo']='tipo_factura';
		$datos[0]['dato']='FA';		
		$datos[1]['campo']='cliente';
		$datos[1]['dato']=$parametros['cli'];
		$datos[2]['campo']='fecha_factura';
		$datos[2]['dato']=date('Y-m-d');
		$datos[3]['campo']='numero_factura';
		$datos[3]['dato']=$parametros['numero'];
		$datos[4]['campo']='fecha_exp';
		$datos[4]['dato']=date('Y-m-d');
		$datos[5]['campo']='tipo_documento';
		$datos[5]['dato']='C';

		$datos[6]['campo']='estado_factura';
		$datos[6]['dato']='F';

		$rep =  $this->modelo->guardar($datos,'facturas');
		$idfac = $this->facturacion->numero_fac($datos[3]['dato'],'C');

		// print_r($idfac);die();
		return  array('id' =>$idfac[0]['id_factura'],'tipo'=>$idfac[0]['tipo_factura']);
	}


  // ingresa materia prima en stock y guarda el movimiento y tipo de transaccion 
	function ingresar_stock_mat()
	{
		$datos = $this->modelo->datos_asiento_k_mat();
		$parametros = array('numero'=>$datos[0]['num_orden'],'cli'=>$datos[0]['id_proveedor']);
	  // print_r($datos);
		// print_r($parametros);die();
		$FA = $this->crear_documento($parametros);

		// print_r($FA);die();

		if(count($datos)>0)
		{

		$existe = true;
		foreach ($datos as $key => $value) {
			$parametros = array('referencia'=>$value['referencia'],'bodega'=>$value['id_bodegas']);
			$producto = $this->modelo->cargar_materia($parametros);
			// print_r($producto);die();
						if(empty($producto))
						{
							$producto[0]['stock'] = 0;
							$producto[0]['idbo'] = $value['id_bodegas'];
							$existe = false;
						}
			// print_r($producto);die();
		    $datos[0]['campo']='id_producto';
		    $datos[0]['dato']=$value['id_producto'];
		    $datos[1]['campo']='entrada';
		    $datos[1]['dato']=$value['cantidad'];
		    $datos[2]['campo']='precio';
		    $datos[2]['dato']=$value['precio'];
		    $datos[3]['campo']='iva';
		    $datos[3]['dato']=$value['iva'];
		    $datos[4]['campo']='total';
		    $datos[4]['dato']=$value['total'];
		    $datos[5]['campo']='num_comprobante';
		    $datos[5]['dato']=$value['num_orden'];
		    $datos[6]['campo']='id_usuario';
		    $datos[6]['dato']=$_SESSION['INICIO']['ID'];
		    $datos[7]['campo']='id_bodega';
		    $datos[7]['dato']=$value['id_bodegas'];
		    $datos[8]['campo']='fecha';
		    $datos[8]['dato']=date('Y-m-d');
		    $rep =  $this->modelo->guardar($datos,'movimientos');


		    $tra = $this->modelo->tipo_transaccion('INGRESO INVENTARIO');	
		    $tran[0]['campo']='id_producto';
		    $tran[0]['dato']=$value['id_producto'];
		    $tran[1]['campo']='cantidad_transferencia';
		    $tran[1]['dato']=$value['cantidad'];
		    $tran[2]['campo']='id_bodega_entrada';
		    $tran[2]['dato']=$value['id_bodegas'];
		    $tran[3]['campo']='id_usuario';
		    $tran[3]['dato']=$_SESSION['INICIO']['ID'];
		    $tran[4]['campo']='fecha';
		    $tran[4]['dato']=date('Y-m-d');
		    $tran[5]['campo']='tipo_transaccion';
		    $tran[5]['dato']=$tra;
		    $tran[6]['campo']='documento';
		    $tran[6]['dato']='INGRESO DE STOCK';
		    $tran[7]['campo']='num_documento';
		    $tran[7]['dato']=$value['num_orden'];
		    $tran[8]['campo']='total';
		    $tran[8]['dato']=$value['total'];

		    // print_r($tran);die();
		    $this->modelo->guardar($tran,'transacciones');

		    //------------------ingreso en kardex----------------
				$existencias =$producto[0]['stock']+$value['cantidad'];

				$datosI[0]['campo']='id_producto';
				$datosI[0]['dato']=$value['id_producto'];
				$datosI[1]['campo']='fecha';
				$datosI[1]['dato']=date('Y-m-d');
				$datosI[2]['campo']='entrada';
				$datosI[2]['dato']=$value['cantidad'];
				$datosI[3]['campo']='valor_uni';
				$datosI[3]['dato']=$value['precio'];
				$datosI[4]['campo']='valor_total';
				$datosI[4]['dato']=number_format($value['total'],2,'.','');
				$datosI[5]['campo']='existencias';
				$datosI[5]['dato']=number_format($existencias);
				$datosI[6]['campo']='factura';
				$datosI[6]['dato']=$FA['id'] ;
				$datosI[7]['campo']='existencias_ant';
				$datosI[7]['dato']= $producto[0]['stock'];
				$datosI[8]['campo']='id_bodega';
				$datosI[8]['dato']= $producto[0]['idbo'];
				$datosI[9]['campo']='procedencia';
				$datosI[9]['dato']= $value['procedencia'];
				$datosI[10]['campo']='materia_prima';
				$datosI[10]['dato']= '1';

				$this->modelo->guardar($datosI,'kardex');

				//-----------------fin de ingreso en kardex-----------

				

		    if($rep==-1)
		    {
		    	return -1;
		    }

		    if($existe==true)
		    {

		    $datos1[0]['campo']='stock_producto';
		    $datos1[0]['dato']=$value['cantidad']+$producto[0]['stock'];

		    $where[0]['campo']='id_producto';
		    $where[0]['dato']=$value['id_producto'];
		    $rep1 =  $this->modelo->update('productos',$datos1,$where);
		    if($rep==-1)
		    {
		    	return -1;
		    }
		  }else
		  {
		  		    $parametros = array('id'=>$value['id_producto']);
						  $parametros = $this->modelo->cargar_articulos($parametros);
						  $datos[0]['campo']='detalle_producto';
					    $datos[0]['dato']=$parametros[0]['detalle'];
					    $datos[1]['campo']='precio_producto';
					    $datos[1]['dato']=$parametros[0]['precio'];
					    $datos[2]['campo']='stock_producto';
					    $datos[2]['dato']=$value['cantidad'];
					    $datos[3]['campo']='id_categoria';
					    $datos[3]['dato']=$parametros[0]['idcat'];
					    $datos[4]['campo']='referencia_producto';
					    $datos[4]['dato']=$parametros[0]['ref'];
					    $datos[5]['campo']='peso';
					    $datos[5]['dato']=$parametros[0]['peso'];
					    $datos[6]['campo']='gramo';
					    $datos[6]['dato']=$parametros[0]['gramo'];
					    $datos[7]['campo']='costo';
					    $datos[7]['dato']=$parametros[0]['costo'];
					    $datos[8]['campo']='produccion';
					    $datos[8]['dato']=$parametros[0]['produccion'];
					    $datos[9]['campo']='talla';
					    $datos[9]['dato']=$parametros[0]['talla'];
					    $datos[10]['campo']='modificacion';
					    $datos[10]['dato']=$parametros[0]['modificacion'];
					    $datos[11]['campo']='bodega';
					    $datos[11]['dato']=$value['id_bodegas'];					    
					    $datos[12]['campo']='foto_producto';
					    $datos[12]['dato']=$parametros[0]['foto'];
					    $this->modelo->guardar($datos,'productos');


		  }   			
		}

		$this->modelo->eliminar_asiento_K($value['num_orden']);	
		return 1;
	}else
	{
		return -2;
	}

	}
// ingresa nuevas categoria
	function ingresar_categoria($categoria)
	{
		if($this->modelo->existente_cate($categoria)==false)
		{
		    $datos[0]['campo']='detalle_categoria';
		    $datos[0]['dato']=$categoria;
		    $rep =  $this->modelo->guardar($datos,'categorias');
		   return $rep;
		}else
		{
			return -2;
		}

	}

 // devuelve stock y nombre de producto que se encuentra en bodega especifica en forma de lsita dropdownlist
	function productos_x_bodega($query,$bodega)
	{
		$datos = $this->modelo->productos_x_bodega($query,$bodega);
		$pro = array();
		foreach ($datos as $key => $value) {
			$pro[] = array('id'=>$value['id'].','.$value['stock'],'text'=>$value['nombre']);
		}

		return $pro;
	}

	// agrega en tabla temporar de transferencias 
	function add_transferencia($parametros)
	{

		// print_r($parametros);die();
		$existe_en_bodega = $this->modelo->existe_trans_datos($parametros['salida'],$parametros['entrada'],$parametros['producto']);

		// print_r($existe_en_bodega);die();
		if($existe_en_bodega)
		{
			$articulo = $this->modelo->existe_trans_datos($parametros['salida'],$parametros['entrada'],$parametros['producto']);
			$datos[0]['campo']='cantidad_transferencia';
		    $datos[0]['dato']=$parametros['cant']+$articulo[0]['cant'];

		    $where[0]['campo']='id_transferencia';
		    $where[0]['dato']=$articulo[0]['id'];

		    return  $this->modelo-> update('transferencias_bodegas_temp',$datos,$where);
		}else
		{
			$datos[0]['campo']='id_producto';
		    $datos[0]['dato']=$parametros['producto'];
		    $datos[1]['campo']='cantidad_transferencia';
		    $datos[1]['dato']=$parametros['cant'];
		    $datos[2]['campo']='id_bodega_salida';
		    $datos[2]['dato']=$parametros['salida'];
		    $datos[3]['campo']='id_bodega_entrada';
		    $datos[3]['dato']=$parametros['entrada'];
		    $datos[4]['campo']='id_usuario';
		    $datos[4]['dato']=$_SESSION['INICIO']['ID']; 
		    return  $this->modelo->guardar($datos,'transferencias_bodegas_temp');
		}
		
	}

	// transfiere datos de tabla temporal trasnfeencia a bodega real
	function transferir_art()
	{
		$datos = $this->modelo->tabla_transferencias_();
		if(count($datos)>0)
		{
			foreach ($datos as $key => $value) {
				// print_r($value);die();
				 $tra = $this->modelo->tipo_transaccion('TRANSFERENCIA');	
				$trns[0]['campo']='id_producto';
				$trns[1]['campo']='cantidad_transferencia';
				$trns[2]['campo']='id_bodega_salida';
				$trns[3]['campo']='id_bodega_entrada';
				$trns[4]['campo']='id_usuario';
				$trns[5]['campo']='fecha';
				$trns[6]['campo']='tipo_transaccion';	
				$trns[7]['campo']='documento';	
				$trns[8]['campo']='num_documento';	
				$trns[0]['dato']=$value['id_producto'];
				$trns[1]['dato']=$value['cantidad_transferencia'];
				$trns[2]['dato']=$value['id_bodega_salida'];
				$trns[3]['dato']=$value['id_bodega_entrada'];
				$trns[4]['dato']=$value['id_usuario'];
				$trns[5]['dato']=date('Y-m-d');
				$trns[6]['dato']= $tra;			
				$trns[7]['dato']='TRANSFERENCIA DE BODEGA';
				$trns[8]['dato']='2';			
				// print_r($trns);die();
				$this->modelo->guardar($trns,'transacciones');

				$pro = $this->modelo->productos($value['id_producto'],false,$value['id_bodega_salida']);
				if(count($pro)>1)
				{
				if($this->modelo->existe_bodega_arti($value['id_bodega_entrada'],$pro[0]['referencia_producto']))
				{
				
					$datos2[0]['campo']='stock_producto';
                    $datos2[0]['dato']=floatval($pro[0]['stock_producto']-$value['cantidad_transferencia']);
                    $where1[0]['campo'] = 'id_producto';
                    $where1[0]['dato'] = $pro[0]['id_producto'];
                    $this->modelo->update('productos',$datos2,$where1);

					$pro1 = $this->modelo->productos(false,$pro[0]['referencia_producto'],$value['id_bodega_entrada']);				
					$datos1[0]['campo']='stock_producto';
                    $datos1[0]['dato']=floatval($pro1[0]['stock_producto']+$value['cantidad_transferencia']);
                    $where[0]['campo'] = 'id_producto';
                    $where[0]['dato'] =$pro1[0]['id_producto'];
                    $this->modelo->update('productos',$datos1,$where);

				}else
				{
                    $datos[0]['campo']='referencia_producto';
                    $datos[1]['campo']='detalle_producto';
                    $datos[2]['campo']='precio_producto';
                    $datos[3]['campo']='foto_producto';
                    $datos[4]['campo']='id_categoria';
                    $datos[5]['campo']='stock_producto';
                    $datos[6]['campo']='fecha_creacion' ;
                    $datos[7]['campo']='maximo' ;
                    $datos[8]['campo']='minimo';
                    $datos[9]['campo']='codBarras' ;
                    $datos[10]['campo']='bodega';
                    $datos[0]['dato']=$pro[0]['referencia_producto'];
                    $datos[1]['dato']=$pro[0]['detalle_producto'];
                    $datos[2]['dato']=$pro[0]['precio_producto'];
                    $datos[3]['dato']=$pro[0]['foto_producto'];
                    $datos[4]['dato']=$pro[0]['id_categoria'];
                    $datos[5]['dato']=$value['cantidad_transferencia'];
                    $datos[6]['dato']=$pro[0]['fecha_creacion']['date'];
                    $datos[7]['dato']=$pro[0]['maximo' ];
                    $datos[8]['dato']=$pro[0]['minimo'];
                    $datos[9]['dato']=$pro[0]['codBarras' ];
                    $datos[10]['dato']=$value['id_bodega_entrada'];


                    $datos1[0]['campo']='stock_producto';
                    $datos1[0]['dato']=floatval($pro[0]['stock_producto']-$value['cantidad_transferencia']);
                    $where[0]['campo'] = 'id_producto';
                    $where[0]['dato'] =$pro[0]['id_producto'];
                    $this->modelo->update('productos',$datos1,$where);
                    $this->modelo->guardar($datos,'productos');
				}
			 }
			}
			$this->modelo->delete_all_transferencias();
			return 1;

		}else
		{
			return -2;
		}

	}
	// devuelve busqueda de autocomplet de materia prima tipo lista dropdownlist
	function auto_material($query)
	{
		$datos = $this->modelo->auto_material($query);
		$rep = array();
		foreach ($datos as $key => $value) {
			$rep[] = array('id'=>$value['id'],'text'=>$value['nombre']);
		}
		// print_r($rep);die();

		return $rep;

	}

	// agrega materia prima a stock
	function add_prima($parametros)
	{
		$exis = $this->modelo->existe_materia_produccion($parametros['materia'],$parametros['articulo']);
		// print_r($exis);die();
		if($exis==false)
		{
		$datos[0]['campo']='cantidad';
		$datos[0]['dato']=$parametros['can'];
		$datos[1]['campo']='id_producto';
		$datos[1]['dato']=$parametros['articulo'];
		$datos[2]['campo']='id_materia_prima';
		$datos[2]['dato']=$parametros['materia'];
		return $this->modelo->guardar($datos,'datos_produccion');
	   }else
	   {
	   	return 2;
	   }
		// $datos[3]['campo']='';
		// $datos[3]['dato']=$parametros[''];

	}

	// edita materia prima
	function editar_prima($parametros)
	{
		
		$datos[0]['campo']='cantidad';
		$datos[0]['dato']=$parametros['cant'];
		
		$where[0]['campo']='id_datos_produccion';
		$where[0]['dato']=$parametros['id'];
		return $this->modelo-> update('datos_produccion',$datos,$where);
	  
		// $datos[3]['campo']='';
		// $datos[3]['dato']=$parametros[''];

	}

	// genera codigo de referencia para articulos nuevos
	function referencia_codigo_producto()
	{
		$datos = $this->modelo->referencia_codigo_producto();
		// print_r($datos[0]['referencia_producto']);die();
		if(count($datos)>0)
		{
		  $cod = explode('F', $datos[0]['referencia_producto']);
		  $numero = intval($cod[1])+1;
		  $new_cod = $this->pagina->agregar_ceros(9,$numero);
		  return 'EF'.$new_cod;
	  }else
	  {
	  	return 'EF000000001';
	  }

	}

// genera codigo de referencia para materia prima nuevos
	function referencia_codigo_material()
	{
		$datos = $this->modelo->referencia_codigo_material();
		// print_r($datos[0]['referencia_producto']);die();
		if(count($datos)>0)
		{
		  $cod = explode('A', $datos[0]['referencia_producto']);
		  $numero = intval($cod[1])+1;
		  $new_cod = $this->pagina->agregar_ceros(9,$numero);
		  return 'MA'.$new_cod;
	  }else
	  {
	  	return 'MA000000001';
	  }

	}

// genra una etiqueta para producto
	function etiqueta($id)
	{
		// print_r($id);die();
		$parametros=array('id'=>$id);
		$datos = $this->modelo->cargar_articulos($parametros);
		$this->pdf->etiqueta($datos);
	}

	// genera etiqueta patra materia prima
	function etiqueta_m($id)
	{
		// print_r($id);die();
		$parametros=array('id'=>$id);
		$datos = $this->modelo->cargar_materia($parametros);
		// print_r($datos);die();
		$this->pdf->etiqueta_m($datos);
	}
}
?>