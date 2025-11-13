<?php

// require_once("db.php");
// require_once("ConnectHikcentral.php");

$eventos = new events();
/*
para que lleguen los evento aca este se debe condfigurar primero en hikcentral en openApi

en mi caso en 

/api/eventService/v1/eventSubscriptionByEventTypes

196893 --> para foto de facial

los parametros
{
    "eventTypes": [
      196892,198914
    ],
    "eventDest": "https://corsinf.com:447/pruebas/appPrueba/hikphp/events.php?EntranteHik"
}

*/

if(isset($_GET['listenEvents']))
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	      // Datos recibidos
	    $timestamp = date('Y-m-d H:i:s');
	    $jsonInput = file_get_contents('php://input'); // Obtener el JSON crudo
	    $dataArray = json_decode($jsonInput, true); // Decodificar el JSON en un array asociativo

	    // Verificar si el JSON es válido
	    if (json_last_error() === JSON_ERROR_NONE) 
	    {

	    	// print_r($dataArray);die();


	    	// if($dataArray['params']['events'][0]['eventType']=='198914')
	    	// {

	      //   // Agregar timestamp al array
	      //   $dataArray['timestamp'] = $timestamp;
	      //   $IdPerson = $dataArray['params']['events'][0]['data']["personId"];
	        $jsonData = json_encode($dataArray);
			        	
			        	

		    // 	switch ($dataArray['params']['events'][0]['srcIndex']) {


		    // 		case '144':
		    // 		//garita principal

		    // 			$camaras = array('7805','8048');
		    // 			$data = $eventos->buscar_visitante($IdPerson);		    			
		    // 		 	$eventos->takePhoto($IdPerson,$camaras);
			  //       	$playerIds =array($data[0]['id']);

			  //       	$title = 'Ingreso de Visitante';
				// 				$message = 'Su visitante '.$data[0]['NombreVisitante'].' acaba de ingresar por garita principal';
			  //       	$eventos->sendPushNotification($title, $message, $playerIds);
			  //       	$eventos->InsertarNotificacion($message,$data[0]['Residente'],$data[0]['idVis']);

		    // 			break;

		    // 		case '157':
		    // 			//garita ingreso piscina
			  //       $data = $eventos->buscar_visitante($IdPerson);
		    // 			$camaras = array('396','7821','8047');

		    // 			//4609 camara no existe por que se movio
		    // 			if($data[0]['FotoEntrada']!='')
		    // 			{
		    // 				// print_r($data[0]['FotoEntrada']);die();
		    // 		 		$eventos->UpdatetakePhoto($IdPerson,$camaras,$data[0]['FotoEntrada']);
		    // 		 	}else
		    // 		 	{
			  //   		 	$eventos->takePhoto($IdPerson,$camaras);
		    // 		 	}

			  //       	$playerIds =array($data[0]['id']);

			  //       	$title = 'Ingreso de Visitante';
				// 				$message = 'Su visitante '.$data[0]['NombreVisitante'].' acaba de ingresar por garita piscina';
			  //       	$eventos->sendPushNotification($title, $message, $playerIds);
			  //       	$eventos->InsertarNotificacion($message,$data[0]['Residente'],$data[0]['idVis']);

		    // 			break;
		    // 		case '149':

		    // 		// garita piscina salida 
			  //       $data = $eventos->buscar_visitante($IdPerson);
		    // 			$camaras = array('401','7822','8049'); //7822
		    // 			if($data[0]['FotoEntrada']!='')
		    // 			{
		    // 		 		$eventos->UpdatetakePhoto($IdPerson,$camaras,$data[0]['FotoEntrada']);
		    // 		 	}else
		    // 		 	{
			  //   		 	$eventos->takePhoto($IdPerson,$camaras);
		    // 		 	}
		    // 			$data = $eventos->buscar_visitante($IdPerson);
			  //       	$playerIds =array($data[0]['id']);

			  //       	$title = 'Salida de Visitante';
				// 		$message = 'Su visitante '.$data[0]['NombreVisitante'].' acaba de salir por garita piscina';
			  //       	$eventos->sendPushNotification($title, $message, $playerIds);
			  //       	$eventos->InsertarNotificacion($message,$data[0]['Residente'],$data[0]['idVis']);

		    // 			break;
		    		
		    // 		default:
		    // 			// code...
		    // 			break;
		    // 	}


<<<<<<< HEAD
			    file_put_contents('log_events/data.txt', $jsonData . "\n", FILE_APPEND);
=======
			    file_put_contents('Eventos/data.txt', $jsonData . "\n", FILE_APPEND);
>>>>>>> fd7a13f090c09fd3d17bd4ab260e55565161fe28
		    // }
	        
	    } 
    }
}

