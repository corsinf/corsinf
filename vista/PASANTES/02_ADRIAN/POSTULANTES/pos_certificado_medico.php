<script>
    //Certificados Médicos
    function insertar_editar_certificado_medico() {
        var txt_nombre_certificado_medico = $('#txt_nombre_certificado_medico').val();
        var txt_respaldo_medico = $('#txt_respaldo_medico').val();

        var parametros_certificado_medico = {
            'txt_nombre_certificado_medico': txt_nombre_certificado_medico,
            'txt_respaldo_medico': txt_respaldo_medico,
        }

        if ($("#form_certificado_medico").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_certificado_medico)
        }
    }
</script>

<div class="row mb-2">
    <div class="col-10 d-flex align-items-center">
        <p class="fw-bold">Certificado médico de enfermedad cualquiera</p>
    </div>
    <div class="col-2 d-flex justify-content-end">
        <a href="#" class=""><i class='text-info bx bx-download me-2' style="font-size: 20px;"></i></a>
        <a href="#" class=""><i class='text-danger bx bx-trash me-0' style="font-size: 20px;"></i></a>
    </div>
</div>

<!-- Modal para agregar certificados médicos-->
<div class="modal" id="modal_agregar_certificado_medico" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un Certificado Médico</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick=""></button>
            </div>
            <!-- Modal body -->
            <form id="form_certificado_medico">
                <div class="modal-body">
                    <div class="md-3">
                        <label for="txt_nombre_certificado_medico" class="form-label form-label-sm">Nombre del certificado <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_nombre_certificado_medico" id="txt_nombre_certificado_medico" placeholder="Escriba el nombre del certificado médico">
                    </div>
                    <div class="md-3">
                        <label for="txt_respaldo_medico" class="form-label form-label-sm">Documentación que respalde la aptitud para el trabajo <label style="color: red;">*</label></label>
                        <input type="file" class="form-control form-control-sm" name="txt_respaldo_medico" id="txt_respaldo_medico" accept=".pdf">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_certificado_medico" onclick="insertar_editar_certificado_medico()">Guardar Certificado Médico</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        //Validación Certificados Médicos
        $("#form_certificado_medico").validate({
            rules: {
                txt_nombre_certificado_medico: {
                    required: true,
                },
                txt_respaldo_medico: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_certificado_medico: {
                    required: "Por favor ingrese un nombre para su certificado médico",
                },
                txt_respaldo_medico: {
                    required: "Por favor suba un documento que lo respalde",
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