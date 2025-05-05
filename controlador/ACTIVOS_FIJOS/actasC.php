
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
		$tipo_articulo = false;

		if (isset($parametros['exacto']) && $parametros['exacto'] == 'true') {
			$exacto = 1;
		}

		$asset = 0;
		if (isset($parametros['serie']) && $parametros['serie'] == 'true') {
			$asset = 1;
		} else if (isset($parametros['rfid']) && $parametros['rfid'] == 'true') {
			$asset = 2;
		}

		if (isset($parametros['tipo_articulo'])) {
			$tipo_articulo = $parametros['tipo_articulo'];
		}

		// print_r($loc);
		// print_r($asset);
		// die();

		$datos = $this->modelo->cantidad_registros($query, $loc, $cus, false, false,  $asset, $exacto, $masivo, $masivo_cus, $masivo_loc, $tipo_articulo);
		$total_reg = $datos[0]['numreg'];

		// Si los registros son más de 25, realiza paginación
		if ($total_reg > 25) {
			$pag = false;
			$datos = $this->modelo->lista_articulos($query, $loc, $cus, $pag, false, $exacto, $asset, false, false, $masivo, $masivo_cus, $masivo_loc, $tipo_articulo);
		} else {
			// Si no se necesita paginación
			$datos = $this->modelo->lista_articulos($query, $loc, $cus, false, false, $exacto, $asset, false, false, $masivo, $masivo_cus, $masivo_loc, $tipo_articulo);
		}

		// Agregamos los datos y la cantidad total de registros a la respuesta
		$datos2 = [
			'datos' => $datos,
			'cant' => $total_reg,
			'buscar' => $query
		];

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
				$res = $this->modelo->add('ac_articulos_actas', $datos);
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
			$res = $this->modelo->add('ac_articulos_actas', $datos);
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

			$r = $this->modelo->update('ac_articulos', $datosUP, $whereup);
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
				$this->modelo->add('ac_movimiento', $datosM);
			}
		}

		return $resp;
	}

	function cambiar_custodio($parametros)
	{
		$usuario = $_SESSION['INICIO']['ID_USUARIO'];
		$datos = $this->modelo->lista_actas($usuario);
		$resp = -2;

		foreach ($datos as $key => $value) {
			$art = $this->modelo->articulo($value['tag']);

			$CUSTODIO = $this->custodio->buscar_custodio_todo($id = $parametros['idC'], $person_no = false, $person_nom = false);
			$LOCATION = $this->localizacion->buscar_localizacion($parametros['idL']);

			$des = ' TEMPORAL';
			if ($parametros['acta'] == 3) {
				$des = ' DEFINITIVO';
			}

			$datosUP = array(
				array('campo' => 'th_per_id', 'dato' => $CUSTODIO[0]['ID_PERSON']),
				array('campo' => 'id_localizacion',  'dato' => $parametros['idL']),
				array('campo' => 'descripcion', 'dato' => $des),
			);

			$whereup = array(
				array('campo' => 'id_articulo', 'dato' => $art[0]['id_articulo']),
			);

			$r = $this->modelo->update('ac_articulos', $datosUP, $whereup);

			if ($r != 1) {
				$resp = -1;
			} else {

				$datosM = array(
					array('campo' => 'obs_movimiento',   'dato' => 'Cambio de CUSTODIO ' . trim($des)),
					array('campo' => 'fecha_movimiento', 'dato' => date('Y-m-d')),
					array('campo' => 'responsable',      'dato' => $_SESSION['INICIO']['USUARIO']),
					array('campo' => 'codigo_nue',       'dato' => $parametros['idC']),
					array('campo' => 'dato_nuevo',       'dato' => $parametros['custodio']),
					array('campo' => 'codigo_ant',       'dato' => $art[0]['th_per_id']),
					array('campo' => 'dato_anterior',    'dato' => $art[0]['PERSON_NOM']),
					array('campo' => 'id_plantilla',     'dato' => $art[0]['id_articulo']),
					array('campo' => 'seccion',          'dato' => 'ACTAS'),
					array('campo' => 'id_usuario',       'dato' => $usuario),
				);

				$this->modelo->add('ac_movimiento', $datosM);

				$datosM = array(
					array('campo' => 'obs_movimiento',   'dato' => 'Cambio de LOCALIZACIÓN ' . trim($des)),
					array('campo' => 'fecha_movimiento', 'dato' => date('Y-m-d')),
					array('campo' => 'responsable',      'dato' => $_SESSION['INICIO']['USUARIO']),
					array('campo' => 'codigo_nue',       'dato' => $LOCATION[0]['EMPLAZAMIENTO']),
					array('campo' => 'dato_nuevo',       'dato' => $parametros['location']),
					array('campo' => 'codigo_ant',       'dato' => $art[0]['EMPLAZAMIENTO']),
					array('campo' => 'dato_anterior',    'dato' => $art[0]['DENOMINACION']),
					array('campo' => 'id_plantilla',     'dato' => $art[0]['id_articulo']),
					array('campo' => 'seccion',          'dato' => 'ACTAS'),
					array('campo' => 'id_usuario',       'dato' => $usuario),
				);

				$res = $this->modelo->add('ac_movimiento', $datosM);
			}
		}

		return $res;
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

			$r = $this->modelo->update('ac_articulos', $datosUP, $whereup);
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
				$this->modelo->add('ac_movimiento', $datosM);

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
				$this->modelo->add('ac_movimiento', $datosM);
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
				$res = $this->modelo->add('ac_articulos_actas', $datos);
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