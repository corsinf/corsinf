<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_competencias_detalleM.php');

$controlador = new th_contr_cargo_competencias_detalleC();

if (isset($_GET['listar'])) {
    // aceptar id (detalle) o carcomp (th_carcomp_id) para listar por asignación
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_competencia_detalle'])) {
    // aceptar id (detalle) o carcomp (th_carcomp_id) para listar por asignación
    echo json_encode($controlador->listar_competencia_detalle($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros'] ?? []));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id'] ?? 0));
}

if (isset($_GET['buscar'])) {
    $query = $_GET['q'] ?? '';
    echo json_encode($controlador->buscar(['query' => $query]));
}


class th_contr_cargo_competencias_detalleC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_cargo_competencias_detalleM();
    }
  
    function listar($id = '')
    {
        if ($id == "") {
            $datos =$this->modelo->where('th_carcompdet_estado',1)->listar();
        }else{
            $datos = $this->modelo->where('th_carcompdet_estado',1)->where('th_carcompdet_id',$id)->listar();
        }

       
        return $datos;
    }
    function listar_competencia_detalle($id = '')
    {
            $datos = $this->modelo->where('th_carcomp_id',$id)->where('th_carcompdet_estado',1)->listar();
        return $datos;
    }

    function insertar_editar($parametros)
    {
        // normalizadores
        $toInt = function ($v) { return ($v === '' || $v === null) ? null : (int)$v; };
        $toBool = function ($v) { return isset($v) && ($v === '1' || $v === 1 || $v === true || $v === 'true' || $v === 'on'); };

        // preparar datos
        $datos = array(
            array('campo' => 'th_carcomp_id', 'dato' => $toInt($parametros['th_carcomp_id'] ?? $parametros['th_carcomp_id'] ?? null)),
            array('campo' => 'th_carcompdet_subcompetencia', 'dato' => trim($parametros['th_carcompdet_subcompetencia'] ?? '')),
            array('campo' => 'th_carcompdet_descripcion', 'dato' => $parametros['th_carcompdet_descripcion'] ?? ''),
            array('campo' => 'th_carcompdet_nivel_utilizacion', 'dato' => $toInt($parametros['th_carcompdet_nivel_utilizacion'] ?? null)),
            array('campo' => 'th_carcompdet_nivel_contribucion', 'dato' => $toInt($parametros['th_carcompdet_nivel_contribucion'] ?? null)),
            array('campo' => 'th_carcompdet_nivel_habilidad', 'dato' => $toInt($parametros['th_carcompdet_nivel_habilidad'] ?? null)),
            array('campo' => 'th_carcompdet_nivel_maestria', 'dato' => $toInt($parametros['th_carcompdet_nivel_maestria'] ?? null)),
            array('campo' => 'th_carcompdet_indicador_medicion', 'dato' => $parametros['th_carcompdet_indicador_medicion'] ?? ''),
            array('campo' => 'th_carcompdet_comportamientos_observables', 'dato' => $parametros['th_carcompdet_comportamientos_observables'] ?? ''),
            array('campo' => 'th_carcompdet_orden', 'dato' => $toInt($parametros['th_carcompdet_orden'] ?? null)),
            array('campo' => 'th_carcompdet_estado', 'dato' => 1),
            array('campo' => 'th_carcompdet_fecha_modificacion', 'dato' => date('Y-m-d H:i:s'))
        );

        // Inserción
        if (empty($parametros['_id'])) {
            // validar duplicado de subcompetencia para la misma asignación (th_carcomp_id)
            $sub = trim($parametros['th_carcompdet_subcompetencia'] ?? '');
            $carcomp = $parametros['th_carcomp_id'] ?? null;
            if ($sub !== '') {
                $existing = $this->modelo->where('th_carcomp_id', (int)$carcomp)->where('th_carcompdet_subcompetencia', $sub)->where('th_carcompdet_estado', 1)->listar();
                if (count($existing) > 0) return -2; // duplicado
            }

            // fecha creación
            $datos[] = array('campo' => 'th_carcompdet_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));

            // insertar y devolver id si es necesario
            $id = $this->modelo->insertar_id($datos);
            return 1;
        } else {
            // edición: validar duplicado en otro registro con mismo nombre y mismo th_carcomp_id
            $sub = trim($parametros['th_carcompdet_subcompetencia'] ?? '');
            $carcomp = $parametros['th_carcomp_id'] ?? null;
            if ($sub !== '') {
                $existing = $this->modelo->where('th_carcomp_id', (int)$carcomp)->where('th_carcompdet_subcompetencia', $sub)->where('th_carcompdet_id !', $parametros['_id'])->listar();
                if (count($existing) > 0) return -2; // duplicado
            }

            $where[0]['campo'] = 'th_carcompdet_id';
            $where[0]['dato'] = $parametros['_id'];

            $res = $this->modelo->editar($datos, $where);
            return $res;
        }
    }
   
    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_carcompdet_estado', 'dato' => 0),
            array('campo' => 'th_carcompdet_fecha_modificacion', 'dato' => date('Y-m-d H:i:s'))
        );

        $where[0]['campo'] = 'th_carcompdet_id';
        $where[0]['dato'] = $id;

        $res = $this->modelo->editar($datos, $where);
        return $res;
    }
    function buscar($parametros)
    {
        $lista = array();
        $query = $parametros['query'] ?? '';

        // buscar por subcompetencia
        $concat = "th_carcompdet_subcompetencia";
        $datos = $this->modelo->where('th_carcompdet_estado', 1)->like($concat, $query);

        foreach ($datos as $value) {
            $lista[] = array('id' => $value['th_carcompdet_id'], 'text' => $value['th_carcompdet_subcompetencia']);
        }

        return $lista;
    }
}