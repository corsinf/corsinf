<?php
require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_certificaciones_capacitacionesM.php');

$controlador = new th_pos_certificaciones_capacitacionesC();

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


class th_pos_certificaciones_capacitacionesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_certificaciones_capacitacionesM();
    }

    //Funcion para listar la formacion academica del postulante
    function listar($id)
    {
        $datos = $this->modelo->listar_certificaciones_postulante($id);

        if (empty($datos)) {
            $texto = '<div class="alert alert-info mb-0"><p>No se encontraron certificaciones registradas.</p></div>';
        } else {
            $texto = '<div class="row g-3">';

            foreach ($datos as $value) {
                // Lógica de fechas
                $fecha_desde = !empty($value['th_cert_fecha_desde'])
                    ? date('d/m/Y', strtotime($value['th_cert_fecha_desde']))
                    : '';

                $es_actualidad = ($value['th_cert_sigue_cursando'] == 1 || $value['th_cert_fecha_hasta'] == '1900-01-01');

                $fecha_hasta = ($es_actualidad)
                    ? '<span class="fw-bold text-primary">Actualidad</span>'
                    : (!empty($value['th_cert_fecha_hasta']) ? date('d/m/Y', strtotime($value['th_cert_fecha_hasta'])) : '');

                $texto .= <<<HTML
            <div class="col-md-6 mb-col">
                <div class="cert-card p-3 h-100 position-relative shadow-sm">
                    
                    <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                            onclick="abrir_modal_certificaciones_capacitaciones('{$value['_id']}')" 
                            title="Editar Certificación">
                        <i class="bx bx-pencil text-primary"></i>
                    </button>

                    <div class="d-flex flex-column h-100">
                        <div class="mb-2">
                            <span class="cert-badge mb-1">Capacitación / Certificación</span>
                            
                            <h6 class="fw-bold text-dark cert-title mb-1">
                                {$value['th_cert_nombre_curso']}
                            </h6>
                            
                            <p class="cert-doctor mb-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.3px;">
                                <i class="bx bx-bookmark-alt me-1"></i>{$value['nombre_evento_certificado']}
                            </p>

                            <p class="m-0 text-muted" style="font-size: 0.75rem;">
                                <i class="bx bx-award me-1"></i>Logro: <strong>{$value['nombre_certificado']}</strong>
                            </p>
                            <p class="m-0 text-muted" style="font-size: 0.75rem;">
                                <i class="bx bx-map-pin me-1"></i>País: <strong>{$value['nombre_pais']}</strong>
                            </p>
                        </div>

                        <div class="mt-auto pt-2">
                            <div class="d-flex align-items-center justify-content-between p-2" 
                                 style="background: rgba(13, 110, 253, 0.05); border-radius: 8px; border: 1px dashed rgba(13, 110, 253, 0.3);">
                                
                                <div class="cert-date-range">
                                    <div class="cert-label-small" style="color: #0d6efd;">Periodo y Carga Horaria</div>
                                    <span class="text-dark" style="font-size: 0.65rem;">
                                        <i class="bx bx-calendar me-1"></i>{$fecha_desde} — {$fecha_hasta} | 
                                        <i class="bx bx-time-five me-1"></i>{$value['th_cert_duracion_horas']} Horas
                                    </span>
                                </div>

                                <button onclick="ruta_iframe_certificaciones('{$value['th_cert_ruta_archivo']}');" 
                                        data-bs-toggle="modal" data-bs-target="#modal_ver_pdf_certificaciones"
                                        class="btn btn-dark btn-xs py-1 px-2" style="font-size: 0.65rem;">
                                    DOCUMENTO
                                </button>
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

    function listar_modal($id)
    {
        if ($id == '') {
            // Por seguridad, si no hay ID, limitamos la búsqueda o devolvemos vacío
            $datos = [];
        } else {
            $datos = $this->modelo->listar_certificaciones_postulante(null, $id);
        }
        return $datos;
    }

    function insertar_editar($file, $parametros)
    {
        if ($parametros['txt_postulante_cedula'] == "") {
            return -5;
        }

        $fecha_hasta = $parametros['txt_fecha_final_capacitacion'];

        // Determinamos si es actualidad por el checkbox O por la fecha específica
        $sigue_cursando = 0;
        if ((isset($parametros['cbx_fecha_final_capacitacion']) && $parametros['cbx_fecha_final_capacitacion'] == '1') ||
            $fecha_hasta == '1900-01-01' || $fecha_hasta == ''
        ) {
            $sigue_cursando = 1;
            $fecha_hasta = '1900-01-01'; // Normalizamos para la base de datos
        }
        $datos = array(
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_postulante_id']),
            array('campo' => 'th_cert_nombre_curso', 'dato' => $parametros['txt_nombre_curso']),
            array('campo' => 'th_cert_duracion_horas', 'dato' => $parametros['txt_duracion_horas'] ?? 0),
            array('campo' => 'th_cert_fecha_desde', 'dato' => $parametros['txt_fecha_inicio_capacitacion']),
            array('campo' => 'th_cert_fecha_hasta', 'dato' => $parametros['txt_fecha_final_capacitacion']),
            array('campo' => 'th_cert_sigue_cursando', 'dato' => $sigue_cursando),
            array('campo' => 'id_certificado', 'dato' => $parametros['ddl_certificado']),
            array('campo' => 'id_evento_cert', 'dato' => $parametros['ddl_evento_cert']),
            array('campo' => 'id_pais', 'dato' => $parametros['ddl_pais_cerficacion']),
        );

        $id_certificaciones_capacitaciones = $parametros['txt_certificaciones_capacitaciones_id'];

        if ($id_certificaciones_capacitaciones == '') {
            $id_nuevo = $this->modelo->insertar_id($datos);
            $this->guardar_archivo($file, $parametros, $id_nuevo);
            return 1;
        } else {
            $where = array(
                array('campo' => 'th_cert_id', 'dato' => $id_certificaciones_capacitaciones),
            );
            $res = $this->modelo->editar($datos, $where);

            if (isset($file['txt_ruta_archivo']['tmp_name']) && $file['txt_ruta_archivo']['tmp_name'] != '') {
                $this->guardar_archivo($file, $parametros, $id_certificaciones_capacitaciones);
            }
            return $res;
        }
    }



    function eliminar($id)
    {
        $datos_archivo = $this->modelo->where('th_cert_id', $id)->where('th_cert_estado', 1)->listar();

        if ($datos_archivo && isset($datos_archivo[0]['th_cert_ruta_archivo'])) {
            $ruta_relativa = ltrim($datos_archivo[0]['th_cert_ruta_archivo'], './');
            $ruta_archivo = dirname(__DIR__, 3) . '/' . $ruta_relativa;

            if (file_exists($ruta_archivo)) {
                unlink($ruta_archivo);
            }
        }

        $datos = array(
            array('campo' => 'th_cert_estado', 'dato' => 0),
        );

        $where = array(
            array('campo' => 'th_cert_id', 'dato' => strval($id)),
        );


        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    private function guardar_archivo($file, $post, $id_insertar_editar)
    {
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $ruta = dirname(__DIR__, 3) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/'; //ruta carpeta donde queremos copiar los archivos
        $ruta .= $post['txt_postulante_cedula'] . '/' . 'CERTIFICACIONES_CAPACITACIONES/';

        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        if ($this->validar_formato_archivo($file) === 1) {
            $uploadfile_temporal = $file['txt_ruta_archivo']['tmp_name'];
            $extension = pathinfo($file['txt_ruta_archivo']['name'], PATHINFO_EXTENSION);
            //Para CERTIFICACIONES y CAPACITACIONES
            $nombre = 'certificaciones_capacitaciones_' . $id_insertar_editar . '.' . $extension;
            $nuevo_nom = $ruta . $nombre;

            $nombre_ruta = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $post['txt_postulante_cedula'] . '/' . 'CERTIFICACIONES_CAPACITACIONES/';
            $nombre_ruta .= $nombre;
            //print_r($post); exit(); die();

            if (is_uploaded_file($uploadfile_temporal)) {
                if (move_uploaded_file($uploadfile_temporal, $nuevo_nom)) {

                    $datos = array(
                        array('campo' => 'th_cert_ruta_archivo', 'dato' => $nombre_ruta),
                    );

                    $where = array(
                        array('campo' => 'th_cert_id', 'dato' => $id_insertar_editar),
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
        switch ($file['txt_ruta_archivo']['type']) {
            case 'application/pdf':
                return 1;
                break;
            default:
                return -1;
                break;
        }
    }
}
