<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        consultar_datos();
    });

    function consultar_datos(id = '') {
        var estudiantes = '';
        $.ajax({
            // data: {
            //     id: id
            // },
            url: '../controlador/agendamientoC.php?cita_actual=true',
            type: 'post',
            dataType: 'json',
            //Para el id representante tomar los datos con los de session
            success: function(response) {
                // console.log(response);   
                var lista = '';
                $.each(response, function(i, item) {

                    console.log(item);

                    lista += '<div class="col">' +
                        '<div class="card radius-15">' +
                        '<div class="card-body text-center">' +
                        '<div class="p-4 border radius-15">' +
                        '<img src="../assets/images/avatars/avatar-1.png" width="110" height="110" class="rounded-circle shadow" alt="">' +
                        '<h5 class="mb-0 mt-5">' + item.nombres + '</h5>' +
                        '<p class="mb-3">' + item.sa_conp_tipo_consulta.toUpperCase() + '</p>' +
                        '<div class="d-grid"> ' +

                        '<a class="btn btn-outline-success radius-15 mb-1" href="../vista/inicio.php?mod=7&acc=registrar_consulta_paciente&id_consulta=' + item.sa_conp_id + '&tipo_consulta=' + item.sa_conp_tipo_consulta + '&id_ficha=' + item.sa_fice_id + '&id_paciente=' + item.sa_pac_id + '"Comenzar title=" Consulta">Comenzar Consulta</a>' +

                        '<button class="btn btn-outline-primary radius-15 mt-2" onclick="consultar_datos_h(' + item.sa_fice_id + ', \'' + item.nombres + '\')">Historial</button>' +
                        '</div>' +
                        '</div>' +
                        ' </div>' +
                        '</div>' +
                        '</div>';
                });

                $('#citas_actuales').html(lista);
            }
        });
    }


    function show_historial() {
        $('#myModal_historial').modal('show');
    }

    function consultar_datos_h(id_paciente = '', nombres = '') {
        $('#title_nombre').html(nombres);
        var consulta = '';
        var cont = 1;
        $.ajax({
            data: {
                id_ficha: id_paciente
            },
            url: '../controlador/consultasC.php?listar_consulta_ficha=true',
            type: 'post',
            dataType: 'json',
            //Para el id representante tomar los datos con los de session
            success: function(response) {
                console.log(response);

                $('#tbl_consultas_pac').DataTable({
                    destroy: true, // Destruir la tabla existente antes de recrearla
                    data: response,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                    },
                    responsive: true, // Datos de las consultas médicas
                    columns: [
                        // Definir las columnas
                        {
                            data: null,
                            render: function(data, type, row, meta) {
                                // Usar el contador autoincremental proporcionado por DataTables
                                return meta.row + 1;
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, item) {
                                if (item.sa_conp_desde_hora.date == null || item.sa_conp_fecha_ingreso.date == null) {
                                    return '';
                                } else {
                                    return fecha_nacimiento_formateada(item.sa_conp_fecha_creacion.date) + ' / ' + obtener_hora_formateada(item.sa_conp_fecha_creacion.date);
                                }
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, item) {
                                if (item.sa_conp_desde_hora.date == null || item.sa_conp_hasta_hora.date == null) {
                                    return '';
                                } else {
                                    return fecha_nacimiento_formateada(item.sa_conp_fecha_ingreso.date) + ' / [' + obtener_hora_formateada(item.sa_conp_desde_hora.date) + ' / ' + obtener_hora_formateada(item.sa_conp_hasta_hora.date) + ']';
                                }
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, item) {
                                if (item.sa_conp_tipo_consulta == 'consulta') {
                                    return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + item.sa_conp_tipo_consulta + '</div>';
                                } else {
                                    return '<div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">' + item.sa_conp_tipo_consulta + '</div>';
                                }
                            }
                        },
                        {
                            data: null,
                            render: function(data, type, item) {
                                //return '<a class="btn btn-primary btn-sm" target="_blank"  title="Enviar Mensaje" href="../vista/inicio.php?mod=7&acc=registrar_consulta_estudiante&id_ficha=' + item.sa_conp_id + '&id_estudiante=' + item.sa_fice_id + '&id_consulta=2&ver=1">' + '<i class="bx bx-show-alt me-0"></i>' + '</a>';
                                return '<a class="btn btn-primary btn-sm" target="_blank"  title="Enviar Mensaje" href="#" onclick="abrir_ventana_emergente(' + item.sa_conp_id + ');">' + '<i class="bx bx-show-alt me-0"></i>' + '</a>';
                            
                            }
                        },

                    ],
                    order: [
                        [1, 'desc'] // Ordenar por la segunda columna (índice 1) en orden ascendente
                    ]
                });

                show_historial();
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
                            Atenciones Estudiantes
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->


        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4" id="citas_actuales">


        </div>
        <!--end row-->
    </div>
</div>


<div class="modal" id="myModal_historial" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Historial de consultas - <b id="title_nombre" class="text-primary"></b></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped responsive text-center" id="tbl_consultas_pac" style="width:100%">

                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Tipo de Atención</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<script src="../js/ENFERMERIA/consulta_medica.js"></script>