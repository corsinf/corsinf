<?php
//Relacionado con la coneccion a la API

class Idukay
{
    private $url_API;
    private $bearerToken;

    public function __construct($url_API, $bearerToken)
    {
        $this->url_API = $url_API;
        $this->bearerToken = $bearerToken;
    }

    public function consulta_API($body, $query)
    {
        $headers = [
            'Authorization: Bearer ' . $this->bearerToken,
            //'Cache-Control: no-cache',
            //'Accept: */*',
            //'Content-Type: application/json',
            //'Accept-Encoding: gzip, deflate, br',
            //'Connection: keep-alive',
            'Type: API',
        ];

        $options = array(
            'http' => array(
                'header' => $headers,
                'method'  => 'GET',
                //'content' => $body,
            ),
            'ssl' => array(
                'verify_peer'       => false,
                'verify_peer_name'  => false,
            ),
        );

        $context = stream_context_create($options);

        try {
            $response = @file_get_contents($this->url_API . $query, false, $context);

            if ($response === FALSE) {
                $http_response_header = isset($http_response_header) ? $http_response_header : [];
                if (empty($http_response_header)) {
                    return -11;
                } else {
                    if (strpos($http_response_header[0], '503') !== false) {
                        //throw new Exception("El servicio está suspendido (HTTP 503 Service Unavailable).");
                        return -11;
                    } else {
                        //throw new Exception("Error en la solicitud HTTP.");
                        return -11;
                    }
                }
            }

            $result = json_decode($response, true);

            if ($result === null) {
                //throw new Exception("Error al decodificar la respuesta JSON.");
                return -11;
            }

            return $result;
        } catch (Exception $e) {
            // Aquí puedes manejar el error de una manera específica o registrar el error
            //echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            // O lanzar nuevamente la excepción si prefieres
            // throw $e;
            return -11;
        }
    }

    //Peticiones
    function respuesta_Json($body, $query)
    {
        $body_json = json_encode($body);
        return $this->consulta_API($body_json, $query);
    }
}
