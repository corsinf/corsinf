<?php
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) . '/modelo/loginM.php');
require_once(dirname(__DIR__, 2) . '/modelo/admin_punto_ventaM.php');
/**
 * 
 */
$controlador = new admin_punto_ventaC();
if(isset($_GET['lista']))
{
	$query = '';
	$datos = $controlador->lista_puntos($query);
	echo json_encode($datos);
}
if(isset($_GET['bodegas_asignadas']))
{
	$query = '';
	$datos = $controlador->bodegas_asignadas($query);
	echo json_encode($datos);
}
if(isset($_GET['usuarios_punto']))
{
	$query = '';
	$datos = $controlador->usuarios_punto($query);
	echo json_encode($datos);
}
if(isset($_GET['editar']))
{
	$parametros = $_POST['parametros'];
	$datos = $controlador->editar($parametros);
	echo json_encode($datos);
}
if(isset($_GET['eliminar']))
{
	$query = $_POST['id'];
	$datos = $controlador->eliminar_bode($query);
	echo json_encode($datos);
}
if(isset($_GET['eliminar_usu']))
{
	$query = $_POST['id'];
	$datos = $controlador->eliminar_usu($query);
	echo json_encode($datos);
}
if(isset($_GET['estado']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->estado_usuario($id));
}
if(isset($_GET['estado_punto']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->estado_punto($id));
}
if(isset($_GET['activar']))
{
	$id = $_POST['id'];
	echo json_encode($controlador->activar_usuario($id));
}
if(isset($_GET['inactivo']))
{
	$query = $_POST['query'];
	$datos = $controlador->categorias_inactivas($query);
	echo json_encode($datos);
}
if(isset($_GET['add']))
{
	// print_r($_POST);die();
	$query = $_POST['nombre'];
	$num = $_POST['num'];
	$datos = $controlador->punto_add($query,$num);
	echo json_encode($datos);
}
if(isset($_GET['add_usu']))
{
	// print_r($_POST);die();
	$query = $_POST['usuario'];
	$num = $_POST['punto'];
	$datos = $controlador->punto_add_usuario($query,$num);
	echo json_encode($datos);
}
if(isset($_GET['updtate_punto']))
{
	// print_r($_POST);die();
	$query = $_POST['nombre'];
	$id = $_POST['id'];
	$datos = $controlador->punto_update($query,$id);
	echo json_encode($datos);
}
if(isset($_GET['updtate_bodegas']))
{
	// print_r($_POST);die();
	$query = $_POST['bodegas'];
	$id = $_POST['id'];
	$datos = $controlador->bodega_update($query,$id);
	echo json_encode($datos);
}
if(isset($_GET['bodegas_multi']))
{
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	
	$datos = $controlador->bodegas($query);
	echo json_encode($datos);
}
if(isset($_GET['usuario']))
{
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	
	$datos = $controlador->usuario($query);
	echo json_encode($datos);
}
if(isset($_GET['puntos_asignar']))
{
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	
	$datos = $controlador->puntos($query);
	echo json_encode($datos);
}
class admin_punto_ventaC
{
	private $modelo;
	private $pagina;
	private $punto_venta;
	private $login;
	function __construct()
	{
		$this->modelo = new admin_punto_ventaM();
		$this->pagina = new codigos_globales();
		$this->login = new loginM();
		$this->pagina->registrar_pagina_creada('../vista/admin_punto_venta.php','Admin punto venta','4','estado');

	}
	function categorias_inactivas($query)
	{
		$datos = $this->modelo->lista_bodega_ina($query);
		// print_r($datos);die();
		$cabecera = array('Num Punto','Nombre de punto venta');
		$ocultar = array('id');		
		$botones[0] = array('boton'=>'Activar','icono'=>'<i class="fas fa-check nav-icon"></i>','tipo'=>'success','id'=>'id');
		$tabla = $this->pagina->tabla_generica($datos,$cabecera,$botones,false,$ocultar,$foto=false);
		return $tabla;

	}

