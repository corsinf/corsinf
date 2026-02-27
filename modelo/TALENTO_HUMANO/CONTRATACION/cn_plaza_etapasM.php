<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plaza_etapasM extends BaseModel
{
    protected $tabla = 'cn_plaza_etapas';
    protected $primaryKey = 'cn_plaet_id AS _id';
    protected $camposPermitidos = [
        'cn_pla_id',
        'id_etapa',
        'cn_plaet_orden',
        'cn_plaet_obligatoria',
        'estado',
        'fecha_creacion',
    ];

    public function listar_etapas_por_plaza($plaza_id)
    {
        $plaza_id = intval($plaza_id);
        $sql = "
        SELECT
            pe.cn_plaet_id              AS _id,
            pe.cn_pla_id,
            pe.id_etapa,
            pe.cn_plaet_orden,
            pe.cn_plaet_obligatoria,
            pe.estado,
            pe.fecha_creacion,
            ce.codigo                   AS etapa_codigo,
            ce.nombre                   AS etapa_nombre,
            ce.tipo                     AS etapa_tipo,
            ce.requiere_puntaje         AS etapa_requiere_puntaje,
            ce.es_fin_fijo              AS etapa_es_fin_fijo,
            ce.es_inicio_fijo           AS etapa_es_inicio_fijo,
            ce.obligatoria_default      AS etapa_obligatoria_default,
            ce.color                    AS etapa_color
        FROM cn_plaza_etapas pe
        INNER JOIN cn_cat_plaza_etapas ce ON pe.id_etapa = ce.id_etapa
        WHERE pe.cn_pla_id = $plaza_id
          AND pe.estado    = 1
          AND ce.estado    = 1
        ORDER BY pe.cn_plaet_orden ASC
        ";
        return $this->db->datos($sql);
    }

    public function buscar_existente($id_plaza, $id_etapa)
    {
        $id_plaza = intval($id_plaza);
        $id_etapa = intval($id_etapa);
        $sql = "
        SELECT cn_plaet_id
        FROM cn_plaza_etapas
        WHERE cn_pla_id = $id_plaza
          AND id_etapa  = $id_etapa
        ";
        $resultado = $this->db->datos($sql);
        return !empty($resultado) ? $resultado[0]['cn_plaet_id'] : null;
    }

    function ejecutar_crear_plaza_etapas($cn_pla_id)
    {
        set_time_limit(0);

        $parametros = [
            intval($cn_pla_id),
        ];

        $sql = "EXEC _contratacion.SP_CN_GENERAR_ETAPAS_PLAZA @cn_pla_id = ?";
        return $this->db->ejecutar_procesos_almacenados($sql, $parametros);
    }

    public function evaluar_etapas_plaza() //aqui enviar el array
    {
        $usuario = $_SESSION['INICIO']['ID_USUARIO'] ?? 0;

        $evaluaciones = [
            [
                "cn_post_id" => 14,
                "resultado" => "APROBADO",
                "puntaje" => 85.00,
                "observacion" => "Correcto"
            ],
            [
                "cn_post_id" => 12,
                "resultado" => "REPROBADO",
                "puntaje" => 40.00,
                "observacion" => "No cumple"
            ],
        ];

        $json = json_encode($evaluaciones);

        // print_r($json); exit(); die();

        $sql = "EXEC _contratacion.SP_CN_EVALUAR_ETAPA_MASIVO @json = ?, @usuario = ?;";

        $parametros = array(
            $json,
            $usuario
        );

        $resultado = $this->db->ejecutar_procedimiento_con_retorno_1(
            $sql,
            $parametros
        );

        return $resultado;
    }
}
