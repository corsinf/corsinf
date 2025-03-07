<?php
$controlador = new validar_firmaC();

if (isset($_GET['validar_firma_funcional'])) {

	$parametros = $_POST;
	$p12 = $_FILES;
	echo json_encode($controlador->validar_firma_funcional($p12, $parametros));
	// print_r($_FILES);
	// print_r($_POST);die();
}
if (isset($_GET['validar_firma'])) {

	$parametros = $_POST;
	$p12 = $_FILES;
	echo json_encode($controlador->validar_firma($p12, $parametros));
	// print_r($_FILES);
	// print_r($_POST);die();
}
if (isset($_GET['validar_documento'])) {

	$parametros = $_POST;
	$p12 = $_FILES;
	echo json_encode($controlador->validar_documento($p12, $parametros));
	// print_r($_FILES);
	// print_r($_POST);die();
}
if (isset($_GET['firmar_documento'])) {
	$parametros = $_POST;
	$doc = $_FILES;
	echo json_encode($controlador->firmar_documento($doc, $parametros));
}

/**
 * 
 */
class validar_firmaC
{
	private $rutaTemp;
	function __construct()
	{
		$this->rutaTemp = dirname(__DIR__, 2) . '/TEMP/';
		// code...
	}


	function validar_firma_funcional($file, $parametros) {

	//$p12File = $file["txt_ruta_archivo"]['tmp_name'];
        $p12File = "C:/xampp/htdocs/corsinf/REPOSITORIO/TALENTO_HUMANO/3044/124251/FIRMAS/firmas_electronicas_7.p12";
	    $p12Password = "milton123*";
		$p12Content = file_get_contents($p12File);
		$certs = array();
		print_r(openssl_pkcs12_read($p12Content, $certs, $p12Password)); die();
	
		if (openssl_pkcs12_read($p12Content, $certs, $p12Password)) {
			$privateKey = $certs['pkey'];
			$publicKeyCert = $certs['cert'];
		} else {
			die('Error leyendo el archivo .p12');
		}
	
		$caCertificates = isset($certs['extracerts']) ? $certs['extracerts'] : null;
		if (is_array($caCertificates)) {
			$caCertificates = implode("\n", $caCertificates);
		}
	
	
		$certdata = openssl_x509_parse($certs['cert'], 0);
	
		echo json_encode($certdata);
		die();
	}
	

	function validar_firma($file, $parametros)
	{
		$ruta_p12 = '';

		// Verificar si el archivo fue subido o si ya existe en una ruta
		if (isset($file['txt_cargar_imagen']['tmp_name']) && isset($file['txt_cargar_imagen']['name'])) {
			$uploadfile_temporal = $file['txt_cargar_imagen']['tmp_name'];
			$nombre = $file['txt_cargar_imagen']['name'];

			// Determinar si estamos usando un archivo del repositorio
			if (strpos($uploadfile_temporal, 'REPOSITORIO') !== false && file_exists($uploadfile_temporal)) {
				// Si es un archivo del repositorio, usarlo directamente
				$ruta_p12 = $uploadfile_temporal;
			} else {
				// Si es un archivo subido, moverlo al directorio temporal
				$ruta_p12 = $this->rutaTemp . str_replace(' ', '_', $nombre);

				if (is_uploaded_file($uploadfile_temporal)) {
					if (!move_uploaded_file($uploadfile_temporal, $ruta_p12)) {
						return ['resp' => -2, 'msj' => 'Error al mover el archivo de firma.'];
					}
				} else if (!file_exists($ruta_p12)) {
					return ['resp' => -2, 'msj' => 'Archivo de firma no encontrado.'];
				}
			}
		} else {
			return ['resp' => -2, 'msj' => 'Parámetros de firma incorrectos.'];
		}

		// Verificar que la clave no esté vacía
		if (empty(trim($parametros['txt_ingresarClave']))) {
			return ['resp' => -2, 'msj' => 'La clave de la firma no puede estar vacía.'];
		}

		$rutaJar = dirname(__DIR__, 2) . '/lib/firmarPdf/FirmarPDF.jar';

		// Asegurar que la clave se pasa correctamente
		$clave_firma = escapeshellarg(trim($parametros['txt_ingresarClave']));

		// Ejecutar el comando de validación
		$comando = "java -jar $rutaJar 2 $ruta_p12 $clave_firma";
		$respuesta = shell_exec($comando);

		// Validar que shell_exec no devuelva NULL
		if ($respuesta === null) {
			return ['resp' => -2, 'msj' => 'Error al ejecutar el comando de validación.'];
		}

		// Decodificar la respuesta del JAR
		$resp = json_decode(trim($respuesta), true);

		// Verificar si la respuesta es válida y contiene datos
		if (!is_array($resp) || count($resp) < 2) {
			return ['resp' => -2, 'msj' => 'Formato de respuesta inválido del JAR.'];
		}

		// Si la respuesta indica que la firma es inválida, devolver un error claro
		if ($resp[0] == -1) {
			return ['resp' => -1, 'msj' => 'Firma inválida o contraseña incorrecta.'];
		}

		// Si no se debe mantener el archivo y NO es del repositorio, eliminarlo
		if ((!isset($parametros['mantener']) || $parametros['mantener'] == 0) && strpos($ruta_p12, 'REPOSITORIO') === false) {
			@unlink($ruta_p12);
		}

		// Retornar el resultado exitoso
		return ['resp' => 1, 'msj' => $resp[1]];
	}

