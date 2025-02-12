<?php
include('../modelo/avaluo_articuloM.php');

$controlador = new avaluo_articuloC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['listarTabla'])) {
    echo json_encode($controlador->listarTabla($_POST['id']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

class avaluo_articuloC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new avaluo_articuloM();
    }

    function listar($id)
    {
        $datos = $this->modelo->listar($id);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'id_plantilla', 'dato' => strval($parametros['txt_id_art_avaluo'])),
            array('campo' => 'valor', 'dato' => ($parametros['txt_valor_art'])),
            array('campo' => 'observacion', 'dato' => $parametros['txt_obs_art']),
            array('campo' => 'usu_id', 'dato' => $_SESSION['INICIO']['ID_USUARIO']),
        );

        $datos = $this->modelo->insertar($datos);

        return $datos;
    }

    function eliminar($id)
    {
        $datos = $this->modelo->eliminar($id);
        return $datos;
    }


    public function listarTabla($id)
    {
        $datos = $this->modelo->listar($id);
        $html = '';

        $count_avaluo = 0;
        $hoy = date('Y-m-d'); // Obtener la fecha de hoy en el formato Y-m-d

        foreach ($datos as $avaluo) {
            $count_avaluo++;
            //$fecha_creacion = htmlspecialchars($avaluo['fecha_creacion']);
            $fecha_creacion = (new DateTime($avaluo['fecha_creacion']))->format('Y-m-d');
            $valor = htmlspecialchars($avaluo['valor']);
            $observacion = htmlspecialchars($avaluo['observacion']);
            $id_avaluo = htmlspecialchars($avaluo['id_avaluo']);
            $id_plantilla = htmlspecialchars($avaluo['id_plantilla']);

            $boton_eliminar = '<td width="5%"><button type="button" class="btn btn-danger btn-sm" onclick="eliminarAvaluo(' . $id_avaluo . '); cargarAvaluo(' . $id_plantilla . ');"><i class="bx bx-trash me-0"></i></button></td>';
            $boton_eliminar_val = '';

            if (
                strtoupper($_SESSION['INICIO']['TIPO']) == 'EVALUADOR'
                || strtoupper($_SESSION['INICIO']['TIPO']) == 'DBA'
            ) {
                $boton_eliminar_val = ($fecha_creacion !== $hoy) ? '<td></td>' : $boton_eliminar;
            } else {
                $boton_eliminar_val = '<td></td>';
            }

            $htmlFila = '<tr>';
            $htmlFila .= '<td width="20%"><label>' . $fecha_creacion . '</label></td>';
            $htmlFila .= '<td width="20%"><label>' . '$ ' . $valor . '</label></td>';
            $htmlFila .= '<td width="53%"><label>' . $observacion . '</label></td>';
            $htmlFila .= $boton_eliminar_val;
            $htmlFila .= '</tr>';

            $html .= $htmlFila;
        }

        //return $hoy . '-'. $fecha_creacion;
        return $html;
    }
}
