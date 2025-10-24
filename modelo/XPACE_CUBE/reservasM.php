<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class reservasM extends BaseModel
{
    // Nombre real de la tabla en BD
    protected $tabla = 'hub_reservas';

    // Clave primaria
    protected $primaryKey = 'id_reserva AS _id';

    // Campos que puedes insertar/actualizar
    protected $camposPermitidos = [
        'id_usuario',
        'id_espacio',
        'inicio',
        'fin',
        'numero_personas',
        'id_estado_reserva',
        'notas',
        'creado_en',
        'actualizado_en',
        'estado',
        'nombre'
    ];


    public function listar_reservas($id_reserva = '')
    {
        $sql = "
    SELECT
        r.id_reserva AS _id,
        r.id_usuario,
        m.id_miembro,
        m.nombre_miembro,
        m.apellido_miembro,
        m.telefono_miembro,
        m.direccion_miembro,
        r.id_espacio,
        e.nombre AS nombre_espacio,
        e.codigo AS codigo_espacio,
        e.capacidad AS capacidad_espacio,
        r.inicio,
        r.fin,
        r.numero_personas,
        r.id_estado_reserva,
        r.notas,
        r.creado_en,
        r.actualizado_en,
        r.estado,
        r.nombre AS nombre_reserva
    FROM
        hub_reservas r
    LEFT JOIN co_miembro m ON r.id_usuario = m.id_miembro
    LEFT JOIN hub_espacios e ON r.id_espacio = e.id_espacio
    WHERE 1=1
    ";

        if ($id_reserva !== '') {
            $id = (int) $id_reserva;
            $sql .= " AND r.id_reserva = {$id}";
        }

        $sql .= " ORDER BY r.creado_en DESC";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
