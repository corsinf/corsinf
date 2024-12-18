<?php
require_once(dirname(__DIR__, 2) . '/modelo/FIRMAS_ELECTRONICAS/fi_cat_formulariosM.php');

$controlador = new fi_cat_formulariosC();

if (isset($_GET['listar_ddl'])) {
    echo json_encode($controlador->listar_ddl());
}

class fi_cat_formulariosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new fi_cat_formulariosM();
    }

    function listar_ddl()
    {
        //htmlspecialchar -> para evitar vulnerabilidades
        $datos = $this->modelo->listar();

        if (!$datos) {
            return '';
        }

        $opciones = '';
        foreach ($datos as $value) {
            $id = htmlspecialchars($value['_id'], ENT_QUOTES, 'UTF-8');
            $nombre = htmlspecialchars($value['nombre'], ENT_QUOTES, 'UTF-8');
            $opciones .=
                <<<HTML
                    <option id="ddl_opcion_{$id}" value="{$id}">{$nombre}</option>
                HTML;
        }

        return $opciones;
    }
}
