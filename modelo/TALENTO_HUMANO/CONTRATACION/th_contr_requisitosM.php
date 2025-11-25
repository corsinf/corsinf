<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_requisitosM extends BaseModel
{
    // Nombre real de la tabla en BD
    protected $tabla = 'th_contr_requisitos';

    // Clave primaria (se expone como _id)
    protected $primaryKey = 'th_req_id AS _id';

    // Campos que puedes insertar/actualizar
    protected $camposPermitidos = [
        'th_req_tipo as tipo',
        'th_req_descripcion as descripcion',
        'th_req_obligatorio as obligatorio',
        'th_req_ponderacion as ponderacion',
        'th_req_estado',
        'th_req_fecha_creacion',
        'th_req_fecha_modificacion'
    ];
}