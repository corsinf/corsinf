<?php
include(dirname(__DIR__, 2) . '/db/db.php');

class paciente_datos_adicionalesM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function listar_ultimo($id = '')
    {
        $sql =
            "SELECT *
            FROM paciente_datos_adicionales
            WHERE sa_pacda_estado = 1";

        if ($id) {
            $sql .= ' AND sa_pac_id = ' . $id;
        }

        $sql .= " ORDER BY sa_pacda_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function listar_paciente($id = '', $ultimo = false)
    {
        $ultimo_add = '';
        if ($ultimo) {
            $ultimo_add = ' TOP 1 ';
        }

        $sql =
            "SELECT $ultimo_add *
            FROM paciente_datos_adicionales
            WHERE sa_pacda_estado = 1";

        if ($id) {
            $sql .= ' AND sa_pac_id = ' . $id;
        }

        $sql .= " ORDER BY sa_pacda_id DESC;";

        //print_r($sql); exit();
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('paciente_datos_adicionales', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('paciente_datos_adicionales', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE paciente_datos_adicionales SET sa_pacda_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }
}
