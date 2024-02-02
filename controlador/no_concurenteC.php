<?php 
include('../modelo/no_concurenteM.php');
require_once('../db/codigos_globales.php');
/**
 * 
 */
$controlador = new no_concurenteC();
if(isset($_GET['lista_no_concurente']))
{
	echo json_encode($controlador->lista_no_concurente());
}
if(isset($_GET['tabla_no_concurente']))
{
	echo json_encode($controlador->tabla_no_concurente());
}
if(isset($_GET['campos_tabla_noconcurente']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->campos_tabla_no_concurentes($parametros));
}
if(isset($_GET['add_no_concurente']))
{
	echo json_encode($controlador->add_no_concurente($_POST['parametros']));
}
if(isset($_GET['delete_no_concurente']))
{
	echo json_encode($controlador->eliminar($_POST['parametros']));
}


class no_concurenteC
{
	private $modelo;
	private $cod_global;
	
	function __construct()
	{
		$this->modelo = new no_concurenteM();
		$this->cod_global = new codigos_globales();
		
	}
	function lista_no_concurente()
	{
		$datos = $this->modelo->lista_no_concurente();
		return $datos;
	}

	function tabla_no_concurente()
	{
		$datos = $this->modelo->tabla_no_concurente();
		return $datos;
	}
	function add_no_concurente($parametros)
	{
		$existe = $this->modelo->existe_no_concurente($parametros['tabla']);

		if(count($existe)==0)
		{
			$id_tabla = $this->modelo->id_tabla_no_concurentes($parametros['tabla']);
			$id = $id_tabla[0]['ID'];
			$datos = $this->modelo->datos_no_concurentes($parametros['tabla']);

			foreach ($datos as $key => $value) {
					$datosADD = array(
					array('campo'=>'Tabla','dato'=>$parametros['tabla']),
					array('campo'=>'Id_Empresa','dato'=>$_SESSION['INICIO']['ID_EMPRESA']),
					array('campo'=>'Id_Usuario','dato'=>$value[$id]),
					array('campo'=>'Campo_usuario','dato'=>$parametros['usuario']),
					array('campo'=>'Campo_pass','dato'=>$parametros['pass']),
					array('campo'=>'tipo_perfil','dato'=>$parametros['perfil_usu']),
					array('campo'=>'campo_img','dato'=>$parametros['foto']),
				);
				$this->modelo->insertar('TABLAS_NOCONCURENTE',$datosADD,1);
			}

			$datosUPD = array(
					array('campo'=>'PERFIL','dato'=>2)			
			);
			$where = array(
					array('campo'=>'1','dato'=>1)			
			);		
			return $this->modelo->editar($parametros['tabla'],$datosUPD ,$where,$master=false);
		}else
		{
			return -2;
		}
	}

	function eliminar($parametros)
	{
		$this->modelo->eliminar_no_concurente($parametros['tabla']);
		$datosUPD = array(
				array('campo'=>'PERFIL','dato'=>'.')	
		);
		$where = array(
				array('campo'=>'1','dato'=>1)			
		);		
		return $this->modelo->editar($parametros['tabla'],$datosUPD ,$where,$master=false);
	}

	function campos_tabla_no_concurentes($parametros)
	{
		$tabla = $parametros['tabla'];
		$lista = array();
		$datos = $this->modelo->campos_tabla_no_concurentes($tabla);
		foreach ($datos as $key => $value) {
			if($value['COLUMN_NAME']!='PERFIL')
			{
				$lista[] = array('campo'=>$value['COLUMN_NAME']); 
			}
		}
		return $lista;
	}
	
}
?>