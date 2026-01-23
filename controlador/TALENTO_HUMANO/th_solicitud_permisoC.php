<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_solicitud_permiso_medicoM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_solicitud_permisoM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_personasM.php');

require_once(dirname(__DIR__) . '/TALENTO_HUMANO/SOLICITUDES/DOCUMENTOS/reporte_permiso_personal.php');

$controlador = new th_solicitud_permisoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['th_per_id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_FILES, $_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $query = $_GET['q'] ?? '';
    echo json_encode($controlador->buscar(['query' => $query]));
}

if (isset($_GET['obtener_ruta_pdf'])) {
    echo json_encode($controlador->obtener_ruta_pdf($_POST['id']));
}

class th_solicitud_permisoC
{
    private $modelo;
    private $th_personas;
    private $th_solicitud_permiso_medico;

    function __construct()
    {
        $this->modelo = new th_solicitud_permisoM();
        $this->th_solicitud_permiso_medico = new th_solicitud_permiso_medicoM();
        $this->th_personas = new th_personasM();
    }

    function listar($id = '', $th_per_id = '')
    {
        $datos = $this->modelo->obtener_solicitudes_persona($id, $th_per_id);
        return $datos;
    }

    function obtener_ruta_pdf($id)
    {
        $datos = $this->modelo->where('th_sol_per_id', $id)->listar();

        if (!empty($datos[0]['th_sol_per_ruta_solicitud'])) {
            return [
                'ruta' => $datos[0]['th_sol_per_ruta_solicitud']
            ];
        }

        return ['ruta' => null];
    }

