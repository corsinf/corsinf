<script>
    $(document).ready(function() {
        cargar_datos_v_config();
        $('#txt_salida_pw').html('response');
    });

    function cargar_datos_v_config() {
        $.ajax({
            url: '../controlador/SALUD_INTEGRAL/cat_configuracionGC.php?listar_config_idukay_cron=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);

                // Limpiar el contenido previo del div
                $('#pnl_config_general').empty();

                // Verificar si la respuesta es un array y tiene al menos 5 elementos
                if (Array.isArray(response) && response.length >= 6) {
                    const [
                        nombre_modulo,
                        nombre_empresa,
                        url_guardar_bat,
                        script_php_motor,
                        motor_bat,
                        hora_ejecucion_PW,
                    ] = response;

                    $('#txt_nombre_modulo').val(nombre_modulo.sa_config_valor || '');
                    $('#txt_nombre_empresa').val(nombre_empresa.sa_config_valor || '');
                    //$('#txt_url_guardar_bat').val(url_guardar_bat.sa_config_valor || '');
                    $('#txt_script_php_motor').val(script_php_motor.sa_config_valor || '');
                    $('#txt_motor_bat').val(motor_bat.sa_config_valor || '');
                    $('#txt_hora_PW').val(hora_ejecucion_PW.sa_config_valor || '');
                } else {
                    // Mensaje de error si la respuesta no es válida
                    $('#pnl_config_general').append('<p>No se encontraron configuraciones suficientes.</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Manejo de errores
                console.error('Error al cargar los configs:', textStatus, errorThrown);
                $('#pnl_config_general').append('<p>Error al cargar las configuraciones. Por favor, inténtalo de nuevo más tarde.</p>');
            }
        });
    }

    function editar_insertar() {
        var txt_nombre_modulo = $('#txt_nombre_modulo').val();
        var txt_nombre_empresa = $('#txt_nombre_empresa').val();
        var txt_url_guardar_bat = $('#txt_url_guardar_bat').val();
        var txt_script_php_motor = $('#txt_script_php_motor').val();
        var txt_motor_bat = $('#txt_motor_bat').val();
        var txt_hora_PW = $('#txt_hora_PW').val();

        var parametros = {
            'txt_nombre_modulo': txt_nombre_modulo,
            'txt_nombre_empresa': txt_nombre_empresa,
            'txt_url_guardar_bat': txt_url_guardar_bat,
            'txt_script_php_motor': txt_script_php_motor,
            'txt_motor_bat': txt_motor_bat,
            'txt_hora_PW': txt_hora_PW,
        };

        console.log(parametros);

        insertar(parametros)
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/SALUD_INTEGRAL/cat_configuracionGC.php?editar_config_idukay_cron=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=7&acc=configuraciones_idukay';
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Cédula ya registrada.', 'warning');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Manejo de errores
                console.error('Error al editar los configs:', textStatus, errorThrown);
                $('#pnl_config_general').append('<p>Error al cargar las configuraciones. Por favor, inténtalo de nuevo más tarde.</p>');
            }
        });
    }

    function crear_archivos_CRON() {
        $.ajax({
            // data: {
            //     parametros: parametros
            // },
            url: '../controlador/SALUD_INTEGRAL/cat_configuracionGC_IDUKAY.php?crear_documentos_CRON=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                console.log(response);
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=7&acc=configuraciones_idukay';
                    });
                }
            },
        });
    }

    function ejecutar_PW_PT() {
        $.ajax({
            // data: {
            //     parametros: parametros
            // },
            url: '../controlador/SALUD_INTEGRAL/cat_configuracionGC_IDUKAY.php?ejecutar_PW_programador_tareas=true',
            type: 'post',
            dataType: 'text',

            success: function(response) {
                console.log('response');

                console.log(response);

                $('#txt_salida_pw').html(response);

            },
        });
    }

    function mostrar_logs() {
        $.ajax({
            // data: {
            //     parametros: parametros
            // },
            url: '../controlador/SALUD_INTEGRAL/cat_configuracionGC_IDUKAY.php?leer_archivo_log=true',
            type: 'post',
            dataType: 'text',

            success: function(response) {
                console.log('response');

                console.log(response);

                $('#txt_salida_pw').html(response);
            },
        });
    }
</script>


<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Accesos</div>
            <?php
            //print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Idukay
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

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">Configuraciones Idukay</h5>

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                    <!-- Para agregar botones -->

                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="row" id="pnl_config_general">

                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="row pt-4">
                                    <div class="col-8">
                                        <div class="row mb-3">
                                            <label for="txt_nombre_modulo" class="col-sm-4 col-form-label">Nombre del Módulo</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="txt_nombre_modulo" placeholder="ENFERMERIA">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="txt_nombre_empresa" class="col-sm-4 col-form-label">Nombre de la Empresa</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="txt_nombre_empresa" placeholder="SALUD_DESARROLLO">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="txt_url_guardar_bat" class="col-sm-4 col-form-label">URL Archivo .bat</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="txt_url_guardar_bat" value="<?= dirname(__DIR__, 3) . '\CRON'?>" placeholder="C:\xampp\htdocs\corsinf\CRON" disabled>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="txt_script_php_motor" class="col-sm-4 col-form-label">Script PHP</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="txt_script_php_motor" placeholder="idukay_actualizacion_datos_saint.php">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="txt_motor_bat" class="col-sm-4 col-form-label">Motor .bat</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="txt_motor_bat" placeholder="SD">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="txt_motor_bat" class="col-sm-4 col-form-label">Hora Ejecución PW</label>
                                            <div class="col-sm-8">
                                                <input type="time" class="form-control form-control-sm" id="txt_hora_PW" placeholder="08:30">
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-success btn-sm px-4" onclick="editar_insertar()"><i class="bx bx-save"></i> Guardar</button>
                                        </div>

                                    </div>

                                    <div class="col-4 col-md-12 col-lg-4">
                                        <div class="card text-start">
                                            <div class="card-body">
                                                <h4 class="card-title">Acciones</h4>
                                                <p class="card-text">Para ejecutar CRON</p>

                                                <div class="d-flex justify-content-start m-4">
                                                    <button type="button" class="btn btn-success btn-sm px-2" onclick="crear_archivos_CRON()"><i class='bx bx-folder-plus'></i> Crear CRON</button>
                                                </div>

                                                <div class="d-flex justify-content-start m-4">
                                                    <button type="button" class="btn btn-success btn-sm px-2" onclick="ejecutar_PW_PT()"><i class='bx bx-task'></i> Ejecutar CRON PW</button>
                                                </div>

                                                <div class="d-flex justify-content-start m-4">
                                                    <button type="button" class="btn btn-success btn-sm px-2" onclick="mostrar_logs()"><i class='bx bx-book-open'></i> Mostrar LOGs</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-12" id="txt_salida_pw">
                                        <textarea class="form-control" name="txt_salida_pw_1" id="txt_salida_pw_1"></textarea>
                                    </div>
                                </div>
                            </div><!-- /.container-fluid -->
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>