<script>
    //Documento de Identidad
    function insertar_editar_documento_identidad() {
        var ddl_tipo_documento_identidad = $('#ddl_tipo_documento_identidad').val();
        var txt_agregar_documento_identidad = $('#txt_agregar_documento_identidad').val();

        var parametros_documento_identidad = {
            'ddl_tipo_documento_identidad': ddl_tipo_documento_identidad,
            'txt_agregar_documento_identidad': txt_agregar_documento_identidad,
        }

        if ($("#form_documento_identidad").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_documento_identidad)
        }
    }
</script>

<div class="row mb-2">
    <div class="col-10 d-flex align-items-center">
        <p class="fw-bold">Cédula de Identidad</p>
    </div>
    <div class="col-2 d-flex justify-content-end">
        <a href="#" class=""><i class='text-info bx bx-download me-2' style="font-size: 20px;"></i></a>
        <a href="#" class=""><i class='text-danger bx bx-trash me-0' style="font-size: 20px;"></i></a>
    </div>
</div>

<!-- Modal para agregar documento de identidad-->
<div class="modal" id="modal_agregar_documento_identidad" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un Documento de Identidad</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick=""></button>
            </div>
            <!-- Modal body -->
            <form id="form_documento_identidad">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ddl_tipo_documento_identidad" class="form-label form-label-sm">Tipo de Documento <label style="color: red;">*</label></label>
                        <select class="form-select form-select-sm" id="ddl_tipo_documento_identidad" name="ddl_tipo_documento_identidad">
                            <option selected disabled value="">-- Selecciona una opción --</option>
                            <option value="Cédula de Identidad">Cédula de Identidad</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="Tarjeta de identificación">Tarjeta de identificación</option>
                            <option value="Licencia">Licencia</option>
                            <option value="Carnét o Certificado para miembro de la Fuerza Pública Ecuatoriana">Carnét o Certificado para miembro de la Fuerza Pública Ecuatoriana</option>
                            <option value="Carnét de discapacidad">Carnét de discapacidad</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="txt_agregar_documento_identidad" class="form-label form-label-sm">Copia del Documento de identidad <label style="color: red;">*</label></label>
                        <input type="file" class="form-control form-control-sm" name="txt_agregar_documento_identidad" id="txt_agregar_documento_identidad" accept=".pdf">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_documento_identidad" onclick="insertar_editar_documento_identidad();">Guardar Documento de Identidad</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        //Validación Documento de Identidad
        $("#form_documento_identidad").validate({
            rules: {
                ddl_tipo_documento_identidad: {
                    required: true,
                },
                txt_agregar_documento_identidad: {
                    required: true,
                },
            },
            messages: {
                ddl_tipo_documento_identidad: {
                    required: "Por favor eliga el documento de identidad que va a subir",
                },
                txt_agregar_documento_identidad: {
                    required: "Por favor suba su documento de identidad",
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