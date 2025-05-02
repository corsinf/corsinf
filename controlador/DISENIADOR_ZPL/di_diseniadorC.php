<?php 
require_once(dirname(__dir__,2).'/modelo/articulosM.php');
require_once(dirname(__dir__,2).'/modelo/DISENIADOR_ZPL/di_diseniadorM.php');
require dirname(__dir__,2).'/lib/spout_excel/vendor/autoload.php';

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

$controlador = new di_diseniadorC();
if(isset($_GET['imprimirTag']))
{
	$datos = $_POST['parametros'];
	echo json_encode($controlador->imprimir_etiqueta($datos));

}
if(isset($_GET['addLista']))
{
	$datos = $_POST['datos'];
	echo json_encode($controlador->ingresar_datos($datos));

}
if(isset($_GET['EliminarAnte']))
{
	echo json_encode($controlador->eliminar_etiquetas_anteriores());

}
if(isset($_GET['addDatos']))
{
	$parametros = $_POST['data'];
	echo json_encode($controlador->cargarOrigendatos($parametros));
}
if(isset($_GET['getDBtable']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->getDBtable($parametros));
}
if(isset($_GET['getDBcampos']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->getDBcampos($parametros));
}
if(isset($_GET['desde_excel']))
{	
	$parametros = '';
	// $_POST['parametros'];
	echo json_encode($controlador->cargar_desde_excel($parametros));
}
if(isset($_GET['buscar_impresora']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->buscar_impresora($parametros));
}
if(isset($_GET['guardar_impresora']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_impresora($parametros));
}
if(isset($_GET['lista_impresora']))
{	
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_impresora());
}
if(isset($_GET['GuardarDisenio']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->GuardarDisenio($parametros));
}
if(isset($_GET['ListaEtiquetas']))
{	
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->ListaEtiquetas());
}
if(isset($_GET['ListaEtiquetasDetalle']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->ListaEtiquetasDetalle($parametros));
}
if(isset($_GET['deleteEtiquetas']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->deleteEtiquetas($parametros));
}
if(isset($_GET['probar_conexion']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->probar_conexion($parametros));
}
if(isset($_GET['DescargarLibPrinter']))
{	
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->descargar_printLib());
}


class di_diseniadorC
{
	
	function __construct()
	{		
		$this->articulos = new articulosM();
		$this->modelo = new di_diseniadorM();
	}

	function listar_impresoras()
	{
		$impresoras = shell_exec('powershell -command "Get-Printer | Select-Object -ExpandProperty Name"');
		$lista = explode("\n", trim($impresoras));
	}

	function cargarOrigendatos($parametros)
	{
		// print_r($parametros);die();
		$selec_armado = '';
		$tabla = $parametros['tabla'];
		foreach ($parametros['campos'] as $key => $value) {
			$selec_armado.=''.$value." as '".$key."',";
		}
		$selec_armado = substr($selec_armado, 0,-1);

		if($parametros['terceros']==0)
		{
			$datos = $this->modelo->datosdb($selec_armado,$tabla);
		}
		if($parametros['terceros']==1)
		{
			$datos = $this->modelo->datosdbTerceros($selec_armado,$tabla,$parametros['db'], $parametros['user'], $parametros['pass'], $parametros['host'], $parametros['port']);
		}

		return $datos['principal'];
		
		
	}
	function ingresar_datos($datos)
	{
		$lista = explode("\n",$datos);
		$data_ant = $this->modelo->buscar_etiquetas_anteriores($_SESSION['INICIO']['ID_USUARIO']);

		if(count($data_ant)==0)
		{

			foreach ($lista as $key => $value) {
				$datoss2[0]['campo']='ID_USUARIO';
				$datoss2[0]['dato']=$_SESSION['INICIO']['ID_USUARIO'];
				$datoss2[1]['campo']='SERIE';
				$datoss2[1]['dato'] = $value;
				// print_r($datoss2);die();
				$this->articulos->insertar($datoss2,'IMPRIMIR_TAGS');
			}

			return 1;
		}else
		{
			return -2;
		}
	}


