<script>
    $(document).ready(function() {
        cargar_datos_formacion_academica(<?= $id_postulante ?>);
        cargar_selects2();

        function cargar_selects2() {
            url_nivelAcademicoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_pos_nivel_academicoC.php?buscar=true';
            cargar_select2_url('ddl_nivel_academico', url_nivelAcademicoC, '', '#modal_agregar_formacion');
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

        var parametros_formacion_academica = {
            '_id': txt_id_formacion_academica,
            'txt_id_postulante': txt_id_postulante,
            'txt_titulo_obtenido': txt_titulo_obtenido,
            'txt_institucion': txt_institucion,
            'txt_fecha_inicio_academico': txt_fecha_inicio_academico,
            'txt_fecha_final_academico': txt_fecha_final_academico,
            'txt_fora_registro_senescyt': txt_th_fora_registro_senescyt,
            'ddl_nivel_academico': ddl_nivel_academico,
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
<div class="modal" id="modal_agregar_formacion" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_formacion_acedemica">Agregar Formación Académica</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_formacion_academica_modal();"></button>
            </div>
            <!-- Modal body -->
            <form id="form_formacion_academica">
                <input type="text" id="txt_formacion_id" hidden>
                <div class="modal-body">
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_titulo_obtenido" class="form-label form-label-sm">Título Obtenido </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_titulo_obtenido" id="txt_titulo_obtenido" maxlength="100">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-6">
                            <label for="ddl_nivel_academico" class="form-label form-label-sm">
                                Nivel Académico
                            </label>
                            <select class="form-select form-select-sm mb-2 select2-validation"
                                name="ddl_nivel_academico"
                                id="ddl_nivel_academico">
                                <option value="">-- Seleccione --</option>
                            </select>
                            <label class="error" style="display: none;" for="ddl_nivel_academico"></label>
                        </div>
                        <div class="col-md-6">
                            <label for="txt_th_fora_registro_senescyt" class="form-label form-label-sm">
                                Registro SENESCYT
                            </label>
                            <input type="text"
                                class="form-control form-control-sm mb-2"
                                name="txt_th_fora_registro_senescyt"
                                id="txt_th_fora_registro_senescyt"
                                placeholder="Ej: 1234567890">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_institucion" class="form-label form-label-sm">Institución </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_institucion" id="txt_institucion" maxlength="100">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_inicio_academico" class="form-label form-label-sm">Fecha Inicio Estudios </label>
                            <input type="date" class="form-control form-control-sm no_caracteres" name="txt_fecha_inicio_academico" id="txt_fecha_inicio_academico" onblur="checkbox_actualidad_form_acad();" onkeydown="saltar_input(event, 'txt_fecha_final_academico')">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_final_academico" class="form-label form-label-sm">Fecha Finalización Estudios </label>
                            <input type="date" class="form-control form-control-sm mb-2 no_caracteres" name="txt_fecha_final_academico" id="txt_fecha_final_academico" onblur="checkbox_actualidad_form_acad();" onkeydown="saltar_input(event, 'cbx_fecha_final_academico')">

                            <input type="checkbox" class="form-check-input" name="cbx_fecha_final_academico" id="cbx_fecha_final_academico" onchange="checkbox_actualidad_form_acad();">
                            <label for="cbx_fecha_final_academico" class="form-label form-label-sm">Actualidad</label>
                        </div>
                    </div>



                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_formacion" onclick="validar_fechas_form_acad();insertar_editar_formacion_academica();"><i class="bx bx-save"></i>Agregar</button>
                    <button type="button" style="display: none;" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_formacion_academica" onclick="delete_datos_form_acad();"><i class="bx bx-trash"></i>Eliminar</button>
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
        agregar_asterisco_campo_obligatorio('txt_th_fora_registro_senescyt');

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