<?php 
require_once(dirname(__DIR__,2).'/modelo/FACTURACION/fac_clientesM.php');
require_once(dirname(__DIR__,2).'/modelo/FACTURACION/facturacionM.php');
require_once(dirname(__DIR__,2).'/modelo/FACTURACION/secuencialesM.php');

/**
 * 
 */
$controlador = new cliente_facturaC();
if(isset($_GET['buscar_cliente_x_ci']))
{
	// print_r($_POST);die();
	$query = $_POST['q'];
	echo json_encode($controlador->buscar_cliente_x_ci($query));
}
if(isset($_GET['buscar_cliente_x_ci2']))
{
	// print_r($_POST);die();
	$query = $_POST['q'];
	echo json_encode($controlador->buscar_cliente_x_ci2($query));
}

if(isset($_GET['buscar_all_x_ci']))
{
	// print_r($_POST);die();
	$query = $_POST['q'];
	echo json_encode($controlador->buscar_all_x_ci($query));
}
if(isset($_GET['buscar_all_x_nombre']))
{
	// print_r($_POST);die();
	$query = $_POST['q'];
	echo json_encode($controlador->buscar_all_x_nombre($query));
}

if(isset($_GET['buscar_proveedor_x_ci']))
{
	// print_r($_POST);die();
	$query = $_POST['q'];
	echo json_encode($controlador->buscar_proveedor_x_ci($query));
}

if(isset($_GET['buscar_proveedor_x_ci_get']))
{
	// print_r($_POST);die();
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_proveedor_x_ci_ddl($query));
}

if(isset($_GET['buscar_proveedor']))
{
	// print_r($_POST);die();
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->buscar_proveedor($parametros));
}

if(isset($_GET['lista_clientes']))
{
	// print_r($_POST);die();
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_clientes($parametros));
}

if(isset($_GET['lista_proveedores']))
{
	// print_r($_POST);die();
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_proveedores($parametros));
}

if(isset($_GET['guardar']))
{
	$parametros = $_POST;
	echo json_encode($controlador->editar_cliente($parametros));
}
if(isset($_GET['guardar_foto']))
{
	$parametros = $_FILES;
	$id = $_GET;
	echo json_encode($controlador->guardar_foto($parametros,$id));
}
if(isset($_GET['guardar_editar']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_editar($parametros));
}
if(isset($_GET['guardar_proveedor']))
{
	$parametros = $_POST;
	echo json_encode($controlador->editar_proveedor($parametros));
}

if(isset($_GET['detalle_cliente']))
{
	$parametros = $_POST['id'];
	echo json_encode($controlador->detalle_cliente($parametros));
}
if(isset($_GET['detalle_proveedor']))
{
	$parametros = $_POST['id'];
	echo json_encode($controlador->detalle_proveedor($parametros));
}
if(isset($_GET['editar_estado']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->editar_estado($parametros));
}
class cliente_facturaC
{
	private $modelo;
	private $factura;
	private $secuencial;
	function __construct()
	{
	  $this->modelo = new fac_clientesM();
	  $this->factura = new facturacionM();
	  $this->secuencial= new secuencialesM();
	}

	// function datos_factura($id)
	// {
	// 	$query = $id;
 //        $lineas = $this->modelo->linea_facturas($query);
 //        return  $lineas;

	// }
	// function factura_cliente($id)
	// {
	// 	$query = $id;
 //        $cliente =  $this->modelo->cliente_factura($query);
 //        return  $cliente;

	// }

	function guardar_foto($file,$id)
	{
		// print_r($id);
		// print_r($file);die();
		if (($file['file']["type"] == "image/pjpeg") || ($file['file']["type"] == "image/jpeg") || ($file['file']["type"] == "image/png") || ($file['file']["type"] == "image/gif"))
		{
			$nombre = 'cliente_'.$id['id'].'_'.$_SESSION['INICIO']['ID_EMPRESA'];
			$tipo = explode('/', $file['file']["type"]);
			$tipo = $tipo[1];
			if (move_uploaded_file($file['file']["tmp_name"], "../img/clientes/".$nombre.'.'.$tipo)) 
			{
				$datos[0]['campo'] = 'foto';
				$datos[0]['dato'] = $nombre.'.'.$tipo;

				$datosW[0]['campo'] = 'id_cliente';
				$datosW[0]['dato'] = $id['id'];

				return $this->modelo->update('cliente',$datos,$datosW,$_SESSION['INICIO']['ID_EMPRESA']);

			} else {
				return -1;
			}
		} else {
			return 2;
		}
		print_r($file);die();

	}

