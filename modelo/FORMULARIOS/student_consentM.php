<?php

require_once 'BaseModel.php';

class student_consentM extends BaseModel
{
    protected $tabla = 'student_consent';
    protected $primaryKey = 'edu_id';

    protected $camposPermitidos = [
        'edu_nombre_estudiante',
        'edu_id_estudiante',
        'edu_fecha_nacimiento',
        'edu_proposito_autorizacion',
        'edu_primer_nombre_autorizado',
        'edu_primer_relacion_autorizada',
        'edu_primera_direccion_autorizada',
        'edu_primer_email_autorizado',
        'edu_segundo_nombre_autorizado',
        'edu_segunda_relacion_autorizada',
        'edu_segunda_direccion_autorizada',
        'edu_segundo_email_autorizado',
        'edu_firma_estudiante',
        'edu_fecha_firma',
        'edu_nombre_registro',
        'edu_fecha_registro',
        'edu_cbx_academic_all',
        'edu_cbx_academic_1',
        'edu_cbx_academic_2',
        'edu_cbx_academic_3',
        'edu_cbx_academic_4',
        'edu_cbx_academic_5',
        'edu_cbx_academic_6',
        'edu_cbx_financial_all',
        'edu_cbx_financial_1',
        'edu_cbx_financial_2',
        'edu_cbx_financial_3',
        'edu_cbx_aid_financial',
        'edu_cbx_housing_all',
        'edu_cbx_housing_1',
        'edu_cbx_housing_2',
        'edu_cbx_housing_3',
        'edu_cbx_remove_consent',
        'edu_fecha_creacion',
        'edu_fecha_modificacion',
        'edu_estado',
    ];
}
