<script>
    $(document).ready(function() {
        cargar_datos_contratos_trabajos('<?= $id_postulante ?>');
    });

    //Contratos de Trabajo

    function cargar_datos_contratos_trabajos(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_contratos_trabajoC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_contratos_trabajos').html(response);
            }
        });
    }

    function cargar_datos_modal_contratos_trabajos(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_contratos_trabajoC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_contratos_trabajos_id').val(response[0]._id);
                $('#txt_nombre_empresa_contrato').val(response[0].th_ctr_nombre_empresa);
                $('#txt_tipo_contrato').val(response[0].th_ctr_tipo_contrato);
                $('#txt_ruta_guardada_contratos_trabajos').val(response[0].th_ctr_contratos_trabajos);
                $('#txt_fecha_inicio_contrato').val(response[0].th_ctr_fecha_inicio_contrato);
                $cbx_fecha_fin_experiencia.val(response[0].th_ctr_cbx_fecha_fin_experiencia);
            }
        });
    }

    function insertar_editar_contratos_trabajos() {
        var form_data = new FormData(document.getElementById("form_contratos_trabajos")); // Captura todos los campos y archivos
        var in_cbx_fecha_fin_experiencia = $('#cbx_fecha_fin_experiencia').is(':checked') ? 1 : 0;
        form_data.append('cbx_fecha_fin_experiencia', in_cbx_fecha_fin_experiencia);

        var txt_id_contratos_trabajos = $('#txt_contratos_trabajos_id').val();

        if ($('#txt_ruta_archivo_contrato').val() === '' && txt_id_contratos_trabajos != '') {
            var txt_ruta_archivo_contrato = $('#txt_ruta_guardada_contratos_trabajos').val()
            $('#txt_ruta_archivo_contrato').rules("remove", "required");
        } else {
            var txt_ruta_archivo_contrato = $('#txt_ruta_archivo_contrato').val();
            $('#txt_ruta_archivo_contrato').rules("add", {
                required: true
            });
        }

        if ($("#form_contratos_trabajos").valid()) {

            $.ajax({
                url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_contratos_trabajoC.php?insertar=true',
                type: 'post',
                data: form_data,
                contentType: false,
                processData: false,

                dataType: 'json',
                success: function(response) {
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
                        cargar_datos_contratos_trabajos('<?= $id_postulante ?>');
                        limpiar_parametros_contratos_trabajos();
                        $('#modal_agregar_contratos').modal('hide');
                    }
                }
            });
        }
    }

    //Funcion para editar el registro de contratos y capacitaciones
    function abrir_modal_contratos_trabajos(id) {
        cargar_datos_modal_contratos_trabajos(id);
        $('#modal_agregar_contratos').modal('show');
        $('#lbl_titulo_contratos_trabajos').html('Editar Contrato Trabajo');
        $('#btn_guardar_contratos_trabajos').html('<i class="bx bx-save"></i>Editar');
        $('#btn_eliminar_contratos_trabajos').show();
    }

    function delete_datos_contratos_trabajos() {
        var id = $('#txt_contratos_trabajos_id').val();
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
                eliminar_contratos_trabajos(id);
            }
        })
    }

    function eliminar_contratos_trabajos(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_contratos_trabajoC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    cargar_datos_contratos_trabajos('<?= $id_postulante ?>');
                    limpiar_parametros_contratos_trabajos();
                    $('#modal_agregar_contratos').modal('hide');
                }
            }
        });
    }

    function limpiar_parametros_contratos_trabajos() {
        //contratos capacitaciones
        $('#txt_nombre_empresa_contrato').val('');
        $('#txt_ruta_archivo_contrato').val('');
        $('#txt_tipo_contrato').val('');
        $('#txt_contratos_trabajos_id').val('');
        $('#txt_ruta_guardada_contratos_trabajos').val('');
        $('#txt_fecha_inicio_contrato').val('')
        $('#txt_fecha_fin_contrato').val('')
        $('#txt_fecha_fin_contrato').prop('disabled', false);
        $('#cbx_fecha_fin_experiencia').prop('checked', false);
        //Limpiar validaciones
        $("#form_contratos_trabajos").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');
        //Cambiar texto
        $('#lbl_titulo_contratos_trabajos').html('Agregar Contrato Trabajo');
        $('#btn_guardar_contratos_trabajos').html('<i class="bx bx-save"></i>Agregar');
        $('#btn_eliminar_contratos_trabajos').hide();
    }

    function validar_fechas_contratos_trabajos() {
        var fecha_inicio = $('#txt_fecha_inicio_contrato').val();
        var fecha_final = $('#txt_fecha_fin_contrato').val();
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
                $('#txt_fecha_fin_contrato').val('');
                $('#cbx_fecha_fin_experiencia').prop('checked', false);
                $('#txt_fecha_fin_contrato').prop('disabled', false);
            }
            if (Date.parse(fecha_inicio) > Date.parse(fecha_final)) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "La fecha de inicio no puede ser mayor a la fecha final.",
                });
                $('.form-control').removeClass('is-valid is-invalid');
                $('#txt_fecha_inicio_contrato').val('');
                $('#cbx_fecha_fin_experiencia').prop('checked', false);
                $('#txt_fecha_fin_contrato').prop('disabled', false);
            }
        }
    }

    function definir_ruta_iframe_contratos(url) {
        var cambiar_ruta = $('#iframe_contratos_trabajos_pdf').attr('src', url);
    }

    function limpiar_parametros_iframe() {
        $('#iframe_contratos_trabajos_pdf').attr('src', '');
    }
</script>

<div id="pnl_contratos_trabajos">
</div>

