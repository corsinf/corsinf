<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_pos_referencias_laboralesM.php');

$controlador = new th_pos_referencias_laboralesC();

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


class th_pos_referencias_laboralesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_referencias_laboralesM();
    }

    //Funcion para listar la formacion academica del postulante
    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_refl_estado', 1)->orderBy('th_refl_nombre_referencia', 'DESC')->listar();

        $texto = '';
        foreach ($datos as $key => $value) {
            $url_pdf = '../REPOSITORIO/TALENTO_HUMANO.pdf';

            $texto .=
                <<<HTML
                    <div class="row mb-3">
                        <div class="col-10">
                            <h6 class="fw-bold my-0 d-flex align-items-center">{$value['th_refl_nombre_referencia']}</h6>
                            <p class="my-0 d-flex align-items-center">{$value['th_refl_telefono_referencia']}</p>
                            <a href="#" onclick="definir_ruta_iframe_referencias_laborales('{$value['th_refl_carta_recomendacion']}');">Ver Carta de Recomendación</a>
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-center">
                            <button class="btn" style="color: white;" onclick="abrir_modal_referencias_laborales('{$value['_id']}')">
                                <i class="text-dark bx bx-pencil bx-sm"></i>
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

        $datos = array(
            array('campo' => 'th_refl_nombre_referencia', 'dato' => $parametros['txt_nombre_referencia']),
            array('campo' => 'th_refl_telefono_referencia', 'dato' => $parametros['txt_telefono_referencia']),
            //array('campo' => 'th_refl_carta_recomendacion', 'dato' => $parametros['txt_copia_carta_recomendacion']), 
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_postulante_id']),
            array('campo' => 'th_refl_correo', 'dato' => $parametros['txt_referencia_correo']),
            array('campo' => 'th_refl_nombre_empresa', 'dato' => $parametros['txt_referencia_nombre_empresa']),
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
            $ruta_archivo = dirname(__DIR__, 4) . '/' . $ruta_relativa;

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
        $ruta = dirname(__DIR__, 4) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/'; //ruta carpeta donde queremos copiar los archivos
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
