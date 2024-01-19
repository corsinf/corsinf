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
if(isset($_GET['activo_paginas_dba']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->activo_pagina_dba($parametros));
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
if(isset($_GET['modulos_sis_tabla']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->modulos_sis_tabla($parametros));
}
if(isset($_GET['editar_modulo_sis']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->editar_modulo_sis($parametros));
}

if(isset($_GET['modulos_sis_ddl']))
{
	echo json_encode($controlador->modulos_sis_ddl());
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
		// print_r($parametros);die();
		 $query = $parametros['query'];
		 $modulo = $parametros['modulo'];
		 $datos = $this->modelo->paginas_all($query,$modulo);
		 $tr = '';
		 foreach ($datos as $key => $value) {
		 	$mod_sis = $this->opciones_modulo_sistema($value['id_modulo']);
		 	$tr.='<tr>
		 	<td><select class="form-select form-select-sm" id="ddl_modulos_sis_pag_ing'.$value['id_paginas'].'" name="ddl_modulos_sis_pag_ing'.$value['id_paginas'].'" onchange="cargar_modulos_ddl_ing(\''.$value['id_paginas'].'\')">'.$mod_sis['opciones'].'</select></td>
		 	<td><input id="txt_pagina_new'.$value['id_paginas'].'" name="txt_pagina_new'.$value['id_paginas'].'" class="form-control form-control-sm" value="'.$value['nombre_pagina'].'" /></td>
		 	<td><input id="txt_detalle_pag'.$value['id_paginas'].'" name="txt_detalle_pag'.$value['id_paginas'].'" class="form-control form-control-sm" value="'.$value['detalle_pagina'].'" /></td>
		 	<td><input id="txt_url'.$value['id_paginas'].'" name="txt_url'.$value['id_paginas'].'" class="form-control form-control-sm" value="'.$value['link_pagina'].'" /></td>
		 	<td><select class="form-select form-select-sm" id="ddl_modulos_pag_ing'.$value['id_paginas'].'" name="ddl_modulos_pag_ing'.$value['id_paginas'].'">'.$this->opciones_tipo($value['id_modulo'],$mod_sis['modulo_sis']).'</select></td>';
		 	if($value['default_pag']==1)
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox" title = "Default" onclick="default_pag(\''.$value['id_paginas'].'\')" name="rbl_defaul'.$value['id_paginas'].'" id="rbl_defaul'.$value['id_paginas'].'" checked></td>';
		 	}else
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox"  title = "Default" onclick="default_pag(\''.$value['id_paginas'].'\')" name="rbl_defaul'.$value['id_paginas'].'" id="rbl_defaul'.$value['id_paginas'].'"></td>';
		 	}
		 	if($value['subpagina']==1)
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox"  title = "Sub pagina" onclick="subpag(\''.$value['id_paginas'].'\')" name="rbl_subpag'.$value['id_paginas'].'" id="rbl_subpag'.$value['id_paginas'].'" checked></td>';
		 	}else
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox"  title = "Sub pagina" onclick="subpag(\''.$value['id_paginas'].'\')" name="rbl_subpag'.$value['id_paginas'].'" id="rbl_subpag'.$value['id_paginas'].'"></td>';
		 	}

		 	if($value['estado_pagina']=='A')
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox"  title = "Activo" name="rbl_activo'.$value['id_paginas'].'" id="rbl_activo'.$value['id_paginas'].'" onclick="activo_pag(\''.$value['id_paginas'].'\')"  checked></td>';
		 	}else
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox"  title = "Activo" name="rbl_activo'.$value['id_paginas'].'" id="rbl_activo'.$value['id_paginas'].'" onclick="activo_pag(\''.$value['id_paginas'].'\')" ></td>';
		 	}

		 	if($value['para_dba']==1)
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox"  title = "Para dba" name="rbl_para_dba'.$value['id_paginas'].'" id="rbl_para_dba'.$value['id_paginas'].'" onclick="activo_dba(\''.$value['id_paginas'].'\')"  checked></td>';
		 	}else
		 	{
		 		$tr.='<td width="15px" class="text-center"><input type="checkbox"  title = "Para dba" name="rbl_para_dba'.$value['id_paginas'].'" id="rbl_para_dba'.$value['id_paginas'].'" onclick="activo_dba(\''.$value['id_paginas'].'\')" ></td>';
		 	}

		 	$tr.='<td width="15px" class="text-center">
			 			<div class="input-group input-group-sm mb-3"> 
							<span class="input-group-text" id="inputGroup-sizing-sm">'.$value['icono_paginas'].'</span>
							<input class = "form-control form-control-sm" id="ddl_icono_pag'.$value['id_paginas'].'" name="ddl_icono_pag'.$value['id_paginas'].'" value="'.str_replace('"',"'", $value['icono_paginas']).'" >
						</div>		
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
		$datos[2]['dato']= str_replace("'",'"',$parametros['icono']);
		$datos[3]['campo']='modulos_sistema';
		$datos[3]['dato']= $parametros['modulo_sis'];

		$where[0]['campo']='id_modulo';
		$where[0]['dato'] = $parametros['id'];
		if($parametros['id']!='')
		{
			$this->modelo->update('MODULOS',$datos,$where);
		}else
		{
			$this->modelo->guardar($datos,'MODULOS');
		}
		return $this->cod_global->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);
		// print_r($parametros);die();
	}

	function guardar_paginas($parametros)
	{
		print_r($parametros);die();
		$defa = 0;
		$activo = 'I';
		$subpag = 0;
		$paradb = 0;
		if($parametros['defaul']=='true'){$defa = 1;	}		// print_r($parametros);die();
		if($parametros['activo']=='true'){$activo = 'A';	}
		if($parametros['subpag']=='true'){$subpag = 1;	}	
		if($parametros['paradb']=='true'){$paradb = 1;	}	

		$datos[0]['campo']='nombre_pagina';
		$datos[0]['dato']=$parametros['pagina'];
		$datos[1]['campo']='link_pagina';
		$datos[1]['dato']=$parametros['url'];
		$datos[2]['campo']='icono_paginas';
		$datos[2]['dato']= str_replace("'",'"', $parametros['icono']);
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
		$datos[8]['campo']='para_dba';
		$datos[8]['dato']=$paradb;

		$where[0]['campo']='id_paginas';
		$where[0]['dato'] = $parametros['id'];
		if($parametros['id']!='')
		{
			// print_r($datos);die();
			$this->modelo->update('PAGINAS',$datos,$where);
		}else
		{
			$this->modelo->guardar($datos,'PAGINAS');
		}
		return $this->cod_global->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);
		// print_r($parametros);die();
	}

	function eliminar_modulos($id)
	{
		$res = $this->modelo->eliminar($id);
		if($res=='1')
		{
			$res =  $this->cod_global->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);
		}
		return $res;
	}

	function eliminar_pagina($id)
	{
		$this->modelo->eliminar_pagina($id);		
		return $this->cod_global->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);
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
	function activo_pagina_dba($parametros)
	{   
		$activo = 0;	
		if($parametros['op']=='true'){$activo = 1;	}	
		$datos[1]['campo']='para_dba';
		$datos[1]['dato']=$activo;

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

	function opciones_tipo($modulo,$modulos_sis= false)
	{
		$mod = $this->tipo->lista_modulos($query=false,false,$modulos_sis);
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

	function opciones_modulo_sistema($menu)
	{
		$mod = $this->tipo->lista_modulos($query=false,$menu);
		$sis = $this->tipo->modulos_sis();
		// print_r($mod); 
		// print_r($modulo);
		// die();
		$op = '';
		foreach ($sis as $key => $value) {
			if($value['id_modulos']==$mod[0]['modulos_sistema'])
			{
				$op.="<option class='bx' value='".$value['id_modulos']."' selected>".$value['nombre_modulo']."</option>"; 
			}else
			{
				$op.="<option class='bx' value='".$value['id_modulos']."'>".$value['nombre_modulo']."</option>"; 
			}
		}
		return  array('opciones'=>$op,'modulo_sis'=>$mod[0]['modulos_sistema']);

	}

	function modulos_sis_tabla($parametros)
	{
		$modulos_sis = $this->modelo->modulos_sis();
		$tr='';
		foreach ($modulos_sis as $key => $value) {
			$tr.="<tr>
				<td>
					<input class='form-control form-control-sm' id='txt_modulo_sis".$value['id_modulos']."' name='txt_modulo_sis".$value['id_modulos']."' value='".$value['nombre_modulo']."'>
				</td>
				<td>
					<input class='form-control form-control-sm' id='txt_link_sis".$value['id_modulos']."' name='txt_link_sis".$value['id_modulos']."'  value='".$value['link']."' >
				</td>
				<td>
					<div class='input-group input-group-sm mb-3'> 
						<span class='input-group-text' id='inputGroup-sizing-sm'>".$value['icono']."</span>
						<input class = 'form-control form-control-sm' id='txt_icono_sis".$value['id_modulos']."' name='txt_icono_sis".$value['id_modulos']."' value='".$value['icono']."' >
					</div>
				</td>
				<td class='text-center'>";
				if($value['estado']=='A')
				{
					$tr.="<input type='checkbox' class='' id='rbl_estado".$value['id_modulos']."' name='rbl_estado".$value['id_modulos']."' checked>";
				}else
				{
					$tr.="<input type='checkbox' class='' id='rbl_estado".$value['id_modulos']."' name='rbl_estado".$value['id_modulos']."'>";					
				}
				$tr.="</td>
				<td>
				 	<button class='btn btn-primary btn-sm' onclick='editar_modulo(".$value['id_modulos'].")'><i class='bx bx-pencil'></i></button>
				</td>
			</tr>";
		}

		return $tr;

		print_r($modulos_sis);die();
	}


	function editar_modulo_sis($parametros)
	{
		$datos[0]['campo'] = 'nombre_modulo';
		$datos[0]['dato'] = $parametros['nombre'];
		$datos[1]['campo'] = 'link';
		$datos[1]['dato'] = $parametros['link'];
		$datos[2]['campo'] = 'icono';
		$datos[2]['dato'] = $parametros['icono'];
		$datos[3]['campo'] = 'estado';
		$datos[3]['dato'] = 'A';
		if($parametros['estado']=='false')
		{
			$datos[3]['dato'] = 'I';
		}

		// print_r($parametros);
		// print_r($datos);die();
		if($parametros['id']!='')
		{

			$where[0]['campo'] = 'id_modulos';
			$where[0]['dato'] = $parametros['id'];
			$this->modelo->update('MODULOS_SISTEMA',$datos,$where);


		}else
		{
			$this->modelo->guardar($datos,'MODULOS_SISTEMA');			
		}

		return $this->cod_global->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);
	}

	function modulos_sis_ddl()
	{
		$modulos_sis = $this->modelo->modulos_sis();
		$op = '';
		foreach ($modulos_sis as $key => $value) {
			$op.='<option value="'.$value['id_modulos'].'">'.$value['nombre_modulo'].'</option>';
		}
		return $op;
	}
	
}
?>