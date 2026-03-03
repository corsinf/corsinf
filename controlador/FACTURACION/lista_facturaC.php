<?php
require_once(dirname(__DIR__,2).'/modelo/FACTURACION/lista_facturaM.php');
require_once(dirname(__DIR__,2).'/modelo/FACTURACION/secuencialesM.php');
require_once(dirname(__DIR__,2).'/modelo/FACTURACION/categoriasM.php');
require_once(dirname(__DIR__,2).'/modelo/FACTURACION/tabla_referenciales_sriM.php');
// require_once('../modelo/loginM.php');
/**
 * 
 */
$controlador = new lista_facturaC();

if(isset($_GET['generar_factura']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->session($parametros));
}

if(isset($_GET['lista_facturas']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->lista_facturas($parametros));
}
if(isset($_GET['lista_serie']))
{
    // $parametros = $_POST['parametros'];
    echo json_encode($controlador->lista_series());
}

if(isset($_GET['detalle_factura']))
{
    $factura = $_POST['id'];
    echo json_encode($controlador->lineas_facturas($factura));
}
// if(isset($_GET['lineas_facturas']))
// {
//     $parametros = $_POST['parametros'];
//     echo json_encode($controlador->lineas_facturas($parametros));
// }
if(isset($_GET['cliente_factura']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cliente_factura($parametros));
}
if(isset($_GET['buscar_articulo']))
{
  // print_r($_POST);die();
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->articulos($parametros));
}
if(isset($_GET['add_articulo']))
{
  // print_r($_POST);die();
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->add_articulos($parametros));
}
if(isset($_GET['editar_linea']))
{
  // print_r($_POST);die();
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->editar_linea($parametros));
}
if(isset($_GET['cargar_linea']))
{
  // print_r($_POST);die();
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_linea($parametros));
}
if(isset($_GET['delete_linea']))
{
  // print_r($_POST);die();
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->delete_linea($parametros));
}
if(isset($_GET['facturar']))
{
  // print_r($_POST);die();
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->facturar($parametros));
}
if(isset($_GET['editar_cliente']))
{
  $parametros = $_POST['parametros'];
  echo json_encode($controlador->editar_cliente($parametros));
}

if(isset($_GET['nueva_factura']))
{
  $parametros = $_POST['parametros'];
  echo json_encode($controlador->nueva_factura($parametros));
}
if(isset($_GET['categorias']))
{
  $query = '';
  if(isset($_GET['q']))
  {
    $query = $_GET['q'];
  }
  echo json_encode($controlador->categorias($query));
}

if(isset($_GET['enviar_email']))
{   
  $parametros = $_POST['parametros'];
  echo json_encode($controlador->enviar_email($parametros));
}

if(isset($_GET['reporte_factura']))
{   
  $parametros = $_GET;
  echo json_encode($controlador->reporte_factura($parametros));
}
if(isset($_GET['error_sri']))
{   
  $parametros = $_POST['parametros'];
  echo json_encode($controlador->error_sri($parametros));
}
if(isset($_GET['DCTipoPago']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCTipoPago());
}

if(isset($_GET['eliminar_fac']))
{
  $parametros = $_POST['parametros'];
   echo  json_encode($controlador->eliminarFac($parametros));
}

if(isset($_GET['anular_factura']))
{
  $parametros = $_POST['id'];
   echo  json_encode($controlador->anular_factura($parametros));
}

if(isset($_GET['editar_serie']))
{
  $parametros = $_POST['parametros'];
   echo  json_encode($controlador->editar_serie($parametros));
}
if(isset($_GET['pagada']))
{
  $parametros = $_POST['parametros'];
   echo  json_encode($controlador->pagada($parametros));
}
class lista_facturaC
{
	private $modelo;
  private $sri;
  private $secuencial;
  private $tabla_referenciales_sri;
  private $categorias;

