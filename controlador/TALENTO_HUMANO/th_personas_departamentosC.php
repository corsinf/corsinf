<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_personas_departamentosM.php');

$controlador = new th_personas_departamentosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_personas_modal'])) {
    echo json_encode($controlador->listar_personas_modal($_POST['id']));
}


class th_personas_departamentosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_personas_departamentosM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            //$datos = $this->modelo->where('th_dep_estado', 1)->listar();
        } else {
            //Busqueda por departamento
            $datos = $this->modelo->listar_personas_departamentos($id);
            return $datos;
        }
    }

    function listar_personas_modal($id_departamento = '')
    {
        $datos = $this->modelo->listar_personas_modal($id_departamento);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $salida = '';
        if ($parametros['_id'] != '') {
            if (isset($parametros['personas_seleccionadas'])) {

                foreach ($parametros['personas_seleccionadas'] as $persona_id) {
                    // Crear el array $datos para cada persona seleccionada
                    $datos = array(
                        array('campo' => 'th_per_id', 'dato' => $persona_id), // Usar el ID de la persona seleccionada
                        array('campo' => 'th_dep_id', 'dato' => $parametros['_id']),
                        array('campo' => 'th_perdep_visitor', 'dato' => $parametros['txt_visitor']),
                    );

                    $contar_personas = count($this->modelo->where('th_per_id', $persona_id)->where('th_dep_id', $parametros['_id'])->listar());
                    if ($contar_personas == 0) {
                        $salida .= $persona_id . ' - ' . $contar_personas . '<br>';
                        $datos = $this->modelo->insertar($datos);
                    }
                    
                    $this->modelo->reset();
                }
            }else{
                return -2;
            }

            return 1;
        }
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_perdep_id', 'dato' => $id),
        );

        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
