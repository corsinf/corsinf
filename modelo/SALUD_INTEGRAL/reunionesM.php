<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}
/**
 * 
 */
class reunionesM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_reuniones_todo()
    {

        $sql =
            "SELECT 

                reu.ac_reunion_id,
                reu.ac_horarioD_id,
                reu.ac_representante_id,
                reu.ac_estudiante_id,
                reu.ac_nombre_est,
                reu.ac_reunion_motivo,
                reu.ac_reunion_observacion,
                reu.ac_reunion_fecha_creacion,
                reu.ac_reunion_fecha_modificacion,
                reu.ac_reunion_estado,
                reu.ac_reunion_descripcion,

                hdd.ac_docente_id,
                hdd.ac_horarioD_ubicacion,
                hdd.ac_horarioD_inicio,
                hdd.ac_horarioD_fin,
                hdd.ac_horarioD_fecha_disponible,
                hdd.ac_horarioD_materia,
                hdd.ac_horarioD_estado,

                CONCAT(rep.sa_rep_primer_apellido, ' ', rep.sa_rep_segundo_apellido, ' ', rep.sa_rep_primer_nombre, ' ', rep.sa_rep_segundo_nombre) AS nombre_representante


            FROM reuniones reu
            INNER JOIN horario_disponible hdd ON reu.ac_horarioD_id = hdd.ac_horarioD_id
            INNER JOIN representantes rep ON reu.ac_representante_id = rep.sa_rep_id

            WHERE 1 = 1";


        $sql .= " ORDER BY reu.ac_horarioD_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function lista_reuniones($ac_reunion_id = '')
    {
        if ($ac_reunion_id != '') {
            $sql =
                "SELECT 
                    reu.ac_reunion_id,
                    reu.ac_horarioD_id,
                    reu.ac_representante_id,
                    reu.ac_estudiante_id,
                    reu.ac_nombre_est,
                    reu.ac_reunion_motivo,
                    reu.ac_reunion_observacion,
                    reu.ac_reunion_fecha_creacion,
                    reu.ac_reunion_fecha_modificacion,
                    reu.ac_reunion_estado,
                    reu.ac_reunion_descripcion,

                    hdd.ac_docente_id,
                    hdd.ac_horarioD_ubicacion,
                    hdd.ac_horarioD_inicio,
                    hdd.ac_horarioD_fin,
                    hdd.ac_horarioD_fecha_disponible,
                    hdd.ac_horarioD_materia,
                    hdd.ac_horarioD_estado,

                    CONCAT(rep.sa_rep_primer_apellido, ' ', rep.sa_rep_segundo_apellido, ' ', rep.sa_rep_primer_nombre, ' ', rep.sa_rep_segundo_nombre) AS nombre_representante


                FROM reuniones reu
                INNER JOIN horario_disponible hdd ON reu.ac_horarioD_id = hdd.ac_horarioD_id
                INNER JOIN representantes rep ON reu.ac_representante_id = rep.sa_rep_id
                WHERE 1 = 1 AND reu.ac_reunion_id = $ac_reunion_id";

            $sql .= " ORDER BY reu.ac_horarioD_id;";
            $datos = $this->db->datos($sql);
            return $datos;
        }
    }

    function lista_reuniones_todo_docente($ac_docente_id = '', $ac_representante_id = '')
    {
        if ($ac_docente_id != '' || $ac_representante_id != '') {
            $sql =
                "SELECT 
                    reu.ac_reunion_id,
                    reu.ac_horarioD_id,
                    reu.ac_representante_id,
                    reu.ac_estudiante_id,
                    reu.ac_nombre_est,
                    reu.ac_reunion_motivo,
                    reu.ac_reunion_observacion,
                    reu.ac_reunion_fecha_creacion,
                    reu.ac_reunion_fecha_modificacion,
                    reu.ac_reunion_estado,
                    reu.ac_reunion_descripcion,

                    hdd.ac_docente_id,
                    hdd.ac_horarioD_ubicacion,
                    hdd.ac_horarioD_inicio,
                    hdd.ac_horarioD_fin,
                    hdd.ac_horarioD_fecha_disponible,
                    hdd.ac_horarioD_materia,
                    hdd.ac_horarioD_estado,

                    CONCAT(rep.sa_rep_primer_apellido, ' ', rep.sa_rep_segundo_apellido, ' ', rep.sa_rep_primer_nombre, ' ', rep.sa_rep_segundo_nombre) AS nombre_representante,
                    CONCAT(doc.sa_doc_primer_apellido, ' ', doc.sa_doc_segundo_apellido, ' ', doc.sa_doc_primer_nombre, ' ', doc.sa_doc_segundo_nombre) AS nombre_docente,

                    cub.ac_cubiculo_nombre


                FROM reuniones reu
                INNER JOIN horario_disponible hdd ON reu.ac_horarioD_id = hdd.ac_horarioD_id
                INNER JOIN representantes rep ON reu.ac_representante_id = rep.sa_rep_id
                INNER JOIN docentes doc ON hdd.ac_docente_id = doc.sa_doc_id
                INNER JOIN cat_cubiculo cub ON hdd.ac_horarioD_ubicacion = cub.ac_cubiculo_id
                WHERE 1 = 1 ";

            if ($ac_docente_id != '') {
                $sql .= " AND hdd.ac_docente_id = $ac_docente_id";
            }

            if ($ac_representante_id != '') {
                $sql .= " AND reu.ac_representante_id = $ac_representante_id";
            }

            $sql .= " ORDER BY reu.ac_horarioD_id DESC;";
            $datos = $this->db->datos($sql);
            return $datos;
        }
    }


    function insertar($datos)
    {
        $rest = $this->db->inserts('reuniones', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('reuniones', $datos, $where);
        return $rest;
    }

    function eliminar($id)
    {
        $sql = "DELETE FROM reuniones WHERE ac_horarioD_id = $id;";

        //"UPDATE reuniones SET sa_reu_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }
}
