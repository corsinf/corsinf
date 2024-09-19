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
                console.log(response);
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

                $('#txt_nombre_referencia').val(response[0].th_refl_nombre_referencia);
                $('#txt_telefono_referencia').val(response[0].th_refl_telefono_referencia);

                // if (response[0].th_refl_carta_recomendacion) {
                //     $('#archivo_carta_recomendacion').html('<a href="#" target="_blank" data-bs-toggle="modal" data-bs-target="#modal_ver_pdf" onclick="definir_ruta_iframe(' + id + ')">Ver carta de recomendación</a>');
                //     $('#txt_copia_carta_recomendacion').rules("remove", "required");
                // } else {
                //     $('#archivo_carta_recomendacion').html('No se ha subido una carta de recomendación.');
                //     $('#txt_copia_carta_recomendacion').rules("add", {
                //         required: true
                //     });
                // }
                $('#txt_copia_carta_recomendacion').val(response[0].th_refl_carta_recomendacion)
                console.log(response[0].th_refl_carta_recomendacion)



                $('#txt_referencias_laborales_id').val(response[0]._id);

                console.log(response);
            }
        });
    }

    function insertar_editar_referencias_laborales() {
        var txt_nombre_referencia = $('#txt_nombre_referencia').val();
        var txt_telefono_referencia = $('#txt_telefono_referencia').val();
        var txt_copia_carta_recomendacion = $('#txt_copia_carta_recomendacion').val();
        var txt_id_postulante = '<?= $id ?>';
        var txt_id_referencias_laborales = $('#txt_referencias_laborales_id').val();

        var parametros_referencias = {
            '_id': txt_id_referencias_laborales,
            'txt_id_postulante': txt_id_postulante,
            'txt_nombre_referencia': txt_nombre_referencia,
            'txt_telefono_referencia': txt_telefono_referencia,
            'txt_copia_carta_recomendacion': txt_copia_carta_recomendacion,
        }

        if ($("#form_referencias_laborales").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_referencias)
            insertar_referencias_laborales(parametros_referencias)

        }
    }

    function insertar_referencias_laborales(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_referencias_laboralesC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_referencias_laborales(<?= $id ?>);
                    <?php } ?>
                    limpiar_parametros_referencias_laborales();
                    $('#modal_agregar_referencia_laboral').modal('hide');
                }
            }
        });
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
        $('#archivo_carta_recomendacion').html('')

        //Limpiar validaciones
        $("#form_referencias_laborales").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');

        //Cambiar texto
        $('#lbl_titulo_referencia_laboral').html('Agregue una referencia');
        $('#btn_guardar_referencia_laboral').html('Agregar');
    }

    function definir_ruta_iframe(id) {
        var cambiar_ruta = $('#iframe_pdf').attr('src', '../controlador/PASANTES/01_SEBASTIAN/formularios_firmasC_Adrian.php?persona_juridica=true&id=' + id);
    }

    function limpiar_parametros_iframe() {
        $('#iframe_pdf').attr('src', '');
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
            <form id="form_referencias_laborales">
                <div class="modal-body">
                    <input type="text" id="txt_referencias_laborales_id" hidden>
                    <div class="mb-3">
                        <label for="txt_nombre_referencia" class="form-label form-label-sm">Nombre del empleador <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_nombre_referencia" id="txt_nombre_referencia" placeholder="Escriba el nombre de el empleador">
                    </div>
                    <div class="mb-3">
                        <label for="txt_telefono_referencia" class="form-label form-label-sm">Teléfono del empleador <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_telefono_referencia" id="txt_telefono_referencia" placeholder="Escriba el número de contacto de el empleador">
                    </div>
                    <div class="mb-3">
                        <label for="txt_copia_carta_recomendacion" class="form-label form-label-sm">Copia de la carta de recomendación <label style="color: red;">*</label></label>
                        <input type="file" class="form-control form-control-sm" name="txt_copia_carta_recomendacion" id="txt_copia_carta_recomendacion" accept=".pdf">
                        <div class="mt-2" id="archivo_carta_recomendacion"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row mx-auto">
                        <div class="col-6">
                            <button type="button" class="btn btn-success btn-sm" id="btn_guardar_referencia_laboral" onclick="insertar_editar_referencias_laborales();">Agregar</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-danger btn-sm" id="btn_eliminar_formacion" onclick="delete_datos_referencias_laborales();">Eliminar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal" id="modal_ver_pdf" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary" id="lbl_titulo_referencia_laboral">Agregue una referencia</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_iframe();"></button>
            </div>
            <!-- Modal body -->
            <form id="form_referencias_laborales">
                <div class="modal-body d-flex justify-content-center">
                    <iframe src='' id="iframe_pdf" frameborder="0" width="600px" height="400px"></iframe>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
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