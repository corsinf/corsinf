<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');
require_once(dirname(__DIR__, 3) . '/assets/plugins/datatable/ssp.class.php');
require_once(dirname(__DIR__, 3) . '/db/codigos_globales.php');

class si_simulador_facturadosM  extends BaseModel
{


    protected $tabla = 'v_articulos_detalle';
    protected $primaryKey = 'id';

    protected $camposPermitidos = [
        'id',
        'tag',
        'RFID',
        'serie',
        'nom',
        'modelo',
        'imagen',
        'observacion',
        'fecha_in',
        'fecha_baja',
        'IDL',
        'localizacion',
        'loc_denominacion',
        'IDC',
        'custodio',
        'marca',
        'estado',
        'genero',
        'color',
        'id_tipo_articulo',
        'tipo_articulo',
        'tipo_articulo_color',
        'fecha_compra',
        'precio',
        'caracteristica'
    ];


    function lista_articulos_por_sku($sku)
    {
        $USUARIO_DB = $_SESSION['INICIO']['USUARIO_DB'];
        $PASSWORD_DB = $_SESSION['INICIO']['PASSWORD_DB'];
        $BASEDATO = $_SESSION['INICIO']['BASEDATO'];
        $PUERTO_DB = $_SESSION['INICIO']['PUERTO_DB'];
        $IP_HOST = $_SESSION['INICIO']['IP_HOST'] . ', ' . $PUERTO_DB;

        // Configuración de conexión
        $sql_details = array(
            'user' => $USUARIO_DB,
            'pass' => $PASSWORD_DB,
            'db'   => $BASEDATO,
            'host' => $IP_HOST,
        );

        $table = 'v_articulos_detalle';
        $primaryKey = 'id';

        $ruta = $_SESSION['INICIO']['RUTA_IMG_RELATIVA'];
        $empresa = $_SESSION['INICIO']['BASEDATO'];
        $ruta_img = $ruta . "emp=$empresa&dir=activos&nombre=";

        $columns = array(
            array(
                'db' => 'imagen',
                'dt' => 0,
                'formatter' => function ($d, $row) use ($ruta_img) {
                    $ruta_completa = $ruta_img . $row['imagen'];
                    $id = $row['id'];
                    return '<img src="' . $ruta_completa . '" 
                    alt="' . $row['nom'] . '" 
                    style="width:50px;height:auto;cursor:pointer;" 
                    class="rounded" 
                    onclick="modal_ver_imagen(\'' . $ruta_completa . '\', \'' . $id . '\')">';
                }
            ),
            array('db' => 'tag', 'dt' => 1), // SKU / Tag Serie
            array(
                'db' => 'nom',
                'dt' => 2,
                'formatter' => function ($d, $row) {
                    return '<a type="button" href="#" onclick="redireccionar(' . "'" . $row['id'] . "'" . ')"><u>' . $row['nom'] . '</u></a>';
                }
            ),
            array('db' => 'modelo', 'dt' => 3),
            array('db' => 'serie', 'dt' => 4),
            array('db' => 'RFID', 'dt' => 5),
            array('db' => 'localizacion', 'dt' => 6),
            array('db' => 'custodio', 'dt' => 7),
            array('db' => 'marca', 'dt' => 8),
            array('db' => 'estado', 'dt' => 9),
            array('db' => 'genero', 'dt' => 10),
            array('db' => 'color', 'dt' => 11),
            array('db' => 'fecha_in', 'dt' => 12),
            array('db' => 'observacion', 'dt' => 13),
            array('db' => 'id', 'dt' => 14),
            array('db' => 'tipo_articulo', 'dt' => 15),
            array('db' => 'tipo_articulo_COLOR', 'dt' => 16)
        );

        $whereResult = "";
        $whereAll = "";

        if ($sku != '') {
            $whereAll = "tag = '$sku'";  // FILTRO POR SKU
        }

        // Columnas habilitadas para búsqueda
        $columnSearch = [1, 2, 4]; // Incluyo SKU(tag), nombre y serie

        return (
            SSP::complex($_POST, $sql_details, $table, $primaryKey, $columns, $whereResult, $whereAll, $columnSearch, true)
        );
    }


    function obtener_articulo_simple($RFID)
    {
        $RFID = addslashes($RFID); // Evitar inyección SQL

        $sql = "SELECT TOP (1)
    id,
    tag,
    RFID,
    serie,
    nom,
    modelo,
    imagen,
    observacion,
    fecha_in,
    fecha_baja,
    IDL,
    localizacion,
    loc_denominacion,
    IDC,
    custodio,
    marca,
    estado,
    genero,
    color,
    id_tipo_articulo,
    tipo_articulo,
    tipo_articulo_color,
    fecha_compra,
    precio,
    caracteristica
    FROM v_articulos_detalle
     WHERE RFID = '$RFID'";


        return $this->db->datos($sql); // Devuelve los datos según tu método de acceso
    }

    function obtener_articulos_rfid()
    {
        $sql = "SELECT 
        RFID,
        nom,
       
    FROM v_articulos_detalle
    WHERE RFID != '0' AND LENGTH(RFID) > 2;";

        return $this->db->datos($sql);
    }
}
