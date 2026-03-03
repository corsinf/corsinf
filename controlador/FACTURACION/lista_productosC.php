<?php 
require_once(dirname(__DIR__,2).'/modelo/FACTURACION/lista_facturaM.php');
require_once(dirname(__DIR__,2).'/modelo/FACTURACION/lista_productosM.php');

/**
 * 
 */
$controlador = new lista_productosC();
if(isset($_GET['buscar_articulo']))
{
  // print_r($_POST);die();
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->articulos($parametros));
}
if(isset($_GET['buscar_articulo_materia']))
{
  // print_r($_POST);die();
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->articulos_materia($parametros));
}
if(isset($_GET['buscar_transferencias']))
{
  // print_r($_POST);die();
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->articulos_transferencias($parametros));
}
if(isset($_GET['buscar_articulo_app']))
{
  // print_r($_POST);die();
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->articulos_app($parametros));
}
if(isset($_GET['detalle_articulo']))
{
  // print_r($_POST);die();
    $id = $_POST['id'];
    echo json_encode($controlador->detalle_articulos_all($id));
}
if(isset($_GET['detalle_articulo_ddl']))
{
   $query = '';
   if(isset($_GET['q']))
   {
     $query = $_GET['q'];
   }
    echo json_encode($controlador->buscar_articulos($query));
}
if(isset($_GET['ddl_materia_prima']))
{
   $query = '';
   if(isset($_GET['q']))
   {
     $query = $_GET['q'];
   }
    echo json_encode($controlador->buscar_articulos_materia($query));
}

if(isset($_GET['ddl_articulos_inventario']))
{
   $query = '';
   if(isset($_GET['q']))
   {
     $query = $_GET['q'];
   }
    echo json_encode($controlador->buscar_articulos_inventario($query));
}

if(isset($_GET['detalle_articulo_app']))
{
  // print_r($_POST);die();
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->detalle_articulos_all_app($parametros));
}
if(isset($_GET['add_edit']))
{
   $parametros = $_POST['parametros'];
    echo json_encode($controlador->add_edit($parametros));
}
if(isset($_GET['add_edit_materia']))
{
   $parametros = $_POST['parametros'];
    echo json_encode($controlador->add_edit_materia($parametros));
}
if(isset($_GET['add_edit_app']))
{
   $parametros = $_POST['parametros'];
    echo json_encode($controlador->add_edit_app($parametros));
}
if(isset($_GET['eliminar']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_articulo($parametros));
}
if(isset($_GET['cargar_imagen']))
{
   echo json_encode($controlador->guardar_foto($_FILES,$_POST));
}
if(isset($_GET['cargar_imagen_materia']))
{
   echo json_encode($controlador->guardar_foto_materia($_FILES,$_POST));
}
if(isset($_GET['agregar_trasnferencia']))
{
   $parametros = $_POST['parametros'];
   echo json_encode($controlador->agregar_transferencia($parametros));
}
if(isset($_GET['lista_trasnferencia']))
{
   echo json_encode($controlador->lista_trasnferencia());
}
if(isset($_GET['eliminar_transferencia']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_transferencia($parametros));
}
if(isset($_GET['transferencia']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->transferencia($parametros));
}
if(isset($_GET['kit_tamanio']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->kit_tamanio($parametros));
}

if(isset($_GET['kit_tamanio_add']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->kit_tamanio_add($parametros));
}

if(isset($_GET['kit_tamanio_datos']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->kit_tamanio_datos($parametros));
}

if(isset($_GET['eliminar_adicionales']))
{
    $parametros = $_POST['id'];
    echo json_encode($controlador->adicionales_delete($parametros));
}
if(isset($_GET['eliminar_materia']))
{
    $parametros = $_POST['id'];
    echo json_encode($controlador->materia_delete($parametros));
}


if(isset($_GET['adicionales']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->adicionales($parametros));
}

if(isset($_GET['adicionales_add']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->adicionales_add($parametros));
}
if(isset($_GET['materia_add']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->materia_add($parametros));
}

if(isset($_GET['adicionales_datos']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->adicionales_datos($parametros));
}

if(isset($_GET['add_categoria']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->add_categoria($parametros));
}

if(isset($_GET['eliminar_tama']))
{
    $parametros = $_POST['id'];
    echo json_encode($controlador->adicionales_delete($parametros));
}



