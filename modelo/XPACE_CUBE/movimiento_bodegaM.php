<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class movimiento_bodegaM extends BaseModel
{
    // Nombre real de la tabla en BD
    protected $tabla = 'hub_bodega_movimientos';

    // Clave primaria
    protected $primaryKey = 'id_movimiento AS _id';

    // Campos que puedes insertar/actualizar
    protected $camposPermitidos = [
        'id_articulo',
        'id_reserva',
        'tipo_movimiento',
        'cantidad',
        'motivo',
        'fecha_movimiento',
        'estado',
        'creado_en',
        'actualizado_en',
    ];

    public function listar_movimientos($id_movimiento = '')
    {
        $sql = "
    SELECT
        bm.id_movimiento AS _id,
        bm.id_articulo,
        a.codigo AS codigo_articulo,
        a.nombre AS nombre_articulo,
        a.descripcion AS descripcion_articulo,
        a.categoria AS categoria_articulo,
        a.cantidad_total,
        a.cantidad_disponible,
        a.precio_unitario,
        a.fecha_ingreso AS fecha_ingreso_articulo,
        bm.id_reserva,
        r.inicio AS inicio_reserva,
        r.fin AS fin_reserva,
        r.hora_inicio,
        r.hora_fin,
        r.duracion_horas,
        r.notas,
        r.nombre AS nombre_reserva,
        bm.tipo_movimiento,
        bm.cantidad,
        bm.motivo,
        bm.fecha_movimiento,
        bm.estado
    FROM
        hub_bodega_movimientos bm
    INNER JOIN hub_bodega a ON bm.id_articulo = a.id_articulo
    LEFT JOIN hub_reservas r ON bm.id_reserva = r.id_reserva
    WHERE 1=1
    ";

        // Si se envía un id_movimiento específico, filtramos
        if ($id_movimiento !== '') {
            $id = (int) $id_movimiento;
            $sql .= " AND bm.id_movimiento = {$id}";
        }

        // Ordenamos por fecha más reciente
        $sql .= " ORDER BY bm.fecha_movimiento DESC";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
