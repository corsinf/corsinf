<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


$id_postulante = '';
$id_persona = '';

if (isset($_GET['id_postulante'])) {
    $id_postulante = $_GET['id_postulante'];
}

if (isset($_GET['id_persona'])) {
    $id_persona = $_GET['id_persona'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
    $(document).ready(function() {
        <?php if (isset($_GET['id_postulante'])) { ?>
            cargarDatos('<?= $id_postulante ?>', '<?= $id_persona ?>');
        <?php } ?>
    })


    function recargar_imagen(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#img_postulante_inf').attr('src', response[0].th_pos_foto_url + '?' + Math.random());
            }
        });
    }
</script>


<!-- Vista de la página -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Postulante</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Información personal</li>
                    </ol>
                </nav>
            </div>
            <div class="row m-2">
                <div class="col-sm-12">
                    <button onclick="boton_regresar_js();" class="btn btn-outline-dark btn-sm d-flex align-items-center"><i class="bx bx-arrow-back"></i>Regresar</button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container-fluid">
            <div class="main-body">
                <div class="row d-flex justify-content-center">
                    <!-- Cards de la izquierda -->
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-4 col-xxl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="align-items-center">
                                    <!-- Cambiar Foto -->
                                    <div class="text-center">
                                        <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_cambiar_foto.php'); ?>

                                        <div class="position-relative">

                                            <div class="widget-user-image text-center">
                                                <img class="rounded-circle p-1 bg-primary" src="../img/sin_imagen.jpg"
                                                    class="img-fluid" id="img_postulante_inf"
                                                    alt="Imagen Perfil Postulante" width="110" height="110" />
                                            </div>

                                            <?php if (isset($_GET['id_persona'])) { ?>
                                            <?php } else { ?>
                                                <div>
                                                    <a href="#" class="d-flex justify-content-center" data-bs-toggle="modal"
                                                        data-bs-target="#modal_agregar_cambiar_foto"
                                                        onclick="abrir_modal_cambiar_foto('<?= $id_postulante ?>');">
                                                        <i class='bx bxs-camera bx-sm'></i>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <!-- Información Personal -->
                                    <div class="mt-3 bg-light rounded-3 p-3 shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-info-circle fs-5 text-primary me-2"></i>
                                                <h6 class="fw-bold mb-0 text-primary">Información Personal</h6>
                                            </div>

                                            <?php if (isset($_GET['id_persona'])) { ?>
                                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_personas&id_persona=<?= $id_persona ?>&id_postulante=<?= $id_postulante ?>"
                                                    class="text-success" title="Editar persona"><i class="bx bx-pencil bx-sm"></i>
                                                </a>

                                            <?php } else { ?>
                                                <a href="#" class="text-secondary" data-bs-toggle="modal" data-bs-target="#modal_informacion_personal">
                                                    <i class="bx bx-pencil bx-sm"></i>
                                                </a>
                                            <?php } ?>
                                        </div>

                                        <div class="d-flex flex-column gap-3">

                                            <div class="d-flex align-items-center border-bottom pb-2">
                                                <i class="bx bx-id-card text-primary fs-5 me-3"></i>
                                                <span id="txt_nombres_completos_v" class="fw-semibold text-dark"></span>
                                            </div>

                                            <div class="d-flex align-items-center border-bottom pb-2">
                                                <i class="bx bx-calendar text-primary fs-5 me-3"></i>
                                                <span id="txt_fecha_nacimiento_v" class="text-dark"></span>
                                            </div>

                                            <div class="d-flex align-items-center border-bottom pb-2">
                                                <i class="bx bx-flag text-primary fs-5 me-3"></i>
                                                <span id="txt_nacionalidad_v" class="text-dark"></span>
                                            </div>

                                            <div class="d-flex align-items-center border-bottom pb-2">
                                                <i class="bx bx-id-card text-primary fs-5 me-3"></i>
                                                <span id="txt_numero_cedula_v" class="text-dark"></span>
                                            </div>

                                            <div class="d-flex align-items-center border-bottom pb-2">
                                                <i class="bx bx-heart text-primary fs-5 me-3"></i>
                                                <span id="txt_estado_civil_v" class="text-dark"></span>
                                            </div>

                                            <div class="d-flex align-items-center border-bottom pb-2">
                                                <i class="bx bx-phone text-primary fs-5 me-3"></i>
                                                <span id="txt_telefono_1_v" class="text-dark"></span>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-envelope text-primary fs-5 me-3"></i>
                                                <span id="txt_correo_v" class="text-dark text-break"></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php /*
<div class="card">
    <!-- Información Adicional y Contacto de Emergencia -->
    <div class="card-body">
        <div class="align-items-center">
            <div class="mt-3">

                <div class="row">
                    <div class="col-10">
                        <h5 class="fw-bold text-primary">Contacto de Emergencia</h5>
                    </div>
                    <div class="col-2">
                        <a href="#" class="text-dark icon-hover" data-bs-toggle="modal"
                            data-bs-target="#modal_contacto_emergencia">
                            <i class='bx bx-show bx-sm'></i>
                        </a>
                    </div>
                </div>

                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_contacto_emergencia.php'); ?>

            </div>
        </div>
    </div>
</div>
*/ ?>

                    </div>

                    <!-- Cards de la derecha -->
                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-8 col-xxl-8">
                        <div class="card-body">
                            <!-- Nav Cards -->
                            <ul class="nav nav-tabs nav-success" role="tablist">

                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_all_tab.php'); ?>

                            </ul>
                            <div class="tab-content pt-3">

                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_all_tab_pane.php'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para la informacion personal -->
<div class="modal modal_general" id="modal_informacion_personal" tabindex="-1" aria-modal="true" role="dialog"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold">Información Personal</small></h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_formulario_registro.php'); ?>

                <div class="d-flex justify-content-end pt-2">
                    <?php if ($id_postulante == '') { ?>
                        <button class="btn btn-primary btn-sm px-4 m-0 d-flex align-items-center" onclick="insertar_editar('th_informacion_personal');" type="button"><i class="bx bx-save"></i> Guardar</button>
                    <?php } else { ?>
                        <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center" onclick="insertar_editar('th_informacion_personal');" type="button"><i class="bx bx-save"></i> Guardar</button>
                        <button class="btn btn-danger btn-sm px-4 m-1 d-flex align-items-center" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //Validacion de formulario
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_primer_apellido');
        agregar_asterisco_campo_obligatorio('txt_segundo_apellido');
        agregar_asterisco_campo_obligatorio('txt_observaciones');
        agregar_asterisco_campo_obligatorio('txt_primer_nombre');
        agregar_asterisco_campo_obligatorio('txt_segundo_nombre');
        agregar_asterisco_campo_obligatorio('txt_cedula');
        agregar_asterisco_campo_obligatorio('ddl_sexo');
        agregar_asterisco_campo_obligatorio('txt_fecha_nacimiento');
        agregar_asterisco_campo_obligatorio('txt_edad');
        agregar_asterisco_campo_obligatorio('txt_telefono_1');
        agregar_asterisco_campo_obligatorio('txt_telefono_2');
        agregar_asterisco_campo_obligatorio('txt_correo');
        agregar_asterisco_campo_obligatorio('ddl_nacionalidad');
        agregar_asterisco_campo_obligatorio('ddl_estado_civil');
        agregar_asterisco_campo_obligatorio('ddl_provincias');
        agregar_asterisco_campo_obligatorio('ddl_ciudad');
        agregar_asterisco_campo_obligatorio('ddl_parroquia');
        agregar_asterisco_campo_obligatorio('txt_codigo_postal');
        agregar_asterisco_campo_obligatorio('txt_direccion');
        //Validación Información Personal
        $("#form_informacion_personal").validate({
            rules: {
                txt_primer_apellido: {
                    required: true,
                },
                txt_segundo_apellido: {
                    required: true,
                },
                txt_primer_nombre: {
                    required: true,
                },
                txt_segundo_nombre: {
                    required: true,
                },
                txt_cedula: {
                    required: true,
                },
                ddl_sexo: {
                    required: true,
                },
                txt_fecha_nacimiento: {
                    required: true,
                },
                txt_edad: {
                    required: true,
                },
                txt_telefono_1: {
                    required: true,
                },
                txt_telefono_2: {
                    required: true,
                },
                txt_correo: {
                    required: true,
                },
                ddl_nacionalidad: {
                    required: true,
                },
                ddl_estado_civil: {
                    required: true,
                },
            },
            messages: {
                txt_primer_apellido: {
                    required: "Por favor ingrese el primer apellido",
                },
                txt_segundo_apellido: {
                    required: "Por favor ingrese el segundo apellido",
                },
                txt_primer_nombre: {
                    required: "Por favor ingrese el primer nombre",
                },
                txt_segundo_nombre: {
                    required: "Por favor ingrese el segundo nombre",
                },
                txt_cedula: {
                    required: "Por favor ingresa un número de cédula",
                },
                ddl_sexo: {
                    required: "Por favor seleccione el sexo",
                },
                txt_fecha_nacimiento: {
                    required: "Por favor ingrese la fecha de nacimiento",
                },
                txt_edad: {
                    required: "Por favor ingrese la edad (fecha de nacimiento)",
                },
                txt_telefono_1: {
                    required: "Por favor ingrese el primero teléfono",
                },
                txt_telefono_2: {
                    required: "Por favor ingrese el segundo teléfono",
                },
                txt_correo: {
                    required: "Por favor ingrese un correo",
                },
                ddl_nacionalidad: {
                    required: "Por favor seleccione su nacionalidad",
                },
                ddl_estado_civil: {
                    required: "Por favor seleccione su estado civil",
                },
            }
        });
    });
</script>