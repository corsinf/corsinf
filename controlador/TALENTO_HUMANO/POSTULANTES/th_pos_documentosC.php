<?php
require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_documentosM.php');

$controlador = new th_pos_documentosC();

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


class th_pos_documentosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_documentosM();
    }

    //Funcion para listar los documentos de identificación del postulante
    function listar($id)
    {
        $datos = $this->modelo->listar_por_id($id);


        if (empty($datos)) {
            $texto = '<div class="alert alert-info mb-0">No hay documentos registrados.</div>';
        } else {
            foreach ($datos as $key => $value) {
                $texto = '<div class="row g-3">';


                // Corregido: Usar el alias 'estado' definido en el SQL
                $documentos_repetidos = '';
                if ($value['estado'] == 1) {
                    $documentos_repetidos = '<input type="hidden" name="documentos_identidad[]" value="' . $value['id_documento'] . '">';
                }

                $texto .= <<<HTML
                <div class="col-md-6 mb-col">
                    <div class="cert-card p-3 h-100 position-relative shadow-sm">
                        
                        <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                                onclick="abrir_modal_documentos_identidad('{$value['_id']}');" 
                                title="Editar Identificación">
                            <i class="bx bx-pencil"></i>
                        </button>

                        <div class="d-flex flex-column h-100">
                            <div class="mb-2">
                                <span class="cert-badge mb-1">Documento</span>
                                <h6 class="fw-bold text-dark cert-title mb-1">
                                    {$value['nombre_documento']}
                                </h6>
                            </div>

                            <div class="mt-auto pt-2 d-flex justify-content-between align-items-end">
                                <div class="cert-date-range">
                                    <span class="text-success" style="font-size: 0.7rem;">
                                        <i class="bx bxs-check-shield me-1"></i>Activo
                                    </span>
                                </div>
                                
                                <button onclick="ruta_iframe_documento_identificacion('{$value['th_poi_ruta_archivo']}');" 
                                        class="btn btn-dark btn-xs py-1 px-3 btn-cert-action">
                                    DOCUMENTO
                                </button>
                            </div>
                        </div>

                        {$documentos_repetidos}
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
            $datos = $this->modelo->where('th_poi_estado', 1)->listar();
        } else {
            $datos = $this->modelo->listar_por_id_modal($id);
        }
        return $datos;
    }

    function insertar_editar($file, $parametros)
    {
        $datos = array(
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_postulante_id']),
            array('campo' => 'id_documento', 'dato' => $parametros['ddl_tipo_documento_identidad']),
        );

        $id_documentos_identidad = $parametros['txt_documentos_identificacion_id'];

        if ($id_documentos_identidad == '') {
            $datos = $this->modelo->insertar_id($datos);
            $this->guardar_archivo($file, $parametros, $datos);
            return 1;
        } else {

            $where = array(
                array('campo' => 'th_poi_id', 'dato' => $id_documentos_identidad),
            );

            $datos = $this->modelo->editar($datos, $where);

            if ($file['txt_ruta_documentos_identidad']['tmp_name'] != '' && $file['txt_ruta_documentos_identidad']['tmp_name'] != null) {
                $datos = $this->guardar_archivo($file, $parametros, $id_documentos_identidad);
            }
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos_archivo = $this->modelo->where('th_poi_id', $id)->where('th_poi_estado', 1)->listar();

        if ($datos_archivo && isset($datos_archivo[0]['th_poi_ruta_archivo'])) {
            $ruta_relativa = ltrim($datos_archivo[0]['th_poi_ruta_archivo'], './');
            $ruta_archivo = dirname(__DIR__, 3) . '/' . $ruta_relativa;

            if (file_exists($ruta_archivo)) {
                unlink($ruta_archivo);
            }
        }

        $datos = array(
            array('campo' => 'th_poi_estado', 'dato' => 0),
        );

        $where = array(
            array('campo' => 'th_poi_id', 'dato' => strval($id)),
        );


        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    private function guardar_archivo($file, $post, $id_insertar_editar)
    {
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $ruta = dirname(__DIR__, 3) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/'; //ruta carpeta donde queremos copiar los archivos
        $ruta .= $post['txt_postulante_cedula'] . '/' . 'DOCUMENTOS_IDENTIDAD/';


        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        if ($this->validar_formato_archivo($file) === 1) {
            $uploadfile_temporal = $file['txt_ruta_documentos_identidad']['tmp_name'];
            $extension = pathinfo($file['txt_ruta_documentos_identidad']['name'], PATHINFO_EXTENSION);
            //Para referencias laborales
            $nombre = 'documentos_identidad_' . $id_insertar_editar . '.' . $extension;
            $nuevo_nom = $ruta . $nombre;

            $nombre_ruta = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $post['txt_postulante_cedula'] . '/' . 'DOCUMENTOS_IDENTIDAD/';
            $nombre_ruta .= $nombre;
            //print_r($post); exit(); die();

            if (is_uploaded_file($uploadfile_temporal)) {
                if (move_uploaded_file($uploadfile_temporal, $nuevo_nom)) {

                    $datos = array(
                        array('campo' => 'th_poi_ruta_archivo', 'dato' => $nombre_ruta),
                    );

                    $where = array(
                        array('campo' => 'th_poi_id', 'dato' => $id_insertar_editar),
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
        switch ($file['txt_ruta_documentos_identidad']['type']) {
            case 'application/pdf':
                return 1;
                break;
            default:
                return -1;
                break;
        }
    }
}
