<?php

require_once dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/ac_articulos_itM.php';
require_once dirname(__DIR__, 2) . '/db/codigos_globales.php';

$controlador = new articulosItC();

// Listar activos (o por ID si se pasa)
if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_articulos($_POST['id'] ?? ''));
}

// Listar todos (incluso inactivos)


// Buscar por texto en sistema_op o serie_numero
if (isset($_GET['buscar'])) {
    $q = $_POST['q'] ?? '';
    echo json_encode($controlador->buscar_articulos($q));
}

// Buscar por MAC exacta
if (isset($_GET['buscar_mac'])) {
    $mac = $_POST['mac'] ?? '';
    echo json_encode($controlador->buscar_por_mac($mac));
}

if (isset($_GET['guardar'])) {
    echo json_encode($controlador->insertar_editar($_POST));
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = intval($_POST['id'] ?? 0);
    echo json_encode($controlador->eliminar($id));
}


class articulosItC
{
    private $modelo;
    private $codGlobal;

    public function __construct()
    {
        $this->modelo    = new articulositM();
        $this->codGlobal = new codigos_globales();
    }

    public function lista_articulos($id)
    {
        return $this->modelo->cargar_datos_it($id);
    }
    public function buscar_articulos($q)
    {
        return $this->modelo->buscar_articulos($q);
    }

    public function buscar_por_mac($mac)
    {
        return $this->modelo->buscar_por_mac($mac);
    }

   public function insertar_editar(array $parametros)
{
    // 1) Prepara el array de datos usando los nombres de input “txt_…”
    $datos = [];

    $datos[] = [
        'campo' => 'ac_ait_id_articulo',
        'dato'  => intval($parametros['txt_id_articulo'] ?? 0)
    ];
    $datos[] = [
        'campo' => 'ac_ait_sistema_op',
        'dato'  => $parametros['txt_sistema_op'] ?? null
    ];
    $datos[] = [
        'campo' => 'ac_ait_arquitectura',
        'dato'  => $parametros['txt_arquitectura'] ?? null
    ];
    $datos[] = [
        'campo' => 'ac_ait_kernel',
        'dato'  => $parametros['txt_kernel'] ?? null
    ];
    $datos[] = [
        'campo' => 'ac_ait_producto_id',
        'dato'  => $parametros['txt_producto_id'] ?? null
    ];
    $datos[] = [
        'campo' => 'ac_ait_mac_address',
        'dato'  => $parametros['txt_mac_address'] ?? null
    ];
    $datos[] = [
        'campo' => 'ac_ait_version',
        'dato'  => $parametros['txt_version'] ?? null
    ];
    $datos[] = [
        'campo' => 'ac_ait_service_pack',
        'dato'  => $parametros['txt_service_pack'] ?? null
    ];
    $datos[] = [
        'campo' => 'ac_ait_edicion',
        'dato'  => $parametros['txt_edicion'] ?? null
    ];
    $datos[] = [
        'campo' => 'ac_ait_serie_numero',
        'dato'  => $parametros['txt_serie_numbre'] ?? null
    ];
    $datos[] = [
        'campo' => 'ac_ait_ip_address',
        'dato'  => $parametros['txt_ip_address'] ?? null
    ];

    // 2) Insertar o actualizar según txt_id (que equivale a ac_ait_id)
    if (empty($parametros['txt_id_articulo_IT'])) {
        $result    = $this->modelo->insertar($datos);
        $movimiento = 'Insertado artículo TI (MAC: ' 
            . ($parametros['txt_mac_address'] ?? '') . ')';
    } else {
        $where = [
            ['campo' => 'ac_ait_id', 'dato' => intval($parametros['txt_id_articulo_IT'])]
        ];
        $movimiento = 'Editado artículo TI ID ' . intval($parametros['txt_id_articulo_IT']);
        $result = $this->modelo->editar($datos, $where);
    }

    // 3) Registrar movimiento si fue exitoso
    if ($result === 1) {
        $this->codGlobal->ingresar_movimientos(
            false,
            $movimiento,
            'ARTICULOS_TI'
        );
    }

    return $result;
}


    public function eliminar(int $id)
    {
        return $this->modelo->eliminar($id);
    }
}
