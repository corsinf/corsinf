<?php 
require_once 'vendor/autoload.php';
use phpseclib3\Crypt\AES;

/**
 * Desencripta datos de HikCentral con AesSourceKey
 * @param string $encryptedData Datos encriptados en Base64
 * @param string $aesSourceKey AesSourceKey de la sesión actual (Base64)
 * @return string Datos desencriptados
 */

require_once('apiRequest.php');
/**
 * 
 */
class HikcentralHttp
{
	private $api;
	private $sid;
    private $IV;

    private $PASSWORD;
    private $CHALLENGE;
    private $ITERATIONS;

	function __construct()
	{
		$this->api = new apiRequest();
        $this->IV = "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f";
	}

    /**
     * Genera la clave AES según el algoritmo de HikCentral
     * Basado en: createAESKey(password, challenge, iterations)
     *
     * @param string $password
     * @param string $challenge
     * @param int    $iterations
     * @return string  binary key (bytes)
     */
    function generate_aes_key(string $password, string $challenge, int $iterations): string {
        // Concatenar password + challenge
        $combined = $password . $challenge;

        // Primera iteración: SHA256(password + challenge) -> hex
        $key_hex = hash('sha256', $combined);

        // Iteraciones restantes: SHA256(resultado_anterior)
        for ($i = 1; $i < $iterations; $i++) {
            $key_hex = hash('sha256', $key_hex);
        }

        // Convertir hex a binario (bytes)
        return hex2bin($key_hex);
    }

    /**
     * Desencripta un campo usando AES-256-CBC
     *
     * @param string $encrypted_base64
     * @param string $aes_key (binary)
     * @return string|null  texto descifrado o null si falla
     */
    function decrypt_field(string $encrypted_base64, string $aes_key): ?string {
        try {
            // Decodificar Base64
            $encrypted_data = base64_decode($encrypted_base64, true);
            if ($encrypted_data === false) {
                throw new Exception("Base64 inválido");
            }

            // Usar los primeros 32 bytes (AES-256)
            $key = substr($aes_key, 0, 32);
            if ($key === false || strlen($key) < 16) {
                throw new Exception("Clave AES inválida o demasiado corta");
            }

            // Desencriptar con OpenSSL (raw data, pues ya manejamos padding manualmente)
            $decrypted = openssl_decrypt($encrypted_data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $this->IV);
            if ($decrypted === false) {
                return null;
            }

            // Remover padding PKCS7
            $len = strlen($decrypted);
            if ($len === 0) {
                return null;
            }
            $padding_length = ord($decrypted[$len - 1]);
            if ($padding_length > 0 && $padding_length <= 16) {
                $valid = true;
                for ($i = 0; $i < $padding_length; $i++) {
                    if (ord($decrypted[$len - 1 - $i]) !== $padding_length) {
                        $valid = false;
                        break;
                    }
                }
                if ($valid) {
                    $decrypted = substr($decrypted, 0, $len - $padding_length);
                }
            }

            // Intentar decodificar con diferentes encodings (PHP maneja strings en bytes; asumimos UTF-8)
            // Verificamos si la cadena es válida en UTF-8; si no, intentamos retornarla tal cual.
            if (mb_check_encoding($decrypted, 'UTF-8')) {
                $result = trim($decrypted);
                return $result !== '' ? $result : null;
            } else {
                // devolver interpretación raw si no es UTF-8 (latin1/cp1252 son compatibles byte a byte)
                $result = trim($decrypted);
                return $result !== '' ? $result : null;
            }
        } catch (Exception $e) {
            // Para depuración, podrías imprimir $e->getMessage();
            // pero aquí devolvemos null para mantener la paridad con el script Python.
            return null;
        }
    }


    function decrypt_data($ENCRYPTED_FULLNAME)
    {
        // print_r($this->PASSWORD.'-'.$this->CHALLENGE.'-'.$this->ITERATIONS);die();
        $aes_key = $this->generate_aes_key($this->PASSWORD,$this->CHALLENGE, $this->ITERATIONS);
        return $this->decrypt_field($ENCRYPTED_FULLNAME, $aes_key);
    }

	function GetSid()
	{		
		$data =  $this->api->codigoSID();
		$SID = $data['SID'];
		$this->sid = $SID;
		$this->CHALLENGE = $data['EncryInfo']['Challenge'];
        $this->ITERATIONS = $data['EncryInfo']['Iterations'];
        $this->PASSWORD = $data['PASSWORD'];
		return $SID;
	}

	function listadoPersonas()
	{
		$SID = $this->GetSid();
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
													   "CardDisableStatus"=>"1",
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
                                    "CredentialTypes" => [1, 2, 3, 4],
									"PageSize"=>500
								]
		];
		$respuesta = $this->api->RequestHikcentral($Url,$data);
		return $respuesta;
 		
	}

	
}
?>