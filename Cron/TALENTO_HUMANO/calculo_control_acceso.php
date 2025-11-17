<?php
require_once(dirname(__DIR__, 2) . '/db/db.php');

class calculo_persona
{
    protected $db;

    private $usuario;
    private $password;
    private $servidor;
    private $database;
    private $puerto;


    public function __construct($usuario, $password, $servidor, $database, $puerto)
    {
        $this->usuario = $usuario;
        $this->password = $password;
        $this->servidor = $servidor;
        $this->database = $database;
        $this->puerto = $puerto;

        $this->db = new db();
    }

    public function conexionEmpresa()
    {
        $usuario = $this->usuario;
        $password = $this->password;
        $servidor = $this->servidor;
        $database = $this->database;
        $puerto = $this->puerto;

        $con = $this->db->conexion_db_terceros(
            $database,
            $usuario,
            $password,
            $servidor,
            $puerto
        );

        return $con;
    }

    public function datos($sql)
    {
        $usuario = $this->usuario;
        $password = $this->password;
        $servidor = $this->servidor;
        $database = $this->database;
        $puerto = $this->puerto;

        $con = $this->db->datos_db_terceros(
            $database,
            $usuario,
            $password,
            $servidor,
            $puerto,
            $sql
        );

        return $con;
    }

    public function sql_string($sql)
    {
        $usuario = $this->usuario;
        $password = $this->password;
        $servidor = $this->servidor;
        $database = $this->database;
        $puerto = $this->puerto;

        $con = $this->db->sql_string_db_terceros(
            $database,
            $usuario,
            $password,
            $servidor,
            $puerto,
            $sql
        );

        return $con;
    }

