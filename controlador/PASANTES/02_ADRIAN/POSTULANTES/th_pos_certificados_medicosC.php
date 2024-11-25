<?php
require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificados_medicosM.php');

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
       
        $this-> modelo = new th_pos_certificados_medicosM();
    }

    //Funcion para listar los certidicados médicos del postulante
    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_cer_estado', 1)->listar();
        
        

        $texto = '';
        foreach ($datos as $key => $value) {

            
            $texto .=
                <<<HTML
                    <div class="row mb-col">
                        <div class="col-10">
                            <h6 class="fw-bold mt-3 mb-2">{$value['th_cer_motivo_certificado']}</h6>
                            <h6 class="fw-bold mt-3 mb-2">{$value['th_cer_nom_medico']}</h6>
                            <h6 class="fw-bold mt-3 mb-2">{$value['th_cer_ins_medico']}</h6>
                            <h6 class="fw-bold mt-3 mb-2">{$value['th_cer_fecha_inicio_certificado']}</h6>
                            <h6 class="fw-bold mt-3 mb-2">{$value['th_cer_fecha_fin_certificado']}</h6>
                            <a href="#" onclick="ruta_iframe_certificado_medico('{$value['th_poi_ruta_archivo']}');">Ver Certificado Médico</a>
                          
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-start">
                            <button class="btn" style="color: white;" onclick="abrir_modal_certificado_medico('{$value['_id']}');">
                                <i class="text-dark bx bx-pencil bx-sm"></i>
                            </button>
                        </div>
                    </div>


                HTML;
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
        $datos = array(
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_postulante_id']),
            array('campo' => 'th_cer_motivo_certificado', 'dato' => $parametros['txt_med_motivo_certificado']),
            array('campo' => 'th_cer_nom_medico', 'dato' => $parametros['txt_med_nom_medico']),
            array('campo' => 'th_cer_ins_medico', 'dato' => $parametros['txt_med_ins_medico']),
            array('campo' => 'th_cer_fecha_inicio_certificado', 'dato' => $parametros['txt_med_fecha_inicio_certificado']),
            array('campo' => 'th_cer_fecha_fin_certificado', 'dato' => $parametros['txt_med_fecha_fin_certificado']),
        );

        $id_certificados_medicos = $parametros['txt_certificados_medicos_id'];

        if ($id_certificados_medicos == '') {
            $datos = $this->modelo->insertar_id($datos);
            $this->guardar_archivo($file, $parametros, $datos);
            return 1;
        } else {

            $where = array(
                array('campo' => 'th_cer_id', 'dato' => $id_certificados_medicos),
            );

            $datos = $this->modelo->editar($datos, $where);

            if ($file['txt_ruta_certificados_medicos']['tmp_name'] != '' && $file['txt_ruta_certificados_medicos']['tmp_name'] != null) {
                $datos = $this->guardar_archivo($file, $parametros, $id_certificados_medicos);
            }
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos_archivo = $this->modelo->where('th_cer_id', $id)->where('th_cer_estado', 1)->listar();

        if ($datos_archivo && isset($datos_archivo[0]['th_cer_ruta_certficado'])) {
            $ruta_relativa = ltrim($datos_archivo[0]['th_cer_ruta_certficado'], './');
            $ruta_archivo = dirname(__DIR__, 4) . '/' . $ruta_relativa;

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
        $ruta = dirname(__DIR__, 4) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/'; //ruta carpeta donde queremos copiar los archivos
        $ruta .= $post['txt_postulante_cedula'] . '/' . 'CERTIFICADOS_MEDICOS/';


        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        if ($this->validar_formato_archivo($file) === 1) {
            $uploadfile_temporal = $file['txt_ruta_certificados_medicos']['tmp_name'];
            $extension = pathinfo($file['txt_ruta_certificados_medicos']['name'], PATHINFO_EXTENSION);
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
        switch ($file['txt_ruta_certificados_medicos']['type']) {
            case 'application/pdf':
                return 1;
                break;
            default:
                return -1;
                break;
        }
    }
}
