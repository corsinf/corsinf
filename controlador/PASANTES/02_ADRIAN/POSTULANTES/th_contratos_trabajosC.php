<?php

require_once(dirname(__DIR__, 4) . '/modelo/PASANTES/02_ADRIAN/POSTULANTES/th_contratos_trabajosM.php');

$controlador = new th_contratos_trabajosC();

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

class th_contratos_trabajosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contratos_trabajosM();
    }

    //Funcion para listar la formacion academica del postulante
    function listar($id)
    {
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_ctr_estado', 1)->listar();

        $texto = '';
        foreach ($datos as $key => $value) {
            $url_pdf = '../REPOSITORIO/TALENTO_HUMANO.pdf';

            $texto .=
                <<<HTML
                    <div class="row mb-3">
                        <div class="col-10">
                            <p class="fw-bold my-0 d-flex align-items-center">{$value['th_ctr_nombre_empresa']}</p>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal_ver_pdf_contratos" onclick="definir_ruta_iframe_contratos('{$value['th_ctr_ruta_archivo']}');">Ver Certifiacado o Capacitacion PDF</a>
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-center">
                            <button class="btn btn-xs" style="color: white;" onclick="abrir_modal_contratos_trabajos('{$value['_id']}')">
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
             $datos = $this->modelo->where('th_ctr_estado', 1)->listar();
         } else {
             $datos = $this->modelo->where('th_ctr_id', $id)->listar();
         }
         return $datos;
     }
 
     function insertar_editar($file, $parametros)
     {
         // print_r($file);
         // exit();
         // die();
         $datos = array(            
             array('campo' => 'th_ctr_nombre_empresa', 'dato' => $parametros['txt_nombre_empresa_contrato']),
             array('campo' => 'th_ctr_tipo_contrato', 'dato' => $parametros['txt_tipo_contrato']),
             array('campo' => 'th_ctr_fecha_inicio_contrato', 'dato' => $parametros['txt_fecha_inicio_contrato']),
             array('campo' => 'th_ctr_fecha_fin_contrato', 'dato' => $parametros['txt_fecha_fin_contrato']),
             array('campo' => 'th_pos_id', 'dato' => $parametros['txt_postulante_id']),
            
             array('campo' => 'th_ctr_cbx_fecha_fin_contrato', 'dato' => $parametros['cbx_fecha_fin_contrato']),
         );
 
         $in_stc_cbx_fecha_fin_contrato = (isset($parametros['cbx_fecha_fin_contrato']) && $parametros['cbx_fecha_fin_contrato'] == 'true') ? 1 : 0;
         
         $txt_id_contratos_trabajos = $parametros['txt_contratos_trabajos_id'];
 
         if ($txt_id_contratos_trabajos == '') {
             $datos = $this->modelo->insertar_id($datos);
             $this->guardar_archivo($file, $parametros, $datos);
             return 1;
         } else {
 
             $where = array(
                 array('campo' => 'th_ctr_id', 'dato' => $txt_id_contratos_trabajos),
             );
 
             $datos = $this->modelo->editar($datos, $where);
 
             if ($file['txt_ruta_archivo_contrato']['tmp_name'] != '' && $file['txt_ruta_archivo_contrato']['tmp_name'] != null) {
                 $datos = $this->guardar_archivo($file, $parametros, $txt_id_contratos_trabajos);
             }
         }
 
         return $datos;
     }
            
 
 
     function eliminar($id)
     {
         $datos_archivo = $this->modelo->where('th_ctr_id', $id)->where('th_ctr_estado', 1)->listar();
 
         if ($datos_archivo && isset($datos_archivo[0]['th_ctr_ruta_archivo'])) {
             $ruta_relativa = ltrim($datos_archivo[0]['th_ctr_ruta_archivo'], './');
             $ruta_archivo = dirname(__DIR__, 4) . '/' . $ruta_relativa;
 
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
         $ruta = dirname(__DIR__, 4) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/'; //ruta carpeta donde queremos copiar los archivos
         $ruta .= $post['txt_postulante_cedula'] . '/' . 'CONTRATOS_TRABAJOS/';
 
         if (!file_exists($ruta)) {
             mkdir($ruta, 0777, true);
         }
 
         if ($this->validar_formato_archivo($file) === 1) {
             $uploadfile_temporal = $file['txt_ruta_archivo_contrato']['tmp_name'];
             $extension = pathinfo($file['txt_ruta_archivo_contrato']['name'], PATHINFO_EXTENSION);
           
             $nombre = 'contratos_trabajos_' . $id_insertar_editar . '.' . $extension;
             $nuevo_nom = $ruta . $nombre;
 
             $nombre_ruta = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $post['txt_postulante_cedula'] . '/' . 'CONTRATOS_TRABAJOS/';
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