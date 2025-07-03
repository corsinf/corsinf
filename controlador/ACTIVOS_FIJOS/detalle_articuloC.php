<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/detalle_articuloM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/coloresM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/generoM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/marcasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/estadoM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/proyectosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/custodioM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/localizacionM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/CATALOGOS/ac_cat_tipo_articuloM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/CATALOGOS/ac_cat_unidad_medidaM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/clase_movimientoM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/familiasM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

// require_once('../modelo/coloresM.php');

/**
 * 
 **/

$controlador = new detalle_articuloC();

if (isset($_GET['detalle'])) {
	echo json_encode($controlador->lista_detalle_articulo($id, 15));
}

if (isset($_GET['movimientos'])) {
	$parametros = $_POST['parametros'];
	// print_r($parametros);die();
	echo json_encode($controlador->movimientos($parametros));
}

if (isset($_GET['colores'])) {
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_colores($query));
}

if (isset($_GET['marca'])) {
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_marca($query));
}

if (isset($_GET['genero'])) {
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_genero($query));
}

if (isset($_GET['proyecto'])) {
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_proyecto($query));
}

if (isset($_GET['cargar_datos'])) {
	echo json_encode($controlador->cargar_datos($_POST['id']));
}

if (isset($_GET['cargar_tarjeta'])) {
	echo json_encode($controlador->cargar_tarjeta($_POST['id']));
}

if (isset($_GET['guardarArticulo'])) {
	echo json_encode($controlador->guardar_datos($_POST['parametros']));
}

if (isset($_GET['guardarArticulo_patrimonial'])) {
	echo json_encode($controlador->guardar_datos_patrimoniales($_POST['parametros']));
}

if (isset($_GET['cargar_imagen'])) {

	echo json_encode($controlador->guardar_foto($_FILES, $_POST));
}

if (isset($_GET['navegacion'])) {
	echo json_encode($controlador->navegacion($_POST['parametros']));
}

if (isset($_GET['tarjeta_guardar'])) {
	echo json_encode($controlador->tarjeta_guardar($_POST['parametros']));
}

if (isset($_GET['add_info'])) {
	$parametros = $_POST;
	echo json_encode($controlador->add_info($parametros));
}

if (isset($_GET['cargar_detalle_activo'])) {
	echo json_encode($controlador->cargar_detalle_activo($_POST['id'] ?? '', $_POST['token'] ?? ''));
}

if (isset($_GET['actualizarDatosArticuloDepreciacion'])) {
	echo json_encode($controlador->insertar_editar_depreciacion($_POST));
	return;
}


class detalle_articuloC
{
	private $modelo;
	private $colores;
	private $genero;
	private $marca;
	private $cod_globales;
	private $proyectos;
	private $localizacion;
	private $custodio;
	private $estado;
	private $tipo_articulo;
	private $unidad_medida;
	private $clase_movimiento;
	private $familias;

	function __construct()
	{
		$this->modelo = new detalle_articuloM();
		$this->colores = new coloresM();
		$this->genero = new generoM();
		$this->marca = new marcasM();
		$this->estado = new  estadoM();
		$this->localizacion = new localizacionM();
		$this->custodio = new custodioM();
		$this->cod_globales = new codigos_globales();
		$this->proyectos = new proyectosM();
		$this->tipo_articulo = new ac_cat_tipo_articuloM();
		$this->unidad_medida = new ac_cat_unidad_medidaM();
		$this->clase_movimiento = new clase_movimientoM();
		$this->familias = new familiasM();
	}

	function lista_detalle_articulo($id, $pag)
	{
		$datos = $this->modelo->lista_detalle_articulo($id, $pag);
		// print_r(count($datos));
		// $resultado =  array('datos' => $datos,'num'=>count($datos));
		return $datos;
	}

	function buscar_colores($query)
	{
		$datos = $this->colores->buscar_colores($query);
		$respuesta = array();
		foreach ($datos as $key => $value) {
			$respuesta[] = array('id' => $value['ID_COLORES'], 'text' => $value['DESCRIPCION'], 'data' => $value);
		}

		return $respuesta;
	}

	function buscar_genero($query)
	{
		$datos = $this->genero->buscar_genero($query);
		$respuesta = array();
		foreach ($datos as $key => $value) {
			$respuesta[] = array('id' => $value['ID_GENERO'], 'text' => $value['DESCRIPCION'], 'data' => $value);
		}

		return $respuesta;
	}

	function buscar_proyecto($query)
	{
		$datos = $this->proyectos->buscar_proyecto($query);
		$respuesta = array();
		foreach ($datos as $key => $value) {
			$respuesta[] = array('id' => $value['id'], 'text' => $value['deno'], 'data' => $value);
		}

		return $respuesta;
	}

	function buscar_marca($query)
	{
		$datos = $this->marca->buscar_marcas($query);
		$respuesta = array();
		foreach ($datos as $key => $value) {
			$respuesta[] = array('id' => $value['ID_MARCA'], 'text' => $value['DESCRIPCION'], 'data' => $value);
		}

		return $respuesta;
	}

	function cargar_datos($id)
	{
		$datos = $this->modelo->cargar_datos($id);

		$ruta = $_SESSION['INICIO']['RUTA_IMG_RELATIVA'];
		$empresa = $_SESSION['INICIO']['BASEDATO'];

		$datos[0]['ruta_imagen'] = $ruta . "emp=$empresa&dir=activos&nombre=" .  $datos[0]['imagen'];

		return $datos;
	}

	function cargar_tarjeta($id)
	{
		$datos = $this->modelo->cargar_tarjeta($id);
		if (count($datos) > 0) {
			return $datos;
		}
		return '';
	}

	function tarjeta_guardar($parametros)
	{
		// print_r($parametros);die();		
		$datos[0]['campo'] = 'ARTICULO';
		$datos[0]['dato'] = $parametros['articulo'];
		$datos[1]['campo'] = 'HTML_INFO';
		$datos[1]['dato'] = $parametros['tarjeta'];
		if ($parametros['id_tarjeta'] == '') {
			return $this->modelo->guardar($tabla = 'ac_tarjeta_info', $datos);
		} else {
			$where[0]['campo'] = 'ID_PATRIMONIAL';
			$where[0]['dato'] = $parametros['id_tarjeta'];
			return $this->modelo->update_data('ac_datos_patrimonial', $datos, $where);
		}
	}

	function movimientos($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->movimientos($parametros['id'], $parametros['desde'], $parametros['hasta']);

		// print_r($datos);die();
		//$datos = array_map(array($this->cod_globales, 'transformar_array_encode'), $datos);
		return $datos;
	}

