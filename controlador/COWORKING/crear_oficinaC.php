<?php 
include(dirname(__DIR__, 2).'/modelo/COWORKING/crear_oficinaM.php');
require_once(dirname(__DIR__, 2 ) . '/lib/pdf/cabecera_pdf.php');


$controlador = new claseEjemplo();

$id_espacio = isset($_POST['id_espacio']) ? $_POST['id_espacio'] : '';
// Listar nombres de los espacios para el modal
if (isset($_POST['accion']) && $_POST['accion'] === 'listarEspacios') {
    $espacios = $controlador->getModelo()->listardebase(); // Llama al método del modelo
    $response = [];

    foreach ($espacios as $espacio) {
        $response[] = [
            'id' => $espacio['id_espacio'],
            'nombre' => $espacio['nombre_espacio'] 
        ];
    }

    echo json_encode(['success' => true, 'data' => $response]);
}
// Listar eventos para el calendario
if (isset($_POST['accion']) && $_POST['accion'] === 'listarEventos') {
    $eventos = $controlador->listarEventos(); // Llama al método del modelo
    if ($eventos) {
        echo json_encode($eventos);
    } else {
        echo json_encode([]);
    }
    exit;
}


// Obtener datos de un espacio específico
if (isset($_POST['getEspacio'])) {
    $id_espacio = $_POST['id_espacio'];
    echo json_encode($controlador->getEspacio($id_espacio));
}
if (isset($_GET['generarPDFMobiliario']) && isset($_GET['id_espacio'])) {
    $id_espacio = $_GET['id_espacio'];
    
    echo json_encode($controlador->generarPDFMobiliario());
}
if (isset($_GET['generarPDFEspacios'])) {
    
    echo json_encode($controlador->generarPDFEspacios());
}
// Listar categorías
if (isset($_GET['categoria'])) {
    echo json_encode($controlador->listaCategorias());
}

// Listar espacios
if (isset($_GET['listaEspacios'])) {
    echo json_encode($controlador->listaEspacios());
}

// Agregar espacio
if (isset($_POST['add'])) {
    $data = $_POST["data"];
    echo json_encode($controlador->add($data));
}

// Editar espacio
if (isset($_POST['edit'])) {
    $data = $_POST["data"];
    echo json_encode($controlador->edit($data));
}

// Eliminar espacio
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    if (isset($id) && is_numeric($id)) {
        $resultado = $controlador->delete($id);
        if ($resultado) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el espacio.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID inválido.']);
    }
}
if (isset($_POST['accion']) && $_POST['accion'] === 'guardarEvento') {
    $data = [
        'titulo' => $_POST['titulo'] ?? '',
        'detalle' => $_POST['detalle'] ?? '',
        'id_espacio' => $_POST['id_espacio'] ?? 0,
        'fechaInicio' => $_POST['fechaInicio'] ?? '',
        'fechaFin' => $_POST['fechaFin'] ?? '',
        'estado_pago' => $_POST['estado_pago'] ?? 0,
        'contacto' => $_POST['contacto'] ?? '',
        'responsable' => $_POST['responsable'] ?? '',
    ];

    $resultado = $controlador->addEvento($data);
    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Evento guardado con éxito.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo guardar el evento.']);
    }
}


// Agregar nueva categoría
if (isset($_POST['addCategoria'])) {
    $data = $_POST['data'];
    echo json_encode($controlador->addCategoria($data));
}

// Listar mobiliario para un espacio
if (isset($_GET['listaMobiliario'])) {
    $id_espacio = $_GET['id_espacio'];
    echo json_encode($controlador->listarMobiliario($id_espacio));
}

// Agregar mobiliario
if (isset($_POST['addMobiliario'])) {
    $data = $_POST["data"];
    echo json_encode($controlador->addMobiliario($data));
}

class claseEjemplo {
    private $modelo;
    private $pdf;

