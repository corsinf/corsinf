<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3"><strong>Formulario</strong></div>
            <div class="breadcrumb-separator"></div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bi bi-house"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><strong>Registro Miembros</strong></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <h6 class="mb-0 text-uppercase">Registro de Miembros y Compras</h6>
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
                                    <label for="txt_correo" class="form-label"><strong>Correo:</strong></label>
                                    <input type="email" class="form-control form-control-sm" name="txt_correo" id="txt_correo" placeholder="Correo electrónico" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="txt_cedula" class="form-label"><strong>Cédula:</strong></label>
                                    <input type="text" class="form-control form-control-sm" name="txt_cedula" id="txt_cedula" placeholder="Cédula" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="txt_numero_celular" class="form-label"><strong>Número Celular:</strong></label>
                                    <input type="text" class="form-control form-control-sm" name="txt_numero_celular" id="txt_numero_celular" placeholder="Número" step="1" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" id="btn_registrar_miembro">Registrar Miembro</button>
                        </form>

                        <h2 class="mb-4">Miembros Registrados</h2>
                        <table class="table table-bordered table-striped" id="tbl_miembros">
                            <thead class="table-header">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Cédula</th>
                                    <th>Número</th>
                                    <th>Compras</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>

                        <div class="modal fade" id="modal_registrar_compra" tabindex="-1" aria-labelledby="modal_registrar_compra_label" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal_registrar_compra_label">Registrar Compra</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formulario_compras">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="ddl_producto" class="form-label"><strong>Producto:</strong></label>
                                                    <select class="form-control" id="ddl_producto" name="ddl_producto" required>
                                                        <option value="" data-precio="">Seleccione un producto</option>
                                                        <option value="Doritos" data-precio="0.45">Doritos - $0.45</option>
                                                        <option value="Papas" data-precio="0.70">Papas - $0.70</option>
                                                        <option value="Coca Cola" data-precio="1.70">Coca Cola - $1.70</option>
                                                        <option value="Gomitas" data-precio="1.00">Gomitas - $1.00</option>
                                                        <option value="Caramelos" data-precio="2.70">Caramelos - $2.70</option>
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
                                            </div>
                                            <table class="table table-bordered table-striped mt-4" id="tbl_compras">
                                                <thead class="table-header">
                                                    <tr>
                                                        <th>Miembro</th>
                                                        <th>Producto</th>
                                                        <th>Cantidad</th>
                                                        <th>Precio</th>
                                                        <th>Total</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Aquí se registran las compras -->
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-primary" id="btn_agregar_compra">
                                                <i class="bi bi-bag-plus-fill"></i> Agregar
                                            </button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                            Cerrar <i class="bi bi-file-x"></i>
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

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#ddl_producto').change(function() {
            var precio = $(this).find(':selected').data('precio');
            $('#txt_precio').val(precio ? '$' + parseFloat(precio).toFixed(2) : '');
        });

        $('#btn_registrar_miembro').click(function() {
            var nombre = $('#txt_nombre').val();
            var correo = $('#txt_correo').val();
            var cedula = $('#txt_cedula').val();
            var numero = $('#txt_numero_celular').val();

            if (nombre && correo && cedula && numero) {
                var nuevaFila = `<tr>
                    <td>${nombre}</td>
                    <td>${correo}</td>
                    <td>${cedula}</td>
                    <td>${numero}</td>
                    <td class="acciones">
                        <button class="btn btn-danger btn-sm btn-eliminar"><i class="bi bi-file-x"></i></button>
                        <button class="btn btn-primary btn-sm btn-registrar-compras" data-bs-toggle="modal" data-bs-target="#modal_registrar_compra"><i class="bi bi-cart4"></i></button>
                    </td>
                </tr>`;
                $('#tbl_miembros tbody').append(nuevaFila);
                $('#formulario_miembro')[0].reset();
            }
        });

        $('#btn_agregar_compra').click(function() {
            var producto = $('#ddl_producto').val();
            var cantidad = $('#txt_cantidad').val();
            var precio = $('#txt_precio').val();
            var miembro = $('#btn_agregar_compra').data('miembro');

            if (producto && cantidad && precio) {
                var total = parseFloat(precio.replace('$', '')) * cantidad;
                var nuevaFilaCompra = `<tr>
                    <td>${miembro}</td>
                    <td>${producto}</td>
                    <td>${cantidad}</td>
                    <td>${precio}</td>
                    <td>$${total.toFixed(2)}</td>
                    <td style="display: flex; justify-content: center; align-items: center;">
                        <button type="button" class="btn btn-danger btn-sm btn-eliminar-compra">
                            <i class="bi bi-file-x"></i>
                        </button>
                    </td>
                </tr>`;
                $('#tbl_compras tbody').append(nuevaFilaCompra);
                $('#formulario_compras')[0].reset();
            }
        });

        $('#tbl_miembros').on('click', '.btn-eliminar', function() {
            $(this).closest('tr').remove();
        });

        $('#tbl_compras').on('click', '.btn-eliminar-compra', function() {
            $(this).closest('tr').remove();
        });

        $('#tbl_miembros').on('click', '.btn-registrar-compras', function() {
            var miembro = $(this).closest('tr').find('td:first').text();
            $('#btn_agregar_compra').data('miembro', miembro);
        });
    });
</script>
