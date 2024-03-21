<script src="../js/ENFERMERIA/pacientes.js"></script>
<style>
    .upload-img {
        border-radius: 100%;
        display: inline-block;
        width: 70px;
        height: 70px;
        object-fit: cover;
        object-position: 50% 50%;
        border: 3px solid #20e5bf;
        margin-right: 15px;
        vertical-align: middle;
    }

    .input-file-upload {
        position: relative;
        display: inline-block;
        vertical-align: middle;
    }

    .input-file-upload input[type="file"] {
        opacity: 0;
        padding: 10px 0;
        height: 36px;
        width: 150px;
    }

    .upload-label {
        width: 150px;
        height: 38px;
        background: #3b70f1;
        text-align: center;
        color: #ffffff;
        display: block;
        padding: 8px 0;
        position: absolute;
        line-height: normal;
        font-size: 14px;
        font-weight: 700;
        transition: all 0.3s ease-in-out;
    }

    .input-file-upload:hover .upload-label {
        background: #eaf5fe;
        color: #3b70f1;
    }
</style>
<script type="text/javascript">
    function readURL(input, item) {
        var id = input.id;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#file_upload_' + item).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURLRep(input) {
        var id = input.id;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#img_rep').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function cargar_img_alumno(id) {

        var fileInput = $('#file_estudiante_img_' + id).val();
        if (fileInput == '') {
            Swal.fire('', 'Seleccione una imagen', 'warning');
            return false;
        }

        var formData = new FormData(document.getElementById("form_estudiantes_" + id));
        $.ajax({
            url: '../controlador/estudiantesC.php?cargar_imagen_estudiantes=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            // beforeSend: function () {
            //        $("#foto_alumno").attr('src',"../img/gif/proce.gif");
            //     },
            success: function(response) {
                if (response == -1) {
                    Swal.fire(
                        '',
                        'Algo extraño a pasado intente mas tarde.',
                        'error')

                } else if (response == -2) {
                    Swal.fire(
                        '',
                        'Asegurese que el archivo subido sea una imagen.',
                        'error')
                } else {
                    consultar_datos_estudiante_representante(noconcurente_id);
                }
            }
        });

    }
</script>

