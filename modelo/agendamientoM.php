<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class agendamientoM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function lista_consultas($fecha = false)
    {
        $sql = "SELECT 
                    cm.sa_conp_id,
                    cm.sa_conp_fecha_ingreso,
                    cm.sa_conp_desde_hora,
                    cm.sa_conp_hasta_hora,
                    cm.sa_conp_tipo_consulta,
                    cm.sa_fice_id,
                    pac.sa_pac_id,
                    CONCAT(pac.sa_pac_apellidos, ' ', pac.sa_pac_nombres) AS nombres
                FROM consultas_medicas cm
                INNER JOIN ficha_medica fm ON cm.sa_fice_id = fm.sa_fice_id
                INNER JOIN pacientes pac ON fm.sa_fice_pac_id = pac.sa_pac_id
                WHERE cm.sa_conp_estado_revision = 0";

        if ($fecha) {
            $sql .= " AND CONVERT(VARCHAR(10), sa_conp_fecha_creacion, 120) ='" . $fecha . "'";
        }

        //print_r($sql);die();
        return $this->db->datos($sql);
    }

    function insertar($tabla, $datos)
    {
        $rest = $this->db->inserts($tabla, $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('estudiantes', $datos, $where);
        return $rest;
    }
}
