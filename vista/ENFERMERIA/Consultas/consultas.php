<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabla_administrativo').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/consultasC.php?listar_todo=true',
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
                            return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + item.sa_conp_tipo_consulta + '</div>';
                        } else {
                            return '<div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">' + item.sa_conp_tipo_consulta + '</div>';
                        }
                    }
                },

                {
                    data: null,
                    render: function(data, type, item) {
                        if (item.sa_conp_desde_hora.date == null || item.sa_conp_fecha_ingreso.date == null) {
                            return '';
                        } else {
                            //Fecha de creacion para saber el dia en el que se creo
                            return fecha_nacimiento_formateada(item.sa_conp_fecha_creacion.date) + ' / ' + obtener_hora_formateada(item.sa_conp_fecha_creacion.date);
                        }
                    }
                },

            ],
            order: [
                [3, 'desc']
            ],
        });
    });

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
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Consultas</h5>
                        </div>

                        <hr>

                        <section class="content pt-4">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tabla_administrativo" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Nombres</th>
                                                <th>Tipo de Atención</th>
                                                <th>Fecha Agendado</th>
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