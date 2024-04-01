<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class gradoM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function lista_grado($id = '')
    {
        $sql = "SELECT cg.sa_gra_id, cg.sa_gra_nombre, cg.sa_gra_estado, cs.sa_sec_id, cs.sa_sec_nombre
        FROM cat_grado cg
        INNER JOIN cat_seccion cs ON cg.sa_id_seccion = cs.sa_sec_id
        WHERE cg.sa_gra_estado = 1";

        if ($id) {
            $sql .= ' and sa_gra_id = ' . $id;
        }

        $sql .= " ORDER BY sa_gra_id";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function lista_grado_todo($id = '')
    {
        $sql = "SELECT  sa_gra_id, sa_gra_nombre, sa_gra_estado FROM cat_grado WHERE 1 = 1 ";

        if ($id) {
            $sql .= ' and sa_gra_id= ' . $id;
        }

        $sql .= " ORDER BY sa_gra_id ";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_grado($buscar)
    {
        $sql = "SELECT cg.sa_gra_id, cg.sa_gra_nombre, cg.sa_gra_estado, cs.sa_sec_nombre
        FROM cat_grado cg
        INNER JOIN cat_seccion cs ON cg.sa_id_seccion = cs.sa_sec_id
        WHERE cg.sa_gra_estado = 1 
        and CONCAT(cg.sa_gra_nombre, ' ', cg.sa_gra_id, ' ', cs.sa_sec_nombre) LIKE '%" . $buscar . "%'";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_grado_CODIGO($buscar)
    {
        $sql = "SELECT sa_gra_id, sa_gra_nombre, sa_gra_estado FROM cat_grado WHERE sa_gra_id = '" . $buscar . "'";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('cat_grado', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('cat_grado', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE cat_grado SET sa_gra_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }
}
