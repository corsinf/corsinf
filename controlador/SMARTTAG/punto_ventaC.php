<?php 
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}

require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/punto_ventaM.php');
require_once(dirname(__DIR__, 2) .'/lib/Reporte_pdf.php');
require_once(dirname(__DIR__, 2) .'/modelo/cuentas_x_cobrarM.php');

$controlador = new punto_ventaC();
if(isset($_GET['search']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->autocompletar($query));

}

if(isset($_GET['search_ord']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->autocompletar_all_orden($query));

}

if(isset($_GET['search_all']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->autocompletar_all_detalle($query));

}

if(isset($_GET['search_all_ord']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->autocompletar_all_detalle_ord($query));

}

if(isset($_GET['search_all_id']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->autocompletar_all_detalle_id($query));

}

if(isset($_GET['search_cliente']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	$tipo = $_GET['tipo'];
	echo json_encode($controlador->autocompletar_cliente($query,$tipo));

}
if(isset($_GET['search_cliente_input']))
{
	$query = '';
	if(isset($_POST['search']))
	{
		$query = $_POST['search'];
	}
	$tipo = $_GET['tipo'];
	echo json_encode($controlador->autocompletar_cliente_input($query,$tipo));

}
if(isset($_GET['cargar_pedido']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_pedido($parametros));
}
if(isset($_GET['cargar_pedido_f']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_pedido_f($parametros));
}
if(isset($_GET['add_pedido']))
{
	$parametros = $_POST['datos'];
	echo json_encode($controlador->add_pedido($parametros));
}

if(isset($_GET['crear_documento']))
{
	$parametros = $_POST['datos'];
	echo json_encode($controlador->crear_documento($parametros));
}
if(isset($_GET['new_usuario']))
{
	$parametros = $_POST;
	echo json_encode($controlador->add_usuario($parametros));
}

if(isset($_GET['datos_cliente']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->datos_cliente($parametros));
}

if(isset($_GET['eliminar_linea']))
{
	$parametros = $_POST['id'];
	echo json_encode($controlador->eliminar_linea($parametros));
}
if(isset($_GET['factura_pdf']))
{
	$id = $_GET['fac'];
	echo json_encode($controlador->factura($id));
}
if(isset($_GET['factura_pdf2']))
{
	$id = $_GET['fac'];
	echo json_encode($controlador->factura($id));
}
if(isset($_GET['piezas_compradas']))
{
	$id = $_GET['fac'];
	echo json_encode($controlador->piezas_compradas($id));
}
if(isset($_GET['finalizar_factura']))
{
	$id = $_POST['num'];
	echo json_encode($controlador->finalizar_factura($id));
}

class punto_ventaC
{
	private $modelo;
	private $pagina;
	private $global;
	private $pdf;
	private $abonos;

	
	function __construct()
	{
		$this->modelo = new punto_ventaM();
		$this->pagina = new codigos_globales();
		$this->pagina->registrar_pagina_creada('../vista/punto_venta.php','Punto venta normal','4','estado');
		$this->pdf =  new Reporte_pdf(); 
		$this->abonos = new cuentas_x_cobrarM();
	}

	function autocompletar($query)
	{

		$datos = $this->modelo->lista_de_productos($query);
		$result = array();
		foreach ($datos as $key => $value) {
			 $result[] = array("value"=>$value['ref'],"label"=>$value['ref'].'    '.$value['detalle'].'   '.$value['detalle_bodega'],'producto'=>$value['detalle'],'bodega'=>$value['bodega'],'bodega_nom'=>$value['detalle_bodega'],'nombre'=>$value['detalle'],'precio'=>$value['precio']);
		}
		return $result;
	}

	function autocompletar_all_orden($query)
	{

		$datos = $this->modelo->lista_de_productos_all($query);
		$result = array();
		foreach ($datos as $key => $value) {
			 $result[] = array("value"=>$value['ref'],"label"=>$value['ref'].'    '.$value['detalle'].'   '.$value['detalle_bodega'],'producto'=>$value['detalle'],'bodega'=>$value['bodega'],'bodega_nom'=>$value['detalle_bodega'],'nombre'=>$value['detalle'],'precio'=>$value['precio']);
		}
		return $result;
	}



	function autocompletar_all_detalle($query)
	{

		$datos = $this->modelo->lista_de_productos_all_detalle($query);
		$result = array();
		foreach ($datos as $key => $value) {
			 $result[] = array("value"=>$value['ref'],"label"=>$value['ref'].'    '.$value['detalle'].'   '.$value['detalle_bodega'],'producto'=>$value['detalle'],'bodega'=>$value['bodega'],'bodega_nom'=>$value['detalle_bodega'],'nombre'=>$value['detalle'],'precio'=>$value['precio'],'tipo'=>$value['cate'],'id_tipo'=>$value['idcat'],'peso'=>$value['peso'],'id_ma'=>$value['material'],'material'=>$value['detalle_material'],'foto'=>$value['foto']);
		}
		return $result;
	}

	function autocompletar_all_detalle_ord($query)
	{

		$datos = $this->modelo->lista_de_productos_all_detalle_ord($query);
		$result = array();
		foreach ($datos as $key => $value) {
			 $result[] = array("value"=>$value['ref'],"label"=>$value['ref'].'    '.$value['detalle'].'   '.$value['detalle_bodega'],'producto'=>$value['detalle'],'bodega'=>$value['bodega'],'bodega_nom'=>$value['detalle_bodega'],'nombre'=>$value['detalle'],'precio'=>$value['precio'],'tipo'=>$value['cate'],'id_tipo'=>$value['idcat'],'peso'=>$value['peso'],'id_ma'=>$value['material'],'material'=>$value['detalle_material'],'foto'=>$value['foto']);
		}
		return $result;
	}

	function autocompletar_all_detalle_id($query)
	{

		$datos = $this->modelo->lista_de_productos_all_detalle_id($query);
		$result = array();
		foreach ($datos as $key => $value) {
			 $result[] = array("value"=>$value['ref'],"label"=>$value['ref'].'    '.$value['detalle'].'   '.$value['detalle_bodega'],'producto'=>$value['detalle'],'bodega'=>$value['bodega'],'bodega_nom'=>$value['detalle_bodega'],'nombre'=>$value['detalle'],'precio'=>$value['precio'],'tipo'=>$value['cate'],'id_tipo'=>$value['idcat'],'peso'=>$value['peso'],'id_ma'=>$value['material'],'material'=>$value['detalle_material'],'foto'=>$value['foto'],'idE'=>$value['id_estado_joya'],'est'=>$value['detalle_estado'],'idC'=>$value['id_cliente_prove'],'cli'=>$value['nombre'],'detalle'=>$value['descripcion_trabajo']);
		}
		return $result;
	}


	function autocompletar_cliente($query,$tipo)
	{
		if($tipo=='C')
		{
			$datos = $this->modelo->lista_de_clientes($query);
			$result = array();
			foreach ($datos as $key => $value) {
				$result[] = array("id"=>$value['id'],"text"=>$value['nombre']);
			}
			return $result;
		}else
		{
			$datos = $this->modelo->lista_de_clientes_panio($query);$result = array();
			foreach ($datos as $key => $value) {
				$result[] = array("id"=>$value['id'],"text"=>$value['nombre']);
			}
			return $result;
		}
	}

	function autocompletar_cliente_input($query,$tipo)
	{
		if($tipo=='C')
		{
			$datos = $this->modelo->lista_de_clientes($query);
			$result = array();
			foreach ($datos as $key => $value) {
				$result[] = array("value"=>$value['id'],"label"=>$value['nombre']);
			}
			return $result;
		}else
		{
			$datos = $this->modelo->lista_de_clientes_panio($query);$result = array();
			foreach ($datos as $key => $value) {
				$result[] = array("value"=>$value['id'],"label"=>$value['nombre']);
			}
			return $result;
		}
	}


	function cargar_pedido($parametros)
	{
		$total=0;
		$iva=0;
		$dcto = 0;
		$subtotal = 0;
		if($parametros['id']=='')
		{
			$datos = false;
		}else
		{
			$datos = $this->modelo->lista_de_productos_pedido($parametros['id']);
			foreach ($datos as $key => $value) {
				$subtotal+= $value['subtotal'];
				$total+= $value['total'];
				$dcto+= $value['descuento'];
				$iva+=$value['iva'];			  
		    }
		}

		$cabecera = array('Producto','cant','PreUni','% Desc','SubTotal','Iva','Total');
		$ocultar = array('id'=>'id');
		$posicion = array('L','L','R','R','R','R','R');		
		$boton[0] = array('boton' =>'Eliminar','icono'=>'<i class="fas fa-trash nav-icon"></i>','tipo'=>'danger','id'=>'id' );
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$boton,false,$ocultar,false,$posicion);
		$resp = $this->update_factura($parametros['id'],$total,$subtotal,$iva,$dcto);
		if($resp==1)
		{
			return array('tabla'=>$tabla,'total'=>$total,'dcto'=>$dcto,'subtotal'=>$subtotal,'iva'=>$iva);
		}else
		{
			return -1;
		}
	}

	function cargar_pedido_f($parametros)
	{
		$total=0;
		$iva=0;
		$dcto = 0;
		$subtotal = 0;
		if($parametros['id']=='')
		{
			$datos = false;
		}else
		{
			$datos = $this->modelo->lista_de_productos_pedido($parametros['id']);
			foreach ($datos as $key => $value) {
				$subtotal+= $value['subtotal'];
				$total+= $value['total'];
				$dcto+= $value['descuento'];
				$iva+=$value['iva'];			  
		    }
		}

		$cabecera = array('Producto','cant','PreUni','% Desc','SubTotal','Iva','Total');
		$ocultar = array('id'=>'id');
		$posicion = array('L','L','R','R','R','R','R');		
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$boton=false,false,$ocultar,false,$posicion);
			return array('tabla'=>$tabla,'total'=>$total,'dcto'=>$dcto,'subtotal'=>$subtotal,'iva'=>$iva);
		
	}

	function update_factura($id,$total,$sub,$iva,$dcto)
	{
	     $line[0]['campo']='total_factura';
	     $line[0]['dato']=$total;
	     $line[1]['campo']='subtotal_factura';
	     $line[1]['dato']=$sub;
	     $line[2]['campo']='iva_factura';
	     $line[2]['dato']=$iva;
	     $line[3]['campo']='descuento_factura';
	     $line[3]['dato']=$dcto;

	     $where[0]['campo']='id_factura';
	     $where[0]['dato']=$id;
	     $rep =  $this->modelo->update('facturas',$line,$where);
	     if($rep)
	     {
	       return 1;
	     }
	    
	    return -1;

	}

	function crear_documento($parametros)
	{
		if($parametros['tip']=='B')
		{
		$datos[0]['campo']='tipo_factura';
		$datos[0]['dato']='B'.$parametros['doc'];

		}else
		{
		$datos[0]['campo']='tipo_factura';
		$datos[0]['dato']=$parametros['doc'];
		}
		$datos[1]['campo']='cliente';
		$datos[1]['dato']=$parametros['cli'];

		$datos[2]['campo']='fecha_factura';
		$datos[2]['dato']=$parametros['fefa'];

		$num_fac = $this->modelo->numero_de_factura();
		$datos[3]['campo']='numero_factura';
		$datos[3]['dato']=$num_fac['ultimo']+1;

		$datos[4]['campo']='fecha_exp';
		$datos[4]['dato']=$parametros['feex'];

		$rep =  $this->modelo->guardar($datos,'facturas');
		$idfac = $this->modelo->numero_de_factura($datos[3]['dato']);

		return  array('id' =>$idfac['id'],'tipo'=>$idfac['doc']);

	}

	function add_pedido($parametros)
	{
		// print_r($parametros);die(); 
		 $dcto = (($parametros['pre']*$parametros['can'])*$parametros['des'])/100;
	     $line[0]['campo']='id_factura';
	     $line[0]['dato']=$parametros['idf'];
	     $line[1]['campo']='producto';
	     $line[1]['dato']=$parametros['pro'];
	     $line[2]['campo']='cantidad';
	     $line[2]['dato']=$parametros['can'];
	     $line[3]['campo']='precio_uni';
	     $line[3]['dato']=number_format($parametros['pre'],2,'.','');
	     $line[4]['campo']='subtotal';
	     $line[4]['dato']=number_format(($parametros['pre']*$parametros['can']),2,'.','');
	     $line[5]['campo']='descuento';
	     $line[5]['dato']=$dcto;
	     $line[6]['campo']='total';
	     $line[6]['dato']=number_format($parametros['tot'],2,'.','');
	     $line[7]['campo']='codigo_ref';
	     $line[7]['dato']=$parametros['ref'];	     
	     $line[8]['campo']='id_bodega';
	     $line[8]['dato']=$parametros['bod'];
	     // print_r($line);die();
	     $rep =  $this->modelo->guardar($line,'lineas_factura');
	     if($rep)
	     {
	       return 1;
	     }
	    
	    return -1;
	}

	function  add_usuario($parametros)
	{
		$datos[0]['campo']='nombre';
		$datos[0]['dato']=$parametros['txt_nombre_new'];
		$datos[1]['campo']='ci_ruc';
		$datos[1]['dato']=$parametros['txt_ci_new'];
		$datos[2]['campo']='direccion';
		$datos[2]['dato']=$parametros['txt_dir'];
		$datos[3]['campo']='telefono';
		$datos[3]['dato']=$parametros['txt_telefono'];
		$datos[4]['campo']='email';
		$datos[4]['dato']=$parametros['txt_emial'];
		$rep =  $this->modelo->guardar($datos,'cliente_proveedor');
		if($rep!=1)
		{
			return -1;
		}
			return 1;

	}

	function datos_cliente($parametros)
	{
		// print_r($parametros);die();
		$num = strlen($parametros['doc']);
		$datos = $this->modelo->datos_cliente($parametros['id'],$num);
		return $datos[0];

	}
	function eliminar_linea($parametros)
	{
		return $this->modelo->eliminar_linea($parametros);

	}
	function factura($id)
	{
		$titulo = 'Reporte de todo los articulos';
		$datos = $this->modelo->lista_de_productos_pedido_all_description($id);
		$this->pdf->factura_pdf($datos);
	}
	function piezas_compradas($id)
	{
		$titulo = 'Reporte de todo los articulos';
		$datos = $this->modelo->lista_de_productos_pedido_all_description($id);
		$abonos = $this->abonos->abonos_factura($id);
		$cuotas = $this->abonos->cuotas_factura($id);
		$this->pdf->piezas_compradas($datos['lineas'],$datos['cabecera'],$abonos,$cuotas);
	}
	function finalizar_factura($id)
	{
		$datos[0]['campo'] = 'estado_factura';
		$datos[0]['dato'] = 'F';

		$where[0]['campo'] = 'id_factura';
		$where[0]['dato'] = $id;

		return $this->modelo->update('facturas',$datos,$where);

	}
}
?>