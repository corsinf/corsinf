<?php 
include('../modelo/estadoM.php');
require_once('../db/codigos_globales.php');
/**
 * 
 */
$controlador = new estadoC();
if(isset($_GET['lista']))
{
	echo json_encode($controlador->lista_estado($_POST['id']));
}
if(isset($_GET['buscar']))
{
	echo json_encode($controlador->buscar_estado($_POST['buscar']));
}
if(isset($_GET['insertar']))
{
	echo json_encode($controlador->insertar_editar($_POST['parametros']));
}
if(isset($_GET['eliminar']))
{
	echo json_encode($controlador->eliminar($_POST['id']));
}

if(isset($_GET['lista_drop']))
{
	$query='';
	if(isset($_GET['q']))
	{
		$query= $_GET['q'];
	}
	echo json_encode($controlador->lista_estado_drop($query));
}


class estadoC
{
	private $modelo;
	private $cod_global;
	
	function __construct()
	{
		$this->modelo = new estadoM();
		$this->cod_global = new codigos_globales();
		
	}
	function lista_estado($id)
	{
		$datos = $this->modelo->lista_estado($id);
		return $datos;
	}
	function lista_estado_drop($query)
	{
		$datos = $this->modelo->lista_estado_drop($query);
		$lista = array();
		foreach ($datos as $key => $value) {
			$lista[] = array('id'=>$value['ID_ESTADO'],'text'=>$value['DESCRIPCION'],'data'=>$value);
		}
		return $lista;
	}
	function buscar_estado($buscar)
	{
		$datos = $this->modelo->buscar_estado($buscar);
		return $datos;
	}
	function insertar_editar($parametros)
	{
		$datos[0]['campo'] ='CODIGO';
		$datos[0]['dato']= $parametros['cod'];
		$datos[1]['campo'] = 'DESCRIPCION';
		$datos[1]['dato']= $parametros['des'];
		if($parametros['id'] == '')
		{
		if (count($this->modelo-> buscar_estado_CODIGO($datos[0]['dato']))==0) {				
		        $datos = $this->modelo->insertar($datos);
		        $movimiento='Insertado nuevo registro en ESTADOS ('.$parametros['des'].')';
			}else
			{
				return -2;
			}
	    }else
	    {
	    	$where[0]['campo']= 'ID_ESTADO';
		    $where[0]['dato'] = $parametros['id'];
	    	$movimiento= $this->compara_datos($parametros);
	    	$datos = $this->modelo->editar($datos,$where);
	    	
	    }
	    if($movimiento!='' && $datos==1)
	    	{
	    		$texto = $parametros['cod'].';'.$parametros['des'];
	    		$this->cod_global->para_ftp('estados',$texto);
	    		$this->cod_global->ingresar_movimientos(false,$movimiento,'ESTADOS');
	    	}
		return $datos;
	}

	function compara_datos($parametros)
	{
		$text ='';
		$marca = $this->modelo->lista_estado($parametros['id']);
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
		$datos[0]['campo']='ID_ESTADO';
		$datos[0]['dato']=$id;
		$datos = $this->modelo->eliminar($datos);		
		return $datos;

	}
}
?>