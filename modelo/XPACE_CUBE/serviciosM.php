<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class serviciosM extends BaseModel
{
    // Nombre real de la tabla en BD
    protected $tabla = 'hub_servicios';

    // Clave primaria
    protected $primaryKey = 'id_servicio AS _id';

    // Campos que puedes insertar/actualizar
    protected $camposPermitidos = [
        'nombre',
        'descripcion',
        'precio_unitario',
        'por_hora',
        'estado',
    ];


    public function listar_servicios_no_asignados($id_espacio = '')
    {
        $id = ($id_espacio !== '' && $id_espacio !== null) ? (int)$id_espacio : '';

        if ($id === '') {
            $sql = "
        SELECT s.*
        FROM hub_servicios s
        WHERE s.estado = 1
          AND NOT EXISTS (
            SELECT 1
            FROM hub_reservas_servicios rs
            JOIN hub_reservas r ON rs.id_reserva = r.id_reserva
            WHERE rs.id_servicio = s.id_servicio
              AND r.estado = 1
              AND rs.estado = 1
          )
        ";
        } else {
            $sql = "
        SELECT s.*
        FROM hub_servicios s
        WHERE s.estado = 1
          AND NOT EXISTS (
            SELECT 1
            FROM hub_reservas_servicios rs
            JOIN hub_reservas r ON rs.id_reserva = r.id_reserva
            WHERE rs.id_servicio = s.id_servicio
              AND r.estado = 1
              AND rs.estado = 1
              AND r.id_espacio = {$id}
          )
        ";
        }

        return $this->db->datos($sql);
    }
}
