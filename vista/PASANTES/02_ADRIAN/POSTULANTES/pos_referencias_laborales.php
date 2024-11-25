<script>
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_referencias_laborales(<?= $id ?>);
        <?php } ?>

    });

    //Formación Académica
    function cargar_datos_referencias_laborales(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_referencias_laboralesC.php?listar=true',
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
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_referencias_laboralesC.php?listar_modal=true',
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

            }
        });
    }

    function insertar_editar_referencias_laborales() {
        var form_data = new FormData(document.getElementById("form_referencias_laborales")); // Captura todos los campos y archivos

        var txt_id_referencias_laborales = $('#txt_referencias_laborales_id').val();

        if ($('#txt_copia_carta_recomendacion').val() === '' && txt_id_referencias_laborales != '') {
            var txt_copia_carta_recomendacion = $('#txt_ruta_guardada_carta_recomendacion').val()
            $('#txt_copia_carta_recomendacion').rules("remove", "required");
        } else {
            var txt_copia_carta_recomendacion = $('#txt_copia_carta_recomendacion').val();
            $('#txt_copia_carta_recomendacion').rules("add", {
                required: true
            });
        }

        //console.log([...form_data]);
        // console.log([...form_data.keys()]);
        // console.log([...form_data.values()]);
        //return;

        if ($("#form_referencias_laborales").valid()) {
            $.ajax({
                url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_referencias_laboralesC.php?insertar=true',
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
                            cargar_datos_referencias_laborales(<?= $id ?>);
                        <?php } ?>
                        limpiar_parametros_referencias_laborales();
                        $('#modal_agregar_referencia_laboral').modal('hide');
                    }
                }
            });
        }
    }

    //Funcion para editar el registro de referencias laborales
    function abrir_modal_referencias_laborales(id) {
        cargar_datos_modal_referencias_laborales(id);

        $('#modal_agregar_referencia_laboral').modal('show');

        $('#lbl_titulo_referencia_laboral').html('Editar su referencia');
        $('#btn_guardar_referencia_laboral').html('Guardar');

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
            confirmButtonText: 'Si'
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
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_referencias_laboralesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_referencias_laborales(<?= $id ?>);
                    <?php } ?>
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

        //Limpiar validaciones
        $("#form_referencias_laborales").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');

        //Cambiar texto
        $('#lbl_titulo_referencia_laboral').html('Agregue una referencia');
        $('#btn_guardar_referencia_laboral').html('Agregar');
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


<!-- Modal para agregar referencias laborales-->
<div class="modal" id="modal_agregar_referencia_laboral" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary" id="lbl_titulo_referencia_laboral">Agregue una referencia</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_referencias_laborales()"></button>
            </div>

            <!-- Modal body -->
            <form id="form_referencias_laborales" enctype="multipart/form-data" method="post" style="width: inherit;">

                <div class="modal-body">

                    <input type="hidden" name="txt_referencias_laborales_id" id="txt_referencias_laborales_id">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_nombre_referencia" class="form-label form-label-sm">Nombre del empleador</label>
                            <input type="text" class="form-control form-control-sm" name="txt_nombre_referencia" id="txt_nombre_referencia" placeholder="Escriba el nombre de el empleador" maxlength="50">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_telefono_referencia" class="form-label form-label-sm">Teléfono del empleador </label>
                            <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_telefono_referencia" id="txt_telefono_referencia" placeholder="Escriba el número de contacto de el empleador" maxlength="15">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_referencia_correo" class="form-label form-label-sm">Correo del empleador</label>
                            <input type="email" class="form-control form-control-sm" name="txt_referencia_correo" id="txt_referencia_correo" placeholder="Escriba el correo del empleador" maxlength="100">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_referencia_nombre_empresa" class="form-label form-label-sm">Empresa/Institución </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_referencia_nombre_empresa" id="txt_referencia_nombre_empresa"  maxlength="100">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_copia_carta_recomendacion" class="form-label form-label-sm">Copia de la carta de recomendación </label>
                            <input type="file" class="form-control form-control-sm" name="txt_copia_carta_recomendacion" id="txt_copia_carta_recomendacion" accept=".pdf">
                            <!-- <div class="pt-2"></div> -->
                            <input type="text" class="form-control form-control-sm" name="txt_ruta_guardada_carta_recomendacion" id="txt_ruta_guardada_carta_recomendacion" hidden>
                        </div>
                    </div>

                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_referencia_laboral" onclick="insertar_editar_referencias_laborales();">Agregar</button>
                    <button type="button" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_formacion" onclick="delete_datos_referencias_laborales();">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal_ver_pdf_referencias_laborales" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary" id="lbl_titulo_referencia_laboral">Referencias laborales</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe();"></button>
            </div>
            <!-- Modal body -->
            <form id="form_referencias_laborales">
                <div class="modal-body d-flex justify-content-center">
                    <iframe src='' id="iframe_referencias_laborales_pdf" frameborder="0" width="900px" height="700px"></iframe>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        agregar_asterisco_campo_obligatorio('txt_nombre_referencia');
        agregar_asterisco_campo_obligatorio('txt_telefono_referencia');
        agregar_asterisco_campo_obligatorio('txt_copia_carta_recomendacion');
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
                txt_copia_carta_recomendacion: {
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
                txt_copia_carta_recomendacion: {
                    required: "Por favor suba la carta de recomendación",
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