<?php
/**
 * Script de desencriptación HikCentral - CON TUS DATOS REALES (PHP)
 *
 * Equivalente funcional al script Python que proporcionaste.
 * Requiere PHP 7+ con la extensión OpenSSL habilitada.
 */

/** IV fijo de HikCentral */
const IV = "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f";

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
        $decrypted = openssl_decrypt($encrypted_data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, IV);
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

/**
 * Convenience: genera clave alternativa usando solo password (iterativa)
 * @param string $password
 * @param int $iterations
 * @return string binary key
 */
function generate_key_only_password(string $password, int $iterations): string {
    $key_hex = hash('sha256', $password);
    for ($i = 1; $i < $iterations; $i++) {
        $key_hex = hash('sha256', $key_hex);
    }
    return hex2bin($key_hex);
}

/**
 * Imprime separador
 */
function sep() {
    echo str_repeat("=", 80) . PHP_EOL;
}

/** ---------- MAIN ---------- */

echo "DESENCRIPTADOR HIKVISION - PHP (equivalente)<br>";

// DATOS REALES DE TU SISTEMA
$PASSWORD = "Data12/**";
$CHALLENGE = "0156D0F634D5400B9A646E0ED2B35C57";
$ITERATIONS = 100;

// Datos encriptados del registro
$ENCRYPTED_EMAIL = "d+Agvrmb+W3BZOTZ9cXVt9nHyXBKI1yJIq4T1amUzULNv49/V0kpoNm6eUw+1Sq/";
$ENCRYPTED_FAMILYNAME  = "3pIGrOylSCv5VyjpT7E4KLFiYdqZ7CLRPcF/SDWN6oo=";
$ENCRYPTED_FULLNAME =  "SL9b3pNLzSK7MiNRrPZuXeZAj3D+8lPankrlulkIF+7QmjRsRD64aruK01o9ym0E";
$ENCRYPTED_GIVENNAME  = "BYx3jniZDrA78SpO+vxsUA==";

// $ENCRYPTED_FULLNAME = "iClLYqyqnLJ5a0S2jwhW53iyX/pFPRNGHlYZBtqgltI=";
// $ENCRYPTED_GIVENNAME = "iClLYqyqnLJ5a0S2jwhW5zWq4BfuIUe3X02M+t9XvM0=";
// $ENCRYPTED_FAMILYNAME = "ya0+kakOdwNMBX11OKpChw==";
// $ENCRYPTED_EMAIL = ""

echo PHP_EOL . "[1] CONFIGURACIÓN: <br>";
echo "  Password: {$PASSWORD}<br>" ;
echo "  Challenge: {$CHALLENGE}<br>" ;
echo "  Iterations: {$ITERATIONS}<br>" ;

echo PHP_EOL . "[2] GENERANDO CLAVE AES...<br>";
$aes_key = generate_aes_key($PASSWORD, $CHALLENGE, $ITERATIONS);
echo "  Clave AES (hex): " . substr(bin2hex($aes_key), 0, 64) . "...<br>";

echo "[3] DESENCRIPTANDO CAMPOS...<br>";

// Desencriptar FullName
echo "  FullName (encriptado): " . substr($ENCRYPTED_FULLNAME, 0, 40) . "...<br>";
$fullname = decrypt_field($ENCRYPTED_FULLNAME, $aes_key);
if ($fullname !== null) {
    echo "  FullName (descifrado): ✓ '{$fullname}'<br>";
} else {
    echo "  FullName (descifrado): ✗ FALLÓ<br>";
}

// Desencriptar GivenName
echo "  GivenName (encriptado): " . substr($ENCRYPTED_GIVENNAME, 0, 40) . "...<br>";
$givenname = decrypt_field($ENCRYPTED_GIVENNAME, $aes_key);
if ($givenname !== null) {
    echo "  GivenName (descifrado): ✓ '{$givenname}'<br>";
} else {
    echo "  GivenName (descifrado): ✗ FALLÓ<br>";
}

// Desencriptar FamilyName
echo "  FamilyName (encriptado): {$ENCRYPTED_FAMILYNAME}<br>";
$familyname = decrypt_field($ENCRYPTED_FAMILYNAME, $aes_key);
if ($familyname !== null) {
    echo "  FamilyName (descifrado): ✓ '{$familyname}'<br>";
} else {
    echo "  FamilyName (descifrado): ✗ FALLÓ<br>";
}

// Desencriptar Email
echo "  Email (encriptado): {$ENCRYPTED_EMAIL}<br>";
$email = decrypt_field($ENCRYPTED_EMAIL, $aes_key);
if ($email !== null) {
    echo "  Email (descifrado): ✓ '{$email}'<br>";
} else {
    echo "  Email (descifrado): ✗ FALLÓ<br>";
}



