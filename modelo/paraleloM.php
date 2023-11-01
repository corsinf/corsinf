<?php
if (!class_exists('db_salud')) {
    include('../db/db_salud.php');
}
/**
 * 
 */
class paraleloM
{
    private $db_salud;

    function __construct()
    {
        $this->db_salud = new db_salud();
    }

    function lista_paralelo($id = '')
    {
        $sql = "SELECT cp.sa_par_id, cp.sa_par_nombre, cp.sa_par_estado, cs.sa_sec_id, cs.sa_sec_nombre, cg.sa_gra_id, cg.sa_gra_nombre
        FROM cat_paralelo cp
        INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
        INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id
        WHERE cp.sa_par_estado = 1";

        if ($id) {
            $sql .= ' and sa_par_id = ' . $id;
        }

        $sql .= " ORDER BY sa_par_id";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function lista_paralelo_todo($id = '')
    {
        $sql = "SELECT  sa_par_id, sa_par_nombre, sa_par_estado FROM cat_paralelo WHERE 1 = 1 ";

        if ($id) {
            $sql .= ' and sa_par_id= ' . $id;
        }

        $sql .= " ORDER BY sa_par_id ";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function buscar_paralelo($buscar)
    {
        $sql = "SELECT cp.sa_par_id, cp.sa_par_nombre, cp.sa_par_estado, cs.sa_sec_id, cs.sa_sec_nombre, cg.sa_gra_id, cg.sa_gra_nombre
        FROM cat_paralelo cp
        INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
        INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id
        WHERE cp.sa_par_estado = 1 
        and CONCAT(cp.sa_par_nombre, ' ', cp.sa_par_id, ' ', cs.sa_sec_nombre, ' ', cg.sa_gra_nombre) LIKE '%" . $buscar . "%'";

        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function buscar_paralelo_CODIGO($buscar)
    {
        $sql = "SELECT sa_par_id, sa_par_nombre, sa_par_estado FROM cat_paralelo WHERE sa_par_id = '" . $buscar . "'";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db_salud->inserts('cat_paralelo', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db_salud->update('cat_paralelo', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE cat_paralelo SET sa_par_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db_salud->sql_string($sql);
        return $datos;
    }
}
