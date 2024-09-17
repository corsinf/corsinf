<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


$id = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

?>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        <?php if (isset($_GET['id'])) { ?>
            cargarDatos_informacion_personal(<?= $id ?>);
        <?php } ?>
        <?php if (isset($_GET['id'])) { ?>
            cargarDatos_informacion_adicional(<?= $id ?>);
        <?php } ?>

        $('#ddl_seleccionar_aptitud_blanda').select2({
            placeholder: 'Selecciona una opción',
            dropdownParent: $('#modal_agregar_aptitudes'),
            language: {
                inputTooShort: function() {
                    return "Por favor ingresa 1 o más caracteres";
                },
                noResults: function() {
                    return "No se encontraron resultados";
                },
                searching: function() {
                    return "Buscando...";
                },
                errorLoading: function() {
                    return "No se encontraron resultados";
                }
            }
        });

        $('#ddl_seleccionar_aptitud_tecnica').select2({
            placeholder: 'Selecciona una opción',
            dropdownParent: $('#modal_agregar_aptitudes'),
            language: {
                inputTooShort: function() {
                    return "Por favor ingresa 1 o más caracteres";
                },
                noResults: function() {
                    return "No se encontraron resultados";
                },
                searching: function() {
                    return "Buscando...";
                },
                errorLoading: function() {
                    return "No se encontraron resultados";
                }
            }
        });

    });

    //Funciones del formulario
    function obtener_codigo_postal() {
        var ubicacion = $('#ubicacion');
        var codigo_postal = $('#txt_direccion_postal');

        function success(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;

            // Llamada a la API de Nominatim para obtener el código postal
            var url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`;

            $.getJSON(url, function(data) {
                if (data && data.address && data.address.postcode) {
                    codigo_postal.val(data.address.postcode);
                } else {
                    codigo_postal.val('No se pudo obtener');
                }
            }).fail(function() {
                codigo_postal.val('Error al obtener el código postal');
            });
        }

        // Obtener la ubicación del usuario
        navigator.geolocation.getCurrentPosition(success);
    }

    function cambiar_foto() {
        var btn_elegir_foto = $('#btn_elegir_foto')
        var input_elegir_foto = $('#txt_elegir_foto')

        btn_elegir_foto.click(function() {
            input_elegir_foto.click();
        });
    }

    function ocultar_opciones_estado() {
        var select_opciones_estado = $('#ddl_estado_laboral');
        var valor_seleccionado = select_opciones_estado.val();

        $('#txt_fecha_contratacion_estado').prop('disabled', false);
        $('#txt_fecha_salida_estado').prop('disabled', false);

        if (valor_seleccionado === "Freelancer" || valor_seleccionado === "Autonomo") {
            $('#txt_fecha_contratacion_estado').prop('disabled', true);
            $('#txt_fecha_salida_estado').prop('disabled', true);
        }
    }

    function insertar_editar_foto() {
        var txt_elegir_foto = $('#txt_elegir_foto').val();

        var parametro_foto = {
            'txt_elegir_foto': txt_elegir_foto
        }

        console.log(parametro_foto)
    }

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

                nombres_completos = response[0].th_pos_primer_apellido + ' ' + response[0].th_pos_segundo_apellido + ' ' + response[0].th_pos_primer_nombre + ' ' + response[0].th_pos_segundo_nombre;
                $('#txt_nombres_completos_v').html(nombres_completos);
                $('#txt_fecha_nacimiento_v').html(response[0].th_pos_fecha_nacimiento);
                $('#txt_nacionalidad_v').html(response[0].th_pos_nacionalidad);
                $('#txt_estado_civil_v').html(response[0].th_pos_estado_civil);
                $('#txt_numero_cedula_v').html(response[0].th_pos_cedula);
                $('#txt_telefono_1_v').html(response[0].th_pos_telefono_1);
                $('#txt_correo_v').html(response[0].th_pos_correo);

                console.log(response);
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

        };

        if ($("#form_informacion_personal").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_informacion_personal);
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

    //Información Adicional
    function cargarDatos_informacion_adicional(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_postulante_inf_adicionalC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_direccion_calle').val(response[0].th_posa_direccion_calle);
                $('#txt_direccion_numero').val(response[0].th_posa_direccion_numero);
                $('#txt_direccion_ciudad').val(response[0].th_posa_direccion_ciudad);
                $('#txt_direccion_estado').val(response[0].th_posa_direccion_estado);
                $('#txt_direccion_postal').val(response[0].th_posa_direccion_codpos);
                $('#txt_inf_adicional_id').val(response[0]._id)

                direccion_completa = response[0].th_posa_direccion_calle + ', ' + response[0].th_posa_direccion_numero + ', ' + response[0].th_posa_direccion_ciudad + ', ' + response[0].th_posa_direccion_estado + ', ' + response[0].th_posa_direccion_codpos
                $('#txt_direccion_v').html(direccion_completa);

                console.log(response);
            }
        });
    }

    function insertar_editar_informacion_adicional() {
        var txt_direccion_calle = $('#txt_direccion_calle').val();
        var txt_direccion_numero = $('#txt_direccion_numero').val();
        var txt_direccion_ciudad = $('#txt_direccion_ciudad').val();
        var txt_direccion_estado = $('#txt_direccion_estado').val();
        var txt_direccion_postal = $('#txt_direccion_postal').val();
        var txt_id_postulante = '<?= $id ?>';
        var txt_id_formacion_academica = $('#txt_inf_adicional_id').val();


        var parametros_informacion_adicional = {
            '_id' : txt_id_formacion_academica,
            'txt_id_postulante': txt_id_postulante, 
            'txt_direccion_calle': txt_direccion_calle,
            'txt_direccion_numero': txt_direccion_numero,
            'txt_direccion_ciudad': txt_direccion_ciudad,
            'txt_direccion_estado': txt_direccion_estado,
            'txt_direccion_postal': txt_direccion_postal,
            
        }

        if ($("#form_informacion_adicional").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_informacion_adicional)
            insertar_informacion_adicional(parametros_informacion_adicional)
        }

    }

    function insertar_informacion_adicional(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_postulante_inf_adicionalC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {});
                    <?php if (isset($_GET['id'])) { ?>
                        cargarDatos_informacion_adicional(<?= $id ?>);
                    <?php } ?>
                    $('#modal_informacion_adicional').modal('hide');
                } else if (response == -2) {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    //Certificaciones y Capacitaciones
    function insertar_editar_certificaciones_capacitaciones() {
        var txt_nombre_certificacion = $('#txt_nombre_certificacion').val();
        var txt_enlace_certificado = $('#txt_enlace_certificado').val();
        var txt_pdf_certificado = $('#txt_pdf_certificado').val();

        var parametros_certificaciones_capacitaciones = {
            'txt_nombre_certificacion': txt_nombre_certificacion,
            'txt_enlace_certificado': txt_enlace_certificado,
            'txt_pdf_certificado': txt_pdf_certificado,
        }

        if ($("#form_certificaciones_capacitaciones").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_certificaciones_capacitaciones)
        }
    }

    //Certificados Médicos
    function insertar_editar_certificado_medico() {
        var txt_nombre_certificado_medico = $('#txt_nombre_certificado_medico').val();
        var txt_respaldo_medico = $('#txt_respaldo_medico').val();

        var parametros_certificado_medico = {
            'txt_nombre_certificado_medico': txt_nombre_certificado_medico,
            'txt_respaldo_medico': txt_respaldo_medico,
        }

        if ($("#form_certificado_medico").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_certificado_medico)
        }
    }

    //Referencias Laborales
    function insertar_editar_referencias() {
        var txt_nombre_referencia = $('#txt_nombre_referencia').val();
        var txt_telefono_referencia = $('#txt_telefono_referencia').val();
        var txt_copia_carta_recomendacion = $('#txt_copia_carta_recomendacion').val();

        var parametros_referencias = {
            'txt_nombre_referencia': txt_nombre_referencia,
            'txt_telefono_referencia': txt_telefono_referencia,
            'txt_copia_carta_recomendacion': txt_copia_carta_recomendacion,
        }

        if ($("#form_referencias_laborales").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_referencias)
        }
    }

    //Contratos de Trabajo
    function insertar_editar_contrato_laboral() {
        var txt_nombre_empresa_contrato = $('#txt_nombre_empresa_contrato').val();
        var txt_copia_contrato = $('#txt_copia_contrato').val();

        var parametros_contrato_laboral = {
            'txt_nombre_empresa_contrato': txt_nombre_empresa_contrato,
            'txt_copia_contrato': txt_copia_contrato,
        }

        if ($("#form_contrato_trabajo").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_contrato_laboral)
        }
    }

    //Estado Laboral
    function insertar_editar_estado_laboral() {
        var ddl_estado_laboral = $('#ddl_estado_laboral').val();
        var txt_fecha_contratacion_estado = $('#txt_fecha_contratacion_estado').val();
        var txt_fecha_salida_estado = $('#txt_fecha_salida_estado').val();

        var parametros_estado_laboral = {
            'ddl_estado_laboral': ddl_estado_laboral,
            'txt_fecha_contratacion_estado': txt_fecha_contratacion_estado,
            'txt_fecha_salida_estado': txt_fecha_salida_estado,
        }

        if ($("#form_estado_laboral").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_estado_laboral)
        }

    }

    //Idiomas
    function insertar_editar_idiomas() {
        var ddl_seleccionar_idioma = $('#ddl_seleccionar_idioma').val();
        var ddl_dominio_idioma = $('#ddl_dominio_idioma').val();

        var parametros_idiomas = {
            'ddl_seleccionar_idioma': ddl_seleccionar_idioma,
            'ddl_dominio_idioma': ddl_dominio_idioma,
        }

        if ($("#form_agregar_idioma").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_idiomas)
        }

    }

    //Aptitudes
    function insertar_editar_aptitudes() {
        var ddl_seleccionar_aptitud_blanda = [];
        $('.ddl_seleccionar_aptitud_blanda').each(function() {
            ddl_seleccionar_aptitud_blanda.push($(this).val());
        });

        var ddl_seleccionar_aptitud_tecnica = [];
        $('.ddl_seleccionar_aptitud_tecnica').each(function() {
            ddl_seleccionar_aptitud_tecnica.push($(this).val());
        });

        var parametros_aptitudes = {
            'ddl_seleccionar_aptitud_blanda': ddl_seleccionar_aptitud_blanda,
            'ddl_seleccionar_aptitud_tecnica': ddl_seleccionar_aptitud_tecnica,
        }

        if ($("#form_aptitudes").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_aptitudes)
        }
    }

    //Documento de Identidad
    function insertar_editar_documento_identidad() {
        var ddl_tipo_documento_identidad = $('#ddl_tipo_documento_identidad').val();
        var txt_agregar_documento_identidad = $('#txt_agregar_documento_identidad').val();

        var parametros_documento_identidad = {
            'ddl_tipo_documento_identidad': ddl_tipo_documento_identidad,
            'txt_agregar_documento_identidad': txt_agregar_documento_identidad,
        }

        if ($("#form_documento_identidad").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros_documento_identidad)
        }
    }

    function limpiar_parametros() {
        //Limpiar parámetros

        //experiencia laboral
        $('#txt_nombre_empresa').val('');
        $('#txt_cargos_ocupados').val('');
        $('#txt_fecha_inicio_laboral').val('');
        $('#txt_fecha_final_laboral').val('');
        $('#cbx_fecha_final_laboral').val('');
        $('#txt_responsabilidades_logros').prop('');

        //formacion académica
        $('#txt_titulo_obtenido').val('');
        $('#txt_institucion').val('');
        $('#txt_fecha_inicio_academico').val('');
        $('#txt_fecha_final_academico').val('');

        //certificaciones capacitaciones
        $('#txt_nombre_certificacion').val('');
        $('#txt_enlace_certificado').val('');
        $('#txt_pdf_certificado').val('');


        $('#txt_agregar_documento_identidad').val('');
        $('#txt_nombre_certificado_medico').val('');
        $('#txt_respaldo_medico').val('');
        $('#ddl_tipo_referencia').val('');
        $('#txt_nombre_referencia').val('');
        $('#txt_telefono_referencia').val('');
        $('#txt_copia_carta_recomendacion').val('');
        $('#txt_nombre_empresa_contrato').val('');
        $('#txt_copia_contrato').val('');
        $('#ddl_estado_laboral').val('');
        $('#txt_fecha_contratacion_estado').val('');
        $('#txt_fecha_salida_estado').val('');
        $('#ddl_seleccionar_idioma').val('');
        $('#ddl_dominio_idioma').val('');
        $('#ddl_tipo_aptitudes').val('');
        $('.ddl_seleccionar_aptitud_blanda').val('');
        $('.ddl_seleccionar_aptitud_tecnica').val('');

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
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-4 col-xxl-3">
                        <!-- Cards de la izquierda -->
                        <div class="card">
                            <!-- Información Personal -->
                            <div class="card-body">
                                <div class="align-items-center">
                                    <div class="text-center">
                                        <img src="../img\usuarios\2043.jpeg" alt="Admin" class="rounded-circle p-1 bg-primary mb-2" width="110">
                                        <div>
                                            <a href="#" class="d-flex justify-content-center" data-bs-toggle="modal" data-bs-target="#modal_cambiar_foto" onclick="cambiar_foto();">
                                                <i class='bx bxs-camera bx-sm'></i>
                                            </a>
                                        </div>
                                    </div>
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
                                                <p id="txt_nombres_completos_v"></p>
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
                                                <p id="txt_numero_cedula_v"></p>
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
                                                <h6 class="fw-bold">Teléfono</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p id="txt_telefono_1_v"></p>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <h6 class="fw-bold">Correo Electrónico</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p class="text-wrap" style="width: 9rem;" id="txt_correo_v"></p>
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
                                        <div class="row mb-3">
                                            <div class="col-6 d-flex align-items-center">
                                                <h6 class="fw-bold">Dirección</h6>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <p id="txt_direccion_v"></p>
                                            </div>
                                        </div>

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

                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-8 col-xxl-8">
                        <!-- Cards de la derecha -->
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
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Formación Académica:</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
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
                                                        <div class="col-8 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Certificaciones y Capacitación:</h6>
                                                        </div>
                                                        <div class="col-4 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_certificaciones">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h6 class="fw-bold">CS50: Introduction to Computer Science</h6>
                                                        <a href="#" class="fw-bold">Ver Certificado</a>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Segunda Sección, Documentos relevantes -->
                                <div class="tab-pane fade" id="successdocs" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
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
                                                <div class="row mb-2">
                                                    <div class="col-10 d-flex align-items-center">
                                                        <p class="fw-bold">Cédula de Identidad</p>
                                                    </div>
                                                    <div class="col-2 d-flex justify-content-end">
                                                        <a href="#" class=""><i class='text-info bx bx-download me-2' style="font-size: 20px;"></i></a>
                                                        <a href="#" class=""><i class='text-danger bx bx-trash me-0' style="font-size: 20px;"></i></a>
                                                    </div>
                                                </div>
                                            </div>
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
                                                <div class="row mb-2">
                                                    <div class="col-10 d-flex align-items-center">
                                                        <p class="fw-bold">Contrato de trabajo - Sambitours</p>
                                                    </div>
                                                    <div class="col-2 d-flex justify-content-end">
                                                        <a href="#" class=""><i class='text-info bx bx-download me-2' style="font-size: 20px;"></i></a>
                                                        <a href="#" class=""><i class='text-danger bx bx-trash me-0' style="font-size: 20px;"></i></a>
                                                    </div>
                                                </div>
                                            </div>
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
                                                <div class="row mb-2">
                                                    <div class="col-10 d-flex align-items-center">
                                                        <p class="fw-bold">Certificado médico de enfermedad cualquiera</p>
                                                    </div>
                                                    <div class="col-2 d-flex justify-content-end">
                                                        <a href="#" class=""><i class='text-info bx bx-download me-2' style="font-size: 20px;"></i></a>
                                                        <a href="#" class=""><i class='text-danger bx bx-trash me-0' style="font-size: 20px;"></i></a>
                                                    </div>
                                                </div>
                                            </div>
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
                                                <div class="row mb-2">
                                                    <div class="col-10">
                                                        <p class="fw-bold my-0 d-flex align-items-center">Ing. Roberto Carapaz</p>
                                                        <p class="my-0 d-flex align-items-center">+593 994645643</p>
                                                        <a href="#">Carta de Recomendación</a>
                                                    </div>
                                                    <div class="col-2 d-flex justify-content-end align-items-center">
                                                        <a href="#" class=""><i class='text-info bx bx-pencil me-2' style="font-size: 20px;"></i></a>
                                                        <a href="#" class=""><i class='text-danger bx bx-trash me-0' style="font-size: 20px;"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tercera Sección, Idiomas y aptitudes -->
                                <div class="tab-pane fade" id="successprofile" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
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
                                                <div class="row mt-3">
                                                    <div class="col-8">
                                                        <h6 class="fw-bold">Inglés</h6>
                                                        <p>B1</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Aptitudes</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#" class="text-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_aptitudes">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0">
                                                <div class="row mt-3">
                                                    <div class="col-8">
                                                        <p class="fw-bold">Aptitudes Técnicas</p>
                                                        <ul>
                                                            <li>Dominio de paquete Office</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <p class="fw-bold">Aptitudes Blandas</p>
                                                        <ul>
                                                            <li>Liderazgo</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
                                                    </div>
                                                </div>
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
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <h6 class="fw-bold mb-2">Inactivo</h6>
                                                        <p>Ene 2022 - Oct 2023</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="#" class="d-flex justify-content-end"><i class='text-dark bx bx-pencil bx-sm'></i></a>
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
    </div>
</div>

<!-- Modal para cambiar la foto-->
<div class="modal" id="modal_cambiar_foto" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Foto de Perfil</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body needs-validation">
                <div class="text-center">
                    <img src="../img\usuarios\2043.jpeg" alt="Admin" class="img-fluid mb-3 p-2 bg-secondary" width="240">
                </div>
                <div class="mb-4 d-flex justify-content-center">
                    <input type="button" class="btn btn-primary " name="btn_elegir_foto" id="btn_elegir_foto" value="Elegir otra foto">
                    <input type="file" id="txt_elegir_foto" accept="image/*" style="display: none;">
                </div>
                <div class="mb-3 d-flex justify-content-center">
                    <input type="button" class="btn btn-success" name="btn_confirmar_foto" id="btn_confirmar_foto" value="Confirmar" onclick="insertar_editar_foto();">
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
                    <div class="row">
                        <div class="col-3">
                            <div class="mb-3">
                                <label for="txt_primer_apellido" class="form-label form-label-sm">Primer Apellido <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_primer_apellido" id="txt_primer_apellido" placeholder="Escriba su apellido paterno">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3">
                                <label for="txt_segundo_apellido" class="form-label form-label-sm">Segundo Apellido <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_segundo_apellido" id="txt_segundo_apellido" placeholder="Escriba su apellido materno">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3">
                                <label for="txt_primer_nombre" class="form-label form-label-sm">Primer Nombre <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_primer_nombre" id="txt_primer_nombre" placeholder="Escriba su primer nombre">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3">
                                <label for="txt_segundo_nombre" class="form-label form-label-sm">Segundo Nombre <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_segundo_nombre" id="txt_segundo_nombre" placeholder="Escriba su primer nombre">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-3">
                            <div class="mb-3">
                                <label for="txt_fecha_nacimiento" class="form-label form-label-sm">Fecha de nacimiento <label style="color: red;">*</label></label>
                                <input type="date" class="form-control form-control-sm" name="txt_fecha_nacimiento" id="txt_fecha_nacimiento">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3">
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
                        </div>
                        <div class="col-3">
                            <div class="mb-3">
                                <label for="txt_numero_cedula" class="form-label form-label-sm">N° de Cédula <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_numero_cedula" id="txt_numero_cedula" placeholder="Digite su número de cédula">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3">
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
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="ddl_sexo" class="form-label form-label-sm">Sexo <label style="color: red;">*</label></label>
                                <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo">
                                    <option selected disabled value="">-- Selecciona una opción --</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="txt_telefono_1" class="form-label form-label-sm">Teléfono 1 (personal o fijo) <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_telefono_1" id="txt_telefono_1" placeholder="Escriba su teléfono personal o fijo">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="txt_telefono_2" class="form-label form-label-sm">Teléfono 2 (opcional)</label>
                                <input type="text" class="form-control form-control-sm" name="txt_telefono_2" id="txt_telefono_2" placeholder="Escriba su teléfono personal o fijo (opcional)">
                            </div>
                        </div>
                        <div class="col-8 mx-auto">
                            <div class="mb-3">
                                <label for="txt_correo" class="form-label form-label-sm">Correo Electrónico <label style="color: red;">*</label></label>
                                <input type="email" class="form-control form-control-sm" name="txt_correo" id="txt_correo" placeholder="Escriba su correo electrónico">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_informacion_personal" onclick="insertar_editar_informacion_personal();">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para la informacion Adicional -->
<div class="modal" id="modal_informacion_adicional" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Ingrese sus datos de su dirección</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <form id="form_informacion_adicional">
                <div class="modal-body">
                    <p class="fw-bold">Dirección:</p>
                    <div class="row">
                        <input type="text" id="txt_inf_adicional_id" hidden>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="txt_direccion_calle" class="form-label form-label-sm">Calle <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_direccion_calle" id="txt_direccion_calle" value="" placeholder="Escriba la calle de su dirección" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="txt_direccion_numero" class="form-label form-label-sm">Número <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_direccion_numero" id="txt_direccion_numero" value="" placeholder="Escriba el número de su dirección" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="txt_direccion_ciudad" class="form-label form-label-sm">Ciudad <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_direccion_ciudad" id="txt_direccion_ciudad" value="" placeholder="Escriba la ciudad de su dirección" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="txt_direccion_estado" class="form-label form-label-sm">Provincia <label style="color: red;">*</label></label>
                                <input type="text" class="form-control form-control-sm" name="txt_direccion_estado" id="txt_direccion_estado" value="" placeholder="Escriba la provincia de su dirección" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="txt_direccion_postal" class="form-label form-label-sm">Código Postal <label style="color: red;">*</label></label>
                                <div class="row">
                                    <div class="col-11 me-0">
                                        <input type="text" class="form-control form-control-sm" name="txt_direccion_postal" id="txt_direccion_postal" placeholder="Escriba su código postal o de click en 'Obtener'">
                                    </div>
                                    <div class="col-11 me-0" style="display: none;">
                                        <a id="ubicacion" target="_blank"></a>
                                    </div>
                                    <div class="col-1 d-flex justify-content-start">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="obtener_codigo_postal();">Obtener</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_informacion_contacto" onclick="insertar_editar_informacion_adicional();">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para agregar certificaciones y capacitaciones-->
<div class="modal" id="modal_agregar_certificaciones" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue una Certificación o Capacitación</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_certificaciones_capacitaciones">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="txt_nombre_certificacion" class="form-label form-label-sm">Nombre del curso o capacitación <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_nombre_certificacion" id="txt_nombre_certificacion" value="" placeholder="Escriba el nombre del curso o capacitación">
                    </div>
                    <div class="mb-3">
                        <label for="txt_enlace_certificado" class="form-label form-label-sm">1. Enlace del Certificado obtenido <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_enlace_certificado" id="txt_enlace_certificado" value="" placeholder="Escriba el enlace a su certificado">
                    </div>
                    <div class="mb-3">
                        <label for="txt_pdf_certificado" class="form-label form-label-sm">2. PDF del Certificado obtenido <label style="color: red;">*</label></label>
                        <input type="file" class="form-control form-control-sm" name="txt_pdf_certificado" id="txt_pdf_certificado" accept=".pdf" value="" placeholder="">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_certificaciones" onclick="insertar_editar_certificaciones_capacitaciones();">Guardar Certificación o Capacitación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para agregar certificados médicos-->
<div class="modal" id="modal_agregar_certificado_medico" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un Certificado Médico</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_certificado_medico">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="txt_nombre_certificado_medico" class="form-label form-label-sm">Nombre del certificado <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_nombre_certificado_medico" id="txt_nombre_certificado_medico" placeholder="Escriba el nombre del certificado médico">
                    </div>
                    <div class="mb-3">
                        <label for="txt_respaldo_medico" class="form-label form-label-sm">Documentación que respalde la aptitud para el trabajo <label style="color: red;">*</label></label>
                        <input type="file" class="form-control form-control-sm" name="txt_respaldo_medico" id="txt_respaldo_medico" accept=".pdf">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_certificado_medico" onclick="insertar_editar_certificado_medico()">Guardar Certificado Médico</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para agregar referencias laborales-->
<div class="modal" id="modal_agregar_referencia_laboral" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue una referencia</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_referencias_laborales">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="txt_nombre_referencia" class="form-label form-label-sm">Nombre del empleador <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_nombre_referencia" id="txt_nombre_referencia" placeholder="Escriba el nombre de el empleador">
                    </div>
                    <div class="mb-3">
                        <label for="txt_telefono_referencia" class="form-label form-label-sm">Teléfono del empleador <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_telefono_referencia" id="txt_telefono_referencia" placeholder="Escriba el número de contacto de el empleador">
                    </div>
                    <div class="mb-3">
                        <label for="txt_copia_carta_recomendacion" class="form-label form-label-sm">Copia de la carta de recomendación <label style="color: red;">*</label></label>
                        <input type="file" class="form-control form-control-sm" name="txt_copia_carta_recomendacion" id="txt_copia_carta_recomendacion" accept=".pdf">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_referencia" onclick="insertar_editar_referencias();">Guardar Referencia Laboral</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para agregar contratos de trabajo-->
<div class="modal" id="modal_agregar_contratos" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un Contrato</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_contrato_trabajo">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="txt_nombre_empresa_contrato" class="form-label form-label-sm">Nombre de la empresa <label style="color: red;">*</label></label>
                        <input type="text" class="form-control form-control-sm" name="txt_nombre_empresa_contrato" id="txt_nombre_empresa_contrato" placeholder="Escriba el nombre de la empresa que emitió el contrato">
                    </div>
                    <div class="mb-3">
                        <label for="txt_copia_contrato" class="form-label form-label-sm">Copia del contrato firmado <label style="color: red;">*</label></label>
                        <input type="file" class="form-control form-control-sm" name="txt_copia_contrato" id="txt_copia_contrato" accept=".pdf">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_contratos" onclick="insertar_editar_contrato_laboral();">Guardar Contrato de Trabajo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para agregar estado laboral-->
<div class="modal" id="modal_estado_laboral" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue su estado laboral</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_estado_laboral">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ddl_estado_laboral" class="form-label form-label-sm">Estado laboral: <label style="color: red;">*</label></label>
                        <select class="form-select form-select-sm" id="ddl_estado_laboral" name="ddl_estado_laboral" onchange="ocultar_opciones_estado();" required>
                            <option selected disabled value="">-- Selecciona un Estado Laboral -- <label style="color: red;">*</label></option>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Prueba">En prueba</option>
                            <option value="Pasante">Pasante</option>
                            <option value="Freelancer">Freelancer</option>
                            <option value="Autonomo">Autónomo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="txt_fecha_contratacion_estado" class="form-label form-label-sm">Fecha de contratación <label style="color: red;">*</label></label>
                        <input type="date" class="form-control form-control-sm" name="txt_fecha_contratacion_estado" id="txt_fecha_contratacion_estado">
                    </div>
                    <div class="mb-3">
                        <label for="txt_fecha_salida_estado" class="form-label form-label-sm">Fecha de salida <label style="color: red;">*</label></label>
                        <input type="date" class="form-control form-control-sm" name="txt_fecha_salida_estado" id="txt_fecha_salida_estado">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_estado_laboral" onclick="insertar_editar_estado_laboral();">Guardar Estado Laboral</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para agregar idiomas-->
<div class="modal" id="modal_agregar_idioma" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un idioma</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_agregar_idioma">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ddl_seleccionar_idioma" class="form-label form-label-sm">Idioma <label style="color: red;">*</label></label>
                        <select class="form-select form-select-sm" id="ddl_seleccionar_idioma" name="ddl_seleccionar_idioma">
                            <option selected disabled value="">-- Selecciona un Idioma --</option>
                            <option value="Español">Español</option>
                            <option value="Inglés">Inglés</option>
                            <option value="Francés">Francés</option>
                            <option value="Alemán">Alemán</option>
                            <option value="Chino">Chino</option>
                            <option value="Italiano">Italiano</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ddl_dominio_idioma" class="form-label form-label-sm">Dominio del Idioma <label style="color: red;">*</label></label>
                        <select class="form-select form-select-sm" id="ddl_dominio_idioma" name="ddl_dominio_idioma" required>
                            <option selected disabled value="">-- Selecciona su nivel de dominio del idioma --</option>
                            <option value="Nativo">Nativo</option>
                            <option value="C1">C1</option>
                            <option value="C2">C2</option>
                            <option value="B1">B1</option>
                            <option value="B2">B2</option>
                            <option value="C1">C1</option>
                            <option value="C2">C2</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_idioma" onclick="insertar_editar_idiomas();">Guardar Idioma</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para agregar aptitudes técnicas y blandas-->
<div class="modal" id="modal_agregar_aptitudes" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue Aptitudes</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_aptitudes">
                <div class="modal-body">
                    <div class="mb-4">
                        <div class="row mb-1">
                            <div class="col-12 d-flex align-items-center">
                                <label for="ddl_seleccionar_aptitud_blanda" class="form-label form-label-sm fw-bold">Seleccione sus Aptitudes Blandas <label style="color: red;">*</label></label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <select class="form-select form-select-sm ddl_seleccionar_aptitud_blanda" id="ddl_seleccionar_aptitud_blanda" name="ddl_seleccionar_aptitud_blanda" multiple="multiple">
                                    <option value="Liderazgo">Liderazgo</option>
                                    <option value="Comunicación Efectiva">Comunicación Efectiva</option>
                                    <option value="Trabajo en equipo">Trabajo en equipo</option>
                                    <option value="etc">etc</option>
                                    <option value="etc">etc</option>
                                    <option value="etc">etc</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="row mb-1">
                            <div class="col-12 d-flex align-items-center">
                                <label for="ddl_seleccionar_aptitud_tecnica" class="form-label form-label-sm fw-bold">Seleccione sus Aptitudes Técnicas <label style="color: red;">*</label></label>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <select class="form-select form-select-sm ddl_seleccionar_aptitud_tecnica" id="ddl_seleccionar_aptitud_tecnica" name="ddl_seleccionar_aptitud_tecnica" multiple="multiple">
                                    <option value="Manejo de office">Manejo de office</option>
                                    <option value="Django">Django</option>
                                    <option value="Laravel">Laravel</option>
                                    <option value="Photoshop">Photoshop</option>
                                    <option value="Illustrator">Illustrator</option>
                                    <option value="etc">etc</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_aptitudes" onclick="insertar_editar_aptitudes();">Guardar Aptitudes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para agregar documento de identidad-->
<div class="modal" id="modal_agregar_documento_identidad" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Agregue un Documento de Identidad</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_parametros()"></button>
            </div>
            <!-- Modal body -->
            <form id="form_documento_identidad">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ddl_tipo_documento_identidad" class="form-label form-label-sm">Tipo de Documento <label style="color: red;">*</label></label>
                        <select class="form-select form-select-sm" id="ddl_tipo_documento_identidad" name="ddl_tipo_documento_identidad">
                            <option selected disabled value="">-- Selecciona una opción --</option>
                            <option value="Cédula de Identidad">Cédula de Identidad</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="Tarjeta de identificación">Tarjeta de identificación</option>
                            <option value="Licencia">Licencia</option>
                            <option value="Carnét o Certificado para miembro de la Fuerza Pública Ecuatoriana">Carnét o Certificado para miembro de la Fuerza Pública Ecuatoriana</option>
                            <option value="Carnét de discapacidad">Carnét de discapacidad</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="txt_agregar_documento_identidad" class="form-label form-label-sm">Copia del Documento de identidad <label style="color: red;">*</label></label>
                        <input type="file" class="form-control form-control-sm" name="txt_agregar_documento_identidad" id="txt_agregar_documento_identidad" accept=".pdf">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_documento_identidad" onclick="insertar_editar_documento_identidad();">Guardar Documento de Identidad</button>
                </div>
            </form>
        </div>
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

        //Validación Información Adicional
        $("#form_informacion_adicional").validate({
            rules: {
                txt_direccion_calle: {
                    required: true,
                },
                txt_direccion_numero: {
                    required: true,
                },
                txt_direccion_ciudad: {
                    required: true,
                },
                txt_direccion_estado: {
                    required: true,
                },
                txt_direccion_postal: {
                    required: true,
                },
            },
            messages: {
                txt_direccion_calle: {
                    required: "Por favor ingrese la calle de su dirección",
                },
                txt_direccion_numero: {
                    required: "Por favor ingrese el número de su dirección",
                },
                txt_direccion_ciudad: {
                    required: "Por favor ingrese la ciudad en la que reside",
                },
                txt_direccion_estado: {
                    required: "Por favor ingrese la provincia en la que reside",
                },
                txt_direccion_postal: {
                    required: "Por favor ingrese su código postal o de click en 'Obtener'",
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

        //Validación Contacto de Emergencia
        $("#form_contacto_emergencia").validate({
            rules: {
                txt_nombre_contacto_emergencia: {
                    required: true,
                },
                txt_telefono_contacto_emergencia: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_contacto_emergencia: {
                    required: "Por favor ingrese el nombre de su contacto",
                },
                txt_telefono_contacto_emergencia: {
                    required: "Por favor ingrese el teléfono de su contacto",
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

        //Validación Formación Académica
        $("#form_formacion_academica").validate({
            rules: {
                txt_titulo_obtenido: {
                    required: true,
                },
                txt_institucion: {
                    required: true,
                },
                txt_fecha_inicio_academico: {
                    required: true,
                },
                txt_fecha_final_academico: {
                    required: true,
                },
            },
            messages: {
                txt_titulo_obtenido: {
                    required: "Por favor ingrese el título obtenido",
                },
                txt_institucion: {
                    required: "Por favor ingrese la institución en la que se graduó",
                },
                txt_fecha_inicio_academico: {
                    required: "Por favor ingrese la fecha en la que inició sus estudios",
                },
                txt_fecha_final_academico: {
                    required: "Por favor ingrese la fecha en la que finalizó o finalizará sus estudios",
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

        //Validación Certificaciones y Capacitaciones
        $("#form_certificaciones_capacitaciones").validate({
            rules: {
                txt_nombre_certificacion: {
                    required: true,
                },
                txt_enlace_certificado: {
                    required: true,
                },
                txt_pdf_certificado: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_certificacion: {
                    required: "Por favor ingrese el nombre del certificado",
                },
                txt_enlace_certificado: {
                    required: "Por favor ingrese el enlace de su certificado",
                },
                txt_pdf_certificado: {
                    required: "Por favor ingrese el PDF de su certificado",
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

        //Validación Certificados Médicos
        $("#form_certificado_medico").validate({
            rules: {
                txt_nombre_certificado_medico: {
                    required: true,
                },
                txt_respaldo_medico: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_certificado_medico: {
                    required: "Por favor ingrese un nombre para su certificado médico",
                },
                txt_respaldo_medico: {
                    required: "Por favor suba un documento que lo respalde",
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

        //Validación Referencias Laborales
        $("#form_referencias_laborales").validate({
            rules: {
                txt_nombre_referencia: {
                    required: true,
                },
                txt_telefono_referencia: {
                    required: true,
                },
                txt_copia_carta_recomendacion: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_referencia: {
                    required: "Por favor ingrese el nombre de su referencia laboral",
                },
                txt_telefono_referencia: {
                    required: "Por favor ingrese el teléfono de su referencia laboral",
                },
                txt_copia_carta_recomendacion: {
                    required: "Por favor suba la carta de recomendación",
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

        //Validación Contratos de Trabajo
        $("#form_contrato_trabajo").validate({
            rules: {
                txt_nombre_empresa_contrato: {
                    required: true,
                },
                txt_copia_contrato: {
                    required: true,
                },
            },
            messages: {
                txt_nombre_empresa_contrato: {
                    required: "Por favor ingrese el nombre de la empresa",
                },
                txt_copia_contrato: {
                    required: "Por favor suba la copia de su contrato",
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

        //Validación Estado Laboral
        $("#form_estado_laboral").validate({
            rules: {
                ddl_estado_laboral: {
                    required: true,
                },
                txt_fecha_contratacion_estado: {
                    required: true,
                },
                txt_fecha_salida_estado: {
                    required: true,
                },
            },
            messages: {
                ddl_estado_laboral: {
                    required: "Por favor seleccione su estado laboral",
                },
                txt_fecha_contratacion_estado: {
                    required: "Por favor ingrese la fecha de su contratación",
                },
                txt_fecha_salida_estado: {
                    required: "Por favor ingrese la fecha de su salida",
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

        //Validación Idiomas
        $("#form_agregar_idioma").validate({
            rules: {
                ddl_seleccionar_idioma: {
                    required: true,
                },
                ddl_dominio_idioma: {
                    required: true,
                },
            },
            messages: {
                ddl_seleccionar_idioma: {
                    required: "Por favor seleccione un idioma",
                },
                ddl_dominio_idioma: {
                    required: "Por favor seleccione su dominio con el idioma",
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

        //Validación Aptitudes
        $("#form_aptitudes").validate({
            rules: {
                ddl_seleccionar_aptitud_blanda: {
                    required: true,
                },
                ddl_seleccionar_aptitud_tecnica: {
                    required: true,
                },
            },
            messages: {
                ddl_seleccionar_aptitud_blanda: {
                    required: "Por favor eliga al menos una aptitud blanda",
                },
                ddl_seleccionar_aptitud_tecnica: {
                    required: "Por favor eliga al menos una aptitud técnica",
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

        //Validación Documento de Identidad
        $("#form_documento_identidad").validate({
            rules: {
                ddl_tipo_documento_identidad: {
                    required: true,
                },
                txt_agregar_documento_identidad: {
                    required: true,
                },
            },
            messages: {
                ddl_tipo_documento_identidad: {
                    required: "Por favor eliga el documento de identidad que va a subir",
                },
                txt_agregar_documento_identidad: {
                    required: "Por favor suba su documento de identidad",
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