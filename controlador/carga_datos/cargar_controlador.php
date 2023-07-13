<?php 
include(dirname(__DIR__,2).'/modelo/cargar_modelo.php');
date_default_timezone_set('America/Guayaquil'); 
require_once dirname(__DIR__,2).'/lib/spout_excel/vendor/box/spout/src/Spout/Autoloader/autoload.php';
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

/**
 * 
 */
$controlador = new cargar_controlador();
if(isset($_GET['leer']))
{
	echo json_encode($controlador->leer_excel());
}
class cargar_controlador
{
	private $modelo;
	private $archivo_name;
	function __construct()
	{
		$this->modelo = new cargar_modelo();
		$this->archivo_name = 'datosprueba.csv';
	}

	function crear_excel()
	{


	}
	function leer_excel()
	{

		$url = 'C:\Users\usuario\Desktop/datos10000.csv';
		$reader = ReaderEntityFactory::createReaderFromFile($url);
		$reader->open($url);

		$datos_excel = array();
		$datos_excel_error = array();
		$num_col_head = 0;
		if(file_exists($this->archivo_name))
		{
			unlink($this->archivo_name);
		}

		foreach ($reader->getSheetIterator() as $sheet) {
		    foreach ($sheet->getRowIterator() as $key => $row) {
		        // do stuff with the row
		        $cells = $row->toArray();
		        $cols = explode(';',$cells[0]);
		        // $cells = $row->getCells();
		        if($key>2)
		        {
		        	if(count($cols)==$num_col_head)
		        	{
			        	$res = $this->validar_existencia($marcas=$cols[16],$genero=$cols[18],$colores=$cols[19],$estado=$cols[17],$custodio=$cols[14],$emplazamiento=$cols[12],$proyecto=$cols[20],$clase_mov=$cols[31]);
			        	// print_r($res);die();
			        	if($res!=-1)
			        	{
			        		if(isset($res[0]['CLASE_MOVIMIENTO'])){ $cols[31] = $res[0]['CLASE_MOVIMIENTO']; }
			        		if(isset($res[0]['PROYECTO'])){ $cols[20] = $res[0]['PROYECTO']; }

			        		// son datos obligatorios 
			        		if(isset($res[0]['MARCAS']) && isset($res[0]['GENERO']) && isset($res[0]['COLORES']) &&  isset($res[0]['ESTADO']) && isset($res[0]['CUSTODIO']) && isset($res[0]['EMPLAZAMIENTO']))
			        		{
			        			$cols[16] = $res[0]['MARCAS'];
				        		$cols[18] = $res[0]['GENERO'];
				        		$cols[19] = $res[0]['COLORES'];
				        		$cols[17] = $res[0]['ESTADO'];
				        		$cols[14] = $res[0]['CUSTODIO'];
				        		$datos_excel[] = $cols;
			        		}
			        	}
			        }else
			        {
			        	$datos_excel_error[] = $cols; 
			        }
		        }else
		        {
		        	$num_col_head = count($cols);
		        	$datos_excel[] = $cols;		        	
		        }
		    }
		}

		$this->insert_in_excel($datos_excel,$datos_excel_error);
		$reader->close();

	}

	function insert_masivo()
	{

		try {
		    $db = new db();
		    $conn = $bd->conexion_pdo();

		    // Consulta SQL para la inserción
		    $sql = "INSERT INTO nombre_tabla (columna1, columna2, columna3) VALUES (?, ?, ?)";
		    
		    // Preparar la instrucción SQL
		    $stmt = $conn->prepare($sql);
		    
		    // Aumentar el tamaño del búfer de salida
		    ob_start();
		    
		    // Iniciar transacción
		    $conn->beginTransaction();
		    
		    // Ejecutar inserciones por lotes
		    for ($i = 0; $i < 10000; $i++) {
		        // Asignar nuevos valores
		        $valor1 = "dato1_" . $i;
		        $valor2 = "dato2_" . $i;
		        $valor3 = "dato3_" . $i;		        
		        // Ejecutar la inserción
		        $stmt->execute([$valor1, $valor2, $valor3]);
		    }
		    
		    // Confirmar la transacción
		    $conn->commit();
		    
		    // Cerrar la instrucción y la conexión
		    $stmt = null;
		    $conn = null;
		    
		    ob_end_flush();
		} catch (PDOException $e) {
		    echo "Error de conexión: " . $e->getMessage();
		}
	}

	function validar_existencia($marcas=false,$genero=false,$colores=false,$estado=false,$custodio=false,$emplazamiento=false,$proyecto=false,$clase_mov=false)
	{
		$datos = $this->modelo->validar_exsitencia($marcas,$genero,$colores,$estado,$custodio,$emplazamiento,$proyecto,$clase_mov);
		// print_r($datos);die();
		if (in_array("0", $datos[0])) {
		    return -1;
		}else{
			return $datos;
		}
	}

	function insert_in_excel($cols,$errores)
	{
		
		$filePath = $this->archivo_name; 
		$writer = WriterEntityFactory::createCSVWriter();
		$writer->setFieldDelimiter(';');		
		$writer->openToFile($filePath,'a');
		foreach ($cols as $key => $value) {
			$rowFromValues = WriterEntityFactory::createRowFromArray($value);
			$writer->addRow($rowFromValues);
		}
		$writer->close();


		$filePath = 'errores.csv'; 
		$writer = WriterEntityFactory::createCSVWriter();
		$writer->setFieldDelimiter(';');	
		$writer->openToFile($filePath);
		foreach ($errores as $key => $value) {
			$rowFromValues = WriterEntityFactory::createRowFromArray($value);
			$writer->addRow($rowFromValues);
		}
		$writer->close();

		// die();

	}
}
?>