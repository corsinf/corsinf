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

if(isset($_GET['calcularexcel2']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_tabla2());
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
		
		private $all_iva;
		private $all_retencion;
	function __construct()
	{
		$this->sri = new autorizacion_sri();
		// $this->all_iva = array();
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


	function generar_tabla2()
	{
		set_time_limit(0);
		$this->leer_xml_carpeta();


		$tr = '';
		foreach ($this->documentos as $key => $value) {
			$doc_xml = $this->leer_archivo_xmls($value);
			$tr.='<div class="card">
                     	<div class="card-body">
                     		<div class="row">
                     			<div class="col-sm-6">
	                     			<b>RAZON SOCIAL EMISOR</b><br>
	                     			'.$doc_xml['tributatio']['razonSocial'].'
                     			</div>
                     			<div class="col-sm-3">
	                     			<b>TIPO DE COMPROBANTE</b><br>
	                     			<b><u>'.$this->tipo_comprobante($doc_xml['tributatio']['codDoc']).'</u></b>
                     			</div>
                     			<div class="col-sm-3">
	                     			<b>SERIE COMPROBANTE</b><br>
	                     			'.$doc_xml['tributatio']['estab'].'-'.$doc_xml['tributatio']['ptoEmi'].'
                     			</div>
                     			<div class="col-sm-6">
	                     			<b>RUC: </b>
	                     			'.$doc_xml['tributatio']['ruc'].'
                     			</div>
                     			<div class="col-sm-3">
	                     			<b>FECHA: </b>
	                     			'.$doc_xml['cabecera']['fechaEmision'].'
                     			</div>
                     			<div class="col-sm-3">
	                     			<b>DOCUMENTO: </b>
	                     			'.$value.'
                     			</div>                        			
                     		</div>
                     		<!--- Seccion detalle-->
                     		<div class="card">
								<div class="mt-2">
									<div class="accordion" id="accor-'.$doc_xml['tributatio']['estab'].'-'.$doc_xml['tributatio']['ptoEmi'].'-'.$doc_xml['tributatio']['secuencial'].'">
										<div class="accordion-item">
											<h2 class="accordion-header" id="heading'.$doc_xml['tributatio']['estab'].'-'.$doc_xml['tributatio']['ptoEmi'].'-'.$doc_xml['tributatio']['secuencial'].'">
									  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse'.$doc_xml['tributatio']['estab'].'-'.$doc_xml['tributatio']['ptoEmi'].'-'.$doc_xml['tributatio']['secuencial'].'" aria-expanded="false" aria-controls="collapse'.$doc_xml['tributatio']['estab'].'-'.$doc_xml['tributatio']['ptoEmi'].'-'.$doc_xml['tributatio']['secuencial'].'">
										DETALLE DE '.$this->tipo_comprobante($doc_xml['tributatio']['codDoc']).'
									  </button>
									</h2>
											<div id="collapse'.$doc_xml['tributatio']['estab'].'-'.$doc_xml['tributatio']['ptoEmi'].'-'.$doc_xml['tributatio']['secuencial'].'" class="accordion-collapse collapse" aria-labelledby="heading'.$doc_xml['tributatio']['estab'].'-'.$doc_xml['tributatio']['ptoEmi'].'-'.$doc_xml['tributatio']['secuencial'].'" data-bs-parent="#accor-'.$doc_xml['tributatio']['estab'].'-'.$doc_xml['tributatio']['ptoEmi'].'-'.$doc_xml['tributatio']['secuencial'].'" style="">
												<div class="accordion-body">
												'.$this->lineas_comprobante($doc_xml['tributatio']['codDoc'],$doc_xml['lineas']).'

												</div>
											</div>
										</div>							
									</div>
								</div>
							</div>

						</div>
					</div>';

			// print_r($doc_xml);die();
			// code...
		}


		// print_r($tr);die();
		// print_r($this->documentos);die();
		// $facturas_doc = $this->calcular_excel();


		// print_r($lineas_xml);
		// print_r($facturas_doc);die();

		// reindexado


		$this->all_iva = array_values($this->all_iva);
		if($this->all_retencion!='')
		{
			$this->all_retencion = array_values($this->all_retencion);
		}

		return array('tr'=>$tr,'tipo'=>'','datos_iva'=>$this->all_iva,'Retencion'=>$this->all_retencion);

	}


	function tipo_comprobante($tipo)
	{
		switch ($tipo) {
			case '07':
				return 'RETENCION';
				break;
			case '01':
				return 'FACTURA';
				break;
			case '04':
				return 'NOTA DE CREDITO';
				break;
			default:			
				return 'OTROS';
				break;
		}
	}

	function lineas_comprobante($tipo,$lineas)
	{
		$tbl='';
		//para facturas	
		$total_iva = 0;
		$total_iva_0 = 0;
		$total_sub = 0;
		$total_sub_0 = 0;
		$total_total = 0;		
		$total_total_0 = 0;
		//-----------------------

		$valor_retencion = 0;
		$porce_iva = 0;

		switch ($tipo) {
			case '07':

			// print_r($lineas);die();

				$tbl.='<table class="table table-sm" style="border: 1px solid;width:100%">
						<tbody>
							<tr>
								<td>Detalle</td>
								<td>Base Imponible</td>
								<td>Procentaje %</td>
								<td>Valor</td>
							</tr>';
							foreach ($lineas as $key => $value) {
								// print_r($value);
								$tbl.='<tr><td>'.$value['detalle'].'</td><td>'.$value['baseImponible'].'</td><td>'.intval($value['Porcentaje']).'</td><td>'.$value['valor'].'</td></tr>';
								$valor_retencion+= $value['valor'];
								$porce_iva = intval($value['Porcentaje']);
								if(!isset($this->all_retencion[intval($porce_iva)]))
								{
									$this->all_retencion[intval($porce_iva)] = array('retencion'=>'Retencion '.$porce_iva,'valor'=>$valor_retencion);
								}else
								{
									$this->all_retencion[intval($porce_iva)]['valor']+=$valor_retencion;
							
								}	

							}
							$tbl.='</tbody></table>';
							// die();
							//ban sumando en variables locales los totales de las lineas

										

				break;
			case '01':
			case '04':
			$iva = intval($lineas[0]['iva']); 
			$tbl.='<table class="table table-sm" style="border: 1px solid;width:100%">
						<tbody>
							<tr>
								<td>Detalle</td>
								<td>Cantidad</td>
								<td>Precio</td>
								<td>Descuento</td>
								<td>subtotal</td>
								<td>Iva</td>
								<td>total</td>
							</tr>';
							foreach ($lineas as $key => $value) {
								// print_r($value);die();
								$tbl.='<tr><td>'.$value['detalle'].'</td><td>'.$value['cantidad'].'</td><td>'.$value['pvp'].'</td><td>'.$value['descuento'].'</td><td>'.$value['subtotal'].'</td><td>'.$value['iva_v'].'</td><td>'.$value['Total'].'</td></tr>';

								switch ($value['iva']) {
									case '12':										
									case '15':										
									case '8':
										$total_iva+= $value['iva_v'];
										$total_sub+= $value['subtotal'];
										$total_total+=$value['Total'];
										$iva = $value['iva'];
										break;
									default:
										$total_iva_0+= $value['iva_v'];
										$total_sub_0+= $value['subtotal'];
										$total_total_0+=$value['Total'];
										break;
								}								

							}
							
							$tbl.='
								<tr><td colspan="7"></td></tr>
								<tr><td colspan="4"></td><td colspan="2"><b>Subtotal '.$iva.'%</b></td><td>'.$total_sub.'</td></tr>
								<tr><td colspan="4"></td><td colspan="2"><b>Subtotal 0%</b></td><td>'.$total_sub_0.'</td></tr>
								<tr><td colspan="4"></td><td colspan="2"><b>Iva '.$iva.'%</b></td><td>'.$total_iva.'</td></tr>
								<tr><td colspan="4"></td><td colspan="2"><b>Iva 0%</b></td><td>'.$total_iva_0.'</td></tr>
								<tr><td colspan="4"></td><td colspan="2"><b>Valor Total</b></td><td>'.$total_total.'</td></tr>

							</tbody></table>';
				//ban sumando en variables locales los totales de las lineas
				if(!isset($this->all_iva[intval($value['iva'])]))
				{
					if(intval($value['iva'])==0)
					{
						$this->all_iva[intval($value['iva'])] = array('porcentaje'=>$value['iva'],'iva_valor'=>$total_iva_0,'subtotal'=>$total_sub_0,'total'=>$total_total_0);
					}else
					{
						$this->all_iva[intval($value['iva'])] = array('porcentaje'=>$value['iva'],'iva_valor'=>$total_iva,'subtotal'=>$total_sub,'total'=>$total_total);
					}
				}else
				{
					if(intval($value['iva'])==0)
					{
						$this->all_iva[intval($value['iva'])]['subtotal']+=$total_sub_0;
						$this->all_iva[intval($value['iva'])]['iva_valor']+=$total_iva_0;
						$this->all_iva[intval($value['iva'])]['total']+=$total_total_0;
					}else
					{
						$this->all_iva[intval($value['iva'])]['subtotal']+=$total_sub;
						$this->all_iva[intval($value['iva'])]['iva_valor']+=$total_iva;
						$this->all_iva[intval($value['iva'])]['total']+=$total_total;
					}
				}

				break;
			default:			
				return 'OTROS';
				break;
		}


		


		return $tbl;
	}




	function generar_tabla()
	{
		set_time_limit(0);
		$this->leer_xml_carpeta();
		// print_r($this->documentos);die();
		$lineas_xml = $this->leer_archivo_xmls();
		$facturas_doc = $this->calcular_excel();


		$tipo_doc = array();
		$tipo = 1;
		foreach ($facturas_doc as $key => $value) {
			if($value[0]=='RUC_EMISOR' || $tipo==2)
			{
				$tipo_doc[] = $value[2]; 
				$tipo = 2;
			}else
			{
				$tipo_doc[] = $value[0]; 
			}
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
			// print_r($value[0]);
			if($value[0]=='RUC_EMISOR' || $tipo==2)
			{
				$tipo = 2;
				$valueT[0] = $value[2];
				$valueT[1] = $value[3];
				$valueT[2] = $value[0];
				$valueT[3] = $value[1];
				$valueT[4] = $value[6];
				$valueT[5] = $value[5];

				$valueT[6] = 'NO hay';

				$valueT[7] = $value[11];
				$valueT[8] = $value[7];
				$valueT[9] = $value[4];
				$valueT[10] = $value[4];
				$valueT[11] = $value[10];
			}
			if($tipo==2)
			{
				$value[0] = $valueT[0];
				$value[1] = $valueT[1];
				$value[2] = $valueT[2];
				$value[3] = $valueT[3];
				$value[4] = $valueT[4];
				$value[5] = $valueT[5];
				$value[6] = $valueT[6];
				$value[7] = $valueT[7];
				$value[8] = $valueT[8];
				$value[9] = $valueT[9];
				$value[10] = $valueT[10];
				$value[11] = $valueT[11];
			}

			// print_r($value[9]);die();
			if(is_numeric($value[9]))
			{

			// print_r($value[9]);die();
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

	function leer_archivo_xmls($nombre_xml)
	{
		$detalle = array();
		$detalle = $this->sri->recuperar_xml_a_factura($nombre_xml);
		
		return $detalle;
	}


	function subir_archivo_server($file)
	{
		// $ruta = dirname(__DIR__,1).'/TEMP/';
		// if (!file_exists($ruta)) {
		// 	    mkdir($ruta, 0777, true);
		// }

  		//  $uploadfile_temporal=$file['file']['tmp_name'];
   	    //  //$tipo = explode('/', $file['file']['type']);	       
        //  $nombre = 'datos.txt';	      
   	    //  $nuevo_nom=$ruta.$nombre;
   	    //  // print_r($nuevo_nom);die();
   	    //  if (is_uploaded_file($uploadfile_temporal))
   	    //  {
   		//      move_uploaded_file($uploadfile_temporal,$nuevo_nom); 
   		     return 1;  		     
   	     // }
   	     // else
   	     // {
   		 //    return -1;
   	     // } 
	  
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