if(isset($_GET['eventosEntrante']))
{
	echo json_encode($eventos->EventosEntrantes());
}

if(isset($_GET['EnviarNoti']))
{
	$title = 'Ingreso de Visitante';
	$message = 'Su visitante acaba de ingresar';
	$playerIds = array('b55db543-898a-409d-8b74-3fb133d6010f','832512e7-0945-4891-bc4b-e7113c0e6425');
	echo json_encode($eventos->sendPushNotification($title, $message, $playerIds));
}



/**
 * 
 */
class events
{
	private $db;
	private $hikcentral;

	private $appId;
	private $apiKey;
	
	function __construct()
	{		
		// $this->db = new db();		
		// $this->hikcentral = new ConnectHikcentral();
		$this->appId = 'f7939ef1-1530-4969-a9ee-12a9cf1ff4b1';
		$this->apiKey = 'MjdhMWIwMDctYjRhZi00MGZhLTg5ZTEtOGM0OTFiMzE5YzFh';
	}


	function EventosEntrantes()
	{
		if (file_exists('Eventos/data.txt')) {
		    $data = file_get_contents('Eventos/data.txt');
		    return nl2br($data); // Convertir nuevas líneas a <br> para HTML
		} else {
		    return "No data available";
		}
	}

	function takePhoto($IdPerson,$camaras)
	{
		$listaPhotos = array();
		foreach ($camaras as $key => $value) {
			$base64 = $this->hikcentral->TakePhotoOnline($value);
			$listaPhotos[] = $this->SaveImgRepo($base64,$IdPerson,$key+1);
		}
		$this->EditarRegistroVisita($listaPhotos,$IdPerson);
		
	}

	function InsertarNotificacion($text,$residente,$idVisita)
	{
		 $sql = "INSERT INTO notificaciones (texto,residente,id_visita) VALUES ('".$text."','".$residente."','".$idVisita."')";

		 	// print_r($sql);die();
     return  $this->db->sql_string($sql);
		
	}

	function UpdatetakePhoto($IdPerson,$camaras,$fotos)
	{
		$listaPhotos = json_decode($fotos, true);
		$num = count($listaPhotos);

		// print_r($num);die();
		// $listaPhotos = array();
		// print_r($listaPhotos);
		foreach ($camaras as $key => $value) {
			$base64 = $this->hikcentral->TakePhotoOnline($value);
			$listaPhotos[] = $this->SaveImgRepo($base64,$IdPerson,$num+1);
			$num=$num+1;
		}

		// print_r($listaPhotos);die();

		$this->EditarRegistroVisita($listaPhotos,$IdPerson);
		
	}

	function SaveImgRepo($base64,$IdPerson,$no)
	{
		$ruta = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].''.$_SERVER['SCRIPT_NAME'];
		$ruta = str_replace('events.php','img/IngresoFoto/', $ruta);

		$base64_string = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
		$image_data = base64_decode($base64_string);
		$file_path = 'img/IngresoFoto/'.date('Ymd').'-'.$IdPerson.'-00'.$no.'.png';
		$linkPhoto = $ruta.date('Ymd').'-'.$IdPerson.'-00'.$no.'.png';

		// print_r($file_path);die();

		// Guarda los datos binarios como un archivo de imagen
		file_put_contents($file_path, $image_data);
		if (file_exists($file_path)) {
		    // echo "La imagen ha sido guardada en: " . $file_path;
		    return $linkPhoto;
		} 
	}

	function EditarRegistroVisita($photos,$IdPerson)
	{

        $jsonDataPhotos = json_encode($photos);
        $sql = "UPDATE visitas SET FotoEntrada = '".$jsonDataPhotos."' WHERE IdHik = '".$IdPerson."'";
       return  $this->db->sql_string($sql);

	}

	function buscar_visitante($IdPerson)
	{
		$sql = "SELECT userIdNotification as id,FotoEntrada,NombreVisitante,Id as idVis,Residente FROM visitas  WHERE IdHik = '".$IdPerson."'";
		return  $this->db->datos($sql);
	}

	function sendPushNotification($title, $message, $playerIds) 
	{
		$appId = $this->appId;
		$apiKey = $this->apiKey;


	    $content = array(
	        "en" => $message
	    );

	    $headings = array(
	        "en" => $title
	    );

	    $fields = array(
	        'app_id' => $appId,
	        'include_player_ids' => $playerIds,
	        'headings' => $headings,
	        'contents' => $content
	    );

	    $fields = json_encode($fields);

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
	                                               'Authorization: Basic ' . $apiKey));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);
	    curl_setopt($ch, CURLOPT_POST, TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	    $response = curl_exec($ch);
	    curl_close($ch);

	    return $response;
	}

}


?>