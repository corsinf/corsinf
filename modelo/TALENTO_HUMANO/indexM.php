<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}
class indexM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Poner consultas para el indexfunction lista_articulos_tipo()
    function lista_articulos_tipo()
    {
        $sql =
            "SELECT 
                t.ID_TIPO_ARTICULO,
                t.DESCRIPCION,
                t.COLOR,
                COUNT(a.id_articulo) AS TOTAL_ARTICULOS
            FROM 
                ac_cat_tipo_articulo t
            LEFT JOIN 
                ac_articulos a ON t.ID_TIPO_ARTICULO = a.ID_TIPO_ARTICULO
            GROUP BY 
                t.ID_TIPO_ARTICULO,
                t.DESCRIPCION,
                t.COLOR
            ORDER BY t.ID_TIPO_ARTICULO ASC;";

        $datos = $this->db->datos($sql);
        return $datos;
    }


    function listar_asistencia_departamento($departamento = null)
    {
        $sql = "
        SELECT 
            th_asi_id AS _id,
            th_asi_fecha,
            th_asi_ausente,
            th_asi_hora_ajustada,
            th_asi_salida_ausente,
            th_asi_dia_justificado,
            th_asi_cumple_jornada,
            th_asi_trabajo_con_justificacion,
            th_asi_salida_marcacion_str
        FROM 
            th_control_acceso_calculos
    ";

        // Si enviaron un departamento, agregamos el filtro
        if (!empty($departamento)) {
            $sql .= " WHERE th_asi_departamento = '$departamento' ";
        }

        $sql .= " ORDER BY th_asi_fecha ASC;";

        return $this->db->datos($sql);
    }
}
