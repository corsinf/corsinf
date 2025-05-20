<?php 
include('../modelo/nueva_empresaM.php');
require_once('../db/codigos_globales.php');
require_once(dirname(__DIR__,1).'/modelo/modulos_paginasM.php');
/**
 * 
 */
$controlador = new nueva_empresaC();

if(isset($_GET['iniciar']))
{
	echo json_encode($controlador->buscar_marcas());
}
if(isset($_GET['Guardar_empresa']))
{
	$parametros = $_POST;
	$file = $_FILES;
	echo json_encode($controlador->insertar_editar_canal($parametros,$file));
}
if(isset($_GET['modulos_sistema']))
{
	echo json_encode($controlador->modulos_sistema());
}
if(isset($_GET['listaClienteEmpresas']))
{
	echo json_encode($controlador->listaClienteEmpresas());
}
if(isset($_GET['detalle_licencias']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->detalle_licencias($parametros));
}
if(isset($_GET['detalle_empresa']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->detalle_empresa($parametros));
}
if(isset($_GET['dar_alta']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->dar_alta($parametros));
}


class nueva_empresaC
{
	private $modelo;
	private $cod_global;
	private $modulos_pag;
	
	function __construct()
	{
		$this->modelo = new nueva_empresaM();
		$this->cod_global = new codigos_globales();
		$this->modulos_pag = new modulos_paginasM();
		
	}
	function buscar_marcas()
	{
		$base= 'NUEVA_EMPRESA';
		$modulo = dirname(__DIR__).'/db/Modulos_db/principal.txt';
		$inserts = dirname(__DIR__).'/db/Modulos_db/datos_default.txt';
		// print_r($modulo);die();

		// $datos = $this->modelo->generar_tablas_modulos($base,$modulo);
		$datos = $this->modelo->cargar_datos_default($base,$inserts);
		return $datos;
	}
	function insertar_editar_canal($parametros,$file)
	{
		// print_r($parametros);
		// print_r($file);die();

		$host = '186.4.219.172';
		$tipo = 'SQLSERVER';
		$user = 'sa';
		$pass = 'Tango456';
		$puer = '1487';

		$smtp_host = 'corsinf.com';
		$smtp_port = '465';
		$smtp_usuario = 'soporte';
		$smtp_pass = '62839300';
		$smtp_secure = 'ssl';
		
		$empresa = $this->modelo->buscar_empresa($parametros['txt_ci']);
		if(count($empresa)==0)
		{

			$nuevo_nom = '../img/de_sistema/sin-logo.png';
			if($file['txt_logo']['full_path']!='')
			{
				$ruta='../img/empresa/';
			    if (!file_exists($ruta)) {
			       mkdir($ruta, 0777, true);
			    }
				$uploadfile_temporal=$file['txt_logo']['tmp_name'];
			    $tipo = explode('/', $file['txt_logo']['type']);
			    $nombre = $parametros['txt_ci'].'.'.$tipo[1];	   
			    $nuevo_nom=$ruta.$nombre;
		         if (is_uploaded_file($uploadfile_temporal))
		         {
		           move_uploaded_file($uploadfile_temporal,$nuevo_nom);
		         }
		     }
			$datos[0]['campo'] ='Razon_Social';
			$datos[0]['dato']= $parametros['txt_razon'];
			$datos[1]['campo'] = 'Nombre_Comercial';
			$datos[1]['dato']= $parametros['txt_empresa_nom'];
			$datos[2]['campo'] ='Ruc';
			$datos[2]['dato']= $parametros['txt_ci'];
			$datos[3]['campo'] = 'Direccion';
			$datos[3]['dato']= $parametros['txt_direccion'];
			$datos[4]['campo'] ='Telefono';
			$datos[4]['dato']= $parametros['txt_telefono'];
			// $datos[4]['tipo'] ='STRING';
			$datos[5]['campo'] = 'Email';
			$datos[5]['dato']= $parametros['txt_email'];
			$datos[6]['campo'] ='Logo';
			$datos[6]['dato']= $nuevo_nom;
			//base de datos

			if($parametros['rbx_base']==1)
			{
				$host = $parametros['txt_ip'];
				$tipo = $parametros['txt_tipo_base'];
				$user = $parametros['txt_usuario_db'];
				$pass = $parametros['txt_pass_db'];
				$puer =  $parametros['txt_puerto'];
			}
			$datos[7]['campo'] = 'Ip_host';
			$datos[7]['dato']=  $host;
			$datos[8]['campo'] ='Base_datos';
			$datos[8]['dato']=  $parametros['txt_base'];
			$datos[9]['campo'] = 'Tipo_Base';
			$datos[9]['dato']=  $tipo;
			$datos[10]['campo'] = 'Usuario_db';
			$datos[10]['dato']= $user;
			$datos[11]['campo'] = 'Password_db';
			$datos[11]['dato']= $pass;
			$datos[12]['campo'] = 'Puerto_db';
			$datos[12]['dato']= $puer;
			//smtp 
			if($parametros['rbl_smtp_default']=='no')
			{
				$smtp_host = $parametros['txt_host'];
				$smtp_port =  $parametros['txt_puerto_smtp'];
				$smtp_usuario =  $parametros['txt_usuario_smtp'];
				$smtp_pass =  $parametros['txt_pass_smtp'];
				$smtp_secure = $parametros['rbl_secure'];

			}
			$datos[13]['campo'] = 'smtp_host';
			$datos[13]['dato']= $smtp_host;
			$datos[14]['campo'] = 'smtp_port';
			$datos[14]['dato']=$smtp_port;
			$datos[15]['campo'] = 'smtp_usuario';
			$datos[15]['dato']=$smtp_usuario;
			$datos[16]['campo'] = 'smtp_pass';
			$datos[16]['dato']=$smtp_pass;
			$datos[17]['campo'] = 'smtp_secure';
			$datos[17]['dato']= $smtp_secure;


			$datos[18]['campo'] = 'Ambiente';
			$datos[18]['dato']= '1';

			$datos[19]['campo'] = 'Estado';
			$datos[19]['dato']= 'I';

			$this->modelo->add('EMPRESAS',$datos);
			$empresa = $this->modelo->buscar_empresa($datos[2]['dato']);

			//licencias
			$id_empresa = $empresa[0]['Id_empresa'];
			$licencia = json_decode($parametros['licencias'],true);
			
			foreach ($licencia as $key => $value) {
				$inicio = date('Y-m-d');
				$fecha = new DateTime($inicio);
				$fecha->modify('+'.$value['periodo'].' months');
				$fin = $fecha->format('Y-m-d');

				$licencia_cod = $this->cod_global->generar_licencia($id_empresa,$value['modulo'],$inicio,$fin);

				$datos = array(
					array('campo'=>'Codigo_licencia','dato'=>$licencia_cod),
					array('campo'=>'Id_empresa','dato'=>$id_empresa),
					array('campo'=>'Fecha_ini','dato'=>$inicio),
					array('campo'=>'Fecha_exp','dato'=>$fin),
					array('campo'=>'Numero_maquinas','dato'=>$value['maquinas']),
					array('campo'=>'Id_Modulo','dato'=>$value['modulo']),
					array('campo'=>'registrado','dato'=>0),
					array('campo'=>'numero_pda','dato'=>$value['pda']),
				);

				$this->modelo->add('LICENCIAS',$datos);
			}

			//accesos
			for ($i=1; $i < 4; $i++) { 		 	
				$datos2[0]['campo'] = 'Id_usuario';
				$datos2[0]['dato']= $i;
				$datos2[1]['campo'] = 'Id_Empresa';
				$datos2[1]['dato']= $empresa[0]['Id_empresa'];	
				$datos2[2]['campo'] = 'Id_Tipo_usuario';
				$datos2[2]['dato']  = $i;
				$this->modelo->add('ACCESOS_EMPRESA',$datos2);
			 }


			 // acceso al canal desde el usuario
			 	$datos2[0]['campo'] = 'Id_usuario';
				$datos2[0]['dato']= $_SESSION['INICIO']['ID_USUARIO'];
				$datos2[1]['campo'] = 'Id_Empresa';
				$datos2[1]['dato']= $empresa[0]['Id_empresa'];	
				$datos2[2]['campo'] = 'Id_Tipo_usuario';
				$datos2[2]['dato']  = 3;
				$this->modelo->add('ACCESOS_EMPRESA',$datos2);

				$tipo = $this->modelo->accesos();	
				if(count($tipo)==0)
				{
					 $datosA[0]['campo'] = 'Ver';
					 $datosA[0]['dato']  = 1;
					 $datosA[1]['campo'] = 'editar';
					 $datosA[1]['dato']  = 1;
					 $datosA[2]['campo'] = 'eliminar';
					 $datosA[2]['dato']  = 1;
					 $datosA[3]['campo'] = 'dba';
					 $datosA[3]['dato']  = 1;
					 $datosA[4]['campo'] = 'id_paginas';
					 $datosA[4]['dato']  = 93;
					 $datosA[5]['campo'] = 'id_tipo_usu';
					 $datosA[5]['dato']  = 3;
					 $this->modelo->add('ACCESOS',$datosA);		
				} 		



			 $datos = array(
					array('campo'=>'ca_id_empresa','dato'=>$id_empresa),
					array('campo'=>'ca_id_usuario','dato'=>$_SESSION['INICIO']['ID_USUARIO']),
					array('campo'=>'ca_fecha_registro','dato'=>date('Y-m-d')),
					array('campo'=>'ca_fecha_modificacion','dato'=>date('Y-m-d')),
				);

			$this->modelo->addActual('ca_clientes_canal',$datos);
			

		 	return 1;  
	 	}else
	 	{
	 		return -2;
	 	}

	}

