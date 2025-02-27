<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/articulosM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

/**
 * 
 **/

$controlador = new cambios_custodio_localizacionC();

if (isset($_GET['lista'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_articulos($parametros));
}

if (isset($_GET['meses'])) {
	echo json_encode($controlador->lista_meses());
}

if (isset($_GET['lista_imprimir'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_articulos_impri($parametros));
}

if (isset($_GET['lista_imprimir_'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_articulos_impri_num($parametros));
}

if (isset($_GET['buscar'])) {
	echo json_encode($controlador->buscar_articulos($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
	echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
	echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['paginacion'])) {
	echo json_encode($controlador->lista_articulos_pag());
}

if (isset($_GET['vaciar'])) {
	echo json_encode($controlador->vaciar_tag());
}

if (isset($_GET['articulos_ddl'])) {
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->articulos_ddl($query));
}

if (isset($_GET['cambiar'])) {
	echo json_encode($controlador->cambiar_masivo($_POST['parametros']));
}



class cambios_custodio_localizacionC
{
	private $modelo;
	private $cod_global;

	function __construct()
	{
		$this->modelo = new articulosM();
		$this->cod_global = new codigos_globales();
	}

	function lista_articulos($parametros)
	{
		// print_r($parametros);die();
		// $query = $parametros['query'];
		$loc = $parametros['localizacion'];
		$cus = $parametros['custodio'];
		// $pag = $parametros['pag'];
		// $exacto=false;
		// if(isset($parametros['exacto']))
		// {
		//  $exacto = $parametros['exacto'];
		// }
		// $asset='';
		// if(isset($parametros['asset']))
		// {
		// 	$asset = $parametros['asset'];
		// }
		// $asset_org = '';
		// if(isset($parametros['asset_org']))
		// {
		// 	$asset_org = $parametros['asset_org'];
		// }

		// if($exacto == 'true')
		// {
		// 	$exacto  = true;
		// }else
		// {
		// 	$exacto = false;
		// }
		// if($asset == 'true')
		// {
		// 	$asset  = true;
		// }else
		// {
		$asset = false;
		// }

		$datos = $this->modelo->cantidad_registros($query = false, $loc, $cus);
		$total_reg = $datos[0]['numreg'];
		if ($total_reg > 25) {
			$datos = $this->modelo->lista_articulos($query = false, $loc, $cus, $pag = false, false, $exacto = false, $asset = false);
		} else {
			$datos = $this->modelo->lista_articulos($query = false, $loc, $cus, false, false, $exacto = false, $asset = false);
		}
		// print_r($datos);die();
		//$datos = array_map(array($this->cod_global, 'transformar_array_encode'), $datos);
		$datos2 = array('datos' => $datos, 'cant' => $total_reg);

		// print_r($datos2);die();
		return $datos2;
	}

	function lista_articulos_impri($parametros)
	{
		$v = $this->modelo->existe_datos();
		if ($v == -1) {
			// print_r('expression');die();

			$query = $parametros['query'];
			$loc = $parametros['localizacion'];
			$cus = $parametros['custodio'];
			$pag = $parametros['pag'];
			$datos = $this->modelo->lista_articulos($query, $loc, $cus, $pag);
			foreach ($datos as $key => $value) {
				$rand = $this->generarCodigo(8);
				$rand = "5002000100070028" . $rand;
				if ($this->modelo->existe($rand) == -1) {
					$datoss[0]['campo'] = 'TAG_Unique';
					$datoss[0]['dato'] = $rand;
					$where[0]['campo'] = 'ID_ASSET';
					$where[0]['dato'] = $value['ID_ASSET'];
					$this->modelo->editar_asser($datoss, $where);
					$datoss2[0]['campo'] = 'RFID';
					$datoss2[0]['dato'] = $rand;
					$datoss2[1]['campo'] = 'SERIE';
					$datoss2[1]['dato'] = $value['tag'];
					$this->modelo->insertar($datoss2, 'ac_imprimir_tags');
				} else {
					$rand = $this->generarCodigo(8);
					$rand = "5002000100070028" . $rand;
					$datoss[0]['campo'] = 'TAG_Unique';
					$datoss[0]['dato'] = $rand;
					$where[0]['campo'] = 'ID_ASSET';
					$where[0]['dato'] = $value['ID_ASSET'];
					$this->modelo->editar_asser($datoss, $where);
					$datoss2[0]['campo'] = 'RFID';
					$datoss2[0]['dato'] = $rand;
					$datoss2[1]['campo'] = 'SERIE';
					$datoss2[1]['dato'] = $value['tag'];
					$this->modelo->insertar($datoss2, 'ac_imprimir_tags');
				}
			}

			$d = 1;
			return $d;
		} else {
			$d = 2;
			return $d;
		}
	}

	function lista_articulos_impri_num($parametros)
	{
		// print_r($parametros);die();
		$v = $this->modelo->existe_datos();
		$datoss2 = array();
		if ($v == -1) {

			$numero = $parametros['numero'];
			for ($i = 1; $i < $numero + 1; $i++) {
				$rand = $this->generarCodigo(6);
				$rand = "500200010007002800" . $rand;
				if ($this->modelo->existe($rand) == -1) {
					$datoss2[0]['campo'] = 'RFID';
					$datoss2[0]['dato'] = $rand;
				} else {
					$rand = $this->generarCodigo(6);
					$rand = "500200010007002800" . $rand;
					$datoss2[0]['campo'] = 'RFID';
					$datoss2[0]['dato'] = $rand;
				}

				$this->modelo->insertar($datoss2, 'ac_imprimir_tags');
			}
			//print_r($datoss2);die();

			$d = 1;
			return $d;
		} else {
			$d = 2;
			return $d;
		}
	}

	function generarCodigo($longitud)
	{
		$key = '';
		$pattern = '1234567890';
		$max = strlen($pattern) - 1;
		for ($i = 0; $i < $longitud; $i++) {
			$key .= mt_rand(0, $max);
		}
		return $key;
	}

	function lista_articulos_pag()
	{
		$datos = $this->modelo->lista_articulos_pag();
		$datos = count($datos);
		// print_r($datos);
		return $datos;
	}

	function lista_meses()
	{
		$datos = $this->modelo->meses_modificado();
		return $datos;
	}

	function vaciar_tag()
	{
		$delete = array();
		$datos = $this->modelo->eliminar($delete, 'ac_imprimir_tags');
		return $datos;
	}

	function buscar_articulos($buscar)
	{
		$datos = $this->modelo->buscar_articulos($buscar);
		return $datos;
	}

	function insertar_editar($parametros)
	{
		$datos[0]['campo'] = 'CODIGO';
		$datos[0]['dato'] = $parametros['cod'];
		$datos[1]['campo'] = 'DESCRIPCION';
		$datos[1]['dato'] = $parametros['des'];
		if ($parametros['id'] == '') {
			$datos = $this->modelo->insertar($datos);
		} else {
			$where[0]['campo'] = 'ID_articulos';
			$where[0]['dato'] = $parametros['id'];
			$datos = $this->modelo->editar($datos, $where);
		}


		return $datos;
	}

	function eliminar($id)
	{
		$datos[0]['campo'] = 'ID_articulos';
		$datos[0]['dato'] = $id;
		$datos = $this->modelo->eliminar($datos);
		return $datos;
	}

	function articulos_ddl($query)
	{
		$datos = $this->modelo->lista_articulos($query, '', '', '1-25', false);
		$cambio = [];
		foreach ($datos as $key => $value) {
			$cambio[] = ['id' => $value['id'], 'text' => utf8_encode($value['nom'])];
		}
		return $cambio;
	}

	function cambiar_masivo($parametros)
	{
		if ($parametros['opcion'] == 'L') {
			$loc = $parametros['antes'];
			$articulos = $this->modelo->lista_articulos(false, $loc, false, false, false, false, false, false, false, false, false, false);
		} else {
			$cus = $parametros['antes'];
			$articulos = $this->modelo->lista_articulos(false, false, $cus, false, false, false, false, false, false, false, false, false);
		}
		$ids = '';
		foreach ($articulos as $key => $value) {
			$ids .= $value['id'] . ',';
		}
		$ids = substr($ids, 0, -1);

		return $this->modelo->cambiar_masivo($parametros['opcion'], $ids, $parametros['despues']);

		print_r($articulos);
		die();
		print_r($parametros);
		die();
	}
}
