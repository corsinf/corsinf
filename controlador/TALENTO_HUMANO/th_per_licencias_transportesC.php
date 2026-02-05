<?php
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_licencias_transportesM.php');

$controlador = new th_per_licencias_transportesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar_estados_licencias'])) {
    $query = $_GET['q'] ?? '';
    $parametros = array('query' => $query);
    echo json_encode($controlador->buscar_estados_licencias($parametros));
}

class th_per_licencias_transportesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_licencias_transportesM();
    }

    // Función para listar los vehículos de la persona
    function listar($id)
    {
        $datos = $this->modelo->listar_licencias('', $id);

        if (empty($datos)) {
            $texto = '<div class="alert alert-info border-0 shadow-sm" style="border-radius: 12px;">No hay licencias de transporte registradas.</div>';
        } else {
            $texto = '<div class="row g-3">';

            foreach ($datos as $value) {

                $tipo_licencia = !empty($value['tipo_licencia_transporte']) ? $value['tipo_licencia_transporte'] : 'No especificada';
                $categoria = !empty($value['categoria']) ? $value['categoria'] : '-';
                $estado_licencia = !empty($value['estado_licencia']) ? $value['estado_licencia'] : 'Sin número';
                $autoridad = !empty($value['autoridad_emisora']) ? $value['autoridad_emisora'] : 'N/A';
                $escuela = !empty($value['escuela']) ? $value['escuela'] : 'No registrada';

                // Formateo de fechas
                $fecha_exp = !empty($value['fecha_expedicion']) ? date('d/m/Y', strtotime($value['fecha_expedicion'])) : 'N/A';
                $fecha_venc = !empty($value['fecha_vencimiento']) ? date('d/m/Y', strtotime($value['fecha_vencimiento'])) : 'N/A';

                // Alerta de vencimiento (Color dinámico si está por vencer o vencida)
                $hoy = date('Y-m-d');
                $es_vencida = (!empty($value['fecha_vencimiento']) && $value['fecha_vencimiento'] < $hoy);
                $vencimiento_style = $es_vencida ? 'color: #dc3545; font-weight: bold;' : 'color: #2c3e50;';

                $texto .= <<<HTML
                                <div class="col-md-6 mb-col">
                                    <div class="cert-card p-3 h-100 position-relative shadow-sm">
                                        
                                        <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                                                onclick="abrir_modal_licencias_transportes('{$value['_id']}');" 
                                                title="Editar Licencia">
                                            <i class="bx bx-pencil"></i>
                                        </button>

                                        <div class="d-flex flex-column h-100">
                                            <div class="mb-2">
                                                <span class="cert-badge mb-1" style="background: #34495e; color: #fff;">
                                                    <i class="bx bx-car me-1"></i>LICENCIA TIPO {$categoria}
                                                </span>
                                                
                                                <h6 class="fw-bold text-dark cert-title mb-1">
                                                    {$tipo_licencia}
                                                </h6>
                                                
                                                <p class="cert-doctor mb-1 text-uppercase" style="font-size: 0.85rem; font-weight: 700; color: #2c3e50;">
                                                    <i class="bx bx-id-card me-1 text-primary"></i>{$estado_licencia}
                                                </p>

                                                <div class="mt-2" style="font-size: 0.75rem; color: #666;">
                                                    <p class="m-0"><i class="bx bx-buildings me-1"></i>Escuela: <strong>{$escuela}</strong></p>
                                                    <p class="m-0"><i class="bx bx-shield-quarter me-1"></i>Autoridad: <strong>{$autoridad}</strong></p>
                                                </div>
                                            </div>

                                            <div class="mt-auto pt-2">
                                                <div class="d-flex align-items-center justify-content-between p-2" 
                                                    style="background: rgba(52, 73, 94, 0.05); border-radius: 8px; border: 1px dashed rgba(52, 73, 94, 0.3);">
                                                    
                                                    <div class="cert-date-range">
                                                        <div class="cert-label-small">Vigencia del Documento</div>
                                                        <span style="font-size: 0.65rem; {$vencimiento_style}">
                                                            <i class="bx bx-calendar me-1"></i>{$fecha_exp} — {$fecha_venc}
                                                        </span>
                                                    </div>

                                                    <div style="color: #34495e; opacity: 0.5;">
                                                        <i class="bx bxs-traffic bx-sm"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        HTML;
            }
            $texto .= '</div>';
        }

        return $texto;
    }

    // Buscando registros por id del vehículo
    function listar_modal($id)
    {
        $datos = $this->modelo->listar_licencias($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'id_licencia_transporte', 'dato' => $parametros['ddl_licencia_transporte']),
            array('campo' => 'th_per_id', 'dato' => $parametros['per_id']),
            array('campo' => 'th_lic_numero', 'dato' => $parametros['txt_numero_licencia']),
            array('campo' => 'th_lic_fecha_expedicion', 'dato' => $parametros['txt_fecha_expedicion']),
            array('campo' => 'th_lic_fecha_vencimiento', 'dato' => $parametros['txt_fecha_vencimiento']),
            array('campo' => 'th_lic_autoridad_emisora', 'dato' => $parametros['txt_autoridad_emisora']),
            array('campo' => 'th_lic_escuela', 'dato' => $parametros['txt_escuela']),
            array('campo' => 'th_lis_estado_licencia', 'dato' => $parametros['ddl_estado_licencia']),
            array('campo' => 'th_lic_fecha_modificacion', 'dato' => date("Y-m-d H:i:s")),
        );

        if (isset($parametros['txt_id_licencia_transporte']) && $parametros['txt_id_licencia_transporte'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_lic_id';
            $where[0]['dato'] = $parametros['txt_id_licencia_transporte'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_lic_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_lic_id';
        $where[0]['dato'] = strval($id);

        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }

    function buscar_estados_licencias($parametros)
    {
        $lista = array();

        $lista = $this->modelo->buscar_estados_licencias($parametros);

        return $lista;
    }
}