	function __construct()
	{
		$this->modelo = new lista_facturaM();
    $this->secuencial = new secuencialesM();
    $this->categorias = new categoriasM();
    $this->tabla_referenciales_sri = new tabla_referenciales_sriM();
	    // $this->mail = new enviar_emails();
	    // $this->pdf = new Reporte_pdf(); 
	}
	

function session($parametros)
  {
    $result = $this->modelo->usuario_exist($parametros);
    return $result;
  }
function DCTipoPago()
  {
    $datos = $this->tabla_referenciales_sri->where("Tipo_Referencia",'FORMA DE PAGO')->listar();
    $lista =  array();
    foreach ($datos as $key => $value) {             
      $lista[] =array('Codigo'=>$value['codigo'],'CTipoPago'=>$value['codigo'].' '.$value['descripcion']); 
       // $datos[] = $row;
       }
     // print_r($datos);die();
    return $lista;
  }

  function lista_facturas($parametros)
  {
    $result = $this->modelo->lista_facturas($parametros['query'],$parametros['numfac'],$parametros['desde'],$parametros['hasta'],$parametros['serie']);
    $tr ='';
    foreach($result as $key => $value)
    {
      if(is_object($value['fecha']))
      {
        $value['fecha'] = $value['fecha']->format('Y-m-d');
      }
      $tr.='<tr>
              <td>
                <a class="btn-sm btn btn-primary" href="../controlador/facturar.php?reporte_factura=true&empresa='.$_SESSION['INICIO']['ID_EMPRESA'].'&fac='.$value['id'].'&usu='.$_SESSION['INICIO']['ID_USUARIO'].'" target="_blank"><i class="fa fa-eye" title="Ver factura"></i></a>
                ';
                if($value['estado']=='P' || $value['estado']=='')
                {
                  $tr.='<button class="btn-sm btn btn-danger" onclick="eliminar_factura('.$value['id'].')"><i class="fa fa-trash" title="Eliminar"></i></button><button class="btn-sm btn btn-info" onclick="autorizar('.$value['id'].')"><i class="fa fa-paper-plane" title="Autorizar"></i></button>';
                }
                if($value['estado']=='R')
                {
                  $tr.='<button class="btn-sm btn btn-warning" onclick="modal_error_seri(\''.$value['Autorizacion'].'\',\'FACTURAS\')"><i class="fa fa-exclamation-triangle" title="Descargar xml"></i></button>';
                }
                
                if($value['estado']=='A')
                {
                  $tr.='<button class="btn-sm btn btn-secondary" onclick="anular_factura(\''.$value['id'].'\',\'FACTURAS\')"><i class="fa fa-times-circle" title="Anular"></i></button>';
                }
                if($value['pago']=='0')
                {
                  $tr.='<button class="btn-sm btn btn-secondary" onclick="pagada(\''.$value['id'].'\',\'FACTURAS\')"><i class="fa fa-money-bill" title="Marcar como pagada"></i></button>';
                }
              $tr.='</td>
              <td><a href="#" onclick="cargar_detalle(\''.$value['id'].'\',\''.$value['estado'].'\')">'.$value['nombre'].'</a></td>
              <td>'.$value['fecha'].'</td>
              <td>'.$value['num'].'</td>
              <td>'.$value['serie'].'</td>
              <td>'.number_format($value['total'],2,'.','').'</td>';

      if($value['estado']=='A')
      {
        $tr.='<td class="table-success">'.$value['estado'].'</td>';
      }else if($value['estado']=='R')
      {
        $tr.='<td  class="table-danger">'.$value['estado'].'</td>';
      }else if($value['estado']=='AN')
      {
        $tr.='<td  class="table-warning">'.$value['estado'].'</td>';
      }else
      {
        $tr.='<td>'.$value['estado'].'</td>';
      }
      $tr.='</tr>';
    }
    return $tr;
  }

  function lista_series()
  {
    $result = $this->modelo->lista_series();
    $tr ='<option value="">Serie</option>';
    foreach($result as $key => $value)
    {
      $tr.='<option value="'.$value['serie'].'">'.$value['serie'].'</option>';
    }
    return $tr;
  }

