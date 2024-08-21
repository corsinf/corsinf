<?php
require_once(dirname(__DIR__,2).'/db/db.php');


/**
 *
 */
class claseEjemploM{
    private $db;
    function __construct()
    {
        $this->db = new db();
    }

    function insertarnombre($param){
        $sql = "insert into pasantes_prueba (nombre) value ('".$param."')";
        $resp = $this->db->sql_string($sql);
        return $resp;
        //print_r($sql);die();
    }

    function listardebase(){

    }
}
?>