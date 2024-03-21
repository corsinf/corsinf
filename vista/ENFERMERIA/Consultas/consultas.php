<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        cargar_tabla();
    });

    function cargar_tabla() {
        tabla = $('#txt_tabla').val();

        txt_fecha_inicio = $('#txt_fecha_inicio').val();
        txt_fecha_fin = $('#txt_fecha_fin').val();

        var fecha_Hoy = new Date();
        var formato_Fecha = fecha_Hoy.getFullYear() + '-' + (fecha_Hoy.getMonth() + 1) + '-' + fecha_Hoy.getDate();


        tabla_consultas = $('#tabla_consultas').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/consultasC.php?listar_todo=true',
                data: function(d) {
                    d.listar_todo = true;
                    d.tabla = tabla;
                    d.fecha_inicio = txt_fecha_inicio;
                    d.fecha_fin = txt_fecha_fin;

                },
                dataSrc: ''
            },
            columns: [{
                    data: 'sa_pac_cedula'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<a href="#" onclick="ver_pdf(' + item.sa_conp_id + ', \'' + item.sa_conp_tipo_consulta + '\',' + item.sa_pac_id + ', 1)"><u>' + item.sa_pac_apellidos + ' ' + item.sa_pac_nombres + '</u></a>';

                    }
                },

                {
                    data: null,
                    render: function(data, type, item) {
                        if (item.sa_conp_tipo_consulta == 'consulta') {
                            return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + (item.sa_conp_tipo_consulta).toUpperCase() + '</div>';
                        } else {
                            return '<div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">' + (item.sa_conp_tipo_consulta).toUpperCase() + '</div>';
                        }
                    }
                },

                {
                    data: null,
                    render: function(data, type, item) {
                        if (item.sa_conp_desde_hora == null || item.sa_conp_fecha_ingreso == null) {
                            return '';
                        } else {
                            //Fecha de creacion para saber el dia en el que se creo
                            return fecha_nacimiento_formateada(item.sa_conp_fecha_creacion) + ' / ' + obtener_hora_formateada(item.sa_conp_fecha_creacion);
                        }
                    }
                },

            ],
            order: [
                [3, 'desc']
            ],
            dom: '<"top"Bfr>t<"bottom"lip>',

            buttons: [{
                    extend: 'excel',
                    text: '<i class="bx bxs-spreadsheet me-0"></i> Exportar a Excel',
                    title: ('Reporte Excel ' + formato_Fecha),
                    filename: ('Reporte Excel ' + formato_Fecha),
                    className: 'btn-outline-success btn-sm'
                },
                {
                    extend: 'pdf',
                    text: '<i class="bx bxs-file-pdf me-0"></i> Exportar a PDF',
                    title: ('Reporte PDF ' + formato_Fecha),
                    filename: ('Reporte PDF ' + formato_Fecha),
                    className: 'btn-outline-danger btn-sm'
                }
            ],
            initComplete: function() {
                // Mover los botones al contenedor personalizado
                $('#contenedor_botones').append($('.dt-buttons'));
            }
        });
    }

    function buscar_paciente() {

        if (tabla_consultas) {
            tabla_consultas.destroy(); // Destruir la instancia existente del DataTable
        }

        cargar_tabla();
    }

    function buscar_fechas() {

        if (tabla_consultas) {
            tabla_consultas.destroy(); // Destruir la instancia existente del DataTable
        }
        cargar_tabla();
    }

    function ver_pdf(id_consulta, tipo_consulta, sa_pac_id, btn_regresar) {

        window.open('../vista/inicio.php?mod=7&acc=detalle_consulta&pdf_consulta=true&id_consulta=' + id_consulta + '&id_paciente=' + sa_pac_id + '&tipo_consulta=' + tipo_consulta + '&btn_regresar=' + btn_regresar, '_blank');
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
                            Consultas
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-0">


                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-6">
                                                <div class="card-title d-flex align-items-center">

                                                    <h5 class="card-title fw-bold">Filtros</h5>
                                                </div>
                                            </div>

                                            <div class="col-6 text-end" hidden>
                                                <button class="btn btn-outline-danger btn-sm" onclick=""><i class='bx bxs-file-pdf'></i> Reporte</button>
                                            </div>

                                            <div class="col-6 text-end">
                                                <div id="contenedor_botones"></div>
                                            </div>


                                        </div>


                                        <div class="row pt-1">
                                            <div class="col-md-3">
                                                <label for="txt_fecha_inicio" class="form-label fw-bold">Fecha Inicio <label style="color: red;">*</label> </label>
                                                <input type="date" class="form-control form-control-sm" id="txt_fecha_inicio" name="txt_fecha_inicio">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="txt_fecha_fin" class="form-label fw-bold">Fecha Fin <label style="color: red;">*</label> </label>
                                                <input type="date" class="form-control form-control-sm" id="txt_fecha_fin" name="txt_fecha_fin">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="txt_tabla" class="form-label fw-bold">Tipo de Paciente </label>
                                                <select name="txt_tabla" id="txt_tabla" class="form-select form-select-sm" onchange="buscar_paciente();">
                                                    <option value="">Todos</option>
                                                    <option value="estudiantes">Estudiantes</option>
                                                    <option value="docentes">Docentes</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="modal-footer pt-2" id="seccion_boton_consulta">

                                                    <button class="btn btn-primary btn-sm px-3" onclick="buscar_fechas();" type="button"><i class='bx bx-search'></i> Buscar</button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <section class="content pt-4">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tabla_consultas" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Nombres</th>
                                                <th>Tipo de Atención</th>
                                                <th>Fecha Atendido</th>
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