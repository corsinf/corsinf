<?php

require_once(dirname(__DIR__,2).'/BaseModel.php');

class th_postulantesM extends BaseModel
{
    protected $tabla = 'th_postulantes';
    protected $primaryKey = 'th_pos_id AS _id';

    protected $camposPermitidos = [
        'th_pos_primer_nombre',
        'th_pos_segundo_nombre',
        'th_pos_primer_apellido',
        'th_pos_segundo_apellido',
        'th_pos_cedula',
        'th_pos_sexo',
        'th_pos_fecha_nacimiento',
        'th_pos_nacionalidad',
        'th_pos_estado_civil',
        'th_pos_telefono_1',
        'th_pos_telefono_2',
        'th_pos_correo',
        'th_pos_tabla',
        'th_pos_estado',
        'th_pos_fecha_creacion',
        'th_pos_fecha_modificacion',

    ];
}