    function obtener_turno_programado($th_per_id, $fecha)
    {
        $sql =
            ";WITH prog AS (
                SELECT
                    pro.*,
                    CAST(pro.th_pro_fecha_inicio AS DATE) AS pro_fecha_inicio,
                    CAST(pro.th_pro_fecha_fin AS DATE) AS pro_fecha_fin,
                    dep.th_dep_nombre,
                    CASE WHEN pro.th_per_id IS NOT NULL THEN 0 ELSE 1 END AS prioridad
                FROM
                    th_programar_horarios pro
                    JOIN th_horarios hor ON hor.th_hor_id = pro.th_hor_id
                    JOIN th_turnos_horario tur_hor ON tur_hor.th_hor_id = hor.th_hor_id
                    JOIN th_turnos tur ON tur.th_tur_id = tur_hor.th_tur_id
                    LEFT JOIN th_departamentos dep ON dep.th_dep_id = pro.th_dep_id
                WHERE
                    pro.th_pro_estado = 1
                    AND CAST('$fecha' AS DATE)
                        BETWEEN CAST(pro.th_pro_fecha_inicio AS DATE)
                        AND CAST(pro.th_pro_fecha_fin AS DATE)
                    AND (
                        pro.th_per_id = $th_per_id
                        OR (
                            pro.th_per_id = 0
                            AND pro.th_dep_id IN (
                                SELECT th_dep_id FROM th_personas_departamentos WHERE th_per_id = $th_per_id
                            )
                        )
                    )
            )
            SELECT TOP 1
                pro.*,
                hor.*,
                tur.*,
                pro.th_dep_nombre,
                pro.prioridad,
                pro.pro_fecha_inicio,
                pro.pro_fecha_fin
            FROM
                prog pro
                JOIN th_horarios hor ON hor.th_hor_id = pro.th_hor_id
                JOIN th_turnos_horario tur_hor ON tur_hor.th_hor_id = hor.th_hor_id
                JOIN th_turnos tur ON tur.th_tur_id = tur_hor.th_tur_id
            ORDER BY
                pro.prioridad;
            ";
        $datos = $this->datos($sql);
        return $datos;
    }

    function obtener_justificacion($th_per_id, $fecha)
    {
        $sql = "
        ;WITH jus AS (
            SELECT
                jus.*,
                tipo.th_tip_jus_nombre,
                tipo.th_tip_jus_descripcion,
                CASE
                    WHEN jus.th_per_id IS NOT NULL THEN 0
                    ELSE 1
                END AS prioridad
            FROM th_justificaciones jus
            JOIN th_cat_tipo_justificacion tipo ON tipo.th_tip_jus_id = jus.th_tip_jus_id
            WHERE jus.th_jus_estado = 1
              AND CAST('$fecha' AS DATE)
                  BETWEEN CAST(jus.th_jus_fecha_inicio AS DATE)
                      AND CAST(jus.th_jus_fecha_fin AS DATE)
              AND (
                    jus.th_per_id = $th_per_id
                 OR (
                        jus.th_per_id IS NULL
                    AND jus.th_dep_id IN (
                        SELECT th_dep_id
                        FROM th_personas_departamentos
                        WHERE th_per_id = $th_per_id
                    )
                 )
              )
        )
        SELECT TOP 1
            jus.th_jus_id,
            jus.th_tip_jus_id,
            jus.th_tip_jus_nombre AS tipo_justificacion,
            jus.th_jus_motivo AS justificacion,
            jus.th_jus_fecha_inicio AS fecha_inicio,
            jus.th_jus_fecha_fin AS fecha_fin,
            jus.th_jus_es_rango AS es_rango,
            jus.th_jus_minutos_justificados AS minutos_justificados,
            FORMAT(DATEADD(MINUTE, jus.th_jus_minutos_justificados, '00:00:00'), 'HH:mm') AS horas_justificadas,
            CASE WHEN jus.th_per_id IS NOT NULL THEN 'PERSONA' ELSE 'DEPARTAMENTO' END AS tipo_asignacion,
            jus.prioridad
        FROM jus
        ORDER BY prioridad;
    ";

        // print_r($sql); exit(); die();

        $datos = $this->datos($sql);
        return $datos;
    }

    function obtener_registro_entrada($th_per_id, $inicio, $fin)
    {
        $sql =
            "SELECT TOP 1
            th_acc_fecha_hora
        FROM
            th_control_acceso
        WHERE
            th_per_id = $th_per_id
            AND th_acc_fecha_hora BETWEEN '$inicio' AND '$fin'
        ORDER BY
            th_acc_fecha_hora ASC;";

        $datos = $this->datos($sql);
        return $datos;
    }

    function obtener_registro_salida($th_per_id, $fecha = null, $inicio = null, $fin = null, $fin_hora_entrada)
    {
        //No eliminar, se usa en el futuro
        // $sql =
        //     "SELECT TOP 1
        //         th_acc_fecha_hora
        //     FROM
        //         th_control_acceso
        //     WHERE
        //         th_per_id = $th_per_id
        //         AND th_acc_fecha_hora BETWEEN '$inicio' AND '$fin'
        //     ORDER BY
        //         th_acc_fecha_hora DESC;";

        // $datos = $this->datos($sql);
        // return $datos;

        //No eliminar es para uso libre
        // $sql =
        //     "SELECT TOP 1
        //         th_acc_fecha_hora
        //     FROM
        //         th_control_acceso
        //     WHERE
        //         th_per_id = $th_per_id
        //         AND CAST(th_acc_fecha_hora AS DATE) = '$fecha'
        //     ORDER BY
        //         th_acc_fecha_hora DESC;";

        // $datos = $this->datos($sql);
        // return $datos;


        // $sql =
        //     "SELECT
        //         th_acc_fecha_hora = MIN(CASE
        //                         WHEN CAST(th_acc_fecha_hora AS TIME) > '$fin_hora_entrada'
        //                         THEN th_acc_fecha_hora
        //                     END)
        //     FROM th_control_acceso
        //     WHERE th_per_id = $th_per_id
        //         AND CAST(th_acc_fecha_hora AS DATE) = '$fecha'
        //     HAVING COUNT(*) >= 2;";


        $sql =
            "SELECT TOP 1 th_acc_fecha_hora
            FROM th_control_acceso
            WHERE
                th_per_id = $th_per_id
                AND CAST(th_acc_fecha_hora AS DATE) = '$fecha'
                AND CAST(th_acc_fecha_hora AS TIME) > '$fin_hora_entrada'
            ORDER BY th_acc_fecha_hora DESC;";

        $datos = $this->datos($sql) ?? null;

        if (
            empty($datos) ||
            !isset($datos[0]['th_acc_fecha_hora']) ||
            $datos[0]['th_acc_fecha_hora'] === null ||
            $datos[0]['th_acc_fecha_hora'] === ''
        ) {
            return null;
        }

        return $datos;
    }

    function obtener_datos_persona($th_per_id)
    {
        $sql =
            "SELECT TOP 1
            th_per_primer_apellido,
            th_per_segundo_apellido,
            th_per_primer_nombre,
            th_per_segundo_nombre,
            th_per_cedula,
            th_per_correo,
            th_per_id
        FROM
            th_personas
        WHERE
            th_per_id = $th_per_id;";

        $datos = $this->datos($sql);
        return $datos;
    }

    function obtener_personas_programa_horario($fecha)
    {
        $sql = "
        SELECT DISTINCT per.th_per_id
        FROM th_personas per
        WHERE per.th_per_estado = 1
        AND EXISTS (
            SELECT 1
            FROM th_programar_horarios pro
            JOIN th_horarios hor ON hor.th_hor_id = pro.th_hor_id
            JOIN th_turnos_horario tur_hor ON tur_hor.th_hor_id = hor.th_hor_id
            JOIN th_turnos tur ON tur.th_tur_id = tur_hor.th_tur_id
            WHERE pro.th_pro_estado = 1
            AND (
                (pro.th_per_id = per.th_per_id)
                OR (
                    pro.th_per_id = 0 AND pro.th_dep_id IN (
                        SELECT th_dep_id FROM th_personas_departamentos WHERE th_per_id = per.th_per_id
                    )
                )
            )
            AND CAST('$fecha' AS DATE) BETWEEN CAST(pro.th_pro_fecha_inicio AS DATE) AND CAST(pro.th_pro_fecha_fin AS DATE)
        );
    ";

        return $this->datos($sql);
    }

    function existe_registro_calculo($th_per_id, $fecha)
    {
        $sql =
            "SELECT 1
        FROM th_control_acceso_calculos
        WHERE th_asi_empleado = $th_per_id
        AND th_asi_fecha = '$fecha';";

        $datos = $this->datos($sql);
        return $datos;
    }

    function obtener_feriado($fecha)
    {
        $sql =
            "SELECT CASE 
            WHEN EXISTS (
                SELECT 1
                FROM th_feriados
                WHERE
                    th_fer_estado = 1 AND
                    CAST('$fecha' AS DATE) BETWEEN 
                        CAST(th_fer_fecha_inicio_feriado AS DATE) AND
                        DATEADD(DAY, th_fer_dias - 1, CAST(th_fer_fecha_inicio_feriado AS DATE))
            ) THEN 1
            ELSE 0
        END AS es_feriado;";

        $datos = $this->datos($sql);
        return $datos;
    }

    function calculo_persona_control_acceso($th_per_id, $fecha, $desarrollo = false)
    {
        $turno = $this->obtener_turno_programado($th_per_id, $fecha);
        $turno = $turno[0] ?? null;


        $persona = $this->obtener_datos_persona($th_per_id)[0];
        $apellidos = $persona['th_per_primer_apellido'] . " " . $persona['th_per_segundo_apellido'];
        $nombres = $persona['th_per_primer_nombre'] . " " . $persona['th_per_segundo_nombre'];
        $empleado = $apellidos . " " . $nombres;
        $cedula = $persona['th_per_cedula'];
        $correo = $persona['th_per_correo'];
        $id_persona = $persona['th_per_id'];

        // print_r($persona); exit(); die();

        if (!$turno) {
            $parametros = [
                'th_per_id' => $th_per_id ?? '',
                'th_asi_apellidos' => $apellidos ?? '',
                'th_asi_nombres' => $nombres ?? '',
                'th_asi_empleado' => $empleado ?? '',
                'th_asi_cedula' => $cedula ?? '',
                'th_asi_correo_institucional' => $correo ?? '',
                'th_asi_departamento' => $departamento_nombre ?? '',
                'th_asi_dia' => date('l', strtotime($fecha)),
                'th_asi_fecha' => $fecha,
                'th_asi_sin_turno' => 'SI'
            ];

            return $this->insertar_registro_sin_turno($parametros);
            // exit("Insertado sin turno.");
            exit;
        }

        // print_r($turno); exit(); die();

        // $pro_id = $turno['th_pro_id'];
        // $hor_id = $turno['th_hor_id'];
        // $persona_id = $turno['th_per_id'];
        // $departamento_id = $turno['th_dep_id'];

        $pro_fecha_inicio = $turno['pro_fecha_inicio'];
        $pro_fecha_fin = $turno['pro_fecha_fin'];
        // $pro_no_ciclo = $turno['th_pro_no_ciclo'];
        // $pro_tipo_ciclo = $turno['th_pro_tipo_ciclo'];
        // $pro_si_ciclo = $turno['th_pro_si_ciclo'];
        // $pro_estado = $turno['th_pro_estado'];
        // $pro_fecha_creacion = $turno['th_pro_fecha_creacion'];
        $departamento_nombre = $turno['th_dep_nombre'];
        $prioridad = $turno['prioridad'];

        $horario_nombre = $turno['th_hor_nombre'];
        // $horario_tipo = $turno['th_hor_tipo'];
        // $horario_ciclos = $turno['th_hor_ciclos'];
        // $horario_inicio = $turno['th_hor_inicio'];
        // $horario_estado = $turno['th_hor_estado'];

        // $turno_id = $turno['th_tur_id'];
        $turno_nombre = $turno['th_tur_nombre'];

        $entrada_inicio = $turno['th_tur_checkin_registro_inicio'];
        $hora_entrada = $turno['th_tur_hora_entrada'];
        $entrada_fin = $turno['th_tur_checkin_registro_fin'];
        $tolerancia_entrada = $turno['th_tur_limite_tardanza_in'];

        $salida_inicio = $turno['th_tur_checkout_salida_inicio'];
        $hora_salida = $turno['th_tur_hora_salida'];
        $salida_fin = $turno['th_tur_checkout_salida_fin'];
        $tolerancia_salida = $turno['th_tur_limite_tardanza_out'];

        // $turno_nocturno = $turno['th_tur_turno_nocturno'];
        // $valor_trabajar = $turno['th_tur_valor_trabajar']; // Se usa pero no se ingresa desde el front queda inservible
        $valor_hora_trabajar = $turno['th_tur_valor_hora_trabajar'];
        $valor_min_trabajar = $turno['th_tur_valor_min_trabajar'];

        $descanso = $turno['th_tur_descanso'];
        $hora_descanso = $turno['th_tur_hora_descanso'];

        $usar_descanso = $turno['th_tur_usar_descanso'];
        $descanso_inicio = $turno['th_tur_descanso_inicio'];
        $descanso_fin = $turno['th_tur_descanso_fin'];
        $tolerancia_inicio_descanso = $turno['th_tur_tol_ini_descanso'];
        $tolerancia_fin_descanso = $turno['th_tur_tol_fin_descanso'];

        $calcular_horas_extra = $turno['th_tur_calcular_horas_extra'];
        $suplementaria_inicio = $turno['th_tur_supl_ini'];
        $suplementaria_fin = $turno['th_tur_supl_fin'];
        $extra_inicio = $turno['th_tur_extra_ini'];
        $extra_fin = $turno['th_tur_extra_fin'];

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Calculos para obtener la hora de entrada y salida
        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        // $fecha viene en formato "YYYY-MM-DD"
        $fecha_base = new DateTime($fecha);

        // Clonar la fecha base para manipular sin modificar el objeto original
        $entrada_valida_inicio = clone $fecha_base;
        $entrada_valida_inicio->modify("+$entrada_inicio minutes");

        $entrada_valida_fin = clone $fecha_base;
        $entrada_valida_fin->modify("+$entrada_fin minutes");

        // Aplicar tolerancia
        $inicio_rango = clone $entrada_valida_inicio;

        $fin_rango = clone $entrada_valida_fin;
        $fin_rango->modify("+$tolerancia_entrada minutes");

        // Formatear a string para la consulta SQL
        $inicio_hora_turno = $inicio_rango->format('Y-m-d H:i:s');
        $fin_hora_turno = $fin_rango->format('Y-m-d H:i:s');

        $inicio_hora_turno_salida = $inicio_rango->format('H:i:s');
        $fin_hora_turno_salida = $fin_rango->format('H:i:s');

        // Resultados
        // echo "Inicio Rango: $inicio_hora_turno <br>";
        // echo "Fin Rango: $fin_hora_turno <br>";

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        $entrada = $this->obtener_registro_entrada($th_per_id, $inicio_hora_turno, $fin_hora_turno);

        //Salida de analisis
        $atrasado = '';
        $ausente = '';

        $hora_original = '';
        $hora_entrada_dt = '';
        $hora_ajustada_str = '';

        $fecha_base = new DateTime($fecha . ' 00:00:00');

        if ($entrada) {
            // Crear base con tu fecha de jornada, no con la fecha de la marcación
            // Generar la hora de entrada
            $hora_entrada_dt = clone $fecha_base;
            $hora_entrada_dt->modify("+$hora_entrada minutes");
        }


        if (!$entrada) {
            $atrasado = 'SIN MARCACION';
            $ausente = 'SI';
        } else {
            // Tomar la marcación original
            $entrada_marcacion_raw = $entrada[0]['th_acc_fecha_hora'];

            $entrada_marcacion = new DateTime($entrada_marcacion_raw);


            // Clonar marcación para luego ajustarla si corresponde
            $hora_ajustada = clone $entrada_marcacion;

            // Verificar si es antes de la hora de entrada
            if ($entrada_marcacion < $hora_entrada_dt) {
                $hora_ajustada = clone $hora_entrada_dt;
            }

            // Determinar si está atrasado
            // Atrasado si la marcación original es después de la hora de entrada

            if ($entrada_marcacion > $hora_entrada_dt) {
                $atrasado = 'SI';
            } else {
                $atrasado = 'NO';
            }

            // Formatear la hora ajustada

            // Resultados
            $ausente = 'NO';
            $hora_original = $entrada_marcacion->format('H:i:s');
            $hora_entrada_dt = $hora_entrada_dt->format('Y-m-d H:i:s');
            $hora_ajustada_str = $hora_ajustada->format('Y-m-d H:i:s');
        }


        $hora_entrada_dt_1 = clone $fecha_base;
        $hora_entrada_dt_1->modify("+$hora_entrada minutes");
        $hora_entrada_dt_1 = $hora_entrada_dt_1->format('Y-m-d H:i:s');


        // Resultados
        // echo "Hora original: " . $hora_original . "<br>";
        // echo "Hora entrada : " . $hora_entrada_dt . "<br>";
        // echo "Hora entrada 1: " . $hora_entrada_dt_1 . "<br>";
        // echo "Hora ajustada: " . $hora_ajustada_str . "<br>";
        // echo "Atrasado: " . $atrasado . "<br>";
        // echo "Ausente: " . $ausente . "<br>";
        // exit;


        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Calcular rangos de salida con base en los minutos desde las 00:00 del día del turno
        $fecha_base_salida = new DateTime($fecha . ' 00:00:00');

        $salida_valida_inicio = clone $fecha_base_salida;
        $salida_valida_inicio->modify("+$salida_inicio minutes");

        $salida_valida_fin = clone $fecha_base_salida;
        $salida_valida_fin->modify("+$salida_fin minutes");

        $hora_salida_programada = clone $fecha_base_salida;
        $hora_salida_programada->modify("+$hora_salida minutes");

        // Aplicar tolerancia de salida
        $inicio_rango_salida = clone $salida_valida_inicio;
        $inicio_rango_salida->modify("-$tolerancia_salida minutes");

        $fin_rango_salida = clone $salida_valida_fin;
        $fin_rango_salida->modify("+$tolerancia_salida minutes");

        // Convertir a texto para consulta
        $inicio_salida_turno = $inicio_rango_salida->format('Y-m-d H:i:s');
        $fin_salida_turno = $fin_rango_salida->format('Y-m-d H:i:s');

        $inicio_salida_turno_salida = $inicio_rango_salida->format('H:i:s');
        $fin_salida_turno_salida = $fin_rango_salida->format('H:i:s');

        // Resultados
        // echo "Inicio Rango: $inicio_salida_turno <br>";
        // echo "Fin Rango: $fin_salida_turno <br>";
        // exit;
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        // Obtener registro real de salida
        $salida = $this->obtener_registro_salida($th_per_id, $inicio_salida_turno, null, null, $fin_hora_turno);
        // print_r($salida);
        // exit();
        // die();

        // Inicializar resultados
        $salida_tarde = '';
        $salida_ausente = '';
        $hora_salida_str = null;

        $hora_salida_original = '';
        $hora_salida_dt_str = $hora_salida_programada->format('Y-m-d H:i:s');

        if (!$salida) {
            $salida_tarde = 'SIN MARCACION';
            $salida_ausente = 'SI';
        } else {
            $salida_marcacion_raw = $salida[0]['th_acc_fecha_hora'];
            $salida_marcacion = new DateTime($salida_marcacion_raw);

            $hora_salida_original = $salida_marcacion->format('H:i:s');
            $salida_ausente = 'NO';

            // Verificar si salió antes de la hora programada
            if ($salida_marcacion < $hora_salida_programada) {
                $salida_tarde = 'SI';
            } else {
                $salida_tarde = 'NO';
            }

            $hora_salida_str = $salida_marcacion->format('Y-m-d H:i:s');
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Horas de descanso

        $resultado_descanso = $this->calcular_descanso_real(
            $th_per_id,
            $fecha,
            $usar_descanso,
            $descanso,
            $hora_descanso,
            $descanso_inicio,
            $descanso_fin,
            $tolerancia_inicio_descanso,
            $tolerancia_fin_descanso,
            $hora_ajustada_str,
            $hora_salida_str,
            $fin_hora_turno,
        );

        // print_r($resultado_descanso); exit(); die();
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Justificaciones
        // $justificacion_arr = $this->obtener_justificacion($th_per_id, $fecha);

        $justificacion_arr = $this->validar_justificacion($th_per_id, $fecha);

        // print_r($justificacion_arr);

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Calcular horas extras

        $extras_arr = $this->calcular_horas_extras_detalladas(
            $th_per_id,
            $fecha,
            $calcular_horas_extra,
            $suplementaria_inicio,
            $suplementaria_fin,
            $extra_inicio,
            $extra_fin,
            $hora_ajustada_str,
            $hora_salida_str
        );

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        // Feriado

        $es_feriado = $this->obtener_feriado($fecha)[0];
        // print_r($es_feriado); exit(); die();
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Horas de trabajo
        $horas_trabajo_min =  $valor_hora_trabajar * 60 + $valor_min_trabajar;
        // echo "Minutos de trabajo: " . $horas_trabajo_min . "<br>";

        $horas_trabajadas_arr = $this->calcular_horas_trabajadas(
            $horas_trabajo_min,
            $hora_ajustada_str,
            $hora_salida_str,
            $resultado_descanso,
            $justificacion_arr,
            $es_feriado
        );

        // print_r($horas_trabajadas_arr);
        // echo "<br>";
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////

        $dia = $this->calcular_dia(($fecha));

        // Resultados
        if ($desarrollo) {
            echo "Fecha: $fecha<br>";
            echo "Dia: " . $dia;
            echo "<br>";

            echo "<strong>Información adicional: </strong><br>";
            echo "Apellidos: $apellidos<br>";
            echo "Nombres: $nombres<br>";
            echo "Empleado: $empleado<br>";
            echo "Cédula: $cedula<br>";
            echo "Correo: $correo<br><br>";

            echo "Fecha inicio programación: $pro_fecha_inicio<br>";
            echo "Fecha fin programación: $pro_fecha_fin<br>";
            echo "Departamento: $departamento_nombre<br>";
            echo "Prioridad: $prioridad<br>";
            echo "Horario: $horario_nombre<br>";
            echo "Turno: $turno_nombre<br>";

            echo "<br>";
            echo "<strong>Datos del turno entrada: </strong><br>";
            echo "Hora inicio: " . $inicio_hora_turno_salida . "<br>";
            echo "Hora fin: " . $fin_hora_turno_salida . "<br>";
            echo "Hora marcacion: " . $hora_original . "<br>";
            echo "Hora entrada : " . $hora_entrada_dt_1 . "<br>";
            echo "Hora ajustada: " . $hora_ajustada_str . "<br>"; //La hora con la que se hace el calculo de trabajo de 8 horas
            echo "Atrasado: " . $atrasado . "<br>";
            echo "Ausente: " . $ausente . "<br>";

            echo "<br>";
            echo "<strong>Datos del turno salida: </strong><br>";
            echo "Hora inicio: " . $inicio_salida_turno_salida . "<br>";
            echo "Hora fin: " . $fin_salida_turno_salida . "<br>";
            echo "Hora marcacion salida: " . $hora_salida_original . "<br>";
            echo "Hora marcacion salida str: " . $hora_salida_str . "<br>";
            echo "Hora salida: " . $hora_salida_dt_str . "<br>";
            echo "Salida antes de hora: " . $salida_tarde . "<br>";
            echo "Ausente salida: " . $salida_ausente . "<br>";

            echo "<br>";
            echo "<strong>Datos de descanso: </strong><br>";
            echo "hora_inicio_descanso: " . $resultado_descanso['hora_inicio_descanso'] . "<br>";
            echo "hora_fin_descanso: " . $resultado_descanso['hora_fin_descanso'] . "<br>";
            echo "minutos_descanso: " . $resultado_descanso['minutos_descanso'] . "<br>";
            echo "modo_descanso: " . $resultado_descanso['modo_descanso'] . "<br>";

            echo "<br>";
            echo "<strong>Resultado de Justificación:</strong><br>";
            echo "Justificado: " . $justificacion_arr['justificado'] . "<br>";
            echo "Tipo de Justificación: " . ($justificacion_arr['tipo_justificacion'] ?? 'N/A') . "<br>";
            echo "Motivo: " . ($justificacion_arr['justificacion'] ?? 'N/A') . "<br>";
            echo "Fecha Inicio: " . ($justificacion_arr['fecha_inicio'] ?? 'N/A') . "<br>";
            echo "Fecha Fin: " . ($justificacion_arr['fecha_fin'] ?? 'N/A') . "<br>";
            echo "Horas Justificadas: " . $justificacion_arr['horas_justificadas'] . "<br>";
            // echo "Minutos Justificados: " . $justificacion_arr['minutos_justificados'] . "<br>";
            echo "¿Es Rango?: " . ($justificacion_arr['es_rango'] ? 'Sí' : 'No') . "<br>";
            echo "Asignado a: " . ($justificacion_arr['tipo_asignacion'] ?? 'N/A') . "<br>";

            echo "<br>";
            echo "<strong>Horas de trabajo: </strong><br>";
            echo "Tiempo entrada-salida: " . $horas_trabajadas_arr['tiempo_entrada_salida'] . "<br>";
            echo "Post descanso: " . $horas_trabajadas_arr['tiempo_post_descanso'] . "<br>";
            echo "Post justificación: " . $horas_trabajadas_arr['tiempo_post_justificacion'] . "<br>";
            echo "Horas trabajadas finales: " . $horas_trabajadas_arr['horas_trabajadas'] . "<br>";
            echo "Horas excedentes: " . $horas_trabajadas_arr['horas_excedentes'] . "<br>";
            echo "Horas que debe trabajar: " . $horas_trabajadas_arr['horas_trabajo_hora'] . "<br>";
            echo "Horas faltantes: " . $horas_trabajadas_arr['horas_faltantes'] . "<br>";
            echo "Cumple jornada mínima: " . $horas_trabajadas_arr['cumple_horas_trabajadas'] . "<br>";
            echo "Tipo justificación: " . $horas_trabajadas_arr['tipo_justificacion'] . "<br>";
            echo "Minutos descanso: " . $horas_trabajadas_arr['minutos_descanso'] . "<br>";
            echo "Minutos justificados: " . $horas_trabajadas_arr['minutos_justificados'] . "<br>";
            echo "Es feriado: " . ($horas_trabajadas_arr['es_feriado'] == 1 ? 'SI' : 'NO') . "<br>";


            echo "¿Trabajó en feriado?: " . $horas_trabajadas_arr['trabajo_en_feriado'] . "<br>";
            echo "¿Trabajó con justificación?: " . $horas_trabajadas_arr['trabajo_con_justificacion'] . "<br>";

            echo "<br>";
            echo "<strong>Horas de extra: </strong><br>";
            echo "Suplementarias: {$extras_arr['horas_suplementarias']}<br>";
            echo "Extras: {$extras_arr['horas_extras']}<br>";
            echo "Rango Suplementarias: {$extras_arr['rango_suplementarias']}<br>";
            echo "Rango Extras: {$extras_arr['rango_extras']}<br>";

            exit();
        }

        $parametros = [
            // Datos personales y generales
            'th_per_id' => $id_persona ?? '',
            'th_asi_apellidos' => $apellidos ?? '',
            'th_asi_nombres' => $nombres ?? '',
            'th_asi_empleado' => $empleado ?? '',
            'th_asi_cedula' => $cedula ?? '',
            'th_asi_correo_institucional' => $correo ?? '',
            'th_asi_departamento' => $departamento_nombre ?? '',
            'th_asi_dia' => $dia ?? '',
            'th_asi_fecha' => $fecha ?? '',

            // Programación y turno
            'th_asi_fecha_inicio_programacion' => $pro_fecha_inicio ?? '',
            'th_asi_fecha_fin_programacion' => $pro_fecha_fin ?? '',
            'th_asi_prioridad_programacion' => $prioridad ?? '',
            'th_asi_horario_contrato' => $horario_nombre ?? '',
            'th_asi_turno_nombre' => $turno_nombre ?? '',

            // Entrada
            'th_asi_entrada_hora_inicio_turno' => $inicio_hora_turno_salida ?? '',
            'th_asi_entrada_hora_fin_turno' => $fin_hora_turno_salida ?? '',
            'th_asi_regentrada' => $hora_original ?? '',
            'th_asi_hora_entrada' => $hora_entrada_dt_1 ?? '',
            'th_asi_hora_ajustada' => $hora_ajustada_str ?? '',
            'th_asi_atrasos' => $atrasado ?? '',
            'th_asi_ausente' => $ausente ?? '',

            // Salida
            'th_asi_salida_hora_inicio_turno' => $inicio_salida_turno_salida ?? '',
            'th_asi_salida_hora_fin_turno' => $fin_salida_turno_salida ?? '',
            'th_asi_regsalida' => $hora_salida_original ?? '',
            'th_asi_salida_marcacion_str' => $hora_salida_str ?? '',
            'th_asi_hora_salida' => $hora_salida_dt_str ?? '',
            'th_asi_salidas_temprano' => $salida_tarde ?? '',
            'th_asi_salida_ausente' => $salida_ausente ?? 'NO',

            // Jornada
            'th_asi_cumple_jornada' => $horas_trabajadas_arr['cumple_horas_trabajadas'] ?? 'NO',
            'th_asi_dias_trabajados' => ($ausente == 'SI' ? 0 : 1),
            'th_asi_horas_faltantes' => $horas_trabajadas_arr['horas_faltantes'] ?? 0,

            // Descanso
            'th_asi_usa_descanso_formal' => ($resultado_descanso['modo_descanso'] == 'FORMAL' ? 1 : 0),
            'th_asi_descanso_inicio' => $resultado_descanso['hora_inicio_descanso'] ?? '',
            'th_asi_descanso_fin' => $resultado_descanso['hora_fin_descanso'] ?? '',
            'th_asi_minutos_descanso_simple' => $resultado_descanso['minutos_descanso'] ?? 0,
            'th_asi_descanso_simple' => ($resultado_descanso['modo_descanso'] == 'SIMPLE' ? 1 : 0),

            // Justificación
            'th_asi_dia_justificado' => ($justificacion_arr['justificado'] ? 'SI' : 'NO'),
            'th_asi_motivo_justificacion' => $justificacion_arr['justificacion'] ?? '',
            'th_asi_inicio_justificacion' => $justificacion_arr['fecha_inicio'] ?? '',
            'th_asi_fin_justificacion' => $justificacion_arr['fecha_fin'] ?? '',
            'th_asi_horas_justificadas' => $justificacion_arr['horas_justificadas'] ?? '',
            'th_asi_justificacion_es_rango' => $justificacion_arr['es_rango'] ?? 0,
            'th_asi_justificacion_asignado_a' => $justificacion_arr['tipo_asignacion'] ?? '',

            // Cálculos adicionales
            'th_asi_tiempo_entrada_salida' => $horas_trabajadas_arr['tiempo_entrada_salida'] ?? '',
            'th_asi_tiempo_post_descanso' => $horas_trabajadas_arr['tiempo_post_descanso'] ?? '',
            'th_asi_tiempo_post_justificacion' => $horas_trabajadas_arr['tiempo_post_justificacion'] ?? '',
            'th_asi_horas_trabajadas_finales' => $horas_trabajadas_arr['horas_trabajadas'] ?? '',
            'th_asi_horas_excedentes' => $horas_trabajadas_arr['horas_excedentes'] ?? '',
            'th_asi_horas_trabajo_hora' => $horas_trabajadas_arr['horas_trabajo_hora'] ?? '',
            'th_asi_tipo_justificacion_aplicada' => $horas_trabajadas_arr['tipo_justificacion'] ?? '',
            'th_asi_minutos_descanso_calculado' => $horas_trabajadas_arr['minutos_descanso'] ?? 0,
            'th_asi_minutos_justificados_calculado' => $horas_trabajadas_arr['minutos_justificados'] ?? 0,
            'th_asi_es_feriado' => ($horas_trabajadas_arr['es_feriado'] == 1 ? 'SI' : 'NO'),
            'th_asi_trabajo_en_feriado' => $horas_trabajadas_arr['trabajo_en_feriado'] ?? 0,
            'th_asi_trabajo_con_justificacion' => $horas_trabajadas_arr['trabajo_con_justificacion'] ?? 0,

            // Horas Extra
            'th_asi_calcula_horas_extra' => $extras_arr['calcula_horas_extra'] ?? 0,
            'th_asi_horas_suplementarias' => $extras_arr['horas_suplementarias'] ?? '',
            'th_asi_horas_extraordinarias' => $extras_arr['horas_extras'] ?? '',
            'th_asi_rango_suplementarias' => $extras_arr['rango_suplementarias'] ?? '',
            'th_asi_rango_extras' => $extras_arr['rango_extras'] ?? '',
        ];

        $salida = $this->insertar_editar_calculo_persona($parametros);

        return $salida;

        // print_r($salida);
        // exit();
        // die();
    }

    function calcular_horas_trabajadas(
        $horas_trabajo_min,
        $hora_entrada_completa,
        $hora_salida_completa,
        $resultado_descanso = [],
        $justificacion_arr = [],
        $es_feriado = 0
    ) {
        $hay_marcacion = !empty($hora_entrada_completa) && !empty($hora_salida_completa);

        // Inicializar variables
        $min_entrada_salida = 0;
        $min_post_descanso = 0;
        $min_justificados = 0;
        $tipo_justificacion = 'NINGUNA';
        $min_finales = 0;
        $trabajo_en_feriado = 'NO';
        $trabajo_con_justificacion = 'NO';
        $horas_trabajadas_reales = '00:00';

        if ($hay_marcacion) {
            $dt_entrada = new DateTime($hora_entrada_completa);
            $dt_salida = new DateTime($hora_salida_completa);
            if ($dt_salida < $dt_entrada) $dt_salida->modify('+1 day');

            $segundos_trabajados = $dt_salida->getTimestamp() - $dt_entrada->getTimestamp();
            $min_entrada_salida = max(0, floor($segundos_trabajados / 60));

            $min_descanso = isset($resultado_descanso['minutos_descanso']) ? (int)$resultado_descanso['minutos_descanso'] : 0;
            $min_post_descanso = max(0, $min_entrada_salida - $min_descanso);

            $horas_trabajadas_reales = $this->minutos_a_horas_mm($min_post_descanso);
            $min_finales = $min_post_descanso;

            // Justificación
            if (!empty($justificacion_arr) && $justificacion_arr['justificado'] === 'SI') {
                $min_justificados = (int)$justificacion_arr['minutos_justificados'];
                $tipo_justificacion = $justificacion_arr['tipo_justificacion'];
                $trabajo_con_justificacion = 'SI';

                if ($justificacion_arr['es_rango']) {
                    $min_finales = max($min_finales, $horas_trabajo_min);
                } else {
                    $min_finales += $min_justificados;
                }
            }

            // Feriado con marcación
            if ($es_feriado['es_feriado'] == 1) {
                $tipo_justificacion = 'FERIADO';
                $trabajo_en_feriado = 'SI';
                // Aquí NO se fuerza jornada mínima, se mantiene lo que trabajó
            }
        } else {
            // No hay marcaciones, validar justificación
            if (!empty($justificacion_arr) && $justificacion_arr['justificado'] === 'SI') {
                $min_justificados = (int)$justificacion_arr['minutos_justificados'];
                $tipo_justificacion = $justificacion_arr['tipo_justificacion'];
                $trabajo_con_justificacion = 'SI';

                if ($justificacion_arr['es_rango']) {
                    $min_finales = max($min_finales, $horas_trabajo_min);
                } else {
                    $min_finales = $min_justificados;
                }
            }

            // No marcación, pero es feriado
            if ($es_feriado['es_feriado'] == 1) {
                $tipo_justificacion = 'FERIADO';
                $min_finales = max($min_finales, $horas_trabajo_min);
            }
        }

        $horas_trabajo_hora = $this->minutos_a_horas_mm($horas_trabajo_min);

        $min_faltantes = max(0, $horas_trabajo_min - $min_finales);
        $horas_faltantes = $this->minutos_a_horas_mm($min_faltantes);

        // Formatos finales
        $tiempo_entrada_salida     = $this->minutos_a_horas_mm($min_entrada_salida);
        $tiempo_post_descanso      = $this->minutos_a_horas_mm($min_post_descanso);
        $tiempo_post_justificacion = $this->minutos_a_horas_mm($min_finales);
        $horas_excedentes          = $this->minutos_a_horas_mm(max(0, $min_finales - $horas_trabajo_min));
        $cumple                    = $min_finales >= $horas_trabajo_min ? 'SI' : 'NO';

        return [
            'tiempo_entrada_salida'     => $tiempo_entrada_salida,
            'tiempo_post_descanso'      => $tiempo_post_descanso,
            'tiempo_post_justificacion' => $tiempo_post_justificacion,
            'horas_trabajadas'          => $tiempo_post_justificacion,
            'horas_trabajadas_reales'   => $horas_trabajadas_reales,
            'horas_excedentes'          => $horas_excedentes,
            'horas_faltantes'           => $horas_faltantes,
            'cumple_horas_trabajadas'   => $cumple,
            'tipo_justificacion'        => $tipo_justificacion,
            'minutos_descanso'          => $min_descanso ?? 0,
            'minutos_justificados'      => $min_justificados,
            'es_feriado'                => $es_feriado['es_feriado'],
            'trabajo_en_feriado'        => $trabajo_en_feriado,
            'trabajo_con_justificacion' => $trabajo_con_justificacion,
            'horas_trabajo_hora'        => $horas_trabajo_hora,
        ];
    }

    //No se usa
    function calcular_horas_trabajadas_2($horas_trabajo_min, $hora_entrada_completa, $hora_salida_completa)
    {
        // Crear objetos DateTime
        $dt_entrada = new DateTime($hora_entrada_completa);
        $dt_salida = new DateTime($hora_salida_completa);

        // Si la salida es antes que la entrada, sumar un día
        if ($dt_salida < $dt_entrada) {
            $dt_salida->modify('+1 day');
        }

        // Calcular diferencia en segundos
        $segundos_trabajados = $dt_salida->getTimestamp() - $dt_entrada->getTimestamp();
        $minutos_trabajados = floor($segundos_trabajados / 60);

        // Convertir a HH:MM
        $horas = floor($minutos_trabajados / 60);
        $minutos = $minutos_trabajados % 60;
        $horas_trabajadas = str_pad($horas, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutos, 2, '0', STR_PAD_LEFT);

        // Evaluar si cumple la jornada mínima
        $cumple = $minutos_trabajados >= $horas_trabajo_min ? 'SI' : 'NO';

        // Calcular excedente
        $excedente_minutos = max(0, $minutos_trabajados - $horas_trabajo_min);
        $horas_excedentes = str_pad(floor($excedente_minutos / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad($excedente_minutos % 60, 2, '0', STR_PAD_LEFT);

        return [
            'horas_trabajadas' => $horas_trabajadas,
            'horas_excedentes' => $horas_excedentes,
            'cumple_horas_trabajadas' => $cumple
        ];
    }

    function calcular_descanso_real(
        $th_per_id,
        $fecha,
        $usar_descanso,
        $descanso,
        $hora_descanso,
        $descanso_inicio,
        $descanso_fin,
        $tolerancia_inicio,
        $tolerancia_fin,
        $hora_ajustada_str,
        $hora_salida_str,
        $fin_hora_turno_entrada
    ) {
        $salida_descanso = null;
        $regreso_descanso = null;

        $minutos_descanso = 0;
        $modo = 'NINGUNO';

        // Caso 1: descanso fijo
        if ($descanso == 1 && $usar_descanso == 0) {
            $minutos_descanso = $hora_descanso;
            $modo = 'FIJO';
        }

        // Caso 2: descanso por rango con marcaciones
        if ($usar_descanso == 1) {
            $modo = 'RANGO';

            $fecha_base = new DateTime($fecha . ' 00:00:00');

            $inicio_teorico = clone $fecha_base;
            $inicio_teorico->modify("+$descanso_inicio minutes");

            $fin_teorico = clone $fecha_base;
            $fin_teorico->modify("+$descanso_fin minutes");

            $inicio_rango = clone $inicio_teorico;
            $inicio_rango->modify("-$tolerancia_inicio minutes");

            $fin_rango = clone $fin_teorico;
            $fin_rango->modify("+$tolerancia_fin minutes");

            $inicio_rango_str = $inicio_rango->format('Y-m-d H:i:s');
            $fin_rango_str = $fin_rango->format('Y-m-d H:i:s');

            // Obtener marcaciones reales dentro del rango
            $marcacion_inicio = $this->obtener_registro_entrada($th_per_id, $inicio_rango_str, $fin_rango_str);
            $marcacion_fin = $this->obtener_registro_salida($th_per_id, $inicio_rango_str, null, null, $fin_hora_turno_entrada);

            if (!empty($marcacion_inicio) && !empty($marcacion_fin)) {
                $salida_descanso = new DateTime($marcacion_inicio[0]['th_acc_fecha_hora']);
                $regreso_descanso = new DateTime($marcacion_fin[0]['th_acc_fecha_hora']);

                // Verificar que el descanso esté dentro de la jornada (entre entrada y salida reales)
                $hora_entrada = new DateTime($hora_ajustada_str);
                $hora_salida = new DateTime($hora_salida_str);

                if ($salida_descanso > $hora_entrada && $regreso_descanso < $hora_salida) {
                    $minutos_descanso = floor(($regreso_descanso->getTimestamp() - $salida_descanso->getTimestamp()) / 60);
                } else {
                    $modo = 'RANGO_FUERA_JORNADA';
                }
            } else {
                $modo = 'RANGO_INCOMPLETO';
                $minutos_descanso = $hora_descanso;
            }
        }

        // Convertir a HH:MM con DateTime
        $base = new DateTime('00:00');
        $intervalo_descanso = new DateInterval("PT{$minutos_descanso}M");
        $base->add($intervalo_descanso);
        $horas_descanso = $base->format('H:i');

        return [
            'minutos_descanso' => $horas_descanso,
            'modo_descanso' => $modo,
            'hora_inicio_descanso' => $salida_descanso ? $salida_descanso->format('H:i:s') : '0',
            'hora_fin_descanso' => $regreso_descanso ? $regreso_descanso->format('H:i:s') : '0'
        ];
    }

    function validar_justificacion($th_per_id, $fecha)
    {
        $justificacion = $this->obtener_justificacion($th_per_id, $fecha);

        if (!$justificacion || count($justificacion) == 0) {
            return [
                'justificado' => 'NO',
                'tipo_justificacion' => null,
                'justificacion' => null,
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'horas_justificadas' => '00:00',
                'minutos_justificados' => 0,
                'es_rango' => 0,
                'tipo_asignacion' => null
            ];
        }

        $datos = $justificacion[0]; // viene como array de 1 fila

        return [
            'justificado' => 'SI',
            'tipo_justificacion' => $datos['tipo_justificacion'],
            'justificacion' => $datos['justificacion'],
            'fecha_inicio' => $datos['fecha_inicio'],
            'fecha_fin' => $datos['fecha_fin'],
            'horas_justificadas' => $datos['horas_justificadas'],
            'minutos_justificados' => $datos['minutos_justificados'],
            'es_rango' => $datos['es_rango'],
            'tipo_asignacion' => $datos['tipo_asignacion']
        ];
    }

    function calcular_horas_extras_detalladas(
        $fecha,
        $calcular_horas_extra,
        $supl_ini,
        $supl_fin,
        $extra_ini,
        $extra_fin,
        $hora_entrada_completa,
        $hora_salida_completa
    ) {
        $min_supl = 0;
        $min_extra = 0;
        $modo = 'NO_APLICA';

        $rango_supl = '';
        $rango_extra = '';

        if ($calcular_horas_extra != 1) {
            return [
                'horas_suplementarias' => '00:00',
                'horas_extras' => '00:00',
                'min_suplementarias' => 0,
                'min_extras' => 0,
                'modo_extra' => $modo,
                'rango_suplementarias' => $rango_supl,
                'rango_extras' => $rango_extra
            ];
        }

        $modo = 'RANGO';
        $fecha_base = new DateTime($fecha . ' 00:00:00');

        // Rangos suplementarias
        $dt_supl_ini = clone $fecha_base;
        $dt_supl_ini->modify("+$supl_ini minutes");

        $dt_supl_fin = clone $fecha_base;
        $dt_supl_fin->modify("+$supl_fin minutes");

        // Rangos extraordinarias
        $dt_extra_ini = clone $fecha_base;
        $dt_extra_ini->modify("+$extra_ini minutes");

        $dt_extra_fin = clone $fecha_base;
        $dt_extra_fin->modify("+$extra_fin minutes");

        // Rango legible
        $rango_supl = $dt_supl_ini->format('H:i') . ' - ' . $dt_supl_fin->format('H:i');
        $rango_extra = $dt_extra_ini->format('H:i') . ' - ' . $dt_extra_fin->format('H:i');

        // Entrada y salida reales
        $entrada = new DateTime($hora_entrada_completa);
        $salida = new DateTime($hora_salida_completa);
        if ($salida < $entrada) {
            $salida->modify('+1 day');
        }

        // Calcular traslapes reales
        $min_supl = $this->calcular_minutos_traslapados($entrada, $salida, $dt_supl_ini, $dt_supl_fin);
        $min_extra = $this->calcular_minutos_traslapados($entrada, $salida, $dt_extra_ini, $dt_extra_fin);

        // Convertir a HH:MM
        $dt_supl = new DateTime('00:00');
        $dt_supl->add(new DateInterval("PT{$min_supl}M"));

        $dt_extra = new DateTime('00:00');
        $dt_extra->add(new DateInterval("PT{$min_extra}M"));

        return [
            'horas_suplementarias' => $dt_supl->format('H:i'),
            'horas_extras' => $dt_extra->format('H:i'),
            'min_suplementarias' => $min_supl,
            'min_extras' => $min_extra,
            'modo_extra' => $modo,
            'rango_suplementarias' => $rango_supl,
            'rango_extras' => $rango_extra
        ];
    }

    //Gestion de Carga Masiva de Datos

    function insertar_editar_calculo_persona($parametro)
    {
        // $sql = [];

        // foreach ($grupo as $parametro) {
        // Construir los datos en formato esperado por upsertSQL
        $dato = array(
            //Datos personales
            array('campo' => 'th_per_id', 'dato' => $parametro['th_per_id']),
            array('campo' => 'th_asi_apellidos', 'dato' => $parametro['th_asi_apellidos']),
            array('campo' => 'th_asi_nombres', 'dato' => $parametro['th_asi_nombres']),
            array('campo' => 'th_asi_empleado', 'dato' => $parametro['th_asi_empleado']),
            array('campo' => 'th_asi_cedula', 'dato' => $parametro['th_asi_cedula']),
            array('campo' => 'th_asi_correo_institucional', 'dato' => $parametro['th_asi_correo_institucional']),

            //Programación
            array('campo' => 'th_asi_fecha', 'dato' => $parametro['th_asi_fecha']),
            array('campo' => 'th_asi_dia', 'dato' => $parametro['th_asi_dia']),
            array('campo' => 'th_asi_departamento', 'dato' => $parametro['th_asi_departamento']),
            array('campo' => 'th_asi_horario_contrato', 'dato' => $parametro['th_asi_horario_contrato']),
            array('campo' => 'th_asi_turno_nombre', 'dato' => $parametro['th_asi_turno_nombre']),
            array('campo' => 'th_asi_fecha_inicio_programacion', 'dato' => $parametro['th_asi_fecha_inicio_programacion']),
            array('campo' => 'th_asi_fecha_fin_programacion', 'dato' => $parametro['th_asi_fecha_fin_programacion']),
            array('campo' => 'th_asi_prioridad_programacion', 'dato' => $parametro['th_asi_prioridad_programacion']),

            //Entrada
            array('campo' => 'th_asi_entrada_hora_inicio_turno', 'dato' => $parametro['th_asi_entrada_hora_inicio_turno']),
            array('campo' => 'th_asi_entrada_hora_fin_turno', 'dato' => $parametro['th_asi_entrada_hora_fin_turno']),
            array('campo' => 'th_asi_regentrada', 'dato' => $parametro['th_asi_regentrada']),
            array('campo' => 'th_asi_hora_entrada', 'dato' => $parametro['th_asi_hora_entrada']),
            array('campo' => 'th_asi_hora_ajustada', 'dato' => $parametro['th_asi_hora_ajustada']),
            array('campo' => 'th_asi_atrasos', 'dato' => $parametro['th_asi_atrasos']),
            array('campo' => 'th_asi_ausente', 'dato' => $parametro['th_asi_ausente']),

            //Salida
            array('campo' => 'th_asi_salida_hora_inicio_turno', 'dato' => $parametro['th_asi_salida_hora_inicio_turno']),
            array('campo' => 'th_asi_salida_hora_fin_turno', 'dato' => $parametro['th_asi_salida_hora_fin_turno']),
            array('campo' => 'th_asi_regsalida', 'dato' => $parametro['th_asi_regsalida']),
            array('campo' => 'th_asi_salida_marcacion_str', 'dato' => $parametro['th_asi_salida_marcacion_str']),
            array('campo' => 'th_asi_hora_salida', 'dato' => $parametro['th_asi_hora_salida']),
            array('campo' => 'th_asi_salidas_temprano', 'dato' => $parametro['th_asi_salidas_temprano']),
            array('campo' => 'th_asi_salida_ausente', 'dato' => $parametro['th_asi_salida_ausente']),

            //Descanso
            array('campo' => 'th_asi_usa_descanso_formal', 'dato' => $parametro['th_asi_usa_descanso_formal']),
            array('campo' => 'th_asi_descanso_inicio', 'dato' => $parametro['th_asi_descanso_inicio']),
            array('campo' => 'th_asi_descanso_fin', 'dato' => $parametro['th_asi_descanso_fin']),
            array('campo' => 'th_asi_reg_inicio_descanso', 'dato' => $parametro['th_asi_descanso_inicio']),
            array('campo' => 'th_asi_reg_fin_descanso', 'dato' => $parametro['th_asi_descanso_fin']),
            array('campo' => 'th_asi_descanso_simple', 'dato' => $parametro['th_asi_descanso_simple']),
            array('campo' => 'th_asi_minutos_descanso_simple', 'dato' => $parametro['th_asi_minutos_descanso_simple']),
            array('campo' => 'th_asi_minutos_descanso_calculado', 'dato' => $parametro['th_asi_minutos_descanso_calculado']),

            //Justificación
            array('campo' => 'th_asi_dia_justificado', 'dato' => $parametro['th_asi_dia_justificado']),
            array('campo' => 'th_asi_motivo_justificacion', 'dato' => $parametro['th_asi_motivo_justificacion']),
            array('campo' => 'th_asi_inicio_justificacion', 'dato' => $parametro['th_asi_inicio_justificacion']),
            array('campo' => 'th_asi_fin_justificacion', 'dato' => $parametro['th_asi_fin_justificacion']),
            array('campo' => 'th_asi_horas_justificadas', 'dato' => $parametro['th_asi_horas_justificadas']),
            array('campo' => 'th_asi_justificacion_es_rango', 'dato' => $parametro['th_asi_justificacion_es_rango']),
            array('campo' => 'th_asi_justificacion_asignado_a', 'dato' => $parametro['th_asi_justificacion_asignado_a']),
            array('campo' => 'th_asi_horas_trabajo_hora', 'dato' => $parametro['th_asi_horas_trabajo_hora']),
            array('campo' => 'th_asi_tipo_justificacion_aplicada', 'dato' => $parametro['th_asi_tipo_justificacion_aplicada']),
            array('campo' => 'th_asi_minutos_justificados_calculado', 'dato' => $parametro['th_asi_minutos_justificados_calculado']),

            //Cálculo
            array('campo' => 'th_asi_dias_trabajados', 'dato' => $parametro['th_asi_dias_trabajados']),
            array('campo' => 'th_asi_cumple_jornada', 'dato' => $parametro['th_asi_cumple_jornada']),
            array('campo' => 'th_asi_horas_faltantes', 'dato' => $parametro['th_asi_horas_faltantes']),
            array('campo' => 'th_asi_horas_excedentes', 'dato' => $parametro['th_asi_horas_excedentes']),
            array('campo' => 'th_asi_tiempo_entrada_salida', 'dato' => $parametro['th_asi_tiempo_entrada_salida']),
            array('campo' => 'th_asi_tiempo_post_descanso', 'dato' => $parametro['th_asi_tiempo_post_descanso']),
            array('campo' => 'th_asi_tiempo_post_justificacion', 'dato' => $parametro['th_asi_tiempo_post_justificacion']),
            array('campo' => 'th_asi_horas_trabajadas_finales', 'dato' => $parametro['th_asi_horas_trabajadas_finales']),

            //Feriado
            array('campo' => 'th_asi_es_feriado', 'dato' => $parametro['th_asi_es_feriado']),
            array('campo' => 'th_asi_trabajo_en_feriado', 'dato' => $parametro['th_asi_trabajo_en_feriado']),
            array('campo' => 'th_asi_trabajo_con_justificacion', 'dato' => $parametro['th_asi_trabajo_con_justificacion']),

            //Horas extra
            array('campo' => 'th_asi_calcula_horas_extra', 'dato' => $parametro['th_asi_calcula_horas_extra']),
            array('campo' => 'th_asi_horas_suplementarias', 'dato' => $parametro['th_asi_horas_suplementarias']),
            array('campo' => 'th_asi_horas_extraordinarias', 'dato' => $parametro['th_asi_horas_extraordinarias']),
            array('campo' => 'th_asi_rango_suplementarias', 'dato' => $parametro['th_asi_rango_suplementarias']),
            array('campo' => 'th_asi_rango_extras', 'dato' => $parametro['th_asi_rango_extras']),
        );

        // Generar SQL MERGE para este registro (por empleado + fecha)
        // $sql[] = array(
        //     $this->upsertSQL('th_control_acceso_calculos', $dato, ['th_per_id', 'th_asi_fecha'])
        // );
        // }

        // Construir la sentencia final concatenando todos los MERGE
        // $sentenciaSql = '';
        // foreach ($sql as $grupoSQL) {
        //     foreach ($grupoSQL as $consulta) {
        //         $sentenciaSql .= $consulta . ' ';
        //     }
        // }

        // print_r($sentenciaSql);
        // exit();
        // die();
        // Ejecutar la sentencia SQL en lote
        // $resultado = $this->sql_string($sentenciaSql);

        // // Retornar según resultado
        // if ($resultado !== 1) {
        //     return -10; // Error en ejecución
        //     exit();
        // }

        return $dato;
    }

    function insertar_registro_sin_turno($persona_basica)
    {
        // Construir los datos en formato esperado por upsertSQL
        $dato = [
            ['campo' => 'th_per_id', 'dato' => $persona_basica['th_per_id'] ?? 0],
            ['campo' => 'th_asi_apellidos', 'dato' => $persona_basica['th_asi_apellidos'] ?? ''],
            ['campo' => 'th_asi_nombres', 'dato' => $persona_basica['th_asi_nombres'] ?? ''],
            ['campo' => 'th_asi_empleado', 'dato' => $persona_basica['th_asi_empleado'] ?? ''],
            ['campo' => 'th_asi_cedula', 'dato' => $persona_basica['th_asi_cedula'] ?? ''],
            ['campo' => 'th_asi_correo_institucional', 'dato' => $persona_basica['th_asi_correo_institucional'] ?? ''],
            ['campo' => 'th_asi_departamento', 'dato' => $persona_basica['th_asi_departamento'] ?? ''],
            ['campo' => 'th_asi_fecha', 'dato' => $persona_basica['th_asi_fecha'] ?? ''],
            ['campo' => 'th_asi_dia', 'dato' => $persona_basica['th_asi_dia'] ?? ''],
            ['campo' => 'th_asi_sin_turno', 'dato' => 'SI']
        ];

        // Generar sentencia SQL con upsertSQL
        // $sql[] = array(
        //     $this->upsertSQL('th_control_acceso_calculos', $dato, ['th_per_id', 'th_asi_fecha'])
        // );

        // // Construir la sentencia SQL
        // $sentenciaSql = '';
        // foreach ($sql as $grupo) {
        //     foreach ($grupo as $consulta) {
        //         $sentenciaSql .= $consulta . ' ';
        //     }
        // }

        // print_r($sentenciaSql); exit(); die();

        // // Ejecutar la sentencia
        // $resultado = $this->sql_string($sentenciaSql);

        // // Retornar según resultado
        // if ($resultado !== 1) {
        //     return -10; // Error en ejecución
        // }

        return $dato;
    }



    //Funciones adicionales
    function convertir_a_minutos($hora_hhmm)
    {
        list($horas, $minutos) = explode(':', $hora_hhmm);
        return (int)$horas * 60 + (int)$minutos;
    }

    function minutos_a_horas_mm($min)
    {
        $dt = new DateTime('00:00');
        $dt->add(new DateInterval("PT{$min}M"));
        return $dt->format('H:i');
    }

    function calcular_minutos_traslapados($inicio_a, $fin_a, $inicio_b, $fin_b)
    {
        $inicio = max($inicio_a->getTimestamp(), $inicio_b->getTimestamp());
        $fin = min($fin_a->getTimestamp(), $fin_b->getTimestamp());
        return max(0, floor(($fin - $inicio) / 60));
    }



    //Funciones para generar la carga masiva de datos

    function carga_masiva($fecha)
    {
        $personas = $this->obtener_personas_programa_horario($fecha);

        //Para personas especificas
        // $personas = [
        //     ['th_per_id' => 2]
        // ];

        // print_r($personas); exit(); die();

        $grupos = array_chunk($personas, 100);
        $total_insertadas = 0;

        foreach ($grupos as $grupo) {
            $datos_lote = [];

            foreach ($grupo as $persona) {
                $th_per_id = $persona['th_per_id'];

                $parametros = $this->calculo_persona_control_acceso($th_per_id, $fecha);

                if (is_array($parametros)) {
                    $fila = [];

                    foreach ($parametros as $item) {
                        $campo = $item['campo'] ?? null;
                        $dato = $item['dato'] ?? null;

                        if ($campo !== null) {
                            $fila[$campo] = $dato;
                        }
                    }

                    if (!empty($fila)) {
                        $datos_lote[] = $fila;
                    }
                }
            }

            if (!empty($datos_lote)) {
                $sql_lote = $this->generar_merge_lote('th_control_acceso_calculos', $datos_lote, ['th_per_id', 'th_asi_fecha']);

                // print_r($sql_lote);
                // exit();
                // die();

                $resultado = $this->sql_string($sql_lote);

                if ($resultado !== 1) {
                    return "[ERR] Error al ejecutar un lote.";
                    // return -1;
                }

                $total_insertadas += count($datos_lote);
            }
        }

        return "[INF] Inserción masiva finalizada. Total de personas procesadas: $total_insertadas";
    }

    function generar_merge_lote($tabla, $datos_lote, $campos_clave)
    {
        if (empty($datos_lote)) return '';

        // Reunir todas las columnas presentes en el lote
        $todas_las_columnas = [];
        foreach ($datos_lote as $fila) {
            foreach (array_keys($fila) as $col) {
                if (!in_array($col, $todas_las_columnas)) {
                    $todas_las_columnas[] = $col;
                }
            }
        }

        // Construir cada fila usando todas las columnas (rellenando con NULL si no existe)
        $values = [];
        foreach ($datos_lote as $fila) {
            $valores = [];
            foreach ($todas_las_columnas as $col) {
                $valor = array_key_exists($col, $fila) ? $fila[$col] : null;
                $valores[] = is_null($valor) ? "NULL" : "'" . str_replace("'", "''", $valor) . "'";
            }
            $values[] = "(" . implode(", ", $valores) . ")";
        }

        $target = "target";
        $source = "source";

        $sql = "MERGE INTO $tabla AS $target\n";
        $sql .= "USING (VALUES \n" . implode(",\n", $values) . "\n) AS $source (" . implode(", ", $todas_las_columnas) . ")\n";

        // Condición ON
        $condicion_on = [];
        foreach ($campos_clave as $col) {
            $condicion_on[] = "$target.$col = $source.$col";
        }
        $sql .= "ON " . implode(" AND ", $condicion_on) . "\n";

        // UPDATE
        $set = [];
        foreach ($todas_las_columnas as $col) {
            if (!in_array($col, $campos_clave)) {
                $set[] = "$target.$col = $source.$col";
            }
        }
        $sql .= "WHEN MATCHED THEN UPDATE SET " . implode(", ", $set) . "\n";

        // INSERT
        $sql .= "WHEN NOT MATCHED THEN INSERT (" . implode(", ", $todas_las_columnas) . ")\n";
        $sql .= "VALUES (" . implode(", ", array_map(fn($c) => "$source.$c", $todas_las_columnas)) . ");";

        return $sql;
    }

    function upsertSQL($tabla, $datos, $where)
    {
        $campos = '';
        $insertCampos = '';
        $insertValores = '';
        $sourceCols = '';
        $sourceVals = '';
        $onClause = '';
        $updateClause = '';

        foreach ($datos as $value) {
            $campo = $value['campo'];
            $valor = $value['dato'];

            // Formateo seguro de datos
            if (is_null($value['dato']) || $value['dato'] === '') {
                $valor = "''"; // o "NULL" si prefieres no guardar cadena vacía
            } else if (is_string($value['dato'])) {
                $valor = "'" . str_replace("'", "''", $value['dato']) . "'";
            } else {
                $valor = is_numeric($value['dato']) ? $value['dato'] : "'" . $value['dato'] . "'";
            }

            // INSERT
            $campos .= "$campo,";
            $insertCampos .= "$campo,";
            $insertValores .= "$valor,";
        }

        $campos = rtrim($campos, ',');
        $insertCampos = rtrim($insertCampos, ',');
        $insertValores = rtrim($insertValores, ',');

        // Construcción del source y ON para claves compuestas
        $whereClauseList = [];
        foreach ($where as $w) {
            $sourceCols .= "$w,";
        }
        $sourceCols = rtrim($sourceCols, ',');

        foreach ($where as $w) {
            // Encontrar el valor correspondiente en $datos
            $val = '';
            foreach ($datos as $v) {
                if ($v['campo'] === $w) {
                    $val = is_string($v['dato']) ? "'" . str_replace("'", "''", $v['dato']) . "'" : $v['dato'];
                    break;
                }
            }
            $sourceVals .= "$val,";
            $whereClauseList[] = "target.$w = source.$w";
        }

        $sourceVals = rtrim($sourceVals, ',');
        $onClause = implode(' AND ', $whereClauseList);

        // Construcción del SET para UPDATE (excepto claves)
        foreach ($datos as $value) {
            $campo = $value['campo'];
            if (in_array($campo, $where)) continue; // omitimos claves
            $valor = is_string($value['dato']) ? "'" . str_replace("'", "''", $value['dato']) . "'" : $value['dato'];
            $updateClause .= "target.$campo = $valor, ";
        }
        $updateClause = rtrim($updateClause, ', ');

        // Final SQL MERGE
        $sql =
            "MERGE INTO $tabla AS target
                USING (VALUES ($sourceVals)) AS source ($sourceCols)
                ON $onClause
                WHEN MATCHED THEN
                    UPDATE SET $updateClause
                WHEN NOT MATCHED THEN
                    INSERT ($insertCampos)
                    VALUES ($insertValores);
                ";

        return $sql;
    }

    function calcular_dia($fecha)
    {
        $ingresar_fecha = strtotime($fecha);
        $dias_ingles = date('l', $ingresar_fecha);

        switch ($dias_ingles) {
            case "Monday":
                return "Lunes";
            case 'Tuesday':
                return "Martes";
            case 'Wednesday':
                return "Miércoles";
            case 'Thursday':
                return "Jueves";
            case 'Friday':
                return "Viernes";
            case 'Saturday':
                return "Sábado";
            case 'Sunday':
                return "Domingo";
        }
    }
}
