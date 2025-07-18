<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_control_aprobacionM extends BaseModel
{
    protected $tabla = 'th_control_aprobacion';
    protected $primaryKey = 'th_ctp_id AS _id';

    protected $camposPermitidos = [
        'usu_id AS id_usuario',
        'th_ctp_estado AS estado',
        'th_ctp_fecha_creacion AS fecha_creacion',
        'th_ctp_fecha_modificacion AS fecha_modificacion',
    ];

    function listar_usuarios()
    {
        $sql =
            "SELECT 
                th.th_ctp_id AS _id,
                th.usu_id AS id_usuario,
                th.th_ctp_estado AS estado,
                th.th_ctp_fecha_creacion AS fecha_creacion,
                th.th_ctp_fecha_modificacion AS fecha_modificacion,
                u.nombres,
                u.apellidos
            FROM 
                th_control_aprobacion th
            INNER JOIN 
                USUARIOS u ON th.usu_id = u.id_usuarios;
            ";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
