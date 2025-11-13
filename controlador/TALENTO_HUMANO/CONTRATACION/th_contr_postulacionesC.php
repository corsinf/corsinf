<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_postulacionesM.php');

$controlador = new th_contr_postulacionesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_plaza_postulados'])) {
    echo json_encode($controlador->listar_plaza_postulados($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $query = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}


class  th_contr_postulacionesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_postulacionesM();
    }

    function listar($id = '')
    {
       
        if($id == ''){

            $datos = $this->modelo->where('th_posu_estado', 1)->modelo->listar();

        }else{
             $datos = $this->modelo->where('th_posu_id',$id)->where('th_posu_estado', 1)->listar();
        }
       

        return $datos; 

    }
    function listar_plaza_postulados($id = '')
    {
        $datos = $this->modelo->listar_postulaciones_por_plaza($id);
        return $datos; 

    }

    function insertar_editar($parametros)
    {

       
    }

    function eliminar($id)
    {
        
    }

    //Para usar en select2
    function buscar($parametros)
    {

        
       
    }
}