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
        cargar_selects2();
    });

    function cargar_selects2() {
        url_personasC = '../controlador/TALENTO_HUMANO/th_personasC.php?buscar=true';
        cargar_select2_url('ddl_personas', url_personasC);
    }

    function editar_insertar() {
        var ddl_personas = $('#ddl_personas').val();
        var txt_fecha_hora = $('#txt_fecha_hora').val();
        var txt_descripcion = $('#txt_descripcion').val();

        var parametros = {
            '_id': '<?= $_id ?>',
            'ddl_personas': ddl_personas,
            'txt_fecha_hora': txt_fecha_hora,
            'txt_descripcion': txt_descripcion,
        };

        if ($("#form_marcacion_manual").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            insertar(parametros);
        }
        //console.log(parametros);

    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_control_acceso_temporalC.php?insertar_manual=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_marcaciones_web';
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Error.', 'warning');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Marcaciones</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar Marcación Manual
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
                                    echo 'Registrar Marcación Manual';
                                } else {
                                    echo 'Modificar Marcación Manual';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_marcaciones_web" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <form id="form_marcacion_manual">

                            <div class="row pt-3 mb-col">
                                <div class="col-md-6">
                                    <label for="ddl_personas" class="form-label">Personas </label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_personas" name="ddl_personas">
                                        <option selected disabled>-- Seleccione --</option>
                                    </select>
                                    <label class="error" style="display: none;" for="ddl_personas"></label>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <label for="txt_fecha_hora" class="form-label">Marcación </label>
                                    <input type="datetime-local" class="form-control form-control-sm" id="txt_fecha_hora" name="txt_fecha_hora">
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <label for="txt_descripcion" class="form-label">Descripción </label>
                                    <textarea class="form-control form-control-sm no_caracteres" name="txt_descripcion" id="txt_descripcion" rows="3" maxlength="200"></textarea>

                                </div>
                            </div>

                            <div class="d-flex justify-content-start pt-2">
                                <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Registrar</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<script>
    //Validacion de formulario
    $(document).ready(function() {
        // Selecciona el label existente y añade el nuevo label

        agregar_asterisco_campo_obligatorio('ddl_personas');
        agregar_asterisco_campo_obligatorio('txt_fecha_hora');
        agregar_asterisco_campo_obligatorio('txt_descripcion');

        //Para validar los select2
        $(".select2-validation").on("select2:select", function(e) {
            unhighlight_select(this);
        });

        $("#form_marcacion_manual").validate({
            rules: {
                ddl_personas: {
                    required: true,
                },
                txt_fecha_hora: {
                    required: true,
                },
                txt_descripcion: {
                    required: true,
                },
            },

            highlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al contenedor correcto de select2
                    $element.next(".select2-container").find(".select2-selection").removeClass("is-valid").addClass("is-invalid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, aplicar la clase al grupo de radios (al contenedor padre si existe)
                    $('input[name="' + $element.attr("name") + '"]').addClass("is-invalid").removeClass("is-valid");
                } else {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al input normal
                    $element.removeClass("is-valid").addClass("is-invalid");
                }
            },

            unhighlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Para Select2, elimina 'is-invalid' y agrega 'is-valid' en el contenedor adecuado
                    $element.next(".select2-container").find(".select2-selection").removeClass("is-invalid").addClass("is-valid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, marcar todo el grupo como válido
                    $('input[name="' + $element.attr("name") + '"]').removeClass("is-invalid").addClass("is-valid");
                } else {
                    // Para otros elementos normales
                    $element.removeClass("is-invalid").addClass("is-valid");
                }
            }

        });
    });
</script>