
<?php

class HIK_TCP
{
    // Configuración de la conexión
    private $ip; //= "192.168.1.6";
    private $puerto; // = 15300;

    public function __construct($ip, $puerto)
    {
        $this->ip = $ip;
        $this->puerto = $puerto;
    }

    function TCP_enviar($mensaje_usu)
    {
        // Crear un socket
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($socket === false) {
            echo "Error al crear el socket: " . socket_strerror(socket_last_error()) . PHP_EOL;
        } else {
            // Conectar al servidor
            $resultado = socket_connect($socket, $this->ip, $this->puerto);

            if ($resultado === false) {
                echo "Error al conectar al servidor: " . socket_strerror(socket_last_error($socket)) . PHP_EOL;
            } else {
                // Enviar mensaje al servidor (debe tener esa separacion en el mensaje obligatorio)
                $mensaje = $mensaje_usu . PHP_EOL;
                socket_write($socket, $mensaje, strlen($mensaje));

                //Para ver el mensaje que se esta enviando
                //echo "Mensaje enviado al servidor: $mensaje" . PHP_EOL;

                socket_close($socket);
            }
        }
    }
}
