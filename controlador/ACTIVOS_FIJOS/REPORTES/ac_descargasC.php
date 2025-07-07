<?php
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/REPORTES/ac_descargasM.php');
require_once(dirname(__DIR__, 3) . '/db/codigos_globales.php');

require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/articulosM.php');
require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/ac_articulos_itM.php');


$controlador = new ac_descargasC();

if (isset($_GET['lista_drop'])) {
    $q = isset($_GET['q']) ? $_GET['q'] : '';
    $lote = isset($_GET['lote']) ? $_GET['lote'] : 'lote_1'; // Por defecto lote_1
    echo json_encode($controlador->lista_lote_drop($lote, $q));
}

if (isset($_GET['descargar_pdf'])) {
    $parametros = $_GET;
    $controlador->descargar($parametros);
    exit;
}



class ac_descargasC
{
    private $descargas;
    private $articulos;
    private $ac_articulos_itM;


    public function __construct()
    {
        $this->descargas = new ac_descargasM();
        $this->articulos = new articulosM();
        $this->ac_articulos_itM = new ac_articulos_itM();
    }

    function lista_lote_drop($lote, $q)
    {
        $datos = $this->descargas->listar_datos_lote($lote, $q);
        $datos2 = array();

        foreach ($datos as $value) {
            $datos2[] = array(
                'id' => $value['numero_lote'],
                'text' => $value['numero_lote']
            );
        }

        return $datos2;
    }

    function descargar($parametros)
    {
        if ($parametros['rbx_tipo_carga'] == 'individual') {
            // Buscar los registros que quieres procesar
            $datos = $this->descargas->buscar_lote($parametros['rbx_lote_tipo'], $parametros['ddl_lote']);

            $archivosPDF = [];

            // Carpeta donde guardar los PDFs
            $carpetaPDFs = dirname(__DIR__, 3) . '/REPOSITORIO/TEMP/cedulas_activos/';

            // Asegurarse que la carpeta existe
            if (!is_dir($carpetaPDFs)) {
                mkdir($carpetaPDFs, 0777, true);
            }

            foreach ($datos as $fila) {
                $id_articulo = $fila['id_articulo'];

                // Ruta del PDF individual
                $rutaPDF = $carpetaPDFs . 'cedula_activo_' . 'SKU_' . $fila['tag_serie'] . '.pdf';

                // print_r($rutaPDF); exit(); die();

                // Generar y guardar el PDF en disco
                $this->reporte_cedula_activo($id_articulo, false, $rutaPDF);

                // Añadir al array
                $archivosPDF[] = $rutaPDF;
            }

            // Nombre y ruta del ZIP
            $zipNombre = 'cedulas_activos_' . date('Ymd_His') . '.zip';
            $zipRuta = $carpetaPDFs . $zipNombre;

            // Crear el ZIP
            $zip = new ZipArchive();
            if ($zip->open($zipRuta, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                foreach ($archivosPDF as $file) {
                    $zip->addFile($file, basename($file));
                }
                $zip->close();

                // Forzar descarga del ZIP
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $zipNombre . '"');
                header('Content-Length: ' . filesize($zipRuta));
                readfile($zipRuta);

                // IMPORTANTE: Después de readfile, eliminar todo
                foreach ($archivosPDF as $file) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
                if (file_exists($zipRuta)) {
                    unlink($zipRuta);
                }

                exit;
            } else {
                echo "Error al crear el ZIP.";
            }
        } else if ($parametros['rbx_tipo_carga'] == 'masivo') {
            $datos = $this->descargas->buscar_lote($parametros['rbx_lote_tipo'], $parametros['ddl_lote']);

            $ids = [];
            $articulos_array = [];
            $datos_it_array = [];

            foreach ($datos as $fila) {
                $id = $fila['id_articulo'];
                $ids[] = $id;

                $articulos = $this->articulos->listar_articulos_id($id);
                $datos_it = $this->ac_articulos_itM->where('ac_ait_id_articulo', $id)->listar();

                $articulos_array[] = $articulos;
                $datos_it_array[] = $datos_it;
            }

            return $this->reporte_todo_en_uno($ids, $articulos_array, $datos_it_array);
        }
    }

    function reporte_cedula_activo($id_articulo, $mostrar = false, $local)
    {
        require_once('DOCUMENTOS/reporte_cedula_activo.php');

        $articulos = $this->articulos->listar_articulos_id($id_articulo);

        $datos_articulo_it = $this->ac_articulos_itM->where('ac_ait_id_articulo', $id_articulo)->listar();
        // print_r($datos_articulo_it); exit(); die();

        return pdf_cedula_activo($articulos, $datos_articulo_it, $mostrar, $local);
    }

    function reporte_todo_en_uno($ids, $articulos_array, $datos_it_array)
    {
        require_once('DOCUMENTOS/reporte_cedula_activo.php');

        return crear_pdf_todo_en_uno($ids, $articulos_array, $datos_it_array);
    }
}
