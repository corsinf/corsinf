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

    function listar($id)
    {
        // Usamos la función unificada del modelo
        $datos = $this->modelo->listar_idiomas_completo($id);

        if (empty($datos)) {
            return <<<HTML
                        <div class="d-flex align-items-center bg-white border border-start-0 shadow-sm rounded-3" 
                            style="border-left: 4px solid !important; padding: 12px 24px; max-width: 600px;">
                        
                        <i class='bx bx-data me-3 text-primary' style='font-size: 28px;'></i>

                        <div class="lh-sm">
                            <div class="text-dark fw-bold mb-1" style="font-size: 1rem;">
                            Sin registros en este apartado
                            </div>
                            <div class="text-secondary" style="font-size: 0.85rem;">
                            No hemos encontrado información disponible para mostrar en esta sección.
                            </div>
                        </div>
                        </div>
                    HTML;
        } else {
            $texto = '<div class="row g-3">';

            foreach ($datos as $key => $value) {
                // Formato de fecha de inicio
                $fecha_inicio_idioma = !empty($value['th_idi_fecha_inicio_idioma'])
                    ? date('d/m/Y', strtotime($value['th_idi_fecha_inicio_idioma']))
                    : '';

                // Lógica para determinar si es "Actualidad"
                $es_actualidad = ($value['th_idi_actualidad'] == 1 || $value['th_idi_fecha_fin_idioma'] == '1900-01-01');

                $fecha_fin_idioma = ($es_actualidad)
                    ? '<span class="fw-bold text-primary">Actualidad</span>'
                    : (!empty($value['th_idi_fecha_fin_idioma']) ? date('d/m/Y', strtotime($value['th_idi_fecha_fin_idioma'])) : '');

                $texto .= <<<HTML
                                <div class="col-md-6 mb-col">
                                    <div class="cert-card p-3 h-100 position-relative shadow-sm">
                                        
                                        <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                                                onclick="abrir_modal_idiomas('{$value['_id']}');" 
                                                title="Editar Idioma">
                                            <i class="bx bx-pencil text-primary"></i>
                                        </button>

                                        <div class="d-flex flex-column h-100">
                                            <div class="mb-2">
                                                <span class="cert-badge mb-1" style="background-color: #f3e5f5; color: #6610f2;">Dominio de Idioma</span>
                                                
                                                <h6 class="fw-bold text-dark cert-title mb-1">
                                                    {$value['nombre_idioma']}
                                                </h6>
                                                
                                                <p class="cert-doctor m-0" style="font-size: 0.8rem;">
                                                    <i class="bx bx-medal me-1"></i>Nivel: <strong>{$value['nivel_idioma_descripcion']}</strong>
                                                </p>
                                                
                                                <p class="text-muted m-0" style="font-size: 0.75rem;">
                                                    <i class="bx bx-buildings me-1"></i>Institución: <strong>{$value['th_idi_institucion']}</strong>
                                                </p>

                                                <p class="text-muted m-0" style="font-size: 0.75rem;">
                                                    <i class="bx bx-certification me-1"></i>Certificado: <strong>{$value['nombre_certificacion']}</strong>
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
            $datos = $this->modelo->listar_idiomas_completo(null, $id);
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {
        // Capturamos la fecha de fin enviada desde el formulario de idiomas
        $fecha_hasta = $parametros['txt_fecha_fin_idioma'];
        $sigue_cursando = 0;

        // Lógica para determinar si está cursando actualmente
        // Revisamos el checkbox, si la fecha es la de defecto (1900) o si viene vacía
        if ((isset($parametros['cbx_fecha_final_idioma']) && $parametros['cbx_fecha_final_idioma'] == '1') ||
            $fecha_hasta == '1900-01-01' || $fecha_hasta == ''
        ) {
            $sigue_cursando = 1;
            $fecha_hasta = '1900-01-01'; // Normalizamos para la base de datos
        }

        $datos = array(
            array('campo' => 'th_pos_id', 'dato' => $parametros['id_postulante']),
            array('campo' => 'id_idiomas', 'dato' => $parametros['ddl_idiomas']),
            array('campo' => 'id_idiomas_nivel', 'dato' => $parametros['ddl_idiomas_nivel']),
            array('campo' => 'th_idi_institucion', 'dato' => $parametros['txt_institucion_1']),
            array('campo' => 'th_idi_fecha_inicio_idioma', 'dato' => $parametros['txt_fecha_inicio_idioma']),
            array('campo' => 'th_idi_fecha_fin_idioma', 'dato' => $fecha_hasta),
            array('campo' => 'th_idi_actualidad', 'dato' => $sigue_cursando),
            array('campo' => 'id_certificacion', 'dato' => $parametros['ddl_certificacion_idioma'] ?? null),
        );

        if ($parametros['_id'] == '') {
            $res = $this->modelo->insertar($datos);
        } else {
            $where = array(
                array('campo' => 'th_idi_id', 'dato' => $parametros['_id'])
            );
            $res = $this->modelo->editar($datos, $where);
        }

        return $res;
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
