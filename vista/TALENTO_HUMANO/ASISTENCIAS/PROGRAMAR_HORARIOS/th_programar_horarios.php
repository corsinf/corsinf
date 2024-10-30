<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        tbl_departmento_horario = $('#tbl_departmento_horario').DataTable($.extend({}, configuracion_datatable('Turnos', 'turnos', 'contenedor_botones_departamento'), {
            reponsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_programar_horariosC.php?listar_departamentos_horarios=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_programar_horarios&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre_departamento}</u></a>`;
                    }
                },
                {
                    data: 'nombre_horario'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        salida = fecha_formateada(item.fecha_inicio);
                        return salida;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        salida = fecha_formateada(item.fecha_fin);
                        return salida;
                    }
                },
            ],
            order: [
                [1, 'asc']
            ]
        }));

        tbl_persona_horario = $('#tbl_persona_horario').DataTable($.extend({}, configuracion_datatable('Turnos', 'turnos', 'contenedor_botones_persona'), {
            reponsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_programar_horariosC.php?listar_personas_horarios=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_programar_horarios&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.nombre_persona}</u></a>`;
                    }
                },
                {
                    data: 'nombre_horario'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        salida = fecha_formateada(item.fecha_inicio);
                        return salida;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        salida = fecha_formateada(item.fecha_fin);
                        return salida;
                    }
                },
            ],
            order: [
                [1, 'asc']
            ]
        }));

    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Turnos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lista de Turnos
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

                        <div class="row">

                            <div class="col-12 col-md-6">
                                <div class="card-title d-flex align-items-center">

                                    <div class="" id="btn_nuevo">
                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_programar_horarios"
                                            type="button" class="btn btn-success btn-sm ">
                                            <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                        </a>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="">
                            <div class="">
                                <ul class="nav nav-tabs nav-primary" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="true">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bxs-school font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Departamentos</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-group font-18 me-1'></i>
                                                </div>
                                                <div class="tab-title">Personas</div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                                        <section class="content pt-0">
                                            <div class="container-fluid">

                                             

                                                <div class="table-responsive">
                                                    <table class="table table-striped responsive " id="tbl_departmento_horario" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Departamento</th>
                                                                <th>Horario</th>
                                                                <th>Fecha Inicial</th>
                                                                <th>Fecha Final</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div><!-- /.container-fluid -->
                                        </section>

                                    </div>
                                    <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                                        <section class="content pt-0">
                                            <div class="container-fluid">

                                               
                                                <div class="table-responsive">
                                                    <table class="table table-striped responsive " id="tbl_persona_horario" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Departamento</th>
                                                                <th>Horario</th>
                                                                <th>Fecha Inicial</th>
                                                                <th>Fecha Final</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div><!-- /.container-fluid -->
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>