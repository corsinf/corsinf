<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            cargar_bodegas(<?= $_id ?>);
        <?php } ?>


        function cargar_bodegas(id) {
            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/XPACE_CUBE/bodegasC.php?listar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response);
                    $('#txt_codigo').val(response[0].codigo);
                    $('#txt_nombre').val(response[0].nombre);
                    $('#txt_descripcion').val(response[0].descripcion);
                    //$('#txt_categoria').val(response[0].categoria);
                    $('#txt_cantidad_total').val(response[0].cantidad_total);
                    $('#txt_cantidad_disponible').val(response[0].cantidad_disponible);
                    $('#txt_precio_unitario').val(response[0].precio_unitario);
                    $('#txt_fecha_ingreso').val(formatearFechaParaInput(response[0].fecha_ingreso));
                    $('#ddl_miembro').append($('<option>', {
                        value: response[0].categoria,
                        text: response[0].categoria,
                        selected: true
                    }));
                }
            });

        }

        function formatearFechaParaInput(fechaSQL) {
        if (!fechaSQL) return "";
        // Elimina los microsegundos si existen y reemplaza el espacio por 'T'
        return fechaSQL.replace(" ", "T").substring(0, 16);
    }
    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Ubicaciones</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Registros
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Artículo';
                                } else {
                                    echo 'Modificar Artículo';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_bodegas" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_espacios">
                            <!-- Requiere Bootstrap 5 y Boxicons -->
                            <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
                            <!-- <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> -->

                            <div class="row g-3">
                                <!-- Código -->
                                <div class="col-md-6">
                                    <label for="txt_codigo" class="form-label fw-bold d-flex align-items-center mb-1">
                                        <i class="bx bx-barcode me-2 text-primary fs-5" aria-hidden="true"></i> Código
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_codigo"
                                        name="txt_codigo"
                                        placeholder="Ej: ART-001"
                                        autocomplete="off"
                                        aria-label="Código" />
                                </div>

                                <!-- Nombre -->
                                <div class="col-md-6">
                                    <label for="txt_nombre" class="form-label fw-bold d-flex align-items-center mb-1">
                                        <i class="bx bx-tag me-2 text-primary fs-5" aria-hidden="true"></i> Nombre
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_nombre"
                                        name="txt_nombre"
                                        placeholder="Nombre del artículo"
                                        autocomplete="off"
                                        aria-label="Nombre" />
                                </div>
                               

                                <!-- Descripción -->
                                <div class="col-12">
                                    <label for="txt_descripcion" class="form-label fw-bold d-flex align-items-center mb-1">
                                        <i class="bx bx-file me-2 text-secondary fs-5" aria-hidden="true"></i> Descripción
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-sm"
                                        id="txt_descripcion"
                                        name="txt_descripcion"
                                        placeholder="Descripción corta del artículo"
                                        autocomplete="off"
                                        aria-label="Descripción" />
                                </div>

                                <!-- Categoria / Tipo -->
                                <div class="col-md-6">
                                    <label for="ddl_categoria" class="form-label fw-bold d-flex align-items-center mb-1">
                                        <i class="bx bx-grid-alt me-2 text-success fs-5" aria-hidden="true"></i> Categoría
                                    </label>
                                    <select class="form-select form-select-sm select2-validation"
                                        id="ddl_categoria"
                                        name="ddl_categoria"
                                        required
                                        aria-label="Categoría">
                                        <option value="" selected hidden>-- Seleccione --</option>
                                        <option value="Mobiliario" selected>Mobiliario</option>
                                        <option value="Tecnología" selected>Tecnología</option>
                                        <option value="Iluminación" selected>Iluminación</option>
                                        <option value="Equipamiento" selected>Equipamiento</option>
                                        <!-- opciones dinámicas -->
                                    </select>
                                    <div class="form-text">Seleccione la categoría del artículo.</div>
                                    <label class="error" style="display: none;" for="ddl_categoria"></label>
                                </div>

                                <!-- Cantidad total -->
                                <div class="col-md-3">
                                    <label for="txt_cantidad_total" class="form-label fw-bold d-flex align-items-center mb-1">
                                        <i class="bx bx-layer me-2 text-warning fs-5" aria-hidden="true"></i> Cantidad total
                                    </label>
                                    <input type="number"
                                        class="form-control form-control-sm"
                                        id="txt_cantidad_total"
                                        name="txt_cantidad_total"
                                        placeholder="0"
                                        min="0"
                                        step="1"
                                        inputmode="numeric"
                                        aria-label="Cantidad total" />
                                    <div class="form-text">Número entero.</div>
                                </div>

                                <!-- Cantidad disponible -->
                                <div class="col-md-3">
                                    <label for="txt_cantidad_disponible" class="form-label fw-bold d-flex align-items-center mb-1">
                                        <i class="bx bx-box me-2 text-warning fs-5" aria-hidden="true"></i> Cantidad disponible
                                    </label>
                                    <input type="number"
                                        class="form-control form-control-sm"
                                        id="txt_cantidad_disponible"
                                        name="txt_cantidad_disponible"
                                        placeholder="0"
                                        min="0"
                                        step="1"
                                        inputmode="numeric"
                                        aria-label="Cantidad disponible" />
                                    <div class="form-text">Cantidad actualmente disponible.</div>
                                </div>

                                <!-- Precio unitario -->
                                <div class="col-md-6">
                                    <label for="txt_precio_unitario" class="form-label fw-bold d-flex align-items-center mb-1">
                                        <i class="bx bx-dollar-circle me-2 text-info fs-5" aria-hidden="true"></i> Precio unitario
                                    </label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number"
                                            class="form-control form-control-sm"
                                            id="txt_precio_unitario"
                                            name="txt_precio_unitario"
                                            placeholder="0.00"
                                            min="0"
                                            step="0.01"
                                            inputmode="decimal"
                                            pattern="^\d+(\.\d{1,2})?$"
                                            aria-label="Precio unitario" />
                                    </div>
                                    <div class="form-text">Máx. 2 decimales.</div>
                                </div>

                                <!-- Fecha de ingreso -->
                                <div class="col-md-6">
                                    <label for="txt_fecha_ingreso" class="form-label fw-bold d-flex align-items-center mb-1">
                                        <i class="bx bx-calendar me-2 text-success fs-5" aria-hidden="true"></i> Fecha de ingreso
                                    </label>
                                    <input type="datetime-local"
                                        class="form-control form-control-sm"
                                        id="txt_fecha_ingreso"
                                        name="txt_fecha_ingreso"
                                        autocomplete="off"
                                        aria-label="Fecha de ingreso" />
                                </div>

                                
                            </div>



                            <div class="mt-4 text-end">
                                <?php if ($_id == '') { ?>
                                    <button type="button" class="btn btn-success btn-sm px-4 m-0" id="btn_guardar" onclick=""><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar" onclick=""><i class="bx bx-save"></i> Editar</button>
                                    <button type="button" class="btn btn-danger btn-sm px-4 m-1" id="" onclick=""><i class="bx bx-trash"></i> Eliminar</button>
                                <?php } ?>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>