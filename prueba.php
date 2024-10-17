<?php

// habilitar en apache php.inuit extension=socket
// Puerto SADP utilizado por Hikvision
$port = 37020;

// Dirección de broadcast
$broadcastAddress = '239.255.255.250';

// Tu mensaje XML
$xmlMessage =  '<?xml version="1.0" encoding="utf-8"?><Probe><Uuid>13A888A9-F1B1-4020-AE9F-05607682D23B</Uuid><Types>inquiry</Types></Probe>';
            

// Crear un socket UDP
$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

// Verificar si se creó correctamente el socket
if (!$socket) {
    die("Error al crear el socket: " . socket_strerror(socket_last_error()) . "\n");
}

// Configurar el socket para que permita el uso de broadcast
socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, 1);

// Convertir el mensaje XML a bytes
$discoveryMessage = $xmlMessage;

// Enviar el mensaje de descubrimiento por broadcast
socket_sendto($socket, $discoveryMessage, strlen($discoveryMessage), 0, $broadcastAddress, $port);
echo "Mensaje de descubrimiento enviado.\n";

// Buffer para recibir las respuestas
$buffer = '';
$from = '';
$port = 0;

// Esperar y recibir las respuestas
while (socket_recvfrom($socket, $buffer, 1024, 0, $from, $port)) {
    echo "Respuesta recibida desde: $from\n";
    echo "Datos: $buffer\n";

    // Procesar la respuesta recibida
}

// Cerrar el socket
socket_close($socket);
?>