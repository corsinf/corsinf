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
        // Tambien se  puede quitar el where
        $sql =
            "UPDATE ca
                SET ca.th_per_id = cd.th_per_id
            FROM _talentoh.th_control_acceso ca
            JOIN _talentoh.th_card_data cd ON ca.th_cardNo = cd.th_cardNo;
            ";

        // print_r($sql); exit(); die();

        // $datos = $this->db->datos($sql, false, true, true);
        $datos = $this->db->sql_string($sql, false, true);

        return $datos;
    }

    function listar_personalizado($fecha_ini = '', $fecha_final = '')
    {

        $limit = '';
        if ($fecha_ini == '') {
            $limit = "TOP 1000";
        }

        $sql =
            "SELECT $limit
                ca.th_acc_fecha_hora AS fecha,
                p.th_per_codigo_externo_1 AS nombre,
                d.th_dis_nombre         AS dispositivo_nombre
            FROM th_control_acceso AS ca
            LEFT JOIN th_personas AS p
                ON p.th_per_id = ca.th_per_id
            LEFT JOIN th_dispositivos AS d
                ON d.th_dis_host = ca.th_dis_id
            AND d.th_dis_port = TRY_CONVERT(int, NULLIF(ca.th_acc_puerto, '.'))

            ";

        if ($fecha_ini) {
            $sql .= "WHERE 
                    CONVERT(date, ca.th_acc_fecha_hora) BETWEEN '$fecha_ini' AND '$fecha_final'";
        }

        $sql .= "ORDER BY ca.th_acc_fecha_hora DESC;";

        // print_r($sql); exit(); die();


        $datos = $this->db->datos($sql, false, true);
        return $datos;
    }
}
