<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_pos_habilidadesM extends BaseModel
{
    protected $tabla = 'th_pos_habilidades';
    protected $primaryKey = 'th_habp_id AS _id';

    protected $camposPermitidos = [
        'th_hab_id',
        'th_pos_id',
        'th_habp_estado',
        'th_habp_fecha_creacion',
        'th_habp_fecha_modificacion',
    ];

    function listarJoin()
    {
        // Construir la parte JOIN de la consulta
        $this->join('th_cat_habilidades', 'th_pos_habilidades.th_hab_id = th_cat_habilidades.th_hab_id');

        $datos = $this->where('th_habp_estado', '1')->listar();

        return $datos;
    }
    function listar_habilidades_postulante($id_postulante, $tipo_habilidad)
    {
        $sql = 
        "SELECT 
                cah.th_hab_id, cah.th_hab_nombre
            FROM 
                 th_cat_habilidades cah
            LEFT JOIN 
                th_pos_habilidades poh  ON poh.th_hab_id = cah.th_hab_id
                AND poh.th_habp_estado = 1             
                AND poh.th_pos_id = $id_postulante
            WHERE 
                poh.th_hab_id IS NULL 
                AND cah.th_tiph_id = $tipo_habilidad         
            ORDER BY 
                poh.th_hab_id;";

        $datos = $this->db->datos($sql);
        
        return $datos;
    }
}
