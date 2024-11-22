<script>
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_documentos_identidad(<?= $id ?>);
        <?php } ?>

    });

    //Documentos de Identidad
    function cargar_datos_documentos_identidad(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_documentosC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_documentos_identidad').html(response);
            }
        });
    }

    function obtener_documentos_repetidos() {
        documentos_identidad = $('input[name="documentos_identidad[]"]').map(function() {
            return $(this).val();
        }).get();

        console.log(documentos_identidad);

        $('#ddl_tipo_documento_identidad option').each(function() {
            if (documentos_identidad.includes($(this).val())) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    }

    function cargar_datos_modal_documentos_identidad(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_documentosC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_documentos_identificacion_id').val(response[0]._id);

                $('#ddl_tipo_documento_identidad').val(response[0].th_poi_tipo);
                $('#txt_ruta_guardada_documentos_identidad').val(response[0].th_pos_documentos);
            }
        });
    }

    function insertar_editar_documentos_identidad() {
        var form_data = new FormData(document.getElementById("form_documento_identidad"));

        var txt_id_documentos_identidad = $('#txt_documentos_identificacion_id').val();

        if ($('#txt_ruta_documentos_identidad').val() === '' && txt_id_documentos_identidad != '') {
            var txt_ruta_documentos_identidad = $('#txt_ruta_guardada_documentos_identidad').val()
            $('#txt_ruta_documentos_identidad').rules("remove", "required");
        } else {
            var txt_ruta_documentos_identidad = $('#txt_ruta_documentos_identidad').val();
            $('#txt_ruta_documentos_identidad').rules("add", {
                required: true
            });
        }
        if ($("#form_documento_identidad").valid()) {
            $.ajax({
                url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_documentosC.php?insertar=true',
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
                        <?php if (isset($_GET['id'])) { ?>
                            cargar_datos_documentos_identidad(<?= $id ?>);
                        <?php } ?>
                        limpiar_parametros_documentos_identidad();
                        $('#modal_agregar_documentos_identidad').modal('hide');
                    }
                }
            });
        }
    }

    //Funcion para editar el registro de documentos identidad
    function abrir_modal_documentos_identidad(id) {
        cargar_datos_modal_documentos_identidad(id);

        $('#modal_agregar_documentos_identidad').modal('show');

        $('#lbl_titulo_documentos_identidad').html('Editar su documento de identidad');
        $('#btn_guardar_documentos_identidad').html('Guardar');

    }


    function delete_datos_documentos_identidad() {
        var id = $('#txt_documentos_identificacion_id').val();
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
                eliminar_documentos_identidad(id);
            }
        })
    }

    function eliminar_documentos_identidad(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_documentosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_documentos_identidad(<?= $id ?>);
                    <?php } ?>
                    limpiar_parametros_documentos_identidad();
                    $('#modal_agregar_documentos_identidad').modal('hide');
                }
            }
        });
    }

    function limpiar_parametros_documentos_identidad() {
        //certificaciones capacitaciones
        $('#ddl_tipo_documento_identidad').val('');
        $('#txt_cargar_documento_identidad').val('');
        $('#txt_ruta_documentos_identidad').val('');

        $('#txt_documentos_identificacion_id').val('');
        $('#txt_ruta_guardada_documentos_identidad').val('');

        //Limpiar validaciones
        $("#form_documento_identidad").validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');


        //Cambiar texto
        $('#lbl_titulo_documentos_identidad').html('Agregue un documento de identidad');
        $('#btn_guardar_documentos_identidad').html('Agregar');
    }

    function ruta_iframe_documento_identificacion(url) {
        $('#modal_ver_pdf_documentos_identidad').modal('show');
        var cambiar_ruta = $('#iframe_documentos_identidad_pdf').attr('src', url);
    }

    function limpiar_parametros_iframe() {
        $('#iframe_documentos_identidad_pdf').attr('src', '');
    }
</script>


<div id="pnl_documentos_identidad">

</div>

<!-- Modal para agregar documento de identidad-->
<div class="modal" id="modal_agregar_documentos_identidad" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h6><label class="text-body-secondary fw-bold" id="lbl_titulo_documentos_identidad">Agregue un Documento de Identidad</small></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_documentos_identidad()"></button>
            </div>

            <!-- Modal Body -->
            <form id="form_documento_identidad">
                <input type="hidden">
                <div class="modal-body">

                    <input type="hidden" name="txt_documentos_identificacion_id" id="txt_documentos_identificacion_id">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="ddl_tipo_documento_identidad" class="form-label form-label-sm">Tipo de Documento <label style="color: red;">*</label></label>
                            <select class="form-select form-select-sm" id="ddl_tipo_documento_identidad" name="ddl_tipo_documento_identidad" onclick="obtener_documentos_repetidos();">
                                <option selected disabled value="">-- Selecciona una opción --</option>
                                <option value="Cédula de Identidad">Cédula de Identidad</option>
                                <option value="Pasaporte">Pasaporte</option>
                                <option value="Tarjeta de identificación">Tarjeta de identificación</option>
                                <option value="Licencia">Licencia</option>
                                <option value="Carnét o Certificado para miembro de la Fuerza Pública Ecuatoriana">Carnét o Certificado para miembro de la Fuerza Pública Ecuatoriana</option>
                                <option value="Carnét de discapacidad">Carnét de discapacidad</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_ruta_documentos_identidad" class="form-label form-label-sm">Copia de la carta de recomendación <label style="color: red;">*</label></label>
                            <input type="file" class="form-control form-control-sm" name="txt_ruta_documentos_identidad" id="txt_ruta_documentos_identidad" accept=".pdf">
                            <!-- <div class="pt-2"></div> -->
                            <input type="hidden" class="form-control form-control-sm" name="txt_ruta_guardada_documentos_identidad" id="txt_ruta_guardada_documentos_identidad">
                        </div>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_documentos_identidad" onclick="insertar_editar_documentos_identidad();">Agregar</button>
                    <button type="button" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_formacion" onclick="delete_datos_documentos_identidad();">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal_ver_pdf_documentos_identidad" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary" id="lbl_titulo_documentos_identidad">Documento Identidad</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe();"></button>
            </div>
            <!-- Modal body -->
            <form id="form_documento_identidad">
                <div class="modal-body d-flex justify-content-center">
                    <iframe src='' id="iframe_documentos_identidad_pdf" frameborder="0" width="900px" height="700px"></iframe>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        //Validación Documento de Identidad
        $("#form_documento_identidad").validate({
            rules: {
                ddl_tipo_documento_identidad: {
                    required: true,
                },
                txt_ruta_documentos_identidad: {
                    required: true,
                },
            },
            messages: {
                ddl_tipo_documento_identidad: {
                    required: "Por favor eliga el documento de identidad que va a subir",
                },
                txt_ruta_documentos_identidad: {
                    required: "Por favor suba su documento de identidad",
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