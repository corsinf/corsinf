<script>
    $(document).ready(function() {
        lista_aptitudes_tecnicas();
        lista_aptitudes_blandas();
        <?php if (isset($_GET['id'])) { ?>
            cargar_datos_aptitudes_tecnicas(<?= $id ?>);
        <?php } ?>
    });

    function activar_select2() {
        $('#ddl_seleccionar_aptitud_blanda').select2({
            placeholder: 'Selecciona una opción',
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
            placeholder: 'Selecciona una opción',
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
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_habilidadesC.php?cargar_datos_aptitudes_tecnicas=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_aptitudes_tecnicas').html(response);
                console.log(response)
            }
        });
    }

    function cargar_datos_aptitudes_blandas(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_pos_habilidadesC.php?cargar_datos_aptitudes_blandas=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_aptitudes_blandas').html(response);
                console.log(response)
            }
        });
    }


    function lista_aptitudes_tecnicas() {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_cat_habilidadesC.php?listar_tecnicas=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#ddl_seleccionar_aptitud_tecnica').html(response);
            }
        });
    }

    function lista_aptitudes_blandas() {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_cat_habilidadesC.php?listar_blandas=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#ddl_seleccionar_aptitud_blanda').html(response);
            }
        });
    }

    function insertar_editar_aptitudes() {
        var ddl_seleccionar_aptitud_blanda = [];
        $('.ddl_seleccionar_aptitud_blanda').each(function() {
            ddl_seleccionar_aptitud_blanda.push($(this).val());
        });

        var ddl_seleccionar_aptitud_tecnica = [];
        $('.ddl_seleccionar_aptitud_tecnica').each(function() {
            ddl_seleccionar_aptitud_tecnica.push($(this).val());
        });

        var txt_id_postulante = '<?= $id ?>';
        var txt_id_formacion_academica = $('#txt_formacion_id').val();

        var parametros_aptitudes = {
            'ddl_seleccionar_aptitud_blanda': ddl_seleccionar_aptitud_blanda,
            'ddl_seleccionar_aptitud_tecnica': ddl_seleccionar_aptitud_tecnica,
        }

        if ($("#form_aptitudes").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_aptitudes)
        }
    }
</script>

<div id="pnl_aptitudes_tecnicas">
    <!-- <div class="row mt-3">
        <div class="col-8">
            <p class="fw-bold">Aptitudes Técnicas</p>
            <ul>
                <li>Dominio de paquete Office</li>
            </ul>
        </div>
        <div class="col-4">
            <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
        </div>
    </div> -->
</div>
<div id="pnl_aptitudes_blandas">
    <!-- <div class="row">
        <div class="col-8">
            <p class="fw-bold">Aptitudes Blandas</p>
            <ul>
                <li>Liderazgo</li>
            </ul>
        </div>
        <div class="col-4">
            <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
        </div>
    </div> -->
</div>

<!-- Modal para agregar aptitudes técnicas y blandas-->
<div class="modal" id="modal_agregar_aptitudes" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue Aptitudes</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick=""></button>
            </div>
            <!-- Modal body -->
            <form id="form_aptitudes">
                <div class="modal-body">
                    <div class="mb-4">
                        <div class="row mb-1">
                            <div class="col-12 d-flex align-items-center">
                                <label for="ddl_seleccionar_aptitud_blanda" class="form-label form-label-sm fw-bold">Seleccione sus Aptitudes Blandas <label style="color: red;">*</label></label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <select class="form-select form-select-sm ddl_seleccionar_aptitud_blanda" id="ddl_seleccionar_aptitud_blanda" name="ddl_seleccionar_aptitud_blanda" multiple="multiple">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="row mb-1">
                            <div class="col-12 d-flex align-items-center">
                                <label for="ddl_seleccionar_aptitud_tecnica" class="form-label form-label-sm fw-bold">Seleccione sus Aptitudes Técnicas <label style="color: red;">*</label></label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <select class="form-select form-select-sm ddl_seleccionar_aptitud_tecnica" id="ddl_seleccionar_aptitud_tecnica" name="ddl_seleccionar_aptitud_tecnica" multiple="multiple">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_aptitudes" onclick="insertar_editar_aptitudes();">Guardar Aptitudes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
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