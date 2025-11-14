<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_etapas_procesoM.php');

$controlador = new th_contr_etapas_procesoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
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


class th_contr_etapas_procesoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_etapas_procesoM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_etapa_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_etapa_id', $id)->where('th_etapa_estado', 1)->listar();
        }

        return $datos;
    }

    function insertar_editar($parametros)
    {
        // Determinar id de la plaza (acepta varias keys comunes)
        $th_pla_id = $parametros['sel_th_pla_id'] ?? $parametros['txt_th_pla_id'] ?? $parametros['th_pla_id'] ?? '';

        // Preparar datos comunes
        $datos = array(
            array('campo' => 'th_pla_id', 'dato' => $th_pla_id),
            array('campo' => 'th_etapa_nombre', 'dato' => $parametros['txt_th_etapa_nombre'] ?? ''),
            array('campo' => 'th_etapa_tipo', 'dato' => $parametros['txt_th_etapa_tipo'] ?? ''),
            array('campo' => 'th_etapa_orden', 'dato' => ($parametros['txt_th_etapa_orden'] ?? '') !== '' ? (int)$parametros['txt_th_etapa_orden'] : null),
            array('campo' => 'th_etapa_obligatoria', 'dato' => isset($parametros['chk_th_etapa_obligatoria']) ? ($parametros['chk_th_etapa_obligatoria'] ? 1 : 0) : 0),
            array('campo' => 'th_etapa_descripcion', 'dato' => $parametros['txt_th_etapa_descripcion'] ?? ''),
            array('campo' => 'th_etapa_estado', 'dato' => isset($parametros['chk_th_etapa_estado']) ? ($parametros['chk_th_etapa_estado'] ? 1 : 0) : 1),
            // auditoría
            array('campo' => 'th_etapa_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        // Inserción
        if (empty($parametros['_id'])) {
            // Verificar que no exista otra etapa activa con el mismo nombre en la misma plaza
            $cond = $this->modelo->where('th_etapa_nombre', $parametros['txt_th_etapa_nombre'] ?? '')->where('th_etapa_estado', 1);
            if ($th_pla_id !== '') {
                $cond = $cond->where('th_pla_id', $th_pla_id);
            }
            if (count($cond->listar()) == 0) {
                // agregar fecha de creación
                $datos[] = array('campo' => 'th_etapa_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));

                // insertar y obtener id (si lo necesitas)
                $id = $this->modelo->insertar_id($datos);

                // devolver 1 para indicar éxito (coherente con tus respuestas JS)
                return 1;
            } else {
                return -2; // nombre duplicado en la misma plaza
            }
        } else {
            // Edición: verificar que no exista otro registro con el mismo nombre en la misma plaza
            $cond = $this->modelo->where('th_etapa_nombre', $parametros['txt_th_etapa_nombre'] ?? '')->where('th_etapa_id !', $parametros['_id']);
            if ($th_pla_id !== '') {
                $cond = $cond->where('th_pla_id', $th_pla_id);
            }

            if (count($cond->listar()) == 0) {
                $where[0]['campo'] = 'th_etapa_id';
                $where[0]['dato'] = $parametros['_id'];

                $res = $this->modelo->editar($datos, $where);
                return $res;
            } else {
                return -2; // nombre duplicado en otro registro de la misma plaza
            }
        }
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_etapa_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_etapa_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    // Para usar en select2
    function buscar($parametros)
    {
        $lista = array();
        $concat = "th_etapa_nombre, th_etapa_descripcion";
        $datos = $this->modelo->where('th_etapa_estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['th_etapa_nombre'];
            $lista[] = array('id' => ($value['th_etapa_id']), 'text' => ($text)/*, 'data' => $value */);
        }

        return $lista;
    }
}