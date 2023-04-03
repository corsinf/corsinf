<?php 
include('../modelo/modulos_paginasM.php');
include('../modelo/tipo_usuarioM.php');
require_once('../db/codigos_globales.php');
/**
 * 
 */
$controlador = new modulos_paginasC();
if(isset($_GET['lista_paginas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_paginas($parametros));
}
if(isset($_GET['guardar_modulos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_modulos($parametros));
}
if(isset($_GET['eliminar_modulos']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->eliminar_modulos($id));
}
if(isset($_GET['eliminar_pagina']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->eliminar_pagina($id));
}
if(isset($_GET['activo_paginas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->activo_pagina($parametros));
}
if(isset($_GET['defaul_paginas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->default_pagina($parametros));
}
if(isset($_GET['sub_pagina']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->sub_pagina($parametros));
}
if(isset($_GET['guardar_paginas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_paginas($parametros));
}
class modulos_paginasC
{
	private $modelo;
	private $cod_global;
	private $tipo;
	
	function __construct()
	{
		$this->modelo = new modulos_paginasM();
		$this->tipo = new tipo_usuarioM();
		$this->cod_global = new codigos_globales();
		
	}
	function lista_paginas($parametros)
	{
		 $query = $parametros['query'];
		 $modulo = $parametros['modulo'];
		 $datos = $this->modelo->paginas_all($query,$modulo);
		 $tr = '';
		 foreach ($datos as $key => $value) {
		 	$tr.='<tr>
		 	<td><input id="txt_pagina_new'.$value['id_paginas'].'" name="txt_pagina_new'.$value['id_paginas'].'" class="form-control form-control-sm" value="'.$value['nombre_pagina'].'" /></td>
		 	<td><input id="txt_detalle_pag'.$value['id_paginas'].'" name="txt_detalle_pag'.$value['id_paginas'].'" class="form-control form-control-sm" value="'.$value['detalle_pagina'].'" /></td>
		 	<td><input id="txt_url'.$value['id_paginas'].'" name="txt_url'.$value['id_paginas'].'" class="form-control form-control-sm" value="'.$value['link_pagina'].'" /></td>
		 	<td><select class="form-select form-select-sm" id="ddl_modulos_pag'.$value['id_paginas'].'" name="ddl_modulos_pag'.$value['id_paginas'].'">'.$this->opciones_tipo($value['id_modulo']).'</select></td>';
		 	if($value['default_pag']==1)
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox" onclick="default_pag(\''.$value['id_paginas'].'\')" name="rbl_defaul'.$value['id_paginas'].'" id="rbl_defaul'.$value['id_paginas'].'" checked></td>';
		 	}else
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox" onclick="default_pag(\''.$value['id_paginas'].'\')" name="rbl_defaul'.$value['id_paginas'].'" id="rbl_defaul'.$value['id_paginas'].'"></td>';
		 	}
		 	if($value['subpagina']==1)
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox" onclick="subpag(\''.$value['id_paginas'].'\')" name="rbl_subpag'.$value['id_paginas'].'" id="rbl_subpag'.$value['id_paginas'].'" checked></td>';
		 	}else
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox" onclick="subpag(\''.$value['id_paginas'].'\')" name="rbl_subpag'.$value['id_paginas'].'" id="rbl_subpag'.$value['id_paginas'].'"></td>';
		 	}

		 	if($value['estado_pagina']=='A')
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox" name="rbl_activo'.$value['id_paginas'].'" id="rbl_activo'.$value['id_paginas'].'" onclick="activo_pag(\''.$value['id_paginas'].'\')"  checked></td>';
		 	}else
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox" name="rbl_activo'.$value['id_paginas'].'" id="rbl_activo'.$value['id_paginas'].'" onclick="activo_pag(\''.$value['id_paginas'].'\')" ></td>';
		 	}

		 	$tr.='<td width="15px" class="text-center">
		 	<i class="bx">'.$value['icono_paginas'].'</i>
		 	 <select class="bx" id="ddl_icono_pag'.$value['id_paginas'].'" name="ddl_icono_pag'.$value['id_paginas'].'"> 
                 	  <option class="bx"> ICONO</option>
                      <option class="bx" value="ea75" > &#xea75;</option>
                      <option class="bx" value="e95f" > &#xe95f;</option>
                      <option class="bx" value="e9be" > &#xe9be;</option>
                      <option class="bx" value="eb2b" > &#xeb2b;</option>
                      <option class="bx" value="ea5c" > &#xea5c; </option>
                      <option class="bx" value="eaab" > &#xeaab;</option>
                      <option class="bx" value="e9e6" > &#xe9e6;</option>
                      <option class="bx" value="ea1a" > &#xea1a;</option>
                      <option class="bx" value="ea37" > &#xea37;</option>
                      <option class="bx" value="ebbf" > &#xebbf;</option>
                      <option class="bx" value="ea6f" > &#xea6f;</option>
                      <option class="bx" value="ea21" > &#xea21;</option>
                      <option class="bx" value="e9d0" > &#xe9d0;</option>
                      <option class="bx" value="e9ba" > &#xe9ba;</option>
                      <option class="bx" value="e91a" > &#xe91a;</option>
                      <option class="bx" value="e919" > &#xe919;</option>
                      <option class="bx" value="e982" > &#xe982;</option>
                      <option class="bx" value="eb43" > &#xeb43;</option>
                      <option class="bx" value="e9f7" > &#xe9f7;</option>

              </select> 
		 	</td>
		      <td width="15px" class="text-center">
		      <button class="btn btn-primary btn-sm" onclick="guardar_pagina(\''.$value['id_paginas'].'\')"><i class="bx bx-save"></i></button>
		      	<button class="btn btn-danger btn-sm" onclick="eliminar_pagina(\''.$value['id_paginas'].'\')"><i class="bx bx-trash"></i></button>
		      </td>
		 	</tr>';
		 }
		 return $tr;
	}

	function guardar_modulos($parametros)
	{

		// print_r($parametros);die();
		$datos[0]['campo']='nombre_modulo';
		$datos[0]['dato']=$parametros['modulo'];
		$datos[1]['campo']='descripcion_modulo';
		$datos[1]['dato']=$parametros['detalle'];
		$datos[2]['campo']='icono_modulo';
		$datos[2]['dato']= '&#x'.$parametros['icono'].';';

		$where[0]['campo']='id_modulo';
		$where[0]['dato'] = $parametros['id'];
		if($parametros['id']!='')
		{
			return $this->modelo->update('MODULOS',$datos,$where);
		}else
		{
			return $this->modelo->guardar($datos,'MODULOS');
		}
		// print_r($parametros);die();
	}

	function guardar_paginas($parametros)
	{
		// print_r($parametros);die();
		$defa = 0;
		$activo = 'I';
		$subpag = 0;
		if($parametros['defaul']=='true'){$defa = 1;	}		// print_r($parametros);die();
		if($parametros['activo']=='true'){$activo = 'A';	}
		if($parametros['subpag']=='true'){$subpag = 1;	}	

		$datos[0]['campo']='nombre_pagina';
		$datos[0]['dato']=$parametros['pagina'];
		$datos[1]['campo']='link_pagina';
		$datos[1]['dato']=$parametros['url'];
		$datos[2]['campo']='icono_paginas';
		$datos[2]['dato']= '&#x'.$parametros['icono'].';';
		$datos[3]['campo']='id_modulo';
		$datos[3]['dato']=$parametros['modulo'];
		$datos[4]['campo']='estado_pagina';
		$datos[4]['dato']=$activo;
		$datos[5]['campo']='default_pag';
		$datos[5]['dato']=$defa;
		$datos[6]['campo']='detalle_pagina';
		$datos[6]['dato']=$parametros['detalle'];
		$datos[7]['campo']='subpagina';
		$datos[7]['dato']=$subpag;

		$where[0]['campo']='id_paginas';
		$where[0]['dato'] = $parametros['id'];
		if($parametros['id']!='')
		{
			// print_r($datos);die();
			return $this->modelo->update('PAGINAS',$datos,$where);
		}else
		{
			return $this->modelo->guardar($datos,'PAGINAS');
		}
		// print_r($parametros);die();
	}

	function eliminar_modulos($id)
	{
		return $this->modelo->eliminar($id);
	}

	function eliminar_pagina($id)
	{
		return $this->modelo->eliminar_pagina($id);
	}

	function activo_pagina($parametros)
	{   
		$activo = 'I';	
		if($parametros['op']=='true'){$activo = 'A';	}	
		$datos[6]['campo']='estado_pagina';
		$datos[6]['dato']=$activo;

		$where[0]['campo']='id_paginas';
		$where[0]['dato'] = $parametros['id'];

		return $this->modelo->update('PAGINAS',$datos,$where);
	}
	function default_pagina($parametros)
	{
		// print_r($parametros);die();
		$defa = 0;
		if($parametros['op']=='true'){$defa = 1;	}		
		$datos[6]['campo']='default_pag';
		$datos[6]['dato']=$defa;

		$where[0]['campo']='id_paginas';
		$where[0]['dato'] = $parametros['id'];

		// print_r($datos);print_r($where);die();
		return $this->modelo->update('PAGINAS',$datos,$where);
	}

	function sub_pagina($parametros)
	{
		// print_r($parametros);die();
		$subpag = 0;
		if($parametros['op']=='true'){$subpag = 1;}	

		// print_r($subpag);die();	
		$datos[0]['campo']='subpagina';
		$datos[0]['dato']=$subpag;

		$where[0]['campo']='id_paginas';
		$where[0]['dato'] = $parametros['id'];
		// print_r($datos);print_r($where);die();

		return $this->modelo->update('PAGINAS',$datos,$where);
	}

	function opciones_tipo($modulo)
	{
		$mod = $this->tipo->lista_modulos($query=false,false);
		// print_r($mod); 
		// print_r($modulo);
		// die();
		$op = '';
		foreach ($mod as $key => $value) {
			if($value['id']==$modulo)
			{
				$op.="<option class='bx' value='".$value['id']."' selected>".$value['modulo']."</option>"; 
			}else
			{
				$op.="<option class='bx' value='".$value['id']."'>".$value['modulo']."</option>"; 
			}
		}
		return $op;

	}
	
}
?>