<script>
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargarDatos_informacion_adicional(<?= $id ?>);
        <?php } ?>
    });

    //Información Adicional
    function cargarDatos_informacion_adicional(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_postulante_inf_adicionalC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_direccion_calle').val(response[0].th_posa_direccion_calle);
                $('#txt_direccion_numero').val(response[0].th_posa_direccion_numero);
                $('#txt_direccion_ciudad').val(response[0].th_posa_direccion_ciudad);
                $('#txt_direccion_estado').val(response[0].th_posa_direccion_estado);
                $('#txt_direccion_postal').val(response[0].th_posa_direccion_codpos);
                $('#txt_inf_adicional_id').val(response[0]._id)

                direccion_completa = response[0].th_posa_direccion_calle + ', ' + response[0].th_posa_direccion_numero + ', ' + response[0].th_posa_direccion_ciudad + ', ' + response[0].th_posa_direccion_estado + ', ' + response[0].th_posa_direccion_codpos
                $('#txt_direccion_v').html(direccion_completa);

                console.log(response);
            }
        });
    }

    function insertar_editar_informacion_adicional() {
        var txt_direccion_calle = $('#txt_direccion_calle').val();
        var txt_direccion_numero = $('#txt_direccion_numero').val();
        var txt_direccion_ciudad = $('#txt_direccion_ciudad').val();
        var txt_direccion_estado = $('#txt_direccion_estado').val();
        var txt_direccion_postal = $('#txt_direccion_postal').val();
        var txt_id_postulante = '<?= $id ?>';
        var txt_id_formacion_academica = $('#txt_inf_adicional_id').val();


        var parametros_informacion_adicional = {
            '_id': txt_id_formacion_academica,
            'txt_id_postulante': txt_id_postulante,
            'txt_direccion_calle': txt_direccion_calle,
            'txt_direccion_numero': txt_direccion_numero,
            'txt_direccion_ciudad': txt_direccion_ciudad,
            'txt_direccion_estado': txt_direccion_estado,
            'txt_direccion_postal': txt_direccion_postal,

        }

        if ($("#form_informacion_adicional").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_informacion_adicional)
            insertar_informacion_adicional(parametros_informacion_adicional)
        }

    }

    function insertar_informacion_adicional(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_postulante_inf_adicionalC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {});
                    <?php if (isset($_GET['id'])) { ?>
                        cargarDatos_informacion_adicional(<?= $id ?>);
                    <?php } ?>
                    limpiar_validaciones_informacion_adicional();
                    $('#modal_informacion_adicional').modal('hide');
                } else if (response == -2) {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function limpiar_validaciones_informacion_adicional() {

        //Limpiar validaciones
        $("#form_informacion_adicional").validate().resetForm();
        $('.form-control').removeClass('is-valid is-invalid');

    }
</script>

<div class="row mb-3">
    <div class="col-6 d-flex align-items-center">
        <h6 class="fw-bold">Dirección</h6>
    </div>
    <div class="col-6 d-flex align-items-center">
        <p id="txt_direccion_v"></p>
    </div>
</div>

<!-- Modal para la informacion Adicional -->
<div class="modal" id="modal_informacion_adicional" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Ingrese su dirección</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_validaciones_informacion_adicional();"></button>
            </div>
            <!-- Modal body -->
            <form id="form_informacion_adicional">
                <div class="modal-body">
                    <p class="fw-bold">Dirección:</p>
                    <div class="row">
                        <input type="text" id="txt_inf_adicional_id" hidden>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="txt_direccion_calle" class="form-label form-label-sm">Calle <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_direccion_calle" id="txt_direccion_calle" value="" placeholder="Escriba la calle de su dirección" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="txt_direccion_numero" class="form-label form-label-sm">Número <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_direccion_numero" id="txt_direccion_numero" value="" placeholder="Escriba el número de su dirección" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="txt_direccion_ciudad" class="form-label form-label-sm">Ciudad <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_direccion_ciudad" id="txt_direccion_ciudad" value="" placeholder="Escriba la ciudad de su dirección" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="txt_direccion_estado" class="form-label form-label-sm">Provincia <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_direccion_estado" id="txt_direccion_estado" value="" placeholder="Escriba la provincia de su dirección" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="txt_direccion_postal" class="form-label form-label-sm">Código Postal <label style="color: red;">*</label></label>
                                <div class="row">
                                    <div class="col-11 me-0">
                                        <input type="text" class="form-control form-control-sm" name="txt_direccion_postal" id="txt_direccion_postal" placeholder="Escriba su código postal o de click en 'Obtener'">
                                    </div>
                                    <div class="col-11 me-0" style="display: none;">
                                        <a id="ubicacion" target="_blank"></a>
                                    </div>
                                    <div class="col-1 d-flex justify-content-start">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="obtener_codigo_postal();">Obtener</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_informacion_contacto" onclick="insertar_editar_informacion_adicional();">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    //Validacion de formulario
    $(document).ready(function() {
        //Validación Información Adicional
        $("#form_informacion_adicional").validate({
            rules: {
                txt_direccion_calle: {
                    required: true,
                },
                txt_direccion_numero: {
                    required: true,
                },
                txt_direccion_ciudad: {
                    required: true,
                },
                txt_direccion_estado: {
                    required: true,
                },
                txt_direccion_postal: {
                    required: true,
                },
            },
            messages: {
                txt_direccion_calle: {
                    required: "Por favor ingrese la calle de su dirección",
                },
                txt_direccion_numero: {
                    required: "Por favor ingrese el número de su dirección",
                },
                txt_direccion_ciudad: {
                    required: "Por favor ingrese la ciudad en la que reside",
                },
                txt_direccion_estado: {
                    required: "Por favor ingrese la provincia en la que reside",
                },
                txt_direccion_postal: {
                    required: "Por favor ingrese su código postal o de click en 'Obtener'",
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
</script>