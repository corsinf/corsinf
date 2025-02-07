
<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/actasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/custodioM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/localizacionM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

$controlador = new actasC();

if (isset($_GET['lista'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_articulos($parametros));
}

if (isset($_GET['addacta'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->addarticulos($parametros));
}

if (isset($_GET['lista_actas'])) {
	echo json_encode($controlador->lista_actas());
}

if (isset($_GET['eliminar_lista'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_lista($parametros));
}

if (isset($_GET['add_masivo'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->add_masivo($parametros));
}

if (isset($_GET['add_selected'])) {
	$parametros = $_POST;
	echo json_encode($controlador->add_selected($parametros));
}

if (isset($_GET['delete_masivo'])) {
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->delete_masivo());
}

if (isset($_GET['dar_baja'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->dar_baja($parametros));
}

if (isset($_GET['cambiar_custodio'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cambiar_custodio($parametros));
}

if (isset($_GET['cambiar_E_S'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cambiar_E_S($parametros));
}



class actasC
{
	private $modelo;
	private $cod_global;
	private $custodio;
	private $localizacion;

	function __construct()
	{
		$this->modelo = new actasM();
		$this->cod_global = new codigos_globales();
		$this->custodio = new custodioM();
		$this->localizacion = new localizacionM();
	}

	function lista_articulos($parametros)
	{
		// print_r($parametros);die();
		$query = $parametros['query'];
		$masivo = $parametros['masivo'];
		$masivo_loc = $parametros['masivo_lo'];
		$masivo_cus = $parametros['masivo_cu'];
		if (strpos($query, ',') !== false) {

			$masivo = 1;
		}
		if ($parametros['masivo'] == 1) {
			$query = preg_replace("[\n|\r|\n\r| ]", "-", $parametros['query2']);
			$query = explode('-', $query);
			$query = array_filter($query);
			$query2 = '';
			foreach ($query as $key => $value) {
				$query2 .= "'" . $value . "',";
			}
			$query2 = substr($query2, 0, -1);
			$query = $query2;
		}

		$loc = $parametros['localizacion'];
		if ($parametros['masivo_lo'] == 1) {
			$lista_loc = $parametros['empla_masivo'];
			$lista = '';
			foreach ($lista_loc as $key => $value) {
				$lista .= "'" . $value . "',";
			}
			$lista = substr($lista, 0, -1);
			// print_r($lista);die();
			$loc = $lista;
		}
		$cus = $parametros['custodio'];
		if ($parametros['masivo_cu'] == 1) {
			$lista_cus = $parametros['custodio_masivo'];
			$lista = '';
			foreach ($lista_cus as $key => $value) {
				$lista .= "'" . $value . "',";
			}
			$lista = substr($lista, 0, -1);
			// print_r($lista);die();
			$cus = $lista;
		}
		$pag = $parametros['pag'];
		$exacto = 0;
		$bajas = false;
		$terceros = false;
		$patrimoniales = false;
		if (isset($parametros['exacto']) && $parametros['exacto'] == 'true') {
			$exacto = 1;
		}
		$asset = 0;
		if (isset($parametros['asset']) && $parametros['asset'] == 'true') {
			$asset = 1;
		}
		if (isset($parametros['asset_org']) && $parametros['asset_org'] == 'true') {
			$asset = 2;
		}
		if (isset($parametros['rfid']) && $parametros['rfid'] == 'true') {
			$asset = 0;
		}

		if (isset($parametros['bajas']) && $parametros['bajas'] == 'true') {
			$bajas = 1;
		}
		if (isset($parametros['patri']) && $parametros['patri'] == 'true') {
			$patrimoniales = 1;
		}
		if (isset($parametros['terce']) && $parametros['terce'] == 'true') {
			$terceros = 1;
		}

		// print_r($loc);
		// print_r($parametros);die();	
		$datos = $this->modelo->cantidad_registros($query, $loc, $cus, false, false, $bajas, $patrimoniales, $terceros, $asset, $exacto, $masivo, $masivo_cus, $masivo_loc);
		$total_reg = $datos[0]['numreg'];
		if ($total_reg > 25) {
			$pag = false;
			$datos = $this->modelo->lista_articulos($query, $loc, $cus, $pag, false, $exacto, $asset, $bajas = false, $terceros = false, $patrimoniales = false, false, false, $masivo, $masivo_cus, $masivo_loc);
			if ($bajas) {
				$bajas = $this->modelo->lista_articulos($query, $loc, $cus, $pag, false, $exacto, $asset, $bajas, $terceros = false, $patrimoniales = false, false, false, $masivo, $masivo_cus, $masivo_loc);
				array_merge($datos, $bajas);
			}
			if ($terceros) {
				$terce = $this->modelo->lista_articulos($query, $loc, $cus, $pag, false, $exacto, $asset, $bajas = false, $terceros, $patrimoniales = false, false, false, $masivo, $masivo_cus, $masivo_loc);
				array_merge($datos, $terce);
			}
			if ($patrimoniales) {
				// print_r($patrimoniales);die();
				$patri = $this->modelo->lista_articulos($query, $loc, $cus, $pag, false, $exacto, $asset, $bajas = false, $terceros = false, $patrimoniales, false, false, $masivo, $masivo_cus, $masivo_loc);
				array_merge($datos, $patri);
			}

			// print_r($datos);die();
		} else {
			$datos = $this->modelo->lista_articulos($query, $loc, $cus, false, false, $exacto, $asset, $bajas, $terceros, $patrimoniales, false, false, $masivo, $masivo_cus, $masivo_loc);
		}

		//$datos = array_map(array($this->cod_global, 'transformar_array_encode'), $datos);
		$datos2 = array('datos' => $datos, 'cant' => $total_reg, 'buscar' => $query);

		// print_r($datos2);die();
		return $datos2;
	}


	function add_selected($parametros)
	{
		$respuesta = 1;
		foreach ($parametros as $key => $value) {
			$usuario = $_SESSION['INICIO']['ID_USUARIO'];
			$id = $value;

			$resp = $this->modelo->existe_en_lista($id, $usuario);
			if (count($resp) == 0) {
				$datos[0]['campo'] = 'id_articulo';
				$datos[0]['dato']  = $id;
				$datos[1]['campo'] = 'fecha';
				$datos[1]['dato']  = date('Y-m-d');
				$datos[2]['campo'] = 'estado';
				$datos[2]['dato']  = 0;
				$datos[3]['campo'] = 'usuario';
				$datos[3]['dato']  = $usuario;
				$res = $this->modelo->add('ARTICULOS_ACTAS', $datos);
			} else {
				$respuesta =  -2;
			}
		}
		return $respuesta;
	}

	function addarticulos($parametros)
	{
		$usuario = $_SESSION['INICIO']['ID_USUARIO'];
		$id = $parametros['id'];

		$resp = $this->modelo->existe_en_lista($id, $usuario);
		if (count($resp) == 0) {
			$datos[0]['campo'] = 'id_articulo';
			$datos[0]['dato']  = $id;
			$datos[1]['campo'] = 'fecha';
			$datos[1]['dato']  = date('Y-m-d');
			$datos[2]['campo'] = 'estado';
			$datos[2]['dato']  = 0;
			$datos[3]['campo'] = 'usuario';
			$datos[3]['dato']  = $usuario;
			$res = $this->modelo->add('ARTICULOS_ACTAS', $datos);
			return $res;
		} else {
			return -2;
		}
	}

	function dar_baja($parametros)
	{
		$datos = $this->modelo->lista_actas();
		$resp = 1;
		foreach ($datos as $key => $value) {
			$art = $this->modelo->articulo($value['asset']);

			$datosUP[0]['campo'] = 'CLASE_MOVIMIENTO';
			$datosUP[0]['dato'] = $parametros['movimiento'];
			$datosUP[1]['campo'] = 'BAJAS';
			$datosUP[1]['dato'] = 1;
			$datosUP[2]['campo'] = 'DESCRIPT2';
			$datosUP[2]['dato'] = $parametros['descripcion_mov'];

			$whereup[0]['campo'] = 'id_plantilla';
			$whereup[0]['dato'] = $art[0]['id_plantilla'];

			$r = $this->modelo->update('PLANTILLA_MASIVA', $datosUP, $whereup);
			if ($r != 1) {
				$resp = -1;
			} else {
				$datosM[0]['campo'] = 'obs_movimiento';
				$datosM[0]['dato'] = 'Dato de baja por acta de donaciones';
				$datosM[1]['campo'] = 'fecha_movimiento';
				$datosM[1]['dato'] = date('Y-m-d');
				$datosM[2]['campo'] = 'responsable';
				$datosM[2]['dato'] = $_SESSION['INICIO']['USUARIO'];
				$datosM[3]['campo'] = 'codigo_nue';
				$datosM[3]['dato'] = $parametros['movimiento'];
				$datosM[4]['campo'] = 'dato_nuevo';
				$datosM[4]['dato'] = $parametros['descripcion_mov'];
				$datosM[5]['campo'] = 'id_plantilla';
				$datosM[5]['dato'] = $art[0]['id_plantilla'];
				$datosM[6]['campo'] = 'seccion';
				$datosM[6]['dato'] = 'ACTAS';
				$this->modelo->add('MOVIMIENTO', $datosM);
			}
		}

		return $resp;
	}

	function cambiar_custodio($parametros)
	{
		$datos = $this->modelo->lista_actas();
		$resp = 1;
		foreach ($datos as $key => $value) {
			$art = $this->modelo->articulo($value['asset']);
			$CUSTODIO = $this->custodio->buscar_custodio_todo($id = false, $parametros['idC'], $person_nom = false);
			$LOCATION = $this->localizacion->buscar_localizacion($parametros['idL']);

			$des = ' TEMPORAL';
			if ($parametros['acta'] == 3) {
				$des = ' DEFINITIVO';
			}

			$datosUP[0]['campo'] = 'PERSON_NO';
			$datosUP[0]['dato'] = $CUSTODIO[0]['ID_PERSON'];
			$datosUP[1]['campo'] = 'LOCATION';
			$datosUP[1]['dato'] = $parametros['idL'];
			$datosUP[2]['campo'] = 'DESCRIPT2';
			$datosUP[2]['dato'] = $des;

			$whereup[0]['campo'] = 'id_plantilla';
			$whereup[0]['dato'] = $art[0]['id_plantilla'];

			$r = $this->modelo->update('PLANTILLA_MASIVA', $datosUP, $whereup);
			if ($r != 1) {
				$resp = -1;
			} else {
				$datosM[0]['campo'] = 'obs_movimiento';
				$datosM[0]['dato'] = 'Cambio de CUSTODIO ' . $des;
				$datosM[1]['campo'] = 'fecha_movimiento';
				$datosM[1]['dato'] = date('Y-m-d');
				$datosM[2]['campo'] = 'responsable';
				$datosM[2]['dato'] = $_SESSION['INICIO']['USUARIO'];
				$datosM[3]['campo'] = 'codigo_nue';
				$datosM[3]['dato'] = $parametros['idC'];
				$datosM[4]['campo'] = 'dato_nuevo';
				$datosM[4]['dato'] = $parametros['custodio'];
				$datosM[5]['campo'] = 'codigo_ant';
				$datosM[5]['dato'] = $art[0]['PERSON_NO'];
				$datosM[6]['campo'] = 'dato_anterior';
				$datosM[6]['dato'] = $art[0]['PERSON_NOM'];
				$datosM[7]['campo'] = 'id_plantilla';
				$datosM[7]['dato'] = $art[0]['id_plantilla'];
				$datosM[8]['campo'] = 'seccion';
				$datosM[8]['dato'] = 'ACTAS';
				$this->modelo->add('MOVIMIENTO', $datosM);

				$datosM[0]['campo'] = 'obs_movimiento';
				$datosM[0]['dato'] = 'Cambio de emplazamiento ' . $des;
				$datosM[1]['campo'] = 'fecha_movimiento';
				$datosM[1]['dato'] = date('Y-m-d');
				$datosM[2]['campo'] = 'responsable';
				$datosM[2]['dato'] = $_SESSION['INICIO']['USUARIO'];
				$datosM[3]['campo'] = 'codigo_nue';
				$datosM[3]['dato'] = $LOCATION[0]['EMPLAZAMIENTO'];
				$datosM[4]['campo'] = 'dato_nuevo';
				$datosM[4]['dato'] = $parametros['location'];
				$datosM[5]['campo'] = 'codigo_ant';
				$datosM[5]['dato'] = $art[0]['EMPLAZAMIENTO'];
				$datosM[6]['campo'] = 'dato_anterior';
				$datosM[6]['dato'] = $art[0]['DENOMINACION'];
				$datosM[7]['campo'] = 'id_plantilla';
				$datosM[7]['dato'] = $art[0]['id_plantilla'];
				$datosM[8]['campo'] = 'seccion';
				$datosM[8]['dato'] = 'ACTAS';
				$this->modelo->add('MOVIMIENTO', $datosM);
			}
		}

		return $resp;
	}

	function cambiar_E_S($parametros)
	{
		$datos = $this->modelo->lista_actas();
		$resp = 1;
		foreach ($datos as $key => $value) {
			$art = $this->modelo->articulo($value['asset']);
			$CUSTODIO_E = $this->custodio->buscar_custodio_todo($id = false, $parametros['idCE'], $person_nom = false);
			$LOCATION_E = $this->localizacion->buscar_localizacion($parametros['idLE']);

			$CUSTODIO_S = $this->custodio->buscar_custodio_todo($id = false, $parametros['idCS'], $person_nom = false);
			$LOCATION_S = $this->localizacion->buscar_localizacion($parametros['idLS']);



			$datosUP[0]['campo'] = 'PERSON_NO';
			$datosUP[0]['dato'] = $CUSTODIO_E[0]['ID_PERSON'];
			$datosUP[1]['campo'] = 'LOCATION';
			$datosUP[1]['dato'] = $parametros['idLE'];

			$whereup[0]['campo'] = 'id_plantilla';
			$whereup[0]['dato'] = $art[0]['id_plantilla'];

			$r = $this->modelo->update('PLANTILLA_MASIVA', $datosUP, $whereup);
			if ($r != 1) {
				$resp = -1;
			} else {
				$datosM[0]['campo'] = 'obs_movimiento';
				$datosM[0]['dato'] = 'Cambio de custodio entrada/salida';
				$datosM[1]['campo'] = 'fecha_movimiento';
				$datosM[1]['dato'] = date('Y-m-d');
				$datosM[2]['campo'] = 'responsable';
				$datosM[2]['dato'] = $_SESSION['INICIO']['USUARIO'];
				$datosM[3]['campo'] = 'codigo_nue';
				$datosM[3]['dato'] = $parametros['idCE'];
				$datosM[4]['campo'] = 'dato_nuevo';
				$datosM[4]['dato'] = $parametros['custodioE'];
				$datosM[5]['campo'] = 'codigo_ant';
				$datosM[5]['dato'] = $parametros['idCS'];
				$datosM[6]['campo'] = 'dato_anterior';
				$datosM[6]['dato'] = $parametros['custodioS'];
				$datosM[7]['campo'] = 'id_plantilla';
				$datosM[7]['dato'] = $art[0]['id_plantilla'];
				$datosM[8]['campo'] = 'seccion';
				$datosM[8]['dato'] = 'ACTAS';
				$this->modelo->add('MOVIMIENTO', $datosM);

				$datosM[0]['campo'] = 'obs_movimiento';
				$datosM[0]['dato'] = 'Cambio de emplazamiento entrada/salida';
				$datosM[1]['campo'] = 'fecha_movimiento';
				$datosM[1]['dato'] = date('Y-m-d');
				$datosM[2]['campo'] = 'responsable';
				$datosM[2]['dato'] = $_SESSION['INICIO']['USUARIO'];
				$datosM[3]['campo'] = 'codigo_nue';
				$datosM[3]['dato'] = $LOCATION_E[0]['EMPLAZAMIENTO'];
				$datosM[4]['campo'] = 'dato_nuevo';
				$datosM[4]['dato'] = $parametros['locationE'];
				$datosM[5]['campo'] = 'codigo_ant';
				$datosM[5]['dato'] = $LOCATION_S[0]['EMPLAZAMIENTO'];
				$datosM[6]['campo'] = 'dato_anterior';
				$datosM[6]['dato'] = $parametros['locationS'];
				$datosM[7]['campo'] = 'id_plantilla';
				$datosM[7]['dato'] = $art[0]['id_plantilla'];
				$datosM[8]['campo'] = 'seccion';
				$datosM[8]['dato'] = 'ACTAS';
				$this->modelo->add('MOVIMIENTO', $datosM);
			}
		}

		return $resp;
	}




	function delete_masivo()
	{
		return $this->modelo->eliminar_lista();
		// print_r('da');die();
	}


	function add_masivo($parametros)
	{
		// print_r($parametros);die();
		$usuario = $_SESSION['INICIO']['ID_USUARIO'];
		$query = $parametros['query'];
		$loc = $parametros['localizacion'];
		$cus = $parametros['custodio'];
		$pag = $parametros['pag'];
		$masivo = $parametros['masivo'];
		if (strpos($query, ',') !== false) {

			$masivo = 1;
		}
		if ($parametros['masivo'] == 1) {
			$query = preg_replace("[\n|\r|\n\r| ]", "-", $parametros['query2']);
			$query = explode('-', $query);
			$query = array_filter($query);
			$query2 = '';
			foreach ($query as $key => $value) {
				$query2 .= "'" . $value . "',";
			}
			$query2 = substr($query2, 0, -1);
			$query = $query2;
		}
		if (isset($parametros['exacto']) && $parametros['exacto'] == 'true') {
			$exacto = 1;
		}
		$asset = 0;
		if (isset($parametros['asset']) && $parametros['asset'] == 'true') {
			$asset = 1;
		}
		if (isset($parametros['asset_org']) && $parametros['asset_org'] == 'true') {
			$asset = 2;
		}
		if (isset($parametros['rfid']) && $parametros['rfid'] == 'true') {
			$asset = 0;
		}
		$datosart = $this->modelo->lista_articulos($query, $loc, $cus, false, false, $exacto, $asset, false, false, false, false, false, $masivo);


		$respuesta = 1;
		foreach ($datosart as $key => $value) {
			$resp = $this->modelo->existe_en_lista($value['id'], $usuario);
			if (count($resp) == 0) {
				$datos[0]['campo'] = 'id_articulo';
				$datos[0]['dato']  = $value['id'];
				$datos[1]['campo'] = 'fecha';
				$datos[1]['dato']  = date('Y-m-d');
				$datos[2]['campo'] = 'estado';
				$datos[2]['dato']  = 0;
				$datos[3]['campo'] = 'usuario';
				$datos[3]['dato']  = $usuario;
				$res = $this->modelo->add('ARTICULOS_ACTAS', $datos);
			} else {
				$respuesta = -2;
				// return -2;
			}
		}
		return $respuesta;
	}

	function lista_actas()
	{
		$usuario = $_SESSION['INICIO']['ID_USUARIO'];
		$datos = $this->modelo->lista_actas($usuario);
		return $datos;
		// print_r($datos);die();
	}

	function eliminar_lista($parametros)
	{
		$id = $parametros['id'];
		return $this->modelo->eliminar_lista($id);
	}
}


?>