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
            cargar_espacio(<?= $_id ?>);
        <?php } ?>
        cargar_selects2();

        function cargar_espacio(id) {
            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/XPACE_CUBE/espaciosC.php?listar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response);
                    $('#txt_nombre').val(response[0].nombre);
                    $('#txt_codigo').val(response[0].codigo);
                    $('#txt_capacidad').val(response[0].capacidad);
                    $('#txt_tarifa_hora').val(response[0].tarifa_hora);
                    $('#txt_tarifa_dia').val(response[0].tarifa_dia);
                    $('#ddl_tipo_espacio').append($('<option>', {
                        value: response[0].id_tipo_espacio,
                        text: response[0].nombre_tipo_espacio,
                        selected: true
                    }));
                    $('#ddl_ubicacion').append($('<option>', {
                        value: response[0].id_ubicacion,
                        text: response[0].nombre_ubicacion,
                        selected: true
                    }));
                }
            });

        }
    });

    function cargar_selects2() {
        url_espaciosC = '../controlador/XPACE_CUBE/tipo_espacioC.php?buscar=true';
        cargar_select2_url('ddl_tipo_espacio', url_espaciosC);

        url_ubicacionesC = '../controlador/XPACE_CUBE/ubicacionesC.php?buscar=true';
        cargar_select2_url('ddl_ubicacion', url_ubicacionesC);
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Espacios</div>
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
                                    echo 'Registrar Espacio';
                                } else {
                                    echo 'Modificar Espacio';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_espacios" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_espacios">
                            <div class="row g-3">
                                <!-- Row 1: Nombre + Código -->
                                <div class="col-md-6">
                                    <label for="txt_nombre" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-edit-alt me-2 text-primary fs-5"></i> Nombre
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_nombre"
                                        name="txt_nombre"
                                        placeholder="Ingrese el nombre"
                                        autocomplete="off" />
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_codigo" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-barcode me-2 text-primary fs-5"></i> Código
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_codigo"
                                        name="txt_codigo"
                                        placeholder="Ingrese el código"
                                        autocomplete="off" />
                                </div>

                                <!-- Row 2: Tipo de espacio + Ubicación (selects) -->
                                <div class="col-md-6">
                                    <label for="ddl_tipo_espacio" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-grid-alt me-2 text-success fs-5"></i> Tipo de espacio
                                    </label>
                                    <select class="form-select form-select-sm select2-validation"
                                        id="ddl_tipo_espacio"
                                        name="ddl_tipo_espacio"
                                        required>
                                        <option value="" selected hidden>-- Seleccione --</option>
                                        <!-- opciones dinámicas -->
                                    </select>
                                    <div class="form-text">Seleccione el tipo de espacio.</div>
                                    <label class="error" style="display: none;" for="ddl_tipo_espacio"></label>
                                </div>

                                <div class="col-md-6">
                                    <label for="ddl_ubicacion" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-building me-2 text-success fs-5"></i> Ubicación
                                    </label>
                                    <select class="form-select form-select-sm select2-validation"
                                        id="ddl_ubicacion"
                                        name="ddl_ubicacion"
                                        required>
                                        <option value="" selected hidden>-- Seleccione --</option>
                                        <!-- opciones dinámicas -->
                                    </select>
                                    <div class="form-text">Seleccione la ubicación del espacio.</div>
                                    <label class="error" style="display: none;" for="ddl_ubicacion"></label>
                                </div>

                                <!-- Row 3: Capacidad -->
                                <div class="col-md-6">
                                    <label for="txt_capacidad" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-group me-2 text-warning fs-5"></i> Capacidad
                                    </label>
                                    <input type="number"
                                        class="form-control form-control-sm"
                                        id="txt_capacidad"
                                        name="txt_capacidad"
                                        placeholder="Capacidad del espacio"
                                        min="0"
                                        step="1"
                                        inputmode="numeric" />
                                    <div class="form-text">Número entero (personas/puestos).</div>
                                </div>

                                <!-- Row 3 (cont): Tarifas hora / día -->
                                <div class="col-md-6">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label for="txt_tarifa_hora" class="form-label fw-bold d-flex align-items-center">
                                                <i class="bx bx-time me-2 text-info fs-5"></i> Tarifa (Hora)
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">$</span>
                                                <input type="number"
                                                    class="form-control form-control-sm"
                                                    id="txt_tarifa_hora"
                                                    name="txt_tarifa_hora"
                                                    placeholder="0.00"
                                                    min="0"
                                                    step="0.01"
                                                    inputmode="decimal"
                                                    pattern="^\d+(\.\d{1,2})?$" />
                                            </div>
                                            <div class="form-text">Precio por hora (máx 2 decimales).</div>
                                        </div>

                                        <div class="col-6">
                                            <label for="txt_tarifa_dia" class="form-label fw-bold d-flex align-items-center">
                                                <i class="bx bx-calendar me-2 text-info fs-5"></i> Tarifa (Día)
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">$</span>
                                                <input type="number"
                                                    class="form-control form-control-sm"
                                                    id="txt_tarifa_dia"
                                                    name="txt_tarifa_dia"
                                                    placeholder="0.00"
                                                    min="0"
                                                    step="0.01"
                                                    inputmode="decimal"
                                                    pattern="^\d+(\.\d{1,2})?$" />
                                            </div>
                                            <div class="form-text">Precio por día (máx 2 decimales).</div>
                                        </div>
                                    </div>
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


<div class="modal" id="modal_blank" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="">Tipo de <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm" onchange="">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="">Blank <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-success btn-sm" onclick=""><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>