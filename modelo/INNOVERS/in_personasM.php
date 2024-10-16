<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class in_personasM extends BaseModel
{
    protected $tabla = 'in_personas';
    protected $primaryKey = 'in_per_id AS _id';

    protected $camposPermitidos = [
        'in_per_primer_apellido AS primer_apellido',
        'in_per_segundo_apellido AS segundo_apellido',
        'in_per_primer_nombre AS primer_nombre',
        'in_per_segundo_nombre AS segundo_nombre',
        'in_per_cedula AS cedula',
        'in_per_sexo AS sexo',
        'in_per_fecha_nacimiento AS fecha_nacimiento',
        'in_per_telefono_1 AS telefono_1',
        'in_per_telefono_2 AS telefono_2',
        'in_per_correo AS correo',
        'in_per_tabla AS tabla',
        'in_per_direccion AS direccion',
        'in_per_foto_url AS foto_url',
        'in_estado_civil AS estado_civil',
        //'in_prov_id AS prov_id',
        //'in_ciu_id AS ciu_id',
        //'in_barr_id AS barr_id',
        'in_per_postal AS postal',
        'in_per_observaciones AS observaciones',
        //'in_per_id_comunidad AS id_comunidad',
        //'in_per_tabla_union AS tabla_union',
        //'PASS',
        //'PERFIL',
        //'in_per_estado AS estado',
        'in_per_fecha_creacion AS fecha_creacion',
        'in_per_fecha_modificacion AS fecha_modificacion',
    ];
}
