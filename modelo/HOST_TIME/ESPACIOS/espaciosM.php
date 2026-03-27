<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class espaciosM extends BaseModel
{
    protected $tabla = 'hub_espacios';
    protected $primaryKey = 'id_espacio AS _id';

    // Campos actualizados según tu SELECT TOP 1000
    protected $camposPermitidos = [
        'codigo',
        'nombre',
        'id_ubicacion',
        'id_numero_piso',
        'id_tipo_espacio',
        'capacidad',
        'tarifa_hora',
        'tarifa_dia',
        'imagen',
        'id_estado_espacio',
        'is_deleted',
        'id_usuario_crea',
        'fecha_creacion',
        'id_usuario_modifica',
        'fecha_modificacion'
    ];

    public function listar_espacios($id_espacio = '')
    {
        $sql = "
        SELECT
            esp.id_espacio AS _id, 
            esp.codigo, 
            esp.nombre, 
            esp.id_ubicacion, 
            esp.id_numero_piso,
            esp.id_tipo_espacio, 
            esp.capacidad,
            esp.tarifa_hora,
            esp.tarifa_dia,
            esp.imagen,
            esp.id_estado_espacio,
            esp.is_deleted,
            esp.fecha_creacion,
            ubi.nombre AS nombre_ubicacion,
            tipo.nombre AS nombre_tipo_espacio,
            piso.descripcion AS descripcion_numero_piso
        FROM hub_espacios esp
        LEFT JOIN hub_ubicaciones ubi ON esp.id_ubicacion = ubi.id_ubicacion
        LEFT JOIN hub_catn_tipo_espacios tipo ON esp.id_tipo_espacio = tipo.id_tipo_espacio
        LEFT JOIN hub_catn_numero_piso piso ON esp.id_numero_piso = piso.id_numero_piso
        WHERE esp.is_deleted = 0
        ";

        if ($id_espacio !== '') {
            $id = (int) $id_espacio;
            $sql .= " AND esp.id_espacio = {$id}";
        }

        return $this->db->datos($sql);
    }

    public function listar_pisos_por_ubicacion($id_ubicacion)
    {
        $id_ubi = intval($id_ubicacion);
        $sql = "
        SELECT DISTINCT 
            piso.id_numero_piso AS _id, 
            piso.descripcion AS nombre_piso
        FROM hub_espacios esp
        INNER JOIN hub_catn_numero_piso piso ON esp.id_numero_piso = piso.id_numero_piso
        WHERE esp.id_ubicacion = {$id_ubi}
          AND esp.is_deleted = 0
          AND esp.id_estado_espacio = 1
        ORDER BY piso.descripcion ASC
        ";
        return $this->db->datos($sql);
    }

    public function listar_tipos_por_ubicacion_piso($id_ubicacion, $id_piso)
    {
        $id_ubi = intval($id_ubicacion);
        $id_p = intval($id_piso);

        $sql = "
        SELECT DISTINCT 
            tipo.id_tipo_espacio, 
            tipo.nombre
        FROM hub_espacios esp
        INNER JOIN hub_catn_tipo_espacios tipo ON esp.id_tipo_espacio = tipo.id_tipo_espacio
        WHERE esp.id_ubicacion = {$id_ubi}
          AND esp.id_numero_piso = {$id_p}
          AND esp.is_deleted = 0
          AND esp.id_estado_espacio = 1
        ORDER BY tipo.nombre ASC
    ";
        return $this->db->datos($sql);
    }
}
