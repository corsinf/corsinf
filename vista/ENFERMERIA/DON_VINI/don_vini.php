<?php


?>

<script type="text/javascript">
    $(document).ready(function() {

    });

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
            url: '../controlador/SALUD_INTEGRAL/cat_configuracionGC_IDUKAY.php?listar_idukay_estudiantes=true',
            type: 'post',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                Swal.close();
                // Inicializar DataTable con la configuración requerida
                $('#tabla_estudiantes').DataTable({
                    language: {
                        url: '../assets/plugins/datatable/spanish.json'
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
            url: '../controlador/SALUD_INTEGRAL/cat_configuracionGC_IDUKAY.php?idukay_estudiantes=true',
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
            url: '../controlador/SALUD_INTEGRAL/cat_configuracionGC_IDUKAY.php?idukay_representantes=true',
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

                            <h5>Configuración Idukay</h5>

                            <div class="row pt-3" id="pnl_config_idukay">

                            </div>

                            <h6>Estudiantes - Representantes</h6>

                            <button class="btn btn-primary btn-sm m-1" onclick="sincronizar_estudiantes()" type="button"><i class='bx bx-sync'></i> Sincronizar Estudiantes Idukay</button>

                            <button class="btn btn-primary btn-sm m-1" onclick="sincronizar_representantes()" type="button"><i class='bx bx-sync'></i> Sincronizar Representantes Idukay</button>

                            <div id="pnl_idukay" class="mb-3 pt-1">

                            </div>

                            <h6>Cargar TABLA Estudiantes</h6>
                            <button class="btn btn-primary btn-sm m-1" onclick="cargar_estudiantes_tabla()" type="button"><i class='bx bx-sync'></i> Cargar Tabla Estudiantes Idukay</button>
                            <div class="row">

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