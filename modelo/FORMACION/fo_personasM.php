<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class fo_personasM extends BaseModel
{
    protected $tabla = 'fo_personas';
    protected $primaryKey = 'fo_per_id AS _id';

    protected $camposPermitidos = [
        'fo_per_primer_apellido AS primer_apellido',
        'fo_per_segundo_apellido AS segundo_apellido',
        'fo_per_primer_nombre AS primer_nombre',
        'fo_per_segundo_nombre AS segundo_nombre',
        'fo_per_cedula AS cedula',
        'fo_per_sexo AS sexo',
        'fo_per_fecha_nacimiento AS fecha_nacimiento',
        'fo_per_telefono_1 AS telefono_1',
        'fo_per_telefono_2 AS telefono_2',
        'fo_per_correo AS correo',
        'fo_per_tabla AS tabla',
        'fo_per_direccion AS direccion',
        'fo_per_foto_url AS foto_url',
        'fo_estado_civil AS estado_civil',
        //'fo_prov_id AS prov_id',
        //'fo_ciu_id AS ciu_id',
        //'fo_barr_id AS barr_id',
        'fo_per_postal AS postal',
        'fo_per_observaciones AS observaciones',
        //'fo_per_id_comunidad AS id_comunidad',
        //'fo_per_tabla_union AS tabla_union',
        //'PASS',
        //'PERFIL',
        'fo_per_estado AS estado',
        'fo_per_fecha_creacion AS fecha_creacion',
        'fo_per_fecha_modificacion AS fecha_modificacion',
        'fo_per_estado_actividades AS estado_actividades',
    ];
}
