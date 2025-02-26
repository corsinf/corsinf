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
require_once(dirname(__DIR__, 2) .'/modelo/articulosM.php');
require_once(dirname(__DIR__, 2) .'/lib/Reporte_pdf.php');
// include('../modelo/headerM.php');

$controlador = new facturacionC();
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
if(isset($_GET['punto_venta1']))
{
	echo json_encode($controlador->bodegas_punto1());
}
if(isset($_GET['buscar_punto']))
{

	$id = $_POST['id'];
	echo json_encode($controlador->buscar_punto($id));
}

if(isset($_GET['eliminar_cheque']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->eliminar_cheque($id));
}

if(isset($_GET['buscar_bodegas_punto']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->buscar_bodegas_punto($id));
}
if(isset($_GET['cuotas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_cuotas($parametros));
}
if(isset($_GET['cheques_pos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cheque_pos($parametros));
}
if(isset($_GET['add_cheque']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_cheque($parametros));
}

if(isset($_GET['cheques_cruzar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->table_cruzar($parametros));
}

class facturacionC
{
	private $modelo;
	private $pagina;
	private $global;
	private $pdf;
	private $login;		
	private $transac; 
	
	function __construct()
	{
		$this->modelo = new punto_ventaM();
		$this->pagina = new codigos_globales();
		$this->login = new loginM();
		$this->pagina->registrar_pagina_creada('../vista/Facturacion.php','Facturacion','','estado');
		$this->pdf =  new Reporte_pdf(); 
		$this->transac = new  articulosM();
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
				$result[] = array("value"=>$value['id'],"label"=>$value['nombre'],'ci'=>$value['ci'],"email"=>$value["email"],"dir"=>$value['direccion'],"tel"=>$value['telefono'],"credito"=>$value['credito']);
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
		$ocultar = array('id'=>'id','id_bodega');
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

		$num_fac = $this->modelo->numero_de_factura();
		$datos[3]['campo']='numero_factura';
		$datos[3]['dato']=$num_fac;

		$datos[4]['campo']='punto_venta';
		$datos[4]['dato']=$_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO'];

		$rep =  $this->modelo->guardar($datos,'facturas');
		$idfac = $this->modelo->numero_fac($datos[3]['dato']);

		return  array('id' =>$idfac[0]['id_factura'],'tipo'=>$idfac[0]['tipo_factura']);

	}

	function add_pedido($parametros)
	{
		print_r($parametros);die(); 
		  $dcto = (($parametros['pre']*$parametros['can'])*$parametros['des'])/100;
	     $line[0]['campo']='id_factura';
	     $line[0]['dato']=$parametros['idf'];
	     $line[1]['campo']='producto';
	     $line[1]['dato']=$parametros['pro'];
	     $line[2]['campo']='cantidad';
	     $line[2]['dato']=$parametros['can'];
	     $line[3]['campo']='precio_uni';
	     $line[3]['dato']= number_format($parametros['pre'],2,'.','');
	     $line[4]['campo']='subtotal';
	     $line[4]['dato']= number_format(($parametros['pre']*$parametros['can']),2,'.','');
	     $line[5]['campo']='descuento';
	     $line[5]['dato']=$dcto;
	     $line[6]['campo']='total';
	     $line[6]['dato']= number_format($parametros['tot'],2,'.','');
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

		function bodegas_punto1()
	{
		if(count(explode(',', $_SESSION['INICIO']['PUNTO_VENTA']))>1)
		{
			$datos = $this->modelo->bodegas_punto();
			$op ='<option value="">Seleccione punto venta</option>';
			foreach ($datos as $key => $value) {
				$op.='<option value="'.$value['id'].'-'.$value['bodega'].'">'.$value['nombre'].'</option>';
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
	function generar_cuotas($parametros)
	{
		// print_r($parametros);die();
	
		$total_fac = -1;
		$datos[0]['campo'] ='factura_id' ;
		$datos[0]['dato'] = $parametros['factura'];
		$datos[1]['campo'] = 'tipo_pago';
		$datos[1]['dato'] = $parametros['tipo'];
		$datos[2]['campo'] ='monto' ;
		$datos[2]['dato'] = number_format($parametros['total']-$parametros['monto'],2,'.','');
		$datos[3]['campo'] = 'abono';
		$datos[3]['dato'] = number_format($parametros['monto'],2,'.','');
		$datos[4]['campo'] = 'num_cheqDep';
		$datos[4]['dato'] =$parametros['cheque'] ;
		$datos[5]['campo'] = 'fecha';
		$datos[5]['dato'] = $parametros['fecha'];
		$datos[6]['campo'] = 'fecha_efec';
		$datos[6]['dato'] = $parametros['fecha_efec'];
		$datos[7]['campo'] = 'banco';
		$datos[7]['dato'] = $parametros['banco'];
		$datos[8]['campo'] = 'estado_abono';
		$datos[8]['dato'] ='CO';


		$tra = $this->transac->tipo_transaccion('EGRESO INVENTARIO');	
		$num_Fa = $this->modelo->datos_cliente($parametros['factura']);
		$bo = explode(',',$_SESSION['INICIO']['BODEGAS']);
		$tran[0]['campo'] ='id_bodega_salida';
		$tran[0]['dato'] =$bo[0];
		$tran[1]['campo'] ='id_usuario';
		$tran[1]['dato'] =$_SESSION['INICIO']['ID'];
		$tran[2]['campo'] ='fecha';
		$tran[2]['dato'] =$parametros['fecha'];
		$tran[3]['campo'] ='documento';
		$tran[3]['dato'] ='FACTURA';
		$tran[4]['campo'] ='num_documento';
		$tran[4]['dato'] =$num_Fa[0]['fac']; 
		$tran[5]['campo'] ='total';
		$tran[5]['dato'] =$parametros['total'];
		$tran[6]['campo'] ='tipo_transaccion';
		$tran[6]['dato'] =$tra;

		// print_r($tran);die();

		$this->modelo->guardar($tran,'transacciones');

		if($parametros['total'] == $parametros['monto'])
		{
			$datos1[0]['campo']='estado_pago';
			$datos1[0]['dato']='PA';
			$where[0]['campo']='id_factura';
			$where[0]['dato']=$parametros['factura'];
			$this->modelo->update('facturas',$datos1,$where);
			$total_fac = 1;

		}
		$resp = $this->modelo->guardar($datos,'abonos');
		//actualizar monto de credito
		$dcli = $this->modelo->datos_cliente($parametros['factura'],'');
		$cre[0]['campo']='credito';
		$cre[0]['dato']=$parametros['credito'];
		$wherecre[0]['campo'] = 'id_cliente_prove';
		$wherecre[0]['dato'] = $dcli[0]['id'];
	    $this->modelo->update('cliente_proveedor',$cre,$wherecre);
		//fin de actualizacion de credito

		$j = 1;
		if($parametros['meses']>1)
		{
			for ($i=1; $i <= $parametros['meses']; $i++) { 
				$fecha_actual = date($parametros['fecha']);
				$fecha_new =  date("d-m-Y",strtotime($fecha_actual."+ ".$j." month")); 
				$datos2[0]['campo'] ='factura_id' ;
		        $datos2[0]['dato'] = $parametros['factura'];
		        $datos2[1]['campo'] ='cuota' ;
		        $datos2[1]['dato'] = number_format(($parametros['total']-$parametros['monto'])/$parametros['meses'],2,'.','');
		        $datos2[2]['campo'] = 'fecha_cuota';
		        $datos2[2]['dato'] = $fecha_new;
		        $j+=1;
		        $resp = $this->modelo->guardar($datos2,'abonos');
			}
			// print_r($datos);die();
			
		}

		if($resp==1 && $total_fac ==1)
		{
			return 2 ;
		}else if($resp==1 && $total_fac==-1)
		{
			$this->ingresar_kardex($parametros);
			return 1;

		}else
		{
			return -1;
		}

	}

	function ingresar_kardex($parametros)
	{
		$lineas = $this->modelo->lista_de_productos_pedido_($parametros['factura']);
		// print_r($lineas);die();
		foreach ($lineas as $key => $value) {
			  $producto = $this->modelo->lista_de_productos_all($value['producto'],$value['id_bodega']);
			  // print_r($producto);die();
			    	$existencias =$producto[0]['stock']-$value['cantidad'];
						$datosI[0]['campo']='id_producto';
						$datosI[0]['dato']=$producto[0]['id'];
						$datosI[1]['campo']='fecha';
						$datosI[1]['dato']=date('Y-m-d');
						$datosI[2]['campo']='salida';
						$datosI[2]['dato']=$value['cantidad'];
						$datosI[3]['campo']='valor_uni';
						$datosI[3]['dato']=$value['precio_uni'];
						$datosI[4]['campo']='valor_total';
						$datosI[4]['dato']=number_format($value['total'],2,'.','');
						$datosI[5]['campo']='existencias';
						$datosI[5]['dato']=number_format($existencias);
						$datosI[6]['campo']='factura';
						$datosI[6]['dato']=$parametros['factura'] ;
						$datosI[7]['campo']='existencias_ant';
						$datosI[7]['dato']= $producto[0]['stock'];
						$datosI[8]['campo']='id_bodega';
						$datosI[8]['dato']= $producto[0]['bodega'];

						$this->modelo->guardar($datosI,'kardex');
						
						//-----------------fin de ingreso en kardex----------- 
				      $datos1[0]['campo']='stock_producto';
					    $datos1[0]['dato']=$existencias;

					    $where[0]['campo']='id_producto';
					    $where[0]['dato']=$producto[0]['id'];
					    $rep1 =  $this->modelo->update('productos',$datos1,$where);
					    
		}
		return 1;
	}

	function cheque_pos($parametros)
	{
		$datos = $this->modelo->cheques_posfechados($parametros['factura']);
		$cabecera = array('Numero cheque','Banco','Fecha efectivo','Monto');
		$ocultar = array('cheques_id');
		$botones[0] = array('boton'=>'Eliminar cheque','icono'=>'<i class="fas fa-trash nav-icon"></i>','tipo'=>'danger','id'=>'cheques_id');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		// print_r($tabla);die();
		$nuc_c = count($datos);
		return array('tabla'=>$tabla,'reg'=>$nuc_c);
	}
	function eliminar_cheque($id)
	{
		$tabla = 'cheques_pos';
		$datos[0]['campo'] ='cheques_id';
		$datos[0]['dato'] = $id;
		return $this->modelo->delete($tabla,$datos);
	}
	function add_cheque($parametros)
	{
		$datos2[0]['campo'] = 'cheques_num';
		$datos2[0]['dato'] = $parametros['num'];
		$datos2[1]['campo'] ='cheques_banco' ;
		$datos2[1]['dato'] = $parametros['ban'];
		$datos2[2]['campo'] = 'cheques_fecha';
		$datos2[2]['dato'] = $parametros['fec'];
		$datos2[3]['campo'] = 'cheque_monto';
		$datos2[3]['dato'] = $parametros['mon'];
		$datos2[4]['campo'] = 'factura';
		$datos2[4]['dato'] = $parametros['id'];
		return  $this->modelo->guardar($datos2,'cheques_pos');
	}
	function table_cruzar($parametros)
	{
		// print_r($parametros);die();
			$datos = $this->modelo->cheques_pos_fecha_cru($parametros['factura']);
			// print_r($datos);die();
		  $cabecera = array('Numero cheque','Fecha efectivizar','Banco','Monto');
		  $ocultar = array('cheques_id','factura');
		  $botones[0] = array('boton'=>'Cruzar cheque','icono'=>'<i class="fas fa-exchange-alt nav-icon"></i>','tipo'=>'primary','id'=>'cheques_id');
		  $tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		// print_r($tabla);die();
		  $nuc_c = count($datos);
		  return array('tabla'=>$tabla,'reg'=>$nuc_c);

	}

}
?>