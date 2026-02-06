<?php
require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_referencias_laboralesM.php');

$controlador = new th_pos_referencias_laboralesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['listar_modal_experiencia_referencias'])) {
    echo json_encode($controlador->listar_modal_experiencia_referencias($_POST['id']));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_FILES, $_POST));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}


class th_pos_referencias_laboralesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_referencias_laboralesM();
    }

    //Funcion para listar la formacion academica del postulante
    function listar_modal_experiencia_referencias($id)
    {
        // Buscamos solo las referencias de esta experiencia específica
        $datos = $this->modelo->where('th_expl_id', $id)
            ->where('th_refl_estado', 1)
            ->orderBy('th_refl_nombre_referencia')
            ->listar();


        if (empty($datos)) {
            return <<<HTML
                        <div class="d-flex align-items-center bg-white border border-start-0 shadow-sm rounded-3" 
                            style="border-left: 4px solid !important; padding: 12px 24px; max-width: 600px;">
                        
                        <i class='bx bx-data me-3 text-primary' style='font-size: 28px;'></i>

                        <div class="lh-sm">
                            <div class="text-dark fw-bold mb-1" style="font-size: 1rem;">
                            Sin registros en este apartado
                            </div>
                            <div class="text-secondary" style="font-size: 0.85rem;">
                            No hemos encontrado información disponible para mostrar en esta sección.
                            </div>
                        </div>
                        </div>
                    HTML;
        }

        $texto = '<div id="scroll_referencias" style="max-height: 200px; overflow-y: auto; padding: 5px;">';

        foreach ($datos as $value) {
            $id_ref = $value['_id']; // ID de la referencia

            $texto .= <<<HTML
                            <div class="d-flex align-items-center justify-content-between bg-white border rounded p-2 mb-2 shadow-sm">
                                <div style="line-height: 1.2;">
                                    <div class="fw-bold text-dark" style="font-size: 0.75rem;">
                                        <i class="bx bx-user me-1 text-primary"></i>{$value['th_refl_nombre_referencia']}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.7rem;">
                                        <i class="bx bx-phone me-1"></i>{$value['th_refl_telefono_referencia']}
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-link text-info p-0" 
                                            onclick="abrir_modal_referencias_laborales('{$id_ref}','1');" 
                                            title="Editar Referencia">
                                        <i class="bx bx-pencil" style="font-size: 1rem;"></i>
                                    </button>
                                </div>
                            </div>
                        HTML;
        }

        $texto .= '</div>';
        return $texto;
    }

    public function listar($id)
    {
        // Llamamos a la nueva función del modelo
        $datos = $this->modelo->listar_referencias_completo($id);

        if (empty($datos)) {
            return <<<HTML
                        <div class="d-flex align-items-center bg-white border border-start-0 shadow-sm rounded-3" 
                            style="border-left: 4px solid !important; padding: 12px 24px; max-width: 600px;">
                        
                        <i class='bx bx-data me-3 text-primary' style='font-size: 28px;'></i>

                        <div class="lh-sm">
                            <div class="text-dark fw-bold mb-1" style="font-size: 1rem;">
                            Sin registros en este apartado
                            </div>
                            <div class="text-secondary" style="font-size: 0.85rem;">
                            No hemos encontrado información disponible para mostrar en esta sección.
                            </div>
                        </div>
                        </div>
                    HTML;
        }

        $texto = '<div class="row g-3">';

        foreach ($datos as $value) {

            // Validación de documento
            $boton_documento = "";
            if (!empty($value['th_refl_carta_recomendacion']) && $value['th_refl_carta_recomendacion'] !== 'null') {
                $boton_documento = <<<HTML
                                        <button onclick="definir_ruta_iframe_referencias_laborales('{$value['th_refl_carta_recomendacion']}');" 
                                                class="btn btn-dark btn-xs py-1 px-3 btn-cert-action">
                                            DOCUMENTO
                                        </button>
                                    HTML;
            }

            // Lógica de Badge (Laboral o Personal)
            $tipo_referencia = (!empty($value['th_expl_id'])) ? 'Laboral' : 'Personal';
            $badge_class = (!empty($value['th_expl_id'])) ? 'bg-primary' : 'bg-secondary';

            $texto .= <<<HTML
                            <div class="col-md-6 mb-col">
                                <div class="cert-card p-3 h-100 position-relative shadow-sm">
                                    
                                    <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                                            onclick="abrir_modal_referencias_laborales('{$value['_id']}')" 
                                            title="Editar Referencia">
                                        <i class="bx bx-pencil"></i>
                                    </button>

                                    <div class="d-flex flex-column h-100">
                                        <div class="mb-2">
                                            <span class="badge {$badge_class} mb-2" style="font-size: 0.6rem;">{$tipo_referencia}</span>
                                            
                                            <h6 class="fw-bold text-dark cert-title mb-1">
                                                {$value['th_refl_nombre_referencia']}
                                            </h6>
                                            <p class="cert-doctor m-0 text-truncate" title="{$value['nombre_empresa_final']}">
                                                <i class="bx bx-buildings me-1"></i>{$value['nombre_empresa_final']}
                                            </p>
                                            <p class="cert-doctor m-0">
                                                <i class="bx bx-phone me-1"></i>{$value['th_refl_telefono_referencia']}
                                            </p>
                                        </div>

                                        <div class="mt-auto pt-2 d-flex justify-content-between align-items-end border-top">
                                            <div class="cert-date-range">
                                                <div class="cert-label-small">Contacto</div>
                                                <span class="text-muted" style="font-size: 0.7rem;">{$value['th_refl_correo']}</span>
                                            </div>
                                            
                                            {$boton_documento}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        HTML;
        }
        $texto .= '</div>';
        return $texto;
    }

    //Buscando registros por id de la formacion academica
    function listar_modal($id)
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_refl_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_refl_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($file, $parametros)
    {
        // print_r($file);
        // exit();
        // die();

        if ($parametros['txt_postulante_cedula'] == "") {
            return -5;
        }

        $datos = array(
            array('campo' => 'th_refl_nombre_referencia', 'dato' => $parametros['txt_nombre_referencia']),
            array('campo' => 'th_refl_telefono_referencia', 'dato' => $parametros['txt_telefono_referencia']),
            //array('campo' => 'th_refl_carta_recomendacion', 'dato' => $parametros['txt_copia_carta_recomendacion']), 
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_postulante_id']),
            array('campo' => 'th_refl_correo', 'dato' => $parametros['txt_referencia_correo']),
            array('campo' => 'th_refl_nombre_empresa', 'dato' => $parametros['txt_referencia_nombre_empresa']),
            array('campo' => 'th_expl_id', 'dato' => !empty($parametros['txt_referencia_experiencia_id']) ? $parametros['txt_referencia_experiencia_id'] : null),
        );

        $id_referencias_laboral = $parametros['txt_referencias_laborales_id'];

        if ($id_referencias_laboral == '') {
            $datos = $this->modelo->insertar_id($datos);
            $this->guardar_archivo($file, $parametros, $datos);
            return 1;
        } else {

            $where = array(
                array('campo' => 'th_refl_id', 'dato' => $id_referencias_laboral),
            );

            $datos = $this->modelo->editar($datos, $where);

            if ($file['txt_copia_carta_recomendacion']['tmp_name'] != '' && $file['txt_copia_carta_recomendacion']['tmp_name'] != null) {
                $datos = $this->guardar_archivo($file, $parametros, $id_referencias_laboral);
            }
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos_archivo = $this->modelo->where('th_refl_id', $id)->where('th_refl_estado', 1)->listar();

        if ($datos_archivo && isset($datos_archivo[0]['th_refl_carta_recomendacion'])) {
            $ruta_relativa = ltrim($datos_archivo[0]['th_refl_carta_recomendacion'], './');
            $ruta_archivo = dirname(__DIR__, 3) . '/' . $ruta_relativa;

            if (file_exists($ruta_archivo)) {
                unlink($ruta_archivo);
            }
        }

        $datos = array(
            array('campo' => 'th_refl_estado', 'dato' => 0),
        );

        $where = array(
            array('campo' => 'th_refl_id', 'dato' => strval($id)),
        );


        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    private function guardar_archivo($file, $post, $id_insertar_editar)
    {
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $ruta = dirname(__DIR__, 3) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/'; //ruta carpeta donde queremos copiar los archivos
        $ruta .= $post['txt_postulante_cedula'] . '/' . 'REFERENCIAS_LABORALES/';

        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        if ($this->validar_formato_archivo($file) === 1) {
            $uploadfile_temporal = $file['txt_copia_carta_recomendacion']['tmp_name'];
            $extension = pathinfo($file['txt_copia_carta_recomendacion']['name'], PATHINFO_EXTENSION);
            //Para referencias laborales
            $nombre = 'referencia_laboral_' . $id_insertar_editar . '.' . $extension;
            $nuevo_nom = $ruta . $nombre;

            $nombre_ruta = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $post['txt_postulante_cedula'] . '/' . 'REFERENCIAS_LABORALES/';
            $nombre_ruta .= $nombre;
            //print_r($post); exit(); die();

            if (is_uploaded_file($uploadfile_temporal)) {
                if (move_uploaded_file($uploadfile_temporal, $nuevo_nom)) {

                    $datos = array(
                        array('campo' => 'th_refl_carta_recomendacion', 'dato' => $nombre_ruta),
                    );

                    $where = array(
                        array('campo' => 'th_refl_id', 'dato' => $id_insertar_editar),
                    );

                    // Ejecutar la actualización en la base de datos
                    $base = $this->modelo->editar($datos, $where);

                    return $base == 1 ? 1 : -1;
                } else {
                    return -1;
                }
            } else {
                return -1;
            }
        } else {
            return -2;
        }
    }

    private function validar_formato_archivo($file)
    {
        switch ($file['txt_copia_carta_recomendacion']['type']) {
            case 'application/pdf':
                return 1;
                break;
            default:
                return -1;
                break;
        }
    }
}
