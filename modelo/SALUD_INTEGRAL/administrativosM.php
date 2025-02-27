<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}
/**
 * 
 */
class administrativosM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_administrativos_todo()
    {
        $sql =
            "SELECT 
                    adm.sa_adm_id,
                    adm.sa_adm_primer_apellido,
                    adm.sa_adm_segundo_apellido,
                    adm.sa_adm_primer_nombre,
                    adm.sa_adm_segundo_nombre,
                    adm.sa_adm_cedula,
                    adm.sa_adm_fecha_nacimiento,
                    adm.sa_adm_telefono_1,
                    adm.sa_adm_telefono_2,
                    adm.sa_adm_correo

                    FROM administrativos adm
                    WHERE adm.sa_adm_estado = 1";

        $sql .= " ORDER BY sa_adm_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function lista_administrativos($id = '')
    {
        $sql =
            "SELECT 
                    adm.sa_adm_id,
                    adm.sa_adm_primer_apellido,
                    adm.sa_adm_segundo_apellido,
                    adm.sa_adm_primer_nombre,
                    adm.sa_adm_segundo_nombre,
                    adm.sa_adm_cedula,
                    adm.sa_adm_sexo,
                    adm.sa_adm_fecha_nacimiento,
                    adm.sa_adm_telefono_1,
                    adm.sa_adm_telefono_2,
                    adm.sa_adm_correo,
                    adm.sa_adm_tabla,
                    adm.sa_adm_estado,
                    adm.sa_adm_fecha_creacion,
                    adm.sa_adm_fecha_modificacion

                    FROM administrativos adm
                    WHERE adm.sa_adm_estado = 1";

        if ($id) {
            $sql .= ' and sa_adm_id = ' . $id;
        }

        $sql .= " ORDER BY sa_adm_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_administrativos($buscar)
    {
        $sql = "SELECT 
                    adm.sa_adm_id,
                    adm.sa_adm_primer_apellido,
                    adm.sa_adm_segundo_apellido,
                    adm.sa_adm_primer_nombre,
                    adm.sa_adm_segundo_nombre,
                    adm.sa_adm_cedula,
                    adm.sa_adm_sexo,
                    adm.sa_adm_fecha_nacimiento,
                    adm.sa_adm_telefono_1,
                    adm.sa_adm_telefono_2,
                    adm.sa_adm_correo,
                    adm.sa_adm_tabla,
                    adm.sa_adm_estado,
                    adm.sa_adm_fecha_creacion,
                    adm.sa_adm_fecha_modificacion

                    FROM administrativos adm
                    WHERE adm.sa_adm_estado = 1

                    AND CONCAT(adm.sa_adm_primer_apellido, ' ', adm.sa_adm_segundo_apellido, ' ', 
                       adm.sa_adm_primer_nombre, ' ', adm.sa_adm_segundo_nombre, ' ',
                       adm.sa_adm_cedula, ' ',          
                       adm.sa_adm_correo) LIKE '%" . $buscar . "%'";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_administrativos_CEDULA($buscar)
    {
        $sql = "SELECT sa_adm_id, sa_adm_cedula, sa_adm_primer_apellido, sa_adm_primer_nombre FROM administrativos WHERE sa_adm_cedula = '" . $buscar . "'";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('administrativos', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('administrativos', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE administrativos SET sa_adm_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }
}
