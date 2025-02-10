<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}
/**
 * 
 */
class v_med_insM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_vista_med_ins($tipo)
    {
        if ($tipo != '') {
            $sql =
                "SELECT 
                sa_vmi_id,
                sa_vmi_descripcion,
                sa_vmi_id_input,
                sa_vmi_tipo,
                sa_vmi_estado

                FROM v_med_ins
                WHERE sa_vmi_tipo = '$tipo';";

            $datos = $this->db->datos($sql);
            return $datos;
        } else {
            return -2;
        }
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('v_med_ins', $datos, $where);
        return $rest;
    }

   
}
