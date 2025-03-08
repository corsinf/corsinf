<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/detalle_articuloM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/coloresM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/generoM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/marcasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/estadoM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/proyectosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/custodioM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/localizacionM.php');
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
	// print_r($id);die();
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
		if (count($datos) > 0) {
			if (!file_exists('../img/' . $datos[0]['imagen'])) {
				$datos[0]['imagen'] = 'sin_imagen.jpg';
			}
		} else {
			$datos[0]['imagen'] = 'sin_imagen.jpg';
		}
		// $datos = array_map(array($this->cod_globales, 'transformar_array_encode'), $datos);
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

		// print_r($parametros);die();		

		// $loc = $this->localizacion->buscar_localizacion($parametros['loca']);
		// $cus = $this->custodio->buscar_custodio_todo($parametros['cust']);
		// $marca = $this->marca->buscar_marcas_all($buscar = false, $parametros['marc']);
		// $est = $this->estado->lista_estado_todo($parametros['esta']);
		// $genero = $this->genero->lista_genero_todo($parametros['gene']);
		// $color = $this->colores->lista_colores_todo($parametros['colo']);
		// $pro = $this->proyectos->lista_proyectos($parametros['crit']);

		// array('campo' => 'id_articulo', 'dato' => $parametros['']),
		$datos = array(
			// rbl_asset
			array('campo' => 'tag_unique', 'dato' => $parametros['txt_asset']),
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
			// array('campo' => 'id_usuario_actualizar', 'dato' => $parametros['']),
			// array('campo' => 'fecha_creacion', 'dato' => $parametros['']),
			array('campo' => 'fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
			// array('campo' => 'fecha_baja', 'dato' => $parametros['']),
			array('campo' => 'fecha_referencia', 'dato' => $parametros['txt_compra']),
			array('campo' => 'fecha_contabilizacion', 'dato' => $parametros['txt_fecha']),
			// array('campo' => 'id_rubro', 'dato' => $parametros['']),
		);

		$where = array(
			array('campo' => 'id_articulo', 'dato' => $parametros['idAr']),
		);

		$datos = $this->modelo->editar($datos, $where);


		return $datos;



		// $where1[0]['campo'] = 'id_plantilla';
		// $where1[0]['dato'] = $parametros['idAr'];
		// $movimientos = $this->comparacion_movimiento($parametros['idAr'], $parametros);
		// $respuesta1 = $this->modelo->update_data('ac_articulos', $datos1, $where1);
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

	function comparacion_movimiento($id, $parametros)
	{
		$datos = $this->modelo->cargar_datos($id);
		$texto = '';
		if ($parametros['desc'] != $datos[0]['nom']) {
			$movimiento = ' Se cambio DESCRIPCION de ' . $datos[0]['nom'] . ' a ' . $parametros['desc'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['nom'], $parametros['desc'], '', '');
		}
		if ($parametros['des2'] != $datos[0]['des']) {
			$movimiento = ' Se cambio DESCRIPCION 2 de ' . $datos[0]['des'] . ' a ' . $parametros['des2'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['des'], $parametros['des2'], '', '');
		}
		if ($parametros['loca'] != $datos[0]['id_loc']) {
			$loc = $this->localizacion->buscar_localizacion($parametros['loca']);
			$locAnt = $this->localizacion->buscar_localizacion($datos[0]['id_loc']);
			$movimiento = ' Se cambio LOCALIZACION de ' . $datos[0]['DENOMINACION'] . ' a ' . $loc[0]['EMPLAZAMIENTO'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['DENOMINACION'], $loc[0]['EMPLAZAMIENTO'], $locAnt[0]['EMPLAZAMIENTO'], $loc[0]['EMPLAZAMIENTO']);
		}
		if ($parametros['cust'] != $datos[0]['id_cus']) {
			$cus = $this->custodio->buscar_custodio_todo($parametros['cust']);
			$cusAnt = $this->custodio->buscar_custodio_todo($datos[0]['id_cus']);
			$movimiento = ' Se cambio CUSTODIO de ' . $datos[0]['PERSON_NOM'] . ' a ' . $cus[0]['PERSON_NOM'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['PERSON_NOM'], $cus[0]['PERSON_NOM'], $cusAnt[0]['PERSON_NO'], $cus[0]['PERSON_NO']);
		}
		if ($parametros['marc'] != $datos[0]['mar']) {
			$marca = $this->marca->buscar_marcas_all($buscar = false, $parametros['marc']);
			$marcaAnt = $this->marca->buscar_marcas_all($buscar = false, $datos[0]['mar']);
			$movimiento = ' Se cambio MARCA de ' . $datos[0]['marca'] . ' a ' . $marca[0]['DESCRIPCION'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['marca'], $marca[0]['DESCRIPCION'], $marcaAnt[0]['CODIGO'], $marca[0]['CODIGO']);
		}
		if ($parametros['colo'] != $datos[0]['col']) {
			$color = $this->colores->lista_colores_todo($parametros['colo']);
			$colorAnt = $this->colores->lista_colores_todo($datos[0]['col']);
			$movimiento = ' Se cambio COLOR de ' . $datos[0]['color'] . ' a ' . $color[0]['DESCRIPCION'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['color'], $color[0]['DESCRIPCION'], $colorAnt[0]['CODIGO'], $color[0]['CODIGO']);
		}
		if ($parametros['gene'] != $datos[0]['gen']) {
			$genero = $this->genero->lista_genero_todo($parametros['gene']);
			$generoAnt = $this->genero->lista_genero_todo($datos[0]['gen']);
			$movimiento = ' Se cambio GENERO de ' . $datos[0]['genero'] . ' a ' . $genero[0]['DESCRIPCION'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['genero'], $genero[0]['DESCRIPCION'], $generoAnt[0]['CODIGO'], $genero[0]['CODIGO']);
		}
		if ($parametros['asse'] != $datos[0]['tag_s']) {
			$movimiento = ' Se cambio ASSET de ' . $datos[0]['tag_s'] . ' a ' . $parametros['asse'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['tag_s'], $parametros['asse'], '', '');
		}
		if ($parametros['esta'] != $datos[0]['est']) {
			$est = $this->estado->lista_estado_todo($parametros['esta']);
			$estAnt = $this->estado->lista_estado_todo($datos[0]['est']);
			$movimiento = ' Se cambio ESTADO de ' . $estAnt[0]['DESCRIPCION'] . ' a ' . $est[0]['DESCRIPCION'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $estAnt[0]['DESCRIPCION'], $est[0]['DESCRIPCION'], $estAnt[0]['CODIGO'], $est[0]['CODIGO']);
		}
		if ($parametros['rfid'] != $datos[0]['rfid']) {
			$movimiento = ' Se cambio RFID escripcion de ' . $datos[0]['rfid'] . ' a ' . $parametros['rfid'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['rfid'], $parametros['rfid'], '', '');
		}
		if ($parametros['tagA'] != $datos[0]['ant']) {
			$movimiento = 'Se cambio TAG ANTERIOR de ' . $datos[0]['ant'] . ' a ' . $parametros['tagA'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['ant'], $parametros['tagA'], '', '');
		}
		if ($parametros['seri'] != $datos[0]['SERIE']) {
			$movimiento = ' Se cambio SERIE de ' . $datos[0]['SERIE'] . ' a ' . $parametros['seri'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['SERIE'], $parametros['seri'], '', '');
		}
		if ($datos[0]['fecha'] != '') {
			// print_r($datos[0]['fecha']);die();
			if (is_object($datos[0]['fecha'])) {
				$datos[0]['fecha'] = $datos[0]['fecha']->format('Y-m-d');
			}
			if ($parametros['fech'] != substr($datos[0]['fecha'], 0, 10)) {
				$movimiento = ' Se cambio FECHA INGRESO de ' . $datos[0]['fecha'] . ' a ' . $parametros['fech'];
				$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['fecha'], $parametros['fech'], '', '');
			}
		}
		if ($parametros['mode'] != $datos[0]['MODELO']) {
			$movimiento = ' Se cambio MODELO de ' . $datos[0]['MODELO'] . ' a ' . $parametros['mode'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['MODELO'], $parametros['mode'], '', '');
		}
		if ($parametros['obse'] != $datos[0]['OBSERVACION']) {
			$movimiento = ' Se cambio OBSERVACION de ' . $datos[0]['OBSERVACION'] . ' a ' . $parametros['obse'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['OBSERVACION'], $parametros['obse'], '', '');
		}
		if ($parametros['cant'] != $datos[0]['QUANTITY']) {
			$movimiento = ' Se cambio CANTIDAD de ' . $datos[0]['QUANTITY'] . ' a ' . $parametros['cant'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['QUANTITY'], $parametros['cant'], '', '');
		}
		if ($parametros['uni'] != $datos[0]['BASE_UOM']) {
			$movimiento = ' Se cambio UNIDAD MEDIDA de ' . $datos[0]['BASE_UOM'] . ' a ' . $parametros['uni'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['BASE_UOM'], $parametros['uni'], '', '');
		}
		if ($datos[0]['ORIG_ACQ_YR'] != '') {
			if (is_object($datos[0]['ORIG_ACQ_YR'])) {
				$datos[0]['ORIG_ACQ_YR'] = $datos[0]['ORIG_ACQ_YR']->format('Y-m-d');
			}
			if ($parametros['compra'] != $datos[0]['ORIG_ACQ_YR']) {
				$movimiento = ' Se cambio FECHA DE INVENTARIO de ' . $datos[0]['ORIG_ACQ_YR'] . ' a ' . $parametros['compra'];
				$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['ORIG_ACQ_YR'], $parametros['uni'], '', '');
			}
		}
		if ($parametros['cara'] != $datos[0]['CARACTERISTICA']) {
			$movimiento = ' Se cambio CARACTERISTICAS de ' . $datos[0]['CARACTERISTICA'] . ' a ' . $parametros['cara'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['CARACTERISTICA'], $parametros['cara'], '', '');
		}
		if ($parametros['valor'] != $datos[0]['ORIG_VALUE']) {
			$movimiento = ' Se cambio VALOR de ' . $datos[0]['ORIG_VALUE'] . ' a ' . $parametros['valor'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['ORIG_VALUE'], $parametros['valor'], '', '');
		}

		if ($parametros['act'] != $datos[0]['ORIG_ASSET']) {
			$movimiento = ' Se cambio ASSET ORIGINAL de ' . $datos[0]['ORIG_ASSET'] . ' a ' . $parametros['act'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['ORIG_ASSET'], $parametros['act'], '', '');
		}

		$bajas = 0;
		if ($parametros['bajas'] == 'true') {
			$bajas = 1;
		}
		if ($bajas != $datos[0]['BAJAS']) {
			if ($datos[0]['BAJAS'] == 0) {
				$movimiento = ' Se dio de BAJAS';
				$datos_ac = 1;
				$dato_ant = 0;
			} else {
				$movimiento = ' Se recupero de BAJAS';
				$dato_ac = 0;
				$dato_ant = 1;
			}
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $dato_ant, $dato_ac, '', '');
		}

		$tercero = 0;
		if ($parametros['terceros'] == 'true') {
			$tercero = 1;
		}
		if ($tercero != $datos[0]['TERCEROS']) {
			if ($datos[0]['TERCEROS'] == 0) {
				$movimiento = ' Se MARCO como TERCEROS';
				$datos_ac = 1;
				$dato_ant = 0;
			} else {
				$movimiento = ' Se DESMARCO como TERCERO';
				$datos_ac = 0;
				$dato_ant = 1;
			}

			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $dato_ant, $dato_act, '', '');
		}

		$patrimonial = 0;
		if ($parametros['patrimoniales'] == 'true') {
			$patrimonial = 1;
		}
		if ($patrimonial != $datos[0]['PATRIMONIALES']) {
			if ($datos[0]['PATRIMONIALES'] == 0) {
				$actual = ' Se MARCO como PATRIMONIAL';
				$datos_ac = 1;
				$dato_ant = 0;
			} else {
				$actual = ' Se DESMARCO como PATRIMONIAL';
				$datos_ac = 0;
				$dato_ant = 1;
			}
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $dato_ant, $dato_act, '', '');
		}

		if ($bajas == 0 && $tercero == 0 && $patrimonial == 0) {
			$op = '';
			if ($datos[0]['TERCEROS'] == '1') {
				$op = 'TERCEROS';
			}
			if ($datos[0]['BAJAS'] == '1') {
				$op = 'BAJAS';
			}
			if ($datos[0]['PATRIMONIALES'] == '1') {
				$op = 'PATRIMONIALES';
			}
			$movimiento = ' Se CAMBIO de ' . $op . ' A NINGUNO';
			//$this->cod_globales->ingresar_movimientos($id,$movimiento,$seccion='ARTICULOS',$op,'NINGUNO');
		}



		if ($parametros['crit'] != $datos[0]['idpro']) {
			$pro = $this->proyectos->lista_proyectos($parametros['crit']);
			$proAnt = $this->proyectos->lista_proyectos($datos[0]['idpro']);
			$movimiento = ' Se cambio PROYECTO de ' . $datos[0]['proyecto'] . ' a ' . $pro[0]['desc'];
			$this->cod_globales->ingresar_movimientos($id, $movimiento, $seccion = 'ARTICULOS', $datos[0]['proyecto'], $pro[0]['desc'], $proAnt[0]['pro'], $pro[0]['pro']);
		}

		// return 1;

	}

	function guardar_foto($file, $post)
	{
		$ruta = '../img/'; //ruta carpeta donde queremos copiar las imágenes
		if (!file_exists($ruta)) {
			mkdir($ruta, 0777, true);
		}
		if ($file['file']['type'] == "image/jpeg" || $file['file']['type'] == "image/pjpeg" || $file['file']['type'] == "image/gif" || $file['file']['type'] == "image/png") {
			$uploadfile_temporal = $file['file']['tmp_name'];
			$tipo = explode('/', $file['file']['type']);
			$nombre = $post['txt_nom_img'] . '.' . $tipo[1];

			$nuevo_nom = $ruta . $nombre;
			if (is_uploaded_file($uploadfile_temporal)) {
				move_uploaded_file($uploadfile_temporal, $nuevo_nom);
				$base = $this->modelo->img_guardar($nombre, $post['txt_idA_img']);
				if ($base == 1) {
					return 1;
				} else {
					return -1;
				}
			} else {
				return -1;
			}
		} else {
			return -2;
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
}