	function insertar_editar($parametros,$file)
	{
		print_r($parametros);
		print_r($file);die();

		$empresa = $this->modelo->buscar_empresa($parametros['txt_ci']);
		if(count($empresa)==0)
		{
			$nuevo_nom = '../img/de_sistema/sin-logo.png';
			if($file['txt_logo']['full_path']!='')
			{
				$ruta='../img/empresa/';
			    if (!file_exists($ruta)) {
			       mkdir($ruta, 0777, true);
			    }
				$uploadfile_temporal=$file['txt_logo']['tmp_name'];
			    $tipo = explode('/', $file['txt_logo']['type']);
			    $nombre = $parametros['txt_ci'].'.'.$tipo[1];	   
			    $nuevo_nom=$ruta.$nombre;
		         if (is_uploaded_file($uploadfile_temporal))
		         {
		           move_uploaded_file($uploadfile_temporal,$nuevo_nom);
		         }
		     }
			$datos[0]['campo'] ='Razon_Social';
			$datos[0]['dato']= $parametros['txt_empresa'];
			$datos[1]['campo'] = 'Nombre_Comercial';
			$datos[1]['dato']= $parametros['txt_empresa'];
			$datos[2]['campo'] ='Ruc';
			$datos[2]['dato']= $parametros['txt_ci'];
			$datos[3]['campo'] = 'Direccion';
			$datos[3]['dato']= $parametros['txt_direccion'];
			$datos[4]['campo'] ='Telefono';
			$datos[4]['dato']= $parametros['txt_telefono'];
			// $datos[4]['tipo'] ='STRING';
			$datos[5]['campo'] = 'Email';
			$datos[5]['dato']= $parametros['txt_email'];
			$datos[6]['campo'] ='Logo';
			$datos[6]['dato']= $nuevo_nom;
			//base de datos
			$datos[7]['campo'] = 'Ip_host';
			$datos[7]['dato']= $parametros['txt_ip'];
			$datos[8]['campo'] ='Base_datos';
			$datos[8]['dato']= $parametros['txt_base'];
			$datos[9]['campo'] = 'Tipo_Base';
			$datos[9]['dato']= $parametros['txt_tipo_base'];
			$datos[10]['campo'] = 'Usuario_db';
			$datos[10]['dato']= $parametros['txt_usuario_db'];
			$datos[11]['campo'] = 'Password_db';
			$datos[11]['dato']= $parametros['txt_pass_db'];
			$datos[12]['campo'] = 'Puerto_db';
			$datos[12]['dato']= $parametros['txt_puerto'];
			//smtp 
			$datos[13]['campo'] = 'smtp_host';
			$datos[13]['dato']= $parametros['txt_host'];
			$datos[14]['campo'] = 'smtp_port';
			$datos[14]['dato']= $parametros['txt_puerto_smtp'];
			$datos[15]['campo'] = 'smtp_usuario';
			$datos[15]['dato']= $parametros['txt_usuario_smtp'];
			$datos[16]['campo'] = 'smtp_pass';
			$datos[16]['dato']= $parametros['txt_pass_smtp'];
			$datos[17]['campo'] = 'smtp_secure';
			$datos[17]['dato']= $parametros['rbl_secure'];

			$this->modelo->add('EMPRESAS',$datos);
			$empresa = $this->modelo->buscar_empresa($datos[2]['dato']);


			for ($i=1; $i < 4; $i++) { 		 	
				$datos2[0]['campo'] = 'Id_usuario';
				$datos2[0]['dato']= $i;
				$datos2[1]['campo'] = 'Id_Empresa';
				$datos2[1]['dato']= $empresa[0]['Id_empresa'];	
				$datos2[2]['campo'] = 'Id_Tipo_usuario';
				$datos2[2]['dato']  = $i;
				$this->modelo->add('ACCESOS_EMPRESA',$datos2);
			 }
			

		 	return 1;  
	 	}else
	 	{
	 		return -2;
	 	}

	}

