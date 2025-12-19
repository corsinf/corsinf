<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_feriadosM extends BaseModel
{
    protected $tabla = 'th_feriados';
    protected $primaryKey = 'th_fer_id AS _id';

    protected $camposPermitidos = [
        'th_fer_fecha_inicio_feriado AS fecha_inicio_feriado',
        'th_fer_nombre AS nombre',
        'th_fer_dias AS dias',
        'th_fer_fecha_creacion AS fecha_creacion',
        'th_fer_fecha_modificacion AS fecha_modificacion',
        'th_fer_estado AS estado',
        'id_usuario AS id_usuario',
    ];


    public function existe_feriado_en_rango($fecha_inicio, $dias, $id_feriado_excluir = null)
{
    $dias = intval($dias);
    $id_feriado_excluir = intval($id_feriado_excluir);

    // 🔒 Asegurar formato ISO
    $fecha_inicio = date('Y-m-d\TH:i:s', strtotime($fecha_inicio));

    $sql = "
        SELECT COUNT(*) AS total
        FROM th_feriados
        WHERE th_fer_estado = 1
        AND th_fer_fecha_inicio_feriado <= DATEADD(DAY, $dias, '$fecha_inicio')
        AND DATEADD(
            DAY,
            CAST(th_fer_dias AS INT),
            th_fer_fecha_inicio_feriado
        ) >= '$fecha_inicio'
    ";

    if ($id_feriado_excluir > 0) {
        $sql .= " AND th_fer_id <> $id_feriado_excluir";
    }

    return $resultado = $this->db->datos($sql);
}





}
