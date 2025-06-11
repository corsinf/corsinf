<?php
require_once(dirname(__DIR__, 1) . '/middleware/jwt_auth.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/actasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/detalle_articuloM.php');


header('Content-Type: application/json');

// ───── Ejecutar API ─────
$api = new activosE();

if ($accion == 'lista') {
    // print_r($parametros); exit(); die();
    echo json_encode($api->lista($parametros['id']));
}

if ($accion == 'lista_get') {
    echo json_encode($api->lista($id));
}

if ($accion == 'activos_koha_actualizar_856') {
    echo json_encode($api->activos_koha_actualizar_856($parametros));
}


class activosE
{
    private $auth;
    private $payload;
    private $actasM;
    private $detalle_articulo;

    public function __construct()
    {
        $this->auth = new TokenValidator();

        $decoded = $this->auth->verify();

        if (!$decoded) {
            http_response_code(401);
            echo json_encode(["error" => "Token inválido"]);
            exit;
        }

        $this->payload = json_decode(json_encode($decoded), true);
        $this->actasM = new actasM($this->payload['data']['empresa'] ?? false);
        $this->detalle_articulo = new detalle_articuloM($this->payload['data']['empresa'] ?? false);
    }

    function lista($parametros)
    {
        return $this->actasM->articulo($parametros);
    }

    function activos_koha_actualizar_856($parametros)
    {
        // print_r($parametros);die();		
        $datos = array(
            array('campo' => 'tag_unique', 'dato' => $parametros['rfid']),
        );

        $where = array(
            array('campo' => 'tag_serie', 'dato' => $parametros['sku']),
        );

        $datos = $this->detalle_articulo->editar($datos, $where);

        //KOHA
        $host = '201.218.25.218';
        $dbname = 'koha_koha_bdd';
        $user = 'ruben';
        $password = 'Ruben_2457';

        // $host = 'localhost';           // O IP del servidor MySQL
        $port = 3306;                  // Puerto MySQL por defecto
        // $dbname = 'TU_BASE_DE_DATOS';
        // $user = 'USUARIO';
        // $password = 'CONTRASEÑA';

        $p_id = $parametros['sku'];
        $p_nuevo_valor = $parametros['rfid'];

        try {
            // Conexión a MySQL con puerto especificado
            $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Llamar al procedimiento almacenado con CALL
            $stmt = $conn->prepare("CALL SP_KOHA_ACTUALIZAR_CODIGO_856_SUB_Y(?, ?)");
            $stmt->execute([$p_id, $p_nuevo_valor]);

            echo "Procedimiento ejecutado correctamente.";
        } catch (PDOException $e) {
            echo "Error de conexión o ejecución: " . $e->getMessage();
        }


        return $datos;
    }
}
