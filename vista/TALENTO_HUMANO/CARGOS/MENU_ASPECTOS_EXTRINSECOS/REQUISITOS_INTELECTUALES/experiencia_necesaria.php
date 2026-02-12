<script>
    $(document).ready(function() {
        cargar_pnl_experiencia_necesaria('<?= $_id ?>');
    });

    function cargar_pnl_experiencia_necesaria(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_experienciaC.php?listar_modal=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {

                $('#pnl_experiencia_necesaria').hide().html(response.html).fadeIn(400);

            }
        });
    }

    function insertar_editar_experiencia_necesaria() {
        var anios = $('#txt_anios_experiencia').val();
        var id_experiencia = $('#th_reqi_instruccion_id').val();
        var id_cargo = '<?= $_id ?>';

        var parametros = {
            'th_reqe_anios': anios,
            'id_cargo': id_cargo,
            '_id': id_experiencia,
        };

        if ($("#form_experiencia_necesaria").valid()) {

        } else {
            return;
        }

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_experienciaC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
                $('#btn_guardar_experiencia_necesaria').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Guardando...');
            },
            success: function(response) {
                $('#btn_guardar_experiencia_necesaria').prop('disabled', false).html('<i class="bx bx-save"></i> Guardar');
                if (response == 1) {
                    Swal.fire('¡Éxito!', 'Operación realizada correctamente.', 'success');
                    $('#modal_experiencia_necesaria').modal('hide');
                    limpiar_campos_experiencia_necesaria_modal();
                    cargar_pnl_experiencia_necesaria(id_cargo);
                } else {
                    Swal.fire('Error', 'No se pudo procesar la solicitud.', 'error');
                }
            }
        });
    }

    function abrir_modal_experiencia_necesaria(id = null) {
        limpiar_campos_experiencia_necesaria_modal();
        if (id) {
            $('#lbl_titulo_experiencia_necesaria').html('<i class="bx bx-edit me-2"></i>Editar Experiencia');
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_experienciaC.php?listar=true',
                type: 'post',
                data: {
                    experiencia_id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response && response.length > 0) {
                        $('#th_reqi_instruccion_id').val(response[0]._id);
                        $('#txt_anios_experiencia').val(response[0].th_reqe_anios);
                    }
                }
            });
        } else {
            $('#lbl_titulo_experiencia_necesaria').html('<i class="bx bx-plus me-2"></i>Agregar Experiencia');
        }
        $('#modal_experiencia_necesaria').modal('show');
    }

    function delete_datos_experiencia_necesaria(id) {
        Swal.fire({
            title: '¿Eliminar registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_experienciaC.php?eliminar=true',
                    type: 'post',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response == 1) {
                            Swal.fire('Eliminado', 'Registro eliminado', 'success');
                            cargar_pnl_experiencia_necesaria('<?= $_id ?>');
                        }
                    }
                });
            }
        });
    }

    function limpiar_campos_experiencia_necesaria_modal() {
        $('#form_experiencia_necesaria')[0].reset();
        $('#th_reqi_instruccion_id').val('');
        $('.form-control').removeClass('is-invalid');
    }
</script>


<div class="" id="pnl_experiencia_necesaria">

</div>


<div class="modal fade" id="modal_experiencia_necesaria" tabindex="-1" aria-hidden="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-dark bg-opacity-10">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="lbl_titulo_experiencia_necesaria">
                        <i class='bx bx-briefcase me-2'></i>Estado Laboral Interno
                    </h5>
                    <small class="text-muted">Gestiona la situación contractual, cargos y remuneraciones.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_experiencia_necesaria_modal()"></button>
            </div>

            <form id="form_experiencia_necesaria" class="needs-validation">
                <input type="hidden" name="th_reqi_instruccion_id" id="th_reqi_instruccion_id">

                <div class="modal-body">

                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label for="txt_anios_experiencia" class="form-label fw-semibold fs-7">Años de Experiencia </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-muted"><i class='bx bx-time-five'></i></span>
                                <input type="number"
                                    class="form-control"
                                    name="txt_anios_experiencia"
                                    id="txt_anios_experiencia"
                                    placeholder="Ej: 2">
                            </div>
                            <label class="error" style="display: none;" for="txt_anios_experiencia"></label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                        <div class="ms-auto">
                            <button type="button" class="btn btn-secondary btn-sm me-2" data-bs-dismiss="modal" onclick="limpiar_campos_experiencia_necesaria_modal()">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-sm px-4" id="btn_guardar_experiencia_necesaria" onclick="insertar_editar_experiencia_necesaria() ">
                                <i class="bx bx-save"></i> Guardar
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_anios_experiencia');

        $("#form_experiencia_necesaria").validate({
            ignore: [], // IMPORTANTE: Para que valide campos ocultos (como el select de select2)
            rules: {
                txt_anios_experiencia: {
                    required: true
                }
            },
            messages: {
                txt_anios_experiencia: {
                    required: "Ingrese los años de experiencia"
                }
            },
            errorPlacement: function(error, element) {
                // Si el elemento es un Select2, ponemos el error después del contenedor de Select2
                if (element.hasClass('select2-validation') || element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
                // Si es select2, podemos forzar un refresh visual si fuera necesario
            },
            unhighlight: function(element) {
                $(element).addClass('is-valid').removeClass('is-invalid');
            },
            submitHandler: () => false
        });

    });
</script>