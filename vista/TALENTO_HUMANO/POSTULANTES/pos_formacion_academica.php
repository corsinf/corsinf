<script>
    $(document).ready(function() {
        cargar_datos_formacion_academica(<?= $id_postulante ?>);
        cargar_selects2();

        function cargar_selects2() {
            url_nivelAcademicoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_pos_nivel_academicoC.php?buscar=true';
            cargar_select2_url('ddl_nivel_academico', url_nivelAcademicoC, '', '#modal_agregar_formacion');

            url_PaisC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_paisC.php?buscar=true';
            cargar_select2_url('ddl_pais', url_PaisC, '', '#modal_agregar_formacion');

            url_area_estudioC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_area_estudioC.php?buscar=true';
            cargar_select2_url('ddl_area_estudio', url_area_estudioC, '', '#modal_agregar_formacion');
        }
    });



    //Formación Académica
    function cargar_datos_formacion_academica(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_formacion_academicaC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_formacion_academica').html(response);
            }
        });
    }

    function cargar_datos_modal_formacion_academica(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_formacion_academicaC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_titulo_obtenido').val(response[0].th_fora_titulo_obtenido);
                $('#txt_institucion').val(response[0].th_fora_institución);
                $('#txt_fecha_inicio_academico').val(response[0].th_fora_fecha_inicio_formacion);
                $('#txt_th_fora_registro_senescyt').val(response[0].th_fora_registro_senescyt);
                $('#ddl_nivel_academico').append($('<option>', {
                    value: response[0].id_nivel_academico,
                    text: response[0].nivel_academico_descripcion,
                    selected: true
                }));
                $('#ddl_pais').append($('<option>', {
                    value: response[0].id_pais,
                    text: response[0].pais_nombre,
                    selected: true
                }));

                var fecha_fin = response[0].th_fora_fecha_fin_formacion;
                if (fecha_fin === '') {
                    var hoy = new Date();
                    var dia = String(hoy.getDate()).padStart(2, '0');
                    var mes = String(hoy.getMonth() + 1).padStart(2, '0');
                    var year = hoy.getFullYear();
                    var fecha_actual = year + '-' + mes + '-' + dia;
                    $('#txt_fecha_final_academico').val(fecha_actual);
                    $('#txt_fecha_final_academico').prop('disabled', true);
                    $('#cbx_fecha_final_academico').prop('checked', true);
                } else {
                    $('#txt_fecha_final_academico').val(fecha_fin);
                }
                $('#txt_formacion_id').val(response[0]._id);
            }
        });
    }

    function insertar_editar_formacion_academica() {
        var txt_titulo_obtenido = $('#txt_titulo_obtenido').val();
        var txt_institucion = $('#txt_institucion').val();
        var txt_fecha_inicio_academico = $('#txt_fecha_inicio_academico').val();
        if ($('#cbx_fecha_final_academico').is(':checked')) {
            var txt_fecha_final_academico = '';
        } else {
            var txt_fecha_final_academico = $('#txt_fecha_final_academico').val();
        }
        var txt_id_postulante = $('#txt_postulante_id').val();
        var txt_id_formacion_academica = $('#txt_formacion_id').val();

        var txt_th_fora_registro_senescyt = $('#txt_th_fora_registro_senescyt').val();
        var ddl_nivel_academico = $('#ddl_nivel_academico').val();
        var ddl_pais = $('#ddl_pais').val();
        var ddl_area_estudio = $('#ddl_area_estudio').val();

        var parametros_formacion_academica = {
            '_id': txt_id_formacion_academica,
            'txt_id_postulante': txt_id_postulante,
            'txt_titulo_obtenido': txt_titulo_obtenido,
            'txt_institucion': txt_institucion,
            'txt_fecha_inicio_academico': txt_fecha_inicio_academico,
            'txt_fecha_final_academico': txt_fecha_final_academico,
            'txt_fora_registro_senescyt': txt_th_fora_registro_senescyt,
            'ddl_nivel_academico': ddl_nivel_academico,
            'ddl_pais': ddl_pais,
            'ddl_area_estudio': ddl_area_estudio,
        }

        if ($("#form_formacion_academica").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            insertar_formacion_academica(parametros_formacion_academica);
        }
    }

    function insertar_formacion_academica(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_formacion_academicaC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                    cargar_datos_formacion_academica('<?= $id_postulante ?>');
                    limpiar_campos_formacion_academica_modal();
                    $('#modal_agregar_formacion').modal('hide');
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    //Funcion para editar el registro de formacion academica
    function abrir_modal_formacion_academica(id) {
        cargar_datos_modal_formacion_academica(id);
        $('#modal_agregar_formacion').modal('show');
        $('#lbl_titulo_formacion_acedemica').html('Editar Formación Académica');
        $('#btn_guardar_formacion').html('<i class="bx bx-save"></i>Editar');
        $('#btn_eliminar_formacion_academica').show();
    }

    function delete_datos_form_acad() {
        //Para revisar y enviar el dato como parametro 
        id = $('#txt_formacion_id').val();
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
                eliminar_form_acad(id);
            }
        })
    }

    function eliminar_form_acad(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_formacion_academicaC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    cargar_datos_formacion_academica('<?= $id_postulante ?>');
                    limpiar_campos_formacion_academica_modal();
                    $('#modal_agregar_formacion').modal('hide');
                }
            }
        });
    }

    function limpiar_campos_formacion_academica_modal() {
        $('#txt_titulo_obtenido').val('');
        $('#txt_institucion').val('');
        $('#txt_fecha_inicio_academico').val('');
        $('#txt_fecha_final_academico').val('');
        $('#txt_formacion_id').val('');
        $('#cbx_fecha_final_academico').prop('checked', false)
        $('#txt_fecha_final_academico').prop('disabled', false);
        //Limpiar validaciones
        $("#form_formacion_academica").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');
        //Cambiar texto
        $('#lbl_titulo_formacion_acedemica').html('Agregar Formación Académica');
        $('#btn_guardar_formacion').html('<i class="bx bx-save"></i>Agregar');
        $('#btn_eliminar_formacion_academica').hide();

        $('#ddl_nivel_academico').val(null).trigger('change');
        $('#ddl_pais').val(null).trigger('change');
        $('#ddl_area_estudio').val(null).trigger('change');

        $('#txt_th_fora_registro_senescyt').val('');

        $('.select2-selection').removeClass('is-valid is-invalid');
        $('.select2-validation').each(function() {
            $('label.error[for="' + this.id + '"]').hide();
        });
    }

    function validar_fechas_form_acad() {
        var fecha_inicio = $('#txt_fecha_inicio_academico').val();
        var fecha_final = $('#txt_fecha_final_academico').val();
        var hoy = new Date();
        var fecha_actual = hoy.toISOString().split('T')[0];
        //* Validar que la fecha final no sea menor a la fecha de inicio
        if (fecha_inicio && fecha_final) {
            if (Date.parse(fecha_final) < Date.parse(fecha_inicio)) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La fecha final no puede ser menor a la fecha de inicio.",
                });
                $('.form-control').removeClass('is-valid is-invalid');
                $('#txt_fecha_final_academico').val('');
                $('#txt_fecha_inicio_academico').val('');
                $('#cbx_fecha_final_academico').prop('checked', false);
                $('#txt_fecha_final_academico').prop('disabled', false);
            }
            if (Date.parse(fecha_inicio) > Date.parse(fecha_actual)) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La fecha de inicio no puede ser mayor a la fecha final.",
                });
                $('.form-control').removeClass('is-valid is-invalid');
                $('#txt_fecha_inicio_academico').val('');
                $('#cbx_fecha_final_academico').prop('checked', false);
                $('#txt_fecha_final_academico').prop('disabled', false);
            }
        }

        //* Validar que la fecha de inicio y final no sean mayores a la fecha actual
        if (fecha_inicio && Date.parse(fecha_inicio) > Date.parse(fecha_actual)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "La fecha de inicio no puede ser mayor a la fecha actual.",
            });
            $('.form-control').removeClass('is-valid is-invalid');
            $('#txt_fecha_inicio_academico').val('');
        }

        if (fecha_final && Date.parse(fecha_final) > Date.parse(fecha_actual)) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "La fecha final no puede ser mayor a la fecha actual.",
            });
            $('.form-control').removeClass('is-valid is-invalid');
            $('#txt_fecha_final_academico').val('');
            $('#cbx_fecha_final_academico').prop('checked', false);
            $('#txt_fecha_final_academico').prop('disabled', false);
        }
    }
