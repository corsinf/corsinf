<script>
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            cargar_cargo_instrucciones_basicas(<?= 1 ?>);
            cargar_cargo_experiencia_necesaria(<?= 1 ?>);
            cargar_cargo_aptitudes(<?= 1 ?>);
            cargar_cargo_idiomas(<?= 1 ?>);
            cargar_cargo_iniciativas(<?= 1 ?>);
        <?php } ?>

        function cargar_cargo_instrucciones_basicas(id) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_instruccionC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: false
                },
                dataType: 'json',
                success: function(response) {
                    $('#pnl_cargo_instruccion_basica').hide().html(response).fadeIn(400);
                }
            });
        }

        function cargar_cargo_experiencia_necesaria(id) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_experienciaC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: false
                },
                dataType: 'json',
                success: function(response) {

                    $('#pnl_cargo_experiencia_necesaria').hide().html(response.html).fadeIn(400);

                }
            });
        }

        function cargar_cargo_aptitudes(id) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_aptitudC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: false
                },
                dataType: 'json',
                success: function(response) {
                    $('#pnl_cargo_aptitudes_necesarias').hide().html(response).fadeIn(400);
                }
            });
        }

        function cargar_cargo_idiomas(id) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_idiomasC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: false
                },
                dataType: 'json',
                success: function(response) {
                    $('#pnl_cargo_idiomas').hide().html(response).fadeIn(400);
                }
            });
        }

        function cargar_cargo_iniciativas(id) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqi_iniciativaC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: false
                },
                dataType: 'json',
                success: function(response) {
                    $('#pnl_cargo_iniciativa_necesaria').hide().html(response.html).fadeIn(400);

                }
            });
        }
    });
</script>

<div class="container-fluid">
    <!-- SECCIÓN 1: INSTRUCCIÓN BÁSICA -->
    <div class="mb-5">
        <div class="row mb-3">
            <div class="col-6 d-flex align-items-center">
                <h4><i class="bx bx-briefcase me-2"></i> Instrucción Básica Necesaria</h4>
            </div>
        </div>
        <div id="pnl_cargo_instruccion_basica">
        </div>
    </div>

    <hr class="my-4">

    <!-- SECCIÓN 2: EXPERIENCIA NECESARIA -->
    <div class="mb-5">
        <div class="row mb-3">
            <div class="col-6 d-flex align-items-center">
                <h4><i class="bx bx-briefcase me-2"></i>Experiencia Necesaria</h4>
            </div>
        </div>
        <div id="pnl_cargo_experiencia_necesaria">
        </div>
    </div>

    <hr class="my-4">

    <!-- SECCIÓN 3: Idiomas -->
    <div class="mb-5">
        <div class="row mb-3">
            <div class="col-6 d-flex align-items-center">
                <h4><i class="bx bx-bulb me-2"></i>Idiomas</h4>
            </div>
        </div>
        <div id="pnl_cargo_idiomas">
        </div>
    </div>

    <hr class="my-4">

    <!-- SECCIÓN 4: APTITUDES NECESARIAS -->
    <div class="mb-5">
        <div class="row mb-3">
            <div class="col-6 d-flex align-items-center">
                <h4><i class="bx bx-star me-2"></i>Aptitudes Necesarias</h4>
            </div>
        </div>
        <div id="pnl_cargo_aptitudes_necesarias">
        </div>
    </div>
    <hr class="my-4">

    <!-- SECCIÓN 4: Iniciativa Necesaria -->
    <div class="mb-5">
        <div class="row mb-3">
            <div class="col-6 d-flex align-items-center">
                <h4><i class="bx bx-bulb me-2"></i>Iniciativa Necesaria</h4>
            </div>
        </div>
        <div id="pnl_cargo_iniciativa_necesaria">
        </div>
    </div>
</div>