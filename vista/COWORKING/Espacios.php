

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
                            <strong>Registro Espacios</strong>
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
                        <h1 class="titulo mb-4">Espacios</h1>
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
                                <i class="bx bx-user-plus"></i><strong>Registrar Espacio</strong>
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
                                    
                                </div>
                                <div class="btn-group">
                                    
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
                                            <th>Espacio</th>
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

R


    

    
</script>