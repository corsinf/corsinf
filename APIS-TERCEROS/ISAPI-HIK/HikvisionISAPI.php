<?php
class HikvisionISAPI {


    private $ip;
    private $username;
    private $password;
    private $port;
    
    public function __construct($ip,$port="8000",$username, $password) {
        $this->ip = $ip;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
    }
    
    /**
     * Realiza petición POST con Digest Auth
     */
    public function post($endpoint, $data) {
        return $this->request('POST', $endpoint, $data);
    }

    public function postXML( $endpoint, $xmlData,$json=1)
    {
        return $this->requestXML('POST', $endpoint, $xmlData,$json);
    }
    
    /**
     * Realiza petición GET con Digest Auth
     */
    public function get($endpoint) {
        return $this->request('GET', $endpoint);
    }
    
    /**
     * Realiza petición DELETE con Digest Auth
     */
    public function delete($endpoint,$data=null) {
        return $this->request('DELETE', $endpoint,$data);
    }

    public function put($endpoint, $data) {
        return $this->request('PUT', $endpoint, $data);
    }
    
    /**
     * Realiza petición POST multipart (para imágenes)
     */
    public function postMultipart($endpoint, $jsonData, $imagePath) {
        $url = "{$this->ip}/{$endpoint}?format=json";
        $ch = curl_init();
        
        $boundary = "---------------------" . md5(microtime());
        $body = $this->buildMultipartBody($jsonData, $imagePath, $boundary);
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: multipart/form-data; boundary={$boundary}",
            "Content-Length: " . strlen($body)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return ['success' => false, 'message' => $error, 'code' => $httpCode];
        }
        
        return [
            'success' => $httpCode === 200,
            'code' => $httpCode,
            'data' => json_decode($response, true),
            'raw' => $response
        ];
    }
    
    /**
     * Método base para todas las peticiones HTTP
     */
    private function request($method, $endpoint, $data = null,$xml=0) {
        if($this->port=="8000")
        {
            $url = "{$this->ip}/{$endpoint}";
        }else{
            $url = "{$this->ip}:{$this->port}/{$endpoint}";
        }
        
        // Asegurar formato JSON
        if (strpos($url, '?') === false) {
            $url .= "?format=json";
        } elseif (strpos($url, 'format=') === false) {
            $url .= "&format=json";
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);


        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        if ($data && ($method === 'POST' || $method === 'PUT')) {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Content-Length: " . strlen($jsonData)
            ]);
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return ['success' => false, 'message' => $error, 'code' => $httpCode];
        }
        
        // Para códigos 401, podría necesitar manejo de Digest adicional [citation:2]
        if ($httpCode === 401) {
            return ['success' => false, 'message' => 'Error de autenticación Digest', 'code' => 401];
        }
        
        return [
            'success' => $httpCode === 200 || $httpCode === 201,
            'code' => $httpCode,
            'data' => json_decode($response, true),
            'raw' => $response
        ];
    }

    public function requestXML($method, $endpoint, $xmlData,$json) {
        if($this->port=="8000")
        {
            $url = "{$this->ip}/{$endpoint}";
        }else{
            $url = "{$this->ip}:{$this->port}/{$endpoint}";
        }

        if($json==1)
        {
            if (strpos($url, '?') === false) {
                $url .= "?format=json";
            } elseif (strpos($url, 'format=') === false) {
                $url .= "&format=json";
            }
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/xml",
            "Content-Length: " . strlen($xmlData)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Mayor timeout para captura


        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return ['success' => false, 'message' => $error, 'code' => $httpCode];
        }
        
        // Para códigos 401, podría necesitar manejo de Digest adicional [citation:2]
        if ($httpCode === 401) {
            return ['success' => false, 'message' => 'Error de autenticación Digest', 'code' => 401];
        }
        
        return [
            'success' => $httpCode === 200 || $httpCode === 201,
            'code' => $httpCode,
            'data' => json_decode($response, true),
            'raw' => $response
        ];
    }
    
    /**
     * Construye body multipart para enviar imagen
     */
    // private function buildMultipartBody($jsonData, $imagePath, $boundary) {
    //     $body = "";
        
    //     // Parte JSON
    //     $body .= "--{$boundary}\r\n";
    //     $body .= "Content-Disposition: form-data; name=\"FaceDataRecord\"\r\n";
    //     $body .= "Content-Type: application/json\r\n\r\n";
    //     $body .= json_encode($jsonData) . "\r\n";
        
    //     // Parte imagen
    //     $body .= "--{$boundary}\r\n";
    //     $body .= "Content-Disposition: form-data; name=\"faceImage\"; filename=\"" . basename($imagePath) . "\"\r\n";
    //     $body .= "Content-Type: image/jpeg\r\n\r\n";
    //     $body .= file_get_contents($imagePath) . "\r\n";
        
    //     // Cierre
    //     $body .= "--{$boundary}--\r\n";
        
    //     return $body;
    // }


    private function buildMultipartBody($jsonData, $imagePath, $boundary) {
        $eol = "\r\n";
        $body = "";

        // JSON (🔥 SIN json_encode otra vez)
        $body .= "--{$boundary}{$eol}";
        $body .= 'Content-Disposition: form-data; name="FaceDataRecord"' . $eol;
        $body .= "Content-Type: application/json{$eol}{$eol}";
        $body .= $jsonData['FaceDataRecord'] . $eol;

        // Imagen
        $body .= "--{$boundary}{$eol}";
        $body .= 'Content-Disposition: form-data; name="faceImage"; filename="' . basename($imagePath) . '"' . $eol;
        $body .= "Content-Type: image/jpeg{$eol}{$eol}";
        $body .= file_get_contents($imagePath) . $eol;

        // cierre
        $body .= "--{$boundary}--{$eol}";

        return $body;
    }

    public function isOnline() {
        $result = $this->checkConnection();
        return $result['connected'];
    }


     public function checkConnection() {
        if($this->port=="8000")
        {
            $url = "{$this->ip}/ISAPI/System/time?format=json";
        }else{
            $url = "{$this->ip}:{$this->port}/ISAPI/System/time?format=json";
        }

        // print_r($url);die();
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout de 5 segundos
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            return [
                'connected' => false,
                'message' => "Error de conexión: $curlError",
                'time' => null
            ];
        }
        
        if ($httpCode === 200) {
            $data = json_decode($response, true);
            $this->connected = true;
            return [
                'connected' => true,
                'message' => 'Dispositivo conectado correctamente',
                'time' => $data['time'] ?? null
            ];
        } elseif ($httpCode === 401) {
            return [
                'connected' => false,
                'message' => 'Error de autenticación: verificar usuario/contraseña',
                'time' => null
            ];
        } else {
            return [
                'connected' => false,
                'message' => "Error HTTP $httpCode: Dispositivo no responde",
                'time' => null
            ];
        }
    }
}
?>