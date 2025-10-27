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
            cargar_ubicacion(<?= $_id ?>);
        <?php } ?>


        function cargar_ubicacion(id) {
            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/XPACE_CUBE/ubicacionesC.php?listar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response);
                    $('#txt_nombre').val(response[0].nombre);
                    $('#txt_direccion').val(response[0].direccion);
                    $('#txt_ciudad').val(response[0].ciudad);
                    $('#txt_telefono').val(response[0].telefono);
                }
            });

        }
    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Ubicaciones</div>
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
                                    echo 'Registrar Ubicación';
                                } else {
                                    echo 'Modificar Ubicación';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_ubicaciones" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_espacios">
                            <div class="row g-3">
                                <!-- Nombre -->
                                <div class="col-md-4">
                                    <label for="txt_nombre" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-user me-2 text-primary fs-5"></i> Nombre
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_nombre"
                                        name="txt_nombre"
                                        placeholder="Ingrese el nombre"
                                        autocomplete="off" />
                                </div>

                                <!-- Dirección -->
                                <div class="col-md-4">
                                    <label for="txt_direccion" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-home me-2 text-success fs-5"></i> Dirección
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_direccion"
                                        name="txt_direccion"
                                        placeholder="Ingrese la dirección"
                                        autocomplete="off" />
                                </div>

                                <!-- Ciudad -->
                                <div class="col-md-4">
                                    <label for="txt_ciudad" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-building me-2 text-warning fs-5"></i> Ciudad
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_ciudad"
                                        name="txt_ciudad"
                                        placeholder="Ingrese la ciudad"
                                        autocomplete="off" />
                                </div>

                                <!-- Teléfono -->
                                <div class="col-md-4">
                                    <label for="txt_telefono" class="form-label fw-bold d-flex align-items-center">
                                        <i class="bx bx-phone me-2 text-info fs-5"></i> Teléfono
                                    </label>
                                    <input type="text"
                                        class="form-control form-control-sm no_caracteres"
                                        id="txt_telefono"
                                        name="txt_telefono"
                                        placeholder="Ingrese el teléfono"
                                        autocomplete="off" />
                                </div>
                            </div>


                            <div class="mt-4 text-end">
                                <?php if ($_id == '') { ?>
                                    <button type="button" class="btn btn-success btn-sm px-4 m-0" id="btn_guardar" onclick=""><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar" onclick=""><i class="bx bx-save"></i> Editar</button>
                                    <button type="button" class="btn btn-danger btn-sm px-4 m-1" id="" onclick=""><i class="bx bx-trash"></i> Eliminar</button>
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