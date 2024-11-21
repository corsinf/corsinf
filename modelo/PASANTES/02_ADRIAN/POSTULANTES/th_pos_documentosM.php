<?php

require_once(dirname(__DIR__, 2) . '/BaseModel.php');

class th_pos_documentosM extends BaseModel
{
    protected $tabla = 'th_pos_documentos';
    protected $primaryKey = 'th_poi_id AS _id';

    protected $camposPermitidos = [
        'th_poi_id',
        'th_pos_id',
        'th_poi_tipo',
        'th_poi_ruta_archivo',
        'th_poi_fecha_creacion',
        'th_poi_fecha_modificacion',
        'th_poi_estado',
    ];


    // function select_all(){
    //     $sql = "SELECT * FROM th_pos_documentos";

    //     $datos = $this->db->datos($sql);
    //     return $datos;

    // }

    // CREATE TABLE th_pos_documentos (
    //     th_poi_id INT IDENTITY(1,1) PRIMARY KEY,
    //     th_pos_id INT,
    //     th_poi_tipo VARCHAR(100),
    //     th_poi_ruta_archivo VARCHAR(400),
    //     th_poi_fecha_creacion DATETIME2 DEFAULT GETDATE(),
    //     th_poi_fecha_modificacion DATETIME2,
    //     th_poi_estado SMALLINT DEFAULT 1
    // );
}

