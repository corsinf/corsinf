<script src="../js/ENFERMERIA/pacientes.js"></script>

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
    var lista_estudiantes

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
                if (response[0].sexo != '') {
                    $('#txt_sexo').html(response[0].sexo);
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
        var estudiantes2 = '<option value="">-- Seleccione --</option>';
        var ids = '';
        var contador_alertas = 0;
        var contador_alertas_div = 0;

        $.ajax({
            data: {
                id_representante: id_representante,
            },
            url: '../controlador/estudiantesC.php?listar_estudiante_representante=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                //console.log(response);
                $.each(response, function(i, item) {
                    sexo_estudiante = '';
                    if (item.sa_est_sexo == 'Masculino') {
                        sexo_estudiante = 'Masculino';
                    } else if (item.sa_est_sexo == 'Femenino') {
                        sexo_estudiante = 'Femenino';
                    }

                    curso = item.sa_sec_nombre + '/' + item.sa_gra_nombre + '/' + item.sa_par_nombre;

                    $.ajax({
                        data: {
                            sa_pac_id_comunidad: item.sa_est_id,
                            sa_pac_tabla: item.sa_est_tabla,
                        },
                        url: '../controlador/ficha_MedicaC.php?id_paciente_id_comunidad_tabla=true',
                        type: 'post',
                        dataType: 'json',
                        success: function(response) {
                            alert_salida = '';

                            console.log(response);

                            if (response === null) {
                                // Si la respuesta es nula o no es un objeto JSON válido
                                alert_salida =
                                    '<div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">' +
                                    '<div class="d-flex align-items-center">' +
                                    '<div class="font-35 text-danger"><i class="bx bxs-message-square-x"></i>' +
                                    '</div>' +
                                    '<div class="ms-3">' +
                                    '<h6 class="mb-0 text-danger text-start">¡Atención!</h6>' +
                                    '<div class="mb-0 text-start">La ficha médica aún no está realizada</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                            } else {
                                // Si la respuesta es válida (no nula y es un objeto JSON)

                                ////////////////////////////////////////
                                //Ficha medica 
                                ////////////////////////////////////////

                                ficha_medica_validacion(response.sa_pac_id, contador_alertas);

                            }

                            // Actualiza el contenido del elemento con el ID "alert_notificacion"
                            $('#alert_notificacion_' + contador_alertas).html(alert_salida);
                            // Incrementa el contador aquí
                            contador_alertas++;

                            console.log(contador_alertas);

                        }
                    });

                    estudiantes +=
                        '<div class="col-12">' +
                        '<div class="card radius-15">' +
                        '<div class="card-body text-center">' +
                        '<div class="p-4 border radius-15">' +

                        '<div id="alert_notificacion_' + (contador_alertas_div) + '"></div>' +

                        '<img src="../img/computadora.jpg" width="110" height="110" class="rounded-circle shadow" alt="">' +
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
                    estudiantes2 +=
                        '<option value="' + item.sa_est_id + '">' + item.sa_est_primer_apellido + ' ' + item.sa_est_segundo_apellido + ' ' + item.sa_est_primer_nombre + ' ' + item.sa_est_segundo_nombre + '</option>';

                    ids += item.sa_est_id + ',';

                    contador_alertas_div++;
                });

                $('#card_estudiantes').html(estudiantes);
                $('#lista_estudiantes').html(estudiantes2);
                $('#ids_est').val(ids);
                lista_seguros();


            }
        });
    }

    function ficha_medica_validacion(sa_pac_id, id_notificacion) {
        $.ajax({
            data: {
                sa_pac_id: sa_pac_id
            },
            url: '../controlador/ficha_MedicaC.php?listar_paciente_ficha=true',
            type: 'post',
            dataType: 'json',
            success: function(response_2) {
                //console.log(response_2[0].sa_fice_estado_realizado + ' conta ' + contador_alertas);

                if (response_2[0].sa_fice_estado_realizado == 1) {
                    alert_salida =
                        '<div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">' +
                        '<div class="d-flex align-items-center">' +
                        '<div class="font-35 text-success"><i class="bx bxs-check-circle"></i>' +
                        '</div>' +
                        '<div class="ms-3">' +
                        '<h6 class="mb-0 text-success">La ficha médica se ha guardado correctamente.</h6>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                } else {
                    alert_salida =
                        '<div class="alert border-0 border-start border-5 border-warning alert-dismissible fade show py-2">' +
                        '<div class="d-flex align-items-center">' +
                        '<div class="font-35 text-warning"><i class="bx bx-info-circle"></i>' +
                        '</div>' +
                        '<div class="ms-3">' +
                        '<h6 class="mb-0 text-warning">Falta completar datos en la ficha médica.</h6>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }

                $('#alert_notificacion_' + id_notificacion).html(alert_salida);
                //contador_alertas++;
            }
        });

        console.log(id_notificacion + sa_pac_id)
    }

    function SaveNewSeguro() {
        var prov = $('#txtSeguroProveedorNew').val();
        var seguro = $('#txtSeguroNombreNew').val();
        var estudiantes = $('#lista_estudiantes').val();

        console.log(estudiantes);
        console.log($('#rbl_todos').prop('checked'));
        if (($('#rbl_todos').prop('checked') == false && estudiantes == '') || prov == '' || seguro == '') {
            Swal.fire('', 'Llene todo los campos', 'info')
            return false;
        }
        var parametros = {
            'Proveedor': prov,
            'seguro': seguro,
            'todos': $('#rbl_todos').prop('checked'),
            'estudiantes': estudiantes,
            'ids': $('#ids_est').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/estudiantesC.php?SaveSeguros=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {

                if (response == 1) {
                    Swal.fire('', 'Agregado', 'success');
                    lista_seguros();
                } else if (response == -2) {
                    Swal.fire("", "Estudiante ya esta registrado con este seguro", "info")
                }
            }
        });
    }


    function lista_seguros() {

        var parametros = {
            'estudiantes': $('#ids_est').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/estudiantesC.php?ListaSeguros=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                $('#tbl_body').html(response)

                console.log(response)
            }
        });
    }

    function eliminar_seguro(id) {
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

                $.ajax({
                    data: {
                        id: id
                    },
                    url: '../controlador/estudiantesC.php?EliminarSeguros=true',
                    type: 'post',
                    dataType: 'json',

                    success: function(response) {
                        if (response == 1) {
                            lista_seguros();
                        }
                    }
                });
            }
        })
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
                                <a class="nav-link" data-bs-toggle="tab" href="#seguros" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Seguros</div>
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
                                    <div class="col-lg-6 mx-1 col-sm-12">
                                        <div class="">
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

                                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3" id="card_estudiantes">

                                </div>
                            </div>

                            <div class="tab-pane fade" id="seguros" role="tabpanel">
                                <div class="row">

                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b>Nombre de Proveedor</b>
                                                <input type="text" name="" id="txtSeguroProveedorNew" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-sm-12">
                                                <b>Nombre de seguro</b>
                                                <input type="text" name="" id="txtSeguroNombreNew" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-sm-12">
                                                <b>Aplicar a:</b>
                                                <div class="input-group text-center">
                                                    <div class="input-group-text">
                                                        <label>
                                                            <input class="form-check-input" type="checkbox" id="rbl_todos"> Todos</label>
                                                        <input class="" type="hidden" id="ids_est">
                                                    </div>

                                                    <select class="form-select form-select-sm" id="lista_estudiantes" name="lista_estudiantes">
                                                        <option value="">-- Seleccione --</option>
                                                    </select>
                                                    <span>

                                                    </span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <br>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="SaveNewSeguro()"><i class="bx bx-save me-0"></i> Guardar</button>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <th></th>
                                                    <th>Proveedor</th>
                                                    <th>Nombre del seguro</th>
                                                    <th>Estudiante</th>
                                                </thead>
                                                <tbody id="tbl_body">
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
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