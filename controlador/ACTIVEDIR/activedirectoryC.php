<?php 

require_once(dirname(__DIR__,2).'/modelo/ACTIVEDIR/activedirectoryM.php');
require_once(dirname(__DIR__,2).'/db/codigos_globales.php');


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
	private $cod_global;
	function __construct()
	{
		$this->modelo = new  activeDirM();
		$this->cod_global = new codigos_globales();

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

	function conexion_ldapcom($empresa =false)
	{
		if(!$empresa)
		{
			$empresa = $this->modelo->datos_empresa($_SESSION['INICIO']['ID_EMPRESA']);
		}
		if(count($empresa)==0)
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


		// print_r($this->ip);
		// print_r($this->dc);
		// print_r($this->usu);
		// print_r($this->pass);
		// print_r($this->dominio);
		// print_r($this->dc);
		// die();
	}

	function usuarios_directory()
	{
		$tr = "";
		$grupo1 = $this->cargar_grupo();
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
	                    // print_r($usu);
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
		                    	$tabla_NoConcu = $this->modelo->buscar_no_concurente_ligado();
		                    	$tabla_enco = 'Usuarios';
		                    	if(count($usu)==0)
		                    	{$tabla_enco = '';

			                    	//--------------------------- buscar en no concurentes -----------------------------//
			                    	foreach ($tabla_NoConcu as $key => $value3) {
			                    		$campos_correo = $this->modelo->bucar_campos_tabla($value3['Tabla'],'correo');
			                    		if(count($campos_correo)==0)
										{
											$campos_correo = $this->modelo->bucar_campos_tabla($value3['Tabla'],'email');
											if(count($campos_correo)==0)
											{
												$campos_correo = $this->modelo->bucar_campos_tabla($value3['Tabla'],'mail');
											}
										}

										//buscar select
											$val_select = '';
											foreach ($campos_correo as $key => $value4) {
												$val_select.=$value4['COLUMN_NAME']."='".$value2['email']."' AND ";
											}

											$val_select = substr($val_select, 0,-5);
											$sql = "SELECT * FROM ".$value3['Tabla']." WHERE ".$val_select;
											// print_r($sql);
											$res = $this->modelo->ejecutar_sql($sql,1);
											if(count($res)>0)
											{
												$usu = array('id'=>'d');
												$tabla_enco.=$value3['Tabla'].'/';
											
											}

											// print_r($usu);
											// print_r($tabla_enco);
											// die();
			                    	}
			                    	//-------------------------- fin buscar en no concurentes ------------------------------//
			                    }

		                    	// print_r($usu);die();
		                    	// print_r($tabla_NoConcu);die();

		                    	if(count($usu)==0)
		                    	{
		                    		// print_r(count($usu));die();
	                    			$tr.='<li><label onclick="calcular_usu(\'form_'.$value['id'].'\')"><input type="checkbox" value="'.$value2['nombre'].'-'.$value2['email'].'" />  '.$value2['nombre'].' / '.$value2['email'].'</label></li>';
	                    		}else
	                    		{

		                    		// print_r($tabla_enco); die();
	                    			$tr.='<li><label>'.$value2['nombre'].'/'.$value2['email'].' </label><span class="badge bg-success">Asignado / '.$tabla_enco.'</span></li>';
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
			        $attributes = array('cn', 'mail','objectguid');  // Atributos a recuperar

			        $result = ldap_search($ldapconn, $base_dn, $filter);
			        $entries = ldap_get_entries($ldapconn, $result);

// print_r('hola');
// print_r($entries);
			        // Mostrar resultados
			        for ($i = 0; $i < $entries['count']; $i++) {

			        	// print_r($entries['count']);
			        	if(isset($entries[$i]['cn'][0]) && isset($entries[$i]['mail'][0]) && isset($entries[$i]['objectguid'][0]))
			        	{
			        		$utf8_string = mb_convert_encoding($entries[$i]['objectguid'][0], 'UTF-8', 'UTF-16LE');
			        		$usuarios[] = array('nombre' => $entries[$i]['cn'][0],'email'=>$entries[$i]['mail'][0],'d'=>$utf8_string);
			        		// print_r($usuarios);
			        		// print_r($entries[$i]);
			        		// die();
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
		$msg = '';
		$result = 1;
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
			$value['tipo'] = $tipo_usuario;

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

			//verificar si esta el tipo de usuario en no concurente
			$existe_concurente = $this->modelo->buscar_no_concurente_ligado($tipo_usuario);

			if(count($existe_concurente)>0)
			{
				$campos_nombre = $this->modelo->bucar_campos_tabla($existe_concurente[0]['Tabla'],'nombre');
				$campos_correo = $this->modelo->bucar_campos_tabla($existe_concurente[0]['Tabla'],'correo');
				if(count($campos_correo)==0)
				{
					$campos_correo = $this->modelo->bucar_campos_tabla($existe_concurente[0]['Tabla'],'email');
					if(count($campos_correo)==0)
					{
						$campos_correo = $this->modelo->bucar_campos_tabla($existe_concurente[0]['Tabla'],'mail');
					}
				}
					
				$resp = $this->guardar_no_concurentes($existe_concurente[0]['Tabla'],$campos_nombre,$campos_correo,$value);
				if($resp['resp']==-1)
				{
					$msg.='Usuario '.$value['nombre'].'  existente en '.$existe_concurente[0]['Tabla'].'<br>';
					$result = 2;
				}

				//asigno a el usuario en la tabla de no_concurentes
						//valido que no exista en la atabla
						$id_tbl = $this->cod_global->id_tabla($existe_concurente[0]['Tabla']);

						// print_r($id_tbl);
						// print_r($resp);
						// die();

						if(count($resp['datos']))
						{
							$id = $resp['datos'][0][$id_tbl[0]['ID']];
							$usu = $this->modelo-> buscar_registr_noconcurente(false,$id,$existe_concurente[0]['Tabla'],$tipo_usuario);

							if(count($usu)==0)
						    {
								$datos[0]['campo']='Tabla';
							    $datos[0]['dato']=$existe_concurente[0]['Tabla'];
							    $datos[1]['campo']='Id_Empresa';
							    $datos[1]['dato']=$_SESSION['INICIO']['ID_EMPRESA'];	
							    $datos[2]['campo']='Id_usuario';
							    $datos[2]['dato']=$id;	
							    $datos[3]['campo']='tipo_perfil';
							    $datos[3]['dato']=$tipo_usuario;
							    $datos[4]['campo']='Campo_usuario';
							    $datos[4]['dato']=$existe_concurente[0]['Campo_usuario'];
							    $datos[5]['campo']='Campo_pass';
							    $datos[5]['dato']=$existe_concurente[0]['Campo_pass'];
							    $datos[6]['campo']='campo_img';
							    $datos[6]['dato']=$existe_concurente[0]['campo_img'];
							    // Guarda los datos del usuario en master
							    // print_r($datos);die();
							    $this->modelo->guardar($datos,'TABLAS_NOCONCURENTE'); 
							}
						}else
						{
							return -1;
						}

			}else
			{

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
				    // Guarda los datos del usuario en master
				    $this->modelo->guardar($datos,'USUARIOS'); 
				}
			    //agregar en acceso empresas de lista empresas.
			    $usu = $this->modelo->lista_usuarios_simple(false,false,false,$value['email']);
			    $acceso_usu = $this->modelo->existe_acceso_usuario_empresa($usu[0]['id']);
			    if(count($acceso_usu)==0)
			    {
				    $datosA[0]['campo']='Id_usuario';
				    $datosA[0]['dato']=$usu[0]['id'];
				    $datosA[1]['campo']='Id_Empresa';
				    $datosA[1]['dato']=$_SESSION['INICIO']['ID_EMPRESA'];	
				    $datosA[2]['campo']='Id_Tipo_usuario';
				    $datosA[2]['dato']=$tipo_usuario;	
				    // Guarda los datos del usuario en master
				  $res =  $this->modelo->guardar($datosA,'ACCESOS_EMPRESA'); 
				  // print_r($res);die();
				}
			}

		}

		return array('msg' => $msg,'resp'=>$result);
	}

	function guardar_no_concurentes($tabla,$campos_nombre,$campos_correo,$valores)
	{
		$camp = '';
		$vals = '';
		$val_select = '';
		foreach ($campos_nombre as $key => $value) {
				$camp.= $value['COLUMN_NAME'].',';
				$vals.="'".$valores['nombre']."',"; 
		}
		foreach ($campos_correo as $key => $value) {
			$camp.= $value['COLUMN_NAME'].',';
			$vals.="'".$valores['email']."',"; 
			$val_select.=$value['COLUMN_NAME']."='".$valores['email']."' AND ";
		}
		$camp = substr($camp, 0,-1);
		$vals = substr($vals, 0,-1);
		$val_select = substr($val_select, 0,-5);

		$sql = "SELECT * FROM ".$tabla." WHERE ".$val_select;
		// print_r($sql);die();
		$res = $this->modelo->ejecutar_sql($sql,1);
		if(count($res)==0)
		{
			// print_r('expression');die();
			$sql2 = 'INSERT INTO '.$tabla.' ('.$camp.',PERFIL) VALUES ('.$vals.','.$valores['tipo'].');';
			// print_r($sql2);die();
			$r =  $this->modelo->ejecutar_sql($sql2);
			// print_r($r);die();
			if($r==-1)
			{
				return array('resp'=>-2,'datos'=>array());
			}else
			{
				$res = $this->modelo->ejecutar_sql($sql,1);
				// print_r($res);die();
				return array('resp'=>1,'datos'=>$res);
			}

		}else
		{
			//existe en los registros
			return array('resp'=>-1,'datos'=>$res);
		}
		
	}

	function AutentificarUserActiveDir($username, $password,$empresa=false) 
	{

		$usuarios = array();
		$this->conexion_ldapcom($empresa);
		$ldapconfig['host'] = 'ldap://'.$this->ip;  // Servidor de Active Directory
		$ldapconfig['port'] = $this->port;  // Puerto LDAP predeterminado
		$ldapconfig['basedn'] = $this->dc;  // Base DN de tu dominio

		// Nombre de usuario y contraseña de prueba
		$usernameconfig = $this->usu;
		$passwordconfig = $this->pass;

		// print_r($username);die();

		// Intentar la conexión
		  $ldapconn = ldap_connect($ldapconfig['host'], $ldapconfig['port']);

		if ($ldapconn) 
		{
			    // Configurar opciones de LDAP
			    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
			    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

			    // Intentar la autenticación
			    $ldapbind = @ldap_bind($ldapconn, "$usernameconfig@".$this->dominio, $passwordconfig);

			    if ($ldapbind) 
			    {
			    	// $search_filter =  "(sAMAccountName=$username)"; 
			    	$search_filter = "(mail=$username)";
				    $search_result = ldap_search($ldapconn, $ldapconfig['basedn'], $search_filter);

				    if ($search_result) 
				    {
				        $entries = ldap_get_entries($ldapconn, $search_result);

				        if ($entries["count"] > 0) 
				        {
				            // Obtener el DN del usuario
				            $user_dn = $entries[0]["dn"];

				            // Autenticar el usuario usando su DN y contraseña
				            if (@ldap_bind($ldapconn, $user_dn, $password)) 
				            {
				               return 1;
				               // echo "Autenticación exitosa.";
				            }
				            else 
				            {
				            	return -1;
				                // echo "Error de autenticación. Credenciales incorrectas.";
				            }
				        } 
				        else 
				        {
				        	return -2;
				            // echo "Usuario no encontrado.";
				        }
				    } else 
				    {
				    	return -3;
				    	// echo "Error en la búsqueda LDAP.";
				   	}			       
			    } else {
			    	return -4;
			    }
			    ldap_close($ldapconn);
		}else
		{
			return -4;
		}
		
	}
}

?>