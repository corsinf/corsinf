<<<<<<< HEAD
<<<<<<< HEAD
<script type="text/javascript">
    $(document).ready(function() {
        consultar_datos();
    });

    function consultar_datos(id = '') {
        var representantes = '';
        $.ajax({
            data: {
                id: id
            },
            url: '<?php echo $url_general ?>/controlador/representantesC.php?listar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response);   
                $.each(response, function(i, item) {
                    //console.log(item);

                    representantes +=
                        '<tr>' +
                        '<td>' + item.sa_rep_cedula + '</td>' +
                        '<td><a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_representantes&id=' + item.sa_rep_id + '&id_seccion=' + item.sa_id_seccion + '&id_grado=' + item.sa_id_grado + '&id_paralelo=' + item.sa_id_paralelo + '"><u>' + item.sa_rep_primer_apellido + ' ' + item.sa_rep_segundo_apellido + ' ' + item.sa_rep_primer_nombre + ' ' + item.sa_rep_segundo_nombre + '</u></a></td>' +
                        '<td>' + item.sa_sec_nombre + ' / ' + item.sa_gra_nombre + ' / ' + item.sa_par_nombre + '</td>' +
                        '<td>' + item.sa_rep_correo + '</td>' +
                        '<td>' + item.sa_rep_telefono_1 + '</td>' +
                        '<td>' + edad_fecha_nacimiento(item.sa_rep_fecha_nacimiento.date) + '</td>' +
                        '</tr>';
                });

                $('#tbl_datos').html(representantes);
            }
        });
    }

    function edad_fecha_nacimiento(fecha_nacimiento) {
        const fechaNacimientoJson = fecha_nacimiento;

        // Crear un objeto Date a partir del string de fecha
        const fechaNacimiento = new Date(fechaNacimientoJson);

        // Obtener la fecha actual
        const fechaActual = new Date();

        // Calcular la diferencia en milisegundos entre la fecha actual y la fecha de nacimiento
        const diferenciaEnMilisegundos = fechaActual - fechaNacimiento;

        // Calcular la edad en años a partir de la diferencia en milisegundos
        const edadEnMilisegundos = new Date(diferenciaEnMilisegundos);
        const edadEnAnios = Math.abs(edadEnMilisegundos.getUTCFullYear() - 1970);

        var salida = 'jp';
        // Mostrar la edad en años

        salida = edadEnAnios;

        return salida;
    }

    function buscar(buscar) {
        var representantes = '';

        $.ajax({
            data: {
                buscar: buscar
            },
            url: '<?= $url_general ?>/controlador/representantesC.php?buscar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                // console.log(response);   
                $.each(response, function(i, item) {
                    //console.log(item);

                    representantes +=
                        '<tr>' +
                        '<td>' + item.sa_rep_cedula + '</td>' +
                        '<td><a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_representantes&id=' + item.sa_rep_id + '&id_seccion=' + item.sa_id_seccion + '&id_grado=' + item.sa_id_grado + '&id_paralelo=' + item.sa_id_paralelo + '"><u>' + item.sa_rep_primer_apellido + ' ' + item.sa_rep_segundo_apellido + ' ' + item.sa_rep_primer_nombre + ' ' + item.sa_rep_segundo_nombre + '</u></a></td>' +
                        '<td>' + item.sa_sec_nombre + ' / ' + item.sa_gra_nombre + ' / ' + item.sa_par_nombre + '</td>' +
                        '<td>' + item.sa_rep_correo + '</td>' +
                        '<td>' + item.sa_rep_telefono_1 + '</td>' +
                        '<td>' + edad_fecha_nacimiento(item.sa_rep_fecha_nacimiento.date) + '</td>' +
                        '</tr>';
                });

                $('#tbl_datos').html(representantes);
            }

        });
    }

    function limpiar() {
        $('#codigo').val('');
        $('#descripcion').val('');
        $('#id').val('');
        $('#titulo').text('Nueva Sección');
        $('#op').text('Guardar');
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

=======
<script src="<?= $url_general ?>/js/ENFERMERIA/operaciones_generales.js"></script>
=======
<script src="../js/ENFERMERIA/operaciones_generales.js"></script>
>>>>>>> c9a234889f7443a040d28d13f82e35ef88467ae7

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabla_representante').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/representantesC.php?listar_todo=true',
                dataSrc: ''
            },
            columns: [{
                    data: 'sa_rep_cedula'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<a href="#" onclick="enviar_ID_representante(' + item.sa_rep_id + ')"><u>' + item.sa_rep_primer_apellido + ' ' + item.sa_rep_segundo_apellido + ' ' + item.sa_rep_primer_nombre + ' ' + item.sa_rep_segundo_nombre + '</u></a>';
                    }
                },
                {
                    data: 'sa_rep_correo'
                },
                {
                    data: 'sa_rep_telefono_1'
                },
                {
                    data: null,
                    render: function(data, type, item) {

                        fecha_nacimiento = item.sa_rep_fecha_nacimiento;
                        
                        salida = fecha_nacimiento ? calcular_edad_fecha_nacimiento(item.sa_rep_fecha_nacimiento) : '';

                        return salida;
                    }
                },
            ],
            order: [
                [1, 'asc']
            ],
        });
    });

    function enviar_ID_representante(id) {
        // Actualiza el valor del campo de entrada con el ID
        $('#sa_rep_id').val(id);

        // Envía el formulario por POST
        $('#form_enviar').submit();
    }
