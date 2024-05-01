<?php 
require_once(dirname(__DIR__,2).'/modelo/ACTIVEDIR/activedirectoryM.php');

$controlado = new activeDirC();

if(isset($_GET['repositoy_active']))
{
	echo json_encode($controlado->repositoy_active());
}
if(isset($_GET['usuarios_directory']))
{
	echo json_encode($controlado->usuarios_directory());
}
if(isset($_GET['Asignar_usuarios']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlado->Asignar_usuarios($parametros));
}

/**
 * 
 */
class activeDirC
{
	private $modelo;
	private $ip;
	private $port;
	private $dc;
	private $usu;
	private $pass;
	private $dominio;	
	function __construct()
	{
		$this->modelo = new  activeDirM();
	}

	function repositoy_active()
	{
		$activo = 1;
		$empresa = $this->modelo->datos_empresa($_SESSION['INICIO']['ID_EMPRESA']);
		if(!count($empresa))
		{
			// no existe empresa
			return -3;
		}
		if($empresa[0]['ip_directory']=="" || $empresa[0]['ip_directory']==null)
		{
			$activo = 0;
		}			
		if($empresa[0]['puerto_directory']=="" || $empresa[0]['puerto_directory']==null)
		{
			$activo = 0;
		}
		if($empresa[0]['basedn_directory']=="" || $empresa[0]['basedn_directory']==null)
		{
			$activo = 0;
		}
		if($empresa[0]['usuario_directory']=="" || $empresa[0]['usuario_directory']==null)
		{
			$activo = 0;
		}
		if($empresa[0]['password_directory']=="" || $empresa[0]['password_directory']==null)
		{
			$activo = 0;
		}
		if($empresa[0]['dominio_directory']=="" || $empresa[0]['dominio_directory']==null)
		{
			$activo = 0;
		}

		return $activo;

	}

	function conexion_ldapcom()
	{
		$empresa = $this->modelo->datos_empresa($_SESSION['INICIO']['ID_EMPRESA']);
		if(!count($empresa))
		{
			// no existe empresa
			return -3;
		}
		$this->ip = $empresa[0]['ip_directory'];
		$this->port = $empresa[0]['puerto_directory'];
		$this->dc = $empresa[0]['basedn_directory'];
		$this->usu = $empresa[0]['usuario_directory'];
		$this->pass = $empresa[0]['password_directory'];
		$this->dominio = $empresa[0]['dominio_directory'];
	}

	function usuarios_directory()
	{
		$tr = "";
		$grupo1 = $this->cargar_grupo();
		// print_r($grupo1);die();
		foreach ($grupo1 as $key => $value) {

			$usuT = $this->cargar_usuario_x_grupo($value['text']);
	        if(count($usuT)>0)
	        {   
				$tr.= '<div class="accordion-item">
	                <h2 class="accordion-header" id="h_'.$value['id'].'">
	                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c_'.$value['id'].'" aria-expanded="false" aria-controls="c_'.$value['text'].'">
	                  '.$value['text'].'
	                  </button>
	                </h2>
	                <div id="c_'.$value['id'].'" class="accordion-collapse collapse" aria-labelledby="h_'.$value['id'].'" data-bs-parent="#accordionExample">
	                    <div class="accordion-body">	                    

	                    <form id="form_'.$value['id'].'"><ul>';
	                    $usu = $this->cargar_usuario_x_grupo($value['text']);
	                    foreach ($usu as $key2 => $value2) 
	                    {

	                    	if($value2['email']!='')
	                    	{
	                    		if($key2==0)
		                    	{ $tr.='<div class="row">
			                    		<div class="col-sm-12 text-end">
			                   				<button type="button" onclick="modal_tipo_usu(\'form_'.$value['id'].'\')" class="btn btn btn-outline-secondary btn-sm">Asignar a <code><b id="lbl_cant_usu_'.$value['id'].'">todos</b></code></button>	 
			                   			</div>                   
		                    		</div>'; 
		                    	}

		                    	$usu = $this->modelo->lista_usuarios_simple(false,false,false,$value2['email']);
		                    	if(count($usu)==0)
		                    	{
	                    			$tr.='<li><label onclick="calcular_usu(\'form_'.$value['id'].'\')"><input type="checkbox" value="'.$value2['nombre'].'-'.$value2['email'].'" />  '.$value2['nombre'].' / '.$value2['email'].'</label></li>';
	                    		}else
	                    		{
	                    			$tr.='<li><label>'.$value2['nombre'].' </label><span class="badge bg-success">Asignado</span></li>';
	                    		}
	                    	}else
	                    	{

	                    		$tr.='<li><label>'.$value2['nombre'].' </label><code> Bloqueado por falta de email</code></li>';
	                    	}
	                    }
	                    $tr.='</ul></form></div>
	                </div>
	              </div>';
          	}

			// print_r($value);
			// $usu = $this->cargar_usuario_x_grupo($value['text']);		
			// print_r($usu);die();

		}
		// print_r($usu);die();
		// print_r($grupo);die();

		return $tr;
	}

