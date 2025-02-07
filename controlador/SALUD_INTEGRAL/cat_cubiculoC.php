<?php
include('../modelo/cat_cubiculoM.php');

$controlador = new cat_cubiculoC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_cubiculos($_POST['hora_inicio'], $_POST['hora_fin'], $_POST['fecha_disponible']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class cat_cubiculoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new cat_cubiculoM();
    }

    function lista_cubiculos($hora_incio, $hora_fin, $fecha_disponible)
    {
        $datos = $this->modelo->lista_cubiculos($hora_incio, $hora_fin, $fecha_disponible);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $horario_disponibleM = new horario_disponibleM();

        $datos = array(
            array('campo' => 'ac_horarioD_id', 'dato' => strval($parametros['ac_horarioD_id'])),
        );

        if ($parametros['ac_reunion_id'] == '') {
            $datos = $this->modelo->insertar($datos);
            $horario_disponibleM->turno_representanteM(strval($parametros['ac_horarioD_id']));
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = $this->modelo->eliminar($id);
        return $datos;
    }
}
