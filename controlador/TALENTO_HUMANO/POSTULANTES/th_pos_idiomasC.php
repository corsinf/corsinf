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
     
        $texto = '';
        
        foreach ($datos as $key => $value) {
            //Formato de fechas de idiomas
            $fecha_inicio_idioma = date('d/m/Y', strtotime($value['th_idi_fecha_inicio_idioma']));
            $fecha_fin_idioma = date('d/m/Y', strtotime($value['th_idi_fecha_fin_idioma']));
            
            $texto .= 
                <<<HTML
                    <div class="row mb-col">
                        <div class="col-10">
                            <h6 class="fw-bold mt-3 mb-2">{$value['th_idi_nombre_idioma']}</h6>
                            <p class="m-0">{$value['th_idi_nivel']}</p>
                            <p class="m-0">{$value['th_idi_institucion']} </p>
                            <p class="m-0">{$fecha_inicio_idioma} - {$fecha_fin_idioma}</p>
                        </div>
                        <div class="col-2 d-flex justify-content-end align-items-center">
                            <button class="btn icon-hover" style="color: white;" onclick="abrir_modal_idiomas({$value['_id']});">
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
