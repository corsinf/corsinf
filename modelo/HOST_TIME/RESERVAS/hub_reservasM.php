<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class hub_reservasM extends BaseModel
{
    protected $tabla = 'hub_reservas';
    protected $primaryKey = 'id_reserva AS _id';

    protected $camposPermitidos = [
        'codigo',
        'th_per_id',
        'id_espacio',
        'inicio',
        'fin',
        'observaciones',
        'id_estado_reservas',
        'is_deleted',
        'id_usuario_crea',
        'fecha_creacion',
        'id_usuario_modifica',
        'fecha_modificacion',
    ];


    public function ejecutar_crear_reserva($parametros)
    {
        $sql = "EXEC [_host_time].[SP_RESERVAR_ESPACIO] 
            @id_espacio = ?, 
            @inicio = ?, 
            @fin = ?, 
            @id_usuario = ?, 
            @th_per_id = ?, 
            @observaciones = ?";

        $valores = [
            $parametros['id_espacio'],
            $parametros['inicio'],
            $parametros['fin'],
            $parametros['id_usuario'],
            $parametros['th_per_id'],
            $parametros['observaciones'] ?? ''
        ];

        return $this->db->ejecutar_procedimiento_con_retorno_1($sql, $valores);
    }

    public function listar_reservas_detalle($id = '')
    {
        $sql = "SELECT 
                r.id_reserva AS _id,
                r.codigo,
                r.inicio,
                r.fin,
                r.th_per_id,
                r.id_estado_reservas,
                r.observaciones,
                r.id_espacio,
                e.nombre AS nombre_espacio,
                e.codigo AS codigo_espacio,
                er.nombre AS estado_reserva,
                CONCAT(p.th_per_primer_nombre, ' ', p.th_per_segundo_nombre, ' ', p.th_per_primer_apellido, ' ', p.th_per_segundo_apellido) AS nombre_persona,
                p.th_per_cedula AS cedula,
                p.th_per_telefono_1 AS telefono,
                p.th_per_correo AS correo
            FROM hub_reservas r
            LEFT JOIN hub_espacios e ON r.id_espacio = e.id_espacio
            LEFT JOIN hub_cats_estado_reservas er ON r.id_estado_reservas = er.id_estado_reserva
            LEFT JOIN th_personas p ON r.th_per_id = p.th_per_id
            WHERE r.is_deleted = 0";

        if ($id != '') {
            $sql .= " AND r.id_reserva = " . intval($id);
        }

        return $this->db->datos($sql);
    }
}