if(isset($_GET['kit_materia_prima']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->kit_materia_prima($parametros));
}
class lista_productosC
{
	private $modelo;
	private $fatura;
	function __construct()
	{
	  $this->modelo = new lista_productosM();
	  $this->factura = new lista_facturaM();
	}

function articulos($parametros)
  {
    // print_r($parametros);die();
    if($parametros['tipo']=='P')
    {
      $datos = $this->factura->articulos_all2($_SESSION['INICIO']['ID_EMPRESA'],$parametros['query'],$parametros['cate'],false,false,false,1);
    }else
    {
      $datos = $this->factura->articulos_all2($_SESSION['INICIO']['ID_EMPRESA'],$parametros['query'],$parametros['cate'],false,false,1,false);
    }
    
    // print_r($parametros);die();

    return $datos;



    // $arti = '';
    // foreach ($datos as $key => $value) {
    //   $alerta='table-default';
    //   // if($value['inventario']==1 && $value['stock']<=0)
    //   // {
    //   //   $alerta='table-danger';
    //   // }
    //   $lleva = '';
    //   if($value['iva']=='1')
    //   {
    //     $lleva = '<span class="badge badge-primary badge-counter">lleva iva</span>';
    //   }
    //   $arti.='
    //   <tr class="'.$alerta.'" onclick="usar(\''.$value['id_productos'].'\',\''.$value['referencia'].'\',\''.$value['nombre'].'\',\''.number_format($value['precio_uni'],2,'.',',').'\',\''.$value['iva'].'\')">
    //   <td><a href="inicio.php&detalle_articulos.php?id='.$value['id_productos'].'"><u>'.$value['referencia'].'</u></a></td>
    //   <td>'.$value['codigo_aux'].'</td>
    //   <td><a href="detalle_articulos.php?id='.$value['id_productos'].'"><u>'.$value['nombre'].'  '.$lleva.'</u></a></td>
    //   <td>'.$value['stock'].'</td>
    //   <td>'.$value['uni_medida'].'</td>
    //   <td>'.number_format($value['peso'],2,'.',',').'</td>
    //   <td>'.$value['nombre_sucursal'].'</td>
    //   <td>'.$value['categoria'].'</td>
    //   </tr>';
    // }
    //  // $d[] = array('value'=>$value['id_productos'],'label'=>$value['nombre'],'precio'=>number_format($value['precio_uni'],2),'iva'=>$value['iva'],'ref'=>$value['referencia']);
    // return $arti;
  }

  function articulos_materia($parametros)
  {
    $datos = $this->factura->articulos_all($_SESSION['INICIO']['ID_EMPRESA'],$query=$parametros['query'],$ref=false,$categoria=false,$tipo=1,$materia_p=true);
    // print_r($parametros);die();
    $arti = '';
    foreach ($datos as $key => $value) {
      $alerta='table-default';
      // if($value['inventario']==1 && $value['stock']<=0)
      // {
      //   $alerta='table-danger';
      // }
      $lleva = '';
      if($value['iva']=='1')
      {
        $lleva = '<span class="badge badge-primary badge-counter">lleva iva</span>';
      }
      $arti.='
      <tr class="'.$alerta.'" onclick="usar(\''.$value['id_productos'].'\',\''.$value['referencia'].'\',\''.$value['nombre'].'\',\''.number_format($value['precio_uni'],2,'.',',').'\',\''.$value['iva'].'\')">
      <td><a href="detalle_materia.php?id='.$value['id_productos'].'"><u>'.$value['referencia'].'</u></a></td>
      <td>'.$value['codigo_aux'].'</td>
      <td><a href="detalle_materia.php?id='.$value['id_productos'].'"><u>'.$value['nombre'].'  '.$lleva.'</u></a></td>
      <td>'.$value['stock'].'</td>
      <td>'.$value['uni_medida'].'</td>
      <td>'.number_format($value['peso'],2,'.',',').'</td>
      <td>'.$value['nombre_sucursal'].'</td>
      <td>'.$value['min'].'</td>
      <td>'.$value['max'].'</td>
      </tr>';
    }
     // $d[] = array('value'=>$value['id_productos'],'label'=>$value['nombre'],'precio'=>number_format($value['precio_uni'],2),'iva'=>$value['iva'],'ref'=>$value['referencia']);
    return $arti;
  }

  function articulos_transferencias($parametros)
  {
    $datos = $this->factura->articulos($parametros['query'],$parametros['ref'],$parametros['cate'],$parametros['tipo'],$_SESSION['INICIO']['ID_EMPRESA']);
    // print_r($parametros);die();
    $arti = '';
    foreach ($datos as $key => $value) {
      $alerta='table-default';
      // if($value['inventario']==1 && $value['stock']<=0)
      // {
      //   $alerta='table-danger';
      // }
      $lleva = '';
      if($value['iva']=='1')
      {
        $lleva = '<span class="badge badge-primary badge-counter">lleva iva</span>';
      }
      $arti.='
      <tr>
      <td><button class="btn btn-primary btn-sm" title="Agregar a trasnferencias" onclick="cantidad_transferencia(\''.$value['id_productos'].'\',\''.$value['stock'].'\')"><i class="fa fa-cart-plus"></i></button></td>
      <td><a href="detalle_articulos.php?id='.$value['id_productos'].'"><u>'.$value['referencia'].'</u></a></td>
      <td>'.$value['codigo_aux'].'</td>
      <td><a href="detalle_articulos.php?id='.$value['id_productos'].'"><u>'.$value['nombre'].'  '.$lleva.'</u></a></td>
      <td>'.$value['stock'].'</td>
      <td>'.$value['uni_medida'].'</td>
      <td>'.number_format($value['peso'],2,'.',',').'</td>
      <td>'.$value['nombre_sucursal'].'</td>
      <td>'.$value['categoria'].'</td>
      </tr>';
    }
     // $d[] = array('value'=>$value['id_productos'],'label'=>$value['nombre'],'precio'=>number_format($value['precio_uni'],2),'iva'=>$value['iva'],'ref'=>$value['referencia']);
    return $arti;
  }

