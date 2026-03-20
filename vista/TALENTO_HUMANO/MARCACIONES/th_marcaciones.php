<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$per_id = 0;

if ($_SESSION['INICIO']['ID_PERSONA'] > 0) {
    /*
    if ($_SESSION['INICIO']['ID_PERSONA'] != $_per_id && $_per_id != '') {
        echo "<script>location.href = 'inicio.php?acc=pagina_error';</script>";
        exit;
    }*/
    $per_id = $_SESSION['INICIO']['ID_PERSONA'];
}

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    // ── Declarar globalmente ───────────────────────
    var tbl_marcaciones = null; // ← fuera de cualquier función

    $(document).ready(function() {
        cargar_tabla();
    });

    function cargar_tabla() {
        // var tabla = $('#txt_tabla').val(); ← eliminar, no existe en el HTML

        var txt_fecha_inicio = $('#txt_fecha_inicio').val();
        var txt_fecha_fin = $('#txt_fecha_fin').val();

        tbl_marcaciones = $('#tbl_marcaciones').DataTable($.extend({}, configuracion_datatable('Marcaciones', 'Marcaciones'), {
            responsive: true, // ← estaba "reponsive"
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_control_accesoC.php?listar=true',
                type: 'POST',
                data: function(d) {
                    d.listar_todo = true;
                    d.fecha_inicio = txt_fecha_inicio;
                    d.fecha_fin = txt_fecha_fin;
                    d.per_id = <?= $per_id ?>;
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'nombre'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return fecha_formateada_hora(item.fecha);
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
            tbl_marcaciones.destroy();
            tbl_marcaciones = null; // ← limpiar referencia tras destruir
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