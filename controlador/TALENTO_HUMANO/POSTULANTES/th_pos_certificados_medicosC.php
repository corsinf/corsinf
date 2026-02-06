<?php
require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_certificados_medicosM.php');

$controlador = new th_pos_certificados_medicosC();

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


class th_pos_certificados_medicosC
{
    private $modelo;

    function __construct()
    {

        $this->modelo = new th_pos_certificados_medicosM();
    }

    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_cer_estado', 1)->listar();

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
        } else {
            $texto = '<div class="row g-3">';

            foreach ($datos as $key => $value) {

                $alergia = $value['th_cer_alergia_req'] == 1 ? 'Sí' : 'No';
                $tratamiento = $value['th_cer_tratamiento_req'] == 1 ? 'Sí' : 'No';

                $btn_documento = '';
                if (!empty($value['th_cer_ruta_certficado'])) {
                    $btn_documento = <<<HTML
                    <button onclick="ruta_iframe_certificados_medicos('{$value['th_cer_ruta_certficado']}');" 
                            class="btn btn-dark btn-xs py-1 px-3 btn-cert-action">
                        DOCUMENTO
                    </button>
                HTML;
                }

                $texto .= <<<HTML
                <div class="col-md-6 mb-col">
                    <div class="cert-card p-3 h-100 position-relative shadow-sm">
                        
                        <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                                onclick="abrir_modal_certificados_medicos('{$value['_id']}');" 
                                title="Editar Certificado">
                            <i class="bx bx-edit-alt"></i>
                        </button>

                        <div class="d-flex flex-column h-100">
                            <div class="mb-2">
                                <span class="cert-badge mb-1">Certificado</span>
                                
                                <h6 class="fw-bold text-dark cert-title mb-1">
                                    {$value['th_cer_motivo_certificado']}
                                </h6>

                                <div class="mt-2 d-flex flex-column gap-1">
                                    <span class="text-muted" style="font-size: 0.72rem;">
                                        <i class="bx bx-alert-triangle me-1"></i>¿Alergia? <strong class="text-dark">{$alergia}</strong>
                                    </span>
                                    <span class="text-muted" style="font-size: 0.72rem;">
                                        <i class="bx bx-medical me-1"></i>¿Tratamiento Continuo? <strong class="text-dark">{$tratamiento}</strong>
                                    </span>
                                    <p class="m-0 text-muted text-truncate" style="font-size: 0.75rem; max-width: 250px;" title="{$value['th_cer_observaciones']}">
                                        <i class="bx bx-map-pin me-1"></i>
                                        Observación <strong>{$value['th_cer_observaciones']}</strong>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-auto pt-2 d-flex justify-content-end align-items-end">
                                {$btn_documento}
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

    function listar_modal($id)
    {

        if ($id == '') {
            $datos = $this->modelo->where('th_cer_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_cer_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($file, $parametros)
    {

        if ($parametros['txt_postulante_cedula'] == "") {
            return -5;
        }
        // Checkbox: si no viene en POST vale null, así que lo convertimos a 1 o 0
        $alergia = isset($parametros['th_cer_alergia_req']) ? 1 : 0;
        $tratamiento = isset($parametros['th_cer_tratamiento_req']) ? 1 : 0;

        $datos = array(
            array('campo' => 'th_pos_id',                        'dato' => $parametros['txt_postulante_id']),
            array('campo' => 'th_cer_motivo_certificado',        'dato' => $parametros['th_cer_motivo_certificado']),
            array('campo' => 'th_cer_observaciones',        'dato' => $parametros['txt_observaciones_medico']),
            array('campo' => 'th_cer_alergia_req',               'dato' => $alergia),
            array('campo' => 'th_cer_tratamiento_req',           'dato' => $tratamiento),
            // Campos que no existen en el modal → NULL
            array('campo' => 'th_cer_nom_medico',                'dato' => null),
            array('campo' => 'th_cer_ins_medico',                'dato' => null),
            array('campo' => 'th_cer_fecha_inicio_certificado',  'dato' => null),
            array('campo' => 'th_cer_fecha_fin_certificado',     'dato' => null),
        );

        $id_certificados_medicos = $parametros['txt_certificados_medicos_id'];

        if ($id_certificados_medicos == '') {
            // INSERTAR
            $id_nuevo = $this->modelo->insertar_id($datos);
            // Solo guardar archivo si el usuario subió uno
            if (!empty($file['th_cer_ruta_certficado']['tmp_name'])) {
                $this->guardar_archivo($file, $parametros, $id_nuevo);
            }
            return 1;
        } else {
            // EDITAR
            $where = array(
                array('campo' => 'th_cer_id', 'dato' => $id_certificados_medicos),
            );

            $this->modelo->editar($datos, $where);

            // Solo reemplazar archivo si subió uno nuevo
            if (!empty($file['th_cer_ruta_certficado']['tmp_name'])) {
                $this->guardar_archivo($file, $parametros, $id_certificados_medicos);
            }

            return 1;
        }
    }
    function eliminar($id)
    {
        $datos_archivo = $this->modelo->where('th_cer_id', $id)->where('th_cer_estado', 1)->listar();

        if ($datos_archivo && isset($datos_archivo[0]['th_cer_ruta_certficado'])) {
            $ruta_relativa = ltrim($datos_archivo[0]['th_cer_ruta_certficado'], './');
            $ruta_archivo = dirname(__DIR__, 3) . '/' . $ruta_relativa;

            if (file_exists($ruta_archivo)) {
                unlink($ruta_archivo);
            }
        }

        $datos = array(
            array('campo' => 'th_cer_estado', 'dato' => 0),
        );

        $where = array(
            array('campo' => 'th_cer_id', 'dato' => strval($id)),
        );


        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    private function guardar_archivo($file, $post, $id_insertar_editar)
    {
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $ruta = dirname(__DIR__, 3) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/'; //ruta carpeta donde queremos copiar los archivos
        $ruta .= $post['txt_postulante_cedula'] . '/' . 'CERTIFICADOS_MEDICOS/';


        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        if ($this->validar_formato_archivo($file) === 1) {
            $uploadfile_temporal = $file['th_cer_ruta_certficado']['tmp_name'];
            $extension = pathinfo($file['th_cer_ruta_certficado']['name'], PATHINFO_EXTENSION);
            //Para referencias laborales
            $nombre = 'certificados_medicos_' . $id_insertar_editar . '.' . $extension;
            $nuevo_nom = $ruta . $nombre;

            $nombre_ruta = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $post['txt_postulante_cedula'] . '/' . 'CERTIFICADOS_MEDICOS/';
            $nombre_ruta .= $nombre;
            //print_r($post); exit(); die();

            if (is_uploaded_file($uploadfile_temporal)) {
                if (move_uploaded_file($uploadfile_temporal, $nuevo_nom)) {

                    $datos = array(
                        array('campo' => 'th_cer_ruta_certficado', 'dato' => $nombre_ruta),
                    );

                    $where = array(
                        array('campo' => 'th_cer_id', 'dato' => $id_insertar_editar),
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
        switch ($file['th_cer_ruta_certficado']['type']) {
            case 'application/pdf':
                return 1;
                break;
            default:
                return -1;
                break;
        }
    }
}
