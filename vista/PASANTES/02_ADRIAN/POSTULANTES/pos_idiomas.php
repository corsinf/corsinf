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
                $('#pnl_idioma').html(response);
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
                $('#ddl_seleccionar_idioma').val(response[0].th_idi_nombre_idioma);
                $('#ddl_dominio_idioma').val(response[0].th_idi_nivel);
                $('#txt_institucion_1').val(response[0].th_idi_institucion);
                $('#txt_fecha_inicio_idioma').val(response[0].th_idi_fecha_inicio_idioma);
                $('#txt_fecha_fin_idioma').val(response[0].th_idi_fecha_fin_idioma);

                $('#txt_idiomas_id').val(response[0]._id);

            }
        });
    }

    function insertar_editar_idiomas() {

        var ddl_seleccionar_idioma = $('#ddl_seleccionar_idioma').val();
        var ddl_dominio_idioma = $('#ddl_dominio_idioma').val();
        var txt_institucion_1 = $('#txt_institucion_1').val();
        var txt_fecha_inicio_idioma = $('#txt_fecha_inicio_idioma').val();
        var txt_fecha_fin_idioma = $('#txt_fecha_fin_idioma').val();
       
        var id_postulante = '<?= $id ?>';
        var txt_idi_idiomas_id = $('#txt_idiomas_id').val();

        var parametros_idiomas = {
            //'_id': txt_idi_idiomas_id,
            'id_postulante': id_postulante,
            'ddl_seleccionar_idioma': ddl_seleccionar_idioma,
            'ddl_dominio_idioma': ddl_dominio_idioma,
            'txt_institucion_1': txt_institucion_1,
            'txt_fecha_inicio_idioma': txt_fecha_inicio_idioma,
            'txt_fecha_fin_idioma': txt_fecha_fin_idioma,
            '_id': txt_idi_idiomas_id
        }

        if ($("#form_agregar_idioma").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            //console.log(parametros_idiomas)
            insertar_idiomas(parametros_idiomas);
        }

    }

    function insertar_idiomas(parametros) {

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_idiomasC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_idiomas(<?= $id ?>);
                        limpiar_campos_idiomas_modal();
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

        $('#modal_agregar_idioma').modal('show');
        $('#lbl_nombre_idioma').html('Editar Idioma');
        $('#btn_guardar_idioma').html('Editar');

    }

    function borrar_datos_idioma() {
        //Para revisar y enviar el dato como parametro 
        id = $('#txt_idiomas_id').val();
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
                eliminar_idioma(id);
            }
        })
    }

    function eliminar_idioma(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_idiomasC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_idiomas(<?= $id ?>);
                        limpiar_campos_idiomas_modal();
                    <?php } ?>
                    $('#modal_agregar_idioma').modal('hide');
                }
            }
        });
    }

    function limpiar_campos_idiomas_modal() {
        $('#form_agregar_idioma').validate().resetForm();
        $('.form-control, .form-select').removeClass('is-valid is-invalid');
        $('#ddl_seleccionar_idioma').val('');
        $('#ddl_dominio_idioma').val('');
        $('#txt_institucion_1').val('');
        $('#txt_fecha_inicio_idioma').val('');
        $('#txt_fecha_fin_idioma').val('');
        $('#txt_idiomas_id').val('');
        // //Cambiar texto
        $('#lbl_nombre_idioma').html('Agregue un idioma');
        $('#btn_guardar_idioma').html('Agregar');
    }

    function validar_fechas_idioma() {
    var fecha_inicio = $('#txt_fecha_inicio_idioma').val();
    var fecha_final = $('#txt_fecha_fin_idioma').val();
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
            reiniciar_campos_fecha('#txt_fecha_fin_idioma');
            return;
        }
    }

    //* Validar que la fecha de inicio no sea mayor a la fecha actual
    if (fecha_inicio && Date.parse(fecha_inicio) > Date.parse(fecha_actual)) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "La fecha de inicio no puede ser mayor a la fecha actual.",
        });
        reiniciar_campos_fecha('#txt_fecha_inicio_idioma');
        return;
    }

    //* Validar que la fecha final no sea mayor a la fecha actual
    if (fecha_final && Date.parse(fecha_final) > Date.parse(fecha_actual)) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "La fecha final no puede ser mayor a la fecha actual.",
        });
        reiniciar_campos_fecha('#txt_fecha_fin_idioma');
        return;
    }
}