if ($fullname !== null || $givenname !== null || $familyname !== null) {
    echo "RESULTADO FINAL:<br>";
    if ($fullname !== null) echo "  Nombre Completo: {$fullname}<br>";
    if ($givenname !== null) echo "  Nombre: {$givenname}<br>";
    if ($familyname !== null) echo "  Apellido: {$familyname}<br>";
    if ($email !== null) echo "  email: {$email}<br>";
    echo "✓ ¡DESENCRIPTACIÓN EXITOSA!<br>" . PHP_EOL;
} else {
    echo "✗ NO SE PUDO DESENCRIPTAR CON EL MÉTODO PRINCIPAL<br>" . PHP_EOL;
    echo "Probando métodos alternativos...<br>" . PHP_EOL;

    // Método alternativo 1: Solo password
    echo PHP_EOL . "[ALT 1] Probando con solo password (sin Challenge)..." . PHP_EOL;
    $alt_key1 = generate_key_only_password($PASSWORD, $ITERATIONS);
    $result = decrypt_field($ENCRYPTED_FULLNAME, $alt_key1);
    if ($result !== null) {
        echo "  ✓ FUNCIONA! Resultado: '{$result}'" . PHP_EOL;
    } else {
        echo "  ✗ No funciona" . PHP_EOL;
    }

    // Método alternativo 2: Challenge + Password (orden inverso)
    echo PHP_EOL . "[ALT 2] Probando Challenge + Password (orden inverso)..." . PHP_EOL;
    $combined = $CHALLENGE . $PASSWORD;
    $key_hex = hash('sha256', $combined);
    for ($i = 1; $i < $ITERATIONS; $i++) {
        $key_hex = hash('sha256', $key_hex);
    }
    $alt_key2 = hex2bin($key_hex);
    $result = decrypt_field($ENCRYPTED_FULLNAME, $alt_key2);
    if ($result !== null) {
        echo "  ✓ FUNCIONA! Resultado: '{$result}'" . PHP_EOL;
    } else {
        echo "  ✗ No funciona" . PHP_EOL;
    }

    // Método alternativo 3: Sin iteraciones
    echo PHP_EOL . "[ALT 3] Probando sin iteraciones (solo 1 SHA256)..." . PHP_EOL;
    $key_hex = hash('sha256', $PASSWORD . $CHALLENGE);
    $alt_key3 = hex2bin($key_hex);
    $result = decrypt_field($ENCRYPTED_FULLNAME, $alt_key3);
    if ($result !== null) {
        echo "  ✓ FUNCIONA! Resultado: '{$result}'" . PHP_EOL;
    } else {
        echo "  ✗ No funciona" . PHP_EOL;
    }

    // Método alternativo 4: Diferentes iteraciones
    echo PHP_EOL . "[ALT 4] Probando diferentes números de iteraciones..." . PHP_EOL;
    foreach ([1, 10, 50, 200, 1000] as $test_iter) {
        $test_key = generate_aes_key($PASSWORD, $CHALLENGE, $test_iter);
        $result = decrypt_field($ENCRYPTED_FULLNAME, $test_key);
        if ($result !== null) {
            echo "  ✓ Con {$test_iter} iteraciones: '{$result}'" . PHP_EOL;
            break;
        } else {
            echo "  ✗ Con {$test_iter} iteraciones: No funciona" . PHP_EOL;
        }
    }

    // Método alternativo 5: Challenge en minúsculas
    echo PHP_EOL . "[ALT 5] Probando Challenge en minúsculas..." . PHP_EOL;
    $test_key = generate_aes_key($PASSWORD, strtolower($CHALLENGE), $ITERATIONS);
    $result = decrypt_field($ENCRYPTED_FULLNAME, $test_key);
    if ($result !== null) {
        echo "  ✓ FUNCIONA! Resultado: '{$result}'" . PHP_EOL;
    } else {
        echo "  ✗ No funciona" . PHP_EOL;
    }

    // Método alternativo 6: Challenge sin iteraciones (solo iterativa del challenge)
    echo PHP_EOL . "[ALT 6] Probando solo Challenge sin password..." . PHP_EOL;
    $key_hex = hash('sha256', $CHALLENGE);
    for ($i = 1; $i < $ITERATIONS; $i++) {
        $key_hex = hash('sha256', $key_hex);
    }
    $alt_key6 = hex2bin($key_hex);
    $result = decrypt_field($ENCRYPTED_FULLNAME, $alt_key6);
    if ($result !== null) {
        echo "  ✓ FUNCIONA! Resultado: '{$result}'" . PHP_EOL;
    } else {
        echo "  ✗ No funciona" . PHP_EOL;
    }
}

echo PHP_EOL;
sep();

?>
