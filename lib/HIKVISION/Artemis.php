<?php


class Artemis
{
    private $ip_puerto_API; //= 'https://192.168.1.6:449';
    private $clave_partner;
    private $clave_secreta;

    public function __construct($clave_partner, $clave_secreta, $ip_puerto_API, $puerto_API)
    {
        $this->clave_partner = $clave_partner;
        $this->clave_secreta = $clave_secreta;

        $this->ip_puerto_API = 'https://' . $ip_puerto_API . ':' .  $puerto_API;
    }

    public function consulta_API($parametros)
    {

        $this->clave_partner = $parametros['clave_partner'];
        $url_API = $parametros['url_API'];
        $firma_Peticion = $parametros['firma_Peticion'];
        $body = $parametros['body'];

        $headers = [
            'x-ca-key: ' . $this->clave_partner,
            'x-ca-signature-headers: x-ca-key',
            'x-ca-signature: ' . $firma_Peticion,
            'Content-Type: application/json',
            'Accept: */*'
        ];

        $options = array(
            'http' => array(
                'header' => $headers,
                'method'  => 'POST',
                'content' => $body,
            ),
            'ssl' => array(
                'verify_peer'       => false,
                'verify_peer_name'  => false,
            ),
        );

        $context = stream_context_create($options);

        try {
            $response = @file_get_contents($this->ip_puerto_API . $url_API, false, $context);

            if ($response === FALSE) {
                $http_response_header = isset($http_response_header) ? $http_response_header : [];
                if (strpos($http_response_header[0], '503') !== false) {
                    //throw new Exception("El servicio está suspendido (HTTP 503 Service Unavailable).");
                    return -11;
                } else {
                    //throw new Exception("Error en la solicitud HTTP.");
                    return -11;
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

    public function url_firma($parametros)
    {

        $this->clave_partner = $parametros['clave_partner'];
        $this->clave_secreta = $parametros['clave_secreta'];
        $url_API = $parametros['url_API'];

        //Texto plano
        $texto_Original = "";
        $texto_Original .= "POST" . "\n";
        $texto_Original .= "*/*" . "\n";
        $texto_Original .= "application/json" . "\n";
        $texto_Original .= "x-ca-key:" . $this->clave_partner . "" . "\n";
        $texto_Original .= $url_API;

        return $this->calcularHMAC($texto_Original, $this->clave_secreta);
    }

    function calcularHMAC($texto, $claveSecreta)
    {
        // Calcula el HMAC-SHA256
        $hmac = hash_hmac('sha256', $texto, $claveSecreta, true);

        // Convierte el resultado a Base64
        $hmacBase64 = base64_encode($hmac);

        return $hmacBase64;
    }

    //Peticiones
    function respuesta_Json($url_API, $body)
    {

        $datos = [
            'clave_partner' => $this->clave_partner,
            'clave_secreta' => $this->clave_secreta,
            'url_API' => $url_API,
        ];

        $firma_Peticion = $this->url_firma($datos);

        $body_json = json_encode($body);

        $datos_Pet = [
            'clave_partner' => $this->clave_partner,
            'url_API' => $url_API,
            'firma_Peticion' => $firma_Peticion,
            'body' => $body_json,
        ];

        return $this->consulta_API($datos_Pet);
    }
}