	function eliminar_etiquetas_anteriores()
	{
		return $this->modelo->eliminar_etiquetas_anteriores($_SESSION['INICIO']['ID_USUARIO']);
	}

	function imprimir_etiqueta($parametros)
	{
		// print_r($parametros);die();
		$data = $parametros['code'];
		$patch = dirname(__dir__,2).'\TEMP\\';
		if(!file_exists($patch))
		{
			mkdir($patch,0777);
		}

		$nombreArchivo = $patch."imprimirTag_".date("s")."_".$parametros['indice'].".zpl";

		$contenido = $data['zpl'];

		// Crear y abrir el archivo en modo de escritura
		$archivo = fopen($nombreArchivo, "w"); // "w" para escribir (sobrescribe si ya existe)

		// Verificar si el archivo se abrió correctamente
		if ($archivo) {
		    // Escribir el contenido en el archivo
		    fwrite($archivo, $contenido);

		    // Cerrar el archivo
		    fclose($archivo);
		}

		

		return 1;

		// $scriptPath = $patch.'script.ps1';


		// $zplScript = <<<PS
		// \$zplCommand = Get-Content "$nombreArchivo" | Out-String
		// \$printerIP = "192.168.1.122"
		// \$port = 9100
		// \$tcpClient = New-Object System.Net.Sockets.TcpClient(\$printerIP, \$port)
		// \$stream = \$tcpClient.GetStream()
		// \$writer = New-Object System.IO.StreamWriter(\$stream)
		// \$writer.Write(\$zplCommand)
		// \$writer.Flush()
		// \$tcpClient.Close()
		// PS;

		// // Ruta del archivo temporal para guardar el script
		// $tempScriptPath = $scriptPath;

		// // Guarda el script de PowerShell en un archivo
		// file_put_contents($tempScriptPath, $zplScript);

		// // Ejecuta el archivo PowerShell desde PHP
		// $output = shell_exec("powershell -ExecutionPolicy Bypass -File \"$tempScriptPath\"");

		// // Muestra la salida
		// if ($output) {
		//     echo "Script ejecutado con éxito:\n$output";
		// } else {
		//     echo "Error al ejecutar el script.";
		// }

	}

	function getDBtable($parametros)
	{
		// print_r($parametros);die();
		if($parametros['terceros']==0)
		{
			$tablas = $this->modelo->tablasDb();
		}
		if($parametros['terceros']=='1')
		{
			$tablas = $this->modelo->tablasDbTerceros($parametros['db'], $parametros['user'], $parametros['pass'], $parametros['host'], $parametros['port']);
		}
		return $tablas;
	}
	function getDBcampos($parametros)
	{

		// print_r($parametros);die();
		if($parametros['terceros']==0)
		{
			$tablas = $this->modelo->CamposDb($parametros['tabla']);
		}
		if($parametros['terceros']=='1')
		{
			$tablas = $this->modelo->CamposDbTerceros($parametros['tabla'],$parametros['db'], $parametros['user'], $parametros['pass'], $parametros['host'], $parametros['port']);
		}
		return $tablas;
	}

	function cargar_desde_excel($parametros)
	{
		// Ruta al archivo Excel
		$inputFileName = 'C:\Users\lenovo\Downloads\RESOLUCIONES 30 ENE 2025_.CSV';

		// Crear un lector de Excel
		$reader = ReaderEntityFactory::createXLSXReader();
		$reader->open($inputFileName);

		// Iterar sobre cada hoja del archivo
		foreach ($reader->getSheetIterator() as $sheet) {
		    echo "Leyendo la hoja: " . $sheet->getName() . "\n";

		    // Iterar sobre cada fila de la hoja
		    foreach ($sheet->getRowIterator() as $rowIndex => $row) {
		        // Obtener las celdas de la fila actual
		        $cells = $row->getCells();

		        // Iterar sobre cada columna (celda) de la fila
		        foreach ($cells as $columnIndex => $cell) {
		            $value = $cell->getValue();
		            echo "Columna $columnIndex, Fila $rowIndex: $value\n";
		        }
		    }
		}

		// Cerrar el lector
		$reader->close();
	}