  function lineas_facturas($fac)
  {
    $query =$fac;
    $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
    $result = $this->modelo->linea_facturas($query,$id_empresa);
    $estado_fac = $this->modelo->cliente_factura($query,$id_empresa);

    $tr ='';
    $total = 0;
    $iva = 0; 
    $sub = 0;
    $des = 0;
    $sin_iva =0;
    $con_iva=0 ;
     $lineas='';    
    if(count($result)==0)
    {
      $lineas.= ' <tr>
                    <td colspan="9" class="text-center">Sin items..</td>
                </tr>';
    }
    foreach($result as $key => $value)
    {
      // print_r($value);die();
      $lineas.= ' <tr>';
       if($estado_fac[0]['estado_factura']!='A' && $estado_fac[0]['estado_factura']!='AN')
        {
          $lineas.= '   <td>
                         <button class="btn p-2 btn-danger" onclick="Eliminar(\''.$value['id_lineas'].'\')" ><i class="fa fa-trash"></i></button>
                        <!--- <button class="btn-sm btn-primary" onclick="Editar(\''.$value['id_lineas'].'\')" ><i class="fa fa-save"></i></button> --!>
                      </td>';
        }else{ $lineas.= '<td></td>';}
      $lineas.= '   <td>'.$value['producto'].'</td>
                    <td>'.$value['cantidad'].'</td>
                    <td>$'.number_format(floatval($value['precio_uni']),2,'.','').'</td>
                    <td>'.number_format(floatval($value['iva']),2,'.','').'</td>
                    <td>'.number_format(floatval($value['descuento']),2,'.','').'</td>
                    <td>'.number_format(floatval($value['subtotal']),2,'.','').'</td>
                    <td>'.number_format(floatval($value['total']),2,'.','').'</td>
                    <td>'.$value['observacion'].'</td>
                    <td>
                       <img src="'.$value['foto'].'" class="img-profile rounded-circle" style="width:100%">
                    </td>
                </tr>';
    
        
        $total+=floatval($value['total']);
        if($value['iva']==0)
        {
          $sin_iva +=floatval($value['subtotal']);

        }else
        {
          $con_iva +=floatval($value['subtotal']);

        }
        $iva +=floatval($value['iva']);
        $des+= floatval($value['descuento']);
        $sub+=floatval($value['subtotal']);
    }
    $iva = number_format($iva,2,'.','');
    $tota= number_format($total,2,'.','');
    $sub = number_format($sub,2,'.','');
    $des = number_format($des,2,'.','');


    $datos[0]['campo'] = 'subtotal';
    $datos[0]['dato'] = $sub;
    $datos[1]['campo'] = 'descuento';
    $datos[1]['dato'] = $des;
    $datos[2]['campo'] = 'iva';
    $datos[2]['dato'] = $iva;
    $datos[3]['campo'] = 'total';
    $datos[3]['dato'] = $tota;

    $datos[4]['campo'] = 'Sin_Iva';
    $datos[4]['dato'] = $sin_iva;   
    $datos[5]['campo'] = 'Con_Iva';
    $datos[5]['dato'] =  $con_iva;
    

    $where[0]['campo'] = 'id_factura';
    $where[0]['dato'] = $query;

    // print_r($datos);die();

    $this->modelo->update('facturas',$datos,$where,$id_empresa);
    $dat = array('tr'=>$lineas,'total'=>$tota,'sub'=>$sub,'iva'=>$iva,'des'=>$des,'factura'=>$estado_fac[0]);
    return $dat;
  }