	function guardar_editar($parametros)
	{

		$datos[0]['campo']='nombre';
		$datos[0]['dato']=$parametros['txt_nombre'];
		$datos[1]['campo']='telefono';
		$datos[1]['dato']=$parametros['txt_telefono'];
		$datos[2]['campo']='mail';
		$datos[2]['dato']=$parametros['txt_email'];
		$datos[3]['campo']='direccion';
		$datos[3]['dato']=$parametros['txt_direccion'];		
		$datos[4]['campo']=	'ci_ruc';
		$datos[4]['dato']=$parametros['txt_ci'];		
		$datos[5]['campo']=	'id_empresa';
		$datos[5]['dato']=$_SESSION['INICIO']['ID_EMPRESA'];	
		$datos[6]['campo']=	'Razon_Social';
		if($parametros['txt_razon']!='')
		{
			$datos[6]['dato']=$parametros['txt_razon'];	
		}else
		{
			$datos[6]['dato']=$parametros['txt_nombre'];
		}
		$datos[7]['campo']=	'tipo';
		$datos[7]['dato']=$parametros['txt_tipo'];
		if(strlen($parametros['txt_ci'])==10)
		{
			$datos[8]['campo']=	'TD';
			$datos[8]['dato']='C';
		}else if(strlen($parametros['txt_ci'])==13)
		{			
			$datos[8]['campo']=	'TD';
			$datos[8]['dato']='R';
		}else{

			$datos[8]['campo']=	'TD';
			$datos[8]['dato']='O';
		}

		$idCli = '';
		if($parametros['txt_id']!='')
		{
		    $where[0]['campo']='id_cliente';
		    $where[0]['dato']=$parametros['txt_id'];
            return $this->modelo->update($tabla='cliente',$datos,$where,$_SESSION['INICIO']['ID_EMPRESA']);
            
		}else
		{
		   $resp = $this->modelo->lista_clientes(false,$datos[4]['dato'],$parametros['txt_tipo']);
		   if(count($resp)==0)
		   {
		   	$this->modelo->add($tabla='cliente',$datos,$_SESSION['INICIO']['ID_EMPRESA']);
		   	$resp = $this->modelo->lista_clientes(false,$datos[4]['dato'],$parametros['txt_tipo']);
		   	return $resp[0]['id'];
		   }else
		   {
		   	// si encuentra la cedula registrada
		   	 return -2;
		   }
		}


	}