  function articulos_app($parametros)
  {
    $datos = $this->factura->articulos($parametros['query'],$parametros['ref'],$parametros['cate'],$parametros['tipo'],$parametros['empresa']);
    // print_r($datos);die();
    $arti = '';
    foreach ($datos as $key => $value) {
      $alerta='table-default';
      // if($value['inventario']==1 && $value['stock']<=0)
      // {
      //   $alerta='table-danger';
      // }
      $lleva = '';
      if($value['iva']=='1')
      {
        $lleva = '<span class="badge badge-primary badge-counter">lleva iva</span>';
      }

      if(file_exists($value['foto']))
      {
        $im = explode('/',$value['foto']);
        $partes = count($im);
        if($partes>0)
        {
          $im = $im[$partes-1];
        }else
        {
          $im=$value['foto'];
        }
      }else
      {
        $im='sin_imagen.jpg';
      }

      $arti.='<li class="nav-item dropdown no-arrow mx-1 show">
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in show" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  <u>'.$value['referencia'].' / '.$value['nombre'].' </u>
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="detalle_producto.html?id='.$value['id_productos'].'">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="ruta_img/'.$im.'" style="max-width: 60px" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div class="font-weight-bold">
                    <div class="text-center">$ '.number_format($value['precio_uni'],2,'.','').' '.$lleva.'</div>
                    <div class="small text-gray-500">Stock · '.$value['stock'].' '.$value['uni_medida'].'</div>
                  </div>
                </a>               
              </div>
            </li>';
    }
    return $arti;
  }