<!-- Modal para agregar contratos de trabajo-->
<div class="modal" id="modal_agregar_contratos" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_contratos_trabajos">Agregar Contrato Trabajo</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_contratos_trabajos()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_contratos_trabajos" enctype="multipart/form-data" method="post" style="width: inherit;">

                <div class="modal-body">
                    <input type="hidden" name="txt_contratos_trabajos_id" id="txt_contratos_trabajos_id">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">


                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_nombre_empresa_contrato" class="form-label form-label-sm">Nombre Empresa </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_nombre_empresa_contrato" id="txt_nombre_empresa_contrato" maxlength="100">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_tipo_contrato" class="form-label form-label-sm">Tipo Contrato </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_tipo_contrato" id="txt_tipo_contrato" maxlength="100">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_inicio_contrato" class="form-label form-label-sm">Fecha Inicio Contrato </label>
                            <input type="date" class="form-control form-control-sm no_caracteres" name="txt_fecha_inicio_contrato" id="txt_fecha_inicio_contrato" onblur="checkbox_actualidad_contratos_trabajos();" onkeydown="saltar_input(event, 'txt_fecha_fin_contrato')">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_fin_contrato" class="form-label form-label-sm">Fecha Finalización Contrato </label>
                            <input type="date" class="form-control form-control-sm no_caracteres" name="txt_fecha_fin_contrato" id="txt_fecha_fin_contrato" onblur="checkbox_actualidad_contratos_trabajos();" onkeydown="saltar_input(event, 'cbx_fecha_fin_experiencia')">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col md-12">
                            <input type="checkbox" class="form-check-input" name="cbx_fecha_fin_experiencia" id="cbx_fecha_fin_experiencia" onchange="checkbox_actualidad_contratos_trabajos();">
                            <label for="cbx_fecha_fin_experiencia" class="form-label form-label-sm">Actualidad</label>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_ruta_archivo_contrato" class="form-label form-label-sm">Pdf Contrato Firmado </label>
                            <input type="file" class="form-control form-control-sm" name="txt_ruta_archivo_contrato" id="txt_ruta_archivo_contrato" accept=".pdf" value="">
                        </div>
                    </div>


                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_contratos_trabajos" onclick="validar_fechas_contratos_trabajos();insertar_editar_contratos_trabajos();"><i class="bx bx-save"></i>Agregar</button>
                    <button type="button" style="display: none;" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_contratos_trabajos" onclick="delete_datos_contratos_trabajos();"><i class="bx bx-trash"></i>Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal_ver_pdf_contratos" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_contratos_trabajos">Visualizacion Documento</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe();"></button>
            </div>
            <!-- Modal body -->
            <form id="form_contratos_trabajos">
                <div class="modal-body d-flex justify-content-center">
                    <iframe src='' id="iframe_contratos_trabajos_pdf" frameborder="0" width="900px" height="700px"></iframe>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_nombre_empresa_contrato');
        agregar_asterisco_campo_obligatorio('txt_tipo_contrato');
        agregar_asterisco_campo_obligatorio('txt_fecha_inicio_contrato');
        agregar_asterisco_campo_obligatorio('txt_fecha_fin_contrato');
        agregar_asterisco_campo_obligatorio('txt_ruta_archivo_contrato');

        //Validación Contratos de Trabajo
        $("#form_contratos_trabajos").validate({
            rules: {
                txt_nombre_empresa_contrato: {
                    required: true,
                },
                txt_ruta_archivo_contrato: {
                    required: true,
                },
                txt_tipo_contrato: {
                    required: true,
                },
                txt_fecha_inicio_contrato: {
                    required: true,
                },
                txt_fecha_fin_contrato: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_empresa_contrato: {
                    required: "Por favor ingrese el nombre de la empresa",
                },
                txt_ruta_archivo_contrato: {
                    required: "Por favor suba la copia de su contrato",
                },
                txt_tipo_contrato: {
                    required: "Por favor ingresa el tipo de contrato",
                },
                txt_fecha_inicio_contrato: {
                    required: "Por favor ingrese la fecha en la que iniciaron sus funciones",
                },
                txt_fecha_fin_contrato: {
                    required: "Por favor ingrese la fecha de finalización o seleccione 'Actualidad' si sigue trabajando.",
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

    function checkbox_actualidad_contratos_trabajos() {
        if ($('#cbx_fecha_fin_experiencia').is(':checked')) {
            var hoy = new Date();
            var dia = String(hoy.getDate()).padStart(2, '0');
            var mes = String(hoy.getMonth() + 1).padStart(2, '0');
            var year = hoy.getFullYear();
            var fecha_actual = year + '-' + mes + '-' + dia;

            $('#txt_fecha_fin_contrato').val(fecha_actual); // Coloca la fecha de hoy en la fecha final
            $('#txt_fecha_fin_contrato').prop('readonly', true); // Hace solo lectura el campo
            $('#txt_fecha_fin_contrato').rules("remove", "required"); // Elimina la validación de "required"

            // Agrega clase válida
            $('#txt_fecha_fin_contrato').addClass('is-valid');
            $('#txt_fecha_fin_contrato').removeClass('is-invalid');
        } else {
            if ($('#txt_fecha_fin_contrato').prop('readonly')) {
                $('#txt_fecha_fin_contrato').val('');
            }

            $('#txt_fecha_fin_contrato').prop('readonly', false); // Habilita para edición
            $('#txt_fecha_fin_contrato').rules("add", {
                required: true
            });
            $('#txt_fecha_fin_contrato').removeClass('is-valid');
            $('#form_contratos_trabajos').validate().resetForm();
            $('.form-control').removeClass('is-valid is-invalid');
        }

        // Validar fechas
        validar_fechas_contratos_trabajos();
    }
</script>