	function modulos_sistema()
	{
		$data = $this->modulos_pag->modulos_sis_all(1);
		return $data;
		// print_r($data);die();
	}

	function listaClienteEmpresas()
	{
		$dba = 0;
		if($_SESSION['INICIO']['TIPO']!='DBA')
		{
			$datos = $this->modelo->listaClienteEmpresas(strval($_SESSION['INICIO']['ID_USUARIO']));
		}else{
			$datos = $this->modelo->listaClienteEmpresas();	
			$dba = 1;
				
		}

		// print_r($datos);die();
		$listaEmpresas = array();
		foreach ($datos as $key => $value) {
			$empresa = $this->modelo->buscar_empresa(false,$value['ca_id_empresa']);
			if(count($empresa)>0)
			{
				array_push($listaEmpresas,$empresa[0]);
			}
		}

		$html = '';

		foreach ($listaEmpresas as $key => $value) {
			$html.= '<tr>
						<td>'.$value['Razon_Social'].'</td>
						<td>'.$value['Ruc'].'</td>
						<td>'.$value['Telefono'].'</td>
						<td>'.$value['Email'].'</td>';
						 if($value['Estado']=='I')
                          {
                              $html.='<td><div class="d-flex align-items-center text-danger"> <i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>
                                    <span>Validando Informacion</span></td>';
                          }else
                          {
                               $html.='<td><div class="d-flex align-items-center text-success"> <i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>
                                    <span>Validado</span></td>';
                          }
						$html.='<td>';
						if($dba==1 && $value['Estado']=='I')
						{
							$html.='<button type="button" onclick="dar_alta(\''.$value['Id_empresa'].'\')" title="Dar de alta" class="btn btn-success btn-sm"><i class="bx bxs-check-circle me-0"></i></button>';
						}

						$html.='<button type="button" onclick="detalle_empresa(\''.$value['Id_empresa'].'\')" title="Detalle de empresa" class="btn btn-primary btn-sm"><i class="bx bx-buildings me-0"></i></button>';
						if($value['Estado']=='A')
						{
							$html.='<button type="button" onclick="detalle_licencias(\''.$value['Id_empresa'].'\')" title="Detalle de licencias" class="btn btn-primary btn-sm"><i class="bx bx-key me-0"></i></button>';
						}
							
						$html.='</td>
					</tr>';
		}

		return $html;
	}

