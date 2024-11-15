.<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_certificaciones_capacitacionesM.php');

$controlador = new th_certificaciones_capacitacionesC();

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


class th_certificaciones_capacitacionesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_certificaciones_capacitacionesM();
    }

    //Funcion para listar la formacion academica del postulante
    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_cert_estado', 1)->listar();

        $texto = '';
        foreach ($datos as $key => $value) {
            $url_pdf = '../REPOSITORIO/TALENTO_HUMANO.pdf';

            $texto .=
                <<<HTML
                    <div class="row mb-3">
                        <div class="col-10">
                            <p class="fw-bold my-0 d-flex align-items-center">{$value['th_cert_nombre_curso']}</p>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal_ver_pdf" onclick="definir_ruta_iframe('{$value['th_cert_enlace_certificado']}');">Ver Carta de Recomendación</a>
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-center">
                            <button class="btn btn-xs" style="color: white;" onclick="abrir_modal_certificaciones_capacitaciones('{$value['_id']}')">
                                <i class="text-dark bx bx-pencil me-0" style="font-size: 20px;"></i>
                            </button>
                        </div>
                    </div>
                HTML;
        }

        return $texto;
    }

    //Buscando registros por id de la formacion academica
    function listar_modal($id)
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_cert_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_cert_id', $id)->listar();
        }
        return $datos;
    }
    
    function insertar_editar($file, $parametros)
    {
        // Array de datos a insertar o editar
        $datos = array(
            array('campo' => 'th_cert_nombre_curso', 'dato' => $parametros['txt_nombre_curso']),
            array('campo' => 'th_cert_enlace_certificado', 'dato' => $parametros['txt_enlace_certificado']),
            array('campo' => 'th_cert_pdf_certificado', 'dato' => $parametros['txt_pdf_certificado']), 
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_postulante_id']),
        );
        
        // Obtener el ID de certificación (para editar si existe)
        $id_certificaciones_capacitaciones = $parametros['txt_certificaciones_capacitaciones_id'];
    
        // Si no se proporciona un ID (es una nueva inserción)
        if (empty($id_certificaciones_capacitaciones)) {
            // Inserta los datos y obtén el ID generado (esto debe retornar el nuevo ID)
            $id_certificaciones_capacitaciones = $this->modelo->insertar_id($datos);
    
            // Ahora, guardamos el archivo si existe
            if ($id_certificaciones_capacitaciones && isset($file['txt_pdf_certificado']) && $file['txt_pdf_certificado']['tmp_name']) {
                // Guardamos el archivo y actualizamos el campo con el archivo guardado
                $this->guardar_archivo($file, $parametros, $id_certificaciones_capacitaciones);
            }
    
            // Retorna el resultado de la inserción (ID de la nueva certificación)
            return $id_certificaciones_capacitaciones;
    
        } else {
            // Si el ID existe, significa que es una actualización
            $where = array(
                array('campo' => 'th_cert_id', 'dato' => $id_certificaciones_capacitaciones),
            );
    
            // Actualizamos los datos en la base de datos
            $this->modelo->editar($datos, $where);
    
            // Si hay un archivo PDF nuevo, lo guardamos
            if (isset($file['txt_pdf_certificado']) && $file['txt_pdf_certificado']['tmp_name']) {
                $this->guardar_archivo($file, $parametros, $id_certificaciones_capacitaciones);
            }
    
            // Retorna el ID de la certificación editada
            return $id_certificaciones_capacitaciones;
        }
    }
    
    function eliminar($id)
    {
        $datos_archivo = $this->modelo->where('th_cert_id', $id)->where('th_cert_estado', 1)->listar();

        if ($datos_archivo && isset($datos_archivo[0]['th_cert_pdf_certificado'])) {
            $ruta_relativa = ltrim($datos_archivo[0]['th_cert_pdf_certificado'], './');
            $ruta_archivo = dirname(__DIR__, 4) . '/' . $ruta_relativa;

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
        $ruta = dirname(__DIR__, 4) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/'; //ruta carpeta donde queremos copiar los archivos
        $ruta .= $post['txt_postulante_cedula'] . '/' . 'CERTIFICACIONES_CAPACITACIONES/';

        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        if ($this->validar_formato_archivo($file) === 1) {
            $uploadfile_temporal = $file['txt_pdf_certificado']['tmp_name'];
            $extension = pathinfo($file['txt_pdf_certificado']['name'], PATHINFO_EXTENSION);
            //Para CERTIFICACIONES y CAPACITACIONES
            $nombre = 'certificaciones_capacitaciones_' . $id_insertar_editar . '.' . $extension;
            $nuevo_nom = $ruta . $nombre;

            $nombre_ruta = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $post['txt_postulante_cedula'] . '/' . 'CERTIFICACIONES_CAPACITACIONES/';
            $nombre_ruta .= $nombre;
            //print_r($post); exit(); die();

            if (is_uploaded_file($uploadfile_temporal)) {
                if (move_uploaded_file($uploadfile_temporal, $nuevo_nom)) {

                    $datos = array(
                        array('campo' => 'th_cert_pdf_certificado', 'dato' => $nombre_ruta),
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
        switch ($file['txt_pdf_certificado']['type']) {
            case 'application/pdf':
                return 1;
                break;
            default:
                return -1;
                break;
        }
    }
}
