<?php

require_once dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/ac_articulos_itM.php';
require_once dirname(__DIR__, 2) . '/db/codigos_globales.php';

$controlador = new articulosItC();

// Listar activos (o por ID si se pasa)
if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_articulos($_POST['id'] ?? ''));
}

if (isset($_GET['guardar'])) {
    echo json_encode($controlador->insertar_editar($_POST));
}


class articulosItC
{
    private $modelo;
    private $codGlobal;

    public function __construct()
    {
        $this->modelo    = new ac_articulos_itM();
        $this->codGlobal = new codigos_globales();
    }

    public function lista_articulos($id)
    {
        //print_r($id); exit;
        $datos = $this->modelo->where('ac_ait_id_articulo', $id)->listar();
        return $datos;
    }


    function insertar_editar($parametros)
    {
        $datos = array(
            array('campo' => 'ac_ait_id_articulo', 'dato' => intval($parametros['txt_id_articulo'])),
            array('campo' => 'ac_ait_sistema_op', 'dato' => $parametros['txt_sistema_op']),
            array('campo' => 'ac_ait_arquitectura', 'dato' => $parametros['txt_arquitectura']),
            array('campo' => 'ac_ait_kernel', 'dato' => $parametros['txt_kernel']),
            array('campo' => 'ac_ait_producto_id', 'dato' => $parametros['txt_producto_id']),
            array('campo' => 'ac_ait_mac_address', 'dato' => $parametros['txt_mac_address']),
            array('campo' => 'ac_ait_version', 'dato' => $parametros['txt_version']),
            array('campo' => 'ac_ait_service_pack', 'dato' => $parametros['txt_service_pack']),
            array('campo' => 'ac_ait_edicion', 'dato' => $parametros['txt_edicion']),
            array('campo' => 'ac_ait_serie_numero', 'dato' => $parametros['txt_serie_numbre']),
            array('campo' => 'ac_ait_ip_address', 'dato' => $parametros['txt_ip_address']),
        );

        if ($parametros['txt_id_articulo_IT'] == '') {
            $result = $this->modelo->insertar($datos);
        } else {
            $where = array(
                array('campo' => 'ac_ait_id', 'dato' => intval($parametros['txt_id_articulo_IT']))
            );
            $result = $this->modelo->editar($datos, $where);
        }

        return $result;
    }
}
