<?php 
include('../modelo/cargar_bajasM.php');
require_once('../db/codigos_globales.php');


include('../modelo/marcasM.php');
include('../modelo/estadoM.php');
include('../modelo/generoM.php');
include('../modelo/coloresM.php');
include('../modelo/proyectosM.php');
include('../modelo/localizacionM.php');
include('../modelo/custodioM.php');

/**
 * 
 */
$controlador = new cargar_bajasC();
if(isset($_GET['subir_archivo_server']))
{
	echo json_encode($controlador->subir_archivo_server($_FILES,$_POST['txt_opcion']));
}
if(isset($_GET['ejecutar_sp']))
{
	$parametros = $_POST['parametros'];
	 echo json_encode($controlador->ejecutar_sp($parametros));
}


class cargar_bajasC
{
	private $modelo;
	private $cod_global;
	
	function __construct()
	{
		$this->modelo = new cargar_bajasM();
		$this->cod_global = new codigos_globales();

		$this->marcas = new marcasM();
		$this->estado = new estadoM();
		$this->genero = new generoM();
		$this->color = new coloresM();
		$this->proyecto = new proyectosM();
		$this->localizacion = new  localizacionM();
		$this->custodio = new  custodioM();
		
	}

	function generarCSV($arreglo, $ruta, $delimitador, $encapsulador){
	  $file_handle = fopen($ruta, 'w');
	  foreach ($arreglo as $linea) {
	    fputcsv($file_handle, $linea, $delimitador, $encapsulador);
	  }
	  rewind($file_handle);
	  fclose($file_handle);
	}



