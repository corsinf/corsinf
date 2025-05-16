<?php
require_once(dirname(__DIR__, 2) . '/db/db.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');
require_once(dirname(__DIR__, 1) . '/utils/jwt.php');

class Auth
{
    private $db;
    private $jwtHandler;
    private $cod_global;

    public function __construct()
    {
        $this->db = new db();
        $this->cod_global = new codigos_globales();

        // Crear instancia de JWTHandler
        $this->jwtHandler = new JWTHandler();
    }

    // Método para procesar el login
    public function login($data)
    {
        // Validar que los campos estén presentes
        if (!isset($data->usuario) || !isset($data->clave) || !isset($data->empresa)) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan campos"]);
            exit;
        }

        // $data = new stdClass();
        // $data->usuario = 'paco@pepe.com';
        // $data->empresa = 'woieruasdfhn123466';

        $this->db->modificar_parametros_db($data->empresa);

        // Preparar la consulta para obtener el usuario
        $sql = "SELECT TOP 1 id_usuarios, nombres, apellidos, password FROM usuarios WHERE email = '$data->usuario';";
        $user = $this->db->datos($sql);



        if ($user && ($data->clave == $this->cod_global->desenciptar_clave($user[0]['password']))) {
            // Generar el token JWT
            $token = $this->jwtHandler->generateToken(
                [
                    'id' => $user[0]['id_usuarios'],
                    'usuario' => $user[0]['nombres'],
                    'empresa' => $data->empresa,
                ]
            );
            echo json_encode(["token" => $token]);
        } else {
            // Si las credenciales son incorrectas
            http_response_code(401);
            echo json_encode(["error" => "Credenciales inválidas"]);
        }
    }
}

// Lógica para recibir el input de la solicitud
$data = file_get_contents("php://input");
$data = json_decode($data,true);
$data = json_decode(json_encode($data));

// $data = $_POST;

// print_r($data);die();

// Crear la instancia de la clase Auth y llamar al método login
$auth = new Auth();
$auth->login($data);
