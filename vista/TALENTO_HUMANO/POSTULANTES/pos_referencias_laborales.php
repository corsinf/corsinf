<script>
    $(document).ready(function() {
        cargar_datos_referencias_laborales('<?= $id_postulante ?>');
    });

    //Formación Académica
    function cargar_datos_referencias_laborales(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_referencias_laboralesC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_referencias_laborales').html(response);
            }
        });
    }

    function cargar_datos_modal_referencias_laborales(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_referencias_laboralesC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_referencias_laborales_id').val(response[0]._id);
                $('#txt_nombre_referencia').val(response[0].th_refl_nombre_referencia);
                $('#txt_telefono_referencia').val(response[0].th_refl_telefono_referencia);
                $('#txt_ruta_guardada_carta_recomendacion').val(response[0].th_refl_carta_recomendacion);
                $('#txt_referencia_correo').val(response[0].th_refl_correo);
                $('#txt_referencia_nombre_empresa').val(response[0].th_refl_nombre_empresa);
                if (response[0].th_expl_id != null) {
                    $('#pnl_referencia_empresa').slideUp();
                    $('#txt_referencia_nombre_empresa').prop('readonly', true);
                    $('#txt_referencia_experiencia_id').val(response[0].th_expl_id);
                } else {
                    $('#pnl_referencia_empresa').slideDown();
                    $('#txt_referencia_nombre_empresa').prop('readonly', false);
                    $('#txt_referencia_experiencia_id').val('');
                }
            }
        });
    }

    function insertar_editar_referencias_laborales() {
        var form_data = new FormData(document.getElementById("form_referencias_laborales")); // Captura todos los campos y archivos

        var txt_id_referencias_laborales = $('#txt_referencias_laborales_id').val();

        if ($('#txt_copia_carta_recomendacion').val() === '' && txt_id_referencias_laborales != '') {
            var txt_copia_carta_recomendacion = $('#txt_ruta_guardada_carta_recomendacion').val()
            $('#txt_copia_carta_recomendacion').rules("remove", "required");
        }

        // console.log([...form_data]);
        // console.log([...form_data.keys()]);
        // console.log([...form_data.values()]);
        // return;

        if ($("#form_referencias_laborales").valid()) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_referencias_laboralesC.php?insertar=true',
                type: 'post',
                data: form_data,
                contentType: false,
                processData: false,

                dataType: 'json',
                success: function(response) {
                    //console.log(response);
                    if (response == -1) {
                        Swal.fire({
                            title: '',
                            text: 'Algo extraño ha ocurrido, intente más tarde.',
                            icon: 'error',
                            allowOutsideClick: false,
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    } else if (response == -2) {
                        Swal.fire({
                            title: '',
                            text: 'Asegúrese de que el archivo subido sea un PDF.',
                            icon: 'error',
                            allowOutsideClick: false,
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    } else if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success');
                        cargar_datos_experiencia_laboral('<?= $id_postulante ?>');
                        cargar_datos_referencias_laborales('<?= $id_postulante ?>');
                        limpiar_parametros_referencias_laborales();
                        $('#modal_agregar_referencia_laboral').modal('hide');
                    } else if (response == -5) {
                        Swal.fire({
                            title: 'Cédula Requerida',
                            text: 'Para registrar documentos, primero debe ingresar su número de cédula en la sección de Información Personal.',
                            icon: 'warning',
                            allowOutsideClick: false,
                            showConfirmButton: true,
                            confirmButtonText: 'Ir a Información Personal',
                            confirmButtonColor: '#0d6efd'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#modal_agregar_referencia_laboral').modal('hide');
                                $('.nav-link:contains("Información Personal")').tab('show');
                                setTimeout(() => {
                                    $('input[name*="cedula"], #txt_cedula').focus();
                                }, 300);
                            }
                        });
                    }
                }
            });
        }
    }

    //Funcion para editar el registro de referencias laborales
    function abrir_modal_referencias_laborales(id) {
        cargar_datos_modal_referencias_laborales(id);
        if ($('#modal_agregar_experiencia').is(':visible')) {
            $('#modal_agregar_experiencia').modal('hide');
        }
        $('#modal_agregar_referencia_laboral').modal('show');
        $('#lbl_titulo_referencia_laboral').html('Editar Referencia Laboral');
        $('#btn_guardar_referencia_laboral').html('<i class="bx bx-save"></i>Editar');
        $('#btn_eliminar_referencia_laboral').show();
    }

    function delete_datos_referencias_laborales() {
        var id = $('#txt_referencias_laborales_id').val();
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_referencias_laborales(id);
            }
        })
    }

    function eliminar_referencias_laborales(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_referencias_laboralesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    cargar_datos_experiencia_laboral('<?= $id_postulante ?>');
                    cargar_datos_referencias_laborales('<?= $id_postulante ?>');
                    limpiar_parametros_referencias_laborales();
                    $('#modal_agregar_referencia_laboral').modal('hide');
                }
            }
        });
    }

    function limpiar_parametros_referencias_laborales() {
        //certificaciones capacitaciones
        $('#txt_nombre_referencia').val('');
        $('#txt_telefono_referencia').val('');
        $('#txt_copia_carta_recomendacion').val('');
        $('#txt_referencias_laborales_id').val('');
        $('#txt_ruta_guardada_carta_recomendacion').val('');
        $('#txt_referencia_correo').val('');
        $('#txt_referencia_nombre_empresa').val('');
        $('#txt_referencia_experiencia_id').val('');
        //Limpiar validaciones
        $("#form_referencias_laborales").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');
        //Cambiar texto
        $('#lbl_titulo_referencia_laboral').html('Agregar Referencia Laboral');
        $('#btn_guardar_referencia_laboral').html('<i class="bx bx-save"></i>Agregar');
        $('#btn_eliminar_referencia_laboral').hide();
    }

    function definir_ruta_iframe_referencias_laborales(url) {
        $('#modal_ver_pdf_referencias_laborales').modal('show');
        var cambiar_ruta = $('#iframe_referencias_laborales_pdf').attr('src', url);
    }

    function limpiar_parametros_iframe() {
        $('#iframe_referencias_laborales_pdf').attr('src', '');
    }
