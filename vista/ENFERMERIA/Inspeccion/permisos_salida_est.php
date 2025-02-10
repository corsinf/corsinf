<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        consultar_tablas_datos();
        cargar_tabla();

    });

    function cargar_tabla() {
        tabla_permisos = $('#tabla_permisos').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/SALUD_INTEGRAL/permisos_salidaC.php?listar_todo=true',
                dataSrc: ''
            },

            columns: [{
                    data: null,
                    render: function(data, type, item) {

                        botones = '';


                        if (item.ac_ps_estado_salida == '0' && item.ac_ps_hora_entrada == null) {
                            botones += '<div class="d-inline">';
                            botones += '<button type="button" class="btn btn-primary btn-sm m-1" title="Hora de Llegada" onclick="llegada(' + '\'' + item.ac_ps_id + '\'' + ');"> <i class="bx bx-check me-0"></i></button>';
                            botones += '</div>';
                        }

                        return botones;


                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return fecha_nacimiento_formateada(item.ac_ps_fecha_creacion);
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {

                        return `<div"><a href="#" onclick="informacion('${item.ac_ps_id_tabla}', '${item.ac_ps_observacion}');" title="Información"><u>${item.ac_ps_nombre}</u></a></div>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        if (item.ac_ps_estado_salida == '0') {
                            return '<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">' + 'Retorno' + '</div>';
                        } else if (item.ac_ps_estado_salida == '1') {
                            return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + 'Retirada' + '</div>';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return obtener_hora_formateada(item.ac_ps_hora_salida);
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {

                        if (item.ac_ps_hora_entrada != null) {
                            return obtener_hora_formateada(item.ac_ps_hora_entrada);
                        } else if (item.ac_ps_estado_salida == '0' && item.ac_ps_hora_entrada == null) {
                            return '<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">' + 'Aún no llega' + '</div>';
                        } else if (item.ac_ps_estado_salida == '1') {
                            return '<div class="badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3">' + 'Ausente' + '</div>';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        if (item.ac_ps_prioridad == '1') {
                            return '<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">' + 'Alta' + '</div>';
                        } else if (item.ac_ps_prioridad == '2') {
                            return '<div class="badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3">' + 'Media' + '</div>';
                        } else if (item.ac_ps_prioridad == '3') {
                            return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + 'Baja' + '</div>';
                        }
                    }
                },
            ],
            order: [
                [1, 'desc'],
                [4, 'desc']
            ],


        });
    }

    function insertar_datos() {

        var ac_ps_id_autoriza = <?= $_SESSION['INICIO']['ID_USUARIO'] ?>;
        var ac_ps_nombre = $('#ac_ps_nombre').val();
        var ac_ps_estado_salida = $('#ac_ps_estado_salida').val();
        var ac_ps_id_tabla = $('#ac_ps_id_tabla').val();
        var ac_ps_observacion = $('#ac_ps_observacion').val();
        var ac_ps_prioridad = $('#ac_ps_prioridad').val();

        var parametros = {
            'ac_ps_id_autoriza': ac_ps_id_autoriza,
            'ac_ps_nombre': ac_ps_nombre,
            'ac_ps_estado_salida': ac_ps_estado_salida,
            'ac_ps_id_tabla': ac_ps_id_tabla,
            'ac_ps_observacion': ac_ps_observacion,
            'ac_ps_prioridad': ac_ps_prioridad,
        };


        if (
            ac_ps_id_autoriza === '' ||
            ac_ps_nombre === '' ||
            ac_ps_estado_salida === '' ||
            ac_ps_id_tabla === '' ||
            ac_ps_prioridad === '' ||
            ac_ps_observacion === ''
        ) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Asegurese de llenar todos los campos',
            })
        } else {
            //console.log(parametros);
            insertar(parametros)
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/SALUD_INTEGRAL/permisos_salidaC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    $("#modal_permisos").modal('hide');
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {});
                    $('#ac_ps_estado_salida').val('');
                    $('#ac_ps_prioridad').val('');
                    $('#ac_ps_observacion').val('');
                    $('#ac_ps_id_tabla').val([]).trigger('change');
                }else if (response == -10){
                    $("#modal_permisos").modal('hide');
                    Swal.fire('', 'Operacion realizada con exito. ' + 'HIKVISION NO ALERTÓ AL GUARDIA INFORMAR PERSONALMENTE', 'success').then(function() {});
                    $('#ac_ps_estado_salida').val('');
                    $('#ac_ps_prioridad').val('');
                    $('#ac_ps_observacion').val('');
                    $('#ac_ps_id_tabla').val([]).trigger('change');
                }
            }
        });

        if (tabla_permisos) {
            tabla_permisos.destroy();
        }

        cargar_tabla();
    }

    function consultar_tablas_datos() {

        var sa_tbl_pac_prefijo = 'sa_est';

        //alert(sa_tbl_pac_prefijo);

        $('#ac_ps_id_tabla').select2({
            placeholder: 'Selecciona una opción',
            dropdownParent: $('#modal_permisos'),
            language: {
                inputTooShort: function() {
                    return "Por favor ingresa 1 o más caracteres";
                },
                noResults: function() {
                    return "No se encontraron resultados";
                },
                searching: function() {
                    return "Buscando...";
                },
                errorLoading: function() {
                    return "No se encontraron resultados";
                }
            },
            minimumInputLength: 1,
            ajax: {
                url: '../controlador/SALUD_INTEGRAL/' + 'estudiantes' + 'C.php?listar_todo=true',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term // Envía el término de búsqueda al servidor
                    };
                },
                processResults: function(data, params) { // Agrega 'params' como parámetro
                    var searchTerm = params.term.toLowerCase();

                    var options = data.reduce(function(filtered, item) {

                        var fullName = item['' + sa_tbl_pac_prefijo + '_cedula'] + " - " + item['' + sa_tbl_pac_prefijo + '_primer_apellido'] + " " + item['' + sa_tbl_pac_prefijo + '_segundo_apellido'] + " " + item['' + sa_tbl_pac_prefijo + '_primer_nombre'] + " " + item['' + sa_tbl_pac_prefijo + '_segundo_nombre'];
                        var nombres = item['' + sa_tbl_pac_prefijo + '_primer_apellido'] + " " + item['' + sa_tbl_pac_prefijo + '_primer_nombre'];

                        if (fullName.toLowerCase().includes(searchTerm)) {
                            filtered.push({
                                id: item['' + sa_tbl_pac_prefijo + '_id'],
                                text: fullName,
                                nombres: nombres
                            });
                        }

                        return filtered;
                    }, []);

                    return {
                        results: options
                    };
                },
                cache: true
            }
        }).on('select2:select', function(e) {
            var nombres = e.params.data.nombres;
            $('#ac_ps_nombre').val(nombres);
        });
    }

    function llegada(id) {
        Swal.fire({
            title: '¿Quieres registrar la hora de llegada?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí.'
        }).then((result) => {
            // Si el usuario hace clic en "Sí"
            if (result.isConfirmed) {
                // Ejecuta la solicitud AJAX
                $.ajax({
                    url: '../controlador/SALUD_INTEGRAL/permisos_salidaC.php?llegada=true',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        // Maneja la respuesta exitosa
                        Swal.fire('Éxito', 'La operación se realizó con éxito', 'success');
                        if (tabla_permisos) {
                            tabla_permisos.destroy();
                        }

                        cargar_tabla();
                    },
                    error: function() {
                        // Maneja el error
                        Swal.fire('Error', 'Hubo un error en la operación', 'error');
                    }
                });
            }
        });
    }

    //Por el momento solo de estudiantes
    function informacion(id, observacion) {

        $('#ac_ps_observacion_Inf').val(observacion);

        //Datos del estudiante
        $.ajax({
            data: {
                id: id

            },
            url: '../controlador/SALUD_INTEGRAL/estudiantesC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                ///  Para la tabla de inicio /////////////////////////////////////////////////////////////////////////////////////////////////////////
                $('#txt_ci').html(response[0].sa_est_cedula + " <i class='bx bxs-id-card'></i>");

                nombres = response[0].sa_est_primer_nombre + ' ' + response[0].sa_est_segundo_nombre;
                apellidos = response[0].sa_est_primer_apellido + ' ' + response[0].sa_est_segundo_apellido;

                $('#txt_nombres').html(apellidos + " " + nombres);

                sexo_paciente = '';
                if (response[0].sa_est_sexo === 'Masculino') {
                    sexo_paciente = "Masculino <i class='bx bx-male'></i>";
                } else if (response[0].sa_est_sexo === 'Femenino') {
                    sexo_paciente = "Famenino <i class='bx bx-female'></i>";
                }
                $('#txt_sexo').html(sexo_paciente);
                $('#txt_fecha_nacimiento').html((response[0].sa_est_fecha_nacimiento) + ' (' + calcular_edad_fecha_nacimiento(response[0].sa_est_fecha_nacimiento) + ' años)');

                curso = response[0].sa_sec_nombre + ' / ' + response[0].sa_gra_nombre + ' / ' + response[0].sa_par_nombre;
                $('#txt_curso').html(curso);

            }
        });

        $("#modal_informacion").modal('show');

    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Accesos</div>

            <?php
            //echo ($_SESSION['INICIO']['ID_USUARIO']);
            ?>

            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Permisos de Salida
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

                            <div class="row mx-1">
                                <div class="col-sm-12" id="btn_nuevo">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_permisos"><i class='bx bx-plus'></i> Nuevo Permiso</button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-4">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tabla_permisos" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">Acciones</th>
                                                <th>Fecha de Creación</th>
                                                <th>Nombres</th>
                                                <th>Estado</th>
                                                <th>Hora de Salida</th>
                                                <th>Hora de Entrada</th>
                                                <th>Prioridad</th>
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


<div class="modal" id="modal_permisos" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5>Permiso de Salida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <input type="hidden" name="ac_ps_nombre" id="ac_ps_nombre">

                <div class="row">
                    <div class="col-12">
                        <label for="ac_horarioC_materia">Estudiante <label class="text-danger">*</label></label>
                        <select name="ac_ps_id_tabla" id="ac_ps_id_tabla" class="form-select form-select-sm">
                            <option selected disabled>-- Seleccione un Estudiante --</option>
                            <option value="Faltas">Faltas</option>
                            <option value="Notas">Notas</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="ac_horarioC_materia">Tipo de Salida <label class="text-danger">*</label></label>
                        <select name="ac_ps_estado_salida" id="ac_ps_estado_salida" class="form-select form-select-sm">
                            <option selected disabled>-- Seleccione Tipo de Salida --</option>
                            <option value="0">Retorno</option>
                            <option value="1">Retirada</option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="ac_horarioC_materia">Prioridad <label class="text-danger">*</label></label>
                        <select name="ac_ps_prioridad" id="ac_ps_prioridad" class="form-select form-select-sm">
                            <option selected disabled>-- Seleccione Prioridad --</option>
                            <option value="1">Alta</option>
                            <option value="2">Media</option>
                            <option value="3">Baja</option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="ac_horarioC_materia">Observación <label class="text-danger">*</label></label>
                        <textarea name="ac_ps_observacion" id="ac_ps_observacion" cols="30" rows="10" class="form-control form-control-sm" placeholder="Observaciones"></textarea>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-sm" onclick="insertar_datos()"><i class="bx bx-save"></i> Guardar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal" id="modal_informacion" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5>Información</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="">
                            <table class="table mb-0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;"></th>
                                        <th style="width: 25%;"></th>
                                        <th style="width: 25%;"></th>
                                        <th style="width: 25%;"></th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="table-secondary text-end">Cédula:</th>
                                        <td id="txt_ci"></td>

                                        <th class="table-secondary text-end">Sexo:</th>
                                        <td id="txt_sexo"></td>
                                    </tr>
                                    <tr>
                                        <th class="table-secondary text-end">Nombres:</th>
                                        <td id="txt_nombres" colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th class="table-secondary text-end">Fecha de Nacimiento:</th>
                                        <td id="txt_fecha_nacimiento" colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th class="table-secondary text-end" id="variable_paciente">Curso:</th>
                                        <td id="txt_curso" colspan="3"></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="ac_horarioC_materia">Observación <label class="text-danger"></label></label>
                        <textarea readonly name="ac_ps_observacion_Inf" id="ac_ps_observacion_Inf" cols="30" rows="10" class="form-control form-control-sm" placeholder="Observaciones"></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>