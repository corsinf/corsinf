<?php 
require_once(dirname(__DIR__,2)."/db/db.php");
require_once("linkSRI.php");
// require_once(dirname(__DIR__,2)."/funciones/funciones.php");

date_default_timezone_set('America/Guayaquil');

@session_start(); 

$controlador = new autorizacion_sri();
if(isset($_GET['autorizar']))
{
	$parametros = $_POST['parametros'];
     echo json_encode($controlador->Autorizar($parametros));
}

/**
 * 
 */
class autorizacion_sri
{
	private $clave;
	//Metodo de encriptación
	private $method;
	private $iv;
	private $conn;
	private $SRI;
	private $linkSriAutorizacion;
	private $linkRecepcion;
	private $JDK8;
	// Puedes generar una diferente usando la funcion $getIV()
	
	function __construct()
	{
		// $this->conn = new db();
		$this->conn = new db();
		$this->SRI = new linkSRI();
		$this->JDK8 = escapeshellarg("C:\\Program Files\\Java\\jdk-1.8\\bin\\");
		// $this->JDK8 = "";
	}


	function Autorizar_factura_o_liquidacion($parametros)
	{
		/*
			retorna entre 1 2 3
			1 Este documento electronico autorizado
			2 XML devuelto
			3 Este documento electronico ya esta autorizado
		*/
		
		if(!isset($parametros['empresa']))
		{
			$parametros['empresa'] = $_SESSION['INICIO']['ID_EMPRESA'];
		}
		$sql = "SELECT * FROM EMPRESAS WHERE id_empresa = '".$parametros['empresa']."'";
		if(isset($parametros['tc']) && $parametros['tc']=='LC')
		{
			$sql2 ="SELECT * FROM liquidacion_compras WHERE id_factura = '".$parametros['fac']."'" ;
			$cabecera['cod_doc']='03';
		    $cabecera['carpeta'] = 'LIQUIDACIONES';
		}else{
			$sql2 ="SELECT * FROM facturas WHERE id_factura = '".$parametros['fac']."'" ;
		    $cabecera['cod_doc']='01';
		    $cabecera['carpeta'] = 'FACTURAS';
		}
		$empresa = $this->conn->datos($sql);
		$factura = $this->conn->datos($sql2);

		$cabecera['ambiente']=$empresa[0]['Ambiente'];
	    $cabecera['ruta_ce']=$empresa[0]['Ruta_Certificado'];
	    $cabecera['clave_ce']=$empresa[0]['Clave_certificado'];
	    $cabecera['nom_comercial_principal']=$this->quitar_carac($empresa[0]['Nombre_Comercial']);
	    $cabecera['razon_social_principal']=$this->quitar_carac($empresa[0]['Razon_Social']);
	    $cabecera['ruc_principal']=$empresa[0]['Ruc'];
	    $cabecera['direccion_principal']= $this->quitar_carac($empresa[0]['Direccion']);
	    $cabecera['serie']=$factura[0]['serie'];
	    $cabecera['factura']=$factura[0]['num_factura'];
	    $cabecera['esta']=substr($factura[0]['serie'],0,3); 
	    $cabecera['pto_e']=substr($factura[0]['serie'],3,6); 
	    $cabecera['item']=$parametros['empresa'];
	    $cabecera['Entidad']=$parametros['empresa'];	    
		$cabecera['contabilidad'] = $empresa[0]['Obligado_conta'];
		$cabecera['contribuyenteEspecial'] = $empresa[0]['Contribuyente_esp'];
	    $cabecera['tc']='FA';
	    $link = $this->SRI->links_sri($empresa[0]['Ambiente']);
	    $this->linkSriAutorizacion = $link[0];
	    $this->linkSriRecepcion = $link[1]; 
	    // $cabecera['periodo']=$empresa[0]['periodo'];

	    if($cabecera['cod_doc']=='01')
	    {
	    	$sql="SELECT * 
			FROM facturas F
			INNER JOIN cliente C ON F.id_cliente = C.id_cliente
			WHERE F.id_factura = '".$parametros['fac']."'";		
	    }else
	    {
	    	$sql="SELECT * 
			FROM liquidacion_compras L
			INNER JOIN cliente C ON L.id_cliente = C.id_cliente
			WHERE L.id_factura = '".$parametros['fac']."'";		
	    }


	    // if($cabecera['cod_doc']=='01')
	    // {
	    	//datos de factura
			$datos_fac = $this->conn->datos($sql);
		// print_r($sql);die();
	        if(is_object($datos_fac[0]['fecha']))
	        {
	        	 $datos_fac[0]['fecha'] = $datos_fac[0]['fecha']->format('Y-m-d');
	        }

	    	// print_r($datos_fac);die();
	    	    $cabecera['RUC_CI']=$datos_fac[0]['ci_ruc'];
				$cabecera['Fecha']=$datos_fac[0]['fecha'];
				$cabecera['Razon_Social_Comp']=$this->quitar_carac($datos_fac[0]['Razon_Social']);
				$cabecera['Direccion_RS']=$this->quitar_carac($datos_fac[0]['direccion']);
				$cabecera['Sin_IVA']= number_format($datos_fac[0]['Sin_Iva'],2,'.','');
				$cabecera['Descuento']=number_format($datos_fac[0]['descuento'],2,'.','');
				$cabecera['baseImponible']=number_format($datos_fac[0]['Sin_Iva']+$cabecera['Descuento'],2,'.','');
				$cabecera['Porc_IVA']=number_format($datos_fac[0]['Porc_IVA'],2,'.','');
				$cabecera['Con_IVA']=number_format($datos_fac[0]['Con_IVA'],2,'.','');
				$cabecera['Total_MN']=floatval($datos_fac[0]['total']);
				if($datos_fac[0]['Tipo_pago'] == '' || $datos_fac[0]['Tipo_pago'] == '.')
				{
					$cabecera['formaPago']='01';
				}else
				{
					$cabecera['formaPago']=$datos_fac[0]['Tipo_pago'];
				}
				$cabecera['Propina']=$datos_fac[0]['Propina'];
				$cabecera['Autorizacion']=$datos_fac[0]['Autorizacion'];
				// $cabecera['Imp_Mes']=$datos_fac[0]['Imp_Mes'];
				$cabecera['SP']='0';//rrevisar que es
				// $cabecera['CodigoC']=$datos_fac[0]['CodigoC'];
				$cabecera['TelefonoC']=$datos_fac[0]['telefono'];
				$cabecera['Orden_Compra']=0;//$datos_fac[0]['Orden_Compra'];
				$cabecera['baseImponibleSinIva']=$cabecera['Sin_IVA'];//-$datos_fac[0]['Desc_0'];
				$cabecera['baseImponibleConIva']=$cabecera['Con_IVA'];//-$datos_fac[0]['Desc_X'];
				$cabecera['totalSinImpuestos']=$cabecera['Sin_IVA']+$cabecera['Con_IVA']- $cabecera['Descuento'];
				$cabecera['IVA']=number_format($datos_fac[0]['iva'],2,'.','');
				$cabecera['Total_MN']=number_format($datos_fac[0]['total'],2,'.','');
				$cabecera['descuentoAdicional']=0;
				$cabecera['moneda']="DOLAR";
				$cabecera['tipoIden']='';

				//datos de cliente
	    		// $datos_cliente = $this->datos_cliente($datos_fac[0]['CodigoC']);
	    		// print_r($datos_cliente);die();
	    	    $cabecera['Cliente']=$this->quitar_carac($datos_fac[0]['nombre']);
				$cabecera['DireccionC']=$this->quitar_carac($datos_fac[0]['direccion']);
				$cabecera['TelefonoC']=$datos_fac[0]['telefono'];
				$cabecera['EmailR']=$this->quitar_carac($datos_fac[0]['mail']);
				$cabecera['EmailC']=$this->quitar_carac($datos_fac[0]['mail']);
				$cabecera['Contacto']='99999999';//$datos_cliente[0]['Contacto'];
				$cabecera['Grupo']='0';

			//codigo verificador 
				if($cabecera['RUC_CI']=='9999999999999')
				  {
				  	$cabecera['tipoIden']='07';
			      }else
			      {
			      	$cod_veri = $this->digito_verificadorf($datos_fac[0]['ci_ruc'],1);
			      	switch ($cod_veri) {
			      		case 'R':
			      			$cabecera['tipoIden']='04';
			      			break;
			      		case 'C':
			      			$cabecera['tipoIden']='05';
			      			break;
			      		case 'O':
			      			$cabecera['tipoIden']='06';
			      			break;
			      	}
			      }

			      // print_r($cabecera['Porc_IVA']);die()

			    $val_iva = ($cabecera['Porc_IVA']*100);
			    $sql3 = "SELECT * FROM porcentaje_iva WHERE porcentaje = ".$val_iva." AND '".date('Y-m-d')."' between fecha and fechafin";
				$iva_porc = $this->conn->datos($sql3);
				// print_r($iva_porc);die();
				if(count($iva_porc)>0)
				{
					$cabecera['codigoPorcentaje']=$iva_porc[0]['codigo'];
				}


			    // $cabecera['codigoPorcentaje']=0;
			    // if(($cabecera['Porc_IVA']*100)>12)
			    // {
			    //    $cabecera['codigoPorcentaje']=3;
			    // }else
			    // {
			    //   $cabecera['codigoPorcentaje']=2;
			    // }
			   //detalle de factura
			    $detalle = array();
			    if($cabecera['cod_doc']=='01')
			    {
			    	$cuerpo_fac = $this->detalle_factura($parametros['fac'],$parametros['empresa']);
			    }else
			    {
			    	$cuerpo_fac = $this->detalle_liquidacion($parametros['fac'],$parametros['empresa']);			    	
			    }
			    foreach ($cuerpo_fac as $key => $value) 
			    {
			    	$producto = $this->datos_producto($value['referencia'],$parametros['empresa']);
			    	$detalle[$key]['Codigo'] =  $value['referencia'];
			    	$detalle[$key]['Cod_Aux'] =  $producto[0]['codigo_aux'];
				    $detalle[$key]['Cod_Bar'] =  $producto[0]['codigo_bar'];
				    $detalle[$key]['Producto'] = $this->quitar_carac($value['producto']);
				    $detalle[$key]['Cantidad'] = $value['cantidad'];
				    $detalle[$key]['Precio'] = $value['precio_uni'];
				    $detalle[$key]['descuento'] = $value['descuento'];
				    $detalle[$key]['SubTotal'] = $value['subtotal'];//number_format(($value['cantidad']*$value['precio_uni'])-($value['descuento']),2,'.','');
				    $detalle[$key]['Serie_No'] = $value['Serie_No'];
				    $detalle[$key]['Total_IVA'] = number_format($value['iva'],2,'.','');
				    $detalle[$key]['Porc_IVA']= $value['porc_iva'];
			    }
			    $cabecera['fechaem']=  date("d/m/Y", strtotime($cabecera['Fecha']));
			    $cabecera['ClaveAcceso'] =$this->Clave_acceso($cabecera['Fecha'],$cabecera['cod_doc'],$cabecera['serie'],$cabecera['factura'],$cabecera['ambiente'],$cabecera['ruc_principal']);
			    // print_r($cabecera);print_r($detalle);die();

	           $xml = $this->generar_xml($cabecera,$detalle);
	           // die();

	           if($xml==1)
	           {           	 

	           	 $firma = $this->firmar_documento(
	           	 	$cabecera['ClaveAcceso'],
	           	 	$cabecera['Entidad'],
	           	 	$cabecera['item'],
	           	 	$cabecera['clave_ce'],
	           	 	$cabecera['ruta_ce'],$cabecera['carpeta']);
	           	 // die();
	           	 if($firma['resp']==1)
	           	 {
	           	 	
			   		 	$enviar_sri = $this->enviar_xml_sri(
			   		 		$cabecera['ClaveAcceso'],
			   		 		$this->linkSriRecepcion,$cabecera['carpeta']);
			   		 	// print_r('expression');die();
			   		 	if($enviar_sri[0]==1)
			   		 	{
			   		 		//una vez enviado comprobamos el estado de la factura
			   		 		$resp =  $this->comprobar_xml_sri($cabecera['ClaveAcceso'],$this->linkSriAutorizacion,$cabecera['carpeta']);
			   		 		if($resp[0]==1)
			   		 		{
			   		 			
			   		 			if($cabecera['cod_doc']=='01')
								{
								 $resp_auto = $this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],'A',$_SESSION['INICIO']['ID_EMPRESA']);
								}else
								{
									$this->actualizar_datos_LC($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],'A',$_SESSION['INICIO']['ID_EMPRESA']);
								}
			   		 			// $this->actualizar_saldo_empresa();
			   		 			return $resp;
			   		 		}else
			   		 		{
			   		 			
			   		 			if($cabecera['cod_doc']=='01')
								{
								$resp_auto = $this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],'R',$_SESSION['INICIO']['ID_EMPRESA']);
								}else
								{
									$this->actualizar_datos_LC($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],'R',$_SESSION['INICIO']['ID_EMPRESA']);
								}



			   		 			return $resp;
			   		 		}
			   		 		// print_r($resp);die();
			   		 	}else
			   		 	{
			   		 		
			   		 		if($cabecera['cod_doc']=='01')
								{
								$resp = $this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],'R',$_SESSION['INICIO']['ID_EMPRESA']);
								}else
								{
									$this->actualizar_datos_LC($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],'R',$_SESSION['INICIO']['ID_EMPRESA']);
								}
			   		 		return $enviar_sri;
			   		 	}

	           	 }else
	           	 {
	           	 	//RETORNA SI FALLA AL FIRMAR EL XML
	           	 	return $firma;
	           	 }
	           }else
	           {
	           	//RETORNA SI FALLA EL GENERAR EL XML
	           	return $xml;
	           }            

	    // }
	}


	function Autorizar_retencion($parametros)
	{
		// print_r($parametros);die();
		if(!isset($parametros['empresa']))
		{
			$parametros['empresa'] = $_SESSION['INICIO']['ID_EMPRESA'];
		}
		$sql = "SELECT * FROM EMPRESAS WHERE id_empresa = '".$parametros['empresa']."'";
		$sql2 ="SELECT * FROM retenciones R INNER JOIN cliente C ON R.idproveedor = C.id_cliente WHERE id_retenciones = '".$parametros['ret']."'" ;
		$empresa = $this->conn->datos($sql,$parametros['empresa']);
		$retencion  = $this->conn->datos($sql2,$parametros['empresa']);
		$impuestosfac = array(); //inpuesto de factura bienes y servicios
		$impuestosret = array();
		if(count($retencion)>0)
		{
			$sql3 ="SELECT * FROM retenciones_impuestos WHERE NoRetencion = '".$retencion[0]['numero']."' AND serieRet = '".$retencion[0]['serie']."' AND bienes_servicios = 1" ;
			$sql4 ="SELECT * FROM retenciones_impuestos WHERE NoRetencion = '".$retencion[0]['numero']."' AND serieRet = '".$retencion[0]['serie']."'  AND bienes_servicios = 0" ;
			$impuestosfac = $this->conn->datos($sql3,$parametros['empresa']);	
			$impuestosret = $this->conn->datos($sql4,$parametros['empresa']);
			$cabecera['PagoLocExt'] = $retencion[0]['PagoLocExt'];		
		}else{ return -1;}		


		// print_r($retencion);die();
		$cabecera['carpeta'] = 'RETENCIONES';
		$cabecera['ambiente']=$empresa[0]['Ambiente'];
		$cabecera['obligadoContabilidad'] = $empresa[0]['obligadoContabilidad'];
	    $cabecera['ruta_ce']=$empresa[0]['Ruta_Certificado'];
	    $cabecera['clave_ce']=$empresa[0]['Clave_Certificado'];
	    $cabecera['nom_comercial_principal']=$this->quitar_carac($empresa[0]['Nombre_Comercial']);
	    $cabecera['razon_social_principal']=$this->quitar_carac($empresa[0]['Razon_Social']);
	    $cabecera['ruc_principal']=$empresa[0]['RUC'];
	    $cabecera['direccion_principal']= $this->quitar_carac($empresa[0]['Direccion']);
	    $cabecera['serie_R']= str_replace('-','',$retencion[0]['serie']);
	    $cabecera['serie_R2'] = $retencion[0]['serie'];
	    $cabecera['retencion']=$retencion[0]['numero'];
	    $cabecera['esta']=substr( str_replace('-','',$retencion[0]['serie']),0,3); 
	    $cabecera['pto_e']=substr( str_replace('-','',$retencion[0]['serie']),4,5); 
	    if(is_object($retencion[0]['fechaEmision']))
	    {
	    	$cabecera['fecha']=$retencion[0]['fechaEmision']->format('Y-m-d'); 
	    }else
	    {
	    	$cabecera['fecha']=$retencion[0]['fechaEmision']; 
	    }

	    if(is_object($retencion[0]['emisionFac']))
	    {
	    	$cabecera['EmisionFac']=$retencion[0]['emisionFac']->format('Y-m-d'); 
	    }else
	    {
	    	$cabecera['EmisionFac']=$retencion[0]['emisionFac']; 
	    }

	    if(is_object($retencion[0]['VencimientoFac']))
	    {
	    	$cabecera['VencimientoFac']=$retencion[0]['VencimientoFac']->format('Y-m-d'); 
	    }else
	    {
	    	$cabecera['VencimientoFac']=$retencion[0]['VencimientoFac']; 
	    }    
		

	    $cabecera['CI_RUC']=$retencion[0]['ci_ruc']; 
	    $cabecera['proveedor']=$retencion[0]['nombre']; 
	    $cabecera['DireccionC']= $retencion[0]['direccion'];
		$cabecera['TelefonoC']= $retencion[0]['telefono'];
		$cabecera['EmailC']= $retencion[0]['mail'];
	    $cabecera['TD']=$retencion[0]['TD']; 
	    $cabecera['TD']=$retencion[0]['TD']; 
	    $cabecera['BaseImponible'] = number_format($retencion[0]['tarifa0'],2,'.','');
 		$cabecera['BaseImpGrav'] = number_format($retencion[0]['tarifa12'],2,'.','');
		$cabecera['MontoIva'] = number_format($retencion[0]['montoIva'],2,'.','');
		$cabecera['serie_F']=$retencion[0]['EstablecimientoFac'].$retencion[0]['puntoventa_Fac'];
		$cabecera['Factura']=$retencion[0]['numeroFac'];
		$cabecera['AutorizacionFac']=$retencion[0]['autorizacionFac'];
	    $cabecera['cod_doc']='07';
	    $cabecera['item']=$parametros['empresa'];
	    $cabecera['Entidad']=$parametros['empresa'];	    
		$cabecera['contabilidad'] = $empresa[0]['obligadoContabilidad'];
		$cabecera['contribuyenteEspecial'] = $empresa[0]['contribuyenteEspecial'];
	    $cabecera['tc']='RE';	    
	    $cabecera['ClaveAcceso']=$this->Clave_acceso($cabecera['fecha'],$cabecera['cod_doc'],$cabecera['serie_R'],$cabecera['retencion'],$cabecera['ambiente'],$cabecera['ruc_principal']);


	    $link = $this->SRI->links_sri($empresa[0]['Ambiente']);
	    $this->linkSriAutorizacion = $link[0];
	    $this->linkSriRecepcion = $link[1]; 

	    $xml = $this->generar_xml_retencion($cabecera,$impuestosfac,$impuestosret);

	    if($xml==1)
	       {           	 

	       	 $firma = $this->firmar_documento(
	       	 	$cabecera['ClaveAcceso'],
	       	 	$cabecera['Entidad'],
	       	 	$cabecera['item'],
	       	 	$cabecera['clave_ce'],
	       	 	$cabecera['ruta_ce'],
	       	 	$cabecera['carpeta']);
	       	 if($firma==1)
	       	 {
	       	 	$validar_autorizado = $this->comprobar_xml_sri(
	       	 		$cabecera['ClaveAcceso'],
	       	 		$this->linkSriAutorizacion,$cabecera['carpeta']);
	       	 	if($validar_autorizado == -1)
		   		 {
		   		 	$enviar_sri = $this->enviar_xml_sri(
		   		 		$cabecera['ClaveAcceso'],
		   		 		$this->linkSriRecepcion,$cabecera['carpeta']);
		   		 	if($enviar_sri==1)
		   		 	{
		   		 		//una vez enviado comprobamos el estado de la factura
		   		 		$resp =  $this->comprobar_xml_sri($cabecera['ClaveAcceso'],$this->linkSriAutorizacion,$cabecera['carpeta']);
		   		 		if($resp==1)
		   		 		{
		   		 			$resp = $this->actualizar_datos_RET($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie_R2'],$cabecera['retencion'],$cabecera['Entidad'],$cabecera['ClaveAcceso'],'A',$_SESSION['INICIO']['ID_EMPRESA']);
		   		 			return  $resp;
		   		 		}
		   		 		// print_r($resp);die();
		   		 	}else
		   		 	{
		   		 		$estado = 'A';
		   		 		if ($enviar_sri==2) {
		   		 			$estado = 'R';
		   		 		}
		   		 		$resp = $this->actualizar_datos_RET($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie_R2'],$cabecera['retencion'],$cabecera['Entidad'],$cabecera['ClaveAcceso'],$estado,$_SESSION['INICIO']['ID_EMPRESA']);
		   		 			// return  $resp;
		   		 		return $enviar_sri;
		   		 	}

		   		 }else 
		   		 {
		   		 	// RETORNA SI YA ESTA AUTORIZADO O SI FALL LA REVISIO EN EL SRI
		   			return $validar_autorizado;
		   		 }
	       	 }else
	       	 {
	       	 	//RETORNA SI FALLA AL FIRMAR EL XML
	       	 	return $firma;
	       	 }
	       }else
	       {
	       	//RETORNA SI FALLA EL GENERAR EL XML
	       	$resp = $this->actualizar_datos_RET($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie_R2'],$cabecera['retencion'],$cabecera['Entidad'],$cabecera['ClaveAcceso'],'A',$_SESSION['INICIO']['ID_EMPRESA']);
	       	return $xml;
	       }        
	}


	function Autorizar_guia_remision($parametros)
	{
		/*
			retorna entre 1 2 3
			1 Este documento electronico autorizado
			2 XML devuelto
			3 Este documento electronico ya esta autorizado
		*/
		$factura = array();
		if(!isset($parametros['empresa']))
		{
			$parametros['empresa'] = $_SESSION['INICIO']['ID_EMPRESA'];
		}
		$sql = "SELECT * FROM empresa WHERE id_empresa = '".$parametros['empresa']."'";
		$empresa = $this->conn->datos($sql,$parametros['empresa']);

		$sql3 = "SELECT * FROM guia_remision G 
		INNER JOIN cliente C ON G.id_cliente = C.id_cliente
		WHERE G.id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."' AND ID = '".$parametros['guia']."'";
		$guia =  $this->conn->datos($sql3,$parametros['empresa']);

		if(count($guia)>0 && $guia[0]['id_fac_interna']!='')
		{
			$sql2 ="SELECT * FROM facturas F INNER JOIN cliente C ON F.id_cliente = C.id_cliente WHERE id_factura = '".$guia[0]['id_fac_interna']."' " ;
			$factura = $this->conn->datos($sql2);
		}

		
		$cabecera['carpeta'] = 'GUIA_REMISION';
		$cabecera['ambiente']=$empresa[0]['Ambiente'];
	    $cabecera['ruta_ce']=$empresa[0]['Ruta_Certificado'];
	    $cabecera['clave_ce']=$empresa[0]['Clave_Certificado'];
	    $cabecera['nom_comercial_principal']=$this->quitar_carac($empresa[0]['Nombre_Comercial']);
	    $cabecera['razon_social_principal']=$this->quitar_carac($empresa[0]['Razon_Social']);
	    $cabecera['ruc_principal']=$empresa[0]['RUC'];
	    $cabecera['direccion_principal']= $this->quitar_carac($empresa[0]['Direccion']);	    
	    $cabecera['item']=$parametros['empresa'];
	    $cabecera['Entidad']=$parametros['empresa'];	    
		$cabecera['obligadoContabilidad'] = $empresa[0]['obligadoContabilidad'];
		$cabecera['contribuyenteEspecial'] = $empresa[0]['contribuyenteEspecial'];
	    $cabecera['tc']='GR';


	    // datos de guia
	    if(is_object($guia[0]['FechaGRE']))
	    {
	    	$cabecera['FechaGR']=$guia[0]['FechaGRE']->format('Y-m-d');
	    }else
	    {
	    	$cabecera['FechaGR']= substr($guia[0]['FechaGRE'],0,10);
	    }
	    $cabecera['Serie_GR'] = $guia[0]['Serie_GR'];
	    $cabecera['Serie_GR2']= str_replace('-','',$guia[0]['Serie_GR']);
	    $cabecera['Remision']=$guia[0]['Remision'];
	    $cabecera['esta']=substr($cabecera['Serie_GR2'],0,3); 
	    $cabecera['pto_e']=substr($cabecera['Serie_GR2'],4,6); 
	    $cabecera['cod_doc']='06';
	    $cabecera['Nombre_Establecimiento'] = '';
		$cabecera['CiudadGRI'] = $guia[0]['CiudadGRI'];
		$cabecera['Comercial'] = $guia[0]['Comercial'];
		$cabecera["CIRUCComercial"] = $guia[0]['CIRUC_Comercial'];
		$cabecera['FechaGRI'] = $guia[0]['FechaGRI'];
		$cabecera['FechaGRF'] = $guia[0]['FechaGRF'];
		$cabecera['Placa_Vehiculo'] = $guia[0]['Placa_Vehiculo'];
		$cabecera['CIRUCEntrega'] =  $guia[0]['CIRUC_Entrega'];
		$cabecera['Entrega'] =  $guia[0]['Entrega'];
		$cabecera['CiudadGRF'] =  $guia[0]['CiudadGRF'];
		$cabecera['TC'] =  'GR';
		$cabecera['Serie'] =  $guia[0]['Serie'];
		$cabecera['Serie2'] = str_replace('-','', $guia[0]['Serie']);
		$cabecera["Autorizacion"] = $guia[0]['Autorizacion'];
		 if(is_object($guia[0]['Fecha']))
	    {
	    	$cabecera['Fecha']=$guia[0]['Fecha']->format('Y-m-d');
	    }else
	    {
	    	$cabecera['Fecha']= substr($guia[0]['Fecha'],0,10);
	    }
		$cabecera['Factura'] = $guia[0]['Factura'];
		$cabecera['DireccionC'] = $guia[0]['direccion'];
		$cabecera['TelefonoC'] = $guia[0]['telefono'];
		$cabecera['EmailC'] = $guia[0]['mail'];
	    


	    $link = $this->SRI->links_sri($empresa[0]['Ambiente']);
	    $this->linkSriAutorizacion = $link[0];
	    $this->linkSriRecepcion = $link[1]; 
	    // $cabecera['periodo']=$empresa[0]['periodo'];


	    $cuerpo_fac = $this->detalle_guia($parametros['guia'],$parametros['empresa']);
	    foreach ($cuerpo_fac as $key => $value) 
	    {
	    	$producto = $this->datos_producto($value['referencia'],$parametros['empresa']);
	    	$detalle[$key]['Codigo'] =  $value['referencia'];
	    	$detalle[$key]['Cod_Aux'] =  $producto[0]['codigo_aux'];
		    $detalle[$key]['Cod_Bar'] =  $producto[0]['codigo_bar'];
		    $detalle[$key]['Producto'] = $this->quitar_carac($value['producto']);
		    $detalle[$key]['Cantidad'] = $value['cantidad'];
		    $detalle[$key]['Precio'] = $value['precio_uni'];
		    $detalle[$key]['descuento'] = $value['descuento'];
		    $detalle[$key]['SubTotal'] = $value['subtotal'];//number_format(($value['cantidad']*$value['precio_uni'])-($value['descuento']),2,'.','');
		    $detalle[$key]['Serie_No'] = $value['Serie_No'];
		    $detalle[$key]['Total_IVA'] = number_format($value['iva'],2,'.','');
		    $detalle[$key]['Porc_IVA']= $value['porc_iva'];
	    }
	    $cabecera['fechaem']=  date("d/m/Y", strtotime($cabecera['FechaGR']));
	    $cabecera['ClaveAcceso_GR'] =$this->Clave_acceso($cabecera['FechaGR'],$cabecera['cod_doc'],$cabecera['Serie_GR'],$cabecera['Remision'],$cabecera['ambiente'],$cabecera['ruc_principal']);
	    // print_r($cabecera);print_r($detalle);die();

       		$xml = $this->generar_xml_guia($cabecera,$detalle);
       		// die();
       		if($xml==1)
	       {           	 

	       	 $firma = $this->firmar_documento(
	       	 	$cabecera['ClaveAcceso_GR'],
	       	 	$cabecera['Entidad'],
	       	 	$cabecera['item'],
	       	 	$cabecera['clave_ce'],
	       	 	$cabecera['ruta_ce'],
	       	 	$cabecera['carpeta']);
	       	 if($firma==1)
	       	 {
	       	 	$validar_autorizado = $this->comprobar_xml_sri(
	       	 		$cabecera['ClaveAcceso_GR'],
	       	 		$this->linkSriAutorizacion,$cabecera['carpeta']);
	       	 	if($validar_autorizado == -1)
		   		 {
		   		 	$enviar_sri = $this->enviar_xml_sri(
		   		 		$cabecera['ClaveAcceso_GR'],
		   		 		$this->linkSriRecepcion,$cabecera['carpeta']);
		   		 	if($enviar_sri==1)
		   		 	{
		   		 		//una vez enviado comprobamos el estado de la factura
		   		 		$resp =  $this->comprobar_xml_sri($cabecera['ClaveAcceso_GR'],$this->linkSriAutorizacion,$cabecera['carpeta']);
		   		 		if($resp==1)
		   		 		{
		   		 			$resp = $this->actualizar_datos_GR($cabecera['ClaveAcceso_GR'],$cabecera['tc'],$cabecera['Serie_GR'],$cabecera['Remision'],'A',$_SESSION['INICIO']['ID_EMPRESA']);
		   		 			return  $resp;
		   		 		}
		   		 		// print_r($resp);die();
		   		 	}else
		   		 	{
		   		 		$estado = 'P';
		   		 		if($enviar_sri==2){$estado = 'R';}
		   		 		$resp = $this->actualizar_datos_GR($cabecera['ClaveAcceso_GR'],$cabecera['tc'],$cabecera['Serie_GR'],$cabecera['Remision'],$estado,$_SESSION['INICIO']['ID_EMPRESA']);
		   		 			// return  $resp;
		   		 		return $enviar_sri;
		   		 	}

		   		 }else 
		   		 {
		   		 	// RETORNA SI YA ESTA AUTORIZADO O SI FALL LA REVISIO EN EL SRI
		   		 	$resp = $this->actualizar_datos_GR($cabecera['ClaveAcceso_GR'],$cabecera['tc'],$cabecera['Serie_GR'],$cabecera['Remision'],'R',$_SESSION['INICIO']['ID_EMPRESA']);
		   			return $validar_autorizado;
		   		 }
	       	 }else
	       	 {
	       	 	//RETORNA SI FALLA AL FIRMAR EL XML
	       	 	return $firma;
	       	 }
	       }else
	       {
	       	//RETORNA SI FALLA EL GENERAR EL XML
	       	$resp = $this->actualizar_datos_GR($cabecera['ClaveAcceso_GR'],$cabecera['tc'],$cabecera['Serie_GR'],$cabecera['Remision'],'A',$_SESSION['INICIO']['ID_EMPRESA']);
	       	return $xml;
	       }        	    
   	}



   	function generar_xml_guia($cabecera,$detalle)
   	{
   		// print_r($cabecera);
		// print_r('expression');
		//print_r($detalle);
		// die();
	$entidad = $cabecera['Entidad'];
	$empresa = $cabecera['item'];
	$this->crear_carpetas($entidad,$empresa,$cabecera['carpeta']);
	$ambiente =$cabecera['ambiente'];
	$RIMPE = $this->tipo_contribuyente($cabecera['ruc_principal']);
	/*
	$sucursal = $this->catalogo_lineas('RE',$cabecera['Serie_GR']);
	if(count($sucursal)>0)
	 {
	 	$cabecera[0]['Nombre_Establecimiento'] = $sucursal[0]['Nombre_Establecimiento'];
	 	$cabecera[0]['Direccion_Establecimiento'] = $sucursal[0]['Direccion_Establecimiento'];
	 	$cabecera[0]['Telefono_Establecimiento'] = $sucursal[0]['Telefono_Estab'];
	 	$cabecera[0]['Ruc_Establecimiento'] = $sucursal[0]['RUC_Establecimiento'];
	 	$cabecera[0]['Email_Establecimiento'] = $sucursal[0]['Email_Establecimiento'];
	 	$cabecera[0]['Placa_Vehiculo'] ='.';
	 	$cabecera[0]['Cta_Establecimiento'] = '.';
	 	if(isset($sucursal[0]['Placa_Vehiculo']))
	 	{
	 		$cabecera['Placa_Vehiculo'] = $sucursal[0]['Placa_Vehiculo'];
	 	}
	 	if (isset($sucursal[0]['Cta_Establecimiento'])) {
	 		$cabecera['Cta_Establecimiento'] = $sucursal[0]['Cta_Establecimiento'];
	 	}		 	
	 }
	*/
	$carpeta_autorizados = dirname(__DIR__)."/entidades/entidad_".$entidad.'/CE'.$empresa."/".$cabecera['carpeta']."/Autorizados";		  
	if(file_exists($carpeta_autorizados.'/'.$cabecera['ClaveAcceso_GR'].'.xml'))
	{
		$respuesta = 3;
		return $respuesta;
	}

	    $xml = new DOMDocument( "1.0", "UTF-8");
        $xml->formatOutput = true;
        $xml->preserveWhiteSpace = false;
	    $xml->xmlStandalone = true;

	    $xml_inicio = $xml->createElement( "guiaRemision" );
        $xml_inicio->setAttribute( "id", "comprobante" );
        $xml_inicio->setAttribute( "version", "1.0.0" );
        //informacion de cabecera
	    $xml_infotributaria = $xml->createElement("infoTributaria");
	    $xml_ambiente = $xml->createElement("ambiente",$ambiente);
	    $xml_tipoEmision = $xml->createElement("tipoEmision","1");
	    $xml_razonSocial = $xml->createElement("razonSocial",$cabecera['razon_social_principal']);
	    $xml_nombreComercial = $xml->createElement("nombreComercial",$cabecera['nom_comercial_principal']);
	    $xml_ruc = $xml->createElement("ruc",$cabecera['ruc_principal']);
	    $xml_claveAcceso = $xml->createElement("claveAcceso",$cabecera['ClaveAcceso_GR']);
	    $xml_codDoc = $xml->createElement("codDoc",'06');
	    $xml_estab = $xml->createElement("estab",substr($cabecera['Serie_GR2'], 0,3));
	    $xml_ptoEmi = $xml->createElement("ptoEmi",substr($cabecera['Serie_GR2'], 3,3));
	    $xml_secuencial = $xml->createElement("secuencial",$this->generaCeros($cabecera['Remision'],9));
	    $xml_dirMatriz = $xml->createElement("dirMatriz",$cabecera['direccion_principal']);




        $xml_infotributaria->appendChild($xml_ambiente);
        $xml_infotributaria->appendChild($xml_tipoEmision);
        $xml_infotributaria->appendChild($xml_razonSocial);
        $xml_infotributaria->appendChild($xml_nombreComercial);
        $xml_infotributaria->appendChild($xml_ruc);
        $xml_infotributaria->appendChild($xml_claveAcceso);
        $xml_infotributaria->appendChild($xml_codDoc);
        $xml_infotributaria->appendChild($xml_estab);
        $xml_infotributaria->appendChild($xml_ptoEmi);
        $xml_infotributaria->appendChild($xml_secuencial);
        $xml_infotributaria->appendChild($xml_dirMatriz);

		
		if(count($RIMPE)>0)
		{
			if($RIMPE['microempresa']!='.' && $RIMPE['microempresa']!='' )
			{
				$xml_contribuyenteRimpe = $xml->createElement( "contribuyenteRimpe",$RIMPE['microempresa']);
				$xml_infotributaria->appendChild( $xml_contribuyenteRimpe);
			}
			if($RIMPE['agente']!='.' && $RIMPE['agente']!='')
			{
				$xml_agenteRetencion = $xml->createElement( "agenteRetencion",'1');
				$xml_infotributaria->appendChild( $xml_agenteRetencion);
			}
		}

        // $xml->appendChild($xml_infotributaria);

        $xml_inicio->appendChild($xml_infotributaria);
        //fin de cabecera


	    $xml_infoCompGuia = $xml->createElement( "infoGuiaRemision");

	   
	    
	    if(isset($cabecera['Nombre_Establecimiento']) &&  strlen($cabecera['Nombre_Establecimiento'])>0 && $cabecera['Nombre_Establecimiento']!='.')
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera['Direccion_Establecimiento']);
		}else
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",strtoupper($cabecera['direccion_principal'] ));
		}    
	    $xml_infoCompGuia->appendChild($xml_dirEstablecimiento);

	    $xml_dirpartida = $xml->createElement('dirPartida',$cabecera['CiudadGRI']);
	    $xml_infoCompGuia->appendChild($xml_dirpartida);

	    $xml_razonsocialtrans = $xml->createElement('razonSocialTransportista',$cabecera['Comercial']);
	    $xml_infoCompGuia->appendChild($xml_razonsocialtrans);

	    $DigVerif =  $this->tipo_documento($cabecera["CIRUCComercial"]);
        $TipoIdent = "P";
        switch ($DigVerif['Tipo']) {
        	case 'R':
        		if($cabecera['CIRUCComercial']){$TipoIdent = '07';}else{$TipoIdent = '04';}
        		break;
        	case 'C':
        	 	$TipoIdent = '05';
        		break;
        	case 'P':
        	 	$TipoIdent = '06';
        		break;
        	default:        	
        		 	$TipoIdent = '07';
        		break;
        }
           

	    $xml_tipoidetrans = $xml->createElement('tipoIdentificacionTransportista',$TipoIdent);
	    $xml_infoCompGuia->appendChild($xml_tipoidetrans);

	    $xml_ructrans = $xml->createElement('rucTransportista',$cabecera['CIRUCComercial']);
	    $xml_infoCompGuia->appendChild($xml_ructrans);

	    $xml_rise = $xml->createElement('rise','000');
	    $xml_infoCompGuia->appendChild($xml_rise);
	    
	    if($cabecera['obligadoContabilidad']==1)
		{
			$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",'SI' );
		}else
		{

			$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",'NO' );
		}
	    $xml_infoCompGuia->appendChild($xml_obligadoContabilidad);

	    $fecha = date("d/m/Y", strtotime($cabecera['FechaGRI']));
	    $xml_fechainitrans = $xml->createElement('fechaIniTransporte', $fecha);
	    $xml_infoCompGuia->appendChild($xml_fechainitrans);

	    $fecha2 =  date("d/m/Y", strtotime($cabecera['FechaGRF']));
	    $xml_fechafintrans = $xml->createElement('fechaFinTransporte', $fecha2);
	    $xml_infoCompGuia->appendChild($xml_fechafintrans);

	    $xml_placa = $xml->createElement('placa',$cabecera['Placa_Vehiculo']);
	    $xml_infoCompGuia->appendChild($xml_placa);	


	    $xml_destinatarios = $xml->createElement( "destinatarios");

	        $xml_destinatario = $xml->createElement( "destinatario"); 

	        $xml_iddestinatario = $xml->createElement('identificacionDestinatario',$cabecera['CIRUCEntrega']);
	    	$xml_destinatario->appendChild($xml_iddestinatario);

	    	$xml_razondestinatario = $xml->createElement('razonSocialDestinatario',$cabecera['Entrega']);
	    	$xml_destinatario->appendChild($xml_razondestinatario);	


	    	$xml_dirdestinatario = $xml->createElement('dirDestinatario',$cabecera['CiudadGRF']);
	    	$xml_destinatario->appendChild($xml_dirdestinatario);	

	    	$xml_motivo = $xml->createElement('motivoTraslado',"Translado de mercaderia");
	    	$xml_destinatario->appendChild($xml_motivo);	

	    	$xml_ruta = $xml->createElement('ruta',"De ".$cabecera["CiudadGRI"]." a ".$cabecera["CiudadGRF"]);
	    	$xml_destinatario->appendChild($xml_ruta);

	    	switch ($cabecera['TC']) {
	    		case 'FA':
	    		$xml_coddocsus = $xml->createElement('codDocSustento','01');
	    			break;
	    		case 'NV':
	    		$xml_coddocsus = $xml->createElement('codDocSustento','02');
	    			break;    				    		
	    		default:
	    		$xml_coddocsus = $xml->createElement('codDocSustento','00');
	    			break;
	    	}
	    	$xml_destinatario->appendChild($xml_coddocsus);

	    	$cadena=$cabecera['Serie']."-".$this->generaCeros($cabecera['Factura'],9);
	    	$xml_numdocsus = $xml->createElement('numDocSustento',$cadena);
	    	$xml_destinatario->appendChild($xml_numdocsus);

	    	$xml_numautodocsus = $xml->createElement('numAutDocSustento',$cabecera["Autorizacion"]);
	    	$xml_destinatario->appendChild($xml_numautodocsus);

	    	$fechaeds = date("d/m/Y", strtotime($cabecera['Fecha']));
	    	$xml_fechaemidocsus = $xml->createElement('fechaEmisionDocSustento',$fechaeds);
	    	$xml_destinatario->appendChild($xml_fechaemidocsus);

	    		if(count($detalle)>0)
	    		{
	    			$xml_detalles = $xml->createElement( "detalles");
	    			foreach ($detalle as $key => $value) {	    
			
	    	 			$xml_detalle = $xml->createElement( "detalle");

	    	 			$Producto = trim($value["Producto"]);
	                    
	                    $SubTotal = ($value["Cantidad"] * $value["Precio"]) - $value["descuento"];

	    	 			  	$xml_codigo = $xml->createElement('codigoInterno',$value["Codigo"]);
	    	 			  	$xml_detalle->appendChild($xml_codigo);

	    	 			  	$xml_codigo = $xml->createElement('descripcion',$Producto);
	    	 			  	$xml_detalle->appendChild($xml_codigo);


	    	 			  	$xml_codigo = $xml->createElement('cantidad',$value["Cantidad"]);
	    	 			  	$xml_detalle->appendChild($xml_codigo);

	    	 			$xml_detalles->appendChild($xml_detalle);   		
	    			}

	    			$xml_destinatario->appendChild($xml_detalles);
	    		}

	    $xml_destinatarios->appendChild($xml_destinatario);


	 
        $xml_inicio->appendChild($xml_infoCompGuia);        
        $xml_inicio->appendChild($xml_destinatarios);




        //fin de xml retencion
        $xml_infoAdicional = $xml->createElement("infoAdicional");
/*
        if($cabecera['Cliente'] <>G_NINGUNO &&  $cabecera['Razon_Social'] <> $cabecera['Cliente'])
        {
           if(strlen($cabecera['Cliente']) > 1){
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['Cliente']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Beneficiario");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}

            if(strlen($cabecera['Curso']) > 1){
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['Grupo']."-".$cabecera['Curso']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Ubicacion");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
	    }

	    */
	    if (strlen($cabecera['DireccionC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['DireccionC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Direccion");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);

        	}
         if (strlen($cabecera['TelefonoC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['TelefonoC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Telefono");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
         if( strlen($cabecera['EmailC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['EmailC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Email");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
        	$xml_inicio->appendChild($xml_infoAdicional);
        	$xml->appendChild($xml_inicio);

		     $ruta_G = dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/'.$cabecera['carpeta'].'/Generados';
		     // print_r($ruta_G);die();
			if($archivo = fopen($ruta_G.'/'.$cabecera['ClaveAcceso_GR'].'.xml',"w+b"))
			  {
			  	fwrite($archivo,$xml->saveXML());
			  	return 1;
			  }else
			  {
			  	return -1;
			  }

	}

	function nota_de_credito($parametros)
	{
		if(!isset($parametros['empresa']))
		{
			$parametros['empresa'] = $_SESSION['INICIO']['ID_EMPRESA'];
		}
		$sql = "SELECT * FROM empresa WHERE id_empresa = '".$parametros['empresa']."'";
		$sql2 ="SELECT * FROM nota_credito N LEFT JOIN cliente C ON N.cliente = C.id_cliente 
		WHERE id_nota_credito = '".$parametros['txt_nota']."'" ;
		$empresa = $this->conn->datos($sql,$parametros['empresa']);
		$nota_credito = $this->conn->datos($sql2,$parametros['empresa']);

		// print_r($nota_credito);die();

		
		$cabecera['carpeta'] = 'NOTAS_CREDITO';
		$cabecera['ambiente']=$empresa[0]['Ambiente'];
	    $cabecera['ruta_ce']=$empresa[0]['Ruta_Certificado'];
	    $cabecera['clave_ce']=$empresa[0]['Clave_Certificado'];
	    $cabecera['nom_comercial_principal']=$this->quitar_carac($empresa[0]['Nombre_Comercial']);
	    $cabecera['razon_social_principal']=$this->quitar_carac($empresa[0]['Razon_Social']);
	    $cabecera['ruc_principal']=$empresa[0]['RUC'];
	    $cabecera['direccion_principal']= $this->quitar_carac($empresa[0]['Direccion']);	    
	    $cabecera['item']=$parametros['empresa'];
	    $cabecera['Entidad']=$parametros['empresa'];	    
		$cabecera['obligadoContabilidad'] = $empresa[0]['obligadoContabilidad'];
		$cabecera['contribuyenteEspecial'] = $empresa[0]['contribuyenteEspecial'];

		$cabecera['fecha_nc'] = $nota_credito[0]['fecha_nc'];
		$cabecera['numero_nc'] = $nota_credito[0]['numero_nc'];
		$cabecera['serie_doc'] = $nota_credito[0]['serie_doc'];
		$cabecera['serie_nc'] = $nota_credito[0]['serie_nc'];
		$cabecera['numero_doc'] = $nota_credito[0]['numero_doc'];
		$cabecera['fecha_doc'] = $nota_credito[0]['fecha_doc'];
		$cabecera['Razon_Social'] = $nota_credito[0]['cliente'];
		if($nota_credito[0]['Razon_Social']!='')
		{
			$cabecera['Razon_Social'] = $nota_credito[0]['Razon_Social'];
		}
		$cabecera['Cliente'] = $nota_credito[0]['nombre'];
		$cabecera['RUC_CI'] = $nota_credito[0]['ci_ruc'];
	    $cabecera['TB'] = $nota_credito[0]['TD'];
	    $cabecera['Porc_IVA'] = $nota_credito[0]['porc_iva'];
	    $cabecera['DireccionC'] = $nota_credito[0]['direccion'];
		$cabecera['TelefonoC'] = $nota_credito[0]['telefono'];
		$cabecera['EmailC'] = $nota_credito[0]['mail'];

		 $link = $this->SRI->links_sri($empresa[0]['Ambiente']);
	    $this->linkSriAutorizacion = $link[0];
	    $this->linkSriRecepcion = $link[1]; 


		// print_r($TFA);die();
		//    'NOTA DE CREDITO
		$SubT_Con_Inv = False;
		$Total_Sin_IVA = 0;
		$Total_Con_IVA = 0;
		$Total_Desc = 0;
		$Total_Desc2 = 0;
		$TFA['Total_IVA_NC'] = 0;
		    
	    $sql = "SELECT * FROM lineas_nota_credito WHERE nota_credito = '".$nota_credito[0]['id_nota_credito']."' ";
	    $AdoDBNC = $this->conn->datos($sql,$parametros['empresa']);
	    if(count($AdoDBNC)>0)
	    {
	    	$Con_Inv = True;
	    	foreach ($AdoDBNC as $key => $value) {
	    		$Total_Desc = $Total_Desc + $value["descuento"];
	            if($value["iva"] == 0){
	                $Total_Sin_IVA = $Total_Sin_IVA + $value["total"];
	            }else{
	                $Total_Con_IVA = $Total_Con_IVA + $value["total"];
	            }
	            $TFA['Total_IVA_NC'] = $TFA['Total_IVA_NC'] + $value["iva"];	    		
	    	}
	    }
	       
		   /* if(count($AdoDBNC)<= 0 )
		    {
		       $sql = "SELECT *
		            FROM Trans_Abonos
		            WHERE Item = '".$_SESSION['INGRESO']['item']."'
		            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		            AND Autorizacion = '".$TFA['Autorizacion'] & "'
		            AND Serie = '".$TFA['Serie'] & "'
		            AND TP = '".$TFA['TC'] & "'
		            AND Factura = ".$TFA['Factura'] & "
		            AND Serie_NC = '".$TFA['Serie_NC'] & "'
		            AND Secuencial_NC = ".$TFA['Nota_Credito'] & "
		            AND Banco = 'NOTA DE CREDITO'
		            ORDER BY TP,Fecha,Cta,Cta_CxP,Abono,Banco,Cheque ";
		       	   $AdoDBNC = $this->db->datos($sql);
		       
		         if(count($AdoDBNC) > 0)
		         {
		         	foreach ($AdoDBNC as $key => $value) 
		         	{	
		                if($value["Cheque"] = "I.V.A.")
		                {
		                    $TFA['Total_IVA_NC'] = $value["Abono"];
		                    $SubT_Con_Inv = True;
		                }else if($value["Cheque"] == "VENTAS SIN IVA")
		                {
		                    $Total_Sin_IVA = $value["Abono"];
		                }else{
		                    $Total_Con_IVA = $value["Abono"];
		                }
		            }		
		         }
		    }*/
		    $Total_Sin_IVA = number_format($Total_Sin_IVA, 2,'.','');
		    $Total_Con_IVA = number_format($Total_Con_IVA, 2,'.','');
		    $cabecera['Total_IVA_NC'] = 0; //number_format($cabecera['Total_IVA_NC'], 2,'.','');
		    $cabecera['SubTotal_NC'] = number_format($Total_Sin_IVA + $Total_Con_IVA, 2,'.','');
		    $TextoXML = "";

		// revisar

		/*if(!isset($TFA['Porc_NC']) || $TFA['Porc_NC'] == 0){
            $TFA['Porc_IVA'] = Validar_Porc_IVA($TFA['Fecha_NC']);
         }else{
            $TFA['Porc_IVA'] = $TFA['Porc_NC'];
         }*/


		// print_r($TFA);die();
        $cabecera['serie2'] = str_replace('-','',$nota_credito[0]['serie_nc']);
		$cabecera['TOTAL_SIN_IMPUESTOS'] = $Total_Sin_IVA + $Total_Con_IVA - $Total_Desc;
		$cabecera['VALOR_MODIFICACION'] = $Total_Sin_IVA + $Total_Con_IVA - $Total_Desc + $cabecera['Total_IVA_NC'];
		$cabecera['BASEIMPONIBLE'] = number_format($Total_Con_IVA - $Total_Desc,2,'.','');
		$cabecera['ClaveAcceso_NC'] = $this->Clave_acceso($nota_credito[0]['fecha_nc'],'04',$cabecera['serie2'],$nota_credito[0]['numero_nc'],$cabecera['ambiente'],$cabecera['ruc_principal']);

		$aut = $cabecera['ClaveAcceso_NC'];
		// print_r($TFA);die();
		 $xml = $this->generar_xml_nota_credito($cabecera,$AdoDBNC);
// die();
		 if($xml==1)
	       {           	 

	       	 $firma = $this->firmar_documento(
	       	 	$cabecera['ClaveAcceso_NC'],
	       	 	$cabecera['Entidad'],
	       	 	$cabecera['item'],
	       	 	$cabecera['clave_ce'],
	       	 	$cabecera['ruta_ce'],
	       	 	$cabecera['carpeta']);
	       	 if($firma==1)
	       	 {
	       	 	$validar_autorizado = $this->comprobar_xml_sri(
	       	 		$cabecera['ClaveAcceso_NC'],
	       	 		$this->linkSriAutorizacion,$cabecera['carpeta']);
	       	 	if($validar_autorizado == -1)
		   		 {
		   		 	$enviar_sri = $this->enviar_xml_sri(
		   		 		$cabecera['ClaveAcceso_NC'],
		   		 		$this->linkSriRecepcion,$cabecera['carpeta']);
		   		 	if($enviar_sri==1)
		   		 	{
		   		 		//una vez enviado comprobamos el estado de la factura
		   		 		$resp =  $this->comprobar_xml_sri($cabecera['ClaveAcceso_NC'],$this->linkSriAutorizacion,$cabecera['carpeta']);
		   		 		if($resp==1)
		   		 		{
		   		 			$resp = $this->actualizar_datos_NC($cabecera['ClaveAcceso_NC'],$cabecera['serie_nc'],$cabecera['numero_nc'],'A',$_SESSION['INICIO']['ID_EMPRESA']);
		   		 			return  $resp;
		   		 		}else
		   		 		{
		   		 			return $resp;
		   		 		}
		   		 		// print_r($resp);die();
		   		 	}else
		   		 	{
		   		 		$resp = $this->actualizar_datos_NC($cabecera['ClaveAcceso_NC'],$cabecera['serie_nc'],$cabecera['numero_nc'],'R',$_SESSION['INICIO']['ID_EMPRESA']);
		   		 			// return  $resp;
		   		 		return $enviar_sri;
		   		 	}

		   		 }else 
		   		 {
		   		 	// RETORNA SI YA ESTA AUTORIZADO O SI FALL LA REVISIO EN EL SRI
		   			return $validar_autorizado;
		   		 }
	       	 }else
	       	 {
	       	 	//RETORNA SI FALLA AL FIRMAR EL XML
	       	 	return $firma;
	       	 }
	       }else
	       {
	       	//RETORNA SI FALLA EL GENERAR EL XML
	       	if($xml==3){$estado = 'A';}else{$estado ='R';}
	       	$resp = $this->actualizar_datos_NC($cabecera['ClaveAcceso_NC'],$cabecera['serie_nc'],$cabecera['numero_nc'],$estado,$_SESSION['INICIO']['ID_EMPRESA']);
	       	return $xml;
	       }  

	}

	function generar_xml_nota_credito($cabecera,$detalle)
	{

	// print_r($cabecera);
	// print_r($detalle);
	// die();
	$this->crear_carpetas($cabecera['Entidad'],$cabecera['item'],$cabecera['carpeta']);
	$ambiente =$cabecera['ambiente'];
	$RIMPE = $this->tipo_contribuyente($cabecera['ruc_principal']);

		// $sucursal = $this->catalogo_lineas('RE',$cabecera[0]['Serie_R']);
		//  if(count($sucursal)>0)
		//  {
		//  	$cabecera[0]['Nombre_Establecimiento'] = $sucursal[0]['Nombre_Establecimiento'];
		//  	$cabecera[0]['Direccion_Establecimiento'] = $sucursal[0]['Direccion_Establecimiento'];
		//  	$cabecera[0]['Telefono_Establecimiento'] = $sucursal[0]['Telefono_Estab'];
		//  	$cabecera[0]['Ruc_Establecimiento'] = $sucursal[0]['RUC_Establecimiento'];
		//  	$cabecera[0]['Email_Establecimiento'] = $sucursal[0]['Email_Establecimiento'];
		//  	$cabecera[0]['Placa_Vehiculo'] ='.';
		//  	$cabecera[0]['Cta_Establecimiento'] = '.';
		//  	if(isset($sucursal[0]['Placa_Vehiculo']))
		//  	{
		//  		$cabecera[0]['Placa_Vehiculo'] = $sucursal[0]['Placa_Vehiculo'];
		//  	}
		//  	if (isset($sucursal[0]['Cta_Establecimiento'])) {
		//  		$cabecera[0]['Cta_Establecimiento'] = $sucursal[0]['Cta_Establecimiento'];
		//  	}		 	
		//  }

		$carpeta_autorizados = dirname(__DIR__)."/entidades/entidad_".$cabecera['Entidad'].'/CE'.$cabecera['item'].'/'.$cabecera['carpeta']."/Autorizados";		  
		if(file_exists($carpeta_autorizados.'/'.$cabecera['ClaveAcceso_NC'].'.xml'))
		{
			$respuesta = 3;
			return $respuesta;
		}

	    $xml = new DOMDocument( "1.0", "UTF-8");
        $xml->formatOutput = true;
        $xml->preserveWhiteSpace = false;
	    $xml->xmlStandalone = true;

	    $xml_inicio = $xml->createElement( "notaCredito" );
        $xml_inicio->setAttribute( "id", "comprobante" );
        $xml_inicio->setAttribute( "version", "1.0.0" );
        //informacion de cabecera
	    $xml_infotributaria = $xml->createElement("infoTributaria");
	    $xml_ambiente = $xml->createElement("ambiente",$ambiente);
	    $xml_tipoEmision = $xml->createElement("tipoEmision","1");
	    $xml_razonSocial = $xml->createElement("razonSocial",$this->quitar_carac($cabecera['razon_social_principal']));
	    $xml_nombreComercial = $xml->createElement("nombreComercial",$this->quitar_carac($cabecera['nom_comercial_principal']));
	    $xml_ruc = $xml->createElement("ruc",$cabecera['ruc_principal']);
	    $xml_claveAcceso = $xml->createElement("claveAcceso",$cabecera['ClaveAcceso_NC']);
	    $xml_codDoc = $xml->createElement("codDoc",'04');
	    $xml_estab = $xml->createElement("estab",substr($cabecera['serie2'], 0,3));
	    $xml_ptoEmi = $xml->createElement("ptoEmi",substr($cabecera['serie2'], 3,3));
	    $xml_secuencial = $xml->createElement("secuencial",$this->generaCeros($cabecera['numero_nc'],9));
	    $xml_dirMatriz = $xml->createElement("dirMatriz",$cabecera['direccion_principal']);




        $xml_infotributaria->appendChild($xml_ambiente);
        $xml_infotributaria->appendChild($xml_tipoEmision);
        $xml_infotributaria->appendChild($xml_razonSocial);
        $xml_infotributaria->appendChild($xml_nombreComercial);
        $xml_infotributaria->appendChild($xml_ruc);
        $xml_infotributaria->appendChild($xml_claveAcceso);
        $xml_infotributaria->appendChild($xml_codDoc);
        $xml_infotributaria->appendChild($xml_estab);
        $xml_infotributaria->appendChild($xml_ptoEmi);
        $xml_infotributaria->appendChild($xml_secuencial);
        $xml_infotributaria->appendChild($xml_dirMatriz);

		if(count($RIMPE)>0)
		{
			if($RIMPE['microempresa']!='.' && $RIMPE['microempresa']!='' )
			{
				$xml_contribuyenteRimpe = $xml->createElement( "contribuyenteRimpe",$RIMPE['microempresa']);
				$xml_infotributaria->appendChild( $xml_contribuyenteRimpe);
			}
			if($RIMPE['agente']!='.' && $RIMPE['agente']!='')
			{
				$xml_agenteRetencion = $xml->createElement( "agenteRetencion",'1');
				$xml_infotributaria->appendChild( $xml_agenteRetencion);
			}
		}


        // $xml->appendChild($xml_infotributaria);

        $xml_inicio->appendChild($xml_infotributaria);
        //fin de cabecera

	    $xml_infoNotaCredito = $xml->createElement( "infoNotaCredito");
	    
	    	$xml_fechaemidocsus = $xml->createElement('fechaEmision',date('d/m/Y',strtotime($cabecera["fecha_nc"])));
	    	$xml_infoNotaCredito->appendChild($xml_fechaemidocsus);

	
	    if(isset($cabecera[0]['Nombre_Establecimiento']) &&  strlen($cabecera[0]['Nombre_Establecimiento'])>0 && $cabecera[0]['Nombre_Establecimiento']!='.')
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera[0]['Direccion_Establecimiento']);
		}else
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",strtoupper($cabecera['direccion_principal']));
		}    
	   $xml_infoNotaCredito->appendChild($xml_dirEstablecimiento);

	    // print_r($cabecera);

	    //codigo verificador 
      	switch ($cabecera['TB']) {
      		case 'R':
      			if($cabecera['RUC_CI']=='9999999999999')
				  {
				  	$cabecera['tipoIden']='07';
			      }else{
      				$cabecera['tipoIden']='04';
      			}
      			break;
      		case 'C':
      			$cabecera['tipoIden']='05';
      			break;
      		case 'O':
      			$cabecera['tipoIden']='06';
      			break;
      	}

      	$xml_tipoIdentificacionComprador = $xml->createElement( "tipoIdentificacionComprador",$cabecera['tipoIden'] );
		$xml_infoNotaCredito->appendChild( $xml_tipoIdentificacionComprador );	 

		$xml_razonSocialComprador = $xml->createElement( "razonSocialComprador",$cabecera['Razon_Social'] );
		$xml_infoNotaCredito->appendChild( $xml_razonSocialComprador );

		$xml_identificacionComprador = $xml->createElement( "identificacionComprador",$cabecera['RUC_CI'] );		
		$xml_infoNotaCredito->appendChild( $xml_identificacionComprador );

		if($cabecera['obligadoContabilidad']==1)
		{
			$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",'SI' );
		}else
		{

			$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",'NO' );
		}

		$xml_infoNotaCredito->appendChild($xml_obligadoContabilidad);
		   

	    $xml_codDocModificado = $xml->createElement('codDocModificado','01');
	    $xml_infoNotaCredito->appendChild($xml_codDocModificado);

	    $xml_numDocModificado = $xml->createElement('numDocModificado',$cabecera['serie_doc'].'-'.$this->generaCeros($cabecera['numero_doc'],9));
		$xml_infoNotaCredito->appendChild($xml_numDocModificado);

		// print_r($cabecera);die();
		$xml_fechaEmisionDocSustento = $xml->createElement('fechaEmisionDocSustento',date('d/m/Y',strtotime($cabecera['fecha_doc'])));
		$xml_infoNotaCredito->appendChild($xml_fechaEmisionDocSustento);


	    $xml_totalSinImpuestos = $xml->createElement('totalSinImpuestos', $cabecera['TOTAL_SIN_IMPUESTOS']);
	    $xml_infoNotaCredito->appendChild($xml_totalSinImpuestos);

	    $xml_valorModificacion = $xml->createElement('valorModificacion', $cabecera['VALOR_MODIFICACION']);
	    $xml_infoNotaCredito->appendChild($xml_valorModificacion);

	    $xml_moneda = $xml->createElement('moneda','DOLAR');
	    $xml_infoNotaCredito->appendChild($xml_moneda);	

	    $xml_totalConImpuestos = $xml->createElement( "totalConImpuestos" );
		//sin iva
		$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );		 

		$xml_codigo = $xml->createElement( "codigo",'2' );

		// print_r($cabecera['Porc_IVA']);die();
		if(($cabecera['Porc_IVA'] * 100) > 12 ){
             $xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'3' );
           }else{
             $xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'2' );
           }

		$xml_baseImponible = $xml->createElement( "baseImponible",$cabecera['BASEIMPONIBLE'] );
		//$xml_tarifa = $xml->createElement( "tarifa",'0.00' );
		$xml_valor = $xml->createElement( "valor",$cabecera['Total_IVA_NC'] );
		
		$xml_totalImpuesto->appendChild( $xml_codigo );
		$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
		$xml_totalImpuesto->appendChild( $xml_baseImponible );
		//$xml_totalImpuesto->appendChild( $xml_tarifa );
		$xml_totalImpuesto->appendChild( $xml_valor );
		$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
        
        $xml_infoNotaCredito->appendChild( $xml_totalConImpuestos );

        $xml_motivo = $xml->createElement('motivo','Anulacion por Nota de Credito');
	    $xml_infoNotaCredito->appendChild($xml_motivo);	


        $xml_inicio->appendChild($xml_infoNotaCredito);  

		if(count($detalle)>0)
		{
			$xml_detalles = $xml->createElement( "detalles");
			foreach ($detalle as $key => $value) {	    					
	 			$xml_detalle = $xml->createElement( "detalle");

	 			//$CodAdicional = CambioCodigoCtaSup($value["Codigo_Inv"]);

	 			$Producto = trim($this->quitar_carac($value["detalle"]));	 			
                // $SubTotal = ($value["Cantidad"] * $value["Precio"]) - ($value["Total_Desc"] + $value["Total_Desc2"]);

 			  	$xml_codigo = $xml->createElement('codigoInterno',$value["referencia"]);
 			  	$xml_detalle->appendChild($xml_codigo);

 			  	// $xml_codigoAdi = $xml->createElement('codigoAdicional',$CodAdicional);
 			  	// $xml_detalle->appendChild($xml_codigoAdi);

 			  	$xml_descripcion = $xml->createElement('descripcion',$Producto);
 			  	$xml_detalle->appendChild($xml_descripcion);

 			  	$xml_cantidad = $xml->createElement('cantidad',$value["cantidad"]);
 			  	$xml_detalle->appendChild($xml_cantidad);

 			  	$xml_precio = $xml->createElement('precioUnitario',$value["pvp"]);
 			  	$xml_detalle->appendChild($xml_precio);

 			  	$xml_descuento = $xml->createElement('descuento',$value["descuento"]);
 			  	$xml_detalle->appendChild($xml_descuento);

 			  	$xml_sinImpu = $xml->createElement('precioTotalSinImpuesto',$value["total"]-$value["descuento"]);
 			  	$xml_detalle->appendChild($xml_sinImpu);

 			  	$xml_impuestos = $xml->createElement( "impuestos" );
				$xml_impuesto = $xml->createElement( "impuesto" );
				$xml_codigo = $xml->createElement( "codigo",'2' );

					if($value['iva'] == 0)
					{
						$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0' );
						$xml_tarifa = $xml->createElement( "tarifa",'0' );
					}
					else
					{
						if(($cabecera['Porc_IVA']*100) > 12)
						{
							$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'3' );
						}
						else
						{
							$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'2' );
						}
						$xml_tarifa = $xml->createElement( "tarifa",round(($cabecera['Porc_IVA']*100),2) );
						
					}
					$xml_baseImponible = $xml->createElement( "baseImponible",$value['total']-$value['descuento'] );
					$xml_valor = $xml->createElement( "valor",number_format($value['iva'],2,'.','')  );

					$xml_impuesto->appendChild( $xml_codigo );
					$xml_impuesto->appendChild( $xml_codigoPorcentaje );
					$xml_impuesto->appendChild( $xml_tarifa );
					$xml_impuesto->appendChild( $xml_baseImponible );
					$xml_impuesto->appendChild( $xml_valor );
				
					$xml_impuestos->appendChild( $xml_impuesto );
					$xml_detalle->appendChild( $xml_impuestos );
					$xml_detalles->appendChild( $xml_detalle );


	 			$xml_detalles->appendChild($xml_detalle);   		
			}

			$xml_inicio->appendChild($xml_detalles);
		}      
        // $xml_inicio->appendChild($xml_destinatarios);




        //fin de xml retencion
        $xml_infoAdicional = $xml->createElement("infoAdicional");

        if($cabecera['Cliente'] <>'' && $cabecera['Cliente'] <>'.' &&  $cabecera['Razon_Social'] <> $cabecera['Cliente'])
        {
           if( isset($cabecera['Cliente']) &&  strlen($cabecera['Cliente']) > 1){
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['Cliente']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Beneficiario");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}

            if( isset($cabecera['Curso']) &&   strlen($cabecera['Curso']) > 1){
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['Grupo']."-".$cabecera['Curso']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Ubicacion");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
	    }

	    if ( isset($cabecera['DireccionC']) &&  strlen($cabecera['DireccionC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['DireccionC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Direccion");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);

        	}
         if ( isset($cabecera['TelefonoC']) && strlen($cabecera['TelefonoC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['TelefonoC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Telefono");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
         if( isset($cabecera['EmailC']) && strlen($cabecera['EmailC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['EmailC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Email");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
        	$xml_inicio->appendChild($xml_infoAdicional);
        
        	$xml->appendChild($xml_inicio);

        	$ruta_G = dirname(__DIR__).'/entidades/entidad_'.$cabecera['Entidad']."/CE".$cabecera['item'].'/'.$cabecera['carpeta'].'/Generados';
		     // print_r($ruta_G);die();
			if($archivo = fopen($ruta_G.'/'.$cabecera['ClaveAcceso_NC'].'.xml',"w+b"))
			  {
			  	fwrite($archivo,$xml->saveXML());
			  	return 1;
			  }else
			  {
			  	return -1;
			  }
	

		    /* $ruta_G = dirname(__DIR__).'/entidades/entidad_'.generaCeros($entidad,3)."/CE".generaCeros($empresa,3).'/Generados';
		     // print_r($ruta_G);die();
			if($archivo = fopen($ruta_G.'/'.$cabecera['ClaveAcceso_NC'].'.xml',"w+b"))
			  {
			  	fwrite($archivo,$xml->saveXML());
			  	// die();
			  	return 1;
			  }else
			  {
			  	return -1;
			  }*/


	}


	function generar_xml_retencion($cabecera,$impuestosfac,$impuestosret)
	{
		
		$this->crear_carpetas($cabecera['Entidad'],$cabecera['item'],$cabecera['carpeta']);
		$ambiente =$cabecera['ambiente'];
		$RIMPE = $this->tipo_contribuyente($cabecera['ruc_principal']);

		// $sucursal = $this->catalogo_lineas('RE',$cabecera[0]['Serie_R']);
		//  if(count($sucursal)>0)
		//  {
		//  	$cabecera[0]['Nombre_Establecimiento'] = $sucursal[0]['Nombre_Establecimiento'];
		//  	$cabecera[0]['Direccion_Establecimiento'] = $sucursal[0]['Direccion_Establecimiento'];
		//  	$cabecera[0]['Telefono_Establecimiento'] = $sucursal[0]['Telefono_Estab'];
		//  	$cabecera[0]['Ruc_Establecimiento'] = $sucursal[0]['RUC_Establecimiento'];
		//  	$cabecera[0]['Email_Establecimiento'] = $sucursal[0]['Email_Establecimiento'];
		//  	$cabecera[0]['Placa_Vehiculo'] ='.';
		//  	$cabecera[0]['Cta_Establecimiento'] = '.';
		//  	if(isset($sucursal[0]['Placa_Vehiculo']))
		//  	{
		//  		$cabecera[0]['Placa_Vehiculo'] = $sucursal[0]['Placa_Vehiculo'];
		//  	}
		//  	if (isset($sucursal[0]['Cta_Establecimiento'])) {
		//  		$cabecera[0]['Cta_Establecimiento'] = $sucursal[0]['Cta_Establecimiento'];
		//  	}		 	
		//  }

		$carpeta_autorizados = dirname(__DIR__)."/entidades/entidad_".$cabecera['Entidad'].'/CE'.$cabecera['item'].'/'.$cabecera['carpeta']."/Autorizados";		  
		if(file_exists($carpeta_autorizados.'/'.$cabecera['ClaveAcceso'].'.xml'))
		{
			$respuesta = 3;
			return $respuesta;
		}

	    $xml = new DOMDocument( "1.0", "UTF-8");
        $xml->formatOutput = true;
        $xml->preserveWhiteSpace = false;
	    $xml->xmlStandalone = true;

	    $xml_inicio = $xml->createElement( "comprobanteRetencion" );
        $xml_inicio->setAttribute( "id", "comprobante" );
        $xml_inicio->setAttribute( "version", "2.0.0" );
        //informacion de cabecera
	    $xml_infotributaria = $xml->createElement("infoTributaria");
	    $xml_ambiente = $xml->createElement("ambiente",$ambiente);
	    $xml_tipoEmision = $xml->createElement("tipoEmision","1");
	    $xml_razonSocial = $xml->createElement("razonSocial",$cabecera['razon_social_principal']);
	    $xml_nombreComercial = $xml->createElement("nombreComercial",$cabecera['nom_comercial_principal']);
	    $xml_ruc = $xml->createElement("ruc",$cabecera['ruc_principal']);
	    $xml_claveAcceso = $xml->createElement("claveAcceso",$cabecera['ClaveAcceso']);
	    $xml_codDoc = $xml->createElement("codDoc",'07');
	    $xml_estab = $xml->createElement("estab",substr($cabecera['serie_R'], 0,3));
	    $xml_ptoEmi = $xml->createElement("ptoEmi",substr($cabecera['serie_R'], 3,3));
	    $xml_secuencial = $xml->createElement("secuencial",$this->generaCeros($cabecera['retencion'],9));
	    $xml_dirMatriz = $xml->createElement("dirMatriz",$cabecera['direccion_principal']);




        $xml_infotributaria->appendChild($xml_ambiente);
        $xml_infotributaria->appendChild($xml_tipoEmision);
        $xml_infotributaria->appendChild($xml_razonSocial);
        $xml_infotributaria->appendChild($xml_nombreComercial);
        $xml_infotributaria->appendChild($xml_ruc);
        $xml_infotributaria->appendChild($xml_claveAcceso);
        $xml_infotributaria->appendChild($xml_codDoc);
        $xml_infotributaria->appendChild($xml_estab);
        $xml_infotributaria->appendChild($xml_ptoEmi);
        $xml_infotributaria->appendChild($xml_secuencial);
        $xml_infotributaria->appendChild($xml_dirMatriz);

		if(count($RIMPE)>0)
		{
			if($RIMPE['microempresa']!='.' && $RIMPE['microempresa']!='' )
			{
				$xml_contribuyenteRimpe = $xml->createElement( "contribuyenteRimpe",$RIMPE['microempresa']);
				$xml_infotributaria->appendChild( $xml_contribuyenteRimpe);
			}
			if($RIMPE['agente']!='.' && $RIMPE['agente']!='')
			{
				$xml_agenteRetencion = $xml->createElement( "agenteRetencion",'1');
				$xml_infotributaria->appendChild( $xml_agenteRetencion);
			}
		}


        $xml->appendChild($xml_infotributaria);
        $xml_inicio->appendChild($xml_infotributaria);
        //fin de cabecera


	    $xml_infoCompRetencion = $xml->createElement( "infoCompRetencion");
	    $xml_fechaEmision = $xml->createElement( "fechaEmision",date('d/m/Y',strtotime($cabecera['fecha'])));
	    if(isset($cabecera[0]['Nombre_Establecimiento']) &&  strlen($cabecera[0]['Nombre_Establecimiento'])>0 && $cabecera['Nombre_Establecimiento']!='.')
		{
			// si existe otro establecimeinto o sucursal que esta comentadop arriba
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera[0]['Direccion_Establecimiento']);

		}else
		{
			$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",strtoupper($cabecera['direccion_principal']));
		}

	    // $xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",strtoupper($_SESSION['INGRESO']['Direccion']));
	    
	    if($cabecera['obligadoContabilidad']==1)
		{
			$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",'SI' );
		}else
		{

			$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",'NO' );
		}
	    switch ($cabecera['TD']) {
	    	case 'R':
	    		if($cabecera['CI_RUC']=="9999999999999"){$cabecera['TD'] = '07';}else{$cabecera['TD']='04';}
	    		break;
	    	case 'C':
	    		$cabecera['TD'] = '05';
	    		break;
	    	case 'P':
	    		$cabecera['TD'] = '06';
	    		break;
	    }
	    $xml_tipoIdentificacionSujetoRetenido = $xml->createElement( "tipoIdentificacionSujetoRetenido",$cabecera['TD']);

        if($cabecera["PagoLocExt"] == "01"){        	
            $xml_parterel  = $xml->createElement("parteRel",'NO');
          }else{                    
            $xml_tipoSujetoRetenido = $xml->createElement('tipoSujetoRetenido',$cabecera["PagoLocExt"]);
	    	$xml_parterel  = $xml->createElement("parteRel",'SI');
          }

	    $xml_razonSocialSujetoRetenido = $xml->createElement( "razonSocialSujetoRetenido",$cabecera['proveedor']);
	    $xml_identificacionSujetoRetenido = $xml->createElement( "identificacionSujetoRetenido",$cabecera['CI_RUC']);
	    $xml_periodoFiscal = $xml->createElement( "periodoFiscal",date('m/Y',strtotime($cabecera['fecha'])));

    
	    $xml_infoCompRetencion->appendChild($xml_fechaEmision);
	    $xml_infoCompRetencion->appendChild($xml_dirEstablecimiento);
	    // ojo con esto de contribuyente	
	    // if(strlen($ContEspec)>1){
	    // 	$xml_contribuyenteEspecial = $xml->createElement( "contribuyenteEspecial",$ContEspec);
	    //     $xml_infoCompRetencion->appendChild($xml_contribuyenteEspecial);
	    // }
	    $xml_infoCompRetencion->appendChild($xml_obligadoContabilidad);
	    $xml_infoCompRetencion->appendChild($xml_tipoIdentificacionSujetoRetenido);	
	    if($cabecera['PagoLocExt']!='01')
	    { 
	    	$xml_infoCompRetencion->appendChild($xml_tipoSujetoRetenido);   
	    }
	    $xml_infoCompRetencion->appendChild($xml_parterel);
	    $xml_infoCompRetencion->appendChild($xml_razonSocialSujetoRetenido);
	    $xml_infoCompRetencion->appendChild($xml_identificacionSujetoRetenido);
	    $xml_infoCompRetencion->appendChild($xml_periodoFiscal);

        $xml_inicio->appendChild($xml_infoCompRetencion);
        
		// print_r($cabecera);die();
        $Total_Servicio = 0;
        $Total_Propinas = 0;
        $Total_Comision = 0;
        $Total_Sin_No_IVA = 0;
        $Total_Sin_IVA = $cabecera['BaseImponible'];
        $Total_Con_IVA = $cabecera['BaseImpGrav'];
        $Total_IVA = $cabecera['MontoIva'];
        $Total_SubTotal = $Total_Sin_IVA + $Total_Con_IVA;
        $Total_Factura = $Total_SubTotal + $Total_IVA;


		// print_r($impuestosfac);print_r($impuestosret);die();
        $xml_docsSustento = $xml->createElement("docsSustento");
        $xml_docSustento = $xml->createElement("docSustento");

        $xml_codsustento = $xml->createElement("codSustento",'01'); //revisar
        $xml_coddocsustento = $xml->createElement("codDocSustento",'01'); //revisar
        $xml_numdocsustento = $xml->createElement("numDocSustento",$cabecera['serie_F'].$this->generaCeros($cabecera['Factura'],9));
        $xml_fechaemisiondocsustento = $xml->createElement("fechaEmisionDocSustento", date('d/m/Y',strtotime($cabecera['EmisionFac'])));
        $xml_fecharegistrocontable = $xml->createElement("fechaRegistroContable", date('d/m/Y', strtotime($cabecera['VencimientoFac'])));
        $xml_numautodocsustento = $xml->createElement("numAutDocSustento",$cabecera['AutorizacionFac']);
        $xml_pagolocext = $xml->createElement("pagoLocExt",$cabecera['PagoLocExt']);
        $xml_totalsinimpuesto = $xml->createElement("totalSinImpuestos",number_format($Total_SubTotal,2,'.',''));
        $xml_importetotal = $xml->createElement("importeTotal",number_format($Total_Factura,2,'.',''));


         $xml_docSustento->appendChild($xml_codsustento);
         $xml_docSustento->appendChild($xml_coddocsustento);
         $xml_docSustento->appendChild($xml_numdocsustento);
         $xml_docSustento->appendChild($xml_fechaemisiondocsustento);
         $xml_docSustento->appendChild($xml_fecharegistrocontable);
         $xml_docSustento->appendChild($xml_numautodocsustento);
         $xml_docSustento->appendChild($xml_pagolocext);
         $xml_docSustento->appendChild($xml_totalsinimpuesto);
         $xml_docSustento->appendChild($xml_importetotal);




        $xml_impuestodocssustento =$xml->createElement("impuestosDocSustento");

        
        $xml_impuestodocsustento =$xml->createElement("impuestoDocSustento");
        $xml_codimpuestodocsustento = $xml->createElement("codImpuestoDocSustento",'2');
        $xml_codigoprocentaje = $xml->createElement("codigoPorcentaje",'2');
        $xml_baseimponible1 = $xml->createElement("baseImponible", $Total_Con_IVA);
        $xml_tarifa = $xml->createElement("tarifa",$_SESSION['INICIO']['IVA']);
        $xml_valorimpuesto = $xml->createElement("valorImpuesto",$Total_IVA);

        $xml_impuestodocsustento->appendChild($xml_codimpuestodocsustento);
        $xml_impuestodocsustento->appendChild($xml_codigoprocentaje);
        $xml_impuestodocsustento->appendChild($xml_baseimponible1);
        $xml_impuestodocsustento->appendChild($xml_tarifa);
        $xml_impuestodocsustento->appendChild($xml_valorimpuesto);
        $xml_impuestodocssustento->appendChild($xml_impuestodocsustento);

        $xml_impuestodocssustento->appendChild($xml_impuestodocsustento);        


        // print_r($Total_Sin_IVA);die();
        $xml_impuestodocsustento2 =$xml->createElement("impuestoDocSustento");
        $xml_codimpuestodocsustento = $xml->createElement("codImpuestoDocSustento",'2');
        $xml_codigoprocentaje = $xml->createElement("codigoPorcentaje",'0');
        $xml_baseimponible2 = $xml->createElement("baseImponible",$Total_Sin_IVA);
        $xml_tarifa = $xml->createElement("tarifa",'0');
        $xml_valorimpuesto = $xml->createElement("valorImpuesto",'0.00');

        $xml_impuestodocsustento2->appendChild($xml_codimpuestodocsustento);
        $xml_impuestodocsustento2->appendChild($xml_codigoprocentaje);
        $xml_impuestodocsustento2->appendChild($xml_baseimponible2);
        $xml_impuestodocsustento2->appendChild($xml_tarifa);
        $xml_impuestodocsustento2->appendChild($xml_valorimpuesto);

        $xml_impuestodocssustento->appendChild($xml_impuestodocsustento2);


        $xml_retenciones =$xml->createElement("retenciones");

		// print_r($impuestosret);die();
          foreach ($impuestosret as $key => $value) {
          	$xml_retencion =$xml->createElement("retencion");
	        $xml_codigo = $xml->createElement("codigo",'1');
	        $xml_codigoretencion = $xml->createElement("codigoRetencion",$value['codigo_retencion']);
	        $xml_baseimponible3= $xml->createElement("baseImponible",$value['base_imponible']);
	        $xml_porcentajeretencion = $xml->createElement("porcentajeRetener",number_format($value["porcentajeRet"],2,'.',''));
	        $xml_valorretenido = $xml->createElement("valorRetenido",$value['valorRetenido']);

	        $xml_retencion->appendChild($xml_codigo);
	        $xml_retencion->appendChild($xml_codigoretencion);
	        $xml_retencion->appendChild($xml_baseimponible3);
	        $xml_retencion->appendChild($xml_porcentajeretencion);
	        $xml_retencion->appendChild($xml_valorretenido);

	        $xml_retenciones->appendChild($xml_retencion);
	    }



	    foreach ($impuestosfac as $key => $value) 
	    {
	    	
			    if($value['por_bienes']==1)
			    {
			    	$xml_retencion =$xml->createElement("retencion");
			    	switch ($value['porcentajeRet']) {
			    		case '10':  $CodigoA = '9';
			    			break;	    		
			    		case '30': $CodigoA = '1';
			    			break;	    		
			    		case '70': $CodigoA = '2';
			    			break;
			    		case '100': $CodigoA = '3';
			    			break;
			    		default:  $CodigoA = '2';
			    		break;
			    	}
			    	
			    	$Total = $value["base_imponible"];
		            $Retencion = intval($value["porcentajeRet"]);
		            $Valor = number_format(($Total * ($Retencion / 100)), 2);


		            $xml_codigo = $xml->createElement("codigo",'2');
			        $xml_codigoretencion = $xml->createElement("codigoRetencion",$CodigoA);
			        $xml_baseimponible4 = $xml->createElement("baseImponible",$Total);
			        $xml_porcentajeretencion = $xml->createElement("porcentajeRetener",$Retencion);
			        $xml_valorretenido = $xml->createElement("valorRetenido",$Valor);

			        $xml_retencion->appendChild($xml_codigo);
			        $xml_retencion->appendChild($xml_codigoretencion);
			        $xml_retencion->appendChild($xml_baseimponible4);
			        $xml_retencion->appendChild($xml_porcentajeretencion);
			        $xml_retencion->appendChild($xml_valorretenido);

			        $xml_retenciones->appendChild($xml_retencion);

			    }

		     if($value['por_servicios']==1)
		    	{	    	
		    	$xml_retencion =$xml->createElement("retencion");
		    	switch ($value['porcentajeRet']) {	
		    		case '20': $CodigoA1 = '10';
		    			break;
		    		case '30':$CodigoA1 = '1';
		    			break;
		    		case '70':$CodigoA1 = '2';
		    			break;
		    		case '100':$CodigoA1 = '3';
		    			break;
		    		default:  $CodigoA1 = '2';
		    		break;
		    	}
		    	$Total = $value["base_imponible"];
	            $Retencion = intval($value["porcentajeRet"]);
	            $Valor = number_format(($Total * ($Retencion / 100)), 2);

		    	$xml_codigo = $xml->createElement("codigo",'2');
		        $xml_codigoretencion = $xml->createElement("codigoRetencion",$CodigoA1);
		        $xml_baseimponible5 = $xml->createElement("baseImponible",$Total);
		        $xml_porcentajeretencion = $xml->createElement("porcentajeRetener",$Retencion);
		        $xml_valorretenido = $xml->createElement("valorRetenido",$Valor);

		        $xml_retencion->appendChild($xml_codigo);
		        $xml_retencion->appendChild($xml_codigoretencion);
		        $xml_retencion->appendChild($xml_baseimponible5);
		        $xml_retencion->appendChild($xml_porcentajeretencion);
		        $xml_retencion->appendChild($xml_valorretenido);

		        $xml_retenciones->appendChild($xml_retencion);

		    }
		}

        $xml_pagos =$xml->createElement("pagos");
        $xml_pago =$xml->createElement("pago");

        $xml_formapago = $xml->createElement("formaPago",$cabecera['PagoLocExt']);
        $xml_total = $xml->createElement("total",$Total_Factura);

        $xml_pago->appendChild($xml_formapago);
        $xml_pago->appendChild($xml_total);

        $xml_pagos->appendChild($xml_pago);


		$xml_docsSustento->appendChild($xml_docSustento);
		$xml_docSustento->appendChild($xml_impuestodocssustento);
		$xml_docSustento->appendChild($xml_retenciones);
		$xml_docSustento->appendChild($xml_pagos);

		$xml_inicio->appendChild($xml_docsSustento);




        //fin de xml retencion
        $xml_infoAdicional = $xml->createElement("infoAdicional");

       
        if (strlen($cabecera['DireccionC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['DireccionC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Direccion");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);

        	}
         if (strlen($cabecera['TelefonoC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['TelefonoC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Telefono");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}
         if( strlen($cabecera['EmailC']) > 1){ 
        	 $xml_campoAdicional = $xml->createElement("campoAdicional",$cabecera['EmailC']);
        	 $xml_campoAdicional->setAttribute( "nombre", "Email");
        	$xml_infoAdicional->appendChild($xml_campoAdicional);
        	}

        	 $xml_campoAdicional = $xml->createElement("campoAdicional",'CD'.'-'.$this->generaCeros($cabecera['retencion'],9));
        	 $xml_campoAdicional->setAttribute( "nombre", "Comprobante No");
        	 $xml_infoAdicional->appendChild($xml_campoAdicional);

        	$xml_inicio->appendChild($xml_infoAdicional);
        	$xml->appendChild($xml_inicio);

		     $ruta_G = dirname(__DIR__).'/entidades/entidad_'.$cabecera['Entidad']."/CE".$cabecera['item'].'/'.$cabecera['carpeta'].'/Generados';
		     // print_r($ruta_G);die();
			if($archivo = fopen($ruta_G.'/'.$cabecera['ClaveAcceso'].'.xml',"w+b"))
			  {
			  	fwrite($archivo,$xml->saveXML());
			  	return 1;
			  }else
			  {
			  	return -1;
			  }
	
	}


	function Clave_acceso($fecha,$tipo_com,$serie,$numfac,$ambiente,$ruc)
	{
		$Fecha1 = explode("-",$fecha);
		$fechaem=$Fecha1[2].'/'.$Fecha1[1].'/'.$Fecha1[0];
	    $fecha = str_replace('/','',$fechaem);
	    $numfac=$this->generaCeros($numfac, '9');
	    $emi='1';
	    $nume='12345678';
	    $serie = str_replace('-','',$serie);
	    $Clave = $fecha.$tipo_com.$ruc.$ambiente.$serie.$numfac.$nume.$emi;	

	    // print_r($Clave);die();
	    $dig=$this->digito_verificador($Clave);

	    // print_r($Clave.$dig);
	    return $Clave.$dig;
	}

	function Autorizar_factura_o_liquidacion1($parametros)
	{
		// 1 para autorizados
	    //-1 para no autorizados
	    // 2 para devueltas
	    // texto del erro en forma de matris
	    if(!isset($parametros['empresa']))
		{
			$parametros['empresa'] = $_SESSION['INICIO']['ID_EMPRESA'];
		}
		$sql = "SELECT * FROM empresa WHERE id_empresa = '".$parametros['empresa']."'";
		$sql2 ="SELECT * FROM facturas WHERE id_factura = '".$parametros['fac']."'" ;
		$empresa = $this->conn->datos($sql);
		$factura = $this->conn->datos($sql2);


		$cabecera['ambiente']=$empresa[0]['Ambiente'];
	    $cabecera['ruta_ce']=$empresa[0]['Ruta_Certificado'];
	    $cabecera['clave_ce']=$empresa[0]['Clave_Certificado'];
	    $cabecera['nom_comercial_principal']=$this->quitar_carac($empresa[0]['Nombre_Comercial']);
	    $cabecera['razon_social_principal']=$this->quitar_carac($empresa[0]['Razon_Social']);
	    $cabecera['ruc_principal']=$empresa[0]['RUC'];
	    $cabecera['direccion_principal']= $this->quitar_carac($empresa[0]['Direccion']);
	    $cabecera['serie']=$factura[0]['serie'];
	    $cabecera['factura']=$factura[0]['num_factura'];
	    $cabecera['esta']=substr($factura[0]['serie'],0,3); 
	    $cabecera['pto_e']=substr($factura[0]['serie'],4,5); 
	    $cabecera['cod_doc']='01';
	    $cabecera['item']=$parametros['empresa'];
		$cabecera['contribuyenteEspecial'] = $empresa[0]['contribuyenteEspecial'];
		$cabecera['contabilidad'] = $empresa[0]['obligadoContabilidad'];
	    $cabecera['tc']='CD';
	    $link = $this->SRI->links_sri($empresa[0]['Ambiente']);
	    $this->linkSriAutorizacion = $link[0];
	    $this->linkSriRecepcion = $link[1]; 


				//datos de factura
	    		$datos_fac = $this->datos_factura($cabecera['serie'],$cabecera['factura'],$cabecera['tc']);
	    		// print_r($datos_fac);die();
	    	    $cabecera['RUC_CI']=$datos_fac[0]['RUC_CI'];
				$cabecera['Fecha']=$datos_fac[0]['Fecha']->format('Y-m-d');
				$cabecera['Razon_Social']=$this->quitar_carac($datos_fac[0]['Razon_Social']);
				$cabecera['Direccion_RS']=$this->quitar_carac($datos_fac[0]['Direccion_RS']);
				$cabecera['Sin_IVA']= $datos_fac[0]['Sin_IVA'];
				$cabecera['Descuento'] = $datos_fac[0]['Descuento']+$datos_fac[0]['Descuento2'];
				$cabecera['baseImponible'] = $datos_fac[0]['Sin_IVA']+$cabecera['Descuento'];
				$cabecera['Porc_IVA'] = $datos_fac[0]['Porc_IVA'];
				$cabecera['Con_IVA'] = $datos_fac[0]['Con_IVA'];
				$cabecera['Total_MN'] = $datos_fac[0]['Total_MN'];
				if($datos_fac[0]['Forma_Pago'] == '.')
				{
					$cabecera['formaPago']='01';
				}else
				{
					$cabecera['formaPago']=$datos_fac[0]['Forma_Pago'];
				}
				$cabecera['Propina']=$datos_fac[0]['Propina'];
				$cabecera['Autorizacion']=$datos_fac[0]['Autorizacion'];
				$cabecera['Imp_Mes']=$datos_fac[0]['Imp_Mes'];
				$cabecera['SP']=$datos_fac[0]['SP'];
				$cabecera['CodigoC']=$datos_fac[0]['CodigoC'];
				$cabecera['TelefonoC']=$datos_fac[0]['Telefono_RS'];
				$cabecera['Orden_Compra']=$datos_fac[0]['Orden_Compra'];
				$cabecera['baseImponibleSinIva'] = $cabecera['Sin_IVA']-$datos_fac[0]['Desc_0'];
				$cabecera['baseImponibleConIva'] = $cabecera['Con_IVA']-$datos_fac[0]['Desc_X'];
				$cabecera['totalSinImpuestos'] = $cabecera['Sin_IVA']+$cabecera['Con_IVA'] - $cabecera['Descuento'];
				$cabecera['IVA'] = $datos_fac[0]['IVA'];
				$cabecera['descuentoAdicional']=0;
				$cabecera['moneda']="DOLAR";
				$cabecera['tipoIden']='';
				// print_r($cabecera);die();

			//datos de cliente
	    	$datos_cliente = $this->datos_cliente($datos_fac[0]['CodigoC']);
	    	// print_r($datos_cliente);die();
	    	    $cabecera['Cliente']=$this->quitar_carac($datos_cliente[0]['Cliente']);
				$cabecera['DireccionC']=$this->quitar_carac($datos_cliente[0]['Direccion']);
				$cabecera['TelefonoC']=$datos_cliente[0]['Telefono'];
				$cabecera['EmailR']=$this->quitar_carac($datos_cliente[0]['Email2']);
				$cabecera['EmailC']=$this->quitar_carac($datos_cliente[0]['Email']);
				$cabecera['Contacto']=$datos_cliente[0]['Contacto'];
				$cabecera['Grupo']=$datos_cliente[0]['Grupo'];

			//codigo verificador 
				if($cabecera['RUC_CI']=='9999999999999')
				  {
				  	$cabecera['tipoIden']='07';
			      }else
			      {
			      	$cod_veri = $this->digito_verificadorf($datos_fac[0]['RUC_CI'],1);
			      	switch ($cod_veri) {
			      		case 'R':
			      			$cabecera['tipoIden']='04';
			      			break;
			      		case 'C':
			      			$cabecera['tipoIden']='05';
			      			break;
			      		case 'O':
			      			$cabecera['tipoIden']='06';
			      			break;
			      	}
			      }
			    $cabecera['codigoPorcentaje']=0;
			    if((floatval($cabecera['Porc_IVA'])*100)>12)
			    {
			       $cabecera['codigoPorcentaje']=3;
			    }else
			    {
			      $cabecera['codigoPorcentaje']=2;
			    }
			   //detalle de factura
			    $detalle = array();
			    $cuerpo_fac = $this->detalle_factura($cabecera['serie'],$cabecera['factura'],$cabecera['Autorizacion'],$cabecera['tc']);
			    foreach ($cuerpo_fac as $key => $value) 
			    {			    	
			    	$producto = $this->datos_producto($value['Codigo']);
			    	$detalle[$key]['Codigo'] =  $value['Codigo'];
			    	$detalle[$key]['Cod_Aux'] =  $producto[0]['Desc_Item'];
				    $detalle[$key]['Cod_Bar'] =  $producto[0]['Codigo_Barra'];
				    $detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']);
				    $detalle[$key]['Cantidad'] = $value['Cantidad'];
				    $detalle[$key]['Precio'] = $value['Precio'];
				    $detalle[$key]['descuento'] = $value['Total_Desc']+$value['Total_Desc2'];
				  if ($cabecera['Imp_Mes']==true)
				  {
				   	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', '.$value['Ticket'].': '.$value['Mes'].' ';
				  }
				  if($cabecera['SP']==true)
				  {
				  	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', Lote No. '.$value['Lote_No'].
					', ELAB. '.$value['Fecha_Fab'].
					', VENC. '.$value['Fecha_Exp'].
					', Reg. Sanit. '.$value['Reg_Sanitario'].
					', Modelo: '.$value['Modelo'].
					', Procedencia: '.$value['Procedencia'];
				  }
				   $detalle[$key]['SubTotal'] = ($value['Cantidad']*$value['Precio'])-($value['Total_Desc']+$value['Total_Desc2']);
				   $detalle[$key]['Serie_No'] = $value['Serie_No'];
				   $detalle[$key]['Total_IVA'] = number_format($value['Total_IVA'],2);
				   $detalle[$key]['Porc_IVA']= $value['Porc_IVA'];
			    }
			    $cabecera['fechaem']=  date("d/m/Y", strtotime($cabecera['Fecha']));		

			    $linkSriAutorizacion = $_SESSION['INGRESO']['Web_SRI_Autorizado'];
 	    		$linkSriRecepcion = $_SESSION['INGRESO']['Web_SRI_Recepcion'];
			    // print_r($cabecera);print_r($detalle);die();
			    $cabecera['ClaveAcceso'] =$this->Clave_acceso($parametros['Fecha'],$cabecera['cod_doc'],$cabecera['serie'],$parametros['factura'],$cabecera['ambiente'],$cabecera['ruc_principal']);
		
	            
	           $xml = $this->generar_xml($cabecera,$detalle);
	           // die();

	           if($xml==1)
	           {
	           	 $firma = $this->firmar_documento(
	           	 	$cabecera['ClaveAcceso'],
	           	 	$_SESSION['INGRESO']['IDEntidad'],
	           	 	$_SESSION['INGRESO']['item'],
	           	 	$_SESSION['INGRESO']['Clave_Certificado'],
	           	 	$_SESSION['INGRESO']['Ruta_Certificado']);
	           	 if($firma==1)
	           	 {
	           	 	$validar_autorizado = $this->comprobar_xml_sri(
	           	 		$cabecera['ClaveAcceso'],
	           	 		$linkSriAutorizacion);
	           	 	if($validar_autorizado == -1)
			   		 {
			   		 	$enviar_sri = $this->enviar_xml_sri(
			   		 		$cabecera['ClaveAcceso'],
			   		 		$linkSriRecepcion);
			   		 	if($enviar_sri==1)
			   		 	{
			   		 		//una vez enviado comprobamos el estado de la factura
			   		 		$resp =  $this->comprobar_xml_sri($cabecera['ClaveAcceso'],$linkSriAutorizacion);
			   		 		if($resp==1)
			   		 		{
			   		 			$resp = $this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion']);
			   		 			return  $resp;
			   		 		}
			   		 		// print_r($resp);die();
			   		 	}else
			   		 	{
			   		 		return $enviar_sri;
			   		 	}

			   		 }else 
			   		 {
			   		 	// RETORNA SI YA ESTA AUTORIZADO O SI FALL LA REVISIO EN EL SRI
			   			return $validar_autorizado;
			   		 }
	           	 }else
	           	 {
	           	 	//RETORNA SI FALLA AL FIRMAR EL XML
	           	 	return $firma;
	           	 }
	           }else
	           {
	           	//RETORNA SI FALLA EL GENERAR EL XML
	           	return $xml;
	           }

	           // print_r($respuesta);die();
	}

	
	function datos_factura($serie,$fact)
	{
		$sql = "SELECT * From Facturas 
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND TC = 'FA' 
		AND Serie = '".$serie."' 
		AND Factura = ".$fact." AND LEN(Autorizacion) = 13 AND T <> 'A' ";
		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
	     return $datos;
	}

	function datos_cliente($codigo)
	{

		$con = $this->conn->conexion();
		$sql = "SELECT * From Clientes WHERE Codigo = '".$codigo."'";
		
		$datos = $this->conn->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
	        return $datos;

	}

	function detalle_factura($factura,$id_empresa)
	{
		$con = $this->conn->conexion($id_empresa);
		$sql="SELECT * FROM lineas_factura WHERE id_factura = '".$factura."'";
		 return $this->conn->datos($sql);
	}
	function detalle_liquidacion($factura,$id_empresa)
	{
		$con = $this->conn->conexion($id_empresa);
		$sql="SELECT * FROM lineas_liquidacion WHERE id_factura = '".$factura."'";
		 return $this->conn->datos($sql);
	}
	function detalle_guia($guia,$id_empresa)
	{
		$con = $this->conn->conexion($id_empresa);
		$sql="SELECT * FROM lineas_factura WHERE id_guiaRemi = '".$guia."'";
		 return $this->conn->datos($sql);
	}

	function datos_producto($codigo,$id_empresa)
	{
		$con = $this->conn->conexion($id_empresa);
		$sql="SELECT * FROM productos WHERE referencia = '".$codigo."';";
		return $this->conn->datos($sql);
	}

    function digito_verificadorf($ruc,$tipo=null,$pag=null,$idMen=null,$item=null)
    {
		$DigStr = "";
		$VecDig = "";
		$Dig3 = "";
		$sSQLRUC = "";
		$CodigoEmp = "";
		$Producto = "";
		$SumaDig = "";
		$NumDig = "";
		$ValDig = "";
		$TipoModulo = "";
		$CodigoRUC = "";
		$Residuo = "";
		//echo $ruc.' ';
		$Dig3 = substr($ruc, 2, 1);
		//echo $Dig3;
		//$Codigo_RUC_CI = substr($ruc, 0, 10);
		//echo $Dig3.' '.$Codigo_RUC_CI ;
		$Tipo_Beneficiario = "P";
		//$NumEmpresa='001';
		$NumEmpresa=$item;
		//echo $item.' dddvc '.$NumEmpresa;
		$Codigo_RUC_CI = $NumEmpresa . "0000001";
		$Digito_Verificador = "-";
		$RUC_CI = $ruc;
		$RUC_Natural = False;
		//echo $Codigo_RUC_CI;
		//die();
		if($ruc == "9999999999999" )
		{
			$Tipo_Beneficiario = "R";
			$Codigo_RUC_CI = substr($ruc, 0, 10);
			$Digito_Verificador = 9;
			$DigStr = "9";
			//echo ' ccc '.$Codigo_RUC_CI;
			//die();
		}
		else
		{
			$DigStr = $ruc;
			$TipoBenef = "P";
			$VecDig = "000000000";
			$TipoModulo = 1;
			If (is_numeric($ruc) And $ruc <= 0)
			{
				$Codigo_RUC_CI = $NumEmpresa & "0000001";
			}
			Else
			{
				//es cedula
				if(strlen($ruc)==10 and is_numeric($ruc))
				{
					$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
					$arr1 = str_split($ruc);
					$resu = array();
					$resu1=0;
					$coe1=0;
					$pro='';
					$ter='';
					$TipoModulo=10;
					//validador
					$ban=0;
					for($jj=0;$jj<(strlen($ruc));$jj++)
					{
						//echo $arr1[$jj].' -- '.$jj.' cc ';
						//validar los dos primeros registros
						if($jj==0 or $jj==1)
						{
							$pro=$pro.$arr1[$jj];
						}
						if($jj==2)
						{
							$ter=$arr1[$jj];
						}
						//operacion suma
						if($jj<=(strlen($ruc)-2))
						{
							$resu[$jj]=$coe[$jj]*$arr1[$jj];
							if($resu[$jj]>=10)
							{
								$resu[$jj]=$resu[$jj]-9;
							}
							//suma
							$resu1=$resu[$jj]+$resu1;
						}
						//ultimo digito
						if($jj==(strlen($ruc)-1))
						{
							//echo " entro ";
							$coe1=$arr1[$jj];
						}
						
					}
					//verificamos los dos primeros registros
					if($pro>=24)
					{
						//echo "RUC/CI <p style='color:#FF0000;'>incorrecto los dos primeros digitos</p>";
						$ban=1;
					}
					//verificamos el tercer registros
					if($ter>6)
					{
						//echo "RUC/CI <p style='color:#FF0000;'>incorrecto el tercer digito</p>";
						$ban=1;
					}
					//partimos string
					$arr2 = str_split($resu1);
					for($jj=0;$jj<(strlen($resu1));$jj++)
					{
						if($jj==0)
						{
							$arr2[$jj]=$arr2[$jj]+1;
						}
					}
					//aumentamos a la siguiente decena
					$resu2=$arr2[0].'0';
					//resultado del ultimo coeficioente
					$resu3 = $resu2- $resu1;
					$Residuo = $resu1 % $TipoModulo;
					//echo ' dsdsd '.$Residuo;
					//die();
					If ($Residuo == 0)
					{
					  $Digito_Verificador = "0";
					}
					Else
					{
					   $Residuo = $TipoModulo - $Residuo;
					   $Digito_Verificador = $Residuo;
					}
					//echo $Digito_Verificador .' correcto '. substr($ruc, 9, 1);
					if($ban==0)
					{
						If ($Digito_Verificador == substr($ruc, 9, 1))
						{
							$Tipo_Beneficiario = "C";
						}	
					}					
				}
				else
				{
					//caso ruc
					if(strlen($ruc)==13 and is_numeric($ruc))
					{
						//caso ruc ecuatorianos de extrangeros
						$Tipo_Beneficiario='O';
						if ($Dig3 == 6 )
						{
							$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
							$arr1 = str_split($ruc);
							$resu = array();
							$resu1=0;
							$coe1=0;
							$pro='';
							$ter='';
							$TipoModulo=10;
							//validador
							$ban=0;
							for($jj=0;$jj<(count($coe));$jj++)
							{
								//echo $arr1[$jj].' -- '.$jj.' cc ';
								//validar los dos primeros registros
								if($jj==0 or $jj==1)
								{
									$pro=$pro.$arr1[$jj];
								}
								if($jj==2)
								{
									$ter=$arr1[$jj];
								}
								//operacion suma
								if($jj<=(count($coe)-2))
								{
									$resu[$jj]=$coe[$jj]*$arr1[$jj];
									if($resu[$jj]>=10)
									{
										$resu[$jj]=$resu[$jj]-9;
									}
									//suma
									$resu1=$resu[$jj]+$resu1;
								}
								//ultimo digito
								if($jj==(count($coe)-1))
								{
									//echo " entro ";
									$coe1=$arr1[$jj];
								}
								
							}
							//verificamos los dos primeros registros
							if($pro>=24)
							{
								//echo "RUC/CI <p style='color:#FF0000;'>incorrecto los dos primeros digitos</p>";
								$ban=1;
							}
							//verificamos el tercer registros
							if($ter>6)
							{
								//echo "RUC/CI <p style='color:#FF0000;'>incorrecto el tercer digito</p>";
								$ban=1;
							}
							//partimos string
							$arr2 = str_split($resu1);
							for($jj=0;$jj<(strlen($resu1));$jj++)
							{
								if($jj==0)
								{
									$arr2[$jj]=$arr2[$jj]+1;
								}
							}
							//aumentamos a la siguiente decena
							$resu2=$arr2[0].'0';
							//resultado del ultimo coeficioente
							$resu3 = $resu2- $resu1;
							$Residuo = $resu1 % $TipoModulo;
							//echo ' dsdsd '.$Residuo;
							//die();
							If ($Residuo == 0)
							{
							  $Digito_Verificador = "0";
							}
							Else
							{
							   $Residuo = $TipoModulo - $Residuo;
							   $Digito_Verificador = $Residuo;
							}
							//echo $Digito_Verificador .' correcto '. substr($ruc, 9, 1);
							if($ban==0)
							{
								If ($Digito_Verificador == substr($ruc, 9, 1))
								{
									$Tipo_Beneficiario = "R";
									$RUC_Natural = True;
								}	
							}	
						}
						if($Tipo_Beneficiario=='O')
						{
							$TipoModulo = 11;
							//echo $Dig3.' qmm ';
							if (($Dig3 <= 5) and ($Dig3 >= 0))
							{
								$TipoModulo = 10;
								$TipoModulo1=9;
								$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
								$VecDig = "212121212";
								//echo " aquiii 1 ";
							}
							else
							{
								if ($Dig3 == 6)
								{
									$coe = array("3", "2", "7", "6","5", "4", "3", "2");
									$TipoModulo1=8;
									$VecDig = "32765432";
									//echo " aquiii 2 ";
								}
								else
								{
									if($Dig3 == 9)
									{
										$coe = array("4", "3", "2", "7", "6","5", "4", "3", "2");
										$TipoModulo1=9;
										$VecDig = "432765432";
										//echo " aquiii 3 ";/
									}
									else
									{
										$VecDig = "222222222";
										$TipoModulo1=9;
										//echo " aquiii 4 ";
										$coe = array("2", "2", "2", "2", "2","2", "2", "2", "2");
									}
								}
							}
							$arr1 = str_split($ruc);
							$resu = array();
							$resu1=0;
							$coe1=0;
							$pro='';
							$ter='';
							$ban=0;
							for($jj=0;$jj<($TipoModulo1);$jj++)
							{
								if($jj==0 or $jj==1)
								{
									$pro=$pro.$arr1[$jj];
								}
								if($jj==2)
								{
									$ter=$arr1[$jj];
								}
								if($jj<=(strlen($ruc)-2))
								{
									$resu[$jj]=$coe[$jj]*$arr1[$jj];
									If (0 <= $Dig3 And $Dig3 <= 5 And $resu[$jj] > 9)
									{
										$resu[$jj]=$resu[$jj]-9;
									}									
									//suma
									$resu1=$resu[$jj]+$resu1;
									
								}
								if($jj==(strlen($ruc)-1))
								{
									//echo " entro ";
									$coe1=$arr1[$jj];
								}
								
							}
							//partimos string
							$arr2 = str_split($resu1);
							for($jj=0;$jj<(strlen($resu1));$jj++)
							{
								if($jj==0)
								{
									$arr2[$jj]=$arr2[$jj]+1;
								}
							}
							//aumentamos a la siguiente decena
							$resu2=$arr2[0].'0';
							//resultado del ultimo coeficioente
							$resu3 = $resu2- $resu1;
							$Residuo = $resu1 % $TipoModulo;
							If ($Residuo == 0)
							{
							  $Digito_Verificador = "0";
							}
							Else
							{
							   $Residuo = $TipoModulo - $Residuo;
							   $Digito_Verificador = $Residuo;
							}
							//echo $Digito_Verificador.' '.$Dig3.' ';
							If ($Dig3 == 6) 
							{
								If ($Digito_Verificador = substr($ruc, 8, 1)) 
								{
									$Tipo_Beneficiario = "R";
								}
							} 
							Else
							{
								If ($Digito_Verificador == substr($ruc, 9, 1))
								{
									$Tipo_Beneficiario = "R";
								}							
							}
							If ($Dig3 < 6 )
							{
								$RUC_Natural = True;
							}
						}
					}
					else
					{
						if(strlen($ruc)==48 and is_numeric($ruc))
						{
							
						}
					}
				}
			}
			if(substr($ruc, 12, 1)!='1')
			{
				$Tipo_Beneficiario = 'O';
			}
			
		}
	    if($tipo==null OR $tipo==0)
	    {	
		   return $Digito_Verificador;
	    }
	    else
	    {
		   return $Tipo_Beneficiario;
	    }
    } 

    function generaCeros($numero, $tamaño=null)
    {
	   //obtengop el largo del numero
	   $largo_numero = strlen($numero);
	   //especifico el largo maximo de la cadena
	   if($tamaño==null)
	   {
		  $largo_maximo = 7;
	   }
	   else
	   {
		 $largo_maximo = $tamaño;
	   }
	   //tomo la cantidad de ceros a agregar
	   $agregar = $largo_maximo - $largo_numero;
	   //agrego los ceros
	   for($i =0; $i<$agregar; $i++){
	     $numero = "0".$numero;
	   }
	   //retorno el valor con ceros
	   return $numero;
    }

    function digito_verificador($cadena)
    {
    	// print_r($cadena);die();
	    $cadena=trim($cadena);
	    $baseMultiplicador=7;
	    $aux=new SplFixedArray(strlen($cadena));
	    $aux=$aux->toArray();
	    $multiplicador=2;
	    $total=0;
	    $verificador=0;
	    for($i=count($aux)-1;$i>=0;--$i)
	    {
		    $aux[$i]= substr($cadena,$i,1);
		    $aux[$i]*=$multiplicador;
		    ++$multiplicador;
		    if($multiplicador>$baseMultiplicador)
		    {
			    $multiplicador=2;
		    }
		$total+=$aux[$i];
	    }
	    if(($total==0)||($total==1)) $verificador=0;
	    else
	    {
		    $verificador=(11-($total%11)==11)?0:11-($total%11);
	    }
	    if($verificador==10)
	    {
		    $verificador=1;
	    }
	    return $verificador;
    }

    function tipo_documento($ci)
    {
    	if(strlen($ci)==10)
    	{
    		$tipo = 'C';
    	}else if(strlen($ci)==13)
    	{
    		$tipo = 'R';
    	}else
    	{
    		$tipo = 'O';
    	}

    	return array('CI'=>$ci,'Tipo'=>$tipo);
    }


    //parametros clave de acceso
    /*
    1 Fecha de Emisión Numérico             ddmmaaaa       8 Obligatorio <claveAcceso> 
    2 Tipo de Comprobante                   Tabla 3        2 
    3 Número de RUC                         1234567890001  13 
    4 Tipo de Ambiente                      Tabla 4        1 
    5 Serie                                 001001         6 
    6 Número del Comprobante (secuencial)   000000001      9 
    7 Código Numérico                       Numérico       8 
    8 Tipo de Emisión                       Tabla 2        1 
    9 Dígito Verificador (módulo 11 )       Numérico       1*/
	function generar_xml($cabecera,$detalle)
	{
			$RIMPE =  $this->tipo_contribuyente($cabecera['ruc_principal']);
	   	    $entidad=$cabecera['Entidad']; //cambiar por la entidad
		    $empresa=$cabecera['item'];
		    $numero=$this->generaCeros($cabecera['factura'], '9');
		    $ambiente=$cabecera['ambiente'];
		    $codDoc=$cabecera['cod_doc'];
		    $compro = $cabecera['ClaveAcceso'];

		    // print_r($cabecera);die();
		    $this->crear_carpetas($cabecera['Entidad'],$cabecera['item'],$cabecera['carpeta']);

		    $carpeta_autorizados = dirname(__DIR__)."/entidades/entidad_".$cabecera['Entidad'].'/CE'.$cabecera['item'].'/'.$cabecera['carpeta']."/Autorizados";		  
			if(file_exists($carpeta_autorizados.'/'.$cabecera['ClaveAcceso'].'.xml'))
			{
				$respuesta = array('0'=>3);
				if($cabecera['cod_doc']=='01')
				{
				$this->actualizar_datos_CE($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],'A',$_SESSION['INICIO']['ID_EMPRESA']);
				}else
				{
					$this->actualizar_datos_LC($cabecera['ClaveAcceso'],$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['Entidad'],$cabecera['Autorizacion'],'A',$_SESSION['INICIO']['ID_EMPRESA']);
				}
				return $respuesta;
			}
			// "Create" the document.
			$xml = new DOMDocument( "1.0", "UTF-8" );
			$xml->formatOutput = true;
			$xml->preserveWhiteSpace = false; 

			// Create some elements.
			switch ($codDoc) {
				case '01':
					$xml_factura = $xml->createElement( "factura" );
					break;
				case '07':
					$xml_factura = $xml->createElement( "comprobanteRetencion" );
					break;
				case '03':
					$xml_factura = $xml->createElement( "liquidacionCompra" );
					break;
				case '04':
					$xml_factura = $xml->createElement( "notaCredito" );
					break;
				case '05':
					$xml_factura = $xml->createElement( "notaDebito" );
					break;
				case '06':
					$xml_factura = $xml->createElement( "guiaRemision" );
					break;
				
				
			}
			
			$xml_factura->setAttribute( "id", "comprobante" );
			$xml_factura->setAttribute( "version", "1.1.0" );
			$xml_infoTributaria = $xml->createElement( "infoTributaria" );
			$xml_ambiente = $xml->createElement( "ambiente",$ambiente );
			$xml_tipoEmision = $xml->createElement( "tipoEmision",'1' );
			$xml_razonSocial = $xml->createElement( "razonSocial",$cabecera['razon_social_principal']);
			$xml_nombreComercial = $xml->createElement( "nombreComercial",$cabecera['nom_comercial_principal'] );
			$xml_ruc = $xml->createElement( "ruc",$cabecera['ruc_principal'] );
			$xml_claveAcceso = $xml->createElement( "claveAcceso",$compro);
			$xml_codDoc = $xml->createElement( "codDoc",$codDoc );
			$xml_estab = $xml->createElement( "estab",$cabecera['esta'] );
			$xml_ptoEmi = $xml->createElement( "ptoEmi",$cabecera['pto_e'] );
			$xml_secuencial = $xml->createElement( "secuencial",$numero );
			$xml_dirMatriz = $xml->createElement( "dirMatriz",$cabecera['direccion_principal'] );
			// $xml_contribuyenteRimpe = $xml->createElement( "contribuyenteRimpe",'CONTRIBUYENTE RÉGIMEN RIMPE' );
				
			$xml_agenteRetencion = $xml->createElement( "agenteRetencion",'1');
				
			$xml_infoTributaria->appendChild( $xml_ambiente );
			$xml_infoTributaria->appendChild( $xml_tipoEmision );
			$xml_infoTributaria->appendChild( $xml_razonSocial );
			$xml_infoTributaria->appendChild( $xml_nombreComercial );
			$xml_infoTributaria->appendChild( $xml_ruc );
			$xml_infoTributaria->appendChild( $xml_claveAcceso );
			$xml_infoTributaria->appendChild( $xml_codDoc );
			$xml_infoTributaria->appendChild( $xml_estab );
			$xml_infoTributaria->appendChild( $xml_ptoEmi );
			$xml_infoTributaria->appendChild( $xml_secuencial );
			$xml_infoTributaria->appendChild( $xml_dirMatriz );
			if(count($RIMPE)>0)
			{
				// print_r($RIMPE);die();
				if($RIMPE['microempresa']!='.' && $RIMPE['microempresa']!='' && $RIMPE['microempresa']=='CONTRIBUYENTE RÉGIMEN RIMPE')
				{
					$xml_contribuyenteRimpe = $xml->createElement( "contribuyenteRimpe",$RIMPE['microempresa']);
					$xml_infoTributaria->appendChild( $xml_contribuyenteRimpe);
				}
				if($RIMPE['agente']!='.' && $RIMPE['agente']!='')
				{
					$xml_agenteRetencion = $xml->createElement( "agenteRetencion",'1');
					$xml_infoTributaria->appendChild( $xml_agenteRetencion);
				}
			}


			$xml_infoFactura = $xml->createElement( "infoFactura" );
			if($codDoc=='03')
			{
				$xml_infoFactura = $xml->createElement( "infoLiquidacionCompra" );
		    }

			$xml_fechaEmision = $xml->createElement( "fechaEmision",$cabecera['fechaem'] );
			if($cabecera['Direccion_RS']=='.')
			{
				$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera['direccion_principal']);
			}
			else
			{
				$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera['Direccion_RS'] );
			}

			if($cabecera['contabilidad']==1)
			{
				$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",'SI' );
			}else
			{

				$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",'NO' );
			}

			$xml_tipoIdentificacionComprador = $xml->createElement( "tipoIdentificacionComprador",$cabecera['tipoIden'] );
			$xml_razonSocialComprador = $xml->createElement("razonSocialComprador",$cabecera['Razon_Social_Comp']);
			$xml_identificacionComprador = $xml->createElement( "identificacionComprador",$cabecera['RUC_CI'] );
			$xml_totalSinImpuestos = $xml->createElement( "totalSinImpuestos",$cabecera['totalSinImpuestos'] );
			$xml_totalDescuento = $xml->createElement( "totalDescuento",$cabecera['Descuento']);

			if($codDoc=='03')
			{
				$xml_tipoIdentificacionComprador = $xml->createElement( "tipoIdentificacionProveedor",$cabecera['tipoIden'] );
				$xml_razonSocialComprador = $xml->createElement( "razonSocialProveedor",$cabecera['Razon_Social_Comp'] );
				$xml_identificacionComprador = $xml->createElement( "identificacionProveedor",$cabecera['RUC_CI'] );		
				
			}

			$xml_infoFactura->appendChild( $xml_fechaEmision );
			$xml_infoFactura->appendChild( $xml_dirEstablecimiento );
			$xml_infoFactura->appendChild( $xml_obligadoContabilidad );
			$xml_infoFactura->appendChild( $xml_tipoIdentificacionComprador );
			$xml_infoFactura->appendChild( $xml_razonSocialComprador );
			$xml_infoFactura->appendChild( $xml_identificacionComprador );
			$xml_infoFactura->appendChild( $xml_totalSinImpuestos );
			$xml_infoFactura->appendChild( $xml_totalDescuento );

			$xml_totalConImpuestos = $xml->createElement( "totalConImpuestos" );
			//sin iva
			$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );
			$xml_codigo = $xml->createElement( "codigo",'2' );
			$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0'  );
			$xml_descuentoAdicional = $xml->createElement( "descuentoAdicional",$cabecera['descuentoAdicional']);
			$xml_baseImponible = $xml->createElement( "baseImponible",number_format($cabecera['baseImponibleSinIva'],2,'.','') );
			//$xml_tarifa = $xml->createElement( "tarifa",'0.00' );
			$xml_valor = $xml->createElement( "valor",'0.00' );
			
			$xml_totalImpuesto->appendChild( $xml_codigo );
			$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
			$xml_totalImpuesto->appendChild( $xml_descuentoAdicional );
			$xml_totalImpuesto->appendChild( $xml_baseImponible );
			//$xml_totalImpuesto->appendChild( $xml_tarifa );
			$xml_totalImpuesto->appendChild( $xml_valor );
			$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
			// print_r($cabecera['Con_IVA']);die();
			if(($cabecera['Con_IVA']) > 0)
			{
				// print_r($cabecera['codigoPorcentaje']);die();
				$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );
				$xml_codigo = $xml->createElement( "codigo",'2' );
				$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",$cabecera['codigoPorcentaje'] );
				$xml_descuentoAdicional = $xml->createElement( "descuentoAdicional",number_format($cabecera['descuentoAdicional'],2,'.','') );
				$xml_baseImponible = $xml->createElement( "baseImponible",number_format($cabecera['baseImponibleConIva'],2,'.','') );
				$xml_tarifa = $xml->createElement( "tarifa",number_format(($cabecera['Porc_IVA']*100),2,'.','') );
				$xml_valor = $xml->createElement( "valor",$cabecera['IVA']);
				
				$xml_totalImpuesto->appendChild( $xml_codigo );
				$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
				$xml_totalImpuesto->appendChild( $xml_descuentoAdicional );
				$xml_totalImpuesto->appendChild( $xml_baseImponible );
				$xml_totalImpuesto->appendChild( $xml_tarifa );
				$xml_totalImpuesto->appendChild( $xml_valor );
				
				$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
			}
			$xml_infoFactura->appendChild( $xml_totalConImpuestos );
			if($codDoc=='01')
			{
				$xml_propina = $xml->createElement( "propina",number_format($cabecera['Propina'],2,'.','') );		
				$xml_infoFactura->appendChild( $xml_propina );
			}

			$xml_importeTotal = $xml->createElement( "importeTotal",$cabecera['Total_MN'] );
			$xml_moneda = $xml->createElement( "moneda",$cabecera['moneda'] );

			$xml_pagos = $xml->createElement("pagos");
			$xml_pago = $xml->createElement("pago");
			   $xml_formapago = $xml->createElement( "formaPago",$cabecera['formaPago']);
			   $xml_total = $xml->createElement( "total",$cabecera['Total_MN']);
			   $xml_pago->appendChild( $xml_formapago );
			   $xml_pago->appendChild($xml_total);

			   $xml_pagos->appendChild($xml_pago);


			$xml_infoFactura->appendChild( $xml_importeTotal );
			$xml_infoFactura->appendChild( $xml_moneda );
			$xml_infoFactura->appendChild( $xml_pagos );


			$xml_detalles = $xml->createElement( "detalles");

			// print_r($detalle);die();
			foreach ($detalle as $key => $value) {
				if($value['Cod_Bar'] !='' or $value['Codigo']!='')
				{
					$xml_detalle = $xml->createElement( "detalle" );
					if($cabecera['SP']==true)
					{
						if(strlen($value['Cod_Bar'])>1)
						{
							$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$value['Cod_Bar'] );
						}
						$xml_detalle->appendChild( $xml_codigoPrincipal );
						if(strlen($detalle[$i]['Cod_Aux'])>1)
						{
							$xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$value['Cod_Aux'] );
						}
						else
						{
							$xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$value['Codigo'] );
						}
						$xml_detalle->appendChild( $xml_codigoAuxiliar );

					}else
					{

						$cod_au = str_replace('.','', $value['Codigo']);
						$cod =explode('.', $value['Codigo']);
							$num_partes = count($cod);
							$val_cod = '';
							for ($i=0; $i <$num_partes-1 ; $i++) { 
								$val_cod.= $cod[$i].'.';
								$val_cod = substr($val_cod,0,-1);
							}

						if(strlen($value['Cod_Aux'])>1)
						{
							$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$value['Cod_Aux'] );
						}
						else
						{					
							$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$value['Codigo']);
						}
						$xml_detalle->appendChild( $xml_codigoPrincipal );
						// if(strlen($value['Cod_Bar'])>1)
						// {
							// $xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$val_cod);
							// $xml_detalle->appendChild( $xml_codigoAuxiliar );
						// }
					}

					$xml_descripcion = $xml->createElement( "descripcion",$value['Producto'] );
					$xml_unidadMedida = $xml->createElement( "unidadMedida",$cabecera['moneda'] );
					$xml_cantidad = $xml->createElement( "cantidad",$value['Cantidad'] );
					$xml_precioUnitario = $xml->createElement( "precioUnitario",number_format($value['Precio'],6,'.','') );
					$xml_descuento = $xml->createElement( "descuento",number_format($value['descuento'],2,'.','') );				
					$xml_precioTotalSinImpuesto = $xml->createElement( "precioTotalSinImpuesto",number_format(floatval($value['SubTotal']),2,'.','') );
					
					$xml_detalle->appendChild( $xml_codigoPrincipal );
					
					$xml_detalle->appendChild( $xml_descripcion );
					$xml_detalle->appendChild( $xml_unidadMedida );
					$xml_detalle->appendChild( $xml_cantidad );
					$xml_detalle->appendChild( $xml_precioUnitario );
					$xml_detalle->appendChild( $xml_descuento );
					$xml_detalle->appendChild( $xml_precioTotalSinImpuesto );
					if(strlen($value['Serie_No'])>1)
					{
						$detallesAdicionales = $xml->createElement( "detallesAdicionales" );
						$detAdicional = $xml->createElement( "detAdicional" );
						$detAdicional->setAttribute( "nombre", "Serie_No" );
						$detAdicional->setAttribute( "valor", $value['Serie_No'] );
						$detallesAdicionales->appendChild( $detAdicional );
						$xml_detalle->appendChild( $detallesAdicionales );
					}
					$xml_impuestos = $xml->createElement( "impuestos" );
					$xml_impuesto = $xml->createElement( "impuesto" );
					$xml_codigo = $xml->createElement( "codigo",'2' );

					if($value['Total_IVA'] == 0)
					{
						$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0' );
						$xml_tarifa = $xml->createElement( "tarifa",'0' );
					}
					else
					{
						 $val_iva = ($value['Porc_IVA']*100);
					    $sql3 = "SELECT * FROM porcentaje_iva WHERE porcentaje = ".$val_iva." AND '".date('Y-m-d')."' between fecha and fechafin";
						$iva_porc = $this->conn->datos($sql3);

						// print_r($iva_porc);die();
						if(count($iva_porc)>0)
						{
							$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",$iva_porc[0]['codigo']);
						}
						$xml_tarifa = $xml->createElement( "tarifa",number_format(($value['Porc_IVA']*100),2,'.','') );
						
					}
					$xml_baseImponible = $xml->createElement( "baseImponible",number_format($value['SubTotal'],2,'.','') );
					$xml_valor = $xml->createElement( "valor",number_format($value['Total_IVA'],2,'.','')  );
					$xml_impuesto->appendChild( $xml_codigo );
					$xml_impuesto->appendChild( $xml_codigoPorcentaje );
					$xml_impuesto->appendChild( $xml_tarifa );
					$xml_impuesto->appendChild( $xml_baseImponible );
					$xml_impuesto->appendChild( $xml_valor );
				
					$xml_impuestos->appendChild( $xml_impuesto );
					$xml_detalle->appendChild( $xml_impuestos );
					$xml_detalles->appendChild( $xml_detalle );
				}
			}
			$xml_infoAdicional = $xml->createElement( "infoAdicional");
			//agregar informacion por default
				// $xml_campoAdicional = $xml->createElement( "campoAdicional",'.' );
				// $xml_campoAdicional->setAttribute( "nombre", "adi" );
				// $xml_infoAdicional->appendChild( $xml_campoAdicional );
			if($cabecera['Cliente']<>'.' AND ($cabecera['Cliente']!=$cabecera['Razon_Social_Comp']))
			{
				if(strlen($cabecera['Cliente'])>1)
				{
					$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Cliente'] );
					$xml_campoAdicional->setAttribute( "nombre", "Beneficiario" );
					$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Grupo'] );
					$xml_campoAdicional->setAttribute( "nombre", "Ubicacion" );
					$xml_infoAdicional->appendChild( $xml_campoAdicional );
				}
			}
			if(strlen($cabecera['DireccionC'])>1)
			{
				$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['DireccionC'] );
				$xml_campoAdicional->setAttribute( "nombre", "Direccion" );
				$xml_infoAdicional->appendChild( $xml_campoAdicional );
			}
			if(strlen($cabecera['TelefonoC'])>1)
			{
				$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['TelefonoC'] );
				$xml_campoAdicional->setAttribute( "nombre", "Telefono" );
				$xml_infoAdicional->appendChild( $xml_campoAdicional );
			}
			if(strlen($cabecera['EmailC'])>1)
			{
				$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['EmailC'] );
				$xml_campoAdicional->setAttribute( "nombre", "Email" );
				$xml_infoAdicional->appendChild( $xml_campoAdicional );
			}
			if(strlen($cabecera['EmailR'])>1)
			{
				$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['EmailR'] );
				$xml_campoAdicional->setAttribute( "nombre", "Email2" );
				$xml_infoAdicional->appendChild( $xml_campoAdicional );
			}
			if(strlen($cabecera['Contacto'])>1)
			{
				$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Contacto'] );
				$xml_campoAdicional->setAttribute( "nombre", "Referencia" );
				$xml_infoAdicional->appendChild( $xml_campoAdicional );
			}
			if(strlen($cabecera['Orden_Compra'])>1)
			{
				$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Orden_Compra'] );
				$xml_campoAdicional->setAttribute( "nombre", "ordenCompra" );
				$xml_infoAdicional->appendChild( $xml_campoAdicional );
			}
			
			$xml_factura->appendChild( $xml_infoTributaria );
			$xml_factura->appendChild( $xml_infoFactura );
			$xml_factura->appendChild( $xml_detalles );
			$xml_factura->appendChild( $xml_infoAdicional );


			$xml->appendChild($xml_factura);

			$ruta_G = dirname(__DIR__).'/entidades/entidad_'.$cabecera['Entidad']."/CE".$cabecera['item'].'/'.$cabecera['carpeta'].'/Generados';
			if($archivo = fopen($ruta_G.'/'.$compro.'.xml',"w+b"))
			  {
			  	fwrite($archivo,$xml->saveXML());
			  	 
			  	 return 1;
			  }else
			  {
			  	// print_r('sss')
			  	return -1;
			  }	
	}


  
  function firmar_documento($nom_doc,$entidad,$empresa,$pass,$p12,$carpeta)
    {	

 	    $firmador = dirname(__DIR__).'/SRI/firmar/firmador.jar';
 	    $url_generados=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/'.$carpeta.'/Generados/';
 	    $url_firmados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/'.$carpeta.'/Firmados/';
 	    $url_rechazado =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/'.$carpeta.'/Rechazados/';
 	    $certificado_1 = dirname(__DIR__).'/certificados/';
 	    // print_r($certificado_1.$p12);die();
 	    if(file_exists($certificado_1.$p12))
	    {
	       	// print_r("java -jar ".$firmador." ".$nom_doc.".xml ".$url_generados." ".$url_firmados." ".$certificado_1." ".$p12." ".$pass);die();
	       	 $command = $this->JDK8."java -jar ".$firmador." ".$nom_doc.".xml ".$url_generados." ".$url_firmados." ".$certificado_1." ".$p12." ".$pass;

	       	 // print_r($command);die();

	        	exec($command, $f);

	        	// print_r($f);die();
	        	if(count($f)<6)
		 		{
		 			return  array('resp'=>1,'msj'=>'documento firmado');		 		
		 		}else
		 		{		 			
		 			$respuesta = array('resp'=>-1,'msj'=>'No se pudo firmar el xml');
		 			return $respuesta;          
		        }

	 	   }else
	 	   {
	 	   		$respuesta = array('resp'=>-1,'msj'=>'No se han encontrado Certificados');
	 			return $respuesta;
	 	   } 		
    }

    //comprueba si el xml ya se envio al sri
    // 1 para autorizados
    //-1 para no autorizados
    // 0 para devueltas o no autorizadas
    function comprobar_xml_sri($clave_acceso,$link_autorizacion,$carpeta)
    {
    	$entidad =$_SESSION['INICIO']['ID_EMPRESA'];
    	$empresa =$_SESSION['INICIO']['ID_EMPRESA'];
    	$comprobar_sri = dirname(__DIR__).'/SRI/firmar/JavClientSri.jar';
    	$url_autorizado=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/'.$carpeta.'/Autorizados/';
 	    $url_No_autorizados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/'.$carpeta.'/No_autorizados/';

    	 // print_r($this->JDK8." java -jar ".$comprobar_sri." 2 ".$clave_acceso." ".$url_autorizado." ".$url_No_autorizados." ".$link_autorizacion);
    	 // die();

   		$comprobado = true;
 	    $output = '';
 	    $veces_envio = 1;
 	    while ($comprobado) {


   		 	$command = $this->JDK8."java -jar ".$comprobar_sri." 2 ".$clave_acceso." ".$url_autorizado." ".$url_No_autorizados." ".$link_autorizacion; 
   		  // print_r($command);die();
	   		
	   		$output = shell_exec($command);   
	   		// print_r($output);die();		
	   		$output = mb_convert_encoding($output, 'UTF-8', 'ISO-8859-1');
			$output = json_decode($output,true); // <== para que la respuesta se haga un array
			if($output[2]=='AUTORIZADO' || $veces_envio>=3)
			{
				$comprobado = false;
			}	
			$veces_envio = $veces_envio+1;
 	    }  		 
   		
   		return $output;


    }

    //envia el xml asia el sri
    function enviar_xml_sri($clave_acceso,$url_recepcion,$carpeta)
    {
    	$entidad = $_SESSION['INICIO']['ID_EMPRESA'];
    	$empresa = $_SESSION['INICIO']['ID_EMPRESA'];

    	$ruta_firmados=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/'.$carpeta.'/Firmados/';
    	$ruta_enviados=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/'.$carpeta.'/Enviados/';
 	    $ruta_rechazados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE".$empresa.'/'.$carpeta.'/Rechazados/';
    	$enviar_sri = dirname(__DIR__).'/SRI/firmar/JavClientSri.jar';
    	if(!file_exists($ruta_firmados.$clave_acceso.'.xml'))
    	{
    		$respuesta = array("0"=>'-1','1'=>"",'2'=>"",'3'=> ' XML firmado no encontrado');
	 		return $respuesta;
    	}
		
		$command = $this->JDK8."java -jar ".$enviar_sri." 1 ".$clave_acceso." ".$ruta_firmados." ".$ruta_enviados." ".$ruta_rechazados." ".$url_recepcion; 
   		 // print_r($command);die();

   		$output = shell_exec($command);
   		// print_r($output);die();
   		$output = mb_convert_encoding($output, 'UTF-8', 'ISO-8859-1');
   		$output = json_decode($output,true); 	   		
   		return $output;

    }

    // function datos_rimpe($ruc)
    // {
    // 	$sql = "SELECT * FROM lista_tipo_contribuyente WHERE RUC ='".$ruc."'";
    // 	// print_r($sql);die();
    // 	return $this->conn->datos($sql,$_SESSION['INICIO']['ID_EMPRESA']);
    // }
    function actualizar_datos_CE($autorizacion,$tc,$serie,$factura,$entidad,$autorizacion_ant,$estado,$id_empresa)
    {
			$con = $this->conn->conexion($id_empresa);
			$sql ="UPDATE facturas SET Autorizacion='".$autorizacion."',Clave_Acceso='".$autorizacion."',estado_factura='".trim($estado)."' WHERE 
			serie = '".$serie."' 
			AND num_factura = ".$factura." ";
			// print_r($sql);die();
			$resp = $this->conn->sql_string($sql);
			return $resp;
    }
    function actualizar_datos_LC($autorizacion,$tc,$serie,$factura,$entidad,$autorizacion_ant,$estado,$id_empresa)
    {
			$con = $this->conn->conexion($id_empresa);
			$sql ="UPDATE liquidacion_compras SET Autorizacion='".$autorizacion."',Clave_Acceso='".$autorizacion."',estado_factura='".$estado."' WHERE 
			serie = '".$serie."' 
			AND num_factura = ".$factura." ";
			// print_r($sql);die();
			$resp = $this->conn->sql_string($sql,$id_empresa);
			return $resp;
    }

    function actualizar_datos_RET($autorizacion,$tc,$serie,$factura,$entidad,$autorizacion_ant,$estado,$id_empresa)
    {
			$con = $this->conn->conexion($id_empresa);
			$sql ="UPDATE retenciones SET autorizacion='".$autorizacion."',estadoRet='".$estado."' WHERE 
			serie = '".$serie."' 
			AND numero = ".$factura." and id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."' ";
			// print_r($sql);die();
			$resp = $this->conn->sql_string($sql,$id_empresa);
			return $resp;
    }

    function actualizar_datos_GR($autorizacion,$tc,$serie,$remision,$estado,$id_empresa)
    {
			$con = $this->conn->conexion($id_empresa);
			$sql ="UPDATE guia_remision SET Autorizacion_GR='".$autorizacion."',Clave_Acceso_GR='".$autorizacion."',estado='".$estado."' WHERE 
			Serie_GR = '".$serie."' 
			AND Remision = ".$remision." 
			and id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."' ";
			// print_r($sql);die();
			$resp = $this->conn->sql_string($sql,$id_empresa);
			return $resp;
    }

    function actualizar_datos_NC($autorizacion,$serie,$nota_credito,$estado,$id_empresa)
    {
			$con = $this->conn->conexion($id_empresa);
			$sql ="UPDATE nota_credito SET autorizacion_nc='".$autorizacion."',estado='".$estado."' WHERE 
			serie_nc = '".$serie."' 
			AND  numero_nc = '".$nota_credito."'
			AND empresa = '".$id_empresa."'"; 
			// print_r($sql);die();
			$resp = $this->conn->sql_string($sql,$id_empresa);
			return $resp;
    }

    function actualizar_saldo_empresa()
    {
		$id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
		$con = $this->conn->conexion($id_empresa);
		$sql = "SELECT * FROM EMPRESAS WHERE id_empresa = '".$id_empresa."'";
		$empresa = $this->conn->datos($sql,$id_empresa);
		if(count($empresa)>0)
		{
			if($empresa[0]['pvpDoc']!='.')
			{
				$saldo_actual =  floatval($empresa[0]['Plan']);
				$pvp_actual =  floatval($empresa[0]['pvpDoc']);
				$saldo_nuevo = $saldo_actual-$pvp_actual;
				$sql ="UPDATE empresa SET Plan='".$saldo_nuevo."' WHERE  id_empresa = '".$id_empresa."'"; 
				// print_r($sql);die();
				$resp = $this->conn->sql_string($sql,$id_empresa);
				// return $resp;
			}
		}
			
    }


    function quitar_carac($query)
    {
    	$query = preg_replace("[\n|\r|\n\r]", "", $query);
    	$buscar = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú','Ñ','ñ','/','?','�','-','&');
    	$remplaza = array('a','e','i','o','u','A','E','I','O','U','N','n','','','','','Y');
    	$corregido = str_replace($buscar, $remplaza, $query);
    	 // print_r($corregido);
    	return $corregido;

    }

    function tipo_contribuyente($RUC)
    {
    	$AgenteRetencion = '.';
    	$MicroEmpresa = '.';
   	    $ContribuyenteEspecial = 0;
		$RimpeE = 0;
		$RimpeP = 0;
    	$sql = "SELECT * FROM lista_tipo_contribuyente WHERE RUC = '".$RUC."'";
    	$datos = $this->conn->datos($sql);
    	if(count($datos)>0)
    	{
    		if($datos[0]['RIMPE_P'] == 1){ 
		       $MicroEmpresa = 'Regimen RIMPE Negocios Populares';
			}		
			if($datos[0]['RIMPE_E'] == 1)
			{
			   $MicroEmpresa = 'CONTRIBUYENTE RÉGIMEN RIMPE';
			} 
			if($datos[0]['Contribuyente_Especial'] == 1){ 
			   $AgenteRetencion = 'NAC-DNCRASC20-CE-00000001';
			}
			if($datos[0]['regimen_general'] == 1){ 
			   $MicroEmpresa = 'CONTRIBUYENTE RÉGIMEN GENERAL';
			}

    	}

    	return array('agente'=>$AgenteRetencion,'microempresa'=> $MicroEmpresa);

    		
    }


    function crear_carpetas($entidad,$empresa,$carpeta)
    {
        //verificamos si existe una carpeta de la entidad si no existe las creamos
	    $carpeta_entidad = dirname(__DIR__)."/entidades/entidad_".$entidad;
	    $carpeta_autorizados = "";		  
        $carpeta_generados = "";
        $carpeta_firmados = "";
        $carpeta_no_autori = "";
		if(file_exists($carpeta_entidad))
		{
			$carpeta_comprobantes = $carpeta_entidad.'/CE'.$empresa.'/'.$carpeta;
			if(file_exists($carpeta_comprobantes))
			{
			  $carpeta_autorizados = $carpeta_comprobantes."/Autorizados";		  
			  $carpeta_generados = $carpeta_comprobantes."/Generados";
			  $carpeta_firmados = $carpeta_comprobantes."/Firmados";
			  $carpeta_no_autori = $carpeta_comprobantes."/No_autorizados";
			  $carpeta_rechazados = $carpeta_comprobantes."/Rechazados";
			  $carpeta_rechazados = $carpeta_comprobantes."/Enviados";

				if(!file_exists($carpeta_autorizados))
				{
					mkdir($carpeta_entidad."/CE".$empresa.'/'.$carpeta."/Autorizados", 0777);
				}
				if(!file_exists($carpeta_generados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/Generados', 0777);
				}
				if(!file_exists($carpeta_firmados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/Firmados', 0777);
				}
				if(!file_exists($carpeta_no_autori))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/No_autorizados', 0777);
				}
				if(!file_exists($carpeta_rechazados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/Rechazados', 0777);
				}
				if(!file_exists($carpeta_rechazados))
				{
					 mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/Enviados', 0777);
				}
			}else
			{
				mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta, 0777);
				mkdir($carpeta_entidad."/CE".$empresa.'/'.$carpeta."/Autorizados", 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/Generados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/Firmados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/No_autorizados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/Rechazados', 0777);
			    mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/Enviados', 0777);
			}
		}else
		{
			   mkdir($carpeta_entidad, 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa, 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta, 0777);
			   mkdir($carpeta_entidad."/CE".$empresa.'/'.$carpeta."/Autorizados", 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/Generados', 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/Firmados', 0777);
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/No_autorizados', 0777);	  
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/Rechazados', 0777);  
			   mkdir($carpeta_entidad.'/CE'.$empresa.'/'.$carpeta.'/Enviados', 0777);
		}
    }

    function recuperar_xml_a_factura($documento)
	{

		$respuesta = 1;
		//busco el archivo xml
		$ruta_G = dirname(__DIR__,2).'/TEMP/XMLS';
		// print_r($ruta_G);die();
		
		$texto = file_get_contents($ruta_G.'/'.$documento);
		
		$texto = str_replace('EN PROCESO','nulo', $texto,$remplazado);
		if($remplazado>0)
		{
			return -2;
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

			if(isset($detalle['codigo']))
			{
				$detalle = array($detalle);
			}
			// print_r($documentos['impuestos']['impuesto']);
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

			
			return array('lineas' => $lineas,'cabecera'=>$cabecera,"tributatio"=>$tributaria);
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
			
			return array('lineas' => $lineas,'cabecera'=>$cabecera,"tributatio"=>$tributaria);
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
			return array('lineas' => $lineas,'cabecera'=>$cabecera,"tributatio"=>$tributaria);

		}
		
	}

}

?>