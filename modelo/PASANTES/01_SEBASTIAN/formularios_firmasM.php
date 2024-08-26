<?php
//require_once(dirname(__DIR__, 3) . '/db/db.php');
require_once(dirname(__DIR__, 1).'/BaseModel.php');


class formularios_firmasM extends BaseModel
{
    protected $tabla = 'fir_solicitudes';
    protected $primaryKey = 'fir_sol_id';

    protected $camposPermitidos = [
        'fir_sol_segundo_apellido',
        'fir_sol_primer_apellido',
        'fir_sol_primer_nombre',
        'fir_sol_segundo_nombre',
        'fir_sol_numero_identificacion',
        'fir_sol_tipo_formulario',
        'fir_sol_direccion_domicilio',
        'fir_sol_provincia',
        'fir_sol_ciudad',
        'fir_sol_correo',
        'fir_sol_numero_celular',
        'fir_sol_numero_fijo',
        'fir_sol_razon_social',
        'fir_sol_ruc_juridico',
        'fir_sol_direccion_ruc_juridico',
        'fir_sol_correo_empresarial',
        'fir_sol_estado',
        'fir_sol_fecha_creacion',
        'fir_sol_fecha_modficacion'
    ];
}
