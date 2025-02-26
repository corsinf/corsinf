<?php 
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/trabajosM.php');
require_once(dirname(__DIR__, 2) .'/lib/Reporte_pdf.php');
/**
 * 
 */

$controlador = new trabajosC();
if(isset($_GET['trabajos']))
{
	echo json_encode($controlador->cargar_trabajos());
}

if(isset($_GET['trabajos_ingreso']))
{
	$parametros = $_POST;
	echo json_encode($controlador->trabajos_ingreso($parametros));
}
if(isset($_GET['Articulos_imagen']))
{
	// $parametros = $_POST;
	// print_r($parametros);die();
	echo json_encode($controlador->agregar_articulo_foto($_FILES,$_POST));
}
if(isset($_GET['material']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->material($query));
}
if(isset($_GET['estado_joya']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->estado_joya($query));
}
if(isset($_GET['reporte']))
{
	// $parametros = $_POST['parametros'];
	$query = $_GET['orden'];
	$detalle = $_GET['detalle'];
	echo json_encode($controlador->reporte($query,$detalle));
}

class trabajosC
{
	private $modelo;
	private $pagina;
	private $pdf;
		
	function __construct()
	{
		$this->modelo = new trabajosM();
		$this->pagina = new codigos_globales();
		$this->pagina->registrar_pagina_creada('../vista/trabajos.php','Trabajos en joyas','6','estado');
		$this->pdf = new Reporte_pdf(); 
	}
	function cargar_trabajos()
	{
		$datos = $this->modelo->lista_trabajos($query=false);
		// print_r($datos);die();
		$ocultar = array('id','id_detalle_trabajo');
		// $botones[0] =array('boton'=>'Eliminar linea','icono'=>'<i class="fas fa-save nav-icon"></i>','tipo'=>'danger','id'=>'id');
		$cabecera = array('Codigo','Articulos','Fecha Ing','Peso(g)','Precio','Tipo joya','Material','Cliente','Estado','Trabajo a realizar');
		$enlace[0] = array('posicion'=>1,'link'=>'nuevo_trabajo_joya.php','get'=>array('nombre'=>'orden,detalle','valor'=>'id,id_detalle_trabajo'));
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones=false,false,$ocultar,$foto=false,false,$enlace);

