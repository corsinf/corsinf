<?php
require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_formacion_academicaM.php');

$controlador = new th_pos_formacion_academicaC();

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


class th_pos_formacion_academicaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_formacion_academicaM();
    }

    //Funcion para listar la formacion academica del postulante
    function listar($id)
    {
        $datos = $this->modelo->listar_formacion_academica_con_nivel_id($id);

        $texto = '<div class="row g-3">';

        foreach ($datos as $value) {

            // Lógica de fechas
            $fecha_inicio_estudio = !empty($value['th_fora_fecha_inicio_formacion'])
                ? date('d/m/Y', strtotime($value['th_fora_fecha_inicio_formacion']))
                : '';

            $fecha_fin_estudio = empty($value['th_fora_fecha_fin_formacion'])
                ? '<span class="fw-bold text-primary">Actualidad</span>'
                : date('d/m/Y', strtotime($value['th_fora_fecha_fin_formacion']));

            $nivel = !empty($value['nivel_academico_descripcion'])
                ? $value['nivel_academico_descripcion']
                : 'No especificado';

            $senescyt = !empty($value['th_fora_registro_senescyt'])
                ? " | SENESCYT: {$value['th_fora_registro_senescyt']}"
                : '';

            $texto .= <<<HTML
                            <div class="col-md-6 mb-col">
                                <div class="cert-card p-3 h-100 position-relative shadow-sm">
                                    
                                    <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                                            onclick="abrir_modal_formacion_academica('{$value['_id']}');" 
                                            title="Editar Formación">
                                        <i class="bx bx-pencil"></i>
                                    </button>

                                    <div class="d-flex flex-column h-100">
                                        <div class="mb-2">
                                            <span class="cert-badge mb-1">Instrucción Formal</span>
                                            
                                            <h6 class="fw-bold text-dark cert-title mb-1">
                                                {$value['th_fora_titulo_obtenido']}
                                            </h6>
                                            
                                            <p class="cert-doctor mb-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.3px;">
                                                <i class="bx bx-buildings me-1"></i>{$value['th_fora_institución']}
                                            </p>

                                            <p class="m-0 text-muted" style="font-size: 0.75rem;">
                                                <i class="bx bx-graduation me-1"></i>Nivel: <strong>{$nivel}</strong>
                                            </p>
                                        </div>

                                        <div class="mt-auto pt-2">
                                            <div class="d-flex align-items-center justify-content-between p-2" 
                                                style="background: rgba(255, 193, 7, 0.05); border-radius: 8px; border: 1px dashed rgba(255, 193, 7, 0.3);">
                                                
                                                <div class="cert-date-range">
                                                    <div class="cert-label-small" style="color: #856404;">Periodo y Registro</div>
                                                    <span class="text-dark" style="font-size: 0.65rem;">
                                                        <i class="bx bx-calendar me-1"></i>{$fecha_inicio_estudio} — {$fecha_fin_estudio}{$senescyt}
                                                    </span>
                                                </div>

                                                <div style="color: #ffc107; opacity: 0.6;">
                                                    <i class="bx bxs-certification bx-sm"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        HTML;
        }

        $texto .= '</div>';

        return $texto;
    }


    //Buscando registros por id de la formacion academica
    function listar_modal($id)
    {

        if ($id == '') {
            $datos = $this->modelo->where('th_fora_estado', 1)->listar();
        } else {
            $datos = $this->modelo->listar_formacion_academica_con_nivel($id);
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'th_fora_titulo_obtenido', 'dato' => $parametros['txt_titulo_obtenido']),
            array('campo' => 'th_fora_institución', 'dato' => $parametros['txt_institucion']),
            array('campo' => 'th_fora_fecha_inicio_formacion', 'dato' => $parametros['txt_fecha_inicio_academico']),
            array('campo' => 'th_fora_fecha_fin_formacion', 'dato' => $parametros['txt_fecha_final_academico']),
            array('campo' => 'th_pos_id', 'dato' => $parametros['txt_id_postulante']),
            array('campo' => 'th_fora_registro_senescyt', 'dato' => $parametros['txt_fora_registro_senescyt']),
            array('campo' => 'id_nivel_academico', 'dato' => $parametros['ddl_nivel_academico']),
        );

        if ($parametros['_id'] == '') {
            $datos = $this->modelo->insertar($datos);
        } else {
            $where[0]['campo'] = 'th_fora_id';
            $where[0]['dato'] = $parametros['_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {

        $datos = array(
            array('campo' => 'th_fora_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_fora_id';
        $where[0]['dato'] = strval($id);

        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }
}
