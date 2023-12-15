<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class representantesM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_representantes_todo()
    {
        $sql =
            "SELECT 
                    rep.sa_rep_id,
                    rep.sa_rep_primer_apellido,
                    rep.sa_rep_segundo_apellido,
                    rep.sa_rep_primer_nombre,
                    rep.sa_rep_segundo_nombre,
                    rep.sa_rep_cedula,
                    rep.sa_rep_fecha_nacimiento,
                    rep.sa_rep_telefono_1,
                    rep.sa_rep_telefono_2,
                    rep.sa_rep_correo

                    FROM representantes rep
                    WHERE rep.sa_rep_estado = 1";

        $sql .= " ORDER BY sa_rep_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function lista_representantes($id = '')
    {
        $sql =
            "SELECT 
                    rep.sa_rep_id,
                    rep.sa_rep_primer_apellido,
                    rep.sa_rep_segundo_apellido,
                    rep.sa_rep_primer_nombre,
                    rep.sa_rep_segundo_nombre,
                    rep.sa_rep_cedula,
                    rep.sa_rep_sexo,
                    rep.sa_rep_fecha_nacimiento,
                    rep.sa_rep_telefono_1,
                    rep.sa_rep_telefono_2,
                    rep.sa_rep_correo,
                    rep.sa_rep_tabla,
                    rep.sa_rep_estado,
                    rep.sa_rep_fecha_creacion,
                    rep.sa_rep_fecha_modificacion

                    FROM representantes rep
                    WHERE rep.sa_rep_estado = 1";

        if ($id) {
            $sql .= ' and sa_rep_id = ' . $id;
        }

        $sql .= " ORDER BY sa_rep_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_representantes($buscar)
    {
        $sql = "SELECT 
                    rep.sa_rep_id,
                    rep.sa_rep_primer_apellido,
                    rep.sa_rep_segundo_apellido,
                    rep.sa_rep_primer_nombre,
                    rep.sa_rep_segundo_nombre,
                    rep.sa_rep_cedula,
                    rep.sa_rep_sexo,
                    rep.sa_rep_fecha_nacimiento,
                    rep.sa_rep_telefono_1,
                    rep.sa_rep_telefono_2,
                    rep.sa_rep_correo,
                    rep.sa_rep_tabla,
                    rep.sa_rep_estado,
                    rep.sa_rep_fecha_creacion,
                    rep.sa_rep_fecha_modificacion

                    FROM representantes rep
                    WHERE rep.sa_rep_estado = 1

                    AND CONCAT(rep.sa_rep_primer_apellido, ' ', rep.sa_rep_segundo_apellido, ' ', 
                       rep.sa_rep_primer_nombre, ' ', rep.sa_rep_segundo_nombre, ' ',
                       rep.sa_rep_cedula, ' ',          
                       rep.sa_rep_correo) LIKE '%" . $buscar . "%'";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_representantes_CEDULA($buscar)
    {
        $sql = "SELECT sa_rep_id, sa_rep_cedula, sa_rep_primer_apellido, sa_rep_primer_nombre FROM representantes WHERE sa_rep_cedula = '" . $buscar . "'";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('representantes', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('representantes', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE representantes SET sa_rep_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }


}
