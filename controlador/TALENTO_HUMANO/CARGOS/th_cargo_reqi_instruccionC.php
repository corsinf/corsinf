<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CARGOS/th_cargo_reqi_instruccionM.php');

$controlador = new th_cargo_reqi_instruccionC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? '', $_POST['instruccion_id'] ?? ''));
}


if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


if (isset($_GET['buscar_nivel_academico'])) {
    $parametros = array(
        'query' => isset($_GET['q']) ? $_GET['q'] : '',
        'car_id' => isset($_GET['car_id']) ? $_GET['car_id'] : 0
    );

    $datos = $controlador->buscar_niveles_academicos($parametros);
    echo json_encode($datos);
    exit;
}


class  th_cargo_reqi_instruccionC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cargo_reqi_instruccionM();
    }


    function listar_modal($id = '')
    {
        $datos = [];
        if ($id !== '') {
            $datos = $this->modelo->listar_cargo_instruccion(null, $id);
        }

        if (empty($datos)) {
            return
                <<<HTML
            <div class="d-flex align-items-center bg-light border-start border-secondary border-3 p-2 shadow-sm rounded-2">
              <i class='bx bx-info-circle me-2 text-secondary' style='font-size: 20px;'></i>
                <div class="lh-1">
                    <div class="text-dark fw-bold small">Sin instrucción registrada</div>
                    <div class="text-muted" style="font-size: 0.75rem;">No se ha definido el nivel académico.</div>
                </div>
            </div>
            HTML;
        }

        $texto = '<ul class="list-unstyled mb-0">';
        foreach ($datos as $value) {
            $id_reg = $value['_id'];
            $descripcion = $value['nivel_academico_descripcion'];

            $texto .= <<<HTML
        <li class="mb-1">
            <div class="d-flex align-items-center ">
                <div class="d-flex align-items-center overflow-hidden">
                    <i class='bx bxs-circle me-2 text-dark' style="font-size: 8px;"></i>
                    
                   <h6 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.85rem;">
                        {$descripcion}
                    </h6>
                </div>
                
                <div class="d-flex gap-1 ms-3">
                    <button type="button" class="btn btn-danger btn-xs" onclick="delete_datos_instruccion_basica('{$id_reg}')">
                        <i class="bx bx-trash fs-7 fw-bold"></i>
                    </button>
                </div>
            </div>
        </li>
HTML;
        }
        $texto .= '</ul>';

        return $texto;
    }

    function listar($id = '', $instruccion_id = '')
    {
        if ($id !== '') {
            $datos = $this->modelo->listar_cargo_instruccion(null, $id);
        }
        if ($instruccion_id !== '') {
            $datos = $this->modelo->listar_cargo_instruccion($instruccion_id);
        }
        return $datos;
    }


    function insertar_editar($parametros)
    {

        $id = $parametros['_id'];
        $datos = array(
            array('campo' => 'id_cargo', 'dato' =>  $parametros['id_cargo']),
            array('campo' => 'id_nivel_academico', 'dato' => $parametros['id_nivel_academico']),
            array('campo' => 'th_reqi_estado', 'dato' => 1),
            array('campo' => 'th_reqi_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
        );
        if ($id == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $datos[] = ['campo' => 'th_reqi_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')];
            $where[0]['campo'] = 'th_reqi_instruccion_id';
            $where[0]['dato'] = $id;
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }
    function eliminar($id)
    {
        // Borrado lógico (cambio de estado)
        $datos = array(
            array('campo' => 'th_reqi_instruccion_id', 'dato' => $id),
        );
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }


    public function buscar_niveles_academicos($parametros)
    {
        $lista = [];

        $query = isset($parametros['query']) ? trim($parametros['query']) : '';
        $car_id = isset($parametros['car_id']) ? (int)$parametros['car_id'] : 0;

        if ($car_id <= 0) {
            return $lista;
        }

        // Obtiene los niveles académicos NO asignados al cargo
        $datos = $this->modelo->listar_niveles_no_asignados($car_id);

        foreach ($datos as $item) {
            $texto = trim($item['descripcion']);

            // Filtro manual por si el Select2 envía texto de búsqueda
            if ($query === '' || stripos($texto, $query) !== false) {
                $lista[] = [
                    'id'   => $item['id_nivel_academico'],
                    'text' => $texto
                ];
            }
        }

        return $lista;
    }
}
