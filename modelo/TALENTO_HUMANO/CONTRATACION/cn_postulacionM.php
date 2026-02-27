<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_postulacionM extends BaseModel
{
    protected $tabla = '_contratacion.cn_postulacion';
    protected $primaryKey = 'cn_post_id AS _id';
    protected $camposPermitidos = [
        'cn_pla_id',
        'th_pos_id',
        'cn_plaet_id_actual',
        'cn_post_estado_proceso',
        'cn_post_estado',
        'cn_post_fecha_creacion',
        'cn_post_fecha_modificacion',
    ];

    function ejecutar_crear_postulacion($cn_pla_id, $th_pos_id)
    {
        set_time_limit(0);

        $parametros = [
            intval($cn_pla_id),
            intval($th_pos_id)
        ];

        $sql = "EXEC _contratacion.SP_CN_CREAR_POSTULACION @cn_pla_id = ?, @th_pos_id = ?";
        return $this->db->ejecutar_procesos_almacenados($sql, $parametros);
    }

    public function ejecutar_crear_postulacion_bulk($cn_pla_id, $th_pos_ids)
    {
        set_time_limit(0);
        $resultados = ['exitosos' => 0, 'fallidos' => 0];

        foreach ($th_pos_ids as $th_pos_id) {
            $parametros = [intval($cn_pla_id), intval($th_pos_id)];
            $sql = "EXEC _contratacion.SP_CN_CREAR_POSTULACION @cn_pla_id = ?, @th_pos_id = ?";
            $res = $this->db->ejecutar_procesos_almacenados($sql, $parametros);
            if ($res == 1) $resultados['exitosos']++;
            else $resultados['fallidos']++;
        }

        return $resultados;
    }

    public function listar_postulantes_por_plaza($cn_pla_id)
    {
        $cn_pla_id = intval($cn_pla_id);
        $sql = "
            SELECT
                p.cn_post_id                                                        AS _id,
                pos.th_pos_id,
                CONCAT(pos.th_pos_primer_apellido, ' ', ISNULL(pos.th_pos_segundo_apellido,''),
                    ' ', pos.th_pos_primer_nombre, ' ', ISNULL(pos.th_pos_segundo_nombre,'')) AS nombre_completo,
                pos.th_pos_cedula,
                pos.th_pos_correo,
                pos.th_pos_telefono_1,
                CASE WHEN pos.th_pos_contratado = 1 THEN 'Interno' ELSE 'Externo' END AS tipo_postulante
            FROM cn_postulacion p
            INNER JOIN th_postulantes pos ON pos.th_pos_id = p.th_pos_id
            WHERE p.cn_pla_id   = $cn_pla_id
            AND p.cn_post_estado = 1
            ORDER BY pos.th_pos_primer_apellido ASC
            ";
        return $this->db->datos($sql);
    }

    public function listar_postulantes_por_etapa($cn_plaet_id)
    {
        $cn_plaet_id = intval($cn_plaet_id);
        $sql = "
        SELECT
            pe.cn_pose_id                   AS _id,
            pe.cn_post_id,
            pe.cn_plaet_id,
            pe.cn_pose_estado_proceso,
            pe.cn_pose_puntuacion,
            LTRIM(RTRIM(CONCAT(
                ISNULL(pos.th_pos_primer_apellido,''), ' ',
                ISNULL(pos.th_pos_segundo_apellido,''), ' ',
                ISNULL(pos.th_pos_primer_nombre,''), ' ',
                ISNULL(pos.th_pos_segundo_nombre,'')
            ))) AS nombre_completo,
            pos.th_pos_cedula
        FROM cn_postulacion_etapas pe
        INNER JOIN cn_postulacion p   ON p.cn_post_id  = pe.cn_post_id
        INNER JOIN th_postulantes     pos ON pos.th_pos_id = p.th_pos_id
        WHERE pe.cn_plaet_id    = $cn_plaet_id
          AND pe.cn_pose_estado = 1
          AND p.cn_post_estado  = 1
        ORDER BY pos.th_pos_primer_apellido ASC
    ";
        return $this->db->datos($sql);
    }
}
