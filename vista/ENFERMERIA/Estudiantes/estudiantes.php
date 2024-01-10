<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabla_estudiante').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/estudiantesC.php?listar_todo=true',
                dataSrc: ''
            },
            columns: [{
                    data: 'sa_est_cedula'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<a href="#" onclick="enviar_ID_estudiante(' + item.sa_est_id + ', ' + item.sa_id_seccion + ', ' + item.sa_id_grado + ', ' + item.sa_id_paralelo + ', ' + item.sa_id_representante +')"><u>' + item.sa_est_primer_apellido + ' ' + item.sa_est_segundo_apellido + ' ' + item.sa_est_primer_nombre + ' ' + item.sa_est_segundo_nombre + '</u></a>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return item.sa_sec_nombre + ' / ' + item.sa_gra_nombre + ' / ' + item.sa_par_nombre;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return calcular_edad_fecha_nacimiento(item.sa_est_fecha_nacimiento.date);
                    }
                },
            ]
        });
    });

    function enviar_ID_estudiante(id, sa_id_seccion, sa_id_grado, sa_id_paralelo, id_representante) {
        // Actualiza el valor del campo de entrada con el ID
        $('#sa_est_id').val(id);
        $('#sa_sec_id').val(sa_id_seccion);
        $('#sa_gra_id').val(sa_id_grado);
        $('#sa_par_id').val(sa_id_paralelo);
        $('#id_representante').val(id_representante);

        // Envía el formulario por POST
        $('#form_enviar').submit();
    }
</script>

<form id="form_enviar" action="../vista/inicio.php?mod=7&acc=registrar_estudiantes" method="post" style="display: none;">
    <input type="hidden" id="sa_est_id" name="sa_est_id">
    <input type="hidden" id="sa_sec_id" name="sa_sec_id">
    <input type="hidden" id="sa_gra_id" name="sa_gra_id">
    <input type="hidden" id="sa_par_id" name="sa_par_id">
    <input type="hidden" id="id_representante" name="id_representante">
</form>

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
                            Comunidad Educativa - Estudiantes
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
                            <h5 class="mb-0 text-primary">Estudiantes</h5>

                            <div class="row mx-1">
                                <div class="col-sm-12" id="btn_nuevo">
                                    <a href="../vista/inicio.php?mod=7&acc=registrar_estudiantes" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-4">
                            <div class="container-fluid">

                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tabla_estudiante" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Nombre</th>
                                                <th>Sección/Grado/Paralelo</th>
                                                <th>Edad</th>
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