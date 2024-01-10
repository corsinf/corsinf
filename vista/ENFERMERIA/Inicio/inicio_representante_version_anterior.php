<script type="text/javascript">
    $(document).ready(function() {
        var id = '<?php echo $_SESSION['INICIO']['ID_USUARIO']; ?>';
        //console.log(id);
        if (id != '') {
            cargarDatos(id)
        }

        //Esta consultando unos datos por defecto
        consultar_datos_estudiante_representante(3);

        //consultar_datos(6);
    });

    function cargarDatos(id) {
        // $('#nuevo_tipo_usuario').modal('show');
        // $('#btn_opcion').text('Editar');
        // $('#exampleModalLongTitle').text('Editar tipo de usuario');
        var noconcurente = '<?php echo $_SESSION['INICIO']['NO_CONCURENTE']; ?>';
        var parametros = {
            'id': id,
            'query': '',
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/usuariosC.php?datos_usuarios=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                //console.log(response);
                $('#txt_ci').html(response[0].ci + " <i class='bx bxs-id-card'></i>");
                $('#txt_nombre').html(response[0].nombres);
                $('#txt_apellido').html(response[0].ape);
                $('#txt_sexo').html('Falta dato en usuario' + " <i class='bx bx-female'></i> <i class='bx bx-male'></i>");
                $('#txt_fecha_nacimiento').html('Falta dato en usuario');
                $('#txt_edad').html('Falta dato en usuario');
                $('#txt_email').html(response[0].email + " <i class='bx bx-envelope'></i>");
                $('#txt_telefono').html(response[0].tel + " <i class='bx bxs-phone'></i>");
            }
        });
    }

    function consultar_datos_estudiante_representante(id_representante = '') {
        var estudiantes = '';
        $.ajax({
            data: {
                id_representante: id_representante,
            },
            url: '../controlador/estudiantesC.php?listar_estudiante_representante=true',
            type: 'post',
            dataType: 'json',
            //Para el id representante tomar los datos con los de session
            success: function(response) {
                console.log(response);
                $.each(response, function(i, item) {
                    //console.log(item);

                    /*estudiantes +=
                        '<tr>' +
                        '<td>' + item.sa_est_cedula + '</td>' +
                        '<td><a href="../vista/inicio.php?mod=7&acc=registrar_estudiantes&id=' + item.sa_est_id + '&id_seccion=' + item.sa_id_seccion + '&id_grado=' + item.sa_id_grado + '&id_paralelo=' + item.sa_id_paralelo + '&id_representante=' + item.sa_id_representante + '"><u>' + item.sa_est_primer_apellido + ' ' + item.sa_est_segundo_apellido + ' ' + item.sa_est_primer_nombre + ' ' + item.sa_est_segundo_nombre + '</u></a></td>' +
                        '<td>' + item.sa_sec_nombre + ' / ' + item.sa_gra_nombre + ' / ' + item.sa_par_nombre + '</td>' +
                        '<td>' + edad_fecha_nacimiento(item.sa_est_fecha_nacimiento.date) + '</td>' +
                        '</tr>';*/

                    /*estudiantes +=
                        '<tr>' +
                        '<th style="width:40%" class="table-success text-end">Cédula:</th>' +
                        '<td>' + item.sa_est_cedula + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<th style="width:40%" class="table-success text-end">Nombres:</th>' +
                        '<td>' + item.sa_est_primer_nombre + ' ' + item.sa_est_segundo_nombre + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<th style="width:40%" class="table-success text-end">Apellidos:</th>' +
                        '<td>' + item.sa_est_primer_apellido + ' ' + item.sa_est_segundo_apellido + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<th style="width:40%" class="table-success text-end">Sexo:</th>' +
                        '<td>' + item.sa_est_cedula + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<th style="width:40%" class="table-success text-end">Fecha de Nacimiento:</th>' +
                        '<td>' + fecha_nacimiento_formateada(item.sa_est_fecha_nacimiento.date) + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<th style="width:40%" class="table-success text-end">Edad Actual:</th>' +
                        '<td>' + edad_fecha_nacimiento(item.sa_est_fecha_nacimiento.date) + ' años</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<th style="width:40%" class="table-success text-end">Correo Electrónico:</th>' +
                        '<td>' + item.sa_est_correo + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<th style="width:40%" class="table-success text-end">Sección/Grado/Curso:</th>' +
                        '<td>' + item.sa_id_grado + ' </td>' +
                        '</tr><tr><td></td><td></td></tr>';*/

                    sexo_estudiante = '';
                    if (item.sa_est_sexo == 'M') {
                        sexo_estudiante = 'Masculino';
                    } else if (item.sa_est_sexo == 'F') {
                        sexo_estudiante = 'Famenino';
                    }

                    curso = item.sa_sec_nombre + '/' + item.sa_gra_nombre + '/' + item.sa_par_nombre;

                    estudiantes +=
                        '<div class="col">' +
                        '<div class="card radius-15">' +
                        '<div class="card-body text-center">' +
                        '<div class="p-4 border radius-15">' +
                        '<img src="../img/computadora.jpg" width="110" height="110" class="rounded-circle shadow" alt="">' +
                        '<h5 class="mb-0 mt-5">' + item.sa_est_primer_apellido + ' ' + item.sa_est_segundo_apellido + ' ' + item.sa_est_primer_nombre + ' ' + item.sa_est_segundo_nombre + '</h5>' +
                        '<p class="mb-0">' + item.sa_est_cedula + '</p>' +
                        '<p class="mb-0">' + sexo_estudiante + '</p>' +
                        //'<p class="mb-0">' + fecha_nacimiento_formateada(item.sa_est_fecha_nacimiento.date) + ' (' + edad_fecha_nacimiento(item.sa_est_fecha_nacimiento.date) + ' años)' + '</p>' +
                        //'<p class="mb-0">' + item.sa_est_correo + '</p>' +
                        '<p class="mb-3">' + curso + '</p>' +

                        '<div class="d-grid mt-3">' +
                        '<a href="../vista/inicio.php?mod=7&acc=ficha_medica_representado&id_estudiante=' + item.sa_est_id + '"' + 'class="btn btn-outline-primary radius-15">Detalles</a>' +

                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                });

                //$('#tbl_datos').html(estudiantes);
                $('#card_estudiantes').html(estudiantes);

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
                            Inicio
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-success" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#inicio" role="tab" aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Inicio</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#estudiantes" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Estudiantes</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content py-3">
                            <div class="tab-pane fade show active" id="inicio" role="tabpanel">
                                <div class="row">
                                    <div class="col-6 mx-5">
                                        <div class="table-responsive">
                                            <table class="table mb-0" style="width:100%">
                                                <tbody>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Cédula:</th>
                                                        <td id="txt_ci">0000000000</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Nombres:</th>
                                                        <td id="txt_nombre">Mark Ryden</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Apellidos:</th>
                                                        <td id="txt_apellido">Tipan Páez</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Sexo:</th>
                                                        <td id="txt_sexo">Masculino</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Fecha de Nacimiento:</th>
                                                        <td id="txt_fecha_nacimiento">25 de mayo 2006</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Edad Actual:</th>
                                                        <td id="txt_edad">17 años</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Correo Electrónico:</th>
                                                        <td id="txt_email">mark@mail.com </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Teléfono:</th>
                                                        <td id="txt_telefono">0999865412 </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="estudiantes" role="tabpanel">
                                <div class="row">
                                    <div class="col-6 mx-5">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tbody id="tbl_datos">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4" id="card_estudiantes">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>