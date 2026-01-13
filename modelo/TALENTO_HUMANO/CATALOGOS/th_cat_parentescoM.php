<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_parentescoM extends BaseModel
{
    protected $tabla = 'th_cat_parentesco';
    protected $primaryKey = 'id_parentesco AS _id';

    protected $camposPermitidos = [
        'id_parentesco AS id_parentesco',
        'descripcion AS descripcion',
        'estado AS estado',
        'cantidad AS cantidad',
        'fecha_creacion AS fecha_creacion',
        'requiere_fec_nac AS requiere_fec_nac'
    ];


    public function buscar_parientes($parametros)
    {
        $query = isset($parametros['query']) ? trim($parametros['query']) : '';
        $th_per_id = intval($parametros['th_per_id']);
        $sql = "
        SELECT
            p.id_parentesco AS id,
            p.descripcion AS text,
            p.cantidad,
            COUNT(pp.th_ppa_id) AS total_asignados
        FROM th_cat_parentesco p
        LEFT JOIN th_per_parientes pp
            ON pp.id_parentesco = p.id_parentesco
            AND pp.th_per_id = $th_per_id
            AND pp.th_ppa_estado = 1
        WHERE p.estado = 1
    ";
        if ($query !== '') {
            $sql .= " AND p.descripcion LIKE '%" . addslashes($query) . "%'";
        }

        $sql .= "
        GROUP BY
            p.id_parentesco,
            p.descripcion,
            p.cantidad
        HAVING
            (
                ISNUMERIC(p.cantidad) = 0
                OR
                COUNT(pp.th_ppa_id) < CAST(p.cantidad AS INT)
            )
        ORDER BY p.id_parentesco ASC
    ";

        return $this->db->datos($sql);
    }
}
