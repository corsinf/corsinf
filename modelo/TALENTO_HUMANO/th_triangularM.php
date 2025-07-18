<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_triangularM extends BaseModel
{
    protected $tabla = 'th_triangular';
    protected $primaryKey = 'th_tri_id AS _id';

    protected $camposPermitidos = [
        'th_tri_nombre AS nombre',
        'th_tri_descripcion AS descripcion',
        'th_tri_estado AS estado',
        'usu_id AS usu_id',
        'th_tri_fecha_creacion AS fecha_creacion',

    ];

    public function lista_triangular($id = false, $query = false)
    {
        $sql = "SELECT
                th_tri_id AS _id,
                th_tri_nombre AS nombre,
                usu_id,
                th_tri_fecha_creacion AS fecha_creacion
            FROM
                th_triangular
            WHERE
                1 = 1"; // siempre verdadero, para concatenar fácilmente

        if ($query) {
            $sql .= " AND th_tri_nombre = '" . $query . "'";
        }

        if ($id) {
            $sql .= " AND th_tri_id = " . intval($id);
        }

        $sql .= " ORDER BY th_tri_id";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
