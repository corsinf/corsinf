<script>
    $(document).ready(function() {
        cargar_datos_idiomas('<?= $id_postulante ?>');
        cargar_selected_idiomas();
    });

    function cargar_selected_idiomas() {
        url_CertificacionC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_certficacionC.php?buscar=true';
        url_IdiomaC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_idiomasC.php?buscar=true';
        url_IdiomaNivelC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_idiomas_nivelC.php?buscar=true';

        cargar_select2_url('ddl_certificacion_idioma', url_CertificacionC, '', '#modal_agregar_idioma');
        cargar_select2_url('ddl_idiomas', url_IdiomaC, '', '#modal_agregar_idioma');
        cargar_select2_url('ddl_idiomas_nivel', url_IdiomaNivelC, '', '#modal_agregar_idioma');
    }

    //Idiomas
    function cargar_datos_idiomas(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_idiomasC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_idioma').html(response);
            }
        });
    }

    function cargar_datos_modal_idiomas(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_idiomasC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                var datos = response[0];

                $('#ddl_idiomas').empty().append($('<option>', {
                    value: datos.id_idiomas,
                    text: datos.nombre_idioma,
                    selected: true
                })).trigger('change');

                $('#ddl_idiomas_nivel').empty().append($('<option>', {
                    value: datos.id_idiomas_nivel,
                    text: datos.nivel_idioma_descripcion,
                    selected: true
                })).trigger('change');

                $('#ddl_certificacion_idioma').empty().append($('<option>', {
                    value: datos.id_certificacion,
                    text: datos.nombre_certificacion,
                    selected: true
                })).trigger('change');

                $('#txt_institucion_1').val(datos.th_idi_institucion);
                $('#txt_fecha_inicio_idioma').val(datos.th_idi_fecha_inicio_idioma);
                $('#txt_idiomas_id').val(datos._id);

                if (datos.th_idi_actualidad == 1 || datos.th_idi_fecha_fin_idioma == '1900-01-01') {
                    $('#cbx_fecha_final_idioma').prop('checked', true);

                    // Asignar fecha actual
                    var hoy = new Date();
                    var dia = String(hoy.getDate()).padStart(2, '0');
                    var mes = String(hoy.getMonth() + 1).padStart(2, '0');
                    var year = hoy.getFullYear();
                    var fecha_actual = year + '-' + mes + '-' + dia;
                    $('#txt_fecha_fin_idioma').val(fecha_actual);

                    $('#txt_fecha_fin_idioma').prop('disabled', true);
                    $('#txt_fecha_fin_idioma').rules("remove", "required");
                    $('#txt_fecha_fin_idioma').removeClass('is-invalid').addClass('is-valid');
                } else {
                    $('#cbx_fecha_final_idioma').prop('checked', false);
                    $('#txt_fecha_fin_idioma').val(datos.th_idi_fecha_fin_idioma);
                    $('#txt_fecha_fin_idioma').prop('disabled', false);
                }
            }
        });
    }

    function insertar_editar_idiomas() {
        var ddl_idiomas = $('#ddl_idiomas').val();
        var ddl_idiomas_nivel = $('#ddl_idiomas_nivel').val();
        var ddl_certificacion_idioma = $('#ddl_certificacion_idioma').val();
        var txt_institucion_1 = $('#txt_institucion_1').val();
        var txt_fecha_inicio_idioma = $('#txt_fecha_inicio_idioma').val();
        var id_postulante = $('#txt_postulante_id').val();
        var txt_idi_idiomas_id = $('#txt_idiomas_id').val();
        var cbx_actualidad = $('#cbx_fecha_final_idioma').is(':checked') ? '1' : '0';

        var txt_fecha_fin_idioma = '';
        if (cbx_actualidad === '1') {
            txt_fecha_fin_idioma = '1900-01-01';
        } else {
            txt_fecha_fin_idioma = $('#txt_fecha_fin_idioma').val();
        }

        var parametros_idiomas = {
            'id_postulante': id_postulante,
            'ddl_idiomas': ddl_idiomas,
            'ddl_idiomas_nivel': ddl_idiomas_nivel,
            'ddl_certificacion_idioma': ddl_certificacion_idioma,
            'txt_institucion_1': txt_institucion_1,
            'txt_fecha_inicio_idioma': txt_fecha_inicio_idioma,
            'txt_fecha_fin_idioma': txt_fecha_fin_idioma,
            'cbx_fecha_final_idioma': cbx_actualidad,
            '_id': txt_idi_idiomas_id
        }

        if (validar_fechas_idioma() && $("#form_agregar_idioma").valid()) {
            insertar_idiomas(parametros_idiomas);
        }
    }

    function insertar_idiomas(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_idiomasC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                    cargar_datos_idiomas('<?= $id_postulante ?>');
                    limpiar_campos_idiomas_modal();
                    $('#modal_agregar_idioma').modal('hide');
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function abrir_modal_idiomas(id) {
        cargar_datos_modal_idiomas(id);
        $('#modal_agregar_idioma').modal('show');
        $('#lbl_nombre_idioma').html('Editar Idioma');
        $('#btn_guardar_idioma').html('<i class="bx bx-save"></i>Editar');
        $('#btn_eliminar_idiomas').show();
    }

    function borrar_datos_idioma() {
        id = $('#txt_idiomas_id').val();
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
                eliminar_idioma(id);
            }
        })
    }

    function eliminar_idioma(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_idiomasC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    cargar_datos_idiomas('<?= $id_postulante ?>');
                    limpiar_campos_idiomas_modal();
                    $('#modal_agregar_idioma').modal('hide');
                }
            }
        });
    }

    function limpiar_campos_idiomas_modal() {
        $('#form_agregar_idioma').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#cbx_fecha_final_idioma').prop('checked', false);
        $('#ddl_idiomas').val(null).trigger('change');
        $('#ddl_idiomas_nivel').val(null).trigger('change');
        $('#ddl_certificacion_idioma').val(null).trigger('change');
        $('#txt_institucion_1').val('');
        $('#txt_fecha_inicio_idioma').val('');
        $('#txt_fecha_fin_idioma').val('');
        $('#txt_fecha_fin_idioma').prop('disabled', false); // IMPORTANTE: habilitar el campo
        $('#txt_idiomas_id').val('');

        //Cambiar texto
        $('#lbl_nombre_idioma').html('Agregar Idioma');
        $('#btn_guardar_idioma').html('<i class="bx bx-save"></i>Agregar');
        $('#btn_eliminar_idiomas').hide();
    }

    function validar_fechas_idioma() {
        var fecha_inicio = $('#txt_fecha_inicio_idioma').val();
        var fecha_final = $('#txt_fecha_fin_idioma').val();
        var hoy = new Date().toISOString().split('T')[0];
        var es_actualidad = $('#cbx_fecha_final_idioma').is(':checked');

        if (fecha_inicio && Date.parse(fecha_inicio) > Date.parse(hoy)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "La fecha de inicio no puede ser mayor a la fecha actual."
            });
            $('.form-control').removeClass('is-valid is-invalid');
            $('#txt_fecha_inicio_idioma').val('');
            return false; 
        }

        if (!es_actualidad && fecha_final) {
            if (Date.parse(fecha_final) > Date.parse(hoy)) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La fecha final no puede ser mayor a la fecha actual."
                });
                $('.form-control').removeClass('is-valid is-invalid');
                $('#txt_fecha_fin_idioma').val('');
                return false; 
            }

            if (fecha_inicio && Date.parse(fecha_final) < Date.parse(fecha_inicio)) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La fecha final no puede ser menor a la fecha de inicio."
                });
                $('.form-control').removeClass('is-valid is-invalid');
                $('#txt_fecha_fin_idioma').val('');
                return false; 
            }
        }

        return true; 
    }

    function checkbox_actualidad_form_idioma() {
        var campo_fecha_fin = $('#txt_fecha_fin_idioma');
        var switch_actualidad = $('#cbx_fecha_final_idioma');

        if (switch_actualidad.is(':checked')) {
            var hoy = new Date();
            var dia = String(hoy.getDate()).padStart(2, '0');
            var mes = String(hoy.getMonth() + 1).padStart(2, '0');
            var year = hoy.getFullYear();
            var fecha_actual = year + '-' + mes + '-' + dia;

            campo_fecha_fin.val(fecha_actual);

            campo_fecha_fin.prop('disabled', true);
            campo_fecha_fin.rules("remove", "required");

            var validator = $("#form_agregar_idioma").validate();
            validator.successList.push(campo_fecha_fin[0]);
            validator.showErrors();

            campo_fecha_fin.addClass('is-valid').removeClass('is-invalid');
            $('label.error[for="txt_fecha_fin_idioma"]').hide();

        } else {
            if (campo_fecha_fin.prop('disabled')) {
                campo_fecha_fin.val('');
            }

            campo_fecha_fin.prop('disabled', false);
            campo_fecha_fin.rules("add", {
                required: true,
                messages: {
                    required: "Por favor escriba la fecha de fin de los estudios"
                }
            });

            campo_fecha_fin.removeClass('is-valid is-invalid');
        }

        validar_fechas_idioma();
    }
