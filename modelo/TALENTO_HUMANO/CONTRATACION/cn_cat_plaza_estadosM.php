<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_cat_plaza_estadosM extends BaseModel
{
    protected $tabla = 'cn_cat_plaza_estados';
    protected $primaryKey = 'id_plaza_estados AS _id';
    protected $camposPermitidos = [
        'codigo',
        'descripcion',
        'orden',
        'editable',
        'permite_postulacion',
        'permite_evaluacion',
        'visible_postulantes',
        'estado',
        'is_delete',
        'modificado_usuario',
    ];
}