  function articulos($parametros)
  {
    if($parametros['tipo']=='P')
    {
      $datos = $this->modelo->articulos_all2($_SESSION['INICIO']['ID_EMPRESA'],$parametros['query'],$parametros['cate'],false,false,false,1);
    }else
    {
      $datos = $this->modelo->articulos_all2($_SESSION['INICIO']['ID_EMPRESA'],$parametros['query'],$parametros['cate'],false,false,1,false);
    }

    // print_r($parametros);die();
    $arti = '';
    foreach ($datos as $key => $value) {
      $alerta='table-success';
      if($value['inventario']==1 && $value['stock']<=0)
      {
        $alerta='table-danger';
      }
      $lleva = '';
      if($value['iva']=='1')
      {
        $lleva = '<span class="badge badge-primary badge-counter">lleva iva</span>';
      }
      $arti.='
      <tr class="'.$alerta.'" onclick="usar(\''.$value['id_productos'].'\',\''.$value['referencia'].'\',\''.$value['nombre'].'\',\''.number_format($value['precio_uni'],2,'.','').'\',\''.$value['iva'].'\')">
      <td>'.$value['referencia'].'</td>
      <td>'.$value['nombre'].'  '.$lleva.'</td>
      <td>'.$value['stock'].'</td>
      <td>'.number_format($value['precio_uni'],2,'.','').'</td>
      <td>'.number_format($value['peso'],2,'.','').'</td>
      <td>'.$value['uni_medida'].'</td>
      <td>'.$value['marca'].'</td>
      <td>'.$value['modelo'].'</td>
      <td>'.$value['categoria'].'</td>
      </tr>';
    }
     // $d[] = array('value'=>$value['id_productos'],'label'=>$value['nombre'],'precio'=>number_format($value['precio_uni'],2),'iva'=>$value['iva'],'ref'=>$value['referencia']);
    return $arti;
  }

  function add_articulos($parametros)
  {

    $datos[0]['campo'] = 'Tipo_pago';
    $datos[0]['dato'] = $parametros['tipopago'];
    $datos[1]['campo'] = 'fecha';
    $datos[1]['dato'] = $parametros['fecha'];
    $datos[2]['campo'] = 'datos_adicionales';
    $datos[2]['dato'] = $parametros['adicionales'];

    $where[0]['campo'] = 'id_factura';
    $where[0]['dato'] = $parametros['fac'];
    $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
    // print_r($datos);die();

    $this->modelo->update('facturas',$datos,$where,$id_empresa);

    // print_r($parametros);die();
    $datosA = $this->modelo->articulos_id($parametros['id'],$_SESSION['INICIO']['ID_EMPRESA']);

    $datos[0]['campo'] = 'producto';
    $datos[0]['dato'] = $parametros['articulo']; //$datosA[0]['nombre'];
    $datos[1]['campo'] = 'cantidad';
    $datos[1]['dato'] = $parametros['cant'];
    $datos[2]['campo'] = 'precio_uni';
    $datos[2]['dato'] = $parametros['pvp'];
    $datos[3]['campo'] = 'subtotal';
    $datos[3]['dato'] = number_format($parametros['cant']*$parametros['pvp'],2,'.','');
    $datos[4]['campo'] = 'id_factura';
    $datos[4]['dato'] = $parametros['fac'];
    $datos[5]['campo'] = 'descuento';
    $datos[5]['dato'] = number_format((($parametros['subto']*$parametros['desc'])/100),2,'.','');
    $datos[6]['campo'] = 'iva';
    $datos[6]['dato'] = number_format(floatval($parametros['iva']),2,'.','');
    $datos[7]['campo'] = 'total';
    $datos[7]['dato'] = number_format(floatval($parametros['total']),2,'.','');
    $datos[8]['campo'] = 'porc_descuento';
    $datos[8]['dato'] = $parametros['desc'];
    $datos[9]['campo'] = 'referencia';
    $datos[9]['dato'] = $datosA[0]['referencia'];    
    $datos[10]['campo'] = 'Serie_No';
    $datos[10]['dato'] = $_SESSION['INICIO']['SERIE'];
    $datos[11]['campo'] = 'observacion';
    $datos[11]['dato'] = $parametros['detalle'];
    if($parametros['iva']!=0){   
      $datos[12]['campo'] = 'porc_iva';
      $datos[12]['dato'] = ($_SESSION['INICIO']['IVA']/100);
    }else
    {
      $datos[12]['campo'] = 'porc_iva';
      $datos[12]['dato'] = 0;
    }

   return  $this->modelo->add('lineas_factura',$datos,$_SESSION['INICIO']['ID_EMPRESA']);

  }

