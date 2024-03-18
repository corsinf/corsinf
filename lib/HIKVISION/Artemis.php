<?php


class Artemis
{
    private $ip_puerto_API = 'https://192.168.1.6:449';

    private $clave_partner;
    private $clave_secreta;

    public function __construct($clave_partner, $clave_secreta)
    {
        $this->clave_partner = $clave_partner;
        $this->clave_secreta = $clave_secreta;
    }

    public function consulta_API($parametros)
    {

        $this->clave_partner = $parametros['clave_partner'];
        $url_API = $parametros['url_API'];
        $firma_Peticion = $parametros['firma_Peticion'];
        $body = $parametros['body'];


        $options = array(
            'http' => array(
                'header' => [
                    'x-ca-key: ' .  $this->clave_partner,
                    'x-ca-signature-headers: x-ca-key',
                    'x-ca-signature: ' . $firma_Peticion,
                    'Content-Type: application/json',
                    'Accept: */*'
                ],

                'method'  => 'POST',
                'content' => $body,
            ),
            'ssl' => array(
                'verify_peer'       => false,
                'verify_peer_name'  => false,
            ),
        );

        $context = stream_context_create($options);
        $response = file_get_contents($this->ip_puerto_API . $url_API, false, $context);

        if ($response === FALSE) {
            throw new Exception("Error en la solicitud HTTP.");
        }

        $result = ($response);

        if ($result === null) {
            throw new Exception("Error al decodificar la respuesta JSON.");
        }

        return $result;
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