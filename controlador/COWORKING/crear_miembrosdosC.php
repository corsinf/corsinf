<?php
include(dirname(__DIR__, 2).'/COWORKING/ClaseEjemploM.php');
include('path/to/ClaseEjemploM.php'); // Asegúrate de incluir la clase que contiene listardebase()

$ejemplo = new claseEjemploM();
$espacios = $ejemplo->listardebase();
?>