<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        cargar_tabla();
    });


    function cargar_tabla() {
        tabla = $('#txt_tabla').val();

        txt_fecha_inicio = $('#txt_fecha_inicio').val();
        txt_fecha_fin = $('#txt_fecha_fin').val();

        tbl_marcaciones = $('#tbl_marcaciones').DataTable($.extend({}, configuracion_datatable('Marcaciones', 'Marcaciones'), {
            reponsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_control_accesoC.php?listar=true',
                type: 'POST',
                data: function(d) {
                    d.listar_todo = true;
                    d.fecha_inicio = txt_fecha_inicio;
                    d.fecha_fin = txt_fecha_fin;

                },
                dataSrc: ''
            },
            columns: [

                {
                    data: 'nombre'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        salida = fecha_formateada_hora(item.fecha);
                        return salida;
                    }
                },
                {
                    data: 'dispositivo_nombre'
                },
            ],
            order: [
                [1, 'asc']
            ]
        }));
    }


    function buscar_fechas() {

        if (tbl_marcaciones) {
            tbl_marcaciones.destroy(); // Destruir la instancia existente del DataTable
        }
        cargar_tabla();
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Marcaciones</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Marcaciones
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
                        <!-- Fechas -->
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <label for="txt_fecha_inicio" class="form-label fw-bold">
                                    <i class="bx bx-calendar me-1"></i> Fecha Inicio
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control form-control-sm"
                                    id="txt_fecha_inicio" name="txt_fecha_inicio">
                            </div>

                            <div class="col-md-6">
                                <label for="txt_fecha_fin" class="form-label fw-bold">
                                    <i class="bx bx-calendar me-1"></i> Fecha Fin
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control form-control-sm"
                                    id="txt_fecha_fin" name="txt_fecha_fin">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="modal-footer pt-2" id="seccion_boton_consulta">

                                    <button class="btn btn-primary btn-sm px-3" onclick="buscar_fechas();" type="button"><i class='bx bx-search'></i> Buscar</button>

                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="row">

                            <div class="col-12 col-md-6">

                            </div>

                            <div class="col-12 col-md-6 text-md-end text-start">
                                <div id="contenedor_botones"></div>
                            </div>

                        </div>

                        <hr>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_marcaciones" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Fecha</th>
                                                <th>Biométrico</th>
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