	function validar_documento($file, $parametros)
	{
		$ruta_doc = '';

		$uploadfile_temporal = $file['txt_cargar_imagen']['tmp_name'];
		$nombre = $file['txt_cargar_imagen']['name'];
		$ruta_doc = $this->rutaTemp . str_replace(' ', '_', $nombre);
		// print_r($ruta_p12);die();
		if (is_uploaded_file($uploadfile_temporal)) {
			move_uploaded_file($uploadfile_temporal, $ruta_doc);
		}

		$rutaJar = dirname(__DIR__, 2) . '/lib/firmarPdf/FirmarPDF.jar';
		// $param = array('2',$ruta_p12,$parametros['txt_ingresarClave']);

		// $param = json_encode($param);

		$comando = "java -jar $rutaJar 3 $ruta_doc ";
		$respuesta = shell_exec($comando);

		$resp = json_decode($respuesta);
		if (count($resp) > 0) {
			if ($resp[0] == 1) {
				$firmas = json_decode($resp[1], true);
				// print_r($firmas);die();
				$tr = '';
				foreach ($firmas as $key => $value) {
					$ci = '';
					if (isset($value['SERIALNUMBER'])) {
						$ci = $value['SERIALNUMBER'];
						$serie = explode('-', $value['SERIALNUMBER']);
						if (count($serie) > 1) {
							$ci = $serie[0];
						}
					}
					$tr .= '<tr>
    							<td>' . $ci . '</td>
    							<td>' . str_replace($ci, "", $value['CN']) . '</td>
    							<td>' . $value['EMC_O'] . '</td>
    							<td>' . $value['FechaFirma'] . '</td>
    						</tr>';
				}
				return array('resp' => $resp[0], 'msj' => '', 'tr' => $tr);
			} else {
				return array('resp' => $resp[0], 'msj' => $resp[1], 'tr' => '');
			}
		}

		print_r($ruta_p12);
		print_r($parametros);
		die();
	}


