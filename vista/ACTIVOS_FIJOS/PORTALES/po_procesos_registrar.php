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
            datos_col(<?= $_id ?>);
        <?php } ?>

    });

    function datos_col(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/ACTIVOS_FIJOS/PORTALES/po_procesosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                $('#txt_nivel').val(response[0].nivel);
                $('#txt_TP').val(response[0].TP);
                $('#txt_proceso').val(response[0].proceso);
                $('#txt_DC').val(response[0].DC);
                $('#txt_cmds').val(response[0].cmds);
                $('#txt_picture').val(response[0].picture);
                $('#txt_color').val(response[0].color);
                $('#txt_cta_costo').val(response[0].cta_costo);
                $('#txt_mi_cta').val(response[0].mi_cta);
            }
        });
    }

    function editar_insertar() {
        let txt_nivel = $('#txt_nivel').val();
        let txt_TP = $('#txt_TP').val();
        let txt_proceso = $('#txt_proceso').val();
        let txt_DC = $('#txt_DC').val();
        let txt_cmds = $('#txt_cmds').val();
        let txt_picture = $('#txt_picture').val();
        let txt_color = $('#txt_color').val();
        let txt_cta_costo = $('#txt_cta_costo').val();
        let txt_mi_cta = $('#txt_mi_cta').val();

        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_nivel': txt_nivel,
            'txt_TP': txt_TP,
            'txt_proceso': txt_proceso,
            'txt_DC': txt_DC,
            'txt_cmds': txt_cmds,
            'txt_picture': txt_picture,
            'txt_color': txt_color,
            'txt_cta_costo': txt_cta_costo,
            'txt_mi_cta': txt_mi_cta,
        };

        if ($("#form_procesos").valid()) {
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
            url: '../controlador/ACTIVOS_FIJOS/PORTALES/po_procesosC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=po_procesos';
                    });
                } else if (response == -2) {
                    //Swal.fire('', 'El nombre del dispositivo ya está en uso', 'warning');
                    $(txt_proceso).addClass('is-invalid');
                    $('#error_txt_proceso').text('El nombre ya está en uso.');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_proceso').on('input', function() {
            $('#error_txt_proceso').text('');
        });
    }

    function delete_datos() {
        var id = '<?= $_id ?>';
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {
                eliminar(id);
            }
        })
    }

    function eliminar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/ACTIVOS_FIJOS/PORTALES/po_procesosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=po_procesos';
                    });
                }
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Procesos</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar Proceso
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
                                    echo 'Registrar Proceso';
                                } else {
                                    echo 'Modificar Proceso';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=po_procesos" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_procesos">

                            <div class="row pt-3 mb-col">
                                <div class="col-md-6">
                                    <label for="txt_proceso" class="form-label">Proceso </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_proceso" name="txt_proceso" maxlength="50">
                                    <span id="error_txt_proceso" class="text-danger"></span>
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_TP" class="form-label">Tipo de Proceso </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_TP" name="txt_TP" maxlength="50">
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <label for="txt_picture" class="form-label">Imagen </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_picture" name="txt_picture" maxlength="50">
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_color" class="form-label">Color </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_color" name="txt_color" maxlength="50">
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-4">
                                    <label for="txt_nivel" class="form-label">Nivel </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_nivel" name="txt_nivel" maxlength="50">
                                </div>

                                <div class="col-md-4">
                                    <label for="txt_cta_costo" class="form-label">Cuenta Costo </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_cta_costo" name="txt_cta_costo" maxlength="50">
                                </div>

                                <div class="col-md-4">
                                    <label for="txt_mi_cta" class="form-label">Mi Cuenta </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_mi_cta" name="txt_mi_cta" maxlength="50">
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <label for="txt_DC" class="form-label">DC </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_DC" name="txt_DC" maxlength="50">
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_cmds" class="form-label">Comandos </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_cmds" name="txt_cmds" maxlength="50">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-2">

                                <?php if ($_id == '') { ?>
                                    <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Editar</button>
                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
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

<script>
    //Validacion de formulario
    $(document).ready(function() {
        // Selecciona el label existente y añade el nuevo label

        agregar_asterisco_campo_obligatorio('txt_nivel');
        agregar_asterisco_campo_obligatorio('txt_TP');
        agregar_asterisco_campo_obligatorio('txt_proceso');
        agregar_asterisco_campo_obligatorio('txt_DC');
        agregar_asterisco_campo_obligatorio('txt_cmds');
        agregar_asterisco_campo_obligatorio('txt_picture');
        agregar_asterisco_campo_obligatorio('txt_color');
        agregar_asterisco_campo_obligatorio('txt_cta_costo');
        agregar_asterisco_campo_obligatorio('txt_mi_cta');

        $("#form_procesos").validate({
            rules: {
                txt_nivel: {
                    required: true,
                },
                txt_TP: {
                    required: true,
                },
                txt_proceso: {
                    required: true,
                },
                txt_DC: {
                    required: true,
                },
                txt_cmds: {
                    required: true,
                },
                txt_picture: {
                    required: true,
                },
                txt_color: {
                    required: true,
                },
                txt_cta_costo: {
                    required: true,
                },
                txt_mi_cta: {
                    required: true,
                },
            },

            highlight: function(element) {
                // Agrega la clase 'is-invalid' al input que falla la validación
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                // Elimina la clase 'is-invalid' si la validación pasa
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');

            }
        });
    });
</script>