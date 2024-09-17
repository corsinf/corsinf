<script>
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_formacion_academica(<?= $id ?>);
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

                $('#txt_formacion_id').val(response[0]._id);

                console.log(response);
            }
        });
    }

    function insertar_editar_formacion_academica() {
        var txt_titulo_obtenido = $('#txt_titulo_obtenido').val();
        var txt_institucion = $('#txt_institucion').val();
        var txt_fecha_inicio_academico = $('#txt_fecha_inicio_academico').val();
        var txt_fecha_final_academico = $('#txt_fecha_final_academico').val();
        var txt_id_postulante = '<?= $id ?>';
        var txt_id_formacion_academica = $('#txt_formacion_id').val();

        var parametros_formacion_academica = {
            '_id': txt_id_formacion_academica,
            'txt_id_postulante': txt_id_postulante,
            'txt_titulo_obtenido': txt_titulo_obtenido,
            'txt_institucion': txt_institucion,
            'txt_fecha_inicio_academico': txt_fecha_inicio_academico,
            'txt_fecha_final_academico': txt_fecha_final_academico,
        }

        if ($("#form_formacion_academica").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_formacion_academica);
            insertar_formacion_academica(parametros_formacion_academica);
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
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_formacion_academica(<?= $id ?>);
                        limpiar_campos_formacion_academica_modal();
                    <?php } ?>
                    $('#modal_agregar_formacion').modal('hide');
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function abrir_modal_formacion_academica(id) {
        cargar_datos_modal_formacion_academica(id);

        $('#modal_agregar_formacion').modal('show');

    }

    function delete_datos() {
        //Para revisar y enviar el dato como parametro 
        id = $('#txt_formacion_id').val();
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
                eliminar(id);
            }
        })
    }
    
    function eliminar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_formacion_academicaC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_formacion_academica(<?= $id ?>);
                        limpiar_campos_formacion_academica_modal();
                    <?php } ?>
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
                <h5><small class="text-body-secondary">Agregue una formación académica</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_formacion_academica_modal();"></button>
            </div>
            <!-- Modal body -->
            <form id="form_formacion_academica">
                <input type="text" id="txt_formacion_id" hidden>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="txt_titulo_obtenido" class="form-label form-label-sm">Título obtenido <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm no_caracteres" name="txt_titulo_obtenido" id="txt_titulo_obtenido" placeholder="Escriba su título académico">
                    </div>
                    <div class="mb-3">
                        <label for="txt_institucion" class="form-label form-label-sm">Institución <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm no_caracteres" name="txt_institucion" id="txt_institucion" placeholder="Escriba la institución en la que se formó">
                    </div>
                    <div class="mb-3">
                        <label for="txt_fecha_inicio_academico" class="form-label form-label-sm">Fecha de inicio <label style="color: red;">*</label></label>
                        <input type="date" class="form-control form-control-sm no_caracteres" name="txt_fecha_inicio_academico" id="txt_fecha_inicio_academico">
                    </div>
                    <div class="mb-3">
                        <label for="txt_fecha_final_academico" class="form-label form-label-sm">Fecha de finalización <label style="color: red;">*</label></label>
                        <input type="date" class="form-control form-control-sm mb-2 no_caracteres" name="txt_fecha_final_academico" id="txt_fecha_final_academico">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row mx-auto">
                        <div class="col-6">
                            <button type="button" class="btn btn-success btn-sm" id="btn_guardar_formacion" onclick="insertar_editar_formacion_academica();">Agregar</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-danger btn-sm" id="btn_eliminar_formacion" value="" onclick="delete_datos();">Eliminar</button>
                        </div>
                    </div>
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