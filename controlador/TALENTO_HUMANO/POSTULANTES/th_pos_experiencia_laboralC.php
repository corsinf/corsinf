<?php
require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_experiencia_laboralM.php');
require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/th_per_informacion_adicionalM.php');

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

    function __construct()
    {
        $this->modelo = new th_pos_experiencia_laboralM();
        $this->th_per_informacion_adicional = new th_per_informacion_adicionalM();
    }

    //Funcion para listar la experiencia previa del postulante
    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_expl_estado', 1)->orderBy('th_expl_cbx_fecha_fin_experiencia', 'DESC')->orderBy('th_expl_fecha_fin_experiencia', 'DESC')->listar();
        //$datos = $this->modelo->where('th_pos_id', $id)->where('th_expl_estado', 1)->orderBy('th_expl_fecha_fin_experiencia', 'DESC')->listar();
        $texto = '';

        foreach ($datos as $key => $value) {
            //Formato de fechas de experiencia laboral
            $fecha_inicio_experiencia = date('d/m/Y', strtotime($value['th_expl_fecha_inicio_experiencia']));
            //$fecha_fin_experiencia = $value['th_expl_fecha_fin_experiencia'] == '' ? 'Actualidad' : date('d/m/Y', strtotime($value['th_expl_fecha_fin_experiencia']));
            $fecha_fin_experiencia = $value['th_expl_cbx_fecha_fin_experiencia'] == 1 ? 'Actualidad' : date('d/m/Y', strtotime($value['th_expl_fecha_fin_experiencia']));

            $sueldo_actual = number_format($value['th_expl_sueldo'], 2, '.', ',');
            $texto .=
                <<<HTML
                    <div class="row mb-col">
                        <div class="col-10">
                            <h6 class="fw-bold">{$value['th_expl_nombre_empresa']}</h6>
                            <p class="m-0">{$value['th_expl_cargos_ocupados']}</p>
                            <p class="m-0">{$sueldo_actual}</p>
                            <p class="m-0">{$fecha_inicio_experiencia} - {$fecha_fin_experiencia}</p>
                            <p class="m-0">{$value['th_expl_responsabilidades_logros']}</p>
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-start">
                            <button class="btn icon-hover" style="color: white;" onclick="abrir_modal_experiencia_laboral({$value['_id']});">
                                <i class="text-dark bx bx-pencil bx-sm"></i>
                            </button>
                        </div>
                    </div>
                HTML;
        }
        return $texto;
    }

    function listar_modal($id)
    {

        if ($id == '') {
            $datos = $this->modelo->where('th_expl_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_expl_id', $id)->listar();
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
            array('campo' => 'th_expl_responsabilidades_logros', 'dato' => $parametros['txt_responsabilidades_logros']),
            array('campo' => 'th_expl_sueldo', 'dato' => $parametros['txt_sueldo']),
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_id_postulante']),

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

    function eliminar($id, $id_persona , $id_postulante)
    {
        $datos = [
            ['campo' => 'th_expl_estado', 'dato' => 0],
        ];

        $where[0]['campo'] = 'th_expl_id';
        $where[0]['dato']  = strval($id);

        $this->modelo->editar($datos, $where);

        $experiencias = $this->modelo
            ->listar_experiencia_laboral_postulante($id_postulante);

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
