<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_per_parientesM extends BaseModel
{
    protected $tabla = 'th_per_parientes';

    // Primary Key
    protected $primaryKey = 'th_ppa_id AS _id';

    // Campos permitidos (con alias)
    protected $camposPermitidos = [
        'th_per_id AS th_per_id',
        'id_parentesco AS id_parentesco',
        'th_ppa_nombres AS nombres',
        'th_ppa_apellidos AS apellidos',
        'th_ppa_estado AS estado',
        'th_ppa_fecha_creacion AS fecha_creacion',
        'th_ppa_fecha_modificacion AS fecha_modificacion'
    ];

    /**
     * Listar parientes de una persona
     */
    public function listar_parientes_por_persona($id)
    {
        $id = intval($id);

        $sql = "
            SELECT 
                pp.th_ppa_id AS _id,
                pp.th_per_id,
                pp.id_parentesco,
                pp.th_ppa_nombres AS nombres,
                pp.th_ppa_apellidos AS apellidos,
                p.descripcion AS parentesco_nombre,
                pp.th_ppa_fecha_creacion AS fecha_creacion
            FROM th_per_parientes pp
            LEFT JOIN th_cat_parentesco p 
                ON pp.id_parentesco = p.id_parentesco
            WHERE pp.th_per_id = $id
              AND pp.th_ppa_estado = 1
            ORDER BY p.descripcion ASC, pp.th_ppa_nombres ASC
        ";

        return $this->db->datos($sql);
    }

    public function listar_pariente_por_id($id)
    {
        $id = intval($id);

        $sql = "
            SELECT 
                pp.th_ppa_id AS _id,
                pp.th_per_id,
                pp.id_parentesco,
                pp.th_ppa_nombres AS nombres,
                pp.th_ppa_apellidos AS apellidos,
                p.descripcion AS parentesco_nombre
            FROM th_per_parientes pp
            LEFT JOIN th_cat_parentesco p 
                ON pp.id_parentesco = p.id_parentesco
            WHERE pp.th_ppa_id = $id
              AND pp.th_ppa_estado = 1
        ";

        return $this->db->datos($sql);
    }

   
    public function obtener_parentesco_por_id($id_parentesco)
    {
        $id_parentesco = intval($id_parentesco);

        $sql = "
            SELECT 
                id_parentesco,
                descripcion AS parentesco_nombre
            FROM th_cat_parentesco
            WHERE id_parentesco = $id_parentesco
              AND estado = 1
        ";

        return $this->db->datos($sql);
    }
}