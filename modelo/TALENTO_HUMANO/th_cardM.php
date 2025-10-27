<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_cardM  extends BaseModel
{

    protected $tabla = 'th_card_data';
    protected $primaryKey = 'th_card_id AS _id';

    protected $camposPermitidos = [
        'th_card_id',
        'th_per_id',
        'th_card_nombre',
        'th_cardNo',
        'th_card_creacion',
    ];

    function sincronizar_datos_card_persona()
    {
        $sql =
            "UPDATE c
                SET c.th_per_id = p.th_per_id
                FROM th_card_data AS c
                JOIN th_personas AS p
                ON LTRIM(RTRIM(p.th_per_observaciones)) = LTRIM(RTRIM(c.th_card_nombre))
                WHERE c.th_per_id IS NULL;";


        $datos = $this->db->sql_string($sql);
        return $datos;
    }
}
