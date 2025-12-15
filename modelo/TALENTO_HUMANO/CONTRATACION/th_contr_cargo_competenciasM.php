<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_cargo_competenciasM extends BaseModel
{
    protected $tabla = 'th_contr_cargo_competencias';

    protected $primaryKey = 'th_carcomp_id AS _id';

    protected $camposPermitidos = [
        'th_car_id',
        'th_comp_id',
        'th_carcomp_nivel_requerido',
        'th_carcomp_disc_valor_d',
        'th_carcomp_disc_valor_i',
        'th_carcomp_disc_valor_s',
        'th_carcomp_disc_valor_c',
        'th_carcomp_disc_grafica_json',
        'th_carcomp_nivel_utilizacion',
        'th_carcomp_nivel_contribucion',
        'th_carcomp_nivel_habilidad',
        'th_carcomp_nivel_maestria',
        'th_carcomp_es_critica',
        'th_carcomp_es_evaluable',
        'th_carcomp_metodo_evaluacion',
        'th_carcomp_ponderacion',
        'th_carcomp_observaciones',
        'th_carcomp_estado',
        'th_carcomp_fecha_creacion',
        'th_carcomp_fecha_modificacion'
    ];
}