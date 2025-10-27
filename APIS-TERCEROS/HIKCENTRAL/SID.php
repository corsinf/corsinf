<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php'; // Composer autoload

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;

class CryptoResponse {
    public string $SID;
    public int $CryptoType;
    public int $CryptoMode;
    public string $CryptoKey;

    public function __construct(array $data) {
        $this->SID = $data['SID'];
        $this->CryptoType = (int) $data['CryptoType'];
        $this->CryptoMode = (int) $data['CryptoMode'];
        $this->CryptoKey = $data['CryptoKey'];
    }

    /**
     * Devuelve la clave pública como objeto phpseclib (lista para encrypt).
     * phpseclib maneja DER PKCS1/PKCS8 correctamente si le pasamos el binario.
     *
     * @return \phpseclib3\Crypt\PublicKey
     * @throws \RuntimeException
     */
    public function getPublicKey() {
        $der = base64_decode($this->CryptoKey, true);
        if ($der === false) {
            throw new RuntimeException("CryptoKey no es base64 válida.");
        }

        try {
            // PublicKeyLoader acepta DER binario y devuelve un objeto clave
            $pub = PublicKeyLoader::load($der);
            // Asegurar padding PKCS1
            if (method_exists($pub, 'withPadding')) {
                $pub = $pub->withPadding(RSA::ENCRYPTION_PKCS1);
            }
            return $pub;
        } catch (\Throwable $e) {
            throw new RuntimeException("Error cargando clave pública (phpseclib): " . $e->getMessage());
        }
    }
}

class LoginRequest {
    public string $UserName;
    public ?string $Password = null;
    public int $LoginModel = 1;
    public string $LoginAddress;
    public int $IsRSMWebLogin = 0;

    public function __construct(string $username, string $loginAddress) {
        $this->UserName = $username;
        $this->LoginAddress = $loginAddress;
    }

    /**
     * Rellena Password con la versión RSA-PKCS1v15 + base64
     *
     * @param string $passwordPlain
     * @param \phpseclib3\Crypt\PublicKey $pubKey
     * @return string base64 del ciphertext
     * @throws \RuntimeException
     */
    public function fillPassword(string $passwordPlain, $pubKey): string {
        try {
            $cipher = $pubKey->encrypt($passwordPlain); // pkcs1 padding ya configurado en CryptoResponse
            $this->Password = base64_encode($cipher);
            return $this->Password;
        } catch (\Throwable $e) {
            throw new RuntimeException("Error cifrando contraseña: " . $e->getMessage());
        }
    }

    public function toArray(): array {
        return [
            "UserName" => $this->UserName,
            "Password" => $this->Password,
            "LoginModel" => $this->LoginModel,
            "LoginAddress" => $this->LoginAddress,
            "IsRSMWebLogin" => $this->IsRSMWebLogin
        ];
    }
}

class HikCentralClient {
    private string $baseUrl;
    private bool $verifySsl;
    private ?string $caInfo;
    private int $timeout;
    private int $maxRetriesOnSidExpire;

    /**
     * @param string $baseUrl e.g. https://medico.saintdominic.edu.ec:447
     * @param bool $verifySsl si false ignora verificaciones SSL (solo pruebas)
     * @param string|null $caInfo ruta al archivo .pem de CA para verificación SSL (opcional)
     * @param int $timeout timeout en segundos para curl
     * @param int $maxRetriesOnSidExpire reintentos si ErrorCode 210 (SID expirado)
     */
    public function __construct(
        string $baseUrl,
        bool $verifySsl = true,
        ?string $caInfo = null,
        int $timeout = 15,
        int $maxRetriesOnSidExpire = 2
    ) {
        $this->baseUrl = rtrim($baseUrl, "/");
        $this->verifySsl = $verifySsl;
        $this->caInfo = $caInfo;
        $this->timeout = $timeout;
        $this->maxRetriesOnSidExpire = $maxRetriesOnSidExpire;
    }