	function detalle_licencias($parametros)
	{
		$datos = $this->cod_global->buscar_licencias($parametros['id']);
		return $datos;

		// print_r($datos);die();
	}

	function detalle_empresa($parametros)
	{
		$datos = $this->cod_global->buscar_empresa(false,$parametros['id']);
		$modulos = $this->cod_global->buscar_licencias($parametros['id']);
		$datos[0]['modulos'] = $modulos;

		return $datos;
		 // print_r($datos);die();
	}

	function dar_alta($parametros)
	{
		// $datos = $this->cod_global->buscar_empresa(false,$parametros['id']);
		// return $datos;
		$empresa = $this->modelo->buscar_empresa(false,$parametros['id']);
		// print_r($empresa);die();
		 // print_r($parametros);die();
		if(IP_MASTER==$empresa[0]['Ip_host'])
		{
			//local

			// 1.- Crea la base de datos 
				$db = $this->modelo->crear_database($empresa[0]['Usuario_db'], $empresa[0]['Password_db'],$empresa[0]['Ip_host'],$empresa[0]['Puerto_db'], $empresa[0]['Base_datos']);
			// 2.- crea un usuario para base de datos
				$pass = $this->cod_global->enciptar_clave($empresa[0]['Base_datos']);
			 	$user = $this->modelo->crear_usuario_db($empresa[0]['Usuario_db'], $empresa[0]['Password_db'],$empresa[0]['Ip_host'],$empresa[0]['Puerto_db'],$empresa[0]['Base_datos'],$pass);
			//3.- editamos en empresas el usuario de la base de datos
			 	$datos = array( array('campo'=>'Usuario_db','dato'=>'USER_'.$empresa[0]['Base_datos']),
			 					array('campo'=>'Password_db','dato'=>$pass),
			 					array('campo'=>'Estado','dato'=>'A')
			 				);
			 	$where = array( array('campo'=>'Id_empresa','dato'=>$empresa[0]['Id_empresa']));
			return 	 $this->modelo->editar('EMPRESAS',$datos,$where,1);



		}else
		{
			//terceros

		}


	}

	
}
?> 