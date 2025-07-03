<?php

require_once dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/detalle_articuloM.php';
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
    private $detalle_articuloM;
    private $cod_globales;

    public function __construct()
    {
        $this->detalle_articuloM    = new detalle_articuloM();
        $this->modelo    = new ac_articulos_itM();
        $this->cod_globales = new codigos_globales();
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
            array('campo' => 'ac_ait_sku', 'dato' => $parametros['txt_ac_ait_sku']),
        );

        if (empty($parametros['txt_id_articulo_IT'])) {
            $existe = $this->modelo->where('ac_ait_id_articulo', $parametros['txt_id_articulo'])->listar();

            if (count($existe) == 0) {
                $datos = $this->modelo->insertar($datos);
                $this->editar_is_it($parametros['txt_id_articulo']);
                $movimiento = "Se cambio IT de 0 a 1";
                $this->cod_globales->ingresar_movimientos($parametros['txt_id_articulo'], $movimiento, 'ARTICULOS', 0, 1, '', '', $_SESSION['INICIO']['USUARIO'] ?? '');
            } else {
                return -2;
            }
        } else {
            $where = [
                ['campo' => 'ac_ait_id', 'dato' => $parametros['txt_id_articulo_IT']]
            ];

            $datos = $this->modelo->editar($datos, $where);
            $this->editar_is_it($parametros['txt_id_articulo']);
        }

        return $datos;
    }


    function editar_is_it($idArticulo)
    {
        $datos = array(
            array('campo' => 'es_it', 'dato' => 1),
        );


        $where = array(
            array('campo' => 'id_articulo', 'dato' => intval($idArticulo))
        );
        $result = $this->detalle_articuloM->editar($datos, $where);

        return $result;
    }
}
