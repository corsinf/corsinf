<script>
     $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_idiomas(<?= $id ?>);
        <?php } ?>

    });
   
    //Idiomas
    function cargar_datos_idiomas(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_idiomasC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_idiomas').html(response);
            }
        });
    }

    function cargar_datos_modal_idiomas(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_idiomasC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#ddl_seleccionar_idioma').val(response[0].th_idi_nombre);
                $('#ddl_dominio_idioma').val(response[0].th_idi_nivel);
                $('#txt_institucion_1').val(response[0].th_idi_institucion);
                $('#txt_fecha_inicio_idioma').val(response[0].th_idi_fecha_inicio_idioma);

                // var fecha_fin_idioma = response[0].th_idi_fecha_fin_idioma;

                // if (fecha_fin_idioma === '') {
                //     var hoy = new Date();
                //     var dia = String(hoy.getDate()).padStart(2, '0');
                //     var mes = String(hoy.getMonth() + 1).padStart(2, '0');
                //     var year = hoy.getFullYear();

                //     var fecha_actual_idioma = year + '-' + mes + '-' + dia;
                //     $('#txt_fecha_fin_idioma').val(fecha_actual_idioma);
                //     $('#txt_fecha_fin_idioma').prop('disabled', true);
                //     $('#txt_fecha_fin_idioma').prop('checked', true);
                // } else {
                //     $('#txt_fecha_fin_idioma').prop('checked', false);
                //     $('#txt_fecha_fin_idioma').prop('disabled', false);
                //     $('#txt_fecha_fin_idioma').val(fecha_fin_idioma);
                // }
                
            }
        });
    }


    function insertar_editar_idiomas() {
    
        var ddl_seleccionar_idioma = $('#ddl_seleccionar_idioma').val();
        var ddl_dominio_idioma = $('#ddl_dominio_idioma').val();
        var txt_institucion_1 = $('#txt_institucion_1').val();
        var txt_fecha_inicio_idioma = $('#txt_fecha_inicio_idioma').val();
        var txt_fecha_fin_idioma   = $('#txt_fecha_fin_idioma').val();

        var id_postulante = '<?= $id ?>';
        
        var parametros_idiomas = {
            'id_postulante': id_postulante,
            'ddl_seleccionar_idioma': ddl_seleccionar_idioma,
            'ddl_dominio_idioma': ddl_dominio_idioma,
            'txt_institucion_1': txt_institucion_1,
            'txt_fecha_inicio_idioma': txt_fecha_inicio_idioma,
            'txt_fecha_fin_idioma': txt_fecha_fin_idioma,

        }

        if ($("#form_agregar_idioma").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_idiomas)
            insertar_idiomas(parametros_idiomas);
        }

    }

    function insertar_idiomas(parametros) {
        alert ("hola2")
    
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_idiomasC.php?hola=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_idiomas(<?= $id ?>);
                        limpiar_campos_idiomas();
                    <?php } ?>
                    $('#modal_agregar_idioma').modal('hide');
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    //* Función para editar el registro de idiomas
    function abrir_modal_idiomas(id) {
        cargar_datos_modal_idiomas(id);

        $('#modal_agregar_idiomas').modal('show');
        $('#lbl_nombre_idioma').html('Editar Idioma');
        $('#btn_guardar_idioma').html('Editar');

    }

    function limpiar_campos_() {
        $('#form_agregar_idioma').validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');
        $('#ddl_seleccionar_idioma').val('');
        $('#ddl_dominio_idioma').val('');
        // $('#txt_fecha_inicio_laboral').val('');
        // $('#txt_fecha_final_laboral').val('');
        // $('#txt_fecha_final_laboral').prop('disabled', false);
        // $('#cbx_fecha_final_laboral').prop('checked', false);
        // $('#txt_responsabilidades_logros').val('');
        // $('#txt_experiencia_id').val('');
        // //Cambiar texto
        // $('#lbl_titulo_experiencia_laboral').html('Agregue una Experiencia Laboral');
        // $('#btn_guardar_experiencia').html('Agregar');
    }    

</script>

<div id="pnl_idiomas">

</div>

<!-- Modal para agregar idiomas-->
<div class="modal" id="modal_agregar_idioma" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h6><label class="text-body-secondary fw-bold" id="lbl_nombre_idioma">Agregue un idioma</small></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_idiomas_modal()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_agregar_idioma">
                <div class="modal-body">
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="ddl_seleccionar_idioma" class="form-label form-label-sm">Idioma <label style="color: red;">*</label></label>
                            <select class="form-select form-select-sm" id="ddl_seleccionar_idioma" name="ddl_seleccionar_idioma">
                                <option selected disabled value="">-- Selecciona un Idioma --</option>
                                <option value="Español">Español</option>
                                <option value="Inglés">Inglés</option>
                                <option value="Francés">Francés</option>
                                <option value="Alemán">Alemán</option>
                                <option value="Chino">Chino</option>
                                <option value="Italiano">Italiano</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="ddl_dominio_idioma" class="form-label form-label-sm">Dominio del Idioma <label style="color: red;">*</label></label>
                            <select class="form-select form-select-sm" id="ddl_dominio_idioma" name="ddl_dominio_idioma" required>
                                <option selected disabled value="">-- Selecciona su nivel de dominio del idioma --</option>
                                <option value="Nativo">Nativo</option>
                                <option value="C1">A1</option>
                                <option value="C2">A2</option>
                                <option value="B1">B1</option>
                                <option value="B2">B2</option>
                                <option value="C1">C1</option>
                                <option value="C2">C2</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_institucion" class="form-label form-label-sm">Instución </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_institucion_1" id="txt_institucion_1" placeholder="Escriba la institución donde recibió su certificado" maxlength="200">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_inicio_idioma" class="form-label form-label-sm">Fecha de Inicio  </label>
                            <input type="date" class="form-control form-control-sm no_caracteres" name="txt_fecha_inicio_idioma" id="txt_fecha_inicio_idioma" placeholder="Escriba la fecha de inicio de estudios " maxlength="200">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_fin_idioma" class="form-label form-label-sm">Fecha de fin del curso </label>
                            <input type="date" class="form-control form-control-sm no_caracteres" name="txt_fecha_fin_idioma" id="txt_fecha_fin_idioma" placeholder="Escriba la fecha de fin de los estudios" maxlength="200">
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_idioma" onclick="insertar_editar_idiomas();">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        //Validación Idiomas
        $("#form_agregar_idioma").validate({
            rules: {
                ddl_seleccionar_idioma: {
                    required: true,
                },
                ddl_dominio_idioma: {
                    required: true,
                },
                txt_institucion_1: {
                    required: true,
                    maxlength: "200"
                },
                txt_fecha_inicio_idioma: {
                    required: true,
                },
                txt_fecha_fin_idioma: {
                    required: true,
                },
            },

            messages: {
                ddl_seleccionar_idioma: {
                    required: "Por favor seleccione un idioma",
                },
                ddl_dominio_idioma: {
                    required: "Por favor seleccione su dominio con el idioma",
                },
                txt_institucion_1: {
                    required: "Por favor escriba la institución donde recibió su certificado",
                    maxlength: "El campo no puede tener más de 200 caracteres"
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