	function firmar_documento($file, $parametros)
	{
		// Determinar la ruta del archivo .p12
		$ruta_p12 = '';

		// Verificar si se ha proporcionado una ruta de firma en el repositorio
		if (!empty($parametros['txt_url_firma'])) {
			// Convertir la ruta relativa a absoluta
			$ruta_p12_repo = str_replace("..", dirname(__DIR__, 2), $parametros['txt_url_firma']);

			if (file_exists($ruta_p12_repo) && is_file($ruta_p12_repo)) {
				// Usar la firma del repositorio
				$ruta_p12 = $ruta_p12_repo;

				// Crear datos para validación cuando se usa firma del repositorio
				$file2 = [
					'txt_cargar_imagen' => [
						'tmp_name' => $ruta_p12,
						'name' => basename($ruta_p12)
					]
				];
			} else {
				return ['resp' => -2, 'mensaje' => 'El archivo de firma en el repositorio no existe o no es válido.'];
			}
		}
		// Si no hay ruta en el repositorio, usar el archivo subido
		elseif (isset($file['uploadFirma']['tmp_name']) && !empty($file['uploadFirma']['tmp_name'])) {
			if (is_uploaded_file($file['uploadFirma']['tmp_name'])) {
				$ruta_p12 = $this->rutaTemp . str_replace(" ", "_", $file['uploadFirma']['name']);
				move_uploaded_file($file['uploadFirma']['tmp_name'], $ruta_p12);

				// Datos para validación cuando se usa firma subida
				$file2 = ['txt_cargar_imagen' => $file['uploadFirma']];
			} else {
				return ['resp' => -2, 'mensaje' => 'El archivo de firma subido no es válido.'];
			}
		} else {
			return ['resp' => -2, 'mensaje' => 'No se ha proporcionado un archivo de firma válido.'];
		}

		// Validar que la contraseña no esté vacía
		if (empty(trim($parametros['txt_passFirma']))) {
			return ['resp' => -2, 'mensaje' => 'La clave de la firma no puede estar vacía.'];
		}

		// Validar que la firma sea correcta
		$parametros['txt_ingresarClave'] = $parametros['txt_passFirma'];
		$parametros['mantener'] = 1;

		$firma_valida = $this->validar_firma($file2, $parametros);

		if ($firma_valida['resp'] == -1) {
			return ['resp' => -2, 'mensaje' => 'Certificado o clave inválidos. Verifique sus credenciales.'];
		}

		// Verificar que exista el documento PDF
		if (!isset($file['uploadPDF']['tmp_name']) || empty($file['uploadPDF']['tmp_name'])) {
			return ['resp' => -2, 'mensaje' => 'No se ha seleccionado un documento PDF.'];
		}

		// Verificar que existan coordenadas de firma
		if (empty($parametros['insertedImages'])) {
			return ['resp' => -2, 'mensaje' => 'No se han definido ubicaciones para las firmas.'];
		}

		// Cargar datos para firmar el documento
		$datos_firmas = json_decode($parametros['insertedImages'], true);
		$pass_p12 = $parametros['txt_passFirma'];

		// Procesar el documento PDF
		$uploadfile_temporal = $file['uploadPDF']['tmp_name'];
		$nombre = $file['uploadPDF']['name'];
		$ruta_doc = $this->rutaTemp . str_replace(' ', '_', $nombre);

		if (is_uploaded_file($uploadfile_temporal)) {
			move_uploaded_file($uploadfile_temporal, $ruta_doc);
		} else {
			return ['resp' => -2, 'mensaje' => 'Error al procesar el documento PDF.'];
		}

		$rutaJar = dirname(__DIR__, 2) . '/lib/firmarPdf/FirmarPDF.jar';
		$respuesta = null;

		foreach ($datos_firmas as $key => $value) {
			$ruta_final = $this->rutaTemp . 'Firmado_' . $key . '_' . str_replace(' ', '_', $nombre);
			$pag = $value['page'];
			$camvas_y = $value['canvasY'];
			$x = intval($value['x'] + 45);
			$y = $camvas_y - intval($value['y'] + 45);

			// Ejecutar el comando para firmar el PDF
			$comando = "java -jar $rutaJar 1 $ruta_p12 $pass_p12 $ruta_doc $ruta_final $x $y $pag";
			$respuesta = shell_exec($comando);

			// Verificar si hubo errores en la ejecución
			if ($respuesta === null) {
				return ['resp' => -2, 'mensaje' => 'Error al ejecutar el proceso de firma.'];
			}

			// Verificar respuesta del JAR (opcional, si tu JAR devuelve resultados en JSON)
			$resp_cmd = json_decode(trim($respuesta), true);
			if (is_array($resp_cmd) && isset($resp_cmd[0]) && $resp_cmd[0] == -1) {
				return ['resp' => -2, 'mensaje' => 'Error durante la firma: ' . ($resp_cmd[1] ?? 'Error desconocido')];
			}

			$this->delete_update_pdf($ruta_doc);
			$ruta_doc = $ruta_final;
		}

		return [
			'resp' => 1,
			'ruta' => str_replace(dirname(__DIR__, 2), "..", $ruta_doc),
			'mensaje' => 'Documento firmado correctamente'
		];
	}


	function delete_update_pdf($archivo_a_eliminar)
	{
		// Eliminar el archivo PDF
		if (file_exists($archivo_a_eliminar)) {
			if (unlink($archivo_a_eliminar)) {
				return 1;
			} else {
				return -1;
			}
		}
	}
}