  function editar_linea($parametros)
  {
    // print_r($parametros);die();
    $datos[0]['campo'] = 'precio_uni';
    $datos[0]['dato'] = $parametros['pre'];
    $datos[1]['campo'] = 'cantidad';
    $datos[1]['dato'] = $parametros['cant'];
    $datos[2]['campo'] = 'total';
    $datos[2]['dato'] = number_format($parametros['tot'],2);    
    $datos[3]['campo'] = 'descuento';
    $datos[3]['dato'] = $parametros['vald'];
    $datos[4]['campo'] = 'iva';
    $datos[4]['dato'] = $parametros['iva'];
    $datos[5]['campo'] = 'subtotal';
    $datos[5]['dato'] = $parametros['sub'];
    $datos[6]['campo'] = 'porc_descuento';
    $datos[6]['dato'] = $parametros['pord'];
    $datos[10]['campo'] = 'porc_iva';
    $datos[10]['dato'] = 0;
    if($parametros['iva']!=0){   
      $datos[10]['campo'] = 'porc_iva';
      $datos[10]['dato'] = number_format($parametros['p_iva']/100,2);
      }

    $where[0]['campo']='id_lineas';
    $where[0]['dato']=$parametros['id'];
   return  $this->modelo->update('lineas_factura',$datos,$where,$parametros['idEmp']);

  }
  function cargar_linea($parametros)
  {
    // print_r($parametros);die();
    return $this->modelo->linea_detalle($parametros['id'],$parametros['idEmp']);
  }

  function delete_linea($parametros)
  {
    // print_r($parametros);die();
    $datos[0]['campo']='id_lineas';
    $datos[0]['dato']=$parametros['id'];
    return $this->modelo->delete('lineas_factura',$datos,$_SESSION['INICIO']['ID_EMPRESA']);
  }
  function facturar($parametros)
  {
    // print_r('ssss');die();
    return  $this->sri->Autorizar($parametros);
  }
  function cliente_factura($parametros)
  {
    // print_r($parametros);die();
    return $this->modelo->cliente_factura($parametros['fac'],$parametros['idEmp']);
  }

  function editar_cliente($parametros)
  {
    //print_r($parametros);die();
    $datos[0]['campo'] = 'nombre';
    $datos[0]['dato'] = $parametros['nom'];
    $datos[1]['campo'] = 'telefono';
    $datos[1]['dato'] = $parametros['tel'];
    $datos[2]['campo'] ='mail' ;
    $datos[2]['dato'] = $parametros['ema'];
    $datos[3]['campo'] = 'direccion';
    $datos[3]['dato'] = $parametros['dir'];
    $datos[4]['campo'] = 'ci_ruc';
    $datos[4]['dato'] =$parametros['ci'];

  
    $where[0]['campo'] = 'id_cliente';
    $where[0]['dato']= $parametros['id'];
   
    return $this->modelo->update('cliente',$datos,$where,$parametros['idEmp']);
   

  }