</script>

<div id="pnl_referencias_laborales">
</div>

<!-- Modal para agregar referencias laborales
<div class="modal fade" id="modal_agregar_referencia_laboral" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_referencia_laboral">
                        <i class='bx bx-briefcase me-2'></i>Referencias Laborales
                    </h5>
                    <small class="text-muted">Ingresa contactos de empleadores previos que puedan validar tu experiencia.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_referencias_laborales()"></button>
            </div>

            <form id="form_referencias_laborales" enctype="multipart/form-data" class="needs-validation">
                <div class="modal-body">

                    <input type="hidden" name="txt_referencias_laborales_id" id="txt_referencias_laborales_id">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">
                    <input type="hidden" name="txt_referencia_experiencia_id" id="txt_referencia_experiencia_id">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="txt_nombre_referencia" class="form-label fw-semibold fs-7">Nombre del Jefe o Contacto </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-user'></i></span>
                                <input type="text" class="form-control no_caracteres" name="txt_nombre_referencia" id="txt_nombre_referencia" maxlength="50" placeholder="Ej: Ing. Juan Pérez">
                            </div>
                            <label class="error" style="display: none;" for="txt_nombre_referencia"></label>
                        </div>
                    </div>

                    <div id="pnl_referencia_empresa" class="row mb-3" style="display: none;">
                        <div class="col-md-12">
                            <label for="txt_referencia_nombre_empresa" class="form-label fw-semibold fs-7">Empresa / Institución </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-buildings'></i></span>
                                <input type="text" class="form-control no_caracteres" name="txt_referencia_nombre_empresa" id="txt_referencia_nombre_empresa" maxlength="100" placeholder="Nombre de la organización">
                            </div>
                            <label class="error" style="display: none;" for="txt_referencia_nombre_empresa"></label>
                        </div>
                    </div>

                    <div class="row mb-3 g-3">
                        <div class="col-md-6">
                            <label for="txt_telefono_referencia" class="form-label fw-semibold fs-7">Teléfono de Contacto </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-phone'></i></span>
                                <input type="text" class="form-control solo_numeros_int" name="txt_telefono_referencia" id="txt_telefono_referencia" maxlength="15" placeholder="Ej: 0987654321">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="txt_referencia_correo" class="form-label fw-semibold fs-7">Correo Electrónico </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-envelope'></i></span>
                                <input type="email" class="form-control" name="txt_referencia_correo" id="txt_referencia_correo" maxlength="100" placeholder="ejemplo@correo.com">
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border border-dashed">
                        <label for="txt_copia_carta_recomendacion" class="form-label fw-semibold">Carta de Recomendación (PDF) </label>
                        <input type="file" class="form-control form-control-sm" name="txt_copia_carta_recomendacion" id="txt_copia_carta_recomendacion" accept=".pdf">
                        <input type="hidden" name="txt_ruta_guardada_carta_recomendacion" id="txt_ruta_guardada_carta_recomendacion">
                        <div class="form-text text-xs"><i class='bx bx-info-circle'></i> Adjunta el documento escaneado firmado. Máximo 5MB.</div>
                    </div>

                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display: none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_referencia_laboral" onclick="delete_datos_referencias_laborales();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_parametros_referencias_laborales()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_referencia_laboral" onclick="insertar_editar_referencias_laborales();">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
