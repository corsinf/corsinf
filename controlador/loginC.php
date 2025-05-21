<?php
include('../modelo/loginM.php');
include('../db/codigos_globales.php');
include('../modelo/modulos_paginasM.php');
include('../modelo/tipo_usuarioM.php');
require_once(dirname(__DIR__).'/controlador/ACTIVEDIR/activedirectoryC.php');
include('../modelo/no_concurenteM.php');
include('../lib/phpmailer/enviar_emails.php');

if(isset($_SESSION['INICIO']))
{	
  @session_start();
} 
/**
 * 
 */
$controlador = new loginC();
if(isset($_GET['empresa_seleccionada']))
{
echo json_encode($controlador->empresa_seleccionada($_POST['parametros']));
}
if(isset($_GET['empresa_seleccionada_x_modulo']))
{
	// print_r($_POST['parametros']);die();
echo json_encode($controlador->empresa_seleccionada($_POST['parametros']));
}


if(isset($_GET['empresa_seleccionada_head']))
{
	echo json_encode($controlador->empresa_seleccionada_head($_POST['parametros']));
}
if(isset($_GET['iniciar_empresa']))
{
	echo json_encode($controlador->iniciar_sesion($_POST['parametros']));
}
if(isset($_GET['cambiar_empresa']))
{
	echo json_encode($controlador->iniciar_sesion($_POST['parametros'],1));
}
if(isset($_GET['registrar_licencia']))
{
	echo json_encode($controlador->registrar_licencia($_POST['parametros']));
}
if(isset($_GET['iniciar']))
{
	echo json_encode($controlador->buscar_empresas($_POST['parametros']));
}
if(isset($_GET['mis_empresas']))
{
	echo json_encode($controlador->mis_empresas());
}
if(isset($_GET['cerrar']))
{
echo json_encode($controlador->cerrar_session());
}
if(isset($_GET['restriccion']))
{
	$pagina = '';
	if(isset($_POST['pagina']))
	{
	  $pagina = $_POST['pagina'];
  }
  echo json_encode($controlador->restriccion($pagina));
}
if(isset($_GET['menu_lateral']))
{
  echo json_encode($controlador->menu_lateral());
  //file_put_contents('debug_menu_lateral.log', print_r($_SESSION['INICIO']['MENU_LATERAL'], true), FILE_APPEND);
}
if(isset($_GET['modulos_sistema']))
{
  echo json_encode($controlador->modulos_sistema());
}
if(isset($_GET['validar_licencias']))
{
  echo json_encode($controlador->validar_licencias());
}
if(isset($_GET['modulos_sistema_acceso_rapido']))
{
  echo json_encode($controlador->modulos_sistema_acceso_rapido());
}
if(isset($_GET['modulos_sistema_selected']))
{
	$parametros = $_POST['modulo_sistema'];
  echo json_encode($controlador->modulos_sistema_selected($parametros));
}
if(isset($_GET['reseteo']))
{
	 $parametros = $_POST['parametros'];
  echo json_encode($controlador->resetear($parametros));
}
if(isset($_GET['change_settings']))
{
	 $_SESSION['INICIO']['MODULO_SISTEMA_ANT'] =  $_SESSION['INICIO']['MODULO_SISTEMA']; 
	 $_SESSION['INICIO']['MODULO_SISTEMA'] = '1';
	 $controlador->menu_lateral();

	echo json_encode('1');
}
if(isset($_GET['regresar_modulo']))
{
	 $_SESSION['INICIO']['MODULO_SISTEMA'] =  $_SESSION['INICIO']['MODULO_SISTEMA_ANT']; 
	 // $_SESSION['INICIO']['MODULO_SISTEMA'] = '1';
	 $controlador->menu_lateral();

	echo json_encode($_SESSION['INICIO']['MODULO_SISTEMA']);
}
if(isset($_GET['primer_inicio']))
{
	 $parametros = $_POST['parametros'];
  echo json_encode($controlador->primer_inicio($parametros));
}

if(isset($_GET['validar_directory']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->validar_directory($parametros));
}

if(isset($_GET['primerInicioActive']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->primerInicioActive($parametros));
}
class loginC
{
	private $login;
	private $modulos;
	private $tipo;
	private $email;
	private $globales;
	private $noconcurente;
	private $active;
	private $cod_global;
	function __construct()
	{
		$this->login = new loginM();
		$this->modulos = new modulos_paginasM();
		$this->tipo = new tipo_usuarioM();
		$this->email = new enviar_emails();
		$this->cod_global = new codigos_globales();
		$this->noconcurente = new no_concurenteM();
		$this->active = new activeDirC();
	}

	function buscar_empresas($parametros)
	{
		if(isset($_SESSION['INICIO']))
			{
				 session_destroy();
			}

			 $lista_empresas = array();
			 $parametros['pass'] = $this->cod_global->enciptar_clave($parametros['pass']);
			 $no_concurente = 0;

			 //busca la empresa en donde es usuario normal 
			 // print_r($parametros);die();
			 $datos = $this->login->buscar_empresas($parametros['email'],false,false,1);
			 // if(count($datos)>0) {
			 // 	//valida si es deba  1 siempre va a ser DBA
			 // 	 if($datos[0]['Id_Tipo_usuario']==1)
			 // 	 {
			 // 	 		$datos = $this->login->buscar_empresas($parametros['email'],false,false);
			 // 	 }
			 // }
			 // print_r($datos);die();
			 $active_Valido = 1;
			 foreach ($datos as $key => $value) {
			 		$primera_vez = 0;
					if($value['ip_directory']=='' || $value['puerto_directory']=='' || $value['basedn_directory']=='' || $value['dominio_directory']=='' || $value['usuario_directory']=='' || $value['password_directory']==''){
						$active_Valido = 0;
					}
					if($value['password']=='' && $value['password']==null)
					{
						$primera_vez = 1;
					}

			 		$lista_empresas[] = array('Logo'=>$value['Logo'],'Id_Empresa'=>$value['Id_Empresa'],'Nombre_Comercial'=>$value['Nombre_Comercial'],'ActiveDirectory'=>$active_Valido,'normal'=>1,'no_concurente'=>0,'primera_vez'=>$primera_vez); 
			 }

			 // print_r($lista_empresas);die();
			 //buscamos las empresas en donde el usuario es no concurente
			 	 $datos = array();		
			 	 $no_concurentes = $this->login->empresa_tabla_noconcurente(false,false,1);
			 	 foreach ($no_concurentes as $key => $value) {
			 	 		$primera_vez = 0;
			 	 		$tipo = $value['tipo'];
			 	 	 	$empresa = $this->login->lista_empresa($value['Id_Empresa']);
			 	 	 	$parametros['Campo_Usuario'] = $value['Campo_usuario'];
			 	 	 	$Campo_Pass = $value['Campo_pass'];
			 	 	 	$parametros['tabla'] = $value['Tabla'];
			 	 	 	// print_r($empresa);die();
			 	 	 	if(count($empresa)>0)
			 	 	 	{
					 	 	 	$busqueda_tercero = $this->login->buscar_db_terceros($empresa[0]['Base_datos'],$empresa[0]['Usuario_db'],$empresa[0]['Password_db'],$empresa[0]['Ip_host'],$empresa[0]['Puerto_db'],$parametros);
					 	 		if(count($busqueda_tercero)>0)
					 	 	 	{
					 	 	 		if($busqueda_tercero[0][$Campo_Pass]=='' || $busqueda_tercero[0][$Campo_Pass]==null)
					 	 	 		{
					 	 	 			$primera_vez = 1;
					 	 	 		}
					 	 	 		$existe_en_lista = 0;
					 	 	 		foreach ($lista_empresas as $key2 => $value2) {
					 	 	 				if($value2['Id_Empresa']==$empresa[0]['Id_empresa'])
					 	 	 				{
					 	 	 					 $existe_en_lista = 1;
					 	 	 					 break;
					 	 	 				}
					 	 	 		}

					 	 	 		if($existe_en_lista==0)
					 	 	 		{
					 	 	 			$lista_empresas[] = array('Logo'=>$empresa[0]['Logo'],'Id_Empresa'=>$empresa[0]['Id_Empresa'],'Nombre_Comercial'=>$empresa[0]['Nombre_Comercial'],'ActiveDirectory'=>$active_Valido,'normal'=>0,'no_concurente'=>1,'PERFIL'=>$tipo,'Cod_Perfil'=>$value['tipo_perfil'],'primera_vez'=>$primera_vez); 
					 	 	 				$existe_en_lista = 0;
					 	 	 		}
					 	 	 	}
				 	 	 }
			 	 }

			 	 // print_r($lista_empresas);die();
			 	 // $datos = $this->login->buscar_empresas_no_concurentes($parametros['email'],$parametros['pass']);

			 	 if(count($lista_empresas)==0)
			 	 {
			 			return array('lista'=>'-3','no_concurente'=>$no_concurente);			 
			 	 }
			 

			 	 $empresas = '';

			 	 // print_r($lista_empresas);die();
			 foreach ($lista_empresas as $key => $value) {
			 	$foto = 'img/de_sistema/apudata.jpeg';
			 	if(file_exists($value['Logo'])){$foto = str_replace('../','',$value['Logo']); }

			 	// $empresas.= '<li class="list-group-item d-flex align-items-center radius-10 mb-2 shadow-sm" onclick="empresa_selecconada('.$value['Id_Empresa'].','.$value['ActiveDirectory'].','.$value['primera_vez'].',\''.$parametros['email'].'\','.$value['primera_vez'].')">
				// 							<div class="d-flex align-items-center">
				// 								<div class="font-20"><img style="width:70px; height:50px" src="'.$foto.'" />
				// 								</div>
				// 								<div class="flex-grow-1 ms-2">
				// 									<h6 class="mb-0">'.$value['Nombre_Comercial'].'</h6>
				// 										<!--	<div class="d-flex align-items-center text-primary">	<i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>
														
				// 									</div> --!>


				// 								</div>
				// 							</div>
				// 							<div class="ms-auto">
				// 							</div>
				// 						</li>';

				$empresas.='<div class="row border mx-0 mb-2 py-2 radius-10 cursor-pointer" onclick="empresa_selecconada('.$value['Id_Empresa'].','.$value['ActiveDirectory'].','.$value['primera_vez'].',\''.$parametros['email'].'\','.$value['primera_vez'].')">
									<div class="col-sm-9">
										<div class="d-flex align-items-center">
											<div class="product-img">
												<img style="width:70px; height:50px" src="'.$foto.'" />
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-1">'.$value['Nombre_Comercial'].'</h6>
											</div>
										</div>
									</div>
									<div class="col-sm-3">
									<br>
										<div class="badge rounded-pill bg-success w-100">Ingresar</div>
									</div>									
								</div>';
			 }
			 return array('lista'=>$empresas,'no_concurente'=>$no_concurente);
	}



