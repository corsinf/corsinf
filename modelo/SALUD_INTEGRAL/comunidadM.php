<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class comunidadM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_comunidad_todo()
    {
        $sql =
            "SELECT 
                    com.sa_com_id,
                    com.sa_com_primer_apellido,
                    com.sa_com_segundo_apellido,
                    com.sa_com_primer_nombre,
                    com.sa_com_segundo_nombre,
                    com.sa_com_cedula,
                    com.sa_com_fecha_nacimiento,
                    com.sa_com_telefono_1,
                    com.sa_com_telefono_2,
                    com.sa_com_correo

                    FROM comunidad com
                    WHERE com.sa_com_estado = 1";

        $sql .= " ORDER BY sa_com_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function lista_comunidad($id = '')
    {
        $sql =
            "SELECT 
                    com.sa_com_id,
                    com.sa_com_primer_apellido,
                    com.sa_com_segundo_apellido,
                    com.sa_com_primer_nombre,
                    com.sa_com_segundo_nombre,
                    com.sa_com_cedula,
                    com.sa_com_sexo,
                    com.sa_com_fecha_nacimiento,
                    com.sa_com_telefono_1,
                    com.sa_com_telefono_2,
                    com.sa_com_correo,
                    com.sa_com_tabla,
                    com.sa_com_estado,
                    com.sa_com_fecha_creacion,
                    com.sa_com_fecha_modificacion

                    FROM comunidad com
                    WHERE com.sa_com_estado = 1";

        if ($id) {
            $sql .= ' and sa_com_id = ' . $id;
        }

        $sql .= " ORDER BY sa_com_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_comunidad($buscar)
    {
        $sql = "SELECT 
                    com.sa_com_id,
                    com.sa_com_primer_apellido,
                    com.sa_com_segundo_apellido,
                    com.sa_com_primer_nombre,
                    com.sa_com_segundo_nombre,
                    com.sa_com_cedula,
                    com.sa_com_sexo,
                    com.sa_com_fecha_nacimiento,
                    com.sa_com_telefono_1,
                    com.sa_com_telefono_2,
                    com.sa_com_correo,
                    com.sa_com_tabla,
                    com.sa_com_estado,
                    com.sa_com_fecha_creacion,
                    com.sa_com_fecha_modificacion

                    FROM comunidad com
                    WHERE com.sa_com_estado = 1

                    AND CONCAT(com.sa_com_primer_apellido, ' ', com.sa_com_segundo_apellido, ' ', 
                       com.sa_com_primer_nombre, ' ', com.sa_com_segundo_nombre, ' ',
                       com.sa_com_cedula, ' ',          
                       com.sa_com_correo) LIKE '%" . $buscar . "%'";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_comunidad_CEDULA($buscar)
    {
        $sql = "SELECT sa_com_id, sa_com_cedula, sa_com_primer_apellido, sa_com_primer_nombre FROM comunidad WHERE sa_com_cedula = '" . $buscar . "'";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('comunidad', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('comunidad', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE comunidad SET sa_com_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }


}
