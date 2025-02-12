<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/custodioM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

/**
 * 
 **/

$controlador = new custodioC();

if (isset($_GET['lista'])) {
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->lista_custodio($query));
}

if (isset($_GET['lista_acta'])) {
	$query = '';
	if (isset($_GET['q'])) {
		$query = $_GET['q'];
	}
	echo json_encode($controlador->lista_custodio_acta($query));
}

if (isset($_GET['buscar'])) {
	$query = $_POST['parametros'];
	echo json_encode($controlador->buscar_custodio($query));
}

if (isset($_GET['insertar'])) {
	echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
	echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar'])) {
	$query = $_POST['id'];
	echo json_encode($controlador->buscar_custodio_id($query));
}

if (isset($_GET['listar_todo'])) {
	$query = $_POST['id'];
	echo json_encode($controlador->buscar_custodio_todo($query));
}

if (isset($_GET['cargar_imagen'])) {
	echo json_encode($controlador->guardar_foto($_FILES, $_POST));
}

if (isset($_GET['numero_custodios'])) {
	echo json_encode($controlador->numero_custodios());
}

if (isset($_GET['custodios_masivos'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->custodios_masivos($parametros));
}



class custodioC
{
	private $modelo;
	private $cod_global;

	function __construct()
	{
		$this->modelo = new custodioM();
		$this->cod_global = new codigos_globales();
	}

	function lista_custodio($query)
	{
		$cambio = array();
		$lista = $this->modelo->lista_custodio($query);
		foreach ($lista as $key => $value) {
			$cambio[] = array('id' => $value['ID_PERSON'], 'text' => $value['PERSON_NOM']);
		}
		// print_r($cambio);die();
		return $cambio;
	}

	function lista_custodio_acta($query)
	{
		$cambio = [];
		$lista = $this->modelo->lista_custodio($query);
		foreach ($lista as $key => $value) {
			$cambio[] = ['id' => $value['PERSON_NO'], 'text' => $value['PERSON_NOM']];
		}
		return $cambio;
	}

	function buscar_custodio($buscar)
	{
		if (is_array($buscar)) {
			$reg = $this->modelo->lista_custodio_count($buscar['buscar']);
			if ($reg > 25) {

				$pagi = explode('-', $buscar['pag']);
				$lista = $this->modelo->lista_custodio($buscar['buscar'], $pagi[0], $pagi[1]);
			} else {
				$lista = $this->modelo->lista_custodio($buscar['buscar']);
			}

			// $lista = array_map(array($this->cod_global, 'transformar_array_encode'), $lista);
			$datos2 = array('datos' => $lista, 'cant' => $reg[0]['cant']);

			return $datos2;
		} else {

			$reg = $this->modelo->lista_custodio_count($buscar);
			$lista = $this->modelo->lista_custodio($buscar, 0, 25);
			// $lista = array_map(array($this->cod_global, 'transformar_array_encode'), $lista);
			$datos2 = array('datos' => $lista, 'cant' => $reg[0]['cant']);
			return $datos2;
		}
	}

	function buscar_custodio_id($buscar)
	{
		$lista = $this->modelo->buscar_custodio($buscar);
		// $lista = array_map(array($this->cod_global, 'transformar_array_encode'), $lista);		
		return $lista;
	}

	function buscar_custodio_todo($buscar)
	{
		$lista = $this->modelo->buscar_custodio_todo($buscar);
		// $lista = array_map(array($this->cod_global, 'transformar_array_encode'), $lista);		
		return $lista;
	}

	function insertar_editar($parametros)
	{

		$datos[0]['campo'] = 'PERSON_NOM';
		$datos[0]['dato'] = $parametros['nombre'];
		$datos[1]['campo'] = 'PERSON_CI';
		$datos[1]['dato'] = $parametros['ci'];
		$datos[2]['campo'] = 'PERSON_CORREO';
		$datos[2]['dato'] = $parametros['email'];
		$datos[3]['campo'] = 'PUESTO';
		$datos[3]['dato'] = $parametros['puesto'];
		$datos[4]['campo'] = 'UNIDAD_ORG';
		$datos[4]['dato'] = $parametros['unidad'];
		$datos[5]['campo'] = 'PERSON_NO';
		$datos[5]['dato'] = $parametros['per'];
		$datos[6]['campo'] = 'TELEFONO';
		$datos[6]['dato'] = $parametros['tel'];
		$datos[7]['campo'] = 'DIRECCION';
		$datos[7]['dato'] = $parametros['dir'];
		if ($parametros['id'] == '') {
			if (count($this->modelo->buscar_custodio_($datos[5]['dato'])) == 0) {
				$datos = $this->modelo->insertar($datos);
				$movimiento = 'Insertado nuevo registro en CUSTODIO (' . $parametros['nombre'] . ')';
			} else {
				$datos = -2;
			}
		} else {
			$where[0]['campo'] = 'ID_PERSON';
			$where[0]['dato'] = $parametros['id'];
			$movimiento = $this->compara_datos($parametros);
			$datos = $this->modelo->editar($datos, $where);
		}
		if ($movimiento != '' && $datos == 1) {
			$texto = $parametros['per'] . ';' . $parametros['ci'] . ';' . $parametros['nombre'] . ';' . $parametros['puesto'] . ';' . $parametros['unidad'] . ';' . $parametros['email'];
			$this->cod_global->para_ftp('custodio', $texto);
			$this->cod_global->ingresar_movimientos(false, $movimiento, 'CUSTODIO');
		}


		return $datos;
	}

	function compara_datos($parametros)
	{
		$text = '';
		$marca = $this->modelo->buscar_custodio($parametros['id']);
		if ($marca[0]['PERSON_NO'] != $parametros['per']) {
			$text .= ' Se modifico CODIGO en CUSTODIO de ' . $marca[0]['PERSON_NO'] . ' a ' . $parametros['per'];
		}
		if ($marca[0]['PERSON_NOM'] != $parametros['nombre']) {
			$text .= ' Se modifico NOMBRE en CUSTODIO de ' . $marca[0]['PERSON_NOM'] . ' a ' . $parametros['nombre'];
		}
		if ($marca[0]['PERSON_CI'] != $parametros['ci']) {
			$text .= ' Se modifico CI en CUSTODIO de ' . $marca[0]['PERSON_CI'] . ' a ' . $parametros['ci'];
		}
		if ($marca[0]['PERSON_CORREO'] != $parametros['email']) {
			$text .= ' Se modifico CORREO en CUSTODIO de ' . $marca[0]['PERSON_CORREO'] . ' a ' . $parametros['email'];
		}
		if ($marca[0]['PUESTO'] != $parametros['puesto']) {
			$text .= ' Se modifico PUESTO en CUSTODIO de ' . $marca[0]['PUESTO'] . ' a ' . $parametros['puesto'];
		}
		if ($marca[0]['UNIDAD_ORG'] != $parametros['unidad']) {
			$text .= ' Se modifico UNIDAD en CUSTODIO de ' . $marca[0]['UNIDAD_ORG'] . ' a ' . $parametros['unidad'];
		}

		return $text;
	}

	function eliminar($id)
	{
		$datos[0]['campo'] = 'ID_PERSON';
		$datos[0]['dato'] = $id;
		$datos = $this->modelo->eliminar($datos);
		return $datos;
	}

	function guardar_foto($file, $post)
	{
		$ruta = '../img/custodios/'; //ruta carpeta donde queremos copiar las imágenes
		if (!file_exists($ruta)) {
			mkdir($ruta, 0777, true);
		}
		if ($this->validar_formato_img($file) == 1) {
			$uploadfile_temporal = $file['file_img']['tmp_name'];
			$tipo = explode('/', $file['file_img']['type']);
			$nombre = $post['id'] . '.' . $tipo[1];
			$nuevo_nom = $ruta . $nombre;
			if (is_uploaded_file($uploadfile_temporal)) {
				move_uploaded_file($uploadfile_temporal, $nuevo_nom);

				$datosI[0]['campo'] = 'FOTO';
				$datosI[0]['dato'] = $nuevo_nom;
				$where[0]['campo'] = 'ID_PERSON';
				$where[0]['dato'] = $post['id'];
				$base = $this->modelo->editar($datosI, $where);
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

	function validar_formato_img($file)
	{
		switch ($file['file_img']['type']) {
			case 'image/jpeg':
			case 'image/pjpeg':
			case 'image/gif':
			case 'image/png':
				return 1;
				break;
			default:
				return -1;
				break;
		}
	}

	function numero_custodios()
	{
		$datos = $this->modelo->lista_custodio_count();
		return $datos;
	}

	function custodios_masivos($parametros)
	{
		$query = preg_replace("[\n|\r|\n\r| ]", "-", $parametros['custodios']);
		$query = explode('-', $query);
		$query = array_unique($query);

		$lista = array();
		$sms = '';
		foreach ($query as $key => $value) {
			$cus = $this->modelo->buscar_custodio_todo($id = false, $value, $person_nom = false);
			if (count($cus) > 0) {
				$lista[] = array('id' => $cus[0]['ID_PERSON'], 'text' => $cus[0]['PERSON_NOM']);
			} else {
				$sms .= 'Custodio con codigo: ' . $value . 'No Encontrado \n ';
			}
		}

		return array('custodios' => $lista, 'mensaje' => $sms);
		// print_r($query);die();
	}
}
