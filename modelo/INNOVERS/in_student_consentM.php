<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class student_consentM extends BaseModel
{
    protected $tabla = 'in_student_consent';
    protected $primaryKey = 'in_stc_id _id';

    protected $camposPermitidos = [
        'in_per_id AS id_persona',
        'in_stc_nombre_estudiante AS nombre_estudiante',
        'in_stc_fecha_nacimiento AS fecha_nacimiento',
        'in_stc_proposito_autorizacion AS proposito_autorizacion',
        'in_stc_primer_nombre_autorizado AS primer_nombre_autorizado',
        'in_stc_primer_relacion_autorizada AS primer_relacion_autorizada',
        'in_stc_primera_direccion_autorizada AS primera_direccion_autorizada',
        'in_stc_primer_email_autorizado AS primer_email_autorizado',
        'in_stc_segundo_nombre_autorizado AS segundo_nombre_autorizado',
        'in_stc_segunda_relacion_autorizada AS segunda_relacion_autorizada',
        'in_stc_segunda_direccion_autorizada AS segunda_direccion_autorizada',
        'in_stc_segundo_email_autorizado AS segundo_email_autorizado',
        'in_stc_firma_estudiante AS firma_estudiante',
        'in_stc_fecha_firma AS fecha_firma',
        'in_stc_nombre_registro AS nombre_registro',
        'in_stc_fecha_registro AS fecha_registro',
        'in_stc_cbx_academic_all AS cbx_academic_all',
        'in_stc_cbx_academic_1 AS cbx_academic_1',
        'in_stc_cbx_academic_2 AS cbx_academic_2',
        'in_stc_cbx_academic_3 AS cbx_academic_3',
        'in_stc_cbx_academic_4 AS cbx_academic_4',
        'in_stc_cbx_academic_5 AS cbx_academic_5',
        'in_stc_cbx_academic_6 AS cbx_academic_6',
        'in_stc_cbx_financial_all AS cbx_financial_all',
        'in_stc_cbx_financial_1 AS cbx_financial_1',
        'in_stc_cbx_financial_2 AS cbx_financial_2',
        'in_stc_cbx_financial_3 AS cbx_financial_3',
        'in_stc_cbx_aid_financial AS cbx_aid_financial',
        'in_stc_cbx_housing_all AS cbx_housing_all',
        'in_stc_cbx_housing_1 AS cbx_housing_1',
        'in_stc_cbx_housing_2 AS cbx_housing_2',
        'in_stc_cbx_housing_3 AS cbx_housing_3',
        'in_stc_cbx_remove_consent AS cbx_remove_consent',
        'in_stc_fecha_creacion AS fecha_creacion',
        'in_stc_fecha_modificacion AS fecha_modificacion',
        'in_stc_estado AS estado',
    ];
}
