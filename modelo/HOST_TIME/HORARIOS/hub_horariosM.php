<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_horariosM extends BaseModel
{
    protected $tabla = 'hub_horarios';
    protected $primaryKey = 'id_horario AS _id';
    protected $camposPermitidos = [
        'id_espacio AS id_espacio',
        'dia_semana AS dia_semana',
        'hora_inicio AS hora_inicio',
        'hora_fin AS hora_fin',
        'activo AS activo',
        'estado AS estado',
    ];

    public function listar_horarios($id_espacio = null, $id_horario = null)
    {
        $sql = "
            SELECT
                h.id_horario AS _id,
                h.id_espacio,
                h.dia_semana,
                h.hora_inicio,
                h.hora_fin,
                h.activo,
                h.estado,
                e.nombre AS nombre_espacio
            FROM hub_horarios h
            LEFT JOIN hub_espacios e ON h.id_espacio = e.id_espacio
            WHERE h.estado = 1
        ";

        if (!empty($id_espacio)) {
            $id_espacio = intval($id_espacio);
            $sql .= " AND h.id_espacio = $id_espacio";
        }

        if (!empty($id_horario)) {
            $id_horario = intval($id_horario);
            $sql .= " AND h.id_horario = $id_horario";
        }

        $sql .= " ORDER BY h.dia_semana ASC, h.hora_inicio ASC";

        return $this->db->datos($sql);
    }
}