</script>

<div id="pnl_idioma">
</div>

<!-- Modal para agregar idiomas-->
<div class="modal fade" id="modal_agregar_idioma" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_nombre_idioma">
                        <i class='bx bx-world me-2'></i>Idiomas y Lenguas
                    </h5>
                    <small class="text-muted">Registra tu dominio de lenguas extranjeras o nativas.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_idiomas_modal()"></button>
            </div>

            <form id="form_agregar_idioma" class="needs-validation">
                <input type="hidden" id="txt_idiomas_id">

                <div class="modal-body">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="ddl_idiomas" class="form-label fw-semibold fs-7">Idiomas </label>
                            <select class="form-select select2-validation" id="ddl_idiomas" name="ddl_idiomas" required>
                                <option value="">-- Seleccione Idioma --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_idiomas"></label>
                        </div>
                        <div class="col-md-6">
                            <label for="ddl_idiomas_nivel" class="form-label fw-semibold fs-7">Nivel </label>
                            <select class="form-select select2-validation" id="ddl_idiomas_nivel" name="ddl_idiomas_nivel" required>
                                <option value="">-- Seleccione el Nivel --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_idiomas_nivel"></label>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="txt_institucion_1" class="form-label fw-semibold fs-7">Institución </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-buildings'></i></span>
                                <input type="text" class="form-control no_caracteres" name="txt_institucion_1" id="txt_institucion_1" maxlength="100" oninput="texto_mayusculas(this);" placeholder="Ej: Alianza Francesa, British Council...">
                            </div>
                            <label class="error" style="display: none;" for="txt_institucion_1"></label>
                        </div>
                        <div class="col-md-6">
                            <label for="ddl_certificacion_idioma" class="form-label fw-semibold fs-7">Cerficación </label>
                            <select class="form-select select2-validation" id="ddl_certificacion_idioma" name="ddl_certificacion_idioma" required>
                                <option value="">-- Seleccione certificado --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_certificacion_idioma"></label>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border border-dashed">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="text-muted fs-7 mb-0 fw-bold text-uppercase">Periodo de Estudios </h6>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" name="cbx_fecha_final_idioma" id="cbx_fecha_final_idioma" onchange="checkbox_actualidad_form_idioma();">
                                <label for="cbx_fecha_final_idioma" class="form-check-label fs-7 fw-semibold text-primary">Cursando actualmente</label>
                            </div>
                            <label class="error" style="display: none;" for="cbx_fecha_final_idioma"></label>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="txt_fecha_inicio_idioma" class="form-label fs-7 mb-1">Fecha Inicio </label>
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_inicio_idioma" id="txt_fecha_inicio_idioma">
                            </div>
                            <div class="col-md-6">
                                <label for="txt_fecha_fin_idioma" class="form-label fs-7 mb-1">Fecha Finalización </label>
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_fin_idioma" id="txt_fecha_fin_idioma">
                            </div>
                        </div>
                        <div class="form-text text-xs mt-2"><i class='bx bx-calendar-check'></i> Indica el tiempo que tomó tu formación.</div>
                    </div>

                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display: none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_idiomas" onclick="borrar_datos_idioma();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_idiomas_modal()">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_idioma" onclick="insertar_editar_idiomas(); validar_fechas_idioma();">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        agregar_asterisco_campo_obligatorio('ddl_idiomas');
        agregar_asterisco_campo_obligatorio('ddl_idiomas_nivel');
        agregar_asterisco_campo_obligatorio('ddl_certificacion_idioma');
        agregar_asterisco_campo_obligatorio('txt_institucion_1');
        agregar_asterisco_campo_obligatorio('txt_fecha_inicio_idioma');
        agregar_asterisco_campo_obligatorio('txt_fecha_fin_idioma');
        //Validación Idiomas
        $("#form_agregar_idioma").validate({
            rules: {
                ddl_idiomas: {
                    required: true,
                },
                ddl_idiomas_nivel: {
                    required: true,
                },
                ddl_certificacion_idioma: {
                    required: true,
                },
                txt_institucion_1: {
                    required: true,
                },
                txt_fecha_inicio_idioma: {
                    required: true,
                },
                txt_fecha_fin_idioma: {
                    required: true,
                },
            },
            messages: {
                ddl_idiomas: {
                    required: "Por favor seleccione un idioma",
                },
                ddl_idiomas_nivel: {
                    required: "Por favor seleccione su nivel",
                },
                ddl_certificacion_idioma: {
                    required: "Por favor seleccione un cerficado",
                },
                txt_institucion_1: {
                    required: "Por favor escriba la institución donde recibió su certificado",
                },
                txt_fecha_inicio_idioma: {
                    required: "Por favor escriba la fecha de inicio de estudios",
                },
                txt_fecha_fin_idioma: {
                    required: "Por favor escriba la fecha de fin de los estudios",
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
    })
</script>