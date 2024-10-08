<?php 
include(dirname(__DIR__, 2).'/modelo/COWORKING/ClaseEjemploM.php');
require_once(dirname(__DIR__, 2 ) . '/lib/pdf/cabecera_pdf.php');

$controlador = new claseEjemplo();
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
    function __construct() {
        $this->modelo = new claseEjemploM();
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
        $titulo = 'MI PRIMER PDF';
        $tablaHTML = array();
        $fechaini = '';
        $fechafin = '';
        $sizetable = 8  ; 
        $mostrar = 1;
        
        
        $tablaHTML[0]['medidas']=$medidas = array(195);
		$tablaHTML[0]['alineado']=$alineado  = array('C');
		$tablaHTML[0]['datos']=$header = array('Informe de mobiliario');
        $tablaHTML[0]['estilo']='B';
        $tablaHTML[0]['size'] = 20;

        $tablaHTML[1]['medidas']=$medidas = array(195);
		$tablaHTML[1]['alineado']=$alineado  = array('C');
		$tablaHTML[1]['datos']=$header = array('');

        $tablaHTML[2]['medidas']=$medidas = array(195);
		$tablaHTML[2]['alineado']=$alineado  = array('C');
		$tablaHTML[2]['datos']=$header = array('');

        //$tablaHTML[3]['medidas']=$medidas = array(25, 100);
		//$tablaHTML[3]['alineado']=$alineado  = array('C');
		//$tablaHTML[3]['datos']=$header = array('Nombre', 'Alejandro');
		//$tablaHTML[0]['borde'] = '1';
        $id_espacio = isset($_POST['id_espacio']) ? intval($_POST['id_espacio']) : null;
        
        $data = $this->modelo->listarMobiliario($id_espacio);
        //print_r($data); die();
        $tablaHTML[3]['medidas']=$medidas = array(38, 38, 38, 38);
		$tablaHTML[3]['alineado']=$alineado  = array('C', 'C', 'C', 'C');
		$tablaHTML[3]['datos']= array( 'Mobiliario', 'Espacio', 'Cantidad', 'Detalle');
		$tablaHTML[3]['estilo']='B';
		$tablaHTML[3]['borde'] = '1';
        $tablaHTML[3]['size'] = 10;
 
        $posicion = 4;

        foreach($data as $key => $value)
        {
        //print_r($value);die();
        $tablaHTML[$posicion]['medidas']= $tablaHTML[3]['medidas'];
		$tablaHTML[$posicion]['alineado']= $tablaHTML[3]['alineado'];
		$tablaHTML[$posicion]['datos']= array($value['id_mobiliario'], $value['id_espacio'], $value['cantidad'], $value['detalle_mobiliario'] );
		$tablaHTML[$posicion]['estilo']='';
		$tablaHTML[$posicion]['borde'] = '  1';
        $tablaHTML[$posicion]['size'] = 9;
        $posicion ++;
        }
        
        $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false, $image=false, $fechaini,$fechafin,$sizetable,$mostrar, 5);
        //print_r('HOLA MUNDO');die();
    }

function generarPDFEspacios(){
    $titulo = 'PDF Espacios';
    $tablaHTML = array();
    $fechaini = '';
    $fechafin = '';
    $sizetable = 8  ; 
    $mostrar = 1;
    
    
    $tablaHTML[0]['medidas']=$medidas = array(195);
    $tablaHTML[0]['alineado']=$alineado  = array('C');
    $tablaHTML[0]['datos']=$header = array('Informe de espacios');
    $tablaHTML[0]['estilo']='B';
    $tablaHTML[0]['size'] = 20;

    $tablaHTML[1]['medidas']=$medidas = array(195);
    $tablaHTML[1]['alineado']=$alineado  = array('C');
    $tablaHTML[1]['datos']=$header = array('');

    $tablaHTML[2]['medidas']=$medidas = array(195);
    $tablaHTML[2]['alineado']=$alineado  = array('C');
    $tablaHTML[2]['datos']=$header = array('');

    //$tablaHTML[3]['medidas']=$medidas = array(25, 100);
    //$tablaHTML[3]['alineado']=$alineado  = array('C');
    //$tablaHTML[3]['datos']=$header = array('Nombre', 'Alejandro');
    //$tablaHTML[0]['borde'] = '1';
    $id_espacio = isset($_POST['id_espacio']) ? intval($_POST['id_espacio']) : null;
    $data = $this->modelo->listardebase($id_espacio);
    $tablaHTML[3]['medidas']=$medidas = array(38, 38, 38, 38, 38);
    $tablaHTML[3]['alineado']=$alineado  = array('C', 'C', 'C', 'C', 'C');
    $tablaHTML[3]['datos']= array('Id del espacio', 'Nombre del espacio', 'Aforo del espacio', 'Precio del espacio', 'Estado del espacio');
    $tablaHTML[3]['estilo']='B';
    $tablaHTML[3]['borde'] = '1';
    $tablaHTML[3]['size'] = 10;

    $posicion = 4;

    foreach($data as $key => $value)
    {
    //print_r($value);die();
    $tablaHTML[$posicion]['medidas']= $tablaHTML[3]['medidas'];
    $tablaHTML[$posicion]['alineado']= $tablaHTML[3]['alineado'];
    $tablaHTML[$posicion]['datos']= array($value['id_espacio'], $value['nombre_espacio'], $value['aforo_espacio'], $value['precio_espacio'], $value['estado_espacio'] );
    $tablaHTML[$posicion]['estilo']='';
    $tablaHTML[$posicion]['borde'] = '  1';
    $tablaHTML[$posicion]['size'] = 9;
    $posicion ++;
    }
    
    $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false, $image=false, $fechaini,$fechafin,$sizetable,$mostrar, 5);
    //print_r('HOLA MUNDO');die();
}

    // Listar espacios
    function listaEspacios() {
        $lista = $this->modelo->listardebase();
        $tr = '';
        foreach ($lista as $value) {
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
                </td>
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
