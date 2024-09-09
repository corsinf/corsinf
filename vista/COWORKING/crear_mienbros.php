<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.19/jspdf.plugin.autotable.min.js"></script>


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
                        <h1 class="titulo mb-4">Oficina 5</h1>

                        <!-- Pestañas -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="miembros-tab" data-bs-toggle="tab" data-bs-target="#miembros" type="button" role="tab" aria-controls="miembros" aria-selected="true">
                                    <i class='bx bx-user'></i> <strong>Miembros</strong>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="servicios-tab" data-bs-toggle="tab" data-bs-target="#servicios" type="button" role="tab" aria-controls="servicios" aria-selected="false">
                                    <i class='bx bx-store-alt'></i> <strong>Servicios Extra</strong>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="myTabContent">
                            <!-- Tab de Miembros -->
                            <div class="tab-pane fade show active" id="miembros" role="tabpanel" aria-labelledby="miembros-tab">
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
                                    <button type="button" onclick="enviardatos()" class="btn btn-primary" id="btn_registrar_miembro">
                                        <strong>Registrar Miembro</strong>
                                    </button>
                                </form>

                                <h2 class="mb-4">Miembros Registrados</h2>
                                <div class="row mb-3">
                                    <div class="col-12 d-flex justify-content-end">
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

                                <table class="table table-bordered table-striped" id="tbl_miembros">
                                    <thead class="table-header">
                                        <tr>
                                            <th>Comprar</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>Telefono</th>
                                            <th>Direccion</th>
                                            <th>Espacio</th>
                                            <th>Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl_body">
                                    </tbody>
                                </table>
                            </div>

                            <!-- Tab de Servicios Extra -->
                            <div class="tab-pane fade" id="servicios" role="tabpanel" aria-labelledby="servicios-tab">
                                <h2 class="mb-4">Servicios Extra</h2>

                                <form id="formulario_servicios">
                                    <div class="row mb-3">
                                        <input type="hidden" name="id_servicio" id="id_servicio" class="form-control" readonly>
                                        <div class="col-md-3">
                                            <label for="txt_servicio" class="form-label"><strong>Servicio:</strong></label>
                                            <select class="form-control" id="txt_servicio" name="txt_servicio" required>
                                                <option value="" disabled selected>Seleccionar servicio</option>
                                                <option value="Servicio 1">Servicio 1</option>
                                                <option value="Servicio 2">Servicio 2</option>
                                                <option value="Servicio 3">Servicio 3</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="txt_cantidad_servicio" class="form-label"><strong>Cantidad:</strong></label>
                                            <input type="number" class="form-control" id="txt_cantidad_servicio" name="txt_cantidad_servicio" value="1" min="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="txt_precio_servicio" class="form-label"><strong>Precio:</strong></label>
                                            <input type="text" class="form-control" id="txt_precio_servicio" name="txt_precio_servicio" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="txt_total_servicio" class="form-label"><strong>Total:</strong></label>
                                            <input type="text" class="form-control" id="txt_total_servicio" name="txt_total_servicio" readonly>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end ms-auto">
                                            <button style="margin-top: 20px;" type="button" onclick="enviarServicio()" class="btn btn-primary w-100 btn-margin-top" id="btn_agregar_servicio">
                                                <i class='bx bx-cart'></i> <strong>Agregar Servicio</strong>
                                            </button>
                                        </div>
                                    </div>

                                    <table class="table table-bordered table-striped mt-4" id="tbl_servicios">
                                        <thead class="table-header">
                                            <tr>
                                                <th>Servicio</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Total</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbl_body_servicios">
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
                                                <input type="hidden" name="id_sala" id="id_sala" class="form-control" value="">
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
            if (response === 1) {
                
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
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
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
                    title: 'Error al agregar el compra',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
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
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('Error al eliminar miembro:', textStatus, errorThrown);
                                Swal.fire('Error', 'Error al eliminar el miembro', 'error');
                            }
                        });
                    }
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al verificar compras:', textStatus, errorThrown);
            Swal.fire('Error', 'Error al verificar las compras del miembro', 'error');
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


    function generarExcelMiembros() {
            const ws = XLSX.utils.table_to_sheet(document.getElementById('tbl_miembros'));
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Miembros");
            XLSX.writeFile(wb, "miembros.xlsx");
        }

        function generarExcelCompras() {
            const ws = XLSX.utils.table_to_sheet(document.getElementById('tbl_compras'));
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Compras");
            XLSX.writeFile(wb, "compras.xlsx");
        }

        async function generarPDFMiembros() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text('Informe de Miembros', 10, 10);
            const table = document.getElementById('tbl_miembros');
            doc.autoTable({ html: table });
            doc.save('miembros.pdf');
        }

        async function generarPDFCompras() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text('Informe de Compras', 10, 10);
            const table = document.getElementById('tbl_compras');
            doc.autoTable({ html: table });
            doc.save('compras.pdf');
        }




</script>