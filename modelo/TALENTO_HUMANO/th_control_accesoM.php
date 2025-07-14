<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_control_accesoM extends BaseModel
{
    protected $tabla = 'th_control_acceso';
    protected $primaryKey = 'th_acc_id AS _id';

    protected $camposPermitidos = [
        'th_per_id',
        'th_cardNo',
        'th_dis_id',
        'th_acc_tipo_registro',
        'th_acc_hora',
        'th_acc_fecha_hora',
        'th_acc_fecha_creacion',
        'th_acc_fecha_modificacion'
    ];

    function listarJoin()
    {
        // Construir la parte JOIN de la consulta
        $this->join('th_card_data', 'th_card_data.th_cardNo = th_control_acceso.th_cardNo');
        $this->join('th_personas', 'th_personas.th_per_id = th_control_acceso.th_per_id');
        $datos = $this->listar();
        return $datos;
    }
    function buscarAccesoPorPersonaYFecha($idPersona, $fecha)
    {
        $idPersona = intval($idPersona);
        $fecha = date('Y-m-d', strtotime($fecha));
        $sql = "SELECT 
            th_acc_id,
            th_acc_hora,
            th_acc_fecha_hora,
            th_per_id
            FROM th_control_acceso ca
            WHERE ca.th_per_id = '$idPersona'
            AND CONVERT(DATE, th_acc_fecha_hora) = '$fecha';";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function actualizar_per_id_no_card()
    {
        $sql =
            "UPDATE ca
                SET ca.th_per_id = cd.th_per_id
            FROM th_control_acceso ca
            JOIN th_card_data cd ON ca.th_cardNo = cd.th_cardNo
            WHERE ca.th_per_id IS NULL;
            ";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
