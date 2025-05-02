<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_reporte_camposM extends BaseModel
{
    protected $tabla = 'th_reporte_campos';
    protected $primaryKey = 'th_rec_id AS _id';

    protected $camposPermitidos = [
        'th_rep_id AS id_reporte',
        'th_crep_id AS id_catalogo_reporte',
        'th_rec_orden AS orden',
        'th_rec_es_obligatorio AS es_obligatorio',
        'th_rec_fecha_creacion AS fecha_creacion',
        'th_rec_fecha_modificacion AS fecha_modificacion',
        'th_rec_estado AS estado',
    ];

    function listar_reporte_campos($id_reporte)
    {
        $sql =
            "SELECT 
                rc.th_rec_id AS _id,
                rc.th_rep_id AS id_reporte,
                rc.th_crep_id AS id_catalogo_reporte,
                rc.th_rec_orden AS orden,
                rc.th_rec_es_obligatorio AS es_obligatorio,
                rc.th_rec_fecha_creacion AS fecha_creacion,
                rc.th_rec_fecha_modificacion AS fecha_modificacion,
                rc.th_rec_estado AS estado,
                cra.th_crep_nombre_encabezado AS nombre_encabezado,
                cra.th_crep_nombre_atributo AS nombre_atributo,
                cra.th_crep_nombre_descripcion AS nombre_descripcion,
                cra.th_crep_modulo AS modulo
            FROM th_reporte_campos rc
            INNER JOIN th_cat_reporte_atributo cra ON rc.th_crep_id = cra.th_crep_id
            WHERE rc.th_rep_id = '$id_reporte';";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function editar_insertarM_2($datos)
    {
        $updateQuery = "";
        $insertValues = [];

        foreach ($datos as $item) {
            $id_rt = $item['txt_id_rt']; // ID existente o vacío
            $id_ct = $item['txt_id_ct'];
            $orden = $item['txt_orden'];
            $id_reporte = $item['txt_id_rep'];

            if (!empty($id_rt)) {
                // Si tiene txt_id_rt, hacer UPDATE
                $updateQuery .= "UPDATE th_reporte_campos SET th_rec_orden = $orden WHERE th_rec_id = $id_rt;";
            } else {
                // Si txt_id_rt está vacío, hacer INSERT
                $insertValues[] = "($id_reporte, $id_ct, $orden)";
            }
        }

        // Generar consulta INSERT solo si hay valores
        $insertQuery = "";
        if (!empty($insertValues)) {
            $insertQuery = "INSERT INTO th_reporte_campos (th_rep_id, th_crep_id, th_rec_orden) VALUES " . implode(",", $insertValues) . ";";
        }

        // Retornar la consulta completa (puede ejecutarse en una sola llamada SQL)
        $sql = $updateQuery . $insertQuery;

        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    function editar_insertarM($datos)
    {
        $values = [];
        foreach ($datos as $item) {
            $values[] = "SELECT {$item['txt_id_rep']} AS th_rep_id, {$item['txt_id_ct']} AS th_crep_id, {$item['txt_orden']} AS th_rec_orden";
        }

        $sql = "MERGE INTO th_reporte_campos AS target
                    USING (" . implode(" UNION ALL ", $values) . ") AS source
                    ON target.th_rep_id = source.th_rep_id 
                    AND target.th_crep_id = source.th_crep_id 
                    WHEN MATCHED THEN
                        UPDATE SET target.th_rec_orden = source.th_rec_orden
                    WHEN NOT MATCHED THEN
                        INSERT (th_rep_id, th_crep_id, th_rec_orden)
                        VALUES (source.th_rep_id, source.th_crep_id, source.th_rec_orden);";

        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    function eliminar_registros($datos)
    {
        $ids = array_map(function ($item) {
            return $item['txt_id_rt'];  // Extraemos el valor de 'txt_id_rt'
        }, $datos);

        $ids_list = implode(',', $ids);

        if (empty($ids_list)) {
            return 1;
        }

        $sql = "DELETE FROM th_reporte_campos WHERE th_rec_id IN ($ids_list)";

        $datos = $this->db->sql_string($sql);

        return $datos;
    }
}
