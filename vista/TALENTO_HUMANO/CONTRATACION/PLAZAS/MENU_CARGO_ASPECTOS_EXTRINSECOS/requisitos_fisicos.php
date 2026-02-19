<script>
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            cargar_cargo_reqf_fisicos(<?= 1 ?>);
        <?php } ?>

        function cargar_cargo_reqf_fisicos(id) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CARGOS/th_cargo_reqf_fisicosC.php?listar_modal=true',
                type: 'post',
                data: {
                    id: id,
                    button_delete: false
                },
                dataType: 'json',
                success: function(response) {
                    $('#pnl_cargo_requerimientos_fisicos').hide().html(response).fadeIn(400);
                }
            });
        }

    });
</script>

<div class="container-fluid">
    <div class="mb-5">
        <div class="row mb-3">
            <div class="col-6 d-flex align-items-center">
                <h4><i class="bx bx-body me-2"></i>Requerimientos Físicos</h4>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="#" class="text-success icon-hover d-flex align-items-center"
                    data-bs-toggle="modal" data-bs-target="#modal_reqf_fisico">
                    <i class='bx bx-plus-circle bx-sm me-1'></i>
                    <span>Agregar</span>
                </a>
            </div>
            <?php include_once('../vista/TALENTO_HUMANO/CARGOS/MENU_ASPECTOS_EXTRINSECOS/REQUISITOS_FISICOS/requisitos_fisicos.php'); ?>
            <div id="pnl_cargo_requerimientos_fisicos">
            </div>
        </div>
    </div>
</div>