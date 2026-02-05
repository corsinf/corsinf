<?php
require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_experiencia_laboralM.php');
require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/th_per_informacion_adicionalM.php');
require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_referencias_laboralesM.php');

$controlador = new th_pos_experiencia_laboralC();

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
    echo json_encode($controlador->eliminar($_POST['id'] ?? '', $_POST['id_persona'] ?? '', $_POST['id_postulante'] ?? ''));
}


class th_pos_experiencia_laboralC
{
    private $modelo;
    private $th_per_informacion_adicional;
    private $th_pos_referencias_laborales;

    function __construct()
    {
        $this->modelo = new th_pos_experiencia_laboralM();
        $this->th_per_informacion_adicional = new th_per_informacion_adicionalM();
        $this->th_pos_referencias_laborales = new th_pos_referencias_laboralesM();
    }

    //Funcion para listar la experiencia previa del postulante
    function listar($id)
    {
        $datos = $this->modelo->listar_experiencia_laboral_postulante($id);

        if (empty($datos)) {
            return <<<HTML
                        <div class="d-flex align-items-center bg-white border border-start-0 shadow-sm rounded-3" 
                            style="border-left: 4px solid !important; padding: 12px 24px; max-width: 600px;">
                        
                        <i class='bx bx-data me-3 text-primary' style='font-size: 28px;'></i>

                        <div class="lh-sm">
                            <div class="text-dark fw-bold mb-1" style="font-size: 1rem;">
                            Sin registros en este apartado
                            </div>
                            <div class="text-secondary" style="font-size: 0.85rem;">
                            No hemos encontrado información disponible para mostrar en esta sección.
                            </div>
                        </div>
                        </div>
                    HTML;
        } else {
            $texto = '<div class="row g-3">';

            foreach ($datos as $key => $value) {
                // Buscamos las referencias asociadas
                $referencias = $this->th_pos_referencias_laborales
                    ->where('th_expl_id', $value['_id'])
                    ->where('th_refl_estado', 1)
                    ->listar();

                $html_referencias = "";

                // SI EXISTEN REFERENCIAS, SOLO TOMAMOS LA PRIMERA [0]
                if (!empty($referencias)) {
                    $ref = $referencias[0]; // Accedemos solo al primer elemento

                    $html_referencias = <<<HTML
                <div class="d-flex align-items-center justify-content-between bg-white border rounded-2 p-2 mb-1 shadow-xs">
                    <div style="line-height: 1.1;">
                        <div class="fw-bold text-primary" style="font-size: 0.65rem;">
                            <i class="bx bx-user me-1"></i>{$ref['th_refl_nombre_referencia']}
                        </div>
                        <div class="text-muted" style="font-size: 0.6rem;">
                            <i class="bx bx-phone me-1"></i>{$ref['th_refl_telefono_referencia']}
                        </div>
                    </div>
                    <button type="button" class="btn btn-link text-info p-0" 
                onclick="abrir_modal_experiencia_referencias_laborales('{$ref['_id']}','{$value['_id']}');" 
                title="Ver Detalles">
            <i class="bx bx-pencil" style="font-size: 1rem;"></i>
        </button>
                </div>
HTML;
                } else {
                    $html_referencias = '<p class="text-muted mb-0 text-center" style="font-size: 0.65rem; font-style: italic;">Sin referencias</p>';
                }

                $fecha_inicio_experiencia = date('d/m/Y', strtotime($value['th_expl_fecha_inicio_experiencia']));
                $fecha_fin_experiencia = $value['th_expl_cbx_fecha_fin_experiencia'] == 1
                    ? '<span class="fw-bold text-success">Actualidad</span>'
                    : date('d/m/Y', strtotime($value['th_expl_fecha_fin_experiencia']));

                $sueldo_actual = number_format($value['th_expl_sueldo'], 2, '.', ',');

                $texto .= <<<HTML
            <div class="col-md-6 mb-col">
                <div class="cert-card p-3 h-100 position-relative shadow-sm">
                    <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                            onclick="abrir_modal_experiencia_laboral('{$value['_id']}');" 
                            title="Editar Experiencia">
                        <i class="bx bx-pencil"></i>
                    </button>

                    <div class="d-flex flex-column h-100">
                        <div class="mb-2">
                            <span class="cert-badge mb-1" style="background-color: #e8f5e9; color: #2e7d32;">Experiencia Laboral</span>
                            <h6 class="fw-bold text-dark cert-title mb-1">{$value['th_expl_nombre_empresa']}</h6>
                            <p class="cert-doctor mb-2">
                                <i class="bx bx-briefcase-alt-2 me-1"></i><strong>{$value['th_expl_cargos_ocupados']}</strong>
                            </p>
                           <p class="cert-doctor mb-2 text-truncate" 
                            style="max-width: 200px; display: block;" 
                            title="{$value['descripcion_nomina']}">
                                Figura Legal: <strong>{$value['descripcion_nomina']}</strong>
                            </p>
                        </div>

                        <div class="mt-auto">
                            <div class="d-flex align-items-center justify-content-between p-2 mb-2" 
                                 style="background: rgba(25, 135, 84, 0.05); border-radius: 8px; border: 1px dashed rgba(25, 135, 84, 0.2);">
                                <div class="cert-date-range">
                                    <div class="cert-label-small" style="color: #198754;">Periodo</div>
                                    <span class="text-dark" style="font-size: 0.65rem;">
                                        <i class="bx bx-calendar me-1"></i>{$fecha_inicio_experiencia} — {$fecha_fin_experiencia}
                                    </span>
                                </div>
                                <div class="text-end">
                                    <div class="cert-label-small" style="color: #198754;">Sueldo</div>
                                    <span class="fw-bold" style="font-size: 0.9rem; color: #198754;">
                                        <small>$</small>{$sueldo_actual}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-2 p-2 rounded-2" style="background-color: #f8f9fa; border: 1px solid #edf2f7;">
                                <label class="fw-bold text-uppercase text-muted mb-1 d-block" style="font-size: 0.55rem; letter-spacing: 0.5px;">Referencia Principal</label>
                                <div id="pnl_referencias_laborales_{$value['_id']}">
                                    {$html_referencias}
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button onclick="modal_referencia_experiencia('{$value['_id']}','{$value['th_expl_nombre_empresa']}');" 
                                        class="btn btn-success btn-xs py-1 px-3 btn-cert-action" 
                                        style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    AGREGAR REFERENCIA
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
HTML;
            }
            $texto .= '</div>';
        }
        return $texto;
    }