	function buscar_empresas_logeado($parametros)
	{
		
			 $lista_empresas = array();
			 $parametros['pass'] = $this->cod_global->enciptar_clave($parametros['pass']);
			 $no_concurente = 0;

			 //busca la empresa en donde es usuario normal 
			 // print_r($parametros);die();
			 $datos = $this->login->buscar_empresas($parametros['email'],false,false,1);
			 // print_r($datos);die();
			 $active_Valido = 1;
			 foreach ($datos as $key => $value) {
			 		$primera_vez = 0;
					if($value['ip_directory']=='' || $value['puerto_directory']=='' || $value['basedn_directory']=='' || $value['dominio_directory']=='' || $value['usuario_directory']=='' || $value['password_directory']==''){
						$active_Valido = 0;
					}
					if($value['password']=='' && $value['password']==null)
					{
						$primera_vez = 1;
					}

			 		$lista_empresas[] = array('Logo'=>$value['Logo'],'Id_Empresa'=>$value['Id_Empresa'],'Nombre_Comercial'=>$value['Nombre_Comercial'],'ActiveDirectory'=>$active_Valido,'normal'=>1,'no_concurente'=>0,'primera_vez'=>$primera_vez); 
			 }

			 // print_r($lista_empresas);die();
			 //buscamos las empresas en donde el usuario es no concurente
			 	 $datos = array();		
			 	 $no_concurentes = $this->login->empresa_tabla_noconcurente(false,false,1);
			 	 foreach ($no_concurentes as $key => $value) {
			 	 		$primera_vez = 0;
			 	 		$tipo = $value['tipo'];
			 	 	 	$empresa = $this->login->lista_empresa($value['Id_Empresa']);
			 	 	 	$parametros['Campo_Usuario'] = $value['Campo_usuario'];
			 	 	 	$Campo_Pass = $value['Campo_pass'];
			 	 	 	$parametros['tabla'] = $value['Tabla'];
			 	 	 	// print_r($empresa);die();
			 	 	 	if(count($empresa)>0)
			 	 	 	{
					 	 	 	$busqueda_tercero = $this->login->buscar_db_terceros($empresa[0]['Base_datos'],$empresa[0]['Usuario_db'],$empresa[0]['Password_db'],$empresa[0]['Ip_host'],$empresa[0]['Puerto_db'],$parametros);
					 	 		if(count($busqueda_tercero)>0)
					 	 	 	{
					 	 	 		if($busqueda_tercero[0][$Campo_Pass]=='' || $busqueda_tercero[0][$Campo_Pass]==null)
					 	 	 		{
					 	 	 			$primera_vez = 1;
					 	 	 		}
					 	 	 		$existe_en_lista = 0;
					 	 	 		foreach ($lista_empresas as $key2 => $value2) {
					 	 	 				if($value2['Id_Empresa']==$empresa[0]['Id_empresa'])
					 	 	 				{
					 	 	 					 $existe_en_lista = 1;
					 	 	 					 break;
					 	 	 				}
					 	 	 		}

					 	 	 		if($existe_en_lista==0)
					 	 	 		{
					 	 	 			$lista_empresas[] = array('Logo'=>$empresa[0]['Logo'],'Id_Empresa'=>$empresa[0]['Id_Empresa'],'Nombre_Comercial'=>$empresa[0]['Nombre_Comercial'],'ActiveDirectory'=>$active_Valido,'normal'=>0,'no_concurente'=>1,'PERFIL'=>$tipo,'Cod_Perfil'=>$value['tipo_perfil'],'primera_vez'=>$primera_vez); 
					 	 	 				$existe_en_lista = 0;
					 	 	 		}
					 	 	 	}
				 	 	 }
			 	 }

			 	 // print_r($lista_empresas);die();
			 	 // $datos = $this->login->buscar_empresas_no_concurentes($parametros['email'],$parametros['pass']);

			 	 if(count($lista_empresas)==0)
			 	 {
			 			return array('lista'=>'-3','no_concurente'=>$no_concurente);			 
			 	 }
			 

			 	 $empresas = '';

			 	 // print_r($lista_empresas);die();
			 foreach ($lista_empresas as $key => $value) {
			 	$foto = 'img/de_sistema/apudata.jpeg';
			 	if(file_exists($value['Logo'])){$foto = str_replace('../../','',$value['Logo']); }
				$empresas.='<div class="row border mx-0 mb-2 py-2 radius-10 cursor-pointer" onclick="empresa_selecconada('.$value['Id_Empresa'].','.$value['ActiveDirectory'].','.$value['primera_vez'].',\''.$parametros['email'].'\','.$value['primera_vez'].')">
									<div class="col-sm-9">
										<div class="d-flex align-items-center">
											<div class="product-img">
												<img style="width:70px; height:50px" src="'.$foto.'" />
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-1">'.$value['Nombre_Comercial'].'</h6>
											</div>
										</div>
									</div>
									<div class="col-sm-3">
									<br>';
										if($_SESSION['INICIO']['ID_EMPRESA']==$value['Id_Empresa'])
											{
												$empresas.= '<div class="badge rounded-pill bg-warning text-dark w-100">Empresa Actual</div>';
											}
									$empresas.='</div>									
								</div>';
			 }
			 return array('lista'=>$empresas,'no_concurente'=>$no_concurente,'numEmpresas'=>count($lista_empresas));
	
	}


	function mis_empresas()
	{
			$usuario = $this->login->datos_login(false,false,$_SESSION['INICIO']['ID_USUARIO']);

			 $no_concurente = 0;
			if(count($usuario)>0)
			{
				// $email = $usuario[0]['email'];
				$parametros['email'] = $usuario[0]['email'];
				$pass = $usuario[0]['password'];
				$parametros['pass'] =  $usuario[0]['password'];
				$mis_empresas = $this->buscar_empresas_logeado($parametros);
				return $mis_empresas;
			}else
			{
				 return -1;
			}			
	}




