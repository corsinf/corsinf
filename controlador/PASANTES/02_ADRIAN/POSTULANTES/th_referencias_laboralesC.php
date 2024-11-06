<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_referencias_laboralesM.php');

$controlador = new th_referencias_laboralesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
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

if (isset($_GET['cargar_archivo'])) {
    echo json_encode($controlador->guardar_archivo($_FILES, $_POST));
}


class th_referencias_laboralesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_referencias_laboralesM();
    }

    //Funcion para listar la formacion academica del postulante
    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_refl_estado', 1)->listar();

        $texto = '';
        foreach ($datos as $key => $value) {

            $texto .=
                <<<HTML
                    <div class="row mb-3">
                        <div class="col-10">
                            <p class="fw-bold my-0 d-flex align-items-center">{$value['th_refl_nombre_referencia']}</p>
                            <p class="my-0 d-flex align-items-center">{$value['th_refl_telefono_referencia']}</p>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal_ver_pdf" onclick="definir_ruta_iframe({$value['_id']});">Ver Carta de Recomendación</a>
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-center">
                            <button class="btn btn-xs" style="color: white;" onclick="abrir_modal_referencias_laborales({$value['_id']})">
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
            $datos = $this->modelo->where('th_refl_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_refl_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_refl_nombre_referencia', 'dato' => $parametros['txt_nombre_referencia']),
            array('campo' => 'th_refl_telefono_referencia', 'dato' => $parametros['txt_telefono_referencia']),
            array('campo' => 'th_refl_carta_recomendacion', 'dato' => $parametros['txt_copia_carta_recomendacion']),
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_id_postulante']),

        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_refl_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {

        $datos = array(
            array('campo' => 'th_refl_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_refl_id';
        $where[0]['dato'] = strval($id);

        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }

    function guardar_archivo($file, $post)
    {
        $ruta = dirname(__DIR__, 4) . '/REPOSITORIO/talento_humano_1/'; //ruta carpeta donde queremos copiar las imágenes

        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        if ($this->validar_formato_archivo($file) === 1) {
            $uploadfile_temporal = $file['pos_ref_lab_file']['tmp_name'];
            $extension = pathinfo($file['pos_ref_lab_file']['name'], PATHINFO_EXTENSION);
            $nombre = 'referencia_laboral_' . $post['txt_id'] . '.' . $extension;
            $nuevo_nom = $ruta . $nombre;

            if (is_uploaded_file($uploadfile_temporal)) {
                if (move_uploaded_file($uploadfile_temporal, $nuevo_nom)) {

                    $datos = [
                        ['campo' => 'th_refl_carta_recomendacion', 'dato' => $nuevo_nom],
                    ];

                    $where = [
                        ['campo' => 'th_refl_id', 'dato' => $post['txt_id']],
                    ];

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
        switch ($file['pos_ref_lab_file']['type']) {
            case 'application/pdf':
                return 1;
                break;
            default:
                return -1;
                break;
        }
    }
}
