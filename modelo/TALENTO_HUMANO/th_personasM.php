<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_personasM extends BaseModel
{
    protected $tabla = 'th_personas';
    protected $primaryKey = 'th_per_id AS _id';

    protected $camposPermitidos = [
        'th_per_primer_apellido AS primer_apellido',
        'th_per_segundo_apellido AS segundo_apellido',
        'th_per_primer_nombre AS primer_nombre',
        'th_per_segundo_nombre AS segundo_nombre',
        'th_per_cedula AS cedula',
        'th_per_sexo AS sexo',
        'th_per_fecha_nacimiento AS fecha_nacimiento',
        'th_per_telefono_1 AS telefono_1',
        'th_per_telefono_2 AS telefono_2',
        'th_per_correo AS correo',
        'th_per_tabla AS tabla',
        'th_per_direccion AS direccion',
        'th_per_foto_url AS foto_url',
        'th_per_estado_civil AS estado_civil',
        'th_per_es_admin AS es_admin',
        'th_per_habiltado AS habiltado',
        //'th_prov_id',
        //'th_ciu_id',
        //'th_barr_id',
        'th_per_postal AS postal',
        'th_per_cargo AS cargo',
        'th_per_fecha_admision AS fecha_admision',
        'th_per_fecha_aut_limite AS fecha_aut_limite',
        'th_per_fecha_aut_inicio AS fecha_aut_inicio',
        'th_per_observaciones AS observaciones',
        'th_per_id_comunidad AS id_comunidad',
        'th_per_tabla_union AS tabla_union',
        //'PASS',
        //'PERFIL',
        'th_per_estado AS estado',
        'th_per_fecha_creacion AS fecha_creacion',
        'th_per_fecha_modificacion AS fecha_modificacion',
    ];
}
