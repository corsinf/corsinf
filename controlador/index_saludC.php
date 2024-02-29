<?php
include('../modelo/index_saludM.php');

$controlador = new index_saludC();
if (isset($_GET['total_pacientes'])) {
    echo json_encode($controlador->total_pacientes());
}

if (isset($_GET['total_docentes'])) {
    echo json_encode($controlador->total_docentes());
}

if (isset($_GET['total_estudiantes'])) {
    echo json_encode($controlador->total_estudiantes());
}

if (isset($_GET['total_comunidad'])) {
    echo json_encode($controlador->total_comunidad());
}

if (isset($_GET['total_Agendas'])) {
    echo json_encode($controlador->total_Agendas());
}

if (isset($_GET['total_medicamentos'])) {
    echo json_encode($controlador->total_medicamentos());
}

if (isset($_GET['total_insumos'])) {
    echo json_encode($controlador->total_insumos());
}

if (isset($_GET['lista_medicamentos'])) {
    echo json_encode($controlador->lista_medicamentos());
}

if (isset($_GET['lista_insumos'])) {
    echo json_encode($controlador->lista_insumos());
}

if (isset($_GET['total_consultas'])) {
    echo json_encode($controlador->total_consultas());
}

if (isset($_GET['pacientes_atendidos'])) {
    echo json_encode($controlador->pacientes_atendidos());
}

if (isset($_GET['estudiantes_atendidos'])) {
    echo json_encode($controlador->estudiantes_atendidos($_POST['id_representante']));
}


//echo json_encode($controlador->insertar_editar('Ejemplo1'));

class index_saludC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new index_saludM();
    }

    function total_pacientes()
    {
        return $this->modelo->total_pacientes();
    }

    function total_docentes()
    {
        return $this->modelo->total_docentes();
    }

    function total_estudiantes()
    {
        return $this->modelo->total_estudiantes();
    }

    function total_comunidad()
    {
        return $this->modelo->total_comunidad();
    }

    function total_Agendas()
    {
        return $this->modelo->total_Agendas();
    }

    function total_medicamentos()
    {
        return $this->modelo->total_medicamentos();
    }

    function lista_medicamentos()
    {
        $data = array();
        $cate = array();
        $datos =  $this->modelo->lista_medicamentos();
        $alertas = '';
        foreach ($datos as $key => $value) {
            $cate[] = $value['sa_cmed_presentacion'];
            $data[] = $value['sa_cmed_stock'];
            if ($value['sa_cmed_stock'] >= $value['sa_cmed_minimos'] && $value['sa_cmed_stock'] < ($value['sa_cmed_minimos'] + 10)) {
                $alertas .= '<div class="alert alert-warning border-0 bg-warning alert-dismissible fade show">
                                    <div class="text-dark">' . $value['sa_cmed_presentacion'] . ' Proximo a agotarse</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
            } else if ($value['sa_cmed_stock'] < $value['sa_cmed_minimos']) {
                $alertas .= '<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                    <div class="text-white">' . $value['sa_cmed_presentacion'] . ' Agotado</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
            }
        }
        return array('data' => $data, 'cate' => $cate, 'alertas' => $alertas);
    }

    function lista_insumos()
    {
        $data = array();
        $cate = array();
        $datos = $this->modelo->lista_insumos();
        $alertas = '';
        foreach ($datos as $key => $value) {
            $cate[] = $value['sa_cins_presentacion'];
            $data[] = $value['sa_cins_stock'];
            if ($value['sa_cins_stock'] >= $value['sa_cins_minimos'] && $value['sa_cins_stock'] < ($value['sa_cins_minimos'] + 10)) {
                $alertas .= '<div class="alert alert-warning border-0 bg-warning alert-dismissible fade show">
                                    <div class="text-dark">' . $value['sa_cins_presentacion'] . ' Proximo a agotarse</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
            } else if ($value['sa_cins_stock'] < $value['sa_cins_minimos']) {
                $alertas .= '<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                                    <div class="text-white">' . $value['sa_cins_presentacion'] . ' Agotado</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
            }
        }
        return array('data' => $data, 'cate' => $cate, 'alertas' => $alertas);
    }

    function total_insumos()
    {
        return $this->modelo->total_insumos();
    }

    function total_consultas()
    {
        return $this->modelo->total_consultas();
    }

    function pacientes_atendidos()
    {
        $tipo = array();
        $cant = array();
        $datos = $this->modelo->pacientes_atendidos();
        foreach ($datos as $key => $value) {
            $tipo[] = $value['tipo'];
            $cant[] = $value['total'];
        }

        return array('tipo' => $tipo, 'cant' => $cant);
    }

    function estudiantes_atendidos($id_representante)
    {
        $estudiante = array();
        $atenciones = array();
        $datos = $this->modelo->estudiantes_atendidos($id_representante);
        foreach ($datos as $key => $value) {
            $estudiante[] = $value['nombre_estudiante'];
            $atenciones[] = $value['consultas_total'];
        }

        return array('estudiante' => $estudiante, 'atenciones' => $atenciones);
    }


}
