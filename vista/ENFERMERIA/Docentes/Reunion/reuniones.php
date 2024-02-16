<?php

$id_docente = '2';

if (isset($_POST['id_docente'])) {
    $id_docente = $_POST['id_docente'];
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
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/reunionesC.php',
                data: function(d) {
                    d.listar_todo_docentes = true; // Otros parámetros que puedas necesitar
                    // Agrega el parámetro ac_reunion_id
                    d.id_docente = <?= $id_docente ?>; // Puedes obtener este valor dinámicamente según tus necesidades
                },
                dataSrc: ''
            },

            columns: [{
                    data: 'ac_horarioD_ubicacion'
                },
                {
                    data: 'nombre_representante'
                },
                {
                    data: 'ac_reunion_motivo'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return fecha_nacimiento_formateada(item.ac_horarioD_fecha_disponible.date);
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return obtener_hora_formateada(item.ac_horarioD_inicio.date);
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return obtener_hora_formateada(item.ac_horarioD_fin.date);
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
                {
                    data: null,
                    render: function(data, type, item) {

                        return `<button type="button" class="btn btn-primary btn-sm" onclick="abrir_modal('${item.ac_reunion_id}');"><i class="bx bx-plus me-0"></i></button>`
                    }
                },


            ],
            order: [
                [1, 'asc']
            ],
        });
    }

    // Función para abrir el modal
    function abrir_modal(ac_reunion_id) {
        $('#ac_reunion_id').val(ac_reunion_id);

        if (ac_reunion_id) {
            $.ajax({
                url: '../controlador/reunionesC.php?listar=true',
                data: {
                    id_reunion: ac_reunion_id
                },
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    console.log(response)

                    $('#ac_reunion_motivo').val(response[0].ac_reunion_motivo);
                    $('#ac_reunion_observacion').val(response[0].ac_reunion_observacion);
                    $('#ac_reunion_estado').val(response[0].ac_reunion_estado);

                }
            });
        }

        $('#modal_agendar_reunion').modal('show');
    }

    function guardar_obs() {

        var ac_reunion_id = $('#ac_reunion_id').val();
        var ac_reunion_observacion = $('#ac_reunion_observacion').val();
        var ac_reunion_estado = $('#ac_reunion_estado').val();

        //alert(ac_horarioD_inicio + ' ' + ac_horarioD_fin);

        var parametros = {
            'ac_reunion_id': ac_reunion_id,
            'ac_reunion_observacion': ac_reunion_observacion,
            'ac_reunion_estado': ac_reunion_estado,
        }

        console.log(parametros);

        if (ac_reunion_id != '' && ac_reunion_observacion != '' && ac_reunion_estado != '') {
            $.ajax({
                url: '../controlador/reunionesC.php?insertar=true',
                data: {
                    parametros: parametros
                },
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response)
                    Swal.fire('', 'Observación Registrado.', 'success');
                }
            });

        } else {
            Swal.fire('', 'Falta llenar los campos.', 'error');
        }
        
        if (tabla_reunion) {
            tabla_reunion.destroy(); // Destruir la instancia existente del DataTable
        }

        $('#modal_agendar_reunion').modal('hide');
        
        cargar_tabla();

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
                                                <th>Representante</th>
                                                <th>Motivo</th>
                                                <th>Fecha Turno</th>
                                                <th>Hora de Inicio</th>
                                                <th>Hora Fin</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
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

<div class="modal" id="modal_agendar_reunion" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5>Obervaciones del Turno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="ac_horarioC_materia">Motivo de la Reunión: <label class="text-danger">*</label></label>
                        <input type="text" id="ac_reunion_motivo" name="ac_reunion_motivo" class="form-control form-control-sm" disabled>

                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="ac_horarioD_fecha_disponible">Estado de la Reunión: <label class="text-danger">*</label></label>
                        <select name="ac_reunion_estado" id="ac_reunion_estado" class="form-select form-select-sm">
                            <option value="0" selected disabled>-- Seleccione --</option>
                            <option value="1">Completa</option>
                            <option value="2">Docente Anula</option>
                            <option value="3">Representante Ausente</option>


                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="ac_horarioC_materia">Observación: <label class="text-danger">*</label></label>
                        <input type="text" id="ac_reunion_observacion" name="ac_reunion_observacion" class="form-control form-control-sm">
                    </div>
                </div>

                <input type="hidden" name="ac_reunion_id" id="ac_reunion_id">

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-sm" onclick="guardar_obs()"><i class="bx bx-save"></i> Guardar</button>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>