<script>
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_cerficados_medicos(<?= $id ?>);
        <?php } ?>

    });

 //Certificados Médicos
 function cargar_datos_cerficados_medicos(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificados_medicosC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_certificados_medicos').html(response);
            }
        });
    }

    
    function cargar_datos_modal_certificados_medicos(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificados_medicosC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_certificados_medicos_id').val(response[0]._id);

                $('#txt_med_motivo_certificado').val(response[0].th_med_motivo_certificado);
                $('#txt_med_nom_medico').val(response[0].th_med_nom_medico);
                $('#txt_med_ins_medico').val(response[0].th_med_ins_medico);
                $('#txt_med_fecha_inicio_certificado').val(response[0].th_med_fecha_inicio_certificado);
                $('#txt_med_fecha_fin_certificado').val(response[0].th_med_fecha_fin_certificado);
                $('#txt_ruta_guardada_certificados_medicos').val(response[0].th_med_ruta_certficado);

                
            }
        });
    }

    function insertar_editar_certificados_medicos() {
        var form_data = new FormData(document.getElementById("form_certificados_medicos"));

        var txt_ruta_certificados_medicos = $('#txt_certificados_medicos_id').val();

        if ($('#txt_ruta_certificados_medicos').val() === '' && txt_ruta_certificados_medicos != '') {
            var txt_ruta_certificados_medicos = $('#txt_ruta_guardada_certificados_medicos').val()
            $('#txt_ruta_certificados_medicos').rules("remove", "required");
        } else {
            var txt_ruta_certificados_medicos = $('#txt_ruta_certificados_medicos').val();
            $('#txt_ruta_certificados_medicos').rules("add", {
                required: true
            });
        }
        if ($("#form_certificados_medicos").valid()) {
            $.ajax({
                url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificados_medicosC.php?insertar=true',
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
                            cargar_datos_cerficados_medicos(<?= $id ?>);
                        <?php } ?>
                        limpiar_parametros_certificados_medicos();
                        $('#modal_agregar_certificados_medicos').modal('hide');
                    }
                }
            });
        }
    }

    //Funcion para editar el registro de certificados médicos
    function abrir_modal_certificados_medicos(id) {
        cargar_datos_modal_certificados_medicos(id);

        $('#modal_agregar_certificados_medicos').modal('show');

        $('#lbl_titulo_certificados_medicos').html('Editar el certificado médico');
        $('#btn_guardar_certificados_medicos').html('Guardar');

    }


    function delete_datos_certificados_medicos() {
        var id = $('#txt_certificados_medicos_id').val();
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
                eliminar_certificados_medicos(id);
            }
        })
    }

    function eliminar_certificados_medicos(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_certificados_medicosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_documentos_identidad(<?= $id ?>);
                    <?php } ?>
                    limpiar_parametros_certificados_medicos();
                    $('#modal_agregar_certificados_medicos').modal('hide');
                }
            }
        });
    }

    function limpiar_parametros_certificados_medicos() {
        //certificaciones capacitaciones
        $('#txt_med_motivo_certificado').val('');
        $('#txt_med_nom_medico').val('');
        $('#txt_med_ins_medico').val('');
        $('#txt_med_fecha_inicio_certificado').val('');
        $('#txt_med_fecha_fin_certificado').val('');
        $('#txt_ruta_certificados_medicos').val('');
        $('#txt_ruta_certificados_medicos').val('');

        $('#txt_certificados_medicos_id').val('');
        $('#txt_ruta_guardada_certificados_medicos').val('');

        //Limpiar validaciones
        $("#form_certificados_medicos").validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');


        //Cambiar texto
        $('#lbl_titulo_certificados_medicos').html('Agregue un certificado médico');
        $('#btn_guardar_certificados_medicos').html('Agregar');
    }

    function ruta_iframe_certificados_medicos(url) {
        $('#modal_ver_pdf_certificados_medicos').modal('show');
        var cambiar_ruta = $('#iframe_certificados_medicos_pdf').attr('src', url);
    }

    function limpiar_parametros_iframe() {
        $('#iframe_certificados_medicos_pdf').attr('src', '');
    }
</script>


<div id="pnl_certificados_medicos">

