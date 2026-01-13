<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_per_nominaM extends BaseModel
{
    protected $tabla = 'th_per_nomina';
    protected $primaryKey = 'th_per_nom_id AS _id';

    protected $camposPermitidos = [
        'id_nomina',
        'th_per_id',
        'th_per_nom_remuneracion',
        'th_per_nom_fecha_ini',
        'th_per_nom_fecha_fin',
        'th_per_nom_estado',
        'th_per_nom_fecha_creacion',
        'th_per_nom_fecha_modificacion',
    ];

    public function listar_nomina_por_persona($id)
    {
        $id = intval($id);

        $sql = "
            SELECT 
                pn.th_per_nom_id AS _id,
                pn.id_nomina,
                pn.th_per_id,
                pn.th_per_nom_remuneracion,
                pn.th_per_nom_fecha_ini,
                pn.th_per_nom_fecha_fin,
                n.codigo AS nomina_codigo,
                n.nombre AS nomina_nombre,
                n.tipo   AS nomina_tipo
            FROM th_per_nomina pn
            LEFT JOIN th_cat_nomina n 
                ON pn.id_nomina = n.id_nomina
            WHERE pn.th_per_id = $id
              AND pn.th_per_nom_estado = 1
            ORDER BY pn.th_per_nom_fecha_ini DESC
        ";

        return $this->db->datos($sql);
    }

    
    public function listar_nomina_por_id($id)
    {
        $id = intval($id);

        $sql = "
            SELECT 
                pn.th_per_nom_id AS _id,
                pn.id_nomina,
                pn.th_per_id,
                pn.th_per_nom_remuneracion,
                pn.th_per_nom_fecha_ini,
                pn.th_per_nom_fecha_fin,
                n.codigo AS nomina_codigo,
                n.nombre AS nomina_nombre,
                n.tipo   AS nomina_tipo
            FROM th_per_nomina pn
            LEFT JOIN th_cat_nomina n 
                ON pn.id_nomina = n.id_nomina
            WHERE pn.th_per_nom_id = $id
              AND pn.th_per_nom_estado = 1
        ";

        return $this->db->datos($sql);
    }
}
