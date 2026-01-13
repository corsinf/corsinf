<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_proteccion_datos_personaM.php');

$controlador = new th_proteccion_datos_personaC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class th_proteccion_datos_personaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_proteccion_datos_personaM();
    }

    function listar($id = '')
    {
        return $this->modelo->listar_personas_proteccion_datos($id);
    }


    function insertar_editar($parametros)
    {
        $datos = [
            ['campo' => 'th_per_id', 'dato' => $parametros['th_per_id']],
            ['campo' => 'th_prod_rol', 'dato' => $parametros['th_prod_rol']],
            ['campo' => 'th_prod_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
        ];

        if ($parametros['_id'] == '') {

            $existe_persona = $this->modelo
                ->where('th_per_id', $parametros['th_per_id'])
                ->where('th_prod_estado', 1)
                ->listar();

            if (count($existe_persona) > 0) {
                return -2;
            }

            $existe_rol = $this->modelo
                ->where('th_prod_rol', $parametros['th_prod_rol'])
                ->where('th_prod_estado', 1)
                ->listar();

            if (count($existe_rol) > 0) {
                return -3;
            }


            $datos[] = ['campo' => 'th_prod_estado', 'dato' => 1];
            $datos[] = ['campo' => 'th_prod_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];

            $datos = $this->modelo->insertar_id($datos);

            return $datos ? 1 : -2;
        }

        $where[] = [
            'campo' => 'th_prod_id',
            'dato'  => intval($parametros['_id'])
        ];

        $datos = $this->modelo->editar($datos, $where);
        return $datos ? 1 : -2;
    }

    function eliminar($id)
    {
        $datos = [
            ['campo' => 'th_prod_id', 'dato' => $id]
        ];
        return $this->modelo->eliminar($datos);
    }
}