	function guardar_datos($parametros)
	{

		$datos = array(
			array('campo' => 'tag_unique', 'dato' => $parametros['txt_rfid']),
			array('campo' => 'longitud_rfid', 'dato' => $parametros['rbl_asset']),
			array('campo' => 'tag_serie', 'dato' => $parametros['txt_tag_serie']),
			array('campo' => 'tag_antiguo', 'dato' => $parametros['txt_tag_anti']),
			array('campo' => 'subnumero', 'dato' => $parametros['txt_subno']),
			array('campo' => 'th_per_id', 'dato' => $parametros['ddl_custodio']),
			array('campo' => 'descripcion', 'dato' => $parametros['txt_descripcion']),
			array('campo' => 'descripcion_2', 'dato' => $parametros['txt_descripcion_2']),
			array('campo' => 'caracteristica', 'dato' => $parametros['txt_carac']),
			array('campo' => 'observaciones', 'dato' => $parametros['txt_observacion']),
			array('campo' => 'modelo', 'dato' => $parametros['txt_modelo']),
			array('campo' => 'serie', 'dato' => $parametros['txt_serie']),
			array('campo' => 'cantidad', 'dato' => $parametros['txt_cant']),
			array('campo' => 'precio', 'dato' => $parametros['txt_valor']),
			// array('campo' => 'imagen', 'dato' => $parametros['']),
			array('campo' => 'kit', 'dato' => $parametros['cbx_kit']),
			array('campo' => 'maximo', 'dato' => $parametros['txt_maximo']),
			array('campo' => 'minimo', 'dato' => $parametros['txt_minimo']),
			array('campo' => 'id_unidad_medida', 'dato' => $parametros['ddl_unidad']),
			array('campo' => 'id_tipo_articulo', 'dato' => $parametros['rbl_tip_articulo']),
			array('campo' => 'id_familia', 'dato' => $parametros['ddl_familia']),
			array('campo' => 'id_subfamilia', 'dato' => $parametros['ddl_subfamilia']),
			array('campo' => 'id_localizacion', 'dato' => $parametros['ddl_localizacion']),
			array('campo' => 'id_marca', 'dato' => $parametros['ddl_marca']),
			array('campo' => 'id_estado', 'dato' => $parametros['ddl_estado']),
			array('campo' => 'id_genero', 'dato' => $parametros['ddl_genero']),
			array('campo' => 'id_color', 'dato' => $parametros['ddl_color']),
			array('campo' => 'id_proyecto', 'dato' => $parametros['ddl_proyecto']),
			array('campo' => 'id_clase_movimiento', 'dato' => $parametros['ddl_clase_mov']),
			array('campo' => 'centro_costos', 'dato' => $parametros['txt_centro_costos']),
			array('campo' => 'resp_cctr', 'dato' => $parametros['txt_resp_cctr']),
			array('campo' => 'companycode', 'dato' => $parametros['txt_company']),
			array('campo' => 'funds_ctr_apc', 'dato' => $parametros['txt_funds_ctr_apc']),
			array('campo' => 'profit_ctr', 'dato' => $parametros['txt_profit_ctr']),
			array('campo' => 'id_usuario_actualizar', 'dato' => $_SESSION['INICIO']['ID_USUARIO']),
			// array('campo' => 'fecha_creacion', 'dato' => $parametros['']),
			array('campo' => 'fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
			// array('campo' => 'fecha_baja', 'dato' => $parametros['']),
			array('campo' => 'fecha_referencia', 'dato' => $parametros['txt_fecha']),
			array('campo' => 'fecha_contabilizacion', 'dato' => $parametros['txt_compra']),
			array('campo' => 'es_it', 'dato' => $parametros['cbx_detalle_it']),
			// array('campo' => 'id_rubro', 'dato' => $parametros['']),
		);

		// print_r($datos); exit(); die();

		$where = array(
			array('campo' => 'id_articulo', 'dato' => $parametros['idAr']),
		);

		//Registra todos los registros de los articulos
		$movimiento = $this->comparacion_movimiento($parametros['idAr'], $parametros);

		// print_r($movimiento);
		// exit();
		// die();

		$datos = $this->modelo->editar($datos, $where);
		return $datos;

		// if ($respuesta == 1 and $respuesta1 == 1) {
		// 	$texto =
		// 		(isset($parametros['company']) ? $parametros['company'] : '') . ';' .
		// 		(isset($parametros['asse']) ? $parametros['asse'] : '') . ';0;' .
		// 		(isset($parametros['desc']) ? $parametros['desc'] : '') . ';' .
		// 		(isset($parametros['des2']) ? $parametros['des2'] : '') . ';' .
		// 		(isset($parametros['mode']) ? $parametros['mode'] : '') . ';' .
		// 		(isset($parametros['seri']) ? $parametros['seri'] : '') . ';' .
		// 		(isset($parametros['rfid']) ? $parametros['rfid'] : '') . ';' .
		// 		(isset($parametros['fech']) ? $parametros['fech'] : '') . ';' .
		// 		(isset($parametros['cant']) ? $parametros['cant'] : '') . ';' .
		// 		(isset($parametros['uni']) ? $parametros['uni'] : '') . ';' .
		// 		(isset($loc[0]['EMPLAZAMIENTO']) ? $loc[0]['EMPLAZAMIENTO'] : '') . ';' .
		// 		(isset($loc[0]['DENOMINACION']) ? $loc[0]['DENOMINACION'] : '') . ';' .
		// 		(isset($cus[0]['PERSON_NO']) ? $cus[0]['PERSON_NO'] : '') . ';' .
		// 		(isset($cus[0]['PERSON_NOM']) ? $cus[0]['PERSON_NOM'] : '') . ';' .
		// 		(isset($marca[0]['CODIGO']) ? $marca[0]['CODIGO'] : '') . ';' .
		// 		(isset($est[0]['CODIGO']) ? $est[0]['CODIGO'] : '') . ';' .
		// 		(isset($genero[0]['CODIGO']) ? $genero[0]['CODIGO'] : '') . ';' .
		// 		(isset($color[0]['CODIGO']) ? $color[0]['CODIGO'] : '') . ';' .
		// 		(isset($pro[0]['pro']) ? $pro[0]['pro'] : '') . ';' .
		// 		(isset($parametros['assetno']) ? $parametros['assetno'] : '') . ';' .
		// 		(isset($parametros['act']) ? $parametros['act'] : '') . ';' .
		// 		(isset($parametros['compra']) ? $parametros['compra'] : '') . ';' .
		// 		(isset($parametros['valor']) ? $parametros['valor'] : '') . ';' .
		// 		(isset($parametros['obse']) ? $parametros['obse'] : '') . ';' .
		// 		(isset($parametros['cara']) ? $parametros['cara'] : '') . ';' .
		// 		'fecha_descapitalizacion;' .
		// 		(isset($parametros['bajas']) ? $parametros['bajas'] : '') . ';';

		// 	//print_r($texto);

		// 	if ($movimientos != '') {
		// 		$this->cod_globales->para_ftp('plantilla_masiva', $texto);
		// 		$this->cod_globales->ingresar_movimientos($parametros['idAr'], $movimientos);
		// 	}
		// 	return 1;
		// } else {
		// 	return -1;
		// }
	}

	function cargar_detalle_activo($id, $id_empresa)
	{
		$id = $this->cod_globales->desencriptar_alfanumerico($id);
		$id_empresa = $this->cod_globales->desencriptar_alfanumerico($id_empresa);

		if (!$id || !$id_empresa) {
			return 'Error';
			exit;
		}

		$datos = $this->modelo->cargar_datos_vista_publica($id, $id_empresa);

		if (count($datos) > 0) {
			return $datos;
		}
		return '';
	}

	/**
	 * 
	 */

	function guardar_datos_patrimoniales($parametros)
	{


		$loc = $this->localizacion->buscar_localizacion($parametros['loca']);
		$cus = $this->custodio->buscar_custodio_todo($parametros['cust']); //ojo con el custodio
		$marca = $this->marca->buscar_marcas_all($buscar = false, $parametros['marc']);
		$est = $this->estado->lista_estado_todo($parametros['esta']);
		$genero = $this->genero->lista_genero_todo($parametros['gene']);
		$color = $this->colores->lista_colores_todo($parametros['colo']);

		// print_r($parametros);die();
		$pro = $this->proyectos->lista_proyectos($parametros['crit']);

		$bajas = 0;
		$tercero = 0;
		$patrimoniales = 0;
		// print_r($parametros);die();
		$datos[0]['campo'] = 'TAG_UNIQUE';
		$datos[0]['dato'] = $parametros['rfid'];
		$datos[1]['campo'] = 'TAG_SERIE';
		$datos[1]['dato'] = $parametros['asse'];
		$datos[2]['campo'] = 'TAG_ANT';
		$datos[2]['dato'] = $parametros['tagA'];
		if ($parametros['idAs'] != '') {
			$datos[0]['ID_ASSET'] = $parametros['idAs'];
			$where[0]['campo'] = 'ID_ASSET';
			$where[0]['dato'] = $parametros['idAs'];
			$respuesta = $this->modelo->update_data('ac_asset', $datos, $where);
		} else {
			$exis = $this->modelo->buscar_asset($tag = $parametros['asse'], $ant = false, $rfid = false);
			if (count($exis)) {
				return -2;
			}
			$exis2 = $this->modelo->buscar_asset($tag = false, $ant = $parametros['tagA'], $rfid = false);
			if (count($exis2)) {
				return -3;
			}

			$respuesta = $this->modelo->guardar('ac_asset', $datos);
			$datos = $this->modelo->buscar_asset($tag = $parametros['asse'], $ant = $parametros['tagA'], $rfid = false);
			// print_r($datos);die();
		}
		$datos1[0]['campo'] = 'ID_ASSET';
		$datos1[0]['dato'] = $datos[0]['ID_ASSET'];
		$datos1[1]['campo'] = 'DESCRIPT';
		$datos1[1]['dato'] = $parametros['desc'];
		$datos1[2]['campo'] = 'DESCRIPT2';
		$datos1[2]['dato'] = $parametros['des2'];
		$datos1[3]['campo'] = 'SERIE';
		$datos1[3]['dato'] = $parametros['seri'];
		$datos1[4]['campo'] = 'FECHA_INV_DATE';
		$datos1[4]['dato'] = $parametros['fech'];
		$datos1[5]['campo'] = 'LOCATION';
		$datos1[5]['dato'] = $parametros['loca'];
		$datos1[6]['campo'] = 'PERSON_NO';
		$datos1[6]['dato'] = $parametros['cust'];
		$datos1[7]['campo'] = 'OBSERVACION';
		$datos1[7]['dato'] = $parametros['obse'];
		$datos1[8]['campo'] = 'EVALGROUP1';
		$datos1[8]['dato'] = $parametros['marc'];
		$datos1[9]['campo'] = 'EVALGROUP2';
		$datos1[9]['dato'] = $parametros['esta'];
		$datos1[10]['campo'] = 'EVALGROUP3';
		$datos1[10]['dato'] = $parametros['gene'];
		$datos1[11]['campo'] = 'EVALGROUP4';
		$datos1[11]['dato'] = $parametros['colo'];
		$datos1[12]['campo'] = 'BASE_UOM';
		$datos1[12]['dato'] = $parametros['uni'];
		$datos1[13]['campo'] = 'CARACTERISTICA';
		$datos1[13]['dato'] = $parametros['cara'];
		$datos1[14]['campo'] = 'ORIG_ACQ_YR';
		$datos1[14]['dato'] = date('Y-m-d', strtotime($parametros['compra']));
		// $datos1[15]['campo']='ORIG_ASSET';
		// $datos1[15]['dato']=$parametros['act'];
		$datos1[15]['campo'] = 'ORIG_VALUE';
		$datos1[15]['dato'] = $parametros['valor'];
		$datos1[16]['campo'] = 'QUANTITY';
		$datos1[16]['dato'] = $parametros['cant'];
		$datos1[17]['campo'] = 'EVALGROUP5';
		$datos1[17]['dato'] = $parametros['crit'];
		$datos1[18]['campo'] = 'ORIG_ASSET';
		$datos1[18]['dato'] = $parametros['tagA'];
		$datos1[19]['campo'] = 'MODELO';
		$datos1[19]['dato'] = $parametros['mode'];


		if ($parametros['bajas'] == 'true') {
			$bajas = 1;
		}
		$datos1[20]['campo'] = 'BAJAS';
		$datos1[20]['dato'] = $bajas;

		if ($parametros['terceros'] == 'true') {
			$tercero = 1;
		}
		$datos1[21]['campo'] = 'TERCEROS';
		$datos1[21]['dato'] = $tercero;

		if ($parametros['patrimoniales'] == 'true') {
			$patrimoniales = 1;
		}
		$datos1[22]['campo'] = 'PATRIMONIALES';
		$datos1[22]['dato'] = $patrimoniales;


		$datos1[23]['campo'] = 'CLASE_MOVIMIENTO';
		$datos1[23]['dato'] = $parametros['clase_mov'];


		// print_r($datos1);die();

		if ($parametros['idAr'] != '') {

			$this->comparacion_movimiento($parametros['idAr'], $parametros);
			$where1[0]['campo'] = 'id_plantilla';
			$where1[0]['dato'] = $parametros['idAr'];
			$respuesta1 = $this->modelo->update_data('ac_articulos', $datos1, $where1);
		} else {
			$respuesta1 = $this->modelo->guardar('ac_articulos', $datos1);
			$movimientos = 'nuevo ingreso';
		}
		if ($respuesta == 1 and $respuesta1 == 1) {
			$texto = $parametros['company'] . ';' . $parametros['asse'] . ';0;' . $parametros['desc'] . ';' . $parametros['des2'] . ';' . $parametros['mode'] . ';' . $parametros['seri'] . ';' . $parametros['rfid'] . ';' . $parametros['fech'] . ';' . $parametros['cant'] . ';' . $parametros['uni'] . ';' . $loc[0]['EMPLAZAMIENTO'] . ';' . $loc[0]['DENOMINACION'] . ';' . $cus[0]['PERSON_NO'] . ';' . $cus[0]['PERSON_NOM'] . ';' . $marca[0]['CODIGO'] . ';' . $est[0]['CODIGO'] . ';' . $genero[0]['CODIGO'] . ';' . $color[0]['CODIGO'] . ';' . $pro[0]['pro'] . ';' . $parametros['assetno'] . ';' . $parametros['act'] . ';' . $parametros['compra'] . ';' . $parametros['valor'] . ';' . $parametros['obse'] . ';' . $parametros['cara'] . ';fecha_descapitalizacion;' . $parametros['bajas'] . ';';

			//if($movimientos!='')
			//{			
			//$this->cod_globales->para_ftp('patrimoniales',$texto);
			//$this->cod_globales->ingresar_movimientos($parametros['idAr'],$movimientos);
			//}
			$id = $this->modelo->buscar_plantilla_masiva($idAsset = $datos[0]['ID_ASSET']);
			return $id[0]['id_plantilla'];
		} else {
			return -1;
		}
	}

	function comparacion_movimiento($id_articulo, $parametros)
	{
		$datos_1 = $this->modelo->cargar_datos($id_articulo);

		$campos = array(
			array('campo' => 'rfid', 'dato' => $parametros['txt_rfid'], 'label' => 'RFID', 'tipo' => 'texto'),
			array('campo' => 'longitud_rfid', 'dato' => $parametros['rbl_asset'], 'label' => 'LONGITUD RFID', 'tipo' => 'texto'),
			array('campo' => 'tag_s', 'dato' => $parametros['txt_tag_serie'], 'label' => 'SERIE', 'tipo' => 'texto'),
			array('campo' => 'ant', 'dato' => $parametros['txt_tag_anti'], 'label' => 'TAG ANTIGUO', 'tipo' => 'texto'),
			array('campo' => 'subnum', 'dato' => $parametros['txt_subno'], 'label' => 'SUBNÚMERO', 'tipo' => 'texto'),

			array('campo' => 'nom', 'dato' => $parametros['txt_descripcion'], 'label' => 'DESCRIPCIÓN', 'tipo' => 'texto'),
			array('campo' => 'des', 'dato' => $parametros['txt_descripcion_2'], 'label' => 'DESCRIPCIÓN 2', 'tipo' => 'texto'),
			array('campo' => 'carac', 'dato' => $parametros['txt_carac'], 'label' => 'CARACTERÍSTICAS', 'tipo' => 'texto'),
			array('campo' => 'obs', 'dato' => $parametros['txt_observacion'], 'label' => 'OBSERVACIONES', 'tipo' => 'texto'),
			array('campo' => 'mod', 'dato' => $parametros['txt_modelo'], 'label' => 'MODELO', 'tipo' => 'texto'),
			array('campo' => 'ser', 'dato' => $parametros['txt_serie'], 'label' => 'SERIE ADICIONAL', 'tipo' => 'texto'),
			array('campo' => 'cant', 'dato' => $parametros['txt_cant'], 'label' => 'CANTIDAD', 'tipo' => 'texto'),
			array('campo' => 'prec', 'dato' => $parametros['txt_valor'], 'label' => 'VALOR', 'tipo' => 'texto'),
			// array('campo' => 'imagen', 'dato' => '', 'label' => 'IMAGEN', 'tipo' => 'texto'),
			array('campo' => 'es_kit', 'dato' => $parametros['cbx_kit'], 'label' => 'KIT', 'tipo' => 'texto'),
			array('campo' => 'max', 'dato' => $parametros['txt_maximo'], 'label' => 'MÁXIMO', 'tipo' => 'texto'),
			array('campo' => 'min', 'dato' => $parametros['txt_minimo'], 'label' => 'MÍNIMO', 'tipo' => 'texto'),

			array('campo' => 'id_person', 'dato' => $parametros['ddl_custodio'], 'label' => 'CUSTODIO', 'tipo' => 'caso_especial_ddl_custodio'), // listo
			array('campo' => 'id_unidad_medida', 'dato' => $parametros['ddl_unidad'], 'label' => 'UNIDAD DE MEDIDA', 'tipo' => 'caso_especial_ddl_unidad'), // listo
			array('campo' => 'id_tipo_articulo', 'dato' => $parametros['rbl_tip_articulo'], 'label' => 'TIPO DE ARTÍCULO', 'tipo' => 'caso_especial_rbl_tip_articulo'), // listo
			array('campo' => 'id_fam', 'dato' => $parametros['ddl_familia'], 'label' => 'FAMILIA', 'tipo' => 'caso_especial_ddl_familia'), // listo
			array('campo' => 'id_subfam', 'dato' => $parametros['ddl_subfamilia'], 'label' => 'SUBFAMILIA', 'tipo' => 'caso_especial_ddl_subfamilia'), // listo
			array('campo' => 'id_loc', 'dato' => $parametros['ddl_localizacion'], 'label' => 'LOCALIZACIÓN', 'tipo' => 'caso_especial_ddl_localizacion'), // listo
			array('campo' => 'id_mar', 'dato' => $parametros['ddl_marca'], 'label' => 'MARCA', 'tipo' => 'caso_especial_ddl_marca'), // listo
			array('campo' => 'id_est', 'dato' => $parametros['ddl_estado'], 'label' => 'ESTADO', 'tipo' => 'caso_especial_ddl_estado'), // listo
			array('campo' => 'id_gen', 'dato' => $parametros['ddl_genero'], 'label' => 'GÉNERO', 'tipo' => 'caso_especial_ddl_genero'), // listo
			array('campo' => 'id_col', 'dato' => $parametros['ddl_color'], 'label' => 'COLOR', 'tipo' => 'caso_especial_ddl_color'), // listo
			array('campo' => 'id_pro', 'dato' => $parametros['ddl_proyecto'], 'label' => 'PROYECTO', 'tipo' => 'caso_especial_ddl_proyecto'), // listo
			array('campo' => 'id_clase_movimiento', 'dato' => $parametros['ddl_clase_mov'], 'label' => 'CLASE DE MOVIMIENTO', 'tipo' => 'caso_especial_ddl_clase_mov'), // listo

			array('campo' => 'centro_costos', 'dato' => $parametros['txt_centro_costos'], 'label' => 'CENTRO DE COSTOS', 'tipo' => 'texto'),
			array('campo' => 'resp_cctr', 'dato' => $parametros['txt_resp_cctr'], 'label' => 'RESPONSABLE DEL CENTRO DE COSTOS', 'tipo' => 'texto'),
			array('campo' => 'companycode', 'dato' => $parametros['txt_company'], 'label' => 'CÓDIGO DE COMPAÑÍA', 'tipo' => 'texto'),
			array('campo' => 'funds_ctr_apc', 'dato' => $parametros['txt_funds_ctr_apc'], 'label' => 'FUNDS CTR APC', 'tipo' => 'texto'),
			array('campo' => 'profit_ctr', 'dato' => $parametros['txt_profit_ctr'], 'label' => 'PROFIT CTR', 'tipo' => 'texto'),

			array('campo' => 'fecha_referencia', 'dato' => $parametros['txt_fecha'], 'label' => 'FECHA DE REFERENCIA', 'tipo' => 'fecha'),
			array('campo' => 'fecha_contabilizacion', 'dato' => $parametros['txt_compra'], 'label' => 'FECHA DE COMPRA', 'tipo' => 'fecha'),

			array('campo' => 'es_it', 'dato' => $parametros['cbx_detalle_it'], 'label' => 'IT', 'tipo' => 'texto'),
		);



		foreach ($campos as $c) {
			$campo = $c['campo'];
			$nuevoValor = $c['dato'];
			$label = $c['label'];
			$valorAnterior = $datos_1[0][$campo] ?? '';

			// Solo realizar cambios si el valor es diferente
			if ($nuevoValor != $valorAnterior) {
				$movimiento = "Se cambió $label de $valorAnterior a $nuevoValor";

				// Procesar caso especial según el tipo
				switch ($c['tipo']) {
					case 'caso_especial_ddl_custodio':
						$cus = $this->custodio->buscar_custodio_todo($nuevoValor);
						$cusAnt = $this->custodio->buscar_custodio_todo($valorAnterior);
						$movimiento = "Se cambio $label de " . $datos_1[0]['person_nom'] . ' a ' . $cus[0]['PERSON_NOM'];
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $datos_1[0]['person_nom'], $cus[0]['PERSON_NOM'], $cusAnt[0]['PERSON_CI'] ?? '', $cus[0]['PERSON_CI'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;

					case 'caso_especial_ddl_unidad':
						$dato = $this->unidad_medida->where('ac_id_unidad', $nuevoValor)->listar();
						$dato_Ant = $this->unidad_medida->where('ac_id_unidad', $valorAnterior)->listar();
						$movimiento = "Se cambio $label de " . $datos_1[0]['unidad_medida'] . ' a ' . $dato[0]['nombre'];
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $datos_1[0]['unidad_medida'], $dato[0]['nombre'], $dato_Ant[0]['simbolo'] ?? '', $dato[0]['simbolo'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;

					case 'caso_especial_rbl_tip_articulo':
						$dato = $this->tipo_articulo->where('ID_TIPO_ARTICULO', $nuevoValor)->listar();
						$dato_Ant = $this->tipo_articulo->where('ID_TIPO_ARTICULO', $valorAnterior)->listar();
						$movimiento = "Se cambio $label de " . $datos_1[0]['tipo_articulo'] . ' a ' . $dato[0]['descripcion'];
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $datos_1[0]['tipo_articulo'], $dato[0]['descripcion'], $dato_Ant[0]['codigo'] ?? '', $dato[0]['codigo'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;

					case 'caso_especial_ddl_familia':
						$dato = $this->familias->lista_familias($nuevoValor);
						$dato_Ant = $this->familias->lista_familias($valorAnterior);
						$movimiento = "Se cambio $label de " . $datos_1[0]['familia'] . ' a ' . $dato[0]['detalle_familia'];
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $datos_1[0]['familia'], $dato[0]['detalle_familia'], $dato_Ant[0]['id_familia'] ?? '', $dato[0]['id_familia'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;

					case 'caso_especial_ddl_subfamilia':
						$dato = $this->familias->lista_subfamilias($nuevoValor);
						$dato_Ant = $this->familias->lista_subfamilias($valorAnterior);
						$movimiento = "Se cambio $label de " . $datos_1[0]['subfamilia'] . ' a ' . $dato[0]['detalle_familia_sub'];
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $datos_1[0]['subfamilia'], $dato[0]['detalle_familia_sub'], $dato_Ant[0]['idF'] ?? '', $dato[0]['idF'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;

					case 'caso_especial_ddl_localizacion':
						$loc = $this->localizacion->buscar_localizacion($nuevoValor);
						$locAnt = $this->localizacion->buscar_localizacion($valorAnterior);
						$movimiento = "Se cambio $label de " . $datos_1[0]['loc_nom'] . ' a ' . $loc[0]['DENOMINACION'];
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $datos_1[0]['loc_nom'] ?? '', $loc[0]['DENOMINACION'] ?? '', $locAnt[0]['EMPLAZAMIENTO'] ?? '', $loc[0]['EMPLAZAMIENTO'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;

					case 'caso_especial_ddl_marca':
						$dato = $this->marca->buscar_marcas_all(false, $nuevoValor);
						$dato_Ant = $this->marca->buscar_marcas_all(false, $valorAnterior);
						$movimiento = "Se cambio $label de " . $datos_1[0]['marca'] . ' a ' . $dato[0]['DESCRIPCION'];
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $datos_1[0]['marca'] ?? '', $dato[0]['DESCRIPCION'] ?? '', $dato_Ant[0]['CODIGO'] ?? '', $dato[0]['CODIGO'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;

					case 'caso_especial_ddl_estado':
						$dato = $this->estado->lista_estado_todo($nuevoValor);
						$dato_Ant = $this->estado->lista_estado_todo($valorAnterior);
						$movimiento = "Se cambio $label de " . $datos_1[0]['estado'] . ' a ' . $dato[0]['DESCRIPCION'];
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $datos_1[0]['estado'] ?? '', $dato[0]['DESCRIPCION'] ?? '', $dato_Ant[0]['CODIGO'] ?? '', $dato[0]['CODIGO'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;

					case 'caso_especial_ddl_genero':
						$dato = $this->genero->lista_genero_todo($nuevoValor);
						$dato_Ant = $this->genero->lista_genero_todo($valorAnterior);
						$movimiento = "Se cambio $label de " . $datos_1[0]['genero'] . ' a ' . $dato[0]['DESCRIPCION'];
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $datos_1[0]['genero'] ?? '', $dato[0]['DESCRIPCION'] ?? '', $dato_Ant[0]['CODIGO'] ?? '', $dato[0]['CODIGO'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;

					case 'caso_especial_ddl_color':
						$dato = $this->colores->lista_colores_todo($nuevoValor);
						$dato_Ant = $this->colores->lista_colores_todo($valorAnterior);
						$movimiento = "Se cambio $label de " . $datos_1[0]['color'] . ' a ' . $dato[0]['DESCRIPCION'];
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $datos_1[0]['color'] ?? '', $dato[0]['DESCRIPCION'] ?? '', $dato_Ant[0]['CODIGO'] ?? '', $dato[0]['CODIGO'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;

					case 'caso_especial_ddl_proyecto':
						$dato = $this->proyectos->lista_proyectos($nuevoValor);
						$dato_Ant = $this->proyectos->lista_proyectos($valorAnterior);
						$movimiento = "Se cambio $label de " . $datos_1[0]['proyecto'] . ' a ' . $dato[0]['desc'];
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $datos_1[0]['proyecto'] ?? '', $dato[0]['desc'] ?? '', $dato_Ant[0]['pro'] ?? '', $dato[0]['pro'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;

					case 'caso_especial_ddl_clase_mov':
						$dato = $this->clase_movimiento->lista_clase_movimiento($nuevoValor);
						$dato_Ant = $this->clase_movimiento->lista_clase_movimiento($valorAnterior);
						$movimiento = "Se cambio $label de " . $datos_1[0]['movimiento'] . ' a ' . $dato[0]['DESCRIPCION'];
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $datos_1[0]['movimiento'] ?? '', $dato[0]['DESCRIPCION'] ?? '', $dato_Ant[0]['CODIGO'] ?? '', $dato[0]['CODIGO'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;

					case 'fecha':
						$fecha_anterior = $datos_1[0][$campo] ?? '';
						$fecha_nueva = $nuevoValor;

						$fecha_anterior_formateada = date('Y-m-d', strtotime($fecha_anterior));
						$fecha_nueva_formateada = date('Y-m-d', strtotime($fecha_nueva));

						if ($fecha_anterior_formateada != $fecha_nueva_formateada) {
							$movimiento = "Se cambió $label de $fecha_anterior_formateada a $fecha_nueva_formateada";
							$this->cod_globales->ingresar_movimientos($id_articulo,	$movimiento, 'ARTICULOS', $fecha_anterior_formateada, $fecha_nueva_formateada,	'',	'',	$_SESSION['INICIO']['USUARIO'] ?? '');
						}
						break;

					default:
						// Si no se encuentra un tipo específico, solo se registra el cambio de valor
						$this->cod_globales->ingresar_movimientos($id_articulo, $movimiento, 'ARTICULOS', $valorAnterior, $nuevoValor, '', '', $_SESSION['INICIO']['USUARIO'] ?? '');
						break;
				}
			}
		}
	}

	function comparacion_movimiento_2($id, $parametros)
	{
		$datos = $this->modelo->cargar_datos($id);
		// print_r($parametros);
		// exit();
		// die();

		if ($parametros['txt_rfid'] != $datos[0]['rfid']) {
			$movimiento = ' Se cambio RFID de ' . $datos[0]['rfid'] . ' a ' . $parametros['txt_rfid'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, 'ARTICULOS', $datos[0]['rfid'], $parametros['txt_rfid'], '', '', $_SESSION['INICIO']['USUARIO'] ?? '');
		}

		if ($parametros['txt_tag_serie'] != $datos[0]['tag_s']) {
			$movimiento = ' Se cambio SERIE de ' . $datos[0]['tag_s'] . ' a ' . $parametros['txt_tag_serie'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, 'ARTICULOS', $datos[0]['tag_s'], $parametros['txt_tag_serie'], '', '', $_SESSION['INICIO']['USUARIO'] ?? '');
		}

		if ($parametros['txt_tag_anti'] != $datos[0]['ant']) {
			$movimiento = 'Se cambio TAG ANTIGUO de ' . $datos[0]['ant'] . ' a ' . $parametros['txt_tag_anti'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, 'ARTICULOS', $datos[0]['ant'], $parametros['txt_tag_anti'], '', '', $_SESSION['INICIO']['USUARIO'] ?? '');
		}

		if ($parametros['txt_subno'] != $datos[0]['subnum']) {
			$movimiento = 'Se cambio SUBNÚMERO de ' . $datos[0]['ant'] . ' a ' . $parametros['txt_subno'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, 'ARTICULOS', $datos[0]['subnum'], $parametros['txt_subno'], '', '', $_SESSION['INICIO']['USUARIO'] ?? '');
		}

		if ($parametros['ddl_custodio'] != $datos[0]['id_person']) {
			$cus = $this->custodio->buscar_custodio_todo($parametros['ddl_custodio']);
			$cusAnt = $this->custodio->buscar_custodio_todo($datos[0]['id_person']);
			$movimiento = ' Se cambio CUSTODIO de ' . $datos[0]['person_nom'] . ' a ' . $cus[0]['PERSON_NOM'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, 'ARTICULOS', $datos[0]['person_nom'], $cus[0]['PERSON_NOM'], $cusAnt[0]['PERSON_CI'] ?? '', $cus[0]['PERSON_CI'] ?? '', $_SESSION['INICIO']['USUARIO'] ?? '');
		}

		if ($parametros['txt_descripcion'] != $datos[0]['nom']) {
			$movimiento = ' Se cambio DESCRIPCIÓN de ' . $datos[0]['nom'] . ' a ' . $parametros['txt_descripcion'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, 'ARTICULOS', $datos[0]['nom'], $parametros['txt_descripcion'], '', '', $_SESSION['INICIO']['USUARIO'] ?? '');
		}

		if ($parametros['txt_descripcion_2'] != $datos[0]['des']) {
			$movimiento = ' Se cambio DESCRIPCION 2 de ' . $datos[0]['des'] . ' a ' . $parametros['txt_descripcion_2'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, 'ARTICULOS', $datos[0]['des'], $parametros['txt_descripcion_2'], '', '', $_SESSION['INICIO']['USUARIO'] ?? '');
		}

		if ($parametros['txt_carac'] != $datos[0]['carac']) {
			$movimiento = ' Se cambio CARACTERISTICAS de ' . $datos[0]['carac'] . ' a ' . $parametros['txt_carac'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['carac'], $parametros['txt_carac'], '', '', $_SESSION['INICIO']['USUARIO'] ?? '');
		}

		if ($parametros['txt_observacion'] != $datos[0]['obs']) {
			$movimiento = ' Se cambio OBSERVACIONES de ' . $datos[0]['obs'] . ' a ' . $parametros['txt_observacion'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['obs'], $parametros['txt_observacion'], '', '', $_SESSION['INICIO']['USUARIO'] ?? '');
		}


		if ($parametros['txt_modelo'] != $datos[0]['mod']) {
			$movimiento = ' Se cambio MODELO de ' . $datos[0]['mod'] . ' a ' . $parametros['txt_modelo'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, 'ARTICULOS', $datos[0]['mod'], $parametros['txt_modelo'], '', '', $_SESSION['INICIO']['USUARIO'] ?? '');
		}

		if ($parametros['ddl_localizacion'] != $datos[0]['id_loc']) {
			$loc = $this->localizacion->buscar_localizacion($parametros['ddl_localizacion']);
			$locAnt = $this->localizacion->buscar_localizacion($datos[0]['id_loc']);
			$movimiento = ' Se cambio LOCALIZACIÓN de ' . $datos[0]['loc_nom'] . ' a ' . $loc[0]['DENOMINACION'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['loc_nom'], $loc[0]['EMPLAZAMIENTO'], $locAnt[0]['EMPLAZAMIENTO'], $loc[0]['EMPLAZAMIENTO'], $_SESSION['INICIO']['USUARIO'] ?? '');
		}


		// if ($parametros['cant'] != $datos[0]['QUANTITY']) {
		// 	$movimiento = ' Se cambio CANTIDAD de ' . $datos[0]['QUANTITY'] . ' a ' . $parametros['cant'];
		// 	$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['QUANTITY'], $parametros['cant'], '', '');
		// }

		// if ($parametros['valor'] != $datos[0]['ORIG_VALUE']) {
		// 	$movimiento = ' Se cambio VALOR de ' . $datos[0]['ORIG_VALUE'] . ' a ' . $parametros['valor'];
		// 	$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['ORIG_VALUE'], $parametros['valor'], '', '');
		// }

		// if ($parametros['uni'] != $datos[0]['BASE_UOM']) {
		// 	$movimiento = ' Se cambio UNIDAD MEDIDA de ' . $datos[0]['BASE_UOM'] . ' a ' . $parametros['uni'];
		// 	$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['BASE_UOM'], $parametros['uni'], '', '');
		// }

		// if ($parametros['marc'] != $datos[0]['mar']) {
		// 	$marca = $this->marca->buscar_marcas_all($buscar = false, $parametros['marc']);
		// 	$marcaAnt = $this->marca->buscar_marcas_all($buscar = false, $datos[0]['mar']);
		// 	$movimiento = ' Se cambio MARCA de ' . $datos[0]['marca'] . ' a ' . $marca[0]['DESCRIPCION'];
		// 	$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['marca'], $marca[0]['DESCRIPCION'], $marcaAnt[0]['CODIGO'], $marca[0]['CODIGO']);
		// }

		// if ($parametros['colo'] != $datos[0]['col']) {
		// 	$color = $this->colores->lista_colores_todo($parametros['colo']);
		// 	$colorAnt = $this->colores->lista_colores_todo($datos[0]['col']);
		// 	$movimiento = ' Se cambio COLOR de ' . $datos[0]['color'] . ' a ' . $color[0]['DESCRIPCION'];
		// 	$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['color'], $color[0]['DESCRIPCION'], $colorAnt[0]['CODIGO'], $color[0]['CODIGO']);
		// }

		// if ($parametros['gene'] != $datos[0]['gen']) {
		// 	$genero = $this->genero->lista_genero_todo($parametros['gene']);
		// 	$generoAnt = $this->genero->lista_genero_todo($datos[0]['gen']);
		// 	$movimiento = ' Se cambio GENERO de ' . $datos[0]['genero'] . ' a ' . $genero[0]['DESCRIPCION'];
		// 	$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['genero'], $genero[0]['DESCRIPCION'], $generoAnt[0]['CODIGO'], $genero[0]['CODIGO']);
		// }

		// if ($parametros['esta'] != $datos[0]['est']) {
		// 	$est = $this->estado->lista_estado_todo($parametros['esta']);
		// 	$estAnt = $this->estado->lista_estado_todo($datos[0]['est']);
		// 	$movimiento = ' Se cambio ESTADO de ' . $estAnt[0]['DESCRIPCION'] . ' a ' . $est[0]['DESCRIPCION'];
		// 	$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $estAnt[0]['DESCRIPCION'], $est[0]['DESCRIPCION'], $estAnt[0]['CODIGO'], $est[0]['CODIGO']);
		// }

		// if ($datos[0]['fecha'] != '') {
		// 	// print_r($datos[0]['fecha']);die();
		// 	if (is_object($datos[0]['fecha'])) {
		// 		$datos[0]['fecha'] = $datos[0]['fecha']->format('Y-m-d');
		// 	}
		// 	if ($parametros['fech'] != substr($datos[0]['fecha'], 0, 10)) {
		// 		$movimiento = ' Se cambio FECHA INGRESO de ' . $datos[0]['fecha'] . ' a ' . $parametros['fech'];
		// 		$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['fecha'], $parametros['fech'], '', '');
		// 	}
		// }

		// if ($datos[0]['ORIG_ACQ_YR'] != '') {
		// 	if (is_object($datos[0]['ORIG_ACQ_YR'])) {
		// 		$datos[0]['ORIG_ACQ_YR'] = $datos[0]['ORIG_ACQ_YR']->format('Y-m-d');
		// 	}

		// 	if ($parametros['compra'] != $datos[0]['ORIG_ACQ_YR']) {
		// 		$movimiento = ' Se cambio FECHA DE INVENTARIO de ' . $datos[0]['ORIG_ACQ_YR'] . ' a ' . $parametros['compra'];
		// 		$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['ORIG_ACQ_YR'], $parametros['uni'], '', '');
		// 	}
		// }

		// if ($parametros['act'] != $datos[0]['ORIG_ASSET']) {
		// 	$movimiento = ' Se cambio ASSET ORIGINAL de ' . $datos[0]['ORIG_ASSET'] . ' a ' . $parametros['act'];
		// 	$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['ORIG_ASSET'], $parametros['act'], '', '');
		// }

		// $bajas = 0;
		// if ($parametros['bajas'] == 'true') {
		// 	$bajas = 1;
		// }
		// if ($bajas != $datos[0]['BAJAS']) {
		// 	if ($datos[0]['BAJAS'] == 0) {
		// 		$movimiento = ' Se dio de BAJAS';
		// 		$datos_ac = 1;
		// 		$dato_ant = 0;
		// 	} else {
		// 		$movimiento = ' Se recupero de BAJAS';
		// 		$dato_ac = 0;
		// 		$dato_ant = 1;
		// 	}
		// 	$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $dato_ant, $dato_ac, '', '');
		// }

		// $tercero = 0;
		// if ($parametros['terceros'] == 'true') {
		// 	$tercero = 1;
		// }
		// if ($tercero != $datos[0]['TERCEROS']) {
		// 	if ($datos[0]['TERCEROS'] == 0) {
		// 		$movimiento = ' Se MARCO como TERCEROS';
		// 		$datos_ac = 1;
		// 		$dato_ant = 0;
		// 	} else {
		// 		$movimiento = ' Se DESMARCO como TERCERO';
		// 		$datos_ac = 0;
		// 		$dato_ant = 1;
		// 	}

		// 	$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $dato_ant, $dato_act, '', '');
		// }

		// $patrimonial = 0;
		// if ($parametros['patrimoniales'] == 'true') {
		// 	$patrimonial = 1;
		// }
		// if ($patrimonial != $datos[0]['PATRIMONIALES']) {
		// 	if ($datos[0]['PATRIMONIALES'] == 0) {
		// 		$actual = ' Se MARCO como PATRIMONIAL';
		// 		$datos_ac = 1;
		// 		$dato_ant = 0;
		// 	} else {
		// 		$actual = ' Se DESMARCO como PATRIMONIAL';
		// 		$datos_ac = 0;
		// 		$dato_ant = 1;
		// 	}
		// 	$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $dato_ant, $dato_act, '', '');
		// }

		// if ($bajas == 0 && $tercero == 0 && $patrimonial == 0) {
		// 	$op = '';
		// 	if ($datos[0]['TERCEROS'] == '1') {
		// 		$op = 'TERCEROS';
		// 	}
		// 	if ($datos[0]['BAJAS'] == '1') {
		// 		$op = 'BAJAS';
		// 	}
		// 	if ($datos[0]['PATRIMONIALES'] == '1') {
		// 		$op = 'PATRIMONIALES';
		// 	}
		// 	$movimiento = ' Se CAMBIO de ' . $op . ' A NINGUNO';
		// 	//$this->cod_globales->ingresar_movimientos($id,$movimiento,$seccion='ARTICULOS',$op,'NINGUNO');
		// }



		// if ($parametros['crit'] != $datos[0]['idpro']) {
		// 	$pro = $this->proyectos->lista_proyectos($parametros['crit']);
		// 	$proAnt = $this->proyectos->lista_proyectos($datos[0]['idpro']);
		// 	$movimiento = ' Se cambio PROYECTO de ' . $datos[0]['proyecto'] . ' a ' . $pro[0]['desc'];
		// 	$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['proyecto'], $pro[0]['desc'], $proAnt[0]['pro'], $pro[0]['pro']);
		// }

		// return 1;

	}

	function guardar_foto($file, $post)
	{
		// $ruta = 'C:/Users/Jaime/Pictures/fotos/ACTIVOS_DEMO/ACTIVOS/';
		$ruta = $_SESSION['INICIO']['RUTA_IMG_COMPARTIDA'] ?? '';

		if (!empty($ruta) && is_dir($ruta) && is_readable($ruta)) {
			if (!isset($file['file']['type'], $file['file']['tmp_name'], $post['txt_nom_img'], $post['txt_idA_img'])) {
				return -3; // Datos incompletos
			}

			$mime = $file['file']['type'];
			$uploadfile_temporal = $file['file']['tmp_name'];

			// Verifica si es una imagen válida
			if (in_array($mime, ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'])) {

				// Crear imagen desde el archivo temporal
				switch ($mime) {
					case 'image/jpeg':
					case 'image/pjpeg':
						$origen = imagecreatefromjpeg($uploadfile_temporal);
						break;
					case 'image/png':
						$origen = imagecreatefrompng($uploadfile_temporal);
						break;
					case 'image/gif':
						$origen = imagecreatefromgif($uploadfile_temporal);
						break;
					default:
						return -2;
				}

				if (!$origen) {
					return -6; // No se pudo procesar imagen
				}

				// Ruta final donde se guarda como .gif
				$nombre = $post['txt_nom_img'] . '.gif';
				$ruta_activos = rtrim($ruta, '/\\') . DIRECTORY_SEPARATOR . 'ACTIVOS';

				if (!file_exists($ruta_activos)) {
					mkdir($ruta_activos, 0777, true);
				}

				$nuevo_nom = $ruta_activos . DIRECTORY_SEPARATOR . $nombre;

				// Guardar como GIF
				if (imagegif($origen, $nuevo_nom)) {
					imagedestroy($origen); // Liberar memoria
					$base = $this->modelo->img_guardar($nombre, $post['txt_idA_img']);
					$movimiento = "Se cambió de imagen no se puede recuperar la anterior.";
					$this->cod_globales->ingresar_movimientos($post['txt_idA_img'], $movimiento, 'ARTICULOS', '', '', '', '', $_SESSION['INICIO']['USUARIO'] ?? '');
					return ($base == 1) ? 1 : -1;
				} else {
					return -4; // No se pudo guardar como GIF
				}
			} else {
				return -2; // Tipo no permitido
			}
		} else {
			return -5; // Ruta inválida o no accesible
		}
	}

	function navegacion($parametros)
	{
		// print_r($parametros);die();
		$loc = explode('--', $parametros['loc']);
		$cus = explode('--', $parametros['cus']);
		$id = $parametros['id'];
		$datos = $this->modelo->navegacion('', $loc[0], $cus[0]);
		$pos = 0;
		foreach ($datos as $key => $value) {
			if ($value['id'] == $id) {
				$pos = $key;
				break;
			}
		}
		$act = $datos[$pos]['id'];
		if ($pos != 0) {
			$ant = $datos[$pos - 1]['id'];
		} else {
			$ant = 0;
		}
		$sig = $datos[$pos + 1]['id'];
		$nav = array('actual' => $act, 'atras' => $ant, 'siguiente' => $sig);
		return $nav;
	}

	function add_info($parametros)
	{
		// print_r($parametros);die();
		$datos[0]['campo'] = 'CODNACIONAL';
		$datos[0]['dato'] = $parametros['txt_codigonacional'];  // => 15987
		$datos[2]['campo'] = 'UNIDADDOCUMENTAL';
		$datos[2]['dato'] = $parametros['rbl_grupo'];  // => H
		$datos[3]['campo'] = 'AUTOR';
		$datos[3]['dato'] = $parametros['txt_autor'];  // => javier
		$datos[4]['campo'] = 'PAIS';
		$datos[4]['dato'] = $parametros['txt_pais'];  // => ecuador
		$datos[5]['campo'] = 'SIGLO';
		$datos[5]['dato'] = $parametros['txt_siglo'];  // => XIX
		$datos[6]['campo'] = 'FECHA';
		$datos[6]['dato'] = $parametros['txt_fecha'];  // => 2022-04-27
		$datos[7]['campo'] = 'PROPIETARIO';
		$datos[7]['dato'] = $parametros['txt_propietario'];  // => casa de la cultura
		$datos[8]['campo'] = 'NDI';
		$datos[8]['dato'] = $parametros['txt_dni'];  // => 147852369
		$datos[9]['campo'] = 'TELEFONO';
		$datos[9]['dato'] = $parametros['txt_telefono'];  // => 09897456231
		$datos[10]['campo'] = 'EMAIL';
		$datos[10]['dato'] = $parametros['txt_correo'];  // => casa@cultura.com
		$datos[11]['campo'] = 'MUNICIPIO';
		$datos[11]['dato'] = $parametros['txt_municipio'];  // => pichincha
		$datos[12]['campo'] = 'DISTRITO';
		$datos[12]['dato'] = $parametros['txt_distrito'];  // => quito
		$datos[13]['campo'] = 'DEPARTAMENTO';
		$datos[13]['dato'] = $parametros['txt_departamento'];  // => QUITO
		$datos[14]['campo'] = 'DIRECCION';
		$datos[14]['dato'] = $parametros['txt_direccion'];  // => AV. 12 DE COTUBRE
		$datos[15]['campo'] = 'DESCRIPCION';
		$datos[15]['dato'] = $parametros['txt_descripcion'];  // => LIBRO DE POEMAS
		$datos[16]['campo'] = 'CONSERVACION';
		$datos[16]['dato'] = $parametros['ddl_unidad_conservacion'];  // => 
		$datos[17]['campo'] = 'UNIDADES';
		$datos[17]['dato'] = $parametros['txt_unidades'];  // => 1
		$datos[18]['campo'] = 'LARGO';
		$datos[18]['dato'] = $parametros['txt_largo'];  // => 3CM
		$datos[19]['campo'] = 'ANCHO';
		$datos[19]['dato'] = $parametros['txt_ancho'];  // => 25
		$datos[20]['campo'] = 'GROSOR';
		$datos[20]['dato'] = $parametros['txt_grosor'];  // => 1CM
		$datos[21]['campo'] = 'METROSLINEALES';
		$datos[21]['dato'] = $parametros['txt_metro_lineal'];  // => 
		$datos[22]['campo'] = 'ESCALA';
		$datos[22]['dato'] = $parametros['txt_escala'];  // => 
		$datos[23]['campo'] = 'INTEGRIDAD';
		$datos[23]['dato'] = $parametros['rbl_integridad'];  // => D
		$datos[24]['campo'] = 'ESTADO';
		$datos[24]['dato'] = $parametros['rbl_estado'];  // => R
		$datos[25]['campo'] = 'OBSERVACION';
		$datos[25]['dato'] = $parametros['txt_observacion_info'];  // => ASDASD
		$datos[26]['campo'] = 'VALORACION';
		$datos[26]['dato'] = $parametros['txt_valoracion'];  // => ASDASDAS
		$datos[27]['campo'] = 'ARTICULO';
		$datos[27]['dato'] = $parametros['txt_id'];  // => ASDASDAS
		if ($parametros['txt_id_info'] == '') {
			return $this->modelo->guardar($tabla = 'ac_datos_patrimonial', $datos);
		} else {
			$where[0]['campo'] = 'ID_PATRIMONIAL';
			$where[0]['dato'] = $parametros['txt_id_info'];
			return $this->modelo->update($tabla = 'ac_datos_patrimonial', $datos, $where);
		}
	}

	function insertar_editar_depreciacion($parametros)
	{
		if ($parametros['id_articulo_update'] != '') {

			// Datos para actualizar (con la estructura que tu método editar espera)
			$datos = array(
				array('campo' => 'valor_residual', 'dato' => floatval($parametros['txt_valor_residual'])),
				array('campo' => 'vida_util', 'dato' => intval($parametros['txt_vida_util']))
			);

			// Condición WHERE para actualizar el registro correcto
			$where = array(
				array('campo' => 'id_articulo', 'dato' => intval($parametros['id_articulo_update']))
			);

			// Ejecutar el update usando el método editar de tu modelo
			$resultado = $this->modelo->editar($datos, $where);

			return $resultado;
		}
	}
}
