<script>
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_formacion_academica(<?= $id ?>);
            cargar_datos_modal_formacion_academica(<?= $id ?>);
        <?php } ?>

    });
    //Formación Académica
    function cargar_datos_formacion_academica(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_formacion_academicaC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_formacion_academica').html(response);

                console.log(response);
            }
        });
    }

    function cargar_datos_modal_formacion_academica(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_formacion_academicaC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_titulo_obtenido').val(response[0].th_fora_titulo_obtenido);
                $('#txt_institucion').val(response[0].th_fora_institución);
                $('#txt_fecha_inicio_academico').val(response[0].th_fora_fecha_inicio_formacion);
                $('#txt_fecha_final_academico').val(response[0].th_fora_fecha_fin_formacion);

                console.log(response);
            }
        });
    }

    function insertar_editar_formacion_academica() {
        var txt_titulo_obtenido = $('#txt_titulo_obtenido').val();
        var txt_institucion = $('#txt_institucion').val();
        var txt_fecha_inicio_academico = $('#txt_fecha_inicio_academico').val();
        var txt_fecha_final_academico = $('#txt_fecha_final_academico').val();

        var parametros_formacion_academica = {
            '_id': '<?= $id ?>',
            'txt_titulo_obtenido': txt_titulo_obtenido,
            'txt_institucion': txt_institucion,
            'txt_fecha_inicio_academico': txt_fecha_inicio_academico,
            'txt_fecha_final_academico': txt_fecha_final_academico,
        }

        if ($("#form_formacion_academica").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_formacion_academica)
            insertar_formacion_academica(parametros_formacion_academica)
        }
    }

    function insertar_formacion_academica(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_formacion_academicaC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {});
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_formacion_academica(<?= $id ?>);
                    <?php } ?>
                    $('#modal_agregar_formacion').modal('hide');
                } else if (response == -2) {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }
</script>

<div class="row mb-3">
    <div class="col-8" id="pnl_formacion_academica">
    </div>
    <div class="col-4">
        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
    </div>
</div>

<!-- Modal para agregar formación académica-->
<div class="modal" id="modal_agregar_formacion" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue una formación académica</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_formacion_academica">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="txt_titulo_obtenido" class="form-label form-label-sm">Título obtenido <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_titulo_obtenido" id="txt_titulo_obtenido" placeholder="Escriba su título académico">
                    </div>
                    <div class="mb-3">
                        <label for="txt_institucion" class="form-label form-label-sm">Institución <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_institucion" id="txt_institucion" placeholder="Escriba la institución en la que se formó">
                    </div>
                    <div class="mb-3">
                        <label for="txt_fecha_inicio_academico" class="form-label form-label-sm">Fecha de inicio <label style="color: red;">*</label></label>
                        <input type="date" class="form-control form-control-sm" name="txt_fecha_inicio_academico" id="txt_fecha_inicio_academico">
                    </div>
                    <div class="mb-3">
                        <label for="txt_fecha_final_academico" class="form-label form-label-sm">Fecha de finalización <label style="color: red;">*</label></label>
                        <input type="date" class="form-control form-control-sm mb-2" name="txt_fecha_final_academico" id="txt_fecha_final_academico">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_formacion" onclick="insertar_editar_formacion_academica();">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
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