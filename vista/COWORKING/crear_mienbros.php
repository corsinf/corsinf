<?php
// Verificar si los parámetros no están presentes
if (!isset($_GET['id']) || !isset($_GET['nombre_espacio'])) {
    echo "<script>window.location.href = '../vista/inicio.php?mod=1010&acc=crear_mienbrosdos';</script>";
    exit;
}

// Continuar con el resto del código si los parámetros están presentes
$espacio = $_GET['id'];
$sala = $_GET['id'];
$nombre = $_GET['nombre_espacio'];
?>




<?$nombre = isset($_GET['nombre_espacio']) ? $_GET['nombre_espacio'] : '';?>
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3"><strong>Formulario</strong></div>
            <div class="breadcrumb-separator"></div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home"></i></a>
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
                        <h1 class="titulo mb-4"><?php echo htmlspecialchars($nombre); ?></h1>
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
                                <input type="hidden" class="form-control form-control-sm" name="ddl_id_espacio" id="ddl_id_espacio" placeholder="Numero de espacio" value = "<?php echo $espacio; ?>"  required>
                               
                            </div>
                            <button type="button" onclick="enviardatos()" class="btn btn-primary" id="btn_registrar_miembro">
                                <i class="bx bx-user-plus"></i><strong>Registrar Miembro</strong>
                            </button>
                        </form>

                        <!-- Contenedor flex para pestañas y botones -->
                        <div class="d-flex justify-content-between mb-4">
                        <ul class="nav nav-pills mb-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active d-flex align-items-center" id="miembros-tab" data-bs-toggle="tab" data-bs-target="#miembros" type="button" role="tab" aria-controls="miembros" aria-selected="true">
                                    <i class='bx bx-user'></i> <strong>Miembros</strong>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center" id="servicios-tab" data-bs-toggle="tab" data-bs-target="#servicios" type="button" role="tab" aria-controls="servicios" aria-selected="false">
                                    <i class='bx bx-store-alt'></i> <strong>Servicios Extra</strong>
                                </button>
                            </li>
                        </ul>


                            <div class="d-flex align-items-center">
                                <div class="btn-group me-2">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class='bx bxs-report'></i><strong>Informe de Miembros</strong>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="generarExcelMiembros()">Informe en Excel</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="generarPDFMiembros()">Informe en PDF</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class='bx bxs-report'></i><strong>Informe de Compras Total</strong>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="generarExcelCompras()">Informe en Excel</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="generarPDFCompras()">Informe en PDF</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content mt-3" id="myTabContent">
                            <div class="tab-pane fade show active" id="miembros" role="tabpanel" aria-labelledby="miembros-tab">
                                <h2 class="mb-4">Miembros Registrados</h2>
                                <table class="table table-bordered table-striped" id="tbl_miembros">
                                    <thead class="table-header">
                                        <tr>
                                            <th>Comprar</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>Telefono</th>
                                            <th>Direccion</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl_body">
                                    </tbody>
                                </table>
                            </div>

                            
                            <div class="tab-pane fade" id="servicios" role="tabpanel" aria-labelledby="servicios-tab">
                                <h2 class="mb-4">Servicios Extra</h2>

                                <form id="formulario_servicios">
                                    <div class="row mb-3">
                                        <input type="hidden" class="form-control" id="did_sala" name="txt_cantidad_servicio" value="1" required>
                                        <div class="col-md-3">
                                            <label for="txt_productos" class="form-label"><strong>Producto:</strong></label>
                                            <select class="form-control" id="txt_productos" name="txt_productos" required>
                                                <!-- Opciones de productos aquí -->
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="txt_cantidad_servicio" class="form-label"><strong>Cantidad:</strong></label>
                                            <input type="number" class="form-control" id="txt_cantidad_servicio" name="txt_cantidad_servicio" value="1" min="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="txt_precios" class="form-label"><strong>Precio:</strong></label>
                                            <input type="text" class="form-control" id="txt_precios" name="txt_precios" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="txt_total_servicio" class="form-label"><strong>Total:</strong></label>
                                            <input type="text" class="form-control" id="txt_total_servicio" name="txt_total_servicio" readonly>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end ms-auto">
                                            <button style="margin-top: 20px;" type="button" onclick="enviarComprassala()" class="btn btn-primary w-100 btn-margin-top" id="btn_agregar_servicio">
                                                <i class='bx bx-cart'></i> <strong>Agregar Servicio</strong>
                                            </button>
                                        </div>
                                    </div>

                                    <table class="table table-bordered table-striped mt-4" id="tbl_servicios">
                                        <thead class="table-header">
                                            <tr>
                                                <th>Sala</th>
                                                <th>Compra</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Total</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbl_body_servicio">
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





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
                                            
                                                <input type="hidden" name="id_miembro" id="id_miembro" class="form-control" value="<?php echo htmlspecialchars($id_miembro); ?>" readonly>
                                                <input type="hidden" name="id_sala" id="id_sala" class="form-control" >
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
                                                <div class="col-md-3 d-flex align-items-end ms-auto">
                                                    <button style="margin-top: 20px;" type="button" onclick="enviarCompras()" class="btn btn-primary w-100 btn-margin-top" id="btn_agregar_compra">
                                                        <i class='bx bx-cart'></i> <strong>Agregar</strong>
                                                    </button>
                                                </div>
                                            </div>
                                            <table class="table table-bordered table-striped mt-4" id="tbl_compras">
                                                <thead class="table-header">
                                                    <tr>
                                                        <th>Sala</th>
                                                        <th>Compra</th>
                                                        <th>Miembro</th>
                                                        <th>Producto</th>
                                                        <th>Cantidad</th>
                                                        <th>Precio</th>
                                                        <th>Total</th>
                                                        <th>Eliminar</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbl_boby">
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            <i class='bx bx-x'></i><strong>Cerrar</strong> 
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
        lista_comprassala();
        select_productos();
        select_productossala();
        
        $('#txt_cantidad').on('input', calcularTotal); 
        $('#txt_producto').on('change', function() {
            var precio = $(this).find('option:selected').data('precio');
            $('#txt_precio').val(precio);
            calcularTotal(); 
        });

        $('#txt_cantidad_servicio').on('input', calcularTotalsala); 
        $('#txt_productos').on('change', function() {
            var precio = $(this).find('option:selected').data('precio');
            $('#txt_precios').val(precio);
            calcularTotalsala(); 
        });
    });
    $(document).ready(function() {
    select_productos();
    $('#txt_cantidad_servicio').on('input', calcularTotalsala); 
    $('#txt_productos').on('change', function() {
        var precio = $(this).find('option:selected').data('precio');
        $('#txt_precios').val(precio);
        calcularTotalsala(); 
    });
})

    function lista_usuario() {    
        var id = '<?php echo $espacio; ?>'
        data = 
        {
            'id':id,
        }   
        $.ajax({
            url: '../controlador/COWORKING/crear_mienbrosC.php?lista_mienbro=true',
            type: 'post',
            data: { data: data },
            dataType: 'json',        
            success: function(response) {  
                $('#tbl_body').html(response);
                console.log(response);
            }       
        });
    }

    function lista_compra() {  
    var id = '<?php echo $sala; ?>'// Asegúrate de que este valor está disponible.
    //console.log(id_espacio);
    data = 
    {
        'id':id,
    }
    $.ajax({
        url: '../controlador/COWORKING/crear_mienbrosC.php?lista_compra=true',
        type: 'post',
        data: { data: data }, 
        dataType: 'json', 
        success: function(response) {  
            $('#tbl_boby').html(response);
            console.log(response);
        }       
    });
}


    function lista_comprassala() {       
        $.ajax({
            url: '../controlador/COWORKING/crear_mienbrosC.php?lista_comprasala=true',
            type: 'post',
            dataType: 'json', 
            success: function(response) {  
                $('#tbl_body_servicio').html(response);
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
            if (response.status === 'success') {
                Swal.fire({
                    title: 'Miembro agregado con éxito',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    lista_usuario(); 
                    $('#formulario_miembro')[0].reset(); 
                });
            } else {
                Swal.fire({
                    title: 'Error al agregar el miembro',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        },
        error: function() {
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un problema en el servidor',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
}


    function enviarCompras() {
        var parametros = {
            'id_miembro': $('#id_miembro').val(),
            'id_producto': $('#txt_producto').val(),
            'cantidad_compra': $('#txt_cantidad').val(),
            'pvp_compra': $('#txt_precio').val(),
            'total_compra': $('#txt_total').val(),
            'id_sala': $('#id_sala').val()
        };

        $.ajax({
            data: {data: parametros},
            url: '../controlador/COWORKING/crear_mienbrosC.php?add_compra=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {  
                if (response == 1) {
                    Swal.fire({
                        title: 'Compra agregada con éxito',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        lista_compra(); 
                        $('#formulario_compras')[0].reset(); 
                    });
                } else {
                    Swal.fire({
                        title: 'Error al agregar la compra',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            }
        });
    }

    function enviarComprassala() {
        var parametros = {
            'id_producto': $('#txt_productos').val(),
            'cantidad_compra': $('#txt_cantidad_servicio').val(),
            'pvp_compra': $('#txt_precios').val(),
            'total_compra': $('#txt_total_servicio').val(),
            'id_sala': $('#did_sala').val()
        };

        
        $.ajax({
            data: {data: parametros},
            url: '../controlador/COWORKING/crear_mienbrosC.php?add_compra=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {  
                if (response == 1) {
                    Swal.fire({
                        title: 'Compra agregada con éxito',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        lista_comprassala(); 
                        $('#formulario_servicios')[0].reset(); 
                    });
                } else {
                    Swal.fire({
                        title: 'Error al agregar la compra',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            }
        });
    }

    function abrirModal(id_miembro, id_sala) {
        console.log('ID Miembro:', id_miembro);  // Verifica que el ID del miembro se pase correctamente
        $('#id_miembro').val(id_miembro);        // Asigna el ID del miembro al campo en el modal

        console.log('ID Sala:', id_sala);        // Verifica que el ID de la sala (espacio) se pase correctamente
        $('#id_sala').val(id_sala);              // Asigna el ID de la sala (espacio) al campo correspondiente en el modal

        select_productos();                      // Cualquier otra lógica adicional que necesites al abrir el modal
    }

    function calcularTotal() {
        var cantidad = parseFloat($('#txt_cantidad').val()) || 0;
        var precio = parseFloat($('#txt_precio').val()) || 0;
        $('#txt_total').val((cantidad * precio).toFixed(2));
    }

    function calcularTotalsala() {
        var cantidad = parseFloat($('#txt_cantidad_servicio').val()) || 0;
        var precio = parseFloat($('#txt_precios').val()) || 0;
        $('#txt_total_servicio').val((cantidad * precio).toFixed(2));
    }

    function eliminarCompra(id_compra) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará la compra seleccionada.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controlador/COWORKING/crear_mienbrosC.php?eliminar_compra=true',
                    type: 'POST',
                    data: { id_compra: id_compra },
                    dataType: 'json',
                    success: function(response) {
                        if (response === "Compra eliminada con éxito") {
                            $('#row-compra-' + id_compra).remove();
                            Swal.fire('Eliminado', 'Compra eliminada con éxito', 'success');
                            lista_compra();
                            lista_comprassala();    
                        } else {
                            Swal.fire('Error', 'Error al eliminar la compra', 'error');
                        }
                    }
                });
            }
        });
    }

    function eliminarMiembro(id_miembro) {
        $.ajax({
            url: '../controlador/COWORKING/crear_mienbrosC.php',
            type: 'POST',
            data: { id_miembro: id_miembro, action: 'verificar_compras' },
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta de verificar_compras:', response);

                if (response.error) {
                    Swal.fire({
                        title: 'Error',
                        text: response.error,
                        icon: 'error',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                if (response.tiene_compras) {
                    Swal.fire({
                        title: 'Esta persona tiene compras agregadas y no se puede eliminar',
                        icon: 'warning',
                        confirmButtonText: 'Entendido'
                    });
                } else {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción eliminará al miembro seleccionado.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '../controlador/COWORKING/crear_mienbrosC.php?eliminar_miembro=true',
                                type: 'POST',
                                data: { id_miembro: id_miembro },
                                dataType: 'json',
                                success: function(response) {
                                    console.log('Respuesta de eliminar_miembro:', response);

                                    if (response === "Miembro eliminado con éxito") {
                                        $('#row-miembro-' + id_miembro).remove();
                                        Swal.fire('Eliminado', 'Miembro eliminado con éxito', 'success');
                                        lista_usuario();
                                    } else {
                                        Swal.fire('Error', 'Error al eliminar el miembro', 'error');
                                    }
                                }
                            });
                        }
                    });
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
            }       
        });
    }

    function select_productossala() {       
        $.ajax({
            url: '../controlador/COWORKING/crear_mienbrosC.php?listar_productossala=true',
            type: 'post',
            dataType: 'json',        
            success: function(response) {
                $('#txt_productos').html(response);
            }       
        });
    }

    function generarPDFMiembros() {
            var url ='../controlador/COWORKING/crear_mienbrosC.php?generarPDFMiembros=true'
            window.open(url,"_blank");
        }

    function generarPDFCompras() {
            var url ='../controlador/COWORKING/crear_mienbrosC.php?generarPDFCompras=true'
            window.open(url,"_blank");
        }

    function generarExcelMiembros(){
            var url ='../lib/excel_spout.php?generarExcelMiembros=true'
            window.open(url,"_blank");


        }

    function generarExcelCompras(){
            var url ='../lib/excel_spout.php?generarExcelCompras=true'
            window.open(url,"_blank");
        }
</script>