<?php 
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/cuentas_x_cobrarM.php');
require_once(dirname(__DIR__, 2) .'/modelo/punto_ventaM.php');
$controlador = new cuentas_x_cobrarC();

if (isset($_GET['facturas_por_pagar'])) 
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->facturas_finalizadas($parametros));
}
if (isset($_GET['facturas_pagadas'])) 
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->facturas_finalizadas_pagadas($parametros));
}
if (isset($_GET['abonos_tabla'])) 
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->abonos_tablas($parametros));
}
if(isset($_GET['search_cliente']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->autocompletar_cliente($query));

}
if(isset($_GET['tipo_pago']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->forma_pago($query));

}
if (isset($_GET['add_abono'])) 
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_abono($parametros));
}
if (isset($_GET['eliminar_abono'])) 
{
	$id = $_POST['id'];
	echo json_encode($controlador->delete_abono($id));
}

if (isset($_GET['cruzar_cheque'])) 
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cruzar_cheque($parametros['cheque_pos']));
}



class cuentas_x_cobrarC
{
	private $modelo;
	private $pagina;
	private $global;
	private $punto_venta;

	
	function __construct()
	{
		$this->modelo = new cuentas_x_cobrarM();
		$this->punto_venta = new punto_ventaM();
		$this->pagina = new codigos_globales();
		$this->pagina->registrar_pagina_creada('../vista/cuentas_x_cobrar.php','Cuentas x Cobrar','5','estado');
	}

  // devuelve en forma de lista de clientes en autocomplete para select2
	function autocompletar_cliente($query)
	{
		
			$datos = $this->punto_venta->lista_de_clientes($query);
			$result = array();
			foreach ($datos as $key => $value) {
				$result[] = array("id"=>$value['id'],"text"=>$value['nombre']);
			}
			return $result;
	}

	// devuelve en forma de tabla facturas finalizadas
	function facturas_finalizadas($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->facturas_finalizadas($parametros['id']);
		$botones[0] = array('boton'=>'Agregar Abono','icono'=>'<i class="fa fa-money-bill nav-icon"></i>','tipo'=>'primary','id'=>'id');
		$ocultar = array('id');
		$cabecera = array('Nun Factura','Nombre','Fecha','Descuento','Subtotal','Iva','Total','Tipo');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar);

