<?php
if (!class_exists('db_salud')) {
    include('../db/db_salud.php');
}
/**
 * 
 */
class representantesM
{
    private $db_salud;

    function __construct()
    {
        $this->db_salud = new db_salud();
    }

    function lista_representantes($id = '')
    {
        $sql =
            "SELECT rep.sa_rep_id, 
                    rep.sa_rep_primer_apellido,
                    rep.sa_rep_segundo_apellido,
                    rep.sa_rep_primer_nombre,
                    rep.sa_rep_segundo_nombre,
                    rep.sa_rep_cedula,
                    rep.sa_rep_sexo,
                    rep.sa_rep_fecha_nacimiento,
                    rep.sa_id_seccion,
                    rep.sa_id_grado,
                    rep.sa_id_paralelo,
                    rep.sa_rep_correo,
                    rep.sa_rep_parentesco,
                    rep.sa_rep_telefono_1,
                    rep.sa_rep_telefono_2,
                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    pr.sa_par_id, 
                    pr.sa_par_nombre
                    FROM representantes rep
                    INNER JOIN cat_seccion cs ON rep.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON rep.sa_id_grado = cg.sa_gra_id
                    INNER JOIN cat_paralelo pr ON rep.sa_id_paralelo = pr.sa_par_id
                    WHERE rep.sa_rep_estado = 1";

        if ($id) {
            $sql .= ' and sa_rep_id = ' . $id;
        }

        $sql .= " ORDER BY sa_rep_id;";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function lista_representantes_todo($id = '')
    {
        $sql = "SELECT  sa_rep_id, sa_par_nombre, sa_par_estado FROM representantes WHERE 1 = 1 ";

        if ($id) {
            $sql .= ' and sa_rep_id= ' . $id;
        }

        $sql .= " ORDER BY sa_rep_id ";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function buscar_representantes($buscar)
    {
        $sql = "SELECT rep.sa_rep_id, 
                    rep.sa_rep_primer_apellido,
                    rep.sa_rep_segundo_apellido,
                    rep.sa_rep_primer_nombre,
                    rep.sa_rep_segundo_nombre,
                    rep.sa_rep_cedula,
                    rep.sa_rep_sexo,
                    rep.sa_rep_fecha_nacimiento,
                    rep.sa_id_seccion,
                    rep.sa_id_grado,
                    rep.sa_id_paralelo,
                    rep.sa_rep_correo,
                    rep.sa_rep_parentesco,
                    rep.sa_rep_telefono_1,
                    rep.sa_rep_telefono_2,
                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    pr.sa_par_id, 
                    pr.sa_par_nombre
            FROM representantes rep
            INNER JOIN cat_seccion cs ON rep.sa_id_seccion = cs.sa_sec_id
            INNER JOIN cat_grado cg ON rep.sa_id_grado = cg.sa_gra_id
            INNER JOIN cat_paralelo pr ON rep.sa_id_paralelo = pr.sa_par_id
            WHERE rep.sa_rep_estado = 1 
            AND CONCAT(rep.sa_rep_primer_apellido, ' ', rep.sa_rep_segundo_apellido, ' ', 
                       rep.sa_rep_primer_nombre, ' ', rep.sa_rep_segundo_nombre, ' ',
                       rep.sa_rep_cedula, ' ',          
                       rep.sa_rep_correo,
                       cs.sa_sec_nombre, ' ', 
                       cg.sa_gra_nombre, ' ', 
                       pr.sa_par_nombre) LIKE '%" . $buscar . "%'";

        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function buscar_representantes_CODIGO($buscar)
    {
        $sql = "SELECT sa_rep_id, sa_rep_cedula, sa_rep_primer_apellido, sa_rep_primer_nombre FROM representantes WHERE sa_rep_id = '" . $buscar . "'";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db_salud->inserts('representantes', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db_salud->update('representantes', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE representantes SET sa_rep_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db_salud->sql_string($sql);
        return $datos;
    }

    
}
