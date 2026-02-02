<?php
require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/POSTULANTES/th_pos_discapacidadM.php');

$controlador = new th_pos_discapacidadC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->guardar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class th_pos_discapacidadC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_pos_discapacidadM();
    }

    function listar($id)
    {
        $datos = $this->modelo->listar_discapacidad_postulante($id);

        $texto = '<div class="row g-3">';

        if (empty($datos)) {
            return '<div class="alert alert-info border-0 shadow-sm" >No registra discapacidad.</div>';
        }

        foreach ($datos as $value) {

            $texto .= <<<HTML
                            <div class="col-md-6 mb-col">
                                <div class="cert-card p-3 h-100 position-relative shadow-sm">
                                    
                                    <button class="btn btn-sm btn-edit-minimal position-absolute top-0 end-0 m-2" 
                                            onclick="abrir_modal_discapacidad('{$value['_id']}');" 
                                            title="Editar Discapacidad">
                                        <i class="bx bx-pencil"></i>
                                    </button>

                                    <div class="d-flex flex-column h-100">
                                        <div class="mb-2">
                                            <span class="cert-badge mb-1">Discapacidad</span>
                                            
                                            <h6 class="fw-bold text-dark cert-title mb-1">
                                                {$value['discapacidad']}
                                            </h6>
                                            
                                            <p class="cert-doctor m-0">
                                                <i class="bx bx-layer me-1"></i>Escala: {$value['escala_discapacidad']}
                                            </p>
                                        </div>

                                        <div class="mt-auto pt-2">
                                            <div class="d-flex align-items-center justify-content-between p-2" 
                                                style="background: rgba(13, 110, 253, 0.05); border-radius: 8px; border: 1px dashed rgba(13, 110, 253, 0.2);">
                                                
                                                <div class="cert-date-range">
                                                    <div class="cert-label-small" style="color: #0d6efd;">Porcentaje</div>
                                                    <span class="fw-bold" style="font-size: 1.2rem; color: #0d6efd;">
                                                        {$value['th_pos_dis_porcentaje']}<small class="fw-normal">%</small>
                                                    </span>
                                                </div>

                                                <div class="text-primary opacity-50">
                                                    <i class="bx bxs-pie-chart-alt-2 bx-sm"></i>
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


    function listar_modal($id)
    {
        return $this->modelo->listar_discapacidad_postulante(null,$id);
    }

    function guardar($parametros)
    {
        $id_postulante = $parametros['pos_id'];
        $id_discapacidad = $parametros['ddl_discapacidad'];
        $id_escala = $parametros['ddl_discapacidad_escala'];
        $id_registro = $parametros['_id'];

        $this->modelo->reset();
        $this->modelo->where('th_pos_id', $id_postulante)
            ->where('id_discapacidad', $id_discapacidad)
            ->where('id_escala_dis', $id_escala);

        if ($id_registro != '') {
            $this->modelo->where('th_pos_dis_id !', $id_registro);
        }

        $existe = $this->modelo->listar();

        if (count($existe) > 0) {
            return -2;
        }

        $datos = [
            ['campo' => 'th_pos_id', 'dato' => $id_postulante],
            ['campo' => 'id_discapacidad', 'dato' => $id_discapacidad],
            ['campo' => 'id_escala_dis', 'dato' => $id_escala],
            ['campo' => 'th_pos_dis_porcentaje', 'dato' => $parametros['txt_porcentaje']],
        ];

        if ($id_registro == '') {
            return $this->modelo->insertar($datos);
        } else {
            $where[] = [
                'campo' => 'th_pos_dis_id',
                'dato'  => $id_registro
            ];
            return $this->modelo->editar($datos, $where);
        }
    }


    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_pos_dis_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_pos_dis_id';
        $where[0]['dato'] = strval($id);
        $datos = $this->modelo->eliminar($where);

        return $datos;
    }
}
