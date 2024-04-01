<?php 
include('../modelo/nueva_empresaM.php');
require_once('../db/codigos_globales.php');
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
	echo json_encode($controlador->insertar_editar($parametros,$file));
}



class nueva_empresaC
{
	private $modelo;
	private $cod_global;
	
	function __construct()
	{
		$this->modelo = new nueva_empresaM();
		$this->cod_global = new codigos_globales();
		
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
	function insertar_editar($parametros,$file)
	{
// print_r($file);die();
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
			$datos[4]['tipo'] ='STRING';
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

	
}
?>