</div>
<!-- Modal para agregar certificados médicos-->
<div class="modal" id="modal_agregar_certificado_medico" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un Certificado Médico</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros_certificados_medicos()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_certificados_medicos">
                <div class="modal-body">
                <div class="modal-body">

                    <input type="hidden" name="txt_certificados_medicos_id" id="txt_documentos_identificacion_id">
                    <input type="hidden" name="txt_postulante_cedula" id="txt_postulante_cedula">
                    <input type="hidden" name="txt_postulante_id" id="txt_postulante_id">



                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_med_motivo_certificado" class="form-label form-label-sm">Motivo del certificado </label>
                            <input type="text" class="form-control form-control-sm" name="txt_med_motivo_certificado" id="txt_med_motivo_certificado" placeholder="Escriba el motivo del certificado médico">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_med_nom_medico" class="form-label form-label-sm">Nombre del Médico Tratante </label>
                            <input type="text" class="form-control form-control-sm" name="txt_med_nom_medico" id="txt_med_nom_medico" placeholder="Escriba el motivo del certificado médico">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_med_ins_medico" class="form-label form-label-sm">Nombre de la Institución Médica  </label>
                            <input type="text" class="form-control form-control-sm" name="txt_med_ins_medico" id="txt_med_ins_medico" placeholder="Escriba el motivo del certificado médico">
                        </div>
                    </div>

                    
                    <!-- <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_med_fecha_inicio_certificado" class="form-label form-label-sm">Fecha de Inicio del Certificado</label>
                            <input type="date" class="form-control form-control-sm no_caracteres" name="txt_med_fecha_inicio_certificado" id="txt_med_fecha_inicio_certificado" onchange="txt_fecha_fin_certificado_1();">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_med_fecha_fin_certificado" class="form-label form-label-sm">Fecha de fin del Certificado</label>
                            <input type="date" class="form-control form-control-sm no_caracteres" name="txt_med_fecha_fin_certificado" id="txt_med_fecha_fin_certificado" onchange="txt_fecha_fin_certificado_1();">
                        </div> -->
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_respaldo_medico" class="form-label form-label-sm">Documentación que respalde la aptitud para el trabajo </label>
                            <input type="file" class="form-control form-control-sm" name="txt_respaldo_medico" id="txt_respaldo_medico" accept=".pdf">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_certificados_medicos" onclick="insertar_editar_certificados_medicos()">Agregar Certificado Médico</button>
                    <button type="button" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_formacion" onclick="delete_datos_certificados_medicos();">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        agregar_asterisco_campo_obligatorio('txt_med_motivo_certificado');
        agregar_asterisco_campo_obligatorio('txt_med_nom_medico');
        agregar_asterisco_campo_obligatorio('txt_med_ins_medico');
        agregar_asterisco_campo_obligatorio('txt_med_fecha_inicio_certificado');
        agregar_asterisco_campo_obligatorio('txt_med_fecha_fin_certificado');
        //Validación Idiomas
        $("#form_certificados_medicos").validate({
            rules: {
                txt_med_motiivo_certiificado: {
                    required: true,
                    maxlength: "200"
                },
                txt_med_nom_medico: {
                    required: true,
                    maxlength: "200"
                },
                txt_med_ins_medico: {
                    required: true,
                    maxlength: "200"
                },
                txt_med_fecha_inicio_certificado: {
                    required: true,
                },
                txt_med_fecha_fin_certificado: {
                    required: true,
                },

            },

            messages: {
                txt_med_motiivo_certiificado: {
                    required: "Por favor, escriba el motivo del certificado médico",
                    maxlength: "El motivo del certificado médico no puede tener más de 200 caracteres"
                },
                txt_med_nom_medico: {
                    required: "Por favor, escriba el nombre del médico tratante",
                    maxlength: "El nombre del médico tratante no puede tener más de 200 caracteres"
                },
                txt_med_ins_medico: {
                    required: "Por favor, escriba el nombre de la institución médica",
                    maxlength: "El nombre de la institución médica no puede tener más de 200 caracteres"
                },
                txt_med_fecha_inicio_certificado: {
                    required: "Por favor, seleccione la fecha de inicio del certificado médico",
                },
                txt_med_fecha_fin_certificado: {
                    required: "Por favor, seleccione la fecha de fin del certificado médico",
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
  
    function txt_fecha_fin_certificado_1() {
        if ($('#txt_med_fecha_fin_certificado').is(':checked')) {
            var hoy = new Date();
            var dia = String(hoy.getDate()).padStart(2, '0');
            var mes = String(hoy.getMonth() + 1).padStart(2, '0');
            var year = hoy.getFullYear();
            var fecha_actual = year + '-' + mes + '-' + dia;

            // Configurar automáticamente la fecha final como "hoy"
            $('#txt_med_fecha_fin_certificado').val(fecha_actual);
            $('#txt_med_fecha_fin_certificado').prop('disabled', true);
            $('#txt_med_fecha_fin_certificado').rules("remove", "required");

            // Agregar clase 'is-valid' para mostrar el campo como válido
            $('#txt_med_fecha_fin_certificado').addClass('is-valid');
            $('#txt_med_fecha_fin_certificado').removeClass('is-invalid');

        } else {
            if ($('#txt_med_fecha_fin_certificado').prop('disabled')) {
                $('#txt_med_fecha_fin_certificado').val('');
            }

            $('#txt_med_fecha_fin_certificado').prop('disabled', false);
            $('#txt_med_fecha_fin_certificado').rules("add", {
                required: true
            });
            $('#txt_med_fecha_fin_certificado').removeClass('is-valid');
            $('#form_agregar_idioma').validate().resetForm();
            $('.form-control').removeClass('is-valid is-invalid');
        }

        // Validar las fechas (llama a tu función de validación)
        validar_fechas_idioma();
}

</script>