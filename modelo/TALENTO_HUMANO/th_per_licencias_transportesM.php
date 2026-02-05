<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_per_licencias_transportesM extends BaseModel
{
    protected $tabla = 'th_per_licencias_transportes';
    protected $primaryKey = 'th_lic_id AS _id';

    protected $camposPermitidos = [
        'id_licencia_transporte AS id_licencia_transporte',
        'th_per_id AS id_persona',
        'th_lic_fecha_expedicion AS fecha_expedicion',
        'th_lic_fecha_vencimiento AS fecha_vencimiento',
        'th_lic_autoridad_emisora AS autoridad_emisora',
        'th_lic_escuela AS escuela',
        'th_lis_estado_licencia AS estado_licencia',
        'th_lic_estado AS estado',
        'th_lic_fecha_creacion AS fecha_creacion',
        'th_lic_fecha_modificacion AS fecha_modificacion',
    ];


    public function listar_licencias($id_licencia = '', $id_persona = '')
    {
        $sql = "SELECT
                lt.th_lic_id AS _id,
                lt.id_licencia_transporte AS id_licencia_transporte,
                clt.codigo AS codigo,
                clt.descripcion AS tipo_licencia_transporte,
                clt.categoria AS categoria,
                lt.th_per_id AS id_persona,
                lt.th_lic_fecha_expedicion AS fecha_expedicion,
                lt.th_lic_fecha_vencimiento AS fecha_vencimiento,
                lt.th_lic_autoridad_emisora AS autoridad_emisora,
                lt.th_lic_escuela AS escuela,
                lt.th_lis_estado_licencia AS estado_licencia,
                lt.th_lic_estado AS estado,
                lt.th_lic_fecha_creacion AS fecha_creacion,
                lt.th_lic_fecha_modificacion AS fecha_modificacion
            FROM th_per_licencias_transportes lt
            LEFT JOIN th_cat_tipo_licencia_transporte clt 
                ON lt.id_licencia_transporte = clt.id_licencia_transporte
            WHERE lt.th_lic_estado = 1 ";

        // Filtro por ID específico de registro de licencia
        if (!empty($id_licencia)) {
            $id_licencia = intval($id_licencia);
            $sql .= " AND lt.th_lic_id = $id_licencia";
        }

        // Filtro por Persona
        if (!empty($id_persona)) {
            $id_persona = intval($id_persona);
            $sql .= " AND lt.th_per_id = $id_persona";
        }

        // Ordenar por principal para que los destacados salgan primero
        $sql .= " ORDER BY clt.descripcion ASC";

        return $this->db->datos($sql);
    }

    public function buscar_estados_licencias()
    {
        $lista = [];

        foreach (EstadoLicenciaTransporte::cases() as $estado) {
            $lista[] = [
                'id'   => $estado->value,
                'text' => $estado->value
            ];
        }

        return $lista;
    }
}


enum EstadoLicenciaTransporte: string
{
    case VIGENTE   = 'VIGENTE';
    case VENCIDA   = 'VENCIDA';
    case SUSPENDIDA = 'SUSPENDIDA';
    case CANCELADA = 'CANCELADA';
}
