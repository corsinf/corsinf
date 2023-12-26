<script src="<?= $url_general ?>/js/ENFERMERIA/operaciones_generales.js"></script>
<script src="<?= $url_general ?>/js/ENFERMERIA/pacientes.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tbl_pacientes').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '<?php echo $url_general ?>/controlador/pacientesC.php?listar_todo=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        return '<div class="text-center"><a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=consultas_pacientes&pac_id=' + item.sa_pac_id + '" class="btn btn-warning btn-sm " title="Historial de Consultas"><i class="bx bxs-folder me-0"></i></i></a></div>';
                    }
                }, {
                    data: 'sa_pac_cedula'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<a href="#" onclick="gestion_paciente_comunidad(' + item.sa_pac_id_comunidad + ', \'' + item.sa_pac_tabla + '\');"><u>' + item.sa_pac_apellidos + ' ' + item.sa_pac_nombres + '</u></a>';
                    }
                },
                {
                    data: 'sa_pac_correo'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return calcular_edad_fecha_nacimiento(item.sa_pac_fecha_nacimiento.date);
                    }
                },
                {
                    data: 'sa_pac_tabla'
                },
            ]
        });
    });
</script>

<form id="form_enviar" action="<?= $url_general ?>/vista/inicio.php?mod=7&acc=ficha_medica_pacientes" method="post" style="display: none;">
    <input type="hidden" id="sa_pac_id" name="sa_pac_id" value="">
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
                            <h5 class="mb-0 text-primary">Pacientes</h5>

                            <div class="row mx-1">
                                <div class="col-sm-12" id="btn_nuevo">
                                    <a href="<?= $url_general ?>/vista/inicio.php?mod=7&acc=registrar_pacientes" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-4">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_pacientes" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="10px">Consultas</th>
                                                <th>Cédula</th>
                                                <th>Nombres</th>
                                                <th>Correo</th>
                                                <th>Edad</th>
                                                <th>Tipo Paciente</th>
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