	function editar_cliente($parametros)
	{
		// print_r($parametros);die();
		// print_r($_SESSION);die();
		$datos[0]['campo']='nombre';
		$datos[0]['dato']=$parametros['txt_nombre'];
		$datos[1]['campo']='telefono';
		$datos[1]['dato']=$parametros['txt_telefono'];
		$datos[2]['campo']='mail';
		$datos[2]['dato']=$parametros['txt_email'];
		$datos[3]['campo']='direccion';
		$datos[3]['dato']=$parametros['txt_direccion'];		
		$datos[4]['campo']=	'ci_ruc';
		$datos[4]['dato']=$parametros['txt_ci'];		
		$datos[5]['campo']=	'id_empresa';
		$datos[5]['dato']=$_SESSION['INICIO']['ID_EMPRESA'];	
		$datos[6]['campo']=	'Razon_Social';
		if($parametros['txt_razon']!='')
		{
			$datos[6]['dato']=$parametros['txt_razon'];	
		}else
		{
			$datos[6]['dato']=$parametros['txt_nombre'];
		}
		$datos[7]['campo']=	'tipo';
		$datos[7]['dato']='C';

		$idCli = '';
		if($parametros['txt_id']!='')
		{
		    $where[0]['campo']='id_cliente';
		    $where[0]['dato']=$parametros['txt_id'];
            $this->modelo->editar($datos,$where);
            $idCli = $parametros['txt_id'];

		}else
		{
		   $this->modelo->insertar($datos);
		   $dato = $this->modelo->lista_clientes(false,$datos[4]['dato']);
		   $idCli = $dato[0]['id'];
		}


		if(!isset($parametros['txt_fecha']))
		{
			$parametros['txt_fecha'] = date('Y-m-d');
		}

		$detalle = 'FA_SERIE_'.str_replace('-','', $_SESSION['INICIO']['SERIE']);

		$numero = $this->secuencial->where('DETALLE',$detalle)->listar();
		if(count($numero)==0)
		{
			$this->ingresar_secuencial($detalle);
			$numero[0]['numero'] = 0;
			// $numero[0]['Autorizacion'] = $_SESSION['INICIO']['Autorizacion'];
		}


		$vali = $this->secuencial->validar_mas_series('FA_SERIE_'.$_SESSION['INICIO']['SERIE']);

        $new_num = $numero[0]['numero']+1;
	    $datosF[0]['campo']='id_empresa';
	    $datosF[0]['dato']=$_SESSION['INICIO']['ID_EMPRESA'];
	    $datosF[1]['campo']='id_cliente';
	    $datosF[1]['dato']=$idCli;
	    $datosF[2]['campo']='id_usuario';
	    $datosF[2]['dato']=$_SESSION['INICIO']['ID_USUARIO'];
	    $datosF[3]['campo']='fecha';
	    $datosF[3]['dato']=$parametros['txt_fecha'];    
	    $datosF[4]['campo']='Porc_IVA';
	    $datosF[4]['dato']=number_format(($_SESSION['INICIO']['IVA']/100),2,'.','');
	    if(count($vali)==1)
		{
			// print_r($numero);die();
	    	$datosF[5]['campo']='Serie';
	    	$datosF[5]['dato']=$_SESSION['INICIO']['SERIE'];   
	    	$datosF[6]['campo']='num_factura';
	    	$datosF[6]['dato']=$new_num ;  

	    	$datosSecu[0]['campo'] = 'numero';
	    	$datosSecu[0]['dato'] = $new_num;
	    	$datosSecuWhere[0]['campo'] = 'ID_SECUENCIALES';
	    	$datosSecuWhere[0]['dato'] = $numero[0]['_id'];

	    	$this->secuencial->editar($datosSecu,$datosSecuWhere);
			
		}
	
    // print_r($numero);die();

	    
	    $re = $this->factura->insertar($datosF);
	    // print_r($re);
	    $FA = $this->factura->buscar_facturas($_SESSION['INICIO']['ID_EMPRESA'],$new_num);
	    return $FA;


	}



	function editar_proveedor($parametros)
	{
		// print_r($parametros);die();
		// print_r($_SESSION);die();
		$datos[0]['campo']='nombre';
		$datos[0]['dato']=$parametros['txt_nombre'];
		$datos[1]['campo']='telefono';
		$datos[1]['dato']=$parametros['txt_telefono'];
		$datos[2]['campo']='mail';
		$datos[2]['dato']=$parametros['txt_email'];
		$datos[3]['campo']='direccion';
		$datos[3]['dato']=$parametros['txt_direccion'];		
		$datos[4]['campo']=	'ci_ruc';
		$datos[4]['dato']=$parametros['txt_ci'];		
		$datos[5]['campo']=	'id_empresa';
		$datos[5]['dato']=$_SESSION['INICIO']['ID_EMPRESA'];	
		$datos[6]['campo']=	'Razon_Social';
		if($parametros['txt_razon']!='')
		{
			$datos[6]['dato']=$parametros['txt_razon'];	
		}else
		{
			$datos[6]['dato']=$parametros['txt_nombre'];
		}
		$datos[7]['campo']=	'tipo';
		$datos[7]['dato']='P';

		$idCli = '';
		if($parametros['txt_id']!='')
		{
		    $where[0]['campo']='id_cliente';
		    $where[0]['dato']=$parametros['txt_id'];
            $this->modelo->update($tabla='cliente',$datos,$where,$_SESSION['INICIO']['ID_EMPRESA']);
            $idCli = $parametros['txt_id'];

		}else
		{
		   $this->modelo->add($tabla='cliente',$datos,$_SESSION['INICIO']['ID_EMPRESA']);
		   $dato = $this->modelo->lista_proveedores(false,$datos[4]['dato']);
		   $idCli = $dato[0]['id'];
		}

		return $idCli;
	}

