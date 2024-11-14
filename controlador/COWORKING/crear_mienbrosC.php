<?php
include(dirname(__DIR__, 2) . '/modelo/COWORKING/crear_mienbrosM.php');
require_once(dirname(__DIR__, 2) . '/lib/pdf/cabecera_pdf.php');

$controlador = new crear_mienbrosC();
$id_miembro = isset($_GET['id_miembro']) ? intval($_GET['id_miembro']) : 0;
$id_sala = isset($_POST['id_sala']) ? $_POST['id_sala'] : '';



if (isset($_GET['generarPDFMiembros'])) {
    
    echo json_encode($controlador->generarPDFMiembros());
}



if (isset($_GET['generarPDFCompras'])) {
    
    echo json_encode($controlador->generarPDFCompras());
}

if (isset($_GET['lista_mienbro'])) {
    // print_r($_POST);die();
    $parametros = $_POST['data'];
    // print_r($parametros);die();
    echo json_encode($controlador->listar($parametros));
}


if (isset($_GET['listar_productos'])) {
    echo json_encode($controlador->listar_productos());
}


if (isset($_GET['listar_productossala'])) {
    echo json_encode($controlador->listar_productossala());
}


if (isset($_GET['lista_compra'])) {
    $parametros = $_POST['data'];
    echo json_encode($controlador->compraslista($parametros)); // Pasamos el ID del espacio al método
}



if (isset($_GET['lista_comprasala'])) {
    echo json_encode($controlador->listacomprasala($id_sala));
}