</script>

<div id="pnl_formacion_academica">
</div>

<!-- Modal para agregar formación académica-->
<div class="modal fade" id="modal_agregar_formacion" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_formacion_acedemica">
                        <i class='bx bx-book-reader me-2'></i>Formación Académica
                    </h5>
                    <small class="text-muted">Registra tus estudios secundarios, universitarios y de posgrado.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_formacion_academica_modal();"></button>
            </div>

            <form id="form_formacion_academica" class="needs-validation">
                <input type="hidden" id="txt_formacion_id">

                <div class="modal-body">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="txt_titulo_obtenido" class="form-label fw-semibold fs-7">Título Obtenido </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-certification'></i></span>
                                <input type="text" class="form-control no_caracteres" name="txt_titulo_obtenido" id="txt_titulo_obtenido" maxlength="100" oninput="texto_mayusculas(this);" placeholder="Ej: Ingeniero en Sistemas, Bachiller en Ciencias...">
                            </div>
                            <label class="error" style="display: none;" for="txt_titulo_obtenido"></label>

                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="txt_institucion" class="form-label fw-semibold fs-7">Institución Educativa </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-buildings'></i></span>
                                <input type="text" class="form-control no_caracteres" name="txt_institucion" id="txt_institucion" maxlength="100" oninput="texto_mayusculas(this);" placeholder="Ej: Universidad Central del Ecuador">
                            </div>
                            <label class="error" style="display: none;" for="txt_institucion"></label>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="ddl_nivel_academico" class="form-label fw-semibold fs-7">Nivel Académico </label>
                            <select class="form-select select2-validation" name="ddl_nivel_academico" id="ddl_nivel_academico" style="width: 100%;">
                                <option value="">-- Seleccione --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_nivel_academico"></label>
                        </div>

                        <div class="col-md-6">
                            <label for="ddl_area_estudio" class="form-label fw-semibold fs-7">Área de Estudio </label>
                            <select class="form-select select2-validation" name="ddl_area_estudio" id="ddl_area_estudio" style="width: 100%;">
                                <option value="">-- Seleccione --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_area_estudio"></label>
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label for="txt_th_fora_registro_senescyt" class="form-label fw-semibold fs-7">Registro SENESCYT </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-id-card'></i></span>
                                <input type="text" class="form-control" name="txt_th_fora_registro_senescyt" id="txt_th_fora_registro_senescyt" placeholder="Número de registro">
                            </div>
                            <label class="error" style="display: none;" for="txt_th_fora_registro_senescyt"></label>

                        </div>

                        <div class="col-md-6">
                            <label for="ddl_pais" class="form-label fw-semibold fs-7">Pais </label>
                            <select class="form-select select2-validation" name="ddl_pais" id="ddl_pais" style="width: 100%;">
                                <option value="">-- Seleccione --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_pais"></label>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border border-dashed mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="text-muted fs-7 mb-0 fw-bold text-uppercase">Periodo de Estudios </h6>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" name="cbx_fecha_final_academico" id="cbx_fecha_final_academico" onchange="checkbox_actualidad_form_acad();">
                                <label for="cbx_fecha_final_academico" class="form-check-label fs-7 fw-semibold text-primary">Cursando actualmente</label>
                            </div>
                            <label class="error" style="display: none;" for="cbx_fecha_final_academico"></label>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="txt_fecha_inicio_academico" class="form-label fs-7 mb-1">Fecha Inicio </label>
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_inicio_academico" id="txt_fecha_inicio_academico" onblur="checkbox_actualidad_form_acad();">
                            </div>
                            <div class="col-md-6">
                                <label for="txt_fecha_final_academico" class="form-label fs-7 mb-1">Fecha Finalización </label>
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_final_academico" id="txt_fecha_final_academico" onblur="checkbox_actualidad_form_acad();">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" style="display: none;" class="btn btn-outline-danger btn-sm" id="btn_eliminar_formacion_academica" onclick="delete_datos_form_acad();">
                        <i class="bx bx-trash"></i> Eliminar
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_formacion_academica_modal();">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_formacion" onclick="validar_fechas_form_acad();insertar_editar_formacion_academica();">
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
        agregar_asterisco_campo_obligatorio('txt_titulo_obtenido');
        agregar_asterisco_campo_obligatorio('txt_institucion');
        agregar_asterisco_campo_obligatorio('txt_fecha_inicio_academico');
        agregar_asterisco_campo_obligatorio('txt_fecha_final_academico');
        agregar_asterisco_campo_obligatorio('ddl_nivel_academico');
        agregar_asterisco_campo_obligatorio('ddl_pais');
        agregar_asterisco_campo_obligatorio('txt_th_fora_registro_senescyt');
        agregar_asterisco_campo_obligatorio('ddl_area_estudio');

        //Para validar los select2
        $(".select2-validation").on("select2:select", function(e) {
            unhighlight_select(this);
        });

        //Validación Formación Académica
        $("#form_formacion_academica").validate({
            rules: {
                txt_titulo_obtenido: {
                    required: true,
                },
                txt_institucion: {
                    required: true,
                },
                txt_fecha_inicio_academico: {
                    required: true,
                },
                txt_fecha_final_academico: {
                    required: true,
                },
                ddl_nivel_academico: {
                    required: true,
                },
                ddl_pais: {
                    required: true,
                },
                ddl_area_estudio: {
                    required: true,
                },
                txt_th_fora_registro_senescyt: {
                    required: true,
                },
            },
            messages: {
                txt_titulo_obtenido: {
                    required: "Por favor ingrese el título obtenido",
                },
                txt_institucion: {
                    required: "Por favor ingrese la institución en la que se graduó",
                },
                txt_fecha_inicio_academico: {
                    required: "Por favor ingrese la fecha en la que inició sus estudios",
                },
                txt_fecha_final_academico: {
                    required: "Por favor ingrese la fecha en la que finalizó o finalizará sus estudios",
                },
                ddl_pais: {
                    required: "Por favor seleccione el pais",
                },
            },

            highlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al contenedor correcto de select2
                    $element.next(".select2-container").find(".select2-selection").removeClass(
                        "is-valid").addClass("is-invalid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, aplicar la clase al grupo de radios (al contenedor padre si existe)
                    $('input[name="' + $element.attr("name") + '"]').addClass("is-invalid").removeClass(
                        "is-valid");
                } else {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al input normal
                    $element.removeClass("is-valid").addClass("is-invalid");
                }
            },

            unhighlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Para Select2, elimina 'is-invalid' y agrega 'is-valid' en el contenedor adecuado
                    $element.next(".select2-container").find(".select2-selection").removeClass(
                        "is-invalid").addClass("is-valid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, marcar todo el grupo como válido
                    $('input[name="' + $element.attr("name") + '"]').removeClass("is-invalid").addClass(
                        "is-valid");
                } else {
                    // Para otros elementos normales
                    $element.removeClass("is-invalid").addClass("is-valid");
                }
            }
        });
    });

    function checkbox_actualidad_form_acad() {
        if ($('#cbx_fecha_final_academico').is(':checked')) {
            var hoy = new Date();
            var dia = String(hoy.getDate()).padStart(2, '0');
            var mes = String(hoy.getMonth() + 1).padStart(2, '0');
            var year = hoy.getFullYear();

            var fecha_actual = year + '-' + mes + '-' + dia;
            $('#txt_fecha_final_academico').val(fecha_actual);

            $('#txt_fecha_final_academico').prop('disabled', true);
            $('#txt_fecha_final_academico').rules("remove", "required");

            // Agregar clase 'is-valid' para poner el campo en verde
            $('#txt_fecha_final_academico').addClass('is-valid');
            $('#txt_fecha_final_academico').removeClass('is-invalid');

        } else {
            // Solo limpiar el campo si estaba previamente deshabilitado
            if ($('#txt_fecha_final_academico').prop('disabled')) {
                $('#txt_fecha_final_academico').val('');
            }

            $('#txt_fecha_final_academico').prop('disabled', false)
            $('#txt_fecha_final_academico').rules("add", {
                required: true
            });
            $('#txt_fecha_final_academico').removeClass('is-valid');
            $('#form_formacion_academica').validate().resetForm();
            $('.form-control').removeClass('is-valid is-invalid');
        }

        // Validar fechas
        validar_fechas_form_acad();
    }
</script>