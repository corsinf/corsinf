<?php
require_once(dirname(__DIR__, 3) . '/db/db.php');


class formularios_firmasM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function listar()
    {
        $sql = "SELECT * FROM fir_solicitudes;";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('fir_solicitudes', $datos);
        return $rest;
    }
}
