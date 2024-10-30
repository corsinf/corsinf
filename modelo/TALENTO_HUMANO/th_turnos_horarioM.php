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
                    th_tuh_id AS _id,
                    th_tur_nombre AS nombre,
                    th_tur_hora_entrada AS hora_entrada,
                    th_tur_hora_salida AS hora_salida,
                    th_tur_color AS color,
                    th_tuh_dia AS dia
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
}
