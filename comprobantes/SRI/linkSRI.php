<?php 
/**
 * 
 */
class linkSRI
{

	private $linkAutorizacion;
	private $linkRecepcion;	
	function __construct()
	{

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