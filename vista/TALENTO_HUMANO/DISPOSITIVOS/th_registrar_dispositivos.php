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
            url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#ddl_modelo').val(response[0].modelo);
                $('#txt_nombre').val(response[0].nombre);
                $('#txt_host').val(response[0].host);
                $('#txt_puerto').val(response[0].port);
                $('#txt_serial').val(response[0].serial);
                $('#txt_usuario').val(response[0].usuario);
                $('#txt_pass').val(response[0].pass);
                $('#cbx_ssl').prop('checked', (response[0].ssl == 1));

            }
        });
    }

    function editar_insertar() {
        var ddl_modelo = $('#ddl_modelo').val();
        var txt_nombre = $('#txt_nombre').val();
        var txt_host = $('#txt_host').val();
        var txt_puerto = $('#txt_puerto').val();
        var txt_serial = $('#txt_serial').val();
        var txt_usuario = $('#txt_usuario').val();
        var txt_pass = $('#txt_pass').val();
        var cbx_ssl = $('#cbx_ssl').prop('checked') ? 1 : 0;

        var parametros = {
            '_id': '<?= $_id ?>',
            'ddl_modelo': ddl_modelo,
            'txt_nombre': txt_nombre,
            'txt_host': txt_host,
            'txt_puerto': txt_puerto,
            'txt_serial': txt_serial,
            'txt_usuario': txt_usuario,
            'txt_pass': txt_pass,
            'cbx_ssl': cbx_ssl,
        };

        if ($("#form_dispositivo").valid()) {
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
            url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_dispositivos';
                    });
                } else if (response == -2) {
                    //Swal.fire('', 'El nombre del dispositivo ya está en uso', 'warning');
                    $(txt_nombre).addClass('is-invalid');
                    $('#error_txt_nombre').text('El nombre del dispositivo ya está en uso.');
                }
            },
            
            error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_nombre').on('input', function() {
            $('#error_txt_nombre').text('');
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
            url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_dispositivos';
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
            <div class="breadcrumb-title pe-3">Dispositivos</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar Dispositivo
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
                                    echo 'Registrar Dispositivo';
                                } else {
                                    echo 'Modificar Dispositivo';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_dispositivos" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_dispositivo">

                            <div class="row pt-3 mb-col">
                                <div class="col-md-4">
                                    <label for="ddl_modelo" class="form-label">Modelo </label>
                                    <select class="form-select form-select-sm" id="ddl_modelo" name="ddl_modelo">
                                        <option selected disabled>-- Seleccione --</option>
                                        <option value="1">HIK</option>
                                        <option value="2">Vision</option>
                                    </select>
                                </div>

                                <div class="col-md-8">
                                    <label for="txt_nombre" class="form-label">Nombre </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_nombre" name="txt_nombre" maxlength="50">
                                    <span id="error_txt_nombre" class="text-danger"></span>
                                </div>

                            </div>

                            <div class="row mb-col">
                                <div class="col-md-4 ">
                                    <label for="txt_host" class="form-label">IP/Host </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_host" name="txt_host" maxlength="50" oninput="texto_minusculas(this);">
                                </div>

                                <div class="col-md-2 ">
                                    <label for="txt_puerto" class="form-label">Puerto </label>
                                    <input type="text" class="form-control form-control-sm solo_numeros_int" id="txt_puerto" name="txt_puerto" maxlength="4">
                                </div>

                                <div class="col-md-6 ">
                                    <label for="txt_serial" class="form-label">Número de Serie </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_serial" name="txt_serial" maxlength="100">
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-4 ">
                                    <label for="txt_usuario" class="form-label">Usuario </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" id="txt_usuario" name="txt_usuario" maxlength="50">
                                </div>

                                <div class="col-md-8 ">
                                    <label for="txt_pass" class="form-label">Contraseña </label>
                                    <input type="text" class="form-control form-control-sm" id="txt_pass" name="txt_pass" maxlength="50">
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" name="cbx_ssl" id="cbx_ssl">
                                        <label class="form-label" for="cbx_ssl">SSL </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-12">

                                    <button class="btn btn-primary btn-sm px-4" onclick="probar_conexion()" type="button"><i class="lni lni-play fs-6 me-0"></i> Probar conexión</button>
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

        agregar_asterisco_campo_obligatorio('ddl_modelo');
        agregar_asterisco_campo_obligatorio('txt_nombre');

        $("#form_dispositivo").validate({
            rules: {
                ddl_modelo: {
                    required: true,
                },
                txt_nombre: {
                    required: true,
                },
                txt_host: {

                },
                txt_puerto: {
                    digits: true,
                    maxlength: 4,
                    minlength: 1
                },
            },
            messages: {
                ddl_modelo: {
                    required: "El campo 'Modelo' es obligatorio",
                },
                txt_nombre: {
                    required: "El campo 'Nombre' es obligatorio",
                },
                txt_host: {
                    required: "El campo 'IP/Host' es obligatorio",
                },
                txt_puerto: {
                    digits: "El campo 'Puerto' permite solo números",
                }
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

    function probar_conexion()
    {
        var txt_host = $('#txt_host').val();
        var txt_puerto = $('#txt_puerto').val();
        var txt_usuario = $('#txt_usuario').val();
        var txt_pass = $('#txt_pass').val();
        if(txt_pass=='' || txt_usuario=='' || txt_host=='')
        {
            Swal.fire("Ingrese todos los datos","","info")
            return false;
        }

        var parametros = {
            'txt_host': txt_host,
            'txt_puerto': txt_puerto,
            'txt_usuario': txt_usuario,
            'txt_pass': txt_pass,
        };

        $('#myModal_espera').modal('show');
        $('#lbl_msj_espera').text("Probando conexion");
         $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?ProbarConexion=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                $('#myModal_espera').modal('hide');
                if (response.resp == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success');
                } else{
                    Swal.fire('No se pudo conectar', response.msj, 'error')
                }
            },
            
            error: function(xhr, status, error) {
                console.log('Status: ' + status); 
                console.log('Error: ' + error); 
                console.log('XHR Response: ' + xhr.responseText); 

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                $('#myModal_espera').modal('hide');
            }
        });


    }

</script>