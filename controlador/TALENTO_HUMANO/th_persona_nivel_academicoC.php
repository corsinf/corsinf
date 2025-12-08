<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_persona_nivel_academicoM.php');

$controlador = new th_persona_nivel_academicoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}
if (isset($_GET['listar_persona_nivel_academico'])) {
    echo json_encode($controlador->listar_persona_nivel_academico($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
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
    $parametros = array('query' => $query);
    echo json_encode($controlador->buscar($parametros));
}


class th_persona_nivel_academicoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_persona_nivel_academicoM();
    }

    function listar($id = '')
    {
        // Si se pasa un id numérico asumimos que es th_per_id y devolvemos los niveles de esa persona
        if (!empty($id)) {
            // si el id parece un registro de nivel (pna id) puedes cambiar la lógica; aquí devolvemos por persona
            $datos = $this->modelo->where('th_niv_aca_estado', 1);
        } else {
            // listar todos activos
            $datos = $this->modelo->where('th_niv_aca_estado', 1)->listar();
        }

        return $datos;
    }
    function listar_persona_nivel_academico($id = '')
    {
       
        $datos = $this->modelo->where('th_per_id',$id)->where('th_niv_aca_estado', 1)->listar();

        return $datos;
    }
    function insertar_editar($parametros)
    {
        try {
            $th_per_id = isset($parametros['th_per_id']) ? $parametros['th_per_id'] : ($parametros['txt_th_per_id'] ?? '');
            $tipo_nivel = $parametros['pna_tipo_nivel'] ?? $parametros['th_niv_aca_tipo_nivel'] ?? '';
            $nivel_academico = $parametros['pna_nivel_academico'] ?? $parametros['th_niv_aca_nivel_academico'] ?? '';
            $titulo = $parametros['pna_titulo'] ?? $parametros['th_niv_aca_titulo'] ?? '';
            $registro_senescyt = $parametros['pna_registro_senescyt'] ?? $parametros['th_niv_aca_registro_senescyt'] ?? '';

            // Preparar datos para insertar/editar
            $datos = array(
                array('campo' => 'th_per_id', 'dato' => $th_per_id),
                array('campo' => 'th_niv_aca_tipo_nivel', 'dato' => $tipo_nivel),
                array('campo' => 'th_niv_aca_nivel_academico', 'dato' => $nivel_academico),
                array('campo' => 'th_niv_aca_titulo', 'dato' => $titulo),
                array('campo' => 'th_niv_aca_registro_senescyt', 'dato' => $registro_senescyt),
                array('campo' => 'th_niv_aca_estado', 'dato' => 1),
                array('campo' => 'th_niv_aca_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
            );

            // INSERTAR
            if (empty($parametros['_id'])) {
                // Verificar duplicados: misma persona y mismo título (puedes ajustar la regla a tu necesidad)
                $existe = $this->modelo
                    ->where('th_per_id', $th_per_id)
                    ->where('th_niv_aca_titulo', $titulo)
                    ->where('th_niv_aca_estado', 1)
                    ->listar();

                if (count($existe) == 0) {
                    $datos[] = array('campo' => 'th_niv_aca_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
                    // insertar y devolver 1 (coherente con tu frontend)
                    $id = $this->modelo->insertar_id($datos);
                    return 1;
                } else {
                    return -2; // duplicado
                }
            } else {
                // EDITAR
                $existe = $this->modelo
                    ->where('th_per_id', $th_per_id)
                    ->where('th_niv_aca_titulo', $titulo)
                    ->where('th_niv_aca_id !', $parametros['_id'])
                    ->where('th_niv_aca_estado', 1)
                    ->listar();

                if (count($existe) == 0) {
                    $where[0]['campo'] = 'th_niv_aca_id';
                    $where[0]['dato'] = $parametros['_id'];

                    $res = $this->modelo->editar($datos, $where);
                    return $res;
                } else {
                    return -2; // duplicado en otro registro
                }
            }
        } catch (Exception $e) {
            error_log("Error en insertar_editar th_persona_nivel_academicoC: " . $e->getMessage());
            return -1;
        }
    }
 
    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_niv_aca_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_niv_aca_id';
        $where[0]['dato'] = $id;

        $res = $this->modelo->editar($datos, $where);
        return $res;
    }

    function buscar($parametros)
    {
        $lista = array();
        $query = $parametros['query'] ?? '';

        // concatenamos campos para búsqueda (nivel + título)
        $concat = "th_niv_aca_nivel_academico, th_niv_aca_titulo";
        $datos = $this->modelo->where('th_niv_aca_estado', 1)->like($concat, $query);

        foreach ($datos as $key => $value) {
            $text = trim($value['th_niv_aca_titulo'] . ' - ' . $value['th_niv_aca_nivel_academico']);
            $lista[] = array('id' => ($value['th_niv_aca_id']), 'text' => ($text));
        }

        return $lista;
    }
}