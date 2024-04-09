<?php
// require_once 'PHPExcel-1.8/Classes/PHPExcel.php';
require_once('../comprobantes/SRI/autorizar_sri.php');

date_default_timezone_set('America/Guayaquil'); 
require_once '../lib/spout_excel/vendor/box/spout/src/Spout/Autoloader/autoload.php';
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterInterface;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;


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
if(isset($_GET['subir_archivo_xml_server']))
{
	echo json_encode($controlador->subir_archivo_xml_server($_FILES));
}
if(isset($_GET['eliminar_xml']))
{
	echo json_encode($controlador->eliminar_xml());
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
		$sub_sin_iva = 0;
		$sub_con_iva = 0;
		$valor_iva = 0;
		$total_todo = 0;
		$t_sub_sin_iva = 0; 
		$t_sub_con_iva = 0; 
		$t_con_iva_total = 0;
		$t_valor_iva = 0;
		$t_total_todo = 0;
		//-------------------todso los comprobantes listado------------------
		foreach ($facturas_doc as $key => $value) {
			if(is_numeric($value[9]))
			{
				if($value[0]==$parametros['tipo'])
				{
					$tot = '';
					if(isset($value[11])){ $tot = $value[11]; }
					$tr.='<div class="card">
                     	<div class="card-body">
                     		<div class="row">
                     			<div class="col-sm-6">
	                     			<b>RAZON SOCIAL EMISOR</b></br>
	                     			'.$value[3].'
                     			</div>
                     			<div class="col-sm-3">
	                     			<b>TIPO DE COMPROBANTE</b>
	                     			'.$value[0].'
                     			</div>
                     			<div class="col-sm-3">
	                     			<b>SERIE COMPROBANTE</b><br>
	                     			'.$value[1].'
                     			</div>
                     			<div class="col-sm-6">
	                     			<b>RUC: </b>
	                     			'.$value[2].'
                     			</div>
                     			<div class="col-sm-3">
	                     			<b>FECHA: </b>
	                     			'.$value[4].'
                     			</div>
                     			<div class="col-sm-3">
	                     			<b>TOTAL IMPORTE: </b>
	                     			'.$tot.'
                     			</div>
                     		</div>';
				// print_r($value);die();
                 $tabla = '';

					// print_r($tr);die();
					//----------------------------todas ala lineas de los xml leidos-----------------------
					foreach ($lineas_xml as $key2 => $value2) {

						//--------------------------conpara el numero de autorizacion de xmls leidos y listado de comprobantes--------
						if(isset($value2[0]['Autorizacion']) && $value2[0]['Autorizacion']==$value[9])
						{
							$ingresa = 1;
							$tabla.='<tr><td colspan="9"><table class="table table-striped" style="border: 1px solid;width:100%">';
							foreach ($value2 as $key3 => $value3) {

								if($value3['Tipo']=='F')
								{
									$titulo = 'Factura';
									// print_r($value3);die();
									if($key3==0)
									{
									 $tabla.='<tr>
												<td>Detalle</td>
												<td>Cantidad</td>
												<td>Precio</td>
												<td>Descuento</td>
												<td>subtotal</td>
												<td>Iva</td>
												<td>total</td>
											</tr>';
												
									}
									// print_r($value3);die();
									 $tabla.='<tr><td>'.$value3['detalle'].'</td><td>'.number_format($value3['cantidad'],2,'.','').'</td><td>'.number_format($value3['pvp'],2,'.','').'</td><td>'.number_format($value3['descuento'],2,'.','').'</td><td>'.number_format($value3['subtotal'],2,'.','').'</td><td>'.number_format($value3['iva_v'],2,'.','').'</td><td>'.number_format($value3['Total'],2,'.','').'</td></tr>';

									 if(floatval($value3['iva'])==0)
									 {
									 	$sub_sin_iva = number_format($sub_sin_iva+$value3['subtotal'],2,'.','');
									 	$t_sub_sin_iva = number_format($t_sub_sin_iva+$value3['subtotal'],2,'.','');
									 }else
									 {
									 	$sub_con_iva = number_format($sub_con_iva+$value3['subtotal'],2,'.','');
									 	$t_sub_con_iva = number_format($t_sub_con_iva+$value3['subtotal'],2,'.','');
									 	$t_con_iva_total =  number_format($t_con_iva_total+$value3['Total'],2,'.','');
									 }

									$total_todo = number_format($total_todo+$value3['Total'],2,'.','');
									$t_total_todo = number_format($t_total_todo+$value3['Total'],2,'.','');
									$valor_iva = number_format($valor_iva+$value3['iva_v'],2,'.','');
									$t_valor_iva = number_format($t_valor_iva+$value3['iva_v'],2,'.','');
									// 	print_r('Fac');
									// 	print_r($value2);die();
									// print_r($tr);die();
								}

								if($value3['Tipo']=='R')
								{
									// print_r($value3);die();
									$titulo = 'Retencion';
									if($key3==0)
									{
									$tabla.='
										<tr><td>Detalle</td><td>base imponible</td><td>porcentaje</td><td>Valor</td></tr>';
									}

									$tabla.='<tr><td>'.$value3['detalle'].'</td><td>'.$value3['baseImponible'].'</td><td>'.$value3['Porcentaje'].'</td><td>'.$value3['valor'].'</td></tr>';

									// print_r($tr);die();
								}
								if($value3['Tipo']=='NC')
								{
									if($key3==0)
									{
									 $tabla.='<tr>
												<td>Detalle</td>
												<td>Cantidad</td>
												<td>Precio</td>
												<td>Descuento</td>
												<td>subtotal</td>
												<td>Iva</td>
												<td>total</td>
											</tr>';
												
									}

									 $tabla.='<tr><td>'.$value3['detalle'].'</td><td>'.$value3['cantidad'].'</td><td>'.$value3['pvp'].'</td><td>'.$value3['descuento'].'</td><td>'.$value3['subtotal'].'</td><td>'.$value3['iva_v'].'</td><td>'.$value3['Total'].'</td></tr>';
									// print_r('Fac');
									// print_r($value2);die();
								}						
							}

						}
						if($ingresa==1)
						{
							if($value3['Tipo']=='F')
							{
								$tabla.='</table>
										<table class="table table-sm" style="width:100%">
										<tr><td width="70%"></td><td width="20%"><b>Subtotal 12%</b></td><td width="10%">'.$sub_con_iva.'</td></tr>
										<tr><td width="70%"></td><td width="20%"><b>Subtotal 0%</b></td><td width="10%">'.$sub_sin_iva.'</td></tr>
										<tr><td width="70%"></td><td width="20%"><b>Iva 12%</b></td><td width="10%">'.$valor_iva.'</td></tr>
										<tr><td width="70%"></td><td width="20%"><b>Iva 0%</b></td><td width="10%">0.00</td></tr>
										<tr><td width="70%"></td><td width="20%"><b>Valor Total</b></td><td width="10%">'.$total_todo.'</td></tr>
										</table>';
							}else
							{
								$tabla.='</table></div></div>';
							}
							$ingresa=0;
							$sub_con_iva=0;
							$sub_sin_iva=0;
							$valor_iva=0;
							$total_todo=0;		
						}
						// print_r($value2);die();
					}
					$tr.='</table>
							<div class="card">
								<div class="mt-2">
									<div class="accordion" id="accor-'.$value[1].'">
										<div class="accordion-item">
											<h2 class="accordion-header" id="heading'.$value[1].'">
									  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse'.$value['1'].'" aria-expanded="false" aria-controls="collapse'.$value['1'].'">
										Detalle de '.$titulo.'
									  </button>
									</h2>
											<div id="collapse'.$value['1'].'" class="accordion-collapse collapse" aria-labelledby="heading'.$value[1].'" data-bs-parent="#accor-'.$value[1].'" style="">
												<div class="accordion-body">
												'.$tabla.'

												</div>
											</div>
										</div>							
									</div>
								</div>
							</div>

									</div>
										</div>';
				}
				// print_r($value);die();
			}
		}

		return array('tr'=>$tr);

	}


	function generar_tabla()
	{
		set_time_limit(0);
		$this->leer_xml_carpeta();
		// print_r($this->documentos);die();
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
		$sub_sin_iva = 0;
		$sub_con_iva = 0;
		$valor_iva = 0;
		$total_todo = 0;
		$t_sub_sin_iva = 0; 
		$t_sub_con_iva = 0; 
		$t_con_iva_total = 0;
		$t_valor_iva = 0;
		$t_total_todo = 0;

		// ---------------listado del porcentajes de retencion --------------
		$porcentaje_ret = array('Ret_iva'=>0,'Ret_renta'=>0,'Ret_otras'=>0);
		foreach ($lineas_xml as $key => $value) {
			foreach ($value as $key2 => $value2) {
				if($value2['Tipo']=='R')
				{
					$porc = intval($value2['Porcentaje']);

					switch ($porc) {
						case '10':
						case '20':
						case '30':
						case '50':
						case '70':
						case '100':
						$porcentaje_ret['Ret_iva'] = $porcentaje_ret['Ret_iva']+$value2['valor'];
							break;
						case '1':						
						$porcentaje_ret['Ret_renta'] = $porcentaje_ret['Ret_renta']+$value2['valor'];	
						break;					
						default:
						$porcentaje_ret['Ret_otras'] = $porcentaje_ret['Ret_otras']+$value2['valor'];
							break;
					}
					// if(isset($porcentaje_ret[$porc]))
					// {
					// 	$porcentaje_ret[$porc] = $porcentaje_ret[$porc]+$value2['valor'];
					// }else{						
					// 	$porcentaje_ret[$porc] = $value2['valor'];
					// }
				}
			}
		}
		// print_r($porcentaje_ret);die();

		//-------------------todso los comprobantes listado------------------
		foreach ($facturas_doc as $key => $value) {
			// print_r($facturas_doc);die();
			if(is_numeric($value[9]))
			{
				$tot = '';
				if(isset($value[11])){ $tot = $value[11]; }
				$tr.='<div class="card">
                     	<div class="card-body">
                     		<div class="row">
                     			<div class="col-sm-6">
	                     			<b>RAZON SOCIAL EMISOR</b></br>
	                     			'.$value[3].'
                     			</div>
                     			<div class="col-sm-3">
	                     			<b>TIPO DE COMPROBANTE</b></br>
	                     			<b><u>'.$value[0].'</u></b>
                     			</div>
                     			<div class="col-sm-3">
	                     			<b>SERIE COMPROBANTE</b><br>
	                     			'.$value[1].'
                     			</div>
                     			<div class="col-sm-6">
	                     			<b>RUC: </b>
	                     			'.$value[2].'
                     			</div>
                     			<div class="col-sm-3">
	                     			<b>FECHA: </b>
	                     			'.$value[4].'
                     			</div>
                     			<div class="col-sm-3">
	                     			<b>TOTAL IMPORTE: </b>
	                     			'.$tot.'
                     			</div>
                     		</div>';
				// print_r($value);
                 $tabla = '';
                 $titulo = $value[0];
				//----------------------------todas ala lineas de los xml leidos-----------------------
				foreach ($lineas_xml as $key2 => $value2) {
					// print_r($lineas_xml);die();
					//------------------compara el numero de autorizacion de xmls leidos y listado de comprobantes--------
					if(isset($value2[0]['Autorizacion']) && $value2[0]['Autorizacion']==$value[9])
					{
						$ingresa = 1;
						$tabla.='<tr><td colspan="9"><table class="table table-striped" style="border: 1px solid;width:100%">';
						foreach ($value2 as $key3 => $value3) {
							// print_r($value3);die();

							if($value3['Tipo']=='F')
							{

								$titulo = 'Factura';
								// print_r($value3);die();
								if($key3==0)
								{
								 $tabla.='<tr>
											<td>Detalle</td>
											<td>Cantidad</td>
											<td>Precio</td>
											<td>Descuento</td>
											<td>subtotal</td>
											<td>Iva</td>
											<td>total</td>
										</tr>';
											
								}
								// print_r($value3);die();
								 $tabla.='<tr><td>'.$value3['detalle'].'</td><td>'.number_format($value3['cantidad'],2,'.','').'</td><td>'.number_format($value3['pvp'],2,'.','').'</td><td>'.number_format($value3['descuento'],2,'.','').'</td><td>'.number_format($value3['subtotal'],2,'.','').'</td><td>'.number_format($value3['iva_v'],2,'.','').'</td><td>'.number_format($value3['Total'],2,'.','').'</td></tr>';

								 if(floatval($value3['iva'])==0)
								 {
								 	$sub_sin_iva = number_format($sub_sin_iva+$value3['subtotal'],2,'.','');
								 	$t_sub_sin_iva = number_format($t_sub_sin_iva+$value3['subtotal'],2,'.','');
								 }else
								 {
								 	$sub_con_iva = number_format($sub_con_iva+$value3['subtotal'],2,'.','');
								 	$t_sub_con_iva = number_format($t_sub_con_iva+$value3['subtotal'],2,'.','');
								 	$t_con_iva_total =  number_format($t_con_iva_total+$value3['Total'],2,'.','');
								 }

								$total_todo = number_format($total_todo+$value3['Total'],2,'.','');
								$t_total_todo = number_format($t_total_todo+$value3['Total'],2,'.','');
								$valor_iva = number_format($valor_iva+$value3['iva_v'],2,'.','');
								$t_valor_iva = number_format($t_valor_iva+$value3['iva_v'],2,'.','');
								// 	print_r('Fac');
								// 	print_r($value2);die();
								// print_r($tr);die();
							}

							if($value3['Tipo']=='R')
							{
								// print_r($value3);die();
								$titulo = 'Retencion';
								if($key3==0)
								{
								$tabla.='
									<tr><td>Detalle</td><td>base imponible</td><td>porcentaje</td><td>Valor</td></tr>';
								}

								$tabla.='<tr><td>'.$value3['detalle'].'</td><td>'.$value3['baseImponible'].'</td><td>'.$value3['Porcentaje'].'</td><td>'.$value3['valor'].'</td></tr>';

								// print_r($tr);die();
							}
							if($value3['Tipo']=='NC')
							{
								if($key3==0)
								{
								 $tabla.='<tr>
											<td>Detalle</td>
											<td>Cantidad</td>
											<td>Precio</td>
											<td>Descuento</td>
											<td>subtotal</td>
											<td>Iva</td>
											<td>total</td>
										</tr>';
											
								}

								 $tabla.='<tr><td>'.$value3['detalle'].'</td><td>'.$value3['cantidad'].'</td><td>'.$value3['pvp'].'</td><td>'.$value3['descuento'].'</td><td>'.$value3['subtotal'].'</td><td>'.$value3['iva_v'].'</td><td>'.$value3['Total'].'</td></tr>';
								// print_r('Fac');
								// print_r($value2);die();
							}						
						}
						// print_r($value2);die();

					}else
					{
						// print_r($tr);die();
					}
					if($ingresa==1)
					{
						if($value3['Tipo']=='F')
						{
							$tabla.='</table>
									<table class="table table-sm" style="width:100%">
									<tr><td width="70%"></td><td width="20%"><b>Subtotal 12%</b></td><td width="10%">'.$sub_con_iva.'</td></tr>
									<tr><td width="70%"></td><td width="20%"><b>Subtotal 0%</b></td><td width="10%">'.$sub_sin_iva.'</td></tr>
									<tr><td width="70%"></td><td width="20%"><b>Iva 12%</b></td><td width="10%">'.$valor_iva.'</td></tr>
									<tr><td width="70%"></td><td width="20%"><b>Iva 0%</b></td><td width="10%">0.00</td></tr>
									<tr><td width="70%"></td><td width="20%"><b>Valor Total</b></td><td width="10%">'.$total_todo.'</td></tr>
									</table>';
						}else
						{
							$tabla.='</table></div></div>';
						}
						$ingresa=0;
						$sub_con_iva=0;
						$sub_sin_iva=0;
						$valor_iva=0;
						$total_todo=0;		
					}
					// print_r($value2);die();
				}
				// print_r($value);die();
				if($tabla==''){$tabla='<table class="table table-sm"><tr><td colspan="7">No existe xml de detalles</td></tr></table>';}
				  
				$tr.='</table>
				<div class="card">
					<div class="mt-2">
						<div class="accordion" id="accor-'.$value[1].'">
							<div class="accordion-item">
								<h2 class="accordion-header" id="heading'.$value[1].'">
						  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse'.$value['1'].'" aria-expanded="false" aria-controls="collapse'.$value['1'].'">
							Detalle de '.$titulo.'
						  </button>
						</h2>
								<div id="collapse'.$value['1'].'" class="accordion-collapse collapse" aria-labelledby="heading'.$value[1].'" data-bs-parent="#accor-'.$value[1].'" style="">
									<div class="accordion-body">
									'.$tabla.'

									</div>
								</div>
							</div>							
						</div>
					</div>
				</div>

						</div>
							</div>';
			}
			
		}

		return array('tr'=>$tr,'tipo'=>$tipo_doc,'sub_sin_iva'=>$t_sub_sin_iva,'sub_con_iva'=>$t_sub_con_iva,'total_con_iva'=>$t_con_iva_total,'total'=>$t_total_todo,'iva_total'=>$t_valor_iva,'Retencion_val'=>$porcentaje_ret);

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
						// $ln = array_merge(array_slice($ln,0,7), array('-'), array_slice($ln,7));
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
		$ruta_carpeta = dirname(__DIR__,1).'/TEMP/XMLS/';
		if(!file_exists($ruta_carpeta))
		{
			 mkdir($ruta_carpeta, 0777, true);
		}
		// print_r($ruta_carpeta);die();
		$gestor = opendir($ruta_carpeta);
     
        // Recorre todos los elementos del directorio
        while (($archivo = readdir($gestor)) !== false)  {   
         if ($archivo != "." && $archivo != "..") {             
            	$ruta_completa = $ruta_carpeta . "/" . $archivo;
            	// print_r(substr($archivo, -4));die();
            	if(substr($archivo,-4)=='.xml')
            	{
            		$this->documentos[] = $archivo;
            	}
        	}
        }        
        // Cierra el gestor de directorios
        closedir($gestor);
        return $this->documentos;
	}

	function leer_archivo_xmls()
	{
		$detalle = array();
		// print_r($this->documentos);die();
		foreach ($this->documentos as $key => $value) {
			$detalle[] = $this->sri->recuperar_xml_a_factura($value,$value);
			// print_r($detalle);die();
		}

		return $detalle;
	}


	function subir_archivo_server($file)
	{
		$ruta = dirname(__DIR__,1).'/TEMP/';
		if (!file_exists($ruta)) {
			    mkdir($ruta, 0777, true);
		}

  		 $uploadfile_temporal=$file['file']['tmp_name'];
   	     //$tipo = explode('/', $file['file']['type']);	       
         $nombre = 'datos.txt';	      
   	     $nuevo_nom=$ruta.$nombre;
   	     // print_r($nuevo_nom);die();
   	     if (is_uploaded_file($uploadfile_temporal))
   	     {
   		     move_uploaded_file($uploadfile_temporal,$nuevo_nom); 
   		     return 1;  		     
   	     }
   	     else
   	     {
   		    return -1;
   	     } 
	  
	}

	function eliminar_xml()
	{
		$ruta = dirname(__DIR__,1).'/TEMP/XMLS/';
		$this->vaciarDirectorio($ruta);
		array_map('unlink', glob("XMLS/*"));
    	array_filter(glob("XMLS/*"), 'is_dir', GLOB_ONLYDIR) ?: array_map('rmdir', glob("XMLS/*"));
    	return 1;
	}

	function subir_archivo_xml_server($file)
	{
		$ruta = dirname(__DIR__,1).'/TEMP/';
		if(!file_exists($ruta))
		{
			 mkdir($ruta, 0777, true);
		}

		$ruta = dirname(__DIR__,1).'/TEMP/XMLS/';
		if(!file_exists($ruta))
		{
			 mkdir($ruta, 0777, true);
		}
    	// print_r($file);die();
		foreach ($file['files']['name'] as $key => $value) {

			$uploadfile_temporal=$file['files']['tmp_name'][$key];
	   	    //$tipo = explode('/', $file['file']['type']);	       
	        $nombre = str_replace(' ','_',$value);	      
	   	    $nuevo_nom=$ruta.$nombre;
	   	    // print_r($nuevo_nom);die();
	   	    if (is_uploaded_file($uploadfile_temporal))
	   	    {
	   		    move_uploaded_file($uploadfile_temporal,$nuevo_nom); 
	   		}
		}

		return 1;	  
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

	function vaciarDirectorio($directorio) 
	{
	    // Verificar que el directorio exista
	    if (is_dir($directorio)) {
	        // Obtener la lista de archivos y subdirectorios en el directorio
	        $archivos = glob($directorio . '/*');
	        
	        // Eliminar cada archivo o subdirectorio
	        foreach($archivos as $archivo) {
	            // Si es un directorio, llamar recursivamente a la función
	            if (is_dir($archivo)) {
	                vaciarDirectorio($archivo);
	            } else {
	                // Si es un archivo, eliminarlo
	                unlink($archivo);
	            }
	        }
	    }
	}

}
?>