	function empresa_seleccionada($parametros)
	{
		$licencias = $this->login->empresa_licencias($parametros['empresa']);
		if(isset($parametros['modulo_sistema']) && $parametros['modulo_sistema']==1)
		{
			$licencias = $this->login->empresa_licencias_activas($parametros['empresa']);
		}
		// print_r($licencias);die();
		// print_r($_SESSION['INICIO']);die();
		if(count($licencias)==0)
		{
			// onclick="empresa_selecconada('.$value['Id_Empresa'].')
			$modulos = $this->login->modulos_empresa();
			$empresas = '';
			foreach ($modulos as $key => $value) {

				$empresas.= '<li class="list-group-item d-flex align-items-center radius-10 mb-2 shadow-sm">
											<div class="d-flex align-items-center">
												<div class="font-20">'.$value['icono'].'
												</div>
												<div class="flex-grow-1 ms-2">
													<h6 class="mb-0">'.$value['nombre_modulo'].'</h6>
													<input type="text" name="licencia_'.$value['id_modulos'].'" id="licencia_'.$value['id_modulos'].'" class="form-control" />
												</div>
											</div>
											<div class="ms-auto">
											<button class="btn btn-sm btn-primary" onclick="registrar_licencia(\''.$parametros['empresa'].'\',\''.$value['id_modulos'].'\')">Registrar</button>
											</div>
										</li>';
			}
			
			return array('respuesta'=>2,'modulos'=>$empresas);
		}else
		{

			// buscamos los roles
				$roles =  $this->login->roles_x_empresa($parametros['empresa'],$parametros['email']);
				// print_r($roles);die();
				$no_concurentes = $this->login->empresa_tabla_noconcurente($parametros['empresa'],false,1);
			 	 foreach ($no_concurentes as $key => $value) {
			 	 		$primera_vez = 0;
			 	 		$tipo = $value['tipo'];
			 	 	 	$empresa = $this->login->lista_empresa($value['Id_Empresa']);
			 	 	 	$parametros['Campo_Usuario'] = $value['Campo_usuario'];
			 	 	 	$Campo_Pass = $value['Campo_pass'];
			 	 	 	$parametros['tabla'] = $value['Tabla'];
			 	 	 	// print_r($empresa);die();
			 	 	 	if(count($empresa)>0)
			 	 	 	{
					 	 	 	$busqueda_tercero = $this->login->buscar_db_terceros($empresa[0]['Base_datos'],$empresa[0]['Usuario_db'],$empresa[0]['Password_db'],$empresa[0]['Ip_host'],$empresa[0]['Puerto_db'],$parametros);

					 	 	 	if(count($busqueda_tercero)>0)
					 	 	 	{
					 	 	 		$roles[] = array('DESCRIPCION'=>$value['tipo'],'ID_TIPO'=>$value['tipo_perfil'],'normal'=>0);
					 	 	 	}
				 	 	 }
			 	 }


			//actualizamos
			$empresa = $this->login->lista_empresa($parametros['empresa']);
			// print_r($empresa);die();
			if($empresa[0]['Ip_host']==IP_MASTER)
			{
				// print_r($licencias);print_r($empresa);die();
				$tablas_iguales = $this->cod_global->tablas_por_licencias($licencias,$empresa);				
		 		$res = $this->cod_global->generar_primera_vez($empresa[0]['Base_datos'],$parametros['empresa']);
		 		if($tablas_iguales==-1)
		 		foreach ($licencias as $key => $value) {
		 		// print_r($licencias);die();
		 				$r = $this->cod_global->Copiar_estructura($value['Id_Modulo'],$empresa[0]['Base_datos']);
		 				// print_r($r);die();
		 		}
		 	}else{


				$tablas_iguales = $this->cod_global->tablas_por_licencias($licencias,$empresa,1);
		 		$res = $this->cod_global->generar_primera_vez_terceros($empresa,$parametros['empresa']);
		 		if($tablas_iguales==-1){
			 		foreach ($licencias as $key => $value) {
			 				$this->cod_global->Copiar_estructura($value['Id_Modulo'],$empresa[0]['Base_datos'],1,$empresa);
			 		}
			 	}
		 		// print_r($empresa);die();
		 	}
		 	$rol = '';
		 	$noConcu = 0;		 	
		 	$num_roles = count($roles);
		 	if(count($roles)>1)
		 	{
		 		// print_r($roles);die();
		 		foreach ($roles as $key => $value) {
		 			$normal = 1;
		 			if(isset($value['normal']))
		 			{
		 				$normal = 0;
		 			} 


								$rol.='<div class="row border mx-0 mb-2 py-2 radius-10 cursor-pointer" onclick="seleccionar_perfil(\''.$value['ID_TIPO'].'\',\''.$normal.'\')">
									<div class="col-sm-9">
										<div class="d-flex align-items-center">
											<div class="product-img widgets-icons bg-light-info text-info ms-auto">
													<i class="bx bxs-user"></i>
									
											</div>
											<div class="flex-grow-1 ms-2">
												<h6 class="mb-1"> <i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>'.$value['DESCRIPCION'].'</h6>
											</div>
										</div>
									</div>
									<div class="col-sm-3">
									<br>
										<div class="badge rounded-pill bg-success w-100">Ingresar</div>
									</div>									
								</div>';

		 		}
		 	}elseif(count($roles)==1)
		 	{
		 		// print_r($roles);die();
		 		$num_roles = 1;
		 		$noConcu = 1;	
		 		$rol = $roles[0]['ID_TIPO'];	
		 		if(isset($roles[0]['normal']))
		 			{
		 					$noConcu = 0;		
		 			} 
		 	}

		 	// print_r($roles);
		 	// print_r($roles_no);
		 	// die();


		 	// $num_roles = count($roles)+count($roles_no);
		 	// if(count($roles)==1 && count($roles_no)==0)
		 	// {
		 	// 	$rol = $roles[0]['ID_TIPO'];
		 	// 	$noConcu = 1;		
		 	// } else if(count($roles)==0 && count($roles_no)==1)
		 	// {
		 	// 	$rol = $roles_no[0]['ID_TIPO'];
		 	// 	$noConcu = 0;		
		 	// }

		 	// print_r(array('respuesta'=>$res,'num_roles'=>$num_roles,'roles'=>$rol,'normal'=>$noConcu));die();


			return array('respuesta'=>$res,'num_roles'=>$num_roles,'roles'=>$rol,'normal'=>$noConcu);
		}
	}


	function empresa_seleccionada_head($parametros)
	{
			$usuario = $this->login->datos_login(false,false,$_SESSION['INICIO']['ID_USUARIO']);
// print_r($usuario);die();
			 $no_concurente = 0;
			if(count($usuario)>0)
			{
				// $email = $usuario[0]['email'];
				$parametros['email'] = $usuario[0]['email'];
				$pass = $usuario[0]['password'];
				$parametros['pass'] = $this->cod_global->desenciptar_clave($usuario[0]['password']);
				// print_r($parametros);
				$datos = $this->empresa_seleccionada($parametros);
				// print_r($datos);die();
				return $datos;
			}	
	}


	function registrar_licencia($parametros)
	{
		// print_r($parametros);die();
		$registrado = $this->login->empresa_licencias_regitrado_x_master($parametros['empresa'],$parametros['licencia'],$parametros['modulo']);
		$empresa = $this->login->lista_empresa($parametros['empresa'],1);
		$master = false;
		if(isset($parametros['modulos_sistema']) && $parametros['modulos_sistema']==1)
		{
			$master = true;
		}

		// print_r($registrado);die();
		if(count($registrado)>0)
		{
			$datos[0]['campo'] = 'registrado';
			$datos[0]['dato'] = 1;

			$where[0]['campo'] = 'Id_licencias';			
			$where[0]['dato'] = $registrado[0]['Id_licencias'];

			// print_r($where);
			// print_r($datos);die();

			 $this->login->update('LICENCIAS',$datos,$where,$master);

			$base_des = $empresa[0]['Base_datos'];
			if(IP_MASTER==$empresa[0]['Ip_host'])
			{
				$this->cod_global->generar_primera_vez($base_des,$parametros['empresa']);
				$this->cod_global->Copiar_estructura($parametros['modulo'],$base_des);
				return 1;
			}else{
				$this->cod_global->generar_primera_vez_terceros($empresa,$parametros['empresa']);
				$this->cod_global->Copiar_estructura($parametros['modulo'],$base_des,1,$empresa);
				return 1;
				// print_r($empresa);die();
			}
		}else
		{
			 return -1;
		}
	}

	function validar_ingreso_usuario_valido($parametros)
	{

		// print_r($parametros);die();
		$datos = array();
		$empresa = $this->login->lista_empresa($parametros['empresa']);
		// print_r($empresa);die();
		$parametros['activeDir'] = 0; // se setea este valor por que ya no se v aa validar por activedirectory
		if($parametros['activeDir']==1)
		{
				$respuesta = $this->active->AutentificarUserActiveDir($parametros['email'], $parametros['pass'],$empresa);
				// print_r($respuesta);die();
				if($respuesta!='1')
				{
					return -4;
				}

				if($parametros['primera_vez']==1)
				{					
					// print_r($parametros);die();
						$respuesta = $this->save_update_passActiveUsu($parametros);
						$datos = $this->login->datos_login($parametros['email'],$this->cod_global->enciptar_clave($parametros['pass']),false,$parametros['tipo']);
				}else
				{
					$datos = $this->login->datos_login_pass_requiered($parametros['email'],$this->cod_global->enciptar_clave($parametros['pass']),false,$parametros['tipo']);
					if(count($datos)==0)
					{
						 return -1;
					}
				}	
		}else
		{
			$cambiar = false;
			if(isset($parametros['cambiarEmpresa']) && $parametros['cambiarEmpresa']==1){$cambiar=1;}
			$datos = $this->login->datos_login_pass_requiered($parametros['email'],$this->cod_global->enciptar_clave($parametros['pass']),false,$parametros['tipo'],$cambiar);
			// print_r($datos);die();
			if(count($datos)==0)
			{
				 return -1;
			}	
		}
			// if($cambiar){
			// 	$datos = $this->login->datos_login($parametros['email'],$parametros['pass']);
			// }

		// print_r($datos);die();
			if(count($datos)>0)
			{					
	 			// print_r($parametros);die();
				// print_r($datos);die();
				// session_start();
				$_SESSION['INICIO']['ULTIMO_ACCESO'] = time();
				$_SESSION["INICIO"]['VER'] = $datos[0]['Ver'];
				$_SESSION["INICIO"]['EDITAR'] = $datos[0]['editar'];
				$_SESSION["INICIO"]['ELIMINAR'] = $datos[0]['eliminar'];
				$_SESSION["INICIO"]['DBA'] = $datos[0]['dba'];
				$_SESSION["INICIO"]['USUARIO'] = $datos[0]['nombres'].' '.$datos[0]['apellidos'];
				$_SESSION["INICIO"]['ID_USUARIO'] = $datos[0]['id'];
				$_SESSION["INICIO"]['EMAIL'] = $datos[0]['email'];
				$_SESSION["INICIO"]['TIPO'] = $datos[0]['tipo'];
				$_SESSION["INICIO"]['PERFIL'] = $parametros['tipo'];				
				$_SESSION["INICIO"]['FOTO'] = $datos[0]['foto'];
				$_SESSION["INICIO"]['NO_CONCURENTE'] = '';
				$_SESSION["INICIO"]['NO_CONCURENTE_NOM'] ='';
				$_SESSION["INICIO"]['NO_CONCURENTE_TABLA_ID'] ='';
				$_SESSION["INICIO"]['NO_CONCURENTE_TABLA'] ='';
				$_SESSION["INICIO"]['MODULO_SISTEMA_ANT'] ='';
				$_SESSION["INICIO"]['LISTA_ART'] =1;

					return 1;			
			}else
			{
				 return -3;
			}
	}

