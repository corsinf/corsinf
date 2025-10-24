<?php

require_once(dirname(__DIR__, 3) . '/modelo/ACTIVOS_FIJOS/SIMULADORES/si_simulador_facturadosM.php');


$controlador = new si_simulador_facturadosC();


if (isset($_GET['lista_cr'])) {
    echo json_encode($controlador->lista_articulos_cr($_POST['search_value'] ?? ''));
}

if (isset($_GET['buscar'])) {
    $query = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}

class si_simulador_facturadosC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new si_simulador_facturadosM();
    }

    function lista_articulos_cr($sku)
    {
        return $this->modelo->obtener_articulo_simple($sku);
    }

    function buscar($parametros)
    {
        $lista = array();

        // Campos a buscar
        $campos = "RFID, nom";

        // Consulta: solo activos (si quieres puedes quitar la condición de estado)
        $datos = $this->modelo
        
            ->like($campos, $parametros['query']);

        foreach ($datos as $key => $value) {
            // Texto que se mostrará (puedes ajustarlo a tu necesidad)
            $text = $value['RFID'] . ' - ' . $value['nom'];

            $lista[] = array(
                'id' => ($value['id']),   // identificador único
                'text' => ($text),
                // 'data' => $value // opcional si quieres todo el registro
            );
        }

        return $lista;
    }
}
