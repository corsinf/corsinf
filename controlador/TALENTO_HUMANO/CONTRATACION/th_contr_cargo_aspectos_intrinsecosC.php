<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_aspectos_intrinsecosM.php');

$controlador = new th_contr_cargo_aspectos_intrinsecosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_aspecto_cargo'])) {
    echo json_encode($controlador->listar_aspecto_cargo($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros'] ?? []));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id'] ?? ''));
}

if (isset($_GET['buscar'])) {
    $query = '';
    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }
    $parametros = array('query' => $query);
    echo json_encode($controlador->buscar($parametros));
}


class th_contr_cargo_aspectos_intrinsecosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_cargo_aspectos_intrinsecosM();
    }

    function listar($id = '')
    {
       
        if ($id == '') {
            $datos = $this->modelo->listar(); // listar todos
        } else {
            $datos = $this->modelo->listar();
        }

        return $datos;
    }
    function listar_aspecto_cargo($id = '')
    {
       
        if ($id == '') {
            $datos = $this->modelo->listar(); // listar todos
        } else {
            $datos = $this->modelo->where('th_carasp_estado',1)->where('th_car_id',$id)->listar();
        }

        return $datos;
    }

    
    function insertar_editar($parametros)
    {
        // helpers
        $toInt = function ($v) { return ($v === '' || $v === null) ? null : (int)$v; };

        $th_car_id = $toInt($parametros['ddl_th_car_id'] ?? ($parametros['th_car_id'] ?? null));
        $nivel_cargo = $parametros['txt_th_carasp_nivel_cargo'] ?? ($parametros['th_carasp_nivel_cargo'] ?? null);
        $subordinacion = $parametros['txt_th_carasp_subordinacion'] ?? ($parametros['th_carasp_subordinacion'] ?? null);
        $supervision = $parametros['txt_th_carasp_supervision'] ?? ($parametros['th_carasp_supervision'] ?? null);
        $comunicaciones = $parametros['txt_th_carasp_comunicaciones_colaterales'] ?? ($parametros['th_carasp_comunicaciones_colaterales'] ?? null);

        // preparar datos comunes
        $datos = array(
            array('campo' => 'th_car_id', 'dato' => $th_car_id),
            array('campo' => 'th_carasp_nivel_cargo', 'dato' => $nivel_cargo),
            array('campo' => 'th_carasp_subordinacion', 'dato' => $subordinacion),
            array('campo' => 'th_carasp_supervision', 'dato' => $supervision),
            array('campo' => 'th_carasp_comunicaciones_colaterales', 'dato' => $comunicaciones),
            array('campo' => 'th_carasp_estado', 'dato' => isset($parametros['chk_th_carasp_estado']) ? ($parametros['chk_th_carasp_estado'] ? 1 : 0) : 1),
            array('campo' => 'th_carasp_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        // Inserción
        if (empty($parametros['_id'])) {
            // validar duplicado: por ejemplo no permitir dos aspectos activos con mismo cargo + mismo nivel
            $whereDup = $this->modelo->where('th_car_id', $th_car_id)
                                    ->where('th_carasp_nivel_cargo', $nivel_cargo)
                                    ->where('th_carasp_estado', 1)
                                    ->listar();

            if (count($whereDup) == 0) {
                // agregar fecha de creación
                $datos[] = array('campo' => 'th_carasp_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
                $id = $this->modelo->insertar_id($datos);
                // devolver id insertado si quieres; por coherencia con tu otro controlador devuelvo 1
                return 1;
            } else {
                return -2; // duplicado
            }
        } else {
            // Edición: verificar duplicado en otro id
            $dup = $this->modelo->where('th_car_id', $th_car_id)
                               ->where('th_carasp_nivel_cargo', $nivel_cargo)
                               ->where('th_carasp_id !', $parametros['_id'])
                               ->listar();

            if (count($dup) == 0) {
                $where[0]['campo'] = 'th_carasp_id';
                $where[0]['dato']  = $parametros['_id'];

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
            array('campo' => 'th_carasp_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_carasp_id';
        $where[0]['dato']  = $id;

        $res = $this->modelo->editar($datos, $where);
        return $res;
    }

   
    function buscar($parametros)
    {
        
    }
}