	function cargar_grupo()
	{
		$grupos = array();
		$this->conexion_ldapcom();
		$ldapconfig['host'] = 'ldap://'.$this->ip;  // Servidor de Active Directory
		$ldapconfig['port'] = $this->port;  // Puerto LDAP predeterminado
		$ldapconfig['basedn'] = $this->dc;  // Base DN de tu dominio

		// Nombre de usuario y contraseña de prueba
		$username = $this->usu;
		$password = $this->pass;

		// Intentar la conexión
		  $ldapconn = ldap_connect($ldapconfig['host'], $ldapconfig['port']);

		if ($ldapconn) 
		{
			    // Configurar opciones de LDAP
			    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
			    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

			    // Intentar la autenticación
			    $ldapbind = @ldap_bind($ldapconn, "$username@".$this->dominio, $password);

			    if ($ldapbind) {
			        // echo "LDAP bind successful...";
			        // Aquí puedes realizar operaciones LDAP adicionales, como buscar usuarios, etc.

			        // Realizar búsqueda de directorios
			        $base_dn = $this->dc;//$ldapconfig['basedn'];  // Base DN donde buscar directorios
			        $filter = '(objectClass=organizationalUnit)';  // Filtro para buscar todas las unidades organizativas
			        $attributes = array('ou');  // Atributos a recuperar

			        $result = ldap_search($ldapconn, $base_dn, $filter, $attributes);
			        $entries = ldap_get_entries($ldapconn, $result);


			        // Mostrar resultados
			        for ($i = 0; $i < $entries['count']; $i++) {
			            if (isset($entries[$i]['ou'][0])) {
			            	$grupos[] =  array('id' => str_replace(" ","_",$entries[$i]['ou'][0]),'text' =>$entries[$i]['ou'][0]);
			            }
			        }
			        // print_r('hola');die();
			    } else {
			    	return -1;
			    }
			    ldap_close($ldapconn);

			        // print_r('hola');die();

		}
		return $grupos;
		
	}

	function cargar_usuario_x_grupo($grupo)
	{
		$usuarios = array();
		$this->conexion_ldapcom();
		$ldapconfig['host'] = 'ldap://'.$this->ip;  // Servidor de Active Directory
		$ldapconfig['port'] = $this->port;  // Puerto LDAP predeterminado
		$ldapconfig['basedn'] = $this->dc;  // Base DN de tu dominio

		// Nombre de usuario y contraseña de prueba
		$username = $this->usu;
		$password = $this->pass;

		// Intentar la conexión
		  $ldapconn = ldap_connect($ldapconfig['host'], $ldapconfig['port']);

		if ($ldapconn) 
		{
			    // Configurar opciones de LDAP
			    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
			    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

			    // Intentar la autenticación
			    $ldapbind = @ldap_bind($ldapconn, "$username@".$this->dominio, $password);

			    if ($ldapbind) {
			        // echo "LDAP bind successful...";

			        // Realizar búsqueda de directorios
			        $base_dn = 'OU='.$grupo.',' . $ldapconfig['basedn'];  // Base DN donde buscar usuarios
			        $filter = '(objectClass=USER)';  // Filtro para buscar todos los usuarios
			        $attributes = array('cn', 'mail');  // Atributos a recuperar

			        $result = ldap_search($ldapconn, $base_dn, $filter, $attributes);
			        $entries = ldap_get_entries($ldapconn, $result);

			        // Mostrar resultados
			        for ($i = 0; $i < $entries['count']; $i++) {
			        	if(isset($entries[$i]['cn'][0]) && isset($entries[$i]['mail'][0]))
			        	{
			        		$usuarios[] = array('nombre' => $entries[$i]['cn'][0],'email'=>$entries[$i]['mail'][0]);
			        	}else
			        	{
			        		$usuarios[] = array('nombre' => $entries[$i]['cn'][0],'email'=>"");
			        	}
			            
			        }
			    } else {
			    	return -1;
			    }
			    ldap_close($ldapconn);

		}
		$usuarios = array_filter($usuarios);
		return $usuarios;
	}

	function Asignar_usuarios($parametros)
	{
		$tipo_usuario = $parametros['tipo'];
		if(isset($parametros['usuarios']))
		{
			$usuarios = array();
			$list_usuarios = $parametros['usuarios'];
			foreach ($list_usuarios as $key => $value) {
				$data = explode('-',$value);
				$usuarios[] = array('nombre' =>$data[0],'email'=>$data[1]);
			}

		}else{
			$usuarios = $this->cargar_usuario_x_grupo($parametros['grupo']);
		}
		foreach ($usuarios as $key => $value) {

			$name = explode(' ', $value['nombre']);
			$apellidos = '';
			switch (count($name)) {
				case 1:
					$nombres = $name[0];
					break;
				case 2:
					$nombres = $name[0];
					$apellidos = $name[1];
					break;
				case 3:
					$nombres = $name[0].' '.$name[1];
					$apellidos = $name[2];
					break;
				case 4:
					$nombres = $name[0].' '.$name[1];
					$apellidos = $name[2].' '.$name[3];
					break;				
			}
			// print_r($value);die();


		    $usu = $this->modelo->lista_usuarios_simple(false,false,false,$value['email']);
		    if(count($usu)==0)
		    {
				$datos[0]['campo']='nombres';
			    $datos[0]['dato']=$nombres;
			    $datos[1]['campo']='apellidos';
			    $datos[1]['dato']=$apellidos;	
			    $datos[2]['campo']='email';
			    $datos[2]['dato']=$value['email'];	
			    $datos[3]['campo']='perfil';
			    $datos[3]['dato']=$tipo_usuario;
			}
		    // Guarda los datos del usuario en master
		    $this->modelo->guardar($datos,'USUARIOS'); 
		    //agregar en acceso empresas de lista empresas.
		    if(count($usu)>0)
		    {
			    $datosA[0]['campo']='Id_usuario';
			    $datosA[0]['dato']=$usu[0]['id'];
			    $datosA[1]['campo']='Id_Empresa';
			    $datosA[1]['dato']=$_SESSION['INICIO']['ID_EMPRESA'];	
			    $datosA[2]['campo']='Id_Tipo_usuario';
			    $datosA[2]['dato']=$tipo_usuario;	
			    // Guarda los datos del usuario en master
			    $this->modelo->guardar($datosA,'ACCESOS_EMPRESA'); 
			}

		}

		return 1;
	}
}

?>