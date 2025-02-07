<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_pos_certificados_medicosM extends BaseModel
{
    protected $tabla = 'th_pos_certificados_medicos';
    protected $primaryKey = 'th_cer_id AS _id';

    protected $camposPermitidos = [
        'th_pos_id',
        'th_cer_motivo_certificado',
        'th_cer_nom_medico',
        'th_cer_ins_medico',
        'th_cer_fecha_inicio_certificado',
        'th_cer_fecha_fin_certificado',
        'th_cer_ruta_certficado',
        'th_cer_fecha_creacion',
        'th_cer_fecha_modificacion',
        'th_cer_estado',
       
    ];

    // function select_all(){
    //     $sql = "SELECT * FROM th_pos_idiomas";

    //     $datos = $this->db->datos($sql);
    //     return $datos;

    // }
}

