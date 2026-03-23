<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');


class VISITANTESM extends BaseModel
{
    protected $tabla = 'VISITANTES';
    protected $primaryKey = 'id_visitantes AS _id';

    protected $camposPermitidos = [
        'th_per_id',
        'PERFIL',
        'PASS',
        'POLITICAS_ACEPTACION',
        'DELETE_LOGIC',
    ];

    public function obtener_visitante_por_persona($th_per_id = null)
    {
        // Condición base: Solo visitantes que no han sido eliminados lógicamente
        $condicion = "v.DELETE_LOGIC = 0";

        if (!empty($th_per_id)) {
            $id = intval($th_per_id);
            $condicion .= " AND v.th_per_id = {$id}";
        }

        $sql = "SELECT 
                v.id_visitantes        AS id_visitante,
                v.th_per_id            AS th_per_id,
                v.PERFIL               AS perfil,
                v.NICK                 AS nick,
                v.PASS                 AS pass,
                v.POLITICAS_ACEPTACION AS politicas_aceptacion,
                v.DELETE_LOGIC         AS delete_logic
            FROM _no_concurrentes.VISITANTES v
            WHERE {$condicion};";

        return $this->db->datos($sql);
    }
}
