<?php
require_once(dirname(__DIR__,3).'/db/db.php');

class claseEjemploM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function insertarNombre($nombre)
    {
        $sql = "insert into pasantes_prueba (nombre) values ('".$nombre."')";
        $resp = $this->db->sql_string($sql);
        return $resp;
        // print_r($sql);die();
    }

    function listarBase()
    {
        $sql = 'select * from pasantes_prueba';
        $resp = $this->db->datos($sql);
        return $resp;
    }
}