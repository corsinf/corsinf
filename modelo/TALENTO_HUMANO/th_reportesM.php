<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_reportesM extends BaseModel
{

    function control_acceso_departamento($fecha_inicial, $fecha_final, $id_departamento)
    {
        $parametros = array(
            $fecha_inicial,
            $fecha_final,
            $id_departamento,
        );

        $sql = "EXEC generar_reporte_control_accesos @fecha_inicio = ?, @fecha_fin = ?, @id_departamento = ?;";

        $datos = $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros);

        return $datos;
    }
}
