<script type="text/javascript">
    $(document).ready(function() {
        //listar();

        tabla_solicitudes = $('#tabla_solicitudes').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/PASANTES/01_SEBASTIAN/formularios_firmasC.php?listar=true',
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
                    data: 'fir_sol_numero_identificacion'
                },
                {
                    data: null,
                    render: function(data) {
                        return data.fir_sol_primer_nombre + ' ' + data.fir_sol_segundo_nombre + ' ' + data.fir_sol_primer_apellido + ' ' + data.fir_sol_segundo_apellido;
                    },
                },
                {
                    data: 'fir_sol_ciudad'
                },
                {
                    data: 'fir_sol_tipo_formulario',
                    render: function(data) {
                        if (data == 'persona_natural') {
                            return 'Persona Natural';
                        } else if (data == 'persona_natural_ruc') {
                            return 'Persona Natural RUC';
                        } else if (data == 'persona_juridica') {
                            return 'Persona Jurídica';
                        }
                        return data;
                    }
                },
                {
                    data: 'fir_sol_fecha_creacion',
                    render: function(data) {
                        var date = new Date(data);
                        var fechaFormateada = date.getFullYear() + '-' + 
                        ('0' + (date.getMonth() + 1)).slice(-2) + '-' + 
                        ('0' + date.getDate()).slice(-2) + ' ' + 
                        ('0' + date.getHours()).slice(-2) + ':' + 
                        ('0' + date.getMinutes()).slice(-2);
                        return fechaFormateada;
                        }
                }
            ],
            order: [
                [0, 'DESC']
            ],
            initComplete: function() {
                // Mover los botones al contenedor personalizado
                $('#contenedor_botones').append($('.dt-buttons'));
            }


        });
    });

    function listar() {

        $.ajax({
            // data:  {parametros:parametros},
            url: '../controlador/PASANTES/01_SEBASTIAN/formularios_firmasC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);

            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Solicitudes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Persona Natural, RUC y Juridica</li>
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
                                    <h5 class="mb-0 text-primary">Solicitudes</h5>

                                    <div class="row mx-1">
                                        <div class="col-sm-12" id="btn_nuevo">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 text-end">
                                <div id="contenedor_botones"></div>
                            </div>

                        </div>

                        <hr>

                        <section class="content pt-4">
                            <div class="container-fluid">

                                <div class="table-responsive">
                                    <table class="table table-striped responsive" id="tabla_solicitudes" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Nombre Completo</th>
                                                <th>Ciudad</th>
                                                <th>Tipo</th>
                                                <th>Fecha</th>    
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