<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_espacios_tarifasM extends BaseModel
{
    protected $tabla = 'hub_espacios_tarifas';
    protected $primaryKey = 'id_espacio_tarifa AS _id';

    protected $camposPermitidos = [
        'id_espacio',
        'unidad_tiempo',
        'cantidad',
        'precio',
        'nombre_plan',
        'is_deleted',
        'id_usuario_crea',
        'fecha_creacion',
        'id_usuario_modifica',
        'fecha_modificacion'
    ];

    public function listar_espacio_tarifa($id = '', $id_espacio = '')
    {
        $sql = "
    SELECT 
        tar.id_espacio_tarifa AS _id,
        tar.id_espacio,
        tar.unidad_tiempo,
        tar.cantidad,
        tar.precio,
        tar.nombre_plan,
        tar.is_deleted,
        tar.fecha_creacion,
        esp.nombre AS nombre_espacio
    FROM hub_espacios_tarifas tar
    LEFT JOIN hub_espacios esp ON tar.id_espacio = esp.id_espacio
    WHERE tar.is_deleted = 0
    ";

        // Filtro por ID de la Tarifa específica
        if ($id != '') {
            $sql .= " AND tar.id_espacio_tarifa = " . intval($id);
        }

        // Filtro por ID del Espacio (para ver todas las tarifas de una oficina/sala)
        if ($id_espacio != '') {
            $sql .= " AND tar.id_espacio = " . intval($id_espacio);
        }

        $sql .= " ORDER BY tar.fecha_creacion DESC";

        return $this->db->datos($sql);
    }
}
