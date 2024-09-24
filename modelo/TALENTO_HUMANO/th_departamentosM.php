<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_departamentosM extends BaseModel
{
    protected $tabla = 'th_departamentos';
    protected $primaryKey = 'th_dep_id AS _id';

    protected $camposPermitidos = [
        'th_dep_nombre AS nombre',
        'th_dep_desactivar_ADE AS desactivar_ADE',
        'th_dep_contingencia AS contingencia',
        'th_dep_tiempo_maximo_dentro AS tiempo_maximo_dentro',
        'th_dept_id AS tipo_id',
        'th_dep_estado AS estado',
        'th_dep_fecha_creacion AS fecha_creacion',
        'th_dep_fecha_modificacion AS fecha_modificacion',
    ];
}
