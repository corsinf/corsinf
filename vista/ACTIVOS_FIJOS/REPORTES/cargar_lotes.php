<script src="../js/ACTIVOS_FIJOS/avaluos.js"></script>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Detectar lote seleccionado inicialmente
        let loteSeleccionado = $('input[name="rbx_lote_tipo"]:checked').val();

        // Cargar select con el lote seleccionado al inicio
        let url_descargaLoteC = '../controlador/ACTIVOS_FIJOS/REPORTES/ac_descargasC.php?lista_drop=true&lote=' + loteSeleccionado;
        cargar_select2_url('ddl_lote', url_descargaLoteC, '-- Seleccione --');

        // Luego detectar cambios en los radios para actualizar
        $('input[name="rbx_lote_tipo"]').change(function() {
            loteSeleccionado = $('input[name="rbx_lote_tipo"]:checked').val();

            $.ajax({
                url: '../controlador/ACTIVOS_FIJOS/REPORTES/ac_descargasC.php',
                type: 'GET',
                data: {
                    lote: loteSeleccionado
                },
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    // Actualiza UI si quieres
                },
                error: function() {
                    alert('Error al enviar el lote seleccionado');
                }
            });

            url_descargaLoteC = '../controlador/ACTIVOS_FIJOS/REPORTES/ac_descargasC.php?lista_drop=true&lote=' + loteSeleccionado;
            cargar_select2_url('ddl_lote', url_descargaLoteC, '-- Seleccione --');
        });
    });

    function guardar_descarga_lote() {
        // Recolectar valores del formulario
        var parametros = {
            'tipo_lote': $('input[name="rbx_lote_tipo"]:checked').val(),
            'lote_seleccionado': $('#ddl_lote').val(),
            'tipo_carga': $('input[name="rbx_tipo_carga"]:checked').val()
        };

        // Validar el formulario antes de enviar
        if ($("#form_descarga_lote").valid()) {
            $.ajax({
                url: '../controlador/ACTIVOS_FIJOS/REPORTES/ac_descargasC.php?parametros_lote=true',
                type: 'POST',
                dataType: 'json',
                data: {
                    parametros: parametros
                },
                success: function(response) {
                    if (response) {
                        Swal.fire('Éxito Descarga iniciada correctamente.', 'success');
                    } 
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al procesar la descarga.', 'error');
                }
            });
        }
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Descarga por Lotes</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Descargar Lote
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

                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">
                            </div>
                        </div>


                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bx-package me-1 font-22 text-info"></i></div>
                            <h5 class="mb-0 text-info">Selección de Lote</h5>
                        </div>
                        <form id="form_descarga_lote">
                            <section class="content pt-2">
                                <!-- Radio buttons para seleccionar tipo de lote -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rbx_lote_tipo" id="rbx_lote_1" value="lote_1" checked>
                                                <label class="form-check-label" for="rbx_lote_1">Lote 1</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rbx_lote_tipo" id="rbx_lote_2" value="lote_2">
                                                <label class="form-check-label" for="rbx_lote_2">Lote 2</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rbx_lote_tipo" id="rbx_lote_3" value="lote_3">
                                                <label class="form-check-label" for="rbx_lote_3">Lote 3</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Selector de lote + botón de descarga en la misma fila -->
                                <div class="row align-items-end mb-3">
                                    <div class="col-lg-6 col-md-8 col-sm-12">
                                        <label for="ddl_lote" class="form-label">Seleccionar Lote <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" name="ddl_lote" id="ddl_lote">
                                            <option value="">Seleccione un lote</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-4 col-sm-12 ">
                                        <button class="btn btn-primary btn-sm" type="submit">
                                            <i class="bx bx-download"></i> Descargas
                                        </button>
                                    </div>
                                </div>

                                <!-- Radio para tipo de carga: Individual o Masiva -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rbx_tipo_carga" id="rbx_carga_mas" value="masivo" checked>
                                                <label class="form-check-label" for="rbx_carga_mas">Masivo</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="rbx_tipo_carga" id="rbx_carga_ind" value="individual">
                                                <label class="form-check-label" for="rbx_carga_ind">Individual</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#form_descarga_lote").validate({
            rules: {
                ddl_lote: {
                    required: true
                },
                rbx_lote_tipo: {
                    required: true
                },
                rbx_tipo_carga: {
                    required: true
                }
            },
            messages: {
                ddl_lote: {
                    required: "Seleccione un lote."
                },
                rbx_lote_tipo: {
                    required: "Seleccione un tipo de lote."
                },
                rbx_tipo_carga: {
                    required: "Seleccione el tipo de carga."
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "rbx_lote_tipo" || element.attr("name") == "rbx_tipo_carga") {
                    error.insertAfter(element.closest(".d-flex"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            submitHandler: function(form) {
                guardar_descarga_lote();
                return false;
            }
        });
    });
</script>