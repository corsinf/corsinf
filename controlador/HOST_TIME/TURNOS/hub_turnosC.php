<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/TURNOS/hub_turnosM.php');

$controlador = new hub_turnosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $query = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}


class hub_turnosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_turnosM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('is_deleted', 0)->listar();
        } else {
            $datos = $this->modelo->where('hub_tur_id', $id)->listar();
        }

        return $datos;
    }

    function insertar_editar($parametros)
    {
        $ahora = date('Y-m-d H:i:s');

        if ($parametros['_id'] == '') {
            // Verificar nombre duplicado
            if (count($this->modelo->where('hub_tur_nombre', $parametros['txt_nombre'])->where('is_deleted', 0)->listar()) > 0) {
                return -2;
            }

            $datos = array(
                array('campo' => 'hub_tur_nombre',        'dato' => $parametros['txt_nombre']),
                array('campo' => 'hub_tur_hora_entrada',  'dato' => $parametros['txt_hora_entrada']),
                array('campo' => 'hub_tur_hora_salida',   'dato' => $parametros['txt_hora_salida']),
                array('campo' => 'hub_tur_color',         'dato' => $parametros['txt_color']),  // ← COLOR
                array('campo' => 'is_deleted',            'dato' => 0),
                array('campo' => 'id_usuario_crea',       'dato' => $_SESSION['INICIO']['ID_USUARIO'] ?? 1),
                array('campo' => 'fecha_creacion',        'dato' => $ahora),
                array('campo' => 'id_usuario_modifica',   'dato' => $_SESSION['INICIO']['ID_USUARIO'] ?? 1),
                array('campo' => 'fecha_modificacion',    'dato' => $ahora),
            );

            $resultado = $this->modelo->insertar($datos);
        } else {
            // Verificar nombre duplicado excluyendo el registro actual
            if (count($this->modelo->where('hub_tur_nombre', $parametros['txt_nombre'])->where('hub_tur_id !', $parametros['_id'])->where('is_deleted', 0)->listar()) > 0) {
                return -2;
            }

            $datos = array(
                array('campo' => 'hub_tur_nombre',        'dato' => $parametros['txt_nombre']),
                array('campo' => 'hub_tur_hora_entrada',  'dato' => $parametros['txt_hora_entrada']),
                array('campo' => 'hub_tur_hora_salida',   'dato' => $parametros['txt_hora_salida']),
                array('campo' => 'hub_tur_color',         'dato' => $parametros['txt_color']),  // ← COLOR
                array('campo' => 'id_usuario_modifica',   'dato' => $_SESSION['INICIO']['ID_USUARIO'] ?? 1),
                array('campo' => 'fecha_modificacion',    'dato' => $ahora),
            );

            $where[0]['campo'] = 'hub_tur_id';
            $where[0]['dato']  = $parametros['_id'];

            $resultado = $this->modelo->editar($datos, $where);
        }

        return $resultado;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'is_deleted',          'dato' => 1),
            array('campo' => 'id_usuario_modifica', 'dato' => $_SESSION['INICIO']['ID_USUARIO'] ?? 1),
            array('campo' => 'fecha_modificacion',  'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'hub_tur_id';
        $where[0]['dato']  = $id;

        $resultado = $this->modelo->editar($datos, $where);
        return $resultado;
    }

    // Para usar en select2
    function buscar($parametros)
    {
        $lista  = array();
        $concat = "hub_tur_nombre, is_deleted";
        $datos  = $this->modelo->where('is_deleted', 0)->like($concat, $parametros['query']);

        foreach ($datos as $value) {
            $lista[] = array(
                'id'    => $value['hub_tur_id'],
                'text'  => $value['hub_tur_nombre'],
                'color' => $value['hub_tur_color'] ?? '#2196F3',  // ← COLOR en búsqueda
            );
        }

        return $lista;
    }
}