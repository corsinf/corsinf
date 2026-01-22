<?php
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_comisionM.php');

$controlador = new th_per_comisionC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}
if (isset($_GET['listar_personas_comision'])) {
    echo json_encode($controlador->listar_personas_comision($_POST['id']));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_personas_modal'])) {
    echo json_encode($controlador->listar_personas_modal($_POST['id']));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar_personas($_POST['parametros']));
}

if (isset($_GET['mover_varios'])) {

    header('Content-Type: application/json; charset=utf-8');

    $ids_raw = isset($_POST['ids']) ? $_POST['ids'] : '[]';
    $ids = json_decode($ids_raw, true);

    $id_comision_destino = isset($_POST['id_comision_destino'])
        ? $_POST['id_comision_destino']
        : '';

    if (!is_array($ids) || empty($id_comision_destino)) {
        echo json_encode([
            'success'     => false,
            'message'     => 'Parámetros inválidos',
            'exitosos'    => 0,
            'duplicados'  => 0,
            'fallidos'    => 0,
            'errores'     => []
        ]);
        exit;
    }
    $resultado = $controlador->mover_personas_comision(
        $ids,
        $id_comision_destino
    );

    echo json_encode($resultado);
    exit;
}


if (isset($_GET['insertar_editar_persona'])) {
    echo json_encode($controlador->insertar_editar_persona_comision($_POST['parametros']));
}


