<?php

date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_triangularM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_triangular_itemM.php');
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_triangular_departamento_personaM.php');

$controlador = new th_triangularC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['lista_drop'])) {
    $q = '';
    if (isset($_GET['q'])) {
        $q = $_GET['q'];
    }
    echo json_encode($controlador->lista_triangular_drop($q));
}
if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}



class th_triangularC
{
    private $modelo;
    private $th_triangular_item;
    private $th_triangular_departamento_persona;

    function __construct()
    {
        $this->modelo = new th_triangularM();
        $this->th_triangular_item = new th_triangular_itemM();
        $this->th_triangular_departamento_persona = new th_triangular_departamento_personaM();
    }

    function listar($id = '')
    {
        if ($id == '') {
            if ($_SESSION['INICIO']['NO_CONCURENTE']) {
                // 1. Obtener los registros relacionados con el usuario actual
                $relaciones = $this->th_triangular_departamento_persona
                    ->where('th_per_id', $_SESSION['INICIO']['NO_CONCURENTE'])
                    ->where('th_tdp_estado', 1)
                    ->listar();

                // 2. Extraer los th_tri_id únicos de esas relaciones
                $ids_tri = array_column($relaciones, 'tri_id');

                // 3. Si hay IDs, buscar los registros completos desde la tabla principal
                $datos = [];
                if (!empty($ids_tri)) {
                    // Opcional: quitar duplicados por si acaso
                    $ids_tri = array_unique($ids_tri);

                    // 4. Obtener todos los triangulares activos
                    $triangulares = $this->modelo
                        ->where('th_tri_estado', 1)
                        ->listar();

                    // 5. Filtrar solo los que estén en el array de IDs obtenidos
                    foreach ($triangulares as $tri) {
                        if (in_array($tri['_id'], $ids_tri)) {
                            $datos[] = $tri;
                        }
                    }
                }
            } else {
                $datos = $this->modelo->where('th_tri_estado', 1)->listar();
            }
        } else {

            $datos = $this->modelo->where('th_tri_id', $id)->where('th_tri_estado', 1)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {



        $datos = array(
            array('campo' => 'th_tri_nombre', 'dato' => $parametros['txt_nombre'] ?? ''),
            array('campo' => 'th_tri_descripcion', 'dato' => $parametros['txt_descripcion_ubicacion'] ?? ''),
            array('campo' => 'usu_id', 'dato' => $_SESSION['INICIO']['ID_USUARIO'] ?? ''),
            array('campo' => 'th_tri_fecha_creacion', 'dato' => date('Y-m-d H:i:s') ?? null),
        );
        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_tri_nombre', $parametros['txt_nombre'])->where('th_tri_estado', 1)->listar()) == 0) {
                $id = $this->modelo->insertar_id($datos);

                if (!isset($parametros['puntos']) || !is_array($parametros['puntos'])) {
                    return ['estado' => 'error', 'mensaje' => 'No se recibieron puntos válidos'];
                }

                foreach ($parametros['puntos'] as $punto) {
                    if (isset($punto['lat']) && isset($punto['lng'])) {
                        $datos = array(
                            array('campo' => 'th_tri_id', 'dato' => $id),
                            array('campo' => 'th_itr_longitud', 'dato' => $punto['lat'] ?? ''),
                            array('campo' => 'th_itr_latitud', 'dato' => $punto['lng']  ?? ''),
                            array('campo' => 'th_itr_n_punto', 'dato' => $punto['punto'] ?? ''),
                            array('campo' => 'th_itr_fecha_creacion', 'dato' => date('Y-m-d H:i:s') ?? null),
                            // Agrega más campos si es necesario, como ID de zona, usuario, fecha, etc.
                        );

                        $datos = $this->th_triangular_item->insertar($datos);
                    }
                }

                if ($_SESSION['INICIO']['NO_CONCURENTE']) {
                    $datos = array(
                        array('campo' => 'th_tri_id', 'dato' => $id),
                        array('campo' => 'th_dep_id', 'dato' => 0),
                        array('campo' => 'th_per_id', 'dato' => $_SESSION['INICIO']['NO_CONCURENTE']),
                        array('campo' => 'th_tdp_estado', 'dato' => 1),

                        array('campo' => 'th_tdp_fecha_creacion', 'dato' => date('Y-m-d H:i:s') ?? null),
                        array('campo' => 'th_tdp_fecha_modificacion', 'dato' => date('Y-m-d H:i:s') ?? null),
                        // Agrega más campos si es necesario, como ID de zona, usuario, fecha, etc.
                    );

                    $datos = $this->th_triangular_departamento_persona->insertar($datos);
                }
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('th_tri_nombre', $parametros['txt_nombre'])->where('th_tri_id !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'th_tri_id';
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
            array('campo' => 'th_tri_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_tri_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    public function lista_triangular_drop($q)
    {
        $datos = $this->modelo->lista_triangular(false, $q);
        $datos2 = array();
        foreach ($datos as $value) {
            $datos2[] = array(
                'id' => $value['_id'],  // ⚠ asegúrate de usar '_id' si así lo aliaste
                'text' => $value['nombre']
            );
        }
        return $datos2;
    }
}