	function masivo_csv()
	{
		
		set_time_limit(0);
		$archivo = '../TEMP/plantilla_prueba.csv';
		$fp = fopen ($archivo,"r"); 
		$numRows=0;
		$lista =array();
		while ($data = fgetcsv ($fp, 1000, ";")){ 
			// print_r($data);die();
		  // if(is_numeric($data[1]))
		  // {

		  	 $COMPANYCODE = $data[0];
		  	 $ASSET = $data[1]!='' ? $data[1] : '.';	
		  	 $SUBNUMERO = $data[2]!='' ? $data[2] : '.';	
		  	 $DESCRIPCION = $data[3]!='' ? $data[3] : '.';	
		  	 $DESCRIPCION2 = $data[4]!='' ? $data[4] : '.';	
		  	 $MODELO = $data[5] !='' ? $data[5] : '.';	
		  	 $SERIE = $data[6] !='' ? $data[6] : '.';	
		  	 $RFID = $data[7] !='' ? $data[7] : '.';	
		  	 $FECHA_INVENTARIO = $data[8] !='' ? $data[8] : '.';	
		  	 $CANTIDAD = $data[9] !='' ? $data[9] : '0';	
		  	 $UNIDAD_MEDIDA = $data[10] !='' ? $data[10] : '.';	

		  	 $lo = '0'; 	 $cus = '0';  $ma= '0'; $es= '0'; $ge= '0'; $co= '0'; $pr = '0';

		  	 $l = $this->localizacion->buscar_localizacion_codigo($data[11]);
		  	 if(count($l)>0){ $lo = $l[0]['ID_LOCATION'] !='' ? $l[0]['ID_LOCATION'] : '.';	 }		  	 
		  	 $EMPLA = $data[12]!='' ? $data[12] : '.';	
		  	 $cu = $this->custodio->buscar_custodio_($data[13]);
		  	 if(count($cu)>0){ $cus = $cu[0]['ID_PERSON'];}
		  	 $CUS_NOM = $data[14]!='' ? $data[14] : '.';	
		  	 $m = $this->marcas->buscar_marcas_codigo($data[15]);
		  	 if(count($m)>0){ $ma = $m[0]['ID_MARCA'];}
		  	 $e = $this->estado->buscar_estado_CODIGO($data[16]);
		  	 if(count($e)>0){ $es = $e[0]['ID_ESTADO'] ;}
		  	 $g = $this->genero->buscar_genero_CODIGO($data[17]);
		  	 if(count($g)>0){ $ge = $g[0]['ID_GENERO'] ;}
		  	 $c = $this->color->buscar_colores_codigo($data[18]);
		  	 if(count($c)>0){ $co = $c[0]['ID_COLORES'] ;}
		  	 $p = $this->proyecto->buscar_proyecto_programa($data[19]);
		  	 if(count($p)>0){ $pr = $p[0]['id'] ;}

		  	 $SUPRA_NUMERO = $data[20] !='' ? $data[20] : '0';	
		  	 $TAG_ANTIGUO = $data[21]!='' ? $data[21] : '.';	
		  	 $FECHA_COMPRA = $data[22] !='' ? $data[22] : '.';	
		  	 $VALOR_ACTUAL = $data[23]  !='' ? $data[23] : '0';	
		  	 $OBSEVACIONES = $data[24] !='' ? $data[24] : '.';	
		  	 $BAJAS = $data[25]!='' ? $data[25] : '0';	
		  	 $NOTE1 = $data[26] !='' ? $data[26] : '.';	
		  	 $IMAGEN = $data[27] !='' ? $data[27] : '.';	
		  	 $ACTUALIZADO_POR = $data[28]  !='' ? $data[28] : '.';	
		  	 $FECHA_BAJA = $data[29] !='' ? $data[29] : '.';		  	

			 $lista[] = array($COMPANYCODE,$ASSET,$SUBNUMERO,$DESCRIPCION,$DESCRIPCION2,$MODELO,$SERIE,$RFID,$FECHA_INVENTARIO,$CANTIDAD,$UNIDAD_MEDIDA,
		  	 $lo,
		  	 $EMPLA,
		  	 $cus,
		  	 $CUS_NOM, 
		  	 $ma,
		  	 $es,
		  	 $ge,
		  	 $co,
		  	 $pr,
		  	 $SUPRA_NUMERO,
		  	 $TAG_ANTIGUO,
		  	 $FECHA_COMPRA,
		  	 $VALOR_ACTUAL,
		  	 $OBSEVACIONES,
		  	 $BAJAS,
		  	 $NOTE1,
		  	 $IMAGEN,
		  	 $ACTUALIZADO_POR,
		  	 $FECHA_BAJA);
		  	 // print_r($lista);die();
		  	 // if($numRows==10000)
		  	 // {
		  	 // 	break;
		  	 // }
		  	 // $numRows = $numRows+1;
	  	  // }

		}


		$ruta = '../TEMP/mi_archivo.csv';
		$this->generarCSV($lista, $ruta, $delimitador = ';', $encapsulador = '"');
	}


	
	function subir_archivo_server($file,$op)
	{
	  	if($file['file']['type'] == 'text/csv')
	  	{
	  		 $uploadfile_temporal=$file['file']['tmp_name'];
	  		 $ruta = '../TEMP/';
	   	     //$tipo = explode('/', $file['file']['type']);
	       $nombre = '';
	        if ($op==1) {
	          $nombre = 'BAJAS.csv';
	        }elseif ($op==2) {
	          $nombre = 'TERCEROS.csv';
	        }elseif ($op==3) {
	          $nombre = 'PATRIMONIALES.csv';
	        }	        
	   	     $nuevo_nom=$ruta.$nombre;
	   	     if (is_uploaded_file($uploadfile_temporal))
	   	     {
	   		     move_uploaded_file($uploadfile_temporal,$nuevo_nom); 
	   		     return 1;  		     
	   	     }
	   	     else
	   	     {
	   		    return -1;
	   	     } 
	  	}else
	    {
	      return -2;
	    }
	}

	function ejecutar_sp($parametros)
	{

		set_time_limit(0);
		if($parametros['id']==1)
		{
			$resp = $this->modelo->ejecutar_bajas();
			return $resp;
		}
		if($parametros['id']==2)
		{
			$resp = $this->modelo->ejecutar_terceros();
			return $resp;
		}
		if($parametros['id']==3)
		{
			$resp = $this->modelo->ejecutar_patrimoniales();
			return $resp;
		}
	}
}
?>