class th_per_comisionC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_comisionM();
    }


    function listar_personas_comision($id)
    {
        return $this->modelo->listar_personas_comisiones($id);
    }

    function listar($id)
    {
        // ----------------------------------------------------------------
        // Restricciones
        // ----------------------------------------------------------------
        $roles_restringidos = ['PERSONAS', 'POSTULANTES'];
        $tipo_usuario = strtoupper($_SESSION['INICIO']['TIPO']);
        $es_restringido = in_array($tipo_usuario, $roles_restringidos);
        // ----------------------------------------------------------------

        $datos = $this->modelo->listar_comision_por_persona($id);
        $texto = '<div class="row g-3">'; // Contenedor principal

        if (empty($datos)) {
            return '<div class="alert alert-info border-0 shadow-sm" style="border-radius: 12px;">No hay registros de comisión.</div>';
        }

        foreach ($datos as $value) {

            // 1. Definimos el botón con la nueva clase minimalista y la lógica de restricción
            $boton_editar = "";
            if (!$es_restringido) {
                $boton_editar = <<<HTML
                                    <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                                            onclick="abrir_modal_comision('{$value['_id']}');" 
                                            title="Editar Comisión">
                                        <i class="bx bx-pencil"></i>
                                    </button>
                                HTML;
            }

            $texto .= <<<HTML
                            <div class="col-md-6 mb-col">
                                <div class="cert-card p-3 h-100 position-relative shadow-sm">
                                    
                                    {$boton_editar}

                                    <div class="d-flex flex-column h-100">
                                        <div class="mb-2">
                                            <span class="cert-badge mb-1">Comisión</span>
                                            
                                            <h6 class="fw-bold text-dark cert-title mb-1">
                                                {$value['comision_nombre']}
                                            </h6>
                                            
                                            <p class="text-muted m-0" style="font-size: 0.75rem; line-height: 1.3;">
                                                {$value['comision_descripcion']}
                                            </p>
                                        </div>

                                        <div class="mt-auto pt-2">
                                            <div class="d-flex align-items-center justify-content-between p-2" 
                                                style="background: rgba(111, 66, 193, 0.05); border-radius: 8px; border: 1px dashed rgba(111, 66, 193, 0.2);">
                                                
                                                <div class="cert-date-range">
                                                    <div class="cert-label-small" style="color: #6f42c1;">Código Identificador</div>
                                                    <span class="fw-bold" style="font-size: 0.85rem; color: #6f42c1; letter-spacing: 1px;">
                                                        <i class="bx bx-barcode-reader me-1"></i>{$value['comision_codigo']}
                                                    </span>
                                                </div>

                                                <div style="color: #6f42c1; opacity: 0.5;">
                                                    <i class="bx bx-group bx-sm"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        HTML;
        }

        $texto .= '</div>';

        return $texto;
    }


    function listar_personas_modal($id_comision = '')
    {
        $datos = $this->modelo->listar_personas_modal_comision($id_comision);
        return $datos;
    }

    function listar_modal($id)
    {
        return $this->modelo->listar_comision_por_id($id);
    }

    function insertar_editar_personas($parametros)
    {
        $salida = '';

        if ($parametros['_id'] != '') {

            if (isset($parametros['personas_seleccionadas'])) {

                foreach ($parametros['personas_seleccionadas'] as $persona_id) {

                    // Datos para la relación persona–comisión
                    $datos = array(
                        array('campo' => 'th_per_id', 'dato' => $persona_id),
                        array('campo' => 'id_comision', 'dato' => $parametros['_id']),
                    );

                    // Verificar si ya existe la relación
                    $contar_personas = count(
                        $this->modelo
                            ->where('th_per_id', $persona_id)
                            ->where('id_comision', $parametros['_id'])
                            ->where('th_per_com_estado', 1)
                            ->listar()
                    );

                    if ($contar_personas == 0) {

                        // Campos extra solo en inserción
                        $datos[] = array(
                            'campo' => 'th_per_com_fecha_creacion',
                            'dato'  => date('Y-m-d H:i:s')
                        );

                        $this->modelo->insertar($datos);
                    }

                    $this->modelo->reset();
                }

                return 1;
            } else {
                return -2; // No hay personas seleccionadas
            }
        }
    }


    function insertar_editar_persona_comision($parametros)
    {
        $datos = array(
            array('campo' => 'th_per_id', 'dato' => $parametros['id_persona']),
            array('campo' => 'id_comision', 'dato' => $parametros['id_comision']),
        );

        if ($parametros['_id'] == '') {

            // INSERTAR → verificar que no exista relación persona–comisión
            $existe = $this->modelo
                ->where('th_per_id', $parametros['id_persona'])
                ->where('id_comision', $parametros['id_comision'])
                ->where('th_per_com_estado', 1)
                ->listar();

            if (count($existe) == 0) {

                $datos[] = array(
                    'campo' => 'th_per_com_fecha_creacion',
                    'dato'  => date('Y-m-d H:i:s')
                );

                $this->modelo->insertar_id($datos);
                return 1;
            } else {
                return -2;
            }
        } else {

            $existe = $this->modelo
                ->where('th_per_id', $parametros['id_persona'])
                ->where('id_comision', $parametros['id_comision'])
                ->where('th_per_com_id !', $parametros['_id'])
                ->where('th_per_com_estado', 1)
                ->listar();

            if (count($existe) == 0) {

                $where[0]['campo'] = 'th_per_com_id';
                $where[0]['dato']  = $parametros['_id'];

                return $this->modelo->editar($datos, $where);
            } else {
                return -2; // Ya existe la relación
            }
        }
    }

    function insertar_editar($parametros)
    {
        $this->modelo->reset();

        $existe = $this->modelo
            ->where('th_per_id', $parametros['per_id'])
            ->where('id_comision', $parametros['ddl_comision'])
            ->where('th_per_com_estado', 1)
            ->listar();

        if ($parametros['_id'] == '') {

            if (count($existe) > 0) {
                return -2;
            }

            $datos = [
                ['campo' => 'th_per_id', 'dato' => $parametros['per_id']],
                ['campo' => 'id_comision', 'dato' => $parametros['ddl_comision']]
            ];

            return $this->modelo->insertar($datos);
        }

        $this->modelo->reset();

        $existe = $this->modelo
            ->where('th_per_id', $parametros['per_id'])
            ->where('id_comision', $parametros['ddl_comision'])
            ->where('th_per_com_estado', 1)
            ->where('th_per_com_id !', $parametros['_id'])
            ->listar();

        if (count($existe) > 0) {
            return -2;
        }

        $datos = [
            ['campo' => 'th_per_id', 'dato' => $parametros['per_id']],
            ['campo' => 'id_comision', 'dato' => $parametros['ddl_comision']],
            ['campo' => 'th_per_com_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')]
        ];

        $where[] = [
            'campo' => 'th_per_com_id',
            'dato'  => $parametros['_id']
        ];

        return $this->modelo->editar($datos, $where);
    }


    function eliminar($id)
    {
        $datos = [
            ['campo' => 'th_per_com_id', 'dato' => $id]
        ];
        return $this->modelo->eliminar($datos);
    }

    function mover_personas_comision($ids_personas, $id_comision_destino)
    {
        $exitosos  = 0;
        $fallidos  = 0;
        $duplicados = 0;
        $errores   = [];

        foreach ($ids_personas as $item) {

            $per_com = isset($item['per_com']) ? trim($item['per_com']) : '';
            $person  = isset($item['person']) ? trim($item['person']) : '';

            try {

                if ($per_com !== '') {
                    $existe = $this->modelo
                        ->where('th_per_id', $person)
                        ->where('id_comision', $id_comision_destino)
                        ->where('th_per_com_id !', $per_com)
                        ->where('th_per_com_estado', 1)
                        ->listar();

                    if (count($existe) > 0) {
                        $duplicados++;
                        continue;
                    }

                    $datos = array(
                        array('campo' => 'id_comision', 'dato' => $id_comision_destino),
                        array(
                            'campo' => 'th_per_com_fecha_modificacion',
                            'dato'  => date('Y-m-d H:i:s')
                        )
                    );

                    $where[0]['campo'] = 'th_per_com_id';
                    $where[0]['dato']  = $per_com;

                    $res = $this->modelo->editar($datos, $where);

                    if ($res > 0) $exitosos++;
                    else {
                        $fallidos++;
                        $errores[] = "No se pudo actualizar relación per_com_id {$per_com}";
                    }
                } elseif ($person !== '') {

                    // CASO 2: insertar nueva relación
                    $existe = $this->modelo
                        ->where('th_per_id', $person)
                        ->where('id_comision', $id_comision_destino)
                        ->where('th_per_com_estado', 1)
                        ->listar();

                    if (count($existe) > 0) {
                        $duplicados++;
                        continue;
                    }

                    $datos = array(
                        array('campo' => 'th_per_id', 'dato' => $person),
                        array('campo' => 'id_comision', 'dato' => $id_comision_destino),
                        array('campo' => 'th_per_com_estado', 'dato' => 1),
                        array(
                            'campo' => 'th_per_com_fecha_creacion',
                            'dato'  => date('Y-m-d H:i:s')
                        )
                    );

                    $resIns = $this->modelo->insertar_id($datos);

                    if ($resIns > 0) $exitosos++;
                    else {
                        $fallidos++;
                        $errores[] = "Fallo al insertar relación para persona {$person}";
                    }
                } else {
                    $fallidos++;
                    $errores[] = "Item inválido (sin per_com ni person)";
                }
            } catch (Exception $e) {
                $fallidos++;
                $errores[] = "Error (per_com={$per_com}, person={$person}): " . $e->getMessage();
            }
        }

        $mensaje = "Operación completada. ";
        if ($exitosos > 0)   $mensaje .= "$exitosos persona(s) movida(s). ";
        if ($duplicados > 0) $mensaje .= "$duplicados duplicado(s). ";
        if ($fallidos > 0)   $mensaje .= "$fallidos fallido(s). ";

        return [
            'success'     => $exitosos > 0,
            'exitosos'    => $exitosos,
            'duplicados'  => $duplicados,
            'fallidos'    => $fallidos,
            'message'     => $mensaje,
            'errores'     => $errores
        ];
    }
}
