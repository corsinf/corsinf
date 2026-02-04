<?php
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_bancosM.php');

$controlador = new th_per_bancosC();

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

class th_per_bancosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_bancosM();
    }

    // Función para listar los vehículos de la persona
    function listar($id)
    {
        $datos = $this->modelo->listar_bancos('', $id);

        if (empty($datos)) {
            $texto = '<div class="alert alert-info mb-0"><p>No hay información bancaria registrada.</p></div>';
        } else {
            $texto = '<div class="row g-3">';

            foreach ($datos as $value) {

                // Lógica de datos
                $banco = !empty($value['banco_descripcion']) ? $value['banco_descripcion'] : 'Banco no especificado';
                $tipo_cuenta = !empty($value['tipo_cuenta_descripcion']) ? $value['tipo_cuenta_descripcion'] : 'N/A';
                $numero_cuenta = !empty($value['th_ban_numero_cuenta']) ? $value['th_ban_numero_cuenta'] : 'Sin número';
                $forma_pago = !empty($value['forma_pago_descripcion']) ? $value['forma_pago_descripcion'] : 'Sin Forma Pago';

                // Estilo condicional para el registro principal
                $es_principal = ($value['es_principal'] == 1);
                $badge_color = $es_principal ? 'background: #0d6efd; color: #fff;' : 'background: #e9ecef; color: #495057;';
                $card_border = $es_principal ? 'border-top: 3px solid #0d6efd;' : '';
                $texto_principal = $es_principal ? ' <span class="badge bg-primary ms-1" style="font-size:0.6rem;">PRINCIPAL</span>' : '';

                $fecha_mod = !empty($value['th_ban_fecha_modificacion'])
                    ? date('d/m/Y', strtotime($value['th_ban_fecha_modificacion']))
                    : 'N/A';

                $texto .=
                    <<<HTML
                        <div class="col-md-6 mb-col">
                            <div class="cert-card p-3 h-100 position-relative shadow-sm" style="{$card_border}">
                                
                                <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                                        onclick="abrir_modal_bancos('{$value['_id']}');" 
                                        title="Editar Banco">
                                    <i class="bx bx-pencil text-primary"></i>
                                </button>

                                <div class="d-flex flex-column h-100">
                                    <div class="mb-2">
                                        <span class="cert-badge mb-1" style="{$badge_color}">
                                            <i class="bx bxs-bank me-1"></i>Cuenta Bancaria
                                        </span>
                                        
                                        <h6 class="fw-bold text-dark cert-title mb-1">
                                            {$banco} {$texto_principal}
                                        </h6>
                                        
                                        <p class="cert-doctor mb-1 text-uppercase" style="font-size: 0.8rem; font-weight: 600; color: #2c3e50;">
                                            <i class="bx bx-credit-card-front me-1 text-primary"></i>{$numero_cuenta}
                                        </p>

                                        <p class="m-0 text-muted" style="font-size: 0.75rem;">
                                            <i class="bx bx-wallet me-1"></i>Tipo: <strong>{$tipo_cuenta}</strong>
                                        </p>
                                        <p class="m-0 text-muted" style="font-size: 0.75rem;">
                                            <i class="bx bx-wallet me-1"></i>Forma de Pago: <strong>{$forma_pago}</strong>
                                        </p>
                                    </div>

                                    <div class="mt-auto pt-2">
                                        <div class="d-flex align-items-center justify-content-between p-2" 
                                            style="background: rgba(13, 110, 253, 0.05); border-radius: 8px; border: 1px dashed rgba(13, 110, 253, 0.3);">
                                            
                                            <div class="cert-date-range">
                                                <div class="cert-label-small" style="color: #0d6efd;">Gestión de Cuenta</div>
                                                <span class="text-dark" style="font-size: 0.65rem;">
                                                    <i class="bx bx-refresh me-1"></i>Actualizado el: {$fecha_mod}
                                                </span>
                                            </div>

                                            <div style="color: #0d6efd; opacity: 0.5;">
                                                <i class="bx bxs-shield-check bx-sm"></i>
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
        $datos = $this->modelo->listar_bancos($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'id_banco', 'dato' => $parametros['ddl_bancos']),
            array('campo' => 'th_per_id', 'dato' => $parametros['per_id']),
            array('campo' => 'id_tipo_cuenta', 'dato' => $parametros['ddl_tipo_cuenta']),
            array('campo' => 'id_forma_pago', 'dato' => $parametros['ddl_forma_pago']),
            array('campo' => 'th_ban_numero_cuenta', 'dato' => strtoupper($parametros['txt_numero_cuenta'])),
            array('campo' => 'es_principal', 'dato' => $parametros['cbx_es_principal']),
            array('campo' => 'th_ban_fecha_modificacion', 'dato' => date("Y-m-d H:i:s")),
        );

        if (isset($parametros['txt_bancos_id']) && $parametros['txt_bancos_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_ban_id';
            $where[0]['dato'] = $parametros['txt_bancos_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_ban_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_ban_id';
        $where[0]['dato'] = strval($id);

        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }
}
