<?php 
require_once('apiRequest.php');
/**
 * 
 */
class HikcentralHttp
{
	private $api;
	function __construct()
	{
		$this->api = new apiRequest();
	}

	function listadoPersonas()
	{
		$SID = $this->api->codigoSID();
		// print_r($SID);die();
		$Url = "/ISAPI/Bumblebee/Platform/V1/PersonCredential/Persons?MT=GET&SID=".$SID;
		$data = 
		[
			"PersonListRequest"=>[ "SearchCriteria"=>[ "SortField"=>-1,
													   "OrderType"=>0,
													   "PersonName"=>"",
													   "GivenName"=>"",
													   "FamilyName"=>"",
													   "PersonCode"=>"",
													   "CardNo"=>"",
													   "CardDisableStatus"=>"",
													   "CardDisableReasonIDs"=>"",
													   "PersonDisableStatus"=>"",
													   "PersonFrom"=>"",
													   "CertificateStatus"=>"",
													   "UserReleatedStatus"=>0,
													   "PhoneNum"=>"",
													   "PersonStatus"=>0,
													   "ClientCurrentTime"=>"2025-10-27T12:01:34-05:00",
													   "PersonGroupIDs"=>1,
													   "IncludeSubNodes"=>1
													],
									"Field"=>"FullPath,CardList,FingerPrintList,IrisList,CustomFieldList,ImageModelingInfo",
									"AdditionalInfoField"=>"15,16,4,21,44,53,61,65,66,68,71,74",
									"PageIndex"=>1,
									"PageSize"=>100
								]
		];

		$respuesta = $this->api->RequestHikcentral($Url,$data);
		return $respuesta;
 		
	}
}
?>