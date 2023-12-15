<?php 
include('../db/codigos_globales.php');
include('../modelo/tipo_usuarioM.php');
include('../modelo/usuariosM.php');

if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}

// include('../modelo/headerM.php');

$controlador = new tipo_usuarioC();
if(isset($_GET['lista_usuarios']))
{
	echo json_encode($controlador->lista_tipo_usuarios());
}
if(isset($_GET['lista_usuarios_all']))
{
	$query = $_POST['search'];
	echo json_encode($controlador->lista_tipo_usuarios_all($query));
}
if(isset($_GET['lista_usuarios_drop']))
{
	echo json_encode($controlador->lista_tipo_usuarios_drop());
}
if(isset($_GET['lista_usuarios_asignados']))
{
	echo json_encode($controlador->lista_usuarios_asignados());
}

if(isset($_GET['modulos']))
{ 
	$parametros = '';
	if(isset($_POST['parametros']))
	{
		$parametros = $_POST['parametros'];
	}
	echo json_encode($controlador->lista_modulos($parametros));
}
if(isset($_GET['modulo_sistema']))
{ 
	$parametros = '';
	if(isset($_POST['parametros']))
	{
		$parametros = $_POST['parametros'];
	}
	echo json_encode($controlador->modulo_sistema($parametros));
}
if(isset($_GET['modulos_tabla']))
{
	echo json_encode($controlador->lista_modulos_tabla());
}

