<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}
/**
 * 
 */
class seguimiento_personalM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_seguimiento($id)
    {
        if ($id != '') {
            $sql =
                "SELECT 
                    sa_sep_id,
                    pac_id,
                    sa_sep_observacion,
                    usu_id,
                    sa_sep_fecha_creacion,
                    sa_sep_fecha_modificacion

                FROM seguimiento_personal
                WHERE pac_id = '$id';";

            $datos = $this->db->datos($sql);
            return $datos;
        } else {
            return -2;
        }
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('seguimiento_personal', $datos);
        return $rest;
    }
}
