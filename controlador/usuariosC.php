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


	
	// function cargar_articulos_lista($parametros)
	// {
	// 	   $producto = explode('-', $parametros['ddl_producto_modal_ing']);
	// 	    $datos[0]['campo']='id_producto';
	// 	    $datos[0]['dato']=$producto[0];
	// 	    $datos[1]['campo']='cantidad';
	// 	    $datos[1]['dato']=$parametros['txt_canti_modal_ing'];
	// 	    $datos[2]['campo']='precio';
	// 	    $datos[2]['dato']=$parametros['txt_precio_modal_ing'];
	// 	    $datos[3]['campo']='iva';
	// 	    $datos[3]['dato']=$parametros['txt_iva_modal_ing'];
	// 	    $datos[4]['campo']='total';
	// 	    $datos[4]['dato']=$parametros['txt_total_modal_ing'];
	// 	    $datos[5]['campo']='num_orden';
	// 	    $datos[5]['dato']=$parametros['txt_orden_modal_ing'];
	// 	    $datos[6]['campo']='id_usuario';
	// 	    $datos[6]['dato']=$_SESSION['INICIO']['ID'];
	// 	    $datos[7]['campo']='id_bodegas';
	// 	    $datos[7]['dato']=$parametros['ddl_bodega'];
	// 	    $rep =  $this->modelo->guardar($datos,'ASIENTO_K');
	// 	   return $rep;

	// }

	function usuarios_all()
	{
		$datos = $this->modelo->lista_usuarios();
		return $datos;
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
                  <h5 class="mb-0 mt-5">'.$value['nombres'].' '.$value['ape'].'</h5>
                  <p class="mb-3">'.$value['tipo'].'</p>
                  <div class="list-inline contacts-social mt-3 mb-3"> <a href="javascript:;" class="list-inline-item bg-facebook text-white border-0"><i class="bx bxl-facebook"></i></a>
                    <a href="javascript:;" class="list-inline-item bg-twitter text-white border-0"><i class="bx bxl-twitter"></i></a>
                   <!-- <a href="javascript:;" class="list-inline-item bg-google text-white border-0"><i class="bx bxl-google"></i></a> -->
                    <a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0"><i class="bx bxl-linkedin"></i></a>
                  </div>
                  <div class="d-grid"> <a href="#" class="btn btn-outline-primary radius-15">Contact Me</a>
                  </div>
                  <div class="d-grid"><a href="detalle_usuario.php?usuario='.$value['id'].'" class="btn btn-outline-primary radius-15"> Ver Perfil </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          '; 

						// $tabla.='<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
            //           <div class="card bg-light">
            //             <div class="card-header text-muted border-bottom-0">
            //               CI: '.$value['ci'].'
            //             </div>
            //             <div class="card-body pt-0">
            //               <div class="row">
            //                 <div class="col-7">
            //                   <h2 class="lead"><b>'.$value['nombres'].' '.$value['ape'].'</b></h2>
            //                   <p class="text-muted text-sm"><b>Tipo de usuario: </b> '.$value['tipo'].' </p>
            //                   <ul class="ml-4 mb-0 fa-ul text-muted">
            //                     <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Direccion: '.$value['dir'].'</li>
            //                     <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Telefono : '.$value['tel'].'</li>
            //                     <li class="small"><span class="fa-li"><i class="fas fa-lg fa-envelope"></i></span> Email : '.$value['email'].'</li>
            //                   </ul>
            //                 </div>
            //                 <div class="col-5 text-center">
            //                   <img src="'.$value['foto'].'" alt="" class="img-circle img-fluid" style="width:100%;height:112px">
            //                 </div>
            //               </div>
            //             </div>
            //             <div class="card-footer">
            //               <div class="text-right">                            
            //                 <a href="detalle_usuario.php?usuario='.$value['id'].'" class="btn btn-sm btn-primary">
            //                   <i class="fas fa-user"></i> Ver Perfil
            //                 </a>
            //               </div>
            //             </div>
            //           </div>
            //         </div>';
						
					}
				}else
				{


						$tabla.='
						<div class="col">
            <div class="card radius-15">
              <div class="card-body text-center">
                <div class="p-4 border radius-15">
                  <img src="../'.$value['foto'].'" width="110" height="110" class="rounded-circle shadow" alt="">
                  <h5 class="mb-0 mt-5">'.$value['nombres'].' '.$value['ape'].'</h5>
                  <p class="mb-3">'.$value['tipo'].'</p>
                  <div class="list-inline contacts-social mt-3 mb-3"> <a href="javascript:;" class="list-inline-item bg-facebook text-white border-0"><i class="bx bxl-facebook"></i></a>
                    <a href="javascript:;" class="list-inline-item bg-twitter text-white border-0"><i class="bx bxl-twitter"></i></a>
                   <!-- <a href="javascript:;" class="list-inline-item bg-google text-white border-0"><i class="bx bxl-google"></i></a> -->
                    <a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0"><i class="bx bxl-linkedin"></i></a>
                  </div>
                  <div class="d-grid"> <a href="#" class="btn btn-outline-primary radius-15">Contactame</a>
                  </div>
                  <div class="d-grid"><a href="detalle_usuario.php?usuario='.$value['id'].'" class="btn btn-outline-primary radius-15">Ver Perfil</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          '; 

					// $tabla.='<div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
          //             <div class="card bg-light">
          //               <div class="card-header text-muted border-bottom-0">
          //                 CI: '.$value['ci'].'
          //               </div>
          //               <div class="card-body pt-0">
          //                 <div class="row">
          //                   <div class="col-7">
          //                     <h2 class="lead"><b>'.$value['nombres'].' '.$value['ape'].'</b></h2>
          //                     <p class="text-muted text-sm"><b>Tipo de usuario: </b> '.$value['tipo'].' </p>
          //                     <ul class="ml-4 mb-0 fa-ul text-muted">
          //                       <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Direccion: '.$value['dir'].'</li>
          //                       <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Telefono : '.$value['tel'].'</li>
          //                       <li class="small"><span class="fa-li"><i class="fas fa-lg fa-envelope"></i></span> Email : '.$value['email'].'</li>
          //                     </ul>
          //                   </div>
          //                   <div class="col-5 text-center">
          //                     <img src="'.$value['foto'].'" alt="" class="img-circle img-fluid" style="width:100%;height:112px">
          //                   </div>
          //                 </div>
          //               </div>
          //               <div class="card-footer">
          //                 <div class="text-right">                            
          //                   <a href="detalle_usuario.php?usuario='.$value['id'].'" class="btn btn-sm btn-primary">
          //                     <i class="fas fa-user"></i> Ver Perfil
          //                   </a>
          //                 </div>
          //               </div>
          //             </div>
          //           </div>';
					
				}
		}
		// $botones[0] = array('boton'=>'Editar','icono'=>'<i class="fas fa-pen nav-icon"></i>','tipo'=>'primary','id'=>'id');
		// $botones[1] = array('boton'=>'Eliminar','icono'=>'<i class="fas fa-trash nav-icon"></i>','tipo'=>'danger','id'=>'id');
		// $ocultar = array('id','idt','maestro','dir','tipo','pass','estado','nom');
		// $cabecera = array('CI','Nombre','Apellido','telefono','Email');
		// $enlace[0] = array('posicion'=>2,'link'=>'detalle_usuario.php','get'=>array('nombre'=>'usuario','valor'=>'id'));
		
		// $tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones=false,false,$ocultar,false,false,$enlace);

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

	}

	function add_usuario($parametros)
	{
		if($parametros['txt_usuario_update']=='')
		{

		// print_r($parametros);die();
				$datos[0]['campo']='password';
		    $datos[0]['dato']=$parametros['txt_pass'];
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

		    $this->modelo->guardar($datos,'USUARIOS'); 
		    $usuario =  $this->modelo->lista_usuarios_simple($id=false,$query=false,$ci=$parametros['txt_ci'],$email=$parametros['txt_emial']);

		     $datosT[0]['campo']='ID_USUARIO';
		     $datosT[0]['dato']=$usuario[0]['id'];
		     $datosT[1]['campo']='ID_TIPO_USUARIO';
		     $datosT[1]['dato']=$parametros['ddl_tipo_usuario'];	
		     $this->modelo->guardar($datosT,'USUARIO_TIPO_USUARIO'); 


		     return $usuario[0]['id'];

		   
		    
		}else
		{
			  $perfil = $this->modelo->existe_usuario_perfil_datos($tipo=false,$parametros['txt_usuario_update']);

			  // print_r($perfil);die();
		    // $datos[0]['campo']='nick_usuario';
		    // $datos[0]['dato']=$parametros['txt_nick'];
		    $datos[0]['campo']='password';
		    $datos[0]['dato']=$parametros['txt_pass'];
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

		    $datosT[0]['campo']='ID_TIPO_USUARIO';
		    $datosT[0]['dato']=$parametros['ddl_tipo_usuario'];
		    $whereT[0]['campo']='ID';
		    $whereT[0]['dato'] = $perfil[0]['ID'];

		    // print_r($datos);die();
		    $this->modelo->update('USUARIO_TIPO_USUARIO',$datosT,$whereT);
		    return $this->modelo->update('USUARIOS',$datos,$where);
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
		$datos  = $this->modelo->lista_usuarios($parametros['id'],$parametros['query']);
		return $datos;
	}


	function eliminar_tipo($id)
	{
		$resp = $this->modelo->eliminar_tipo($id);
		return $resp;

	}

	function usuario_estado($id)
	{
		 $datos[0]['campo']='estado_usuario';
		 $datos[0]['dato']='I';


		 $where[0]['campo']='id_usuarios';
		 $where[0]['dato'] = $id;
		 return $this->modelo->update('usuarios',$datos,$where);

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
		 $datos[0]['dato']= $parametros['pass'];


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


}
?>