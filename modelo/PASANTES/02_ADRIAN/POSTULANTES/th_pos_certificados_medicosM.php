<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_pos_idiomasM extends BaseModel
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

// CREATE TABLE th_pos_certificados_medicos (
//     th_cer_id INT IDENTITY ( 1, 1 ) PRIMARY KEY,
//     th_pos_id INT,
//     th_cer_motivo_certificado VARCHAR ( 100 ),
//     th_cer_nom_medico VARCHAR ( 200 ),
//     th_cer_ins_medico VARCHAR ( 200 ),
//     th_cer_fecha_inicio_certificado DATE,
//     th_cer_fecha_fin_certificado DATE,
//     th_cer_ruta_certficado VARCHAR ( 400 ),
//     th_cer_fecha_creacion DATETIME2 DEFAULT GETDATE( ),
//     th_cer_fecha_modificacion DATETIME2,
//     th_cer_estado SMALLINT DEFAULT  1 
//   );