<script type="text/javascript">
    var noconcurente_id = '<?php echo $_SESSION['INICIO']['NO_CONCURENTE']; ?>';
    var noconcurente_tabla = '<?php echo $_SESSION['INICIO']['NO_CONCURENTE_TABLA']; ?>';
    $(document).ready(function() {

        var id = '<?php echo $_SESSION['INICIO']['ID_USUARIO']; ?>';

        //console.log(id);

        //alert(noconcurente_tabla)


        //Esta consultando unos datos por defecto
        consultar_datos_estudiante_representante(noconcurente_id);
        cargarDatos(id)

        //cargarDatos(1);
        //consultar_datos_estudiante_representante(1);

        $("#btn_subir_img_rep").on('click', function() {

            var fileInput = $('#file_img').val();
            if (fileInput == '') {
                Swal.fire('', 'Seleccione una imagen', 'warning');
                return false;
            }


            var formData = new FormData(document.getElementById("form_img"));
            $.ajax({
                url: '../controlador/usuariosC.php?cargar_imagen_no_concurente=true',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                // beforeSend: function () {
                //        $("#foto_alumno").attr('src',"../img/gif/proce.gif");
                //     },
                success: function(response) {
                    if (response == -1) {
                        Swal.fire(
                            '',
                            'Algo extraño a pasado intente mas tarde.',
                            'error')

                    } else if (response == -2) {
                        Swal.fire(
                            '',
                            'Asegurese que el archivo subido sea una imagen.',
                            'error')
                    } else {
                        location.reload();
                    }
                }
            });
        });


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
                $('#txt_id').val(response[0].id);
                $('#txt_tabla').val(response[0].tabla);
                $('#txt_ci').val(response[0].ci);
                $('#name_img').val(response[0].ci)
                $('#txt_nombre').val(response[0].nombre);
                $('#txt_apellido').val(response[0].apellido);
                $('#txt_nombre2').val(response[0].nombre2);
                $('#txt_apellido2').val(response[0].apellido2);
                $('#txt_sexo').html('Falta dato en usuario' + " ");
                if (response[0].sexo != "Masculino") {
                    $('#cbx_fe').prop('checked', true);
                } else {
                    $('#cbx_ma').prop('checked', true);
                }
                if (response[0].fechaN != '' && response[0].fechaN != null) {
                    $('#txt_fecha_nacimiento').val((response[0].fechaN));

                    var fecha1 = new Date((response[0].fechaN));
                    var fecha2 = new Date();

                    var diferenciaEnMilisegundos = fecha2 - fecha1;

                    var milisegundosEnUnAnio = 1000 * 60 * 60 * 24 * 365.25; // Aproximadamente 365.25 días en un año
                    var diferenciaEnAnios = Math.round(diferenciaEnMilisegundos / milisegundosEnUnAnio);

                    $('#txt_edad').html(diferenciaEnAnios);
                }
                $('#txt_email').val(response[0].email);
                $('#txt_telefono').val(response[0].telefono);
                if (response[0].foto != '') {
                    $('#img_rep').attr("src", response[0].foto + '?' + Math.random())
                }
                $('#txt_usuario').val(response[0].usu);
                $('#txt_pass').val(response[0].pass);
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

                            // console.log(response);

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

                            //console.log(contador_alertas);

                        }
                    });

                    estudiantes +=
                        '<div class="col-12">' +
                        '<div class="card radius-15">' +
                        '<div class="card-body text-center">' +
                        '<div class="p-4 border radius-15">' +

                        '<div id="alert_notificacion_' + (contador_alertas_div) + '"></div>' +

                        '<form id="form_estudiantes_' + item.sa_est_id + '">' +
                        '<div class="mt-1">'
                    if (item.sa_est_foto_url != '' && item.sa_est_foto_url != null) {
                        estudiantes += '<img src="' + item.sa_est_foto_url + '?' + Math.random() + '" id="file_upload_' + item.sa_est_id + '" width="110" height="110" class="rounded-circle shadow" alt="">'
                    } else {
                        estudiantes += '<img src="../img/sin_imagen.jpg" id="file_upload_' + item.sa_est_id + '" width="110" height="110" class="rounded-circle shadow" alt="">'
                    }
                    <?php if ($_SESSION['INICIO']['TIPO'] != 'DBA') { ?>
                        estudiantes += '<br><div class="input-file-upload mt-1">' +
                            '<div class="btn-group" role="group" aria-label="Button group with nested dropdown">' +
                            '<span class="upload-label">Seleccionar Imagen</span>' +
                            '<input type="file" id="file_estudiante_img_' + item.sa_est_id + '" name="file_estudiante_img_' + item.sa_est_id + '" onchange="readURL(this,' + item.sa_est_id + ');" />' +
                            '<input type="hidden" id="name_img" name="name_img" value="' + item.sa_est_cedula + '" />' +
                            '<input type="hidden" id="txt_idEst" name="txt_idEst" value="' + item.sa_est_id + '" />' +
                            '<div class="btn-group" role="group">' +
                            '<button type="button" class="btn btn-outline-primary" title="subir imagen" onclick="cargar_img_alumno(' + item.sa_est_id + ')" ><i class="bx bx-upload me-0"></i></button>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                        <?php } ?> '</div>' +
                        '</form>' +
                        '<h5 class="mb-0 mt-3">' + item.sa_est_primer_apellido + ' ' + item.sa_est_segundo_apellido + ' ' + item.sa_est_primer_nombre + ' ' + item.sa_est_segundo_nombre + '</h5>' +
                        '<p class="mb-0">' + item.sa_est_cedula + '</p>' +
                        '<p class="mb-0">' + item.sa_est_sexo + '</p>' +
                        '<p class="mb-3">' + curso + '</p>' +

                        '<div class="d-grid mt-3">' +
                        '<a href="#" onclick="gestion_paciente_comunidad(' + item.sa_est_id + ', \'' + item.sa_est_tabla + '\', \'./inicio.php?mod=7&acc=inicio_representante\');" class="btn btn-outline-primary radius-15">Detalles</a>' +

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

        //console.log(id_notificacion + sa_pac_id)
    }

    function SaveNewSeguro() {
        var prov = $('#txtSeguroProveedorNew').val();
        var seguro = $('#txtSeguroNombreNew').val();
        var estudiantes = $('#lista_estudiantes').val();

        //console.log(estudiantes);
        //console.log($('#rbl_todos').prop('checked'));
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

                //console.log(response)
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

    function editar_datos() {
        parametros = {
            'id': $('#txt_id').val(),
            'tabla': $('#txt_tabla').val(),
            'nombre1': $('#txt_nombre').val(),
            'nombre2': $('#txt_nombre2').val(),
            'apellidos1': $('#txt_apellido').val(),
            'apellidos2': $('#txt_apellido2').val(),
            'sexo': $('input [name="cbx_sexo"]:checked').val(),
            'fecha_n': $('#txt_fecha_nacimiento').val(),
            'correo': $('#txt_email').val(),
            'telefono': $('#txt_telefono').val(),
            'cedula': $('#txt_ci').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/usuariosC.php?editar_datos=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire("Datos guardados", "", "success");
                    cargarDatos($('#txt_id').val())
                } else if (response == '-2') {
                    Swal.fire("Usuario no concurrente no asignado", "Consulte con su administrador", "error");
                } else {
                    Swal.fire("No se pudo guardar los datos", "Consulte con su administrador", "error");
                }

            }
        });

    }

    function guardar_credencial() {
        parametros = {
            'id': $('#txt_id').val(),
            'tabla': $('#txt_tabla').val(),
            'usuario': $('#txt_usuario').val(),
            'pass': $('#txt_pass').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/usuariosC.php?guardar_credencial=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire("Datos guardados", "", "success");
                    cargarDatos($('#txt_id').val())
                } else if (response == '-2') {
                    Swal.fire("Usuario no concurrente no asignado", "Consulte con su administrador", "error");
                } else {
                    Swal.fire("No se pudo guardar los datos", "Consulte con su administrador", "error");
                }

            }
        });

    }

    function pass() {
        var pa = document.getElementById("txt_pass");
        if (pa.type == 'password') {
            pa.type = 'text';
        } else {
            pa.type = 'password';
        }
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
                                    <div class="col-sm-6">
                                        <div class="">
                                            <table class="table mb-0" style="width:100%">
                                                <tbody>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Cédula:</th>
                                                        <td>
                                                            <div class="input-group">
                                                                <!-- <i class='bx bxs-id-card'></i> -->
                                                                <input type="hidden" name="" id="txt_id" class="form-control form-control-sm" value="0000000000">
                                                                <input type="hidden" name="" id="txt_tabla" class="form-control form-control-sm" value="0000000000">

                                                                <input type="" name="" id="txt_ci" class="form-control form-control-sm" value="0000000000">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Nombre 1:</th>
                                                        <td>
                                                            <input type="" name="" id="txt_nombre" class="form-control form-control-sm" value="">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Nombre 2:</th>
                                                        <td>
                                                            <input type="" name="" id="txt_nombre2" class="form-control form-control-sm" value="">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Apellido Paterno:</th>
                                                        <td>
                                                            <input type="" name="" id="txt_apellido" class="form-control form-control-sm" value="">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Apellido Materno:</th>
                                                        <td>
                                                            <input type="" name="" id="txt_apellido2" class="form-control form-control-sm" value="">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Sexo:</th>
                                                        <td>

                                                            <label><i class='bx bx-female'><input type="radio" id="cbx_fe" name="cbx_sexo" value="Femenino"> Femenino</i></label>
                                                            &nbsp;&nbsp;&nbsp;
                                                            <label><i class='bx bx-male'><input type="radio" name="cbx_sexo" id="cbx_ma" value="Masculino"> Masculino </i></label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Fecha de Nacimiento:</th>
                                                        <td>
                                                            <input type="date" name="" id="txt_fecha_nacimiento" class="form-control form-control-sm">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Edad Actual:</th>
                                                        <td id="txt_edad">0 años</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Correo Electrónico:</th>
                                                        <td>
                                                            <input type="" name="" id="txt_email" class="form-control form-control-sm" value="">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width:40%" class="table-primary text-end">Teléfono:</th>
                                                        <td>
                                                            <input type="" name="" id="txt_telefono" class="form-control form-control-sm" value="">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-end">
                                                            <button class="btn btn-sm btn-primary" onclick="editar_datos()">Guardar</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <form id="form_img">
                                                <?php if ($_SESSION['INICIO']['TIPO'] == 'DBA' && $_SESSION["INICIO"]['LOGO'] != '') { ?>
                                                    <img id="img_rep" src="<?php echo $_SESSION["INICIO"]['FOTO']; ?>" alt="Admin" class="rounded-circle p-1 bg-primary" style="width: 250px;height: 250px;">
                                                <?php } else { ?>
                                                    <img id="img_rep" src="../img/sin_imagen.jpg" alt="Admin" class="rounded-circle p-1 bg-primary" style="width: 250px;height: 250px;">
                                                <?php } ?>
                                                <div class="mt-3">
                                                    <?php if ($_SESSION['INICIO']['TIPO'] != 'DBA') { ?>
                                                        <div class="row">
                                                            <div class="col-sm-8">
                                                                <input type="file" id="file_img" name="file_img" class="form-control form-control-sm" onchange="readURLRep(this)">
                                                                <input type="hidden" name="name_img" id="name_img">
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <button type="button" class="btn btn-outline-primary btn-block btn-sm" style="width:100%" id="btn_subir_img_rep">Subir</button>
                                                            </div>
                                                        </div>
                                                        <div class="row text-start pt-3">
                                                            <div class="col-sm-12 mb-3">
                                                                <label for="" class="form-label fw-bold"> Usuario <label style="color: red;">*</label> </label>
                                                                <input type="" name="txt_usuario" id="txt_usuario" readonly class="form-control form-control-sm">
                                                            </div>
                                                            <div class="col-sm-12 mb-3">
                                                                <label for="" class="form-label fw-bold"> Contraseña <label style="color: red;">*</label> </label>
                                                                <div class="input-group mb-3">
                                                                    <input type="password" class="form-control form-control-sm" name="txt_pass" id="txt_pass" required="">
                                                                    <button type="button" class="btn btn-info btn-flat btn-sm" onclick="pass()"><i class="lni lni-eye" id="eye"></i></button>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 text-end">
                                                                <button type="button" class="btn-sm btn-primary btn" onclick="guardar_credencial()">Guardar Credenciales</button>
                                                                <?php //print_r($_SESSION['INICIO']); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                </div>
                                            </form>
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
                                    <span class="badge rounded-pill bg-warning"><i class="bx bx-info-circle"></i>Seccion exclusiva para agregar seguros medicos adicionales a los que existen en la institucion </span>

                                    <div class="col-sm-8">
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-12 mb-2">
                                                <label for="" class=" fw-bold"> Nombre del Proveedor </label>
                                                <input type="text" name="" id="txtSeguroProveedorNew" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-sm-12 mb-2">
                                                <label for="" class=" fw-bold"> Nombre del Seguro </label>
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
                                        <br><br>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="SaveNewSeguro()"><i class="bx bx-save me-0"></i> Guardar</button>
                                    </div>
                                    <div class="col-sm-12 pt-3">
                                        <div class="table-responsive table">
                                            <table class="table">
                                                <thead>
                                                    <th></th>
                                                    <th>Proveedor</th>
                                                    <th>Nombre del Seguro</th>
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