	function buscar_all_x_ci($query)
	{
		$ci = false;
		if (is_numeric($query)) {
		   $ci = $query;
		   $query = false;
		}
		$lista =array();
		$nombres = $this->modelo->lista_clientes_all($query,$ci);
		foreach ($nombres as $key => $value) {
			$lista[] = array('value'=>$value['id'],'label'=>$value['ci_ruc'],'telefono'=>$value['telefono'],'email'=>$value['mail'],'direccion'=>$value['direccion'],'nombre'=>$value['nombre'],'razon'=>$value['razon']);
		}
		// print_r($lista);die();
        return  $lista;
	}
	function buscar_all_x_nombre($query)
	{
		$ci = false;
		if (is_numeric($query)) {
		   $ci = $query;
		   $query = false;
		}
		$lista =array();
		$nombres = $this->modelo->lista_clientes_all($query,$ci);
		foreach ($nombres as $key => $value) {
			$lista[] = array('value'=>$value['id'],'label'=>$value['nombre'],'telefono'=>$value['telefono'],'email'=>$value['mail'],'direccion'=>$value['direccion'],'ci'=>$value['ci_ruc'],'razon'=>$value['razon']);
		}
		// print_r($lista);die();
        return  $lista;
	}




	
	function buscar_cliente_x_ci($query)
	{
		$ci = false;
		if (is_numeric($query)) {
		   $ci = $query;
		   $query = false;
		}
		$lista =array();
		$nombres = $this->modelo->lista_clientes($query,$ci);
		foreach ($nombres as $key => $value) {
			$lista[] = array('value'=>$value['id'],'label'=>$value['ci_ruc'],'telefono'=>$value['telefono'],'email'=>$value['mail'],'direccion'=>$value['direccion'],'nombre'=>$value['nombre'],'razon'=>$value['razon']);
		}
		// print_r($lista);die();
        return  $lista;
	}
	function buscar_cliente_x_ci2($query)
	{
		$ci = false;
		if (is_numeric($query)) {
		   $ci = $query;
		   $query = false;
		}
		$lista =array();
		$nombres = $this->modelo->lista_clientes($query,$ci);
		foreach ($nombres as $key => $value) {
			$lista[] = array('value'=>$value['id'],'label'=>$value['nombre'],'telefono'=>$value['telefono'],'email'=>$value['mail'],'direccion'=>$value['direccion'],'ci'=>$value['ci_ruc'],'razon'=>$value['razon']);
		}
		// print_r($lista);die();
        return  $lista;
	}

	function buscar_proveedor_x_ci($query)
	{
		// print_r($query);die();
		$lista =array();
		$nombres = $this->modelo->lista_proveedores(false,$query);
		foreach ($nombres as $key => $value) {
			$lista[] = array('value'=>$value['id'],'label'=>$value['ci_ruc'],'telefono'=>$value['telefono'],'email'=>$value['mail'],'direccion'=>$value['direccion'],'nombre'=>$value['nombre'],'razon'=>$value['razon']);
		}
		// print_r($lista);die();
        return  $lista;
	}

	function buscar_proveedor_x_ci_ddl($query)
	{
		// print_r($query);die();
		$lista =array();
		$nombres = $this->modelo->lista_proveedores(false,$query);
		foreach ($nombres as $key => $value) {
			$lista[] = array('id'=>$value['id'],'text'=>$value['nombre'],'datos'=>$value);
		}
		// print_r($lista);die();
        return  $lista;
	}


	function buscar_proveedor($parametros)
	{
		// print_r($query);die();
		$nombres = $this->modelo->lista_proveedores(false,false,$parametros['id']);
		
        return  $nombres;
	}

