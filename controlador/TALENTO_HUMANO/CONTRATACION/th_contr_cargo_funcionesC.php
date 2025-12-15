<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_cargo_funcionesM.php');

$controlador = new th_contr_cargo_funcionesC();

// === RUTAS ACCIONADAS POR AJAX === //
if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar($_GET['q'] ?? ''));
}

class th_contr_cargo_funcionesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_cargo_funcionesM();
    }

    // ========= LISTAR ========= //
    function listar($car_id)
    {
        if (!$car_id) return [];

        $result = $this->modelo->where('th_car_id', $car_id)
                               ->where('th_carfun_estado', 1)
                               ->listar();
        
        return $result ? $result : [];
    }

    // ========= INSERTAR / EDITAR ========= //
    function insertar_editar($parametros)
    {

        
        $datos = [
            ['campo' => 'th_car_id', 'dato' => $parametros['th_car_id']],
            ['campo' => 'th_carfun_nombre', 'dato' => $parametros['nombre']],
            ['campo' => 'th_carfun_descripcion', 'dato' => $parametros['descripcion'] ?? null],
            ['campo' => 'th_carfun_frecuencia', 'dato' => $parametros['frecuencia'] ?? null],
            ['campo' => 'th_carfun_porcentaje_tiempo', 'dato' => floatval($parametros['porcentaje_tiempo'] ?? 0)],
            ['campo' => 'th_carfun_es_principal', 'dato' => intval($parametros['es_principal'] ?? 0)],
            ['campo' => 'th_carfun_orden', 'dato' => intval($parametros['orden'] ?? 1)],
            ['campo' => 'th_carfun_estado', 'dato' => 1],
            ['campo' => 'th_carfun_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
        ];

        if (empty($parametros['_id'])) {
            

            $datos[] = ['campo' => 'th_carfun_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];

            $resultado = $this->modelo->insertar($datos);
            return $resultado ? 1 : 0;
        }else{
            
        $where = [
            ['campo' => 'th_carfun_id', 'dato' => $parametros['_id']]
        ];

        return $resultado = $this->modelo->editar($datos, $where);

            return $resultado ? 1 : 0;
        }

       
    }

    // ========= ELIMINAR (BAJA LÓGICA) ========= //
    function eliminar($id)
    {
        $datos = [
            ['campo' => 'th_carfun_estado', 'dato' => 0],
            ['campo' => 'th_carfun_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')],
        ];

        $where = [
            ['campo' => 'th_carfun_id', 'dato' => $id]
        ];

        return $this->modelo->editar($datos, $where);
    }

    // ========= SELECT2 / BUSCADOR ========= //
    function buscar($q = '')
    {
       
    }
}