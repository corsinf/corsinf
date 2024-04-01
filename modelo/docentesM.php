<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class docentesM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_docentes_todo()
    {
        $sql =
            "SELECT 
                    doc.sa_doc_id,
                    doc.sa_doc_primer_apellido,
                    doc.sa_doc_segundo_apellido,
                    doc.sa_doc_primer_nombre,
                    doc.sa_doc_segundo_nombre,
                    doc.sa_doc_cedula,
                    doc.sa_doc_fecha_nacimiento,
                    doc.sa_doc_telefono_1,
                    doc.sa_doc_telefono_2,
                    doc.sa_doc_correo

                    FROM docentes doc
                    WHERE doc.sa_doc_estado = 1";

        $sql .= " ORDER BY sa_doc_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function lista_docentes($id = '')
    {
        $sql =
            "SELECT 
                    doc.sa_doc_id,
                    doc.sa_doc_primer_apellido,
                    doc.sa_doc_segundo_apellido,
                    doc.sa_doc_primer_nombre,
                    doc.sa_doc_segundo_nombre,
                    doc.sa_doc_cedula,
                    doc.sa_doc_sexo,
                    doc.sa_doc_fecha_nacimiento,
                    doc.sa_doc_telefono_1,
                    doc.sa_doc_telefono_2,
                    doc.sa_doc_correo,
                    doc.sa_doc_tabla,
                    doc.sa_doc_estado,
                    doc.sa_doc_fecha_creacion,
                    doc.sa_doc_fecha_modificacion

                    FROM docentes doc
                    WHERE doc.sa_doc_estado = 1";

        if ($id) {
            $sql .= ' and sa_doc_id = ' . $id;
        }

        $sql .= " ORDER BY sa_doc_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_docentes($buscar)
    {
        $sql = "SELECT 
                    doc.sa_doc_id,
                    doc.sa_doc_primer_apellido,
                    doc.sa_doc_segundo_apellido,
                    doc.sa_doc_primer_nombre,
                    doc.sa_doc_segundo_nombre,
                    doc.sa_doc_cedula,
                    doc.sa_doc_sexo,
                    doc.sa_doc_fecha_nacimiento,
                    doc.sa_doc_telefono_1,
                    doc.sa_doc_telefono_2,
                    doc.sa_doc_correo,
                    doc.sa_doc_tabla,
                    doc.sa_doc_estado,
                    doc.sa_doc_fecha_creacion,
                    doc.sa_doc_fecha_modificacion

                    FROM docentes doc
                    WHERE doc.sa_doc_estado = 1

                    AND CONCAT(doc.sa_doc_primer_apellido, ' ', doc.sa_doc_segundo_apellido, ' ', 
                       doc.sa_doc_primer_nombre, ' ', doc.sa_doc_segundo_nombre, ' ',
                       doc.sa_doc_cedula, ' ',          
                       doc.sa_doc_correo) LIKE '%" . $buscar . "%'";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_docentes_CEDULA($buscar)
    {
        $sql = "SELECT sa_doc_id, sa_doc_cedula, sa_doc_primer_apellido, sa_doc_primer_nombre FROM docentes WHERE sa_doc_cedula = '" . $buscar . "'";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('docentes', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('docentes', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE docentes SET sa_doc_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }


}
