<script>
    //Idiomas
    function insertar_editar_idiomas() {
        var ddl_seleccionar_idioma = $('#ddl_seleccionar_idioma').val();
        var ddl_dominio_idioma = $('#ddl_dominio_idioma').val();

        var parametros_idiomas = {
            'ddl_seleccionar_idioma': ddl_seleccionar_idioma,
            'ddl_dominio_idioma': ddl_dominio_idioma,
        }

        if ($("#form_agregar_idioma").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_idiomas)
        }

    }
</script>

<div class="row mt-3">
    <div class="col-8">
        <h6 class="fw-bold">Inglés</h6>
        <p>B1</p>
    </div>
    <div class="col-4">
        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
    </div>
</div>

<!-- Modal para agregar idiomas-->
<div class="modal" id="modal_agregar_idioma" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un idioma</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros()"></button>
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
                                <option value="C1">C1</option>
                                <option value="C2">C2</option>
                                <option value="B1">B1</option>
                                <option value="B2">B2</option>
                                <option value="C1">C1</option>
                                <option value="C2">C2</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_idioma" onclick="insertar_editar_idiomas();">Guardar Idioma</button>
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
            },
            messages: {
                ddl_seleccionar_idioma: {
                    required: "Por favor seleccione un idioma",
                },
                ddl_dominio_idioma: {
                    required: "Por favor seleccione su dominio con el idioma",
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