<script src="<?= $url_general ?>/js/ENFERMERIA/pacientes.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var id = '<?php echo $_SESSION['INICIO']['ID_USUARIO']; ?>';

        var noconcurente_id = '<?php echo $_SESSION['INICIO']['NO_CONCURENTE']; ?>';

        var noconcurente_tabla = '<?php echo $_SESSION['INICIO']['NO_CONCURENTE_TABLA']; ?>';
        //console.log(id);

        //alert(noconcurente_tabla)
            cargarDatos(id)
    

        //Esta consultando unos datos por defecto
        consultar_datos_estudiante_representante(noconcurente_id);

        //consultar_datos(6);
    });

    function cargarDatos(id) {

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

            success: function(response) {
                //console.log(response);
                $('#txt_ci').html(response[0].ci + " <i class='bx bxs-id-card'></i>");
                $('#txt_nombre').html(response[0].nombre);
                $('#txt_apellido').html(response[0].ape);
                 $('#txt_sexo').html('Falta dato en usuario' + " <i class='bx bx-female'></i> <i class='bx bx-male'></i>");
                if(response[0].sexo != '')
                {
                     $('#txt_sexo').html( response[0].sexo);
                }
                $('#txt_fecha_nacimiento').html('Falta dato en usuario');
                $('#txt_edad').html('Falta dato en usuario');
                $('#txt_email').html(response[0].email + " <i class='bx bx-envelope'></i>");
                $('#txt_telefono').html(response[0].telefono + " <i class='bx bxs-phone'></i>");
            }
        });
    }

    function consultar_datos_estudiante_representante(id_representante = '') {
        var estudiantes = '';
        $.ajax({
            data: {
                id_representante: id_representante,
            },
            url: '<?php echo $url_general ?>/controlador/estudiantesC.php?listar_estudiante_representante=true',
            type: 'post',
            dataType: 'json',
            //Para el id representante tomar los datos con los de session
            success: function(response) {
                console.log(response);
                $.each(response, function(i, item) {

                    sexo_estudiante = '';
                    if (item.sa_est_sexo == 'Masculino') {
                        sexo_estudiante = 'Masculino';
                    } else if (item.sa_est_sexo == 'Famenino') {
                        sexo_estudiante = 'Famenino';
                    }

                    curso = item.sa_sec_nombre + '/' + item.sa_gra_nombre + '/' + item.sa_par_nombre;

                    alert = '<div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">' +
                        '<div class="d-flex align-items-center">' +
                        '<div class="font-35 text-danger"><i class="bx bxs-message-square-x"></i>' +
                        '</div>' +
                        '<div class="ms-3">' +
                        '<h6 class="mb-0 text-danger text-start">¡Atención!</h6>' +
                        '<div class="mb-0 text-start">La ficha médica aún no esta realizada</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';




                    estudiantes +=
                        '<div class="col-12">' +
                        '<div class="card radius-15">' +
                        '<div class="card-body text-center">' +
                        '<div class="p-4 border radius-15">' +

                        alert +

                        '<img src="<?= $url_general ?>/img/computadora.jpg" width="110" height="110" class="rounded-circle shadow" alt="">' +
                        '<h5 class="mb-0 mt-5">' + item.sa_est_primer_apellido + ' ' + item.sa_est_segundo_apellido + ' ' + item.sa_est_primer_nombre + ' ' + item.sa_est_segundo_nombre + '</h5>' +
                        '<p class="mb-0">' + item.sa_est_cedula + '</p>' +
                        '<p class="mb-0">' + item.sa_est_sexo + '</p>' +
                        '<p class="mb-3">' + curso + '</p>' +

                        '<div class="d-grid mt-3">' +
                        '<a href="#" onclick="gestion_paciente_comunidad(' + item.sa_est_id + ', \'' + item.sa_est_tabla + '\');" class="btn btn-outline-primary radius-15">Detalles</a>' +

                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                });

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
            // print_r($_SESSION['INICIO']);
            // die();

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

                                    <hr>
                                    <form action="<?php echo $url_general ?>/controlador/ficha_MedicaC.php?administrar_comunidad_ficha_medica=true" method="post">
                                        <input type="text" name="sa_pac_id_comunidad" id="">
                                        <select name="sa_pac_tabla" id="">
                                            <option value="estudiantes">estudiantes</option>
                                            <option value="docentes">docentes</option>
                                            <option value="representantes">representantes</option>
                                            <option value="administrativos">administrativos</option>
                                            <option value="comunidad">comunidad</option>
                                        </select>
                                        <input type="submit" value="Enviar">
                                    </form>




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

                                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3" id="card_estudiantes">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>