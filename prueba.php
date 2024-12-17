<?php
$command = 'dotnet C:\\xampp\\htdocs\\corsinf\\lib\\SDKDevices\\hikvision\\bin\\Debug\\net8.0\\CorsinfSDKHik.dll 6 192.168.100.111 admin 8000 Data12/*';

$descriptorspec = array(
    1 => array("pipe", "w"), // stdout
    2 => array("pipe", "w")  // stderr
);

$process = proc_open($command, $descriptorspec, $pipes);

if (is_resource($process)) {
    stream_set_blocking($pipes[1], 0); // No bloquear la lectura del stdout
    while (true) {
        $output = fgets($pipes[1]);
        if ($output !== false) {
            echo $output; // Mostrar la salida en tiempo real
            ob_flush();   // Limpiar el buffer de PHP
            flush();      // Enviar la salida al navegador
        }

        // Si el proceso ha terminado, salir del bucle
        if (feof($pipes[1])) {
            break;
        }

        // Agregar un pequeño delay para evitar uso excesivo de CPU
        usleep(100000); // 100ms
    }

    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
}
?>
