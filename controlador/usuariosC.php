<?php 
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}
include('../db/codigos_globales.php');
include('../modelo/tipo_usuarioM.php');
include('../modelo/usuariosM.php');

/**
 * 
 */$controlador = new usuariosC();
if(isset($_GET['lista_usuarios']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->usuarios($parametros));
}
if(isset($_GET['lista_usuarios_ddl2']))
{
	$parametros = array('query'=>'','id'=>'');
	if(isset($_GET['q']))
	{
		$parametros['query'] = $_GET['q'];
		// $parametros['tipo'] = 'C'; 
	}
	echo json_encode($controlador->ddl_usuarios2($parametros));
}
if(isset($_GET['lista_usuarios_ddl']))
{
	$parametros = array('id'=>'','query'=>isset($_GET['q']));
	echo json_encode($controlador->ddl_usuarios($parametros));
}
if(isset($_GET['usuarios_all_autocompletado']))
{
	$parametros = array('query'=>$_POST['search']);
	echo json_encode($controlador->usuarios_all_autocompletado($parametros));
}
if(isset($_GET['lista_usuarios_ina']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->usuarios_ina($parametros));
}

if(isset($_GET['datos_usuarios']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->usuario_datos($parametros));
}
if(isset($_GET['validar_registro']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->validar_registro($parametros));
}

if(isset($_GET['guardar_usuario']))
{
	$parametros = $_POST;
	echo json_encode($controlador->add_usuario($parametros));
}
if(isset($_GET['guardar_pass']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_pass($parametros));
}
if(isset($_GET['guardar_perfil']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_perfil($parametros));
}

if(isset($_GET['guardar_email']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_email($parametros));
}

if(isset($_GET['tipo']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->tipo_usuario($query));
}
if(isset($_GET['eliminar_tipo']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->eliminar_tipo($id));
}
if(isset($_GET['usuario_estado']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->usuario_estado($id));
}
if(isset($_GET['usuario_estado_']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->usuario_estado_($id));
}

if(isset($_GET['cargar_imagen']))
{
   echo json_encode($controlador->guardar_foto($_FILES,$_POST));
}

if(isset($_GET['usuarios']))
{
   echo json_encode($controlador->usuarios_all());
}

