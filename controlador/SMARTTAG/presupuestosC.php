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
require_once(dirname(__DIR__, 2) .'/modelo/loginM.php');
require_once(dirname(__DIR__, 2) .'/lib/Reporte_pdf.php');
// include('../modelo/headerM.php');

$controlador = new presupuestosC();
if(isset($_GET['search']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->autocompletar($query));

}

if(isset($_GET['search_client']))
{
	$ci='';
	$query = '';
	if(isset($_POST['search']))
	{
		$query = $_POST['search'];

	echo json_encode($controlador->autocompletar_cliente($query,$ci));
	}
	if(isset($_POST['searchCI']))
	{
		$ci = $_POST['searchCI'];
	 echo json_encode($controlador->autocompletar_cliente_ci($query,$ci));
	}
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
if(isset($_GET['update_cliente']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->update_datos_cliente($parametros));
}
if(isset($_GET['datos_cliente_nuevo']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->datos_cliente_nuevo($parametros));
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
if(isset($_GET['finalizar_factura']))
{
	$id = $_POST['num'];
	echo json_encode($controlador->finalizar_factura($id));
}
if(isset($_GET['punto_venta']))
{
	echo json_encode($controlador->bodegas_punto());
}
if(isset($_GET['buscar_punto']))
{

	$id = $_POST['id'];
	echo json_encode($controlador->buscar_punto($id));
}

if(isset($_GET['buscar_bodegas_punto']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->buscar_bodegas_punto($id));
}
if(isset($_GET['pasar_a_factura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->pasar_a_factura($parametros));
}

class presupuestosC
{
	private $modelo;
	private $pagina;
	private $global;
	private $pdf;
	private $login;

	
	function __construct()
	{
		$this->modelo = new punto_ventaM();
		$this->pagina = new codigos_globales();
		$this->login = new loginM();
		$this->pagina->registrar_pagina_creada('../vista/presupuestos.php','Cotizacion','','estado');
		$this->pdf =  new Reporte_pdf(); 
	}

	function autocompletar($query)
	{

		$datos = $this->modelo->lista_de_productos($query);
		$result = array();
		foreach ($datos as $key => $value) {
			 $result[] = array("value"=>$value['ref'],"label"=>$value['ref'].' '.$value['detalle'],'bodega'=>$value['bodega'],'nombre'=>$value['detalle']);
		}
		return $result;
	}

	function autocompletar_cliente($query,$ci)
	{
		
			$datos = $this->modelo->lista_de_clientes($query,$ci);
			$result = array();
			foreach ($datos as $key => $value) {
				$result[] = array("value"=>$value['id'],"label"=>$value['nombre'],'ci'=>$value['ci'],"email"=>$value["email"],"dir"=>$value['direccion'],"tel"=>$value['telefono']);
			}
			return $result;
		
	}

	function autocompletar_cliente_ci($query,$ci)
	{
		
			$datos = $this->modelo->lista_de_clientes($query,$ci);
			$result = array();
			foreach ($datos as $key => $value) {
				$result[] = array("value"=>$value['id'],"label"=>$value['ci'],'nom'=>$value['nombre'],"email"=>$value["email"],"dir"=>$value['direccion'],"tel"=>$value['telefono']);
			}
			return $result;
		
	}
	function datos_cliente_nuevo($parametros)
	{		
			$datos = $this->modelo->lista_de_clientes($parametros['query'],$parametros['ci'],$parametros['id']);
			return $datos;		
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
		$datos[0]['campo']='tipo_factura';
		$datos[0]['dato']=$parametros['doc'];
		$datos[1]['campo']='cliente';
		$datos[1]['dato']=$parametros['cli'];

		$datos[2]['campo']='fecha_factura';
		$datos[2]['dato']=$parametros['fefa'];

		$num_fac = $this->modelo->numero_de_coti();
		$datos[3]['campo']='numero_factura';
		$datos[3]['dato']=$num_fac;

		$datos[4]['campo']='punto_venta';
		$datos[4]['dato']=$_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO'];
		// print_r($datos);die();

		$rep =  $this->modelo->guardar($datos,'facturas');
		$idfac = $this->modelo->numero_coti($datos[3]['dato']);

		return  array('id' =>$idfac[0]['id_factura'],'tipo'=>$idfac[0]['tipo_factura']);

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
	     $line[3]['dato']=$parametros['pre'];
	     $line[4]['campo']='subtotal';
	     $line[4]['dato']=($parametros['pre']*$parametros['can']);
	     $line[5]['campo']='descuento';
	     $line[5]['dato']=$dcto;
	     $line[6]['campo']='total';
	     $line[6]['dato']=$parametros['tot'];
	     $line[7]['campo']='codigo_ref';
	     $line[7]['dato']=$parametros['ref'];
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
	function update_datos_cliente($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo'] = 'nombre';
		$datos[0]['dato'] = $parametros['nom'];
		$datos[1]['campo'] = 'direccion';
		$datos[1]['dato'] = $parametros['dir'];
		$datos[2]['campo'] = 'telefono';
		$datos[2]['dato'] = $parametros['tel'];
		$datos[3]['campo'] = 'email';
		$datos[3]['dato'] = $parametros['ema'];

		$where[0]['campo'] = 'id_cliente_prove';
		$where[0]['dato'] = $parametros['id'];

		return $this->modelo->update('cliente_proveedor',$datos,$where);

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
	function finalizar_factura($id)
	{
		$datos[0]['campo'] = 'estado_factura';
		$datos[0]['dato'] = 'F';

		$where[0]['campo'] = 'id_factura';
		$where[0]['dato'] = $id;

		return $this->modelo->update('facturas',$datos,$where);

	}
	function bodegas_punto()
	{
		if(count(explode(',', $_SESSION['INICIO']['PUNTO_VENTA']))>1)
		{
			$datos = $this->modelo->bodegas_punto();
			$op ='<option value="">Seleccione punto venta</option>';
			foreach ($datos as $key => $value) {
				$op.='<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
			}
			return $op;
		}else
		{
			print_r('expression');die();
		}
		

	}
	function buscar_punto($id)
	{
		$datos = $this->modelo->bodegas_punto($id);
		return $datos;
	}
	function buscar_bodegas_punto($id)
	{
		$b = $this->login->bodegas($id);
		$_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO_NOM']=$b[0]['nombre_punto'];

		// print_r($b);die();
		if($b[0]['all_bodegas']==1)
		{
			 $bo_all = $this->login->bodegas_all();
			 $bo = '';
			 foreach ($bo_all as $key => $value) {
			  $bo.=$value['id'].',';			    		 	
			 }
			 $bo = substr($bo, 0,-1);
			$_SESSION['INICIO']['BODEGAS'] = $bo;

		}else
		{
			 $_SESSION['INICIO']['BODEGAS']=$b[0]['bodega'];
		}
	}


	function pasar_a_factura($parametros)
	{
		// print_r();die();
		$datos = $this->modelo->pasar_a_factura_coti($parametros);
		return $datos;

	}
}
?>