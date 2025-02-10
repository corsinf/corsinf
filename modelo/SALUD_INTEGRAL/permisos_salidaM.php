<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}
/**
 * 
 */
class permisos_salidaM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function lista_permisos_salida_todo()
    {
        $sql =
            "SELECT 
                    pes.ac_ps_id,
                    pes.ac_ps_id_autoriza,
                    pes.ac_ps_tabla,
                    pes.ac_ps_id_tabla,
                    pes.ac_ps_nombre,
                    pes.ac_ps_hora_salida,
                    pes.ac_ps_hora_entrada,
                    pes.ac_ps_estado_salida,
                    pes.ac_ps_codigo_TCP_HIK,
                    pes.ac_ps_prioridad,
                    pes.ac_ps_estado,
                    pes.ac_ps_observacion,
                    pes.ac_ps_fecha_creacion

                    FROM permisos_salida pes
                    WHERE pes.ac_ps_estado = 1";

        $sql .= " ORDER BY pes.ac_ps_id;";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('permisos_salida', $datos);
        return $rest;
    }

    function insertar_id($datos)
    {
        // print_r($datos);die();
        $rest = $this->db->inserts_id('permisos_salida', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('permisos_salida', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE permisos_salida SET ac_ps_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }
}
