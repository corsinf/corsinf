<?php

header('Content-Type: application/json; charset=utf-8');



require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_personas_departamentosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_programar_horariosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_turnos_horarioM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_control_accesoM.php');


$controlador = new th_indexC();

if (isset($_GET['notificaciones_asistencia'])) {
    // Llamar al método imprimirPDF
    echo json_encode($controlador->notificaciones_asistencias());
}

class th_indexC
{
    private $persona_departamento;
    private $th_programar_horarios;
    private $th_turnos_horario;
    private $th_control_acceso;

    function __construct()
    {
        $this->persona_departamento = new th_personas_departamentosM();
        $this->th_programar_horarios = new th_programar_horariosM();
        $this->th_turnos_horario = new th_turnos_horarioM();
        $this->th_control_acceso = new th_control_accesoM();
    }
    function obtenerDiaNumeroPersonalizado()
    {
        $dia = date('w'); // 0 (domingo) a 6 (sábado)
        return $dia == 0 ? 1 : $dia + 1; // 1 (domingo) a 7 (sábado)
    }

    function convertirHoraAMinutos(string $hora): int
    {
        list($horas, $minutos) = explode(':', $hora);
        return intval($horas) * 60 + intval($minutos);
    }

    function verificarAsistencia(array $registros, int $horaEntradaMinutos, int $horaSalidaMinutos, int $tolEntIniSegundos, int $tolEntFinSegundos, int $tolSalIniSegundos, int $tolSalFinSegundos)
    {

        date_default_timezone_set('America/Guayaquil'); // Usa tu zona horaria real
        $tsAhora = date('H:i');
        $horaTransformadaActual = $this->convertirHoraAMinutos($tsAhora);


        $tieneIngreso = false;
        $tieneSalida  = false;

        foreach ($registros as $r) {
            $tsRegistro = substr($r['th_acc_hora'], 0, 5);
            $horaTransformada = $this->convertirHoraAMinutos($tsRegistro);

            if ($horaTransformada  >= $tolEntIniSegundos  && $horaTransformada <= $tolEntFinSegundos) {
                //echo "Desde Ingreso";
                $tieneIngreso = true;
            }
            if ($horaTransformada  >= $tolSalIniSegundos  && $horaTransformada <= $tolSalFinSegundos) {
                $tieneSalida  = true;
            }
        }

        $mensajes = [];
        // Alerta si no ingresó correctamente
        if (! $tieneIngreso) {
            if ($horaTransformadaActual > $tolEntFinSegundos) {
                $minPasados = $horaTransformadaActual - $tolEntFinSegundos;
                $mensajes[] = "Han pasado {$minPasados} minutos desde las "
                    . sprintf('%02d:%02d', floor($tolEntFinSegundos / 60), $tolEntFinSegundos % 60);
            } else {
                $mensajes[] = "Falta tiempo para marcar el ingreso "
                    . sprintf('%02d:%02d', floor($tolEntIniSegundos / 60), $tolEntIniSegundos % 60);
            }
        }

        // Alerta si no salió correctamente
        if (! $tieneSalida) {
            if ($horaTransformadaActual > $tolSalFinSegundos) {
                $minPasados = $horaTransformadaActual - $tolSalFinSegundos;
                $mensajes[] = "Han pasado {$minPasados} minutos desde las "
                    . sprintf('%02d:%02d', floor($tolSalFinSegundos / 60), $tolSalFinSegundos % 60);
            } else {
                $mensajes[] = "Falta tiempo para marcar la salida "
                    . sprintf('%02d:%02d', floor($tolSalIniSegundos / 60), $tolSalIniSegundos % 60);
            }
        }
        return $mensajes;
    }