		return $tabla;
	}
	function trabajos_ingreso($parametros)
	{
		// print_r($parametros);die();

			$datos[0]['campo']='detalle_producto';
		    $datos[0]['dato']=$parametros['txt_nom_art'];
		    $datos[1]['campo']='precio_producto';
		    $datos[1]['dato']=$parametros['txt_pvp'];
		    $datos[2]['campo']='stock_producto';
		    $datos[2]['dato']=1;
		    $datos[3]['campo']='id_categoria';
		    $datos[3]['dato']=$parametros['ddl_tipo_joya'];
		    if($parametros['txt_id_tra']=='')
		    {
		    if($parametros['txt_codigo']!='')
		    {
		    $datos[4]['campo']='referencia_producto';
		    $datos[4]['dato']='T'.$parametros['txt_codigo'];
		     $res = $this->modelo->producto_trabajo($datos[4]['dato']);
		     if(count($res)>0)
		     {
		     	 $datos[4]['dato'] = 'TEF'.$this->pagina->agregar_ceros(9,$this->modelo->nuevo_codigo());
		     }
		    }else
		    { 
		     $datos[4]['campo']='referencia_producto';
		     $datos[4]['dato']='TEF'.$this->pagina->agregar_ceros(9,$parametros['txt_cod_joya']);
		     $res = $this->modelo->producto_trabajo($datos[4]['dato']);
		     if(count($res)>0)
		     {
		     	 $datos[4]['dato'] = 'TEF'.$this->pagina->agregar_ceros(9,$this->modelo->nuevo_codigo());
		     }
		    }
		    }else
		    {
		    	$datos[4]['campo']='referencia_producto';
		        $datos[4]['dato']=$parametros['txt_cod_an'];
		    }

		    $datos[5]['campo']='trabajo';
		    $datos[5]['dato']=1;
		    $datos[6]['campo']='fecha_ingreso';
		    $datos[6]['dato']=$parametros['txt_fecha_ing'];
		    $datos[7]['campo']='material';
		    $datos[7]['dato']=$parametros['ddl_material'];
		    $datos[8]['campo']='peso';
		    $datos[8]['dato']=$parametros['txt_peso'];
		    $datos[9]['campo']='bodega';
		    $datos[9]['dato']=$parametros['ddl_bodega'];
		    $datos[10]['campo']='estado_ingreso';
		    $datos[10]['dato']=$parametros['ddl_estado_joya'];
		    $datos[11]['campo']='descripcion_trabajo';
		    $datos[11]['dato']=$parametros['txt_descripcion'];
		    $datos[12]['campo']='id_proveedor';
		    $datos[12]['dato']=$parametros['txt_pro_id'];
		    $datos[13]['campo']='estado_trabajo';
		    $datos[13]['dato']=1;
		    $datos[14]['campo']='id_punto';
		    $datos[14]['dato']=$_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO'];
		    $datos[15]['campo']='id_maestro';
		    $datos[15]['dato']=$parametros['txt_id_ma'];
		    if(isset($parametros['foto']))
		    {		    
		    $datos[16]['campo']='foto_producto';
		    $datos[16]['dato']=$parametros['foto'];
		    }

		// print_r($datos);die();

		    if($parametros['txt_id_tra']=='')
		    {
		     $resp = $this->modelo->guardar($datos,'productos');
		    }else
		    {

		    	$where1[0]['campo']='id_producto';
		    	$where1[0]['dato']=$parametros['txt_id_tra'];
		    		// print_r($datos);print_r($where1); die();
		    	$resp = $this->modelo->update('productos',$datos,$where1);;
		    }
		    if($resp==1)
		    {
		    	return  $this->modelo->producto_trabajo($datos[4]['dato']);
		    }else
		    {
		    	return -1;
		    }
		// print_r($datos);die();

	}
	function material($query)
	{
		$datos = $this->modelo->lista_material($query);
		$opciones = array();
		foreach ($datos as $key => $value) {
			$opciones[] = array('id'=>$value['id'],'text'=>$value['nombre']);		
		}
		return $opciones;
	}
	function estado_joya($query)
	{
		$datos = $this->modelo->lista_estado_joya($query);
		$opciones = array();
		foreach ($datos as $key => $value) {
			$opciones[] = array('id'=>$value['id'],'text'=>$value['nombre']);		
		}
		return $opciones;
	}

   function agregar_articulo_foto($file,$post,$tipo=false)
   {
   	// print_r($post);die();
   	$id_detalle =  $post['txt_id_det_tra'];
   	$id_producto  = $post['txt_id_tra'];
   	$nombre ='';
   	$num_ima =1;
   	$referen = $post['txt_cod_an'];
   	$num_Reg = count($file['file_img1']['name']);
   	$ruta='../img/trabajos/';//ruta carpeta donde queremos copiar las imágenes
   	if (!file_exists($ruta)) {
       mkdir($ruta, 0777, true);
    }

   	if($file['file_img1']['tmp_name'][0]!='')
   	{
   		if($file['file_img1']['type'][0]=="image/jpeg" || $file['file_img1']['type'][0]=="image/pjpeg" || $file['file_img1']['type'][0]=="image/gif" || $file['file_img1']['type'][0]=="image/png")
   		{
   			$uploadfile_temporal=$file['file_img1']['tmp_name'][0];
   			$tipo = explode('/', $file['file_img1']['type'][0]); 
   			         $nombre = $post['txt_cod_an'].'.'.$tipo[1];
   			         if($post['txt_cod_an']=='')
   			         {
   			         if($post['txt_codigo']!='')
   				     {  
   					     $nombre = 'T'.$post['txt_codigo'].'.'.$tipo[1];
   		             	 $referen = 'T'.$post['txt_codigo'];
   				     }else
   				     {
   					     $nombre = 'TEF'.$post['txt_cod_joya'].'.'.$tipo[1];
   		             	 $referen = 'TEF'.$post['txt_cod_joya'];
   				     }
   				    }
   				$nuevo_nom=$ruta.$nombre;

   	            if (is_uploaded_file($uploadfile_temporal))
   	            {
   		             move_uploaded_file($uploadfile_temporal,$nuevo_nom);
   		             if($post['txt_id_tra']!='')
   		             {
   		             	$this->trabajos_ingreso($post);
   	                     $datos[0]['campo']='foto_producto';
   	                     $datos[0]['dato']=$nuevo_nom;	                		
   		                 $where1[0]['campo']='id_producto';
   		                 $where1[0]['dato']=$post['txt_id_tra'];
   		                 $this->modelo->update('productos',$datos,$where1);

   		             }else
   		             {
   		             	$post['foto'] = $nuevo_nom;
   		                $base = $this->trabajos_ingreso($post);
   	                    $id_producto =$base[0]['id_producto'];
   		             }
   		             $this->save_more_pic($id_producto,$id_detalle,$file,$referen);   
   		             return  $this->modelo->producto_trabajo(false,$id_producto);
   		             // print_r($post);print_r($file);die();   		              
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
     $this->save_more_pic($id_producto,$id_detalle,$file,$referen);     

    return  $this->modelo->producto_trabajo(false,$id_producto);
  }

  function reporte($id,$detalle)
  {
  	$cabecera = $this->modelo->producto_trabajo_detalle($query=false,$id);
  	$deta = $this->modelo->producto_trabajo($codigo=false,$id);
  	// print_r($cabecera);die();
  	$this->pdf->trabajo_joya_nuevo($cabecera,$deta);
  }

  function save_more_pic($id_producto,$id_detalle,$file,$referencia)
  {
  	$sinproblema =false;
  	$ruta='../img/trabajos/';//ruta carpeta donde queremos copiar las imágenes
  	$num_Reg = count($file['file_img1']['name']);
   	if (!file_exists($ruta)) {
       mkdir($ruta, 0777, true);
    }
    $datos[0]['campo']='id_producto';
    $datos[0]['dato'] =$id_producto;
  	for ($i=1; $i < $num_Reg; $i++) { 
  		
        if($file['file_img1']['tmp_name'][$i]!='')
        	{
        		if($file['file_img1']['type'][$i]=="image/jpeg" || $file['file_img1']['type'][$i]=="image/pjpeg" || $file['file_img1']['type'][$i]=="image/gif" || $file['file_img1']['type'][$i]=="image/png")
   		{
   			$uploadfile_temporal=$file['file_img1']['tmp_name'][$i];
   			$tipo = explode('/', $file['file_img1']['type'][$i]); 
   			         $nombre = $referencia.'_'.$i.'.'.$tipo[1];
   			         
   				$nuevo_nom=$ruta.$nombre;

   	            if (is_uploaded_file($uploadfile_temporal))
   	            {
   		             move_uploaded_file($uploadfile_temporal,$nuevo_nom);
   	            }
   	            else
   	            {
   		            return -1;
   	            } 
        }else
        {
     	    return -2;
        }
         $datos[$i]['campo']='foto'.$i;
         $datos[$i]['dato'] =$nuevo_nom;
     }
    
  	}

  	if($id_detalle=='')
  	{
  		$this->modelo->guardar($datos,$tabla='detalle_trabajo');
  	}else
  	{
  		$where[0]['campo'] = 'id_detalle_trabajo';
  		$where[0]['dato'] = $id_detalle;
  		$this->modelo->update($tabla='detalle_trabajo',$datos,$where);
  	}
  }
}

?>