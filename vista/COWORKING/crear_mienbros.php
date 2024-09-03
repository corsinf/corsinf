<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3"><strong>Formulario</strong></div>
            <div class="breadcrumb-separator"></div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bi bi-house"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <strong>Registro Miembros</strong>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <hr>
                <div class="card">
                    <div class="card-body">
                        <h1 class="titulo mb-4">Oficina 5</h1>

                        <form id="formulario_miembro" class="mb-4">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="txt_nombre" class="form-label"><strong>Nombre:</strong></label>
                                    <input type="text" class="form-control form-control-sm" name="txt_nombre" id="txt_nombre" placeholder="Nombre" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="txt_apellido" class="form-label"><strong>Apellido:</strong></label>
                                    <input type="text" class="form-control form-control-sm" name="txt_apellido" id="txt_apellido" placeholder="Apellido" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="txt_numero_celular" class="form-label"><strong>Telefono:</strong></label>
                                    <input type="text" class="form-control form-control-sm" name="txt_numero_celular" id="txt_numero_celular" placeholder="Celular" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="txt_direccion" class="form-label"><strong>Direccion:</strong></label>
                                    <input type="text" class="form-control form-control-sm" name="txt_direccion" id="txt_direccion" placeholder="Direccion" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="ddl_id_espacio" class="form-label"><strong>Espacio:</strong></label>
                                    <input type="number" class="form-control form-control-sm" name="ddl_id_espacio" id="ddl_id_espacio" placeholder="Numero de espacio" required>
                                </div>
                            </div>
                            <button type="button" onclick="enviardatos()"class="btn btn-primary" id="btn_registrar_miembro"><strong>Registrar Miembro</strong></button>
                        </form>

                        <h2 class="mb-4">Miembros Registrados</h2>
                        <table class="table table-bordered table-striped" id="tbl_miembros">
                            <thead class="table-header">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Telefono</th>
                                    <th>Direccion</th>
                                    <th>Espacio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_body">
                                
                            </tbody>
                        </table>

                        <div class="modal fade" id="modal_registrar_compra" tabindex="-1" aria-labelledby="modal_registrar_compra_label" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal_registrar_compra_label"><strong>Registrar Compra</strong></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formulario_compras">
                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <label for="id_miembro" class="form-label">ID Miembro:</label>
                                                    <input type="text" name="id_miembro" id="id_miembro" class="form-control" value="<?php echo htmlspecialchars($id_miembro); ?>" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="txt_producto" class="form-label"><strong>Producto:</strong></label>
                                                    <select class="form-control" id="txt_producto" name="txt_producto" required>

                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="txt_cantidad" class="form-label"><strong>Cantidad:</strong></label>
                                                    <input type="number" class="form-control" id="txt_cantidad" name="txt_cantidad" value="1" min="1" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="txt_precio" class="form-label"><strong>Precio:</strong></label>
                                                    <input type="text" class="form-control" id="txt_precio" name="txt_precio" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="txt_total" class="form-label"><strong>Total:</strong></label>
                                                    <input type="text" class="form-control" id="txt_total" name="txt_total" readonly>
                                                </div>
                                            </div>
                                            <table class="table table-bordered table-striped mt-4" id="tbl_compras">
                                                <thead class="table-header">
                                                    <tr>
                                                        <th>Miembro</th>
                                                        <th>Producto</th>
                                                        <th>Cantidad</th>
                                                        <th>Precio</th>
                                                        <th>Total</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody id="tbl_boby">
                                                    
                                                </tbody>
                                            </table>
                                            <button type="button" onclick="enviarCompras()" class="btn btn-primary" id="btn_agregar_compra">
                                                <i class="bx bx-save"></i> <strong>Agregar</strong>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            <strong>Cerrar</strong> <i class="bi bi-file-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        lista_usuario();
        lista_compra();
        select_productos();
        
        //$('#btn_agregar_compra').click(function() {
        //    enviarCompras();
        //});
    });
    
    function lista_usuario() {       
        $.ajax({
            url: '../controlador/COWORKING/crear_mienbrosC.php?lista_mienbro=true',
            type: 'post',
            dataType: 'json',        
            success: function(response) {  
                $('#tbl_body').html(response);
                console.log(response);
            }       
        });
    }

    function lista_compra() {       
        $.ajax({
            url: '../controlador/COWORKING/crear_mienbrosC.php?lista_compra=true',
            type: 'post',
            dataType: 'json', 
            success: function(response) {  
                $('#tbl_boby').html(response);
                console.log(response);
            }       
        });
    }

    function enviardatos() {
        var parametros = {
            'nombre_miembro': $('#txt_nombre').val(),
            'apellido_miembro': $('#txt_apellido').val(),
            'telefono_miembro': $('#txt_numero_celular').val(),
            'direccion_miembro': $('#txt_direccion').val(),
            'id_espacio': $('#ddl_id_espacio').val()
        };

        $.ajax({
            data: {data: parametros},
            url: '../controlador/COWORKING/crear_mienbrosC.php?add=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {  
                if (response == 1) {
                    alert('Miembro agregado con éxito');
                    lista_usuario();
                } else {
                    alert('Error al agregar el miembro');
                }
            }       
        });
    }

    function enviarCompras() {
        var parametros = {
            'id_miembro': $('#id_miembro').val(),
            'id_producto': $('#txt_producto').val(),
            'cantidad_compra': $('#txt_cantidad').val(),
            'pvp_compra': $('#txt_precio').val(),
            'total_compra': $('#txt_total').val()
        };

        $.ajax({
            data: {data: parametros},
            url: '../controlador/COWORKING/crear_mienbrosC.php?add_compra=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {  
                if (response == 1) {
                    alert('Compra agregada con éxito');
                    lista_compra();
                } else {
                    alert('Error al realizar la compra');
                }
            }      
        });
    }
    $(document).ready(function() {
    select_productos();
    $('#txt_cantidad').on('input', calcularTotal); 
    $('#txt_producto').on('change', function() {
        var precio = $(this).find('option:selected').data('precio');
        $('#txt_precio').val(precio);
        calcularTotal(); 
    });
});

