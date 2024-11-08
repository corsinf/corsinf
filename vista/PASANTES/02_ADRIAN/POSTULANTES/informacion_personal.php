<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


$id = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
//prueba
?>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        <?php if (isset($_GET['id'])) { ?>
            cargarDatos_informacion_personal(<?= $id ?>);
        <?php } ?>

    });

    //Información Personal
    function cargarDatos_informacion_personal(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_postulantesC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_primer_nombre').val(response[0].th_pos_primer_nombre);
                $('#txt_segundo_nombre').val(response[0].th_pos_segundo_nombre);
                $('#txt_primer_apellido').val(response[0].th_pos_primer_apellido);
                $('#txt_segundo_apellido').val(response[0].th_pos_segundo_apellido);
                $('#txt_fecha_nacimiento').val(response[0].th_pos_fecha_nacimiento);
                $('#ddl_nacionalidad').val(response[0].th_pos_nacionalidad);
                $('#txt_numero_cedula').val(response[0].th_pos_cedula);
                $('#ddl_estado_civil').val(response[0].th_pos_estado_civil);
                $('#ddl_sexo').val(response[0].th_pos_sexo);
                $('#txt_telefono_1').val(response[0].th_pos_telefono_1);
                $('#txt_telefono_2').val(response[0].th_pos_telefono_2);
                $('#txt_correo').val(response[0].th_pos_correo);
                $('#txt_direccion_postal').val(response[0].th_pos_postal);

                nombres_completos = response[0].th_pos_primer_apellido + ' ' + response[0].th_pos_segundo_apellido + ' ' + response[0].th_pos_primer_nombre + ' ' + response[0].th_pos_segundo_nombre;
                $('#txt_nombres_completos_v').html(nombres_completos);
                $('#txt_fecha_nacimiento_v').html(response[0].th_pos_fecha_nacimiento);
                $('#txt_nacionalidad_v').html(response[0].th_pos_nacionalidad);
                $('#txt_estado_civil_v').html(response[0].th_pos_estado_civil);
                $('#txt_numero_cedula_v').html(response[0].th_pos_cedula);
                $('#txt_telefono_1_v').html(response[0].th_pos_telefono_1);
                $('#txt_correo_v').html(response[0].th_pos_correo);

                //Input para las referencias laborales
                $('#txt_numero_cedula_referencia_laboral').val(response[0].th_pos_cedula);

                //Input para todos los pos_id que se vayan a colocar en los modales
                $('input[name="txt_postulante_id"]').val(response[0]._id);
                $('input[name="txt_postulante_cedula"]').val(response[0].th_pos_cedula);

                //console.log(response);
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
        var txt_numero_cedula = $('#txt_numero_cedula').val();
        var ddl_estado_civil = $('#ddl_estado_civil').val();
        var ddl_sexo = $('#ddl_sexo').val();
        var txt_telefono_1 = $('#txt_telefono_1').val();
        var txt_telefono_2 = $('#txt_telefono_2').val();
        var txt_correo = $('#txt_correo').val();
        var ddl_provincias = $('#ddl_provincias').val();
        var ddl_ciudad = $('#ddl_ciudad').val();
        var ddl_parroquia = $('#ddl_parroquia').val();
        var txt_direccion_postal = $('#txt_direccion_postal').val();
        var txt_direccion = $('#txt_direccion').val();
            
        var parametros_informacion_personal = {
            '_id': '<?= $id ?>',
            'txt_primer_nombre': txt_primer_nombre,
            'txt_segundo_nombre': txt_segundo_nombre,
            'txt_primer_apellido': txt_primer_apellido,
            'txt_segundo_apellido': txt_segundo_apellido,
            'txt_fecha_nacimiento': txt_fecha_nacimiento,
            'ddl_nacionalidad': ddl_nacionalidad,
            'txt_numero_cedula': txt_numero_cedula,
            'ddl_estado_civil': ddl_estado_civil,
            'ddl_sexo': ddl_sexo,
            'txt_telefono_1': txt_telefono_1,
            'txt_telefono_2': txt_telefono_2,
            'txt_correo': txt_correo,
            'ddl_provincias': ddl_provincias,
            'ddl_ciudad': ddl_ciudad,
            'ddl_parroquia': ddl_parroquia,
            'txt_direccion_postal': txt_direccion_postal,
            'txt_direccion': txt_direccion,

        };

        if ($("#form_informacion_personal").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            //console.log(parametros_informacion_personal);
            insertar_informacion_personal(parametros_informacion_personal);
        }
    }

    function insertar_informacion_personal(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_postulantesC.php?insertar=true',
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

<!-- Vista de la página -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Adrian</div>
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
                    <a href="../vista/inicio.php?mod=1010&acc=postulantes" class="btn btn-outline-dark btn-sm d-flex align-items-center"><i class="bx bx-arrow-back"></i> Postulantes</a>
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
                                        <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_cambiar_foto.php'); ?>
                                        <div>
                                            <a href="#" class="d-flex justify-content-center" data-bs-toggle="modal" data-bs-target="#modal_cambiar_foto" onclick="cambiar_foto();">
                                                <i class='bx bxs-camera bx-sm'></i>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Información Personal -->
                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-10">
                                                <h5 class="fw-bold text-primary">Información Personal</h5>
                                            </div>
                                            <div class="col-2">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal_informacion_personal">
                                                    <i class='text-dark bx bx-pencil bx-sm'></i>
                                                </a>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Nombre Completo</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p class="w-100 text-wrap" id="txt_nombres_completos_v"></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Fecha de Nacimiento</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p id="txt_fecha_nacimiento_v"></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Nacionalidad</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p id="txt_nacionalidad_v"></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Número de Cédula</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p class="w-100 text-wrap" id="txt_numero_cedula_v"></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Estado Civil</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p id="txt_estado_civil_v"></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6 d-flex align-items-center">
                                                <h6 class="w-100 text-wrap fw-bold">Teléfono</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p class="w-100 text-wrap" id="txt_telefono_1_v"></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Correo Electrónico</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p class="w-100 text-wrap" id="txt_correo_v"></p>
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
                                        <div class="row">
                                            <div class="col-10">
                                                <h5 class="fw-bold text-primary">Información Adicional</h5>
                                            </div>
                                            <div class="col-2">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal_informacion_adicional">
                                                    <i class='text-dark bx bx-pencil bx-sm'></i></a>
                                            </div>
                                        </div>
                                        <hr />

                                        <!-- Queda en espera este parte del modulo -->
                                        <?php //include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_informacion_adicional.php'); ?>

                                        <div class="row">
                                            <div class="col-9">
                                                <h5 class="fw-bold text-primary">Contacto de Emergencia</h5>
                                            </div>
                                            <div class="col-3 d-flex justify-content-end">
                                                <button class="btn btn-sm" style='color: white;' data-bs-toggle="modal" data-bs-target="#modal_contacto_emergencia"><i class='text-dark bx bx-show bx-sm me-0'></i></button>
                                            </div>
                                        </div>

                                        <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_contacto_emergencia.php'); ?>

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
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_experiencia" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-briefcase font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Experiencia</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successdocs" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-file-doc font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Documentos</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-brain font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Habilidades</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successcontact" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-user-check font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Estado del Empleado</div>
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
                                                            <h6 class="mb-0 fw-bold text-primary">Experiencia Previa:</h6>
                                                        </div>

                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_experiencia">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_experiencia_previa.php'); ?>

                                            </div>
                                            <!-- Formación Académica -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Formación Académica:</h6>
                                                        </div>
                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" id="btn_modal_agregar_formacion_academica" data-bs-toggle="modal" data-bs-target="#modal_agregar_formacion">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_formacion_academica.php'); ?>

                                            </div>
                                            <!-- Certificaciones y capacitación -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Certificaciones y Capacitación:</h6>
                                                        </div>
                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_certificaciones">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_certificaciones_capacitaciones.php'); ?>

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
                                                            <h6 class="mb-0 fw-bold text-primary">Documento de Identidad:</h6>
                                                        </div>
                                                        <div class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_documento_identidad">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Subir</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_documento_identidad.php'); ?>

                                            </div>
                                            <!-- Contratos de Trabajo -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Contratos de Trabajo:</h6>
                                                        </div>
                                                        <div class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_contratos">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Subir</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_contratos_trabajo.php'); ?>

                                            </div>
                                            <!-- Certificado Médicos -->
                                            <div class="card-body my-0">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Certificados Médicos:</h6>
                                                        </div>
                                                        <div class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_certificado_medico">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Subir</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_certificado_medico.php'); ?>

                                            </div>
                                            <!-- Referencias Laborales -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Referencias laborales:</h6>
                                                        </div>
                                                        <div class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_referencia_laboral">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_referencias_laborales.php'); ?>

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
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_idioma">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0">

                                                <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_idiomas.php'); ?>

                                            </div>
                                            <!-- Aptitudes -->
                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Aptitudes</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_aptitudes" onclick="activar_select2();">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0">

                                                <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_aptitudes.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Cuarta Sección, Estado del Empleado -->
                                <div class="tab-pane fade" id="successcontact" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Estado laboral:</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_estado_laboral">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/pos_estado_laboral.php'); ?>

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
<div class="modal" id="modal_informacion_personal" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Ingrese sus datos</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <form id="form_informacion_personal">
                <div class="modal-body">
                    <div class="row mb-col">
                        <div class="col-md-3">
                            <label for="txt_primer_apellido" class="form-label form-label-sm">Primer Apellido <label style="color: red;">*</label></label>
                            <input type="text" class="form-control form-control-sm" name="txt_primer_apellido" id="txt_primer_apellido" placeholder="Escriba su apellido paterno">
                        </div>
                        <div class="col-md-3">
                            <label for="txt_segundo_apellido" class="form-label form-label-sm">Segundo Apellido <label style="color: red;">*</label></label>
                            <input type="text" class="form-control form-control-sm" name="txt_segundo_apellido" id="txt_segundo_apellido" placeholder="Escriba su apellido materno">
                        </div>
                        <div class="col-md-3">
                            <label for="txt_primer_nombre" class="form-label form-label-sm">Primer Nombre <label style="color: red;">*</label></label>
                            <input type="text" class="form-control form-control-sm" name="txt_primer_nombre" id="txt_primer_nombre" placeholder="Escriba su primer nombre">
                        </div>
                        <div class="col-md-3">
                            <label for="txt_segundo_nombre" class="form-label form-label-sm">Segundo Nombre <label style="color: red;">*</label></label>
                            <input type="text" class="form-control form-control-sm" name="txt_segundo_nombre" id="txt_segundo_nombre" placeholder="Escriba su primer nombre">
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-col">
                        <div class="col-md-3">
                            <label for="txt_fecha_nacimiento" class="form-label form-label-sm">Fecha de nacimiento <label style="color: red;">*</label></label>
                            <input type="date" class="form-control form-control-sm" name="txt_fecha_nacimiento" id="txt_fecha_nacimiento">
                        </div>
                        <div class="col-md-3">
                            <label for="ddl_nacionalidad" class="form-label form-label-sm">Nacionalidad <label style="color: red;">*</label></label>
                            <select class="form-select form-select-sm" id="ddl_nacionalidad" name="ddl_nacionalidad">
                                <option selected disabled value="">-- Selecciona una Nacionalidad --</option>
                                <option value="Ecuatoriano">Ecuatoriano</option>
                                <option value="Colombiano">Colombiano</option>
                                <option value="Peruano">Peruano</option>
                                <option value="Venezolano">Venezolano</option>
                                <option value="Paraguayo">Paraguayo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="txt_numero_cedula" class="form-label form-label-sm">N° de Cédula <label style="color: red;">*</label></label>
                            <input type="text" class="form-control form-control-sm" name="txt_numero_cedula" id="txt_numero_cedula" placeholder="Digite su número de cédula">
                        </div>
                        <div class="col-md-3">
                            <label for="ddl_estado_civil" class="form-label form-label-sm">Estado civil <label style="color: red;">*</label></label>
                            <select class="form-select form-select-sm" id="ddl_estado_civil" name="ddl_estado_civil">
                                <option selected disabled value="">-- Selecciona un Estado Civil --</option>
                                <option value="Soltero">Soltero/a</option>
                                <option value="Casado">Casado/a</option>
                                <option value="Divorciado">Divorciado/a</option>
                                <option value="Viudo">Viudo/a</option>
                                <option value="Union">Unión de hecho</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-3">
                            <label for="ddl_sexo" class="form-label form-label-sm">Sexo <label style="color: red;">*</label></label>
                            <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo">
                                <option selected disabled value="">-- Selecciona una opción --</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="txt_telefono_1" class="form-label form-label-sm">Teléfono 1 (personal o fijo) <label style="color: red;">*</label></label>
                            <input type="text" class="form-control form-control-sm" name="txt_telefono_1" id="txt_telefono_1" placeholder="Escriba su teléfono personal o fijo">
                        </div>
                        <div class="col-md-3">
                            <label for="txt_telefono_2" class="form-label form-label-sm">Teléfono 2 (opcional)</label>
                            <input type="text" class="form-control form-control-sm" name="txt_telefono_2" id="txt_telefono_2" placeholder="Escriba su teléfono personal o fijo (opcional)">
                        </div>
                        <div class="col-md-3">
                            <label for="txt_correo" class="form-label form-label-sm">Correo Electrónico <label style="color: red;">*</label></label>
                            <input type="email" class="form-control form-control-sm" name="txt_correo" id="txt_correo" placeholder="Escriba su correo electrónico">
                        </div>
                    </div>

                    <?php include_once('../vista/PASANTES/02_ADRIAN/POSTULANTES/provincias_ciudades_parroquias.php'); ?>

                    <div class="row mb-col">
                                <div class="col-md-12">
                                    <label for="txt_direccion" class="form-label form-label-sm">Dirección </label>
                                    <input type="text" class="form-control form-control-sm" name="txt_direccion" id="txt_direccion" placeholder="Escriba su dirección">
                                </div>
                            </div>
         
                </div>


                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_informacion_personal" onclick="insertar_editar_informacion_personal();">Guardar</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        //Validacion de formulario
        $(document).ready(function() {
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
                    txt_numero_cedula: {
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
                    txt_numero_cedula: {
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