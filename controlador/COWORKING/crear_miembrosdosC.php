<?php
// Incluir el archivo que contiene la clase crear_oficinaM
require_once(dirname(__DIR__, 2) . '/modelo/COWORKING/crear_oficinaM.php');

include(__DIR__ . '/../../modelo/COWORKING/crear_miembrosdosM.php');


// Crear una instancia del controlador
$controlador = new crear_mienbrosdosC();

if (isset($_GET['lista_tarjetas'])) {
    
    $filtros = $_POST['data'];
    echo json_encode($controlador->lista_tarjetas($filtros));
}



class crear_mienbrosdosC
{
    private $modelo;
    private $oficinaModelo;
    private $oficinas;

    public function __construct() 
    {
        
        // $this->modelo = new crear_mienbrosdosM(); 
        $this->oficinas = new crear_oficinaM(); 
        
        // Instanciar el modelo de oficinas
        $this->oficinaModelo = new crear_oficinaM(); 
    }

    // Listar las tarjetas (espacios)
    function lista_tarjetas($parametros)
    {
        // print_r($parametros);die;
        // Llamamos al método listardebase() del modelo crear_oficinaM para obtener los datos
        $nombre = isset($parametros['nombre_espacio']) ? $parametros['nombre_espacio'] : '';
        //$categoria = isset($parametros['nombre_categoria']) ? $parametros['nombre_categoria'] : '';
        //$estado = $parametros['estado_espacio'];
        $resultado = $this->oficinas->listardebaseFiltros($nombre);
        // print_r($resultado);die();
        // Inicializamos la variable que contendrá el HTML
        $str = '';
    
        // Iteramos sobre los resultados obtenidos
        foreach ($resultado as $key => $espacio) {
            // print_r($espacio);die();
            // Obtenemos los valores de cada espacio
            $nombre_espacio = isset($espacio['nombre_espacio']) ? $espacio['nombre_espacio'] : '';
            $id_espacio = isset($espacio['id_espacio']) ? $espacio['id_espacio'] : '';
            $categoria = isset($espacio['nombre_categoria']) ? $espacio['nombre_categoria'] : '';
            $aforo = isset($espacio['aforo_espacio']) ? $espacio['aforo_espacio'] : '';
            $precio = isset($espacio['precio_espacio']) ? $espacio['precio_espacio'] : '';
            $estado = isset($espacio['estado_espacio']) ? strtoupper($espacio['estado_espacio']) : 'I'; // 'A' para activo, 'I' para inactivo
    
            
            $str .= '<div class="col-md-3 mb-4 espacio" 
                        data-nombre="' . htmlspecialchars($nombre_espacio) . '" 
                        data-estado="' . $estado . '" 
                        data-categoria="' . htmlspecialchars($categoria) . '" 
                        data-precio="' . htmlspecialchars($precio) . '">
                        <div class="product-card ms-2">
                            <img src="https://media.istockphoto.com/id/157334256/es/foto/auditorio.jpg?s=1024x1024&w=is&k=20&c=FcTLVow6mKMcNk76ybgTuo04L39IOB3qnsZHDN1h4xI=" alt="Product Image" class="product-image img-fluid" style="width: 150px; height: auto;">
                            <h5 class="product-title mt-4">' . htmlspecialchars($nombre_espacio) . '</h5>
                            <p><strong>Espacio:</strong> ' . htmlspecialchars($id_espacio) . '</p>
                            <p><strong>Categoría:</strong> ' . htmlspecialchars($categoria) . '</p>
                            <p><strong>Aforo de personas:</strong> ' . htmlspecialchars($aforo) . '</p>
                            <div class="product-price">
                                <span><strong>Precio:</strong> $' . htmlspecialchars($precio) . '</span>
                            </div>
                            <strong><p class="text-muted">Estado:</strong> ' . ($estado === 'A' ? 'Activo' : 'Inactivo') . '</p>
                            <a class="btn btn-primary" href="../vista/inicio.php?mod=1010&acc=crear_mienbros&id='.$espacio['id_espacio'].'">Detalle</a>
                        </div>
                    </div>';
        }
    
        // Retornamos el HTML generado
        return $str;
    }



}
?>


    