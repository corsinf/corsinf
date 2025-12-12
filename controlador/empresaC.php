<?php
require_once('../modelo/empresaM.php');
require_once('../modelo/licenciasM.php');
require_once('../modelo/loginM.php');
require_once('../lib/phpmailer/enviar_emails.php');
require_once('../db/codigos_globales.php');
if(isset($_SESSION['INICIO']))
{	
  @session_start();
} 
/**
 * 
 */
$controlador = new empresaC();
if(isset($_GET['lista_licencias']))
{
	echo json_encode($controlador->lista_licencias());
}
if(isset($_GET['empresa_dato']))
{
	echo json_encode($controlador->empresa_dato());
}
if(isset($_GET['cargar_imagen']))
{
   echo json_encode($controlador->guardar_foto($_FILES,$_POST));
}
if(isset($_GET['cargar_certi']))
{
   echo json_encode($controlador->guardar_certi($_FILES,$_POST));
}
// if(isset($_GET['buscar']))
// {
// 	echo json_encode($controlador->buscar_colores($_POST['buscar']));
// }
if(isset($_GET['insertar']))
{
	echo json_encode($controlador->insertar_editar($_POST['parametros']));
}
if(isset($_GET['tipo_usuario']))
{
	echo json_encode($controlador->tipo_usuario());
}
if(isset($_GET['eli_certi']))
{
	echo json_encode($controlador->eliminar_certificados());
}
if(isset($_GET['probar_conexion_dir']))
{
	echo json_encode($controlador->probar_conexion_dir($_POST['parametros']));
}

if(isset($_GET['probar_conexion_email']))
{
	echo json_encode($controlador->probar_conexion_email($_POST['parametros']));
}

if(isset($_GET['actualizar_empresa']))
{
	echo json_encode($controlador->actualizar_empresa());
}

class empresaC
{
	private $modelo;
	private $email;
	private $cod_global;
	private $login;
	function __construct()
	{
			$this->modelo = new empresaM();
			$this->cod_global = new codigos_globales();
			$this->email = new enviar_emails();
			$this->login = new loginM();
	}

