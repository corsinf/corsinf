<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_control_accesoM extends BaseModel
{
    protected $tabla = 'th_control_acceso';
    protected $primaryKey = 'th_acc_id AS _id';

    protected $camposPermitidos = [
        'th_per_id',
        'th_cardNo',
        'th_dis_id',
        'th_acc_tipo_registro',
        'th_acc_hora',
        'th_acc_fecha_hora',
        'th_acc_tipo_origen',
        'th_acc_fecha_creacion',
        'th_acc_fecha_modificacion',
        'th_per_id',
        'th_acc_puerto',
        'th_acc_tipo_origen',
        'th_act_id',
        'th_acc_detalle_registro',
        'th_acc_dia',
        'th_acc_atraso_min',
        'th_acc_almuerzo_min',
        'th_acc_justificacion_min',
        'th_acc_hor_faltantesJornada_min',
        'th_acc_hor_suplementarias_min',
        'th_acc_hor_extraordinarias_min',
        'th_acc_horas_trabajadasJornada_min',
        'th_acc_horario_jornada'

    ];

    function listarJoin()
    {
        // Construir la parte JOIN de la consulta
        $this->join('th_card_data', 'th_card_data.th_cardNo = th_control_acceso.th_cardNo');
        $this->join('th_personas', 'th_personas.th_per_id = th_control_acceso.th_per_id');
        $datos = $this->where('th_acc_tipo_origen', 'BIO')->listar(10, true);
        return $datos;
    }
    function buscarAccesoPorPersonaYFecha($idPersona, $fecha)
    {
        $idPersona = intval($idPersona);
        $fecha = date('Y-m-d', strtotime($fecha));
        $sql = "SELECT 
            th_acc_id,
            th_acc_hora,
            th_acc_fecha_hora,
            th_per_id
            FROM th_control_acceso ca
            WHERE ca.th_per_id = '$idPersona'
            AND CONVERT(DATE, th_acc_fecha_hora) = '$fecha';";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function actualizar_per_id_no_card()
    {
        // Tambien se  puede quitar el where
        $sql =
            "UPDATE ca
                SET ca.th_per_id = cd.th_per_id
            FROM _talentoh.th_control_acceso ca
            JOIN _talentoh.th_card_data cd ON ca.th_cardNo = cd.th_cardNo;
            ";

        // print_r($sql); exit(); die();

        // $datos = $this->db->datos($sql, false, true, true);
        $datos = $this->db->sql_string($sql, false, true);

        return $datos;
    }

    function listar_personalizado($fecha_ini = '', $fecha_final = '')
    {

        $limit = '';
        if ($fecha_ini == '') {
            $limit = "TOP 1000";
        }

        $sql =
            "SELECT $limit
                ca.th_acc_fecha_hora AS fecha,
                p.th_per_codigo_externo_1 AS nombre,
                d.th_dis_nombre         AS dispositivo_nombre
            FROM th_control_acceso AS ca
            LEFT JOIN th_personas AS p
                ON p.th_per_id = ca.th_per_id
            LEFT JOIN th_dispositivos AS d
                ON d.th_dis_host = ca.th_dis_id
            AND d.th_dis_port = TRY_CONVERT(int, NULLIF(ca.th_acc_puerto, '.'))

            ";

        if ($fecha_ini) {
            $sql .= "WHERE 
                    CONVERT(date, ca.th_acc_fecha_hora) BETWEEN '$fecha_ini' AND '$fecha_final'";
        }

        $sql .= "ORDER BY ca.th_acc_fecha_hora DESC;";

        // print_r($sql); exit(); die();


        $datos = $this->db->datos($sql, false, true);
        return $datos;
    }

    function listar_marcaciones($tabla = false,$fecha_ini = '', $fecha_final = '',$id_usuario=false,$id_departamento=false,$ordenar='sin_ordenar')
    {
        $tabla_search = 'th_control_acceso';
        if($tabla) {$tabla_search  = $tabla ;} 

        $limit = '';
        if ($fecha_ini == '') {
            $limit = "TOP 1000";
        }


        $sql = "
                WITH CTE AS (
                    SELECT $limit
                        ca.th_acc_id AS idAcc,
                        ca.th_cardNo AS card,
                        ca.th_acc_fecha AS Fecha,
                        ca.th_acc_dia AS dia,
                        ca.th_acc_hora AS RegistroSalida,
                        ca.th_per_id AS id,
                        p.th_per_primer_apellido + ' ' + p.th_per_segundo_apellido AS APELLIDOS,
                        p.th_per_primer_nombre + ' ' + p.th_per_segundo_nombre AS NOMBRES,
                        p.th_per_primer_apellido + ' ' + p.th_per_segundo_apellido + ' ' +
                        p.th_per_primer_nombre + ' ' + p.th_per_segundo_nombre AS empleado,
                        p.th_per_cedula AS Cedula,
                        p.th_per_correo AS Correo,
                        ca.th_acc_horario_jornada AS Horario,
                        de.th_dep_nombre AS Departamento,
                        ca.th_acc_atraso_min AS Atrasos,
                        ca.th_acc_hor_suplementarias_min AS Suplem,
                        ca.th_acc_hor_extraordinarias_min AS Extra,
                        ca.th_acc_hor_faltantesJornada_min AS Horas_faltantes,
                        ca.th_acc_horas_trabajadasJornada_min AS Horas_trabajadas,
                        d.th_dis_nombre AS dispositivo_nombre,
                        ca.th_acc_fecha_hora,
                        ca.th_acc_hora_ingreso as RegistroIng,
                        COUNT(*) OVER (PARTITION BY ca.th_per_id) AS TotalMarcaciones,
                        ROW_NUMBER() OVER (
                            PARTITION BY ca.th_per_id
                            ORDER BY ca.th_acc_id DESC
                        ) AS rn
                    FROM _asistencias.".$tabla." AS ca
                    LEFT JOIN _talentoh.th_personas AS p ON p.th_per_id = ca.th_per_id
                    LEFT JOIN _talentoh.th_personas_departamentos AS pd ON p.th_per_id = pd.th_per_id
                    LEFT JOIN _talentoh.th_departamentos AS de ON pd.th_dep_id = de.th_dep_id
                    LEFT JOIN _talentoh.th_dispositivos AS d ON d.th_dis_host = ca.th_dis_id AND d.th_dis_port = TRY_CONVERT(int, NULLIF(ca.th_acc_puerto, '.')) ";

                    if ($fecha_ini) {
                    $sql .= "WHERE 
                            CONVERT(date, ca.th_acc_fecha_hora) BETWEEN '$fecha_ini' AND '$fecha_final'";
                    }
                    if($id_usuario)
                    {
                        $sql.=" AND ca.th_per_id = '".$id_usuario."' ";
                    }
                    if($id_departamento!='todos' && $id_departamento!=' ' && $id_departamento!=''  && $id_departamento!='0')
                    {
                        $sql.=" AND de.th_dep_id = '".$id_departamento."'";
                    }                    
                    $sql.="
                )
                SELECT *
                FROM CTE
                WHERE rn = 1
                ORDER BY idAcc ";
                $sql.=" DESC;";

        // print_r($sql);die();
        $datos = $this->db->datos($sql, false, true,true);
        return $datos;
    }
    
    function lista_detalle_turnos_x_persona($card=false,$dia=false)
    {
        $sql="SELECT th_pro_id,PH.th_per_id,th_pro_fecha_inicio as 'periodo_ini',th_pro_fecha_fin as 'perido_fin',
        HO.th_hor_id,PE.th_per_cedula as 'cedula', PE.th_per_nombres_completos,th_card_id,th_cardNo,
        th_tur_hora_entrada as 'entrada_min',th_tur_hora_salida as 'salida_min',
        th_tur_limite_tardanza_in as 'tolerancia_ini',th_tur_limite_tardanza_out as 'tolerancia_fin',
        th_tur_checkin_registro_inicio as 'entrada_tiempo_marcacion_valida_inicio',
        th_tur_checkin_registro_fin as 'entrada_tiempo_marcacion_valida_fin',
        th_tur_checkout_salida_inicio as 'salida_tiempo_marcacion_valida_inicio',
        th_tur_checkout_salida_fin as 'salida_tiempo_marcacion_valida_fin',
        th_tur_valor_hora_trabajar as 'horas_a_trabajar',th_tur_valor_min_trabajar as 'min_a_trabajar',
        th_tur_descanso as 'aplica_descanso',th_tur_usar_descanso as 'aplica_horario_descanso',
        th_tur_hora_descanso as 'tiempo_descanso',th_tur_descanso_inicio as 'descanso_inicio',
        th_tur_descanso_fin as 'descanso_fin',th_tur_tol_ini_descanso as 'adelanto_descanso',
        th_tur_tol_fin_descanso as 'tolerancia_descanso',th_tur_calcular_horas_extra as 'calcular_horas_extra',
        th_tur_supl_ini as 'inico_suplementario',th_tur_supl_fin as 'fin_suplementarias',th_tur_extra_ini as 'inicio_extraordinarias',
        th_tur_extra_fin as 'fin_extraordinarias',HO.th_hor_nombre as turno
        FROM th_programar_horarios PH
        INNER JOIN th_horarios HO ON PH.th_hor_id = HO.th_hor_id
        INNER JOIN th_turnos_horario TH ON HO.th_hor_id = TH.th_hor_id
        INNER JOIN th_turnos TU ON TH.th_tur_id = TU.th_tur_id
        INNER JOIN th_personas PE ON PH.th_per_id = PE.th_per_id
        INNER JOIN th_card_data CA ON PE.th_per_id = CA.th_per_id
        WHERE PH.th_pro_estado = 1 
        AND HO.th_hor_estado = 1 
        AND PE.th_per_estado = 1 ";
        if($card)
        { 
            $sql.=" AND CA.th_cardNo = '".$card."' ";
        }
        if($dia)
        {            
            $sql.=" AND TH.th_tuh_dia = '".$dia."' ";
        }

        $sql.=" UNION ALL ";

        $sql.="SELECT th_pro_id,PH.th_per_id,th_pro_fecha_inicio as 'periodo_ini',th_pro_fecha_fin as 'perido_fin',
                HO.th_hor_id,PE.th_per_cedula as 'cedula', PE.th_per_nombres_completos,th_card_id,th_cardNo,
                th_tur_hora_entrada as 'entrada_min',th_tur_hora_salida as 'salida_min',
                th_tur_limite_tardanza_in as 'tolerancia_ini',th_tur_limite_tardanza_out as 'tolerancia_fin',
                th_tur_checkin_registro_inicio as 'entrada_tiempo_marcacion_valida_inicio',
                th_tur_checkin_registro_fin as 'entrada_tiempo_marcacion_valida_fin',
                th_tur_checkout_salida_inicio as 'salida_tiempo_marcacion_valida_inicio',
                th_tur_checkout_salida_fin as 'salida_tiempo_marcacion_valida_fin',
                th_tur_valor_hora_trabajar as 'horas_a_trabajar',th_tur_valor_min_trabajar as 'min_a_trabajar',
                th_tur_descanso as 'aplica_descanso',th_tur_usar_descanso as 'aplica_horario_descanso_intervalo',
                th_tur_hora_descanso as 'tiempo_descanso' ,th_tur_descanso_inicio as 'descanso_inicio',
                th_tur_descanso_fin as 'descanso_fin', th_tur_tol_ini_descanso as 'adelanto_descanso',
                th_tur_tol_fin_descanso as 'tolerancia_descanso',th_tur_calcular_horas_extra as 'calcular_horas_extra',
                th_tur_supl_ini as 'inico_suplementario',th_tur_supl_fin as 'fin_suplementarias',
                th_tur_extra_ini as 'inicio_extraordinarias',th_tur_extra_fin as 'fin_extraordinarias',HO.th_hor_nombre as turno 
                FROM th_programar_horarios PH 
                INNER JOIN th_horarios HO ON PH.th_hor_id = HO.th_hor_id
                INNER JOIN th_turnos_horario TH ON HO.th_hor_id = TH.th_hor_id
                INNER JOIN th_turnos TU ON TH.th_tur_id = TU.th_tur_id
                INNER JOIN th_personas_departamentos PD ON PH.th_dep_id = PD.th_dep_id 
                INNER JOIN th_personas PE ON PD.th_per_id = PE.th_per_id
                INNER JOIN th_card_data CA ON PE.th_per_id = CA.th_per_id
                WHERE PH.th_pro_estado = 1 
                AND HO.th_hor_estado = 1 
                AND PE.th_per_estado = 1 ";
                if($card)
                { 
                    $sql.=" AND CA.th_cardNo = '".$card."' ";
                }
                if($dia)
                {            
                    $sql.=" AND TH.th_tuh_dia = '".$dia."' ";
                }


        $datos = $this->db->datos($sql, false, true,false);
        return $datos;

    }

    function lista_detalle_turnos_x_departamento($card=false,$dia=false)
    {
        $sql="SELECT th_pro_id,PH.th_per_id,th_pro_fecha_inicio as 'periodo_ini',th_pro_fecha_fin as 'perido_fin',
                HO.th_hor_id,PE.th_per_cedula as 'cedula', PE.th_per_nombres_completos,th_card_id,th_cardNo,
                th_tur_hora_entrada as 'entrada_min',th_tur_hora_salida as 'salida_min',
                th_tur_limite_tardanza_in as 'tolerancia_ini',th_tur_limite_tardanza_out as 'tolerancia_fin',
                th_tur_checkin_registro_inicio as 'entrada_tiempo_marcacion_valida_inicio',
                th_tur_checkin_registro_fin as 'entrada_tiempo_marcacion_valida_fin',
                th_tur_checkout_salida_inicio as 'salida_tiempo_marcacion_valida_inicio',
                th_tur_checkout_salida_fin as 'salida_tiempo_marcacion_valida_fin',
                th_tur_valor_hora_trabajar as 'horas_a_trabajar',th_tur_valor_min_trabajar as 'min_a_trabajar',
                th_tur_descanso as 'aplica_descanso',th_tur_usar_descanso as 'aplica_horario_descanso_intervalo',
                th_tur_hora_descanso as 'tiempo_descanso' ,th_tur_descanso_inicio as 'descanso_inicio',
                th_tur_descanso_fin as 'descanso_fin', th_tur_tol_ini_descanso as 'adelanto_descanso',
                th_tur_tol_fin_descanso as 'tolerancia_descanso',th_tur_calcular_horas_extra as 'calcular_horas_extra',
                th_tur_supl_ini as 'inico_suplementario',th_tur_supl_fin as 'fin_suplementarias',
                th_tur_extra_ini as 'inicio_extraordinarias',th_tur_extra_fin as 'fin_extraordinarias' 
                FROM th_programar_horarios PH 
                INNER JOIN th_horarios HO ON PH.th_hor_id = HO.th_hor_id
                INNER JOIN th_turnos_horario TH ON HO.th_hor_id = TH.th_hor_id
                INNER JOIN th_turnos TU ON TH.th_tur_id = TU.th_tur_id
                INNER JOIN th_personas_departamentos PD ON PH.th_dep_id = PD.th_dep_id 
                INNER JOIN th_personas PE ON PD.th_per_id = PE.th_per_id
                INNER JOIN th_card_data CA ON PE.th_per_id = CA.th_per_id
                WHERE PH.th_pro_estado = 1 
                AND HO.th_hor_estado = 1 
                AND PE.th_per_estado = 1 ";
                if($card)
                { 
                    $sql.=" AND CA.th_cardNo = '".$card."' ";
                }
                if($dia)
                {            
                    $sql.=" AND TH.th_tuh_dia = '".$dia."' ";
                }

        $datos = $this->db->datos($sql, false, true,false);
        return $datos;

    }
}
