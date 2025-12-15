<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_cargoM.php');

$controlador = new th_contr_plaza_cargoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['_id']));
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


class  th_contr_plaza_cargoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_plaza_cargoM();
    }

    function listar($id = '')
    {
      
        $datos = $this->modelo->listar_plaza_cargo($id);
        
        return $datos; 

    }

   function insertar_editar($parametros)
    {
        $toInt = function ($v) { return ($v === '' || $v === null) ? null : (int)$v; };
        $toFloat = function ($v) { return ($v === '' || $v === null) ? null : (float)$v; };
        $toBoolInt = function ($v) { return ($v === 1 || $v === '1' || $v === true || $v === 'true') ? 1 : 0; };

        $th_pla_id = $toInt($parametros['th_pla_id'] ?? $parametros['ddl_plaza'] ?? null);
        $th_car_id = $toInt($parametros['th_car_id'] ?? $parametros['ddl_cargo'] ?? null);

        $datos = array(
            array('campo' => 'th_pla_id', 'dato' => $th_pla_id),
            array('campo' => 'th_car_id', 'dato' => $th_car_id),
            array('campo' => 'th_pc_cantidad', 'dato' => $toInt($parametros['th_pc_cantidad'] ?? $parametros['txt_th_pc_cantidad'] ?? 1)),
            array('campo' => 'th_pc_salario_ofertado', 'dato' => $toFloat($parametros['th_pc_salario_ofertado'] ?? $parametros['txt_th_pc_salario_ofertado'] ?? null)),
            array('campo' => 'th_pc_estado', 'dato' => $toBoolInt($parametros['th_pc_estado'] ?? 1)),
            array('campo' => 'th_pc_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        // Inserción
        if (empty($parametros['txt_th_pc_id']) && empty($parametros['_id'])) {
            // Verificar si ya existe asignación activa para misma plaza+cargo
            $exists = $this->modelo
                ->where('th_pla_id', $th_pla_id)
                ->where('th_car_id', $th_car_id)
                ->where('th_pc_estado', 1)
                ->listar();

            if (count($exists) > 0) {
                return -2; // ya existe
            }

            $datos[] = array('campo' => 'th_pc_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
            $id = $this->modelo->insertar_id($datos);
            return ($id) ? $id : 0;
        } else {
            // Edición: obtener id desde txt_th_pc_id o _id
            $id_edit = $parametros['txt_th_pc_id'];

            $where[0]['campo'] = 'th_pc_id';
            $where[0]['dato']  = $id_edit;
            $this->modelo->editar($datos, $where);
            return $parametros['txt_th_pc_id'];
        }
    }

    function eliminar($id)
    {
        // Soft delete: marcar estado = 0
        $datos = array(
            array('campo' => 'th_pc_estado', 'dato' => 0),
            array('campo' => 'th_pc_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );
        $where[0]['campo'] = 'th_pc_id';
        $where[0]['dato'] = $id;
        $res = $this->modelo->editar($datos, $where);
        return $res;
    }

    //Para usar en select2
    function buscar($parametros)
    {

        
       
    }
}