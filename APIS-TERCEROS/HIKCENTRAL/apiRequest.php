<?php
@session_start();
require_once('SID.php');

/**
  * 
  */
 class apiRequest
 {
 	private $SID;
 	private $baseUrl;
 	private $username;
 	private $password;

 	function __construct()
 	{
 		$this->baseUrl = "https://medico.saintdominic.edu.ec:447";
	    $this->username = "admin";
	    $this->password = "Data12/**";
 		$this->SID = new HikCentralClient($this->baseUrl, verifySsl: false, caInfo: null, timeout: 15, maxRetriesOnSidExpire: 2);
 	}

 	function codigoSID($baseUrl=false,$username=false,$password=false)
 	{
 		if($baseUrl){$this->baseUrl = $_SESSION['INICIO']['ip_api_hikvision'];}
 		if($username){$this->username = $_SESSION['INICIO']['user_api_hikvision'];}
 		if($password){$this->password = $_SESSION['INICIO']['tc_api_hikvision'];}

		try {
		    $client = new HikCentralClient($this->baseUrl, verifySsl: false, caInfo: null, timeout: 15, maxRetriesOnSidExpire: 2);
		    $result = $client->login($this->username, $this->password);
		    return $result['ResponseStatus']['Data']['Login']['SID'];

		} catch (\Throwable $e) {
		    echo "Error final: " . $e->getMessage() . "\n";
		}
 	}

 	function RequestHikcentral($url,$data=false)
 	{

 		$url = $this->baseUrl."".$url;

	    // Inicializar cURL
	    $ch = curl_init($url);

	    // Configurar la petición POST
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

	    // Encabezados
	    curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        'Accept: application/json',
	        'Content-Type: application/json'
	    ]);

	    // Ejecutar la petición
	    $response = curl_exec($ch);
	    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	    // Verificar errores de cURL
	    if (curl_errno($ch)) {
	        $error_msg = curl_error($ch);
	        curl_close($ch);
	        return [
	            'error' => -1,
	            'status' => 500,
	            'mensaje' => 'Error en la conexión: ' . $error_msg
	        ];
	    }

	    curl_close($ch);

	    // Convertir respuesta a array
	    $respuesta = json_decode($response, true);

	    // Evaluar respuesta
	    if ($httpCode >= 200 && $httpCode < 300) {
	        return $respuesta;
	    } else {
	        return [
	            'error' => -1,
	            'status' => $httpCode,
	            'mensaje' => $respuesta['message'] ?? 'Mensaje no disponible'
	        ];
	    }
	}
 } 
?> 