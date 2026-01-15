<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/DOTACIONES/th_per_dotacion_detalleM.php');
require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/DOTACIONES/th_per_dotacionM.php');


$controlador = new th_per_dotacion_detalleC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_dotaciones_detalle'])) {
    echo json_encode($controlador->listar_dotacion_detalle($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_per_dotacion_detalleC
{
    private $modelo;
    private $th_per_dotacion;

    function __construct()
    {
        $this->modelo = new th_per_dotacion_detalleM();
        $this->th_per_dotacion = new th_per_dotacionM();
    }

    function listar_dotacion_detalle($th_dot_id)
    {
        return $this->modelo->listar_detalle_dotacion($th_dot_id);
    }

    function listar($th_per_id)
    {
        $lista_dotaciones = $this->th_per_dotacion->where('th_per_id', $th_per_id)->where('th_dot_estado', 1)->listar();

        $html = '';

        if (empty($lista_dotaciones)) {
            return '<div class="alert alert-info">No se registran dotaciones para esta persona.</div>';
        }

        foreach ($lista_dotaciones as $value) {
            // Usamos el ID de la cabecera para los identificadores de HTML y funciones
            $id_cabecera = $value['_id'];
            $fecha = date('d/m/Y', strtotime($value['th_dot_fecha_entrega']));
            $obs = $value['th_dot_observacion'] ?: 'Sin observaciones';

            // 2. Obtener los ítems específicos para ESTA cabecera
            $items = $this->modelo->listar_detalle_dotacion($id_cabecera);
            $html_items = '';

            foreach ($items as $item) {
                $html_items .= "
            <li class='list-group-item d-flex justify-content-between align-items-center'>
                <div>
                    <i class='bx bx-check-double text-success'></i> 
                    <strong>{$item['nombre_item']}</strong> <br>
                    <small class='text-muted'>Tipo: {$item['tipo_item']} | Talla: {$item['descripcion_talla']}</small>
                </div>
                <span class='badge bg-primary rounded-pill'>Cant: {$item['th_dotd_cantidad']}</span>
            </li>";
            }

            if (empty($items)) {
                $html_items = "<li class='list-group-item text-muted'>No hay ítems registrados.</li>";
            }

            // 3. Estructura con Botón de Eliminar y Collapse
            $html .= <<<HTML
        <div class="card mb-2 border-primary">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-7">
                        <h6 class="mb-1 text-primary"><strong>Fecha de entrega:</strong> {$fecha}</h6>
                        <p class="small mb-0 text-secondary text-truncate"><strong>Obs:</strong> {$obs}</p>
                    </div>
                    <div class="col-5 text-end">
                        <button class="btn btn-sm btn-outline-danger me-1" 
                                onclick="eliminar_dotacion('{$id_cabecera}')" 
                                title="Eliminar Dotación">
                            <i class="bx bx-trash"></i>
                        </button>
                        
                        <button class="btn btn-sm btn-outline-primary" type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#detalle_{$id_cabecera}" 
                                aria-expanded="false">
                            <i class="bx bx-chevron-down"></i> Ítems
                        </button>
                    </div>
                </div>
                
                <div class="collapse mt-3" id="detalle_{$id_cabecera}">
                    <ul class="list-group list-group-flush border-top">
                        {$html_items}
                    </ul>
                </div>
            </div>
        </div>
HTML;
        }

        return $html;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_dot_id', 'dato' => $parametros['th_dot_id']),
            array('campo' => 'id_dotacion_item', 'dato' => $parametros['ddl_dotacion_item']),
            array('campo' => 'id_talla', 'dato' => $parametros['ddl_talla']),
            array('campo' => 'th_dotd_cantidad', 'dato' => $parametros['txt_cantidad_adicional']),
            array('campo' => 'th_dotd_estado_item', 'dato' => 1),
            array('campo' => 'th_dotd_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        if ($parametros['_id'] == '') {
            $datos[] = array('campo' => 'th_dotd_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
            $datos[] = array('campo' => 'th_dotd_estado', 'dato' => 1);
            return $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_dotd_id';
            $where[0]['dato'] = $parametros['_id'];
            return $this->modelo->editar($datos, $where);
        }
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_dotd_estado', 'dato' => 0),
            array('campo' => 'th_dotd_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        $where[0]['campo'] = 'th_dotd_id';
        $where[0]['dato'] = strval($id);
        $resultado = $this->modelo->editar($datos, $where);

        return $resultado;
    }
}