	function validar_ingreso_noconcurente_valido($parametros)
	{

				// print_r($parametros);die();
				$parametros['pass'] = $this->cod_global->enciptar_clave($parametros['pass']);
				$datos = $this->login->datos_login(false,false,2);
				// busca en tabla no concurrentes 
				 $no_concurentes = $this->login->empresa_tabla_noconcurente($parametros['empresa'],false,false,$parametros['tipo']);
			 	 $empresa = $this->login->lista_empresa($parametros['empresa']);

			 	 // print_r($no_concurentes);die();
			 	 $tabla = '';
			 	 $busqueda_tercero = array();
			 	 foreach ($no_concurentes as $key => $value) {
			 	 	// print_r($value);die();
			 	 	 	$parametros['Campo_Usuario'] = $value['Campo_usuario'];
			 	 	 	$parametros['Campo_Pass'] = $value['Campo_pass'];
			 	 	 	$parametros['tabla'] = $value['Tabla'];
			 	 	 	$parametros['perfil'] = $value['tipo_perfil'];
			 	 	 	$parametros['tipo'] = $value['tipo'];
			 	 	 	$parametros['foto'] = $value['campo_img'];
			 	 	 	$tabla = $value['Tabla'];

			 	 	 	// print_r($parametros);die();
			 	 	 	$busqueda_tercero = $this->login->buscar_db_terceros($empresa[0]['Base_datos'],$empresa[0]['Usuario_db'],$empresa[0]['Password_db'],$empresa[0]['Ip_host'],$empresa[0]['Puerto_db'],$parametros);
			 	 	 	if(count($busqueda_tercero)>0)
			 	 	 	{
			 	 	 		break;
			 	 	 	}
			 	 }
			 	 // print_r($parametros);die();

				 	$id = $this->login->id_tabla_terceros($tabla,$empresa);
				 	// print_r($tabla);
				 	// print_r($id);
				 	// print_r($busqueda_tercero);die();

				 	if(count($busqueda_tercero)>0)
				 	{
					 	$datos_usu = $this->login->datos_no_concurente($empresa,$tabla,$id[0]['ID'],$busqueda_tercero[0][$id[0]['ID']]);

				 	 // print_r($busqueda_tercero);
					 	// print_r($datos_usu[0][$parametros['foto']]);die();
					 	// print_r($datos_usu);die();
					 	$_SESSION['INICIO']['ULTIMO_ACCESO'] = time();
						$_SESSION["INICIO"]['VER'] = $datos[0]['Ver'];
						$_SESSION["INICIO"]['EDITAR'] = $datos[0]['editar'];
						$_SESSION["INICIO"]['ELIMINAR'] = $datos[0]['eliminar'];
						$_SESSION["INICIO"]['DBA'] = $datos[0]['dba'];
						$_SESSION["INICIO"]['USUARIO'] = $datos[0]['nombres'].' '.$datos[0]['apellidos'];
						$_SESSION["INICIO"]['ID_USUARIO'] = $datos[0]['id'];
						$_SESSION["INICIO"]['EMAIL'] = $datos[0]['email'];
						$_SESSION["INICIO"]['TIPO'] = $parametros['tipo'];
						$_SESSION["INICIO"]['PERFIL'] = $parametros['perfil'];
						$_SESSION["INICIO"]['FOTO'] = '';
						if($parametros['foto']!='' && $parametros['foto']!=null && file_exists($parametros['foto']))
						{		
							$_SESSION["INICIO"]['FOTO'] = $datos_usu[0][$parametros['foto']];
						}
						$_SESSION["INICIO"]['NO_CONCURENTE'] = $busqueda_tercero[0][$id[0]['ID']] ;
						$_SESSION["INICIO"]['NO_CONCURENTE_NOM'] =$parametros['email'];
						$_SESSION["INICIO"]['NO_CONCURENTE_TABLA_ID'] =$id[0]['ID'];
						$_SESSION["INICIO"]['NO_CONCURENTE_TABLA'] =$tabla;
						$_SESSION["INICIO"]['NO_CONCURENTE_CAMPO_IMG'] =$parametros['foto'];
						$_SESSION["INICIO"]['MODULO_SISTEMA_ANT'] ='';
						$_SESSION["INICIO"]['LISTA_ART'] =1;


						// print_r($_SESSION['INICIO']);die();

						return 1;
					}else
					{
						return -1;
					}

	}

	function iniciar_sesion($parametros,$cambiar=false)
	{	

		if($cambiar)
		{
			$usuario = $this->login->datos_login(false,false,$_SESSION['INICIO']['ID_USUARIO']);
			$no_concurente = 0;
			if(count($usuario)>0)
			{
				// $email = $usuario[0]['email'];
				$parametros['email'] = $usuario[0]['email'];
				$pass = $usuario[0]['password'];
				$parametros['pass'] = $this->cod_global->desenciptar_clave($usuario[0]['password']);
				$parametros['cambiarEmpresa'] = 1; 
			}

		}


		// print_r($parametros);die();

		$empresa = $this->login->lista_empresa($parametros['empresa']);
		// print_r($empresa);die();
		$usuario_validado ='';
		switch ($parametros['normal']) {
			case '1':
					$usuario_validado = $this->validar_ingreso_usuario_valido($parametros);
				break;
			case '0':			
					$usuario_validado = $this->validar_ingreso_noconcurente_valido($parametros);
				break;	
		}
		$msj = '';
			
	switch ($usuario_validado) {
		case '-1':
			$msj = 'Contraseña incorrecta';
			break;
		case '-1':
			$msj = 'Contraseña incorrecta';
			break;
		case '-4':
			$msj = 'Usuario de active directory no autentificado';
			break;
		
		default:
			// code...
			break;
	}

		// print_r($usuario_validado);die();

		if($usuario_validado == -1)
		{
			return -1;
		}

	if(count($empresa)>0)
		{
				$_SESSION["INICIO"]['ID_EMPRESA'] = $empresa[0]['Id_empresa'];
				$_SESSION["INICIO"]['ASIGNAR_SEGUROS'] = $empresa[0]['Tabla_seguros'];
				$_SESSION["INICIO"]['RAZON_SOCIAL'] = $empresa[0]['Razon_Social'];
				$_SESSION["INICIO"]['IP_HOST'] = $empresa[0]['Ip_host'];
				$_SESSION["INICIO"]['BASEDATO'] = $empresa[0]['Base_datos'];
				$_SESSION["INICIO"]['USUARIO_DB'] = $empresa[0]['Usuario_db'];
				$_SESSION["INICIO"]['PASSWORD_DB'] = $empresa[0]['Password_db'];
				$_SESSION["INICIO"]['PUERTO_DB'] = $empresa[0]['Puerto_db'];
				$_SESSION["INICIO"]['TIPO_BASE'] = $empresa[0]['Tipo_base'];
				$_SESSION["INICIO"]['LOGO'] = $empresa[0]['Logo'];
				$_SESSION["INICIO"]['IP_API_HIKVISION'] = $empresa[0]['ip_api_hikvision'];
				$_SESSION["INICIO"]['KEY_API_HIKVISION'] = $empresa[0]['key_api_hikvision'];
				$_SESSION["INICIO"]['USER_API_HIKVISION'] = $empresa[0]['user_api_hikvision'];
				$_SESSION["INICIO"]['TCP_PUERTO_HIKVISION'] = $empresa[0]['tcp_puerto_hikvision'];
				$_SESSION["INICIO"]['PUERTO_API_HIKVISION'] = $empresa[0]['puerto_api_hikvision'];
				$_SESSION["INICIO"]['ACERCA_DE'] = $empresa[0]['acerca_de'];
				$_SESSION["INICIO"]['TITULO_PESTANIA'] = $empresa[0]['titulo_pestania'];
				$_SESSION["INICIO"]['IDUKAY_URL'] = $empresa[0]['url_api_idukay'];
				$_SESSION["INICIO"]['IDUKAY_TOKEN'] = $empresa[0]['token_idukay'];
				$_SESSION["INICIO"]['IDUKAY_ANIO_LEC'] = $empresa[0]['anio_lectivo_idukay'];
				return 1;
		}




		/*


	 	if($cambiar)
	 	{
	 		$usuario = $this->login->datos_usuario($_SESSION['INICIO']['ID_USUARIO']);
			if(count($usuario)>0)
			{
		 		$parametros['email'] =  $usuario[0]['email'];
		 		$parametros['pass'] =  $usuario[0]['password'];
		 		$parametros['no_concurente'] = 0;
		 		$validar_permisos = $this->acceso_en_terceros($parametros);
		 		if(count($validar_permisos)==0)
		 		{
		 			return -3;
		 		}
		 	}
	 	}

	 	*/
	 	// print_r('sss');die();

			// print_r($empresa);die();
			
	 	// print_r($parametros);die();

	}