-->
<div class="modal fade" id="modal_ver_pdf_referencias_laborales" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10 py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white p-2 rounded-circle me-2 text-primary shadow-sm">
                        <i class='bx bx-briefcase bx-sm'></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="lbl_titulo_referencia_laboral">Referencia Laboral</h5>
                        <small class="text-muted">Vista previa del certificado de experiencia</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe();" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body p-0 bg-light">
                <div class="w-100 position-relative" style="height: 80vh;">

                    <div class="position-absolute top-50 start-50 translate-middle text-muted" style="z-index: 0;">
                        <i class='bx bx-loader-alt bx-spin bx-md'></i> Cargando documento...
                    </div>

                    <iframe src=''
                        id="iframe_referencias_laborales_pdf"
                        class="w-100 h-100 border-0 position-relative"
                        style="z-index: 1;"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>

            <div class="modal-footer py-1 bg-white">
                <small class="text-muted me-auto fst-italic">
                    <i class='bx bx-info-circle'></i> Si el documento no carga, consultar con el administrador.
                </small>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe();">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_nombre_referencia');
        agregar_asterisco_campo_obligatorio('txt_telefono_referencia');
        agregar_asterisco_campo_obligatorio('txt_referencia_correo');
        agregar_asterisco_campo_obligatorio('txt_referencia_nombre_empresa');

        //Validación Referencias Laborales
        $("#form_referencias_laborales").validate({
            rules: {
                txt_nombre_referencia: {
                    required: true,
                },
                txt_telefono_referencia: {
                    required: true,
                },
                txt_referencia_correo: {
                    required: true,
                },
                txt_referencia_nombre_empresa: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_referencia: {
                    required: "Por favor ingrese el nombre de su referencia laboral",
                },
                txt_telefono_referencia: {
                    required: "Por favor ingrese el teléfono de su referencia laboral",
                },
                txt_referencia_correo: {
                    required: "Por favor ingrese el correo de su referencia laboral",
                },
                txt_referencia_nombre_empresa: {
                    required: "Por favor ingrese el nombre de la empresa de su referencia laboral",
                },
            },

            highlight: function(element) {
                // Agrega la clase 'is-invalid' al input que falla la validación
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                // Elimina la clase 'is-invalid' si la validación pasa
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');

            }
        });
    });
</script>