	function empresa_dato()
	{
		$id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
		$datos = $this->modelo->datos_empresa($id_empresa);
		return $datos;
	}
	function guardar_foto($file,$post)
	 {
	 	// print_r($file);print_r($post);die();
	    $ruta='../img/empresa/';//ruta carpeta donde queremos copiar las imágenes
	    if (!file_exists($ruta)) {
	       mkdir($ruta, 0777, true);
	    }
	    if($this->validar_formato_img($file)==1)
	    {
	         $uploadfile_temporal=$file['file_img']['tmp_name'];
	         $tipo = explode('/', $file['file_img']['type']);
	         $nombre = $post['txt_nom_img'].'.'.$tipo[1];	        
	         $nuevo_nom=$ruta.$nombre;
	         if (is_uploaded_file($uploadfile_temporal))
	         {
	           move_uploaded_file($uploadfile_temporal,$nuevo_nom);
	          
	              $datosI[0]['campo']='Logo';
	              $datosI[0]['dato'] = $nuevo_nom;
	              $where[0]['campo'] = 'Id_empresa';
	              $where[0]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];
	              $base = $this->modelo->editar('EMPRESAS',$datosI,$where);
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
  function guardar_certi($file,$post)
	 {
	    $ruta='../comprobantes/certificados/';//ruta carpeta donde queremos copiar las imágenes
	    if (!file_exists($ruta)) {
	       mkdir($ruta, 0777, true);
	    }

	    // print_r($file);print_r($post);die();

	    if($this->validar_formato_certi($file)==1)
	    {
	         $uploadfile_temporal=$file['file_certificado']['tmp_name'];
	         $tipo = explode('/', $file['file_certificado']['type']);
	         $nombre = $file['file_certificado']['name'];      
	         $nuevo_nom=$ruta.$nombre;
	         if (is_uploaded_file($uploadfile_temporal))
	         {
	           move_uploaded_file($uploadfile_temporal,$nuevo_nom);
	          
	              $datosI[0]['campo']='Ruta_Certificado';
	              $datosI[0]['dato'] = $nombre;
	              $datosI[1]['campo']='Clave_Certificado';
	              $datosI[1]['dato'] = $post['txt_clave_cer'];
	              $where[0]['campo'] = 'id_empresa';
	              $where[0]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];
	              $base = $this->modelo->editar('empresa',$datosI,$where);
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

  function validar_formato_certi($file)
  {
    switch ($file['file_certificado']['type']) {
      case 'application/x-pkcs12':
         return 1;
        break;      
      default:
        return -1;
        break;
    }

  }

  function insertar_editar($parametros)
  {
  	// print_r($parametros);die();
  	$conta=0;
  	$datos[0]['campo'] ='Razon_Social';
	$datos[0]['dato']= $parametros['raz'];
	$datos[1]['campo'] = 'Nombre_Comercial';
	$datos[1]['dato']= $parametros['nom'];
	$datos[2]['campo'] = 'RUC';
	$datos[2]['dato']= $parametros['ci'];
	$datos[3]['campo'] = 'Direccion';
	$datos[3]['dato']= $parametros['dir'];
	$datos[4]['campo'] = 'telefono';
	$datos[4]['dato']= $parametros['tel'];
	$datos[5]['campo'] = 'email';
	$datos[5]['dato']= $parametros['ema'];
	$datos[6]['campo'] = 'Ip_host';
	$datos[6]['dato']= $parametros['dbhost'];
	$datos[7]['campo'] = 'Base_datos';
	$datos[7]['dato']= $parametros['db'];		
	$datos[8]['campo'] = 'Tipo_base';
	$datos[8]['dato']= 'SQLSERVER';		
	$datos[9]['campo'] = 'Usuario_db';
	$datos[9]['dato']= $parametros['dbusuario'];		
	$datos[10]['campo'] = 'Password_db';
	$datos[10]['dato']= $parametros['dbpass'];		
	$datos[11]['campo'] = 'Puerto_db';
	$datos[11]['dato']= $parametros['dbpuerto'];		
	$datos[12]['campo'] = 'Ambiente';
	$datos[12]['dato']= $parametros['Ambi'];		
	$datos[13]['campo'] = 'Periodo';
	$datos[13]['dato']= '.';
	// if($parametros['conta']==1){$conta = 1;}
	// $datos[14]['campo'] = 'obligadoContabilidad';
	// $datos[14]['dato']= $conta;
	$datos[15]['campo'] = 'valor_iva';
	$datos[15]['dato']= $parametros['iva'];
	$datos[16]['campo'] = 'smtp_host';
	$datos[16]['dato']= $parametros['host'];		
	$datos[17]['campo'] = 'smtp_usuario';
	$datos[17]['dato']= $parametros['usu'];
	$datos[18]['campo'] = 'smtp_pass';
	$datos[18]['dato']= $parametros['pass'];
	$datos[19]['campo'] = 'smtp_secure';
	$datos[19]['dato']= $parametros['secure'];
	$datos[20]['campo'] = 'smtp_port';
	$datos[20]['dato']= $parametros['puesto'];

	$datos[21]['campo'] = 'facturacion_electronica';
	$datos[21]['dato']= $parametros['fact'];
	// $datos[22]['campo'] = 'procesar_automatico';
	// $datos[22]['dato']= $parametros['proce'];
	// $datos[23]['campo'] = 'encargado_envios';
	// $datos[23]['dato']= $parametros['responsable_envios'];

	$datos[24]['campo'] = 'ip_directory';
	$datos[24]['dato'] = $parametros['ip_dir'];
	$datos[25]['campo'] = 'puerto_directory';
	$datos[25]['dato'] = $parametros['puerto_dir'];
	$datos[26]['campo'] = 'basedn_directory';
	$datos[26]['dato'] = $parametros['base_dir'];
	$datos[27]['campo'] = 'usuario_directory';
	$datos[27]['dato'] = $parametros['usu_dir'];
	$datos[28]['campo'] = 'password_directory';
	$datos[28]['dato'] = $parametros['pass_dir'];
	$datos[29]['campo'] = 'dominio_directory';
	$datos[29]['dato'] = $parametros['dominio_dir'];
	
	$datos[30]['campo'] = 'titulo_pestania';
	$datos[30]['dato']= $parametros['titPes'];

	$datos[31]['campo'] = 'url_api_idukay';
	$datos[31]['dato']= $parametros['idukay_url'];
	$datos[32]['campo'] = 'token_idukay';
	$datos[32]['dato']= $parametros['idukay_token'];
	$datos[33]['campo'] = 'anio_lectivo_idukay';
	$datos[33]['dato']= $parametros['idukay_anio_lec'];

// print_r($datos);die();

	$where[0]['campo'] = 'id_empresa';
	$where[0]['dato']= $_SESSION['INICIO']['ID_EMPRESA'];
	 return  $this->modelo->editar('EMPRESAS',$datos,$where);
  }

  function tipo_usuario()
  {
  	$datos = $this->modelo->tipo_usuario();
  	$op='<optio value="">Seleccione</option>';
  	foreach ($datos as $key => $value) {
  		$op.='<option value="'.$value['id'].'">'.$value['detalle'].'</option>';
  	}
  	return $op;
  }

  function eliminar_certificados()
  {
  	$datos[0]['campo'] = 'Clave_Certificado';
	  $datos[0]['dato']= '';
	  $datos[1]['campo'] = 'Ruta_Certificado';
	  $datos[1]['dato']= '';
	
		$where[0]['campo'] = 'id_empresa';
		$where[0]['dato']= $_SESSION['INICIO']['ID_EMPRESA'];
  	return  $this->modelo->editar('empresa',$datos,$where);
  }

  function probar_conexion_dir($parametros)
  {
			// Configuración de conexión
			$ldapconfig['host'] = 'ldap://'.$parametros['ip_dir'];  // Servidor de Active Directory
			$ldapconfig['port'] = $parametros['puerto_dir'];  // Puerto LDAP predeterminado
			$ldapconfig['basedn'] = 'DC=devcorsinf,DC=local';  // Base DN de tu dominio

			// Nombre de usuario y contraseña de prueba
			$username = $parametros['usu_dir'];
			$password = $parametros['pass_dir'];

			// Intentar la conexión
			$ldapconn = ldap_connect($ldapconfig['host'], $ldapconfig['port']);

			if ($ldapconn) {
			    // Configurar opciones de LDAP
			    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
			    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

			    // Intentar la autenticación
			    $ldapbind = @ldap_bind($ldapconn, "$username@".$parametros['dominio_dir'], $password);

			    if ($ldapbind) {
			    	return 1;
			    } else {
			    	return -1;
			    }

			    // Cerrar la conexión LDAP
			    ldap_close($ldapconn);
			}
  }

  function probar_conexion_email($parametros)
  {
  	$to_correo = $parametros['email_prueba'];
  	$cuerpo_correo = 'Prueba';
  	$titulo_correo = 'Email prueba';
  	$res = $this->email->enviar_email_prueba($parametros,$to_correo,$cuerpo_correo,$titulo_correo,$correo_respaldo='soporte@corsinf.com',$archivos=false,$nombre='Email envio',$HTML=false);

  	return $res;
  	// print_r($parametros);die();
  }

  function actualizar_empresa()
  {

		$licencias = $this->login->empresa_licencias_activas($_SESSION['INICIO']['ID_EMPRESA']);
  	$empresa = $this->modelo->datos_empresa($_SESSION['INICIO']['ID_EMPRESA']);
			// print_r($empresa);die();

			if($empresa[0]['Ip_host']==IP_MASTER)
			{
					$res = $this->cod_global->generar_primera_vez($empresa[0]['Base_datos'],$_SESSION['INICIO']['ID_EMPRESA']);
			 		// // print_r($res);die();
		 	 		// foreach ($licencias as $key => $value) {
			 		// print_r($licencias);die();
			 				$r = $this->cod_global->Copiar_estructura(false,$empresa[0]['Base_datos']);
			 		// 		sleep(10);
			 		// 		// print_r($r);die();
			 		// }
		 	}else{

		 			$res = $this->cod_global->generar_primera_vez_terceros($empresa,$_SESSION['INICIO']['ID_EMPRESA']);
		 	 		foreach ($licencias as $key => $value) {
			 				$this->cod_global->Copiar_estructura($value['Id_Modulo'],$empresa[0]['Base_datos'],1,$empresa);
			 				sleep(10);
			 		}
		 		// print_r($empresa);die();
		 	}

		 	return 1;

  }

}
?>