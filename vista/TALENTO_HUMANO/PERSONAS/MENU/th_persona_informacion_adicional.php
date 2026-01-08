<script>
    $(document).ready(function() {
        cargar_datos_info_adicional(<?= $id_postulante ?>);
    });
    function cargar_datos_info_adicional(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_informacion_adicionalC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_informacion_adicional').html(response);
            }
        });
    }
</script>
<div id="pnl_informacion_adicional">
</div>