if(isset($_GET['cargar_imagen_no_concurente']))
{
   echo json_encode($controlador->guardar_foto_perfil($_FILES,$_POST));
}
if(isset($_GET['editar_datos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->editar_datos($parametros));
}
if(isset($_GET['guardar_credencial']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_credencial($parametros));
}




class usuariosC
{
	private $modelo;
	private $pagina;
	private $global;
	private $pdf;

	
	function __construct()
	{
		$this->modelo = new usuariosM();
		$this->tipo = new tipo_usuarioM();
		$this->pagina = new codigos_globales();
		// $this->pagina->registrar_pagina_creada('../vista/usuarios.php','Usuarios nuevos','3','estado'); para guardar
	}


	function tipo_usuario($query)
	{
		$datos = $this->tipo->lista_tipo_usuario($query);
		$opciones = array();
		foreach ($datos as $key => $value) {
			if($value['nombre']=='DBA')
			{
				if($_SESSION['INICIO']['TIPO']=='DBA')
				{
					$opciones[] = array('id'=>$value['id'],'text'=>$value['nombre']);		
				}
			}else
			{
				$opciones[] = array('id'=>$value['id'],'text'=>$value['nombre']);					
			}
		}
		return $opciones;
	}
	function usuarios_ina($parametros)
	{
		$datos = $this->modelo->lista_usuarios_ina($parametros['id'],$parametros['query']);
		$botones[0] = array('boton'=>'Habilitar','icono'=>'<i class="fas fa-check nav-icon"></i>','tipo'=>'success','id'=>'id');
		$ocultar = array('id','idt');
		$cabecera = array('CI','Nombre','Direccion','telefono','Tipo de usuario','Nick','password','Email','Estado');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar);

		return $tabla;
	}


	
	function usuarios_all()
	{
		$datos = $this->modelo->lista_usuarios();
		return $datos;
	}

	function usuarios_all_autocompletado($parametros)
	{
		$datos = $this->modelo->usuarios_all_sin_tipo_usuario($id=false,$parametros['query'],$tipo=false,$ci=false,$email=false);
		// print_r($datos);die();
		$lista = array();
		if($_SESSION['INICIO']['TIPO']=='DBA')
		{
			foreach ($datos as $key => $value) {
				$usuario_existente = $this->modelo->usuarios_all_empresa_actual($id=false,$parametros['query'],$tipo=false,$ci=false,$email=false);
				// print_r($usuario_existente);die();
				if(empty($usuario_existente)){
					$lista[] = array('value'=>$value['id'],'label'=>$value['nom'],'data'=>$value);
				}
			}
		}
		return $lista;
	}


	function usuarios($parametros)
	{
		$datos = $this->modelo->lista_usuarios($parametros['id'],$parametros['query']);
		$tabla='';
		foreach ($datos as $key => $value) {
			if($value['tipo']=='DBA')
			{
					if($_SESSION['INICIO']['TIPO']=='DBA')
					{

						$tabla.='
						<div class="col">
            <div class="card radius-15">
              <div class="card-body text-center">
                <div class="p-4 border radius-15">
                  <img src="../'.$value['foto'].'" width="110" height="110" class="rounded-circle shadow" alt="">
                  <h5 class="mb-0 mt-5">'.$value['nombre'].' '.$value['apellido'].'</h5>
                  <p class="mb-3">'.$value['tipo'].'</p>
                  <div class="list-inline contacts-social mt-3 mb-3"> <a href="javascript:;" class="list-inline-item bg-facebook text-white border-0"><i class="bx bxl-facebook"></i></a>
                    <a href="javascript:;" class="list-inline-item bg-twitter text-white border-0"><i class="bx bxl-twitter"></i></a>
                   <!-- <a href="javascript:;" class="list-inline-item bg-google text-white border-0"><i class="bx bxl-google"></i></a> -->
                    <a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0"><i class="bx bxl-linkedin"></i></a>
                  </div>
                  <div class="d-grid"> <a href="#" class="btn btn-outline-primary radius-15">Contact Me</a>
                  </div>
                  <div class="d-grid"><a href="inicio.php?acc=detalle_usuario&usuario='.$value['id'].'" class="btn btn-outline-primary radius-15"> Ver Perfil </a>
                  </div>
                </div>
              </div>
            </div>
          </div>'; 
						
					}
				}else
				{
// print_r($value);die();

						$tabla.='
						<div class="col">
            <div class="card radius-15">
              <div class="card-body text-center">
                <div class="p-4 border radius-15">
                  <img src="'.$value['foto'].'" width="110" height="110" class="rounded-circle shadow" alt="">
                  <h5 class="mb-0 mt-5">'.$value['nombre'].' '.$value['apellido'].'</h5>
                  <p class="mb-3">'.$value['tipo'].'</p>
                  <div class="list-inline contacts-social mt-3 mb-3"> <a href="javascript:;" class="list-inline-item bg-facebook text-white border-0"><i class="bx bxl-facebook"></i></a>
                    <a href="javascript:;" class="list-inline-item bg-twitter text-white border-0"><i class="bx bxl-twitter"></i></a>
                   <!-- <a href="javascript:;" class="list-inline-item bg-google text-white border-0"><i class="bx bxl-google"></i></a> -->
                    <a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0"><i class="bx bxl-linkedin"></i></a>
                  </div>
                  <div class="d-grid"> <a href="#" class="btn btn-outline-primary radius-15">Contactame</a>
                  </div>
                  <div class="d-grid"><a href="inicio.php?acc=detalle_usuario&usuario='.$value['id'].'" class="btn btn-outline-primary radius-15">Ver Perfil</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          '; 

					
				}
		}
		
		return $tabla;
	}

	function ddl_usuarios2($parametros)
	{		
		$datos = $this->modelo->lista_usuarios($parametros['id'],$parametros['query']);
		foreach ($datos as $key => $value) {
			if($value['tipo']=='DBA')
			{
				if($_SESSION['INICIO']['TIPO']=='DBA')
				{
				  $rep[] = array('id'=>$value['id'],'text'=>$value['nom']);
				}
			}else
			{
				$rep[] = array('id'=>$value['id'],'text'=>$value['nom']);				
			}
		}
		return $rep;

	}

	function ddl_usuarios($parametros)
	{		
		$datos = $this->modelo->lista_usuarios($parametros['id'],$parametros['query']);
		$rep[0] = array('id'=>'T','text'=>'TODOS');
		foreach ($datos as $key => $value) {
			$rep[] = array('id'=>$value['id'],'text'=>$value['nom']);
		}
		return $rep;

	}

	function guardar_perfil($parametros)
	{
		// print_r($parametros);die();
		if($_SESSION['INICIO']['NO_CONCURENTE']=='')
		{
			$datos[0]['campo']='nombres';
	    $datos[0]['dato']=$parametros['nombre'];
	    $datos[2]['campo']='apellidos';
	    $datos[2]['dato']=$parametros['apellido'];
	    $datos[3]['campo']='direccion';
	    $datos[3]['dato']=$parametros['direccion'];
	    $datos[4]['campo']='telefono';
	    $datos[4]['dato']=$parametros['telefono'];
	    $datos[7]['campo']='ci_ruc';
	    $datos[7]['dato']=$parametros['ci'];			
	    $datos[8]['campo']='email';
	    $datos[8]['dato']=$parametros['email'];	    

		  $where[0]['campo']='id_usuarios';
		  $where[0]['dato'] = $parametros['id'];
		  return $this->modelo->update('USUARIOS',$datos,$where);
		}else
		{
			$datos[0]['campo']='PERSON_NOM';
	    $datos[0]['dato']=$parametros['nombre'];
	    $datos[3]['campo']='DIRECCION';
	    $datos[3]['dato']=$parametros['direccion'];
	    $datos[4]['campo']='TELEFONO';
	    $datos[4]['dato']=$parametros['telefono'];
	    $datos[7]['campo']='PERSON_CI';
	    $datos[7]['dato']=$parametros['ci'];			
	    $datos[8]['campo']='PERSON_CORREO';
	    $datos[8]['dato']=$parametros['email'];	    

		  $where[0]['campo']='PERSON_NO';
		  $where[0]['dato'] = $parametros['id'];
		  return $this->modelo->update('PERSON_NO',$datos,$where);

		}

	}

	function add_usuario($parametros)
	{
		if($parametros['txt_usuario_update']=='')
		{

		// print_r($parametros);die();
				$datos[0]['campo']='password';
		    $datos[0]['dato']= $this->pagina->enciptar_clave($parametros['txt_pass']);
		    $datos[1]['campo']='nombres';
		    $datos[1]['dato']=$parametros['txt_nombre'];
		    $datos[2]['campo']='direccion';
		    $datos[2]['dato']=$parametros['txt_dir'];
		    $datos[3]['campo']='telefono';
		    $datos[3]['dato']=$parametros['txt_telefono'];
		    $datos[4]['campo']='email';
		    $datos[4]['dato']=$parametros['txt_emial'];
		    $datos[5]['campo']='ci_ruc';
		    $datos[5]['dato']=$parametros['txt_ci'];	

		    $datos[6]['campo']='apellidos';
		    $datos[6]['dato']=$parametros['txt_apellido'];

		    $datos[7]['campo']='link_web';
		    $datos[7]['dato']=$parametros['web'];	
		    $datos[8]['campo']='link_tw';
		    $datos[8]['dato']=$parametros['tw'];
		    $datos[9]['campo']='link_ins';
		    $datos[9]['dato']=$parametros['ins'];
		    $datos[10]['campo']='link_fb';
		    $datos[10]['dato']=$parametros['fb'];			     

		    // Guarda los datos del usuario en master
		    $this->modelo->guardar($datos,'USUARIOS'); 
		    // buscamos al usuario creado en master
		    $usuario =  $this->modelo->lista_usuarios_simple($id=false,$query=false,$ci=$parametros['txt_ci'],$email=$parametros['txt_emial']);

		    // guardar el usuario en accesos de empresa actual en master(acceso empresa)
		    $datosAE[0]['campo']='Id_usuario';
		    $datosAE[0]['dato']=$usuario[0]['id'];
		    $datosAE[1]['campo']='Id_Empresa';
		    $datosAE[1]['dato']=$_SESSION['INICIO']['ID_EMPRESA'];	
		    $datosAE[2]['campo']='Id_Tipo_usuario';
		    $datosAE[2]['dato']=$parametros['ddl_tipo_usuario'];	
		    $this->modelo->guardar($datosAE,'ACCESOS_EMPRESA'); 

		    
		     // $datosT[0]['campo']='ID_USUARIO';
		     // $datosT[0]['dato']=$usuario[0]['id'];
		     // $datosT[1]['campo']='ID_TIPO_USUARIO';
		     // $datosT[1]['dato']=$parametros['ddl_tipo_usuario'];		     
		     // $datosT[2]['campo']='ID_EMPRESA';
		     // $datosT[2]['dato']=$_SESSION['INICIO']['ID_EMPRESA'];		
		     // $this->modelo->guardar($datosT,'USUARIO_TIPO_USUARIO'); 


		    // actualiza en empresa logueada
		    $resp = $this->modelo->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);


		     return $usuario[0]['id'];

		   
		    
		}else
		{
			 
			  // print_r($perfil);die();
		    // $datos[0]['campo']='nick_usuario';
		    // $datos[0]['dato']=$parametros['txt_nick'];
		    $datos[0]['campo']='password';
		    $datos[0]['dato']= $this->pagina->enciptar_clave($parametros['txt_pass']);
		    $datos[1]['campo']='nombres';
		    $datos[1]['dato']=$parametros['txt_nombre'];
		    $datos[2]['campo']='direccion';
		    $datos[2]['dato']=$parametros['txt_dir'];
		    $datos[3]['campo']='telefono';
		    $datos[3]['dato']=$parametros['txt_telefono'];
		    $datos[4]['campo']='email';
		    $datos[4]['dato']=$parametros['txt_emial'];
		    $datos[5]['campo']='ci_ruc';
		    $datos[5]['dato']=$parametros['txt_ci'];		
	      $datos[6]['campo']='apellidos';
		    $datos[6]['dato']=$parametros['txt_apellido'];	

		    $datos[7]['campo']='link_web';
		    $datos[7]['dato']=$parametros['web'];	
		    $datos[8]['campo']='link_tw';
		    $datos[8]['dato']=$parametros['tw'];
		    $datos[9]['campo']='link_ins';
		    $datos[9]['dato']=$parametros['ins'];
		    $datos[10]['campo']='link_fb';
		    $datos[10]['dato']=$parametros['fb'];			     
     


		    $where[0]['campo']='id_usuarios';
		    $where[0]['dato'] = $parametros['txt_usuario_update'];
		    $this->modelo->update('USUARIOS',$datos,$where);


		    //ingresa el acceso al usuario en la empresa 

		    $acceso = $this->modelo->existe_acceso_usuario_empresa($parametros['txt_usuario_update']);
		    	$datosA[0]['campo']='Id_usuario';
			    $datosA[0]['dato']=$parametros['txt_usuario_update'];	
			    $datosA[1]['campo']='Id_Empresa';
			    $datosA[1]['dato']=$_SESSION['INICIO']['ID_EMPRESA'];
			    $datosA[2]['campo']='Id_Tipo_usuario';
		    	$datosA[2]['dato']=$parametros['ddl_tipo_usuario'];	
		    if(count($acceso)==0)
		    {			    
			    $this->modelo->guardar($datosA,'ACCESOS_EMPRESA');
			  }else
			  {
			  	// print_r($acceso);die();
			  	$whereA[0]['campo']='Id_accesos_empresa';
			    $whereA[0]['dato']=$acceso[0]['Id_accesos_empresa'];	
			  	$this->modelo->update('ACCESOS_EMPRESA',$datosA,$whereA);
			  }

			  // $perfil = $this->modelo->existe_usuario_perfil(false,$parametros['txt_usuario_update']);
			  // if($perfil==-1)
			  // {
			  // 	$datosA[0]['campo']='ID_USUARIO';
			  //   $datosA[0]['dato']=$parametros['txt_usuario_update'];	
			  //   $datosA[1]['campo']='ID_EMPRESA';
			  //   $datosA[1]['dato']=$_SESSION['INICIO']['ID_EMPRESA'];				    
			  //   $datosA[2]['campo']='ID_TIPO_USUARIO';
			  //   $datosA[2]['dato']=$parametros['ddl_tipo_usuario'];	
			  //   $this->modelo->guardar($datosA,'USUARIO_TIPO_USUARIO');
			  // }else
			  // {				    
			  //   $datosA[1]['campo']='ID_TIPO_USUARIO';
			  //   $datosA[1]['dato']=$parametros['ddl_tipo_usuario'];	

			  //   $where[0]['campo']='ID_USUARIO';
			  //   $where[0]['dato'] = $parametros['txt_usuario_update'];			    
			  //   $where[1]['campo']='ID_EMPRESA';
			  //   $where[1]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];				   
			  //   $this->modelo->update('USUARIO_TIPO_USUARIO',$datosA,$where);

			  // }


		    return  $this->modelo->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);

		}
	}

 //  	function categorias($query)
	// {
	// 	$datos = $this->modelo->categorias($query);
	// 	$cta = array();
	// 	foreach ($datos as $key => $value) {
	// 		$cta[] = array('id'=>$value['id'],'text'=>utf8_encode($value['nombre']));			
	// 	}
	// 	return $cta;
	// }
	function usuario_datos($parametros)
	{
		if($_SESSION['INICIO']['NO_CONCURENTE']=='')
		{
			$datos  = $this->modelo->lista_usuarios($parametros['id'],$parametros['query']);	
		}else
		{
			$datos  = $this->modelo->no_concurente_data();
			$datosNOCon = $this->modelo->credenciales_no_concurentes_campos();
			if(count($datosNOCon)>0)
			{
				$datosNo = $this->modelo->credenciales_no_concurentes_datos($datosNOCon[0]['usu'],$datosNOCon[0]['pass']);
				if(count($datosNo)>0)
				{
					$datos[0]['pass'] = $datosNo[0]['pass'];
					$datos[0]['usu'] = $datosNo[0]['usuario']; 
				}
			}
			if(!file_exists($datos[0]['foto']))
			{
				 $datos[0]['foto'] ='';
			}			
		}
		if($datos[0]['pass']!='')
		{
			$datos[0]['pass'] = $this->pagina->desenciptar_clave($datos[0]['pass']);
		}

		// print_r($datos);die();
		return $datos;

	}

	function validar_registro($parametros)
	{		
		// print_r($parametros);die();

		$datos  = $this->modelo->usuarios_all_empresa_actual(false,false,false,$parametros['cedula']);
		if(count($datos)==0)
		{

			$datos  = $this->modelo->usuarios_all(false,false,false,$parametros['cedula']);
			return $datos;
		}else
		{
			return -3;
		}
	}


	function eliminar_tipo($id)
	{
		$resp = $this->modelo->eliminar_tipo($id);
		$this->modelo->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);
		return $resp;

	}

	function usuario_estado($id)
	{
		 $datos[0]['campo']='estado';
		 $datos[0]['dato']='I';


		 $where[0]['campo']='id_usuarios';
		 $where[0]['dato'] = $id;
		 $this->modelo->update('USUARIOS',$datos,$where);
	   return  $this->modelo->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);


	}
	function usuario_estado_($id)
	{
		 $datos[0]['campo']='estado_usuario';
		 $datos[0]['dato']='A';


		 $where[0]['campo']='id_usuario';
		 $where[0]['dato'] = $id;
		 return $this->modelo->update('usuarios',$datos,$where);

	}

	function guardar_pass($parametros)
	{
		 $datos[0]['campo']='password';
		 $datos[0]['dato']= $this->pagina->enciptar_clave($parametros['pass']);


		 $where[0]['campo']='id_usuarios';
		 $where[0]['dato'] = $parametros['id'];
		 return $this->modelo->update('USUARIOS',$datos,$where);

	}	

	function guardar_email($parametros)
	{
		 $datos[0]['campo']='email';
		 $datos[0]['dato']= $parametros['email'];


		 $where[0]['campo']='id_usuarios';
		 $where[0]['dato'] = $parametros['id'];
		 return $this->modelo->update('USUARIOS',$datos,$where);

	}

	function guardar_foto_perfil($file,$post)
	 {
	 		$ruta='../img/usuarios/';//ruta carpeta donde queremos copiar las imágenes
	 		if($_SESSION['INICIO']['NO_CONCURENTE']!=''){
	    	$ruta='../img/no_concurentes/';//ruta carpeta donde queremos copiar las imágenes
	  	}
	    if (!file_exists($ruta)) {
	       mkdir($ruta, 0777, true);
	    }
	    if($this->validar_formato_img($file)==1)
	    {
	         $uploadfile_temporal=$file['file_img']['tmp_name'];
	         $tipo = explode('/', $file['file_img']['type']);
	         $nombre = $post['name_img'].'.'.$tipo[1];	        
	         $nuevo_nom=$ruta.$nombre;
	         if (is_uploaded_file($uploadfile_temporal))
	         {
	           move_uploaded_file($uploadfile_temporal,$nuevo_nom);
	            if($_SESSION['INICIO']['NO_CONCURENTE']!=''){
	              $datosI[0]['campo']=$_SESSION['INICIO']['NO_CONCURENTE_CAMPO_IMG'];
	              $datosI[0]['dato'] = $nuevo_nom;
	              $where[0]['campo'] = $_SESSION['INICIO']['NO_CONCURENTE_TABLA_ID'];
	              $where[0]['dato'] =  $_SESSION['INICIO']['NO_CONCURENTE'];
	              $base = $this->modelo->updateEmpresa($_SESSION['INICIO']['NO_CONCURENTE_TABLA'],$datosI,$where);
	            }else
	            {
	            	$datosI[0]['campo']='foto';
	              $datosI[0]['dato'] = $nuevo_nom;
	              $where[0]['campo'] = 'id_usuarios';
	              $where[0]['dato'] =  $_SESSION['INICIO']['ID_USUARIO'];
	              $base = $this->modelo->update('USUARIOS',$datosI,$where);
	            }
	             $resp = $this->modelo->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);


	              $_SESSION['INICIO']['FOTO'] = $nuevo_nom;
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

	function guardar_foto($file,$post)
	 {
	 	// print_r($file);
	 	// print_r($post);die();
	    $ruta='../img/usuarios/';//ruta carpeta donde queremos copiar las imágenes
	    if (!file_exists($ruta)) {
	       mkdir($ruta, 0777, true);
	    }
	    if($this->validar_formato_img($file)==1)
	    {
	         $uploadfile_temporal=$file['file_img']['tmp_name'];
	         $tipo = explode('/', $file['file_img']['type']);
	         $nombre = $post['txt_id'].'.'.$tipo[1];	        
	         $nuevo_nom=$ruta.$nombre;
	         if (is_uploaded_file($uploadfile_temporal))
	         {
	           move_uploaded_file($uploadfile_temporal,$nuevo_nom);
	          
	              $datosI[0]['campo']='foto';
	              $datosI[0]['dato'] = $nuevo_nom;
	              $where[0]['campo'] = 'id_usuarios';
	              $where[0]['dato'] = $post['txt_id'];
	              $base = $this->modelo->update('USUARIOS',$datosI,$where);
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
 function validar_formato_img($file)
  {
    switch ($file['file_img']['type']) {
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

  function editar_datos($parametros)
  {
  	switch ($parametros['tabla']) {
  		case 'representantes':
	  		$datos[0]['campo'] ='sa_rep_primer_nombre' ;
	  		$datos[0]['dato'] =$parametros['nombre1'];
	  		$datos[1]['campo'] ='sa_rep_segundo_nombre' ;
	  		$datos[1]['dato'] =$parametros['nombre2'];
	  		$datos[2]['campo'] ='sa_rep_primer_apellido' ;
	  		$datos[2]['dato'] =$parametros['apellidos1'];
	  		$datos[3]['campo'] ='sa_rep_segundo_apellido' ;
	  		$datos[3]['dato'] =$parametros['apellidos2'];
	  		$datos[4]['campo'] ='sa_rep_fecha_nacimiento' ;
	  		$datos[4]['dato'] =$parametros['fecha_n'];
	  		$datos[5]['campo'] ='sa_rep_correo' ;
	  		$datos[5]['dato'] =$parametros['correo'];
	  		$datos[6]['campo'] ='sa_rep_telefono_1' ;
	  		$datos[6]['dato'] =$parametros['telefono'];
	  		$datos[7]['campo'] ='sa_rep_cedula' ;
	  		$datos[7]['dato'] =$parametros['cedula'];
  			break;
  	case 'docentes':
	  		$datos[0]['campo'] ='sa_doc_primer_nombre' ;
	  		$datos[0]['dato'] =$parametros['nombre1'];
	  		$datos[1]['campo'] ='sa_doc_segundo_nombre' ;
	  		$datos[1]['dato'] =$parametros['nombre2'];
	  		$datos[2]['campo'] ='sa_doc_primer_apellido' ;
	  		$datos[2]['dato'] =$parametros['apellidos1'];
	  		$datos[3]['campo'] ='sa_doc_segundo_apellido' ;
	  		$datos[3]['dato'] =$parametros['apellidos2'];
	  		$datos[4]['campo'] ='sa_doc_fecha_nacimiento' ;
	  		$datos[4]['dato'] =$parametros['fecha_n'];
	  		$datos[5]['campo'] ='sa_doc_correo' ;
	  		$datos[5]['dato'] =$parametros['correo'];
	  		$datos[6]['campo'] ='sa_doc_telefono_1' ;
	  		$datos[6]['dato'] =$parametros['telefono'];
	  		$datos[7]['campo'] ='sa_doc_cedula' ;
	  		$datos[7]['dato'] =$parametros['cedula'];
  			break;
  	case 'comunidad':
	  		$datos[0]['campo'] ='sa_com_primer_nombre' ;
	  		$datos[0]['dato'] =$parametros['nombre1'];
	  		$datos[1]['campo'] ='sa_com_segundo_nombre' ;
	  		$datos[1]['dato'] =$parametros['nombre2'];
	  		$datos[2]['campo'] ='sa_com_primer_apellido' ;
	  		$datos[2]['dato'] =$parametros['apellidos1'];
	  		$datos[3]['campo'] ='sa_com_segundo_apellido' ;
	  		$datos[3]['dato'] =$parametros['apellidos2'];
	  		$datos[4]['campo'] ='sa_com_fecha_nacimiento' ;
	  		$datos[4]['dato'] =$parametros['fecha_n'];
	  		$datos[5]['campo'] ='sa_com_correo' ;
	  		$datos[5]['dato'] =$parametros['correo'];
	  		$datos[6]['campo'] ='sa_com_telefono_1' ;
	  		$datos[6]['dato'] =$parametros['telefono'];
	  		$datos[7]['campo'] ='sa_com_cedula' ;
	  		$datos[7]['dato'] =$parametros['cedula'];
  			break;
  	}

  	$where[0]['campo'] = $_SESSION['INICIO']['NO_CONCURENTE_TABLA_ID'];
  	$where[0]['dato'] = $_SESSION['INICIO']['NO_CONCURENTE'];
  	if(count($datos)>0)
  	{
  		return  $this->modelo->updateEmpresa($parametros['tabla'],$datos,$where);
  	}else{
  		return -2;
  	}
  }

  function guardar_credencial($parametros)
  {

  	$usuario= $_SESSION['INICIO']['NO_CONCURENTE'];
		$tabla= $_SESSION['INICIO']['NO_CONCURENTE_TABLA'];
		$campo= $_SESSION['INICIO']['NO_CONCURENTE_TABLA_ID'];

  	$campos = $this->modelo->credenciales_no_concurentes_campos();
  	if(count($campos)>0)
  	{
  		$datos[0]['campo'] = $campos[0]['pass'];
  		$datos[0]['dato'] = $this->pagina->enciptar_clave($parametros['pass']);
  	}

		$where[0]['campo'] = $campo;
		$where[0]['dato'] = $usuario;
		if(count( $datos))
		{
  		return  $this->modelo->updateEmpresa($tabla,$datos,$where);
  	}else{
  		return -2;
  	}
  }


}
?>