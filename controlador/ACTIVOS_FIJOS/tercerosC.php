<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/tercerosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/reportesM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');
require_once(dirname(__DIR__, 2) . '/lib/pdf/cabecera_pdf.php');
require_once(dirname(__DIR__, 2) . '/lib/excel_spout.php');

/**
 * 
 **/

$controlador = new tercerosC();

if (isset($_GET['lista'])) {
	// print_r($_POST);die();
	$parametros = $_POST['parametros'];
	// print_r($parametros);die();
	echo json_encode($controlador->lista_articulos($parametros));
}

if (isset($_GET['lista_kit'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_kit($parametros));
}

if (isset($_GET['guardar_kit'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_kit($parametros));
}

if (isset($_GET['guardar_it'])) {
	$parametros = $_POST;
	echo json_encode($controlador->guardar_it($parametros));
}

if (isset($_GET['delete_kit'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->delete_kit($parametros));
}

if (isset($_GET['lista_patrimoniales'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_articulos_patrimoniales($parametros));
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

if (isset($_GET['tipo_view'])) {
	$view = $_SESSION['INICIO']['LISTA_ART'];
	echo json_encode($view);
}

if (isset($_GET['articulos_especiales'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->articulos_especiales($parametros));
}

if (isset($_GET['ver_pdf'])) {
	$parametros = array(
		'query' => $_GET['txt_buscar'],
		'localizacion' => $_GET['ddl_localizacion'],
		'custodio' => $_GET['ddl_custodio'],
		'exacto' => $_GET['rbl_exacto'],
		'multiple' => $_GET['rbl_multiple'],
		'desde' => $_GET['txt_desde'],
		'hasta' => $_GET['txt_hasta'],
		'informe' => $_GET['informe'],
		'pag' => $_GET['pag'],
		'buscar_por' => $_GET['buscar_por'],
		'lista' => '1',

	);
	// print_r($parametros);die();
	echo ($controlador->generar_pdf($parametros));
}

if (isset($_GET['ver_excel'])) {
	$parametros = array(
		'query' => $_GET['txt_buscar'],
		'localizacion' => $_GET['ddl_localizacion'],
		'custodio' => $_GET['ddl_custodio'],
		'exacto' => $_GET['rbl_exacto'],
		'multiple' => $_GET['rbl_multiple'],
		'desde' => $_GET['txt_desde'],
		'hasta' => $_GET['txt_hasta'],
		'informe' => $_GET['informe'],
		'pag' => $_GET['pag'],
		'buscar_por' => $_GET['buscar_por'],
		'lista' => '1',

	);
	// print_r($parametros);die();
	echo ($controlador->generar_excel($parametros));
}



class tercerosC
{
	private $modelo;
	private $cod_global;
	private $reportes;
	private $excel;
	private $pdf;

	function __construct()
	{
		$this->modelo = new tercerosM();
		$this->cod_global = new codigos_globales();
		$this->reportes = new reportesM();
		$this->pdf = new cabecera_pdf();
		$this->excel = new excel_spout();
	}

	function lista_articulos($parametros)
	{
		// print_r($parametros);die();
		$query = $parametros['query'];
		$loc = $parametros['localizacion'];
		$cus = $parametros['custodio'];
		$pag = $parametros['pag'];
		$coincidencia = $parametros['exacto'];
		$multiple = $parametros['multiple'];
		$buscar_por = $parametros['buscar_por'];
		if ($parametros['desde'] != '') {
			$desde = $parametros['desde'];
		} else {
			$desde = false;
		}
		if ($parametros['hasta'] != '') {
			$hasta = $parametros['hasta'];
		} else {
			$hasta = false;
		}

		$_SESSION['INICIO']['LISTA_ART'] = $parametros['lista'];



		$datos = $this->modelo->cantidad_registros_new($query, $loc, $cus, false, $desde, $hasta, $coincidencia, $multiple, $buscar_por);
		$total_reg = $datos[0]['numreg'];
		if ($total_reg > 25) {
			$datos = $this->modelo->lista_articulos_new($query, $loc, $cus, $pag, $desde, $hasta, $coincidencia, $multiple, $buscar_por);
		} else {
			$pag = false;
			$datos = $this->modelo->lista_articulos_new($query, $loc, $cus, $pag, $desde, $hasta, $coincidencia, $multiple, $buscar_por);
		}

		//$datos = array_map(array($this->cod_global, 'transformar_array_encode'), $datos);
		$datos2 = array('datos' => $datos, 'cant' => $total_reg);

		// print_r($datos2);die();
		return $datos2;
	}

	function lista_kit($parametros)
	{
		$datos2 = $this->modelo->lista_kit($parametros['activo']);
		return $datos2;
	}

	function delete_kit($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo'] = 'id_plantilla';
		$datos[0]['dato'] = $parametros['id'];

		return $this->modelo->eliminar($datos, 'PLANTILLA_MASIVA');
	}

	function guardar_kit($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo'] = 'KIT';
		$datos[0]['dato'] = $parametros['activo'];
		$datos[1]['campo'] = 'DESCRIPT';
		$datos[1]['dato'] = $parametros['nombre'];
		$datos[2]['campo'] = 'CARACTERISTICA';
		$datos[2]['dato'] = $parametros['identificador'];
		$datos[3]['campo'] = 'OBSERVACION';
		$datos[3]['dato'] = $parametros['observacion'];
		$datos[4]['campo'] = 'ACTIVO';  // NO ES UN ACTIVO 
		$datos[4]['dato'] = '0';
		/*$datos[5]['campo'] = 'KIT';
		$datos[5]['dato'] = '1';*/

		return $this->modelo->insertar($datos, $tabla = 'PLANTILLA_MASIVA');
	}

	function guardar_it($parametros)
	{
		$datos[0]['campo'] = 'SISTEMA_OP';
		$datos[0]['dato'] = $parametros['txt_sistema_op'];
		$datos[1]['campo'] = 'ARQUITECTURA';
		$datos[1]['dato'] = $parametros['txt_arquitectura'];
		$datos[2]['campo'] = 'KERNEL';
		$datos[2]['dato'] = $parametros['txt_kernel'];
		$datos[3]['campo'] = 'PRODUCTO_ID';
		$datos[3]['dato'] = $parametros['txt_producto_id'];
		$datos[4]['campo'] = 'VERSION';
		$datos[4]['dato'] = $parametros['txt_version'];
		$datos[5]['campo'] = 'SERVICE_PACK';
		$datos[5]['dato'] = $parametros['txt_service_pack'];
		$datos[6]['campo'] = 'EDICION';
		$datos[6]['dato'] = $parametros['txt_edicion'];
		$datos[7]['campo'] = 'IT';
		$datos[7]['dato'] = '1';

		$where[0]['campo'] = 'id_plantilla';
		$where[0]['dato'] = $parametros['id'];

		return $this->modelo->update($tabla = 'PLANTILLA_MASIVA', $datos, $where);
	}

	function lista_articulos_patrimoniales($parametros)
	{
		// print_r($parametros);die();
		$query = $parametros['query'];
		$loc = $parametros['localizacion'];
		$cus = $parametros['custodio'];
		$pag = $parametros['pag'];
		$exacto = false;
		if (isset($parametros['exacto'])) {
			$exacto = $parametros['exacto'];
		}
		$asset = '';
		if (isset($parametros['asset'])) {
			$asset = $parametros['asset'];
		}
		$asset_org = '';
		if (isset($parametros['asset_org'])) {
			$asset_org = $parametros['asset_org'];
		}

		if ($exacto == 'true') {
			$exacto  = true;
		} else {
			$exacto = false;
		}
		if ($asset == 'true') {
			$asset  = true;
		} else {
			$asset = false;
		}

		$datos = $this->modelo->cantidad_registros_patrimoniales($query, $loc, $cus);
		$total_reg = $datos[0]['numreg'];
		if ($total_reg > 25) {
			$datos = $this->modelo->lista_articulos($query, $loc, $cus, $pag, false, $exacto, $asset, false, false, 1);
		} else {
			$datos = $this->modelo->lista_articulos($query, $loc, $cus, false, false, $exacto, $asset, false, false, 1);
		}

		//$datos = array_map(array($this->cod_global, 'transformar_array_encode'), $datos);
		$datos2 = array('datos' => $datos, 'cant' => $total_reg);

		// print_r($datos2);die();
		return $datos2;
	}

	function lista_articulos_impri($parametros)
	{
		$v = $this->modelo->existe_datos();
		if ($v == -1) {

			$asset = strtoupper($parametros['query']);
			$asset = str_replace('ASSET:', '', $asset);
			$parametros['query'] = $asset;
			$query = $parametros['query'];
			$loc = $parametros['localizacion'];
			$cus = $parametros['custodio'];
			$pag = $parametros['pag'];
			$datos = $this->modelo->lista_articulos($query, $loc, $cus, $pag);
			foreach ($datos as $key => $value) {
				if ($value['RFID'] == '') {
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
						$this->modelo->insertar($datoss2, 'IMPRIMIR_TAGS');
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
						$this->modelo->insertar($datoss2, 'IMPRIMIR_TAGS');
					}
				} else {
					$datoss2[0]['campo'] = 'RFID';
					$datoss2[0]['dato'] = $value['RFID'];
					$datoss2[1]['campo'] = 'SERIE';
					$datoss2[1]['dato'] = $value['tag'];
					$this->modelo->insertar($datoss2, 'IMPRIMIR_TAGS');
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

				$this->modelo->insertar($datoss2, 'IMPRIMIR_TAGS');
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

	function articulos_especiales($parametros)
	{
		if ($parametros['articulos'] == 0) {
			$datos = $this->modelo->lista_articulos($query = false, $loc = false, $cus = false, $pag = false, $whereid = false, $exacto = false, $asset = false, $bajas = $parametros['bajas'], $terceros = $parametros['terceros'], $patrimoniales = $parametros['patrimoniales'], $desde = false, $hasta = false);
		} else {
			$eti = $this->modelo->cantidad_etiquetas();
			$datos = $this->modelo->cantidad_registros();
			$datos[1] = $eti[0];
		}

		return $datos;
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
		$datos = $this->modelo->eliminar($delete, 'IMPRIMIR_TAGS');
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

	function sql_busqueda($parametros)
	{
		// print_r($parametros);die();
		$id = $parametros['informe'];
		//buscamos el informe corespondiente
		$datos = $this->reportes->datos_reporte($id);
		$titulo = $datos[0]['NOMBRE_REPORTE'];


		// obtebnemos el sql con los filtros que se necesitan
		$this->lista_articulos($parametros);
		$datos2 = $this->modelo->set_get_sql();


		//procesamos el select del reporte

		$partes_sql = explode('FROM', $datos[0]['SQL']);
		$select_sql = $partes_sql[0];

		//join

		$joins = explode('LEFT JOIN', $partes_sql[1]);
		// print_r($joins);die();
		$tablas = array();
		$tabla_pricipal = '';
		foreach ($joins as $key => $value) {
			if (strpos($value, 'ON') !== false) {

				$tbl_join = explode(' ON ', $value);
				$tabla_join = explode(' ', trim($tbl_join[0]));
				array_push($tablas, $tabla_join[1]);
			}
			if ($key == 0) {
				$tabla_join = explode(' ', trim($value));
				array_push($tablas, $tabla_join[1]);
				$tabla_pricipal = $tabla_join[0];
			}
		}


		// print_r($datos[0]['SQL']);
		// print_r($select_sql);
		// die();

		// select re organizado
		foreach ($tablas as $key => $value) {
			if ($key != 0) {
				$letra = $this->cod_global->tablas_asociadas($value);
				$sql_new = str_replace(',' . $value . '.', ',' . $letra . '.', $sql_new);
			} else {
				$letra = $this->cod_global->tablas_asociadas($tabla_pricipal);
				$letra_principal = $tablas[0];
				$sql_new = str_replace(',' . $letra_principal . '.', ',' . $letra . '.', $select_sql);
				$sql_new = str_replace(' ' . $letra_principal . '.', ' ' . $letra . '.', $sql_new);
			}
			// code...
		}


		//separamos el sql de los filtros y joins de la busqueda

		$join = explode('FROM', $datos2);

		$from = $join[1];

		$new_sql = $sql_new . ' FROM ' . $from;
		// print_r($new_sql);die();

		return array('sql' => $new_sql, 'titulo' => $titulo);


		// salta ghenerar el pdf
	}

	function generar_excel($parametros)
	{
		// print_r($parametros);die();
		$reporte = $this->sql_busqueda($parametros);

		$datos = $this->modelo->ejecutar_sql($reporte['sql']);

		// print_r($datos);die();

		$header = array();
		$cant_header = 0;
		$data = array();
		$alineado = array();
		$medidas = array();
		foreach ($datos as $key => $value) {
			$data[$key] = array();
			foreach ($value as $key2 => $value2) {
				array_push($alineado, 'L');
				if ($key == 0) {
					$header[] = $key2;
					$cant_header += 1;
				}
				if (is_object($value2)) {
					array_push($data[$key], $value2->format('Y-m-d'));
				} else {
					array_push($data[$key], $value2);
				}
			}
		}

		// print_r($reporte);die();

		return $this->excel->basic_excel($header, $data, $reporte['titulo']);
	}

	function generar_pdf($parametros)
	{
		// print_r($parametros);die();
		$reporte = $this->sql_busqueda($parametros);

		$datos = $this->modelo->ejecutar_sql($reporte['sql']);

		// print_r($datos);die();

		$header = array('N°');
		$cant_header = 1;
		$data = array();
		$alineado = array();
		$medidas = array();
		foreach ($datos as $key => $value) {
			$data[$key] = array();
			array_push($data[$key], ($key + 1));
			array_push($alineado, 'L');
			foreach ($value as $key2 => $value2) {
				array_push($alineado, 'L');
				if ($key == 0) {
					$header[] = $key2;
					$cant_header += 1;
				}
				if (is_object($value2)) {
					array_push($data[$key], $value2->format('Y-m-d'));
				} else {
					array_push($data[$key], $value2);
				}
			}
		}

		$med_pag = 280;
		$med = $med_pag / $cant_header;
		foreach ($header as $key => $value) {
			array_push($medidas, $med);
		}

		$sizetable = 5;
		$titulo = "Reporte bajas";

		$pos = 1;

		$tablaHTML = array();
		$tablaHTML[0]['medidas'] = $medidas;
		$tablaHTML[0]['alineado'] = $alineado;
		$tablaHTML[0]['datos'] = $header;
		$tablaHTML[0]['estilo'] = 'BI';
		$tablaHTML[0]['borde'] = '1';

		foreach ($data as $key => $value) {
			$tablaHTML[$pos]['medidas'] = $medidas;
			$tablaHTML[$pos]['alineado'] = $alineado;
			$tablaHTML[$pos]['datos'] = $value;
			// $tablaHTML[$pos]['estilo']='BI';
			$tablaHTML[$pos]['borde'] = '1';
			$pos += 1;
		}

		return $this->pdf->cabecera_reporte_MC($titulo, $tablaHTML, $contenido = false, $image = false, 'fecha', 'fecha', $sizetable, true, $sal_hea_body = 30, $orientacion = 'H');
	}

	function quitar_carac($query)
	{
		$query = preg_replace("[\n|\r|\n\r]", "", $query);
		$buscar = array("'", '%', 'LIKE', 'like', 'between', 'BETWEEN', '=', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'SELECT', 'select', 'Select');
		$remplaza = array('');
		$corregido = str_replace($buscar, $remplaza, $query);
		// print_r($corregido);
		$corregido = trim($corregido);
		return $corregido;
	}
}
