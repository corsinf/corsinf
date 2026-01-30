<?php
require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_idiomasM.php');

$controlador = new th_pos_idiomasC();

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


class th_pos_idiomasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_idiomasM();
    }

    //Funcion para listar los idiomas del postulante
    function listar($id)
    {
        // $datos = $this->modelo->where('th_pos_id', $id)->listar();
        // return $datos;

        //Formato de ordenamiento de idiomas por fechas
        $datos = $this->modelo->where('th_pos_id', $id)->where('th_idi_estado', 1)->orderBy('th_idi_fecha_fin_idioma', 'DESC')->listar();

         if (empty($datos)) {
            $texto = '<div  class="alert alert-info mb-0"><p>No hay información adicional registrada.</p></div>';
        } else {
        $texto = '<div class="row g-3">';

        foreach ($datos as $key => $value) {
            // Formato de fechas
            $fecha_inicio_idioma = date('d/m/Y', strtotime($value['th_idi_fecha_inicio_idioma']));
            $fecha_fin_idioma = date('d/m/Y', strtotime($value['th_idi_fecha_fin_idioma']));

            $texto .= <<<HTML
                            <div class="col-md-6 mb-col">
                                <div class="cert-card p-3 h-100 position-relative shadow-sm">
                                    
                                    <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                                            onclick="abrir_modal_idiomas('{$value['_id']}');" 
                                            title="Editar Idioma">
                                        <i class="bx bx-pencil"></i>
                                    </button>

                                    <div class="d-flex flex-column h-100">
                                        <div class="mb-2">
                                            <span class="cert-badge mb-1">Idioma</span>
                                            
                                            <h6 class="fw-bold text-dark cert-title mb-1">
                                                {$value['th_idi_nombre_idioma']}
                                            </h6>
                                            
                                            <p class="cert-doctor m-0">
                                                <i class="bx bx-medal me-1"></i>Nivel: <strong>{$value['th_idi_nivel']}</strong>
                                            </p>
                                            <p class="text-muted m-0" style="font-size: 0.75rem;">
                                                <i class="bx bx-buildings me-1"></i>{$value['th_idi_institucion']}
                                            </p>
                                        </div>

                                        <div class="mt-auto pt-2">
                                            <div class="d-flex align-items-center justify-content-between p-2" 
                                                style="background: rgba(102, 16, 242, 0.05); border-radius: 8px; border: 1px dashed rgba(102, 16, 242, 0.2);">
                                                
                                                <div class="cert-date-range">
                                                    <div class="cert-label-small" style="color: #6610f2;">Periodo de estudio</div>
                                                    <span class="text-dark" style="font-size: 0.7rem;">
                                                        <i class="bx bx-calendar me-1"></i>{$fecha_inicio_idioma} — {$fecha_fin_idioma}
                                                    </span>
                                                </div>

                                                <div style="color: #6610f2; opacity: 0.5;">
                                                    <i class="bx bx-world bx-sm"></i>
                                                </div>
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
            $datos = $this->modelo->where('th_idi_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_idi_id', $id)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_pos_id', 'dato' => $parametros['id_postulante']),
            array('campo' => 'th_idi_nombre_idioma', 'dato' => $parametros['ddl_seleccionar_idioma']),
            array('campo' => 'th_idi_nivel', 'dato' => $parametros['ddl_dominio_idioma']),
            array('campo' => 'th_idi_institucion', 'dato' => $parametros['txt_institucion_1']),
            array('campo' => 'th_idi_fecha_inicio_idioma', 'dato' => $parametros['txt_fecha_inicio_idioma']),
            array('campo' => 'th_idi_fecha_fin_idioma', 'dato' => $parametros['txt_fecha_fin_idioma']),

        );

        // return $datos;
        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_idi_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_idi_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_idi_id';
        $where[0]['dato'] = strval($id);
        $datos = $this->modelo->eliminar($where);

        return $datos;
    }
}
