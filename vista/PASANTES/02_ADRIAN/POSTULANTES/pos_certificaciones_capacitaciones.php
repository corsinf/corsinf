<script>
    //Certificaciones y Capacitaciones
    function insertar_editar_certificaciones_capacitaciones() {
        var txt_nombre_certificacion = $('#txt_nombre_certificacion').val();
        var txt_enlace_certificado = $('#txt_enlace_certificado').val();
        var txt_pdf_certificado = $('#txt_pdf_certificado').val();

        var parametros_certificaciones_capacitaciones = {
            'txt_nombre_certificacion': txt_nombre_certificacion,
            'txt_enlace_certificado': txt_enlace_certificado,
            'txt_pdf_certificado': txt_pdf_certificado,
        }

        if ($("#form_certificaciones_capacitaciones").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_certificaciones_capacitaciones)
        }
    }
</script>

<div class="row pt-3 mb-col">
    <div class="col-md-3">
        <h6 class="fw-bold">CS50: Introduction to Computer Science</h6>
        <a href="#" class="fw-bold">Ver Certificado</a>
    </div>
    <div class="col-md-3">
        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
    </div>
</div>


<!-- Modal para agregar certificaciones y capacitaciones-->
<div class="modal" id="modal_agregar_certificaciones" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue una Certificación o Capacitación</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick=""></button>
            </div>
            <!-- Modal body -->
            <form id="form_certificaciones_capacitaciones">
                <div class="modal-body">
                    <div class="row pt-3 mb-col">
                        <div class="md-3">
                            <label for="txt_nombre_certificacion" class="form-label form-label-sm">Nombre del curso o capacitación <label style="color: red;">*</label></label>
                            <input type="text" class="form-control form-control-sm " name="txt_nombre_certificacion" id="txt_nombre_certificacion" value="" placeholder="Escriba el nombre del curso o capacitación">
                        </div>
                        <div class="md-3">
                            <label for="txt_enlace_certificado" class="form-label form-label-sm">1. Enlace del Certificado obtenido <label style="color: red;">*</label></label>
                            <input type="text" class="form-control form-control-sm " name="txt_enlace_certificado" id="txt_enlace_certificado" value="" placeholder="Escriba el enlace a su certificado">
                        </div>
                        <div class="md-3">
                            <label for="txt_pdf_certificado" class="form-label form-label-sm">2. PDF del Certificado obtenido <label style="color: red;">*</label></label>
                            <input type="file" class="form-control form-control-sm" name="txt_pdf_certificado" id="txt_pdf_certificado" accept=".pdf" value="" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_certificaciones" onclick="insertar_editar_certificaciones_capacitaciones();">Guardar Certificación o Capacitación</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        //Validación Certificaciones y Capacitaciones
        $("#form_certificaciones_capacitaciones").validate({
            rules: {
                txt_nombre_certificacion: {
                    required: true,
                },
                txt_enlace_certificado: {
                    required: true,
                },
                txt_pdf_certificado: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_certificacion: {
                    required: "Por favor ingrese el nombre del certificado",
                },
                txt_enlace_certificado: {
                    required: "Por favor ingrese el enlace de su certificado",
                },
                txt_pdf_certificado: {
                    required: "Por favor ingrese el PDF de su certificado",
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