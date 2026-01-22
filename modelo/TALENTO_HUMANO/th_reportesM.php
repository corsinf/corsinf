<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_reportesM extends BaseModel
{
    protected $tabla = 'th_reportes';
    protected $primaryKey = 'th_rep_id AS _id';

    protected $camposPermitidos = [
        'th_rep_nombre AS nombre',
        'th_rep_descripcion AS descripcion',
        'th_tip_rep_id AS tipo_reporte',
        'th_rep_fecha_creacion AS fecha_creacion',
        'th_rep_fecha_modificacion AS fecha_modificacion',
        'th_rep_estado AS estado',
    ];

    //Reporte 1
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

    public function listar_reporte($id = '', $estado = 1)
    {
        $sql = "SELECT 
                    r.th_rep_id AS _id,
                    r.th_rep_nombre AS nombre,
                    r.th_rep_descripcion AS descripcion,
                    r.th_rep_fecha_creacion AS fecha_creacion,
                    r.th_rep_fecha_modificacion AS fecha_modificacion,
                    r.th_rep_estado AS estado,
                    r.th_tip_rep_id AS tipo_reporte,
                    t.th_tip_rep_nombre AS nombre_tipo_reporte
                FROM th_reportes r
                INNER JOIN th_cat_tipo_reporte t ON r.th_tip_rep_id = t.th_tip_rep_id
                WHERE r.th_rep_estado = $estado";

        if ($id != '') {
            $sql .= " AND r.th_rep_id = $id";
        }

        $datos = $this->db->datos($sql);
        return $datos;
    }

    public function sincronizar_marcaciones()
    {
        $sql = "EXEC _asistencias.SP_PROCESAR_LOG_DISPOSITIVOS_MASIVOS;";
        return $this->db->sql_string($sql);
    }
}
