<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_competenciasM.php');

$controlador = new th_contr_competenciasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros'] ?? []));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id'] ?? 0));
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


class th_contr_competenciasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_competenciasM();
    }

    function listar($id = '')
    {
        if ($id == "") {
            // Retornar la competencia específica
            $datos = $this->modelo->where('th_comp_estado', 1)->listar();
        }else{
            $datos = $this->modelo->where('th_comp_estado', 1)->where('th_comp_id', $id)->listar();
        }

        // Por defecto listar solo activas
       
        return $datos;
    }

    function insertar_editar($parametros)
    {
        // Normalizadores rápidos
        $toInt = function ($v) { return ($v === '' || $v === null) ? null : (int)$v; };
        $toBool = function ($v) { return isset($v) && ($v === '1' || $v === 1 || $v === true || $v === 'true' || $v === 'on'); };

        // Preparar datos
        $datos = array(
            array('campo' => 'th_comp_codigo', 'dato' => trim($parametros['txt_th_comp_codigo'] ?? '')),
            array('campo' => 'th_comp_nombre', 'dato' => trim($parametros['txt_th_comp_nombre'] ?? '')),
            array('campo' => 'th_comp_categoria', 'dato' => trim($parametros['ddl_th_comp_categoria'] ?? '')),
            array('campo' => 'th_comp_tipo_disc', 'dato' => trim($parametros['ddl_th_comp_tipo_disc'] ?? '')),
            array('campo' => 'th_comp_descripcion', 'dato' => $parametros['txt_th_comp_descripcion'] ?? ''),
            array('campo' => 'th_comp_definicion_completa', 'dato' => $parametros['txt_th_comp_definicion_completa'] ?? ''),
            array('campo' => 'th_comp_comportamientos_esperados', 'dato' => $parametros['txt_th_comp_comportamientos_esperados'] ?? ''),
            array('campo' => 'th_comp_es_disc', 'dato' => $toBool($parametros['chk_th_comp_es_disc']) ? 1 : 0),
            array('campo' => 'th_comp_estado', 'dato' => 1),
            array('campo' => 'th_comp_fecha_modificacion', 'dato' => date('Y-m-d H:i:s'))
        );

        // Inserción
        if (empty($parametros['_id'])) {
            // validar duplicado por nombre (y por código si lo deseas)
            $existingByName = $this->modelo->where('th_comp_nombre', $parametros['txt_th_comp_nombre'] ?? '')->where('th_comp_estado', 1)->listar();
            if (count($existingByName) == 0) {
                // fecha creación
                $datos[] = array('campo' => 'th_comp_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));

                // insertar y devolver id si se requiere
                $id = $this->modelo->insertar_id($datos);

                // devolver 1 = éxito (coherente con tu front)
                return 1;
            } else {
                return -2; // nombre duplicado
            }
        } else {
            // Edición: validar duplicado en otro registro
            $existing = $this->modelo->where('th_comp_nombre', $parametros['txt_th_comp_nombre'] ?? '')->where('th_comp_id !', $parametros['_id'])->listar();
            if (count($existing) == 0) {
                $where[0]['campo'] = 'th_comp_id';
                $where[0]['dato'] = $parametros['_id'];

                $res = $this->modelo->editar($datos, $where);
                return $res;
            } else {
                return -2; // duplicado en otro registro
            }
        }
    }

    function eliminar($id)
    {
         $datos = array(
            array('campo' => 'th_comp_estado', 'dato' => 0),
            array('campo' => 'th_comp_fecha_modificacion', 'dato' => date('Y-m-d H:i:s'))
        );

        $where[0]['campo'] = 'th_comp_id';
        $where[0]['dato'] = $id;

        $res = $this->modelo->editar($datos, $where);
        return $res;
    }

    function buscar($parametros)
    {
        $lista = array();
        $query = $parametros['query'] ?? '';

        // Buscamos por nombre y código (ajusta el concat según tu BaseModel->like)
        $concat = "th_comp_nombre, th_comp_codigo";
        $datos = $this->modelo->where('th_comp_estado', 1)->like($concat, $query);

        foreach ($datos as $key => $value) {
            $text = $value['th_comp_nombre'] . (!empty($value['th_comp_codigo']) ? ' (' . $value['th_comp_codigo'] . ')' : '');
            $lista[] = array('id' => ($value['th_comp_id']), 'text' => ($text));
        }

        return $lista;
    }
}