function abrirModal(id_miembro) {
    
    console.log('ID Miembro:', id_miembro);

    
    $('#id_miembro').val(id_miembro);

    
    select_productos();
}
    
    function calcularTotal() {
        var cantidad = parseFloat($('#txt_cantidad').val()) || 0;
        var precio = parseFloat($('#txt_precio').val()) || 0;
        $('#txt_total').val((cantidad * precio).toFixed(2));
        }

    function eliminarCompra(id_compra) {
        $.ajax({
        url: '../controlador/COWORKING/crear_mienbrosC.php?eliminar_compra=true',
        type: 'POST',
        data: { id_compra: id_compra },
        dataType: 'json',
        success: function(response) {
            if (response === "Compra eliminada con éxito") {
                
                $('#row-compra-' + id_compra).remove();
                alert('Compra eliminada con éxito');
                
            } 
            else {
                alert('Error al eliminar la compra');
            }
        }
    });
}


    function eliminarMiembro(id_miembro) {
    $.ajax({
        url: '../controlador/COWORKING/crear_mienbrosC.php?eliminar_miembro=true',
        type: 'POST',
        data: { id_miembro: id_miembro },
        dataType: 'json',
        success: function(response) {
            if (response === "Miembro eliminado con éxito") {
                
                $('#row-miembro-' + id_miembro).remove();
                alert('Miembro eliminado con éxito');
                lista_usuario();
            }
             else {
                alert('Error al eliminar el miembro');
            }
        }
    });
}

    function select_productos() {       
        $.ajax({
            url: '../controlador/COWORKING/crear_mienbrosC.php?listar_productos=true',
            type: 'post',
            dataType: 'json', 
            success: function(response) {  
                $('#txt_producto').html(response);

                $('#txt_producto').on('change', function() {
                    let precio = $(this).find('option:selected').data('precio');
                    $('#txt_precio').val(precio);
                });
            }       
        });
    }



</script>