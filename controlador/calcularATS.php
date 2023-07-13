<?php
date_default_timezone_set('America/Guayaquil'); 
require '../lib/lib_excel/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include('../comprobantes/SRI/autorizar_sri.php');

$controlador = new calcular(); 
if(isset($_GET['calcularexcel']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_tabla());
}

if(isset($_GET['filtrar_doc']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->filtrar($parametros));
}

if(isset($_GET['subir_archivo_server']))
{
	echo json_encode($controlador->subir_archivo_server($_FILES));
}

class calcular
{
		private $linkSriRecepcion;
		private $documentos;
	function __construct()
	{
		$this->sri = new autorizacion_sri();
	}


	function filtrar($parametros)
	{
		set_time_limit(0);
		$this->leer_xml_carpeta();
		$lineas_xml = $this->leer_archivo_xmls();
		$facturas_doc = $this->calcular_excel();


		$tipo_doc = array();
		foreach ($facturas_doc as $key => $value) {
			$tipo_doc[] = $value[0]; 
		}

		$tipo_doc = array_unique($tipo_doc);
		$tipo_doc = array_values($tipo_doc);

		// print_r($facturas_doc);die();
		// print_r($lineas_xml);die();
		// print_r($tipo_doc);die();

		$tr = '';
		$ingresa = 0;
		//-------------------todso los comprobantes listado------------------
		foreach ($facturas_doc as $key => $value) {
			if(is_numeric($value[9]))
			{
				if($value[0]==$parametros['tipo'])
				{
					$tot = '';
					if(isset($value[11])){ $tot = $value[11]; }
					$tr.='<tr><td>'.$value[0].'</td><td>'.$value[1].'</td><td>'.$value[2].'</td><td>'.$value[3].'</td><td>'.$value[4].'</td><td>'.$value[7].'</td><td>'.$value[8].'</td><td>'.$value[9].'</td><td>'.$value[10].'</td><td>'.$tot.'</td></tr>';
					//----------------------------todas ala lineas de los xml leidos-----------------------
					foreach ($lineas_xml as $key2 => $value2) {

						//--------------------------conpara el numero de autorizacion de xmls leidos y listado de comprobantes--------
						if($value2[0]['Autorizacion']==$value[9])
						{
							$ingresa = 1;
							$tr.='<tr><td colspan="9"><table style="border: 1px solid">';
							foreach ($value2 as $key3 => $value3) {

								if($value3['Tipo']=='F')
								{
									if($key3==0)
									{
									 $tr.='<tr>
												<td>Detalle</td>
												<td>Cantidad</td>
												<td>Precio</td>
												<td>Descuento</td>
												<td>subtotal</td>
												<td>Iva</td>
												<td>total</td>
											</tr>';
												
									}

									 $tr.='<tr><td>'.$value3['detalle'].'</td><td>'.$value3['cantidad'].'</td><td>'.$value3['pvp'].'</td><td>'.$value3['descuento'].'</td><td>'.$value3['subtotal'].'</td><td>'.$value3['iva_v'].'</td><td>'.$value3['Total'].'</td></tr>';
									// print_r('Fac');
									// print_r($value2);die();
								}

								if($value3['Tipo']=='R')
								{
									if($key3==0)
									{
									$tr.='
										<tr><td>Detalle</td><td>base imponible</td><td>porcentaje</td><td>Valor</td></tr>';
									}

									$tr.='<tr><td colspan="10">'.$value3['detalle'].'</td><td>'.$value3['baseImponible'].'</td><td>'.$value3['Porcentaje'].'</td><td>'.$value3['valor'].'</td></tr>';

									// print_r($tr);die();
								}
								if($value3['Tipo']=='NC')
								{
									if($key3==0)
									{
									 $tr.='<tr>
												<td>Detalle</td>
												<td>Cantidad</td>
												<td>Precio</td>
												<td>Descuento</td>
												<td>subtotal</td>
												<td>Iva</td>
												<td>total</td>
											</tr>';
												
									}

									 $tr.='<tr><td>'.$value3['detalle'].'</td><td>'.$value3['cantidad'].'</td><td>'.$value3['pvp'].'</td><td>'.$value3['descuento'].'</td><td>'.$value3['subtotal'].'</td><td>'.$value3['iva_v'].'</td><td>'.$value3['Total'].'</td></tr>';
									// print_r('Fac');
									// print_r($value2);die();
								}						
							}
							// print_r($value2);die();

						}
						if($ingresa==1)
						{
							$tr.='</table></td></tr>';
							$ingresa=0;						
						}
						// print_r($value2);die();
					}
				}
				// print_r($value);die();
			}else
			{
				//titulos de tabla
				$tr.='<tr><td>'.$value[0].'</td><td>'.$value[1].'</td><td>'.$value[2].'</td><td>'.$value[3].'</td><td>'.$value[4].'</td><td>'.$value[7].'</td><td>'.$value[8].'</td><td>'.$value[9].'</td><td>'.$value[10].'</td><td>'.$value[11].'</td></tr>';			
			}
		}

		return array('tr'=>$tr);

	}