	function iniciar_sesion2($parametros,$cambiar=false)
	 {		
	 	// print_r($parametros);die();

		$empresa = $this->login->lista_empresa($parametros['id']);
		$active_Valido = 1;
		if($empresa[0]['ip_directory']=='' || $empresa[0]['puerto_directory']=='' || $empresa[0]['basedn_directory']=='' || $empresa[0]['dominio_directory']=='' || $empresa[0]['usuario_directory']=='' || $empresa[0]['password_directory']==''){
			$active_Valido = 0;
		}

		

	 	if($cambiar)
	 	{
	 		$usuario = $this->login->datos_usuario($_SESSION['INICIO']['ID_USUARIO']);
			if(count($usuario)>0)
			{
		 		$parametros['email'] =  $usuario[0]['email'];
		 		$parametros['pass'] =  $usuario[0]['password'];
		 		$parametros['no_concurente'] = 0;
		 		$validar_permisos = $this->acceso_en_terceros($parametros);
		 		if(count($validar_permisos)==0)
		 		{
		 			return -3;
		 		}
		 	}
	 	}
	 	// print_r('sss');die();

			// print_r($empresa);die();
			if(count($empresa)>0)
			{
					$_SESSION["INICIO"]['ID_EMPRESA'] = $empresa[0]['Id_empresa'];
					$_SESSION["INICIO"]['ASIGNAR_SEGUROS'] = $empresa[0]['Tabla_seguros'];
					$_SESSION["INICIO"]['RAZON_SOCIAL'] = $empresa[0]['Razon_Social'];
					$_SESSION["INICIO"]['IP_HOST'] = $empresa[0]['Ip_host'];
					$_SESSION["INICIO"]['BASEDATO'] = $empresa[0]['Base_datos'];
					$_SESSION["INICIO"]['USUARIO_DB'] = $empresa[0]['Usuario_db'];
					$_SESSION["INICIO"]['PASSWORD_DB'] = $empresa[0]['Password_db'];
					$_SESSION["INICIO"]['PUERTO_DB'] = $empresa[0]['Puerto_db'];
					$_SESSION["INICIO"]['TIPO_BASE'] = $empresa[0]['Tipo_base'];
					$_SESSION["INICIO"]['LOGO'] = $empresa[0]['Logo'];
					$_SESSION["INICIO"]['IP_API_HIKVISION'] = $empresa[0]['ip_api_hikvision'];
					$_SESSION["INICIO"]['KEY_API_HIKVISION'] = $empresa[0]['key_api_hikvision'];
					$_SESSION["INICIO"]['USER_API_HIKVISION'] = $empresa[0]['user_api_hikvision'];
					$_SESSION["INICIO"]['TCP_PUERTO_HIKVISION'] = $empresa[0]['tcp_puerto_hikvision'];
					$_SESSION["INICIO"]['PUERTO_API_HIKVISION'] = $empresa[0]['puerto_api_hikvision'];
					$_SESSION["INICIO"]['ACERCA_DE'] = $empresa[0]['acerca_de'];
					$_SESSION["INICIO"]['TITULO_PESTANIA'] = $empresa[0]['titulo_pestania'];
					$_SESSION["INICIO"]['IDUKAY_URL'] = $empresa[0]['url_api_idukay'];
					$_SESSION["INICIO"]['IDUKAY_TOKEN'] = $empresa[0]['token_idukay'];
					$_SESSION["INICIO"]['IDUKAY_ANIO_LEC'] = $empresa[0]['anio_lectivo_idukay'];
			}

	 	// print_r($parametros);die();
			if($parametros['no_concurente']==0)
			{

				$datos = $this->login->datos_login($parametros['email']);
				if($active_Valido && $parametros['no_concurente']==1 && $datos[0]['tipo']!='DBA')
				{
					$respuesta = $this->active->AutentificarUserActiveDir($parametros['email'], $parametros['pass'],$empresa);
					if($respuesta!='1')
					{
						return -4;
					}			
				}


	 			// print_r($parametros);die();
				$datos = $this->login->datos_login($parametros['email'],$this->cod_global->enciptar_clave($parametros['pass']));
				// print_r($datos);die();
				if($cambiar){
					$datos = $this->login->datos_login($parametros['email'],$parametros['pass']);
				}
				if(count($datos)>0)
				{
					
	 	// print_r($parametros);die();
				// print_r($datos);die();
				// session_start();
				$_SESSION['INICIO']['ULTIMO_ACCESO'] = time();
				$_SESSION["INICIO"]['VER'] = $datos[0]['Ver'];
				$_SESSION["INICIO"]['EDITAR'] = $datos[0]['editar'];
				$_SESSION["INICIO"]['ELIMINAR'] = $datos[0]['eliminar'];
				$_SESSION["INICIO"]['DBA'] = $datos[0]['dba'];
				$_SESSION["INICIO"]['USUARIO'] = $datos[0]['nombres'].' '.$datos[0]['apellidos'];
				$_SESSION["INICIO"]['ID_USUARIO'] = $datos[0]['id'];
				$_SESSION["INICIO"]['EMAIL'] = $datos[0]['email'];
				$_SESSION["INICIO"]['TIPO'] = $datos[0]['tipo'];
				$_SESSION["INICIO"]['PERFIL'] = $datos[0]['perfil'];				
				$_SESSION["INICIO"]['FOTO'] = $datos[0]['foto'];
				$_SESSION["INICIO"]['NO_CONCURENTE'] = '';
				$_SESSION["INICIO"]['NO_CONCURENTE_NOM'] ='';
				$_SESSION["INICIO"]['NO_CONCURENTE_TABLA_ID'] ='';
				$_SESSION["INICIO"]['NO_CONCURENTE_TABLA'] ='';
				$_SESSION["INICIO"]['MODULO_SISTEMA_ANT'] ='';
				$_SESSION["INICIO"]['LISTA_ART'] =1;

				return 1;			
			}else
			{
				 return -3;
			}

			}else
			{

	 	// print_r('sss');die();
				$parametros['pass'] = $this->cod_global->enciptar_clave($parametros['pass']);
				$datos = $this->login->datos_login(false,false,2);
				// busca en tabla no concurrentes 
				 $no_concurentes = $this->login->empresa_tabla_noconcurente($parametros['id']);
			 	 $empresa = $this->login->lista_empresa($parametros['id']);

			 	 // print_r($no_concurentes);die();
			 	 $tabla = '';
			 	 $busqueda_tercero = array();
			 	 foreach ($no_concurentes as $key => $value) {
			 	 	// print_r($value);die();
			 	 	 	$parametros['Campo_Usuario'] = $value['Campo_usuario'];
			 	 	 	$parametros['Campo_Pass'] = $value['Campo_pass'];
			 	 	 	$parametros['tabla'] = $value['Tabla'];
			 	 	 	$parametros['perfil'] = $value['tipo_perfil'];
			 	 	 	$parametros['tipo'] = $value['tipo'];
			 	 	 	$parametros['foto'] = $value['campo_img'];
			 	 	 	$tabla = $value['Tabla'];
			 	 	 	$busqueda_tercero = $this->login->buscar_db_terceros($empresa[0]['Base_datos'],$empresa[0]['Usuario_db'],$empresa[0]['Password_db'],$empresa[0]['Ip_host'],$empresa[0]['Puerto_db'],$parametros);
			 	 	 	if(count($busqueda_tercero)>0)
			 	 	 	{
			 	 	 		break;
			 	 	 	}
			 	 }
			 	 // print_r($parametros);die();

				 	$id = $this->noconcurente->id_tabla_no_concurentes($tabla);
				 	// print_r($tabla);
				 	// print_r($id);
				 	// print_r($busqueda_tercero);die();

				 	$datos_usu = $this->login->datos_no_concurente($tabla,$id[0]['ID'],$busqueda_tercero[0][$id[0]['ID']]);

			 	 // print_r($busqueda_tercero);
				 	// print_r($datos_usu[0][$parametros['foto']]);die();
				 	// print_r($datos_usu);die();
				 	$_SESSION['INICIO']['ULTIMO_ACCESO'] = time();
					$_SESSION["INICIO"]['VER'] = $datos[0]['Ver'];
					$_SESSION["INICIO"]['EDITAR'] = $datos[0]['editar'];
					$_SESSION["INICIO"]['ELIMINAR'] = $datos[0]['eliminar'];
					$_SESSION["INICIO"]['DBA'] = $datos[0]['dba'];
					$_SESSION["INICIO"]['USUARIO'] = $datos[0]['nombres'].' '.$datos[0]['apellidos'];
					$_SESSION["INICIO"]['ID_USUARIO'] = $datos[0]['id'];
					$_SESSION["INICIO"]['EMAIL'] = $datos[0]['email'];
					$_SESSION["INICIO"]['TIPO'] = $parametros['tipo'];
					$_SESSION["INICIO"]['PERFIL'] = $parametros['perfil'];
					$_SESSION["INICIO"]['FOTO'] = '';
					if($parametros['foto']!='' && $parametros['foto']!=null && file_exists($parametros['foto']))
					{		
						$_SESSION["INICIO"]['FOTO'] = $datos_usu[0][$parametros['foto']];
					}
					$_SESSION["INICIO"]['NO_CONCURENTE'] = $busqueda_tercero[0][$id[0]['ID']] ;
					$_SESSION["INICIO"]['NO_CONCURENTE_NOM'] =$parametros['email'];
					$_SESSION["INICIO"]['NO_CONCURENTE_TABLA_ID'] =$id[0]['ID'];
					$_SESSION["INICIO"]['NO_CONCURENTE_TABLA'] =$tabla;
					$_SESSION["INICIO"]['NO_CONCURENTE_CAMPO_IMG'] =$parametros['foto'];
					$_SESSION["INICIO"]['MODULO_SISTEMA_ANT'] ='';
					$_SESSION["INICIO"]['LISTA_ART'] =1;


					// print_r($_SESSION['INICIO']);die();

					return 1;
			}	

	}


