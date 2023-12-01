<?php
include('../modelo/licenciasM.php');
include('../db/codigos_globales.php');
if(isset($_SESSION['INICIO']))
{	
  @session_start();
} 
/**
 * 
 */
$controlador = new licenciasC();
if(isset($_GET['lista_licencias']))
{
	echo json_encode($controlador->lista_licencias());
}
if(isset($_GET['guardar_licencia']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_licencia($parametros));
}
if(isset($_GET['eliminar_licencia']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_licencia($parametros));
}

class licenciasC
{
	private $modelo;
	private $cod_global;
	function __construct()
	{
		$this->modelo = new licenciasM();
		$this->cod_global = new codigos_globales();
	}

	function lista_licencias()
	{
		$datos = $this->modelo->lista_modulos();
		$tr = '';
		foreach ($datos as $key => $value) {
			$licencia = $this->modelo->lista_licencias($value['id_modulos'],1,false,1);
			$prop = '';$btnModificar = '';$llave='';$btnRegistrar = '';
			if(count($licencia)>0){
				$prop = 'readonly'; 
				$btnModificar = '<button class="btn btn-sm btn-danger" onclick="eliminar_licencia('.$licencia[0]['Id_licencias'].')"><i class="bx bx-trash"></i></button>';
				$llave = $licencia[0]['Codigo_licencia'];
			}else{
				$btnRegistrar = '<button class="btn btn-sm btn-primary" onclick="registrar_licencia('.$value['id_modulos'].')"><i class="bx bx-save"></i></button>';
			}
			// print_r($value);die();

			$tr.='<tr>
						<td>'.$value['icono'].' '.$value['nombre_modulo'].'</td>
						<td><input type="text" name="txt_licencia_'.$value['id_modulos'].'" id="txt_licencia_'.$value['id_modulos'].'" value="'.$llave.'" class="form-control" '.$prop.' /></td>
						<td>
							'.$btnRegistrar.'
							'.$btnModificar.'
			            </td>
					</tr>';

			// print_r($licencia);die();
		}
		return $tr;
	}

	function guardar_licencia($parametros)
	{
		$datos = $this->modelo->lista_licencias($parametros['modulo'],0,$parametros['licencias']);
		if(count($datos)==0)
		{	// la licencia no se encontro
			// tiene que estar restrada la licencia con empresa y el registrado en 0 
		  return -2;
		}else
		{
			$datosL[0]['campo'] = 'registrado';
			$datosL[0]['dato'] = 1;

			$where[0]['campo'] = 'Id_licencias';			
			$where[0]['dato'] = $datos[0]['Id_licencias'];

			$this->modelo->update('LICENCIAS',$datosL,$where,1);

			 return $this->cod_global->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);
		}
	}

	function eliminar_licencia($parametros)
	{
			$datosL[0]['campo'] = 'registrado';
			$datosL[0]['dato'] = 0;

			$where[0]['campo'] = 'Id_licencias';			
			$where[0]['dato'] = $parametros['licencias'];

			$this->modelo->update('LICENCIAS',$datosL,$where,1);

			 return $this->cod_global->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);

	}




}
?>