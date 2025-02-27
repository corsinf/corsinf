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
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->getDBtable());
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
		$resultArray = [];
		parse_str($parametros, $data);
		$tabla = '';
		$tabla2 = '';
		$select = '';
		$rfid = 0;
		$automatico = 0;
		$campo_select = '';
		$campo_rfid = '';
		foreach ($data as $key => $value) {
			$campo = str_replace('ddl_','', $key);
			$campo = str_replace('rbl_','', $campo);
			switch ($campo) {
				case 'tabla':
					$tabla = $value;
					$tabla2 = $value;
					break;
				case 'tabla2':
					if($value!=''){	$tabla2 = $value;}
					break;
				case 'rfid_code':
					$campo_rfid = $value.' as rfid';
					break;
				case 'rfid':
					if($value=='on')
					{
						$rfid = 1;
					}
					break;
				case 'rfid_automatico':
					$automatico = 1;
					break;
				
				default:
					$campo_select.= $value.' as '.$campo.',';
					break;
			}
		}

		if(!isset($data['rbl_rfid']))
		{
			$campo_rfid = '';
			$tabla2 = '';
		}

		$campo_select = substr($campo_select, 0,-1);
		$datos = $this->modelo->datosdb($campo_select,$tabla,$campo_rfid,$tabla2);

		$principal = count($datos['principal']);
		$RFID = count($datos['rfid']);
		if($principal!=$RFID && $rfid==1)
		{
			return -2;
		}else
		{
			return $datos;
		}
		
		// 	$lista = $this->modelo->buscar_etiquetas_anteriores($_SESSION['INICIO']['ID_USUARIO']);
		// 	foreach ($lista as $key => $value) {
		// 		if($parametros['igual']=='true' && $parametros['codificar']=='true')
		// 		{
		// 			$datos[0]['campo']='RFID';
		// 			$datos[0]['dato'] = $value['SERIE'];
		// 		}else
		// 		{
		// 			$datos[0]['campo']='RFID';
		// 			$datos[0]['dato'] = null;					
		// 		}

		// 		$where[0]['campo'] = 'ID';
		// 		$where[0]['dato'] = $value['ID'];
		// 		$this->articulos->update('IMPRIMIR_TAGS',$datos,$where);
		// 	}

		// $data_ant = $this->modelo->buscar_etiquetas_anteriores($_SESSION['INICIO']['ID_USUARIO']);
		// return $data_ant;
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

	function getDBtable()
	{
		$tablas = $this->modelo->tablasDb();
		return $tablas;
	}
	function getDBcampos($parametros)
	{
		$tablas = $this->modelo->CamposDb($parametros['tabla']);
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
}

?>