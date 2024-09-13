<script>
    //Experiencia Laboral
    function insertar_editar_experiencia_laboral() {
        var txt_nombre_empresa = $('#txt_nombre_empresa').val();
        var txt_cargos_ocupados = $('#txt_cargos_ocupados').val();
        var txt_fecha_inicio_laboral = $('#txt_fecha_inicio_laboral').val();
        var txt_fecha_final_laboral = $('#txt_fecha_final_laboral').val();
        var cbx_fecha_final_laboral = $('#cbx_fecha_final_laboral').prop('checked');
        var txt_responsabilidades_logros = $('#txt_responsabilidades_logros').val();

        var parametros_experiencia_laboral = {
            'txt_nombre_empresa': txt_nombre_empresa,
            'txt_cargos_ocupados': txt_cargos_ocupados,
            'txt_fecha_inicio_laboral': txt_fecha_inicio_laboral,
            'txt_fecha_final_laboral': txt_fecha_final_laboral,
            'cbx_fecha_final_laboral': cbx_fecha_final_laboral,
            'txt_responsabilidades_logros': txt_responsabilidades_logros,
        }

        if ($("#form_experiencia_laboral").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_experiencia_laboral)
        }
    }

    function limpiar_parametros_experiencia_laboral() {
        //Limpiar parámetros

        //experiencia laboral
        $('#txt_nombre_empresa').val('');
        $('#txt_cargos_ocupados').val('');
        $('#txt_fecha_inicio_laboral').val('');
        $('#txt_fecha_final_laboral').val('');
        $('#cbx_fecha_final_laboral').val('');
        $('#txt_responsabilidades_logros').prop('');
    }
</script>

<div id="pnl_experiencia_previa">

</div>

<div class="row mb-3">
    <div class="col-10">
        <h6 class="fw-bold">Corsinf</h6>
        <p>Desarrollador de Software</p>
        <p>2024-06-25 - 2024-09-25</p>
        <p>Diseñar, codificar, probar y mantener aplicaciones y sistemas de software de alta calidad.</p>
    </div>
    <div class="col-2">
        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
    </div>
</div>

<!-- Modal para agregar experiencia laboral-->
<div class="modal" id="modal_agregar_experiencia" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue una experiencia laboral</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_experiencia_laboral">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="txt_nombre_empresa" class="form-label form-label-sm">Nombre de la empresa <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_nombre_empresa" id="txt_nombre_empresa" placeholder="Escriba el nombre de la empresa donde trabajó">
                    </div>
                    <div class="mb-3">
                        <label for="txt_cargos_ocupados" class="form-label form-label-sm">Cargos ocupados <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_cargos_ocupados" id="txt_cargos_ocupados" placeholder="Escriba los cargos que ocupo en la empresa">
                    </div>
                    <div class="mb-3">
                        <label for="txt_fecha_inicio_laboral" class="form-label form-label-sm">Fecha de inicio <label style="color: red;">*</label></label>
                        <input type="date" class="form-control form-control-sm" name="txt_fecha_inicio_laboral" id="txt_fecha_inicio_laboral">
                    </div>
                    <div>
                        <label for="txt_fecha_final_laboral" class="form-label form-label-sm">Fecha de finalización <label style="color: red;">*</label></label>
                        <input type="date" class="form-control form-control-sm" name="txt_fecha_final_laboral" id="txt_fecha_final_laboral">
                    </div>
                    <div class="mt-1 mb-3">
                        <input type="checkbox" class="form-check-input" name="cbx_fecha_final_laboral" id="cbx_fecha_final_laboral" onchange="checkbox_actualidad();">
                        <label for="cbx_fecha_final_laboral" class="form-label form-label-sm">Actualidad</label>
                    </div>
                    <div class="mb-3">
                        <label for="txt_responsabilidades_logros" class="form-label form-label-sm">Descripción de responsabilidades y logros <label style="color: red;">*</label></label>
                        <textarea type="text" class="form-control form-control-sm" name="txt_responsabilidades_logros" id="txt_responsabilidades_logros" placeholder=""></textarea>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_agregar_experiencia_laboral" onclick="insertar_editar_experiencia_laboral();">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        //Validación Experiencia Laboral
        $("#form_experiencia_laboral").validate({
            rules: {
                txt_nombre_empresa: {
                    required: true,
                },
                txt_cargos_ocupados: {
                    required: true,
                },
                txt_fecha_inicio_laboral: {
                    required: true,
                },
                txt_fecha_final_laboral: {
                    required: true,
                },
                txt_responsabilidades_logros: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_empresa: {
                    required: "Por favor ingrese el nombre de la empresa",
                },
                txt_cargos_ocupados: {
                    required: "Por favor ingrese los cargos ocupados",
                },
                txt_fecha_inicio_laboral: {
                    required: "Por favor ingrese la fecha en la que iniciaron sus funciones",
                },
                txt_fecha_final_laboral: {
                    required: "Por favor ingrese la fecha de finalización o seleccione 'Actualidad' si sigue trabajando.",
                },
                txt_responsabilidades_logros: {
                    required: "Por favor ingrese sus responsabilidades y logros",
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

    function checkbox_actualidad() {
        if ($('#cbx_fecha_final_laboral').is(':checked')) {
            $('#txt_fecha_final_laboral').rules("remove", "required");
        } else {
            $('#txt_fecha_final_laboral').rules("add", {
                required: true
            });
        }
        $("#form_experiencia_laboral").validate().element('#txt_fecha_final_laboral');
    }
</script>