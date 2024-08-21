<?php
include(dirname(__DIR__,2).'/db/db.php');
class crear_mienbrosM
{
    private $db;
    function __construct()
     {
        $this->db = new db();
     }
    
     function insertarnombre($param)
     {
        $sql = "insert into crear_mienbros (nombre) value ('".$param."')";
        //return 1;
        print_r($sql);die();
     }
     



}
?>