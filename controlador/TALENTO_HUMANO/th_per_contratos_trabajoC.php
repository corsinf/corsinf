<?php

require_once(dirname(__DIR__, 2)  . '/modelo/TALENTO_HUMANO/th_per_contratos_trabajoM.php');

$controlador = new th_pos_contratos_trabajoC();



if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
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



class th_pos_contratos_trabajoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_contratos_trabajoM();
    }

    //Funcion para listar la formacion academica del postulante


    function listar($id)
    {
        $datos = $this->modelo->where('th_per_id', $id)->where('th_ctr_estado', 1)->listar();

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

        foreach ($datos as $key => $value) {

            $texto .= <<<HTML
                        <div class="col-md-6 mb-col">
                            <div class="cert-card p-3 h-100 position-relative shadow-sm">
                                
                                <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                                        onclick="abrir_modal_contratos_trabajos('{$value['_id']}')" 
                                        title="Editar Contrato">
                                    <i class="bx bx-pencil"></i>
                                </button>

                                <div class="d-flex flex-column h-100">
                                    <div class="mb-2">
                                        <span class="cert-badge mb-1">Contrato</span>
                                        
                                        <h6 class="fw-bold text-dark cert-title mb-1">
                                            {$value['th_ctr_nombre_empresa']}
                                        </h6>
                                        
                                        <p class="cert-doctor m-0">
                                            <i class="bx bx-file-blank me-1"></i>{$value['th_ctr_tipo_contrato']}
                                        </p>
                                    </div>

                                    <div class="mt-auto pt-2 d-flex justify-content-between align-items-end">
                                        <div class="cert-date-range">
                                            <div class="cert-label-small" style="color: #052c65;">Documentación</div>
                                            <span class="text-muted" style="font-size: 0.7rem;">
                                                <i class="bx bx-check-double me-1"></i>Copia Digitalizada
                                            </span>
                                        </div>
                                        
                                        <button data-bs-toggle="modal" data-bs-target="#modal_ver_pdf_contratos" 
                                                onclick="definir_ruta_iframe_contratos('{$value['th_ctr_ruta_archivo']}');" 
                                                class="btn btn-dark btn-xs py-1 px-3 btn-cert-action">
                                            CONTRATO
                                        </button>
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
            $datos = $this->modelo->where('th_ctr_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_ctr_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($file, $parametros)
    {
        // print_r($parametros);
        // exit();
        // die();

        // $in_cbx_fecha_fin_experiencia = (isset($parametros['cbx_fecha_fin_experiencia']) && $parametros['cbx_fecha_fin_experiencia'] == 'true') ? 1 : 0;
        if ($parametros['txt_persona_cedula'] == "") {
            return -5;
        }
        $datos = array(
            array('campo' => 'th_ctr_nombre_empresa', 'dato' => $parametros['txt_nombre_empresa_contrato']),
            array('campo' => 'th_ctr_tipo_contrato', 'dato' => $parametros['txt_tipo_contrato']),
            //array('campo' => 'th_ctr_ruta_archivo', 'dato' => $parametros['txt_ruta_archivo']),
            array('campo' => 'th_ctr_fecha_inicio_contrato', 'dato' => $parametros['txt_fecha_inicio_contrato']),
            array('campo' => 'th_ctr_cbx_fecha_fin_experiencia', 'dato' => $parametros['cbx_fecha_fin_experiencia'] ?? 0),
            array('campo' => 'th_per_id', 'dato' => $parametros['txt_persona_id']),


        );

        if (!isset($parametros['cbx_fecha_fin_experiencia']) || !in_array($parametros['cbx_fecha_fin_experiencia'], [0, 1])) {
            return "El campo de fecha fin de experiencia es inválido o no se ha proporcionado correctamente.";
        }

        $id_contratos_trabajos = $parametros['txt_contratos_trabajos_id'];

        if ($id_contratos_trabajos == '') {
            $datos = $this->modelo->insertar_id($datos);
            $this->guardar_archivo($file, $parametros, $datos);
            return 1;
        } else {

            $where = array(
                array('campo' => 'th_ctr_id', 'dato' => $id_contratos_trabajos),
            );

            $datos = $this->modelo->editar($datos, $where);

            if ($file['txt_ruta_archivo_contrato']['tmp_name'] != '' && $file['txt_ruta_archivo_contrato']['tmp_name'] != null) {
                $datos = $this->guardar_archivo($file, $parametros, $id_contratos_trabajos);
            }
        }

        return $datos;
    }



    function eliminar($id)
    {
        $datos_archivo = $this->modelo->where('th_ctr_id', $id)->where('th_ctr_estado', 1)->listar();

        if ($datos_archivo && isset($datos_archivo[0]['th_ctr_ruta_archivo'])) {
            $ruta_relativa = ltrim($datos_archivo[0]['th_ctr_ruta_archivo'], './');
            $ruta_archivo = dirname(__DIR__, 3) . '/' . $ruta_relativa;

            if (file_exists($ruta_archivo)) {
                unlink($ruta_archivo);
            }
        }

        $datos = array(
            array('campo' => 'th_ctr_estado', 'dato' => 0),
        );

        $where = array(
            array('campo' => 'th_ctr_id', 'dato' => strval($id)),
        );


        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    private function guardar_archivo($file, $post, $id_insertar_editar)
    {
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $ruta = dirname(__DIR__, 2) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/'; //ruta carpeta donde queremos copiar los archivos
        $ruta .= $post['txt_persona_cedula'] . '/' . 'CONTRATOS_TRABAJOS/';
        // print_r($ruta); exit(); die();
        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        if ($this->validar_formato_archivo($file) === 1) {
            $uploadfile_temporal = $file['txt_ruta_archivo_contrato']['tmp_name'];
            $extension = pathinfo($file['txt_ruta_archivo_contrato']['name'], PATHINFO_EXTENSION);

            $nombre = 'contratos_trabajos_' . $id_insertar_editar . '.' . $extension;
            $nuevo_nom = $ruta . $nombre;

            $nombre_ruta = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $post['txt_persona_cedula'] . '/' . 'CONTRATOS_TRABAJOS/';
            $nombre_ruta .= $nombre;
            //print_r($post); exit(); die();

            if (is_uploaded_file($uploadfile_temporal)) {
                if (move_uploaded_file($uploadfile_temporal, $nuevo_nom)) {

                    $datos = array(
                        array('campo' => 'th_ctr_ruta_archivo', 'dato' => $nombre_ruta),
                    );

                    $where = array(
                        array('campo' => 'th_ctr_id', 'dato' => $id_insertar_editar),
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
        switch ($file['txt_ruta_archivo_contrato']['type']) {
            case 'application/pdf':
                return 1;
                break;
            default:
                return -1;
                break;
        }
    }
}
