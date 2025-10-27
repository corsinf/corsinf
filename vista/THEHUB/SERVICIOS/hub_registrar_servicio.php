<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            cargar_servicio(<?= $_id ?>);
        <?php } ?>


        function cargar_servicio(id) {
            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/XPACE_CUBE/serviciosC.php?listar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response);
                    $('#txt_nombre').val(response[0].nombre);
                    $('#txt_descripcion').val(response[0].descripcion);
                    $('#txt_precio_unitario').val(response[0].precio_unitario);
                }
            });

        }
    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Servicios</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Registros
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Servicio';
                                } else {
                                    echo 'Modificar Servicio';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_servicios" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_espacios">
                            <div class="row g-3">
                                <!-- Nombre -->
                                <div class="col-md-6">
                                    <label for="txt_nombre" class="form-label fw-bold">
                                        <i class="bx bx-edit-alt"></i> Nombre
                                    </label>
                                    <input type="text"
                                        class="form-control no_caracteres"
                                        id="txt_nombre"
                                        name="txt_nombre"
                                        placeholder="Ingrese el nombre">
                                </div>

                                <!-- Descripción -->
                                <div class="col-md-6">
                                    <label for="txt_descripcion" class="form-label fw-bold">
                                        <i class="bx bx-map"></i> Descripción
                                    </label>
                                    <input type="text"
                                        class="form-control no_caracteres"
                                        id="txt_descripcion"
                                        name="txt_descripcion"
                                        placeholder="Ingrese la descripción">
                                </div>

                                <!-- Precio Unitario -->
                                <div class="col-md-6">
                                    <label for="txt_precio_unitario" class="form-label fw-bold">
                                        <i class="bx bx-dollar"></i> Precio Unitario
                                    </label>
                                    <input type="number"
                                        class="form-control"
                                        id="txt_precio_unitario"
                                        name="txt_precio_unitario"
                                        placeholder="Ingrese el precio unitario"
                                        step="0.01" min="0">
                                </div>
                            </div>


                            <div class="mt-4 text-end border-top pt-3">
                                <?php if ($_id == '') { ?>
                                    <button type="button" class="btn btn-success px-4" id="btn_guardar" onclick="">
                                        <i class="bx bx-save"></i> Guardar
                                    </button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-primary px-4 me-2" id="btn_guardar" onclick="">
                                        <i class="bx bx-edit"></i> Editar
                                    </button>
                                    <button type="button" class="btn btn-danger px-4" id="" onclick="">
                                        <i class="bx bx-trash"></i> Eliminar
                                    </button>
                                <?php } ?>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>