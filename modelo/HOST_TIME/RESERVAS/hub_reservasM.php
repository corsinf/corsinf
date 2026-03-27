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
}
