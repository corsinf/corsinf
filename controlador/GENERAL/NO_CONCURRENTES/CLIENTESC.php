<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/GENERAL/NO_CONCURRENTES/CLIENTESM.php');


$controlador = new CLIENTESC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar_clientes'])) {
    $parametros = array('query' => $_GET['q'] ?? '');
    echo json_encode($controlador->buscar_clientes_select2($parametros));
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

class CLIENTESC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new CLIENTESM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->where('DELETE_LOGIC', 0)->listar();
        } else {
            $datos = $this->modelo->where('id_visitantes', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_per_id',            'dato' => $parametros['th_per_id']),
            array('campo' => 'PERFIL',               'dato' => $parametros['txt_perfil']),
            array('campo' => 'NICK',                 'dato' => $parametros['txt_nick']),
            array('campo' => 'POLITICAS_ACEPTACION', 'dato' => $parametros['txt_politicas']),
        );

        if ($parametros['_id'] == '') {
            // Validar NICK duplicado al insertar
            if (count($this->modelo->where('NICK', $parametros['txt_nick'])
                ->where('DELETE_LOGIC', 0)->listar()) == 0) {

                $datos[] = array('campo' => 'PASS',         'dato' => password_hash($parametros['txt_pass'], PASSWORD_BCRYPT));
                $datos[] = array('campo' => 'DELETE_LOGIC', 'dato' => 0);

                $datos = $this->modelo->insertar($datos);
            } else {
                return -2; // NICK ya existe
            }
        } else {
            // Validar NICK duplicado al editar (excluyendo el registro actual)
            if (count($this->modelo->where('NICK', $parametros['txt_nick'])
                ->where('id_visitantes !', $parametros['_id'])
                ->where('DELETE_LOGIC', 0)->listar()) == 0) {

                // Solo actualizar PASS si se envió una nueva
                if (!empty($parametros['txt_pass'])) {
                    $datos[] = array('campo' => 'PASS', 'dato' => password_hash($parametros['txt_pass'], PASSWORD_BCRYPT));
                }

                $where[0]['campo'] = 'id_visitantes';
                $where[0]['dato']  = $parametros['_id'];

                $datos = $this->modelo->editar($datos, $where);
            } else {
                return -2; // NICK ya existe
            }
        }

        return $datos;
    }

    public function buscar_clientes_select2($parametros)
    {
        $query = $parametros['query'] ?? '';
        $datos = $this->modelo->buscar_clientes($query);
        $lista = array();

        foreach ($datos as $value) {
            // Formato solicitado: Cédula - Nombre Completo
            $text = $value['th_per_cedula'] . ' - ' . $value['th_per_nombres_completos'];

            $lista[] = array(
                'id'    => $value['th_per_id'], // Se retorna la cédula en el value
                'text'  => $text,
                'telefono' => $value['th_per_telefono_1'],
                'correo'   => $value['th_per_correo']
            );
        }

        return $lista;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'DELETE_LOGIC', 'dato' => 1),
        );

        $where[0]['campo'] = 'id_visitantes';
        $where[0]['dato']  = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    // Para usar en select2
    function buscar($parametros)
    {
        $lista  = array();
        $concat = "NICK, PERFIL";
        $datos  = $this->modelo->where('DELETE_LOGIC', 0)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text    = $value['NICK'];
            $lista[] = array('id' => ($value['id_visitantes']), 'text' => ($text));
        }

        return $lista;
    }
}
