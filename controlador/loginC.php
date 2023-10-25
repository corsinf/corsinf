<?php
include('../modelo/loginM.php');
include('../modelo/modulos_paginasM.php');
include('../modelo/tipo_usuarioM.php');
include('../lib/phpmailer/enviar_emails.php');
if(isset($_SESSION['INICIO']))
{	
  @session_start();
} 
/**
 * 
 */
$controlador = new loginC();
if(isset($_GET['iniciar']))
{
echo json_encode($controlador->iniciar_sesion($_POST['parametros']));
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
}
if(isset($_GET['modulos_sistema']))
{
  echo json_encode($controlador->modulos_sistema());
}
if(isset($_GET['modulos_sistema_selected']))
{
	$_SESSION['INICIO']['MODULO_SISTEMA'] = $_POST['modulo_sistema'];
	 echo json_encode(1);
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
	echo json_encode('1');
}
if(isset($_GET['regresar_modulo']))
{
	 $_SESSION['INICIO']['MODULO_SISTEMA'] =  $_SESSION['INICIO']['MODULO_SISTEMA_ANT']; 
	 // $_SESSION['INICIO']['MODULO_SISTEMA'] = '1';
	echo json_encode($_SESSION['INICIO']['MODULO_SISTEMA']);
}
class loginC
{
	private $login;
	private $modulos;
	private $tipo;
	private $email;
	function __construct()
	{
		$this->login = new loginM();
		$this->modulos = new modulos_paginasM();
		$this->tipo = new tipo_usuarioM();
		$this->email = new enviar_emails();
	}


	function iniciar_sesion($parametros)
	{
		if($this->login->existe($parametros['email'],$parametros['pass']) == 1)
		{
			$datos = $this->login->datos_login($parametros['email'],$parametros['pass']);
			if(count($datos)>0)
			{
				// session_start();
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
				$_SESSION["INICIO"]['MODULO_SISTEMA_ANT'] ='';
				$_SESSION["INICIO"]['LISTA_ART'] =1;
				return 1;

			}else
			{
				return -1;
			}

		}else
		{
			// busca en no concurrentes que es en custodios

			$datos = $this->login->datos_login_no_concurentes($parametros['email'],$parametros['pass']);
			// print_r($datos);die();
			if(count($datos)>0)
			{
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
					$_SESSION["INICIO"]['NO_CONCURENTE'] = $datos[0]['PERSON_NO'];
					$_SESSION["INICIO"]['NO_CONCURENTE_NOM'] = $datos[0]['PERSON_NOM'];
				  $_SESSION["INICIO"]['MODULO_SISTEMA_ANT'] ='';
					$_SESSION["INICIO"]['LISTA_ART'] =1;
					return 1;
			}
			return -2;
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
			if(strpos($pagina, 'index')!==false)
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
					$datos = array('ver'=>1,'editar'=>1,'eliminar'=>1,'dba'=>1,'modulo'=>1,'pag'=>$pagina);
				// }
			}else{
				$datos = array('ver'=>0,'editar'=>0,'eliminar'=>0,'dba'=>0,'modulo'=>0,'pag'=>$pagina);
			}
		}
		// print_r($accesos);die();
		return $datos;

	}

	function menu_lateral()
	{
		$opciones = '';
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
									              <div class="parent-icon"><i class="bx">'.$value['icono'].'</i></div>
									              <div class="menu-title">'.$value['modulo'].'</div>
									            </a> <ul>';
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

		// print_r($opciones);die();

		return $opciones;
	}

	function modulos_sistema()
	{		
		
		$mod = '';
		$datos = $this->login->modulos_sistema();
		$num_mod = count($datos);
		$id = '';
		$link = '';
		$pagina = '';
		foreach ($datos as $key => $value) {
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
				$mod.='
					<div class="col">
							<div class="card radius-10">
								<div class="card-body" onclick="modulo_seleccionado(\''.$value['id'].'\',\''.$pagina.'\')">
									<div class="text-center">
										<div class="widgets-icons rounded-circle mx-auto '.$estilo.' mb-3">'.$value['icono'].'
										</div>
										<h4 class="my-1">'.$value['nombre_modulo'].'</h4>
										<p class="mb-0 text-secondary">INGRESAR</p>
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

}
?>