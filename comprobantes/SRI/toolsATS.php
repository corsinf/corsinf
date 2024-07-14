<?php 
date_default_timezone_set('America/Guayaquil');

/**
 * 
 */
class autorizacion_sri
{	
	private $tipo_retencion;
	function __construct()
	{
		$this->tipo_retencion();
	}

	function tipo_retencion()
	{
		$ruta_carpeta = dirname(__DIR__).'/SRI/Tipo_Concepto_Retencion.txt';
		// print_r($ruta_carpeta);die();
		$fp = fopen($ruta_carpeta, "r");
		$tr = array();
		$num = 0;
		while(!feof($fp)) 
		{
			$linea = fgets($fp);
			$linea = str_replace('"','',$linea);
			$ln = explode('	', $linea);
			$this->tipo_retencion[] = $ln;
		}                                         

	}



    //comprueba si el xml ya se envio al sri
    // 1 para autorizados
    //-1 para no autorizados
    // 2 para devueltas
    function comprobar_xml_sri($clave_acceso,$link_autorizacion)
    {
    	$comprobar_sri = dirname(__DIR__).'/SRI/firmar/sri_comprobar.jar';
    	$url_autorizado=dirname(__DIR__).'/XMLS/AUTORIZADOS/';
 	    $url_No_autorizados =dirname(__DIR__).'/XMLS/NO_AUTORIZADOS/';

    	// print_r("java -jar ".$comprobar_sri." ".$clave_acceso." ".$url_autorizado." ".$url_No_autorizados." ".$link_autorizacion);die();
   		 exec("java -jar ".$comprobar_sri." ".$clave_acceso." ".$url_autorizado." ".$url_No_autorizados." ".$link_autorizacion,$f);   	
   		 // print_r($f);
   		  // die();
   		 if(empty($f))
   		 {
   		 	return;
   		 }


   		 $resp = explode('-',$f[0]);

   		 // print_r($resp);
   		 if(count($resp)>1)
   		 {
   		 	$resp[1] = trim($resp[1]);
   		 	// print_r($resp[1]);
   		 	//cuando null NO PROCESADO es liquidacion de compras
	   		 if(isset($resp[1]) && $resp[1]=='FACTURA NO PROCESADO' || isset($resp[1]) && $resp[1]=='LIQUIDACION DE COMPRAS NO PROCESADO' || $resp[1] == 'COMPROBANTE DE RETENCION NO PROCESADO' || $resp[1]=='GUIA DE REMISION NO PROCESADO' || isset($resp[1]) && $resp[1]=='NOTA DE CREDITO NO PROCESADO')
	   		 {
	   		 	// print_r($resp[1].'<br>');

	   		 	return $resp[0].' El archivo no tiene autorizaciones relacionadas';
	   		 }else if(isset($resp[1]) && $resp[1]=='FACTURA AUTORIZADO' || isset($resp[1]) && $resp[1]=='LIQUIDACION DE COMPRAS AUTORIZADO' || $resp[1] == 'COMPROBANTE DE RETENCION AUTORIZADO' || isset($resp[1]) && $resp[1]=='GUIA DE REMISION AUTORIZADO' || isset($resp[1]) && $resp[1]=='NOTA DE CREDITO AUTORIZADO')
	   		 {
	   		 	// print_r('as');
	   		 	return $resp[1];
	   		 }else
	   		 {
	   			return 'ERROR COMPROBACION -'.$f[0];
	   		 }
	   	}else
	   	{
	   		return 2;
	   	}
    }

