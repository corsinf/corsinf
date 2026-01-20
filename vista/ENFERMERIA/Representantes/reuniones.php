<?php

$id = $_SESSION['INICIO']['NO_CONCURENTE'];

if ($id != null && $id != '') {
    $id_representante = $id;
}

?>

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        cargar_tabla();

    });

    function cargar_tabla() {
        tabla_reunion = $('#tabla_reunion').DataTable({
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/SALUD_INTEGRAL/reunionesC.php',
                data: function(d) {
                    d.listar_todo_docentes = true; // Otros parámetros que puedas necesitar
                    // Agrega el parámetro ac_reunion_id
                    d.id_representante = <?= $id_representante ?>; // Puedes obtener este valor dinámicamente según tus necesidades
                },
                dataSrc: ''
            },

            columns: [{
                    data: 'ac_cubiculo_nombre'
                },
                {
                    data: 'nombre_docente'
                },
                {
                    data: 'ac_reunion_motivo'
                },
                {
                    data: 'ac_reunion_descripcion'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return (item.ac_horarioD_fecha_disponible);
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return obtener_hora_formateada(item.ac_horarioD_inicio);
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return obtener_hora_formateada(item.ac_horarioD_fin);
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        if (item.ac_reunion_estado == 0) {
                            return '<div class="badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3">Pendiente</div>';

                        } else if (item.ac_reunion_estado == 1) {
                            return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">Completa</div>';

                        } else if (item.ac_reunion_estado == 2) {
                            return '<div class="badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3">Docente Anula</div>';

                        } else if (item.ac_reunion_estado == 3) {
                            return '<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">Representante Ausente</div>';

                        }

                        return '';
                    }
                },

            ],
            order: [
              
            ],
        });
    }

</script>

<form id="form_enviar" action="../vista/inicio.php?mod=7&acc=registrar_representantes" method="post" style="display: none;">
    <input type="hidden" id="sa_rep_id" name="sa_rep_id" value="">
</form>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Accesos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Reuniones
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
                            <h5 class="mb-0 text-primary">Reuniones</h5>

                        </div>

                        <hr>

                        <section class="content pt-4">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tabla_reunion" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Ubicación</th>
                                                <th>Docente</th>
                                                <th>Motivo</th>
                                                <th>Descripción Motivo</th>
                                                <th>Fecha Turno</th>
                                                <th>Hora Inicio</th>
                                                <th>Hora Fin</th>
                                                <th>Estado</th>
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
