<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabla_administrativo').DataTable({
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/SALUD_INTEGRAL/administrativosC.php?listar_todo=true',
                dataSrc: ''
            },
            columns: [{
                    data: 'sa_adm_cedula'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<a href="#" onclick="enviar_ID(' + item.sa_adm_id + ')"><u>' + item.sa_adm_primer_apellido + ' ' + item.sa_adm_segundo_apellido + ' ' + item.sa_adm_primer_nombre + ' ' + item.sa_adm_segundo_nombre + '</u></a>';
                    }
                },
                {
                    data: 'sa_adm_correo'
                },
                {
                    data: 'sa_adm_telefono_1'
                },
                {
                    data: null,
                    render: function(data, type, item) {

                        fecha_nacimiento = item.sa_adm_fecha_nacimiento;
                        //fecha_nacimiento_calc = ;
                        
                        salida = fecha_nacimiento ? calcular_edad_fecha_nacimiento(item.sa_adm_fecha_nacimiento) : '';

                        return salida;
                    }
                },
            ],
            order: [
                [1, 'asc']
            ],
        });
    });

    function enviar_ID(id) {
        // Actualiza el valor del campo de entrada con el ID
        $('#sa_adm_id').val(id);

        // Envía el formulario por POST
        $('#form_enviar').submit();
    }
</script>

<form id="form_enviar" action="../vista/inicio.php?mod=7&acc=registrar_administrativos" method="post" style="display: none;">
    <input type="hidden" id="sa_adm_id" name="sa_adm_id" value="">
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
                            Comunidad Educativa
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
                            <h5 class="mb-0 text-primary">Administrativos</h5>

                            <div class="row mx-1">
                                <div class="col-sm-12" id="btn_nuevo">
                                    <a href="../vista/inicio.php?mod=7&acc=registrar_administrativos" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-4">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tabla_administrativo" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Nombre</th>
                                                <th>Correo</th>
                                                <th>Teléfono</th>
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