    public function getModelo() {
        return $this->modelo;
    }
    function listarEventos() {
        $eventos = $this->modelo->obtenerEventos(); 
        return $eventos;
    }
    
    
    function addEvento($parametros) {
        return $this->modelo->insertarEvento($parametros);
    }
    function __construct() {
        $this->modelo = new crear_oficinaM();
        $this->pdf = new cabecera_pdf();
    }
    // Obtener datos de un espacio específico
    function getEspacio($id_espacio) {
        $espacio = $this->modelo->obtenerEspacio($id_espacio);
        if ($espacio) {
            return ['success' => true, 'espacio' => $espacio];
        } else {
            return ['success' => false, 'message' => 'No se pudo obtener el espacio.'];
        }
    }
    // Agregar espacio  
    function add($parametros) {
        return $this->modelo->insertarnombre($parametros);
    }

    // Editar espacio
        function edit($parametros) {
            $resultado = $this->modelo->actualizarEspacio($parametros);
            if ($resultado) {
                return ['success' => true, 'message' => 'Espacio actualizado correctamente.'];
            } else {
                return ['success' => false, 'message' => 'No se pudo actualizar el espacio.'];
            }
        }

    // Eliminar espacio
    function delete($id_espacio) {
        return $this->modelo->eliminarEspacio($id_espacio);
    }

    // Listar categorías
    function listaCategorias() {
        $lista = $this->modelo->listarCategorias(); 
        $select = '';
        foreach ($lista as $value) {
            $select .= '<option value="'.$value['id_categoria'].'">'.$value['nombre_categoria'].'</option>';
        }
        return $select;
    }

    // Añadir categoría
    function addCategoria($parametros) {
        $datos[0]["campo"] = "nombre_categoria";
        $datos[0]["dato"] = $parametros["nombre"]; 
        return $this->modelo->insertarCategoria($datos, "co_categoria");

    }
    function generarPDFMobiliario(){
        $titulo = 'Informe de Mobiliario';
        $tablaHTML = array();
        $id_espacio = isset($_GET['id_espacio']) ? intval($_GET['id_espacio']) : null;
    
        if (!$id_espacio) {
            return ['success' => false, 'message' => 'No se ha proporcionado un ID válido.'];
        }
    
        $data = $this->modelo->listarMobiliario($id_espacio);
        if (empty($data)) {
            return ['success' => false, 'message' => 'No hay datos de mobiliario para este espacio.'];
        }
    
        // Título del informe
        $tablaHTML[0]['medidas'] = array(195);
        $tablaHTML[0]['alineado'] = array('C');
        $tablaHTML[0]['datos'] = array('Informe de Mobiliario');
        $tablaHTML[0]['estilo'] = 'B';
        $tablaHTML[0]['size'] = 20;
    
        // Espacio vacío entre título y tabla
        $tablaHTML[1]['medidas'] = array(195);
        $tablaHTML[1]['alineado'] = array('C');
        $tablaHTML[1]['datos'] = array(''); // Fila vacía para el espacio
        $tablaHTML[1]['estilo'] = '';
        $tablaHTML[1]['size'] = 10;
    
        // Encabezado de la tabla de mobiliario
        $tablaHTML[2]['medidas'] = array(38, 38, 38, 38);
        $tablaHTML[2]['alineado'] = array('C', 'C', 'C', 'C');
        $tablaHTML[2]['datos'] = array('ID Mobiliario', 'Espacio', 'Cantidad', 'Detalle');
        $tablaHTML[2]['estilo'] = 'B';
        $tablaHTML[2]['borde'] = '1';
        $tablaHTML[2]['size'] = 10;
    
        // Agregar los datos del mobiliario
        $posicion = 3;
        foreach($data as $key => $value) {
            $tablaHTML[$posicion]['medidas'] = $tablaHTML[2]['medidas'];
            $tablaHTML[$posicion]['alineado'] = $tablaHTML[2]['alineado'];
            $tablaHTML[$posicion]['datos'] = array($value['id_mobiliario'], $value['id_espacio'], $value['cantidad'], $value['detalle_mobiliario']);
            $tablaHTML[$posicion]['estilo'] = '';
            $tablaHTML[$posicion]['borde'] = '1';
            $tablaHTML[$posicion]['size'] = 9;
            $posicion++;
        }
    
        // Generar el PDF
        $this->pdf->cabecera_reporte_MC($titulo, $tablaHTML, false, false, '', '', 8, true, 5);
    }
    