	function cerrar_session()
	{
		// session_start();
		session_destroy();
		return 1;

	}
	function restriccion($pagina)
	{
	
	/*
		$server = $_SERVER['HTTP_HOST'];
		$proyecto =explode('controlador',substr(dirname($_SERVER['PHP_SELF']),1));
		print_r($proyecto);die();
		$path = 'http://'.$server.'/'.$proyecto[0];
		$p = explode('?',$pagina);
		// print_r($p);die();
		if(count($p)>0){$pagina= $p[0];}
		$pagina = str_replace($path,'../', $pagina);

		$pagina = explode('/',$pagina);
		$num = count($pagina);
		$pagina = $pagina[$num-1];

		// print_r($pagina);die();
		if(stripos($pagina,'#')!==false)
		{
			 $pagina = explode('#', $pagina);
			 $pagina = $pagina[0];
		}

		$termino = substr($pagina,-1,1);
		// print_r($termino);die();
		if($termino=='#')
		{
			$pagina = substr($pagina,0,-1);
		}*/

		$pagina = explode('acc=',$pagina);
		$pagina = explode('?',$pagina[1]);
		$pagina = explode('&',$pagina[0]);
		$pagina = $pagina[0];
		$pagina = $pagina.'.php';
		
		// print_r($pagina);die();
		$accesos = $this->modulos->accesos($pagina,$_SESSION['INICIO']['PERFIL']);
		if(count($accesos)>0)
		{
			$datos = array('ver'=>$accesos[0]['Ver'],'editar'=>$accesos[0]['editar'],'eliminar'=>$accesos[0]['eliminar'],'dba'=>'','modulo'=>$accesos[0]['modulos_sistema'],'pag'=>$pagina,'sistema'=>$_SESSION['INICIO']['MODULO_SISTEMA']);
			$_SESSION['INICIO']['EDITAR'] = $accesos[0]['editar'];
			$_SESSION['INICIO']['ELIMINAR'] = $accesos[0]['eliminar'];
			$_SESSION['INICIO']['VER'] = $accesos[0]['Ver'];
		}else
		{
			if(strpos($pagina, 'index')!==false || strpos($pagina, 'perfil')!==false)
			{
				/*$pag = $this->login->paginas($pagina);
				print_r($pag);die();
				if(count($pag)>0)
				{
					$tabla = 'ACCESOS';
					$datos[0]['campo'] = 'id_tipo_usu';
					$datos[0]['dato'] = $_SESSION['INICIO']['PERFIL'];
					$datos[1]['campo'] = 'ver';
					$datos[1]['dato'] = 1;
					$datos[2]['campo'] = 'editar';
					$datos[2]['dato'] = 1;
					$datos[3]['campo'] = 'eliminar';
					$datos[3]['dato'] = 1;
					$datos[4]['campo'] = 'id_paginas';
					$datos[4]['dato'] = $pag[0]['id_paginas'];
					$this->login->add($tabla,$datos);*/
					$datos = array('ver'=>1,'editar'=>1,'eliminar'=>1,'dba'=>1,'sistema'=>$_SESSION['INICIO']['MODULO_SISTEMA'],'modulo'=>1,'pag'=>$pagina);
				// }
			}else{
				$datos = array('ver'=>0,'editar'=>0,'eliminar'=>0,'dba'=>0,'modulo'=>0,'pag'=>$pagina);
			}
		}
		if($_SESSION['INICIO']['TIPO']=='DBA'){

					$datos = array('ver'=>1,'editar'=>1,'eliminar'=>1,'dba'=>1,'modulo'=>1,'sistema'=>$_SESSION['INICIO']['MODULO_SISTEMA'],'pag'=>$pagina);
		}
		// print_r($accesos);die();
		return $datos;

	}

	function menu_lateral()
	{
		 // Verificar si el menú ya está en la sesión
		//  if (isset($_SESSION['INICIO']['MENU_LATERAL']) && !empty($_SESSION['INICIO']['MENU_LATERAL'])) {
		// 	return $_SESSION['INICIO']['MENU_LATERAL']; // Devolver menú cacheado
		// }

		$opciones = '<li><a href="inicio.php?acc=index"><div class="parent-icon"><i class="bx bx-home"></i></div><div class="menu-title">Inicio</div></a></li>';
		$sin_modulo = $this->tipo->lista_modulos('sin modulo',false,$_SESSION['INICIO']['MODULO_SISTEMA']);
		if(count($sin_modulo)>0)
		{
		  $paginas = $this->modulos->paginas($query=false,$sin_modulo[0]['id']);
			foreach ($paginas as $key => $value) {
				// print_r($value);die();
			$opciones.= '<li>
				            <a href="'.$value['link_pagina'].'" id="'.$value['id_paginas'].'">
				              <i class="bx">'.$value['icono_paginas'].'</i>
				              <div class="menu-title">'.$value['nombre_pagina'].'</div>
				            </a>
				          </li>';
			}
		}


		$modulo = $this->tipo->lista_modulos(false,false,$_SESSION['INICIO']['MODULO_SISTEMA']);

		// print_r($modulo);die();

		if(count($modulo)>0)
		{
				foreach($modulo as $key => $value)
				{
						if($value['modulo']!='Sin modulo')
						{
							  $paginas = $this->modulos->paginas($query=false,$value['id']);						 

						// print_r($paginas);die();
								if(count($paginas)>0)
								{
									 $opciones.='<li>
											            <a class="has-arrow" href="javascript:;">
												              <div class="parent-icon">
												              		<i class="bx">'.$value['icono'].'</i>
												              </div>
												              <div class="menu-title">'.$value['modulo'].'</div>
											            </a> 
										           <ul class="mm-collapse">';
											foreach ($paginas as $key2 => $value2) 
											{
												$link = str_replace('.php','', $value2['link_pagina']);
												// print_r($value);die();
												$opciones.= '<li>
												            <a href="inicio.php?mod='.$value['modulos_sistema'].'&acc='.$link.'" id="'.$value2['id_paginas'].'">
												              <i class="bx">'.$value2['icono_paginas'].'</i>
												              '.$value2['nombre_pagina'].'
												            </a>
												          </li>';
												// $opciones.='<li> <a href="table-basic-table.html"><i class="bx bx-right-arrow-alt"></i>Basic Table</a></li>';
											}
										$opciones.='</ul></li>';
								}
						}
				}
		}

		// Guardar el menú generado en la sesión
		$_SESSION['INICIO']['MENU_LATERAL'] = $opciones;
		// print_r($opciones);die();

		return $opciones;
	}

