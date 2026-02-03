<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_per_bancosM extends BaseModel
{
    protected $tabla = 'th_per_bancos';
    protected $primaryKey = 'th_ban_id AS _id';

    protected $camposPermitidos = [
        'id_banco AS id_banco',
        'th_per_id AS id_persona',
        'id_tipo_cuenta AS id_tipo_cuenta',
        'th_ban_numero_cuenta AS numero_cuenta',
        'es_principal AS es_principal',
        'th_ban_estado AS estado',
        'th_ban_fecha_creacion AS fecha_creacion',
        'th_ban_fecha_modificacion AS fecha_modificacion',
    ];


    public function listar_bancos($id_banco = '', $id_persona = '')
    {
        $sql = "SELECT
                pb.th_ban_id AS _id,
                pb.th_per_id,
                pb.id_banco,
                pb.id_tipo_cuenta,
                pb.th_ban_numero_cuenta,
                pb.es_principal,
                pb.th_ban_fecha_modificacion,
                b.descripcion AS banco_descripcion,
                tc.descripcion AS tipo_cuenta_descripcion
            FROM th_per_bancos pb
            LEFT JOIN th_cat_bancos b 
                ON pb.id_banco = b.id_banco
            LEFT JOIN th_cat_tipo_cuenta_banco tc 
                ON pb.id_tipo_cuenta = tc.id_tipo_cuenta
            WHERE pb.th_ban_estado = 1 ";

        // Filtro por ID específico de registro de banco
        if (!empty($id_banco)) {
            $id_banco = intval($id_banco);
            $sql .= " AND pb.th_ban_id = $id_banco";
        }

        // Filtro por Persona
        if (!empty($id_persona)) {
            $id_persona = intval($id_persona);
            $sql .= " AND pb.th_per_id = $id_persona";
        }

        // Ordenar por principal para que los destacados salgan primero
        $sql .= " ORDER BY pb.es_principal DESC, b.descripcion ASC";

        return $this->db->datos($sql);
    }
}
