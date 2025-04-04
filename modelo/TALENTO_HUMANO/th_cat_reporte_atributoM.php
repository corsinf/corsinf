<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_cat_reporte_atributoM extends BaseModel
{
    protected $tabla = 'th_cat_reporte_atributo';
    protected $primaryKey = 'th_crep_id AS _id';

    protected $camposPermitidos = [
        'th_crep_nombre_encabezado AS nombre_encabezado',
        'th_crep_nombre_atributo AS nombre_atributo',
        'th_crep_nombre_descripcion AS nombre_descripcion',
        'th_crep_modulo AS modulo',
    ];

    function listar_cat_reporte_campos_disponibles($id_reporte, $modulo)
    {
        $sql =
            "SELECT
                th_crep_id AS _id,
                th_crep_nombre_encabezado AS nombre_encabezado,
                th_crep_nombre_atributo AS nombre_atributo,
                th_crep_nombre_descripcion AS nombre_descripcion,
                th_crep_modulo AS modulo
            FROM th_cat_reporte_atributo t1
            WHERE NOT EXISTS (
                SELECT 1
                FROM th_reporte_campos t2
                WHERE t1.th_crep_id = t2.th_crep_id
                AND t2.th_rep_id = '$id_reporte'
            )
            AND th_crep_modulo = '$modulo';";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
