<?php
require_once(dirname(__DIR__, 2) . '/modelo/SALUD_INTEGRAL/index_saludM.php');

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

if (isset($_GET['tcp'])) {
    echo json_encode($controlador->tcp());
}

if (isset($_GET['lista_reuniones'])) {
    $tipo = $_POST['tipo'];
    $id_busqueda = $_POST['id_busqueda'];
    echo json_encode($controlador->lista_reuniones($tipo, $id_busqueda));
}

if (isset($_GET['lista_estado_reuniones'])) {
    $tipo = $_POST['tipo'];
    $id_busqueda = $_POST['id_busqueda'];
    echo json_encode($controlador->lista_estado_reuniones($tipo, $id_busqueda));
}

if (isset($_GET['total_horario_disponible'])) {
    $id_docente = $_POST['id_docente'];
    $estado = $_POST['estado'];
    echo json_encode($controlador->total_horario_disponible($id_docente, $estado));
}

if (isset($_GET['total_horario_clases'])) {
    $id_docente = $_POST['id_docente'] ?? '';
    echo json_encode($controlador->total_horario_clases($id_docente));
}

if (isset($_GET['total_clases'])) {
    $id_docente = $_POST['id_docente'] ?? '';
    echo json_encode($controlador->total_clases($id_docente));
}

if (isset($_GET['total_historial_estudiantil_docente'])) {
    $id_docente = $_POST['id_docente'] ?? '';
    echo json_encode($controlador->total_historial_estudiantil_docente($id_docente));
}

if (isset($_GET['horario_clases'])) {
    echo json_encode($controlador->lista_horario_clases_paralelo($_GET['id_paralelo']));
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

    function tcp()
    {
        // print_r('hola');die();
            $ip = '192.168.1.6'; // Dirección IP del servidor 
            // $ip = '186.4.219.172'; // Dirección IP del servidor
            $puerto = 15300; // Puerto en el que el servidor está escuchando
            $mensaje = '1'; // Mensaje a enviar

            // Abre una conexión TCP/IP
            $socket = fsockopen($ip, $puerto, $errno, $errstr, 30);
            if (!$socket) {
                return "Error al abrir el socket: $errstr ($errno)\n";
            } else {
                // Escribe el mensaje en el socket
                fwrite($socket, $mensaje);
                
                // Lee la respuesta del servidor (opcional)
                $respuesta = fread($socket, 1024);
                return "Respuesta del servidor: $respuesta\n";
                
                // Cierra la conexión
                fclose($socket);
            }
    }

    function lista_reuniones($tipo, $id_busqueda)
    {
        $motivo = array();
        $total_motivos = array();
        $datos = $this->modelo->reuniones_realizadas($tipo, $id_busqueda);
        foreach ($datos as $key => $value) {
            $motivo[] = $value['motivo'];
            $total_motivos[] = $value['total_motivos'];
        }

        return array('motivo' => $motivo, 'total_motivos' => $total_motivos);
    }

    function lista_estado_reuniones($tipo, $id_busqueda)
    {
        $estado = array();
        $total_estados = array();
        $datos = $this->modelo->estado_reuniones($tipo, $id_busqueda);
        foreach ($datos as $key => $value) {
            $estado[] = $value['estado'];
            $total_estados[] = $value['total_estados'];
        }

        return array('estado' => $estado, 'total_estados' => $total_estados);
    }

    function total_horario_disponible($id_docente, $estado)
    {
        return $this->modelo->total_horario_dispoible($id_docente, $estado);
    }

    function total_horario_clases($id_docente)
    {
        return $this->modelo->total_horario_clases($id_docente);
    }

    function total_clases($id_docente)
    {
        return $this->modelo->total_clases($id_docente);
    }

    function total_historial_estudiantil_docente($id_docente)
    {
        return $this->modelo->total_historial_estudiantil_docente($id_docente);
    }

    function lista_horario_clases_paralelo($id_paralelo)
    {
        $datos = $this->modelo->lista_horario_clases_paralelo($id_paralelo);
        return $datos;
    }

}
