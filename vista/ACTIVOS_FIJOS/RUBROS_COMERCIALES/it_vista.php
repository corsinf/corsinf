<script>
    $(document).ready(function() {
        // Obtiene todos los parámetros de la query string
        let params = new URLSearchParams(window.location.search);
        // Lee el valor de "_id"
        let id = params.get('_id');
        cargar_articulo_detalles_it_vista(id);

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
               cargar_articulo_detalle_it(response)
            },
            error: function() {
                Swal.fire('Error', 'Fallo en la comunicación con el servidor.', 'error');
            }
        });
    } 

    function cargar_articulo_detalle_it(data){
        if (data.length != 0) {
            $('#lbl_sistema_op').text(data[0].sistema_op);
            $('#lbl_arquitectura').text(data[0].arquitectura);
            $('#lbl_kernel').text(data[0].kernel);
            $('#lbl_producto_id').text(data[0].producto_id);
            $('#lbl_mac_address').text(data[0].mac_address);
            $('#lbl_version').text(data[0].version);
            $('#lbl_service_pack').text(data[0].service_pack);
            $('#lbl_edicion').text(data[0].edicion);
            $('#lbl_serie_numbre').text(data[0].serie_numero);
            $('#lbl_ip_address').text(data[0].ip_address);
        } 

    }
</script>

<div id="detalle_it" style="display:block">
    <hr>
    <h5 class="fw-bold">Detalles IT - Completar!</h5>
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