	function validar_licencias()
	{		
		// print_r($_SESSION['INICIO']);die();
		$mod = '';
		$datos = $this->login->modulos_sistema();
		$num_mod = count($datos);
		$_SESSION['INICIO']['NUM_MODULOS'] = $num_mod;
		$datosEmp =  $this->mis_empresas();
		$_SESSION['INICIO']['NUM_EMPRESAS'] = $datosEmp['numEmpresas'];
		$id = '';
		$link = '';
		$pagina = '';
		// print_r($datosEmp);
		// print_r($datos);die();
		$modulos_draw = array();
		foreach ($datos as $key => $value) {
			$licencia = 1;
			$draw = '';			
			$id = $value['id'];
			$link = $value['link'];
			$lic_activa = $this->login->modulos_sistema_licencia_activa($value['id']);
			$num = rand(1, 5);
			$pagina = str_replace('.php','', $value['link']);
			if(count($lic_activa)==0)
			{
				$licencia = 0;
			}

			if(count($datos)>1)
			{
				$draw = $this->dibujar_modulo($licencia,$lic_activa,$id,$link,$value['icono'],$value['nombre_modulo']);
			}
				
			$mod = array('num'=>$num_mod,'licencia'=>$licencia,'id'=>$id,'link'=>$link,'draw'=>$draw);
			array_push($modulos_draw, $mod);
			}
		 // print_r($mod);die();
		return $modulos_draw;
	}


	function dibujar_modulo($licencia,$lic_activa,$id,$link,$icono,$nombre_modulo)
	{
		// print_r($lic_activa);die();
		$num = rand(1, 5);
		$pagina = str_replace('.php','', $link);
		switch ($num) 
		{
				case '1':		
					$estilo = 'bg-light-danger text-danger';
					break;
				case '2':
					$estilo = 'bg-light-info text-info';
					break;
				case '3':
				  $estilo = 'bg-light-success text-success';
					break;
				case '4':
					$estilo = 'bg-light-warning text-warning';
					break;
				case '5':
					$estilo = 'bg-light-primary text-primary';
					break;
		}

		$dif = 0;
		if(count($lic_activa)>0)
		{
			$fecha1 = new DateTime(date('Y-m-d'));
			$fecha2 = new DateTime($lic_activa[0]['Fecha_exp']);

			$diferencia = $fecha1->diff($fecha2);
			$dif =  $diferencia->days;
			if ($diferencia->invert) {
			    $dif = -$dif;
			}
		}

		$mod ='
					<div class="col">
							<div class="card radius-10">';
							  if($licencia==1)
							  {
									$mod.='<div class="card-body" onclick="modulo_seleccionado(\''.$id.'\',\''.$pagina.'\')">';
								}else
								{
										$mod.='<div class="card-body" onclick="licencia_vencidas_all()" >';
								}
								$mod.='<div class="text-center">
										<div class="widgets-icons rounded-circle mx-auto '.$estilo.' mb-3">'.$icono.'
											</div>
											<h4 class="my-1">'.$nombre_modulo.'</h4>';

									// print_r($dif.'-');
									if($dif<=0){
											$mod.='<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3"><i class="bx bxs-circle align-middle me-1"></i>LICENCIA VENCIDA</div>';
									}else if($dif<=10 && $dif>0)
									{
										$mod.='<div class="badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3"><i class="bx bxs-circle align-middle me-1"></i>LICENCIA POR VENCER</div>';
									}else
									{
										$mod.='<p class="mb-0 text-secondary">INGRESAR</p>';
									}

								$mod.='
									</div>
								</div>
							</div>
						</div>';

					return $mod;
	}


	function modulos_sistema()
	{		
		// print_r($_SESSION['INICIO']);die();
		$mod = '';
		$datos = $this->login->modulos_sistema();
		$num_mod = count($datos);
		$_SESSION['INICIO']['NUM_MODULOS'] = $num_mod;
		$datosEmp =  $this->mis_empresas();
		$_SESSION['INICIO']['NUM_EMPRESAS'] = $datosEmp['numEmpresas'];
		$id = '';
		$link = '';
		$pagina = '';
		// print_r($datosEmp);
		// print_r($datos);die();

		foreach ($datos as $key => $value) {
			$lic_activa = 
			$num = rand(1, 5);
			$pagina = str_replace('.php','', $value['link']);
		switch ($num) {
				case '1':		
					$estilo = 'bg-light-danger text-danger';
					break;
				case '2':
					$estilo = 'bg-light-info text-info';
					break;
				case '3':
				  $estilo = 'bg-light-success text-success';
					break;
				case '4':
					$estilo = 'bg-light-warning text-warning';
					break;
				case '5':
					$estilo = 'bg-light-primary text-primary';
					break;
				}

				$fecha1 = new DateTime(date('Y-m-d'));
				$fecha2 = new DateTime($value['Fecha_exp']);

				$diferencia = $fecha1->diff($fecha2);
				$dif =  $diferencia->days;
				if ($diferencia->invert) {
				    $dif = -$dif;
				}
				// print_r($fecha1);
				// print_r($fecha2);
				// print_r($dif);die();
				$mod.='
					<div class="col">
							<div class="card radius-10">';
							  if($dif>0)
							  {
									$mod.='<div class="card-body" onclick="modulo_seleccionado(\''.$value['id'].'\',\''.$pagina.'\')">';
								}else
								{
										$mod.='<div class="card-body" onclick="swal.fire(\'Licencia Vencida\',\'Cominiquese con su proveedor\',\'error\')" >';
								}
								$mod.='<div class="text-center">
										<div class="widgets-icons rounded-circle mx-auto '.$estilo.' mb-3">'.$value['icono'].'
											</div>
											<h4 class="my-1">'.$value['nombre_modulo'].'</h4>';

									// print_r($dif.'-');
									if($dif<=0){
											$mod.='<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3"><i class="bx bxs-circle align-middle me-1"></i>LICENCIA VENCIDA</div>';
									}else if($dif<=10 && $dif>0)
									{
										$mod.='<div class="badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3"><i class="bx bxs-circle align-middle me-1"></i>LICENCIA POR VEENCER</div>';
									}else
									{
										$mod.='<p class="mb-0 text-secondary">INGRESAR</p>';
									}

								$mod.='
									</div>
								</div>
							</div>
						</div>';
						if($key==0)
							{
								$id = $value['id'];
								$link = $value['link'];
							}
		}

		 // print_r($mod);die();
		return array('num'=>$num_mod,'html'=>$mod,'id'=>$id,'link'=>$pagina);
	}

	function modulos_sistema_acceso_rapido()
	{		
		
		$mod = '';
		$datos = $this->login->modulos_sistema();
		$num_mod = count($datos);
		$id = '';
		$link = '';
		$pagina = '';
		// print_r($datos);die();
		foreach ($datos as $key => $value) {
			// $num = rand(1,3);
			$num = 4;
			$pagina = str_replace('.php','', $value['link']);
		switch ($num) {
				case '1':		
					$estilo = 'bg-gradient-burning text-white';
					break;
				case '2':
					$estilo = 'bg-gradient-lush text-white"';
					break;
				case '3':
				  $estilo = 'bg-gradient-kyoto text-dark';
					break;							
				case '4':
					$estilo = 'text-white';
					  break;							
				}
				// print_r($value);die();


				$fecha1 = new DateTime(date('Y-m-d'));
				$fecha2 = new DateTime($value['Fecha_exp']);

				$diferencia = $fecha1->diff($fecha2);
				$dif =  $diferencia->days;
				if ($diferencia->invert) {
				    $dif = -$dif;
				}


					if($dif>0)
					{
						$mod.='<div class="col text-center" onclick="modulo_seleccionado('.$value['id'].',\'index\')">';
					}else
					{
						$mod.='<div  class="col text-center" onclick="swal.fire(\'Licencia Vencida\',\'Cominiquese con su proveedor\',\'error\')" >';
					}

					if($dif<=0){
								$mod.='<div class="badge rounded-pill bg-danger w-100">Licencia Venc. </div>';
					}else if($dif<=10 && $dif>0)
					{
						$mod.='<div class="badge rounded-pill bg-warning w-100">Licencia por Venc. </div>';
					}			
					$mod.='<div class="app-box mx-auto '.$estilo.'">'.$value['icono'].'
									</div>
									<div class="app-title">'.$value['nombre_modulo'].'</div>								
					</div>';
						if($key==0)
							{
								$id = $value['id'];
								$link = $value['link'];
							}
		}

		 // print_r($mod);die();
		return array('num'=>$num_mod,'html'=>$mod,'id'=>$id,'link'=>$pagina);
	}

	function resetear($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->login->datos_usuario(false,$parametros['email']);
		if(count($datos)>0)
		{
			if($datos[0]['email']=='')
			{
				return array('respuesta'=>-1,'mensaje'=>'*El numero de cedula: '.$parametros['ci'].' No tiene un email registrado');
			}else
			{

				$datosN[0]['campo'] = 'password';
				$datosN[0]['dato'] = $this->generate_string();

				$where[0]['campo'] = 'id_usuarios';
				$where[0]['dato'] = $datos[0]['id_usuarios'];

				if($this->tipo-> update('USUARIOS',$datosN,$where)==1)
				{
					$this->enviar_correo($datos[0]['email'],$datosN[0]['dato'] );
					return array('respuesta'=>1,'mensaje'=>$datos[0]['email']);
				}
			}

		}else
		{
			return array('respuesta'=>-1,'mensaje'=>'*Email: '.$parametros['email'].' No registrado');
		}

		// print_r($datos);die();

	}

 
	function generate_string($strength = 6) {

		$input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $input_length = strlen($input);
	    $random_string = '';
	    for($i = 0; $i < $strength; $i++) {
	        $random_character = $input[mt_rand(0, $input_length - 1)];
	        $random_string .= $random_character;
	    }
	 
	    return $random_string;
	}

