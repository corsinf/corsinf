<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$redireccionar_vista = 'index';

//Para obtener el id de la persona que solicita la firma (No concurrente)
$_id = isset($_SESSION['INICIO']['NO_CONCURENTE']) ? $_SESSION['INICIO']['NO_CONCURENTE'] : null;

// if (empty($_id)) {
//     $_id = '';
// }

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
    function redireccionar(url_redireccion) {
        url_click = "inicio.php?mod=<?= $modulo_sistema ?>&acc=" + url_redireccion;
        window.location.href = url_click;
    }
</script>

<?php if (
    $_SESSION['INICIO']['TIPO'] == 'DBA' ||
    $_SESSION['INICIO']['TIPO'] == 'ADMINISTRADOR'
) { ?>

    <script>
        $(document).ready(function() {
            escribir_correo();
            cargar_datos_ddl_formulario();
        });

        function escribir_correo() {
            // Variables de elementos
            var lbl_correo_index = $('#lbl_correo_index');
            var txt_correo_index = $('#txt_correo_index');
            var txt_dominio = $('#txt_dominio');
            var ddl_dominio = $('#ddl_dominio');


            lbl_correo_index.html(txt_correo_index.val() + ddl_dominio.val());

            function actualizar_correo() {
                lbl_correo_index.html(txt_correo_index.val() + (ddl_dominio.val() === 'otro' ? txt_dominio.val() : ddl_dominio.val()));
            }

            txt_correo_index.on('input', actualizar_correo);
            txt_dominio.on('input', actualizar_correo);
            ddl_dominio.change(actualizar_correo);

            ddl_dominio.change(function() {
                var dominio = $(this).val();
                if (dominio === 'otro') {
                    txt_dominio.show();
                } else {
                    txt_dominio.hide();
                }
            });
        }

        function crear_persona_firma() {
            var txt_correo_index = $('#txt_correo_index').val();
            var ddl_formulario = $('#ddl_formulario').val();
            var ddl_tiempo_vigencia = $('#ddl_tiempo_vigencia').val();
            var lbl_correo_index = $('#lbl_correo_index').text();

            let parametros = {
                'txt_correo_index': lbl_correo_index,
                'ddl_formulario': ddl_formulario,
                'ddl_tiempo_vigencia': ddl_tiempo_vigencia,
            };

            if (txt_correo_index != '' &&
                txt_correo_index != null) {
                // Si es válido, puedes proceder a enviar los datos por AJAX
                insertar_administrador(parametros);
            } else {
                Swal.fire('', 'Ingrese un correo', 'warning');
            }
        }

        function insertar_administrador(parametros) {
            $.ajax({
                data: {
                    parametros: parametros

                },
                url: '../controlador/FIRMAS_ELECTRONICAS/fi_personasC.php?insertar_administrador=true',
                type: 'post',
                dataType: 'json',

                success: function(response) {
                    if (response == 1) {
                        Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                            // location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=<?= $redireccionar_vista ?>';
                            $('#txt_correo_index').val('');
                            $('#lbl_correo_index').text('');
                        });
                    } else if (response == -2) {
                        //Swal.fire('', 'Operación fallida', 'warning');
                        $(txt_correo_index).addClass('is-invalid');
                        $('#error_txt_correo_index').text('El correo ya está en uso.');
                        $('#pnl_error_txt_correo_index').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Status: ' + status);
                    console.log('Error: ' + error);
                    console.log('XHR Response: ' + xhr.responseText);

                    Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                }
            });

            $('#txt_correo_index').on('input', function() {
                $('#pnl_error_txt_correo_index').hide();
                $(txt_correo_index).removeClass('is-invalid');
            });
        }

        function cargar_datos_ddl_formulario() {
            $.ajax({
                url: '../controlador/FIRMAS_ELECTRONICAS/fi_cat_formulariosC.php?listar_ddl=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    $('#ddl_formulario').html(response);
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

<?php } ?>



<?php if ($_SESSION['INICIO']['TIPO'] == 'NO CONCURRENTE') { ?>
    <script>
        //Script para las personas no concurrentes
        $(document).ready(function() {
            validar_estado_pasos();
        });

        //////////////////////////////////////////////////
        //Primer paso
        //////////////////////////////////////////////////

        function cargar_datos_persona_index() {
            $.ajax({
                url: '../controlador/FIRMAS_ELECTRONICAS/fi_personasC.php?listar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    $('#txt_primer_nombre').val(response[0].primer_nombre);
                    $('#txt_segundo_nombre').val(response[0].segundo_nombre);
                    $('#txt_primer_apellido').val(response[0].primer_apellido);
                    $('#txt_segundo_apellido').val(response[0].segundo_apellido);
                    $('#txt_fecha_nacimiento').val(response[0].fecha_nacimiento);
                    $('#ddl_nacionalidad').val(response[0].nacionalidad);
                    $('#txt_cedula').val(response[0].cedula);
                    $('#ddl_estado_civil').val(response[0].estado_civil);
                    $('#ddl_sexo').val(response[0].sexo);
                    $('#txt_telefono_1').val(response[0].telefono_1);
                    $('#txt_telefono_2').val(response[0].telefono_2);
                    $('#txt_correo').val(response[0].correo);
                    $('#txt_codigo_postal').val(response[0].postal);
                    $('#txt_direccion').val(response[0].direccion);
                    $('#txt_observaciones').val(response[0].observaciones);

                    calcular_edad('txt_edad', response[0].fecha_nacimiento);

                    //Cargar Selects de provincia-ciudad-parroquia
                    url_provinciaC = '../controlador/GENERAL/th_provinciasC.php?listar=true';
                    cargar_select2_con_id('ddl_provincias', url_provinciaC, response[0].id_provincia, 'th_prov_nombre');

                    url_ciudadC = '../controlador/GENERAL/th_ciudadC.php?listar=true';
                    cargar_select2_con_id('ddl_ciudad', url_ciudadC, response[0].id_ciudad, 'th_ciu_nombre');

                    url_parroquiaC = '../controlador/GENERAL/th_parroquiasC.php?listar=true';
                    cargar_select2_con_id('ddl_parroquia', url_parroquiaC, response[0].id_parroquia, 'th_parr_nombre');

                    $('#txt_correo').attr('readonly', true);
                },
            });
        }

        function editar_validar() {
            let parametros_vista_persona = parametros_persona();

            if ($("#form_datos_personales_solicitud").valid()) {
                // Si es válido, puedes proceder a enviar los datos por AJAX
                editar(parametros_vista_persona);
            }
        }

        function editar(parametros) {
            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/FIRMAS_ELECTRONICAS/fi_personasC.php?editar=true',
                type: 'post',
                dataType: 'json',

                success: function(response) {
                    if (response == 1) {
                        Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                            //location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=<?= $redireccionar_vista ?>';
                            $('#modal_llenar_datos_personas').modal('hide');
                            validar_estado_pasos();
                        });
                    } else if (response == -2) {
                        //Swal.fire('', 'Operación fallida', 'warning');
                        $(txt_cedula).addClass('is-invalid');
                        $('#error_txt_cedula').text('La cédula ya está en uso.');
                    }
                },

                error: function(xhr, status, error) {
                    console.log('Status: ' + status);
                    console.log('Error: ' + error);
                    console.log('XHR Response: ' + xhr.responseText);

                    Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                }
            });

            $('#txt_cedula').on('input', function() {
                $('#error_txt_cedula').text('');
            });
        }

        function abrir_modal_datos_personas() {
            $('#modal_llenar_datos_personas').modal('show');
            cargar_datos_persona_index();
        }

        //////////////////////////////////////////////////
        //Segundo paso
        //////////////////////////////////////////////////
        function abrir_modal_datos_personas_paso_2() {
            $('#modal_llenar_datos_personas_paso_2').modal('show');
            //cargar_datos_persona_index();
        }

        function validar_estado_pasos() {
            $.ajax({
                data: {
                    //parametros: parametros
                },
                url: '../controlador/FIRMAS_ELECTRONICAS/fi_personasC.php?validar_paso_2=true',
                type: 'post',
                dataType: 'json',

                success: function(response) {
                    $('#pnl_llenar_datos_personas_paso_1').show();

                    if (response['realizado'] == 1) $('#pnl_llenar_datos_personas_paso_2').show();
                    if (response['realizado_2'] == 1) $('#pnl_llenar_datos_personas_paso_3').show();

                    if (response['CFomulario_id'] == 2) {
                        $('#pnl_cert_ruc_paso_3').show();
                        $('#pnl_file_cert_ruc').show();
                        $('#file_cert_ruc').rules("add", {
                            required: true
                        });
                    }

                    if (response['CFomulario_id'] == 3) {
                        $('#pnl_cert_juridico_paso_3').show();
                        $('#pnl_file_cert_juridico').show();
                        $('#file_cert_juridico').rules("add", {
                            required: true
                        });
                    }

                    if (response['estado'] == 1) {
                        $('#pnl_alerta_completo').show();
                        $('.pnl_llenar_datos').hide();
                    }

                    let archivos = {
                        '#img_foto_personal': response['archivo_foto'],
                        '#ifr_copia_cedula': response['archivo_cedula'],
                        '#ifr_cert_ruc': response['archivo_ruc'],
                        '#ifr_cert_juridico': response['archivo_juridico']
                    };

                    $.each(archivos, function(selector, archivo) {
                        $(selector).attr('src', archivo);
                    });
                },

                error: function(xhr, status, error) {
                    console.log('Status: ' + status);
                    console.log('Error: ' + error);
                    console.log('XHR Response: ' + xhr.responseText);

                    Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                }
            });
        }

        function guardar_documentos_persona() {
            var form_data = new FormData(document.getElementById("form_documentos_persona")); // Captura todos los campos y archivos

            if ($("#form_documentos_persona").valid()) {
                $.ajax({
                    url: '../controlador/FIRMAS_ELECTRONICAS/fi_personasC.php?agregar_documentos=true',
                    type: 'post',
                    data: form_data,
                    contentType: false,
                    processData: false,

                    dataType: 'json',
                    success: function(response) {
                        //console.log(response);
                        if (response == -1) {
                            Swal.fire({
                                title: '',
                                text: 'Algo extraño ha ocurrido, intente más tarde.',
                                icon: 'error',
                                allowOutsideClick: false,
                                showConfirmButton: true,
                                confirmButtonText: 'Cerrar'
                            });
                        } else if (response == -2) {
                            Swal.fire({
                                title: '',
                                text: 'Asegúrese de que el archivo subido sea uno válido.',
                                icon: 'error',
                                allowOutsideClick: false,
                                showConfirmButton: true,
                                confirmButtonText: 'Cerrar'
                            });
                        } else if (response == 1) {
                            Swal.fire('', 'Operación realizada con éxito.', 'success');

                            $('#modal_llenar_datos_personas_paso_2').modal('hide');
                            validar_estado_pasos();
                        }
                    }
                });
            }
        }

        //////////////////////////////////////////////////
        //Tercer paso
        //////////////////////////////////////////////////

        function abrir_modal_datos_personas_paso_3() {
            $('#modal_llenar_datos_personas_paso_3').modal('show');
        }

        function guardar_aceptacion() {
            if ($('#cbx_aceptacion').is(':checked')) {
                $.ajax({
                    url: '../controlador/FIRMAS_ELECTRONICAS/fi_personasC.php?guardar_aceptacion=true',
                    type: 'post',
                    dataType: 'json',

                    success: function(response) {
                        if (response == 1) {
                            Swal.fire('', 'Estaremos en contacto contigo.', 'success');
                            $('#pnl_alerta_completo').show();

                            $('.pnl_llenar_datos').hide();

                            $('#modal_llenar_datos_personas_paso_3').modal('hide');

                        } else if (response == -2) {
                            Swal.fire('', 'Operación fallida', 'warning');
                        }
                    },

                    error: function(xhr, status, error) {
                        console.log('Status: ' + status);
                        console.log('Error: ' + error);
                        console.log('XHR Response: ' + xhr.responseText);

                        Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                    }
                });
            } else {
                Swal.fire('', 'Es necesario seleccionar la casilla, de lo contrario no podrá continuar con el proceso.', 'error');
            }
        }
    </script>