    /** Realiza POST JSON con curl y devuelve array decodificado */
    private function postJson(string $url, array $payload, array $queryParams = []): array {
        $ch = curl_init();

        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        if ($this->verifySsl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            if ($this->caInfo !== null) {
                curl_setopt($ch, CURLOPT_CAINFO, $this->caInfo);
            }
        } else {
            // Modo pruebas: deshabilitar verificación de host y peer
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        $response = curl_exec($ch);
        if ($response === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException("cURL error: " . $err);
        }

        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            throw new RuntimeException("HTTP error $httpCode: $response");
        }

        $decoded = json_decode($response, true);
        if ($decoded === null) {
            throw new RuntimeException("Invalid JSON response or empty. Raw response: " . $response);
        }

        return $decoded;
    }

    /** Obtener CryptoResponse del servidor */
    public function getCrypto(string $username, string $password): CryptoResponse {
        $url = $this->baseUrl . "/ISAPI/Bumblebee/Platform/V0/Security/Crypto?MT=GET";
        $payload = ["LoginRequest" => ["UserName" => $username, "Password" => $password]];

        // Logging / debug
        // echo "Payload enviado al endpoint Crypto:\n";
        // echo json_encode($payload, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES) . "\n";

        $resp = $this->postJson($url, $payload);
        if (!isset($resp['ResponseStatus']['Data']['CryptoResponse'])) {
            throw new RuntimeException("Respuesta Crypto inesperada: " . json_encode($resp));
        }
        return new CryptoResponse($resp['ResponseStatus']['Data']['CryptoResponse']);
    }

    /**
     * Ejecuta login. Reintenta (obtener nuevo Crypto + login) si recibe ErrorCode 210.
     *
     * @return array respuesta JSON decodificada del servidor
     */
    public function login(string $username, string $password): array {
        $attempt = 0;
        $lastException = null;

        while ($attempt <= $this->maxRetriesOnSidExpire) {
            $attempt++;
            try {
                $crypto = $this->getCrypto($username, $password);

                $loginRequest = new LoginRequest($username, parse_url($this->baseUrl, PHP_URL_HOST) ?? $this->baseUrl);
                $loginRequest->fillPassword($password, $crypto->getPublicKey());

                $url = $this->baseUrl . "/ISAPI/Bumblebee/Platform/V0/Login";
                $params = ["SID" => $crypto->SID, "CT" => 0, "MT" => "POST"];
                $payload = ["LoginRequest" => $loginRequest->toArray()];

                // Mostrar payload de Login (con password cifrada)
                // echo "Payload enviado al endpoint Login (SID={$crypto->SID}):\n";
                // echo json_encode($payload, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES) . "\n";

                $resp = $this->postJson($url, $payload, $params);

                // Si la respuesta contiene ErrorCode y es 210, reintentar (SID expirado / inválido)
                $errorCode = $resp['ResponseStatus']['ErrorCode'] ?? null;
                if ($errorCode !== null && (int)$errorCode === 210) {
                    // mostrar info y reintentar (hasta maxRetries)
                    $remaining = $resp['ResponseStatus']['Data']['Login']['RemainingLoginNumber'] ?? 'N/A';
                    echo "Login fallido (ErrorCode 210). Intento {$attempt}/{$this->maxRetriesOnSidExpire}. RemainingLoginNumber={$remaining}\n";
                    $lastException = new RuntimeException("Login ErrorCode 210: " . json_encode($resp));
                    // small delay to avoid hammering
                    sleep(1);
                    continue;
                }

                // Si todo ok, devolver la respuesta
                return $resp;

            } catch (\Throwable $e) {
                $lastException = $e;
                // Si fue un error de red o JSON inválido, no reintentamos automáticamente salvo que queramos
                // pero hacemos break y mostramos el error
                echo "Error en intento {$attempt}: " . $e->getMessage() . "\n";
                // si queremos reintentar en caso de fallo de red, descomenta la siguiente línea:
                // sleep(1); continue;
                break;
            }
        }

        throw $lastException ?? new RuntimeException("Falló login desconocido.");
    }
}