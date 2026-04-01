<?php
date_default_timezone_set('America/Guayaquil');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/RESERVAS/hub_reservasM.php');

$controlador = new hub_reservasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['crear_reserva'])) {
    $id_usuario = $_SESSION['INICIO']['ID_USUARIO'] ?? 1;

    $_POST['parametros']['id_usuario'] = $id_usuario;

    echo json_encode($controlador->crear_reserva($_POST['parametros']));
}

if (isset($_GET['listar_detalle'])) {
    echo json_encode($controlador->listar_detalle($_POST['id'] ?? ''));
}

class hub_reservasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_reservasM();
    }

    function crear_reserva($parametros)
    {
        return $this->modelo->ejecutar_crear_reserva($parametros);
    }

    function listar_detalle($id = '')
    {
        return $this->modelo->listar_reservas_detalle($id);
    }

    function listar($id = '') {}

    function insertar_editar($parametros)
    {
        $ahora = date('Y-m-d H:i:s');
        $id_usuario = $_SESSION['INICIO']['ID_USUARIO'] ?? 1;

        $datos = array(
            array('campo' => 'codigo',             'dato' => $parametros['txt_codigo']),
            array('campo' => 'th_per_id',          'dato' => $parametros['ddl_persona']),
            array('campo' => 'id_espacio',         'dato' => $parametros['ddl_espacio']),
            array('campo' => 'inicio',             'dato' => $parametros['txt_inicio']),
            array('campo' => 'fin',                'dato' => $parametros['txt_fin']),
            array('campo' => 'observaciones',      'dato' => $parametros['txt_observaciones']),
            array('campo' => 'id_estado_reservas', 'dato' => $parametros['ddl_estado'] ?? 1),
            array('campo' => 'id_usuario_modifica', 'dato' => $id_usuario),
            array('campo' => 'fecha_modificacion', 'dato' => $ahora),
        );

        if (empty($parametros['_id'])) {
            // Insertar
            $datos[] = array('campo' => 'is_deleted',      'dato' => 0);
            $datos[] = array('campo' => 'id_usuario_crea', 'dato' => $id_usuario);
            $datos[] = array('campo' => 'fecha_creacion',  'dato' => $ahora);

            $resultado = $this->modelo->insertar($datos);
        } else {
            // Editar
            $where[0]['campo'] = 'id_reserva';
            $where[0]['dato']  = $parametros['_id'];

            $resultado = $this->modelo->editar($datos, $where);
        }

        return $resultado;
    }

    function eliminar($id)
    {
        // Borrado lógico: is_deleted = 1
        $datos = array(
            array('campo' => 'is_deleted',          'dato' => 1),
            array('campo' => 'id_usuario_modifica', 'dato' => $_SESSION['INICIO']['ID_USUARIO'] ?? 1),
            array('campo' => 'fecha_modificacion',  'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'id_reserva';
        $where[0]['dato']  = $id;

        return $this->modelo->editar($datos, $where);
    }
}
