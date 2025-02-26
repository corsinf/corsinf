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
require_once(dirname(__DIR__, 2) .'/modelo/headerM.php');

$controlador = new tipo_usuarioC();
if(isset($_GET['lista']))
{
	echo json_encode($controlador->lista_tipo_usuarios());
}

if(isset($_GET['modulos']))
{
	echo json_encode($controlador->lista_modulos());
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
if(isset($_GET['accesos_guardar']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_accesos($parametros));
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

if(isset($_GET['modulos_acceso']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->modulos_acceso($id));
}
if(isset($_GET['guardar_modulos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_modulos($parametros));
}

class tipo_usuarioC
{
	private $modelo;
	private $pagina;
	private $global;
	private $pdf;
	private $header;

	
	function __construct()
	{
		$this->modelo = new tipo_usuarioM();
		$this->pagina = new codigos_globales();
		$this->header = new headerM();
		$this->pagina->registrar_pagina_creada('../vista/tipo_usuario.php','Tipo usuario y accesos','3','estado');
	}


	function lista_tipo_usuarios()
	{
		$datos = $this->modelo->lista_tipo_usuario();
		$html='';
		foreach ($datos as $key => $value) {
			$html.='<div class="col-md-3 text-center">
			            <div class="card card-info collapsed-card" id="panel_'.$value['id'].'">
			                <div class="card-header btn-sm" style="padding: 7px" data-card-widget="collapse" onclick="modulos_acceso(\''.$value['id'].'\');cargar_modulos(\''.$value['id'].'\',\''.$value['nombre'].'\')">
			                    <h3 class="card-title">'.$value['nombre'].'</h3>
			                    <div class="card-tools">
			                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i id="icono_'.$value['id'].'" class="fas fa-plus"></i></button>
			                    </div>
			                   <!-- /.card-tools -->
			                </div>
			                   <!-- /.card-header -->
			                <div class="card-body" id="pan_'.$value['id'].'" style="display: none; padding: 5px">
			                    <button class="btn btn-default" title="Editar" onclick="update(\''.$value['id'].'\',\''.$value['nombre'].'\')"><i class="fas fa-pen"></i></button>
			                    <button class="btn btn-default" title="Eliminar" onclick="eliminar_tipo(\''.$value['id'].'\')"><i class="fas fa-trash"></i></button>
			                    <button class="btn btn-default" title="Usuario Asignados" onclick="cargar_usuarios(\''.$value['id'].'\')"><i class="fas fa-user"></i></button>
			                </div>
			            </div>
			        </div>';
		}
		return $html;
	}

	function lista_modulos($tipo= false)
	{
		if($tipo==false){$tipo['tipo']=false;}
		$datos= $this->modelo->lista_modulos();
		//tab todos
		$li='<li class="nav-item">
                <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="home" aria-selected="true">Default</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="free-tab" data-toggle="tab" href="#free" role="tab" aria-controls="home" aria-selected="true">Sin modulo</a>
              </li>';

         //contenido de tab todos
		$paginas_all = $this->modelo->lista_paginas_default();
		$div='<div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="home-tab">
                  <table class="table table-bordered table table-sm">
                    <thead>
                      <th><input type="checkbox" id="rbl_all_all" name="rbl_all_all"/></th>
                      <th>Pagina</th>
                      <th>Detalle</th>
                      <tbody>';
		foreach ($paginas_all as $key => $value) {
			$rep = $this->header->accesos($value['id'],$tipo['tipo']);
			if($rep==1)
			{
			$div.='<tr>
			        <td><input type="checkbox" name="rbl_'.$value['id'].'_all" checked=""></td>
			        <td>'.$value['pagina'].'</td>
                    <td>'.$value['detalle'].'</td>
                   </tr>';
            }else
            {
            	$div.='<tr>
			        <td><input type="checkbox" name="rbl_'.$value['id'].'_all"></td>
			        <td>'.$value['pagina'].'</td>
                    <td>'.$value['detalle'].'</td>
                   </tr>';

            }
		}
		$div.='</tbody></thead></table></div>';
		//fin de tab todos

		  //contenido de tab todos freee
		$paginas_free = $this->modelo->lista_paginas_sin_modulo();
		$div.='<div class="tab-pane" id="free" role="tabpanel" aria-labelledby="home-tab">
                  <table class="table table-bordered table table-sm">
                    <thead>
                      <th><input type="checkbox" id="rbl_all_all" name="rbl_all_all"/></th>
                      <th>Pagina</th>
                      <th>Detalle</th>
                      <tbody>';
		foreach ($paginas_free as $key => $value) {
			$rep = $this->header->accesos($value['id'],$tipo['tipo']);
			if($rep==1)
			{
			$div.='<tr>
			        <td><input type="checkbox" name="rbl_'.$value['id'].'_free" checked=""></td>
			        <td>'.$value['pagina'].'</td>
                    <td>'.$value['detalle'].'</td>
                   </tr>';
            }else
            {
            	$div.='<tr>
			        <td><input type="checkbox" name="rbl_'.$value['id'].'_free"></td>
			        <td>'.$value['pagina'].'</td>
                    <td>'.$value['detalle'].'</td>
                   </tr>';

            }
		}
		$div.='</tbody></thead></table></div>';
		//fin de tab todos

		foreach ($datos as $key => $value) {
			$li.='<li class="nav-item">
                <a class="nav-link" id="'.$this->pagina->quitar_estacios($value['modulo']).'-tab" data-toggle="tab" href="#'.$this->pagina->quitar_estacios($value['modulo']).'" role="tab" aria-controls="home" aria-selected="true">'.$value['icono'].'  '.$value['modulo'].'</a>
              </li>';
             $div.=$this->paginas($this->pagina->quitar_estacios($value['modulo']),$value['id'],$tipo['tipo']);
		}
		return array('li'=>$li,'div'=>$div);

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
			$rep = $this->header->accesos($value['id'],$tipo);
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

	function guardar_accesos($parametros)
	{
		$tipo = $parametros['txt_tipo_usu'];		
		$eli = $this->modelo->eliminar_permisos($tipo);
		$pag = $this->modelo->lista_paginas_default($query =false,$modulo=false);
		foreach ($pag as $key => $value) {
			$acceso[0]['campo'] = 'id_tipo_usuario';
			$acceso[0]['dato'] = $tipo;
			$acceso[1]['campo'] = 'id_paginas';
			$acceso[1]['dato'] = $value['id'];
			$this->modelo->guardar($acceso,'accesos');			
		}

		if($eli ==1)
		{
		foreach ($parametros as $key => $value) {
			$dato = explode('_', $key);
			if($dato[2]!='all')
			{				
			if(is_numeric($dato[1]))
			{
				 $pa = $this->modelo->lista_paginas_default($query =false,$modulo=false,$dato[1]);
				 if (count($pa)==0){
				 	 $acceso[0]['campo'] = 'id_tipo_usuario';
			         $acceso[0]['dato'] = $tipo;
			         $acceso[1]['campo'] = 'id_paginas';
			         $acceso[1]['dato'] = $dato[1];
			         $this->modelo->guardar($acceso,'accesos');
				 }				

			}else if($dato[1]=='all')
			{
				$pag = $this->modelo->lista_paginas(false,$dato[2]);
				foreach ($pag as $key => $value) {

					$acceso[0]['campo'] = 'id_tipo_usuario';
					$acceso[0]['dato'] = $tipo;
					$acceso[1]['campo'] = 'id_paginas';
					$acceso[1]['dato'] = $value['id'];
					$this->modelo->guardar($acceso,'accesos');
				}
			}
		  }
		}
		return array('id'=>$tipo,'resp'=>1);
	}else{
		return -1;
	}
	}

	function add_tipo($parametros)
	{
		if($parametros['id']=='')
		{
		   $acceso[0]['campo'] = 'detalle_tipo_usuario';
		   $acceso[0]['dato'] =strtoupper($parametros['tipo']);
		   return $this->modelo->guardar($acceso,'tipo_usuario');		
	    }else
	    {
	    	 $acceso[0]['campo'] = 'detalle_tipo_usuario';
		     $acceso[0]['dato'] =strtoupper($parametros['tipo']);
		     $where [0]['campo']='id_tipo_usuario';
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

	function eliminar_tipo($id)
	{
		$resp = $this->modelo->eliminar_tipo($id);
		return $resp;

	}
	function modulos_acceso($tipo)
	{
		$datos= $this->modelo->lista_modulos();
		$modulo = $this->modelo->modulos_habilitados($tipo);
		$cbx = '';
		$coincide = false;
		foreach ($datos as $key => $value) {
			$coincide = false;
			foreach ($modulo as $key1 => $value1) {
				// print_r($value);die();
				if($value1['id_modulos'] == $value['id'])
				{
					$coincide = true;
					$cbx.='<div class="col-sm-2"><label><input type="checkbox" name="modulos" value="'.$value['id'].'" id="cbx_modulo_'.$value['id'].'" checked> '.$value['modulo'].'</label></div>';
					break;
				}
			}
			if($coincide==false)
			{
				$cbx.='<div class="col-sm-2"><label><input type="checkbox" name="modulos" value="'.$value['id'].'" id="cbx_modulo_'.$value['id'].'">  '.$value['modulo'].'</label></div>';
			}
		}

		return $cbx;

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
	
}
?>