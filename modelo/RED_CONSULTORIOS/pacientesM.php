<?php

require_once 'BaseModel.php';

class pacientesM extends BaseModel
{
    protected $tabla = 'pacientes';
    protected $primaryKey = 'pac_id';

    protected $camposPermitidos = [
        'pac_primer_apellido',
        'pac_segundo_apellido',
        'pac_primer_nombre',
        'pac_segundo_nombre',
        'pac_cedula',
        'pac_sexo',
        'pac_tipo_sangre',
        'pac_fecha_nacimiento',
        'pac_telefono_1',
        'pac_telefono_2',
        'pac_correo',
        'pac_fecha_creacion',
        'pac_fecha_modificacion',
        'pac_estado',
        'PERFIL',
        'pac_direccion',
        'pac_foto_url',
        'PASS',
    ];
}

