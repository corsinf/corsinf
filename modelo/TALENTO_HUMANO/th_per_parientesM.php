<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_per_parientesM extends BaseModel
{
    protected $tabla = 'th_per_parientes';

    protected $primaryKey = 'th_ppa_id AS _id';

    protected $camposPermitidos = [
        'th_per_id AS th_per_id',
        'id_parentesco AS id_parentesco',
        'th_ppa_nombres AS nombres',
        'th_ppa_apellidos AS apellidos',
        'th_ppa_numero_telefono AS numero_telefono',
        'th_ppa_contacto_emergencia AS contacto_emergencia',
        'th_ppa_fecha_nacimiento AS fecha_nacimiento',
        'th_ppa_estado AS estado',
        'th_ppa_fecha_creacion AS fecha_creacion',
        'th_ppa_fecha_modificacion AS fecha_modificacion'
    ];


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
            pp.th_ppa_numero_telefono AS numero_telefono,
            pp.th_ppa_fecha_nacimiento AS fecha_nacimiento,
            pp.th_ppa_contacto_emergencia AS contacto_emergencia,
            p.descripcion AS parentesco_nombre,
            p.cantidad AS parentesco_cantidad,
            p.requiere_fec_nac,
            pp.th_ppa_fecha_creacion AS fecha_creacion
        FROM th_per_parientes pp
        LEFT JOIN th_cat_parentesco p 
            ON pp.id_parentesco = p.id_parentesco
        WHERE pp.th_per_id = $id
          AND pp.th_ppa_estado = 1
        ORDER BY 
            pp.th_ppa_contacto_emergencia DESC, 
            pp.id_parentesco ASC,              
            pp.th_ppa_nombres ASC               
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
                pp.th_ppa_numero_telefono AS numero_telefono,
                pp.th_ppa_fecha_nacimiento AS fecha_nacimiento,
                pp.th_ppa_contacto_emergencia AS contacto_emergencia,
                p.descripcion AS parentesco_nombre,
                p.cantidad AS parentesco_cantidad,
                p.requiere_fec_nac
            FROM th_per_parientes pp
            LEFT JOIN th_cat_parentesco p 
                ON pp.id_parentesco = p.id_parentesco
            WHERE pp.th_ppa_id = $id
              AND pp.th_ppa_estado = 1
        ";

        return $this->db->datos($sql);
    }

    /**
     * Obtener información del parentesco
     */
    public function obtener_info_parentesco($id_parentesco)
    {
        $id_parentesco = intval($id_parentesco);

        $sql = "
            SELECT 
                id_parentesco,
                descripcion,
                cantidad,
                requiere_fec_nac
            FROM th_cat_parentesco
            WHERE id_parentesco = $id_parentesco
              AND estado = 1
        ";

        return $this->db->datos($sql);
    }

    public function contar_parientes_por_tipo($per_id, $id_parentesco)
    {
        $per_id = intval($per_id);
        $id_parentesco = intval($id_parentesco);

        $sql = "
            SELECT COUNT(*) as total 
            FROM th_per_parientes 
            WHERE th_per_id = $per_id 
              AND id_parentesco = $id_parentesco 
              AND th_ppa_estado = 1
        ";

        $resultado = $this->db->datos($sql);
    }
}
