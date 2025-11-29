<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_seguimiento_postulanteM.php');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_etapas_procesoM.php');

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
if (isset($_GET['editar'])) {
    echo json_encode($controlador->editar_seguimiento($_POST['parametros']));
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
    private $plaza_etapas_proceso;

    function __construct()
    {
        $this->modelo = new th_contr_seguimiento_postulanteM();

        $this->plaza_etapas_proceso = new th_contr_plaza_etapas_procesoM();
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
        $datos = $this->modelo->listar_seguimiento_postulante_plaza($id_plaza,$id_etapa,$id_pos);
        return $datos;
    }
     public function editar_seguimiento($parametros)
{
    // Helpers (misma lógica que usas en insertar_editar)
    $toDateTime = function ($val) {
        if ($val === null || $val === '') return null;
        $val = str_replace('T', ' ', $val);
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $val)) {
            $val .= ':00';
        }
        $ts = strtotime($val);
        return $ts ? date('Y-m-d H:i:s', $ts) : null;
    };
    $toInt = function ($v) { return ($v === '' || $v === null) ? null : (int)$v; };
    $toFloat = function ($v) { return ($v === '' || $v === null) ? null : (float)$v; };
    $toBoolInt = function ($v) { return ($v === 1 || $v === '1' || $v === true || $v === 'true') ? 1 : 0; };

    // requiere id para editar
    if (empty($parametros['_id'])) {
        return -2; // identificar error (sin id)
    }

    // Preparar datos a actualizar (solo los campos editables)
    $datos = array(
        array('campo' => 'th_posu_id', 'dato' => $toInt($parametros['txt_th_posu_id'] ?? null)),
        array('campo' => 'th_etapa_id', 'dato' => $toInt($parametros['txt_th_etapa_id'] ?? null)),
        array('campo' => 'th_seg_fecha_programada', 'dato' => $toDateTime($parametros['txt_th_seg_fecha_programada'] ?? null)),
        array('campo' => 'th_seg_fecha_realizada', 'dato' => $toDateTime($parametros['txt_th_seg_fecha_realizada'] ?? null)),
        array('campo' => 'th_seg_calificacion', 'dato' => $toFloat($parametros['txt_th_seg_calificacion'] ?? null)),
        array('campo' => 'th_seg_resultado', 'dato' => $parametros['txt_th_seg_resultado'] ?? null),
        // responsable viene desde ddl_responsable en tu JS
        array('campo' => 'th_seg_responsable_persona_id', 'dato' => $toInt($parametros['ddl_responsable'] ?? null)),
        array('campo' => 'th_seg_observaciones', 'dato' => $parametros['txt_th_seg_observaciones'] ?? null),
        array('campo' => 'th_seg_documentos_json', 'dato' => $parametros['txt_th_seg_documentos_json'] ?? null),
        array('campo' => 'th_seg_estado', 'dato' => 1),
        array('campo' => 'th_seg_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
    );

    // Where por id
    $where = array();
    $where[0]['campo'] = 'th_seg_id';
    $where[0]['dato']  = $parametros['_id'];

    // Ejecutar update
    $res = $this->modelo->editar($datos, $where);

    // devolver id si ok, 0 si fallo
    return ($res) ? 1 : 0;
}


  function insertar_editar($parametros)
    {
    // Validar entrada mínima
    if (!isset($parametros['postulantes_seleccionadas']) || 
        !is_array($parametros['postulantes_seleccionadas']) || 
        count($parametros['postulantes_seleccionadas']) == 0) {
        return -2;
    }

    $postulantes = $parametros['postulantes_seleccionadas'];
    $pla_id = isset($parametros['th_pla_id']) ? intval($parametros['th_pla_id']) : 0;
    
    if ($pla_id <= 0) {
        return ['ok' => false, 'msg' => 'Falta th_pla_id'];
    }

    // Extraer IDs de postulantes
    $postulantes_ids = array_map(function($p) {
        return is_array($p) ? intval($p['id']) : intval($p);
    }, $postulantes);

    // Listar etapas faltantes usando la función optimizada (USAR v3 que es la más compatible)
    $etapas_faltantes = $this->modelo->listar_etapas_faltantes_postulantes_v3($pla_id, $postulantes_ids);

    if (empty($etapas_faltantes)) {
        return [
            'ok' => true, 
            'msg' => 'Todos los postulantes ya tienen todas las etapas asignadas',
            'etapas_creadas' => 0
        ];
    }

    // Insertar cada etapa faltante con el formato array campo/dato
    $now = date('Y-m-d H:i:s');
    $etapas_creadas = 0;
    $errores = [];

    foreach ($etapas_faltantes as $faltante) {
        $datos_insertar = array(
            array('campo' => 'th_posu_id', 'dato' => $faltante['th_posu_id']),
            array('campo' => 'th_etapa_id', 'dato' => $faltante['th_eta_id']),
            array('campo' => 'th_seg_fecha_programada', 'dato' => null),
            array('campo' => 'th_seg_fecha_realizada', 'dato' => null),
            array('campo' => 'th_seg_calificacion', 'dato' => null),
            array('campo' => 'th_seg_resultado', 'dato' => null),
            array('campo' => 'th_seg_responsable_persona_id', 'dato' => null),
            array('campo' => 'th_seg_observaciones', 'dato' => ''),
            array('campo' => 'th_seg_documentos_json', 'dato' => null),
            array('campo' => 'th_seg_estado', 'dato' => 1),
            array('campo' => 'th_seg_fecha_creacion', 'dato' => $now),
            array('campo' => 'th_seg_fecha_modificacion', 'dato' => $now)
        );

        $resultado = $this->modelo->insertar_id($datos_insertar);
        
        if ($resultado > 0) {
            $etapas_creadas++;
        } else {
            $errores[] = "Error al crear etapa {$faltante['th_eta_id']} para postulante {$faltante['th_posu_id']}";
        }
    }

    $mensaje = "{$etapas_creadas} de " . count($etapas_faltantes) . " etapas creadas exitosamente";
    if (!empty($errores)) {
        $mensaje .= ". Errores: " . implode(', ', $errores);
    }

    return [
        'ok' => $etapas_creadas > 0,
        'msg' => $mensaje,
        'etapas_creadas' => $etapas_creadas,
        'total_faltantes' => count($etapas_faltantes),
        'errores' => $errores
    ];
    }
// Función auxiliar para obtener resumen de etapas por postulante
function obtener_resumen_etapas_postulante($postulante_id, $plaza_id)
{
    // Obtener etapas de la plaza
    $etapas_plaza = $this->plaza_etapas_proceso
        ->where('th_pla_id', $plaza_id)
        ->where('th_pla_eta_estado', 1)
        ->listar();

    $resumen = [];
    
    foreach ($etapas_plaza as $etapa) {
        $etapa_id = is_array($etapa) ? $etapa['th_eta_id'] : $etapa;
        
        // Verificar si existe seguimiento
        $seguimiento = $this->modelo
            ->where('th_posu_id', $postulante_id)
            ->where('th_etapa_id', $etapa_id)
            ->where('th_seg_estado', 1)
            ->listar();
        
        $resumen[] = [
            'etapa_id' => $etapa_id,
            'existe' => !empty($seguimiento),
            'seguimiento' => !empty($seguimiento) ? $seguimiento[0] : null
        ];
    }
    
    return $resumen;
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