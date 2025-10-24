<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class espaciosM extends BaseModel
{
    // Nombre real de la tabla en BD
    protected $tabla = 'hub_espacios';

    // Clave primaria
    protected $primaryKey = 'id_espacio AS _id';

    // Campos que puedes insertar/actualizar
    protected $camposPermitidos = [
        'id_ubicacion',
        'id_tipo_espacio',
        'codigo',
        'nombre',
        'capacidad',
        'tarifa_hora',
        'tarifa_dia',
        'activo',
        'creado_en',
        'estado',
    ];

    public function listar_espacios($id_espacio = '')
    {
        $sql = "
    SELECT
        esp.id_espacio AS _id,
        esp.id_ubicacion,
        esp.id_tipo_espacio,
        esp.codigo,
        esp.nombre,
        esp.capacidad,
        esp.tarifa_hora,
        esp.tarifa_dia,
        esp.activo,
        esp.creado_en,
        ubi.nombre AS nombre_ubicacion,
        ubi.id_ubicacion,
        tipo.id_tipo_espacio,
        tipo.nombre AS nombre_tipo_espacio
    FROM
        hub_espacios esp
    LEFT JOIN hub_ubicaciones ubi ON esp.id_ubicacion = ubi.id_ubicacion
    LEFT JOIN hub_tipos_espacios tipo ON esp.id_tipo_espacio = tipo.id_tipo_espacio
    WHERE 1=1
    ";

        // Filtro dinámico (cast a entero para seguridad)
        if ($id_espacio !== '') {
            $id = (int) $id_espacio;
            $sql .= " AND esp.id_espacio = {$id}";
        }

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
