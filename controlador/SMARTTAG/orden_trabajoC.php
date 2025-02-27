<?php 

require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/orden_trabajoM.php');
require_once(dirname(__DIR__, 2) .'/modelo/materialesM.php');
require_once(dirname(__DIR__, 2) .'/lib/Reporte_pdf.php');

/**
 * 
 */
$controlador = new orden_trabajoC();
if(isset($_GET['lineas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_orden($parametros),JSON_UNESCAPED_UNICODE );
}
if(isset($_GET['boceto']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->detalle_boceto($parametros));
}
if(isset($_GET['validar_produccion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->validar_produccion($parametros));
}
if(isset($_GET['editar_cabecera']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->editar_cabecera($parametros));
}
if(isset($_GET['ordenes']))
{
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->ordenes());
}
if(isset($_GET['new_order']))
{
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->new_order());
}
if(isset($_GET['add_linea']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_linea($parametros));
}
if(isset($_GET['detalle_diseño']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->detalle_diseño($parametros));
}
if(isset($_GET['eliminar_linea']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_linea($parametros));
}
if(isset($_GET['eliminar_imagen']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_imagen($parametros));
}
if(isset($_GET['search_maestro']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->autocompletar_maestro($query));

}
if(isset($_GET['Articulos_imagen']))
{
	// $parametros = $_POST;
	// print_r($parametros);die();
	echo json_encode($controlador->agregar_articulo_foto($_FILES,$_POST));
}
if(isset($_GET['finalizar_orden']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->finalizar_orden($parametros));
}
if(isset($_GET['reporte']))
{
	// $parametros = $_POST['parametros'];
	$query = $_GET['orden'];
	echo json_encode($controlador->reporte($query));
}
class orden_trabajoC
{
	private $modelo;
	private $pagina;
	private $material;
	private $pdf;
	function __construct()
	{
		$this->modelo = new orden_trabajoM();
		$this->pagina = new codigos_globales();
		$this->material = new  materialesM();
		$this->pagina->registrar_pagina_creada('../vista/orden_trabajo.php','Orden de trabajo','6','');
		$this->pdf = new Reporte_pdf(); 
	}


	function ordenes()
	{
		$datos = $this->modelo->ordenes();
	    $cabecera = array('Encargado','No.Orde','Fecha inicio','Fecha fin','Punto de venta','estado','Registrado por');
		$ocultar = array('id'=>'id','detalle_bodega','bodega_destino','maestro','boceto','idma','codigo');
		$posicion = array('L','L','L','L','L','L','L');
		$boton[0] = array('boton' =>'ver','icono'=>'<i class="fas fa-eye nav-icon"></i>','tipo'=>'default','id'=>'id,estado_orden' );
		
		
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$boton,false,$ocultar,false,$posicion);
		return $tabla;

	}

	function lineas_orden($parametros)
	{
		$datos = $this->modelo->lineas_orden_trabajo($parametros['idorden']);
		$fac = $this->modelo->ordenes($numero = false,$parametros['idorden']);
	    $cabecera = array('Referencia','Producto','cant','Detalle');
		$ocultar = array('id'=>'id','numero_orden','Encargado');
		$posicion = array('L','L','L','L');
		if($parametros['estado']=='' || $parametros['estado']=='P')
		{
		  $boton[0] = array('boton' =>'Eliminar','icono'=>'<i class="fas fa-trash nav-icon"></i>','tipo'=>'danger','id'=>'id' );
		}else{$boton=false;}
		$li = count($datos);
		
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$boton,false,$ocultar,false,$posicion);
		if(empty($fac))
		{
			$num = '';
			$en = '';

		}else
		{
			$num = $fac[0]['numero_orden'];
			$en = $fac[0]['Encargado'];
			$punto = $fac[0]['nombre_punto'];
			$idbo = $fac[0]['bodega_destino'];
			$bode = $fac[0]['detalle_bodega'];
			$idma = $fac[0]['idma'];
			$maes = $fac[0]['maestro'];
			$dise = $fac[0]['boceto'];
			$est = $fac[0]['estado_orden'];
			if($fac[0]['fecha_exp']=='')
			{
				$fac[0]['fecha_exp'] = date('Y-m-d');
			}
			$fecha2 = $fac[0]['fecha_exp'];

			if($fac[0]['fecha_orden']=='')
			{
				$fac[0]['fecha_orden'] = date('Y-m-d');
			}
			$fecha = $fac[0]['fecha_orden'];

		}
		// print_r($datos);die();

		$datos1 = array('tbl'=>$tabla,'num_li'=>$li,'num'=>$num,'encargado'=>$en,'punto'=>$punto,'idbo'=>$idbo,'bode'=>$bode,'fecha_ex'=>$fecha2,'idma'=>$idma,'maestro'=>$maes,'boceto'=>$dise,'fecha'=>$fecha,'estado'=>$est);
		return $datos1;
	}
	function add_linea($parametros)
	{
		$this->modelo->eliminar_de_diseño($parametros['orden']);
		$pro = $this->modelo->producto_select($parametros['ref'],$parametros['bodega']);
		// print_r($parametros);die();
		$datos[0]['campo'] = 'id_factura';
		$datos[0]['dato'] = $parametros['orden'];
		$datos[1]['campo'] = 'producto';
		$datos[1]['dato'] = $parametros['art'];
		$datos[2]['campo'] = 'cantidad';
		$datos[2]['dato'] = $parametros['cant'];
		$datos[3]['campo'] = 'codigo_ref';
		$datos[3]['dato'] = $parametros['ref'];
		$datos[4]['campo'] = 'linea_detalle';
		$datos[4]['dato'] = $parametros['detalle'];
		$datos[5]['campo'] = 'id_producto';
		$datos[5]['dato'] = $pro[0]['id_producto'];
		$resp = $this->modelo->guardar($datos,'lineas_orden');

		$datos1[0]['campo'] = 'boceto';
		$datos1[0]['dato'] = 0;
		
		$where[0]['campo'] = 'id_orden'; 
		$where[0]['dato'] = $parametros['orden'];
		$this->modelo-> update('orden_trabajo',$datos1,$where);
		return $resp;
		// print_r($datos);die();

	}

	function eliminar_linea($parametros)
	{
		return $this->modelo->delete($parametros['linea']);
	}
	function eliminar_imagen($parametros)
	{
		$datos[0]['campo'] = 'foto'.$parametros['posicion'];
		$datos[0]['dato'] = '';

		$where[0]['campo'] = 'id_trabajo';
		$where[0]['dato'] = $parametros['id'];

		$tabla = 'detalle_trabajo';
		
		return $this->modelo->eliminar_imagen($tabla,$datos,$where);
	}
	function validar_produccion($parametros)
	{
		// print_r($parametros);die();
		$materia = $this->modelo->datos_produccion($parametros['refe'],$parametros['bode']);
		$cumple = 1;
		if(count($materia)>0)
		{
		foreach ($materia as $key => $value) {
			$pedir = $value['cantidad']*$parametros['cant'];
			$stock = $this->modelo->stock_material($value['id_materia_prima']);
			if($pedir>$stock[0]['stock_producto'])
			{
				$cumple = -1;
			}
		}
       }else
       {
       	 return 2;
       }
	   return $cumple;

	}

	function new_order()
	{
		$num = $this->modelo->num_orden();
		$n = 1;
		if(count($num)>0)
		{
		   $datos[0]['campo'] = 'numero_orden';
		   $datos[0]['dato'] = $num[0]['num']+1;		   
		   $datos[1]['campo'] = 'fecha_orden';
		   $datos[1]['dato'] = date('Y-m-d');		   
		   $datos[2]['campo'] = 'punto_venta';
		   $datos[2]['dato'] = $_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO'];		   
		   $datos[3]['campo'] = 'id_usuario';
		   $datos[3]['dato'] = $_SESSION['INICIO']['ID']; 
		   $datos[4]['campo'] = 'codigo';
		   $datos[4]['dato'] = 'ORD'.$this->pagina->agregar_ceros(7,$datos[0]['dato']);
		   
		   $resp = $this->modelo->guardar($datos,'orden_trabajo');

		   // print_r($resp);die();
		   $idor = $this->modelo->ordenes($datos[0]['dato']);
		   return $idor[0]['id'];
		}else
		{
		   $datos[0]['campo'] = 'numero_orden';
		   $datos[0]['dato'] = $n;		   
		   $datos[1]['campo'] = 'fecha_orden';
		   $datos[1]['dato'] = date('Y-m-d');		   
		   $datos[2]['campo'] = 'punto_venta';
		   $datos[2]['dato'] = $_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO'];		   
		   $datos[3]['campo'] = 'id_usuario';
		   $datos[3]['dato'] = $_SESSION['INICIO']['ID'];		   	   
		   $datos[4]['campo'] = 'codigo';
		   $datos[4]['dato'] = 'ORD'.$this->pagina->agregar_ceros(7,$n);
		   $resp = $this->modelo->guardar($datos,'orden_trabajo');
		   $idor = $this->modelo->ordenes($datos[0]['dato']);
		   return $idor[0]['id'];
		}
	}

	function editar_cabecera($parametros)
	{
		// print_r($parametros);
		   $datos[0]['campo'] = 'fecha_exp';
		   $datos[0]['dato'] = $parametros['fecha'];		   
		   $datos[1]['campo'] = 'Encargado';
		   $datos[1]['dato'] =  $parametros['encargado'];		   
		   $datos[2]['campo'] = 'bodega_destino';
		   $datos[2]['dato'] = $parametros['bodega'];		   
		   $datos[3]['campo'] = 'maestro';
		   $datos[3]['dato'] = $parametros['maestro'];			   
		   

		   $where[0]['campo'] = 'id_orden';
		   $where[0]['dato'] = $parametros['id'];
		   $resp = $this->modelo->update('orden_trabajo',$datos,$where);
		   return $resp;

	}
	function autocompletar_maestro($query)
	{
		$datos = $this->modelo->lista_de_maestros($query);
		$result = array();
		foreach ($datos as $key => $value) {
			 $result[] = array("value"=>$value['id'],"label"=>$value['nombre']);
		}
		return $result;
	}

   function agregar_articulo_foto($file,$post,$tipo=false)
   {
      // print_r($file);
   	  // print_r($post);die();

   	$num_Reg = count($file['file_img']['name']);
   	$ruta='../img/trabajos/';//ruta carpeta donde queremos copiar las imágenes
   	if (!file_exists($ruta)) {
       mkdir($ruta, 0777, true);
    }
   	for ($i=0; $i < $num_Reg; $i++) { 

   		if($file['file_img']['tmp_name'][$i]!='')
   		{
   			if($file['file_img']['type'][$i]=="image/jpeg" || $file['file_img']['type'][$i]=="image/pjpeg" || $file['file_img']['type'][$i]=="image/gif" || $file['file_img']['type'][$i]=="image/png")
   				{

   					$uploadfile_temporal=$file['file_img']['tmp_name'][$i];
   					$tipo = explode('/', $file['file_img']['type'][$i]);   
   					$nombre = 'FotoTrabajo_'.$post['num_ord'].'_'.$i.'.'.$tipo[1];
   					$nuevo_nom=$ruta.$nombre;

   	                if (is_uploaded_file($uploadfile_temporal))
   	                {
   		                move_uploaded_file($uploadfile_temporal,$nuevo_nom);
   		                $this->pagina->reducir_img($nuevo_nom,$ruta,$nombre);
   		                $base = $this->modelo->img_guardar($nuevo_nom,$post['txt_id_detalle_trabajo'],$posicion=$i,$post['num_ord']);
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
     }
     return 1;
  }

  function detalle_boceto($parametros)
  {
  	 $datos = $this->modelo->detalle_boceto($parametros['idorden']);
  	 $ma = explode(',',$datos[0]['material']);
  	 $option = '';
  	 if($datos[0]['material']!='')
  	 {
	foreach ($ma as $key1 => $value1) {
           $mate = $this->material->lista_categoria(false,$value1);
           $option.='<option value="'.$mate[0]['id'].'" selected="selected">'.$mate[0]['nombre'].'</option>'; 
	}
   }
	$datos[0]['opcion']=$option;
  	return $datos;
  }

  function detalle_diseño($parametros)
  {

  	$this->modelo->eliminar_de_producto($parametros['id']);
  	$mate = '';
  	 foreach ($parametros['material'] as $key => $value) {
  	 	$mate.= $value.',';
  	 }
  	   $mate = substr($mate, 0,-1);
  	   $datos[0]['campo'] = 'modelo';
		$datos[0]['dato'] = $parametros['modelo'];
  	   $datos[2]['campo'] = 'material';
		$datos[2]['dato'] = $mate;
  	   $datos[3]['campo'] = 'observacion';
		$datos[3]['dato'] = $parametros['observacion'];
  	   $datos[4]['campo'] = 'medida';
		$datos[4]['dato'] = $parametros['medida'];
  	   $datos[5]['campo'] = 'id_trabajo';
		$datos[5]['dato'] = $parametros['id'];
  	  
		$where[0]['campo'] = 'id_detalle_trabajo';
		$where[0]['dato'] = $parametros['idDT'];
		// print_r($where);die();
		$tabla = 'detalle_trabajo';

		if($parametros['idDT']=='')
		{ 
	     $datos1[0]['campo'] = 'boceto';
		  $datos1[0]['dato'] = 1;
  	  
		  $where1[0]['campo'] = 'id_orden';
		  $where1[0]['dato'] = $parametros['id'];
		  $this->modelo->update('orden_trabajo',$datos1,$where1);
			return $this->modelo->guardar($datos,$tabla);
		}else
		{
		  return $this->modelo->update($tabla,$datos,$where);
		}

  }

  function finalizar_orden($parametros)
  {
  	      $datos[0]['campo'] = 'estado_orden';
		   $datos[0]['dato'] = 'F';		   

		   $where[0]['campo'] = 'id_orden';
		   $where[0]['dato'] = $parametros['id'];

		   $ord = $this->modelo->ordenes($numero=false,$parametros['id']);

  	// print_r( $ord );die();
		   if($ord[0]['boceto']=='0' || $ord[0]['boceto']=='')
		   {
		   	$li = $this->modelo->lineas_orden_trabajo($parametros['id']);
		   	foreach ($li as $key => $value) {
		   		$prima = $this->modelo->datos_produccion($value['codigo_ref'],$ord[0]['bodega_destino']);
		   		foreach ($prima as $key2 => $value2) {
		   			// print_r($value2);die();
		   			$datos1[0]['campo'] = 'default_pro';
		  				$datos1[0]['dato'] = 1;
		  				$datos1[1]['campo'] = 'id_materia_prima';
		  				$datos1[1]['dato'] = $value2['id_materia_prima'];
		  				$datos1[2]['campo'] = 'cantidad';
		  				$datos1[2]['dato'] = number_format($value['cantidad']*$value2['cantidad'],2,'.','');
		  				$datos1[3]['campo'] = 'id_orden';
		  				$datos1[3]['dato'] = $parametros['id'];
		  				$this->modelo->guardar($datos1,'datos_produccion');
		   		}
		   		
		   	}

		   }
		   $resp = $this->modelo->update('orden_trabajo',$datos,$where);

		   return $resp;

  }
  function reporte($id)
  {
  	$cabecera= $this->modelo->ordenes($numero = false,$id);
  	$detalle = $this->modelo->lineas_orden_trabajo_detalle($id);
  	$detalle_di = $this->modelo->detalle_boceto($id);
  	if(count($detalle_di)>0)
  	{
  	$ma = explode(',', $detalle_di[0]['material']);
  	$m = '';
  	foreach ($ma as $key => $value) {
  		$material = $this->material->lista_categoria(false,$value);
  		$m.=$material[0]['nombre'].','; 
  	}
  	$detalle_di[0]['nombre_material'] = $m;
  }
  
  	// print_r($cabecera);die();
  	$this->pdf->orden_trabajo_nuevo($cabecera,$detalle,$detalle_di);
  }


}
?>