	function lista_impresora()
	{
		$datos = $this->modelo->lista_impresora();
		// print_r($datos);die();
		return $datos;
	}

	function buscar_impresora($parametros)
	{
		$command = "java -jar ".dirname(__DIR__,2)."\lib\IMPRESORA\Printlib.jar 1 ".$parametros['tipoBusqueda'];
		// print_r($command);die();	
		$respuesta = shell_exec($command);
		// print_r($respuesta);die();
		// return $respuesta;
    	$resp = json_decode($respuesta);
    	return $resp;
	}

	function guardar_impresora($parametros)
	{

		$ipAddress = $parametros['ipAddress'];
		$puerto = $parametros['puerto'];

		$data = array(
			array("campo"=>"tipo_impresora","dato"=>$parametros['tipoBusqueda']),
			array("campo"=>"nombre_impresora","dato"=>$parametros['impresora'][0]),
			array("campo"=>"puerto_impresora","dato"=>$puerto),
			array("campo"=>"ip_impresora","dato"=>$ipAddress),
			array("campo"=>"ruta_impresora","dato"=>$parametros['impresora'][0]),
		);

		return $this->modelo->inserts("ac_impresoras",$data);
		print_r($parametros);die();
	}

	function GuardarDisenio($parametros)
	{
		// print_r($parametros);die();
		$fechaString = date("Y-m-d H:i:s");
		$fechaObjeto = DateTime::createFromFormat('Y-m-d H:i:s', $fechaString);
		$rfid = 0;
		if($parametros['rfid']!='false') {$rfid = 1;}

		$tabla = 'ac_disenio_tag';
		$datos = array(
			array("campo"=>"ac_disenio_tag_nombre","dato"=>$parametros['nombre']),
			array("campo"=>"ac_disenio_tag_elementos","dato"=>$parametros['elementos'],'tipo'=>'STRING'),
			array("campo"=>"ac_disenio_tag_creacion","dato"=>$fechaString),
			array("campo"=>"ac_disenio_tag_ancho","dato"=>$parametros['ancho']),
			array("campo"=>"ac_disenio_tag_alto","dato"=>$parametros['alto']),
			array("campo"=>"ac_disenio_tag_dpi","dato"=>$parametros['dpi']),
			array("campo"=>"ac_disenio_tag_unidad","dato"=>$parametros['unidad']),
			array("campo"=>"ac_disenio_tag_rfid","dato"=>$rfid),
		);

		if($parametros['id']=='')
		{
			// print_r($datos);die();
			return $this->modelo->inserts($tabla,$datos);
		}else
		{
			$where = array(
			array("campo"=>"ac_disenio_tag_id","dato"=>$parametros['id'])
		);
			return $this->modelo->update($tabla,$datos,$where);
		}
	}

	function ListaEtiquetas()
	{
		$data = $this->modelo->ListaEtiquetas();
		return $data;
	}

	function ListaEtiquetasDetalle($parametros)
	{
		$data = $this->modelo->ListaEtiquetas($parametros['id']);
		return $data;
	}

	function deleteEtiquetas($parametros)
	{
		return $this->modelo->deleteEtiquetas($parametros['id']);
	}

	function probar_conexion($parametros)
	{
		$con = $this->modelo->comprobar_conexcion_terceros($parametros['db'], $parametros['user'], $parametros['pass'], $parametros['host'], $parametros['port']);
		return $con;
		// print_r($con);die();
	}

	function descargar_printLib()
	{
		$filepath = dirname(__DIR__,2). '/lib/IMPRESORA/CorsinfPrinter/printerlib.rar';
		// print_r($filepath);die();

		if (file_exists($filepath)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="Printerlib.rar"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($filepath));
		    flush(); // Limpia el búfer del sistema
		    readfile($filepath);
		    exit;
		} else {
		    http_response_code(404);
		    echo "Archivo no encontrado.";
		}
	}
}

?>