<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_solicitud_permisoM.php');

$controlador = new th_solicitud_permisoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
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

class th_solicitud_permisoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_solicitud_permisoM();
    }

    function listar($id = '')
    {
        $datos = $this->modelo->obtener_solicitudes_persona($id);
        return $datos;
    }

    function insertar_editar($file, $parametros)
    {
        $parametros = json_decode($_POST['parametros'], true);
        $toInt = fn($v) => ($v === '' || $v === null) ? 0 : (int)$v;
        $toBoolInt = fn($v) => ($v === '1' || $v === 1 || $v === true) ? 1 : 0;

        $fecha_nacimiento = !empty($parametros['fecha_nacimiento'])
            ? date('Y-m-d H:i:s', strtotime($parametros['fecha_nacimiento']))
            : '1900-01-01 00:00:00';

        $datos = [
            ['campo' => 'th_per_id', 'dato' => $toInt($parametros['id_persona'] ?? 0)],
            ['campo' => 'th_sol_per_motivo', 'dato' => $parametros['motivo'] ?? ''],
            ['campo' => 'th_sol_per_detalle', 'dato' => $parametros['detalle'] ?? ''],
            ['campo' => 'th_sol_per_fam_hijos_adultos', 'dato' => $parametros['parentesco'] ?? null],

            ['campo' => 'th_sol_per_maternidad_paternidad', 'dato' => $toBoolInt($parametros['maternidad_paternidad'] ?? 0)],
            ['campo' => 'th_sol_per_enfermedad', 'dato' => $toBoolInt($parametros['enfermedad'] ?? 0)],
            ['campo' => 'th_sol_per_cert_nacido_vivo', 'dato' => $toBoolInt($parametros['cert_nacido_vivo'] ?? 0)],
            ['campo' => 'th_sol_per_cita_medica', 'dato' => $toBoolInt($parametros['cita_medica'] ?? 0)],
            ['campo' => 'th_sol_per_cert_medico', 'dato' => $toBoolInt($parametros['cert_medico'] ?? 0)],
            ['campo' => 'th_sol_per_cert_enfermedad', 'dato' => $toBoolInt($parametros['cert_enfermedad'] ?? 0)],

            ['campo' => 'th_sol_per_tipo_atencion', 'dato' => $parametros['tipo_atencion'] ?? null],
            ['campo' => 'th_sol_per_lugar', 'dato' => $parametros['lugar'] ?? null],
            ['campo' => 'th_sol_per_especialidad', 'dato' => $parametros['especialidad'] ?? null],
            ['campo' => 'th_sol_per_medico', 'dato' => $parametros['medico'] ?? null],

            ['campo' => 'th_sol_per_fecha_atencion', 'dato' => $parametros['fecha_atencion'] ?? date('Y-m-d H:i:s')],
            ['campo' => 'th_sol_per_hora_desde', 'dato' => $parametros['hora_desde'] ?? date('Y-m-d H:i:s')],
            ['campo' => 'th_sol_per_hora_hasta', 'dato' => $parametros['hora_hasta'] ?? date('Y-m-d H:i:s')],

            ['campo' => 'th_sol_per_fecha_desde', 'dato' => $parametros['fecha_desde']],
            ['campo' => 'th_sol_per_fecha_hasta', 'dato' => $parametros['fecha_hasta']],

            ['campo' => 'th_sol_per_total_horas', 'dato' => $parametros['total_horas'] ?? 0],
            ['campo' => 'th_sol_per_total_dias', 'dato' => $parametros['total_dias'] ?? 0],

            ['campo' => 'th_sol_per_parentesco_fecha_nacimiento', 'dato' => $fecha_nacimiento],
            ['campo' => 'th_sol_per_rango_edad', 'dato' => $parametros['rango_edad'] ?? null],
            ['campo' => 'th_sol_per_tipo_cuidado', 'dato' => $parametros['tipo_adulto'] ?? null],
            ['campo' => 'th_sol_per_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
        ];


        return;

        if (empty($parametros['_id'])) {
            $datos[] = ['campo' => 'th_sol_per_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            $datos[] = ['campo' => 'th_sol_per_estado', 'dato' => 1];
            
            $id_insertado = $this->modelo->insertar_id($datos);
            
            if ($id_insertado) {
                // Guardar archivos después de insertar
                $this->guardar_archivos($file, $parametros, $id_insertado);
                return 1;
            }
            return 0;
        }

        $where = [['campo' => 'th_sol_per_id', 'dato' => $parametros['_id']]];
        $resultado = $this->modelo->editar($datos, $where);
        
        if ($resultado) {
            // Actualizar archivos si existen
            $this->guardar_archivos($file, $parametros, $parametros['_id']);
            return 1;
        }
        return 0;
    }

    private function guardar_archivos($file, $post, $id_solicitud)
    {
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $cedula = 'TEMP' ?? 'TEMP';
        
        $ruta_base = dirname(__DIR__, 2) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/';
        $ruta_base .= $cedula . '/PERMISOS/';

        if (!file_exists($ruta_base)) {
            mkdir($ruta_base, 0777, true);
        }

        // Array de archivos a procesar
        $archivos_config = [
            'file_cert_maternidad' => [
                'campo_bd' => 'th_sol_per_ruta_cert_nacido',
                'prefijo' => 'cert_maternidad_'
            ],
            'file_cert_enfermedad' => [
                'campo_bd' => 'th_sol_per_ruta_cert_enfermedad',
                'prefijo' => 'cert_enfermedad_'
            ],
            'file_cert_cita' => [
                'campo_bd' => 'th_sol_per_ruta_cert_medico',
                'prefijo' => 'cert_cita_'
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
                    return $resultado; // Retorna error si falla
                }
            }
        }

        return 1;
    }

    private function procesar_archivo($archivo, $ruta_base, $nombre_base, $id_solicitud, $campo_bd, $cedula, $id_empresa)
    {
        if ($this->validar_formato_archivo($archivo) !== 1) {
            return -2; // Formato inválido
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

        return -1; // Error al subir archivo
    }

    private function validar_formato_archivo($archivo)
    {
        $tipos_permitidos = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (in_array($archivo['type'], $tipos_permitidos)) {
            // Validar tamaño (5MB máximo)
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