</script>

<form id="form_enviar" action="../vista/inicio.php?mod=7&acc=registrar_representantes" method="post" style="display: none;">
    <input type="hidden" id="sa_rep_id" name="sa_rep_id" value="">
</form>

<div class="page-wrapper">
    <div class="page-content">
>>>>>>> f975ff57302e9fcddee9c8879ae90e7325aab8d1
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
<<<<<<< HEAD
                            Comunidad Educativa - Representantes
=======
                            Comunidad Educativa
>>>>>>> f975ff57302e9fcddee9c8879ae90e7325aab8d1
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
<<<<<<< HEAD
            <!-- <div class="col-12 col-lg-3">

=======
            <div class="col-xl-12 mx-auto">
>>>>>>> f975ff57302e9fcddee9c8879ae90e7325aab8d1
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
<<<<<<< HEAD
                            <h5 class="mb-0 text-primary">
                                Comunidad Educativa
                            </h5>
                        </div>

                        <div class="fm-menu">
                            <div class="list-group list-group-flush">
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=estudiantes" class="list-group-item py-1"><i class='bx bx-group'></i><span>&nbsp;Estudiantes</span></a>
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=representantes" class="list-group-item py-1"><i class='bx bx-group'></i><span>&nbsp;Representantes</span></a>
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=" class="list-group-item py-1"><i class='bx bx-group'></i><span>&nbsp;Docente</span></a>
                                <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=" class="list-group-item py-1"><i class='bx bx-group'></i><span>&nbsp;Administrativo</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 -->
            <div class="col-12 col-lg-12">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Representantes</h5>
                        </div>
                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <br>

                            <section class="content">
                                <div class="container-fluid">

                                    <div class="row">
                                        <div class="col-sm-12" id="btn_nuevo">
                                            <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_representantes" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                                            <a href="#" class="btn btn-outline-secondary btn-sm" id="excel_representantes" title="Informe en excel del total de Representantes"><i class="bx bx-file"></i> Total Representantes</a>
                                        </div>

                                    </div>

                                    <div>
                                        <div class="col-sm-8 pt-3">
                                            <input type="" name="" id="txt_buscar" onkeyup="buscar($('#txt_buscar').val())" class="form-control form-control-sm" placeholder="Buscar Representantes">
                                        </div>
                                    </div>
                                    <br>

                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Cédula</th>
                                                    <th>Nombre</th>
                                                    <th>Sección/Grado/Paralelo</th>
                                                    <th>Correo</th>
                                                    <th>Teléfono</th>
                                                    <th>Edad</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbl_datos">

                                            </tbody>
                                        </table>
                                    </div>

                                </div><!-- /.container-fluid -->
                            </section>
                            <!-- /.content -->
                        </div>
=======
                            <h5 class="mb-0 text-primary">Representantes</h5>

                            <div class="row mx-1">
                                <div class="col-sm-12" id="btn_nuevo">
                                    <a href="../vista/inicio.php?mod=7&acc=registrar_representantes" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-4">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tabla_representante" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Nombre</th>
                                                <th>Correo</th>
                                                <th>Teléfono</th>
                                                <th>Edad</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.container-fluid -->
                        </section>
>>>>>>> f975ff57302e9fcddee9c8879ae90e7325aab8d1
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>