<?php } ?>

<style>
    #ddl_dominio {
        width: auto;
        max-width: 145px;
        /* Establece un ancho máximo para evitar que se expanda demasiado */
    }
</style>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Inicio</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"></li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-xl-12 mx-auto">

                <?php if (
                    $_SESSION['INICIO']['TIPO'] == 'DBA'
                ) { ?>

                    <!-- Vista para el administrador -->
                    <div class="row">

                        <div class="col-12 col-sm-6 col-md-6" id="pnl_agregar_personas">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <h4 class="mb-0">Agregar y Enviar Correo</h4>
                                    <hr />

                                    <div class="row mb-col">
                                        <div class="col-md-6">
                                            <label for="ddl_formulario" class="form-label form-label-sm">Tipo de Certificado </label>
                                            <div>
                                                <select id="ddl_formulario" class="form-select form-select-sm" aria-label="Seleccionar Certificado">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="ddl_tiempo_vigencia" class="form-label form-label-sm">Vigencia del Certificado </label>
                                            <select id="ddl_tiempo_vigencia" class="form-select form-select-sm" aria-label="Seleccionar Vigencia">
                                                <option value="1">1 año</option>
                                                <option value="2">2 años</option>
                                                <option value="3">3 años</option>
                                                <option value="4">4 años</option>
                                                <option value="5">5 años</option>
                                                <option value="0">Corta Vigencia</option>
                                            </select>
                                        </div>
                                    </div>

                                    <label for="ddl_dominio" class="form-label form-label-sm">Correo </label>
                                    <div class="row mb-col">
                                        <div class="col-12 col-md-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm" id="txt_correo_index" name="txt_correo_index" placeholder="Escriba del correo" aria-label="Text input with dropdown button">
                                                <input type="text" class="form-control form-control-sm" id="txt_dominio" placeholder="@" value="@" style="display:none;">
                                                <select id="ddl_dominio" class="form-select form-select-sm" aria-label="Seleccionar dominio">
                                                    <option value="@gmail.com">@gmail.com</option>
                                                    <option value="@hotmail.com">@hotmail.com</option>
                                                    <option value="@outlook.com">@outlook.com</option>
                                                    <option value="@yahoo.com">@yahoo.com</option>
                                                    <option value="@icloud.com">@icloud.com</option>
                                                    <option value="@gob.es">@gob.es</option>
                                                    <option value="@hotmail.es">@hotmail.es</option>
                                                    <option value="otro">Otro</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3 d-grid text-end">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="crear_persona_firma();">Agregar</button>
                                        </div>
                                    </div>
                                    <div id="pnl_error_txt_correo_index" style="display: none;">
                                        <label for="txt_correo_index" class="error" id="error_txt_correo_index"></label>
                                        <br>
                                    </div>
                                    <label class="text-primary fw-bold" id="lbl_correo_index">Correo</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-4" id="pnl_personas" onclick="redireccionar('fir_personas');">
                            <div class="card radius-10 shadow-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Personas</p>
                                            <h4 class="my-1" id="lbl_pacientes">0</h4>
                                        </div>
                                        <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bx-group'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4" id="pnl_solicitudes" onclick="redireccionar('fir_mis_solicitudes');">
                            <div class="card radius-10 shadow-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Solicitudes</p>
                                            <h4 class="my-1" id="lbl_pacientes">0</h4>
                                        </div>
                                        <div class="widgets-icons bg-light-success text-success ms-auto"><i class='bx bx-cog'></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if (
                    $_SESSION['INICIO']['TIPO'] == 'DBA' ||
                    $_SESSION['INICIO']['TIPO'] == 'NO CONCURRENTE'
                ) { ?>

                    <h6 class="mb-0 text-uppercase">DASHBOARD</h6>
                    <hr>

                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-6" id="pnl_alerta_completo" style="display: none;">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <!-- <h4 class="mb-0 text-primary">
                                        Estaremos en contacto contigo...
                                    </h4> -->

                                    <!-- Imagen responsive -->
                                    <img src="../img/Firmas/paso_4.jpg" class="img-fluid w-100" id="img_postulante_inf_modal" alt="Paso 4" />

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-6 pnl_llenar_datos" id="pnl_llenar_datos_personas_paso_1" onclick="abrir_modal_datos_personas();" style="display: none;">
                            <div class="card radius-10 shadow-card">
                                <div class="card-body">
                                    <!-- <h4 class="mb-0 text-primary">
                                        Paso 1: Completa tu información para continuar
                                    </h4>
                                    <span class="small text-danger">(Los campos marcados con asterisco rojo son obligatorios)</span> -->


                                    <!-- Imagen responsive -->
                                    <img src="../img/Firmas/paso_1.jpg" class="img-fluid w-100" id="img_postulante_inf_modal" alt="Paso 1" />
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6 pnl_llenar_datos" id="pnl_llenar_datos_personas_paso_2" onclick="abrir_modal_datos_personas_paso_2();" style="display: none;">
                            <div class="card radius-10 shadow-card">
                                <div class="card-body">
                                    <!-- <h4 class="mb-0 text-primary">
                                        Paso 2: Subir la documentación necesaria para la emisión del Certificado (Firma)
                                    </h4>
                                    <span class="small text-danger">(Por favor subir los documentos necesarios)</span> -->


                                    <!-- Imagen responsive -->
                                    <img src="../img/Firmas/paso_2.jpg" class="img-fluid w-100" id="img_postulante_inf_modal" alt="Paso 2" />
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6 pnl_llenar_datos" id="pnl_llenar_datos_personas_paso_3" onclick="abrir_modal_datos_personas_paso_3();" style="display: none;">
                            <div class="card radius-10 shadow-card">
                                <div class="card-body">
                                    <!-- <h4 class="mb-0 text-primary">
                                        Paso 3: Revisar la Información
                                    </h4>
                                    <span class="small text-danger">(Si todo es correcto, acepte los términos y condiciones.)</span> -->

                                    <!-- Imagen responsive -->
                                    <img src="../img/Firmas/paso_3.jpg" class="img-fluid w-100" id="img_postulante_inf_modal" alt="Paso " />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para la informacion personal -->
                    <div class="modal modal_general_provincias" id="modal_llenar_datos_personas" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5><small class="text-body-secondary fw-bold">Paso 1: Completa tu información para continuar</small></h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form id="form_datos_personales_solicitud">

                                        <?php include_once('../vista/GENERAL/registrar_personas.php'); ?>

                                        <div class="d-flex justify-content-end pt-2">
                                            <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center" onclick="editar_validar();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 2 -->
                    <div class="modal" id="modal_llenar_datos_personas_paso_2" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5><small class="text-body-secondary fw-bold">Paso 2: Subir la documentación necesaria para la emisión del Certificado (Firma)</small></h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form id="form_documentos_persona">
                                        <div class="row mb-col" id="pnl_file_foto_personal">
                                            <div class="col-md-12">
                                                <label for="file_foto_personal" class="form-label form-label-sm"> 1. Foto personal con la cédula colocada debajo del mentón. </label>
                                                <input type="file" class="form-control form-control-sm" name="file_foto_personal" id="file_foto_personal" accept=".jpg, .jpeg, .png">
                                            </div>
                                        </div>

                                        <div class="row mb-col" id="pnl_file_copia_cedula">
                                            <div class="col-md-12">
                                                <label for="file_copia_cedula" class="form-label form-label-sm">2. Copia legible de la cédula. </label>
                                                <input type="file" class="form-control form-control-sm" name="file_copia_cedula" id="file_copia_cedula" accept=".pdf">
                                            </div>
                                        </div>

                                        <div class="row mb-col" id="pnl_file_cert_ruc" style="display: none;">
                                            <div class="col-md-12">
                                                <label for="file_cert_ruc" class="form-label form-label-sm">3. Certificado actualizado del RUC. </label>
                                                <input type="file" class="form-control form-control-sm" name="file_cert_ruc" id="file_cert_ruc" accept=".pdf">
                                            </div>
                                        </div>

                                        <div class="row mb-col" id="pnl_file_cert_juridico" style="display: none;">
                                            <div class="col-md-12">
                                                <label for="file_cert_juridico" class="form-label form-label-sm">4. Certificado Jurídico. </label>
                                                <input type="file" class="form-control form-control-sm" name="file_cert_juridico" id="file_cert_juridico" accept=".pdf">
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end pt-2">
                                            <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center" onclick="guardar_documentos_persona();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 3 -->
                    <div class="modal" id="modal_llenar_datos_personas_paso_3" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5><small class="text-body-secondary fw-bold">Paso 3: Revisar la Información</small></h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form id="form_documentos_persona">
                                        <div class="row mb-col">
                                            <div class="col-md-3" id="pnl_foto_personal_paso_3">
                                                <p class="form-label form-labeli-sm">1. Foto personal con la cédula colocada debajo del mentón.</p>
                                                <img src="" class="img-fluid w-100 border rounded shadow-sm" id="img_foto_personal" alt="Ejemplo Foto Personal" />
                                            </div>

                                            <div class="col-md-3" id="pnl_copia_cedula_paso_3">
                                                <p class="form-label form-label-sm">2. Copia legible de la cédula. </p>
                                                <iframe src="" class="border rounded shadow-sm" id="ifr_copia_cedula" frameborder="0" width="100%" height="200px"></iframe>
                                            </div>

                                            <div class="col-md-3" id="pnl_cert_ruc_paso_3" style="display: none;">
                                                <p class="form-label form-label-sm">3. Certificado actualizado del RUC. </p>
                                                <iframe src="" class="border rounded shadow-sm" id="ifr_cert_ruc" frameborder="0" width="100%" height="200px"></iframe>
                                            </div>

                                            <div class="col-md-3" id="pnl_cert_juridico_paso_3" style="display: none;">
                                                <p class="form-label form-label-sm">4. Certificado Jurídico. </p>
                                                <iframe src="" class="border rounded shadow-sm" id="ifr_cert_juridico" frameborder="0" width="100%" height="200px"></iframe>
                                            </div>

                                        </div>

                                        <div class="row mb-col">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" name="cbx_aceptacion" id="cbx_aceptacion">
                                                    <label class="form-label" for="cbx_aceptacion">
                                                        ¿Toda la información es correcta?
                                                        <br>
                                                        <small class="text-success">Al marcar esta casilla, usted autoriza que la información proporcionada sea utilizada para fines relacionados.</small>
                                                    </label>

                                                </div>
                                                <br>
                                                <small class="text-danger">(Si algún documento es incorrecto, por favor regrese al Paso 2 para corregirlo.)</small>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end pt-2">
                                            <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center" onclick="guardar_aceptacion();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>



<!-- Estilos para redireccionar -->
<script>
    $(document).ready(function() {
        $('.shadow-card').on('mouseover', function() {
            $(this).addClass('hoverEffect');
        });

        $('.shadow-card').on('mouseout', function() {
            $(this).removeClass('hoverEffect');
        });

        $('.shadow-card').on('click', function() {
            $(this).toggleClass('clickedEffect');
        });

        $(document).on('mouseout', '.shadow-card', function() {
            $(this).removeClass('clickedEffect');
        });

    });
</script>

<style>
    .card {
        cursor: pointer;
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    .card.hoverEffect {
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        background-color: rgba(45, 216, 34, 0.1);
    }

    .card.clickedEffect {
        background-color: rgba(128, 224, 122, 0.5);
    }
</style>
<!-- End -->

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('ddl_formulario');
        agregar_asterisco_campo_obligatorio('ddl_tiempo_vigencia');
        agregar_asterisco_campo_obligatorio('ddl_dominio');

        agregar_asterisco_campo_obligatorio('file_foto_personal');
        agregar_asterisco_campo_obligatorio('file_copia_cedula');
        agregar_asterisco_campo_obligatorio('file_cert_ruc');
        agregar_asterisco_campo_obligatorio('file_cert_juridico');

        //* Validacion de formulario
        $("#form_datos_personales_solicitud").validate({
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
                    //required: true,
                },
                txt_telefono_1: {
                    required: true,
                },
                txt_telefono_2: {
                    //required: true,
                },
                txt_correo_index: {
                    required: true,
                },
                ddl_nacionalidad: {
                    //required: true,
                },
                ddl_estado_civil: {
                    //required: true,
                },
                ddl_provincias: {
                    required: true,
                },
                ddl_ciudad: {
                    required: true,
                },
                ddl_parroquia: {
                    //required: true,
                },
                txt_codigo_postal: {
                    required: true,
                },
                txt_direccion: {
                    //required: true,
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
                    required: "Por favor ingrese un número de teléfono",
                },
                txt_telefono_2: {
                    required: "Por favor ingrese un número de teléfono",
                },
                txt_correo_index: {
                    required: "Por favor ingrese un correo electrónico",
                },
                ddl_nacionalidad: {
                    required: "Por favor seleccione una nacionalidad",
                },
                ddl_estado_civil: {
                    required: "Por favor seleccione un estado civil",
                },
                ddl_provincias: {
                    required: "Por favor seleccione una provincia",
                },
                ddl_ciudad: {
                    required: "Por favor seleccione una ciudad",
                },
                ddl_parroquia: {
                    required: "Por favor seleccione una parroquia",
                },
                txt_codigo_postal: {
                    required: "Por favor ingrese una dirección postal",
                },
                txt_direccion: {
                    required: "Por favor ingrese una dirección",
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

        //* Validacion de formulario
        $("#form_documentos_persona").validate({
            rules: {
                file_foto_personal: {
                    required: true,
                },
                file_copia_cedula: {
                    required: true,
                },
                // file_cert_ruc: {
                //     required: true,
                // },
                // file_cert_juridico: {
                //     required: true,
                // },
            },
            messages: {

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