    function listar_modal($id)
    {

        if ($id == '') {
            $datos = $this->modelo->where('th_expl_estado', 1)->listar();
        } else {
            $datos = $this->modelo->listar_experiencia_laboral_postulante(null, $id);
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {

        $datos = array(
            array('campo' => 'th_expl_nombre_empresa', 'dato' => $parametros['txt_nombre_empresa']),
            array('campo' => 'th_expl_cargos_ocupados', 'dato' => $parametros['txt_cargos_ocupados']),
            array('campo' => 'th_expl_fecha_inicio_experiencia', 'dato' => $parametros['txt_fecha_inicio_laboral']),
            array('campo' => 'th_expl_fecha_fin_experiencia', 'dato' => $parametros['txt_fecha_final_laboral']),
            array('campo' => 'th_expl_cbx_fecha_fin_experiencia', 'dato' => $parametros['cbx_fecha_final_laboral']),
            array('campo' => 'th_expl_responsabilidades', 'dato' => $parametros['txt_responsabilidades']),
            array('campo' => 'th_expl_logros', 'dato' => $parametros['txt_logros']),
            array('campo' => 'th_expl_sueldo', 'dato' => $parametros['txt_sueldo']),
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_id_postulante']),
            array('campo' => 'id_nomina', 'dato' => $parametros['ddl_nomina_experiencia']),

        );
        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_expl_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        $experiencias = $this->modelo->listar_experiencia_laboral_postulante($parametros['txt_id_postulante']);

        $resultado = $this->calcular_experiencia_y_remuneracion($experiencias);

        $texto = $resultado['resumen_general']['tiempo_total']['texto'];

        $promedio = round(
            (float)$resultado['resumen_general']['remuneracion_promedio'],
            2
        );
        $encontrado =  $this->th_per_informacion_adicional
            ->where('th_per_id', $parametros['txt_id_persona'])
            ->listar();

        $datos_inf = array(
            array('campo' => 'th_per_id', 'dato' =>  $parametros['txt_id_persona']),
            array('campo' => 'th_inf_adi_tiempo_trabajo', 'dato' => $texto),
            array('campo' => 'th_inf_adi_remuneracion_promedio', 'dato' => $promedio),
            array('campo' => 'th_inf_adi_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );
        if ($encontrado == null) {

            $this->th_per_informacion_adicional->insertar($datos_inf);
        } else {
            $where[0]['campo'] = 'th_inf_adi_id';
            $where[0]['dato'] =  $encontrado[0]['th_inf_adi_id'];
            $this->th_per_informacion_adicional->editar($datos_inf, $where);
        }

        return $datos;
    }

    function eliminar($id, $id_persona, $id_postulante)
    {
        $datos = [
            ['campo' => 'th_expl_estado', 'dato' => 0],
        ];

        $where[0]['campo'] = 'th_expl_id';
        $where[0]['dato']  = strval($id);

        $this->modelo->editar($datos, $where);

        $experiencias = $this->modelo
            ->listar_experiencia_laboral_postulante($id_postulante);

        $referencias = $this->th_pos_referencias_laborales
            ->where('th_expl_id', $id)
            ->where('th_refl_estado', 1)
            ->listar();

        if (!empty($referencias)) {
            foreach ($referencias as $ref) {
                $datos = array(
                    array('campo' => 'th_refl_estado', 'dato' => 0),
                );

                $where = array(
                    array('campo' => 'th_refl_id', 'dato' => strval($ref['_id'])),
                );

                // Editamos cada referencia individualmente
                $this->th_pos_referencias_laborales->editar($datos, $where);
            }
        }

        $resultado = $this->calcular_experiencia_y_remuneracion($experiencias);

        $texto = $resultado['resumen_general']['tiempo_total']['texto'];
        $promedio = round(
            (float)$resultado['resumen_general']['remuneracion_promedio'],
            2
        );

        $encontrado = $this->th_per_informacion_adicional
            ->where('th_per_id', $id_persona)
            ->listar();

        $datos_inf = [
            ['campo' => 'th_inf_adi_tiempo_trabajo', 'dato' => $texto],
            ['campo' => 'th_inf_adi_remuneracion_promedio', 'dato' => $promedio],
            ['campo' => 'th_inf_adi_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
        ];

        if (empty($encontrado)) {
            $datos_inf[] = ['campo' => 'th_per_id', 'dato' => $id_persona];
            $this->th_per_informacion_adicional->insertar($datos_inf);
        } else {
            $whereInf[0]['campo'] = 'th_inf_adi_id';
            $whereInf[0]['dato']  = $encontrado[0]['th_inf_adi_id'];
            $this->th_per_informacion_adicional->editar($datos_inf, $whereInf);
        }

        return 1;
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
