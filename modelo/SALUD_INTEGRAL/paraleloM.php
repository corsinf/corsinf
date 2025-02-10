<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}
/**
 * 
 */
class paraleloM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
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
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function lista_paralelo_todo()
    {
        $sql = "SELECT cp.sa_par_id, cp.sa_par_nombre, cp.sa_par_estado, cs.sa_sec_id, cs.sa_sec_nombre, cg.sa_gra_id, cg.sa_gra_nombre
        FROM cat_paralelo cp
        INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
        INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id
        WHERE cp.sa_par_estado = 1";

        $sql .= " ORDER BY sa_par_id";
        $datos = $this->db->datos($sql);
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

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_paralelo_CODIGO($buscar)
    {
        $sql = "SELECT sa_par_id, sa_par_nombre, sa_par_estado FROM cat_paralelo WHERE sa_par_id = '" . $buscar . "'";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('cat_paralelo', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('cat_paralelo', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE cat_paralelo SET sa_par_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    /*/////////////////////////////////////////////////////////////////////

    Para consultar en estudiantes y en cursos

    /////////////////////////////////////////////////////////////////////*/


    function buscar_seccion_grado($buscar)
    {
        $sql = "SELECT cg.sa_gra_id, cg.sa_gra_nombre, cg.sa_gra_estado, cs.sa_sec_id, cs.sa_sec_nombre
                FROM cat_grado cg
                INNER JOIN cat_seccion cs ON cg.sa_id_seccion = cs.sa_sec_id
                WHERE cg.sa_gra_estado = 1
                AND cs.sa_sec_id = " . $buscar;

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_grado_paralelo($buscar)
    {
        $sql = "SELECT cp.sa_par_id, cp.sa_par_nombre, cp.sa_par_estado, cs.sa_sec_id, cs.sa_sec_nombre, cg.sa_gra_id, cg.sa_gra_nombre
                FROM cat_paralelo cp
                INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id
                WHERE cp.sa_par_estado = 1
                AND cg.sa_gra_id = " . $buscar;

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
