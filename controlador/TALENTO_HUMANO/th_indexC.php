<?php


require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_personas_departamentosM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_programar_horariosM.php');


$controlador = new th_indexC();

if (isset($_GET['notificaciones_asistencia'])) {
    // Llamar al método imprimirPDF
    echo json_encode($controlador->notificaciones_asistencias());
    exit; // Importante: terminar la ejecución después de enviar la respuesta JSON
}

class th_indexC
{
    private $persona_departamento;
    private $th_programar_horarios;

    function __construct()
    {
        $this->persona_departamento = new th_personas_departamentosM();
        $this->th_programar_horarios = new th_programar_horariosM();

    }

    function notificaciones_asistencias(){
        print_r($_SESSION['INICIO']);
        die();
    }

}