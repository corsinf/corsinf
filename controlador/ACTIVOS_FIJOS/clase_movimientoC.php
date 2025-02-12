<?php 
include('../modelo/clase_movimientoM.php');
require_once('../db/codigos_globales.php');
/**
 * 
 */
$controlador = new clase_movimientoC();
if(isset($_GET['lista']))
{
	echo json_encode($controlador->lista_clase_movimiento($_POST['id']));
}
if(isset($_GET['buscar']))
{
	echo json_encode($controlador->buscar_clase_movimiento($_POST['buscar']));
}
if(isset($_GET['buscar_auto']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_clase_movimiento_auto($query));
}
if(isset($_GET['insertar']))
{
	echo json_encode($controlador->insertar_editar($_POST['parametros']));
}
if(isset($_GET['eliminar']))
{
	echo json_encode($controlador->eliminar($_POST['id']));
}


class clase_movimientoC
{
	private $modelo;
	private $cod_global;
	
	function __construct()
	{
		$this->modelo = new clase_movimientoM();
		$this->cod_global = new codigos_globales();
		
	}
	function lista_clase_movimiento($id)
	{
		$datos = $this->modelo->lista_clase_movimiento($id);
		return $datos;
	}
	function buscar_clase_movimiento($buscar)
	{
		$datos = $this->modelo->buscar_clase_movimiento($buscar);
		return $datos;
	}

	function buscar_clase_movimiento_auto($buscar)
	{
		$datos = $this->modelo->buscar_clase_movimiento($buscar);
		$lis = array();
		foreach ($datos as $key => $value) {
			$lis[] = array('id'=>$value['CODIGO'],'text'=>$value['DESCRIPCION']);
		}
		return $lis;
	}


	function insertar_editar($parametros)
	{
		$datos[0]['campo'] ='CODIGO';
		$datos[0]['dato']= $parametros['cod'];
		$datos[1]['campo'] = 'DESCRIPCION';
		$datos[1]['dato']= $parametros['des'];
		if($parametros['id'] == '')
		{
		if (count($this->modelo->buscar_clase_movimiento_CODIGO($datos[0]['dato']))==0) {				
		        $datos = $this->modelo->insertar($datos);
		        $movimiento='Insertado nuevo registro en clase movimiento ('.$parametros['des'].')';
			}else
			{
				return -2;
			}
	    }else
	    {
	    	$where[0]['campo']= 'ID_MOVIMIENTO';
		    $where[0]['dato'] = $parametros['id'];
	    	$movimiento= $this->compara_datos($parametros);
	    	$datos = $this->modelo->editar($datos,$where);
	    	
	    }
	    if($movimiento!='' && $datos==1)
	    	{
	    		$texto = $parametros['cod'].';'.$parametros['des'];
	    		$this->cod_global->para_ftp('clase_movimientos',$texto);
	    		$this->cod_global->ingresar_movimientos(false,$movimiento,'clase_movimientoS');
	    	}
		return $datos;
	}

	function compara_datos($parametros)
	{
		$text ='';
		$marca = $this->modelo->lista_clase_movimiento($parametros['id']);
		if($marca[0]['CODIGO']!=$parametros['cod'])
		{
			$text.=' Se modifico CODIGO en GENERO de '.$marca[0]['CODIGO'].' a '.$parametros['cod'];
		}
		if ($marca[0]['DESCRIPCION']!= $parametros['des']) {
			$text.=' Se modifico DESCRIPCION en GENERO DE '.$marca[0]['DESCRIPCION'].' a '.$parametros['des'];
		}

		return $text;
		
	}
	function eliminar($id)
	{
		return $this->modelo->eliminar($id);
		// $datos[0]['campo']='ID_MOVIMIENTO';
		// $datos[0]['dato']=$id;
		// $datos = $this->modelo->eliminar($datos);		
		// return $datos;

	}
}
?>