if(isset($_GET['accesos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_modulos($parametros));
}
if(isset($_GET['guardar_tipo']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_tipo($parametros));
}
if(isset($_GET['accesos_guardar_edi']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_accesos_edi($parametros));
}

if(isset($_GET['cargar_usuarios']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->usuarios_en_tipo($id));
}
if(isset($_GET['eliminar_tipo']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->eliminar_tipo($id));
}
if(isset($_GET['eliminar_usuario_tipo']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->eliminar_usuario_tipo($id));
}
if(isset($_GET['accesos_asignados']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->accesos_asignados($parametros));
}
if(isset($_GET['guardar_modulos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_modulos($parametros));
}
if(isset($_GET['guardar_en_perfil']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_en_perfil($parametros));
}
if(isset($_GET['lista_paginas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_paginas($parametros));
}
if(isset($_GET['valida_licencia']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->valida_licencia($parametros));
}
if(isset($_GET['lista_usuarios_perfil_accesos']))
{
	$tipo = $_POST['tipo'];
	echo json_encode($controlador->usuarios_en_tipo_accesos($tipo));
}


class tipo_usuarioC
{
	private $modelo;
	private $pagina;
	private $global;
	private $pdf;
	private $header;
	private $usuarios;

	
	function __construct()
	{
		$this->modelo = new tipo_usuarioM();
		$this->pagina = new codigos_globales();
		$this->usuario = new usuariosM();

		// $this->header = new headerM();
		// $this->pagina->registrar_pagina_creada('../vista/tipo_usuario.php','Tipo usuario y accesos','3','estado');
	}


	function lista_tipo_usuarios()
	{
		$datos = $this->modelo->lista_tipo_usuario();

		// print_r($datos);die();
		$html='';
		foreach ($datos as $key => $value) {
			if($value['nombre']=='DBA' )
			{
				if($_SESSION['INICIO']['TIPO']=='DBA')
				{
					$html.='<tr>
					<td><input type="text" class="form-control form-control-sm" id="txt_tipo_usuario_'.$value['id'].'" name="txt_tipo_usuario_'.$value['id'].'"  value="'.$value['nombre'].'" /></td>
					<td>
					<button class="btn btn-sm btn-primary" onclick="add_tipo('.$value['id'].')"><i class="bx bx-save font-18 me-1"></i></button>
					<button class="btn btn-sm btn-danger" onclick="eliminar_tipo('.$value['id'].')"><i class="bx bx-trash font-18 me-1"></i></button>
					</td>
					</tr>';
				}
			}else
			{
				$html.='<tr>
				<td><input type="text" class="form-control form-control-sm" id="txt_tipo_usuario_'.$value['id'].'" name="txt_tipo_usuario_'.$value['id'].'"  value="'.$value['nombre'].'" /></td>
				<td>
				<button class="btn btn-sm btn-primary" onclick="add_tipo('.$value['id'].')"><i class="bx bx-save font-18 me-1"></i></button>
				<button class="btn btn-sm btn-danger" onclick="eliminar_tipo('.$value['id'].')"><i class="bx bx-trash font-18 me-1"></i></button>
				</td>
				</tr>';
			}
		}
		return $html;
	}

	function lista_tipo_usuarios_all($query)
	{
		$datos = $this->modelo->lista_tipo_usuario_all($query);
		$tipo = array();
		foreach ($datos as $key => $value) {
			$tipo[] = array("value"=>$value['id'],"label"=>$value['nombre']);
		}
		return $tipo;
	}


	function lista_tipo_usuarios_drop()
	{
		$datos = $this->modelo->lista_tipo_usuario();

		// print_r($datos);die();
		$html='';
		foreach ($datos as $key => $value) {
			if($value['nombre']=='DBA')
			{
				if($_SESSION['INICIO']['TIPO']=='DBA')
				{
					$html.='
					<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
				}
			}else
			{
				$html.='
				<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
			}
		}
		return $html;
	}

	function lista_usuarios_asignados()
	{
		$datos = $this->modelo->lista_tipo_usuario();

		// print_r($datos);die();
		$html='';
		foreach ($datos as $key => $value) {
		$usuarios = $this->usuario->perfiles_asignados($id=false,$query=false,$value['id']);
		if($value['nombre']=='DBA')
		{
			if($_SESSION['INICIO']['TIPO']=='DBA')
			{
				$html.='<div class="accordion-item">
                      <h2 class="accordion-header" id="flush-headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panel_'.$key.'" aria-expanded="false" aria-controls="panel_'.$key.'">
                    '.$value['nombre'].'                    
                      <span class="alert-count" style="left: 0px;">'.count($usuarios).'</span>
                    </button>
                  </h2>
                      <div id="panel_'.$key.'" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                        <table class="table table-striped table-bordered dataTable">
				                  <thead>
				                    <th>Usuario</th>
				                    <th></th>
				                  </thead>
				                  <tbody>';
				                  foreach ($usuarios as $key => $value) {
				                  	$html.='<tr>
				                      <td>'.$value['nom'].'</td>                      
				                      <td><button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i> </button></td>
				                    </tr>';
				                  }                    
				          $html.='</tbody>
				                </table>		                

                        </div>
                      </div>
                    </div>';



        }
      }else
      {
      	
        $html.='<div class="accordion-item">
                      <h2 class="accordion-header" id="flush-headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panel_'.$key.'" aria-expanded="false" aria-controls="panel_'.$key.'">
                    '.$value['nombre'].'                    
                      <span class="alert-count" style="left: 0px;">'.count($usuarios).'</span>
                    </button>                    
                  </h2>
                      <div id="panel_'.$key.'" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                        <table class="table table-striped table-bordered dataTable">
				                  <thead>
				                    <th>Usuario</th>
				                    <th></th>
				                  </thead>
				                  <tbody>';
				                  foreach ($usuarios as $key => $value) {
				                  	$html.='<tr>
				                      <td>'.$value['nom'].'</td>                      
				                      <td><button class="btn btn-sm btn-danger" onclick="eliminar_usuario_tipo('.$value['ID'].')"><i class="bx bx-trash"></i> </button></td>
				                    </tr>';
				                  }                    
				          $html.='</tbody>
				                </table>		                

                        </div>
                      </div>
                    </div>';

      }
		}
		return $html;
	}
	function modulo_sistema()
	{
		$tr ='<option value="" selected>Todos</option>';
	 
		$datos= $this->modelo->modulos_sis();
		foreach ($datos as $key => $value) {
			
				$tr.='<option value="'.$value['id_modulos'].'">'.$value['nombre_modulo'].'</option>';				
		}
		return $tr;	
	}




	function lista_modulos($parametros)
	{

		$tr ='<option value="" selected>Todos</option>';
	  if(isset($parametros['tipo']))
	  {
	  	if($parametros['tipo']==2)
	  	{
	  		$tr ='';
	  	}
	  }		
		$datos= $this->modelo->lista_modulos();
		if($parametros!='')
		{
			$datos= $this->modelo->lista_modulos(false,false,$parametros['modulo_sis']);
		}
		foreach ($datos as $key => $value) {
			
				$tr.='<option value="'.$value['id'].'">'.$value['modulo'].'</option>';				
		}
		return $tr;	
	}

		function lista_modulos_tabla($tipo= false)
	{
		if($tipo==false){$tipo['tipo']=false;}
		$datos= $this->modelo->lista_modulos();
		$tr ='';
		foreach ($datos as $key => $value) {
		
		$modulos_sis = $this->modelo->modulos_sis();
		$op = '';
		foreach ($modulos_sis as $key2 => $value2) {
			if($value2['id_modulos']==$value['modulos_sistema'])
			{
				$op.="<option value='".$value2['id_modulos']."' selected>".$value2['nombre_modulo']."</option>";
			}else
			{
				$op.="<option value='".$value2['id_modulos']."'>".$value2['nombre_modulo']."</option>";				
			}
		}

			$tr.='
			<tr>
			<td>
					<select class="form-select form-select-sm" id="ddl_modulos_sis'.$value['id'].'">'.$op.'
					</select>
			</td>
			<td><input name="txt_modulo'.$value['id'].'" id="txt_modulo'.$value['id'].'" value="'.$value['modulo'].'" class="form-control form-control-sm"></td>
			<td><input name="txt_detalle'.$value['id'].'" id="txt_detalle'.$value['id'].'" value="'.$value['detalle'].'" class="form-control form-control-sm"></td>
			<td>
				<div class="input-group input-group-sm mb-3"> 
							<span class="input-group-text" id="inputGroup-sizing-sm">'.$value['icono'].'</span>
							<input class = "form-control form-control-sm" id="ddl_icono'.$value['id'].'" name="ddl_icono'.$value['id'].'" value="'.str_replace('"',"'",$value['icono']).'" >
				</div>

			</td>
			<td><button class="btn btn-primary btn-sm" onclick="guardar_modulos(\''.$value['id'].'\')"><i class="bx bx-save"></i></button>
					<button class="btn btn-danger btn-sm" onclick="eliminar_modulos(\''.$value['id'].'\')"><i class="bx bx-trash"></i></button>
			</td>
			</tr>';
		}
		return $tr;
	
	}

	function paginas($modulo=false,$id_modulo=false,$tipo=false)
	{
		$paginas = $this->modelo->lista_paginas(false,$id_modulo);
		$div='<div class="tab-pane fade" id="'.$modulo.'" role="tabpanel" aria-labelledby="home-tab">
                  <table class="table table-bordered table table-sm">
                    <thead>
                      <th><input type="checkbox" id="rbl_all_'.$id_modulo.'" name="rbl_all_'.$id_modulo.'"/></th>
                      <th>Pagina</th>
                      <th>Detalle</th>
                      <tbody>';
		foreach ($paginas as $key => $value) {
			$rep =0; 
			// $this->header->accesos($value['id'],$tipo);
			if($rep==1)
			{
			$div.='<tr>
			        <td><input type="checkbox" name="rbl_'.$value['id'].'_'.$modulo.'" checked="" /></td>
			        <td>'.$value['pagina'].'</td>
                    <td>'.$value['detalle'].'</td>
                   </tr>';
            }else
            {
            	$div.='<tr>
			        <td><input type="checkbox" name="rbl_'.$value['id'].'_'.$modulo.'" /></td>
			        <td>'.$value['pagina'].'</td>
                    <td>'.$value['detalle'].'</td>
                   </tr>';

            }
		}
		$div.='</tbody></thead></table></div>';
		return $div;

	}

	function guardar_accesos_edi($parametros)
	{
		// print_r($parametros);die();
		$ver = 0;	$edi =0;$eli =0;
		if($parametros['ver']=='true'){ $ver = 1;}
		if($parametros['edi']=='true'){ $edi = 1;} 
		if($parametros['eli']=='true'){ $eli = 1;}

		$dato = $this->modelo->existe_acceso($parametros['pag'],$parametros['perfil']);
		// print_r($dato);die();
		if(count($dato)>0)
		{
			$where[0]['campo'] = 'id_accesos';
			$where[0]['dato'] = $dato[0]['id_accesos'];

			$datos[0]['campo'] = 'Ver';
			$datos[0]['dato'] = $ver;
			$datos[1]['campo'] = 'editar';
			$datos[1]['dato'] = $edi;
			$datos[2]['campo'] = 'eliminar';
			$datos[2]['dato'] = $eli;
			$this->modelo->update('ACCESOS',$datos,$where);
		}else
		{
			$datos[0]['campo'] = 'Ver';
			$datos[0]['dato'] = $ver;
			$datos[1]['campo'] = 'editar';
			$datos[1]['dato'] = $edi;
			$datos[2]['campo'] = 'eliminar';
			$datos[2]['dato'] = $eli;
			$datos[3]['campo'] = 'id_paginas';
			$datos[3]['dato'] = $parametros['pag'];
			$datos[4]['campo'] = 'id_tipo_usu';
			$datos[4]['dato'] = $parametros['perfil'];

			$this->modelo->guardar($datos,'ACCESOS');	
		}

		return  $this->pagina->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);	
		// print_r($parametros);die();
	}

	function add_tipo($parametros)
	{

		// print_r($parametros);die();
		if($parametros['id']=='')
		{
				if($parametros['tipo_usu_empresa']!='')
				{

				 //ingresamos en tipo usuario empresa
					$tipo[0]['campo'] = 'id_empresa';
					$tipo[0]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];
					$tipo[1]['campo'] = 'id_tipo_usuario';
					$tipo[1]['dato'] = $parametros['tipo_usu_empresa'];
			   	$this->modelo->guardar($tipo,'TIPO_USUARIO_EMPRESA',1);		

			 	}else
			 	{
			 		//buscar el tipo de usuario que se esta creando
			 		$datos = $this->modelo->lista_tipo_usuario_all($parametros['tipo'],1);
			 		// print_r($datos);die();
			 		if(count($datos)>0)
			 		{
			 			$tipo[0]['campo'] = 'id_empresa';
						$tipo[0]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];
						$tipo[1]['campo'] = 'id_tipo_usuario';
						$tipo[1]['dato'] = $datos[0]['id'];
				   	$this->modelo->guardar($tipo,'TIPO_USUARIO_EMPRESA',1);		

			 		}else
			 		{
			 			// guarda en tipo de usuario
			 			$tipo[0]['campo'] = 'DESCRIPCION';
						$tipo[0]['dato'] = strtoupper($parametros['tipo']);
						$tipo[1]['campo'] = 'ESTADO';
						$tipo[1]['dato'] = 'A';
				   	$this->modelo->guardar($tipo,'TIPO_USUARIO',1);	

			 			$datos = $this->modelo->lista_tipo_usuario_all($tipo[0]['dato'],1);
			 			$tipo[0]['campo'] = 'id_empresa';
						$tipo[0]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];
						$tipo[1]['campo'] = 'id_tipo_usuario';
						$tipo[1]['dato'] = $datos[0]['id'];
				   	$this->modelo->guardar($tipo,'TIPO_USUARIO_EMPRESA',1);		



			 		}
			 	}

			return $this->pagina->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);

    }else
    {
    	 $acceso[0]['campo'] = 'DESCRIPCION';
	     $acceso[0]['dato'] =strtoupper($parametros['tipo']);
	     $where [0]['campo']='ID_TIPO';
	     $where [0]['dato']= $parametros['id'];
	     $this->modelo->update('tipo_usuario',$acceso,$where);	

			return $this->pagina->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);

    }
		
	}
	function usuarios_en_tipo($id)
	{
		$datos = $this->modelo->lista_usuarios_en_tipo($id);
		$cabecera = array('Nombre','Email','Usuario','password');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera);

		return $tabla;

	}
	function usuarios_en_tipo_accesos($id)
	{
		// print_r($id);die();
		$datos = $this->modelo->lista_usuarios_en_tipo($id);		
		// print_r($datos);die();
		return $datos;

	}

	function eliminar_tipo($id)
	{
		$resp = $this->modelo->eliminar_tipo($id);

		$this->pagina->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);
		return $resp;

	}
	function accesos_asignados($parametros)
	{
		// $perfil = $this->modelo->lista_usuarios_en_tipo(false,$parametros['usuario']);
		// 	print_r($perfil);die();
		if($parametros['usuario']!='T')
		{
			return $this->modelo->lista_accesos_asignados($parametros['usuario']);
		}else
		{
			return array();
		}
	}
	function guardar_modulos($parametros)
	{
	     $resp = $this->modelo->eliminar_all_modulos($parametros['tipo']);
		if($resp==1)
		{
			foreach ($parametros['modulos'] as $key => $value) {
				$datos[0]['campo']='id_modulos';
				$datos[0]['dato'] =$value;
				$datos[1]['campo']='id_tipo_usuario';
				$datos[1]['dato'] =$parametros['tipo'];
				$this->modelo->guardar($datos,'accesos');
			}
			return 1;
		}

	}

	function guardar_en_perfil($parametros)
	{
		
		    $resp = $this->usuario->existe_usuario_perfil($parametros['tipo'],$parametros['usuario']);
		    if($resp==-1)
		    {
				$datos[0]['campo']='ID_USUARIO';
				$datos[0]['dato'] =$parametros['usuario'];
				$datos[1]['campo']='ID_TIPO_USUARIO';
				$datos[1]['dato'] =$parametros['tipo'];
			  return 	$this->modelo->guardar($datos,'USUARIO_TIPO_USUARIO');
			 }else
			 {
			 	return 2;
			 }	
	}

	function lista_paginas($parametros)
	{
		 $query = $parametros['query'];
		 $modulo = $parametros['modulo_sis'];
		 $menu = $parametros['modulo'];
		 // print_r($_SESSION['INICIO']);die();
		 if($_SESSION['INICIO']['TIPO']=='DBA')
		 {		 	
		 	//no se coloca nada para qu aparezcan todas las paginas sin excepcion
			 $datos = $this->modelo->paginas($query,$modulo,$menu);
		 }else{
		 	//se agrega el nuemero 2 para qu aparescan todas las pagina que no sean para dba
		  $datos = $this->modelo->paginas($query,$modulo,$menu,2);
		 }
		 // print_r('aqui');die();

		 $tr = '';
		 foreach ($datos as $key => $value) {
		 	$tr.='<tr>
		 	<td>'.$value['nombre_pagina'].'</td>
		 	<td>'.$value['detalle_pagina'].'</td>
		 	<td>'.$value['estado_pagina'].'</td>
		 	<td>'.$value['nombre_modulo'].'</td>
		 	<td>'.$value['default_pag'].'</td>
		 	<td width="15px" class="text-center"><input type="checkbox" class="rbl_pag_ver" onclick="guardar_accesos_edi(\''.$value['id_paginas'].'\') " name="ver_'.$value['id_paginas'].'" id="ver_'.$value['id_paginas'].'"></td>
      <td width="15px" class="text-center"><input type="checkbox" class="rbl_pag_edi" onclick="guardar_accesos_edi(\''.$value['id_paginas'].'\')" name="edi_'.$value['id_paginas'].'" id="edi_'.$value['id_paginas'].'"></td>
      <td width="15px" class="text-center"><input type="checkbox" class="rbl_pag_eli" onclick="guardar_accesos_edi(\''.$value['id_paginas'].'\')" name="eli_'.$value['id_paginas'].'" id="eli_'.$value['id_paginas'].'"></td>
		 	</tr>';
		 }
		 return $tr;
	}


	function valida_licencia($parametros)
	{
			$id = $parametros['modulo_sis'];
			$mod = $this->modelo->modulos_sistema_actual($id);
			if(count($mod)>0)
			{
				return 1;
			}else
			{
				return 0;
			}

	}

	function eliminar_usuario_tipo($id)
	{
		$resp = $this->modelo->eliminar_usuario_tipo($id);
		return $resp;

	}
	
}
?>