	function generar_tabla()
	{
		set_time_limit(0);
		$this->leer_xml_carpeta();
		$lineas_xml = $this->leer_archivo_xmls();
		$facturas_doc = $this->calcular_excel();


		$tipo_doc = array();
		foreach ($facturas_doc as $key => $value) {
			$tipo_doc[] = $value[0]; 
		}

		$tipo_doc = array_unique($tipo_doc);
		$tipo_doc = array_values($tipo_doc);

		// print_r($facturas_doc);die();
		// print_r($lineas_xml);die();
		// print_r($tipo_doc);die();

		$tr = '';
		$ingresa = 0;
		//-------------------todso los comprobantes listado------------------
		foreach ($facturas_doc as $key => $value) {
			if(is_numeric($value[9]))
			{
				$tot = '';
				if(isset($value[11])){ $tot = $value[11]; }
				$tr.='<tr><td>'.$value[0].'</td><td>'.$value[1].'</td><td>'.$value[2].'</td><td>'.$value[3].'</td><td>'.$value[4].'</td><td>'.$value[7].'</td><td>'.$value[8].'</td><td>'.$value[9].'</td><td>'.$value[10].'</td><td>'.$tot.'</td></tr>';
				//----------------------------todas ala lineas de los xml leidos-----------------------
				foreach ($lineas_xml as $key2 => $value2) {

					//--------------------------conpara el numero de autorizacion de xmls leidos y listado de comprobantes--------
					if($value2[0]['Autorizacion']==$value[9])
					{
						$ingresa = 1;
						$tr.='<tr><td colspan="9"><table style="border: 1px solid">';
						foreach ($value2 as $key3 => $value3) {

							if($value3['Tipo']=='F')
							{
								if($key3==0)
								{
								 $tr.='<tr>
											<td>Detalle</td>
											<td>Cantidad</td>
											<td>Precio</td>
											<td>Descuento</td>
											<td>subtotal</td>
											<td>Iva</td>
											<td>total</td>
										</tr>';
											
								}

								 $tr.='<tr><td>'.$value3['detalle'].'</td><td>'.$value3['cantidad'].'</td><td>'.$value3['pvp'].'</td><td>'.$value3['descuento'].'</td><td>'.$value3['subtotal'].'</td><td>'.$value3['iva_v'].'</td><td>'.$value3['Total'].'</td></tr>';
								// print_r('Fac');
								// print_r($value2);die();
							}

							if($value3['Tipo']=='R')
							{
								if($key3==0)
								{
								$tr.='
									<tr><td>Detalle</td><td>base imponible</td><td>porcentaje</td><td>Valor</td></tr>';
								}

								$tr.='<tr><td colspan="10">'.$value3['detalle'].'</td><td>'.$value3['baseImponible'].'</td><td>'.$value3['Porcentaje'].'</td><td>'.$value3['valor'].'</td></tr>';

								// print_r($tr);die();
							}
							if($value3['Tipo']=='NC')
							{
								if($key3==0)
								{
								 $tr.='<tr>
											<td>Detalle</td>
											<td>Cantidad</td>
											<td>Precio</td>
											<td>Descuento</td>
											<td>subtotal</td>
											<td>Iva</td>
											<td>total</td>
										</tr>';
											
								}

								 $tr.='<tr><td>'.$value3['detalle'].'</td><td>'.$value3['cantidad'].'</td><td>'.$value3['pvp'].'</td><td>'.$value3['descuento'].'</td><td>'.$value3['subtotal'].'</td><td>'.$value3['iva_v'].'</td><td>'.$value3['Total'].'</td></tr>';
								// print_r('Fac');
								// print_r($value2);die();
							}						
						}
						// print_r($value2);die();

					}
					if($ingresa==1)
					{
						$tr.='</table></td></tr>';
						$ingresa=0;						
					}
					// print_r($value2);die();
				}
				// print_r($value);die();
			}else
			{
				//titulos de tabla
				$tr.='<tr><td>'.$value[0].'</td><td>'.$value[1].'</td><td>'.$value[2].'</td><td>'.$value[3].'</td><td>'.$value[4].'</td><td>'.$value[7].'</td><td>'.$value[8].'</td><td>'.$value[9].'</td><td>'.$value[10].'</td><td>'.$value[11].'</td></tr>';
			
			}
		}

		return array('tr'=>$tr,'tipo'=>$tipo_doc);

	}

