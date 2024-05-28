<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class notificacionesM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_notificaciones($parametros)
    {
        $rol = $parametros['rol'];
        $tabla = $parametros['tabla'];
        $id_tabla = $parametros['id_tabla'];

        if ($rol == 'DOCENTES' || $rol == 'INSPECTOR' || $rol == 'REPRESENTANTE') {
            $sql =
                "SELECT 
                ntc.GLO_id,
                ntc.GLO_modulo,
                ntc.GLO_titulo,
                ntc.GLO_cuerpo,
                ntc.GLO_icono,
                ntc.GLO_tabla,
                ntc.GLO_id_tabla,
                ntc.GLO_busqueda_especifica,
                ntc.GLO_desc_busqueda,
                ntc.GLO_link_redirigir,
                ntc.GLO_rol,
                ntc.GLO_observacion,
                ntc.GLO_estado,
                ntc.GLO_fecha_creacion

            FROM notificaciones ntc";

            if ($tabla == 'docentes') {
                $sql .=
                    " INNER JOIN docente_paralelo dop ON ntc.GLO_busqueda_especifica = dop.ac_paralelo_id 
                INNER JOIN docentes doc ON dop.ac_docente_id= doc.sa_doc_id";
            }

            $sql .= " WHERE ntc.GLO_estado = 1";

            if ($tabla == 'docentes') {
                $sql .= " AND doc.sa_doc_id = '$id_tabla'";
            }

            if ($tabla == 'representantes') {
                $sql .= " AND GLO_id_tabla = '$id_tabla'";
            }

            if ($rol == 'INSPECTOR') {
                $sql .= " AND ntc.GLO_rol = '$rol'";
            }


            $sql .= " AND CONVERT(DATE, ntc.GLO_fecha_creacion) = CONVERT(DATE, GETDATE())";

            $sql .= " ORDER BY GLO_id DESC;";
            //print_r($sql);exit();
            $datos = $this->db->datos($sql);
            return $datos;
        }
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('notificaciones', $datos);
        return $rest;
    }
}
