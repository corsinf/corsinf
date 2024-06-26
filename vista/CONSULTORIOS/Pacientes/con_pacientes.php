<script src="../js/RED_CONSULTORIOS/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        carga_tabla();

        //eliminar(3);


    });

    function carga_tabla() {
        tabla_pacientes = $('#tbl_pacientes').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/RED_CONSULTORIOS/pacientesC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: 'pac_cedula'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<div"><a href="../vista/inicio.php?mod=7&acc=con_pacientes#" " title="Historial de Consultas"><u>' + item.pac_primer_apellido + ' ' + item.pac_primer_nombre + '</u></a></div>';
                    }
                },
                {
                    data: 'pac_correo'
                },
                {
                    data: null,
                    render: function(data, type, item) {

                        fecha_nacimiento = item.pac_fecha_nacimiento;

                        salida = fecha_nacimiento ? calcular_edad_fecha_nacimiento(item.pac_fecha_nacimiento) : '';

                        return salida;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return (item.pac_cedula).toUpperCase();
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        url = '../vista/inicio.php?mod=7&acc=pacientes';
                        return '<a title="Editar Ficha Médica" class="text-center btn btn-warning btn-sm" href="#" onclick="gestion_paciente_comunidad(' + item.pac_id_comunidad + ', \'' + item.pac_cedula + '\', \'' + url + '\');"><u>' + '<i class="bx bxs-edit-alt me-0"></i>' + '</u></a>';
                    }
                }
            ],

            order: []
        });
    }

    // Función para validar los campos
    function validarCampos(validacion) {
        var errores = [];

        // Recorrer el objeto validacion y verificar los campos
        for (var campo in validacion) {
            if (validacion.hasOwnProperty(campo)) {
                var tipo = validacion[campo];
                var valor = window[campo]; // Obtener el valor del campo por su nombre
                var value = $('#' + campo).val(); // Obtener el valor del campo por su ID

                //console.log(tipo);
                //console.log(value);
                console.log(campo);


                if (tipo === 'required' && value === '' || value === null) {
                    errores.push('Campo Obligatorio ' + campo.replace('_', ' ').toUpperCase() + '.');
                }
                // Puedes agregar más validaciones según el tipo de dato esperado
            }
        }

        // Mostrar errores si los hay
        if (errores.length > 0) {
            var mensajeError = '';
            errores.forEach(function(error) {
                mensajeError += '<br>' + error + '</br>';
            });
            mensajeError += '';

            Swal.fire('', mensajeError, 'error');
            return false; // Indicar que hay errores
        }
    }

    function editar_insertar() {

        var txt_id = $('#txt_id').val();
        var txt_primer_apellido = $('#txt_primer_apellido').val();
        var txt_segundo_apellido = $('#txt_segundo_apellido').val();
        var txt_primer_nombre = $('#txt_primer_nombre').val();
        var txt_segundo_nombre = $('#txt_segundo_nombre').val();
        var txt_cedula = $('#txt_cedula').val();
        var ddl_sexo = $('#ddl_sexo').val();
        var txt_tipo_sangre = $('#txt_tipo_sangre').val();
        var txt_fecha_nacimiento = $('#txt_fecha_nacimiento').val();
        var txt_telefono_1 = $('#txt_telefono_1').val();
        var txt_telefono_2 = $('#txt_telefono_2').val();
        var txt_correo = $('#txt_correo').val();
        var txt_direccion = $('#txt_direccion').val();

        var parametros = {
            'txt_id': txt_id,
            'txt_primer_apellido': txt_primer_apellido,
            'txt_segundo_apellido': txt_segundo_apellido,
            'txt_primer_nombre': txt_primer_nombre,
            'txt_segundo_nombre': txt_segundo_nombre,
            'txt_cedula': txt_cedula,
            'ddl_sexo': ddl_sexo,
            'txt_tipo_sangre': txt_tipo_sangre,
            'txt_fecha_nacimiento': txt_fecha_nacimiento,
            'txt_telefono_1': txt_telefono_1,
            'txt_telefono_2': txt_telefono_2,
            'txt_correo': txt_correo,
            'txt_direccion': txt_direccion,
        };

        var validacion = {
            'txt_id': '',
            'txt_primer_apellido': 'required',
            'txt_segundo_apellido': 'required',
            'txt_primer_nombre': 'required',
            'txt_segundo_nombre': 'required',
            'txt_cedula': 'required',
            'ddl_sexo': 'required',
            'txt_tipo_sangre': 'required',
            'txt_fecha_nacimiento': 'required',
            'txt_telefono_1': 'required',
            'txt_telefono_2': 'required',
            'txt_correo': 'required',
            'txt_direccion': 'required'
        };


        validarCampos(validacion)

        insertar(parametros)

        console.log(parametros);


    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/RED_CONSULTORIOS/pacientesC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        //location.href = '../vista/inicio.php?mod=7&acc=administrativos';
                        tabla_pacientes.ajax.reload();
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Cédula ya registrada', 'warning');
                }
            }
        });
    }

    function eliminar(parametros = 4) {
        $.ajax({
            data: {
                id: parametros
            },
            url: '../controlador/RED_CONSULTORIOS/pacientesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        //location.href = '../vista/inicio.php?mod=7&acc=administrativos';
                        tabla_pacientes.ajax.reload();
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Cédula ya registrada', 'warning');
                }
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Consultorios</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Consultorios
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div>
            <?php //print_r($_SESSION) ?>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">

                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_pacientes"><i class="bx bx-plus"></i> Nuevo Paciente</button>

                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_pacientes" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Nombres</th>
                                                <th>Correo</th>
                                                <th>Edad</th>
                                                <th>Tipo Paciente</th>
                                                <th width="10px">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
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


<div class="modal" id="modal_pacientes" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">


                <form>

                    <input type="hidden" id="txt_id" name="txt_id">

                    <div class="row pt-3">
                        <div class="col-md-3">
                            <label for="" class="form-label">Primer Apellido <label style="color: red;">*</label> </label>
                            <input type="text" class="form-control form-control-sm" id="txt_primer_apellido" name="txt_primer_apellido">
                        </div>

                        <div class="col-md-3">
                            <label for="" class="form-label">Segundo Apellido <label style="color: red;">*</label> </label>
                            <input type="text" class="form-control form-control-sm" id="txt_segundo_apellido" name="txt_segundo_apellido">
                        </div>

                        <div class="col-md-3">
                            <label for="" class="form-label">Primer Nombre <label style="color: red;">*</label> </label>
                            <input type="text" class="form-control form-control-sm" id="txt_primer_nombre" name="txt_primer_nombre">
                        </div>

                        <div class="col-md-3">
                            <label for="" class="form-label">Segundo Nombre <label style="color: red;">*</label> </label>
                            <input type="text" class="form-control form-control-sm" id="txt_segundo_nombre" name="txt_segundo_nombre">
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-3">
                            <label for="" class="form-label">Cédula de Identidad <label style="color: red;">*</label> </label>
                            <input type="text" class="form-control form-control-sm" id="txt_cedula" name="txt_cedula" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>

                        <div class="col-md-3">
                            <label for="" class="form-label">Sexo <label style="color: red;">*</label> </label>
                            <select class="form-select form-select-sm" name="ddl_sexo" id="ddl_sexo">
                                <option selected disabled>-- Seleccione --</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Masculino">Masculino</option>
                            </select>
                        </div>



                        <div class="col-md-3">
                            <label for="" class="form-label">Fecha de Nacimiento <label style="color: red;">*</label> </label>
                            <input type="date" class="form-control form-control-sm" id="txt_fecha_nacimiento" name="txt_fecha_nacimiento" onchange="edad_normal(this.value);">
                        </div>

                        <div class="col-md-3">
                            <label for="" class="form-label">Edad <label style="color: red;">*</label> </label>
                            <input type="text" class="form-control form-control-sm" id="txt_edad" name="txt_edad" readonly>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-4">
                            <label for="" class="form-label">Teléfono 1 <label style="color: red;">*</label> </label>
                            <input type="text" class="form-control form-control-sm" id="txt_telefono_1" name="txt_telefono_1">
                        </div>

                        <div class="col-md-4">
                            <label for="" class="form-label">Teléfono 2 <label style="color: red;">*</label> </label>
                            <input type="text" class="form-control form-control-sm" id="txt_telefono_2" name="txt_telefono_2">
                        </div>

                        <div class="col-md-4">
                            <label for="" class="form-label">Correo <label style="color: red;">*</label> </label>
                            <input type="email" class="form-control form-control-sm" id="txt_correo" name="txt_correo">
                        </div>
                    </div>

                    <div class="row pt-3">

                        <div class="col-md-3">
                            <label for="" class="form-label"> Tipo de Sangre <label style="color: red;">*</label> </label>
                            <select class="form-select form-select-sm" id="txt_tipo_sangre" name="txt_tipo_sangre">
                                <option selected disabled>-- Seleccione --</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="" class="form-label">Dirección <label style="color: red;">*</label> </label>
                            <input type="text" class="form-control form-control-sm" id="txt_direccion" name="txt_direccion">
                        </div>


                    </div>

                </form>


                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-sm" onclick="editar_insertar()"><i class="bx bx-save"></i> Agregar</button>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>