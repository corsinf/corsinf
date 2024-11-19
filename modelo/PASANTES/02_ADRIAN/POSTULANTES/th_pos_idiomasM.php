<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_pos_idiomasM extends BaseModel
{
    protected $tabla = 'th_pos_idiomas';
    protected $primaryKey = 'th_idi_id AS _id';

    protected $camposPermitidos = [
        'th_pos_id',
        'th_idi_nombre_idioma',
        'th_idi_nivel',
        'th_idi_institucion',
        'th_idi_fecha_inicio_idioma',
        'th_idi_fecha_fin_idioma',
        'th_idi_fecha_creacion',
        'th_idi_fecha_modificacion',
        'th_idi_estado',
        
    ];

    // function select_all(){
    //     $sql = "SELECT * FROM th_pos_idiomas";

    //     $datos = $this->db->datos($sql);
    //     return $datos;

    // }
}

