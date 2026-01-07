<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_proteccion_datos_personaM extends BaseModel
{
    protected $tabla = 'th_proteccion_datos_persona';

    protected $primaryKey = 'th_prod_id AS _id';

    protected $camposPermitidos = [
        'th_per_id AS th_per_id',
        'th_prod_rol AS rol',
        'th_prod_estado AS estado',
        'th_prod_fecha_creacion AS fecha_creacion',
        'th_prod_fecha_modificacion AS fecha_modificacion',
    ];
    function listar_personas_proteccion_datos($th_prod_id = null)
    {
        $where = "";

        if (!empty($th_prod_id)) {
            $th_prod_id = intval($th_prod_id);
            $where = " AND pdp.th_prod_id = $th_prod_id ";
        }

        $sql = "
        SELECT 
            pdp.th_prod_id AS _id,
            per.th_per_id AS th_per_id,
            CONCAT(
                per.th_per_primer_apellido, ' ',
                per.th_per_segundo_apellido, ' ',
                per.th_per_primer_nombre, ' ',
                per.th_per_segundo_nombre
            ) AS nombre_completo,
            per.th_per_cedula AS cedula,
            pdp.th_prod_rol AS th_prod_rol,
            pdp.th_prod_estado AS th_prod_estado,
            pdp.th_prod_fecha_creacion AS th_prod_fecha_creacion,
            pdp.th_prod_fecha_modificacion AS th_prod_fecha_modificacion
        FROM th_proteccion_datos_persona pdp
        INNER JOIN th_personas per
            ON per.th_per_id = pdp.th_per_id
        WHERE per.th_per_estado = 1
        $where
        ORDER BY pdp.th_prod_fecha_creacion DESC
    ";

        return $this->db->datos($sql);
    }
}
