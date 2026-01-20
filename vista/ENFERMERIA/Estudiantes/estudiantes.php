<script src="../js/ENFERMERIA/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        tabla_estudiante = $('#tabla_estudiante').DataTable({
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/SALUD_INTEGRAL/estudiantesC.php?listar_todo=true',
                dataSrc: ''
            },
            dom: '<"top"Bfr>t<"bottom"lip>',
            buttons: [{
                    extend: 'excel',
                    text: '<i class="bx bxs-file-pdf me-0"></i> Exportar a Excel',
                    title: 'Título del archivo Excel',
                    filename: 'nombre_archivo_excel'
                },
                {
                    extend: 'pdf',
                    text: '<i class="bx bxs-spreadsheet me-0"></i> Exportar a PDF',
                    title: 'Título del archivo PDF',
                    filename: 'nombre_archivo_PDF'
                }
            ],
            columns: [{
                    data: 'sa_est_cedula'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return '<a href="#" onclick="enviar_ID_estudiante(' + item.sa_est_id + ', ' + item.sa_id_seccion + ', ' + item.sa_id_grado + ', ' + item.sa_id_paralelo + ', ' + item.sa_id_representante + ', ' + item.sa_id_representante_2 + ')"><u>' + item.sa_est_primer_apellido + ' ' + item.sa_est_segundo_apellido + ' ' + item.sa_est_primer_nombre + ' ' + item.sa_est_segundo_nombre + '</u></a>';
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

                        fecha_nacimiento = item.sa_est_fecha_nacimiento;
                        //fecha_nacimiento_calc = ;

                        salida = fecha_nacimiento ? calcular_edad_fecha_nacimiento(item.sa_est_fecha_nacimiento) : '';

                        return salida;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return `<button type="button" class="btn btn-primary btn-sm m-1" onclick="actualizar_idukay('${item.sa_est_id}')"><i class="lni lni-spinner-arrow fs-6 me-0 fw-bold"></i></button>`;
                    }
                },
            ],
            order: [
                [1, 'asc']
            ],
            initComplete: function() {
                // Mover los botones al contenedor personalizado
                $('#contenedor_botones').append($('.dt-buttons'));
            }
        });
    });

    function enviar_ID_estudiante(id, sa_id_seccion, sa_id_grado, sa_id_paralelo, id_representante, id_representante_2) {
        // Actualiza el valor del campo de entrada con el ID
        $('#sa_est_id').val(id);
        $('#sa_sec_id').val(sa_id_seccion);
        $('#sa_gra_id').val(sa_id_grado);
        $('#sa_par_id').val(sa_id_paralelo);
        $('#id_representante').val(id_representante);
        $('#id_representante_2').val(id_representante_2);

        // Envía el formulario por POST
        $('#form_enviar').submit();
    }

    function actualizar_idukay(id_estudiante) {
        Swal.fire({
            title: 'Por favor, espere',
            text: 'Procesando la solicitud...',
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '../controlador/SALUD_INTEGRAL/cat_configuracionGC_IDUKAY.php?idukay_actualizar_estudiante=true',
            data: {
                id_estudiante: id_estudiante
            },
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response == 1) {
                    Swal.close();
                    Swal.fire('', 'Actualización exitosa.', 'success');
                    tabla_estudiante.ajax.reload();
                    $('#pnl_idukay').html('<p>Actualización exitosa.</p>');
                } else if (response == -10) {
                    Swal.close();
                    $('#pnl_idukay').html('<p>Error al subir datos a la Base de Datos.</p>');
                    Swal.fire('', 'Error al subir datos a la Base de Datos.', 'error');
                } else if (response == -11) {
                    Swal.close();
                    $('#pnl_idukay').html('<p>Error al conectarse con la API de Idukay.</p>');
                    Swal.fire('', 'Error al conectarse con la API de Idukay.', 'error');
                }

            },
            error: function() {
                // Manejo de errores
                $('#pnl_idukay').html('<p>Error al cargar los configs.</p>');
            }
        });
    }

    function actualizar_idukay_json(id_estudiante_idukay) {


        alert(id_estudiante_idukay);

        $.ajax({
            url: '../controlador/SALUD_INTEGRAL/cat_configuracionGC_IDUKAY.php?idukay_actualizar_estudiante=true',
            data: {
                id_estudiante_idukay: id_estudiante_idukay
            },
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);

                // Contar el número de registros
                var numRegistros = response.response.length;

                // Mostrar el número de registros
                var contadorElement = $('<p></p>').text('Número de registros: ' + numRegistros);
                $('#pnl_idukay').append(contadorElement);

                // Convertir el JSON a una cadena con formato
                var formattedJson = JSON.stringify(response, null, 2);

                // Crear un elemento <pre> para mostrar el JSON con formato
                var preElement = $('<pre></pre>').text(formattedJson);

                // Agregar el <pre> al panel
                $('#pnl_idukay').append(preElement);
            },
            error: function() {
                // Manejo de errores
                $('#pnl_idukay').append('<p>Error al cargar los configs.</p>');
            }
        });
    }
</script>

<form id="form_enviar" action="../vista/inicio.php?mod=7&acc=registrar_estudiantes" method="post" style="display: none;">
    <input type="hidden" id="sa_est_id" name="sa_est_id">
    <input type="hidden" id="sa_sec_id" name="sa_sec_id">
    <input type="hidden" id="sa_gra_id" name="sa_gra_id">
    <input type="hidden" id="sa_par_id" name="sa_par_id">
    <input type="hidden" id="id_representante" name="id_representante">
    <input type="hidden" id="id_representante_2" name="id_representante_2">
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

                        <div class="row">

                            <div class="col-6">
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
                            </div>

                            <div class="col-6 text-end">
                                <div id="contenedor_botones"></div>
                            </div>

                            <div id="pnl_idukay">

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