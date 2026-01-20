<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        tbl_personas = $('#tbl_personas').DataTable($.extend({}, configuracion_datatable('Pasantes', 'pasantes'), {
            reponsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/FORMACION/fo_personasC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        botones = '';
                        botones += '<div class="d-flex justify-content-center">';
                        botones += `<a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=fo_asistencias_pasantes&_id=${item._id}" class="btn btn-primary btn-xs me-1"><i class="bx bx-file fs-5 me-0"></i></a>`;
                        botones += `<a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=fo_informes_asistencias&_id=${item._id}" class="btn btn-danger btn-xs me-1"><i class="bx bxs-file-pdf fs-5 me-0"></i></a>`;
                        botones += `<buttom type="buttom" class="btn btn-info btn-xs me-1" onclick="abrir_modal_adicional();"><i class="bx bxs-user fs-5 me-0 text-white"></i></buttom>`;
                        botones += '</div>';

                        return botones;

                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {

                        return fecha_formateada_hora(item.fecha_creacion);
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=fo_registrar_pasantes&_id=${item._id}`;
                        return `<a href="${href}"><u>${item.primer_apellido} ${item.segundo_apellido} ${item.primer_nombre} ${item.segundo_nombre}</u></a>`;
                    }
                },
                // Estado
                // Porcentaje
                // Horas Cumplir 
                {
                    data: null,
                    render: function(data, type, item) {
                        if (item.estado_actividades == 0) {
                            return `<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">En proceso</div>`;
                        } else if (item.estado_actividades == 1) {
                            return `<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">Finalizado</div>`;
                        } else if (item.estado_actividades == 2) {
                            return `<div class="badge rounded-pill text-dark bg-warning p-2 text-uppercase px-3">Suspendido</div>`;
                        }
                    }
                },

                {
                    data: null,
                    render: function(data, type, item) {
                        random = getRandomInt(1, 100);

                        return `<div class="pt-0">
                                    <h6 class="mb-0 text-end fw-bold">${random} de 240 (horas)</h6>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: ${random}%;" aria-valuenow="${random}" aria-valuemin="0" aria-valuemax="100">${random}%</div>
                                    </div>
                                </div>`;
                    }

                },
            ],
            order: [
                [1, 'asc']
            ],
        }));
    });

    function abrir_modal_adicional() {
        $('#modal_pasantes_adicional').modal('show');
    }

    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pasantes</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lista de Pasantes
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

                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=fo_registrar_pasantes"
                                            type="button" class="btn btn-success btn-sm">
                                            <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                        </a>

                                    </div>

                                </div>
                            </div>

                            <div class="col-12 col-md-6 text-md-end text-start">
                                <div id="contenedor_botones"></div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_personas" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">Acción</th>
                                                <th>Fecha De Ingreso</th>
                                                <th>Nombres</th>
                                                <th>Estado</th>
                                                <th>Porcentaje</th>
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


<div class="modal" id="modal_pasantes_adicional" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary">Información</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div>
                    <input type="hidden" name="txt_id_registro" id="txt_id_registro">

                    <div class="row pt-2 mb-col">
                        <div class="col-md-12">
                            <label for="ddl_sexo" class="form-label">Empresa </label>
                            <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo">
                                <option selected disabled>-- Seleccione --</option>
                                <option value="Femenino">Corsinf</option>
                                <option value="Masculino">Apudata</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-6">
                            <label for="ddl_sexo" class="form-label">Tutor </label>
                            <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo">
                                <option selected disabled>-- Seleccione --</option>
                                <option value="Femenino">Ruben</option>
                                <option value="Masculino">Javier</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="ddl_sexo" class="form-label">Formacion </label>
                            <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo">
                                <option selected disabled>-- Seleccione --</option>
                                <option value="Femenino">Cuarto Nivel</option>
                                <option value="Masculino">Tercer Nivel</option>
                                <option value="Masculino">Segundo Nivel</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-6">
                            <label for="ddl_sexo" class="form-label">Pasante </label>
                            <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo">
                                <option selected disabled>-- Seleccione --</option>
                                <option value="Femenino">Hayes Hayes Mildred Mildred</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="ddl_sexo" class="form-label">Universidad/Instituto </label>
                            <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo">
                                <option selected disabled>-- Seleccione --</option>
                                <option value="Femenino">PUCE</option>
                                <option value="Masculino">Salesiana</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-6">
                            <label for="txt_fecha_nacimiento" class="form-label">Fecha de Inicio </label>
                            <input type="date" class="form-control form-control-sm" id="txt_fecha_nacimiento" name="txt_fecha_nacimiento" onblur="calcular_edad('txt_edad', this.value);">
                        </div>

                        <div class="col-md-6">
                            <label for="txt_fecha_nacimiento" class="form-label">Fecha de Finalización </label>
                            <input type="date" class="form-control form-control-sm" id="txt_fecha_nacimiento" name="txt_fecha_nacimiento" onblur="calcular_edad('txt_edad', this.value);">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-12">
                            <label for="txt_obs_pasantes">Descripción </label>
                            <textarea class="form-control form-control-sm" name="txt_obs_pasantes" id="txt_obs_pasantes"></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_informacion_personal" onclick="insertar_editar_informacion_personal();">Guardar</button>
                </div>

            </div>
        </div>
    </div>
</div>