	function enviar_correo($to_correo,$pass)
	{
		$cuerpo_correo = utf8_decode('Su nueva contraseña es: ').$pass;
		$titulo_correo = utf8_decode('RESETEO DE CONTRASEÑA');
		$nombre = utf8_decode('RESETEO DE CONTRASEÑA CORSINF.COM');
		return $this->email->enviar_email($to_correo,$cuerpo_correo,$titulo_correo,$correo_respaldo='example@example.com',$archivos=false,$nombre,$HTML=false);
	}


	function primer_inicio($parametros)
	{
		 // print_r($parametros);die();
		 $datos = $this->login->lista_empresa($parametros['empresa']);
		 $db_destino = $datos[0]['Base_datos'];
		 return $this->cod_global->generar_primera_vez($db_destino,$parametros['empresa']);
		 
	}

	function nombre_modulo()
	{
		$datos = $this->login-> modulos_empresa_search($_SESSION['INICIO']['MODULO_SISTEMA']);
		return $datos[0]['nombre_modulo'];
	}

	function imagen_icono_modulo()
	{
		$datos = $this->login-> modulos_empresa_search($_SESSION['INICIO']['MODULO_SISTEMA']);
		return $datos[0]['icono'];
	}

	function acceso_en_terceros($parametros)
	{
			$empresa = $this->login->lista_empresa($parametros['id']);
			$database = $empresa[0]['Base_datos'];
			$usuario = $empresa[0]['Usuario_db'];
			$password = $empresa[0]['Password_db'];
			$servidor = $empresa[0]['Ip_host'];
			$puerto = $empresa[0]['Puerto_db'];
			// print_r($parametros);die();
			// $this->cod_global->generar_primera_vez($database,$parametros['id']);
			$datos = $this->login->permisos_db_terceros($database, $usuario, $password, $servidor, $puerto);

			// print_r($datos);die();
			return $datos;
	}

	function validar_directory($parametros)
	{
		$correo = $parametros['user'];
		$lista_empresas = array();
		if($correo!='')
		{
			$tabla_noconcurente = $this->login->tabla_noconcurente();

			if(count($tabla_noconcurente)>0)
			{
					foreach ($tabla_noconcurente as $key => $value) {
							$activeDir = 1;
							$empresa = $this->login->lista_empresa($value['Id_Empresa'],1);
							if(count($empresa)>0)
							{
								if($empresa[0]['ip_directory']=='' || $empresa[0]['puerto_directory']=='' || $empresa[0]['basedn_directory']=='' || $empresa[0]['dominio_directory']=='' || $empresa[0]['usuario_directory']=='' || $empresa[0]['password_directory']=='')
								{
									 $activeDir = 0; 
								}

								  if($this->validar_dominio($empresa[0]['dominio_directory'],$correo)==1)
								  {

										// print_r($value);die();
										$datos = $this->login->buscar_en_tablas_noconcurente_empresaTerceros($empresa,$value['Tabla'],$correo,$value['Campo_usuario']);
										// print_r($datos);
										// print_r($empresa);
										
										if(count($datos)>0)
										{
												$user = $this->login->buscar_en_tablas_noconcurente_empresaTerceros($empresa,$value['Tabla'],$correo,$value['Campo_usuario'],1,$value['Campo_pass']);
												// print_r($user);die();

											$empresa[0]['ActiveDirectory'] = $activeDir;
											if(count($user)>0)
											{
												$primerIngreso = 0;
											}else
											{
												$primerIngreso = 1;								
											}
											$lista_empresas[] = array('id'=>$empresa[0]['Id_empresa'],'Empresa'=>$empresa[0]['Razon_Social'],'ActiveDirectory'=>$activeDir,'PrimerIngresoActiveDir'=>$primerIngreso,'tabla'=>$value['Tabla']);
										}
										// print_r($empresa);
										// break;
									}
							}
					}
			}

			if(count($lista_empresas)==0)
			{
				$activeDir = 1;
				$datos = $this->login->buscar_empresas($correo);
				if($datos[0]['ip_directory']=='' || $datos[0]['puerto_directory']=='' || $datos[0]['basedn_directory']=='' || $datos[0]['dominio_directory']=='' || $datos[0]['usuario_directory']=='' || $datos[0]['password_directory']=='')
					{
						 $activeDir = 0; 
					}

					if($datos[0]['password']!='')
					{
						$primerIngreso = 0;
					}else
					{
						$primerIngreso = 1;								
					}
					$lista_empresas[] = array('id'=>$datos[0]['Id_Empresa'],'Empresa'=>$datos[0]['Razon_Social'],'ActiveDirectory'=>$activeDir,'PrimerIngresoActiveDir'=>$primerIngreso,'tabla'=>'USUARIOS');

				 // print_r($usuarios);die();

			}
				 

		}

		return $lista_empresas;
	}

	function validar_dominio($dominio,$correo)
	{

		$dom = explode('.',$dominio);
		$pos = strpos($correo, $dom[0]);
		if ($pos === false) {
		   return -1;
		} else
		{
			return 1;
		}
	}

	function primerInicioActive($parametros)
	{
			$empresa = $this->login->lista_empresa($parametros['empresa'],1);
			$respuesta = $this->active->AutentificarUserActiveDir($parametros['user'], $parametros['pass'],$empresa);
			$msj='';
			switch ($respuesta) {
				case '1':
					if($parametros!='USUARIOS')
					{
						$respuesta = $this->save_update_passActive($parametros);
					}
					{						
						$respuesta = $this->save_update_passActiveUsu($parametros);
					}
					$msj = 'Autenticación exitosa.';
					break;
				case '-1':
				$msj = 'Error de autenticación. Credenciales incorrectas.';
					// code...
					break;
				case '-2':
				$msj = 'Usuario no encontrado.';
					// code...
					break;
				case '-3':
				$msj = 'Error en la búsqueda LDAP.';
					// code...
					break;
				case '-4':
				$msj = 'Error en la conexion LDAP.';
					// code...
					break;				
			}

			return array('resp'=>$respuesta,'msj'=>$msj);

	}


	function save_update_passActive($parametros)
	{
		 $empresa = $this->login->lista_empresa($parametros['empresa'],1);
		 $no_concurente = $this->login->tabla_noconcurente($parametros['empresa'],$parametros['tabla']);
		 $r = -1;
		 if(count($no_concurente)>0)
		 {
		 	  $campoValidar = $no_concurente[0]['Campo_usuario'];
		 		$user = $this->login->buscar_en_tablas_noconcurente_empresaTerceros($empresa,$parametros['tabla'],$parametros['user'],$campoValidar,false,false);
		 		$ID = $this->login->id_tabla_terceros($parametros['tabla'],$empresa);
		 		$sql = "UPDATE ".$parametros['tabla']." SET PASS = '".$this->cod_global->enciptar_clave($parametros['pass'])."' WHERE ".$ID[0]['ID']." = ".$user[0][$ID[0]['ID']];
		 		$r =  $this->login->update_no_concurente($empresa,$sql);

		 		// print_r($r);die();
		 }

		 // print_r('hola');die();

		 return $r;
	}

	function save_update_passActiveUsu($parametros)
	{
		 $empresa = $this->login->lista_empresa($parametros['empresa'],1);
		 $user = $this->login->buscar_empresas($parametros['user'],$pass=false,$parametros['empresa']);

		 $datos[0]['campo']= 'password';
		 $datos[0]['dato']= $this->cod_global->enciptar_clave($parametros['pass']);


		 $datosW[0]['campo']= 'id_usuarios';
		 $datosW[0]['dato']= $user[0]['Id_usuario'];

		 return $this->login->update('USUARIOS',$datos,$datosW);
	}

	function modulos_sistema_selected($parametros)
	{
		// print_r($parametros);
		// print_r($_SESSION['INICIO']);die();

		$licencias = $this->login->empresa_licencias($_SESSION['INICIO']['ID_EMPRESA'],$parametros);

		$fecha1 = new DateTime(date('Y-m-d'));
		$fecha2 = new DateTime($licencias[0]['Fecha_exp']);

		$diferencia = $fecha1->diff($fecha2);
		$dif =  $diferencia->days;
		if ($diferencia->invert) {
		    $dif = -$dif;
		}
		if($dif>0)
		{
				$_SESSION['INICIO']['MODULO_SISTEMA'] = $parametros;
				$_SESSION['INICIO']['MODULO_SISTEMA_NOMBRE'] = $this->nombre_modulo();
				$_SESSION['INICIO']['MODULO_SISTEMA_IMG_ICO'] = $this->imagen_icono_modulo();

				$this->menu_lateral();
				// print_r($_SESSION['INICIO']);die();
				return 1;
		}else
		{
			return -2;
		}

	}

}
?>