	function calcular_excel()
	{

		// 1 pruebas - 2 produccion
		$link = $this->links_sri(2);
		$archivo = dirname(__DIR__,1)."/TEMP/datos.txt";
		$fp = fopen($archivo, "r");
		$tr = array();
		$num = 0;
		while(!feof($fp)) 
		{
			
				$linea = fgets($fp);
				// $linea = str_replace('		', '	', $linea);
				$ln = explode('	', $linea);
				if(count($ln)==1)
				{

					$posTR = count($tr)-1;
					$posln = count($tr[$posTR]);			
					$tr[$posTR][$posln] = $ln[0];

					// if(is_numeric($ln[0]))
					// {
						// print_r($ln);die();
					// }
				}else{
					if(isset($ln[8]) && is_numeric($ln[8]))
					{
						//$ln[11] = $this->sri->comprobar_xml_sri($ln[9],$link[0]);
						// $fh = fopen('comprobantes/XMLS/FIRMADOS/'.$ln[9].".xml", 'w');
						// $resp = $this->sri->enviar_xml_sri($ln[9],$link[1]);
						// $resp2 = $this->sri->comprobar_xml_sri($ln[9],$link[0]);
					}
					$numero = is_numeric($ln[2]);
					if(!$numero)
					{
						// print_r('expression');die();
						$ln = array_merge(array_slice($ln,0,7), array('-'), array_slice($ln,7));
						// print_r($ln);die();
					}
					$tr[] = array_map("utf8_encode",$ln);
				}
			
		}
		fclose($fp);
		// print_r($tr);die();
		return $tr;
	}

	function leer_xml_carpeta()
	{
		$ruta_carpeta = dirname(__DIR__).'/XMLS_TEMP';
		// print_r($ruta_carpeta);die();
		$gestor = opendir($ruta_carpeta);
		$archivo = readdir($gestor);
		if($archivo!='.')
		{     
	        // Recorre todos los elementos del directorio
	        while (($archivo = readdir($gestor)) !== false)  {   
	         if ($archivo != "." && $archivo != "..") {             
	            	$ruta_completa = $ruta_carpeta . "/" . $archivo;
	            	$this->documentos[] = $archivo;
	        	}
	        }        
	        // Cierra el gestor de directorios
	        closedir($gestor);
    	}

        return $this->documentos;
	}

	function leer_archivo_xmls()
	{
		$detalle = array();
		if($this->documentos!='')
		{
			foreach ($this->documentos as $key => $value) {
				$detalle[] = $this->sri->recuperar_xml_a_factura($value,$value);
				// print_r($detalle);die();
			}
		}

		return $detalle;
	}


	function subir_archivo_server($file)
	{
	  	// if($file['file']['type'] == 'text/csv')
	  	// {
	  		 $uploadfile_temporal=$file['file']['tmp_name'];
	  		 $ruta = '../TEMP/';
	   	     //$tipo = explode('/', $file['file']['type']);	       
	          $nombre = 'datos.txt';	      
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
	  	// }else
	    // {
	    //   return -2;
	    // }
	}

	function links_sri($ambiente)
	{
		$link = array();
		if($ambiente=='1')
		{
			$link[0] = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';
			$link[1] = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';			
		}else
		{
			$link[0] = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';
			$link[1] = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
			
		}
		return $link;

	}

}
?>