<?php
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();

     // print_r($_POST['parametros']);die();
    $_SESSION['INICIO']['HOST'] =$_POST['parametros']['host'] ;
	$_SESSION['INICIO']['USU']=$_POST['parametros']['usu'];
	$_SESSION['INICIO']['PORT']=$_POST['parametros']['port'];
	$_SESSION['INICIO']['PASS'] =$_POST['parametros']['pass'];
	$_SESSION['INICIO']['DB']=$_POST['parametros']['db'];
	$_SESSION['INICIO']['TIPO_BASE'] = $_POST['parametros']['tipo'];
}

require_once(dirname(__DIR__, 2) .'/modelo/indexM.php');
/**
 * 
 */
$controlador = new indexC();
if(isset($_GET['generar']))
{

	$parametros = $_POST['parametros'];
	$datos = $controlador->generar_base($parametros);
	echo json_encode($datos);
}
if(isset($_GET['cambiar']))
{

	$parametros = $_POST['parametros'];
	$datos = $controlador->cambiar_base($parametros);
	echo json_encode($datos);
}
class indexC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new indexM();
	}

	function generar_base($parametros)
	{   

		if($parametros['tipo']=='MYSQL')
		{
			$respuesta = $this->modelo->generar_base_mysql();
			// print_r($respuesta);die();
			return $respuesta;
		}else
		{
			//funcion para crear base de datos sqlserver
		}
		// print_r($parametros);die();
		// $datos = $this->modelo->lista_index();
		// return $datos;
	}

	function cambiar_base($parametros)
	{   
		if(file_exists('../db/session.txt')==false)
	    {
		    $file = fopen('../db/session.txt', "w");
	                  fwrite($file,$_SESSION['INICIO']['TIPO_BASE'].PHP_EOL);
		              fwrite($file,$_SESSION['INICIO']['HOST'].PHP_EOL);
	                  fwrite($file,$_SESSION['INICIO']['USU'].PHP_EOL);
	                  fwrite($file,$_SESSION['INICIO']['PORT'].PHP_EOL);
	                  fwrite($file,$_SESSION['INICIO']['PASS'].PHP_EOL);
	                  fwrite($file,$_SESSION['INICIO']['DB']);
		    fclose($file);
		    return 1;
	    }else
	    {
	    	return -1;
	    }
	}

}

?>