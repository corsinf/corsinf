<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cat_paisM extends BaseModel
{
    protected $tabla = 'th_cat_pais';

    protected $primaryKey = 'id_pais AS _id';

    protected $camposPermitidos = [
        'codigo AS codigo',
        'nombre AS nombre',
        'nacionalidad AS nacionalidad',
        'se_lista_nac AS se_lista_nac',
        'se_lista_pais AS se_lista_pais',
        'estado AS estado',
        'fecha_creacion AS fecha_creacion',
        'fecha_modificacion AS fecha_modificacion'
    ];
}