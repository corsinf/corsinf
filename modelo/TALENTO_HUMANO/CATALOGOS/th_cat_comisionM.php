<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_comisionM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_cat_comision';

    // Clave primaria
    protected $primaryKey = 'id_comision AS _id';

    // Campos que puedes insertar o actualizar
    protected $camposPermitidos = [
        'codigo AS codigo',
        'nombre AS nombre',
        'descripcion AS descripcion',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion'
    ];

    public function listar_comisiones_contar_personas()
{
    $sql = "
        SELECT
            c.id_comision AS _id,
            c.codigo AS codigo,
            c.nombre AS nombre,
            COUNT(pc.th_per_id) AS total_personas
        FROM th_cat_comision c
        LEFT JOIN th_per_comision pc
            ON pc.id_comision = c.id_comision
            AND pc.th_per_com_estado = 1
        LEFT JOIN th_personas p
            ON p.th_per_id = pc.th_per_id
            AND p.th_per_estado = 1
        WHERE c.estado = 1
        GROUP BY
            c.id_comision,
            c.nombre,
            c.codigo
        ORDER BY c.nombre ASC
    ";

    return $this->db->datos($sql);
}

}