	function lista_puntos($query)
	{
		$datos = $this->modelo->lista_puntos($query);
		$tabla = '<tr><td colspan="2">sin registros...</td></tr>';
		if(count($datos)>0)
		{
			$tabla = '';
			foreach ($datos as $key => $value) {
				$tabla.='<tr>
				            <td>
				              <input type="text" name="txt_num_'.$value['id'].'" id="txt_num_'.$value['id'].'" class="form-control-sm form-control" value="'.$value['num'].'" readonly="">
				            </td>
				            <td>
				              <input type="text" name="txt_nombre_'.$value['id'].'" id="txt_nombre_'.$value['id'].'" class="form-control-sm form-control" value="'.$value['nombre'].'">
				            </td>
				            <td>				             
      			 		       <button class="btn btn-primary btn-sm" type="button" onclick="update_punto(\''.$value['id'].'\')"><i class="fa fa-save"></i></button>
      			 			   <button class="btn btn-danger btn-sm" type="button" onclick="eliminar_bode(\''.$value['id'].'\')"><i class="fa fa-trash"></i></button>
				            </td>
				        </tr>';
			}
		}
		// print_r($tabla);die();
		return $tabla;

	}
	function bodegas_asignadas($query)
	{
		$datos = $this->modelo->bodegas_asignadas($query);
		$tabla = '<tr><td colspan="2">sin registros...</td></tr>';
		if(count($datos)>0)
		{
			$tabla = '';
			$option = '';
			$script = '';
			foreach ($datos as $key => $value) {
				if($value['all_bodegas']==1)
				{
					$option = '<option value="TODOS" selected="selected">TODOS</option>';
				}else if($value['bodega'] !='' && $value['all_bodegas']==0 && $value['bodega']!=null)
				{

					// print_r($value['bodega']);die();
					$op = explode(',', $value['bodega']);
					if(count($op)>1)
					{
						$option = '';
					   foreach ($op as $key1 => $value1) {
              				$bode = $this->modelo->lista_bodega(false,$value1);
              				$option.='<option value="'.$bode[0]['id'].'" selected="selected">'.$bode[0]['nombre'].'</option>'; 
					   }
					}else
					{				
              		    $bode = $this->modelo->lista_bodega(false,$value['bodega']);
              		    $option ='<option value="'.$bode[0]['id'].'" selected="selected">'.$bode[0]['nombre'].'</option>'; 
					}
				}
				$tabla.='<tr>
				            <td>
				              <input type="text" name="txt_punto_'.$value['id'].'" id="txt_nombre_punto_'.$value['id'].'" class="form-control-sm form-control" value="'.$value['nombre'].'" readonly="">
				            </td>
				            <td>
				              <select id="ddl_bodega_multi_'.$value['id'].'" class="js-example-basic-multiple js-states form-control" name="ddl_bodega_multi_'.$value['id'].'[]" multiple="multiple">'.$option.'</select>
				            </td>
				            <td>				             
      			 		       <button class="btn btn-primary btn-sm" type="button" onclick="editar_bode(\''.$value['id'].'\')"><i class="fa fa-save"></i></button>
      			 			   <button class="btn btn-danger btn-sm" type="button" onclick="eliminar_bode(\''.$value['id'].'\')"><i class="fa fa-trash"></i></button>
				            </td>
				        </tr>';

              			$script.= "$('#ddl_bodega_multi_".$value['id']."').select2({
              				        placeholder: 'Seleccione una familia',
              				        width:'90%',
              				        ajax: {
              				        	url:   '../controlador/admin_punto_ventaC.php?bodegas_multi=true',
              				        	dataType: 'json',
              				        	delay: 250,
              				        	processResults: function (data) {
              				        	// console.log(data);
              				        		return {
              				        			results: data
              				        	};
              				        },
              				        cache: true
              				       }});";

			$option = '';
			}
		}
		$js = '<script type="text/javascript">'.$script.'</script>';
		// print_r($tabla);die();
		return $tabla.$js;

	}
	function bodegas($query)
	{
		$datos = $this->modelo->lista_bodega($query);
		$resp[] = array('id'=>'TODO','text'=>'TODO');
		foreach ($datos as $key => $value) {
			$resp[] = array('id'=>$value['id'],'text'=>$value['nombre']);
		}
		return $resp;
	}
	function puntos($query)
	{
		$datos = $this->modelo->lista_puntos($query);
		$resp = array();
		foreach ($datos as $key => $value) {
			$resp[] = array('id'=>$value['id'],'text'=>$value['nombre']);
		}
		return $resp;
	}

	function usuario($query)
	{
		$datos = $this->modelo->lista_usuario($query);
		$resp = array();
		foreach ($datos as $key => $value) {
			$resp[] = array('id'=>$value['id'],'text'=>$value['nombre']);
		}
		return $resp;
	}

	// function eliminar($id)
	// {		
	// return	$resp = $this->modelo->delete_punto($id);
	// }
	function editar($parametros)
	{
		$datos[0]['campo']='detalle_bodega';
		$datos[0]['dato']=$parametros['nom'];
		$where[0]['campo']='id_bodegas';
		$where[0]['dato']=$parametros['id'];
		$resp = $this->modelo->update('bodegas',$datos,$where);
		return $resp;

	}
	function estado_usuario($id)
	{
		$where[0]['campo']='id_bodegas';
		$where[0]['dato']=$id;
		$datos[0]['campo']='estado';
		$datos[0]['dato']='I';
		$rep = $this->modelo->update('bodegas',$datos,$where);
		return $rep;
	}
	function activar_usuario($id)
	{
		$where[0]['campo']='id_bodegas';
		$where[0]['dato']=$id;
		$datos[0]['campo']='estado';
		$datos[0]['dato']='A';
		$rep = $this->modelo->update('bodegas',$datos,$where);
		return $rep;
	}
	function punto_add($nombre,$num)
	{
		if($this->modelo->existente($nombre,$num)==false)
		{
		 $datos[0]['campo']='nombre_punto';
		 $datos[0]['dato']=$nombre;
		 $datos[1]['campo']='num_punto_venta';
		 $datos[1]['dato']=$num;
		 $rep = $this->modelo->guardar($datos,'punto_venta');
		 return $rep;
		}else
		{
			return -2;
		}
	}
	function punto_add_usuario($usuario,$punto)
	{
		if($this->modelo->existente_acceso($usuario,$punto)==false)
		{
		 $datos[0]['campo']='id_usuario';
		 $datos[0]['dato']=$usuario;
		 $datos[1]['campo']='id_punto_venta';
		 $datos[1]['dato']=$punto;
		 $rep = $this->modelo->guardar($datos,'acceso_punto_venta');
		 $this->actualizar_punto_venta();
		 return $rep;
		}else
		{
			return -2;
		}
	}
	function punto_update($nombre,$num)
	{
		// print_r($nombre.'--'.$num);die();
		 $datos[0]['campo']='nombre_punto';
		 $datos[0]['dato']=$nombre;
		 $where[0]['campo']='id_punto_venta';
		 $where[0]['dato']=$num;
		 $rep = $this->modelo-> update_punto('punto_venta',$datos,$where);
		 return $rep;
		

	}

    function bodega_update($nombre,$num)
	{
		$t = false;
		$lista = '';
		if(!empty($nombre))
		{
		foreach ($nombre as $key => $value) {
			if($value=='TODO')
			{
				$t=true;
			}else
			{
				$lista.=$value.',';
			}
		}
	    }
		if($t)
		{
			$datos[0]['campo']='all_bodegas';
		    $datos[0]['dato']=1;
		    $where[0]['campo']='id_punto_venta';
		    $where[0]['dato']=$num;
		    $rep = $this->modelo-> update_punto('punto_venta',$datos,$where);
		    return $rep;

		}else{
			if($lista=='')
			{
				$lista = null;
			}
		 $lista = substr($lista, 0,-1);
		 $datos[0]['campo']='bodega';
		 $datos[0]['dato']=$lista;		 
		 $datos[1]['campo']='all_bodegas';
		 $datos[1]['dato']=0;
		 $where[0]['campo']='id_punto_venta';
		 $where[0]['dato']=$num;
		 $rep = $this->modelo-> update_punto('punto_venta',$datos,$where);
		 return $rep;
		}
	}


	function eliminar_bode($id)
	{		
	  $resp = $this->modelo->delete_punto($id);
	  return $resp;
	}
	function eliminar_usu($id)
	{		
	  $resp = $this->modelo->delete_usu_punto($id);
	  $this->actualizar_punto_venta();
	  return $resp;
	}
	function estado_punto($id)
	{
		$where[0]['campo']='id_punto_venta';
		$where[0]['dato']=$id;
		$datos[0]['campo']='estado';
		$datos[0]['dato']='I';
		$rep = $this->modelo->update('punto_venta',$datos,$where);
		return $rep;
	}

	function usuarios_punto()
	{
		$puntos = $this->modelo->acceso_punto();
		$html='';
		foreach ($puntos as $key => $value) {
			$html.= '<div class="card card-primary card-outline">
                          <div class="card-header">
                            <h5 class="card-title m-0"><b>PUNTO DE VENTA: </b>'.$value['nombre'].'</h5>
                          </div>
                          <div class="card-body">
                            <table class="table-hover table-sm table">
                              <!--<thead>
                                <th>USUARIO ASIGNADOS</th>
                              </thead>-->
                              <tbody>';
			$usu = $this->modelo->usuarios_acceso_punto($value['id']);
			foreach ($usu as $key1 => $value1) {
				$html.='<tr><td>'.$value1['nombre'].'</td><td><button class="btn btn-sm btn-danger" onclick="eliminar_usu(\''.$value1['id'].'\')"><i class="fa fa-trash"></i></button></td></tr>';
			}
			$html.='</tbody></table></div></div>';
		}
		return $html;
	}


	function  actualizar_punto_venta()
	{
		$puntos = $this->login->puntos_venta($_SESSION['INICIO']['ID']);
				$p_venta = '';
				if(count($puntos)>0)
				{
				foreach ($puntos as $key => $value) {
					$p_venta.=$value['id'].',';
				}
			    }
				$p_venta = substr($p_venta, 0,-1);
				$_SESSION['INICIO']['PUNTO_VENTA'] = $p_venta;
	}




}

?>