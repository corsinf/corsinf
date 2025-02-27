<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script>
    $(document).ready(function() {
        $('#tabla_postulantes').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: false,
            ajax: {
                url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?listar_todo=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_informacion_personal&id=${item._id}`;

                        btns = `<a href="${href}" class="btn btn-xs btn-primary" title="CV Postulante"><i class="bx bxs-user-pin fs-6 me-0"></i></a>`;

                        if (item.th_pos_contratado == 0) {
                            btns +=
                                ` <buttom onclick="agregar_postulante_persona('${item.th_pos_cedula}');" class="btn btn-xs btn-success" title="Agregar a Personas"><i class="bx bx-user-plus fs-6 me-0"></i></buttom>`;
                        }

                        return btns;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_postulantes&id=${item._id}`;
                        return `<a title="Editar a Postulante" href="${href}"><u> ${item.th_pos_primer_apellido} ${item.th_pos_segundo_apellido} ${item.th_pos_primer_nombre} ${item.th_pos_segundo_nombre}</u></a>`;
                    }
                },
                {
                    data: 'th_pos_cedula'
                },
                {
                    data: 'th_pos_correo'
                },
                {
                    data: 'th_pos_telefono_1'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        fecha_nacimiento = item.th_pos_fecha_nacimiento;

                        salida = fecha_nacimiento ? calcular_edad_fecha(item.th_pos_fecha_nacimiento) : '';

                        return salida;
                    }
                },
            ],
            order: [
                [0, 'asc']
            ],
        });
    });

    function agregar_postulante_persona(pos_cedula) {
        $.ajax({
            data: {
                pos_cedula: pos_cedula
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?agregar_postulante_persona=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                handle_ajax_response(response);
            },

            // error: function(xhr, status, error) {
            //     console.log('Status: ' + status);
            //     console.log('Error: ' + error);
            //     console.log('XHR Response: ' + xhr.responseText);

            //     Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            // }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Postulantes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Postulantes</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container-fluid">
            <div class="main-body">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-id-card me-1 font-24 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Postulantes</h5>

                            <div class="row mx-1">
                                <div class="col-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_postulantes" class="btn btn-sm btn-success d-flex align-items-center"><i class="bx bx-plus me-1"></i><span>Nuevo</span></a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="tabla_postulantes">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Nombre</th>
                                        <th>Cédula</th>
                                        <th>Correo</th>
                                        <th>Teléfono</th>
                                        <th>Edad</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>