    function insertar_editar($file, $parametros)
    {
        $parametros = json_decode($_POST['parametros'], true);
        $toInt = fn($v) => ($v === '' || $v === null) ? 0 : (int)$v;
        $toBoolInt = fn($v) => ($v === '1' || $v === 1 || $v === true) ? 1 : 0;

        $fecha_nacimiento = !empty($parametros['fecha_nacimiento'])
            ? date('Y-m-d H:i:s', strtotime($parametros['fecha_nacimiento']))
            : '1900-01-01 00:00:00';

        // Determinar el motivo según el tipo seleccionado
        $tipo_motivo = $parametros['tipo_motivo'] ?? 'MOTIVO_PERSONAL';
        $motivo = '';
        
        if ($tipo_motivo === 'MOTIVO_PERSONAL') {
            $motivo = $parametros['motivo'] ?? '';
        } else if ($tipo_motivo === 'MOTIVO_MEDICO') {
            $motivo = $parametros['motivo_medico'] ?? '';
        }

        // Determinar si adjuntó certificado
        $certificado_adjunto = 0;
        if (!empty($file['file_certificado']['tmp_name'])) {
            $certificado_adjunto = 1;
        } else if (!empty($parametros['ruta_certificado_actual'])) {
            $certificado_adjunto = 1;
        }

        $datos = [
            ['campo' => 'th_per_id', 'dato' => $toInt($parametros['id_persona'] ?? 0)],
            ['campo' => 'th_sol_per_tipo_motivo', 'dato' => $tipo_motivo],
            ['campo' => 'th_sol_per_motivo', 'dato' => $motivo],
            ['campo' => 'th_sol_per_detalle', 'dato' => $parametros['detalle'] ?? ''],
            ['campo' => 'th_sol_per_fam_hijos_adultos', 'dato' => $parametros['parentesco'] ?? null],
            ['campo' => 'th_sol_per_certificado_adjunto', 'dato' => $certificado_adjunto],
            ['campo' => 'th_sol_per_tipo_atencion', 'dato' => $parametros['tipo_atencion'] ?? null],
            ['campo' => 'th_sol_per_lugar', 'dato' => $parametros['lugar'] ?? null],
            ['campo' => 'th_sol_per_especialidad', 'dato' => $parametros['especialidad'] ?? null],
            ['campo' => 'th_sol_per_medico', 'dato' => $parametros['medico'] ?? null],
            ['campo' => 'th_sol_per_fecha_atencion', 'dato' => $parametros['fecha_atencion'] ?? date('Y-m-d H:i:s')],
            ['campo' => 'th_sol_per_hora_desde', 'dato' => $parametros['hora_desde'] ?? date('Y-m-d H:i:s')],
            ['campo' => 'th_sol_per_hora_hasta', 'dato' => $parametros['hora_hasta'] ?? date('Y-m-d H:i:s')],
            ['campo' => 'th_sol_per_parentesco_fecha_nacimiento', 'dato' => $fecha_nacimiento],
            ['campo' => 'th_sol_per_rango_edad', 'dato' => $parametros['rango_edad'] ?? null],
            ['campo' => 'th_sol_per_tipo_cuidado', 'dato' => $parametros['tipo_adulto'] ?? null],
            ['campo' => 'th_sol_per_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
            ['campo' => 'th_ppa_id', 'dato' => $parametros['th_ppa_id'] ?? null],
            ['campo' => 'th_sol_per_tipo_solicitud', 'dato' => $parametros['tipo_asunto'] ?? null],
            ['campo' => 'th_sol_per_planificacion', 'dato' => $parametros['planificacion'] ?? 0],
        ];

        if (empty($parametros['_id'])) {
            // INSERTAR NUEVO
            $datos[] = ['campo' => 'th_sol_per_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            $datos[] = ['campo' => 'th_sol_per_estado', 'dato' => 1];

            $id_insertado = $this->modelo->insertar_id($datos);

            if ($id_insertado) {
                $this->guardar_archivos($file, $parametros, $id_insertado);
                $this->generar_y_guardar_pdf_solicitud($parametros, $id_insertado);
                return 1;
            }
            return 0;
        }

        // ACTUALIZAR EXISTENTE
        $where = [['campo' => 'th_sol_per_id', 'dato' => $parametros['_id']]];
        $resultado = $this->modelo->editar($datos, $where);

        if ($resultado) {
            $this->guardar_archivos($file, $parametros, $parametros['_id']);
            $this->generar_y_guardar_pdf_solicitud($parametros, $parametros['_id']);
            return 1;
        }
        return 0;
    }

    private function generar_y_guardar_pdf_solicitud($parametros, $id_solicitud)
    {
        try {
            $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
            $cedula = $parametros['cedula_persona'] ?? 'TEMP';

            $ruta_base = dirname(__DIR__, 2) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/';
            $ruta_base .= $cedula . '/PERMISOS/';

            if (!file_exists($ruta_base)) {
                mkdir($ruta_base, 0777, true);
            }

            $nombre_archivo = 'solicitud_permiso_' . $id_solicitud . '.pdf';
            $ruta_completa = $ruta_base . $nombre_archivo;
            $nombre_ruta_bd = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $cedula . '/PERMISOS/' . $nombre_archivo;

            $datos_pdf = $this->obtener_datos_completos_solicitud($id_solicitud);

            require_once(dirname(__DIR__) . '/TALENTO_HUMANO/SOLICITUDES/DOCUMENTOS/reporte_permiso_personal.php');

            $pdf_content = pdf_reporte_permiso($datos_pdf, true);
            file_put_contents($ruta_completa, $pdf_content);

            $datos_update = [
                ['campo' => 'th_sol_per_ruta_solicitud', 'dato' => $nombre_ruta_bd]
            ];

            $where = [
                ['campo' => 'th_sol_per_id', 'dato' => $id_solicitud]
            ];

            $this->modelo->editar($datos_update, $where);

            return true;
        } catch (Exception $e) {
            error_log("Error al generar PDF de solicitud: " . $e->getMessage());
            return false;
        }
    }

    private function obtener_datos_completos_solicitud($id)
    {
        $datos_unificados = [];

        if ($id != null) {
            $res_persona = $this->modelo->buscar_datos_completos_solicitud($id);
            $res_medico = $this->th_solicitud_permiso_medico->where('th_sol_per_id', $id)->where('th_sol_per_med_estado', 1)->listar();

            $persona = (isset($res_persona[0])) ? $res_persona[0] : [];
            $medico = (isset($res_medico[0])) ? $res_medico[0] : [];

            $datos_unificados = array_merge($persona, $medico);
        }

        return $datos_unificados;
    }

    private function guardar_archivos($file, $post, $id_solicitud)
    {
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $cedula = $post['cedula_persona'] ?? 'TEMP';

        $ruta_base = dirname(__DIR__, 2) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/';
        $ruta_base .= $cedula . '/PERMISOS/';

        if (!file_exists($ruta_base)) {
            mkdir($ruta_base, 0777, true);
        }

        // CONFIGURACIÓN DE ARCHIVOS SIMPLIFICADA
        $archivos_config = [
            'file_certificado' => [
                'campo_bd' => 'th_sol_per_ruta_certificado',
                'prefijo' => 'certificado_',
                'ruta_actual' => $post['ruta_certificado_actual'] ?? null
            ],
            'file_act_defuncion' => [
                'campo_bd' => 'th_sol_per_ruta_act_defuncion',
                'prefijo' => 'act_defuncion_',
                'ruta_actual' => $post['ruta_act_defuncion_actual'] ?? null
            ]
        ];

        foreach ($archivos_config as $file_input => $config) {
            if (isset($file[$file_input]) && $file[$file_input]['tmp_name'] != '') {
                $resultado = $this->procesar_archivo(
                    $file[$file_input],
                    $ruta_base,
                    $config['prefijo'] . $id_solicitud,
                    $id_solicitud,
                    $config['campo_bd'],
                    $cedula,
                    $id_empresa
                );

                if ($resultado !== 1) {
                    return $resultado;
                }
            } else {
                if (!empty($config['ruta_actual'])) {
                    $datos = [
                        ['campo' => $config['campo_bd'], 'dato' => $config['ruta_actual']]
                    ];

                    $where = [
                        ['campo' => 'th_sol_per_id', 'dato' => $id_solicitud]
                    ];

                    $this->modelo->editar($datos, $where);
                }
            }
        }

        return 1;
    }

    private function procesar_archivo($archivo, $ruta_base, $nombre_base, $id_solicitud, $campo_bd, $cedula, $id_empresa)
    {
        if ($this->validar_formato_archivo($archivo) !== 1) {
            return -2;
        }

        $uploadfile_temporal = $archivo['tmp_name'];
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombre_archivo = $nombre_base . '.' . $extension;
        $ruta_completa = $ruta_base . $nombre_archivo;

        $nombre_ruta_bd = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $cedula . '/PERMISOS/' . $nombre_archivo;

        if (is_uploaded_file($uploadfile_temporal)) {
            if (move_uploaded_file($uploadfile_temporal, $ruta_completa)) {
                $datos = [
                    ['campo' => $campo_bd, 'dato' => $nombre_ruta_bd]
                ];

                $where = [
                    ['campo' => 'th_sol_per_id', 'dato' => $id_solicitud]
                ];

                $resultado = $this->modelo->editar($datos, $where);
                return $resultado == 1 ? 1 : -1;
            }
        }

        return -1;
    }

    private function validar_formato_archivo($archivo)
    {
        $tipos_permitidos = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (in_array($archivo['type'], $tipos_permitidos)) {
            if ($archivo['size'] <= 5 * 1024 * 1024) {
                return 1;
            }
        }

        return -1;
    }

    function eliminar($id)
    {
        $datos = [
            ['campo' => 'th_sol_per_estado', 'dato' => 'ANULADO'],
            ['campo' => 'th_sol_per_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
        ];

        $where = [
            ['campo' => 'th_sol_per_id', 'dato' => $id]
        ];

        $res = $this->modelo->editar($datos, $where);
        return ($res) ? 1 : 0;
    }

    function buscar($parametros)
    {
        $lista = [];
        $query = isset($parametros['query']) ? trim($parametros['query']) : '';

        $datos = $this->modelo->where('th_sol_per_estado', '!=', 'ANULADO')->listar();

        foreach ($datos as $row) {
            $texto_busqueda = implode(' ', [
                $row['th_sol_per_motivo'] ?? '',
                $row['th_sol_per_lugar'] ?? '',
                $row['th_sol_per_medico'] ?? '',
                $row['th_sol_per_especialidad'] ?? ''
            ]);

            if ($query === '' || stripos($texto_busqueda, $query) !== false) {
                $lista[] = [
                    'id' => $row['th_sol_per_id'],
                    'text' => $row['th_sol_per_motivo'] . ' - ' . ($row['th_sol_per_fecha_desde'] ?? ''),
                    'data' => $row
                ];
            }
        }

        return $lista;
    }
}