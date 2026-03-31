<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/HOST_TIME/CATALOGOS/hub_catn_tipo_espacioM.php');


$controlador = new hub_catn_tipo_espacioC();

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


class hub_catn_tipo_espacioC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new hub_catn_tipo_espacioM();
    }

    // ================= LISTAR =================
    function listar($id = '')
    {
        return $this->modelo
            ->listar_tipo_espacio($id);
    }

    // ================= INSERTAR / EDITAR =================
    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'nombre', 'dato' => $parametros['txt_nombre']),
            array('campo' => 'descripcion', 'dato' => $parametros['txt_descripcion']),
            array('campo' => 'id_unidad_tiempo', 'dato' => $parametros['ddl_unidad_tiempo']),
            array('campo' => 'es_exclusivo', 'dato' => $parametros['chk_exclusivo']),
        );

        // ===== INSERT =====
        if ($parametros['_id'] == '') {

            if (count(
                $this->modelo
                    ->where('nombre', $parametros['txt_nombre'])
                    ->where('is_deleted', 0)
                    ->listar()
            ) == 0) {

                $datos[] = array('campo' => 'id_usuario_crea', 'dato' => $_SESSION['INICIO']['ID_USUARIO']);
                $datos[] = array('campo' => 'fecha_creacion', 'dato' => date('Y-m-d H:i:s'));

                return $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        }

        // ===== UPDATE =====
        else {

            if (count(
                $this->modelo
                    ->where('nombre', $parametros['txt_nombre'])
                    ->where('id_tipo_espacio !', $parametros['_id'])
                    ->where('is_deleted', 0)
                    ->listar()
            ) == 0) {

                $datos[] = array('campo' => 'id_usuario_modifica', 'dato' => $_SESSION['INICIO']['ID_USUARIO']);
                $datos[] = array('campo' => 'fecha_modificacion', 'dato' => date('Y-m-d H:i:s'));

                $where[0]['campo'] = 'id_tipo_espacio';
                $where[0]['dato']  = $parametros['_id'];

                return $this->modelo->editar($datos, $where);
            } else {
                return -2;
            }
        }
    }

    // ================= ELIMINAR =================
    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'is_deleted', 'dato' => 1), // 👈 CORRECTO (eliminado)
            array('campo' => 'id_usuario_modifica', 'dato' => $_SESSION['INICIO']['ID_USUARIO']),
            array('campo' => 'fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'id_tipo_espacio';
        $where[0]['dato']  = $id;

        return $this->modelo->editar($datos, $where);
    }

    // ================= BUSCAR =================
    function buscar($parametros)
    {
        $lista = array();
        $concat = "nombre,is_deleted";

        $datos = $this->modelo
            ->where('is_deleted', 0)
            ->like($concat, $parametros['query']);

        foreach ($datos as $value) {
            $lista[] = array(
                'id' => ($value['id_tipo_espacio']),
                'text' => ($value['nombre'])
            );
        }

        return $lista;
    }
}
