<?php 
include('../modelo/ligar_segurosM.php');
require_once('../db/codigos_globales.php');
/**
 * 
 */
$controlador = new ligar_segurosC();
if(isset($_GET['lista_tabla_seguros']))
{
	echo json_encode($controlador->lista_tabla_seguros());
}
if(isset($_GET['tabla_no_concurente']))
{
	echo json_encode($controlador->tabla_no_concurente());
}
if(isset($_GET['campos_tabla']))
{
	$parametros = $_GET;
	echo json_encode($controlador->campos_tabla($parametros));
}
if(isset($_GET['add']))
{
	echo json_encode($controlador->add($_POST['parametros']));
}
if(isset($_GET['delete_tbl_seguro']))
{
	echo json_encode($controlador->eliminar($_POST['parametros']));
}


class ligar_segurosC
{
	private $modelo;
	private $cod_global;
	
	function __construct()
	{
		$this->modelo = new ligar_segurosM();
		$this->cod_global = new codigos_globales();
		
	}
	function lista_tabla_seguros()
	{
		$lista_tabla = $this->modelo->existe();
		$tablas = $lista_tabla[0]['Tabla_seguros'];
		$arrayDatos = json_decode($tablas, true);
		$existe = 0;
		$tbl = '';
		$campos = 0;
		$campostemp = 0;
		foreach ($arrayDatos as $key => $value) {
			$tbl.= '<tr>';
			foreach ($value as $key2 => $value2) {
				if($key2=='tabla')
				{
					$tbl.='<td><button class="btn btn-danger btn-sm" onclick="eliminar_tbl_seguro(\''.$value2.'\')"><i class="bx bx-trash me-0"></i></button></td>';
				}
				$tbl.='<td>'.$value2.'</td>';
				$campostemp++;				
			}
			if($campostemp > $campos)
			{
				$campos = $campostemp;
			}
			$campostemp = 0;
			$tbl.='</tr>';
		}


		$tabla='<thead><th></th><th><b>Tabla</b></th>';
		for ($i=1; $i < $campos; $i++) { 
			$tabla.='<th><b>Campo '.$i.' </b></th>';			
		}
		$tabla.='</tr>';
		$tabla = $tabla.$tbl;

		return $tabla;
	}

	function tabla_no_concurente()
	{
		$datos = $this->modelo->tabla_no_concurente();
		return $datos;
	}
	function add($parametros)
	{
		$datos = str_replace('ddl_','',$parametros['campos']);
		$datos = str_replace('=',':',$datos);
		$datos = str_replace('&',',',$datos);
		$pares = explode(",", $datos);

		$datos = array();
		$tbl = '';
		foreach ($pares as $par) {
		    list($clave, $valor) = explode(":", $par, 2);
		    $datos[$clave] = $valor;
		    if($clave=='tabla')
		    {
		    	$tbl = $valor;
		    }
		}
		$jsonDatos = json_encode($datos);

		//busca en base de datos
		$lista_tabla = $this->modelo->existe();
		$tablas = $lista_tabla[0]['Tabla_seguros'];
		$arrayDatos = json_decode($tablas, true);
		$existe = 0;
		foreach ($arrayDatos as $key => $value) {
			if($value['tabla']==$tbl)
			{
				$existe = 1;
				break;
			}
		}

		if($existe==0)
		{
			$combinedArray = array_merge(array($datos),$arrayDatos);
			$jsonResultado = json_encode($combinedArray);
			$_SESSION['INICIO']['ASIGNAR_SEGUROS'] = $jsonResultado; 

			$datosIn[0]['campo'] = 'Tabla_seguros';
			$datosIn[0]['dato'] = $jsonResultado;

			$where[0]['campo'] = 'Id_Empresa';			
			$where[0]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];

			$this->modelo->editar('EMPRESAS',$datosIn,$where);
			return $this->modelo->editar('EMPRESAS',$datosIn,$where,1);

		}else
		{
			return -2;
		}

		// if(count($existe)==0)
		// {
		// 	$id_tabla = $this->modelo->id_tabla_no_concurentes($parametros['tabla']);
		// 	$id = $id_tabla[0]['ID'];
		// 	$datos = $this->modelo->datos_no_concurentes($parametros['tabla']);

		// 	foreach ($datos as $key => $value) {
		// 			$datosADD = array(
		// 			array('campo'=>'Tabla','dato'=>$parametros['tabla']),
		// 			array('campo'=>'Id_Empresa','dato'=>$_SESSION['INICIO']['ID_EMPRESA']),
		// 			array('campo'=>'Id_Usuario','dato'=>$value[$id]),
		// 			array('campo'=>'Campo_usuario','dato'=>$parametros['usuario']),
		// 			array('campo'=>'Campo_pass','dato'=>$parametros['pass']),
		// 		);
		// 		$this->modelo->insertar('TABLAS_NOCONCURENTE',$datosADD,1);
		// 	}

		// 	$datosUPD = array(
		// 			array('campo'=>'PERFIL','dato'=>2)			
		// 	);
		// 	$where = array(
		// 			array('campo'=>'1','dato'=>1)			
		// 	);		
		// 	return $this->modelo->editar($parametros['tabla'],$datosUPD ,$where,$master=false);
		// }else
		// {
		// 	return -2;
		// }
	}

	function eliminar($parametros)
	{
		$lista_tabla = $this->modelo->existe();
		$tablas = $lista_tabla[0]['Tabla_seguros'];
		$arrayDatos = json_decode($tablas, true);
		$lis = array();
		foreach ($arrayDatos as $key => $value) {
			if($value['tabla']!=$parametros['tabla'])
			{
				$lis[] = $value;				
			}
		}

		$jsonResultado = json_encode($lis);
		$_SESSION['INICIO']['ASIGNAR_SEGUROS'] = $jsonResultado; 
		$datosIn[0]['campo'] = 'Tabla_seguros';
		$datosIn[0]['dato'] = $jsonResultado;

		$where[0]['campo'] = 'Id_Empresa';			
		$where[0]['dato'] = $_SESSION['INICIO']['ID_EMPRESA'];

		$this->modelo->editar('EMPRESAS',$datosIn,$where);
		return $this->modelo->editar('EMPRESAS',$datosIn,$where,1);


	}

	function campos_tabla($parametros)
	{
		$tabla = $parametros['tbl'];
		$lista = array();
		$datos = $this->modelo->campos_tabla($tabla);
		foreach ($datos as $key => $value) {
			
			$lista[] = array('id'=>$value['COLUMN_NAME'],'text'=>$value['COLUMN_NAME']); 
			
		}
		return $lista;
	}
	
}
?>