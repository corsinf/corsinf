<script>
      $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_certificaciones_capacitaciones(<?= $id ?>);
        <?php } ?>

    });
    //Certificaciones y Capacitaciones
    function cargar_datos_certificaciones_capacitaciones(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_certificaciones_capacitacionesC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_certificaciones_capacitaciones').html(response);
            }
        });
    }
    function cargar_datos_modal_certificaciones_capacitaciones(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_certificaciones_capacitacionesC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_certificaciones_capacitaciones_id').val(response[0]._id);

                $('#txt_nombre_curso').val(response[0].th_refl_nombre_referencia);
                $('#txt_enlace_certificado').val(response[0].th_refl_carta_recomendacion);
                // $('#txt_referencia_correo').val(response[0].th_refl_correo);
                // $('#txt_referencia_nombre_empresa').val(response[0].th_refl_nombre_empresa);

            }
        });
    }


    function insertar_editar_certificaciones_capacitaciones() {
        var txt_nombre_certificacion = $('#txt_nombre_certificacion').val();
        var txt_enlace_certificado = $('#txt_enlace_certificado').val();
        var txt_pdf_certificado = $('#txt_pdf_certificado').val();

        var parametros_certificaciones_capacitaciones = {
            'txt_nombre_certificacion': txt_nombre_certificacion,
            'txt_enlace_certificado': txt_enlace_certificado,
            'txt_pdf_certificado': txt_pdf_certificado,
        }

        if ($("#form_certificaciones_capacitaciones").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            // console.log(parametros_certificaciones_capacitaciones)
            $.ajax({
                url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_certificaciones_capacitacionesC.php?insertar=true',
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
                            cargar_datos_certificaciones_capacitaciones(<?= $id ?>);
                        <?php } ?>
                        limpiar_parametros_certificaciones_capacitaciones();
                        $('#modal_agregar_certificaciones').modal('hide');
                    }
                }
            });
        }
    }

    //Funcion para editar el registro de referencias laborales
    function abrir_modal_certificaciones_capacitaciones(id) {
        cargar_datos_modal_certificaciones_capacitaciones(id);

        $('#modal_agregar_certificaciones').modal('show');

        $('#lbl_titulo_certificaciones_capacitaciones').html('Editar su referencia');
        $('#btn_guardar_certificaciones_capacitaciones').html('Guardar');

    }

    function delete_datos_certificaciones_capacitaciones() {
        var id = $('#txt_certificaciones_capacitaciones_id').val();
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
                eliminar_certificaciones_capacitaciones(id);
            }
        })
    }

    function eliminar_certificaciones_capacitaciones(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_certificaciones_capacitacionesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_certificaciones_capacitaciones(<?= $id ?>);
                    <?php } ?>
                    limpiar_parametros_certificaciones_capacitaciones();
                    $('#modal_agregar_certificaciones').modal('hide');
                }
            }
        });
    }
    function limpiar_parametros_certificaciones_capacitaciones() {
        //certificaciones capacitaciones
        $('#txt_nombre_curso').val('');
        $('#txt_pdf_certificado').val('');
        $('#txt_certificaciones_capacitaciones_id').val('');
        $('#txt_enlace_certificado').val('');

        //Limpiar validaciones
        $("#form_certificaciones_capacitaciones").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');

        //Cambiar texto
        $('#lbl_titulo_certificaciones_capacitaciones').html('Agregue un certificado');
        $('#btn_guardar_certificaciones_capacitaciones').html('Agregar');
    }

    function definir_ruta_iframe(url) {
        var cambiar_ruta = $('#iframe_certificaciones_capacitaciones_pdf').attr('src', url);
    }

    function limpiar_parametros_iframe() {
        $('#iframe_certificaciones_capacitaciones_pdf').attr('src', '');
    }
</script>

<!-- <div id="pnl_certificaciones_capacitaciones"> -->

<div class="row mb-col">
    <div class="col-10">
        <h6 class="fw-bold">CS50: Introduction to Computer Science</h6>
        <a href="#" class="fw-bold">Ver Certificado</a>
    </div>
    <div class="col-2 d-flex justify-content-end align-items-start">
        <button class="btn">
            <i class='text-dark bx bx-pencil bx-sm'></i>
        </button>
    </div>
</div>

<!-- Modal para agregar certificaciones y capacitaciones-->
<div class="modal" id="modal_agregar_certificaciones" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue una Certificación o Capacitación</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick=""></button>
            </div>
            <!-- Modal body -->
            <form id="form_certificaciones_capacitaciones">
                <div class="modal-body">
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_nombre_certificacion" class="form-label form-label-sm">Nombre del curso o capacitación <label style="color: red;">*</label></label>
                            <input type="text" class="form-control form-control-sm " name="txt_nombre_certificacion" id="txt_nombre_certificacion" value="" placeholder="Escriba el nombre del curso o capacitación">
                        </div>
                    </div>
                    
                    <!-- <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_enlace_certificado" class="form-label form-label-sm">1. Enlace del Certificado obtenido <label style="color: red;">*</label></label>
                            <input type="text" class="form-control form-control-sm " name="txt_enlace_certificado" id="txt_enlace_certificado" value="" placeholder="Escriba el enlace a su certificado">
                        </div>
                    </div> -->
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_pdf_certificado" class="form-label form-label-sm">2. PDF del Certificado obtenido <label style="color: red;">*</label></label>
                            <input type="file" class="form-control form-control-sm" name="txt_pdf_certificado" id="txt_pdf_certificado" accept=".pdf" value="" placeholder="">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_certificaciones" onclick="insertar_editar_certificaciones_capacitaciones();">Guardar Certificación o Capacitación</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        //Validación Certificaciones y Capacitaciones
        $("#form_certificaciones_capacitaciones").validate({
            rules: {
                txt_nombre_certificacion: {
                    required: true,
                },
                txt_enlace_certificado: {
                    required: true,
                },
                txt_pdf_certificado: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_certificacion: {
                    required: "Por favor ingrese el nombre del certificado",
                },
                txt_enlace_certificado: {
                    required: "Por favor ingrese el enlace de su certificado",
                },
                txt_pdf_certificado: {
                    required: "Por favor ingrese el PDF de su certificado",
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