//* Función para reiniciar campos
function reiniciar_campos_fecha(campo) {
    $(campo).val('');
    $(campo).removeClass('is-valid is-invalid');
    $('.form-control').removeClass('is-valid is-invalid');
}

</script>

<div id="pnl_idioma">

</div>

<!-- Modal para agregar idiomas-->
<div class="modal" id="modal_agregar_idioma" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_nombre_idioma">Agregue un idioma </small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_idiomas_modal()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_agregar_idioma">
                <input type="hidden" id="txt_idiomas_id">
                <div class="modal-body">
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="ddl_seleccionar_idioma" class="form-label form-label-sm">Idioma </label>
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
                            <label for="ddl_dominio_idioma" class="form-label form-label-sm">Dominio del Idioma </label>
                            <select class="form-select form-select-sm" id="ddl_dominio_idioma" name="ddl_dominio_idioma" required>
                                <option selected disabled value="">-- Selecciona su nivel de dominio del idioma --</option>
                                <option value="Nativo">Nativo</option>
                                <option value="A1: Principiante">A1: Principiante</option>
                                <option value="A2: Básico">A2: Básico</option>
                                <option value="B1: Pre-intermedio">B1: Pre-intermedio</option>
                                <option value="B2: Intermedio">B2: Intermedio</option>
                                <option value="c1: Intermedio-Alto">c1: Intermedio-Alto</option>
                                <option value="C2: Avanzado">C2: Avanzado</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_institucion" class="form-label form-label-sm">Instución </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_institucion_1" id="txt_institucion_1" placeholder="Escriba la institución donde recibió su certificado" maxlength="100">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_inicio_idioma" class="form-label form-label-sm">Fecha de Inicio </label>
                            <input type="date" class="form-control form-control-sm" name="txt_fecha_inicio_idioma" id="txt_fecha_inicio_idioma" onchange="txt_fecha_fin_idioma_1();">
                        </div>
                    </div>
                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_fecha_fin_idioma" class="form-label form-label-sm">Fecha de fin del curso </label>
                            <input type="date" class="form-control form-control-sm" name="txt_fecha_fin_idioma" id="txt_fecha_fin_idioma" onchange="txt_fecha_fin_idioma_1();">
                        </div>
                    </div>
                    </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_idioma" onclick="insertar_editar_idiomas(); validar_fechas_idioma();">Agregar</button>
                    <button type="button" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_formacion" onclick="borrar_datos_idioma();">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        agregar_asterisco_campo_obligatorio('ddl_seleccionar_idioma');
        agregar_asterisco_campo_obligatorio('ddl_dominio_idioma');
        agregar_asterisco_campo_obligatorio('txt_institucion_1');
        agregar_asterisco_campo_obligatorio('txt_fecha_inicio_idioma');
        agregar_asterisco_campo_obligatorio('txt_fecha_fin_idioma');
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
                    maxlength: "200"
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
  
    function txt_fecha_fin_idioma_1() {
        if ($('#txt_fecha_fin_idioma').is(':checked')) {
            var hoy = new Date();
            var dia = String(hoy.getDate()).padStart(2, '0');
            var mes = String(hoy.getMonth() + 1).padStart(2, '0');
            var year = hoy.getFullYear();
            var fecha_actual = year + '-' + mes + '-' + dia;

            // Configurar automáticamente la fecha final como "hoy"
            $('#txt_fecha_fin_idioma').val(fecha_actual);
            $('#txt_fecha_fin_idioma').prop('disabled', true);
            $('#txt_fecha_fin_idioma').rules("remove", "required");

            // Agregar clase 'is-valid' para mostrar el campo como válido
            $('#txt_fecha_fin_idioma').addClass('is-valid');
            $('#txt_fecha_fin_idioma').removeClass('is-invalid');

        } else {
            if ($('#txt_fecha_fin_idioma').prop('disabled')) {
                $('#txt_fecha_fin_idioma').val('');
            }

            $('#txt_fecha_fin_idioma').prop('disabled', false);
            $('#txt_fecha_fin_idioma').rules("add", {
                required: true
            });
            $('#txt_fecha_fin_idioma').removeClass('is-valid');
            $('#form_agregar_idioma').validate().resetForm();
            $('.form-control').removeClass('is-valid is-invalid');
        }

        // Validar las fechas (llama a tu función de validación)
        validar_fechas_idioma();
}

</script>