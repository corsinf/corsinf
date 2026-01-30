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
        // Usamos la función con INNER JOIN que creamos en el modelo
        $datos = $this->modelo->listar_certificaciones_postulante($id);

        $texto = '<div class="row g-3">';

        if (empty($datos)) {
            $texto .= '<div class="col-12 text-center text-muted"><p>No se encontraron certificaciones registradas.</p></div>';
        } else {
            foreach ($datos as $key => $value) {
                $texto .= <<<HTML
            <div class="col-md-6 mb-col">
                <div class="cert-card p-3 h-100 position-relative shadow-sm border-start border-primary border-3">
                    
                    <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                            onclick="abrir_modal_certificaciones_capacitaciones('{$value['_id']}')" 
                            title="Editar Certificación">
                        <i class="bx bx-pencil text-primary"></i>
                    </button>

                    <div class="d-flex flex-column h-100">
                        <div class="mb-2">
                            <span class="badge bg-light text-primary mb-1" style="font-size: 0.65rem; text-transform: uppercase;">
                                {$value['nombre_evento_certificado']}
                            </span>
                            
                            <h6 class="fw-bold text-dark mb-1" style="line-height: 1.2;">
                                {$value['th_cert_nombre_curso']}
                            </h6>
                            
                            <p class="m-0 text-muted" style="font-size: 0.75rem;">
                                <i class="bx bx-award me-1"></i>{$value['nombre_certificado']} | 
                                <i class="bx bx-map-pin me-1"></i>{$value['nombre_pais']}
                            </p>
                        </div>

                        <div class="mt-auto pt-2 d-flex justify-content-between align-items-end">
                            <div class="cert-date-range">
                                <span class="text-success" style="font-size: 0.7rem;">
                                    <i class="bx bxs-check-shield me-1"></i>Vigente
                                </span>
                            </div>
                            
                            <button data-bs-toggle="modal" data-bs-target="#modal_ver_pdf_certificaciones" 
                                    onclick="definir_ruta_iframe_certificaciones('{$value['th_cert_ruta_archivo']}');" 
                                    class="btn btn-dark btn-xs py-1 px-3">
                                <i class="bx bx-show me-1"></i> VER PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
HTML;
            }
        }

        $texto .= '</div>';
        return $texto;
    }


    //Buscando registros por id de la formacion academica
    function listar_modal($id)
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_cert_estado', 1)->listar();
        } else {
            $datos = $this->modelo->listar_certificacion_postulante_id($id);
        }
        return $datos;
    }

    function insertar_editar($file, $parametros)
    {
        // print_r($file);
        // exit();
        // die();
        $datos = array(
            array('campo' => 'th_cert_nombre_curso', 'dato' => $parametros['txt_nombre_curso']),
            // array('campo' => 'th_cert_ruta_archivo', 'dato' => $parametros['txt_ruta_archivo']),
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_postulante_id']),
            array('campo' => 'id_certificado', 'dato' => $parametros['ddl_certificado']),
            array('campo' => 'id_evento_cert', 'dato' => $parametros['ddl_evento_cert']),
            array('campo' => 'id_pais', 'dato' => $parametros['ddl_pais_cerficacion']),
        );


        $id_certificaciones_capacitaciones = $parametros['txt_certificaciones_capacitaciones_id'];

        if ($id_certificaciones_capacitaciones == '') {
            $datos = $this->modelo->insertar_id($datos);
            $this->guardar_archivo($file, $parametros, $datos);
            return 1;
        } else {

            $where = array(
                array('campo' => 'th_cert_id', 'dato' => $id_certificaciones_capacitaciones),
            );

            $datos = $this->modelo->editar($datos, $where);

            if ($file['txt_ruta_archivo']['tmp_name'] != '' && $file['txt_ruta_archivo']['tmp_name'] != null) {
                $datos = $this->guardar_archivo($file, $parametros, $id_certificaciones_capacitaciones);
            }
        }

        return $datos;
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
