<script>
    $(document).ready(function() {
        // lista_kit();
    });

    function guarda_detalles_it() {
        datos = $('#form_detalle_it').serialize();
        datos = datos + '&id=' + $('#txt_id').val();
        $.ajax({
            data: datos,
            url: '../controlador/ACTIVOS_FIJOS/articulosC.php?guardar_it=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Detalles de IT registrados', '', 'success');
                }
                // console.log(response);                      
            }
        });
    }
</script>

<div class="tab-pane fade" id="tab_detalle_it" role="tabpanel">

    <form id="form_detalle_it">
        <div class="row">
            <div class="col-sm-6">
                <div class="row mb-3">
                    <label for="inputEnterYourName" class="col-sm-4 col-form-label">Sistema Op </label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_sistema_op" name="txt_sistema_op" placeholder="Server 2016">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputEnterYourName" class="col-sm-4 col-form-label">Arquitectura</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_arquitectura" name="txt_arquitectura" placeholder="64 bit / 32 bit">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputEnterYourName" class="col-sm-3 col-form-label">Kernel</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="txt_kernel" name="txt_kernel" placeholder="10.0">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputEnterYourName" class="col-sm-4 col-form-label">Producto ID</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_producto_id" name="txt_producto_id" placeholder="0000-000-0000">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row mb-3">
                    <label for="inputEnterYourName" class="col-sm-3 col-form-label">Version</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="txt_version" name="txt_version" placeholder="1.0">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputEnterYourName" class="col-sm-4 col-form-label">Service pack</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_service_pack" name="txt_service_pack" placeholder="service pack1">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputEnterYourName" class="col-sm-3 col-form-label">Edicion</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="txt_edicion" name="txt_edicion" placeholder="">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputEnterYourName" class="col-sm-4 col-form-label">Serie numero</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="txt_serie_numbre" name="txt_serie_numbre" placeholder="000-00000">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 text-end">
                <button class="btn btn-primary btn-sm" type="button" onclick="guarda_detalles_it()">Guardar</button>
            </div>
        </div>
    </form>

</div>