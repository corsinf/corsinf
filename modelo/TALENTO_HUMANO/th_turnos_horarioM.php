<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_turnos_horarioM extends BaseModel
{
    protected $tabla = 'th_turnos_horario';
    protected $primaryKey = 'th_tuh_id AS _id';

    protected $camposPermitidos = [
        'th_hor_id AS id_horario',
        'th_tur_id AS id_turno',
        'th_tuh_dia AS dia',
        'th_tuh_estado AS estado',
        'th_tuh_fecha_creacion AS fecha_creacion',
        'th_tuh_fecha_modificacion AS fecha_modificacion',
    ];

    function listar_turnos_horarios($id_horario)
    {
        if ($id_horario != '' && $id_horario != null) {
            $sql =
                "SELECT
                    tur_hor.th_tuh_id AS _id,
                    tur.th_tur_nombre AS nombre,
                    tur.th_tur_hora_entrada AS hora_entrada,
                    tur.th_tur_hora_salida AS hora_salida,
                    tur.th_tur_color AS color,
                    tur_hor.th_tur_id AS id_turno,
                    tur_hor.th_tuh_dia AS dia,
                    th_hor_id AS id_horario
                FROM
                th_turnos_horario tur_hor
                INNER JOIN th_turnos tur ON tur_hor.th_tur_id = tur.th_tur_id 
                WHERE
                tur_hor.th_hor_id = '$id_horario';";

            $datos = $this->db->datos($sql);
            return $datos;
        }
        return null;
    }


    function listar_turno_dia($id_horario, $id_dia)
    {
        if ($id_horario != '' && $id_horario != null) {
            $sql =
                "SELECT
                    tur_hor.th_tuh_id AS _id,
                    tur.th_tur_nombre AS nombre,
                    tur.th_tur_hora_entrada AS hora_entrada,
                    tur.th_tur_hora_salida AS hora_salida,
                    tur.th_tur_checkin_registro_inicio AS hora_tolerancia_entrada_inicio,
                    tur.th_tur_checkin_registro_fin AS hora_tolerancia_entrada_fin,
                    tur.th_tur_checkout_salida_inicio AS hora_tolerancia_salida_inicio,
                    tur.th_tur_checkout_salida_fin AS hora_tolerancia_salida_fin,
                    tur.th_tur_limite_tardanza_in AS limite_tardanza_in,
                    tur.th_tur_limite_tardanza_out AS limite_tardanza_out,
                    tur.th_tur_color AS color,
                    tur_hor.th_tur_id AS id_turno,
                    tur_hor.th_tuh_dia AS dia,
                    th_hor_id AS id_horario
                FROM
                th_turnos_horario tur_hor
                INNER JOIN th_turnos tur ON tur_hor.th_tur_id = tur.th_tur_id 
                WHERE
                tur_hor.th_hor_id = '$id_horario'
                AND tur_hor.th_tuh_dia = '$id_dia';";

            $datos = $this->db->datos($sql);
            return $datos;
        }
        return null;
    }
}