    function notificaciones_asistencias()
    {

        if ($_SESSION['INICIO']['NO_CONCURENTE']) {
            $th_per_id = $_SESSION['INICIO']['NO_CONCURENTE'];

            $personaHorario = $this->th_programar_horarios->listar_persona_departamentos($th_per_id, 'per');

            if ($personaHorario) {

                $diaActual = $this->obtenerDiaNumeroPersonalizado();

                $listaTurnoDia = $this->th_turnos_horario->listar_turno_dia($personaHorario[0]['id_horario'], $diaActual);

                $control_acceso_diario = $this->th_control_acceso->buscarAccesoPorPersonaYFecha($th_per_id, date('Y-m-d'));

                if ($listaTurnoDia) {
                    $limite_tardanza_in = $listaTurnoDia[0]['limite_tardanza_in'];
                    $limite_tardanza_out = $listaTurnoDia[0]['limite_tardanza_out'];
                    $hora_entrada = $listaTurnoDia[0]['hora_entrada'];
                    $hora_salida = $listaTurnoDia[0]['hora_salida'];
                    $tolEntradaIn  = $listaTurnoDia[0]['hora_tolerancia_entrada_inicio']+ $limite_tardanza_in;
                    $tolEntradaOut = $listaTurnoDia[0]['hora_tolerancia_entrada_fin']+ $limite_tardanza_in;
                    $tolSalidaIn  = $listaTurnoDia[0]['hora_tolerancia_salida_inicio']+$limite_tardanza_out;
                    $tolSalidaOut = $listaTurnoDia[0]['hora_tolerancia_salida_fin']+$limite_tardanza_out;

                    return $this->verificarAsistencia($control_acceso_diario, $hora_entrada, $hora_salida,  $tolEntradaIn, $tolEntradaOut, $tolSalidaIn, $tolSalidaOut);
                } else {

                    return [
                        'message' => 'No esta asginado a ningun turno'
                    ];
                }
            } else {

                $encontrado = $this->persona_departamento->listar_buscar_persona_departamento($th_per_id);

                if ($encontrado) {

                    $idDepartamento = $encontrado[0]['id_departamento'];

                    $departamentoHorario = $this->th_programar_horarios->listar_persona_departamentos($idDepartamento, 'dep');

                    if ($departamentoHorario) {
                        $diaActual = $this->obtenerDiaNumeroPersonalizado();

                        $listaTurnos = $this->th_turnos_horario->listar_turno_dia($departamentoHorario[0]['id_horario'], $diaActual);

                        $control_acceso_diario = $this->th_control_acceso->buscarAccesoPorPersonaYFecha($th_per_id, date('Y-m-d'));


                        if ($listaTurnos) {
                            $limite_tardanza_in = $listaTurnos[0]['limite_tardanza_in'];
                            $limite_tardanza_out = $listaTurnos[0]['limite_tardanza_out'];
                            $hora_entrada = $listaTurnos[0]['hora_entrada'];
                            $hora_salida = $listaTurnos[0]['hora_salida'];
                            $tolEntradaIn  = $listaTurnos[0]['hora_tolerancia_entrada_inicio'] + $limite_tardanza_in;
                            $tolEntradaOut = $listaTurnos[0]['hora_tolerancia_entrada_fin'] + $limite_tardanza_in;
                            $tolSalidaIn  = $listaTurnos[0]['hora_tolerancia_salida_inicio'] + $limite_tardanza_out;
                            $tolSalidaOut = $listaTurnos[0]['hora_tolerancia_salida_fin'] + $limite_tardanza_out;

                            return $this->verificarAsistencia($control_acceso_diario, $hora_entrada, $hora_salida,  $tolEntradaIn, $tolEntradaOut, $tolSalidaIn, $tolSalidaOut);
                        } else {

                            return [
                                'message' => 'No esta asginado a ningun turno'
                            ];
                        }
                    } else {
                        return [
                            'message' => 'No esta asginado a ningun departamento'
                        ];
                    }
                } else {

                    return [
                        'message' => 'No esta asginado a ningun turno'
                    ];
                }
            }
        }
        //print_r($_SESSION['INICIO']['ID_USUARIO']);
    }
}
