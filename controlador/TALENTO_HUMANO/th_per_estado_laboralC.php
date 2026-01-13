<?php
// Ajusta la ruta según tu estructura de carpetas
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_estado_laboralM.php');
require_once(dirname(__DIR__, 2)  . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_experiencia_laboralM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_informacion_adicionalM.php');



$controlador = new th_per_estado_laboralC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class th_per_estado_laboralC
{
    private $modelo;
    private $th_pos_experiencia_laboral;
    private $th_per_informacion_adicional;

    function __construct()
    {
        $this->modelo = new th_per_estado_laboralM();
        $this->th_pos_experiencia_laboral = new th_pos_experiencia_laboralM();
        $this->th_per_informacion_adicional = new th_per_informacion_adicionalM();
    }

    function listar($id)
    {
        $datos = $this->modelo->listar_estado_laboral_por_persona($id);
        $texto = '';

        foreach ($datos as $key => $value) {
            $estado_laboral = $value['estado_laboral_descripcion'] ?? 'N/A';
            $cargo = $value['cargo_nombre'] ?? 'N/A';
            $seccion = $value['seccion_descripcion'] ?? 'N/A';
            $nomina = $value['nomina_nombre'] ?? 'N/A';

            // Remuneración formateada
            $remuneracion = !empty($value['th_est_remuneracion'])
                ? '<p class="mb-1"><strong>Remuneración:</strong> $' . number_format($value['th_est_remuneracion'], 2) . '</p>'
                : '';

            // Fechas
            $fecha_contratacion = !empty($value['th_est_fecha_contratacion'])
                ? date('d/m/Y', strtotime($value['th_est_fecha_contratacion']))
                : 'N/A';

            $fecha_salida = 'Indefinido';
            if (!empty($value['th_est_fecha_salida']) && $value['th_est_fecha_salida'] !== '1900-01-01') {
                $fecha_salida = date('d/m/Y', strtotime($value['th_est_fecha_salida']));
            }

            // Badge Tipo de Cambio (Radio Button logic)
            $badge_tipo_class = ($value['th_est_check_estado_laboral'] == 1) ? 'bg-info' : 'bg-warning text-dark';
            $tipo_badge_text = ($value['th_est_check_estado_laboral'] == 1) ? 'RECATEGORIZACIÓN' : 'DADO DE BAJA';

            // Badge Estado dinámico
            $badge_class = 'bg-secondary';
            switch ($estado_laboral) {
                case 'Activo':
                    $badge_class = 'bg-success';
                    break;
                case 'Inactivo':
                    $badge_class = 'bg-danger';
                    break;
                case 'Prueba':
                    $badge_class = 'bg-warning text-dark';
                    break;
            }

            $texto .= <<<HTML
       <div class="row mb-col">
                    <div class="col-10" style="cursor: pointer;" onclick="abrir_modal_estado_laboral('{$value['_id']}')">
                        <div class="mb-2">
                            <span class="badge {$badge_class}">{$estado_laboral}</span>
                        </div>
                        <p class="mb-1"><strong>Cargo:</strong> {$cargo}</p>
                        <p class="mb-1"><strong>Sección:</strong> {$seccion}</p>
                        <p class="mb-1"><strong>Nómina:</strong> {$nomina}</p>
                        {$remuneracion}
                        <div class="d-flex gap-3 small text-muted mt-2">
                            <span><i class="bx bx-calendar"></i> Inicia: {$fecha_contratacion}</span>
                            <span><i class="bx bx-calendar-x"></i> Fin: {$fecha_salida}</span>
                        </div>
                    </div>
                    
                    <div class="col-2 text-end">
                        <button class="btn btn-sm btn-light border icon-hover" title="Editar Registro" onclick="abrir_modal_estado_laboral('{$value['_id']}')">
                            <i class="bx bx-pencil fs-5 text-dark"></i>
                        </button>
                    </div>
        </div>
HTML;
        }

        if (empty($datos)) {
            $texto = '<div class="alert alert-info text-center">No hay registros de estado laboral.</div>';
        }

        return [
            'html' => $texto,
            'tiene_registros' => !empty($datos)
        ];
    }

    function listar_modal($id)
    {
        $datos = $this->modelo->listar_estado_laboral_por_id($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $tipo_cambio = null;
        $ddl_estado_laboral = 1;



        if (isset($parametros['tipo_cambio']) && $parametros['tipo_cambio'] !== '') {
            if (in_array($parametros['tipo_cambio'], ['DADO_BAJA', 'RECATEGORIZACION'])) {
                $tipo_cambio = $parametros['tipo_cambio'];
            }
            if ($parametros['tipo_cambio'] === 'baja' || $parametros['tipo_cambio'] === 'DADO_BAJA') {
                $ddl_estado_laboral = 2;
            }
            // Si es RECATEGORIZACIÓN, asignamos 1 (o simplemente se mantiene el default)
            else if ($parametros['tipo_cambio'] === 'recategorizacion' || $parametros['tipo_cambio'] === 'RECATEGORIZACION') {
                $ddl_estado_laboral = 1;

                $estado_laboral =  $this->modelo->listar_estado_laboral_por_id($parametros['_id']);
                $empresa   = $_SESSION['INICIO']["RAZON_SOCIAL"] ?? 'Sistema';

                $datos_estado_laboral = array(
                    array('campo' => 'th_expl_nombre_empresa', 'dato' => $empresa),
                    array('campo' => 'th_expl_cargos_ocupados', 'dato' => $estado_laboral[0]['cargo_nombre']),
                    array('campo' => 'th_expl_fecha_inicio_experiencia', 'dato' => $estado_laboral[0]['th_est_fecha_contratacion']),
                    array('campo' => 'th_expl_fecha_fin_experiencia', 'dato' => date('Y-m-d H:i:s')),
                    array('campo' => 'th_expl_cbx_fecha_fin_experiencia', 'dato' => 0),
                    array('campo' => 'th_expl_responsabilidades_logros', 'dato' => 'RECATEGORIZACIÖN'),
                    array('campo' => 'th_expl_sueldo', 'dato' => $estado_laboral[0]['th_est_remuneracion']),
                    array('campo' => 'th_pos_id', 'dato' => $parametros['pos_id']),
                );
                $this->th_pos_experiencia_laboral->insertar($datos_estado_laboral);
                $tipo_cambio = null;
                

                $experiencias = $this->th_pos_experiencia_laboral
                    ->listar_experiencia_laboral_postulante($parametros['pos_id']);

                $resultado = $this->calcular_experiencia_y_remuneracion($experiencias);

                $texto = $resultado['resumen_general']['tiempo_total']['texto'];
                $promedio = round(
                    (float)$resultado['resumen_general']['remuneracion_promedio'],
                    2
                );

                $encontrado = $this->th_per_informacion_adicional
                    ->where('th_per_id', $parametros['per_id'])
                    ->listar();

                $datos_inf = [
                    ['campo' => 'th_inf_adi_tiempo_trabajo', 'dato' => $texto],
                    ['campo' => 'th_inf_adi_remuneracion_promedio', 'dato' => $promedio],
                    ['campo' => 'th_inf_adi_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
                ];

                if (empty($encontrado)) {
                    $datos_inf[] = ['campo' => 'th_per_id', 'dato' =>  $parametros['per_id']];
                    $this->th_per_informacion_adicional->insertar($datos_inf);
                } else {
                    $whereInf[0]['campo'] = 'th_inf_adi_id';
                    $whereInf[0]['dato']  = $encontrado[0]['th_inf_adi_id'];
                    $this->th_per_informacion_adicional->editar($datos_inf, $whereInf);
                }

                $datos_update = array(
                    array('campo' => 'th_est_estado', 'dato' => 0),
                    array('campo' => 'th_est_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
                );

                $where[0]['campo'] = 'th_est_id';
                $where[0]['dato'] = strval($parametros['_id']);
                $resultado = $this->modelo->editar($datos_update, $where);


                $parametros['_id'] = '';
            }
        }

        $datos = array(
            array('campo' => 'th_per_id', 'dato' => $parametros['per_id']),
            array('campo' => 'id_cargo', 'dato' => $parametros['ddl_cargo']),
            array('campo' => 'id_seccion', 'dato' => $parametros['ddl_seccion']),
            array('campo' => 'id_nomina', 'dato' => $parametros['ddl_nomina']),
            array('campo' => 'id_estado_laboral', 'dato' => $ddl_estado_laboral),
            array('campo' => 'th_est_remuneracion', 'dato' => $parametros['txt_remuneracion']),
            array('campo' => 'th_est_check_estado_laboral', 'dato' =>  $tipo_cambio), // Ahora viene del radio button
            array('campo' => 'th_est_fecha_contratacion', 'dato' => $parametros['txt_fecha_contratacion_estado']),
            array('campo' => 'th_est_fecha_salida', 'dato' => $parametros['txt_fecha_salida_estado']),
        );


        if ($parametros['_id'] == '') {
            $datos[] = array('campo' => 'th_est_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
            $resultado = $this->modelo->insertar($datos);
        } else {
            $datos[] = array('campo' => 'th_est_fecha_modificacion', 'dato' => date('Y-m-d H:i:s'));
            $where[0]['campo'] = 'th_est_id';
            $where[0]['dato'] = $parametros['_id'];
            $resultado = $this->modelo->editar($datos, $where);
        }

        return $resultado;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_est_estado', 'dato' => 0),
            array('campo' => 'th_est_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'th_est_id';
        $where[0]['dato'] = strval($id);
        $resultado = $this->modelo->editar($datos, $where);

        return $resultado;
    }


    function calcular_experiencia_y_remuneracion($experiencias)
    {
        $detalleEmpresas = [];

        $totalDias = 0;
        $totalSueldo = 0;
        $contadorSueldos = 0;

        foreach ($experiencias as $exp) {

            $fechaInicio = new DateTime($exp['th_expl_fecha_inicio_experiencia']);

            if ($exp['th_expl_cbx_fecha_fin_experiencia'] == 1 || empty($exp['th_expl_fecha_fin_experiencia'])) {
                $fechaFin = new DateTime();
            } else {
                $fechaFin = new DateTime($exp['th_expl_fecha_fin_experiencia']);
            }

            $diff = $fechaInicio->diff($fechaFin);

            $anios = $diff->y;
            $meses = $diff->m;
            $dias  = $diff->d;

            $diasTotalesExp = ($anios * 365) + ($meses * 30) + $dias;
            $totalDias += $diasTotalesExp;

            $sueldo = floatval($exp['th_expl_sueldo']);
            if ($sueldo > 0) {
                $totalSueldo += $sueldo;
                $contadorSueldos++;
            }

            $detalleEmpresas[] = [
                'empresa' => $exp['th_expl_nombre_empresa'],
                'cargo' => $exp['th_expl_cargos_ocupados'],
                'fecha_inicio' => $exp['th_expl_fecha_inicio_experiencia'],
                'fecha_fin' => ($exp['th_expl_cbx_fecha_fin_experiencia'] == 1 ? 'ACTUALIDAD' : $exp['th_expl_fecha_fin_experiencia']),
                'tiempo_trabajado' => [
                    'anios' => $anios,
                    'meses' => $meses,
                    'dias' => $dias,
                    'texto' => "{$anios} años, {$meses} meses, {$dias} días"
                ],
                'sueldo' => $sueldo
            ];
        }

        $totalAnios = floor($totalDias / 365);
        $totalMeses = floor(($totalDias % 365) / 30);
        $totalDiasFinal = ($totalDias % 365) % 30;

        $remuneracionPromedio = ($contadorSueldos > 0)
            ? round($totalSueldo / $contadorSueldos, 2)
            : 0;

        return [
            'detalle_experiencia' => $detalleEmpresas,
            'resumen_general' => [
                'tiempo_total' => [
                    'anios' => $totalAnios,
                    'meses' => $totalMeses,
                    'dias' => $totalDiasFinal,
                    'texto' => "{$totalAnios} años, {$totalMeses} meses, {$totalDiasFinal} días"
                ],
                'total_sueldo' => round($totalSueldo, 2),
                'remuneracion_promedio' => $remuneracionPromedio
            ]
        ];
    }
}