    function generarPDFEspacios(){
        $titulo = 'Informe de Espacios';
        $tablaHTML = array();
        $data = $this->modelo->listardebase();
    
        if (empty($data)) {
            return ['success' => false, 'message' => 'No hay espacios para listar.'];
        }
    
        // Título del informe
        $tablaHTML[0]['medidas'] = array(195);
        $tablaHTML[0]['alineado'] = array('C');
        $tablaHTML[0]['datos'] = array('Informe de Espacios');
        $tablaHTML[0]['estilo'] = 'B';
        $tablaHTML[0]['size'] = 20;
    
        // Espacio vacío entre título y tabla
        $tablaHTML[1]['medidas'] = array(195);
        $tablaHTML[1]['alineado'] = array('C');
        $tablaHTML[1]['datos'] = array(''); 
        $tablaHTML[1]['estilo'] = '';
        $tablaHTML[1]['size'] = 10;
    
        // Encabezado de la tabla de espacios
        $tablaHTML[2]['medidas'] = array(38, 38, 38, 38, 38);
        $tablaHTML[2]['alineado'] = array('C', 'C', 'C', 'C', 'C');
        $tablaHTML[2]['datos'] = array('ID Espacio', 'Nombre Espacio', 'Aforo', 'Precio', 'Estado');
        $tablaHTML[2]['estilo'] = 'B';
        $tablaHTML[2]['borde'] = '1';
        $tablaHTML[2]['size'] = 10;
    
        // Agregar los datos de los espacios
        $posicion = 3;
        foreach($data as $key => $value) {
            $estado = ($value['estado_espacio'] == 'A') ? 'Activo' : 'Inactivo';
            $tablaHTML[$posicion]['medidas'] = $tablaHTML[2]['medidas'];
            $tablaHTML[$posicion]['alineado'] = $tablaHTML[2]['alineado'];
            $tablaHTML[$posicion]['datos'] = array($value['id_espacio'], $value['nombre_espacio'], $value['aforo_espacio'], $value['precio_espacio'], $estado);
            $tablaHTML[$posicion]['estilo'] = '';
            $tablaHTML[$posicion]['borde'] = '1';
            $tablaHTML[$posicion]['size'] = 9;
            $posicion++;
        }
    
        // Generar el PDF
        $this->pdf->cabecera_reporte_MC($titulo, $tablaHTML, false, false, '', '', 8, true, 5);
    }
    
    

    // Listar espacios
    function listaEspacios() {
        $lista = $this->modelo->listardebase();
        $tr = '';
        foreach ($lista as $value) {
            $id_espacio = isset($value['id_espacio']) ? $value['id_espacio'] : 'desconocido';
            $estado = $value['estado_espacio'] == 'A' ? 'Inactivo' : 'Activo';
            $tr .= '<tr>
                <td>' . htmlspecialchars($value['id_espacio'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($value['nombre_espacio'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($value['aforo_espacio'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($value['precio_espacio'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($value['nombre_categoria'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>
                <button class="btn btn-sm btn-primary" onclick="editarEspacio(' . htmlspecialchars($value['id_espacio'], ENT_QUOTES, 'UTF-8') . ')"><i class="bx bx-edit"></i></button>
                <button class="btn btn-sm btn-danger" onclick="eliminarEspacio(this)" data-id="' . htmlspecialchars($value['id_espacio'], ENT_QUOTES, 'UTF-8') . '"><i class="bx bx-trash"></i></button>
                <button class="btn btn-sm btn-secondary" onclick="openFurnitureModal(' . htmlspecialchars($value['id_espacio'], ENT_QUOTES, 'UTF-8') . ')">Gestionar Mobiliario</button>
                </tr>';
        }   
        return $tr;
    }

    // Agregar mobiliario
    function addMobiliario($parametros) {
        return $this->modelo->insertarMobiliario($parametros);
    }

    // Listar mobiliario por espacio
    function listarMobiliario($id_espacio) {
        $lista = $this->modelo->listarMobiliario($id_espacio);
        $tr = '';  
        //print_r($lista); die();
        foreach ($lista as $value) {
            $tr .= '<tr>
                        <td>'.utf8_decode($value['detalle_mobiliario']).'</td>
                        <td>'.utf8_decode($value['cantidad']).'</td>
                    </tr>';
        }
        return $tr;
    }
}   
?>
