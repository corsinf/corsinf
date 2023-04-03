<?php 
include('../modelo/localizacionM.php');
include('../db/codigos_globales.php');
/**
 * 
 */
$controlador = new localizacionC();
if(isset($_GET['lista']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->lista_localizacion($query));
}
if(isset($_GET['buscar']))
{
	$query = '';
	if(isset($_POST['busca']))
	{
		$query = $_POST['busca'];
	}else
	{
		$query=$_POST['parametros'];
	}
	echo json_encode($controlador->buscar_localizacion($query));
}
if(isset($_GET['insertar']))
{
	echo json_encode($controlador->insertar_editar($_POST['parametros']));
}
if(isset($_GET['eliminar']))
{
	echo json_encode($controlador->eliminar($_POST['id']));
}
if(isset($_GET['listar']))
{
	$query = $_POST['id'];	
	echo json_encode($controlador->buscar_localizacion_id($query));
}

if(isset($_GET['numero_localizaciones']))
{
	echo json_encode($controlador->buscar_localizacion_cant());
}

class localizacionC
{
	private $modelo;
	
	function __construct()
	{
		$this->modelo = new localizacionM();		
		$this->cod_global = new codigos_globales();
		
	}
	function lista_localizacion($query)
	{
		$cambio = array();
		$lista = $this->modelo->lista_localizacion($query);
		foreach ($lista as $key => $value) {
			$cambio[] =array('id'=>$value['ID_LOCATION'],'text'=>$value['DENOMINACION'],'data'=>$value);
		}
		return $cambio;
		
	}
	function buscar_localizacion($buscar)
	{
		//print_r($buscar);die();
		if(is_array($buscar))
		{
			$reg = $this->modelo->lista_localizacion_count($buscar['query']);
		    if($reg >25)
		    {
		  
		    $pagi = explode('-', $buscar['pag']);			
		    $lista = $this->modelo->lista_localizacion($buscar['query'],$pagi[0],$pagi[1]);		
		 
		    }else
		    {
		    	$lista = $this->modelo->lista_localizacion('');		
		     }
		
		   // $lista = array_map(array($this->cod_global, 'transformar_array_encode'), $lista);
		    $datos2 = array('datos'=>$lista,'cant'=>$reg[0]['cant']);

		    return $datos2;

		}else
		{			

			$reg = $this->modelo->lista_localizacion_count($buscar);
			$lista = $this->modelo->lista_localizacion($buscar,0,25);
			//$lista = array_map(array($this->cod_global, 'transformar_array_encode'), $lista);
		    $datos2 = array('datos'=>$lista,'cant'=>$reg[0]['cant']);
		     return $datos2;
		}

		
		
		
		// print_r($datos2);die()'
	
	}
	function insertar_editar($parametros)
	{
	    $datos[0]['campo'] ='CENTRO';
		$datos[0]['dato']= $parametros['centro'];
		$datos[1]['campo'] = 'EMPLAZAMIENTO';
		$datos[1]['dato']= $parametros['empla'];
		$datos[2]['campo'] = 'DENOMINACION';
		$datos[2]['dato']= $parametros['deno'];
		if($parametros['id'] == '')
		{
			if(count($this->modelo->buscar_localizacion_($datos[1]['dato']))==0)
			{
		       $datos = $this->modelo->insertar($datos);		       
		        $movimiento='Insertado nuevo registro en LOCALIZACION ('.$parametros['empla'].')';
		    }else
		    {
		    	$datos=-2;
		    }
	    }else
	    {
	    	$where[0]['campo']= 'ID_LOCATION';
		    $where[0]['dato'] = $parametros['id'];
	    	$movimiento= $this->compara_datos($parametros);
	    	$datos = $this->modelo->editar($datos,$where);
	    }
	     if($movimiento!='' && $datos==1)
    	{
    		$texto = $parametros['centro'].';'.$parametros['empla'].';'.$parametros['deno'];
    		$this->cod_global->para_ftp('localizacion',$texto);
    		$this->cod_global->ingresar_movimientos(false,$movimiento,'EMPLAZAMIENTO');
    	}

		
		return $datos;

	}

	function compara_datos($parametros)
	{
		$text ='';
		$location = $this->modelo->buscar_localizacion($parametros['id']);
		if($location[0]['CENTRO']!=$parametros['centro'])
		{
			$text.=' Se modifico CENTRO en LOCALIZACION de '.$location[0]['CENTRO'].' a '.$parametros['centro'];
		}
		if ($location[0]['EMPLAZAMIENTO']!= $parametros['empla']) {
			$text.=' Se modifico EMPLAZAMIENTO en LOCALIZACION de '.$location[0]['EMPLAZAMIENTO'].' a '.$parametros['empla'];
		}
		if ($location[0]['DENOMINACION']!= $parametros['deno']) {
			$text.=' Se modifico DENOMINACION en LOCALIZACION de '.$location[0]['DENOMINACION'].' a '.$parametros['deno'];
		}

		return $text;
		
	}

	function eliminar($id)
	{

		$datos[0]['campo']='ID_LOCATION';
		$datos[0]['dato']=$id;
		$datos = $this->modelo->eliminar($datos);		
		return $datos;				

	}
	function buscar_localizacion_id($buscar)
	{
		$lista = $this->modelo->buscar_localizacion($buscar);
		//$lista = array_map(array($this->cod_global, 'transformar_array_encode'), $lista);		
		return $lista;
	}

	function buscar_localizacion_cant()
	{
		$datos = $this->modelo->buscar_localizacion_cant();
		return $datos;
	}
}
?>