    //envia el xml asia el sri
    function enviar_xml_sri($clave_acceso,$url_recepcion)
    {
    	$ruta_firmados=dirname(__DIR__).'/XMLS/FIRMADOS/';
    	$ruta_enviados=dirname(__DIR__).'/XMLS/ENVIADOS/';
 	    $ruta_rechazados =dirname(__DIR__).'/XMLS/RECHAZADOS/';
    	$enviar_sri = dirname(__DIR__).'/SRI/firmar/sri_enviar.jar';

    	if(!file_exists($ruta_firmados.$clave_acceso.'.xml'))
    	{
    		$respuesta = ' XML firmado no encontrado';
	 		return $respuesta;
    	}
    	 //print_r("java -jar ".$enviar_sri." ".$clave_acceso." ".$ruta_firmados." ".$ruta_enviados." ".$ruta_rechazados." ".$url_recepcion);die();
   		 exec("java -jar ".$enviar_sri." ".$clave_acceso." ".$ruta_firmados." ".$ruta_enviados." ".$ruta_rechazados." ".$url_recepcion,$f);
   		 // print_r($f);die();
   		 if(count($f)>0)
   		 {
	   		 $resp = explode('-',$f[0]);
	   		 if($resp[1]=='RECIBIDA')
	   		 {
	   		 	return 1;
	   		 }else if($resp[1]=='DEVUELTA')
	   		 {
	   		 	return 2;
	   		 }else if($resp[1]==null || $resp[1]=='' )
	   		 {
	   		 	//es devuelta
	   		 	return 2;
	   		 }else
	   		 {  
	   		 	return $f;
	   		 }
   		}else
   		{
   			// algo paso
   			return 2;
   		}
    }
    function quitar_carac($query)
    {
    	$query = preg_replace("[\n|\r|\n\r]", "", $query);
    	$buscar = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú','Ñ','ñ','/','?','�','-','.');
    	$remplaza = array('a','e','i','o','u','A','E','I','O','U','N','n','','','','','');
    	$corregido = str_replace($buscar, $remplaza, $query);
    	 // print_r($corregido);
    	return trim($corregido);

    }



function recuperar_xml_a_factura($documento)
{
	$respuesta = 1;
	//busco el archivo xml
	$ruta_G = dirname(__DIR__,2).'/XMLS';
	// print_r($ruta_G);die();
	
	$texto = file_get_contents($ruta_G.'/'.$documento);
	
	$texto = str_replace('EN PROCESO','nulo', $texto,$remplazado);
	if($remplazado>0)
	{
		return -2;
	}
	print_r($documentos=='Comprobante de Retención (2).xml')
	{
		print_r($texto);die();
	}
	$xml = simplexml_load_string($texto,"SimpleXMLElement",LIBXML_NOCDATA);
	$objJsonDocument = json_encode($xml);
	$documentos = json_decode($objJsonDocument, TRUE);


	$xml = simplexml_load_string($documentos['comprobante'],"SimpleXMLElement",LIBXML_NOCDATA);
	$objJsonDocument = json_encode($xml);
	$documentos = json_decode($objJsonDocument, TRUE);


	// print_r($documentos);die();
	//-----------------------------------cuando es retencion-----------------
	if(isset($documentos['infoCompRetencion'])){
		$lineas = array();
		$encontrado = 0;
		$tributaria = $documentos['infoTributaria'];
		$cabecera = $documentos['infoCompRetencion'];
		if(isset($documentos['impuestos']['impuesto']))
		{
			$detalle = $documentos['impuestos']['impuesto'];
		}
		if(isset($documentos['docsSustento']['docSustento']['retenciones']['retencion']))
		{
			$detalle = $documentos['docsSustento']['docSustento']['retenciones']['retencion'];
		}
// print_r($documentos['infoCompRetencion']);
// print_r($documentos);die();
		foreach ($detalle as $key => $value) {
			// print_r($value);
			// die();
			foreach ($this->tipo_retencion as $key2 => $value2) {
				// print_r($value['codigoRetencion']);
				if(isset($value2[2]))
				{
					if(isset($value['codigoRetencion']) && isset($value['porcentajeRetener']) && $value['codigoRetencion']==$value2[2] && intval($value['porcentajeRetener'])==$value2[1])
					{
						$lineas[] = array('Tipo'=>'R','Autorizacion'=>$tributaria['claveAcceso'],'detalle'=>$value2[0],'baseImponible'=>$value['baseImponible'],'Porcentaje'=>$value['porcentajeRetener'],'valor'=>$value['valorRetenido']);
						$encontrado = 1;
						break;
					}
				}
			}
			// print_r($encontrado);
			if($encontrado==0)
			{
				// print_r('expression');die();
				if(isset($value['codigoRetencion']) && $value['codigoRetencion']==1)
				{
					$lineas[] = array('Tipo'=>'R','Autorizacion'=>$tributaria['claveAcceso'],'detalle'=>'IVA bienes','baseImponible'=>$value['baseImponible'],'Porcentaje'=>$value['porcentajeRetener'],'valor'=>$value['valorRetenido']);
				}else if(isset($value['codigoRetencion']) && $value['codigoRetencion']==2)
				{
					$lineas[] = array('Tipo'=>'R','Autorizacion'=>$tributaria['claveAcceso'],'detalle'=>'IVA Servicios','baseImponible'=>$value['baseImponible'],'Porcentaje'=>$value['porcentajeRetener'],'valor'=>$value['valorRetenido']);
				}
			}
			$encontrado = 0;
		}

		return $lineas;		
	}

	//-----------------------------------cuando es factura---------------------------
	if(isset($documentos['infoFactura']))
	{
		$tributaria = $documentos['infoTributaria'];
		$cabecera = $documentos['infoFactura'];
		$detalle = $documentos['detalles']['detalle'];
		if(isset($detalle['codigoPrincipal']))
		{
			$detalle = $documentos['detalles'];
		}
		
		$lineas = array();
		foreach ($detalle as $key => $value) {
			// print_r($value);die();
			if(isset($value['impuestos']['impuesto']))
			{

				$iva = $value['impuestos']['impuesto']['tarifa'];
				$valoriva = $value['impuestos']['impuesto']['valor']; 
				$lineas[] = array('Tipo'=>'F','Autorizacion'=>$tributaria['claveAcceso'],'detalle'=>$value['descripcion'],'cantidad'=>$value['cantidad'],'pvp'=>$value['precioUnitario'],'descuento'=>$value['descuento'],'subtotal'=>$value['precioTotalSinImpuesto'],'iva'=>$iva,'iva_v'=>$valoriva,'Total'=>$value['precioTotalSinImpuesto']+$valoriva);
			}
		}
		return $lineas;
	}

	//----------------------------cuando es nota de credito--------------------
	if(isset($documentos['infoNotaCredito']))
	{
		$tributaria = $documentos['infoTributaria'];
		$cabecera = $documentos['infoNotaCredito'];
		$detalle = $documentos['detalles']['detalle'];
		if(isset($detalle['codigoInterno']))
		{
			$detalle = $documentos['detalles'];
		}
		// print_r($detalle);die();
		$lineas = array();
		foreach ($detalle as $key => $value) {
			// print_r($value);die();
			if(isset($value['impuestos']['impuesto']))
			{

				$iva = $value['impuestos']['impuesto']['tarifa'];
				$valoriva = $value['impuestos']['impuesto']['valor']; 
				$lineas[] = array('Tipo'=>'NC','Autorizacion'=>$tributaria['claveAcceso'],'detalle'=>$value['descripcion'],'cantidad'=>$value['cantidad'],'pvp'=>$value['precioUnitario'],'descuento'=>$value['descuento'],'subtotal'=>$value['precioTotalSinImpuesto'],'iva'=>$iva,'iva_v'=>$valoriva,'Total'=>$value['precioTotalSinImpuesto']+$valoriva);
			}
		}
		// print_r($lineas);die();
		return $lineas;

	}
	
}

} 
?>