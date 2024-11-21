<script>
  $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_contratos_trabajos(<?= $id ?>);
        <?php } ?>

    });


    //Contratos de Trabajo

    function cargar_datos_contratos_trabajos(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_contratos_trabajosC.php?listar=true',
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
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_contratos_trabajosC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_contratos_trabajos_id').val(response[0]._id);
                $('#txt_ruta_guardada_contratos_trabajos').val(response[0].th_ctr_contratos_trabajos);
                $('#txt_nombre_empresa_contrato').val(response[0].th_ctr_nombre_empresa);
                $('#txt_tipo_contrato').val(response[0].th_ctr_tipo_contrato);
                $('#txt_ruta_archivo_contrato').val(response[0].th_ctr_ruta_archivo);
                $('#txt_fecha_inicio_contrato').val(response[0].th_ctr_fecha_inicio_contrato);
                $('#txt_fecha_fin_contrato').val(response[0].th_ctr_fecha_fin_contrato);
            }
        });
          
    }

    function insertar_editar_contratos_trabajos() {
        var form_data = new FormData(document.getElementById("form_contratos_trabajos")); // Captura todos los campos y archivos
        
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

        // console.log([...form_data]);
        // console.log([...form_data.keys()]);
        // console.log([...form_data.values()]);
        // return;

        if ($("#form_contratos_trabajos").valid()) {

            $.ajax({
                url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_contratos_trabajosC.php?insertar=true',
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
                            cargar_datos_contratos_trabajos(<?= $id ?>);
                        <?php } ?>
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

        $('#lbl_titulo_contratos_trabajos').html('Editar Contrato');
        $('#btn_guardar_contratos_trabajos').html('Guardar');

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
            confirmButtonText: 'Si'
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
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_contratos_trabajosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_contratos_trabajos(<?= $id ?>);
                    <?php } ?>
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
        
        $('#txt_contratos_trabajos_id').val('');
        $('#txt_ruta_guardada_contratos_trabajos').val('');

        //Limpiar validaciones
        $("#form_contratos_trabajos").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');

        //Cambiar texto
        $('#lbl_titulo_contratos_trabajos').html('Agregue un Contrato');
        $('#btn_guardar_contratos_trabajos').html('Agregar');
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
                <h5><small class="text-body-secondary" id="lbl_titulo_contratos_trabajos">Agregue un Contrato de Trabajo</small></h5>
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
                            <label for="txt_nombre_empresa_contrato" class="form-label form-label-sm">Nombre de la empresa </label>
                            <input type="text" class="form-control form-control-sm" name="txt_nombre_empresa_contrato" id="txt_nombre_empresa_contrato" placeholder="Escriba el nombre de la empresa que emitió el contrato">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_tipo_contrato" class="form-label form-label-sm">Tipo de Contrato </label>
                            <input type="text" class="form-control form-control-sm" name="txt_tipo_contrato" id="txt_tipo_contrato" placeholder="Escriba el tipo de contrato">
                        </div>
                    </div>
                    
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_inicio_contrato" class="form-label form-label-sm">Fecha de Inicio Contrato </label>
                            <input type="date" class="form-control form-control-sm no_caracteres" name="txt_fecha_inicio_contrato" id="txt_fecha_inicio_contrato" onchange="txt_fecha_inicio_contrato_1();">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_fin_contrato" class="form-label form-label-sm">Fecha de Fin Contrato </label>
                            <input type="date" class="form-control form-control-sm no_caracteres" name="txt_fecha_fin_contrato" id="txt_fecha_fin_contrato" onchange="txt_fecha_fin_contrato_1();">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col md-12">
                            <input type="checkbox" class="form-check-input" name="cbx_fecha_final_laboral" id="cbx_fecha_final_laboral" onchange="checkbox_actualidad_exp_prev();">
                            <label for="cbx_fecha_final_laboral" class="form-label form-label-sm">Actualidad</label>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_ruta_archivo_contrato" class="form-label form-label-sm">Copia del contrato firmado </label>
                            <input type="file" class="form-control form-control-sm" name="txt_ruta_archivo_contrato" id="txt_ruta_archivo_contrato" accept=".pdf"  value="" placeholder="">                        
                        </div>
                    </div>


                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_contratos_trabajos" onclick="insertar_editar_contratos_trabajos();">Guardar</button>
                    <button type="button" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_contratos_trabajos" onclick="delete_datos_contratos_trabajos();">Eliminar</button>
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
                <h5><small class="text-body-secondary" id="lbl_titulo_contratos_trabajos">Visualizacion Documento</small></h5>
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

        //Validación Contratos de Trabajo
        $("#form_contratos_trabajos").validate({
            rules: {
                txt_nombre_empresa_contrato: {
                    required: true,
                },
                txt_ruta_archivo_contrato: {
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