  function nueva_factura($parametros)
  {
    $numero = $this->modelo->numero_factura($parametros['empresa']);
    $new_num = $numero+1;
    $datos[0]['campo']='id_empresa';
    $datos[0]['dato']=$parametros['empresa'];
    $datos[1]['campo']='id_cliente';
    $datos[1]['dato']=$parametros['cliente'];
    $datos[2]['campo']='id_usuario';
    $datos[2]['dato']=$parametros['usuario'];
    $datos[3]['campo']='serie';
    $datos[3]['dato']=$parametros['serie'];    
    $datos[4]['campo']='num_factura';
    $datos[4]['dato']=$new_num;   
    $datos[5]['campo']='fecha';
    $datos[5]['dato']=date('Y-m-d');    
    $datos[6]['campo']='Porc_IVA';
    $datos[6]['dato']='0.12';
    $datos[7]['campo']='Autorizacion';
    $datos[7]['dato']='123456789123456789123456789'.$new_num;

    // print_r($datos);die();

    $this->modelo->add('facturas',$datos,$parametros['empresa']);
    $FA = $this->modelo->buscar_facturas($parametros['empresa'],$new_num);
    return $FA;
        
  }
  function categorias($query)
  {
    if($query!=0 && $query!='')
    {
      $datos = $this->categorias->where("estado",'A')->like('nombre',$query)->listar();
    }else
    {      
      $datos = $this->categorias->where("estado",'A')->listar();
    }
    foreach ($datos as $key => $value) {
      $op[]=array('id'=>$value['_id'],'text'=>$value['nombre']);
    }
    return $op;
  }

  function enviar_email($parametros)
  {
    // print_r($parametros);die();
    $emp = $this->modelo->datos_empresa($parametros['empresa']);
    $cliente_factura = $this->modelo->cliente_factura($parametros['fac'],$parametros['empresa']);
    $nombre = $emp[0]['email'];
    $to_correo = $cliente_factura[0]['mail'];
    $cuerpo_correo = '<b>Comprobante electronico</b>';
    $titulo_correo = 'Comprobante electronico';
    $correo_respaldo = 'example@example.com';
    $archivos[0] = $cliente_factura[0]['Autorizacion'].'.pdf';
    $HTML = true;

    $empresa = $this->modelo->datos_empresa_sucursal_usuario($parametros['usu'],$parametros['empresa']);
    // print_r($cliente_factura[0]['Autorizacion']);die();
    $lineas = $this->modelo->linea_facturas_all($parametros['fac'],$parametros['empresa']);
    $this->pdf->factura_pdf($cliente_factura,$lineas,$empresa,false,$descargar=false);


    $this->mail->enviar_email($emp,$to_correo,$cuerpo_correo,$titulo_correo,$correo_respaldo,$archivos,$nombre,$HTML);
  }

