<?php
include(dirname(__DIR__, 2) . '/modelo/COWORKING/crear_mienbrosM.php');



class crear_mienbrosC
{
    private $modelo;

    function __construct() 
    {
        $this->modelo = new crear_mienbrosM();
    }











    function listar()
    {
        $slista = $this->modelo->listardebase();
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
                        <td>' . $value['id_espacio'] . '</td>
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
        return $this->modelo->verificar_compras($id_miembro);
    }

    function verificar_compras($id_miembro)

    try {
        $tiene_compras = $this->modelo->tiene_compras($id_miembro);
        return json_encode(['tiene_compras' => $tiene_compras]);
    } catch (Exception $e) {
        error_log('Error en verificar_compras: ' . $e->getMessage());
        return json_encode(['error' => 'Error al verificar las compras del miembro']);
    }
}
?>