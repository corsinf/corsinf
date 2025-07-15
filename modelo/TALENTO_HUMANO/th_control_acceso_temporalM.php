<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_control_acceso_temporalM extends BaseModel
{
    protected $tabla = 'th_control_acceso_temporal';
    protected $primaryKey = 'th_act_id AS _id';

    protected $camposPermitidos = [
        'th_per_id AS per_id',
        'th_act_cardNo AS cardNo',
        'th_act_tipo_registro AS tipo_registro',
        'th_act_hora AS hora',
        'th_act_fecha_hora AS fecha_hora',
        'th_act_fecha_creacion AS fecha_creacion',
        'th_act_fecha_modificacion AS fecha_modificacion',
        'th_act_puerto AS puerto',
        'th_act_tipo_origen AS tipo_origen',
        'th_act_server_name AS server_name',
        'th_act_server_software AS server_software',
        'th_act_server_protocol AS server_protocol',
        'th_act_server_port AS server_port',
        'th_act_http_host AS http_host',
        'th_act_remote_addr AS remote_addr',
        'th_act_http_user_agent AS http_user_agent',
        'th_act_request_method AS request_method',
        'th_act_request_uri AS request_uri',
        'th_act_host_cliente AS host_cliente',
        'th_act_http_x_forwarded_for AS http_x_forwarded_for',
        'th_act_latitud AS latitud',
        'th_act_longitud AS longitud',
        'th_act_url_foto AS url_foto',
        'th_act_aprobado_por AS aprobado_por',
        'th_act_fecha_aprobacion AS fecha_aprobacion',
        'th_act_estado_aprobacion AS estado_aprobacion',
        'th_act_observacion_aprobacion AS observacion_aprobacion',
    ];

    function listar_accesos_temporales($id_persona = '')
    {
        $sql = "SELECT
                acc.th_act_id AS _id,
                acc.th_per_id AS per_id,
                acc.th_act_cardNo AS cardNo,
                acc.th_act_tipo_registro AS tipo_registro,
                acc.th_act_hora AS hora,
                acc.th_act_fecha_hora AS fecha_hora,
                acc.th_act_fecha_creacion AS fecha_creacion,
                acc.th_act_fecha_modificacion AS fecha_modificacion,
                acc.th_act_puerto AS puerto,
                acc.th_act_tipo_origen AS tipo_origen,
                acc.th_act_server_name AS server_name,
                acc.th_act_server_software AS server_software,
                acc.th_act_server_protocol AS server_protocol,
                acc.th_act_server_port AS server_port,
                acc.th_act_http_host AS http_host,
                acc.th_act_remote_addr AS remote_addr,
                acc.th_act_http_user_agent AS http_user_agent,
                acc.th_act_request_method AS request_method,
                acc.th_act_request_uri AS request_uri,
                acc.th_act_host_cliente AS host_cliente,
                acc.th_act_http_x_forwarded_for AS http_x_forwarded_for,
                acc.th_act_latitud AS latitud,
                acc.th_act_longitud AS longitud,
                acc.th_act_url_foto AS url_foto,
                acc.th_act_aprobado_por AS aprobado_por,
                acc.th_act_fecha_aprobacion AS fecha_aprobacion,
                acc.th_act_estado_aprobacion AS estado_aprobacion,
                acc.th_act_observacion_aprobacion AS observacion_aprobacion,
                CONCAT(per.th_per_primer_apellido, ' ', per.th_per_segundo_apellido, ' ',
                       per.th_per_primer_nombre, ' ', per.th_per_segundo_nombre) AS nombre_persona
            FROM th_control_acceso_temporal acc
            INNER JOIN th_personas per ON acc.th_per_id = per.th_per_id
            WHERE 1 = 1";

        if ($id_persona != '') {
            $sql .= " AND acc.th_per_id = $id_persona";
        }

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
