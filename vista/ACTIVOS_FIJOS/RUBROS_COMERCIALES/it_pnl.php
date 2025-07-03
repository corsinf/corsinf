<script>
    $(document).ready(function() {});

    function guarda_detalles_it() {
        // Recojo los valores uno a uno
        $('#cbx_detalle_it').prop('checked', true).prop('disabled', true);
        let formData = new FormData($('#form_detalle_it')[0]);
        $.ajax({
            url: '../controlador/ACTIVOS_FIJOS/ac_articulo_itC.php?guardar=true',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        vista_pnl();
                        cargar_datos_articulo(<?= $_id ?>);
                        cargar_tabla_movimientos();

                        limpiar_parametros_articulo();
                        cargar_articulo_detalles_it_vista(<?= $_id ?>);
                    });
                } else {
                    Swal.fire('Error', 'No se pudo guardar.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Fallo en la comunicación con el servidor.', 'error');
            }
        });
    }

    function mostrar_datos_articulo_detaller_it(response) {
        if (response.length != 0) {
            $('#txt_sistema_op').val(response[0].sistema_op);
            $('#txt_arquitectura').val(response[0].arquitectura);
            $('#txt_kernel').val(response[0].kernel);
            $('#txt_producto_id').val(response[0].producto_id);
            $('#txt_mac_address').val(response[0].mac_address);
            $('#txt_version').val(response[0].version);
            $('#txt_service_pack').val(response[0].service_pack);
            $('#txt_edicion').val(response[0].edicion);
            $('#txt_serie_numbre').val(response[0].serie_numero);
            $('#txt_ip_address').val(response[0].ip_address);
            $('#txt_id_articulo_IT').val(response[0]._id);
        }
    }
</script>

<div class="tab-pane fade" id="tab_detalle_it" role="tabpanel">

    <form id="form_detalle_it">
        <input type="hidden" id="txt_id_articulo_IT" name="txt_id_articulo_IT" value="" />
        <input type="hidden" id="txt_id_articulo" name="txt_id_articulo" value="" />
        <input type="hidden" id="txt_ac_ait_sku" name="txt_ac_ait_sku" value="" />

        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-sm-6">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Sistema Op</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_sistema_op" name="txt_sistema_op" placeholder="Server 2016">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Arquitectura</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_arquitectura" name="txt_arquitectura" placeholder="64 bit / 32 bit">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Kernel</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_kernel" name="txt_kernel" placeholder="10.0">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Producto ID</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_producto_id" name="txt_producto_id" placeholder="0000-000-0000">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Mac address</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_mac_address" name="txt_mac_address" placeholder="00-00-00-00-00">
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-sm-6">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Versión</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_version" name="txt_version" placeholder="1.0">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Service pack</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_service_pack" name="txt_service_pack" placeholder="Service Pack 1">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Edición</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_edicion" name="txt_edicion" placeholder="">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Serie número</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_serie_numbre" name="txt_serie_numbre" placeholder="000-00000">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">IP address</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_ip_address" name="txt_ip_address" placeholder="192.168.1.1">
                    </div>
                </div>
            </div>

            <!-- Botón -->
            <div class="col-sm-12 text-end mt-2">
                <button class="btn btn-primary btn-sm" type="button" onclick="guarda_detalles_it()">Guardar</button>
            </div>
        </div>
    </form>

</div>
<script>
    $(document).ready(function() {
        $("#form_detalle_it").validate({
            rules: {
                txt_sistema_op: {
                    required: true,
                },
                txt_arquitectura: {
                    required: true,
                },
                txt_kernel: {
                    required: true,
                },
                txt_producto_id: {
                    required: true,
                },
                txt_mac_address: {
                    required: true,
                },
                txt_version: {
                    required: true,
                },
                txt_service_pack: {
                    required: true,
                },
                txt_edicion: {
                    required: true,
                },
                txt_serie_numbre: {
                    required: true,
                },
                txt_ip_address: {
                    required: true,
                }
            },

            messages: {
                txt_sistema_op: {
                    required: "Ingrese el Sistema Operativo.",
                },
                txt_arquitectura: {
                    required: "Ingrese la arquitectura.",
                },
                txt_kernel: {
                    required: "Ingrese la versión del kernel.",
                },
                txt_producto_id: {
                    required: "Ingrese el ID del producto.",
                },
                txt_mac_address: {
                    required: "Ingrese la dirección MAC.",
                },
                txt_version: {
                    required: "Ingrese la versión.",
                },
                txt_service_pack: {
                    required: "Ingrese el service pack.",
                },
                txt_edicion: {
                    required: "Ingrese la edición.",
                },
                txt_serie_numbre: {
                    required: "Ingrese el número de serie.",
                },
                txt_ip_address: {
                    required: "Ingrese la dirección IP.",
                }
            },

            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            submitHandler: function(form) {
                guarda_detalles_it(); // llama a tu función si es válida
            }
        });

    });
</script>