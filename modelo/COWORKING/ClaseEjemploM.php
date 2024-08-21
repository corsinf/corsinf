<?php
include(dirname(__DIR__, 2).'/db/db.php');
/**
 *
 */
class claseEjemploM{
    private $db;
    function __construct()
    {
        $thid -> db = new db();
    }   

    function insertarnombre($param){
        $sql = "insert into pasantes_prueba (nombre) value ('".$param."')";
        $resp = $this->db->sql_strind($sql,1);
        return $resp;
        //print_r($sql);die();
    }
}
?>