	function lista_clientes($parametros)
	{
		// print_r($query);die();
		$ci=false;$nombre=false;
		if(is_numeric($parametros['query']))
		{
			$ci = $parametros['query'];
		}else
		{
			$nombre = $parametros['query'];
		}
		$datos = $this->modelo->lista_clientes($nombre,$ci);
		$tr='';
		foreach ($datos as $key => $value) {
			$foto = 'sin_foto.jpg';
			if(file_exists('../img/clientes/'.$value['foto']))
			{
				$foto = $value['foto'];
			}
			if($foto=='' || $foto==null)
			{
				$foto = 'sin_foto.jpg';
			}
			$tr.='<div class="col-xl-4">
			<div class="card">							
				<div class="card-body">
					<div class="row g-0">
						<div class="col-sm-3 col-xl-12 col-xxl-3 text-center">
							<img src="../img/clientes/'.$foto.'" width="80" height="80" class="rounded-circle mt-2" alt="Angelica Ramos">
						</div>						
					</div>
					<table class="table table-sm mt-2 mb-4">
						<tbody>
							<tr>
								<th>Nombre</th>
								<td>'.$value['nombre'].'</td>
							</tr>
							<tr>
								<th>Razon social</th>
								<td>'.$value['razon'].'</td>
							</tr>
							<tr>
								<th>CI / RUC</th>
								<td>'.$value['ci_ruc'].'</td>
							</tr>
							<tr>
								<th>Email</th>
								<td>'.$value['mail'].'</td>
							</tr>
							<tr>
								<th>Telefono</th>
								<td>'.$value['telefono'].'</td>
							</tr>
							<tr>
								<th>Estado</th>';
								if($value['estado']=='A')
								{
									$tr.='<td><span class="badge bg-success">Active</span></td>';
								}else
								{
									$tr.='<td><span class="badge bg-danger">Inactivo</span></td>';
								}
							$tr.='</tr>
							<tr>
								<th>Direccion</th>
								<td>'.$value['direccion'].'</td>
							</tr>

						</tbody>
					</table>
					<a href="detalle_cliente.php?id='.$value['id'].'" class="btn btn-primary btn-block btn-sm"><i class="fa fa-eye"></i> Ver Detalles</a>
				</div>
			</div>
		</div>';
			// code...
		}
        return  $tr;
	}


	function lista_proveedores($parametros)
	{
		// print_r($query);die();
		$ci=false;$nombre=false;
		if(is_numeric($parametros['query']))
		{
			$ci = $parametros['query'];
		}else
		{
			$nombre = $parametros['query'];
		}
		$datos = $this->modelo->lista_proveedores($nombre,$ci);
		$tr='';
		foreach ($datos as $key => $value) {
			$foto = 'sin_foto.jpg';
			if(file_exists('../img/clientes/'.$value['foto']))
			{
				$foto = $value['foto'];
			}
			if($foto=='' || $foto==null)
			{
				$foto = 'sin_foto.jpg';
			}
			$tr.='<div class="col-xl-4">
			<div class="card">							
				<div class="card-body">
					<div class="row g-0">
						<div class="col-sm-3 col-xl-12 col-xxl-3 text-center">
							<img src="../img/clientes/'.$foto.'" width="80" height="80" class="rounded-circle mt-2" alt="Angelica Ramos">
						</div>						
					</div>
					<table class="table table-sm mt-2 mb-4">
						<tbody>
							<tr>
								<th>Nombre</th>
								<td>'.$value['nombre'].'</td>
							</tr>
							<tr>
								<th>Razon social</th>
								<td>'.$value['razon'].'</td>
							</tr>
							<tr>
								<th>CI / RUC</th>
								<td>'.$value['ci_ruc'].'</td>
							</tr>
							<tr>
								<th>Email</th>
								<td>'.$value['mail'].'</td>
							</tr>
							<tr>
								<th>Telefono</th>
								<td>'.$value['telefono'].'</td>
							</tr>
							<tr>
								<th>Estado</th>';
								if($value['estado']=='A')
								{
									$tr.='<td><span class="badge bg-success">Active</span></td>';
								}else
								{
									$tr.='<td><span class="badge bg-danger">Inactivo</span></td>';
								}
							$tr.='</tr>
							<tr>
								<th>Direccion</th>
								<td>'.$value['direccion'].'</td>
							</tr>

						</tbody>
					</table>
					<a href="detalle_proveedor.php?id='.$value['id'].'" class="btn btn-primary btn-block btn-sm"><i class="fa fa-eye"></i> Ver Detalles</a>
				</div>
			</div>
		</div>';
			// code...
		}
        return  $tr;
	}


