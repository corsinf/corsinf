<?php


?>

<script type="text/javascript">
    $(document).ready(function() {
        cargar_datos_v_config();
        //cargar_estudiantes1();
    });

    function cargar_datos_v_config() {
        $.ajax({
            url: '../controlador/cat_configuracionGC.php?listar_config_general=true',
            type: 'post',
            // data: {
            //     accion: 'correos'
            // },
            dataType: 'json',
            success: function(response) {
                console.log(response);

                // Limpiar el contenido previo del div
                $('#pnl_config_general').empty();

                // Verificar si la respuesta contiene datos
                if (response && response.length > 0) {
                    response.forEach(function(config) {
                        // Crear el HTML para cada config
                        var isChecked = config.sa_config_estado == 1 ? 'checked' : '';
                        var htmlconfig = '<div class="col-md-12">';
                        htmlconfig += '<input type="checkbox" class="config-checkbox" name="config[]" id="' + config.sa_config_validar + '" value="' + config.sa_config_id + '" ' + isChecked + '> ';
                        htmlconfig += '<label>' + config.sa_config_descripcion + '</label>';
                        htmlconfig += '</div>';

                        // Agregar el HTML generado al div
                        $('#pnl_config_general').append(htmlconfig);
                    });

                    // Agregar evento change a los checkboxes generados
                    $('.config-checkbox').change(function() {
                        var sa_config_id = $(this).val();
                        var sa_config_estado = $(this).is(':checked') ? 1 : 0;
                        insertar(sa_config_id, sa_config_estado);
                    });
                }
            },
            error: function() {
                // Manejo de errores
                $('#pnl_config_general').append('<p>Error al cargar los configs.</p>');
            }
        });
    }

    function insertar(sa_config_id, sa_config_estado) {

        var parametros = {
            'sa_config_id': sa_config_id,
            'sa_config_estado': sa_config_estado
        };

        //console.log(parametros);

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/cat_configuracionGC.php?vista_mod=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                } else if (response == -2) {
                    Swal.fire('', 'Código ya registrado', 'error');
                } else {
                    Swal.fire('', 'Algo salió mal, intente nuevamente.', 'error');
                }
            },
            error: function() {
                Swal.fire('', 'Error en la conexión con el servidor.', 'error');
            }
        });
    }

    function cargar_estudiantes_tabla() {
        $('#pnl_tbl_estudiates').show();
        // Mostrar el spinner usando SweetAlert2
        Swal.fire({
            title: 'Por favor, espere',
            text: 'Procesando la solicitud...',
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading();
            }
        });


        $.ajax({
            url: '../controlador/cat_configuracionGC_IDUKAY.php?listar_idukay_estudiantes=true',
            type: 'post',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                Swal.close();
                // Inicializar DataTable con la configuración requerida
                $('#tabla_estudiantes').DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    responsive: true,
                    data: data.response,
                    columns: [{
                            data: '_id'
                        },
                        {
                            data: 'user.surname'
                        },

                        {
                            data: null,
                            render: function(data) {
                                if (data.user.second_surname == null) {
                                    return '';

                                } else {
                                    return data.user.second_surname;
                                }

                            }
                        },
                        {
                            data: 'user.name'
                        },
                        {
                            data: null,
                            render: function(data) {
                                if (data.user.second_name == null) {
                                    return '';

                                } else {
                                    return data.user.second_name;
                                }

                            }
                        },
                        {
                            data: 'user.id_card'
                        },
                        {
                            data: 'user.gender',
                            render: function(data) {
                                return data === 'M' ? 'Masculino' : 'Femenino';
                            }
                        },
                        {
                            data: 'user.birthday',
                            render: function(data) {
                                return new Date(data * 1000).toLocaleDateString();
                            }

                        },
                        {
                            data: 'user.email',
                            defaultContent: '' // Si el campo está vacío, mostrará una cadena vacía en lugar de 'null'
                        },
                        {
                            data: null,
                            render: function(data) {
                                if (data.user.address == null) {
                                    return '';
                                } else {
                                    return data.user.address;
                                }

                            }
                        },
                        {
                            data: 'relatives',
                            render: function(data) {
                                var html = '';
                                data.forEach(function(rel, index) {
                                    html += '<p><strong>Pariente ' + (index + 1) + ':</strong></p>';
                                    html += '<p><strong>Nombre:</strong> ' + rel.relationship + '</p>';
                                    html += '<p><strong>ID:</strong> ' + rel.parent + '</p>';
                                });
                                return html;
                            }
                        }
                    ]
                });
            },
            error: function() {
                // Manejo de errores
                $('#pnl_idukay').append('<p>Error al cargar los estudiantes.</p>');
            }
        });


    }

    function cargar_estudiantes2() {
        $.ajax({
            url: '../controlador/cat_configuracionGC_IDUKAY.php?idukay_estudiantes=true',
            type: 'post',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                var estudiantesHtml = '';

                // Iterar sobre la respuesta JSON
                data.response.forEach(function(estudiante) {
                    // Aquí puedes acceder a los datos de cada estudiante
                    var id = estudiante._id;
                    var apellido = estudiante.user.surname;
                    var segundoApellido = estudiante.user.second_surname;
                    var primerNombre = estudiante.user.name;
                    var segundoNombre = estudiante.user.second_name;
                    var cedula = estudiante.user.id_card;
                    var sexo = estudiante.user.gender;
                    var fechaNacimiento = new Date(estudiante.user.birthday * 1000).toLocaleDateString();

                    // Datos de los parientes
                    var relativesHtml = '';
                    estudiante.relatives.forEach(function(pariente, index) {
                        relativesHtml += '<p><strong>Pariente ' + (index + 1) + ':</strong></p>';
                        relativesHtml += '<p><strong>Nombre:</strong> ' + pariente.relationship + '</p>';
                        relativesHtml += '<p><strong>ID:</strong> ' + pariente._id + '</p>';
                        // Puedes incluir más datos del pariente si es necesario
                    });

                    var direccion = estudiante.user.address;
                    var correo = estudiante.user.email;

                    estudiantesHtml += '<div class="estudiante">';
                    estudiantesHtml += '<p><strong>ID:</strong> ' + id + '</p>';
                    estudiantesHtml += '<p><strong>Primer Apellido:</strong> ' + apellido + '</p>';
                    estudiantesHtml += '<p><strong>Segundo Apellido:</strong> ' + segundoApellido + '</p>';
                    estudiantesHtml += '<p><strong>Primer Nombre:</strong> ' + primerNombre + '</p>';
                    estudiantesHtml += '<p><strong>Segundo Nombre:</strong> ' + segundoNombre + '</p>';
                    estudiantesHtml += '<p><strong>Cédula:</strong> ' + cedula + '</p>';
                    estudiantesHtml += '<p><strong>Género:</strong> ' + (sexo === 'M' ? 'Masculino' : 'Femenino') + '</p>';
                    estudiantesHtml += '<p><strong>Fecha de nacimiento:</strong> ' + fechaNacimiento + '</p>';
                    estudiantesHtml += relativesHtml;
                    estudiantesHtml += '<p><strong>Correo:</strong> ' + correo + '</p>';
                    estudiantesHtml += '<p><strong>Dirección:</strong> ' + direccion + '</p>';
                    estudiantesHtml += '</div><br><hr>';
                });

                // Append the constructed HTML to the panel
                $('#pnl_idukay').append(estudiantesHtml);
            },
            error: function() {
                // Manejo de errores
                $('#pnl_idukay').append('<p>Error al cargar los estudiantes.</p>');
            }
        });
    }

    function cargar_estudiantes_JSON() {
        $.ajax({
            url: '../controlador/cat_configuracionGC_IDUKAY.php?listar_idukay_estudiantes=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);

                // Contar el número de registros
                var numRegistros = response.response.length;

                // Mostrar el número de registros
                var contadorElement = $('<p></p>').text('Número de registros: ' + numRegistros);
                $('#pnl_idukay').append(contadorElement);

                // Convertir el JSON a una cadena con formato
                var formattedJson = JSON.stringify(response, null, 2);

                // Crear un elemento <pre> para mostrar el JSON con formato
                var preElement = $('<pre></pre>').text(formattedJson);

                // Agregar el <pre> al panel
                $('#pnl_idukay').append(preElement);
            },
            error: function() {
                // Manejo de errores
                $('#pnl_idukay').append('<p>Error al cargar los configs.</p>');
            }
        });
    }


    function sincronizar_estudiantes() {
        Swal.fire({
            title: 'Por favor, espere',
            text: 'Procesando la solicitud...',
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '../controlador/cat_configuracionGC_IDUKAY.php?idukay_estudiantes=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response == 1) {
                    Swal.close();
                    Swal.fire('', 'Copia masiva exitosa.', 'success');
                    $('#pnl_idukay').html('<p>Copia masiva exitosa.</p>');
                } else if (response == -10) {
                    Swal.close();
                    $('#pnl_idukay').html('<p>Error al subir datos a la Base de Datos.</p>');
                    Swal.fire('', 'Error al subir datos a la Base de Datos.', 'error');
                } else if (response == -11) {
                    Swal.close();
                    $('#pnl_idukay').html('<p>Error al conectarse con la API de Idukay.</p>');
                    Swal.fire('', 'Error al conectarse con la API de Idukay.', 'error');
                }

            },
            error: function() {
                // Manejo de errores
                $('#pnl_idukay').html('<p>Error al cargar los configs.</p>');
            }
        });
    }

    function sincronizar_representantes() {
        Swal.fire({
            title: 'Por favor, espere',
            text: 'Procesando la solicitud...',
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '../controlador/cat_configuracionGC_IDUKAY.php?idukay_representantes=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response == 1) {
                    Swal.close();
                    Swal.fire('', 'Copia masiva exitosa.', 'success');
                    $('#pnl_idukay').html('<p>Copia masiva exitosa.</p>');
                } else if (response == -10) {
                    Swal.close();
                    $('#pnl_idukay').html('<p>Error al subir datos a la Base de Datos.</p>');
                    Swal.fire('', 'Error al subir datos a la Base de Datos.', 'error');
                } else if (response == -11) {
                    Swal.close();
                    $('#pnl_idukay').html('<p>Error al conectarse con la API de Idukay.</p>');
                    Swal.fire('', 'Error al conectarse con la API de Idukay.', 'error');
                }

            },
            error: function() {
                // Manejo de errores
                $('#pnl_idukay').html('<p>Error al cargar los configs.</p>');
            }
        });
    }

    function sincronizar_idukay() {
        Swal.fire({
            title: 'Por favor, espere',
            text: 'Procesando la solicitud...',
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '../controlador/cat_configuracionGC_IDUKAY.php?idukay_sincronizar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response == 1) {
                    Swal.close();
                    Swal.fire('', 'Copia masiva exitosa.', 'success');
                    $('#pnl_idukay').html('<p>Copia masiva exitosa.</p>');
                } else if (response == -1) {
                    Swal.close();
                    $('#pnl_idukay').html('<p>Error al subir Estudiantes a la Base de Datos.</p>');
                    Swal.fire('', 'Error al subir datos a la Base de Datos.', 'error');
                } else if (response == -2) {
                    Swal.close();
                    $('#pnl_idukay').html('<p>Error al subir Representantes a la Base de Datos.</p>');
                    Swal.fire('', 'Error al subir datos a la Base de Datos.', 'error');
                }

            },
            error: function() {
                // Manejo de errores
                $('#pnl_idukay').html('<p>Error al cargar los configs.</p>');
            }
        });
    }

    function sincronizar_docentes() {
        Swal.fire({
            title: 'Por favor, espere',
            text: 'Procesando la solicitud...',
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '../controlador/cat_configuracionGC_IDUKAY.php?idukay_docentes=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response == 1) {
                    Swal.close();
                    Swal.fire('', 'Copia masiva exitosa.', 'success');
                    $('#pnl_idukay').html('<p>Copia masiva exitosa.</p>');
                } else if (response == -10) {
                    Swal.close();
                    $('#pnl_idukay').html('<p>Error al subir datos a la Base de Datos.</p>');
                    Swal.fire('', 'Error al subir datos a la Base de Datos.', 'error');
                } else if (response == -11) {
                    Swal.close();
                    $('#pnl_idukay').html('<p>Error al conectarse con la API de Idukay.</p>');
                    Swal.fire('', 'Error al conectarse con la API de Idukay.', 'error');
                }

            },
            error: function() {
                // Manejo de errores
                $('#pnl_idukay').html('<p>Error al cargar los configs.</p>');
            }
        });
    }

    function sincronizar_horarios_docentes() {
        Swal.fire({
            title: 'Por favor, espere',
            text: 'Procesando la solicitud...',
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '../controlador/cat_configuracionGC_IDUKAY.php?idukay_horario_docentes=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response == 1) {
                    Swal.close();
                    Swal.fire('', 'Copia masiva exitosa.', 'success');
                    $('#pnl_idukay').html('<p>Copia masiva exitosa.</p>');
                } else if (response == -10) {
                    Swal.close();
                    $('#pnl_idukay').html('<p>Error al subir datos a la Base de Datos.</p>');
                    Swal.fire('', 'Error al subir datos a la Base de Datos.', 'error');
                } else if (response == -11) {
                    Swal.close();
                    $('#pnl_idukay').html('<p>Error al conectarse con la API de Idukay.</p>');
                    Swal.fire('', 'Error al conectarse con la API de Idukay.', 'error');
                }

            },
            error: function() {
                // Manejo de errores
                $('#pnl_idukay').html('<p>Error al cargar los configs.</p>');
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Enfermería</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Configuración
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">

            <div class="col-12">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Configuración - General</h5>

                        </div>

                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <h5>Configuración General</h5>
                            <br>
                            <div class="row" id="pnl_config_general">

                            </div>

                            <br>
                            <br>
                            <hr>

                            <h5>Configuración Idukay</h5>


                            <br>

                            <h6>Estudiantes - Representantes</h6>
                            <button class="btn btn-primary btn-sm m-1" onclick="sincronizar_estudiantes()" type="button"><i class='bx bx-sync'></i> Sincronizar Estudiantes Idukay</button>

                            <button class="btn btn-primary btn-sm m-1" onclick="sincronizar_representantes()" type="button"><i class='bx bx-sync'></i> Sincronizar Representantes Idukay</button>

                            <button hidden class="btn btn-primary btn-sm m-1" onclick="sincronizar_idukay()" type="button"><i class='bx bx-sync'></i> Sincronizar con Idukay Estudiantes y Representantes</button>


                            <h6 class="pt-3">Docentes</h6>
                            <button class="btn btn-primary btn-sm m-1" onclick="sincronizar_docentes()" type="button"><i class='bx bx-sync'></i> Sincronizar Docentes Idukay</button>
                            <button class="btn btn-primary btn-sm m-1" onclick="sincronizar_horarios_docentes()" type="button"><i class='bx bx-sync'></i> Sincronizar Horarios Docentes Idukay</button>

                            <hr>

                            <h6>Cargar JSON Estudiantes</h6>
                            <button class="btn btn-primary btn-sm m-1" onclick="cargar_estudiantes_JSON()" type="button"><i class='bx bx-sync'></i> Cargar JSON Estudiantes Idukay</button>
                            <div class="row" id="pnl_idukay">

                            </div>

                            <br>
                            <h6>Cargar TABLA Estudiantes</h6>
                            <button class="btn btn-primary btn-sm m-1" onclick="cargar_estudiantes_tabla()" type="button"><i class='bx bx-sync'></i> Cargar Tabla Estudiantes Idukay</button>
                            <div class="row" id="pnl_idukay">

                                <br><br>

                                <section class="content pt-4" id="pnl_tbl_estudiates" style="display: none;">
                                    <div class="container-fluid">
                                        <div class="table-responsive">
                                            <table class="table table-striped responsive" id="tabla_estudiantes" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Primer Apellido</th>
                                                        <th>Segundo Apellido</th>
                                                        <th>Primer Nombre</th>
                                                        <th>Segundo Nombre</th>
                                                        <th>Cédula</th>
                                                        <th>Género</th>
                                                        <th>Fecha de Nacimiento</th>
                                                        <th>Correo</th>
                                                        <th>Dirección</th>
                                                        <th>Parientes</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div><!-- /.container-fluid -->
                                </section>

                                <!-- /.content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>