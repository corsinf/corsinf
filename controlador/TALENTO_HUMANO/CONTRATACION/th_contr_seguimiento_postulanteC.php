<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_seguimiento_postulanteM.php');

$controlador = new th_contr_seguimiento_postulanteC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}


if (isset($_GET['listar_todos'])) {
    echo json_encode($controlador->listar_todos($_POST['id_plaza'] ?? '',$_POST['id_etapa'] ?? '', $_POST['id_pos'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {

    $query = $_GET['q'] ?? '';
    $parametros = array('query' => $query);

    echo json_encode($controlador->buscar($parametros));
}


class th_contr_seguimiento_postulanteC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_seguimiento_postulanteM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            // listar todos los activos
            $datos = $this->modelo->where('th_seg_estado', 1)->listar();
        } else {
            // listar uno en específico
            $datos = $this->modelo
                ->where('th_seg_id', $id)
                ->where('th_seg_estado', 1)
                ->listar();
        }

        return $datos;
    }
    function listar_todos($id_plaza = '', $id_etapa = '', $id_pos = '')
    {
        $datos = $this->modelo->listar_seguimiento_postulante($id_plaza,$id_etapa,$id_pos);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        // Campos comunes
        $datos = array(
            array('campo' => 'th_posu_id', 'dato' => $parametros['txt_th_posu_id'] ?? ''),
            array('campo' => 'th_etapa_id', 'dato' => $parametros['txt_th_etapa_id'] ?? ''),
            array('campo' => 'th_seg_fecha_programada', 'dato' => $parametros['txt_th_seg_fecha_programada'] ?? null),
            array('campo' => 'th_seg_fecha_realizada', 'dato' => $parametros['txt_th_seg_fecha_realizada'] ?? null),
            array('campo' => 'th_seg_calificacion', 'dato' => $parametros['txt_th_seg_calificacion'] ?? null),
            array('campo' => 'th_seg_resultado', 'dato' => $parametros['txt_th_seg_resultado'] ?? null),
            array('campo' => 'th_seg_responsable_persona_id', 'dato' => $parametros['txt_th_seg_responsable_persona_id'] ?? null),
            array('campo' => 'th_seg_observaciones', 'dato' => $parametros['txt_th_seg_observaciones'] ?? ''),
            array('campo' => 'th_seg_documentos_json', 'dato' => $parametros['txt_th_seg_documentos_json'] ?? null),
            array('campo' => 'th_seg_estado', 'dato' => isset($parametros['chk_th_seg_estado']) ? ($parametros['chk_th_seg_estado'] ? 1 : 0) : 1),

            // auditoría
            array('campo' => 'th_seg_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        // Insertar
        if (empty($parametros['_id'])) {

            // Insertar fecha de creación
            $datos[] = array('campo' => 'th_seg_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));

            $id = $this->modelo->insertar_id($datos);

            return 1;
        }

        // Editar
        $where[0]['campo'] = 'th_seg_id';
        $where[0]['dato'] = $parametros['_id'];

        return $this->modelo->editar($datos, $where);
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_seg_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_seg_id';
        $where[0]['dato'] = $id;

        return $this->modelo->editar($datos, $where);
    }

    // Select2
    function buscar($parametros)
    {
        $lista = array();

        // Buscar por etapa o postulante
        $concat = "th_etapa_id, th_posu_id";

        $datos = $this->modelo
            ->where('th_seg_estado', 1)
            ->like($concat, $parametros['query']);

        foreach ($datos as $value) {
            $text = "Postulante {$value['th_posu_id']} - Etapa {$value['th_etapa_id']}";
            $lista[] = array(
                'id' => $value['th_seg_id'],
                'text' => $text
            );
        }

        return $lista;
    }
}