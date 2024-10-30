<script>
    //Contratos de Trabajo
    function insertar_editar_contrato_laboral() {
        var txt_nombre_empresa_contrato = $('#txt_nombre_empresa_contrato').val();
        var txt_copia_contrato = $('#txt_copia_contrato').val();

        var parametros_contrato_laboral = {
            'txt_nombre_empresa_contrato': txt_nombre_empresa_contrato,
            'txt_copia_contrato': txt_copia_contrato,
        }

        if ($("#form_contrato_trabajo").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_contrato_laboral)
        }
    }
</script>

<div class="row mb-2">
    <div class="col-10 d-flex align-items-center">
        <p class="fw-bold">Contrato de trabajo - Sambitours</p>
    </div>
    <div class="col-2 d-flex justify-content-end">
        <a href="#" class=""><i class='text-info bx bx-download me-2' style="font-size: 20px;"></i></a>
        <a href="#" class=""><i class='text-danger bx bx-trash me-0' style="font-size: 20px;"></i></a>
    </div>
</div>

<!-- Modal para agregar contratos de trabajo-->
<div class="modal" id="modal_agregar_contratos" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un Contrato</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick=""></button>
            </div>
            <!-- Modal body -->
            <form id="form_contrato_trabajo">
            <div class="modal-body">
                <div class="row pt-3 mb-col">
                    <div class="col-md-12">
                        <label for="txt_nombre_empresa_contrato" class="form-label form-label-sm">Nombre de la empresa <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_nombre_empresa_contrato" id="txt_nombre_empresa_contrato" placeholder="Escriba el nombre de la empresa que emitió el contrato">
                    </div>
                </div>
                <div class="row pt-3 mb-col">
                    <div class="col-md-12">
                        <label for="txt_copia_contrato" class="form-label form-label-sm">Copia del contrato firmado <label style="color: red;">*</label></label>
                        <input type="file" class="form-control form-control-sm" name="txt_copia_contrato" id="txt_copia_contrato" accept=".pdf">
                    </div>
                </div>
            </div>    
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_contratos" onclick="insertar_editar_contrato_laboral();">Guardar Contrato de Trabajo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        //Validación Contratos de Trabajo
        $("#form_contrato_trabajo").validate({
            rules: {
                txt_nombre_empresa_contrato: {
                    required: true,
                },
                txt_copia_contrato: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_empresa_contrato: {
                    required: "Por favor ingrese el nombre de la empresa",
                },
                txt_copia_contrato: {
                    required: "Por favor suba la copia de su contrato",
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