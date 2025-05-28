<?php
include('../modelo/licenciasM.php');
include('../modelo/empresaM.php');
include('../modelo/modulos_paginasM.php');
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
if(isset($_GET['lista_empresas']))
{
	$query = '';
	if(isset($_GET['a']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->lista_empresas($query));
}
if(isset($_GET['lista_licencias_all']))
{
	echo json_encode($controlador->lista_licencias_all());
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
if(isset($_GET['eliminar_licencia_definitivo']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_licencia_definitivo($parametros));
}
if(isset($_GET['eliminar_licencia_definitivo']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->empresa_all($parametros));
}
if(isset($_GET['modulos_sistemas_all']))
{
	echo json_encode($controlador->modulos_sistemas_all());
}
if(isset($_GET['add_licencias']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_licencias($parametros));
}
if(isset($_GET['generar_key']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_key($parametros));
}


class licenciasC
{
	private $modelo;
	private $cod_global;
	private $empresa;
	private $modulos_sis;
	function __construct()
	{
		$this->modelo = new licenciasM();
		$this->empresa = new empresaM();
		$this->modulos_sis = new modulos_paginasM();
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

	function lista_licencias_all()
	{
		$datos = $this->modelo->lista_licencias_all();
		$tr = '';
		foreach ($datos as $key => $value) {
			// print_r($value);die();
			$estado =  $value['registrado'] == '0' ? 'Inactivo' : 'Activo';
			$tr.='<tr>
			<td>'.$value['Razon_Social'].'</td>
			<td>'.$value['Codigo_licencia'].'</td>
			<td>'.$value['nombre_modulo'].'</td>
			<td>'.$value['Fecha_ini'].'</td>
			<td>'.$value['Fecha_exp'].'</td>
			<td>'.$value['Numero_maquinas'].'</td>			
			<td>'.$estado.'</td>
			<td>
					<!-- <button class="btn btn-primary btn-sm me-0"><i class="bx bx-trash"></i></button> -->
					<button class="btn btn-danger btn-sm me-0" onclick="eliminar_licencia('.$value['Id_licencias'].')"><i class="bx bx-trash"></i></button>
			</td>
			</tr>';
			// print_r($value);die();
		}
		return $tr;
	}

	function modulos_sistemas_all()
	{
		$datos = $this->modulos_sis->modulos_sis();		
		return $datos;
	}

	function guardar_licencia($parametros)
	{
		// print_r($parametros);die();
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
			$empresa = $this->cod_global->lista_empresa($_SESSION['INICIO']['ID_EMPRESA']);
			// print_r($empresa);die();
			if($empresa[0]['Ip_host']==IP_MASTER)
			{
				 		// print_r($parametros['modulo']);die();
					$destino =  $_SESSION['INICIO']['BASEDATO'];
					$this->cod_global->Copiar_estructura($parametros['modulo'],$destino);
					return $this->cod_global->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);

		 	}else{
		 		$base_des = $_SESSION['INICIO']['BASEDATO'];
		 		$this->cod_global->generar_primera_vez_terceros($empresa,$_SESSION['INICIO']['ID_EMPRESA']);
				return	$this->cod_global->Copiar_estructura($parametros['modulo'],$base_des,1,$empresa);
			
		 	}


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

	function eliminar_licencia_definitivo($parametros)
	{
		$id = $parametros['licencias'];
	  $this->modelo->eliminar_licencia_definitivo($id);
	  return $this->cod_global->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);

	}

	function lista_empresas($query)
	{
			$datos = $this->empresa->lista_empresas($query);
			$empresa = array();
			foreach ($datos as $key => $value) {
				$empresa[] = array('id'=>$value['Id_empresa'],'text'=>$value['Nombre_Comercial'],'data'=>$value);
			}

			return $empresa;
	}

	function add_licencias($parametros)
	{
		$datos = array(
						array('campo'=>'Codigo_licencia','dato'=>$parametros['clave']),
						array('campo'=>'Id_empresa','dato'=>$parametros['empresa']),
						array('campo'=>'Fecha_ini','dato'=>$parametros['desde']),
						array('campo'=>'Fecha_exp','dato'=>$parametros['hasta']),
						array('campo'=>'Numero_maquinas','dato'=>$parametros['maquinas']),
						array('campo'=>'Id_Modulo','dato'=>$parametros['modulo']),
						array('campo'=>'numero_pda','dato'=>$parametros['pda']),
						array('campo'=>'registrado','dato'=>0)
		);
		$this->modelo->add('LICENCIAS',$datos,1);		
	  return $this->cod_global->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);

		// print_r($parametros);die();
	}

	function generar_key($parametros)
	{
				$licencia_cod = $this->cod_global->generar_licencia($parametros['empresa'],$parametros['modulo'],$parametros['desde'],$parametros['hasta']);
				return $licencia_cod;
	}
}
?>