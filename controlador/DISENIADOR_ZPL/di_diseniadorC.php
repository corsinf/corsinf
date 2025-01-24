<?php 
$controlador = new di_diseniadorC();
if(isset($_GET['imprimirTag']))
{
	$datos = $_POST['parametros'];
	$controlador->imprimir_etiqueta($datos);

}

class di_diseniadorC
{
	
	function __construct()
	{
		// code...
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

		$nombreArchivo = $patch."imprimirTag_".date("s").".zpl";



		// Contenido que deseas escribir
		if($parametros['RFID']!='' && $parametros['RFIDOp']=='true')
		{
		$RFID = '
// Configuración RFID
^RS,,,1,2                       // Configurar el RFID
^RB                            // Comando RFID para preparar el tag
^RFW,H,                         // Escribir datos en el tag RFID
^FD^FD'.$parametros['RFID'].'^FS // EPC (Código RFID) - reemplaza con el EPC real
^PQ1';
		$data = str_replace('^PQ1', $RFID,$data);
	}
		
		$contenido = $data;

		// Crear y abrir el archivo en modo de escritura
		$archivo = fopen($nombreArchivo, "w"); // "w" para escribir (sobrescribe si ya existe)

		// Verificar si el archivo se abrió correctamente
		if ($archivo) {
		    // Escribir el contenido en el archivo
		    fwrite($archivo, $contenido);

		    // Cerrar el archivo
		    fclose($archivo);
		}

		// die();

		$scriptPath = $patch.'script.ps1';


		$zplScript = <<<PS
		\$zplCommand = Get-Content "$nombreArchivo" | Out-String
		\$printerIP = "192.168.1.122"
		\$port = 9100
		\$tcpClient = New-Object System.Net.Sockets.TcpClient(\$printerIP, \$port)
		\$stream = \$tcpClient.GetStream()
		\$writer = New-Object System.IO.StreamWriter(\$stream)
		\$writer.Write(\$zplCommand)
		\$writer.Flush()
		\$tcpClient.Close()
		PS;

		// Ruta del archivo temporal para guardar el script
		$tempScriptPath = $scriptPath;

		// Guarda el script de PowerShell en un archivo
		file_put_contents($tempScriptPath, $zplScript);

		// Ejecuta el archivo PowerShell desde PHP
		$output = shell_exec("powershell -ExecutionPolicy Bypass -File \"$tempScriptPath\"");

		// Muestra la salida
		if ($output) {
		    echo "Script ejecutado con éxito:\n$output";
		} else {
		    echo "Error al ejecutar el script.";
		}



	}
}

?>