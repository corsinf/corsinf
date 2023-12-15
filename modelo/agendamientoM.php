<?php
if (!class_exists('db_salud')) {
    include('../db/db_salud.php');
}
/**
 * 
 */
class agendamientoM
{
    private $db_salud;

    function __construct()
    {
        $this->db_salud = new db_salud();
    }

    function lista_consultas($fecha=false)
    {
    	$sql = "SELECT * from consultas c
        INNER JOIN estudiantes e on c.sa_fice_id = e.sa_est_id
        WHERE sa_conp_estado = 0";
        if($fecha)
        {
            $sql.= " AND CONVERT(VARCHAR(10), sa_conp_fecha_creacion, 120) ='".$fecha."'";
        }

        // print_r($sql);die();
    	return $this->db_salud->datos($sql);
    }

    function insertar($tabla,$datos)
    {
        $rest = $this->db_salud->inserts($tabla, $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db_salud->update('estudiantes', $datos, $where);
        return $rest;
    }

}
