<?php 
include('../modelo/ingresar_procesoM.php');
include('../lib/phpmailer/enviar_emails.php');
require_once('../db/codigos_globales.php');
/**
 * 
 */
$controlador = new ingresar_procesoC();
if(isset($_GET['lineas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_lineas($parametros));
}
if(isset($_GET['solicitud_pdf']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->solicitud_pdf($parametros));
}
if(isset($_GET['observacion_salida']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->observacion_salida($parametros));
}
if(isset($_GET['observacion_entrada']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->observacion_entrada($parametros));
}
if(isset($_GET['notificacion_broker']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->notificacion_broker($parametros));
}
if(isset($_GET['update_step']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->update_step($parametros));
}
if(isset($_GET['finalizar_proceso']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->finalizar_proceso($parametros));
}
if(isset($_GET['enviar_correo']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->enviar_correo($parametros));
}

if(isset($_GET['veri_salida']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->veri_salida($parametros));
}

if(isset($_GET['veri_entrada']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->veri_entrada($parametros));
}

class ingresar_procesoC
{
	private $modelo;
	private $cod_global;
	private $email;
	
	function __construct()
	{
		$this->modelo = new ingresar_procesoM();
		$this->cod_global = new codigos_globales();
		$this->email = new enviar_emails();
		
	}

	function cargar_lineas($parametros)
	{
		$datos = $this->modelo->cargar_lineas($parametros['id']);
		return $datos;
	}
	function solicitud_pdf($parametros)
	{
		// print_r($parametros);die();
		$id = $parametros['id'];
		$datos = $this->modelo->solicitud_pdf($id);
		return $datos;
		// print_r($datos);die();

	}
	function observacion_salida($parametros)
	{
		// print_r($parametros);die();

		$datos[0]['campo'] = 'observacion_salida';
		$datos[0]['dato'] = $parametros['obs'];

		$where[0]['campo'] = 'id_linea_salida';
		$where[0]['dato'] = $parametros['id'];
		return $this->modelo->editar("ac_lineas_solicitud",$datos,$where);
	}

	function observacion_entrada($parametros)
	{
		// print_r($parametros);die();

		$datos[0]['campo'] = 'observacion_entrada';
		$datos[0]['dato'] = $parametros['obs'];

		$where[0]['campo'] = 'id_linea_salida';
		$where[0]['dato'] = $parametros['id'];
		return $this->modelo->editar("ac_lineas_solicitud",$datos,$where);
	}


	function notificacion_broker($parametros)
	{
		// print_r($parametros);die();

		if($parametros['ant']==0 && $parametros['rbl']==true)
		{
			$datos[0]['campo'] = 'fecha_notificacion_broker';
			$datos[0]['dato'] = date('Y-m-d');
			$datos[1]['campo'] = 'paso';
			$datos[1]['dato'] = 1;


			$where[0]['campo'] = 'id_solicitud';
			$where[0]['dato'] = $parametros['id'];
			return $this->modelo->editar("ac_solicitud_salida",$datos,$where);
		}
	}
	function update_step($parametros)
	{
		// print_r($parametros);die();

		if($parametros['ant']==0)
		{
			$datos[0]['campo'] = 'paso';
			$datos[0]['dato'] = $parametros['step']+1;
			$datos[1]['campo'] = 'fecha_update';
			$datos[1]['dato'] = date('Y-m-d');


			$where[0]['campo'] = 'id_solicitud';
			$where[0]['dato'] = $parametros['id'];
			return $this->modelo->editar("ac_solicitud_salida",$datos,$where);
		}
	}

	function finalizar_proceso($parametros)
	{
		$datos[0]['campo'] = 'estado';
		$datos[0]['dato'] = 1;
		$datos[1]['campo'] = 'fecha_update';
		$datos[1]['dato'] = date('Y-m-d');

		$where[0]['campo'] = 'id_solicitud';
		$where[0]['dato'] = $parametros['id'];
		return $this->modelo->editar("ac_solicitud_salida",$datos,$where);
	}
	function enviar_correo($parametros)
	{
		// print_r($parametros);die();
		$to_correo = $parametros['to'];
		$titulo_correo = $parametros['sub'];
		$cuerpo_correo = $parametros['men'].$parametros['tbl'];

		return $this->email->enviar_email($to_correo,$cuerpo_correo,$titulo_correo,$correo_respaldo='soporte@corsinf.com',$archivos=false,$titulo_correo,true);
	}
	function veri_salida($parametros)
	{

		$datos[0]['campo'] = 'salida_verificada';
		$datos[0]['dato'] = 1;
		$datos[1]['campo'] = 'salida_verificada_por';
		$datos[1]['dato'] = $_SESSION['INICIO']['USUARIO'];

		$where[0]['campo'] = 'id_solicitud';
		$where[0]['dato'] = $parametros['id'];
		return $this->modelo->editar("ac_solicitud_salida",$datos,$where);

	}
	function veri_entrada($parametros)
	{

		$datos[0]['campo'] = 'entrada_verificada';
		$datos[0]['dato'] = 1;
		$datos[1]['campo'] = 'entrada_verificada_por';
		$datos[1]['dato'] = $_SESSION['INICIO']['USUARIO'];
		$datos[2]['campo'] = 'fecha_update';
		$datos[2]['dato'] = date('Y-m-d');

		$where[0]['campo'] = 'id_solicitud';
		$where[0]['dato'] = $parametros['id'];
		return $this->modelo->editar("ac_solicitud_salida",$datos,$where);

	}
	
}
?>