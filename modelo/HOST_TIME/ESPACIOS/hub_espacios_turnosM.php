<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_espacios_turnosM extends BaseModel
{
    protected $tabla      = 'hub_espacios_turnos';
    protected $primaryKey = 'hub_tuh_id AS _id';

    protected $camposPermitidos = [
        'id_espacios',
        'hub_tur_id',
        'hub_tuh_dia',
        'is_deleted',
        'id_usuario_crea',
        'fecha_creacion',
        'id_usuario_modifica',
        'fecha_modificacion',
    ];

    /* Listar TODAS las asignaciones de un espacio con datos del turno */
    public function listar_por_espacio($id_espacio)
    {
        $id = intval($id_espacio);
        $sql = "
            SELECT
                et.hub_tuh_id          AS _id,
                et.id_espacios,
                et.hub_tur_id,
                et.hub_tuh_dia,
                t.hub_tur_nombre       AS nombre,
                t.hub_tur_hora_entrada AS hora_entrada,
                t.hub_tur_hora_salida  AS hora_salida,
                t.hub_tur_color        AS color
            FROM hub_espacios_turnos et
            INNER JOIN hub_turnos t ON et.hub_tur_id = t.hub_tur_id
            WHERE et.is_deleted = 0
              AND et.id_espacios = {$id}
            ORDER BY et.hub_tuh_dia ASC, t.hub_tur_hora_entrada ASC
        ";
        return $this->db->datos($sql);
    }

    /**
     * Listar el turno de un espacio para un día específico.
     * hub_tuh_dia sigue el convenio JS: 0=Dom, 1=Lu … 6=Sáb.
     *
     * @param  int $id_espacio
     * @param  int $dia        Número de día JS (0-6)
     * @return array           Array con un registro o vacío
     */
    public function listar_por_espacio_y_dia($id_espacio, $dia)
    {
        $id  = intval($id_espacio);
        $dia = intval($dia);

        $sql = "
            SELECT
                et.hub_tuh_id          AS _id,
                et.id_espacios,
                et.hub_tur_id,
                et.hub_tuh_dia,
                t.hub_tur_nombre       AS nombre,
                t.hub_tur_hora_entrada AS hora_entrada,
                t.hub_tur_hora_salida  AS hora_salida,
                t.hub_tur_color        AS color
            FROM hub_espacios_turnos et
            INNER JOIN hub_turnos t ON et.hub_tur_id = t.hub_tur_id
            WHERE et.is_deleted  = 0
              AND et.id_espacios = {$id}
              AND et.hub_tuh_dia = {$dia}
            ORDER BY t.hub_tur_hora_entrada ASC
            LIMIT 1
        ";
        return $this->db->datos($sql);
    }

    /* Verificar si ya existe la misma asignación (espacio + turno + día) */
    public function verificar_duplicado($id_espacio, $hub_tur_id, $dia, $excluir_id = '')
    {
        $id_espacio = intval($id_espacio);
        $hub_tur_id = intval($hub_tur_id);
        $dia        = intval($dia);

        $sql = "
            SELECT COUNT(*) AS c
            FROM hub_espacios_turnos
            WHERE is_deleted = 0
              AND id_espacios = {$id_espacio}
              AND hub_tur_id  = {$hub_tur_id}
              AND hub_tuh_dia = {$dia}
        ";

        if ($excluir_id !== '') {
            $sql .= " AND hub_tuh_id != " . intval($excluir_id);
        }

        return $this->db->datos($sql);
    }

    public function verificar_solapamiento($id_espacio, $hub_tur_id, $dia)
    {
        $id_espacio = intval($id_espacio);
        $hub_tur_id = intval($hub_tur_id);
        $dia        = intval($dia);

        $sql = "
            SELECT COUNT(*) AS c
            FROM hub_espacios_turnos et
            INNER JOIN hub_turnos t_nuevo
                ON t_nuevo.hub_tur_id = {$hub_tur_id}
            INNER JOIN hub_turnos t_exist
                ON et.hub_tur_id = t_exist.hub_tur_id
            WHERE et.is_deleted = 0
              AND et.id_espacios = {$id_espacio}
              AND et.hub_tuh_dia = {$dia}
              AND t_nuevo.hub_tur_hora_entrada < t_exist.hub_tur_hora_salida
              AND t_nuevo.hub_tur_hora_salida  > t_exist.hub_tur_hora_entrada
        ";

        return $this->db->datos($sql);
    }
}
