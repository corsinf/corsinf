<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/FIRMADOR/th_cat_tipo_firmaM.php');

$controlador = new th_cat_tipo_firmasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
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


class th_cat_tipo_firmasC
{

    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_tipo_firmaM();
    }

    // Método para listar registros; si se pasa un id, lista ese registro, de lo contrario solo los activos (estado = 1)
    function listar()
    {
        $datos = $this->modelo->listar();
        return $datos;
    }

    public function buscar($parametros) {
        $lista = [];
        $camposBusqueda = [
            'th_tipfir_id', 'th_tipfir_descripcion'
        ];
        
        // Filtramos los datos con LIKE en los campos especificados
        $datos = $this->modelo->where('th_tipfir_perfir_estado', 1)
                              ->like(implode(',', $camposBusqueda), $parametros['query']);

        foreach ($datos as $value) {
            $text = "{$value['th_tipfir_descripcion']}";
            $lista[] = [
                'id' => $value['th_tipfir_id'],
                'text' => $text
            ];
        }
        return $lista;
    }

    
}