		return $tabla;
	}

	// devuelve en forma de tabla  facturas pagadas en su totalidad y finalizadas
	function facturas_finalizadas_pagadas($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->facturas_finalizadas_pagadas($parametros['id']);
		$botones[0] = array('boton'=>'Agregar Abono','icono'=>'<i class="fa fa-money-bill nav-icon"></i>','tipo'=>'primary','id'=>'id');
		$ocultar = array('id');
		$cabecera = array('Nun Factura','Nombre','Fecha','Descuento','Subtotal','Iva','Total','Tipo');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones=false,false,$ocultar);

		return $tabla;
	}

	// devuelve en forma de tabla  los abonos realizados a una factura
	function abonos_tablas($parametros)
	{
		$datos = $this->modelo->abonos_factura($parametros['id']);
		$ocultar = array('id');
		$cabecera = array('Tipo de pago','Abono','Num. comprobante','Fecha','Monto restante');		
		$botones[0] = array('boton'=>'Eliminar abono','icono'=>'<i class="fa fa-trash nav-icon"></i>','tipo'=>'danger','id'=>'id');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar);
		$total = $this->factura_datos($parametros['id']);
		$abono_total = 0;
		foreach ($datos as $key => $value) {
			$abono_total+=$value['abono'];
		}
		$faltante = $total[0]['total']-$abono_total;

		$datos_c = $this->modelo->cuotas_factura($parametros['id']);		
		$cabecera_c = array('Fecha','Valor');			
		$ocultar = array('id_abonos');
		// print_r($datos_c);die();
		$tabla_c =  $this->pagina->tabla_generica($datos_c,$cabecera_c,false,false,$ocultar);
		$resp = array('total'=>$total[0]['total'],'tabla'=>$tabla,'total_abono'=>round($abono_total,2),'faltante'=>round($faltante,2),'tabla_cuotas'=>$tabla_c);

		return $resp;

	}


 // devuelve un array donde se encuentran todos los datos de una factura
	function factura_datos($id)
	{
		$datos = $this->modelo->datos_de_factura($id);
		return $datos;
	}

	// devuelve una lista  de las formas de pago
	function forma_pago($query)
	{
		$datos = $this->modelo->forma_pago($query);
		// print_r($datos);die();
		$resp= array();
		foreach ($datos as $key => $value) {
			$resp[] = array('id'=>$value['id'].'_'.$value['comprobante'].'_'.$value['interes'],'text'=>$value['detalle']);
		}
		return $resp;
	}

	// ingresa abonos y genera cuotas a una factura
	function add_abono($parametros)
	{
		$cu = $this->modelo->cuotas_factura($parametros['fac']);
		// print_r($cu);die();
		if(!empty($cu))
		{
			$total = 0;
			$num_cu = 0;
			$total_fac = -1;
			foreach ($cu as $key => $value) {
				if(number_format($value['cuota'],2,'.','')<= number_format($parametros['monto'],2,'.',''))
				{
					// print_r($parametros);die();
		             $datos[0]['campo'] ='factura_id' ;
		             $datos[0]['dato'] = $parametros['fac'];
		             $datos[1]['campo'] = 'tipo_pago';
		             $datos[1]['dato'] = $parametros['pago'];
		             $datos[2]['campo'] ='monto' ;
		             $datos[2]['dato'] = number_format($parametros['falt']+$parametros['monto']-$value['cuota'],2,'.','');
		             $datos[3]['campo'] = 'abono';
		             $datos[3]['dato'] = number_format($value['cuota'],2,'.','');
		             $datos[4]['campo'] = 'num_cheqDep';
		             $datos[4]['dato'] =$parametros['cheqcomp'] ;
		             $datos[5]['campo'] = 'fecha';
		             $datos[5]['dato'] = $parametros['fecha'];
		             $datos[6]['campo'] = 'cuota';
		             $datos[6]['dato'] = 'null';
		             $datos[7]['campo'] = 'estado_abono';
		             $datos[7]['dato'] = 'CO';
		             $datos[8]['campo'] = 'banco';
		             $datos[8]['dato'] = $parametros['banco'];
		             $datos[9]['campo'] = 'fecha_cuota';
		             $datos[9]['dato'] = $value['fecha_cuota']->format('Y-m-d');

		        if(number_format($parametros['falt'],2,'.','')==0)
		        {
			        $datos1[0]['campo']='estado_pago';
			        $datos1[0]['dato']='PA';
			        $where[0]['campo']='id_factura';
			        $where[0]['dato']=$parametros['fac'];
			        $this->modelo->update('facturas',$datos1,$where);
			        $total_fac = 1;
		        }
		        $where[0]['campo'] ='id_abonos'; 
		        $where[0]['dato'] = $value['id_abonos'];
		        $this->modelo->eliminar_abono($value['id_abonos']);
		        // $resp = $this->modelo->update('abonos',$datos,$where);   
		        $resp = $this->modelo->guardar($datos,'abonos');
		        $parametros['monto'] =number_format($parametros['monto']-$value['cuota'],2,'.','');

		       }else
		       {
		       	// print_r('expression');die();
		       	if($parametros['monto']!=0)
		       	{
		       	     $datos2[0]['campo'] ='factura_id' ;
		             $datos2[0]['dato'] = $parametros['fac'];
		             $datos2[1]['campo'] = 'tipo_pago';
		             $datos2[1]['dato'] = $parametros['pago'];
		             $datos2[2]['campo'] ='monto' ;
		             $datos2[2]['dato'] = $parametros['falt'];
		             $datos2[3]['campo'] = 'abono';
		             $datos2[3]['dato'] = number_format($parametros['monto'],2,'.','');
		             $datos2[4]['campo'] = 'num_cheqDep';
		             $datos2[4]['dato'] =$parametros['cheqcomp'] ;
		             $datos2[5]['campo'] = 'fecha';
		             $datos2[5]['dato'] = $parametros['fecha'];
		             $datos2[6]['campo'] = 'fecha_cuota';
		             $datos2[6]['dato'] = $value['fecha_cuota']->format('Y-m-d');
		             // print_r($datos2);die();
		             $resp = $this->modelo->guardar($datos2,'abonos');

		             $datos1[0]['campo']='cuota';
		             $datos1[0]['dato']= number_format($value['cuota']-$parametros['monto'],2,'.','');

		             $where[0]['campo'] ='id_abonos'; 
		             $where[0]['dato'] = $value['id_abonos'];
		             // print_r($datos1);die();
		             $resp = $this->modelo->update('abonos',$datos1,$where);
		       	     break;
		        }		         
		       }		
			}
			if($total_fac ==1)
		         {
			         return 2;
		         }else
		         {
		         	return 1;
		         }  

			
		}else{
		$total_fac = -1;
		$datos[0]['campo'] ='factura_id' ;
		$datos[0]['dato'] = $parametros['fac'];
		$datos[1]['campo'] = 'tipo_pago';
		$datos[1]['dato'] = $parametros['pago'];
		$datos[2]['campo'] ='monto' ;
		$datos[2]['dato'] = number_format($parametros['falt'],2,'.','');
		$datos[3]['campo'] = 'abono';
		$datos[3]['dato'] = number_format($parametros['monto'],2,'.','');
		$datos[4]['campo'] = 'num_cheqDep';
		$datos[4]['dato'] =$parametros['cheqcomp'] ;
		$datos[5]['campo'] = 'fecha';
		$datos[5]['dato'] = $parametros['fecha']; 
		$datos[6]['campo'] = 'estado_abono';
		$datos[6]['dato'] = 'CO';
		$datos[7]['campo'] = 'banco';
		$datos[7]['dato'] = $parametros['banco'];

		if(number_format($parametros['falt'],2)==0)
		{
			$datos1[0]['campo']='estado_pago';
			$datos1[0]['dato']='PA';
			$where[0]['campo']='id_factura';
			$where[0]['dato']=$parametros['fac'];
			$this->modelo->update('facturas',$datos1,$where);
			$total_fac = 1;
		}

		$resp = $this->modelo->guardar($datos,'abonos');
		if($resp==1 && $total_fac ==1)
		{
			return 2 ;
		}else if($resp==1 && $total_fac==-1)
		{
			return 1;

		}else
		{
			return -1;
		}
	 }
		// print_r($parametros);die();

	}

	// elimina  abonos realizados a una factura
	function delete_abono($id)
	{
		$datos1 = $this->modelo->es_cuota($id);
		// print_r($datos1);
		// print_r($padre);
		// die();
		if(empty($datos1))
		{
			return $this->modelo->eliminar_abono($id);
		}else
		{		

				$this->modelo->eliminar_abono($id);
		  	$padre = $this->modelo->cuota_padre($datos1[0]['factura_id'],$datos1[0]['fecha_cuota']->format('Y-m-d'));
		  	if(empty($padre))
		  	{
		  		$datos[0]['campo'] ='cuota' ;
		      $datos[0]['dato'] =  number_format($datos1[0]['abono'],2,'.','');
			    $datos[1]['campo'] = 'tipo_pago';
			    $datos[1]['dato'] = 'null';
			    $datos[2]['campo'] ='monto' ;
			    $datos[2]['dato'] = 'null';
			    $datos[3]['campo'] = 'abono';
			    $datos[3]['dato'] = 'null';
			    $datos[4]['campo'] = 'num_cheqDep';
			    $datos[4]['dato'] ='null';
			    $datos[5]['campo'] = 'estado_abono';
			    $datos[5]['dato'] = 'XC';
			    $datos[6]['campo'] = 'fecha_cuota';
			    $datos[6]['dato'] = $datos1[0]['fecha_cuota']->format('Y-m-d');
			    $datos[7]['campo'] = 'factura_id';
			    $datos[7]['dato'] = $datos1[0]['factura_id'];

				return $this->modelo->guardar($datos,'abonos');

		  		
		  	}else{
				  $datos[0]['campo'] ='cuota' ;
			    $datos[0]['dato'] =  number_format($datos1[0]['abono']+$padre[0]['cuota'],2,'.','');
			    // $datos[1]['campo'] = 'tipo_pago';
			    // $datos[1]['dato'] = 'null';
			    // $datos[2]['campo'] ='monto' ;
			    // $datos[2]['dato'] = 'null';
			    // $datos[3]['campo'] = 'abono';
			    // $datos[3]['dato'] = 'null';
			    // $datos[4]['campo'] = 'num_cheqDep';
			    // $datos[4]['dato'] ='null';
			    // $datos[5]['campo'] = 'fecha';
			    // $datos[5]['dato'] = 'null';
			    // $datos[6]['campo'] = 'estado_abono';
			    // $datos[6]['dato'] = 'XC';

					$where[0]['campo']='id_abonos';
					$where[0]['dato']=$padre[0]['id_abonos'];

				// print_r($datos);die();
				return $this->modelo->update('abonos',$datos,$where);
		  }

		}
	}

	// funcion que cruza cheques registrados con cuotas por pagar
	function cruzar_cheque($id)
	{
		$datos  = $this->modelo->cheques_pos_fecha_cru($id);
		$datosF = $this->modelo->abonos_factura($datos[0]['factura']);
		// $datosT = $this->modelo->forma_pago('cheque');

		// print_r($datosF);die();
		$parametros = array(
		'fecha'=>$datos[0]['cheques_fecha']->format('Y-m-d'),
    'monto'=>number_format($datos[0]['cheque_monto'],2),
    'cheqcomp'=>$datos[0]['cheques_num'],
    'pago'=>'Cheque pos fechado a la fecha',
    'fac'=>$datos[0]['factura'],
    'falt'=>number_format($datosF[0]['monto'],2)-number_format($datos[0]['cheque_monto'],2),
    'banco'=>$datos[0]['cheques_banco'],
);
		$this->modelo->eliminar_cheque_pos($id);
		return $this->add_abono($parametros);
	}
}
?>