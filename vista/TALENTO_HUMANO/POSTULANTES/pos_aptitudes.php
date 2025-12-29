<script>
    $(document).ready(function() {
        cargar_datos_aptitudes_tecnicas(<?= $id ?>);
        cargar_datos_aptitudes_blandas(<?= $id ?>);

    });

    function activar_select2() {

        lista_aptitudes_tecnicas_postulante(<?= $id ?>);
        lista_aptitudes_blandas_postulante(<?= $id ?>);

        $('#ddl_seleccionar_aptitud_blanda').select2({
            placeholder: ' Selecciona una opción',
            dropdownParent: $('#modal_agregar_aptitudes'),
            language: {
                inputTooShort: function() {
                    return "Por favor ingresa 1 o más caracteres";
                },
                noResults: function() {
                    return "No se encontraron resultados";
                },
                searching: function() {
                    return "Buscando...";
                },
                errorLoading: function() {
                    return "No se encontraron resultados";
                }
            }
        });

        $('#ddl_seleccionar_aptitud_tecnica').select2({
            placeholder: ' Selecciona una opción',
            dropdownParent: $('#modal_agregar_aptitudes'),
            language: {
                inputTooShort: function() {
                    return "Por favor ingresa 1 o más caracteres";
                },
                noResults: function() {
                    return "No se encontraron resultados";
                },
                searching: function() {
                    return "Buscando...";
                },
                errorLoading: function() {
                    return "No se encontraron resultados";
                }
            }
        });
    }

    //Aptitudes 
    function cargar_datos_aptitudes_tecnicas(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_habilidadesC.php?cargar_datos_aptitudes_tecnicas=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_aptitudes_tecnicas').html(response);
                //console.log(response)
            }
        });
    }

    function cargar_datos_aptitudes_blandas(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_habilidadesC.php?cargar_datos_aptitudes_blandas=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_aptitudes_blandas').html(response);
                //console.log(response)
            }
        });
    }

    function lista_aptitudes_blandas_postulante(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_habilidadesC.php?listar_aptitudes_blandas_postulante=true',
            data: {
                id_postulante: id
            },
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#ddl_seleccionar_aptitud_blanda').html(response);
            }
        });
    }

    function lista_aptitudes_tecnicas_postulante(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_habilidadesC.php?listar_aptitudes_tecnicas_postulante=true',
            data: {
                id_postulante: id
            },
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#ddl_seleccionar_aptitud_tecnica').html(response);
            }
        });
    }

    function insertar_editar_aptitudes() {
        var txt_id_postulante = $('#txt_postulante_id').val();

        var aptitudes_tecnicas = $('#ddl_seleccionar_aptitud_tecnica').val() || [];
        var aptitudes_blandas = $('#ddl_seleccionar_aptitud_blanda').val() || [];

        var todas_las_aptitudes = aptitudes_tecnicas.concat(aptitudes_blandas);

        var txt_id_habilidades_postulante = $('.txt_id_habilidades_postulante').val();

        var parametros_aptitudes = {
            '_id': txt_id_habilidades_postulante,
            'txt_id_postulante': txt_id_postulante,
            'txt_id_aptitudes': todas_las_aptitudes,
        }

        if ($("#form_aptitudes").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            //console.log(parametros_aptitudes)
            insertar_aptitudes(parametros_aptitudes)
        }
    }

    function insertar_aptitudes(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_habilidadesC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_aptitudes_tecnicas(<?= $id ?>);
                        cargar_datos_aptitudes_blandas(<?= $id ?>);
                    <?php } ?>
                    limpiar_campos_aptitudes_modal();
                    $('#modal_agregar_aptitudes').modal('hide');
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function delete_datos_aptitudes(id) {
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_aptitudes(id);
            }
        })
    }

    function eliminar_aptitudes(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_pos_habilidadesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    <?php if (isset($_GET['id'])) { ?>
                        cargar_datos_aptitudes_tecnicas(<?= $id ?>);
                        cargar_datos_aptitudes_blandas(<?= $id ?>);
                    <?php } ?>
                    limpiar_campos_aptitudes_modal();
                }
            }
        });
    }

    function limpiar_campos_aptitudes_modal() {
        $('#ddl_seleccionar_aptitud_blanda').val('');
        $('#ddl_seleccionar_aptitud_tecnica').val('');
        //Limpiar validaciones
        $("#form_aptitudes").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');
    }
</script>

<h6 class="fw-bold mt-3 mb-2">Técnicas</h6>
<div id="pnl_aptitudes_tecnicas">
</div>

<h6 class="fw-bold mt-4 mb-2">Blandas</h6>
<div id="pnl_aptitudes_blandas">
</div>

<!-- Modal para agregar aptitudes técnicas y blandas-->
<div class="modal" id="modal_agregar_aptitudes" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold">Agregar Aptitudes</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_aptitudes_modal();"></button>
            </div>
            <!-- Modal body -->
            <form id="form_aptitudes">
                <div class="modal-body">
                    <input type="hidden" class="txt_id_habilidades_postulante">

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="ddl_seleccionar_aptitud_tecnica" class="form-label form-label-sm">Seleccione sus Aptitudes Técnicas </label>
                            <select class="form-select form-select-sm ddl_seleccionar_aptitud" id="ddl_seleccionar_aptitud_tecnica" name="ddl_seleccionar_aptitud_tecnica" multiple="multiple"></select>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="ddl_seleccionar_aptitud_blanda" class="form-label form-label-sm">Seleccione sus Aptitudes Blandas </label>
                            <select class="form-select form-select-sm ddl_seleccionar_aptitud" id="ddl_seleccionar_aptitud_blanda" name="ddl_seleccionar_aptitud_blanda" multiple="multiple"></select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-primary btn-sm px-4 m-0 d-flex align-items-center" id="btn_guardar_aptitudes" onclick="insertar_editar_aptitudes();" type="button"><i class="bx bx-save"></i>Guardar Aptitudes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('ddl_seleccionar_aptitud_blanda');
        agregar_asterisco_campo_obligatorio('ddl_seleccionar_aptitud_tecnica');

        //Validación Aptitudes
        $("#form_aptitudes").validate({
            rules: {
                ddl_seleccionar_aptitud_blanda: {
                    required: true,
                },
                ddl_seleccionar_aptitud_tecnica: {
                    required: true,
                },
            },
            messages: {
                ddl_seleccionar_aptitud_blanda: {
                    required: "Por favor eliga al menos una aptitud blanda",
                },
                ddl_seleccionar_aptitud_tecnica: {
                    required: "Por favor eliga al menos una aptitud técnica",
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