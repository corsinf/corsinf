<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_cat_plaza_etapasM extends BaseModel
{
    protected $tabla = 'cn_cat_plaza_etapas';

    protected $primaryKey = 'id_etapa AS _id';

    protected $camposPermitidos = [
        'codigo',
        'nombre',
        'tipo',
        'requiere_puntaje',
        'es_final',
        'estado',
        'fecha_creacion',
    ];

}
