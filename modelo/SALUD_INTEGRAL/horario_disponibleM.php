<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}
/**
 * 
 */
class horario_disponibleM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_horario_disponible_todo()
    {
        $sql =
            "SELECT 
                    hdd.ac_horarioD_id,
                    hdd.ac_docente_id,
                    hdd.ac_horarioD_inicio,
                    hdd.ac_horarioD_fin,
                    hdd.ac_horarioD_fecha_disponible,
                    hdd.ac_horarioD_materia,
                    hdd.ac_horarioD_fecha_creacion,
                    hdd.ac_horarioD_fecha_modificacion,
                    hdd.ac_horarioD_estado

                    FROM horario_disponible hdd

                    WHERE 1 = 1";


        $sql .= " ORDER BY hdd.ac_horarioD_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    //Para que solo tome los turnos con fecha actual
    function lista_horario_disponible($id_docente = '')
    {
        if ($id_docente != '') {
            $sql =
                "SELECT 
                    hdd.ac_horarioD_id,
                    hdd.ac_docente_id,
                    hdd.ac_horarioD_ubicacion,
                    hdd.ac_horarioD_inicio,
                    hdd.ac_horarioD_fin,
                    hdd.ac_horarioD_fecha_disponible,
                    hdd.ac_horarioD_materia,
                    hdd.ac_horarioD_fecha_creacion,
                    hdd.ac_horarioD_fecha_modificacion,
                    hdd.ac_horarioD_estado,
                    cc.ac_cubiculo_nombre

                    FROM horario_disponible hdd
                    INNER JOIN cat_cubiculo cc ON hdd.ac_horarioD_ubicacion = cc.ac_cubiculo_id
                    WHERE 1 = 1 AND hdd.ac_docente_id = $id_docente AND CONVERT(DATE, hdd.ac_horarioD_fecha_disponible) >= CONVERT(DATE, GETDATE())";

            $sql .= " ORDER BY hdd.ac_horarioD_id;";
            $datos = $this->db->datos($sql);
            return $datos;
        }
    }


    function insertar($datos)
    {
        $rest = $this->db->inserts('horario_disponible', $datos);
        return $rest;
    }

    function insertar_id($datos)
    {
        $rest = $this->db->inserts_id('horario_disponible', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('horario_disponible', $datos, $where);
        return $rest;
    }

    function eliminar($id)
    {
        $sql = "DELETE FROM horario_disponible WHERE ac_horarioD_id = $id;";

        //"UPDATE horario_disponible SET sa_hdd_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    function turno_representanteM($id)
    {
        $sql = "UPDATE horario_disponible SET ac_horarioD_estado = 0  WHERE ac_horarioD_id = $id;";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    
}
