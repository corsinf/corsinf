<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


$id = '';
$id_persona = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

if (isset($_GET['id_persona'])) {
    $id_persona = $_GET['id_persona'];
}

$_id = '';

if (isset($_GET['id'])) {
    $_id = $_GET['id'];
}


?>
<!--
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        <?php if (isset($_GET['id'])) { ?>
            cargarDatos_informacion_personal('<?= $id ?>', '<?= $id_persona ?>');
        <?php } ?>

    });

    //Información Personal
    function cargarDatos_informacion_personal(id, id_persona = '') {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?listar=true',
            type: 'post',
            data: {
                id: id,
                id_persona: id_persona
            },
            dataType: 'json',
            success: function(response) {

                if (response.recargar == 1 && response.id_postulante) {
                    let nueva_Url = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_informacion_personal&id=${response.id_postulante}&id_persona=<?= $id_persona ?>`;

                    // Cambia la URL sin recargar
                    window.history.replaceState(null, '', nueva_Url);

                    // Recarga real solo una vez
                    location.reload();
                    return;
                }

                $('#txt_primer_nombre').val(response[0].th_pos_primer_nombre);
                $('#txt_segundo_nombre').val(response[0].th_pos_segundo_nombre);
                $('#txt_primer_apellido').val(response[0].th_pos_primer_apellido);
                $('#txt_segundo_apellido').val(response[0].th_pos_segundo_apellido);
                $('#txt_fecha_nacimiento').val(response[0].th_pos_fecha_nacimiento);
                $('#ddl_nacionalidad').val(response[0].th_pos_nacionalidad);
                $('#txt_cedula').val(response[0].th_pos_cedula);
                $('#ddl_estado_civil').val(response[0].th_pos_estado_civil);
                $('#ddl_sexo').val(response[0].th_pos_sexo);
                $('#txt_telefono_1').val(response[0].th_pos_telefono_1);
                $('#txt_telefono_2').val(response[0].th_pos_telefono_2);
                $('#txt_correo').val(response[0].th_pos_correo);
                $('#txt_codigo_postal').val(response[0].th_pos_postal);
                $('#txt_direccion').val(response[0].th_pos_direccion);
                calcular_edad('txt_edad', response[0].th_pos_fecha_nacimiento);
                //Cargar foto
                // $('#img_postulante_inf').attr('src', response[0].th_pos_foto_url + '?' + Math.random());
                $('#img_postulante_inf')
                    .off('error') // limpiar por si acaso
                    .one('error', function() {
                        console.log("Error 404");
                        $(this).attr('src', '../img/sin_imagen.jpg');
                    })
                    .attr('src', response[0].th_pos_foto_url + '?' + Math.random());

                //Cargar Selects de provincia-ciudad-parroquia
                url_provinciaC = '../controlador/GENERAL/th_provinciasC.php?listar=true';
                cargar_select2_con_id('ddl_provincias', url_provinciaC, response[0].th_prov_id,
                    'th_prov_nombre');

                url_ciudadC = '../controlador/GENERAL/th_ciudadC.php?listar=true';
                cargar_select2_con_id('ddl_ciudad', url_ciudadC, response[0].th_ciu_id, 'th_ciu_nombre');

                url_parroquiaC = '../controlador/GENERAL/th_parroquiasC.php?listar=true';
                cargar_select2_con_id('ddl_parroquia', url_parroquiaC, response[0].th_parr_id,
                    'th_parr_nombre');


                nombres_completos = response[0].th_pos_primer_apellido + ' ' + response[0]
                    .th_pos_segundo_apellido + ' ' + response[0].th_pos_primer_nombre + ' ' + response[0]
                    .th_pos_segundo_nombre;
                $('#txt_nombres_completos_v').html(nombres_completos);
                $('#txt_fecha_nacimiento_v').html(response[0].th_pos_fecha_nacimiento);
                $('#txt_nacionalidad_v').html(response[0].th_pos_nacionalidad);
                $('#txt_estado_civil_v').html(response[0].th_pos_estado_civil);
                $('#txt_numero_cedula_v').html(response[0].th_pos_cedula);
                $('#txt_telefono_1_v').html(response[0].th_pos_telefono_1);
                $('#txt_correo_v').html(response[0].th_pos_correo);

                //Input para todos los pos_id que se vayan a colocar en los modales
                $('input[name="txt_postulante_id"]').val(response[0]._id);
                $('input[name="txt_postulante_cedula"]').val(response[0].th_pos_cedula);

                //console.log(response);

            }
        });
    }

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

    function insertar_editar_informacion_personal() {

        var txt_primer_nombre = $('#txt_primer_nombre').val();
        var txt_segundo_nombre = $('#txt_segundo_nombre').val();
        var txt_primer_apellido = $('#txt_primer_apellido').val();
        var txt_segundo_apellido = $('#txt_segundo_apellido').val();
        var txt_fecha_nacimiento = $('#txt_fecha_nacimiento').val();
        var ddl_nacionalidad = $('#ddl_nacionalidad').val();
        var txt_cedula = $('#txt_cedula').val();
        var ddl_estado_civil = $('#ddl_estado_civil').val();
        var ddl_sexo = $('#ddl_sexo').val();
        var txt_telefono_1 = $('#txt_telefono_1').val();
        var txt_telefono_2 = $('#txt_telefono_2').val();
        var txt_correo = $('#txt_correo').val();
        var ddl_provincias = $('#ddl_provincias').val();
        var ddl_ciudad = $('#ddl_ciudad').val();
        var ddl_parroquia = $('#ddl_parroquia').val();
        var txt_codigo_postal = $('#txt_codigo_postal').val();
        var txt_direccion = $('#txt_direccion').val();

        var parametros_informacion_personal = {
            '_id': '<?= $id ?>',
            'txt_primer_nombre': txt_primer_nombre,
            'txt_segundo_nombre': txt_segundo_nombre,
            'txt_primer_apellido': txt_primer_apellido,
            'txt_segundo_apellido': txt_segundo_apellido,
            'txt_fecha_nacimiento': txt_fecha_nacimiento,
            'ddl_nacionalidad': ddl_nacionalidad,
            'txt_cedula': txt_cedula,
            'ddl_estado_civil': ddl_estado_civil,
            'ddl_sexo': ddl_sexo,
            'txt_telefono_1': txt_telefono_1,
            'txt_telefono_2': txt_telefono_2,
            'txt_correo': txt_correo,
            'ddl_provincias': ddl_provincias,
            'ddl_ciudad': ddl_ciudad,
            'ddl_parroquia': ddl_parroquia,
            'txt_codigo_postal': txt_codigo_postal,
            'txt_direccion': txt_direccion,

        };

        if ($("#form_informacion_personal").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            //console.log(parametros_informacion_personal);
            insertar_informacion_personal(parametros_informacion_personal);
        }
    }

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

    function insertar_informacion_personal(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {

                    });
                    <?php if (isset($_GET['id'])) { ?>
                        cargarDatos_informacion_personal(<?= $id ?>);
                    <?php } ?>
                    $('#modal_informacion_personal').modal('hide');
                } else if (response == -2) {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }
</script>

-->
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

                                            <div>
                                                <a href="#" class="d-flex justify-content-center" data-bs-toggle="modal"
                                                    data-bs-target="#modal_agregar_cambiar_foto"
                                                    onclick="abrir_modal_cambiar_foto('<?= $id ?>');">
                                                    <i class='bx bxs-camera bx-sm'></i>
                                                </a>
                                            </div>
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
                                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_personas&_id=<?= $id_persona ?>&id_postulante=<?= $id ?>"
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
                        <div class="card">
                            <!-- Información Adicional y Contacto de Emergencia -->
                            <div class="card-body">
                                <div class="align-items-center">
                                    <div class="mt-3">
                                        <!-- <div class="row">
                                            <div class="col-10">
                                                <h5 class="fw-bold text-primary">Información Adicional</h5>
                                            </div>
                                            <div class="col-2">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal_informacion_adicional">
                                                    <i class='text-dark bx bx-pencil bx-sm'></i></a>
                                            </div>
                                        </div>
                                        <hr /> -->

                                        <!-- Queda en espera este parte del modulo -->
                                        <?php //include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_informacion_adicional.php'); 
                                        ?>

                                        <div class="row">
                                            <div class="col-10">
                                                <h5 class="fw-bold text-primary">Contacto de Emergencia</h5>
                                            </div>
                                            <div class="col-2">
                                                <a href="#" class="text-dark icon-hover" data-bs-toggle="modal"
                                                    data-bs-target="#modal_contacto_emergencia">
                                                    <i class='bx bx-show bx-sm'></i></a>
                                            </div>
                                        </div>

                                        <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_contacto_emergencia.php'); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cards de la derecha -->
                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-8 col-xxl-8">
                        <div class="card-body">
                            <!-- Nav Cards -->
                            <ul class="nav nav-tabs nav-success" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_experiencia" role="tab"
                                        aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-briefcase font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Experiencia</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successdocs" role="tab"
                                        aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-file-doc font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Documentos</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab"
                                        aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-brain font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Habilidades</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_discapacidad" role="tab"
                                        aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-brain font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Discapacidad</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content pt-3">
                                <!-- Primera Sección, Historial Laboral -->
                                <div class="tab-pane fade show active" id="tab_experiencia" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Experiencia Previa -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Experiencia Previa:
                                                            </h6>
                                                        </div>

                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_experiencia">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_experiencia_previa.php'); ?>

                                            </div>
                                            <!-- Formación Académica -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Formación Académica:
                                                            </h6>
                                                        </div>
                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                id="btn_modal_agregar_formacion_academica"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_formacion">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_formacion_academica.php'); ?>

                                            </div>
                                            <!-- Certificaciones y capacitación -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Certificación y/o
                                                                Capacitación:</h6>
                                                        </div>
                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_certificaciones">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_certificaciones_capacitaciones.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Segunda Sección, Documentos relevantes -->
                                <div class="tab-pane fade" id="successdocs" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Documento de Identidad -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Documento de
                                                                Identidad:</h6>
                                                        </div>
                                                        <div
                                                            class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_documentos_identidad">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_documento_identidad.php'); ?>

                                            </div>
                                            <!-- Contratos de Trabajo -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Contratos de Trabajo:
                                                            </h6>
                                                        </div>
                                                        <div
                                                            class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_contratos">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_contratos_trabajo.php'); ?>

                                            </div>
                                            <!-- Certificado Médicos -->
                                            <div class="card-body my-0">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Certificados Médicos:
                                                            </h6>
                                                        </div>
                                                        <div
                                                            class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_certificados_medicos">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_certificado_medico.php'); ?>

                                            </div>
                                            <!-- Referencias Laborales -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Referencias laborales:
                                                            </h6>
                                                        </div>
                                                        <div
                                                            class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_referencia_laboral">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_referencias_laborales.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tercera Sección, Idiomas y aptitudes -->
                                <div class="tab-pane fade" id="successprofile" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Idiomas -->
                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Idiomas</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_idioma">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_idiomas.php'); ?>

                                            </div>
                                            <!-- Aptitudes -->
                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Aptitudes</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal_agregar_aptitudes"
                                                                onclick="activar_select2();">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_aptitudes.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="tab_discapacidad" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">

                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Discapacidad:</h6>
                                                        </div>

                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#"
                                                                class="text-success icon-hover d-flex align-items-center"
                                                                onclick="abrir_modal_discapacidad('');">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_discapacidad.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            </div>
        </div>
    </div>
</div>


<script>
    //Validacion de formulario
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_primer_apellido');
        agregar_asterisco_campo_obligatorio('txt_segundo_apellido');
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