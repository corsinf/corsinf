<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_log_dispositivosM extends BaseModel
{
    protected $tabla = 'th_log_dispositivos_masivos';
    protected $primaryKey = 'id AS _id';

    protected $camposPermitidos = [
        'LOG_DEVICE AS data',
        'estado_procesado AS estado',
    ];

    function insertar_logs($data)
    {
        $this->db->parametros_conexion();
        $conn =  $this->db->conexion();
        $tabla_esquema = $this->db->esquema_modulo($tabla,1);
        $sql = "INSERT INTO ".$tabla_esquema." (LOG_DEVICE, estado_procesado) VALUES (:log_device, :estado)";
        $stmt = $conn->prepare($sql);

        try {
            // Iniciar transacción
            $conn->beginTransaction();

            foreach ($data as $value) {
                $stmt->execute([
                    ':log_device' => $value,
                    ':estado' => 0
                ]);
            }

            // Confirmar transacción
            $conn->commit();

            return 1;

        } catch (Exception $e) {
            // Revertir si falla algo
            $conn->rollBack();
            throw $e;
        }
    }
}
