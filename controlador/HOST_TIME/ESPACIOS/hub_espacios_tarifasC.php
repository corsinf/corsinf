<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/ESPACIOS/hub_espacios_tarifasM.php');

$controlador = new hub_espacios_tarifasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['id_espacio'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class hub_espacios_tarifasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_espacios_tarifasM();
    }

    function listar($id = '', $id_espacio = '')
    {
        if ($id) {
            $datos = $this->modelo->listar_espacio_tarifa($id);
        } elseif ($id_espacio) {
            $datos = $this->modelo->listar_espacio_tarifa('', $id_espacio);
        } else {
            $datos = $this->modelo->listar_espacio_tarifa();
        }
        return $datos;
    }

    public function insertar_editar($parametros)
    {
        $id_usuario = $_SESSION['INICIO']['ID_USUARIO'] ?? null;
        $fecha = date('Y-m-d H:i:s');

        $datos = [
            ['campo' => 'id_espacio',       'dato' => (int)$parametros['ddl_espacio']],
            ['campo' => 'id_unidad_tiempo',  'dato' => (int)$parametros['ddl_unidad']],
            ['campo' => 'cantidad',          'dato' => (int)$parametros['txt_cantidad']],
            ['campo' => 'precio',            'dato' => (float)$parametros['txt_precio']],
            ['campo' => 'nombre_plan',       'dato' => trim($parametros['txt_nombre_plan'])],
            ['campo' => 'is_deleted',        'dato' => 0],
        ];

        if (empty($parametros['_id'])) {
            $datos[] = ['campo' => 'id_usuario_crea',  'dato' => $id_usuario];
            $datos[] = ['campo' => 'fecha_creacion',   'dato' => $fecha];

            return $this->modelo->insertar($datos);
        } else {
            $datos[] = ['campo' => 'id_usuario_modifica', 'dato' => $id_usuario];
            $datos[] = ['campo' => 'fecha_modificacion',  'dato' => $fecha];

            $where[] = ['campo' => 'id_espacio_tarifa', 'dato' => (int)$parametros['_id']];

            return $this->modelo->editar($datos, $where);
        }
    }

    public function eliminar($id)
    {
        $datos[] = ['campo' => 'is_deleted', 'dato' => 1];
        $where[] = ['campo' => 'id_espacio_tarifa', 'dato' => (int)$id];

        return $this->modelo->editar($datos, $where);
    }
}
