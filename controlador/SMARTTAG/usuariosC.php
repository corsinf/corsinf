<?php 
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}
require_once(dirname(__DIR__, 2) .'/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) .'/modelo/tipo_usuarioM.php');
require_once(dirname(__DIR__, 2) .'/modelo/usuariosM.php');

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
		$this->pagina->registrar_pagina_creada('../vista/usuarios.php','Usuarios nuevos','3','estado');
	}


	function tipo_usuario($query)
	{
		$datos = $this->tipo->lista_tipo_usuario($query);
		$opciones = array();
		foreach ($datos as $key => $value) {
			$opciones[] = array('id'=>$value['id'],'text'=>$value['nombre']);		
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


	function usuarios($parametros)
	{
		$datos = $this->modelo->lista_usuarios($parametros['id'],$parametros['query']);
		// $botones[0] = array('boton'=>'Editar','icono'=>'<i class="fas fa-pen nav-icon"></i>','tipo'=>'primary','id'=>'id');
		$botones[1] = array('boton'=>'Eliminar','icono'=>'<i class="fas fa-trash nav-icon"></i>','tipo'=>'danger','id'=>'id');
		$ocultar = array('id','idt','maestro','dir','tipo','pass','email','estado');
		$cabecera = array('CI','Nombre','telefono','Nick');
		$enlace[0] = array('posicion'=>2,'link'=>'detalle_usuario.php','get'=>array('nombre'=>'usuario','valor'=>'id'));
		
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones=false,false,$ocultar,false,false,$enlace);

		return $tabla;
	}

	function ddl_usuarios2($parametros)
	{		
		$datos = $this->modelo->lista_usuarios($parametros['id'],$parametros['query']);
		foreach ($datos as $key => $value) {
			$rep[] = array('id'=>$value['id'],'text'=>$value['nom']);
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

	function add_usuario($parametros)
	{
		// print_r($parametros);die();
		if($parametros['txt_usuario_update']=='')
		{
			$datos[0]['campo']='nick_usuario';
		    $datos[0]['dato']=$parametros['txt_nick'];
		    $datos[1]['campo']='pass_usuario';
		    $datos[1]['dato']=$parametros['txt_pass'];
		    $datos[2]['campo']='nombre_usuario';
		    $datos[2]['dato']=$parametros['txt_nombre'];
		    $datos[3]['campo']='direccion_usuario';
		    $datos[3]['dato']=$parametros['txt_dir'];
		    $datos[4]['campo']='telefono_usuario';
		    $datos[4]['dato']=$parametros['txt_telefono'];
		    $datos[5]['campo']='email_usuario';
		    $datos[5]['dato']=$parametros['txt_emial'];
		    $datos[6]['campo']='id_tipo_usuario';
		    $datos[6]['dato']=$parametros['ddl_tipo_usuario'];
		    $datos[7]['campo']='ci_ruc_usuario';
		    $datos[7]['dato']=$parametros['txt_ci'];	
		    if(isset($parametros['rbl_maestro']))	 
		    {
		    	 $datos[8]['campo']='maestro';
		       $datos[8]['dato']=$parametros['rbl_maestro'];
		    }  
		    return $this->modelo->guardar($datos,'usuarios');
		    
		}else
		{
		    $datos[0]['campo']='nick_usuario';
		    $datos[0]['dato']=$parametros['txt_nick'];
		    $datos[1]['campo']='pass_usuario';
		    $datos[1]['dato']=$parametros['txt_pass'];
		    $datos[2]['campo']='nombre_usuario';
		    $datos[2]['dato']=$parametros['txt_nombre'];
		    $datos[3]['campo']='direccion_usuario';
		    $datos[3]['dato']=$parametros['txt_dir'];
		    $datos[4]['campo']='telefono_usuario';
		    $datos[4]['dato']=$parametros['txt_telefono'];
		    $datos[5]['campo']='email_usuario';
		    $datos[5]['dato']=$parametros['txt_emial'];
		    $datos[6]['campo']='id_tipo_usuario';
		    $datos[6]['dato']=$parametros['ddl_tipo_usuario'];
		    $datos[7]['campo']='ci_ruc_usuario';
		    $datos[7]['dato']=$parametros['txt_ci'];		     
		    $datos[8]['campo']='maestro';
		    $datos[8]['dato']=$parametros['rbl_maestro'];


		    $where[0]['campo']='id_usuario';
		    $where[0]['dato'] = $parametros['txt_usuario_update'];

		    // print_r($datos);die();
		    return $this->modelo->update('usuarios',$datos,$where);
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


		 $where[0]['campo']='id_usuario';
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

	

}
?>