  function reporte_factura($parametros)
  {
    $empresa = $this->modelo->datos_empresa_sucursal_usuario($parametros['usu'],$parametros['empresa']);
    $cliente_factura = $this->modelo->cliente_factura($parametros['fac'],$parametros['empresa']);
    $lineas = $this->modelo->linea_facturas_all($parametros['fac'],$parametros['empresa']);
    $doc =  $this->pdf->factura_pdf($cliente_factura,$lineas,$empresa,false,true);
    // print_r($doc);die();
    return $doc;
  }

function error_sri($parametros)
{
  // print_r($parametros);die();
  $clave = $parametros['clave'].'.xml';
  $entidad = $_SESSION['INICIO']['ID_EMPRESA'];
  $carpeta_entidad = dirname(__DIR__,1)."/comprobantes/entidades/entidad_".$entidad;
  $carpeta_comprobantes = $carpeta_entidad.'/CE'.$entidad.'/'.$parametros['carpeta'];
  $carpeta_no_autori = $carpeta_comprobantes."/No_autorizados";
  $carpeta_rechazados = $carpeta_comprobantes."/Rechazados";
        
      

  $ruta1 = $carpeta_no_autori.'/'.$clave;
  $ruta2 = $carpeta_rechazados.'/'.$clave;

  // print_r($ruta1);print_r($ruta2);die();
  if(file_exists($ruta1))
  {

    // print_r('ruta1');die();
    $xml = simplexml_load_file($ruta1);
    $codigo = $xml->mensajes->mensaje->mensaje->identificador;
    $mensaje = $xml->mensajes->mensaje->mensaje->mensaje;
    $adicional = $xml->mensajes->mensaje->mensaje->informacionAdicional;
    $estado = $xml->estado;
    $fecha = $xml->fechaAutorizacion;
    // print_r($mensaje);die();
    return  array('estado'=>$estado,'codigo'=>$codigo,'mensaje'=>$mensaje,'adicional'=>$adicional,'fecha'=>$fecha);
  }
  if(file_exists($ruta2))
  {
    // print_r('ruta2');die();
    $fp = fopen($ruta2, "r");
     $linea = '';
    while (!feof($fp)){
        $linea.= fgets($fp);
    }
    fclose($fp);
    $linea = str_replace('ns2:','', $linea);
    $xml = simplexml_load_string($linea);

    $codigo = $xml->respuestaSolicitud->comprobantes->comprobante->mensajes->mensaje->identificador;
    $mensaje = $xml->respuestaSolicitud->comprobantes->comprobante->mensajes->mensaje->mensaje;
    $adicional = $xml->respuestaSolicitud->comprobantes->comprobante->mensajes->mensaje->informacionAdicional;
    $estado = $xml->respuestaSolicitud->estado;
    $fecha = '';
    // print_r($mensaje);die();
    return  array('estado'=>$estado,'codigo'=>$codigo,'mensaje'=>$mensaje,'adicional'=>$adicional,'fecha'=>$fecha);

  }
}

function eliminarFac($parametros)
  {
    $empresa = $_SESSION['INICIO']['ID_EMPRESA'];
     $this->modelo->delete_lineas_factura($empresa,$id=false,$parametros['id']);
     return $this->modelo->delete_factura($empresa,$parametros['id']);
    // print_r($parametros);die();
  }
  function anular_factura($parametros)
  {
    $empresa = $_SESSION['INICIO']['ID_EMPRESA'];
    $datos[0]['campo'] = 'estado_factura';
    $datos[0]['dato'] = 'AN';

    $where[0]['campo'] = 'id_factura';
    $where[0]['dato'] = $parametros;


    // print_r($datos);print_r($where);die();

    return $this->modelo->update('facturas',$datos,$where, $empresa);
    // print_r($parametros);die();
  }

  function editar_serie($parametros)
  {
    $numero = $this->secuencial->buscar_codigo_secuencial($detalle=false,'FA',$parametros['serie']);
    $empresa = $_SESSION['INICIO']['ID_EMPRESA'];
    $datos[0]['campo'] = 'serie';
    $datos[0]['dato'] = $parametros['serie'];
    $datos[1]['campo'] = 'num_factura';
    $datos[1]['dato'] = $numero[0]['numero'];
    $datos[2]['campo'] = 'Autorizacion';
    $datos[2]['dato'] = $numero[0]['Autorizacion'];

    $where[0]['campo'] = 'id_factura';
    $where[0]['dato'] = $parametros['idfac'];
    // print_r($datos);print_r($where);die();

     $this->modelo->update('facturas',$datos,$where, $empresa);

     $datosC[0]['campo']= 'numero';
     $datosC[0]['dato']= $numero[0]['numero']+1;


      $datosCW[0]['campo']= 'id_secuenciales';
      $datosCW[0]['dato']= $numero[0]['id_secuenciales'];
      $datosCW[1]['campo']= 'id_empresa';
      $datosCW[1]['dato']= $_SESSION['INICIO']['ID_EMPRESA'];
    return $this->modelo->update('codigos_secuenciales',$datosC,$datosCW,$_SESSION['INICIO']['ID_EMPRESA']);


    // print_r($parametros);die();
  }

  function pagada($parametros)
  {

    $datosC[0]['campo']= 'estado_pago';
    $datosC[0]['dato']= 1;

    $datosCW[0]['campo']= 'id_factura';
    $datosCW[0]['dato']= $parametros['id'];
    return $this->modelo->update('facturas',$datosC,$datosCW,$_SESSION['INICIO']['ID_EMPRESA']);


    // print_r($parametros);die();
  }


}

?>