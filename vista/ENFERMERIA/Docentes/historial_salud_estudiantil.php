<?php

$id = $_SESSION['INICIO']['NO_CONCURENTE'];
//$id = 10;

if ($id != null && $id != '') {
    $id_docente = $id;
}

?>

<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        cargar_docente_paralelo();
        cargar_tabla();



        //Queda por verificar si se usa de esta forma
        setInterval(function() {
            // Mostrar el indicador de carga antes de la actualización


            /*if (tabla_consultas) {
                tabla_consultas.destroy(); // Destruir la instancia existente del DataTable
            }

            cargar_tabla();*/

            /*var tabla_consultas = $('#tabla_consultas').DataTable();

            // Datos de la nueva fila
            var nuevaFila = {
                sa_pac_cedula: '123456789',
                sa_pac_apellidos: 'ApellidoNuevo',
                sa_pac_nombres: 'NombreNuevo',
                sa_conp_tipo_consulta: 'consulta',
                sa_conp_desde_hora: {
                    date: '2024-03-09 12:30:00.000000'
                }, // Reemplaza con tu fecha
                sa_conp_fecha_ingreso: {
                    date: '2024-03-09 12:30:00.000000'
                }, // Reemplaza con tu fecha
                sa_conp_estado_revision: '1', // 0 para 'En espera', 1 para 'Finalizado'
                sa_conp_fecha_creacion: {
                    date: '2024-03-09 12:30:00.000000'
                },
                // ...
            };

            // Agregar la nueva fila
            tabla_consultas.row.add(nuevaFila).draw();*/

        }, 3000);
    });

    function cargar_tabla() {
        fecha_actual_estado = 1;

        id_paralelo = $('#ac_paralelo_id_busqueda').val();

        if (id_paralelo == '') {
            fecha_actual_estado = 1;
        } else if (id_paralelo == 'todos') {
            fecha_actual_estado = 0;
        }


        if (true) {
            var fecha_Hoy = new Date();
            var formato_Fecha = fecha_Hoy.getFullYear() + '-' + (fecha_Hoy.getMonth() + 1) + '-' + fecha_Hoy.getDate();
            tabla_consultas = $('#tabla_consultas').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                responsive: true,
                ajax: {
                    url: '../controlador/consultasC.php',
                    data: function(d) {
                        if (id_paralelo === '' || id_paralelo === 'todos') {
                            d.lista_con_est_doc = true;
                            d.id_docente = '<?= $id_docente; ?>';
                            d.fecha_actual_estado = fecha_actual_estado;

                        } else {
                            d.lista_con_est = true;
                            d.id_paralelo = id_paralelo;
                        }
                    },
                    dataSrc: ''
                },
                columns: [{
                        data: 'sa_pac_cedula'
                    },
                    {
                        data: null,
                        render: function(data, type, item) {
                            return '<a href="#" onclick="ver_pdf(' + item.sa_conp_id + ', \'' + item.sa_conp_tipo_consulta + '\',' + item.sa_pac_id + ')"><u>' + item.sa_pac_apellidos + ' ' + item.sa_pac_nombres + '</u></a>';

                        }
                    },

                    {
                        data: null,
                        render: function(data, type, item) {
                            if (item.sa_conp_tipo_consulta == 'consulta') {
                                return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + ('Atención médica').toUpperCase() + '</div>';
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
                                return fecha_nacimiento_formateada(item.sa_conp_fecha_creacion) + ' / ' + obtener_hora_formateada_arr(item.sa_conp_fecha_creacion);
                            }
                        }
                    },

                    {
                        data: null,
                        render: function(data, type, item) {
                            if (item.sa_conp_estado_revision == '0') {
                                return '<div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">' + ('En espera').toUpperCase() + '</div>';
                            } else {
                                return '<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">' + ('Finalizado').toUpperCase() + '</div>';
                            }
                        }
                    },

                ],
                order: [

                ],
                /*dom: '<"top"Bfr>t<"bottom"lip>',

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
                }*/
            });
        } else {

            tabla_consultas = $('#tabla_consultas').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
            });
        }
    }

    function cargar_docente_paralelo() {

        var ac_docente_id = '<?= $id_docente; ?>';
        var select = '';

        $.ajax({
            url: '../controlador/docente_paraleloC.php?listar=true',
            data: {
                id_docente: ac_docente_id
            },
            type: 'get',
            dataType: 'json',
            success: function(response) {
                //console.log(response)

                $.each(response, function(i, item) {
                    //console.log(item);
                    select += '<option value="' + item.sa_par_id + '">' + item.sa_sec_nombre + ' / ' + item.sa_gra_nombre + ' / ' + item.sa_par_nombre + '</option>';
                });

                $('#ac_paralelo_id_busqueda').append(select);
            }
        });
    }

    function cargar_solo_paralelos_seleccionados() {

        if (tabla_consultas) {
            tabla_consultas.destroy(); // Destruir la instancia existente del DataTable
        }

        cargar_tabla();
    }

    function ver_pdf(id_consulta, tipo_consulta, sa_pac_id) {
        window.open('../vista/inicio.php?mod=7&acc=detalle_consulta&pdf_consulta=true&id_consulta=' + id_consulta + '&id_paciente=' + sa_pac_id + '&tipo_consulta=' + tipo_consulta + '&btn_regresar=docentes', '_blank');
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
                                                    <h6>
                                                        <?php
                                                        /*echo('<pre>');
                                                        var_dump($_SESSION);
                                                        echo('</pre>');*/
                                                        ?>
                                                    </h6>
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
                                            <div class="col-6">
                                                <label for="ac_horarioD_fecha_disponible">Curso <label class="text-danger">*</label></label>
                                                <select name="ac_paralelo_id_busqueda" id="ac_paralelo_id_busqueda" class="form-select form-select-sm">
                                                    <option value="">Día</option>
                                                    <option value="todos">Todos</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="modal-footer pt-2">

                                                    <button class="btn btn-primary btn-sm px-3" onclick="cargar_solo_paralelos_seleccionados();" type="button"><i class='bx bx-search'></i> Buscar</button>

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
                                                <th>Fecha Agendado</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.container-fluid -->
                        </section>
                        <br><br>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>