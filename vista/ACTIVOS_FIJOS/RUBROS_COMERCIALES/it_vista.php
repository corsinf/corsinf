<script>
    $(document).ready(function() {
        cargar_articulo_detalles_it_vista(<?= $_id ?>);
    });

    function cargar_articulo_detalles_it_vista(id) {
        // Recojo los valores uno a uno
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/ACTIVOS_FIJOS/ac_articulo_itC.php?listar=true',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                mostrar_datos_articulo_it(response);
                mostrar_datos_articulo_detaller_it(response);

            },
            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function mostrar_datos_articulo_it(response) {
        if (response.length != 0) {
            $('#lbl_sistema_op').text(response[0].sistema_op);
            $('#lbl_arquitectura').text(response[0].arquitectura);
            $('#lbl_kernel').text(response[0].kernel);
            $('#lbl_producto_id').text(response[0].producto_id);
            $('#lbl_mac_address').text(response[0].mac_address);
            $('#lbl_version').text(response[0].version);
            $('#lbl_service_pack').text(response[0].service_pack);
            $('#lbl_edicion').text(response[0].edicion);
            $('#lbl_serie_numbre').text(response[0].serie_numero);
            $('#lbl_ip_address').text(response[0].ip_address);
        }
    }
</script>

<div id="detalle_it" style="display:block">
    <hr>
    <h5 class="fw-bold">Detalles IT</h5>
    <dl class="row">
        <dt class="col-sm-3">Sistema Operativo</dt>
        <dd class="col-sm-9" id="lbl_sistema_op"></dd>

        <dt class="col-sm-3">Arquitectura</dt>
        <dd class="col-sm-9" id="lbl_arquitectura"></dd>

        <dt class="col-sm-3">Kernel</dt>
        <dd class="col-sm-9" id="lbl_kernel"></dd>

        <dt class="col-sm-3">Producto ID</dt>
        <dd class="col-sm-9" id="lbl_producto_id"></dd>

        <dt class="col-sm-3">Versión</dt>
        <dd class="col-sm-9" id="lbl_version"></dd>

        <dt class="col-sm-3">Service Pack</dt>
        <dd class="col-sm-9" id="lbl_service_pack"></dd>

        <dt class="col-sm-3">Serie Numbre</dt>
        <dd class="col-sm-9" id="lbl_serie_numbre"></dd>

        <dt class="col-sm-3">Ip Address</dt>
        <dd class="col-sm-9" id="lbl_ip_address"></dd>

        <dt class="col-sm-3">Mac Address</dt>
        <dd class="col-sm-9" id="lbl_mac_address"></dd>
    </dl>
</div>