  function detalle_articulos_all($id)
  {
    $datos = $this->modelo->detalle_articulos_all($id,$_SESSION['INICIO']['ID_EMPRESA']);
    return $datos[0];
  }
   function detalle_articulos_all_app($parametros)
  {
    $datos = $this->modelo->detalle_articulos_all($parametros['id'],$parametros['emp']);
     if(file_exists($datos[0]['foto']))
      {
        $im = explode('/',$datos[0]['foto']);
        $partes = count($im);
        if($partes>0)
        {
          $datos[0]['foto'] = 'ruta_img/'.$im[$partes-1];
        }else
        {
          $datos[0]['foto']='ruta_img/'.$datos[0]['foto'];
        }
      }else
      {
        $datos[0]['foto']='ruta_img/sin_imagen.jpg';
      }

    return $datos[0];
  }
  function add_edit($parametros)
  {
    // print_r($parametros);die();
    if($parametros['id']!='')
    {
      $datos[0]['campo'] = 'referencia';
      $datos[0]['dato'] = $parametros['referencia'];
      $datos[1]['campo'] = 'nombre';
      $datos[1]['dato'] = $parametros['nombre'];
      $datos[2]['campo'] = 'precio_uni';
      $datos[2]['dato'] = $parametros['precio'];
      $datos[3]['campo'] = 'iva';
      $datos[3]['dato'] = 0;
      if($parametros['lleva']=='si')
      {
        $datos[3]['dato'] = 1;
      }
      $datos[4]['campo'] = 'stock';
      $datos[4]['dato'] = $parametros['stock'];      
      $datos[5]['campo'] = 'peso';
      $datos[5]['dato'] = $parametros['peso'];
      $datos[6]['campo'] = 'categoria';
      $datos[6]['dato'] = $parametros['cate'];
      $datos[7]['campo'] = 'codigo_aux';
      $datos[7]['dato'] = $parametros['codAuxiliar'];
      $datos[8]['campo'] = 'codigo_bar';
      $datos[8]['dato'] = $parametros['barras'];
      $datos[9]['campo'] = 'marca';
      $datos[9]['dato'] = $parametros['marca']=='' ? 1:$parametros['marca'];
      $datos[10]['campo'] = 'modelo';
      $datos[10]['dato'] = $parametros['modelo'];
      $datos[11]['campo'] = 'uni_medida';
      $datos[11]['dato'] = $parametros['uni'];
      $datos[12]['campo'] = 'color';
      $datos[12]['dato'] = $parametros['color']=='' ? 1:$parametros['color'];
      $datos[13]['campo'] = 'genero';
      $datos[13]['dato'] = $parametros['genero']=='' ? 1:$parametros['genero'];
      $datos[14]['campo'] = 'estado';
      $datos[14]['dato'] = $parametros['estado']=='' ? 1:$parametros['estado'];
      $datos[15]['campo'] = 'sucursal';
      $datos[15]['dato'] = $parametros['sucu'];
      $datos[16]['campo'] = 'RFID';
      $datos[16]['dato'] = $parametros['rfid'];
      $datos[17]['campo'] = 'descripcion2';
      $datos[17]['dato'] = $parametros['des2'];
      $datos[18]['campo'] = 'max';
      $datos[18]['dato'] = $parametros['max'];
      $datos[19]['campo'] = 'min';
      $datos[19]['dato'] = $parametros['min'];
      $datos[20]['campo'] = 'serie_pro';
      $datos[20]['dato'] = $parametros['serie'];      
      $datos[21]['campo'] = 'inventario';
      $datos[21]['dato'] = ('1' == $parametros['Inv']) ? 1 : 0; 
      if($parametros['tipo']=='P')
      {
        $datos[22]['campo'] = 'producto_terminado';
        $datos[22]['dato'] = 1;
        $datos[23]['campo'] = 'servicio';
        $datos[23]['dato'] = 0;

      }else
      {
        $datos[22]['campo'] = 'producto_terminado';
        $datos[22]['dato'] = 0;
        $datos[23]['campo'] = 'servicio';
        $datos[23]['dato'] = 1;
      }

      // print_r($parametros);
      // print_r($datos);die();

       
      $where[0]['campo'] = 'id_productos';
      $where[0]['dato']=$parametros['id'];
      return  $this->modelo->update($datos,'productos',$where);
    }else
    {
     $datos[0]['campo'] = 'referencia';
      $datos[0]['dato'] = $parametros['referencia'];
      $datos[1]['campo'] = 'nombre';
      $datos[1]['dato'] = $parametros['nombre'];
      $datos[2]['campo'] = 'precio_uni';
      $datos[2]['dato'] = $parametros['precio'];
      $datos[3]['campo'] = 'iva';
      $datos[3]['dato'] = 0;
      if($parametros['lleva']=='si')
      {
        $datos[3]['dato'] = 1;
      }
      $datos[4]['campo'] = 'stock';
      $datos[4]['dato'] = $parametros['stock'];
      
      $datos[5]['campo'] = 'peso';
      $datos[5]['dato'] = $parametros['peso'];
      $datos[6]['campo'] = 'categoria';
      $datos[6]['dato'] = $parametros['cate'];
      $datos[7]['campo'] = 'codigo_aux';
      $datos[7]['dato'] = $parametros['codAuxiliar'];
      $datos[8]['campo'] = 'codigo_bar';
      $datos[8]['dato'] = $parametros['barras'];
      $datos[9]['campo'] = 'marca';
      $datos[9]['dato'] = $parametros['marca']=='' ? 1:$parametros['marca'];
      $datos[10]['campo'] = 'modelo';
      $datos[10]['dato'] = $parametros['modelo'];
      $datos[11]['campo'] = 'uni_medida';
      $datos[11]['dato'] = $parametros['uni'];
      $datos[12]['campo'] = 'color';
      $datos[12]['dato'] = $parametros['color']=='' ? 1:$parametros['color'];
      $datos[13]['campo'] = 'genero';
      $datos[13]['dato'] = $parametros['genero']=='' ? 1:$parametros['genero'];
      $datos[14]['campo'] = 'estado';
      $datos[14]['dato'] = $parametros['estado']=='' ? 1:$parametros['estado'];
      $datos[15]['campo'] = 'sucursal';
      $datos[15]['dato'] = $parametros['sucu'];
      $datos[16]['campo'] = 'RFID';
      $datos[16]['dato'] = $parametros['rfid'];
      $datos[17]['campo'] = 'descripcion2';
      $datos[17]['dato'] = $parametros['des2'];
      $datos[18]['campo'] = 'max';
      $datos[18]['dato'] = $parametros['max'];
      $datos[19]['campo'] = 'min';
      $datos[19]['dato'] = $parametros['min'];
      $datos[20]['campo'] = 'id_empresa';
      $datos[20]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];
      $datos[21]['campo'] = 'serie_pro';
      $datos[21]['dato'] = $parametros['serie'];
      $datos[22]['campo'] = 'fecha_creacion';
      $datos[22]['dato'] = date('Y-m-d');  
       if($parametros['tipo']=='P')
      {
        $datos[23]['campo'] = 'producto_terminado';
        $datos[23]['dato'] = 1;
        $datos[24]['campo'] = 'servicio';
        $datos[24]['dato'] = 0;

      }else
      {
        $datos[23]['campo'] = 'producto_terminado';
        $datos[23]['dato'] = 1;
        $datos[24]['campo'] = 'servicio';
        $datos[24]['dato'] = 0;
      }
     
      
      // print_r($datos);
      // print_r($parametros); die();
      $base = $this->modelo->insertar($datos,'productos');
      // print_r($base);die();
      $datos = $this->modelo->detalle_articulos_ref($parametros['referencia'],$_SESSION['INICIO']['ID_EMPRESA']);
      return $datos[0]['id_productos'];
      
    }
    // print_r($parametros);die();
  }

  function add_edit_materia($parametros)
  {
    // print_r($parametros);die();
    if($parametros['id']!='')
    {
      $datos[0]['campo'] = 'referencia';
      $datos[0]['dato'] = $parametros['referencia'];
      $datos[1]['campo'] = 'nombre';
      $datos[1]['dato'] = $parametros['nombre'];
      $datos[2]['campo'] = 'precio_uni';
      $datos[2]['dato'] = $parametros['precio'];
      $datos[3]['campo'] = 'iva';
      $datos[3]['dato'] = 0;
      if($parametros['lleva']=='si')
      {
        $datos[3]['dato'] = 1;
      }
      $datos[4]['campo'] = 'stock';
      $datos[4]['dato'] = $parametros['stock'];
      $datos[5]['campo'] = 'inventario';
      $datos[5]['dato'] = 1;     
      $datos[6]['campo'] = 'peso';
      $datos[6]['dato'] = $parametros['peso'];
      $datos[7]['campo'] = 'materia_prima';
      $datos[7]['dato'] = 1;
      $datos[8]['campo'] = 'codigo_aux';
      $datos[8]['dato'] = $parametros['codAuxiliar'];
      $datos[9]['campo'] = 'codigo_bar';
      $datos[9]['dato'] = $parametros['barras'];
      $datos[10]['campo'] = 'marca';
      $datos[10]['dato'] = $parametros['marca'];
      $datos[11]['campo'] = 'modelo';
      $datos[11]['dato'] = $parametros['modelo'];
      $datos[12]['campo'] = 'uni_medida';
      $datos[12]['dato'] = $parametros['uni'];
      $datos[13]['campo'] = 'color';
      $datos[13]['dato'] = $parametros['color'];
      $datos[14]['campo'] = 'genero';
      $datos[14]['dato'] = $parametros['genero'];
      $datos[15]['campo'] = 'estado';
      $datos[15]['dato'] = $parametros['estado'];
      $datos[16]['campo'] = 'sucursal';
      $datos[16]['dato'] = $parametros['sucu'];
      $datos[17]['campo'] = 'RFID';
      $datos[17]['dato'] = $parametros['rfid'];
      $datos[18]['campo'] = 'descripcion2';
      $datos[18]['dato'] = $parametros['des2'];
      $datos[19]['campo'] = 'max';
      $datos[19]['dato'] = $parametros['max'];
      $datos[20]['campo'] = 'min';
      $datos[20]['dato'] = $parametros['min'];
      $datos[21]['campo'] = 'serie_pro';
      $datos[21]['dato'] = $parametros['serie'];
      $datos[22]['campo'] = 'paquetes';
      $datos[22]['dato'] = $parametros['paquetes'];
      $datos[23]['campo'] = 'xpaquetes';
      $datos[23]['dato'] = $parametros['xpaquetes'];      
      $datos[24]['campo'] = 'sueltos';
      $datos[24]['dato'] = $parametros['sueltos'];

      $where[0]['campo'] = 'id_productos';
      $where[0]['dato']=$parametros['id'];
      return  $this->modelo->update($datos,'productos',$where);
    }else
    {
      $datos[0]['campo'] = 'referencia';
      $datos[0]['dato'] = $parametros['referencia'];
      $datos[1]['campo'] = 'nombre';
      $datos[1]['dato'] = $parametros['nombre'];
      $datos[2]['campo'] = 'precio_uni';
      $datos[2]['dato'] = $parametros['precio'];
      $datos[3]['campo'] = 'iva';
      $datos[3]['dato'] = 0;
      if($parametros['lleva']=='si')
      {
        $datos[3]['dato'] = 1;
      }
      $datos[4]['campo'] = 'stock';
      $datos[4]['dato'] = $parametros['stock'];
      $datos[5]['campo'] = 'inventario';
      $datos[5]['dato'] = 1;
      $datos[6]['campo'] = 'peso';
      $datos[6]['dato'] = $parametros['peso'];
      $datos[7]['campo'] = 'materia_prima';
      $datos[7]['dato'] = 1;
      $datos[8]['campo'] = 'codigo_aux';
      $datos[8]['dato'] = $parametros['codAuxiliar'];
      $datos[9]['campo'] = 'codigo_bar';
      $datos[9]['dato'] = $parametros['barras'];
      $datos[10]['campo'] = 'marca';
      $datos[10]['dato'] = $parametros['marca'];
      $datos[11]['campo'] = 'modelo';
      $datos[11]['dato'] = $parametros['modelo'];
      $datos[12]['campo'] = 'uni_medida';
      $datos[12]['dato'] = $parametros['uni'];
      $datos[13]['campo'] = 'color';
      $datos[13]['dato'] = $parametros['color'];
      $datos[14]['campo'] = 'genero';
      $datos[14]['dato'] = $parametros['genero'];
      $datos[15]['campo'] = 'estado';
      $datos[15]['dato'] = $parametros['estado'];
      $datos[16]['campo'] = 'sucursal';
      $datos[16]['dato'] = $parametros['sucu'];
      $datos[17]['campo'] = 'RFID';
      $datos[17]['dato'] = $parametros['rfid'];
      $datos[18]['campo'] = 'descripcion2';
      $datos[18]['dato'] = $parametros['des2'];
      $datos[19]['campo'] = 'max';
      $datos[19]['dato'] = $parametros['max'];
      $datos[20]['campo'] = 'min';
      $datos[20]['dato'] = $parametros['min'];
      $datos[21]['campo'] = 'id_empresa';
      $datos[21]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];
      $datos[22]['campo'] = 'serie_pro';
      $datos[22]['dato'] = $parametros['serie'];
      $datos[23]['campo'] = 'fecha_creacion';
      $datos[23]['dato'] = date('Y-m-d');
      $datos[24]['campo'] = 'paquetes';
      $datos[24]['dato'] = $parametros['paquetes'];
      $datos[25]['campo'] = 'xpaquetes';
      $datos[25]['dato'] = $parametros['xpaquetes'];      
      $datos[26]['campo'] = 'sueltos';
      $datos[26]['dato'] = $parametros['sueltos'];
      $base = $this->modelo->insertar($datos,'productos');
      $datos = $this->modelo->detalle_articulos_ref($parametros['referencia'],$_SESSION['INICIO']['ID_EMPRESA']);
      return $datos[0]['id_productos'];
      
    }
    // print_r($parametros);die();
  }

   function add_edit_app($parametros)
  {
    // print_r($parametros);die();
    if($parametros['id']!='')
    {
      $datos[0]['campo'] = 'referencia';
      $datos[0]['dato'] = $parametros['referencia'];
      $datos[1]['campo'] = 'nombre';
      $datos[1]['dato'] = $parametros['nombre'];
      $datos[2]['campo'] = 'precio_uni';
      $datos[2]['dato'] = $parametros['precio'];
      $datos[3]['campo'] = 'iva';
      $datos[3]['dato'] = 0;
      if($parametros['lleva']=='si')
      {
        $datos[3]['dato'] = 1;
      }
      $datos[4]['campo'] = 'stock';
      $datos[4]['dato'] = $parametros['stock'];
      $datos[5]['campo'] = 'inventario';
      $datos[5]['dato'] = 0;
      if($parametros['tipo']=='P')
      {
        $datos[5]['dato'] = 1;
      }
      $datos[6]['campo'] = 'peso';
      $datos[6]['dato'] = $parametros['peso'];
      $datos[7]['campo'] = 'categoria';
      $datos[7]['dato'] = $parametros['cate'];
      $datos[8]['campo'] = 'marca';
      $datos[8]['dato'] = $parametros['marca'];
      $datos[9]['campo'] = 'modelo';
      $datos[9]['dato'] = $parametros['modelo'];
      $datos[10]['campo'] = 'uni_medida';
      $datos[10]['dato'] = $parametros['uni'];
      $datos[11]['campo'] = 'color';
      $datos[11]['dato'] = $parametros['color'];
      $datos[12]['campo'] = 'genero';
      $datos[12]['dato'] = $parametros['genero'];
      $datos[13]['campo'] = 'estado';
      $datos[13]['dato'] = $parametros['estado'];
      $datos[14]['campo'] = 'sucursal';
      $datos[14]['dato'] = $parametros['sucu'];
      $datos[15]['campo'] = 'max';
      $datos[15]['dato'] = $parametros['max'];
      $datos[16]['campo'] = 'min';
      $datos[16]['dato'] = $parametros['min'];
      $datos[17]['campo'] = 'serie_pro';
      $datos[17]['dato'] = $parametros['serie'];

      $where[0]['campo'] = 'id_productos';
      $where[0]['dato']=$parametros['id'];
      return  $this->modelo->update($datos,'productos',$where);
    }else
    {
     $datos[0]['campo'] = 'referencia';
      $datos[0]['dato'] = $parametros['referencia'];
      $datos[1]['campo'] = 'nombre';
      $datos[1]['dato'] = $parametros['nombre'];
      $datos[2]['campo'] = 'precio_uni';
      $datos[2]['dato'] = $parametros['precio'];
      $datos[3]['campo'] = 'iva';
      $datos[3]['dato'] = 0;
      if($parametros['lleva']=='si')
      {
        $datos[3]['dato'] = 1;
      }
      $datos[4]['campo'] = 'stock';
      $datos[4]['dato'] = $parametros['stock'];
      $datos[5]['campo'] = 'inventario';
      $datos[5]['dato'] = 0;
      if($parametros['tipo']=='P')
      {
        $datos[5]['dato'] = 1;
      }
      $datos[6]['campo'] = 'peso';
      $datos[6]['dato'] = $parametros['peso'];
      $datos[7]['campo'] = 'categoria';
      $datos[7]['dato'] = $parametros['cate'];
      $datos[8]['campo'] = 'marca';
      $datos[8]['dato'] = $parametros['marca'];
      $datos[9]['campo'] = 'modelo';
      $datos[9]['dato'] = $parametros['modelo'];
      $datos[10]['campo'] = 'uni_medida';
      $datos[10]['dato'] = $parametros['uni'];
      $datos[11]['campo'] = 'color';
      $datos[11]['dato'] = $parametros['color'];
      $datos[12]['campo'] = 'genero';
      $datos[12]['dato'] = $parametros['genero'];
      $datos[13]['campo'] = 'estado';
      $datos[13]['dato'] = $parametros['estado'];
      $datos[14]['campo'] = 'sucursal';
      $datos[14]['dato'] = $parametros['sucu'];
      $datos[15]['campo'] = 'max';
      $datos[15]['dato'] = $parametros['max'];
      $datos[16]['campo'] = 'min';
      $datos[16]['dato'] = $parametros['min'];
      $datos[17]['campo'] = 'id_empresa';
      $datos[17]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];
      $datos[18]['campo'] = 'serie_pro';
      $datos[18]['dato'] = $parametros['serie'];
      $datos[19]['campo'] = 'fecha_creacion';
      $datos[19]['dato'] = date('Y-m-d');
      $base = $this->modelo->insertar($datos,'productos');
      $datos = $this->modelo->detalle_articulos_ref($parametros['referencia'],$_SESSION['INICIO']['ID_EMPRESA']);
      return $datos[0]['id_productos'];
      
    }
    // print_r($parametros);die();
  }

  function validar_formato_img($file)
  {
    switch ($file['file']['type']) {
      case 'image/jpeg':
      case 'image/pjpeg':
      case 'image/gif':
      case 'image/png':
         return 1;
        break;      
      default:
        return -1;
        break;
    }

  }
  function generarCodigo($longitud) 
  {
       $key = '';
       $pattern = '1234567890';
       $max = strlen($pattern)-1;
       for($i=0;$i < $longitud;$i++)
        { 
          $key.=mt_rand(0,$max);
        }
       return $key;
    } 

  function guardar_foto($file,$post)
  {
    $ruta='../img/articulos/';//ruta carpeta donde queremos copiar las imágenes
    if (!file_exists($ruta)) {
       mkdir($ruta, 0777, true);
    }
    if($this->validar_formato_img($file)==1)
    {
         $uploadfile_temporal=$file['file']['tmp_name'];
         $tipo = explode('/', $file['file']['type']);
         $nombre = $post['txt_nom_img'].'.'.$tipo[1];
         $cod = $this->generarCodigo(7); 
         if($post['txt_nom_img']=='')
         {
           $nombre= $cod.'.'.$tipo[1]; 
         }

         $nuevo_nom=$ruta.$nombre;
         if (is_uploaded_file($uploadfile_temporal))
         {
           move_uploaded_file($uploadfile_temporal,$nuevo_nom);
           if($post['txt_id']=='')
           {
             $datosI[0]['campo']='foto';
             $datosI[0]['dato'] = $nuevo_nom;
             $datosI[1]['campo']='referencia';
             $datosI[1]['dato'] = $cod;
             $datosI[2]['campo']='id_empresa';
             $datosI[2]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];

             $base = $this->modelo->insertar($datosI,'productos');
             $datos = $this->modelo->detalle_articulos_ref($cod,$_SESSION['INICIO']['ID_EMPRESA']);
             // print_r($datos);die();
             return $datos[0]['id_productos'];
           }else
           {
              $datosI[0]['campo']='foto';
              $datosI[0]['dato'] = $nuevo_nom;
              $where[0]['campo'] = 'id_productos';
              $where[0]['dato'] = $post['txt_id'];
              $base = $this->modelo->update($datosI,'productos',$where,$_SESSION['INICIO']['ID_EMPRESA']);
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

   function guardar_foto_materia($file,$post)
  {
    // print_r($post);die();
    $ruta='../img/articulos/';//ruta carpeta donde queremos copiar las imágenes
    if (!file_exists($ruta)) {
       mkdir($ruta, 0777, true);
    }
    if($this->validar_formato_img($file)==1)
    {
         $uploadfile_temporal=$file['file']['tmp_name'];
         $tipo = explode('/', $file['file']['type']);
         $nombre = $post['txt_nom_img'].'.'.$tipo[1];
         $cod = $this->generarCodigo(7); 
         if($post['txt_nom_img']=='')
         {
           $nombre= $cod.'.'.$tipo[1]; 
         }

         $nuevo_nom=$ruta.$nombre;
         if (is_uploaded_file($uploadfile_temporal))
         {
           move_uploaded_file($uploadfile_temporal,$nuevo_nom);
           if($post['txt_id']=='')
           {
             $datosI[0]['campo']='foto';
             $datosI[0]['dato'] = $nuevo_nom;
             $datosI[1]['campo']='referencia';
             $datosI[1]['dato'] = $cod;
             $datosI[2]['campo']='id_empresa';
             $datosI[2]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];

             $base = $this->modelo->insertar($datosI,'productos');
             $datos = $this->modelo->detalle_articulos_ref($cod,$_SESSION['INICIO']['ID_EMPRESA']);
             // print_r($datos);die();
             return $datos[0]['id_productos'];
           }else
           {
              $datosI[0]['campo']='foto';
              $datosI[0]['dato'] = $nuevo_nom;
              $where[0]['campo'] = 'id_productos';
              $where[0]['dato'] = $post['txt_id'];
              $base = $this->modelo->update($datosI,'productos',$where,$_SESSION['INICIO']['ID_EMPRESA']);
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


  function eliminar_articulo($parametros)
  {

    $sql = 'DELETE FROM productos WHERE id_productos ='.$parametros['id'];
    // print_r($sql);die();
    $datos[0]['campo']='id_productos';
    $datos[0]['dato']=$parametros['id'];
    $res = $this->modelo->delete_cod('productos',$datos);
    if($res=='1451' || $res=='547' || $res=='23000')
    {
      return 2;
    }else if($res==1)
    {
      return 1;
    }else
    {
      return -1;
    }
  }

  function agregar_transferencia($parametros)
  {
    // print_r($parametros);die();
    $datos[0]['campo'] = 'id_producto';
    $datos[0]['dato']  = $parametros['id'];
    $datos[1]['campo'] = 'cantidad';
    $datos[1]['dato']  = $parametros['cant'];
    $datos[2]['campo'] = 'id_usuario';
    $datos[2]['dato']  = $_SESSION['INICIO']['ID_USUARIO'];
    // print_r($datos);die();
    return $this->modelo->insertar_('transferencias',$datos);
    // print_r($parametros);die();

  }
  function lista_trasnferencia()
  {
    $datos = $this->modelo->lista_trasnferencia();
    if(count($datos)>0)
    {
      $tr='';
      foreach ($datos as $key => $value) {
        $tr.="<tr>
        <td>".$value['referencia']."</td>
        <td>".$value['nombre']."</td>
        <td>".$value['cantidad']."</td>
        <td>".$value['categoria']."</td>
        <td><button class='btn btn-sm btn-danger' onclick='eliminar_trans(\"".$value['id']."\")'><i class='fa fa-trash'></i></button></td>
        </tr>";
      }
      return $tr;
    }
  }

  function eliminar_transferencia($parametros)
  {
    $id = $parametros['id'];
    return $this->modelo->eliminar_transferencia($id);
  }

  function transferencia($parametros)
  {
     $destino  = $parametros['destino'];
     $datos = $this->modelo->lista_trasnferencia();
     foreach ($datos as $key => $value) {
       $art = $this->modelo->buscar_en_localizacion($destino,$value['referencia']);
       if(count($art)>0)
       {

       }else
       {
        $arti = $this->modelo->
        $datos[0]['campo'] = '';
        $datos[0]['dato']  = '';

       }
     }
     print_r($destino);
     print_r($datos);die();
  }

  function kit_tamanio($parametros)
  {
    $tr='';
    $datos = $this->modelo->kit_tamanio($parametros['id']);
    foreach ($datos as $key => $value) {
      $tr.='<tr>
      <td>'.$value['nombre'].'</td>
      <td>'.$value['precio'].'</td>
      <td>
        <button onclick="eliminar_tama('.$value['id'].')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
        <button onclick="editar_tama('.$value['id'].')" class="btn btn-primary btn-sm"><i class="fa fa-pen"></i></button>
      </td>
      </tr>';
    }
    return $tr;
  }

  function kit_tamanio_delete($id)
  {    
    return $this->modelo->kit_tamanio_delete($id);
  }

  function kit_tamanio_datos($parametros)
  {
    $datos = $this->modelo->kit_tamanio_datos($parametros['id']);   
    return $datos;
  }
  function kit_tamanio_add($parametros)
  {
     $datosI[0]['campo']='nombre';
     $datosI[0]['dato'] = $parametros['nombre'];
     $datosI[1]['campo']='precio';
     $datosI[1]['dato'] = $parametros['precio'];
     $datosI[2]['campo']='id_producto';
     $datosI[2]['dato'] = $parametros['producto'];
     if($parametros['id']!='')
     {
       $where[0]['campo'] = 'id_tamanio';
       $where[0]['dato'] = $parametros['id'];
      return $this->modelo->update_($datosI,$tabla='tamanio',$where);
     }else
     {
      return $this->modelo->insertar_($tabla='tamanio',$datosI);
     }    
  }



  function adicionales($parametros)
  {
    $tr='';
    $datos = $this->modelo->adicionales($parametros['id']);
    foreach ($datos as $key => $value) {
      $tr.='<tr>
      <td>'.$value['nombre'].'</td>
      <td>
        <button onclick="eliminar_adicionales('.$value['id'].')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
      </td>
      </tr>';
    }
    return $tr;
  }

  function adicionales_delete($id)
  {    
    return $this->modelo->adicionales_delete($id);
  }
    function materia_delete($id)
  {    
    return $this->modelo->materia_delete($id);
  }

  function buscar_articulos($query)
  {
     $datos = $this->factura->articulos($query,false,false,'P',$_SESSION['INICIO']['ID_EMPRESA']);
     $ddl =array();
     foreach ($datos as $key => $value) {
       $ddl[] = array('id'=>$value['id_productos'],'text'=>$value['nombre']);
     }
     return $ddl;
  }

  function buscar_articulos_materia($query)
  {
     $datos = $this->factura->articulos_all($_SESSION['INICIO']['ID_EMPRESA'],$query,$ref=false,$categoria=false,$tipo=1,$materia_p=1);
     $ddl =array();
     foreach ($datos as $key => $value) {
       $ddl[] = array('id'=>$value['id_productos'],'text'=>$value['nombre'],'data'=>$value);
     }
     return $ddl;
  }

   function buscar_articulos_inventario($query)
  {
     $datos = $this->factura->articulos_all($_SESSION['INICIO']['ID_EMPRESA'],$query,$ref=false,$categoria=false,$tipo=1,$materia_p=0);
     $ddl =array();
     foreach ($datos as $key => $value) {
       $ddl[] = array('id'=>$value['id_productos'],'text'=>$value['nombre'],'data'=>$value);
     }
     return $ddl;
  }

  function adicionales_datos($parametros)
  {
    $datos = $this->modelo->adicionales_datos($parametros['id']);   
    return $datos;
  }
  function adicionales_add($parametros)
  {
     $datosI[0]['campo']='id_producto';
     $datosI[0]['dato'] = $parametros['id'];
     $datosI[1]['campo']='id_producto_add';
     $datosI[1]['dato'] = $parametros['producto'];     
     return $this->modelo->insertar_($tabla='combo',$datosI);  
  }

  function materia_add($parametros)
  {
    // print_r($parametros);die();
     $datosI[0]['campo']='id_producto';
     $datosI[0]['dato'] = $parametros['id'];
     $datosI[1]['campo']='id_materia_prima';
     $datosI[1]['dato'] = $parametros['materia']; 
     $datosI[2]['campo']='peso';
     $datosI[2]['dato'] = $parametros['peso']; 
     $datosI[3]['campo']='cantidad';
     $datosI[3]['dato'] = $parametros['cantidad']; 

     return $this->modelo->insertar_($tabla='recetas',$datosI);  
  }



  function kit_materia_prima($parametros)
  {
    $datos = $this->modelo->kit_materia_prima($parametros['id']);
    $tr='';
    foreach ($datos as $key => $value) {
      $tr.='<tr>
      <td>'.$value['nombre'].'</td>
      <td>'.$value['cantidad'].'</td>
      <td>'.$value['peso'].'</td>
      <td><button class="btn btn-sm btn-danger" onclick="eliminar_materia_prima('.$value['id'].')"><i class="fa fa-trash"></i></button></td>
      </tr>';
    }
    return $tr;
    // print_r($parametros);die();
  }


  function add_categoria($parametros)
  {
     $datos = $this->modelo->categorias(trim($parametros['categoria']));
     if(count($datos)==0)
     {
       $datos[0]['campo'] ='nombre';     
       $datos[0]['dato']  = trim($parametros['categoria']);
       $datos[1]['campo'] ='empresa';     
       $datos[1]['dato']  =$_SESSION['INICIO']['ID_EMPRESA'];
       return $this->modelo->insertar_($tabla='categoria',$datos);
     }else
     {
      return -2;
     }
  }
 

}

?>