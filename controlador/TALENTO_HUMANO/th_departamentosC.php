<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_departamentosM.php');

$controlador = new th_departamentosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
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
if (isset($_GET['buscar_departamento'])) {
    $query = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar_departamento($parametros));
}


class th_departamentosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_departamentosM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->listar_departamentos_contar_personas();
        } else {
            $datos = $this->modelo->where('th_dep_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_dep_nombre', 'dato' => $parametros['txt_nombre']),
            // array('campo' => 'th_dep_desactivar_ADE', 'dato' => $parametros['txt_desactivar_ADE']),
            // array('campo' => 'th_dep_contingencia', 'dato' => $parametros['txt_contingencia']),
            // array('campo' => 'th_dep_tiempo_maximo_dentro', 'dato' => $parametros['txt_tiempo_maximo_dentro']),
            // array('campo' => 'th_dept_id', 'dato' => $parametros['txt_tipo_id']),

            array('campo' => 'th_dep_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_dep_nombre', $parametros['txt_nombre'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('th_dep_nombre', $parametros['txt_nombre'])->where('th_dep_id !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'th_dep_id';
                $where[0]['dato'] = $parametros['_id'];
                $datos = $this->modelo->editar($datos, $where);
            } else {
                return -2;
            }
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_dep_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_dep_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    //Para usar en select2
    function buscar($parametros)
    {
        $lista = array();
        $concat = "th_dep_nombre, th_dep_estado";
        $datos = $this->modelo->where('th_dep_estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['th_dep_nombre'];
            $lista[] = array('id' => ($value['th_dep_id']), 'text' => ($text), /* 'data' => $value */);
        }

        return $lista;
    }

    public function buscar_departamento($parametros)
    {
        $lista = [];

        $concat = "th_dep_nombre, th_dep_estado";
        $datos = $this->modelo->where('th_dep_estado', 1)->like($concat, $parametros['query']);

        // 🔹 Agregar al inicio la opción "Todos los departamentos"
        $lista[] = [
            'id' => '0',
            'text' => 'Todos los departamentos'
        ];

        // 🔹 Recorrer resultados reales
        foreach ($datos as $key => $value) {
            $text = $value['th_dep_nombre'];
            $lista[] = [
                'id'   => $value['th_dep_nombre'], // aquí estás usando el nombre como ID, puedes cambiarlo a $value['th_dep_id'] si prefieres
                'text' => $text
            ];
        }

        return $lista;
    }
}
