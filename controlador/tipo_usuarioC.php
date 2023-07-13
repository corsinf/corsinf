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
			$s1='';$s2='';$s3='';$s4='';$s5='';$s6='';$s7='';$s8='';$s9='';$s10='';$s11='';$s12='';$s13='';$s14='';$s15='';$s16='';$s17='';$s18='';$s19='';

			$icon = str_replace('&#x','', $value['icono']);
			// print_r($icon);die();
			if(isset($icon))
			{
				// print_r($value['modulo'].'-');
			switch ($icon) {
					case  'ea75;':
					  $s1 = 'selected';
					break;
					case  'e95f;':
					  $s2 = 'selected';
					break;
					case  'e9be;':
					  $s3 = 'selected';
					break;
					case  'eb2b;':
					  $s4 = 'selected';
					break;
					case  'ea5c;':
					  $s5 = 'selected';
					break;
					case  'eaab;':
					  $s6 = 'selected';
					break;
					case 'e9e6;':
					  $s7 = 'selected';
					break;
					case  'ea1a;':
					  $s8 = 'selected';
					break;
					case  'ea37;':
					  $s9 = 'selected';
					break;
					case  'ebbf;':
					  $s10 = 'selected';
					break;
					case  'ea6f;':
					  $s11 = 'selected';
					break;
					case  'ea21;':
					  $s12 = 'selected';
					break;
					case  'e9d0;':
					  $s13 = 'selected';
					break;
					case  'e9ba;':
						$s14 = 'selected';
					break;
					case  'e91a;':
						$s15 = 'selected';
					break;
					case  'e919;':
						$s16 = 'selected';
					break;
					case  'e982;':
						$s17 = 'selected';
					break;
					case  'eb43;':
						$s18 = 'selected';
					break;
					case  'e9f7;':
						$s19 = 'selected';
					break;
			}
		}

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
			 <select class="bx" id="ddl_icono'.$value['id'].'" name="ddl_icono'.$value['id'].'"> 
         <option class="bx"> ICONO</option>
            <option '.$s1.' class="bx" value="ea75" > &#xea75;</option>
            <option '.$s2.' class="bx" value="e95f" > &#xe95f;</option>
            <option '.$s3.' class="bx" value="e9be" > &#xe9be;</option>
            <option '.$s4.' class="bx" value="eb2b" > &#xeb2b;</option>
            <option '.$s5.' class="bx" value="ea5c" > &#xea5c; </option>
            <option '.$s6.' class="bx" value="eaab" > &#xeaab;</option>
            <option '.$s7.' class="bx" value="e9e6" > &#xe9e6;</option>
            <option '.$s8.' class="bx" value="ea1a" > &#xea1a;</option>
            <option '.$s9.' class="bx" value="ea37" > &#xea37;</option>
            <option '.$s10.' class="bx" value="ebbf" > &#xebbf;</option>
            <option '.$s11.' class="bx" value="ea6f" > &#xea6f;</option>
            <option '.$s12.' class="bx" value="ea21" > &#xea21;</option>
            <option '.$s13.' class="bx" value="e9d0" > &#xe9d0;</option>
            <option '.$s14.' class="bx" value="e9ba" > &#xe9ba;</option>
            <option '.$s15.' class="bx" value="e91a" > &#xe91a;</option>
            <option '.$s16.' class="bx" value="e919" > &#xe919;</option>
            <option '.$s17.' class="bx" value="e982" > &#xe982;</option>
            <option '.$s18.' class="bx" value="eb43" > &#xeb43;</option>
            <option '.$s19.' class="bx" value="e9f7" > &#xe9f7;</option>
      </select> 
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
		$ver = 0;	$edi =0;$eli =0;
		if($parametros['ver']=='true'){ $ver = 1;}
		if($parametros['edi']=='true'){ $edi = 1;} 
		if($parametros['eli']=='true'){ $eli = 1;}

		$dato = $this->modelo->existe_acceso($parametros['pag'],$parametros['perfil']);
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
			return $this->modelo->update('ACCESOS',$datos,$where);
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

			return $this->modelo->guardar($datos,'ACCESOS')	;		
		}
		// print_r($parametros);die();
	}

	function add_tipo($parametros)
	{
		if($parametros['id']=='')
		{
		   $acceso[0]['campo'] = 'DESCRIPCION';
		   $acceso[0]['dato'] =strtoupper($parametros['tipo']);
		   return $this->modelo->guardar($acceso,'TIPO_USUARIO');		
	    }else
	    {
	    	 $acceso[0]['campo'] = 'DESCRIPCION';
		     $acceso[0]['dato'] =strtoupper($parametros['tipo']);
		     $where [0]['campo']='ID_TIPO';
		     $where [0]['dato']= $parametros['id'];
		    return $this->modelo->update('tipo_usuario',$acceso,$where);	

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
		 $modulo = $parametros['modulo'];
		 $datos = $this->modelo->paginas($query,$modulo);
		 $tr = '';
		 foreach ($datos as $key => $value) {
		 	$tr.='<tr>
		 	<td>'.$value['nombre_pagina'].'</td>
		 	<td>'.$value['detalle_pagina'].'</td>
		 	<td>'.$value['estado_pagina'].'</td>
		 	<td>'.$value['nombre_modulo'].'</td>
		 	<td>'.$value['default_pag'].'</td>
		 	<td width="15px" class="text-center"><input type="checkbox" name="ver_'.$value['id_paginas'].'" id="ver_'.$value['id_paginas'].'" checked onclick="guardar_accesos_edi(\''.$value['id_paginas'].'\')"></td>
      <td width="15px" class="text-center"><input type="checkbox" onclick="guardar_accesos_edi(\''.$value['id_paginas'].'\')" name="edi_'.$value['id_paginas'].'" id="edi_'.$value['id_paginas'].'"></td>
      <td width="15px" class="text-center"><input type="checkbox" onclick="guardar_accesos_edi(\''.$value['id_paginas'].'\')" name="eli_'.$value['id_paginas'].'" id="eli_'.$value['id_paginas'].'"></td>
		 	</tr>';
		 }
		 return $tr;
	}

	function eliminar_usuario_tipo($id)
	{
		$resp = $this->modelo->eliminar_usuario_tipo($id);
		return $resp;

	}
	
}
?>