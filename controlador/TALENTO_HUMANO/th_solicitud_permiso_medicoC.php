<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_solicitud_permiso_medicoM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_solicitud_permisoM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_personasM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_estado_laboralM.php');


require_once(dirname(__DIR__) . '/TALENTO_HUMANO/SOLICITUDES/DOCUMENTOS/reporte_permiso_personal.php');

$controlador = new th_solicitud_permiso_medicoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
}

if (isset($_GET['listar_solicitud_pdf'])) {
    echo json_encode($controlador->listar_solicitud_pdf($_POST['id'] ?? ''));
}
if (isset($_GET['listar_solicitud_medico'])) {
    echo json_encode($controlador->listar_solicitud_medico($_POST['id'] ?? ''));
}

if (isset($_GET['listar_solicitudes_persona'])) {
    echo json_encode($controlador->listar_solicitudes_persona($_POST['id'] ?? '', $_POST['estado'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class th_solicitud_permiso_medicoC
{
    private $modelo;
    private $th_solicitud_permiso;
    private $th_personas;
    private $th_per_estado_laboral;


    function __construct()
    {
        $this->modelo = new th_solicitud_permiso_medicoM();
        $this->th_solicitud_permiso = new th_solicitud_permisoM();
        $this->th_personas = new th_personasM();
        $this->th_per_estado_laboral = new th_per_estado_laboralM();
    }


    function listar()
    {
        $datos = $this->th_solicitud_permiso->listar_personas_con_total_solicitudes();
        return $datos;
    }

    function listar_solicitudes_persona($per_id = '', $estado = 2)
    {
        $datos =  $this->th_solicitud_permiso->listar_solicitudes_persona_con_medico($per_id);

        return  $datos;
    }

    function listar_solicitud_medico($id = '')
    {

        if ($id !=  '') {
            $datos = $this->modelo->obtener_detalle_completo_solicitud( $id);
        }
        return $datos;
    }

    function listar_solicitud_pdf($id = '')
    {
        $datos_unificados = [];

        if ($id != null) {
            $res_persona = $this->th_solicitud_permiso->buscar_datos_completos_solicitud($id);

            $th_per_id = (int)$res_persona[0]['th_per_id'];
            $res_persona_estado_laboral = $this->th_per_estado_laboral->listar_estado_laboral_por_persona($th_per_id);

            $res_medico = $this->modelo->where('th_sol_per_id', $id)->where('th_sol_per_med_estado', 1)->listar();


            $persona = (isset($res_persona[0])) ? $res_persona[0] : [];
            $medico = (isset($res_medico[0])) ? $res_medico[0] : [];
            $persona_estado_laboral = (isset($res_persona_estado_laboral[0])) ? $res_persona_estado_laboral[0] : [];

            $datos_unificados = array_merge($persona, $medico, $persona_estado_laboral);
        }

        return $datos_unificados;
    }


    function insertar_editar($parametros)
    {
        $toInt = fn($v) => ($v === '' || $v === null) ? 0 : (int)$v;
        $toBool = fn($v) => ($v === '1' || $v === 1 || $v === true) ? 1 : 0;
        $toFloat = fn($v) => ($v === '' || $v === null) ? 0 : (float)$v;

        $datos = [
            ['campo' => 'th_sol_per_id', 'dato' => $parametros['id_solicitud'] ?? null],

            ['campo' => 'th_sol_per_med_reposo', 'dato' => $toBool($parametros['reposo'] ?? 0)],
            ['campo' => 'th_sol_per_med_permiso_consulta', 'dato' => $toBool($parametros['permiso_consulta'] ?? 0)],
            ['campo' => 'th_sol_per_med_tipo_enfermedad', 'dato' => $parametros['tipo_enfermedad'] ?? null],
            ['campo' => 'th_sol_per_med_codigo_idg', 'dato' => $parametros['codigo_idg'] ?? null],

            ['campo' => 'th_sol_per_med_presenta_cert_medico', 'dato' => $toBool($parametros['presenta_cert_medico'] ?? 0)],
            ['campo' => 'th_sol_per_med_presenta_cert_asistencia', 'dato' => $toBool($parametros['presenta_cert_asistencia'] ?? 0)],

            ['campo' => 'th_sol_per_med_motivo', 'dato' => $parametros['motivo'] ?? null],
            ['campo' => 'th_sol_per_med_observaciones', 'dato' => $parametros['observaciones'] ?? null],

            // FECHAS DEL DEPARTAMENTO MÉDICO
            ['campo' => 'th_sol_per_med_fecha', 'dato' => $parametros['fecha_medico'] ?? null],
            ['campo' => 'th_sol_per_med_desde', 'dato' => $parametros['desde_medico'] ?? null],
            ['campo' => 'th_sol_per_med_hasta', 'dato' => $parametros['hasta_medico'] ?? null],
            ['campo' => 'th_sol_per_med_nombre_medico', 'dato' => $parametros['nombre_medico'] ?? null],

            // FECHAS DEL PERMISO (calculadas por el usuario)
            ['campo' => 'th_sol_per_med_tipo_calculo', 'dato' => $parametros['tipo_calculo'] ?? 'fecha'],
            ['campo' => 'th_sol_per_med_fecha_principal_permiso', 'dato' => $parametros['fecha_principal_permiso'] ?? null],
            ['campo' => 'th_sol_per_med_fecha_desde_permiso', 'dato' => $parametros['desde_permiso'] ?? null],
            ['campo' => 'th_sol_per_med_fecha_hasta_permiso', 'dato' => $parametros['hasta_permiso'] ?? null],
            ['campo' => 'th_sol_per_med_total_dias', 'dato' => $toInt($parametros['total_dias'] ?? 0)],
            ['campo' => 'th_sol_per_med_total_horas', 'dato' => $toFloat($parametros['total_horas'] ?? 0)],

            ['campo' => 'th_sol_per_med_estado_solicitud', 'dato' => $parametros['estado_solicitud'] ?? 0],
            ['campo' => 'th_sol_per_med_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
            ['campo' => 'id_idg', 'dato' => $parametros['id_idg'] ?? null],
        ];

        // INSERCIÓN
        if (empty($parametros['_id'])) {
            $datos[] = ['campo' => 'th_sol_per_med_estado', 'dato' => 1];
            $datos[] = ['campo' => 'th_sol_per_med_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];

            $id_insertado = $this->modelo->insertar_id($datos);

            if ($id_insertado) {
                // Generar y guardar PDF con los datos médicos
                $this->generar_y_guardar_pdf_solicitud($parametros);
                return 1;
            }
            return 0;
        }

        // EDICIÓN
        $where = [
            ['campo' => 'th_sol_per_med_id', 'dato' => $parametros['_id']]
        ];

        $resultado = $this->modelo->editar($datos, $where);

        if ($resultado) {
            // Regenerar PDF con los datos actualizados
            $this->generar_y_guardar_pdf_solicitud($parametros);
            return 1;
        }
        return 0;
    }


    private function generar_y_guardar_pdf_solicitud($parametros)
    {
        try {
            $id_solicitud = $parametros['id_solicitud'] ?? null;

            if (!$id_solicitud) {
                error_log("Error: No se proporcionó id_solicitud para generar PDF");
                return false;
            }

            $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
            $cedula = $parametros['cedula_persona'] ?? 'TEMP';

            // Obtener datos completos para el PDF
            $datos_pdf = $this->listar_solicitud_pdf($id_solicitud);

            if (empty($datos_pdf)) {
                error_log("Error: No se encontraron datos para la solicitud $id_solicitud");
                return false;
            }

            // Definir ruta donde se guardará el PDF
            $ruta_base = dirname(__DIR__, 2) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/';
            $ruta_base .= $cedula . '/PERMISOS/';

            // Crear directorio si no existe
            if (!file_exists($ruta_base)) {
                mkdir($ruta_base, 0777, true);
            }

            // Nombre del archivo PDF
            $nombre_archivo = 'solicitud_permiso_' . $id_solicitud . '.pdf';
            $ruta_completa = $ruta_base . $nombre_archivo;

            // ========== ELIMINAR PDF ANTERIOR SI EXISTE ==========
            if (file_exists($ruta_completa)) {
                unlink($ruta_completa);
            }

            // Ruta relativa para guardar en BD
            $nombre_ruta_bd = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $cedula . '/PERMISOS/' . $nombre_archivo;

            // Generar el contenido del PDF
            $pdf_content = pdf_reporte_permiso($datos_pdf, true);

            // Guardar el archivo PDF
            file_put_contents($ruta_completa, $pdf_content);

            // Actualizar la ruta del PDF en la tabla de solicitudes (no en la tabla médica)
            $datos_update = [
                ['campo' => 'th_sol_per_ruta_solicitud', 'dato' => $nombre_ruta_bd]
            ];

            $where = [
                ['campo' => 'th_sol_per_id', 'dato' => $id_solicitud]
            ];

            $this->th_solicitud_permiso->editar($datos_update, $where);

            return true;
        } catch (Exception $e) {
            error_log("Error al generar PDF de solicitud médica: " . $e->getMessage());
            return false;
        }
    }


    function eliminar($id)
    {
        $datos = [
            ['campo' => 'th_sol_per_med_estado', 'dato' => 0],
            ['campo' => 'th_sol_per_med_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
        ];

        $where = [
            ['campo' => 'th_sol_per_med_id', 'dato' => $id]
        ];

        return $this->modelo->editar($datos, $where) ? 1 : 0;
    }
}
