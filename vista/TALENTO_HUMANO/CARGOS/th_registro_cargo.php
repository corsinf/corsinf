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
            datos_col(<?= $_id ?>);
        <?php } ?>
    });

    function datos_col(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_cargoC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#txt_th_car_nombre').val(response[0].nombre);
                    $('#txt_th_car_descripcion').val(response[0].descripcion);
                }
            }
        });
    }

    function editar_insertar() {
        var txt_nombre = $('#txt_th_car_nombre').val();
        var txt_descripcion = $('#txt_th_car_descripcion').val();

        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_th_car_nombre': txt_nombre,
            'txt_th_car_descripcion': txt_descripcion,
        };

        if ($("#form_cargo").valid()) {
            insertar(parametros);
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_cargoC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (parametros._id !== '') {
                    location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_cat_cargos';
                    if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success').then(function() {
                            location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_cat_cargos';
                        });
                    }
                } else {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_cargo&_id=' + response;
                }

            },
            error: function(xhr, status, error) {
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_th_car_nombre').on('input', function() {
            $('#error_txt_th_car_nombre').text('');
            $(this).removeClass('is-invalid');
        });
    }

    function delete_datos() {
        var id = '<?= $_id ?>';
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {
                eliminar(id);
            }
        })
    }

    function eliminar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_cargoC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_cat_cargos';
                    });
                }
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Cargos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Registros</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card ">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-briefcase me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Cargo';
                                } else {
                                    echo 'Modificar Cargo';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_cat_cargos"
                                        class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i>
                                        Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="">
                            <div class="">
                                <ul class="nav nav-tabs nav-primary" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab"
                                            aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-briefcase-alt font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Cargo</div>
                                            </div>
                                        </a>
                                    </li>
                                    <?php if ($_id != '') { ?>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#aspectos_intrinsecos" role="tab"
                                                aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class='bx bxs-file-blank font-18 me-1'></i>
                                                    </div>
                                                    <div class="tab-title">Aspectos Intrínsecos</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab_aspectos_extrinsecos" role="tab"
                                                aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class='bx bxs-file-blank font-18 me-1'></i>
                                                    </div>
                                                    <div class="tab-title">Aspectos Extrínsecos</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab_compliance" role="tab"
                                                aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class='bx bxs-file-blank font-18 me-1'></i>
                                                    </div>
                                                    <div class="tab-title">Compliance</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" data-bs-toggle="tab" href="#tab_competencias" role="tab"
                                                aria-selected="false">
                                                <div class="d-flex align-items-center">
                                                    <div class="tab-icon"><i class='bx bxs-file-blank font-18 me-1'></i>
                                                    </div>
                                                    <div class="tab-title">Competencias</div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="tab-content py-3">
                                <!-- SECCION 1: CARGO -->
                                <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                                    <section class="content pt-0">
                                        <div class="container-fluid">
                                            <form id="form_cargo">
                                                <input type="hidden" id="txt_th_car_id" name="txt_th_car_id" value="<?= $_id ?>" />

                                                <div class="row pt-3 mb-col">
                                                    <div class="col-md-12">
                                                        <label for="txt_th_car_nombre" class="form-label">Nombre </label>
                                                        <input type="text" class="form-control form-control-sm no_caracteres"
                                                            id="txt_th_car_nombre" name="txt_th_car_nombre" maxlength="100" oninput="texto_mayusculas(this);">
                                                        <span id="error_txt_th_car_nombre" class="text-danger"></span>
                                                    </div>
                                                </div>

                                                <div class="row mb-col">
                                                    <div class="col-md-12">
                                                        <label for="txt_th_car_descripcion" class="form-label">Descripción </label>
                                                        <textarea class="form-control form-control-sm no_caracteres"
                                                            id="txt_th_car_descripcion"
                                                            name="txt_th_car_descripcion"
                                                            rows="3"
                                                            maxlength="400"></textarea>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end pt-2">
                                                    <?php if ($_id == '') { ?>
                                                        <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button">
                                                            <i class="bx bx-save"></i> Guardar
                                                        </button>
                                                    <?php } else { ?>
                                                        <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar()" type="button">
                                                            <i class="bx bx-save"></i> Editar
                                                        </button>
                                                        <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button">
                                                            <i class="bx bx-trash"></i> Eliminar
                                                        </button>
                                                    <?php } ?>
                                                </div>
                                            </form>

                                        </div>
                                    </section>
                                </div>

                                <!-- SECCION 2: ASPECTOS INTRINSECOS -->
                                <div class="tab-pane fade" id="aspectos_intrinsecos" role="tabpanel">
                                    <section class="content pt-0">
                                        <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_INTRINSECOS/aspectos_intrinsecos.php'); ?>
                                    </section>
                                </div>

                                <!-- SECCION 3: ASPECTOS EXTRINSECOS -->
                                <div class="tab-pane fade" id="tab_aspectos_extrinsecos" role="tabpanel">
                                    <section class="content pt-0">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-3 bg-light border-end">
                                                    <div class="p-3">
                                                        <div class="nav flex-column nav-pills gap-2"
                                                            id="v-pills-tab"
                                                            role="tablist"
                                                            aria-orientation="vertical">

                                                            <button class="nav-link active py-2 px-3 border shadow-sm"
                                                                data-bs-toggle="pill"
                                                                data-bs-target="#tab_requisitos_intelectuales"
                                                                type="button">
                                                                <i class="bx bx-brain me-2"></i>
                                                                Requisitos Intelectuales
                                                            </button>

                                                            <button class="nav-link py-2 px-3 border shadow-sm"
                                                                data-bs-toggle="pill"
                                                                data-bs-target="#tab_requisitos_fisicos"
                                                                type="button">
                                                                <i class="bx bx-body me-2"></i>
                                                                Requisitos Físicos
                                                            </button>

                                                            <button class="nav-link py-2 px-3 border shadow-sm"
                                                                data-bs-toggle="pill"
                                                                data-bs-target="#tab_responsabilidades_implicitas"
                                                                type="button">
                                                                <i class="bx bx-list-check me-2"></i>
                                                                Responsabilidades Implícitas
                                                            </button>

                                                            <button class="nav-link py-2 px-3 border shadow-sm"
                                                                data-bs-toggle="pill"
                                                                data-bs-target="#tab_condiciones_trabajo"
                                                                type="button">
                                                                <i class="bx bx-briefcase-alt me-2"></i>
                                                                Condiciones de Trabajo
                                                            </button>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-9 bg-white">
                                                    <div class="tab-content p-4" id="v-pills-tabContent">

                                                        <div class="tab-pane fade show active"
                                                            id="tab_requisitos_intelectuales"
                                                            role="tabpanel">
                                                            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/requisitos_intelectuales.php'); ?>
                                                        </div>

                                                        <div class="tab-pane fade"
                                                            id="tab_requisitos_fisicos"
                                                            role="tabpanel">
                                                            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/requisitos_fisicos.php'); ?>
                                                        </div>

                                                        <div class="tab-pane fade"
                                                            id="tab_responsabilidades_implicitas"
                                                            role="tabpanel">
                                                            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/responsabilidades_implicitas.php'); ?>
                                                        </div>

                                                        <div class="tab-pane fade"
                                                            id="tab_condiciones_trabajo"
                                                            role="tabpanel">
                                                            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/condiciones_trabajo.php'); ?>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <!-- SECCION 4: COMPLIANCE -->
                                <div class="tab-pane fade" id="tab_compliance" role="tabpanel">
                                    <section class="content pt-0">
                                        <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_COMPLIANCE/compliance.php'); ?>
                                    </section>
                                </div>

                                <!-- SECCION 5: COMPETENCIAS -->
                                <div class="tab-pane fade" id="tab_competencias" role="tabpanel">
                                    <section class="content pt-0">
                                        <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_COMPENTENCIAS/competencias.php'); ?>
                                    </section>
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
    agregar_asterisco_campo_obligatorio('txt_th_car_nombre');
    agregar_asterisco_campo_obligatorio('txt_th_car_descripcion');

    $("#form_cargo").validate({
        ignore: [],
        rules: {
            txt_th_car_nombre: {
                required: true
            },
            txt_th_car_descripcion: {
                required: true
            }
        },
        messages: {
            txt_th_car_nombre: {
                required: "Ingrese el nombre del cargo."
            },
            txt_th_car_descripcion: {
                required: "Ingrese la descripción del cargo."
            }
        },
        highlight: function(element) {
            $(element).removeClass('is-valid').addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            return false;
        }
    });
</script>