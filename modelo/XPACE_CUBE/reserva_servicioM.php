<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class reserva_servicioM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'hub_reservas_servicios';

    // Clave primaria
    protected $primaryKey = 'id_reserva_servicio AS _id';

    // Campos que se pueden insertar o actualizar
    protected $camposPermitidos = [
        'id_reserva',
        'id_servicio',
        'cantidad',
        'precio_unitario',
        'por_hora',
        'estado'
    ];

    /**
     * Listar los servicios asignados a una reserva
     */
    public function listar_servicios_por_reserva($id_reserva)
    {
        $id = (int) $id_reserva;

        $sql = "
            SELECT 
                s.id_servicio,
                s.nombre,
                s.descripcion,
                s.precio_unitario,
                s.por_hora,
                rs.cantidad,
                rs.precio_unitario AS precio_reserva,
                rs.estado
            FROM hub_reservas_servicios rs
            INNER JOIN hub_servicios s ON s.id_servicio = rs.id_servicio
            WHERE rs.id_reserva = $id
              AND rs.estado = 1
              AND s.estado = 1
        ";

        return $this->db->datos($sql);
    }
}