// Añadir nuevo miembro
if (isset($_GET['add'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : [];
    $resultado = $controlador->add($data);

    if ($resultado === 1) {
        echo json_encode(['status' => 'success', 'message' => 'Miembro registrado con éxito']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar miembro. ' . $resultado]);
    }
}


if (isset($_GET['add_compra'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : [];
    echo json_encode($controlador->add_compra($data));
}


if (isset($_GET['add_comprasala'])) {
    $data = isset($_POST['data']) ? $_POST['data'] : [];
    echo json_encode($controlador->add_comprasala($data));
}


if (isset($_GET['eliminar_miembro'])) {
    $id_miembro = isset($_POST['id_miembro']) ? intval($_POST['id_miembro']) : 0;
    echo json_encode($controlador->eliminar_miembro($id_miembro));
}


if (isset($_GET['eliminar_compra'])) {
    $id_compra = isset($_POST['id_compra']) ? intval($_POST['id_compra']) : 0;
    echo json_encode($controlador->eliminar_compra($id_compra));
}


if (isset($_POST['action']) && $_POST['action'] === 'verificar_compras') {
    $id_miembro = isset($_POST['id_miembro']) ? intval($_POST['id_miembro']) : 0;
    $response = $controlador->verificar_compras($id_miembro);
    echo $response;
}

class crear_mienbrosC
{
    private $modelo;
    private $pdf;
    
    public function __construct() 
    {
        $this->modelo = new crear_mienbrosM();
        $this->pdf = new cabecera_pdf();
    }

    function listar_productos()
    {
        $slista = array(
            array('id_producto' => 1, 'Producto' => 'Producto1', 'Precio' => 10.00),
            array('id_producto' => 2, 'Producto' => 'Producto2', 'Precio' => 20.00),
            array('id_producto' => 3, 'Producto' => 'Producto3', 'Precio' => 30.00),
            array('id_producto' => 4, 'Producto' => 'Producto4', 'Precio' => 40.00),
            array('id_producto' => 5, 'Producto' => 'Producto5', 'Precio' => 50.00)
        );

        $str = '';
        foreach ($slista as $value) {
            $str .= '<option value="' . $value['id_producto'] . '" data-precio="' . $value['Precio'] . '">' . $value['Producto'] . '</option>';
        }
        return $str;
    }

    
    function listar_productossala()
    {
        $slista = array(
            array('id_producto' => 1, 'Producto' => 'Producto1', 'Precio' => 10.00),
            array('id_producto' => 2, 'Producto' => 'Producto2', 'Precio' => 20.00),
            array('id_producto' => 3, 'Producto' => 'Producto3', 'Precio' => 30.00),
            array('id_producto' => 4, 'Producto' => 'Producto4', 'Precio' => 40.00),
            array('id_producto' => 5, 'Producto' => 'Producto5', 'Precio' => 50.00)
        );

        $str = '';
        foreach ($slista as $value) {
            $str .= '<option value="' . $value['id_producto'] . '" data-precio="' . $value['Precio'] . '">' . $value['Producto'] . '</option>';
        }
        return $str;
    }

    // Listar compras por sala
    function listacomprasala()
    {
        $slista = $this->modelo->listacomprasala();

        $str = '';
        foreach ($slista as $key => $value) {
            $id_compra = isset($value['id_compra']) ? $value['id_compra'] : 'id_compra';
            
            $str .= '<tr>
                        <td>' . $value['id_sala'] . '</td> 
                        <td>' . $id_compra . '</td>
                        <td>' . $value['id_producto'] . '</td>
                        <td>' . $value['cantidad_compra'] . '</td>
                        <td>' . $value['pvp_compra'] . '</td>
                        <td>' . $value['total_compra'] . '</td>
                        <td>
                            <button type="button" onclick="eliminarCompra(' . intval($id_compra) . ')" class="btn btn-danger btn-sm">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                     </tr>';
        }

        return $str;
    }


    function compraslista($parametros)
    {
        $id = $parametros['id'];
        $slista = $this->modelo->compraslista($id);
        //print_r($slista); die();

        $str = '';
        foreach ($slista as $key => $value) {
            $id_miembro = isset($value['id_miembro']) ? $value['id_miembro'] : 'id_miembro';
            $id_compra = isset($value['id_compra']) ? $value['id_compra'] : 'id_compra';
            $id_sala = isset($value['id_sala']) ? $value['id_sala'] : 'sala';

            $str .= '<tr>
                        <td>' . $id_sala . '</td> 
                        <td>' . $id_compra . '</td>
                        <td>' . $id_miembro . '</td>
                        <td>' . $value['id_producto'] . '</td>
                        <td>' . $value['cantidad_compra'] . '</td>
                        <td>' . $value['pvp_compra'] . '</td>
                        <td>' . $value['total_compra'] . '</td>
                        <td>
                            <button type="button" onclick="eliminarCompra(' . intval($id_compra) . ')" class="btn btn-danger btn-sm">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                     </tr>';
        }

        return $str;
    }


    function add($parametros)
    {
        return $this->modelo->insertarnombre($parametros);
    }


    function add_compra($parametros)
    {
        return $this->modelo->insertarcompra($parametros);
    }


    function add_comprasala($parametros)
    {
        return $this->modelo->insertarcompra($parametros);
    }


    function listar($parametros)
    {
        //print_r($parametros);die();
        $id = $parametros['id'];
        $slista = $this->modelo->listardebase($id);
        $str = '';
        foreach ($slista as $key => $value) {
            $id_miembro = isset($value['id_miembro']) ? $value['id_miembro'] : 'desconocido';
            $id_espacio = isset($value['id_espacio']) ? $value['id_espacio'] : 'desconocido';

            $str .= '<tr id="row-' . $id_miembro . '">
                        <td>
                            <button type="button" onclick="abrirModal(' . $id_miembro . ', ' . $id_espacio . ')" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_registrar_compra">
                                <i class="bx bx-cart"></i>
                            </button>
                        </td>
                        <td>' . $value['nombre_miembro'] . '</td>
                        <td>' . $value['apellido_miembro'] . '</td>
                        <td>' . $value['telefono_miembro'] . '</td>
                        <td>' . $value['direccion_miembro'] . '</td>
                        
                        <td>
                            <button type="button" onclick="eliminarMiembro(' . $id_miembro . ')" class="btn btn-danger btn-sm">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>';
        }
        return $str;
    }


    function eliminar_miembro($id_miembro)
    {
        return $this->modelo->eliminar_miembro($id_miembro);
    }


    function eliminar_compra($id_compra)
    {
        return $this->modelo->eliminar_compra($id_compra);
    }


    function verificar_compras($id_miembro)
    {
        try {
            $tiene_compras = $this->modelo->tiene_compras($id_miembro);
            return json_encode(['tiene_compras' => $tiene_compras]);
        } catch (Exception $e) {
            error_log('Error en verificar_compras: ' . $e->getMessage());
            return json_encode(['error' => 'Error al verificar las compras del miembro']);
        }
    }



    function generarPDFMiembros(){
        $titulo = 'MI PRIMER PDF';
        $tablaHTML = array();
        $fechaini = '';
        $fechafin = '';
        $sizetable = 8  ; 
        $mostrar = 1;
        
        
        $tablaHTML[0]['medidas']=$medidas = array(195);
		$tablaHTML[0]['alineado']=$alineado  = array('C');
		$tablaHTML[0]['datos']=$header = array('Informe de miembros');
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

        $data = $this->modelo->listardebase();
        $tablaHTML[3]['medidas']=$medidas = array(38, 38, 38, 38, 38);
		$tablaHTML[3]['alineado']=$alineado  = array('C', 'C', 'C', 'C', 'C');
		$tablaHTML[3]['datos']= array('Nombre', 'Apellido', 'Telefono', 'Direccion', 'Espacio');
		$tablaHTML[3]['estilo']='B';
		$tablaHTML[3]['borde'] = '1';
        $tablaHTML[3]['size'] = 10;
 
        $posicion = 4;

        foreach($data as $key => $value)
        {
        //print_r($value);die();
        $tablaHTML[$posicion]['medidas']= $tablaHTML[3]['medidas'];
		$tablaHTML[$posicion]['alineado']= $tablaHTML[3]['alineado'];
		$tablaHTML[$posicion]['datos']= array($value['nombre_miembro'], $value['apellido_miembro'], $value['telefono_miembro'], $value['direccion_miembro'], $value['id_espacio']);
		$tablaHTML[$posicion]['estilo']='';
		$tablaHTML[$posicion]['borde'] = '  1';
        $tablaHTML[$posicion]['size'] = 9;
        $posicion ++;
        }
        
        $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false, $image=false, $fechaini,$fechafin,$sizetable,$mostrar, 5);
        //print_r('HOLA MUNDO');die();
    }


    function generarPDFCompras() {
        $titulo = 'MI PRIMER PDF';
        $tablaHTML = array();
        $fechaini = '';
        $fechafin = '';
        $sizetable = 8; 
        $mostrar = 1;
    
        $tablaHTML[0]['medidas'] = array(195);
        $tablaHTML[0]['alineado'] = array('C');
        $tablaHTML[0]['datos'] = array('Informe de compras');
        $tablaHTML[0]['estilo'] = 'B';
        $tablaHTML[0]['size'] = 20;
    
        $tablaHTML[1]['medidas'] = array(195);
        $tablaHTML[1]['alineado'] = array('C');
        $tablaHTML[1]['datos'] = array('');
    
        $tablaHTML[2]['medidas'] = array(195);
        $tablaHTML[2]['alineado'] = array('C');
        $tablaHTML[2]['datos'] = array('');
    
        $tablaHTML[3]['medidas'] = array(27, 27, 27, 27, 27, 27, 27);
        $tablaHTML[3]['alineado'] = array('C', 'C', 'C', 'C', 'C', 'C', 'C');
        $tablaHTML[3]['datos'] = array('Sala', 'Compra', 'Miembro', 'Producto', 'Cantidad', 'Precio', 'Total');
        $tablaHTML[3]['estilo'] = 'B';
        $tablaHTML[3]['borde'] = '1';
        $tablaHTML[3]['size'] = 10;
    
        $posicion = 4;
    
        $compras_sala = $this->modelo->listacomprasala();
        $compras_lista = $this->modelo->compraslista();
    
        // Crear un array asociativo para mapear id_compra a datos de compraslista
        $compras_lista_map = array();
        foreach ($compras_lista as $compra) {
            $compras_lista_map[$compra['id_compra']] = $compra;
        }
    
        foreach ($compras_sala as $key => $value) {
            // Obtener datos adicionales de compraslista si existen, con nombre_completo
            $datos_adicionales = $compras_lista_map[$value['id_compra']] ?? array('nombre_completo' => 'N/A');
            
            $tablaHTML[$posicion]['medidas'] = $tablaHTML[3]['medidas'];
            $tablaHTML[$posicion]['alineado'] = $tablaHTML[3]['alineado'];
            $tablaHTML[$posicion]['datos'] = array(
                $value['id_sala'], 
                $value['id_compra'], 
                $datos_adicionales['nombre_completo'],  // Usamos nombre_completo
                $value['id_producto'], 
                $value['cantidad_compra'], 
                $value['pvp_compra'], 
                $value['total_compra']
            );
            $tablaHTML[$posicion]['estilo'] = '';
            $tablaHTML[$posicion]['borde'] = '1';
            $tablaHTML[$posicion]['size'] = 9;
            $posicion++;
        }
    
        $this->pdf->cabecera_reporte_MC($titulo, $tablaHTML, $contenido=false, $image=false, $fechaini, $fechafin, $sizetable, $mostrar, 5);
    }
    
    
    


    
}



