	function detalle_cliente($id)
	{
		$cliente = $this->modelo->lista_clientes($query=false,$ci=false,$id);
		if(count($cliente)>0)
		{
			if($cliente[0]['foto']=='' || $cliente[0]['foto']==null)
			{
				$cliente[0]['foto'] = 'sin_foto.jpg';
			}
			if(!file_exists('../img/clientes/'.$cliente[0]['foto']))
			{
				$cliente[0]['foto'] = 'sin_foto.jpg';
			}
			return $cliente[0];
		}else
		{
			return -1;
		}
	}
	function detalle_proveedor($id)
	{
		$cliente = $this->modelo->lista_proveedores($query=false,$ci=false,$id);
		if(count($cliente)>0)
		{
			if($cliente[0]['foto']=='' || $cliente[0]['foto']==null)
			{
				$cliente[0]['foto'] = 'sin_foto.jpg';
			}
			if(!file_exists('../img/clientes/'.$cliente[0]['foto']))
			{
				$cliente[0]['foto'] = 'sin_foto.jpg';
			}
			return $cliente[0];
		}else
		{
			return -1;
		}
	}

	function editar_estado($parametros)
	{
		$datos[0]['campo'] = 'estado';
		$datos[0]['dato'] = $parametros['estado'];

		$datosW[0]['campo'] = 'id_cliente';
		$datosW[0]['dato'] = $parametros['id'];

		return $this->modelo->update('cliente',$datos,$datosW,$_SESSION['INICIO']['ID_EMPRESA']);
	}


	// function buscar_articulo($query,$idempresa)
	// {
	// 	$nombres = $this->modelo->articulos($query,$idempresa);
 //        return  $nombres;
	// }

	// function Sri($id,$idempresa)
	// {
	// 	$parametros = array('empresa'=>$idempresa,'fac'=>$idempresa);
	// 	return  $this->sri->Autorizar($parametros);
	// }
	// function factura_pdf($id)
	// {
	// 	$datos = array();
	// 	$this->pdf->factura_pdf($datos);
	// }

	// function lista_facturas($query,$empresa)
	// {
	// 	$result = $this->modelo->lista_facturas($query,$empresa);
	// 	return $result;
	// }

	// function enviar_Email($empresa)
	// {
	// 	$to_correo = 'javier.farinango92@gmail.com';
	// 	$cuerpo_correo = '<b>hola mail</b>';
	// 	$titulo_correo = 'correo de prueba';
	// 	$correo_respaldo = 'example@example.com';
	// 	$archivos = array('1804202101070216417900110010010000000011234567813.xml','2004202101070216417900110010010000000041234567817.xml');
	// 	$HTML = true;
	// 	$this->mail->enviar_email($empresa,$to_correo,$cuerpo_correo,$titulo_correo,$correo_respaldo,$archivos,$nombre='Email envio',$HTML);
	// }

	// function eliminar_linea($id)
	// {
	// 	$datos[0]['campo']='id_lineas';
	// 	$datos[0]['dato']= $id;
 //        return  $this->modelo->delete('lineas_factura',$datos);
	// }
	function ingresar_secuencial($detalle)
	{
		$datos[0]['campo'] = 'DETALLE';
		$datos[0]['dato'] = $detalle;
		$datos[1]['campo'] = 'NUMERO';
		$datos[1]['dato'] = 1;
		$datos[2]['campo'] = 'id_empresa';
		$datos[2]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];
		// $datos[3]['campo'] = 'Autorizacion';
		// $datos[3]['dato'] = $_SESSION['INICIO']['RUC_EMPRESA'];
		// $datos[4]['campo'] = 'Serie';
		// $datos[4]['dato'] = $_SESSION['INICIO']['SERIE'];
		// print_r($datos);die();
		$res = $this->secuencial->insertar($datos);
	}

}

?>